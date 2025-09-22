<?php
// Removido o exit da função para que o código continue executando
include_once './menu_candidato.php';
include_once './codigos/suporte_inicial_valida.php';
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

    .form-section {
        padding: 25px;
        background-color: white;
        border-radius: 0 0 10px 10px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #444;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ddd;
        transition: var(--transition);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 100, 0, 0.15);
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

    .btn-default {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-default:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        background: linear-gradient(135deg, #5a6268, #6c757d);
        color: white;
    }

    .alert-info {
        background-color: #e8f4e8;
        border: 1px solid #c0e0c0;
        color: #0c540c;
        border-radius: 10px;
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

    .required-field::after {
        content: " *";
        color: #dc3545;
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

    .floating-label {
        position: relative;
        margin-bottom: 20px;
    }

    .floating-input {
        padding: 18px 15px 8px 15px;
        height: 60px;
    }

    .floating-label label {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        transition: var(--transition);
        pointer-events: none;
        color: #6c757d;
        background-color: white;
        padding: 0 5px;
    }

    .floating-input:focus+label,
    .floating-input:not(:placeholder-shown)+label {
        top: 0;
        transform: translateY(-50%) scale(0.85);
        color: var(--primary-color);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .support-icon {
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }
</style>

<div class="main-container">
    <!-- Cabeçalho -->
    <div class="header-bar">
        <div class="header-container">
            <div>
                <h2 class="mb-1">Suporte <i class="fa fa-comments"></i></h2>
                <p class="mb-0 opacity-75">Sistema de Seleção de Candidatos Temporários</p>
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
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="alert alert-info">
                        <h5 class="mb-2"><i class="fa fa-info-circle me-2"></i>Preencha os campos para solicitar suporte à Comissão de Seleção</h5>
                        <p class="mb-0">Todos os campos são obrigatórios</p>
                    </div>
                </div>
            </div>

            <form action="../banco_dados/suporte_inicial_cadastra.php" method="post" onsubmit="return suporte_inicial_valida()" class="needs-validation" novalidate>
                <div class="card mb-4 fade-in">
                    <h5 class="section-header"><i class="fa fa-envelope me-2"></i>Formulário de Suporte</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-12 mb-4 text-center">
                                <div class="support-icon">
                                    <i class="fa fa-headset"></i>
                                </div>
                                <h4 class="text-success">Preencha os campos para enviar uma mensagem para a administração</h4>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <label class="form-label required-field">Motivo da mensagem</label>
                                <select id="motivo" name="motivo" class="form-select" required>
                                    <option value="" selected disabled>Selecione a opção</option>
                                    <option value="nao_consigo_me_cadastrar_sistema">Não estou conseguindo me cadastrar no sistema</option>
                                    <option value="duvida_preenchimento_campo">Estou em dúvida no preenchimento de um campo</option>
                                    <option value="nao_consigo_fazer_login">Não estou conseguindo fazer o login com a senha fornecida pelo sistema</option>
                                    <option value="esqueci_senha">Esqueci a minha senha</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="nome_completo" name="nome_completo" maxlength="120" class="form-control floating-input" placeholder=" " required>
                                    <label for="nome_completo" class="required-field">Nome completo</label>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="cpf" name="cpf" class="form-control floating-input" placeholder=" " onfocus="limpa_cpf()" onblur="verifica_cpf()" required>
                                    <label for="cpf" class="required-field">CPF</label>
                                </div>
                                <span id="cpf_mensagem" class="error-message"></span>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="telefone" maxlength="50" name="telefone" class="form-control floating-input" placeholder=" " required>
                                    <label for="telefone" class="required-field">Telefone(s)</label>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div class="floating-label">
                                    <input type="email" id="mail" maxlength="45" name="mail" class="form-control floating-input" placeholder=" " required>
                                    <label for="mail" class="required-field">E-Mail</label>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div class="floating-label">
                                    <input type="email" id="mail2" maxlength="45" name="mail2" class="form-control floating-input" placeholder=" " required>
                                    <label for="mail2" class="required-field">Repita o seu E-Mail</label>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label class="form-label required-field">Mensagem</label>
                                <textarea id="mensagem" maxlength="1000" name="mensagem" class="form-control" placeholder="Descreva detalhadamente o seu problema ou dúvida..." required></textarea>
                                <small class="text-muted">Máximo de 1000 caracteres</small>
                            </div>

                            <div class="col-lg-12">
                                <div id="div_mensagem_erro" class="alert alert-danger conditional-section" role="alert">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <span id="mensagem_erro"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fa fa-paper-plane me-2"></i>Enviar Mensagem
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-lg-12">
                    <a href="../<?=$_SESSION['nome_arquivo']?>" class="btn btn-default btn-lg w-100">
                        <i class="fa fa-arrow-left me-2"></i>Voltar
                    </a>
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

<script type="text/javascript">
    $('body').removeClass("sidebar-mini").addClass("sidebar-collapse");

    $(document).ready(function() {
        // Aplicar máscaras
        $("#cpf").mask("999.999.999-99");
        $("#telefone").mask("(99) 99999-9999");

        // Validação de formulário Bootstrap
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Prevenir colar no campo de confirmação de e-mail
        document.getElementById('mail2').addEventListener('paste', function(e) {
            e.preventDefault();
            alert('Colar não é permitido neste campo. Por favor, digite o e-mail novamente.');
        });

        // Inicialmente esconder a mensagem de erro
        $('#div_mensagem_erro').hide();
    });
</script>
</body>

</html>