<?php
/*ASP SILVA 06 JUN 25*/
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != "ouvidor" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 7755! Página não encontrada!");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$lista_suporte = $conexao->get_lista_suporte_todos_candidatos();

$lista_suporte_inicial = $conexao->get_suporte();

?>
<style>
    .card-header {
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
    }

    .stat-card {
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .status-respondido {
        color: #28a745;
        font-weight: 600;
    }

    .status-pendente {
        color: #dc3545;
        font-weight: 600;
    }

    .avatar-link {
        display: inline-block;
        transition: transform 0.2s ease;
    }

    .avatar-link:hover {
        transform: scale(1.1);
    }

    .table th {
        border-top: none;
        font-weight: 600;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.9rem;
        }

        .stat-card h4 {
            font-size: 1.2rem;
        }

        .stat-card h5 {
            font-size: 0.8rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .text-truncate {
            max-width: 150px !important;
        }
    }

    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        .stat-card {
            padding: 0.75rem !important;
        }

        .stat-card h4 {
            font-size: 1rem;
        }

        .text-truncate {
            max-width: 120px !important;
        }

        .badge {
            font-size: 0.7rem;
        }
    }

    /* Estilos para o leaderboard */
    .leaderboard {
        margin-top: 2rem;
    }

    .section-title {
        color: #006400;
        font-weight: 600;
        border-bottom: 2px solid #006400;
        padding-bottom: 0.5rem;
    }

    .leaderboard-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        gap: 1rem;
    }

    .leaderboard-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .first-place {
        background: linear-gradient(135deg, #fff9e6 0%, #ffefbf 100%);
        border: 2px solid #ffd700;
    }

    .second-place {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #c0c0c0;
    }

    .third-place {
        background: linear-gradient(135deg, #fef7e6 0%, #fae5cd 100%);
        border: 2px solid #cd7f32;
    }

    .user-rank {
        min-width: 40px;
        text-align: center;
    }

    .rank-number {
        font-weight: bold;
        font-size: 1.5rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        flex: 1;
        gap: 1rem;
        min-width: 0;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
    }

    .user-details {
        min-width: 0;
    }

    .user-name {
        color: #2c3e50;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-stats {
        flex: 2;
        min-width: 0;
    }

    .progress-container {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #006400 0%, #00a86b 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .stats-numbers {
        display: flex;
        justify-content: space-between;
        font-size: 1.2rem;
    }

    .responses-count {
        color: #006400;
        font-weight: 600;
    }

    .percentage {
        color: #6c757d;
        font-weight: 500;
    }

    .user-actions {
        margin-left: auto;
    }

    .user-actions i {
        font-size: 20px;
    }

    /* Estatísticas */
    .stat-card {
        border: 1px solid #e9ecef;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .bg-light-blue {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    }

    .bg-light-green {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    }

    .bg-light-orange {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    }

    .stat-icon {
        color: #006400;
    }

    .stat-number {
        color: #2c3e50;
        font-weight: 700;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .leaderboard-item {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .user-info {
            flex-direction: column;
            text-align: center;
        }

        .user-stats {
            width: 100%;
        }

        .stats-numbers {
            flex-direction: column;
            gap: 0.25rem;
            text-align: center;
        }

        .user-actions {
            margin-left: 0;
        }

        .stat-card {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .user-avatar {
            width: 40px;
            height: 40px;
        }

        .rank-number {
            font-size: 1.2rem;
        }

        .user-name {
            font-size: 0.9rem;
        }

        .responses-count,
        .percentage {
            font-size: 0.8rem;
        }
    }

    /* Estilos premium para o grid de performance */
    .performance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .performance-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .performance-card-gold {
        border: 2px solid #FFD700;
        background: linear-gradient(135deg, #fffaf0 0%, #fff5e6 100%);
    }

    .performance-card-silver {
        border: 2px solid #C0C0C0;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    }

    .performance-card-bronze {
        border: 2px solid #CD7F32;
        background: linear-gradient(135deg, #fff8f0 0%, #fff0e6 100%);
    }

    .performance-card-normal {
        border: 1px solid #e9ecef;
    }

    .rank-badge {
        padding: 0.8rem 0.7rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .bg-gold {
        background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%);
        color: #000;
    }

    .bg-silver {
        background: linear-gradient(135deg, #C0C0C0 0%, #E0E0E0 100%);
        color: #000;
    }

    .bg-bronze {
        background: linear-gradient(135deg, #CD7F32 0%, #D2691E 100%);
        color: #fff;
    }

    .user-profile {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .avatar-container {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
    }

    .performance-user-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 100, 0, 0.8);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }

    .view-profile-btn {
        color: white;
        font-size: 1.2rem;
        text-decoration: none;
    }

    .user-name {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .user-id {
        font-size: 1.1rem;
    }

    .performance-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-label {
        display: block;
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        display: block;
        font-weight: 700;
        color: #006400;
        font-size: 1.2rem;
    }

    .performance-bar {
        margin-top: 1rem;
    }

    .progress {
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        height: 6px;
    }

    .progress-bar {
        background: linear-gradient(90deg, #006400 0%, #00a86b 100%);
        transition: width 1s ease-in-out;
    }

    .card-footer {
        padding: 0.75rem 1rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        text-align: center;
    }

    /* KPIs */
    .kpi-card {
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
    }

    .kpi-value {
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .kpi-label {
        font-size: 0.9rem;
    }

    .section-title {
        color: #006400;
        font-weight: 600;
        border-bottom: 2px solid #006400;
        padding-bottom: 0.5rem;
        margin-top: 2rem;
    }

    .stats-summary {
        border-left: 4px solid #006400;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .performance-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .performance-stats {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .kpi-card {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }

        .avatar-container {
            width: 60px;
            height: 60px;
        }

        .user-name {
            font-size: 0.9rem;
        }

        .stat-value {
            font-size: 1rem;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Suporte ao Candidato <i class="fa fa-comments"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Suporte Candidato</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Card Suporte Candidato - Inscrito -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <h4 class="mb-0 p-15">
                        <i class="fa fa-comments me-2"></i> Suporte Candidato - Inscrito
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-20">
                        <table class="table table-hover table-striped" id="tabela_suporte_inscrito">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>CPF</th>
                                    <th>Motivo</th>
                                    <th>Mensagem</th>
                                    <th>Data Enviado</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $respondidos = 0;
                                $nao_respondidos = 0;
                                $somatorio_dias_resposta = 0;
                                $maior_tempo = 0;

                                foreach ($lista_suporte as $linha) {
                                    $dias_resposta = "";
                                    $usuario_respondeu = "_" . strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                    $respondido = "_Não";
                                    $status_class = "status-pendente";

                                    if ($linha['respondida'] == 1) {
                                        $respondido = "_Sim";
                                        $respondidos++;
                                        $status_class = "status-respondido";

                                        $dias_resposta = 0;
                                        $data_enviado = new DateTime($linha['data_enviado']);
                                        $data_respondido = new DateTime($linha['data_resposta']);
                                        $intervalo = $data_enviado->diff($data_respondido);
                                        $tempo_total = $intervalo->d + $intervalo->h / 24;
                                        $tempo_total = $tempo_total + $intervalo->i / 1440;
                                        $tempo_total = $tempo_total + $intervalo->s / 86400;

                                        if ($intervalo->m > 0) $tempo_total = $tempo_total + (30 * $intervalo->m);
                                        if ($tempo_total > $maior_tempo) $maior_tempo = $tempo_total;
                                        $somatorio_dias_resposta = $somatorio_dias_resposta + $tempo_total;
                                        $dias_resposta = ", em " . round($tempo_total, 2) . " dias por $usuario_respondeu ";
                                    } else {
                                        $nao_respondidos++;
                                    }

                                    echo '
                                <tr>
                                    <td>' . $linha['id'] . '</td>
                                    <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario_remetente'] . '" class="text-decoration-none">' . $linha['cpf'] . '</a></td>
                                    <td><span class="badge bg-secondary">' . $linha['motivo'] . '</span></td>
                                    <td class="text-truncate" style="max-width: 200px;" title="' . htmlspecialchars($linha['mensagem']) . '">' . $linha['mensagem'] . '</td>
                                    <td>' . trata_data_hora($linha['data_enviado']) . '</td>
                                    <td class="' . $status_class . '">' . $respondido . $dias_resposta . '</td>
                                    <td>
                                        <a href="suporte_candidato_visualiza.php?id_suporte=' . $linha['id'] . '" class="btn btn-sm btn-primary" title="Visualizar">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </td>
                                </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Estatísticas -->
                    <div class="row mt-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card bg-success text-white p-2 rounded text-center">
                                <h5 class="mb-1">Respondidos</h5>
                                <h4 class="mb-0"><?php echo $respondidos ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card bg-danger text-white p-2 rounded text-center">
                                <h5 class="mb-1">Não Respondidos</h5>
                                <h4 class="mb-0">
                                    <?php
                                    if ($respondidos > 0 || $nao_respondidos > 0)
                                        $porcentagem = round(($nao_respondidos / ($nao_respondidos + $respondidos)) * 100, 2);

                                    if ($nao_respondidos > 0)
                                        echo $nao_respondidos . " <small>($porcentagem%)</small>";
                                    else echo '0';
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card bg-info text-white p-2 rounded text-center">
                                <h5 class="mb-1">Média de Resp</h5>
                                <h4 class="mb-0"><?php if ($respondidos > 0 && $somatorio_dias_resposta > 0) echo round($somatorio_dias_resposta / $respondidos, 2) . " dias";
                                                    else echo "0"; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card bg-warning text-dark p-2 rounded text-center">
                                <h5 class="mb-1">Maior Tempo</h5>
                                <h4 class="mb-0"><?php echo round($maior_tempo, 2) . " dias"; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Suporte Candidato - Não Inscrito -->
            <?php if ($libera_suporte_inicial): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white mb-20">
                        <h4 class="mb-0 p-15">
                            <i class="fa fa-user-plus me-2"></i> Suporte Candidato - Não Inscrito
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mb-20">
                            <table class="table table-hover table-striped" id="tabela_suporte_nao_inscrito">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>CPF</th>
                                        <th>Motivo</th>
                                        <th>Mensagem</th>
                                        <th>Data Enviado</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $respondidos_ni = 0;
                                    $nao_respondidos_ni = 0;
                                    $somatorio_dias_resposta_ni = 0;
                                    $maior_tempo_ni = 0;

                                    foreach ($lista_suporte_inicial as $linha) {
                                        $dias_resposta = "";
                                        $usuario_respondeu = "_" . strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                        $respondido = "_Não";
                                        $status_class = "status-pendente";

                                        if ($linha['resposta'] != null) {
                                            $respondido = "_Sim";
                                            $respondidos_ni++;
                                            $status_class = "status-respondido";

                                            $dias_resposta = 0;
                                            $data_enviado = new DateTime($linha['data_enviado']);
                                            $data_respondido = new DateTime($linha['data_resposta']);
                                            $intervalo = $data_enviado->diff($data_respondido);
                                            $tempo_total = $intervalo->d + $intervalo->h / 24;
                                            $tempo_total = $tempo_total + $intervalo->i / 1440;
                                            $tempo_total = $tempo_total + $intervalo->s / 86400;

                                            if ($intervalo->m > 0) $tempo_total = $tempo_total + (30 * $intervalo->m);
                                            if ($tempo_total > $maior_tempo_ni) $maior_tempo_ni = $tempo_total;
                                            $somatorio_dias_resposta_ni = $somatorio_dias_resposta_ni + $tempo_total;
                                            $dias_resposta = ", em " . round($tempo_total, 2) . " dias por $usuario_respondeu ";
                                        } else {
                                            $nao_respondidos_ni++;
                                        }

                                        echo '
                                    <tr>
                                        <td>' . $linha['id'] . '</td>
                                        <td>' . $linha['cpf'] . '</td>
                                        <td><span class="badge bg-secondary">' . $linha['motivo'] . '</span></td>
                                        <td class="text-truncate" style="max-width: 200px;" title="' . htmlspecialchars($linha['mensagem']) . '">' . $linha['mensagem'] . '</td>
                                        <td>' . trata_data_hora($linha['data_enviado']) . '</td>
                                        <td class="' . $status_class . '">' . $respondido . $dias_resposta . '</td>
                                        <td>
                                            <a href="suporte_inicial_visualiza.php?criptografia=' . hash('sha256', $linha['id']) . '&id_suporte=' . $linha['id'] . '" class="btn btn-sm btn-primary" title="Visualizar">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </td>
                                    </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Estatísticas -->
                        <div class="row mt-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card bg-success text-white p-2 rounded text-center">
                                    <h5 class="mb-1">Respondidos</h5>
                                    <h4 class="mb-0"><?php echo $respondidos_ni ?></h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card bg-danger text-white p-2 rounded text-center">
                                    <h5 class="mb-1">Não Respondidos</h5>
                                    <h4 class="mb-0">
                                        <?php
                                        if ($respondidos_ni > 0 || $nao_respondidos_ni > 0)
                                            $porcentagem_ni = round(($nao_respondidos_ni / ($nao_respondidos_ni + $respondidos_ni)) * 100, 2);

                                        if ($nao_respondidos_ni > 0)
                                            echo $nao_respondidos_ni . " <small>($porcentagem_ni%)</small>";
                                        else echo '0';
                                        ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card bg-info text-white p-2 rounded text-center">
                                    <h5 class="mb-1">Média de Resp</h5>
                                    <h4 class="mb-0"><?php if ($respondidos_ni > 0 && $somatorio_dias_resposta_ni > 0) echo round($somatorio_dias_resposta_ni / $respondidos_ni, 2) . " dias";
                                                        else echo "0"; ?></h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card bg-warning text-dark p-2 rounded text-center">
                                    <h5 class="mb-1">Maior Tempo</h5>
                                    <h4 class="mb-0"><?php echo round($maior_tempo_ni, 2) . " dias"; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Card Quantidade de respostas por usuário (Admin apenas) -->
            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                <?php
                $lista_get_quantidade_x_usuario_suporte_cand = $conexao->get_quantidade_x_usuario_suporte_cand();
                $lista_get_quantidade_x_usuario_suporte = $conexao->get_quantidade_x_usuario_suporte();
                $lista_get_quantidade_x_usuario_suportes = array_merge(
                    $lista_get_quantidade_x_usuario_suporte_cand ?? [],
                    $lista_get_quantidade_x_usuario_suporte ?? []
                );

                // Ordenar por quantidade de respostas (decrescente)
                usort($lista_get_quantidade_x_usuario_suportes, function ($a, $b) {
                    return $b['quantidade'] - $a['quantidade'];
                });

                $total_respostas = 0;
                foreach ($lista_get_quantidade_x_usuario_suportes as $linha) {
                    $total_respostas += $linha['quantidade'];
                }
                $max_respostas = $lista_get_quantidade_x_usuario_suportes[0]['quantidade'] ?? 1;
                ?>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white mb-20">
                        <h4 class="mb-0 p-15">
                            <i class="fa fa-trophy me-2"></i> Desempenho da Equipe
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- KPIs em Destaque -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="kpi-card text-center p-3">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-users fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="kpi-value text-primary mb-0"><?php echo count($lista_get_quantidade_x_usuario_suportes); ?></h3>
                                    <p class="kpi-label text-muted mb-0">Colaboradores</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="kpi-card text-center p-3">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-reply-all fa-2x text-success"></i>
                                    </div>
                                    <h3 class="kpi-value text-success mb-0"><?php echo $total_respostas; ?></h3>
                                    <p class="kpi-label text-muted mb-0">Total de Respostas</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="kpi-card text-center p-3">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-line-chart fa-2x text-success"></i>
                                    </div>
                                    <h3 class="kpi-value text-success mb-0">
                                        <?php echo $total_respostas > 0 ? round($total_respostas / count($lista_get_quantidade_x_usuario_suportes), 1) : 0; ?>
                                    </h3>
                                    <p class="kpi-label text-muted mb-0">Média por Usuário</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="kpi-card text-center p-3">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-trophy fa-2x text-success"></i>
                                    </div>
                                    <h3 class="kpi-value text-success mb-0"><?php echo $max_respostas; ?></h3>
                                    <p class="kpi-label text-muted mb-0">Recorde</p>
                                </div>
                            </div>
                        </div>

                        <!-- Grid de Performance -->
                        <h5 class="section-title mb-20">
                            <i class="fa fa-medal me-2"></i>Performance Individual - Suporte ao candidato Inscrito
                        </h5>

                        <div class="performance-grid">
                            <?php
                            $rank = 1;
                            foreach ($lista_get_quantidade_x_usuario_suporte_cand as $linha) {
                                $foto = "user.jpg";
                                $get_foto = $conexao->get_foto_usuario($linha['id']);
                                if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                    $foto = $get_foto[0]['nome'];
                                }

                                $nome_usuario = strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                $percentual = $max_respostas > 0 ? ($linha['quantidade'] / $max_respostas) * 100 : 0;

                                // Determinar a cor do card baseado no rank
                                $card_class = '';
                                $badge_class = '';
                                $icon = '';

                                if ($rank == 1) {
                                    $card_class = 'performance-card-gold';
                                    $badge_class = 'bg-gold';
                                    $icon = '🥇';
                                } elseif ($rank == 2) {
                                    $card_class = 'performance-card-silver';
                                    $badge_class = 'bg-silver';
                                    $icon = '🥈';
                                } elseif ($rank == 3) {
                                    $card_class = 'performance-card-bronze';
                                    $badge_class = 'bg-bronze';
                                    $icon = '🥉';
                                } else {
                                    $card_class = 'performance-card-normal';
                                    $badge_class = 'bg-secondary';
                                    $icon = '#' . $rank;
                                }
                            ?>

                                <div class="performance-card <?php echo $card_class; ?> p-20">
                                    <div class="card-header">
                                        <span class="mr-10 rank-badge <?php echo $badge_class; ?>">
                                            <?php echo $icon; ?>
                                        </span>
                                        <span class="responses-count"><?php echo $linha['quantidade']; ?> respostas</span>
                                    </div>

                                    <div class="card-body">
                                        <div class="user-profile">
                                            <div class="avatar-container">
                                                <img class="performance-user-avatar" src="fotos/<?php echo $foto; ?>" alt="<?php echo $nome_usuario; ?>" onerror="this.src='imagens/user.jpg'">
                                                <div class="avatar-overlay">
                                                    <a href="usuario_visualiza.php?id_usuario=<?php echo $linha['id']; ?>" class="view-profile-btn">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <h5 class="user-name"><?php echo $nome_usuario; ?></h5>
                                            <small class="user-id text-muted">ID: <?php echo $linha['id']; ?></small>
                                        </div>

                                        <div class="performance-stats">
                                            <div class="stat-item">
                                                <span class="stat-label">Posição</span>
                                                <span class="stat-value"><?php echo $rank; ?>º</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">Percentual</span>
                                                <span class="stat-value"><?php echo round($percentual); ?>%</span>
                                            </div>
                                        </div>

                                        <div class="performance-bar">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar"
                                                    style="width: <?php echo $percentual; ?>%"
                                                    role="progressbar"
                                                    aria-valuenow="<?php echo $percentual; ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <small class="text-muted">
                                            <i class="fa fa-tachometer-alt me-1"></i>
                                            <?php echo round($percentual); ?>% do recorde
                                        </small>
                                    </div>
                                </div>
                            <?php
                                $rank++;
                            }
                            ?>
                        </div>

                        <?php if ($libera_suporte_inicial): ?>
                            <h5 class="section-title mb-20">
                                <i class="fa fa-medal me-2"></i>Performance Individual - Suporte ao candidato Não Inscrito
                            </h5>

                            <div class="performance-grid">
                                <?php
                                $rank = 1;
                                foreach ($lista_get_quantidade_x_usuario_suporte as $linha) {
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                        $foto = $get_foto[0]['nome'];
                                    }

                                    $nome_usuario = strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                    $percentual = $max_respostas > 0 ? ($linha['quantidade'] / $max_respostas) * 100 : 0;

                                    // Determinar a cor do card baseado no rank
                                    $card_class = '';
                                    $badge_class = '';
                                    $icon = '';

                                    if ($rank == 1) {
                                        $card_class = 'performance-card-gold';
                                        $badge_class = 'bg-gold';
                                        $icon = '🥇';
                                    } elseif ($rank == 2) {
                                        $card_class = 'performance-card-silver';
                                        $badge_class = 'bg-silver';
                                        $icon = '🥈';
                                    } elseif ($rank == 3) {
                                        $card_class = 'performance-card-bronze';
                                        $badge_class = 'bg-bronze';
                                        $icon = '🥉';
                                    } else {
                                        $card_class = 'performance-card-normal';
                                        $badge_class = 'bg-secondary';
                                        $icon = '#' . $rank;
                                    }
                                ?>

                                    <div class="performance-card <?php echo $card_class; ?> p-20">
                                        <div class="card-header">
                                            <span class="mr-10 rank-badge <?php echo $badge_class; ?>">
                                                <?php echo $icon; ?>
                                            </span>
                                            <span class="responses-count"><?php echo $linha['quantidade']; ?> respostas</span>
                                        </div>

                                        <div class="card-body">
                                            <div class="user-profile">
                                                <div class="avatar-container">
                                                    <img class="performance-user-avatar" src="fotos/<?php echo $foto; ?>" alt="<?php echo $nome_usuario; ?>" onerror="this.src='imagens/user.jpg'">
                                                    <div class="avatar-overlay">
                                                        <a href="usuario_visualiza.php?id_usuario=<?php echo $linha['id']; ?>" class="view-profile-btn">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <h5 class="user-name"><?php echo $nome_usuario; ?></h5>
                                                <small class="user-id text-muted">ID: <?php echo $linha['id']; ?></small>
                                            </div>

                                            <div class="performance-stats">
                                                <div class="stat-item">
                                                    <span class="stat-label">Posição</span>
                                                    <span class="stat-value"><?php echo $rank; ?>º</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Percentual</span>
                                                    <span class="stat-value"><?php echo round($percentual); ?>%</span>
                                                </div>
                                            </div>

                                            <div class="performance-bar">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar"
                                                        style="width: <?php echo $percentual; ?>%"
                                                        role="progressbar"
                                                        aria-valuenow="<?php echo $percentual; ?>"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <small class="text-muted">
                                                <i class="fa fa-tachometer-alt me-1"></i>
                                                <?php echo round($percentual); ?>% do recorde
                                            </small>
                                        </div>
                                    </div>
                                <?php
                                    $rank++;
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Leaderboard -->
                        <div class="leaderboard mb-20">
                            <h5 class="section-title mb-20">
                                <i class="fa fa-ranking-star me-2"></i>Ranking de Respostas - Candidatos Inscritos
                            </h5>

                            <div class="leaderboard-list">
                                <?php
                                $rank = 1;
                                $max_respostas = $lista_get_quantidade_x_usuario_suporte_cand[0]['quantidade'] ?? 1;

                                foreach ($lista_get_quantidade_x_usuario_suporte_cand as $linha) {
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                        $foto = $get_foto[0]['nome'];
                                    }

                                    $nome_usuario = strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                    $percentual = $max_respostas > 0 ? ($linha['quantidade'] / $max_respostas) * 100 : 0;

                                    // Determinar a cor do rank
                                    $rank_class = '';
                                    $rank_icon = '';
                                    if ($rank == 1) {
                                        $rank_class = 'first-place';
                                        $rank_icon = '🥇';
                                    } elseif ($rank == 2) {
                                        $rank_class = 'second-place';
                                        $rank_icon = '🥈';
                                    } elseif ($rank == 3) {
                                        $rank_class = 'third-place';
                                        $rank_icon = '🥉';
                                    } else {
                                        $rank_icon = '#' . $rank;
                                    }
                                ?>

                                    <div class="leaderboard-item <?php echo $rank_class; ?>">
                                        <div class="user-rank">
                                            <span class="rank-number"><?php echo $rank_icon; ?></span>
                                        </div>

                                        <div class="user-info">
                                            <img class="user-avatar" src="fotos/<?php echo $foto; ?>" alt="<?php echo $nome_usuario; ?>" onerror="this.src='imagens/user.jpg'">
                                            <div class="user-details">
                                                <h5 class="user-name mb-1"><?php echo $nome_usuario; ?></h5>
                                                <small class="text-muted">ID: <?php echo $linha['id']; ?></small>
                                            </div>
                                        </div>

                                        <div class="user-stats">
                                            <div class="progress-container">
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar" style="width: <?php echo $percentual; ?>%"></div>
                                                </div>
                                                <div class="stats-numbers">
                                                    <span class="responses-count"><?php echo $linha['quantidade']; ?> respostas</span>
                                                    <span class="percentage"><?php echo round($percentual); ?>%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="user-actions">
                                            <a href="usuario_visualiza.php?id_usuario=<?php echo $linha['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver perfil">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                    $rank++;
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Leaderboard Não Inscritos -->
                        <?php if ($libera_suporte_inicial): ?>
                            <div class="leaderboard mb-20">
                                <h5 class="section-title mb-20">
                                    <i class="fa fa-ranking-star me-2"></i>Ranking de Respostas - Não Inscritos
                                </h5>

                                <div class="leaderboard-list">
                                    <?php
                                    $rank = 1;
                                    $max_respostas = $lista_get_quantidade_x_usuario_suporte[0]['quantidade'] ?? 1;

                                    foreach ($lista_get_quantidade_x_usuario_suporte as $linha) {
                                        $foto = "user.jpg";
                                        $get_foto = $conexao->get_foto_usuario($linha['id']);
                                        if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                            $foto = $get_foto[0]['nome'];
                                        }

                                        $nome_usuario = strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                        $percentual = $max_respostas > 0 ? ($linha['quantidade'] / $max_respostas) * 100 : 0;

                                        // Determinar a cor do rank
                                        $rank_class = '';
                                        $rank_icon = '';
                                        if ($rank == 1) {
                                            $rank_class = 'first-place';
                                            $rank_icon = '🥇';
                                        } elseif ($rank == 2) {
                                            $rank_class = 'second-place';
                                            $rank_icon = '🥈';
                                        } elseif ($rank == 3) {
                                            $rank_class = 'third-place';
                                            $rank_icon = '🥉';
                                        } else {
                                            $rank_icon = '#' . $rank;
                                        }
                                    ?>

                                        <div class="leaderboard-item <?php echo $rank_class; ?>">
                                            <div class="user-rank">
                                                <span class="rank-number"><?php echo $rank_icon; ?></span>
                                            </div>

                                            <div class="user-info">
                                                <img class="user-avatar" src="fotos/<?php echo $foto; ?>" alt="<?php echo $nome_usuario; ?>" onerror="this.src='imagens/user.jpg'">
                                                <div class="user-details">
                                                    <h5 class="user-name mb-1"><?php echo $nome_usuario; ?></h5>
                                                    <small class="text-muted">ID: <?php echo $linha['id']; ?></small>
                                                </div>
                                            </div>

                                            <div class="user-stats">
                                                <div class="progress-container">
                                                    <div class="progress-bar-container">
                                                        <div class="progress-bar" style="width: <?php echo $percentual; ?>%"></div>
                                                    </div>
                                                    <div class="stats-numbers">
                                                        <span class="responses-count"><?php echo $linha['quantidade']; ?> respostas</span>
                                                        <span class="percentage"><?php echo round($percentual); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="user-actions">
                                                <a href="usuario_visualiza.php?id_usuario=<?php echo $linha['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver perfil">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php
                                        $rank++;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Resumo Estatístico -->
                        <div class="stats-summary mt-4 p-20 bg-light rounded-3">
                            <h5 class="mb-3">
                                <i class="fa fa-chart-pie me-2"></i>Resumo Estatístico
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Maior performance:</span>
                                        <strong><?php echo $max_respostas; ?> respostas</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Performance média:</span>
                                        <strong><?php echo $total_respostas > 0 ? round($total_respostas / count($lista_get_quantidade_x_usuario_suportes), 1) : 0; ?> respostas</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total de colaboradores:</span>
                                        <strong><?php echo count($lista_get_quantidade_x_usuario_suportes); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Taxa de atividade:</span>
                                        <strong><?php echo $total_respostas > 0 ? '100%' : '0%'; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Inicialização de tooltips (se usando Bootstrap)
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_suporte_inscrito').DataTable({
        "order": [
            [0, "desc"]
        ]
    });

    $('#tabela_suporte_nao_inscrito').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>