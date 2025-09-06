<?php

include_once 'menu.php';

if ($_SESSION['perfil'] != 'candidato') {
    erro("Erro: 2353432455! Não foi possível abrir a página");
    exit();
}

$id_usuario  = $_SESSION['id_usuario'];

if ($id_usuario == null || $id_usuario == '') {
    erro("Usuário não encontrado, erro: 7854654 $id_usuario");
    exit();
}

if (isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == 1) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A senha foi atualizada! <br>"
        },{
                type: "info"
        });
    };
    </script>';
}
?>
<!-- 05/09/2025 -> Iago Silva Mordernizando o layout da página-->
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Resetar senha <i class="fa fa-lock"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Resetar senha</li>
            </ul>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card card-modern">
                <div class="card-header-modern">
                    <i class="fa fa-lock"></i>
                    Redefinir Senha
                </div>
                <div class="card-body-modern">
                    <div class="password-reset-container">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Requisitos da senha:</strong> Mínimo 8 caracteres, incluindo um caractere especial, um número e uma letra maiúscula.
                        </div>

                        <form action="../banco_dados/candidato_reseta_senha.php" method="post" onsubmit="return valida_form()" class="password-reset-form">
                            <div class="form-group password-input-group">
                                <label for="senha1" class="form-label">
                                    <i class="fa fa-key me-1"></i> Nova senha
                                </label>
                                <div class="input-with-validation">
                                    <input type="password" id="senha1" name="senha1" maxlength="120"
                                        class="form-control password-field"
                                        placeholder="Digite sua nova senha"
                                        onkeyup="validatePasswordStrength(this.value)">
                                    <span class="password-toggle" onclick="togglePasswordVisibility('senha1')">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="password-strength-meter mt-2">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="passwordStrength"></div>
                                    </div>
                                    <small class="strength-text" id="passwordStrengthText">Força da senha</small>
                                </div>
                            </div>

                            <div class="form-group password-input-group">
                                <label for="senha2" class="form-label">
                                    <i class="fa fa-key me-1"></i> Confirmar senha
                                </label>
                                <div class="input-with-validation">
                                    <input type="password" id="senha2" name="senha2"
                                        class="form-control password-field"
                                        placeholder="Digite novamente a senha"
                                        onkeyup="checkPasswordMatch()">
                                    <span class="password-toggle" onclick="togglePasswordVisibility('senha2')">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="password-match-feedback mt-2">
                                    <small id="passwordMatchText"></small>
                                </div>
                            </div>

                            <div class="requirements-list">
                                <small class="text-muted mb-20">A senha deve conter:</small>
                                <ul class="list-unstyled">
                                    <li class="requirement-item" id="req-length">
                                        <i class="fa fa-circle requirement-icon"></i>
                                        <span>Mínimo 8 caracteres</span>
                                    </li>
                                    <li class="requirement-item" id="req-special">
                                        <i class="fa fa-circle requirement-icon"></i>
                                        <span>Pelo menos 1 caractere especial</span>
                                    </li>
                                    <li class="requirement-item" id="req-number">
                                        <i class="fa fa-circle requirement-icon"></i>
                                        <span>Pelo menos 1 número</span>
                                    </li>
                                    <li class="requirement-item" id="req-uppercase">
                                        <i class="fa fa-circle requirement-icon"></i>
                                        <span>Pelo menos 1 letra maiúscula</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="mensagem_erro" class="alert alert-danger" style="display: none;">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                <span id="mensagem"></span>
                            </div>

                            <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                            <button type="submit" class="btn btn-primary btn-lg btn-block submit-button">
                                <i class="fa fa-refresh me-2"></i>
                                ALTERAR SENHA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-header-modern {
            font-size: 16px;
            font-weight: 600;
            color: #006400;
        }

        .password-reset-container {
            padding: 1rem;
        }

        .password-reset-form {
            max-width: 100%;
        }

        .form-label {
            font-size: 16px;
            font-weight: 600;
            color: #006400;
            margin-bottom: 0.5rem;
        }

        .password-input-group {
            margin-bottom: 1.5rem;
        }

        .input-with-validation {
            position: relative;
        }

        .password-field {
            padding-right: 45px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .password-field:focus {
            border-color: #006400;
            box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.25);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #006400;
        }

        .password-strength-meter {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 5px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.25rem;
            margin-top: 1.5rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-text {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .password-match-feedback small {
            font-size: 1.2rem;
        }

        .requirements-list small {
            font-size: 1.5rem;
        }

        .requirements-list {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #006400;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .requirement-icon {
            font-size: 0.5rem;
            margin-right: 0.75rem;
            color: #dc3545;
            transition: color 0.3s ease;
        }

        .requirement-item.valid .requirement-icon {
            color: #28a745;
        }

        .submit-button {
            font-size: 15px;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            background-color: #006400;
            border: none;
        }

        .submit-button:hover {
            background-color: #006400;
            transform: translateY(-2px);
        }

        /* Estados de força da senha */
        .strength-weak {
            width: 33%;
            background-color: #dc3545;
        }

        .strength-medium {
            width: 66%;
            background-color: #ffc107;
        }

        .strength-strong {
            width: 100%;
            background-color: #28a745;
        }

        .text-match {
            color: #28a745;
        }

        .text-mismatch {
            color: #dc3545;
        }
    </style>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function validatePasswordStrength(password) {
            // Verificar requisitos
            const hasMinLength = password.length >= 8;
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasUppercase = /[A-Z]/.test(password);

            // Atualizar ícones de requisitos
            toggleRequirement('req-length', hasMinLength);
            toggleRequirement('req-special', hasSpecialChar);
            toggleRequirement('req-number', hasNumber);
            toggleRequirement('req-uppercase', hasUppercase);

            // Calcular força da senha
            let strength = 0;
            if (hasMinLength) strength += 25;
            if (hasSpecialChar) strength += 25;
            if (hasNumber) strength += 25;
            if (hasUppercase) strength += 25;

            // Atualizar medidor visual
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            strengthBar.className = 'strength-fill';

            if (strength <= 25) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Senha fraca';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 75) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Senha média';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Senha forte';
                strengthText.style.color = '#28a745';
            }

            checkPasswordMatch();
        }

        function toggleRequirement(elementId, isValid) {
            const element = document.getElementById(elementId);
            if (isValid) {
                element.classList.add('valid');
            } else {
                element.classList.remove('valid');
            }
        }

        function checkPasswordMatch() {
            const password1 = document.getElementById('senha1').value;
            const password2 = document.getElementById('senha2').value;
            const matchText = document.getElementById('passwordMatchText');

            if (password2.length === 0) {
                matchText.textContent = '';
                return;
            }

            if (password1 === password2) {
                matchText.textContent = 'Senhas coincidem';
                matchText.className = 'text-match';
            } else {
                matchText.textContent = 'Senhas não coincidem';
                matchText.className = 'text-mismatch';
            }
        }

        function valida_form() {
            const password1 = document.getElementById('senha1').value;
            const password2 = document.getElementById('senha2').value;
            const mensagemErro = document.getElementById('mensagem_erro');
            const mensagem = document.getElementById('mensagem');

            // Verificar se as senhas coincidem
            if (password1 !== password2) {
                mensagem.textContent = 'As senhas não coincidem!';
                mensagemErro.classList.remove('d-none');
                return false;
            }

            // Verificar requisitos da senha
            const hasMinLength = password1.length >= 8;
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password1);
            const hasNumber = /\d/.test(password1);
            const hasUppercase = /[A-Z]/.test(password1);

            if (!hasMinLength || !hasSpecialChar || !hasNumber || !hasUppercase) {
                mensagem.textContent = 'A senha não atende a todos os requisitos!';
                mensagemErro.classList.remove('d-none');
                return false;
            }

            return true;
        }
    </script>



</div>
</div>
</body>

</html>