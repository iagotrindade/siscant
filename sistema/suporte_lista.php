<?php
set_time_limit(300); // 5 minutos

include_once 'menu.php';


if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != "ouvidor" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 7755! Página não encontrada!");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$criptografia = $_POST['criptografia'];

$erroImap = false;
if ($_GET['update_emails']) {
    include '../banco_dados/captura_emails.php';
}

$lista_suporte = $conexao->get_lista_suporte_todos_candidatos();

$lista_suporte_inicial = $conexao->get_suporte();

$lista_emails = $conexao->get_lista_emails();
?>
<style>
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
        color: #fff;
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

    .perform-card-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-color: var(--primary-color);
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

    .attachments-container {
        margin-top: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }

    .attachments-title {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .attachments-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .attachment-item {
        display: flex;
        align-items: center;
    }

    .attachment-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        text-decoration: none;
        color: #495057;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .attachment-link:hover {
        background-color: #007bff;
        color: white;
        text-decoration: none;
    }

    .attachment-link i {
        font-size: 12px;
    }

    /* Para mensagens do bot (respostas) */
    .bot-message .attachments-container {
        border-left-color: #28a745;
        background-color: #f0fff4;
    }

    .user-message .attachments-container {
        border-left-color: #007bff;
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .user-info-section {
            flex-direction: column;
            text-align: center;
        }

        .user-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .user-meta {
            justify-content: center;
        }

        .detail-item {
            flex: 0 0 100%;
            margin-right: 0;
        }
    }

    .chat-container {
        height: 400px;
        overflow-y: auto;
        padding: 3rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .chat-input {
        display: flex;
        margin-bottom: 1rem;
    }

    .chat-input input,
    .chat-input textarea {
        flex: 1;
        padding: 1.2rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 6px 0 0 6px;
        outline: none;
    }

    .chat-input button,
    .send-mail-button {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 0 2.5rem;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .chat-input button:hover,
    .send-mail-button:hover {
        background-color: #004d00;
    }

    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
        background: white;
    }

    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .card-header-modern {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body-modern {
        padding: 20px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1.3rem;
    }

    .status-pending {
        background-color: #fff4e0;
        color: #e0a800;
    }

    .status-answered {
        background-color: #e6f7f1;
        color: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary-color);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #d1d3e2;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-btn {
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 1.2rem;
    }

    .divider {
        height: 1px;
        background: #e3e6f0;
        margin: 15px 0;
    }

    .message {
        display: flex;
        font-size: 16px;
        max-width: 75%;
        padding: 0.75rem 1rem;
        border-radius: 18px;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 20px;
    }

    .message:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .user-message {
        background-color: var(--user-message-bg);
        color: var(--text-dark);
        margin-left: auto;
        border-bottom-right-radius: 6px;
        flex-direction: row-reverse;
        border: 1px solid var(--user-message-border);
        box-shadow: 0 2px 8px rgba(0, 100, 0, 0.08);
        border-right: 3px solid #4453feff;
    }

    .bot-message {
        background-color: var(--bot-message-bg);
        color: var(--text-dark);
        margin-right: auto;
        border-bottom-left-radius: 6px;
        border: 1px solid var(--bot-message-border);
        box-shadow: 0 2px 8px rgba(0, 100, 0, 0.05);
        border-left: 3px solid var(--primary-light);
    }

    .message-avatar {
        flex-shrink: 0;
        margin: 0 12px;
        align-self: center;
    }

    .user-message .message-avatar {
        margin: 0 0 0 12px;
    }

    .bot-message .message-avatar {
        margin: 0 12px 0 0;
    }

    .user-image {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bot-message .user-image {
        border: 2px solid var(--primary-color);
    }

    .message-text {
        margin-bottom: 0.4rem;
        line-height: 1.5;
    }

    .message-time {
        font-size: 1.2rem;
        opacity: 0.7;
        display: block;
        text-align: right;
    }

    .bot-message .message-time {
        text-align: left;
    }

    .attachments-upload-area {
        margin: 15px 0;
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .attachments-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        color: #495057;
    }

    .attachments-header i {
        color: #6c757d;
    }

    .attachments-header small {
        font-weight: normal;
        color: #6c757d;
    }

    .attachments-dropzone {
        border: 2px dashed #007bff;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: white;
    }

    .attachments-dropzone:hover {
        border-color: #0056b3;
        background-color: #f8f9fa;
    }

    .attachments-dropzone.dragover {
        border-color: #28a745;
        background-color: #e8f5e8;
    }

    .dropzone-content i {
        font-size: 48px;
        color: #6c757d;
    }

    .dropzone-content p {
        margin: 0 0 5px 0;
        font-weight: 500;
        color: #495057;
    }

    .dropzone-content small {
        color: #6c757d;
    }

    .attachments-preview {
        margin-top: 15px;
        display: none;
    }

    .attachments-preview.has-files {
        display: block;
    }

    .attachment-preview-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .attachment-preview-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .attachment-preview-icon {
        width: 24px;
        text-align: center;
        color: #6c757d;
    }

    .attachment-preview-name {
        font-size: 14px;
        color: #495057;
        word-break: break-all;
    }

    .attachment-preview-size {
        font-size: 12px;
        color: #6c757d;
        margin-left: auto;
        margin-right: 15px;
    }

    .attachment-preview-remove {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .attachment-preview-remove:hover {
        background-color: #f8d7da;
    }

    .attachments-limits {
        margin-top: 10px;
        text-align: center;
    }

    .attachments-limits small {
        color: #6c757d;
    }

    .attachments-limits i {
        margin-right: 5px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .attachments-upload-area {
            padding: 15px;
        }

        .attachments-dropzone {
            padding: 20px;
        }

        .dropzone-content i {
            font-size: 36px;
        }
    }

    @media (max-width: 768px) {
        .message {
            max-width: 90%;
            padding: 0.6rem 0.8rem;
        }

        .user-image {
            width: 36px;
            height: 36px;
        }

        .message-avatar {
            margin: 0 8px;
        }

        .user-message .message-avatar {
            margin: 0 0 0 8px;
        }

        .bot-message .message-avatar {
            margin: 0 8px 0 0;
        }
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
        <div class="col-md-12">
            <!-- Card Suporte Candidato - Inscrito -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0">
                        <i class="fa fa-comments me-2"></i> Suporte Candidato - Inscrito
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-20">
                        <table class="table table-hover table-striped" id="tabela_suporte_inscrito">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                    <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                    <th><i class="fa fa-question-circle me-1"></i> Motivo</th>
                                    <th><i class="fa fa-comment me-1"></i> Mensagem</th>
                                    <th><i class="fa fa-calendar me-1"></i> Data Enviado</th>
                                    <th><i class="fa fa-server me-1"></i> Status</th>
                                    <th><i class="fa fa-cogs me-1"></i> Ações</th>
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
                                    $usuario_respondeu = "_" . mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
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
                                    <td><span class="badge bg-primary">' . $linha['motivo'] . '</span></td>
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
                        <span class="mb-0">
                            <i class="fa fa-user-plus me-2"></i> Suporte Candidato - Não Inscrito
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mb-20">
                            <table class="table table-hover table-striped" id="tabela_suporte_nao_inscrito">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                        <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                        <th><i class="fa fa-question-circle me-1"></i> Motivo</th>
                                        <th><i class="fa fa-comment me-1"></i> Mensagem</th>
                                        <th><i class="fa fa-calendar me-1"></i> Data Enviado</th>
                                        <th><i class="fa fa-server me-1"></i> Respondido</th>
                                        <th><i class="fa fa-cogs me-1"></i> Ações</th>
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
                                        $usuario_respondeu = "_" . mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
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
                                        <td><span class="badge bg-primary">' . $linha['motivo'] . '</span></td>
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

            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0">
                        <i class="fa fa-envelope"></i> E-mails
                    </span>

                    <?php if (!$erroImap): ?>
                        <a href="suporte_lista.php?update_emails=true" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Exportar para Excel">
                            <i class="fa fa-refresh"></i> Sincronizar
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!$erroImap): ?>
                        <form class="mb-20" action="../banco_dados/enviar_email.php" method="POST" id="suporte" enctype="multipart/form-data">
                            <input hidden type="text" name='criptografia' value="<?= $criptografia ?>">

                            <?php if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'ouvidor'): ?>
                                <div class="chat-input">
                                    <select name="destinatarios[]" class="form-control select2" style="width: 100%;" multiple>
                                        <?php
                                        $lista_emails_candidatos = $conexao->get_email_todos_candidatos();

                                        $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                        foreach ($lista_emails_candidatos as $value) { ?>
                                            <option value="<?php echo htmlspecialchars($value['mail']); ?>"
                                                <?php echo in_array($value['mail'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($value['mail'] . ' - ' . $value['nome_completo']) . " (" . $value['cpf'] . ")"; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="chat-input">
                                    <input type="text"
                                        placeholder="Digite o Assunto..."
                                        autocomplete="off"
                                        name="assunto">
                                </div>

                                <div class="chat-input">
                                    <textarea
                                        placeholder="Digite sua mensagem..."
                                        autocomplete="off"
                                        name="mensagem"></textarea>
                                </div>

                                <!-- Área de Anexos da Resposta -->
                                <div class="attachments-upload-area">
                                    <div class="attachments-header">
                                        <i class="fa fa-paperclip"></i>
                                        <span>Anexos</span>
                                        <small>(Opcional)</small>
                                    </div>

                                    <div class="attachments-dropzone" id="attachmentsDropzone">
                                        <div class="dropzone-content">
                                            <i class="fa fa-cloud-upload"></i>
                                            <p>Arraste arquivos aqui ou clique para selecionar</p>
                                            <small>Formatos permitidos: PDF, Word, Excel, Imagens, etc.</small>
                                        </div>
                                        <input type="file"
                                            name="anexos[]"
                                            id="anexosInput"
                                            multiple
                                            style="display: none;"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt,.zip,.rar">
                                    </div>

                                    <div class="attachments-preview" id="attachmentsPreview">
                                        <!-- Os arquivos selecionados aparecerão aqui -->
                                    </div>

                                    <div class="attachments-limits">
                                        <small>
                                            <i class="fa fa-info-circle"></i>
                                            Tamanho máximo por arquivo: 10MB | Máximo de 50 arquivos
                                        </small>
                                    </div>
                                </div>

                                <div style="text-align: right; margin-top: 20px;">
                                    <button class="send-mail-button" style="font-weight:600; width: 20%; padding: 10px; border-radius: 6px" type="submit">
                                        Enviar <i class="bi bi-send"></i>
                                    </button>
                                </div>

                            <?php endif; ?>
                        </form>
                        <div class="table-responsive mb-20">
                            <table class="table table-hover table-striped" id="tabela_emails">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                        <th><i class="fa fa-user me-1"></i> Remetente</th>
                                        <th><i class="fa fa-id-card me-1"></i> Email</th>
                                        <th><i class="fa fa-question-circle me-1"></i> Assunto</th>
                                        <th><i class="fa fa-comment me-1"></i> Mensagem</th>
                                        <th><i class="fa fa-calendar me-1"></i> Data Enviado</th>
                                        <th><i class="fa fa-server me-1"></i> Respondido</th>
                                        <th><i class="fa fa-cogs me-1"></i> Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $respondidos_ni = 0;
                                    $nao_respondidos_ni = 0;
                                    $somatorio_dias_resposta_ni = 0;
                                    $maior_tempo_ni = 0;

                                    foreach ($lista_emails as $linha) {
                                        $dias_resposta = "";
                                        $usuario_respondeu = "_" . mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                                        $respondido = "_Não";
                                        $status_class = "status-pendente";

                                        if ($linha['resposta'] != null || $linha['respondido']) {
                                            $respondido = "_Sim";
                                            $respondidos_ni++;
                                            $status_class = "status-respondido";

                                            $dias_resposta = 0;
                                            $data_enviado = new DateTime($linha['data_criacao']);
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
                                            <td>' . $linha['email_remetente'] . '</td>
                                            <td>' . $linha['remetente'] . '</td>
                                            <td><span class="badge bg-primary text-truncate" style="max-width: 200px;">' . $linha['assunto'] . '</span></td>
                                            <td class="text-truncate" style="max-width: 200px;" title="' . htmlspecialchars($linha['mensagem']) . '">' . $linha['mensagem'] . '</td>
                                            <td>' . trata_data_hora($linha['data_criacao' ?? date('Y-m-d H:i:s')]) . '</td>
                                            <td class="' . $status_class . '">' . $respondido . $dias_resposta . '</td>
                                            <td>
                                                <a target="_blank" href="email_visualiza.php?criptografia=' . hash('sha256', $linha['id']) . '&id_email=' . $linha['id'] . '" class="btn btn-sm btn-primary" title="Visualizar">
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

                    <?php else: ?>
                        <div class="text-center text-muted py-4 border rounded bg-light mb-20 text-danger">
                            <i class="fa fa-exclamation-circle fa-2x mb-2 opacity-50"></i><br>
                            Erro na Conexão com o E-mail!
                            <br>
                            Verifique as Configurações de E-mail dentro de "Configurações da Seleção"
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card Quantidade de respostas por usuário (Admin apenas) -->
            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                <?php
                $lista_get_quantidade_x_usuario_suporte_cand = $conexao->get_quantidade_x_usuario_suporte_cand();
                $lista_get_quantidade_x_usuario_suporte = $conexao->get_quantidade_x_usuario_suporte();
                $lista_get_quantidade_x_usuario_email = $conexao->get_quantidade_x_usuario_email();

                // Consolidar todos os dados em um array único por usuário
                $usuarios_consolidados = [];

                // Função para processar e consolidar os arrays
                function consolidarUsuarios($array, &$usuarios_consolidados)
                {
                    if (!empty($array)) {
                        foreach ($array as $usuario) {
                            $id = $usuario['id'];
                            if (!isset($usuarios_consolidados[$id])) {
                                $usuarios_consolidados[$id] = [
                                    'id' => $usuario['id'],
                                    'posto_grad' => $usuario['posto_grad'],
                                    'nome_guerra' => $usuario['nome_guerra'],
                                    'quantidade_total' => 0,
                                    'quantidade_inscritos' => 0,
                                    'quantidade_nao_inscritos' => 0,
                                    'quantidade_emails' => 0
                                ];
                            }

                            // Identificar o tipo de contagem baseado no array de origem
                            if (isset($usuario['quantidade'])) {
                                $usuarios_consolidados[$id]['quantidade_total'] += $usuario['quantidade'];

                                // Determinar o tipo (isso depende da estrutura dos seus arrays)
                                if (in_array($usuario, $GLOBALS['lista_get_quantidade_x_usuario_suporte_cand'] ?? [])) {
                                    $usuarios_consolidados[$id]['quantidade_inscritos'] += $usuario['quantidade'];
                                } elseif (in_array($usuario, $GLOBALS['lista_get_quantidade_x_usuario_suporte'] ?? [])) {
                                    $usuarios_consolidados[$id]['quantidade_nao_inscritos'] += $usuario['quantidade'];
                                } elseif (in_array($usuario, $GLOBALS['lista_get_quantidade_x_usuario_email'] ?? [])) {
                                    $usuarios_consolidados[$id]['quantidade_emails'] += $usuario['quantidade'];
                                }
                            }
                        }
                    }
                }

                // Consolidar todos os tipos
                consolidarUsuarios($lista_get_quantidade_x_usuario_suporte_cand, $usuarios_consolidados);
                consolidarUsuarios($lista_get_quantidade_x_usuario_suporte, $usuarios_consolidados);
                consolidarUsuarios($lista_get_quantidade_x_usuario_email, $usuarios_consolidados);

                // Converter para array indexado e ordenar por quantidade total
                $lista_consolidada = array_values($usuarios_consolidados);
                usort($lista_consolidada, function ($a, $b) {
                    return $b['quantidade_total'] - $a['quantidade_total'];
                });

                // Calcular totais
                $total_respostas = 0;
                $total_inscritos = 0;
                $total_nao_inscritos = 0;
                $total_emails = 0;

                foreach ($lista_consolidada as $usuario) {
                    $total_respostas += $usuario['quantidade_total'];
                    $total_inscritos += $usuario['quantidade_inscritos'];
                    $total_nao_inscritos += $usuario['quantidade_nao_inscritos'];
                    $total_emails += $usuario['quantidade_emails'];
                }

                $max_respostas = $lista_consolidada[0]['quantidade_total'] ?? 1;
                ?>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white mb-20">
                        <span class="mb-0">
                            <i class="fa fa-trophy me-2"></i> Desempenho da Equipe
                    </div>
                    <div class="card-body">
                        <!-- KPIs em Destaque -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="kpi-card text-center p-3">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-users fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="kpi-value text-primary mb-0"><?php echo count($lista_consolidada); ?></h3>
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
                                        <i class="fa fa-line-chart fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="kpi-value text-primary mb-0">
                                        <?php echo $total_respostas > 0 ? round($total_respostas / count($lista_consolidada), 1) : 0; ?>
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

                        <!-- Breakdown por Tipo -->
                        <div class="divider" style="height: 1px; background-color: #eee; margin: 20px 0;"></div>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="kpi-card text-center p-3 border-start border-4 border-primary">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-check fa-2x text-primary"></i>
                                    </div>
                                    <h4 class="kpi-value text-primary mb-0"><?php echo $total_inscritos; ?></h4>
                                    <p class="kpi-label text-muted mb-0">Candidatos Inscritos</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="kpi-card text-center p-3 border-start border-4 border-info">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-user-plus fa-2x text-primary"></i>
                                    </div>
                                    <h4 class="kpi-value text-primary mb-0"><?php echo $total_nao_inscritos; ?></h4>
                                    <p class="kpi-label text-muted mb-0">Não Inscritos</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="kpi-card text-center p-3 border-start border-4 border-success">
                                    <div class="kpi-icon mb-2">
                                        <i class="fa fa-envelope fa-2x text-success"></i>
                                    </div>
                                    <h4 class="kpi-value text-success mb-0"><?php echo $total_emails; ?></h4>
                                    <p class="kpi-label text-muted mb-0">Emails</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ranking Consolidado -->
                        <h5 class="section-title mb-20">
                            <i class="fa fa-star me-2"></i> Perfomance Individual
                        </h5>

                        <div class="performance-grid">
                            <?php
                            $rank = 1;
                            foreach ($lista_consolidada as $usuario) {
                                $foto = "user.jpg";
                                $get_foto = $conexao->get_foto_usuario($usuario['id']);
                                if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                    $foto = $get_foto[0]['nome'];
                                }

                                $nome_usuario = mb_strtoupper($usuario['posto_grad']) . " " . $usuario['nome_guerra'];
                                $percentual = $max_respostas > 0 ? ($usuario['quantidade_total'] / $max_respostas) * 100 : 0;
                                $percentual_total = $total_respostas > 0 ? ($usuario['quantidade_total'] / $total_respostas) * 100 : 0;

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
                                    $badge_class = 'bg-primary';
                                    $icon = '#' . $rank;
                                }
                            ?>

                                <div class="performance-card <?php echo $card_class; ?> p-20">
                                    <div class="perform-card-header p-10" style="font-size: 1.4rem;">
                                        <span class="mr-10 rank-badge <?php echo $badge_class; ?>">
                                            <?php echo $icon; ?>
                                        </span>
                                        <span class="responses-count"><?php echo $usuario['quantidade_total']; ?> respostas</span>
                                    </div>

                                    <div class="card-body">
                                        <div class="user-profile">
                                            <div class="avatar-container">
                                                <img class="performance-user-avatar" src="fotos/<?php echo $foto; ?>" alt="<?php echo $nome_usuario; ?>" onerror="this.src='imagens/user.jpg'">
                                                <div class="avatar-overlay">
                                                    <a href="usuario_visualiza.php?id_usuario=<?php echo $usuario['id']; ?>" class="view-profile-btn">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <h5 class="user-name"><?php echo $nome_usuario; ?></h5>
                                            <small class="user-id text-muted">ID: <?php echo $usuario['id']; ?></small>
                                        </div>

                                        <!-- Breakdown por tipo -->
                                        <div class="breakdown-stats mb-10">
                                            <div class="breakdown-item">
                                                <small class="text-primary">
                                                    <i class="fa fa-check me-1"></i>
                                                    <?php echo $usuario['quantidade_inscritos']; ?> Inscritos
                                                </small>
                                            </div>
                                            <div class="breakdown-item">
                                                <small class="text-primary">
                                                    <i class="fa fa-user-plus me-1"></i>
                                                    <?php echo $usuario['quantidade_nao_inscritos']; ?> Não inscritos
                                                </small>
                                            </div>
                                            <div class="breakdown-item">
                                                <small class="text-success">
                                                    <i class="fa fa-envelope me-1"></i>
                                                    <?php echo $usuario['quantidade_emails']; ?> Emails
                                                </small>
                                            </div>
                                        </div>

                                        <div class="performance-stats">
                                            <div class="stat-item">
                                                <span class="stat-label">Posição</span>
                                                <span class="stat-value"><?php echo $rank; ?>º</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">Percentual</span>
                                                <span class="stat-value"><?php echo round($percentual_total); ?>%</span>
                                            </div>
                                        </div>

                                        <!-- BARRA DE PROGRESSO RESTAURADA -->
                                        <div class="performance-bar mt-3">
                                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                                <div class="progress-bar 
                                        <?php
                                        if ($rank == 1) echo 'bg-gold';
                                        elseif ($rank == 2) echo 'bg-silver';
                                        elseif ($rank == 3) echo 'bg-bronze';
                                        else echo 'bg-primary';
                                        ?>"
                                                    style="width: <?php echo $percentual; ?>%;"
                                                    role="progressbar"
                                                    aria-valuenow="<?php echo $percentual; ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1 text-center">
                                                <?php echo round($percentual); ?>% do recorde
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <small class="text-muted">
                                            <i class="fa fa-tachometer-alt me-1"></i>
                                            <?php echo round($percentual_total); ?>% do total geral
                                        </small>
                                    </div>
                                </div>
                            <?php
                                $rank++;
                            }
                            ?>
                        </div>

                        <!-- Leaderboard em Lista -->
                        <div class="leaderboard mb-20 mt-4">
                            <h5 class="section-title mb-20">
                                <i class="fa fa-star me-2"></i> Leaderboard Detalhado
                            </h5>

                            <div class="leaderboard-list">
                                <?php
                                $rank = 1;
                                foreach ($lista_consolidada as $usuario) {
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($usuario['id']);
                                    if (count($get_foto) > 0 && !empty($get_foto[0]['nome'])) {
                                        $foto = $get_foto[0]['nome'];
                                    }

                                    $nome_usuario = mb_strtoupper($usuario['posto_grad']) . " " . $usuario['nome_guerra'];
                                    $percentual = $max_respostas > 0 ? ($usuario['quantidade_total'] / $max_respostas) * 100 : 0;

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
                                                <small class="text-muted">ID: <?php echo $usuario['id']; ?></small>
                                            </div>
                                        </div>

                                        <div class="user-stats">
                                            <div class="progress-container">
                                                <div class="progress-bar-container">
                                                    <!-- BARRA DE PROGRESSO NO LEADERBOARD -->
                                                    <div class="progress-bar" style="width: <?php echo $percentual; ?>%"></div>
                                                </div>
                                                <div class="stats-numbers">
                                                    <span class="responses-count" style="color: #006400;"><?php echo $usuario['quantidade_total']; ?> respostas</span>
                                                    <span class="percentage"><?php echo round($percentual); ?>%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="user-actions">
                                            <a href="usuario_visualiza.php?id_usuario=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver perfil">
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

                        <!-- Resumo Estatístico -->
                        <div class="stats-summary mt-4 p-20 bg-light rounded-3">
                            <h5 class="mb-3">
                                <i class="fa fa-chart-pie me-2"></i>Resumo Estatístico
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total de respostas:</span>
                                        <strong><?php echo $total_respostas; ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Maior performance:</span>
                                        <strong><?php echo $max_respostas; ?> respostas</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Performance média:</span>
                                        <strong><?php echo $total_respostas > 0 ? round($total_respostas / count($lista_consolidada), 1) : 0; ?> respostas</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total de colaboradores:</span>
                                        <strong><?php echo count($lista_consolidada); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Candidatos inscritos:</span>
                                        <strong><?php echo $total_inscritos; ?> (<?php echo $total_respostas > 0 ? round(($total_inscritos / $total_respostas) * 100, 1) : 0; ?>%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Não inscritos + Emails:</span>
                                        <strong><?php echo ($total_nao_inscritos + $total_emails); ?> (<?php echo $total_respostas > 0 ? round((($total_nao_inscritos + $total_emails) / $total_respostas) * 100, 1) : 0; ?>%)</strong>
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
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('attachmentsDropzone');
        const fileInput = document.getElementById('anexosInput');
        const preview = document.getElementById('attachmentsPreview');
        const maxFiles = 50;
        const maxSize = 10 * 1024 * 1024; // 10MB

        // Clique no dropzone
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        // Arrastar e soltar
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function() {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // Seleção de arquivos
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            const currentFiles = preview.querySelectorAll('.attachment-preview-item').length;

            if (currentFiles + files.length > maxFiles) {
                alert(`Você pode enviar no máximo ${maxFiles} arquivos.`);
                return;
            }

            for (let file of files) {
                if (file.size > maxSize) {
                    alert(`O arquivo "${file.name}" excede o tamanho máximo de 10MB.`);
                    continue;
                }

                addFilePreview(file);
            }

            updatePreviewVisibility();
        }

        function addFilePreview(file) {
            const item = document.createElement('div');
            item.className = 'attachment-preview-item';

            const size = formatFileSize(file.size);
            const icon = getFileIcon(file.name);

            item.innerHTML = `
            <div class="attachment-preview-info">
                <div class="attachment-preview-icon">
                    <i class="${icon}"></i>
                </div>
                <div class="attachment-preview-name">${file.name}</div>
                <div class="attachment-preview-size">${size}</div>
            </div>
            <button type="button" class="attachment-preview-remove">
                <i class="fa fa-times"></i>
            </button>
        `;

            // Remover arquivo
            item.querySelector('.attachment-preview-remove').addEventListener('click', function() {
                item.remove();
                updatePreviewVisibility();
            });

            preview.appendChild(item);
        }

        function updatePreviewVisibility() {
            if (preview.querySelectorAll('.attachment-preview-item').length > 0) {
                preview.classList.add('has-files');
            } else {
                preview.classList.remove('has-files');
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                'pdf': 'fa fa-file-pdf-o',
                'doc': 'fa fa-file-word-o',
                'docx': 'fa fa-file-word-o',
                'xls': 'fa fa-file-excel-o',
                'xlsx': 'fa fa-file-excel-o',
                'jpg': 'fa fa-file-image-o',
                'jpeg': 'fa fa-file-image-o',
                'png': 'fa fa-file-image-o',
                'gif': 'fa fa-file-image-o',
                'txt': 'fa fa-file-text-o',
                'zip': 'fa fa-file-archive-o',
                'rar': 'fa fa-file-archive-o'
            };
            return icons[ext] || 'fa fa-file-o';
        }
    });

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

    $('#tabela_emails').DataTable({
        "order": [
            [0, "desc"]
        ]
    });

    $(document).ready(function() {
        $('.select2').select2({
            allowClear: true,
            width: '100%',
            placeholder: "Selecione os Destinatários (Máximo 10)",
            maximumSelectionLength: 10,
            language: {
                maximumSelected: function(e) {
                    return 'Você só pode selecionar no máximo ' + e.maximum + ' itens.';
                }
            }
        });
    })
</script>
</body>

</html>
<?php $conexao = null; ?>