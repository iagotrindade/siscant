<?php

include_once './menu_candidato.php';
include_once '../banco_dados/conexao.php';



$id_candidato = $_SESSION['id_candidato_cadastrado'];
$id_criptografado = $_GET['codigo'];


$conexao = new Conexao();

$resultado = $conexao->get_usuario_cpf($_SESSION['cpf_usuario_cadastrado']);

$cpf_criptografado = hash('sha256', $resultado[0]['cpf']);

$navegador = getBrowser();
$navegador = $navegador['platform'] . " - " . $navegador['name'] . " " . $navegador['version'] . "'";
$ip = $_SERVER['REMOTE_ADDR'];
$datetime = date('Y-m-d H:i:s');

$selecao_candidato = $conexao->get_selecao_id();
$ano_selecao = null;
$rm_selecao = null;
$codigo_selecao = null;
if (count($selecao_candidato) == 1) {
    $ano_selecao = $selecao_candidato[0]['ano'];
    $rm_selecao = $selecao_candidato[0]['rm'];
    $codigo_selecao = $selecao_candidato[0]['codigo'];
}

?>
<!-- 31/08/2025 - Iago Silva Pequenos ajustes e melhorias no Layout -->
 
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
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
        border-radius: 10px;
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

    .info-box {
        background: linear-gradient(135deg, #e8f5e8, #d8edd8);
        border-left: 4px solid var(--primary-color);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .urgent-alert {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .success-icon {
        color: var(--primary-color);
        font-size: 3rem;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
    }
</style>

<div class="main-container">
    <!-- Cabeçalho -->
    <div class="header-bar">
        <div class="header-container">
            <div>
                <h2 class="mb-1">SiSCanT - Sistema de Seleção de Candidatos Temporários</h2>
                <p class="mb-0 opacity-75">Cadastro realizado com sucesso</p>
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
            <!-- Card de Sucesso -->
            <div class="card mb-4 fade-in">
                <h5 class="section-header"><i class="fa fa-check-circle me-2"></i>Cadastro realizado com sucesso</h5>
                <div class="p-4">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="info-box">
                                <div class="success-icon">
                                    <i class="fa fa-child"></i>
                                </div>
                                <h4 class="text-success">Credenciais de Acesso</h4>
                                <div class="mb-3">
                                    <strong><i class="fa fa-user me-2"></i>Usuário:</strong> O seu CPF (apenas números)
                                </div>
                                <div>
                                    <strong><i class="fa fa-key me-2"></i>Senha:</strong> <?php echo $_SESSION['senha_cadastrada']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <?php
                            if ($selecao_candidato[0]['codigo'] != 'cet') {
                                if ($_SESSION['cadastro_candidato_email_enviado']) {
                                    echo '
                                    <div class="info-box text-center">
                                        <i class="fa fa-paper-plane success-icon"></i>
                                        <h5 class="text-success">E-mail enviado com sucesso!</h5>
                                        <p>Foi enviado um e-mail para: <br><strong>' . $resultado[0]['mail'] . '</strong></p>
                                        <img src="imagens/mail.png" width="100" alt="Ícone de e-mail" class="img-fluid mt-2">
                                    </div>';
                                } else {
                                    echo '
                                    <div class="urgent-alert">
                                        <div class="text-center mb-3">
                                            <i class="fa fa-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <h5 class="text-warning">Atenção!</h5>
                                        <p>Por algum motivo não foi enviado para o seu e-mail o lembrete da sua senha temporária!</p>
                                        <p>Por isso <strong>anote a senha <u>' . $_SESSION['senha_cadastrada'] . '</u></strong>, acesse o sistema e confirme o seu e-mail.</p>
                                        <p class="mb-0">Caso o seu e-mail esteja correto, não se preocupe! O e-mail que deveria ter sido enviado é APENAS um lembrete da sua senha temporária.</p>
                                    </div>';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Aviso importante -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="urgent-alert">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-exclamation-circle me-3" style="font-size: 2rem;"></i>
                                    <div>
                                        <h4 class="mb-2">Atenção: Ainda não terminou!</h4>
                                        <p class="mb-0">Agora você deve acessar o Sistema, <strong><u>cadastrar a(s) especialidade(s)</u></strong> desejada(s) e <strong><u>anexar os documentos</u></strong> exigidos pelo sistema.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botão de acesso -->
                    <div class="row mt-4">
                        <div class="col-lg-12 text-center">
                            <?php
                            $pagina_acesso_sistema = $_SESSION['nome_arquivo'];
                            ?>
                            <a href="../<?php echo $pagina_acesso_sistema ?>" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fa fa-sign-in-alt me-2"></i>Acessar o Sistema
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
</div>
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