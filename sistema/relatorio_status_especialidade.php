<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 632457437! Página não encontrada!");
    exit();
}

$id_especialidade_selecionada = 0;
$desistencias = false;
$select_candidatos = null;
$lista_candidatos = array();



if (isset($_GET['id_especialidade']) && $_GET['id_especialidade'] != null) $id_especialidade_selecionada = (int)$_GET['id_especialidade'];
if (isset($_GET['select_desistencia']) && $_GET['select_desistencia'] == 'desistencia') $desistencias = true;

if (isset($_GET['select_candidatos']) && $_GET['select_candidatos'] != null || $desistencias || $id_especialidade_selecionada > 0) {
    $lista_candidatos = $conexao->get_candidatos();
    $select_candidatos = $_GET['select_candidatos'];
}

$todos_candidatos = false;
$candidatos_concorrendo = false;
$candidatos_desclassificados = false;

if ($select_candidatos == "todos") $todos_candidatos = true;
if ($select_candidatos == "concorrendo") $candidatos_concorrendo = true;
if ($select_candidatos == "desclassificados") $candidatos_desclassificados = true;

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
    
    .especialidade-info {
        min-width: 250px;
    }

    .location-badge {
        background: #f8f9fa;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #495057;
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .location-badge.bg-success {
        font-weight: 600;
    }

    .badge {
        color: #000;
        padding: 0.35em 0.65em;
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatório completo das especialidades <i class="fa fa-users"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Relatório completo das especialidades</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Filtros -->
            <div class="card filter-card mb-4">
                <div class="card-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtros de Relatório
                    </span>
                </div>

                <div class="card-body">
                    <form name="fomulario" action="relatorio_status_especialidade.php" method="get" class="filter-form">
                        <div class="row g-3">
                            <!-- Filtro de Candidatos -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-users me-1"></i>
                                    Status dos Candidatos
                                </label>
                                <select onchange="fomulario.submit()" name="select_candidatos" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?= $todos_candidatos ? 'selected' : '' ?> value="todos">
                                        TODOS os candidatos
                                    </option>
                                    <option <?= $candidatos_concorrendo ? 'selected' : '' ?> value="concorrendo">
                                        Candidatos CONCORRENDO
                                    </option>
                                    <option <?= $candidatos_desclassificados ? 'selected' : '' ?> value="desclassificados">
                                        Candidatos DESCLASSIFICADOS
                                    </option>
                                </select>
                            </div>

                            <!-- Filtro de Especialidade -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Especialidade
                                </label>
                                <select onchange="fomulario.submit()" name="id_especialidade" class="form-control">
                                    <option value="">Todas as especialidades</option>
                                    <?php
                                    $resultado = $conexao->get_especialidade();
                                    foreach ($resultado as $value):
                                        $selected = $id_especialidade_selecionada == $value['id'] ? 'selected' : '';
                                        $label = mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . htmlspecialchars($value['nome']);
                                    ?>
                                        <option <?= $selected ?> value="<?= $value['id'] ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filtro de Desistências -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-user-times me-1"></i>
                                    Situação de Desistências
                                </label>
                                <select onchange="fomulario.submit()" name="select_desistencia" class="form-control">
                                    <option value="">Sem restrição</option>
                                    <option <?= $desistencias ? 'selected' : '' ?> value="desistencia">
                                        Com desistências
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Candidatos -->
            <div class="card dashboard-card">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-list-alt me-2"></i>
                            Candidatos e Especialidades
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
                                    <th width="120px" class="text-center"><i class="fa fa-info-circle"></i> Status Processo</th>
                                    <th><i class="fa fa-shield"></i> Especialidade</th>
                                    <th width="150px"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                                    <th width="150px"><i class="fa fa-map-marker"></i> Guarnição de Destino</th>
                                    <th width="100px" class="text-center"><i class="fa fa-question-circle"></i> Distribuído</th>
                                    <th width="140px" class="text-center"><i class="fa fa-calendar"></i> Última Atualização</th>
                                    <th width="100px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data_atual = new DateTime(date("Y-m-d"));
                                $contador = 0;

                                foreach ($lista_candidatos as $linha):
                                    $incorporado = $linha['incorporado'] == '1' ? '_Sim' : '_Não';

                                    // Aplicar filtros
                                    if ($linha['concorrendo'] == '1' && $candidatos_desclassificados) continue;
                                    if ($linha['concorrendo'] == '0' && $candidatos_concorrendo) continue;

                                    $concorrendo_processo_class = $linha['concorrendo'] == '1' ? 'success' : 'danger';
                                    $concorrendo_processo_text = $linha['concorrendo'] == '1' ? 'Concorrendo' : 'Desclassificado';

                                    $inscricoes = $conexao->get_especialidade_candidato($linha['id']);
                                    if (count($inscricoes) == 0) continue;

                                    foreach ($inscricoes as $valor):
                                        // Filtro de desistências
                                        if ($desistencias && $valor['justificativa'] != 'Cod 754809 - O Sr(a) NÃO OPTOU pelas guarnições oferecidas. Caso não sejam oferecidas novas vagas no futuro, o Sr(a) não será incorporado(a) como militar temporário.') continue;

                                        // Filtro de especialidade
                                        if ($id_especialidade_selecionada > 0 && $id_especialidade_selecionada != $valor['id_especialidade']) continue;

                                        $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'], $valor['id_especialidade']);
                                        if (count($resultado_verificacao) > 0):
                                            $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                                            $id_especialidade = $resultado_verificacao[0]['id_especialidade'];
                                            $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                            $ott_stt = $resultado_verificacao[0]['ott_stt'];

                                            // Status da especialidade
                                            $especialidade_class = $valor['concorrendo'] == '1' ? 'success' : 'danger';
                                            $especialidade_icon = $valor['concorrendo'] == '1' ? 'fa-check' : 'fa-times';
                                            $especialidade_status = $valor['concorrendo'] == '1' ? '' : '<small class="text-danger d-block mt-1">' . htmlspecialchars($valor['justificativa']) . '</small>';

                                            // Data de atualização
                                            $ultima_atualizacao = '-';
                                            if ($resultado_verificacao[0]['_data_ultima_atualizacao'] != null) {
                                                $ultima_atualizacao = trata_data($resultado_verificacao[0]['_data_ultima_atualizacao']);
                                            }

                                            // Guarnição de destino
                                            $guarnicao_destino = '-';
                                            if ($linha['id_cidade_distribuicao'] != null) {
                                                $resultado_cidade = $conexao->get_cidade_id($linha['id_cidade_distribuicao']);
                                                $guarnicao_destino = $resultado_cidade[0]['nome'];
                                            }

                                            $contador++;
                                ?>
                                            <tr>
                                                <!-- CPF -->
                                                <td>
                                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                        class="text-decoration-none">
                                                        <?= $linha['cpf'] ?>
                                                    </a>
                                                </td>

                                                <!-- Nome -->
                                                <td>
                                                    <div class="candidate-info">
                                                        <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                            class="fw-semibold candidate-name text-decoration-none">
                                                            <?= htmlspecialchars($linha['nome_completo']) ?>
                                                        </a>
                                                    </div>
                                                </td>

                                                <!-- Status do Processo -->
                                                <td class="text-center">
                                                    <span class="badge bg-<?= $concorrendo_processo_class ?>">
                                                        <i class="fa fa-<?= $concorrendo_processo_class == 'success' ? 'check' : 'times' ?> me-1"></i>
                                                        <?= $concorrendo_processo_text ?>
                                                    </span>
                                                </td>

                                                <!-- Especialidade -->
                                                <td>
                                                    <div class="especialidade-info">
                                                        <a href="relatorio_especialidade_candidato.php?id_especialidade=<?= $id_especialidade ?>"
                                                            class="text-decoration-none">
                                                            <span class="badge bg-primary me-1" style="color: #fff;"><?= strtoupper($ott_stt) ?></span>
                                                            <span class="fw-semibold"><?= htmlspecialchars($nome_especialidade) ?></span>
                                                        </a>
                                                        <?= $especialidade_status ?>
                                                    </div>
                                                </td>

                                                <!-- Cidade Escolhida -->
                                                <td>
                                                    <span class="location-badge">
                                                        <?= $resultado_verificacao[0]['nome_cidade'] ?: '-' ?>
                                                    </span>
                                                </td>

                                                <!-- Guarnição de Destino -->
                                                <td>
                                                    <span class="location-badge <?= $guarnicao_destino != '-' ? 'bg-success text-white' : '' ?>">
                                                        <?= $guarnicao_destino ?>
                                                    </span>
                                                </td>

                                                <!-- Distribuído -->
                                                <td class="text-center">
                                                    <span class="badge <?= $incorporado == '_Sim' ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= $incorporado == '_Sim' ? 'Sim' : 'Não' ?>
                                                    </span>
                                                </td>

                                                <!-- Última Atualização -->
                                                <td class="text-center">
                                                    <small class="text-muted update-time"><?= $ultima_atualizacao ?></small>
                                                </td>

                                                <!-- Ações -->
                                                <td class="text-center">
                                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip"
                                                        title="Visualizar candidato">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                <?php
                                        endif;
                                    endforeach;
                                endforeach;
                                ?>
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