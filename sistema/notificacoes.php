<?php
// 27/08/2025 -> Iago Silva Adicionado página de notificações para envio de mensagens aos candidatos
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (!isset($_SESSION))
    session_start();

$resultado_selecao = $conexao->get_selecao_id();
if (
    $resultado_selecao[0]['codigo'] == 'ott_stt'
    || $resultado_selecao[0]['codigo'] == 'mfdv'
    || $resultado_selecao[0]['codigo'] == 'cet'
    || $resultado_selecao[0]['codigo'] == 'ott'
    || $resultado_selecao[0]['codigo'] == 'stt'
) {
    $_SESSION['eipot'] = 0;
    unset($_SESSION['eipot']);
}


if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235332446!");
    exit();
}

$notificacoes = $conexao->get_notificacoes($_SESSION['selecao']);
?>

<style>
    .notification-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        transition: transform 0.2s;
    }

    .notification-card:hover {
        transform: translateY(-3px);
    }

    .notification-date {
        font-size: 1.1rem;
        color: #6c757d;
    }

    .btn-send {
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 6 00;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .btn-delete:hover {
        background-color: #bb2d3b;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .char-count {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: right;
    }

    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
</style>

<div class="">
    <div class="content-wrapper">
        <div class="page-title">
            <div>
                <h1>Envio de Notificações <i class="fa fa-bullhorn"></i></h1>
            </div>
            <div>
                <ul class="breadcrumb">
                    <li><i class="fa fa-home fa-lg"></i></li>
                    <li><a href="index.php">Página Inicial</a></li>
                    <li>Envio de Notificações</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="../banco_dados/notificacao_cadastra.php">
                            <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                            <div class="mb-3">
                                <label for="notificationTitle" class="form-label">Título da Notificação</label>
                                <input type="text" class="form-control" id="notificationTitle" name="titulo" placeholder="Digite um título para a notificação" required>
                            </div>
                            <div class="mb-3">
                                <label for="notificationMessage" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="notificationMessage" name="mensagem" rows="5" placeholder="Digite a mensagem para os candidatos..." maxlength="800" required></textarea>
                                <div class="char-count"><span id="charCount">0</span>/800 caracteres</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-send">
                                <i class="fa fa-paper-plane me-2"></i> Enviar Notificação
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="">Informações</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Atenção:</strong> Após o envio, a notificação não poderá ser editada, apenas excluída.
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Notificações enviadas
                                <span class="badge bg-primary rounded-pill" id="notificationCount"><?= count($notificacoes) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Último envio
                                <span class="text-muted" id="lastSent"><?= !empty($notificacoes) ? trata_data_hora(end($notificacoes)['data_envio']) : 'Nenhum' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white" style="display: flex; justify-content: space-between; align-items: center;">
                        <h5 class="">Notificações Enviadas</h5>
                        <button class="btn btn-sm btn-outline-secondary mb-20" id="toggleNotifications">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="notificationsList">
                            <!-- As notificações serão carregadas aqui -->
                            <?php if (empty($notificacoes)) : ?>
                                <div class="empty-state">
                                    <i class="fa fa-bullhorn"></i>
                                    <h4>Nenhuma notificação enviada</h4>
                                    <p>As notificações enviadas aparecerão aqui.</p>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($notificacoes as $notificacao) : ?>
                                <div class="notification-card card mb-20">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start" style="display: flex; align-items: center; justify-content: space-between;">
                                            <h5 class="card-title"><?= $notificacao['titulo'] ?></h5>
                                            <button class="btn btn-md btn-delete" onclick="funcao_apagar('<?= $notificacao['id'] ?>', 'notificacao')"> <i class="fa fa-trash"></i> </button>
                                        </div>
                                        <p class="card-text"><?= $notificacao['mensagem'] ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="notification-date">Enviado em: <?= trata_data_hora($notificacao['data_envio']) ?> por <?= $notificacao['posto_grad'] . ' - ' . $notificacao['nome_guerra'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos do DOM
        const notificationMessage = document.getElementById('notificationMessage');
        const charCount = document.getElementById('charCount');
        const notificationsList = document.getElementById('notificationsList');
        const toggleNotifications = document.getElementById('toggleNotifications');

        // Contador de caracteres
        notificationMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Alternar visualização da lista de notificações
        toggleNotifications.addEventListener('click', function() {
            const notificationsCardBody = notificationsList.parentElement;
            if (notificationsCardBody.style.display === 'none') {
                notificationsCardBody.style.display = 'block';
                toggleNotifications.innerHTML = '<i class="fa fa-chevron-down"></i>';
            } else {
                notificationsCardBody.style.display = 'none';
                toggleNotifications.innerHTML = '<i class="fa fa-chevron-up"></i>';
            }
        });
    });
</script>

</div>
<script src="sistema/js/bootstrap5.3.3.js"></script>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>

</body>

</html>
<?php
$conexao = null;
?>