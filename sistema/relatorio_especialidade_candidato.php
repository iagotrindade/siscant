<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "avaliador" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 23523523543! Página não encontrada!");
    exit();
}

$avaliador = false;
if ($_SESSION['perfil'] == "avaliador")
    $avaliador = true;

$id_especialidade_selecionada = 0;
$select_nota_prova_pratica_teorica = 1;

if (isset($_GET['select_nota_prova_pratica_teorica']) && $_GET['select_nota_prova_pratica_teorica'] == '0')
    $select_nota_prova_pratica_teorica = 0;

if (isset($_GET['id_especialidade']))
    $id_especialidade_selecionada = $_GET['id_especialidade'];

$avaliador_pode_avaliar_id_especialidade = false;
if ($avaliador) {
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

    foreach ($lista_especialidade_avaliador as $linha_avaliador) {
        if ($linha_avaliador['id_especialidade'] == $id_especialidade_selecionada)
            $avaliador_pode_avaliar_id_especialidade = true;
    }
    if (!$avaliador_pode_avaliar_id_especialidade && $id_especialidade_selecionada != 0) {
        erro("Erro 48923543! Você não tem permissão para avaliar essa especialidade!");
        exit();
    }
}

$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);

$voluntario_obrigatorio = '';
if (isset($_GET['voluntario_obrigatorio']))
    $voluntario_obrigatorio = $_GET['voluntario_obrigatorio'];

