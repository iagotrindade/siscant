<?php
include_once './menu_candidato.php';
$cpf = $_SESSION['suporte_inicial'];

if ($cpf == null || $cpf == '') {
    header("Location: index.php?erro=512474");
    exit();
}

if ($_GET['c'] != hash('sha256', $cpf)) {
    header("Location: index.php?erro=512157");
    exit();
}
?>

<!-- 06/07/2025 - Iago Silva Pequenos ajustes e melhorias no Layout -->
<style>
    :root {
        --primary-color: #006400;
        --primary-light: #228B22;
        --secondary-color: #6c757d;
        --accent-color: #32CD32;
        --light-bg: #f0f8f0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        background-color: var(--light-bg);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-x: hidden;
    }

    .main-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 0;
    }

    .header-bar {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .content-wrapper {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 20px;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .section-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
        margin: 0;
    }

    .alert-success {
        background-color: #e8f5e8;
        border-color: #c3e6c3;
        color: #2d5a2d;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 100, 0, 0.3);
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .logo-placeholder img {
        height: 80px;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        color: var(--primary-color);
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .confirmation-box {
        background: linear-gradient(135deg, #e8f5e8, #d8edd8);
        border-left: 4px solid var(--primary-color);
        border-radius: 10px;
        padding: 30px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .success-icon {
            font-size: 3rem;
        }
    }

    .celebrate-animation {
        animation: celebrate 1s ease-in-out;
    }

    @keyframes celebrate {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

<div class="main-container">
    <!-- Cabeçalho -->
    <div class="header-bar">
        <div class="header-container">
            <div>
                <h2 class="mb-1">SiSCanT - Sistema de Seleção de Candidatos Temporários</h2>
                <p class="mb-0 opacity-75">Confirmação de envio de suporte</p>
            </div>
            <div class="text-md-end">
                <a href="../index.php" class="d-inline-block">
                    <div class="logo-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <img src="imagens/brasao_eb.png" alt="">
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Card de Confirmação -->
            <div class="card mb-4 fade-in">
                <h5 class="section-header"><i class="fa fa-check-circle me-2"></i>Suporte enviado com sucesso</h5>
                <div class="p-5 text-center">
                    <div class="success-icon celebrate-animation">
                        <i class="fa fa-comments"></i>
                    </div>

                    <div class="confirmation-box mb-5">
                        <h3 class="text-success mb-3">Sua mensagem foi enviada para a Comissão de Seleção!</h3>
                        <p class="lead mb-0">Em breve estaremos respondendo para o seu E-Mail.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <a href="../<?=$_SESSION['nome_arquivo']?>" class="btn btn-primary btn-lg">
                                <i class="fa fa-arrow-left me-2"></i>Voltar para a Página Inicial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 15px 0; text-align: center; margin-top: auto;">
        <div class="container">
            <p class="mb-0">SiSCanT - Sistema de Seleção de Candidatos Temporários © <?php echo date("Y"); ?></p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efeito de comemoração para o ícone de sucesso
        const successIcon = document.querySelector('.success-icon');
        if (successIcon) {
            setTimeout(() => {
                successIcon.classList.add('celebrate-animation');
            }, 500);
        }

        // Efeito de digitação para a mensagem de sucesso
        const confirmationText = document.querySelector('.confirmation-box');
        if (confirmationText) {
            confirmationText.style.opacity = '0';
            setTimeout(() => {
                confirmationText.style.transition = 'opacity 1s ease-in';
                confirmationText.style.opacity = '1';
            }, 300);
        }
    });
</script>
<!-- Javascripts-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/pace.min.js"></script>
<script src="js/main.js"></script>
<script type="text/javascript">
    $('body').removeClass("sidebar-mini").addClass("sidebar-collapse");
</script>
</body>

</html>