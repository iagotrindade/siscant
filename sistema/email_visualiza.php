<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'ouvidor') {
    erro("Erro 544654: Página não encontrada");
    exit();
}

$email_id = null;
$id_email = $_GET['id_email'];
$criptografia = $_GET['criptografia'];

if ($_GET['criptografia'] != hash('sha256', $id_email)) {
    erro("Erro 345345! Página não encontrada!");
    exit();
}

$get_email_id = $conexao->get_email_id($id_email);

$nome_completo = null;
$mail = null;
$mensagem = null;
$data_enviado = null;
$resposta = null;
$data_resposta = null;
$id_usuario_resposta = null;

$cpf_reposta = null;
$nome_reposta = null;

if ($get_email_id != null) {
    $mensagem = $get_email_id['mensagem'];
    $data_enviado = $get_email_id['data_criacao'];
    $resposta_atual = $get_email_id['resposta'];
    $data_resposta = $get_email_id['data_resposta'];
    $id_usuario_resposta = $get_email_id['id_usuario_respondeu'];

    $foto_resposta = "user.jpg";
    $foto_resposta1 = $conexao->get_foto_usuario($id_usuario_resposta);
    if ($foto_resposta1 != null)
        $foto_resposta = $foto_resposta1[0]['nome'];

    $usuario_resposta = $conexao->get_usuario_id($id_usuario_resposta);
    if ($usuario_resposta != null) {
        $cpf_reposta = $usuario_resposta[0]['cpf'];
        $nome_reposta = $usuario_resposta[0]['posto_grad'];
        $nome_reposta = $nome_reposta . " " . $usuario_resposta[0]['nome_guerra'];
    }
}

if ($data_resposta != null)
    $data_resposta = trata_data_hora($data_resposta);
if ($data_enviado != null)
    $data_enviado = trata_data_hora($data_enviado);

$dados_usuario = $conexao->get_usuario_email(trim($get_email_id['email_remetente']));

?>