$especialidade_medico = false;
?>
<style>
    dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #006400 0%, #008000 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
        border: none;
    }

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

    .badge-table {
        background-color: var(--primary-color);
        font-size: 1.4rem;
        padding: 0.35em 0.65em;
    }

    .progress-container {
        position: relative;
        margin-top: 20px;
    }

    .progress-text {
        position: absolute;
        top: -20px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
    }

    .progress {
        height: 10px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
        background-color: var(--primary-color);
    }

    .especialidades-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.04);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .especialidades-list {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .fw-semibold {
        font-weight: 600;
    }

    .multiple-especialidade {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatorio Candidatos Especialidade <i class="fa fa-file-text"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Relatorio Candidatos Especialidade</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Filtros -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtros da Especialidade
                    </span>
                </div>
                <div class="card-body">
                    <form name="fomulario" action="relatorio_especialidade_candidato.php" method="get" class="filter-form">
                        <div class="row g-3">
                            <!-- Seleção de Especialidade -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade desejada
                                </label>
                                <select onchange="fomulario.submit()" name="id_especialidade" class="form-control form-select-lg">
                                    <option value="">Selecione a especialidade</option>
                                    <?php
                                    if ($avaliador) {
                                        foreach ($lista_especialidade_avaliador as $value) {
                                            $selected = $id_especialidade_selecionada == $value['id_especialidade'] ? 'selected' : '';
                                            echo '<option ' . $selected . ' value="' . $value['id_especialidade'] . '">' .
                                                mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . htmlspecialchars($value['nome']) . '</option>';
                                        }
                                    } else {
                                        $resultado = $conexao->get_especialidade();
                                        foreach ($resultado as $value) {
                                            $selected = $id_especialidade_selecionada == $value['id'] ? 'selected' : '';
                                            echo '<option ' . $selected . ' value="' . $value['id'] . '">' .
                                                mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . htmlspecialchars($value['nome']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Filtro de Notas (Admin) -->
                            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-line-chart me-1"></i>
                                        Considerar notas da prova
                                    </label>
                                    <select onchange="fomulario.submit()" name="select_nota_prova_pratica_teorica" class="form-control form-select-lg">
                                        <option value="1">Considerar notas da prova Teórica/Prática</option>
                                        <option <?= $select_nota_prova_pratica_teorica == '0' ? 'selected' : '' ?> value="0">
                                            NÃO considerar as notas da prova Teórica/Prática
                                        </option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <!-- Filtro Voluntário/Obrigatório -->
                            <?php
                            if ($id_especialidade_selecionada != 0) {
                                $get_especialidade_selecionada = $conexao->get_especialidade_id($id_especialidade_selecionada);
                                if ($get_especialidade_selecionada[0]['ott_stt'] == 'medico' || $get_especialidade_selecionada[0]['ott_stt'] == 'dentista') {
                                    $especialidade_medico = true;
                                }
                            }

                            if ($especialidade_medico == false)
                                $voluntario_obrigatorio = "";
                            ?>

                            <?php if ($especialidade_medico): ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-user-tag me-1"></i>
                                        Tipo de candidato
                                    </label>
                                    <select onchange="fomulario.submit()" name="voluntario_obrigatorio" class="form-select form-select-lg">
                                        <option value="">Selecione a opção</option>
                                        <option <?= $voluntario_obrigatorio == 'voluntario' ? 'selected' : '' ?> value="voluntario">
                                            Somente voluntários
                                        </option>
                                        <option <?= $voluntario_obrigatorio == 'obrigatorio' ? 'selected' : '' ?> value="obrigatorio">
                                            Somente obrigatórios
                                        </option>
                                        <option <?= $voluntario_obrigatorio == 'voluntario_obrigatorio' ? 'selected' : '' ?> value="voluntario_obrigatorio">
                                            Obrigatórios e voluntários
                                        </option>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Includes dos Critérios de Desempate -->
            <?php if ($id_especialidade_selecionada != 0): ?>
                <?php if (count($get_especialidade_selecionada) > 0 && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta')): ?>
                    <!-- Primeiro Include -->
                    <div class="mb-4">
                        <?php
                        if ($get_especialidade_selecionada[0]['musica'] == 1)
                            include_once 'codigos/criterios_desempate_musica.php';
                        else if ($get_especialidade_selecionada[0]['ott_stt'] == 'cet') {
                            include_once 'codigos/criterios_desempate_cet.php';
                        } else
                            include_once 'codigos/criterios_desempate_ott_stt.php';
                        ?>
                    </div>

                    <!-- Segundo Include -->
                    <?php if ($get_especialidade_selecionada[0]['musica'] != 1 && $get_especialidade_selecionada[0]['ott_stt'] != 'cet'): ?>
                        <div class="mb-4">
                            <?php include_once 'codigos/criterios_desempate_ott_stt3.php'; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tabela de Candidatos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header d-flex justify-content-between align-items-center mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-users me-2"></i>
                        Candidatos da Especialidade Selecionada
                    </span>
                    <?php if ($perfil == "admin" || $perfil == "consulta"): ?>
                        <a href="excel_candidatos_especialidade.php?id_especialidade=<?= $id_especialidade_selecionada ?>"
                            class="btn btn-success btn-sm"
                            data-bs-toggle="tooltip"
                            title="Exportar para Excel">
                            <i class="fa fa-file-excel-o me-1"></i> Exportar
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-user"></i> Candidato</th>
                                    <th width="140px"><i class="fa fa-id-card-o"></i> CPF</th>
                                    <th width="100px"><i class="fa fa-usd"></i> Pagamento</th>
                                    <th width="120px"><i class="fa fa-stethoscope"></i> IS</th>
                                    <th width="100px"><i class="fa fa-stethoscope"></i> Grupo IS</th>
                                    <th width="140px"><i class="fa fa-calendar"></i> Data Ex. Médico</th>
                                    <th width="120px"><i class="fa fa-pencil"></i> Nota Prova</th>
                                    <th width="120px"><i class="fa fa-percent"></i> Progresso</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_candidatos as $linha): ?>
                                    <?php
                                    // Filtros voluntário/obrigatório
                                    if ($voluntario_obrigatorio == 'voluntario' && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1)) continue;
                                    if ($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;

                                    // Pagamento
                                    $get_pagamento_candidato = $conexao->get_arquivo_pagamento($linha['id']);
                                    $pagou = count($get_pagamento_candidato) > 0 ? "_Sim" : "_Não";

                                    // Validação de currículos
                                    $curriculos_candidato = $conexao->get_avaliado_especialidade($linha['id'], $id_especialidade_selecionada);
                                    $quantidade_validado = 0;
                                    $quantidade_total = count($curriculos_candidato);

                                    foreach ($curriculos_candidato as &$curriculo_candidato) {
                                        if ($curriculo_candidato['valido'] != null)
                                            $quantidade_validado++;
                                    }

                                    $porcentagem_validacao = 0;
                                    if ($quantidade_validado != 0 && $quantidade_total != 0)
                                        $porcentagem_validacao = ($quantidade_validado / $quantidade_total) * 100;

                                    // Status do progresso
                                    $progress_class = 'danger';
                                    if ($porcentagem_validacao == 100) $progress_class = 'success';
                                    elseif ($porcentagem_validacao >= 60) $progress_class = 'warning';
                                    elseif ($porcentagem_validacao >= 30) $progress_class = 'info';

                                    if ($quantidade_total == 0) {
                                        $porcentagem_validacao = null;
                                        $progress_class = 'secondary';
                                    }

                                    // Pontuação
                                    $pontuacao_avaliada = null;
                                    $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade_selecionada);
                                    if (count($get_pontuacao_avaliada) > 0)
                                        $pontuacao_avaliada = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);

                                    // Foto
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    // Data exame saúde
                                    $data_ex_saude = $linha['data_exame_saude'];
                                    if ($data_ex_saude != null) $data_ex_saude = trata_data($data_ex_saude);

                                    // Status apto
                                    $apto_class = 'secondary';
                                    $apto_text = 'Não feito';
                                    if ($linha['apto_saude'] == '1') {
                                        $apto_class = 'success';
                                        $apto_text = 'APTO';
                                    } elseif ($linha['apto_saude'] == '0') {
                                        $apto_class = 'danger';
                                        $apto_text = 'INAPTO';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="fotos/<?= $foto ?>"
                                                    class="img-circle mr-10"
                                                    width="40"
                                                    height="40"
                                                    alt="Foto">
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($linha['nome_completo']) ?></div>
                                                    <small class="text-muted">Docs: <?= $quantidade_total ?> | Validados: <?= $quantidade_validado ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?= $linha['cpf'] ?>
                                        </td>
                                        <td>
                                            <span class="badge-table bg-info badge bg-<?= $pagou == '_Sim' ? 'success' : 'danger' ?>">
                                                <?= $pagou == '_Sim' ? 'Sim' : 'Não' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-table bg-info badge bg-<?= $apto_class ?>"><?= $apto_text ?></span>
                                        </td>
                                        <td>
                                            <span class="badge-table bg-info badge"><?= mb_strtoupper($linha['grupo_saude']) ?></span>
                                        </td>
                                        <td>
                                            <span><?= $data_ex_saude ?: '-' ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary"><?= $linha['nota_prova_teorico_pratico'] ?: '0' ?></span>
                                        </td>
                                        <td>
                                            <?php if ($porcentagem_validacao !== null): ?>
                                                <div class="progress-container-sm">
                                                    <div class="progress-text-sm"><?= round($porcentagem_validacao) ?>%</div>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-<?= $progress_class ?>"
                                                            role="progressbar"
                                                            style="width: <?= $porcentagem_validacao ?>%"
                                                            aria-valuenow="<?= $porcentagem_validacao ?>"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Sem docs</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
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

            <!-- Candidatos Desclassificados -->
            <div class="card dashboard-card">
                <div class="card-header dashboard-header bg-danger text-white mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-user-times me-2"></i>
                        Candidatos desclassificados da especialidade com justificativa
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidato</th>
                                    <th width="140px">CPF</th>
                                    <th>Justificativa</th>
                                    <th width="80px" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_candidatos_desclassificados = $conexao->get_candidatos_desclassificados_especialidade($id_especialidade_selecionada);

                                foreach ($lista_candidatos_desclassificados as $linha):
                                    if ($voluntario_obrigatorio == 'voluntario' && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1)) continue;
                                    if ($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;

                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="fotos/<?= $foto ?>"
                                                    class="img-circle mr-10"
                                                    width="40"
                                                    height="40"
                                                    alt="Foto">
                                                <div class="fw-semibold"><?= htmlspecialchars($linha['nome_completo']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?= $linha['cpf'] ?>
                                        </td>
                                        <td>
                                            <span class="text-danger"><?= htmlspecialchars($linha['justificativa_ce']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
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
<!-- Scripts DataTables -->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Seleciona todas as tabelas com a classe .tabela_dinamica
        $('.tabela_dinamica').each(function() {
            $(this).DataTable({
                ordering: true
            });
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

</body>

</html>
<?php $conexao = null; ?>