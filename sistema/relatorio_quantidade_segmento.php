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
            <h1>Relatório de candidatos por Segmento <i class="fa fa-venus-mars"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos por Segmento</li>
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
                    <form name="fomulario" action="relatorio_quantidade_segmento.php" method="get">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade desejada
                                </label>
                                <select onchange="fomulario.submit()" name="id_especialidade" class="form-control form-control-lg">
                                    <option value="">Todas</option>
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
                            <i class="fa fa-venus-mars me-2"></i>
                            Candidatos por Segmento
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Candidato</th>
                                    <th class="text-center"><i class="fa fa-graduation-cap"></i> Especialidades</th>
                                    <th class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th class="text-center"><i class="fa fa-venus-mars"></i> Segmento</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_docs_adicionados = 0;
                                $total_docs_avaliados = 0;

                                foreach ($lista_candidatos as $linha):
                                    $especialidades_cadastradas = "";
                                    $especialidades_do_candidato = $conexao->get_especialidade_candidato($linha['id']);

                                    foreach ($especialidades_do_candidato as $esp) {
                                        if ($esp['ott_stt'] == 'ott') $tem_ott = true;
                                        if ($esp['ott_stt'] == 'stt') $tem_stt = true;
                                        $especialidades_cadastradas .= '<span class="badge text-dark mr-10 mb-1" style="background-color: var(--primary-color);">' . strtoupper($esp['ott_stt']) . ' - ' . htmlspecialchars($esp['especialidade'] . ' (et_ ' . $esp['etapa'] . ')') . '</span>';
                                    }
                                ?>
                                    <tr>

                                        <!-- CPF -->
                                        <td>
                                            <?= $linha['cpf'] ?>
                                        </td>

                                        <!-- Nome -->
                                        <td>
                                            <div class="candidate-info">
                                                <div class="fw-semibold candidate-name">
                                                    <?= htmlspecialchars($linha['nome_completo']) ?>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Especialidade -->
                                        <td class="text-center">
                                            <div class="especialidades-list">
                                                <?= $especialidades_cadastradas ?>
                                            </div>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            et_<?= $linha['etapa'] ?>
                                        </td>

                                        <!-- Segmento -->
                                        <td class="text-center">
                                            <?= mb_strtoupper($linha['sexo']) ?>
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
        "pageLength": 50,
        "order": [
            [0, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>