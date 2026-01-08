<?php
include_once 'menu.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23948! Página não encontrada!");
    exit();
}

$foto_candidato = "user.jpg";
$foto_resposta = "user.jpg";

$id_suporte = $_GET['id_suporte'];
$get_suporte_candidato_id = $conexao->get_suporte_candidato_id($id_suporte);

$id_usuario_remetente = $get_suporte_candidato_id[0]['id_usuario_remetente'];
$id_usuario_respondeu = $get_suporte_candidato_id[0]['id_usuario_respondeu'];

$usuario_remetente = $conexao->get_usuario_id($id_usuario_remetente);
$usuario_resposta  = $conexao->get_usuario_id($id_usuario_respondeu);

$nome_candidato = $usuario_remetente[0]['nome_completo'];
$cpf_candidato = $usuario_remetente[0]['cpf'];
$mail_candidato = $usuario_remetente[0]['mail'];

if ($usuario_resposta != null) {
    $nome_resposta = $usuario_resposta[0]['posto_grad'] . " " . $usuario_resposta[0]['nome_guerra'];
    $cpf_resposta = $usuario_resposta[0]['cpf'];
}

$leu_resposta = $get_suporte_candidato_id[0]['leu_resposta'];
$data_enviado = $get_suporte_candidato_id[0]['data_enviado'];
$mensagem = $get_suporte_candidato_id[0]['mensagem'];
$motivo = $get_suporte_candidato_id[0]['motivo'];
$resposta_atual = $get_suporte_candidato_id[0]['resposta'];
$data_resposta = $get_suporte_candidato_id[0]['data_resposta'];
$respondida = $get_suporte_candidato_id[0]['respondida'];

if (empty($cpf_candidato)) {
    erro("Erro 5646! Suporte não encontrado!");
    exit();
}

$foto_candidato1 = $conexao->get_foto_usuario($id_usuario_remetente);
if ($foto_candidato1 != null)
    $foto_candidato = $foto_candidato1[0]['nome'];

if ($data_resposta != null)
    $data_resposta = trata_data_hora($data_resposta);
if ($data_enviado != null)
    $data_enviado = trata_data_hora($data_enviado);
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

    .main-message-content {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid var(--accent-color);
        line-height: 1.6;
        margin-top: 0.5rem;
        font-size: 16px;
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

    .chat-input button {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 0 2.5rem;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .chat-input button:hover {
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
            <h1>Informações do suporte <i class="fa fa-comments"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Suporte Candidato</li>
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
                        Solicitação de Suporte Atual
                    </div>
                    <span class="status-badge status-<?= $status ?>"><?= $resposta_atual  ? 'Respondido' : 'Pendente' ?></span>
                </div>
                <div class="card-body-modern">
                    <div class="support-request">
                        <div class="user-info-section">
                            <div class="user-avatar">
                                <a href="usuario_visualiza.php?id_usuario=<?php echo $id_usuario_remetente ?>">
                                    <img src="fotos/<?php echo $foto_candidato ?>" alt="<?php echo $nome_candidato ?>" class="user-image">
                                </a>
                            </div>
                            <div class="user-details">
                                <h4><?php echo $nome_candidato ?></h4>
                                <div class="user-meta">
                                    <span class="user-cpf">
                                        <i class="fa fa-id-card"></i>
                                        <a href="usuario_visualiza.php?id_usuario=<?php echo $id_usuario_remetente ?>">
                                            <?php echo mascara($cpf_candidato, '###.###.###-##') ?>
                                        </a>
                                    </span>
                                    <span class="user-email">
                                        <i class="fa fa-envelope"></i>
                                        <?php echo $mail_candidato ?>
                                    </span>
                                    <span class="request-date">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo $data_enviado ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="request-details">
                            <div class="detail-row">
                                <div class="detail-item">
                                    <span class="detail-label">
                                        <i class="fa fa-exclamation-circle"></i>
                                        Motivo:
                                    </span>
                                    <span class="detail-value"><?php echo $motivo ?></span>
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-item full-width">
                                    <span class="detail-label">
                                        <i class="fa fa-comment"></i>
                                        Mensagem:
                                    </span>
                                    <div class="message-value">
                                        <?php echo $mensagem ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        $lista_suporte = $conexao->get_suporte_candidato($id_usuario_remetente);
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
                                $foto_resposta1 = $conexao->get_foto_usuario($linha['id_usuario_respondeu']);
                                $foto_resposta = ($foto_resposta1 != null) ? $foto_resposta1[0]['nome'] : "user.jpg";

                                $data_envio    = $linha['data_enviado']   ? trata_data_hora($linha['data_enviado'])   : '-';
                                $mensagem      = $linha['mensagem']       ? nl2br(htmlspecialchars($linha['mensagem'])) : '-';
                                $resposta      = $linha['resposta']       ? nl2br(htmlspecialchars($linha['resposta'])) : '-';
                                $data_resposta = $linha['data_resposta']  ? trata_data_hora($linha['data_resposta'])  : '-';
                                $status        = $linha['data_resposta']  ? 'answered' : 'pending';
                                $status_text   = $linha['data_resposta']  ? 'Respondido' : 'Pendente';
                                ?>

                                <div class="message user-message <?= $status ?>">
                                    <div class="message-avatar">
                                        <a href="usuario_visualiza.php?id_usuario=<?= $id_usuario_remetente ?>">
                                            <img class="user-image" src="fotos/<?php echo $foto_candidato ?>" alt="Avatar do usuário">
                                        </a>
                                    </div>
                                    <div class="message-content">
                                        <div class="message-text"><?= $mensagem ?></div>
                                        <span class="message-time"><?= $data_enviado ?></span>
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

                    <form action="../banco_dados/suporte_candidato_resposta.php" method="POST" id="suporte">
                        <input type="hidden" value="<?= hash('sha256', $_SESSION['chave'] . 'freitas') ?>" name="crip">
                        <input hidden type="text" name='id_suporte' value="<?php echo $id_suporte ?>">

                        <?php if ($resposta_atual == null && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'ouvidor')): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                O candidato irá visualizar esta mensagem em sua área de Suporte.
                            </div>

                            <div class="chat-input">
                                <textarea
                                    placeholder="Digite sua mensagem..."
                                    autocomplete="off"
                                    name="resposta"
                                    autocorrect="on"
                                    rows="5">



Atenciosamente,
<?= $posto_grad . ' ' . $nome_guerra ?> - Comissão de Seleção Especial (CSE)</textarea>

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
</script>
</body>

</html>