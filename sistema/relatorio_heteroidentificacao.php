<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr') {
    erro("Erro 632457437! Página não encontrada!");
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
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Candidatos Autodeclarados Cotistas <i class="fa fa-users"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos Autodeclarados Cotistas</li>
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
                    <form name="fomulario" action="relatorio_heteroidentificacao.php" method="get">
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

            <!-- Tabela de Autodeclarados Cotistas -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-users me-2"></i>
                            Candidatos Autodeclarados Cotistas
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
                                    <th><i class="fa fa-pencil-square-o"></i> Autodeclaração</th>
                                    <th class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th class="text-center"><i class="fa fa-graduation-cap"></i> Especialidades</th>
                                    <th class="text-center"><i class="fa fa-circle"></i> Heteroidentificação</th>
                                    <th class="text-center"><i class="fa fa-circle"></i> Heteroidentificação Revisora</th>
                                    <th class="text-center"><i class="fa fa-circle"></i> Recurso Heteroidentificação</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recursos = $conexao->get_recursos();

                                foreach ($lista_candidatos as $linha):

                                    $etapa = $_SESSION['selecao_codigo'] == 'mfdv' ? 4 : 5;
                                    
                                    if (!$linha['vaga_reservada'] || $linha['etapa'] < $etapa) {
                                        continue;
                                    }
                                    
                                    $pareceres = $conexao->get_pareceres_heteroidentificacao($linha['id']);

                                    $parecerHc = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 1);
                                    $parecerRevisora = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 2);

                                    $pareceresFase1 = array_filter($pareceres, function ($parecer) {
                                        return $parecer['fase'] == 1;
                                    });

                                    $pareceresFase2 = array_filter($pareceres, function ($parecer) {
                                        return $parecer['fase'] == 2;
                                    });

                                    if (count($pareceresFase1) < 5) {
                                        $parecerHc = 'PENDENTE';
                                    }

                                    if (count($pareceresFase2) < 3) {
                                        $parecerRevisora = 'PENDENTE';
                                    }

                                    $aparece = true;

                                    if ($aparece == false) continue;

                                    if ($linha['id_selecao'] != $_SESSION['selecao']) continue;

                                    $hc = '';


                                    // Recurso Etapa 3
                                    $recursoHeteroidentificacao = 'NÃO';
                                    $recurso_class = 'secondary';

                                    foreach ($recursos as $recurso) {
                                        $etapa = $_SESSION['selecao_codigo'] == 'mfdv' ? 4 : 5;

                                        if ($recurso['etapa'] == $etapa && $recurso['id_candidato'] == $linha['id']) {
                                            $recursoHeteroidentificacao = 'SIM';
                                            $recurso_class = 'primary';
                                            break;
                                        } else {
                                            $recursoHeteroidentificacao = 'NÃO';
                                            $recurso_class = 'primary';
                                        }
                                    }

                                    $especialidades_cadastradas = "";
                                    $especialidades_do_candidato = $conexao->get_especialidade_candidato($linha['id']);

                                    foreach ($especialidades_do_candidato as $esp) {
                                        if ($esp['ott_stt'] == 'ott') $tem_ott = true;
                                        if ($esp['ott_stt'] == 'stt') $tem_stt = true;
                                        $especialidades_cadastradas .= '<span class="badge text-dark mr-10 mb-1" style="background-color: var(--primary-color);">' . strtoupper($esp['ott_stt']) . ' - ' . htmlspecialchars($esp['especialidade']) . '</span>';
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
                                                <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome_completo']) ?></div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="candidate-info">
                                                <div class="fw-semibold candidate-name"><?= htmlspecialchars(strtoupper($linha['autodeclaracao'])) ?></div>
                                            </div>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            et_<?= $linha['etapa'] ?>
                                        </td>

                                        <!-- Especialidades -->
                                        <td class="text-center">
                                            <div class="especialidades-list">
                                                <?= $especialidades_cadastradas ?>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $parecerHc ?></span>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $parecerRevisora ?></span>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-<?= $recurso_class ?>"><?= $recursoHeteroidentificacao ?></span>
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
    $('#tabela_dinamica').DataTable();
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>