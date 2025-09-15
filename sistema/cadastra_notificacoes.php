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
            <div class="col-xl-6 col-md-7">
                <div class="card mb-20">
                    <div class="card-header mb-20" style="background-color: #006400; color: white;">
                        <h4>
                            <i class="fa fa-bell me-2"></i> Enviar Nova Notificação
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="../banco_dados/notificacao_cadastra.php">
                            <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

                            <div class="mb-10">
                                <label for="notificationEtapa" class="form-label">Etapa da Notificação</label>
                                <select class="form-control" id="notificationEtapa" name="etapa" required style="border-radius: 6px; padding: 10px 15px; border: 1px solid #D3D3D3;">
                                    <option value="">Selecione a etapa</option>
                                    <option value="0">Todas</option>
                                    <option value="1">Etapa 1</option>
                                    <option value="2">Etapa 2</option>
                                    <option value="3">Etapa 3</option>
                                    <option value="4">Etapa 4</option>
                                    <option value="5">Etapa 5</option>
                                    <option value="6">Etapa 6</option>
                                    <option value="7">Etapa 7</option>
                                </select>
                            </div>

                            <div class="mb-10">
                                <label for="notificationTitle" class="form-label">Título da Notificação</label>
                                <input type="text" class="form-control" id="notificationTitle" name="titulo"
                                    placeholder="Digite um título para a notificação" required
                                    style="border-radius: 6px; padding: 10px 15px; border: 1px solid #D3D3D3;">
                            </div>

                            <div class="mb-10">
                                <label for="notificationMessage" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="notificationMessage" name="mensagem" rows="5"
                                    placeholder="Digite a mensagem para os candidatos..." maxlength="1000" required
                                    style="border-radius: 6px; padding: 10px 15px; border: 1px solid #D3D3D3;"></textarea>
                                <div class="form-text text-end"><span id="charCount">0</span>/1000 caracteres</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-paper-plane me-2"></i> Enviar Notificação
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-5">
                <div class="card mb-20">
                    <div class="card-header mb-20" style="background-color: #006400; color: white;">
                        <h4>
                            <i class="fa fa-info-circle me-2"></i> Informações
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <strong>Atenção:</strong> Após o envio, a notificação não poderá ser editada, apenas excluída.
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Notificações enviadas
                                <span class="badge rounded-pill" style="background-color: #006400;"><?= count($notificacoes) ?></span>
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center mb-20" style="background-color: #006400; color: white;">
                        <h4>
                            <i class="fa fa-bullhorn me-2"></i> Notificações Enviadas
                        </h4>
                        <button class="btn btn-sm" id="toggleNotifications" style="background-color:#006400;">
                            <i class="fa fa-chevron-down" style="margin: 0;"></i>
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div id="notificationsList">
                            <?php if (empty($notificacoes)) : ?>
                                <div class="text-center py-5">
                                    <i class="fa fa-bullhorn fa-3x mb-10" style="color: #D3D3D3;"></i>
                                    <h4 class="text-muted">Nenhuma notificação enviada</h4>
                                    <p class="text-muted">As notificações enviadas aparecerão aqui.</p>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($notificacoes as $notificacao) : ?>
                                <div class="card mb-10 mx-3 mt-10">
                                    <div class="card-body">
                                        <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                            <div class="mb-10">
                                                <h4 class="card-title mb-10"><?= $notificacao['titulo'] ?></h4>
                                                <span class="badge notification-badge" style="background-color: #228B22;">
                                                    Etapa: <?= $notificacao['etapa'] == 0 ? 'TODAS' : $notificacao['etapa'] ?>
                                                </span>
                                            </div>
                                            <a
                                                class="btn-modern btn-delete"
                                                onclick="funcao_apagar('<?= $notificacao['id'] ?>', 'notificacao')">
                                                <i class="fa fa-trash"></i> Excluir
                                            </a>
                                        </div>

                                        <p class="card-text"><?= nl2br(htmlspecialchars($notificacao['mensagem'])) ?></p>

                                        <div class="d-flex justify-content-between align-items-center mt-10">
                                            <small class="text-muted">
                                                Enviado em: <?= trata_data_hora($notificacao['data_envio']) ?>
                                                por <?= $notificacao['posto_grad'] . ' - ' . $notificacao['nome_guerra'] ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            :root {
                --primary-color: #006400;
                /* Verde escuro como cor primária */
                --secondary-color: #228B22;
                /* Verde mar como cor de sucesso */
                --text-color: #333333;
                /* Cor do texto principal */
                --border-color: #D3D3D3;
                /* Cor das bordas */
            }

            .card {
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                border: 1px solid var(--border-color);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                background-color: var(--primary-color);
                color: white;
                border-radius: 10px 10px 0 0 !important;
                padding: 5px 20px;
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .card-header i {
                margin-right: 8px;
            }

            .form-label {
                font-weight: 500;
                margin-bottom: 5px;
                color: var(--text-color);
            }

            .btn-primary {
                background-color: var(--primary-color);
                border: none;
                padding: 10px 20px;
                font-weight: 600;
                transition: all 0.3s ease;
                width: 100%;
            }

            .btn-primary:hover {
                background-color: #004d00;
                /* Tom mais escuro do verde primário */
                transform: scale(1.01);
            }

            .alert {
                border-radius: 8px;
                margin-bottom: 15px;
                border: none;
            }


            .section-title {
                color: var(--primary-color);
                border-bottom: 2px solid var(--secondary-color);
                padding-bottom: 10px;
                margin: 30px 0 20px 0;
                font-weight: 700;
            }

            .form-control {
                border-radius: 6px;
                border: 1px solid var(--border-color);
            }

            .form-control:focus {
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .table-hover tbody tr:hover {
                background-color: rgba(34, 139, 34, 0.1);
            }


            .form-control,
            .form-select {
                border-radius: 6px;
                padding: 10px 15px;
                border: 1px solid #D3D3D3;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #228B22;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
            }

            .btn-primary {
                background-color: #006400;
                border: none;
                padding: 10px 20px;
                font-weight: 600;
            }

            .btn-primary:hover {
                background-color: #004d00;
            }

            .alert-info {
                background-color: #E8F5E9;
                border-color: #C8E6C9;
                color: #2E7D32;
            }

            .notification-badge {
                font-size: 1em;
                padding: 0.5em 0.8em;
            }
        </style>

        <script>
            // Contador de caracteres
            document.getElementById('notificationMessage').addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('charCount').textContent = charCount;
            });

            // Alternar visibilidade das notificações
            document.getElementById('toggleNotifications').addEventListener('click', function() {
                const notificationsList = document.getElementById('notificationsList');
                const icon = this.querySelector('i');

                if (notificationsList.style.display === 'none') {
                    notificationsList.style.display = 'block';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    notificationsList.style.display = 'none';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        </script>
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