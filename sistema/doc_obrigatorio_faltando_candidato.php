<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235235! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 234235! Página não encontrada!");
    exit();
}

$lista_docs_obrigatorios = $conexao->get_documentos_obrigatorios_cadastrados();

$doc_selecionado = null;
if (isset($_GET['curriculo_selecionado']))
    $doc_selecionado = (int)$_GET['curriculo_selecionado'];

$candidatos_selecionados = null;
if (isset($_GET['candidatos']))
    $candidatos_selecionados = $_GET['candidatos'];
?>

<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 12px 15px;
        font-size: 1.3rem;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 1.4rem;
    }

    .table td a:hover {
        background-color: #006400;
        color: white;
    }

    .table td i {
        font-size: 2rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.03);
    }

    .especialidades-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Documento Obrigatório Faltando </h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Documento Obrigatório Faltando</li>
            </ul>
        </div>
    </div>
    <!-- Filtros -->
    <div class="card filter-card mb-4">
        <div class="card-header filter-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-filter me-2"></i>
                Filtros de Pesquisa
            </span>
        </div>
        <div class="card-body">
            <form name="fomulario" action="doc_obrigatorio_faltando_candidato.php" method="get" class="filter-form">
                <div class="row g-3">
                    <!-- Filtro de Candidatos -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Status dos Candidatos
                            <span class="text-danger">*</span>
                        </label>
                        <select onchange="fomulario.submit()" name="candidatos" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="concorrendo" <?= $candidatos_selecionados == 'concorrendo' ? 'selected' : '' ?>>
                                Candidatos Concorrendo
                            </option>
                            <option value="desclassificados" <?= $candidatos_selecionados == 'desclassificados' ? 'selected' : '' ?>>
                                Candidatos Desclassificados
                            </option>
                            <option value="todos" <?= $candidatos_selecionados == 'todos' ? 'selected' : '' ?>>
                                Todos os Candidatos
                            </option>
                        </select>
                    </div>

                    <!-- Filtro de Documentos -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Documento Obrigatório Não Anexado
                        </label>
                        <select name="curriculo_selecionado" class="form-control" onchange="fomulario.submit()">
                            <option value="">Selecione o documento</option>
                            <?php foreach ($lista_docs_obrigatorios as $linha): ?>
                                <option value="<?= $linha['id'] ?>" <?= $doc_selecionado == $linha['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($linha['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Botão de Pesquisa -->
                    <div class="col-md-12 mt-20">
                        <button type="submit" class="btn btn-primary btn-md w-100">
                            <i class="fa fa-search me-2"></i>
                            PESQUISAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Resultados -->
    <div class="card dashboard-card">
        <div class="card-header dashboard-header mb-20">
            <div class="d-flex justify-content-between align-items-center">
                <span class="card-title mb-0">
                    <i class="fa fa-file-text me-2"></i>
                    Documentos Obrigatórios Faltantes
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="tabela_dinamica">
                    <thead class="table-light">
                        <tr>
                            <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                            <th><i class="fa fa-user"></i> Candidato</th>
                            <th width="120px" class="text-center"><i class="fa fa-info-circle"></i> Status</th>
                            <th width="120px" class="text-center"><i class="fa fa-file-text"></i> Docs Faltando</th>
                            <th><i class="fa fa-shield"></i> Especialidades</th>
                            <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lista_candidatos = array();
                        $contador = 0;

                        if ($candidatos_selecionados != null) {
                            $lista_candidatos = $conexao->get_candidatos_desc_class();
                        }

                        foreach ($lista_candidatos as $candidato):
                            // Pular candidatos médicos obrigatórios
                            if ($candidato['medico_obrigatorio'] == 1) continue;

                            // Aplicar filtro de status
                            if ($candidatos_selecionados == 'concorrendo' && $candidato['concorrendo'] == 0) continue;
                            if ($candidatos_selecionados == 'desclassificados' && $candidato['concorrendo'] == 1) continue;

                            // Verificar documentos faltando
                            $lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($candidato['id']);
                            $get_candidato = $conexao->get_usuario_id($candidato['id']);
                            $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando_candidato);

                            $possui_documento = true;
                            foreach ($lista_docs_obrigatorios_sobrando as $documento) {
                                if ($doc_selecionado == $documento['id']) {
                                    $possui_documento = false;
                                    break;
                                }
                            }

                            $quantidade_docs_faltando = count($lista_docs_obrigatorios_sobrando);

                            // Filtrar por documento específico
                            if ($doc_selecionado != null && $possui_documento) continue;
                            if ($doc_selecionado == null && $quantidade_docs_faltando == 0) continue;

                            // Especialidades do candidato
                            $nome_especialidades = '';
                            $especialidades = $conexao->get_especialidade_candidato($candidato['id']);
                            $i = 1;
                            foreach ($especialidades as $especialidade) {
                                $nome_especialidades .= '<span class="badge bg-primary mr-10">' .
                                    $i . 'ª ' . mb_strtoupper($especialidade['ott_stt'], "UTF-8") . ' ' .
                                    htmlspecialchars($especialidade['especialidade']) . '</span>';
                                $i++;
                            }

                            $status_class = $candidato['concorrendo'] == 1 ? 'success' : 'danger';
                            $status_text = $candidato['concorrendo'] == 1 ? 'Concorrendo' : 'Desclassificado';

                            $contador++;
                        ?>
                            <tr>
                                <!-- CPF -->
                                <td>
                                    <a href="usuario_visualiza.php?id_usuario=<?= $candidato['id'] ?>"
                                        class="text-decoration-none">
                                        <?= $candidato['cpf'] ?>
                                    </a>
                                </td>

                                <!-- Nome -->
                                <td>
                                    <div class="candidate-info">
                                        <div class="fw-semibold candidate-name"><?= htmlspecialchars($candidato['nome_completo']) ?></div>
                                        <?php if ($quantidade_docs_faltando > 0): ?>
                                            <small class="text-warning">
                                                <?= $quantidade_docs_faltando ?> documento(s) obrigatório(s) pendente(s)
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="text-center">
                                    <span class="badge" <?= $status_class == 'danger' ? 'style="background-color: #dc3545;"' : 'style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light));"' ?>>
                                        <?= $status_text ?>
                                    </span>
                                </td>

                                <!-- Docs Faltando -->
                                <td class="text-center">
                                    <span class="docs-count <?= $quantidade_docs_faltando > 0 ? 'text-danger fw-bold' : 'text-success' ?>">
                                        <?= $quantidade_docs_faltando ?>
                                    </span>
                                </td>

                                <!-- Especialidades -->
                                <td>
                                        <?= $nome_especialidades ?>
                                 
                                </td>

                                <!-- Ações -->
                                <td class="text-center">
                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                        class="btn btn-sm btn-outline-primary view-btn"
                                        data-bs-toggle="tooltip"
                                        title="Visualizar candidato">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable({
        "order": [
            [4, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>