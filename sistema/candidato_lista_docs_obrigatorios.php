<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'avaliador' || $perfil == 'ouvidor') {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$id_especialidade_selecionada = null;
if (isset($_GET['id_especialidade']))
    $id_especialidade_selecionada = $_GET['id_especialidade'];

if ($id_especialidade_selecionada != null)
    $lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);
else
    $lista_candidatos = $conexao->get_candidatos_concorrendo();
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

    .summary-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        height: 100%;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 2rem;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
    }

    .summary-label {
        font-size: 1.2rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatório de documentos obrigatórios <i class="fa fa-file-text-o"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Filtro de Especialidade -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtro de Especialidade
                    </span>
                </div>
                <div class="card-body">
                    <form name="fomulario" action="candidato_lista_docs_obrigatorios.php" method="get">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade desejada
                                </label>
                                <select onchange="fomulario.submit()" name="id_especialidade" class="form-control form-control-lg">
                                    <option value="">Selecione a especialidade</option>
                                    <?php
                                    if ($avaliador) {
                                        foreach ($lista_especialidade_avaliador as $value) {
                                            $selected = $id_especialidade_selecionada == $value['id_especialidade'] ? 'selected' : '';
                                            $label = mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . htmlspecialchars($value['nome']);
                                            echo '<option ' . $selected . ' value="' . $value['id_especialidade'] . '">' . $label . '</option>';
                                        }
                                    } else {
                                        $resultado = $conexao->get_especialidade();
                                        foreach ($resultado as $value) {
                                            $selected = $id_especialidade_selecionada == $value['id'] ? 'selected' : '';
                                            $label = mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . htmlspecialchars($value['nome']);
                                            echo '<option ' . $selected . ' value="' . $value['id'] . '">' . $label . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Avaliação -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-file-text me-2"></i>
                            Avaliação dos Documentos Obrigatórios
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center"><i class="fa fa-hashtag"></i> Código</th>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Candidato</th>
                                    <th class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th class="text-center"><i class="fa fa-dollar"></i> Pagamento</th>
                                    <th class="text-center"><i class="fa fa-file-text"></i> Docs Faltando</th>
                                    <th class="text-center"><i class="fa fa-file-text"></i> Docs Adicionados</th>
                                    <th class="text-center"><i class="fa fa-file-text"></i> Docs Válidos</th>
                                    <th class="text-center"><i class="fa fa-percent"></i> Progresso</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_docs_adicionados = 0;
                                $total_docs_avaliados = 0;

                                foreach ($lista_candidatos as $linha):
                                    // Arquivo de pagamento
                                    $arquivo_pagamento = $conexao->get_arquivo_pagamento($linha['id']);
                                    $add_arqu_pag = count($arquivo_pagamento) > 0 ? '_Sim' : '_Não';
                                    $pagamento_class = $add_arqu_pag == '_Sim' ? 'success' : 'danger';

                                    // Documentos faltando
                                    $lista_docs_obrigatorios_faltando = $conexao->get_documentos_obrigatorios_sobrando_candidato($linha['id']);
                                    $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($linha, $lista_docs_obrigatorios_faltando);
                                    $quantidade_docs_faltando = count($lista_docs_obrigatorios_sobrando);

                                    // Documentos adicionados e avaliados
                                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($linha['id']);
                                    $quantidade_docs_adicionados = count($lista_docs_obrigatorios);
                                    $quantidade_docs_avaliados = 0;
                                    $quantidade_docs_validos = 0;

                                    foreach ($lista_docs_obrigatorios as &$doc) {
                                        if ($doc['valido'] != null) $quantidade_docs_avaliados++;
                                        if ($doc['valido'] == '1') $quantidade_docs_validos++;
                                    }

                                    $total_docs_adicionados += (int)$quantidade_docs_adicionados;
                                    $total_docs_avaliados += $quantidade_docs_avaliados;

                                    // Cálculo de porcentagem
                                    $porcentagem = 0;
                                    if ($quantidade_docs_avaliados != 0 && $quantidade_docs_adicionados != 0) {
                                        $porcentagem = ($quantidade_docs_avaliados / $quantidade_docs_adicionados) * 100;
                                    }

                                    // Status do progresso
                                    $progress_class = 'secondary';
                                    if ($porcentagem >= 100) $progress_class = 'primary';
                                    elseif ($porcentagem >= 60) $progress_class = 'warning';
                                    elseif ($porcentagem >= 30) $progress_class = 'info';
                                    elseif ($porcentagem > 0) $progress_class = 'danger';

                                    // Status dos documentos válidos
                                    $docs_validos_class = 'success';
                                    $docs_validos_icon = 'fa-check';
                                    if ($quantidade_docs_validos != $quantidade_docs_adicionados && $porcentagem == 100) {
                                        $docs_validos_class = 'danger';
                                        $docs_validos_icon = 'fa-exclamation-triangle';
                                    } elseif ($quantidade_docs_validos == 0 && $quantidade_docs_adicionados > 0) {
                                        $docs_validos_class = 'danger';
                                        $docs_validos_icon = 'fa-times';
                                    }

                                    // Código final
                                    $codigo_final = substr((string)$linha['id'], -1);
                                ?>
                                    <tr>
                                        <!-- Código -->
                                        <td class="text-center">
                                            <?= $linha['id'] ?>-<?= $codigo_final ?>
                                        </td>

                                        <!-- CPF -->
                                        <td>
                                            <?= $linha['cpf'] ?>
                                        </td>

                                        <!-- Nome -->
                                        <td>
                                            <div class="candidate-info">
                                                <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome_completo']) ?></div>
                                                <?php if ($quantidade_docs_faltando > 0): ?>
                                                    <small class="text-warning">
                                                        <?= $quantidade_docs_faltando ?> documento(s) pendente(s)
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            et_<?= $linha['etapa'] ?>
                                        </td>

                                        <!-- Pagamento -->
                                        <td class="text-center">
                                            <?= $add_arqu_pag == '_Sim' ? 'Sim' : 'Não' ?>
                                        </td>

                                        <!-- Docs Faltando -->
                                        <td class="text-center">
                                            <?= $quantidade_docs_faltando ?>
                                        </td>

                                        <!-- Docs Adicionados -->
                                        <td class="text-center">
                                            <?= $quantidade_docs_adicionados ?>
                                        </td>

                                        <!-- Docs Válidos -->
                                        <td class="text-center">
                                            <?= $quantidade_docs_validos ?>/<?= $quantidade_docs_adicionados ?>
                                        </td>

                                        <!-- Progresso -->
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-text"><?= round($porcentagem, 1) ?>%</div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-<?= $progress_class ?>"
                                                        role="progressbar"
                                                        style="width: <?= $porcentagem ?>%"
                                                        aria-valuenow="<?= $porcentagem ?>"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
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

            <!-- Resumo Geral -->
            <div class="card summary-card">
                <div class="card-header summary-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-bar-chart"></i>
                        Resumo Geral da Avaliação
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="summary-item">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label">Total de Docs Adicionados</span>
                                    <span class="summary-value"><?= $total_docs_adicionados ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-item">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label">Total de Docs Avaliados</span>
                                    <span class="summary-value"><?= $total_docs_avaliados ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-item">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label">Docs Pendentes</span>
                                    <span class="summary-value"><?= $total_docs_adicionados - $total_docs_avaliados ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="summary-item">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div class="summary-content">
                                    <span class="summary-label">Progresso Total</span>
                                    <span class="summary-value">
                                        <?php
                                        if ($total_docs_avaliados != 0 && $total_docs_adicionados != 0) {
                                            $porcentagem_total = ($total_docs_avaliados / $total_docs_adicionados) * 100;
                                            echo number_format($porcentagem_total, 2) . '%';
                                        } else {
                                            echo '0%';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
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
        "pageLength": 50,
        "order": [
            [0, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>