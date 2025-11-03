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

$lista_curriculos = $conexao->get_curriculo_cadastrados();

$curriculo_1 = null;
if (isset($_GET['curriculo_1']))
    $curriculo_1 = (int)$_GET['curriculo_1'];

$curriculo_2 = null;
if (isset($_GET['curriculo_2']))
    $curriculo_2 = (int)$_GET['curriculo_2'];

$curriculo_3 = null;
if (isset($_GET['curriculo_3']))
    $curriculo_3 = (int)$_GET['curriculo_3'];

$candidatos_selecionados = null;
if (isset($_GET['candidatos']))
    $candidatos_selecionados = $_GET['candidatos'];
?>

<style>
    .table th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Melhoria na responsividade */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Currículos faltantes <i class="fa fa-file-text-o"></i></h1>
            
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Currículos faltantes</li>
            </ul>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-file-text me-2"></i> Relatório de Currículos Faltantes
            </span>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Informação:</strong> O relatório irá trazer os candidatos que não possuem os currículos selecionados
            </div>

            <form name="formulario_relatorio" action="curriculos_faltando_candidatos.php" method="get" class="needs-validation" novalidate>
                <div class="row g-3">

                    <!-- Filtro de Candidatos -->
                    <div class="col-md-6 col-lg-3">
                        <label for="candidatos" class="form-label fw-semibold">
                            <i class="fa fa-users me-1"></i> Tipo de Candidatos
                        </label>
                        <select name="candidatos" id="candidatos" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <?php
                            $opcoes_candidatos = [
                                'concorrendo' => 'Candidatos Concorrendo',
                                'desclassificados' => 'Candidatos Desclassificados',
                                'todos' => 'Todos os Candidatos'
                            ];

                            foreach ($opcoes_candidatos as $valor => $texto) {
                                $selected = ($candidatos_selecionados == $valor) ? 'selected' : '';
                                echo "<option value=\"$valor\" $selected>$texto</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Filtros de Currículos -->
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <div class="col-md-6 col-lg-3 mb-20">
                            <label for="curriculo_<?php echo $i; ?>" class="form-label fw-semibold">
                                <i class="fa fa-file me-1"></i> Currículo <?php echo $i; ?>
                            </label>
                            <select name="curriculo_<?php echo $i; ?>" id="curriculo_<?php echo $i; ?>" class="form-control">
                                <option value="">Selecione o currículo</option>
                                <?php
                                foreach ($lista_curriculos as $linha) {
                                    $var_name = "curriculo_$i";
                                    $selected = ($$var_name == $linha['id']) ? 'selected' : '';
                                    echo "<option value=\"{$linha['id']}\" $selected>{$linha['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    <?php endfor; ?>

                    <!-- Botão de Ação -->
                    <div class="col-lg-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-search me-2"></i>Gerar Relatório
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-list me-2"></i> Resultados do Relatório
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="tabela_dinamica">
                    <thead class="table-light">
                        <tr>
                            <th width="140"><i class="fa fa-id-card"></i> CPF</th>
                            <th><i class="fa fa-user"></i> Candidato</th>
                            <th width="120" class="text-center"><i class="fa fa-info-circle"></i> Status</th>
                            <th width="100" class="text-center"><i class="fa fa-list"></i> Etapa</th>
                            <th width="120" class="text-center"><i class="fa fa-file-text"></i> Currículos</th>
                            <th width="100" class="text-center"><i class="fa fa-tag"></i> Categoria</th>
                            <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                            <th width="80" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lista_candidatos = null;
                        if ($candidatos_selecionados != null) {
                            $lista_candidatos = $conexao->get_candidatos_desc_class();
                        }

                        $total_resultados = 0;

                        if ($lista_candidatos) {
                            foreach ($lista_candidatos as $candidato):
                                // Pula médicos obrigatórios
                                if ($candidato['medico_obrigatorio'] == 1) continue;

                                // Filtra por tipo de candidato
                                if ($candidatos_selecionados == 'concorrendo' && $candidato['concorrendo'] == 0) continue;
                                if ($candidatos_selecionados == 'desclassificados' && $candidato['concorrendo'] == 1) continue;

                                $inscricoes = $conexao->get_especialidade_candidato($candidato['id']);

                                foreach ($inscricoes as $especialidade):
                                    $lista_curriculos = $conexao->get_curriculos_inseridos_candidato($candidato['id'], $especialidade['id_especialidade']);

                                    $exibe_linha = true;
                                    $quantidade_curriculos = count($lista_curriculos);

                                    // Verifica se falta algum currículo selecionado
                                    foreach ($lista_curriculos as $curriculo) {
                                        if ($curriculo_1 != null && $curriculo['id_curriculo'] == $curriculo_1) $exibe_linha = false;
                                        if ($curriculo_2 != null && $curriculo['id_curriculo'] == $curriculo_2) $exibe_linha = false;
                                        if ($curriculo_3 != null && $curriculo['id_curriculo'] == $curriculo_3) $exibe_linha = false;
                                    }

                                    if (!$exibe_linha) continue;

                                    $total_resultados++;

                                    // Determina status
                                    $status = $candidato['concorrendo'] == 1 ? 'Concorrendo' : 'Desclassificado';
                                    $status_class = $candidato['concorrendo'] == 1 ? 'primary' : 'danger';

                                    // Status da especialidade
                                    $especialidade_status = $especialidade['concorrendo'] == 1 ? '' : '<small class="text-danger d-block mt-1">Desclassificado na especialidade</small>';

                                    // Cor para quantidade de currículos
                                    $qtd_class = 'text-success';
                                    if ($quantidade_curriculos == 0) $qtd_class = 'text-danger';
                                    elseif ($quantidade_curriculos <= 2) $qtd_class = 'text-warning';
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
                                                <?php if ($quantidade_curriculos == 0): ?>
                                                    <small class="text-warning">Nenhum currículo adicionado</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            <span class="badge bg-<?= $status_class ?>">
                                                <?= $status ?>
                                            </span>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            <span class="etapa-badge etapa-<?= $candidato['etapa'] ?>">
                                                <?= $candidato['etapa'] ?>
                                            </span>
                                        </td>

                                        <!-- Quantidade de Currículos -->
                                        <td class="text-center">
                                            <span class="curriculo-count <?= $qtd_class ?>">
                                                <?= $quantidade_curriculos ?>
                                            </span>
                                        </td>

                                        <!-- Categoria -->
                                        <td class="text-center">
                                            <span class="categoria-badge">
                                                <?= mb_strtoupper($especialidade['ott_stt'], "UTF-8") ?>
                                            </span>
                                        </td>

                                        <!-- Especialidade -->
                                        <td>
                                            <div class="especialidade-info">
                                                <span class="fw-medium"><?= htmlspecialchars($especialidade['especialidade']) ?></span>
                                                <?= $especialidade_status ?>
                                            </div>
                                        </td>

                                        <!-- Ações -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $candidato['id'] ?>"
                                                class="btn btn-sm action-btn"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar candidato">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            endforeach;
                        }

                        // Mensagem quando não há resultados
                        if ($total_resultados === 0):
                            ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Nenhum candidato encontrado</h6>
                                        <p class="text-muted mb-0">Utilize os filtros acima para gerar o relatório</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
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
            [3, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>