<style>
    :root {
        --primary-color: #006400;
        --primary-light: #4c8c4a;
        --primary-lighter: #80c97f;
        --primary-soft: #e6f4ea;
        --user-message-bg: #f0f8ff;
        --user-message-border: #d1e7ff;
        --bot-message-bg: #f8f9f4;
        --bot-message-border: #e2e8d8;
        --text-dark: #2d3748;
        --text-medium: #4a5568;
        --text-light: #718096;
    }

    .support-request {
        padding: 0.5rem;
    }

    .user-info-section {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .user-avatar {
        flex-shrink: 0;
        margin-right: 1.5rem;
    }

    .user-details h4 {
        margin: 0 0 0.5rem 0;
        color: var(--primary-color);
        font-weight: 600;
    }

    .user-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .user-meta span {
        display: inline-flex;
        align-items: center;
        font-size: 1.5rem;
        color: #6c757d;
    }

    .user-meta i {
        margin-right: 0.4rem;
        color: var(--primary-color);
    }

    .user-meta a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .user-meta a:hover {
        color: var(--primary-color);
    }

    .request-details {
        margin-top: 1.5rem;
    }

    .detail-row {
        display: flex;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .detail-item {
        flex: 1;
        min-width: 250px;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }

    .detail-item.full-width {
        flex: 0 0 100%;
        margin-right: 0;
    }

    .detail-label {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.4rem;
    }

    .detail-label i {
        margin-right: 0.5rem;
    }

    .detail-value {
        display: block;
        padding: 0.6rem 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid var(--primary-color);
        font-size: 16px;
    }

    .message-value {
        display: block;
        padding: 0.6rem 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid var(--primary-color);
        font-size: 16px;
    }

    .message-content {
        max-width: 85%;
    }

    .main-message-content {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid var(--accent-color);
        line-height: 1.6;
        margin-top: 0.5rem;
        font-size: 16px;
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
        align-items: center;
        overflow: hidden;
        text-overflow: ellipsis;
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
        padding: 30px;
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
        margin-bottom: 10px;
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
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Informações do suporte inicial <i class="fa fa-file-text-o"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Cadastro de arquivo obrigatório</li>
            </ul>
        </div>
    </div>

    <?php
    $status = $resposta_atual  ? 'answered' : 'pending';
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card-modern">
                <div class="card-header-modern">
                    <div>
                        <i class="fa fa-comments me-2"></i>
                        <?= empty($get_email_id['assunto']) ? 'Sem assunto' : $get_email_id['assunto'] ?>
                    </div>
                    <span class="status-badge status-<?= $status ?>"><?= $resposta_atual  ? 'Respondido' : 'Pendente' ?></span>
                </div>
                <div class="card-body-modern">
                    <div class="support-request">
                        <a href="usuario_visualiza.php?id_usuario=<?= $dados_usuario[0]['id'] ?>">
                            <div class="user-info-section">
                                <div class="user-avatar">

                                    <img src="./imagens/user.jpg" alt="<?php echo $get_email_id['remetente'] ?>" class="user-image">

                                </div>
                                <div class="user-details">
                                    <h4><?= $get_email_id['remetente'] ?></h4>
                                    <div class="user-meta">
                                        <span class="user-email">
                                            <i class="fa fa-envelope"></i>
                                            <?= $get_email_id['email_remetente'] ?>
                                        </span>
                                        <span class="request-date">
                                            <i class="fa fa-calendar"></i>
                                            <?= trata_data_hora($get_email_id['data_criacao']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <div class="divider"></div>

                        <div class="request-details">
                            <div class="detail-row">
                                <div class="detail-item full-width">
                                    <span class="detail-label">
                                        <i class="fa fa-comment"></i>
                                        Mensagem:
                                    </span>
                                    <div class="message-value">
                                        <?= empty($mensagem) ? 'Sem mensagem' : nl2br(htmlspecialchars($mensagem)) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($get_email_id['arquivos'])): ?>
                            <div class="request-details">
                                <div class="detail-row">
                                    <div class="detail-item full-width">
                                        <span class="detail-label">
                                            <i class="fa fa-file"></i>
                                            Anexos:
                                        </span>
                                        <div class="message-value">
                                            <?php foreach ($get_email_id['arquivos'] as $arquivo): ?>
                                                <a href="./arquivos/arquivos_email/<?php echo htmlspecialchars($arquivo['nome_arquivo']); ?>"
                                                    target="_blank"
                                                    class="arquivo-item" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <i class="fa fa-file"></i>
                                                    <?php echo htmlspecialchars($arquivo['nome_arquivo']); ?>
                                                </a>
                                                <br>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card-modern">
                <div class="card-header-modern">
                    <div>
                        <i class="fa fa-comments me-2"></i> Mensagens com o Candidato
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="filter-buttons">
                        <button class="btn btn-primary filter-btn active" data-filter="all">Todas</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="answered">Respondidas</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="pending">Pendentes</button>
                    </div>

                    <div class="chat-container" id="chatContainerSupport">
                        <?php
                        $lista_suporte = $conexao->get_emails_candidato($get_email_id['email_remetente']);
                        ?>

                        <?php if (empty($lista_suporte)): ?>
                            <div class="empty-state">
                                <i class="far fa-comment-alt"></i>
                                <h4>Nenhuma mensagem encontrada</h4>
                                <p>Você ainda não enviou nenhuma mensagem.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($lista_suporte as $index => $linha): ?>
                                <?php
                                $data_envio    = $linha['data_criacao']   ? trata_data_hora($linha['data_criacao'])   : '-';
                                $mensagem      = $linha['mensagem']       ? nl2br(htmlspecialchars($linha['mensagem'])) : '-';
                                $resposta      = $linha['resposta']       ? nl2br(htmlspecialchars($linha['resposta'])) : '-';
                                $data_resposta = $linha['data_resposta']  ? trata_data_hora($linha['data_resposta'])  : '-';
                                $status        = $linha['data_resposta']  ? 'answered' : 'pending';
                                $status_text   = $linha['data_resposta']  ? 'Respondido' : 'Pendente';
                                ?>

                                <div class="message user-message <?= $status ?>">
                                    <div class="message-avatar">
                                        <img class="user-image" src="./imagens/user.jpg" alt="Avatar do usuário">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-text"><?= $mensagem ?? 'Sem mensagem' ?></div>

                                        <!-- Área de Anexos da Mensagem do Usuário -->
                                        <?php if (!empty($linha['arquivos']) && is_array($linha['arquivos'])): ?>
                                            <div class="attachments-container">
                                                <div class="attachments-title">
                                                    <i class="fa fa-paperclip"></i>
                                                    Anexos:
                                                </div>
                                                <div class="attachments-list">
                                                    <?php foreach ($linha['arquivos'] as $arquivo): ?>
                                                        <div class="attachment-item">
                                                            <a href="./arquivos/arquivos_email/<?= htmlspecialchars($arquivo['nome_arquivo']) ?>"
                                                                target="_blank"
                                                                class="attachment-link">
                                                                <i class="fa fa-file"></i>
                                                                <?= htmlspecialchars($arquivo['nome_arquivo']) ?>
                                                            </a>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <span class="message-time"><?= $data_envio ?? date('d/m/Y') ?></span>
                                    </div>
                                </div>

                                <?php if ($resposta && $resposta !== '-'): ?>
                                    <div class="message bot-message <?= $status ?>">
                                        <div class="message-avatar">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id_usuario_respondeu'] ?>">
                                                <img class="user-image" src="fotos/<?php echo $foto_resposta ?>" alt="Avatar do usuário">
                                            </a>
                                        </div>
                                        <div class="message-content">
                                            <div class="message-text"><?= $resposta ?></div>

                                            <!-- Área de Anexos da Resposta -->
                                            <?php if (!empty($linha['arquivos_resposta']) && is_array($linha['arquivos_resposta'])): ?>
                                                <div class="attachments-container">
                                                    <div class="attachments-title">
                                                        <i class="fa fa-paperclip"></i>
                                                        Anexos da resposta:
                                                    </div>
                                                    <div class="attachments-list">
                                                        <?php foreach ($linha['arquivos_resposta'] as $arquivo): ?>
                                                            <div class="attachment-item">
                                                                <a href="./arquivos/arquivos_email/<?= htmlspecialchars($arquivo['nome_arquivo']) ?>"
                                                                    target="_blank"
                                                                    class="attachment-link">
                                                                    <i class="fa fa-file"></i>
                                                                    <?= htmlspecialchars($arquivo['nome_arquivo']) ?>
                                                                </a>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <span class="message-time"><?= $data_resposta ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="status-badge status-<?= $status ?>"><?= $status_text ?></span>
                                    </div>
                                </div>

                                <?php if ($index < count($lista_suporte) - 1): ?>
                                    <div class="divider"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form action="../banco_dados/responder_email.php" method="POST" id="suporte" enctype="multipart/form-data">
                        <input hidden type="text" name='criptografia' value="<?= $criptografia ?>">
                        <input hidden type="text" name='id_email' value="<?= $id_email ?>">

                        <?php if ($resposta_atual == null && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'ouvidor')): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                A resposta será enviada por e-mail.
                            </div>

                            <div class="chat-input">
                                <input type="text"
                                    placeholder="Digite sua mensagem..."
                                    autocomplete="off"
                                    name="assunto"
                                    value="Resposta - <?= $get_email_id['assunto'] ?>">
                                <button><i class="bi bi-flag"></i></button>
                            </div>

                            <div class="chat-input">
                                <textarea
                                    placeholder="Digite sua mensagem..."
                                    autocomplete="off"
                                    name="resposta"
                                    autocorrect="on"
                                    rows="5">



Atenciosamente,
<?= $posto_grad . ' ' . $nome_guerra?> - Comissão de Seleção Especial (CSE)</textarea>
                            </div>

                            <!-- Área de Anexos da Resposta -->
                            <div class="attachments-upload-area">
                                <div class="attachments-header">
                                    <i class="fa fa-paperclip"></i>
                                    <span>Anexos da Resposta</span>
                                    <small>(Opcional)</small>
                                </div>

                                <div class="attachments-dropzone" id="attachmentsDropzone">
                                    <div class="dropzone-content">
                                        <i class="fa fa-cloud-upload"></i>
                                        <p>Arraste arquivos aqui ou clique para selecionar</p>
                                        <small>Formatos permitidos: PDF, Word, Excel, Imagens, etc.</small>
                                    </div>
                                    <input type="file"
                                        name="anexos_resposta[]"
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
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const messages = document.querySelectorAll('#chatContainerSupport .message');

    function scrollToBottom() {
        const chatContainer = document.querySelector('#chatContainerSupport');
        if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    scrollToBottom();
    setTimeout(scrollToBottom, 100);

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            button.classList.add('active', 'btn-primary');
            button.classList.remove('btn-outline-primary');

            messages.forEach(msg => {
                if (filter === 'all') {
                    msg.style.display = '';
                } else {
                    msg.style.display = msg.classList.contains(filter) ? '' : 'none';
                }
            });

            document.querySelectorAll('#chatContainerSupport .status-badge, #chatContainerSupport .divider')
                .forEach(el => {
                    if (filter === 'all') {
                        el.style.display = '';
                    } else {
                        const parent = el.closest('.d-flex, .divider');
                        const relatedMsg = parent?.previousElementSibling;
                        if (relatedMsg && relatedMsg.classList.contains(filter)) {
                            el.style.display = '';
                        } else {
                            el.style.display = 'none';
                        }
                    }
                });
        });
    });

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
</script>
</body>

</html>