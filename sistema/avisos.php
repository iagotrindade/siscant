<?php
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

?>

<style>
    .page-title {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

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
        font-size: 0.85rem;
        color: #6c757d;
    }

    .btn-send {
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
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
                <p class="text-muted">Envie avisos importantes para os candidatos</p>
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
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Nova Notificação</h5>
                    </div>
                    <div class="card-body">
                        <form id="notificationForm">
                            <div class="mb-3">
                                <label for="notificationTitle" class="form-label">Título da Notificação</label>
                                <input type="text" class="form-control" id="notificationTitle" placeholder="Digite um título para a notificação" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="notificationMessage" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="notificationMessage" rows="5" placeholder="Digite a mensagem para os candidatos..." maxlength="500" required></textarea>
                                <div class="char-count"><span id="charCount">0</span>/500 caracteres</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-send">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Notificação
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informações</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Atenção:</strong> Após o envio, a notificação não poderá ser editada, apenas excluída.
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Notificações enviadas
                                <span class="badge bg-primary rounded-pill" id="notificationCount">0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Último envio
                                <span class="text-muted" id="lastSent">Nenhum</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notificações Enviadas</h5>
                        <button class="btn btn-sm btn-outline-secondary" id="toggleNotifications">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="notificationsList">
                            <!-- As notificações serão carregadas aqui -->
                            <div class="empty-state">
                                <i class="fas fa-bullhorn"></i>
                                <h4>Nenhuma notificação enviada</h4>
                                <p>As notificações enviadas aparecerão aqui.</p>
                            </div>
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
        const notificationForm = document.getElementById('notificationForm');
        const notificationTitle = document.getElementById('notificationTitle');
        const notificationMessage = document.getElementById('notificationMessage');
        const charCount = document.getElementById('charCount');
        const notificationsList = document.getElementById('notificationsList');
        const notificationCount = document.getElementById('notificationCount');
        const lastSent = document.getElementById('lastSent');
        const successToast = new bootstrap.Toast(document.getElementById('successToast'));
        const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
        const toggleNotifications = document.getElementById('toggleNotifications');

        // Contador de caracteres
        notificationMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Carregar notificações salvas
        loadNotifications();

        // Envio do formulário
        notificationForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (notificationTitle.value.trim() === '' || notificationMessage.value.trim() === '') {
                return;
            }

            // Criar notificação
            const notification = {
                id: Date.now(),
                title: notificationTitle.value.trim(),
                message: notificationMessage.value.trim(),
                date: new Date().toLocaleString('pt-BR'),
                sent: true
            };

            // Salvar notificação
            saveNotification(notification);

            // Limpar formulário
            notificationForm.reset();
            charCount.textContent = '0';

            // Feedback visual
            successToast.show();

            // Recarregar a lista
            loadNotifications();
        });

        // Alternar visualização da lista de notificações
        toggleNotifications.addEventListener('click', function() {
            const notificationsCardBody = notificationsList.parentElement;
            if (notificationsCardBody.style.display === 'none') {
                notificationsCardBody.style.display = 'block';
                toggleNotifications.innerHTML = '<i class="fas fa-chevron-down"></i>';
            } else {
                notificationsCardBody.style.display = 'none';
                toggleNotifications.innerHTML = '<i class="fas fa-chevron-up"></i>';
            }
        });

        // Função para carregar notificações
        function loadNotifications() {
            const notifications = getSavedNotifications();

            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-bullhorn"></i>
                            <h4>Nenhuma notificação enviada</h4>
                            <p>As notificações enviadas aparecerão aqui.</p>
                        </div>
                    `;
                notificationCount.textContent = '0';
                lastSent.textContent = 'Nenhum';
                return;
            }

            // Ordenar por data (mais recente primeiro)
            notifications.sort((a, b) => b.id - a.id);

            // Atualizar contadores
            notificationCount.textContent = notifications.length;
            lastSent.textContent = notifications[0].date;

            // Gerar HTML das notificações
            let html = '';
            notifications.forEach(notification => {
                html += `
                        <div class="notification-card card mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title">${notification.title}</h5>
                                    <button class="btn btn-sm btn-delete" data-id="${notification.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <p class="card-text">${notification.message}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="notification-date">Enviado em: ${notification.date}</small>
                                    <span class="badge bg-success">Enviado</span>
                                </div>
                            </div>
                        </div>
                    `;
            });

            notificationsList.innerHTML = html;

            // Adicionar event listeners para os botões de exclusão
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    deleteNotification(id);
                });
            });
        }

        // Função para obter notificações salvas
        function getSavedNotifications() {
            const notificationsJSON = localStorage.getItem('candidateNotifications');
            return notificationsJSON ? JSON.parse(notificationsJSON) : [];
        }

        // Função para salvar notificação
        function saveNotification(notification) {
            const notifications = getSavedNotifications();
            notifications.push(notification);
            localStorage.setItem('candidateNotifications', JSON.stringify(notifications));
        }

        // Função para excluir notificação
        function deleteNotification(id) {
            if (confirm('Tem certeza que deseja excluir esta notificação?')) {
                let notifications = getSavedNotifications();
                notifications = notifications.filter(notification => notification.id !== id);
                localStorage.setItem('candidateNotifications', JSON.stringify(notifications));
                loadNotifications();
            }
        }
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