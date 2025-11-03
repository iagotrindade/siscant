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

$lista_candidatos = $conexao->get_candidatos_concorrendo();
$lista_medicos_obrigatorias = $conexao->get_medicos_obrigatorios();

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Tempo de sv público <i class="fa fa-clock-o"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Tempo de sv público</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela de Todos os Candidatos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-users me-2"></i>
                            Todos os Candidatos
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th width="120px" class="text-center"><i class="fa fa-shield"></i> Ativa/Reserva</th>
                                    <th width="100px" class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidades</th>
                                    <th width="100px" class="text-center"><i class="fa fa-shield"></i> Sv Militar</th>
                                    <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Idade</th>
                                    <th width="100px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data_atual = new DateTime(date("Y-m-d"));

                                foreach ($lista_candidatos as $linha):
                                    $data_nasc = new DateTime(reverte_data($linha['data_nascimento']));
                                    $intervalo = $data_atual->diff($data_nasc);

                                    $anos_vida = (int)$intervalo->format('%Y');
                                    $meses_vida = (int)$intervalo->format('%m');
                                    $dias_vida = (int)$intervalo->format('%d');

                                    // Cálculo do tempo de serviço
                                    $anos_sv_publico = (int)$linha['tempo_sv_pub_anos'];
                                    $meses_sv_publico = (int)$linha['tempo_sv_pub_meses'];
                                    $dias_sv_publico = (int)$linha['tempo_sv_pub_dias'];

                                    $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                                    $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                                    $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];

                                    $total_dias_sv_militar = $dias_sv_publico + $dias_sv_militar;
                                    $total_mese_sv_publico = $meses_sv_publico + $meses_sv_militar;

                                    if ($total_dias_sv_militar >= 30) {
                                        $total_mese_sv_publico++;
                                        $total_dias_sv_militar = $total_dias_sv_militar - 30;
                                    }

                                    $total_anos_sv_publico = $anos_sv_publico + $anos_sv_militar;
                                    if ($total_mese_sv_publico >= 12) {
                                        $total_anos_sv_publico++;
                                        $total_mese_sv_publico = $total_mese_sv_publico - 12;
                                    }

                                    // Especialidades do Candidato
                                    $inscricoes = $conexao->get_especialidade_candidato($linha['id']);
                                    $especialidades_array = [];

                                    foreach ($inscricoes as $valor):
                                        $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'], $valor['id_especialidade']);

                                        if (count($resultado_verificacao) > 0):
                                            $ott_stt = $resultado_verificacao[0]['ott_stt'];
                                            $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                            $especialidades_array[] = [
                                                'ott' => mb_strtoupper($ott_stt),
                                                'nome' => $nome_especialidade
                                            ];
                                        endif;
                                    endforeach;
                                ?>
                                    <tr>
                                        <!-- CPF -->
                                        <td>
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                <?= $linha['cpf'] ?>
                                            </a>
                                        </td>

                                        <!-- Nome -->
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                        </td>

                                        <!-- Ativa/Reserva -->
                                        <td class="text-center">
                                            <?php
                                            $status_class = 'primary';
                                            if ($linha['ativa_reserva'] == 'ativa') $status_class = 'primary';
                                            if ($linha['ativa_reserva'] == 'reserva') $status_class = 'warning';
                                            ?>
                                            <span class="status-badge badge bg-<?= $status_class ?>">
                                                <?= $linha['ativa_reserva'] ?>
                                            </span>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            <span> _<?= htmlspecialchars($linha['etapa']) ?>
                                        </td>
                                        </td>

                                        <!-- Especialidades -->
                                        <td>
                                            <div class="especialidades-list">
                                                <?php foreach ($especialidades_array as $especialidade): ?>
                                                    <span class="badge bg-primary text-dark border me-1 mb-1">
                                                        <small><?= $especialidade['ott'] ?> - <?= htmlspecialchars($especialidade['nome']) ?></small>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>

                                        <!-- Serviço Militar -->
                                        <td class="text-center">
                                            <div class="servico-militar-info">
                                                <small class="text-muted d-block">Anos: <strong><?= $total_anos_sv_publico ?></strong></small>
                                                <small class="text-muted d-block">Meses: <strong><?= $total_mese_sv_publico ?></strong></small>
                                                <small class="text-muted">Dias: <strong><?= $total_dias_sv_militar ?></strong></small>
                                            </div>
                                        </td>

                                        <!-- Idade -->
                                        <td class="text-center">
                                            <div class="idade-info">
                                                <small class="text-muted d-block">Anos: <strong><?= $anos_vida ?></strong></small>
                                                <small class="text-muted d-block">Meses: <strong><?= $meses_vida ?></strong></small>
                                                <small class="text-muted">Dias: <strong><?= $dias_vida ?></strong></small>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
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

            <!-- Tabela de Médicos Obrigatórios -->
            <?php if ($_SESSION['selecao_codigo'] == 'mfdv'): ?>
                <div class="card dashboard-card mb-4">
                    <div class="card-header dashboard-header mb-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="card-title mb-0">
                                <i class="fa fa-user-md me-2"></i>
                                Médicos Obrigatórios
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabela_dinamica2">
                                <thead class="table-light">
                                    <tr>
                                        <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                        <th><i class="fa fa-user"></i> Nome</th>
                                        <th><i class="fa fa-stethoscope"></i> Especialidades</th>
                                        <th width="100px" class="text-center"><i class="fa fa-hospital"></i> Serviço Público</th>
                                        <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Idade</th>
                                        <th width="100px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_atual = new DateTime(date("Y-m-d"));

                                    foreach ($lista_medicos_obrigatorias as $linha):
                                        $data_nasc = new DateTime(reverte_data($linha['data_nascimento']));
                                        $intervalo = $data_atual->diff($data_nasc);

                                        $anos_vida = (int)$intervalo->format('%Y');
                                        $meses_vida = (int)$intervalo->format('%m');
                                        $dias_vida = (int)$intervalo->format('%d');

                                        // Cálculo do tempo de serviço
                                        $anos_sv_publico = (int)$linha['tempo_sv_pub_anos'];
                                        $meses_sv_publico = (int)$linha['tempo_sv_pub_meses'];
                                        $dias_sv_publico = (int)$linha['tempo_sv_pub_dias'];

                                        $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                                        $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                                        $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];

                                        $total_dias_sv_militar = $dias_sv_publico + $dias_sv_militar;
                                        $total_mese_sv_publico = $meses_sv_publico + $meses_sv_militar;

                                        if ($total_dias_sv_militar >= 30) {
                                            $total_mese_sv_publico++;
                                            $total_dias_sv_militar = $total_dias_sv_militar - 30;
                                        }

                                        $total_anos_sv_publico = $anos_sv_publico + $anos_sv_militar;
                                        if ($total_mese_sv_publico >= 12) {
                                            $total_anos_sv_publico++;
                                            $total_mese_sv_publico = $total_mese_sv_publico - 12;
                                        }

                                        // Especialidades do Candidato
                                        $inscricoes = $conexao->get_especialidade_candidato($linha['id']);
                                        $especialidades_array = [];

                                        foreach ($inscricoes as $valor):
                                            $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'], $valor['id_especialidade']);

                                            if (count($resultado_verificacao) > 0):
                                                $ott_stt = $resultado_verificacao[0]['ott_stt'];
                                                $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                                $especialidades_array[] = [
                                                    'ott' => mb_strtoupper($ott_stt),
                                                    'nome' => $nome_especialidade
                                                ];
                                            endif;
                                        endforeach;
                                    ?>
                                        <tr>
                                            <!-- CPF -->
                                            <td>
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                    <?= $linha['cpf'] ?>
                                                </a>
                                            </td>

                                            <!-- Nome -->
                                            <td>
                                                <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                            </td>

                                            <!-- Especialidades -->
                                            <td>
                                                <div class="especialidades-list">
                                                    <?php foreach ($especialidades_array as $especialidade): ?>
                                                        <span class="badge bg-primary    text-dark border me-1 mb-1">
                                                            <?= $especialidade['ott'] ?> - <?= htmlspecialchars($especialidade['nome']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>

                                            <!-- Serviço Público -->
                                            <td class="text-center">
                                                <div class="servico-publico-info">
                                                    <small class="text-muted d-block">Anos: <strong><?= $total_anos_sv_publico ?></strong></small>
                                                    <small class="text-muted d-block">Meses: <strong><?= $total_mese_sv_publico ?></strong></small>
                                                    <small class="text-muted">Dias: <strong><?= $total_dias_sv_militar ?></strong></small>
                                                </div>
                                            </td>

                                            <!-- Idade -->
                                            <td class="text-center">
                                                <div class="idade-info">
                                                    <small class="text-muted d-block">Anos: <strong><?= $anos_vida ?></strong></small>
                                                    <small class="text-muted d-block">Meses: <strong><?= $meses_vida ?></strong></small>
                                                    <small class="text-muted">Dias: <strong><?= $dias_vida ?></strong></small>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
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
            <?php endif; ?>
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