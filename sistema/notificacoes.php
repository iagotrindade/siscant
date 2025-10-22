<?php
// 27/08/2025 -> Iago Silva Adicionado página de notificações para os candidatos
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$conexao = new Conexao();
?>

<style>
    :root {
        --primary-color: #006400;
        --secondary-color: #004d00;
        --light-bg: #f8f9fc;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .notification-card {
        border: none;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        margin-bottom: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        background: linear-gradient(to bottom, #ffffff, #f8f9fc);
        overflow: hidden;
    }

    .notification-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }

    .notification-card.new {
        border-left: 4px solid var(--primary-color);
    }

    .notification-header {
        background: linear-gradient(to right, #f8f9fc, #ffffff);
        padding: 15px 20px;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-title {
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        font-size: 1.7rem;
    }

    .notification-date {
        font-size: 1.2rem;
        color: #858796;
        background-color: #eaecf4;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .notification-body {
        padding: 20px;
    }

    .notification-content {
        font-size: 1.4rem;
        line-height: 1.6;
        color: #4a4b4f;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--primary-color);
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--primary-color);
    }

    .filter-buttons {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .filter-btn:hover {
        background: var(--primary-color);
        color: #ffffff;
        border-color: var(--primary-color);
    }

    .filter-btn.active {
        color: #ffffff;
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .badge-new {
        background-color: var(--primary-color);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        margin-left: 8px;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        padding: 15px 20px;
        border-top: 1px solid #e3e6f0;
        background-color: #f8f9fc;
    }

    @media (max-width: 768px) {
        .filter-buttons {
            flex-direction: column;
        }

        .notification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .notification-date {
            align-self: flex-start;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Notificações da Seleção <i class="fa fa-bell"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="filter-buttons">
                <button class="btn btn-outline-primary filter-btn active" data-filter="all">
                    Todas as Notificações
                </button>
                <button class="btn btn-outline-primary filter-btn" data-filter="new">
                    Recentes <span class="badge-new" id="newCount">
                        <?php
                        $newCount = 0;
                        foreach ($notificacoes as $notificacao) {
                            if (strtotime($notificacao['data_envio']) >= strtotime('-5 days')) {
                                $newCount++;
                            }
                        }
                        echo $newCount;
                        ?>
                    </span>
                </button>
                <button class="btn btn-outline-primary filter-btn" data-filter="old">
                    Antigas
                </button>
            </div>

            <div id="notificationsContainer">
                <!-- Notificações serão carregadas aqui -->
                <?php if (empty($notificacoes)) : ?>
                    <div class="empty-state">
                        <i class="fa fa-bell-slash"></i>
                        <h3>Nenhuma notificação disponível</h3>
                        <p>Quando houver novas notificações, elas aparecerão aqui.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($notificacoes as $notificacao) : ?>
                    <div class="notification-card <?php //Verificar se $data_envio é maior que 7 dias
                                                    if (strtotime($notificacao['data_envio']) < strtotime('-5 days')) {
                                                        echo 'old';
                                                    } else {
                                                        echo 'new';
                                                    }
                                                    ?>">
                        <div class="notification-header">
                            <h3 class="notification-title"><?= $notificacao['titulo'] ?></h3>
                            <span class="notification-date"><?= trata_data_hora($notificacao['data_envio']) ?></span>
                        </div>
                        <div class="notification-body">
                            <p class="notification-content"><?= nl2br(htmlspecialchars($notificacao['mensagem'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // Filtrar notificações
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const notificationsContainer = document.getElementById('notificationsContainer');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const filter = this.getAttribute('data-filter');
                filterNotifications(filter);
            });
        });

        function filterNotifications(filter) {
            const notifications = notificationsContainer.querySelectorAll('.notification-card');
            notifications.forEach(notification => {
                if (filter === 'all') {
                    notification.style.display = 'block';
                } else if (filter === 'new' && notification.classList.contains('new')) {
                    notification.style.display = 'block';
                } else if (filter === 'old' && !notification.classList.contains('new')) {
                    notification.style.display = 'block';
                } else {
                    notification.style.display = 'none';
                }
            });
        }
    });
</script>
</body>

</html>
<?php $conexao = null; ?>