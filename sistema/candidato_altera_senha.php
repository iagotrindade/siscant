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
        <div class="col-lg-12 col-xl-8">
            <div class="card security-card">
                <div class="card-header mb-20">
                    <div style="display: flex; align-items: center;">
                        <div class="mr-10">
                            <i class="fa fa-shield fa-2x"></i>
                        </div>
                        <span class="mb-0">Redefinir Senha</span>

                    </div>
                </div>

                <div class="card-body">
                    <div class="security-alert">
                        <i class="fa fa-info-circle"></i>
                        <div class="alert-content">
                            <strong>Requisitos de segurança:</strong> Sua senha deve atender a todos os critérios abaixo
                        </div>
                    </div>

                    <form action="../banco_dados/candidato_reseta_senha.php" method="post" onsubmit="return valida_form()" class="security-form">
                        <div class="form-group-modern">
                            <label for="senha1" class="">
                                <i class="fa fa-lock"></i>
                                Nova Senha
                            </label>
                            <div class="input-group-modern">
                                <input type="password" id="senha1" name="senha1" maxlength="120"
                                    class="form-control password-field"
                                    placeholder="Digite sua nova senha"
                                    onkeyup="validatePasswordStrength(this.value)"
                                    autocomplete="new-password">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('senha1')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength-container">
                                <div class="strength-meter">
                                    <div class="strength-segments">
                                        <div class="segment" data-strength="weak"></div>
                                        <div class="segment" data-strength="medium"></div>
                                        <div class="segment" data-strength="strong"></div>
                                    </div>
                                </div>
                                <div class="strength-feedback">
                                    <span class="strength-text" id="passwordStrengthText">Digite sua senha</span>
                                    <span class="strength-score" id="passwordStrengthScore"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label for="senha2">
                                <i class="fa fa-lock-check"></i>
                                Confirmar Senha
                            </label>
                            <div class="input-group-modern">
                                <input type="password" id="senha2" name="senha2" maxlength="120"
                                    class="form-control password-field"
                                    placeholder="Confirme sua nova senha"
                                    onkeyup="checkPasswordMatch()"
                                    autocomplete="new-password">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('senha2')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="match-feedback">
                                <span class="match-icon" id="matchIcon"></span>
                                <span class="match-text" id="passwordMatchText"></span>
                            </div>
                        </div>

                        <div class="requirements-panel">
                            <h4 class="">Critérios de Segurança</h4>
                            <div class="requirements-grid">
                                <div class="requirement" id="req-length">
                                    <div class="requirement-icon">
                                        <i class="fa fa-circle"></i>
                                    </div>
                                    <span class="requirement-text">Mínimo 8 caracteres</span>
                                    <div class="requirement-check">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                                <div class="requirement" id="req-special">
                                    <div class="requirement-icon">
                                        <i class="fa fa-circle"></i>
                                    </div>
                                    <span class="requirement-text">1 caractere especial</span>
                                    <div class="requirement-check">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                                <div class="requirement" id="req-number">
                                    <div class="requirement-icon">
                                        <i class="fa fa-circle"></i>
                                    </div>
                                    <span class="requirement-text">1 número</span>
                                    <div class="requirement-check">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <div class="requirement-icon">
                                        <i class="fa fa-circle"></i>
                                    </div>
                                    <span class="requirement-text">1 letra maiúscula</span>
                                    <div class="requirement-check">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="mensagem_erro" class="error-alert">
                            <div class="error-content">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span id="mensagem"></span>
                            </div>
                        </div>

                        <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                        <button type="submit" class="btn-security-primary">
                            <i class="fa fa-key"></i>
                            <span>Atualizar Senha</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .security-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .security-body {
            padding: 2rem;
        }

        .security-alert {
            display: flex;
            align-items: flex-start;
            align-items: center;
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .security-alert i {
            color: #006400;
            font-size: 1.25rem;
            margin-right: 0.75rem;
            margin-top: 0.125rem;
        }

        .alert-content {
            flex: 1;
        }

        .form-group-modern {
            margin-bottom: 2rem;
        }



        .input-group-modern {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control-modern {
            flex: 1;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control-modern:focus {
            border-color: #006400;
            box-shadow: 0 0 0 3px rgba(0, 100, 0, 0.1);
            outline: none;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .password-toggle-btn:hover {
            background: #f7fafc;
            color: #006400;
        }

        .password-strength-container {
            margin-top: 1rem;
        }

        .strength-meter {
            margin-bottom: 0.5rem;
        }

        .strength-segments {
            display: flex;
            gap: 4px;
            height: 6px;
        }

        .segment {
            flex: 1;
            background: #e2e8f0;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .segment.active.weak {
            background: #e53e3e;
        }

        .segment.active.medium {
            background: #dd6b20;
        }

        .segment.active.strong {
            background: #38a169;
        }

        .strength-feedback {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.3rem;
        }

        .strength-text {
            color: #718096;
        }

        .strength-score {
            font-weight: 600;
        }

        .match-feedback {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .match-icon {
            margin-right: 0.5rem;
            font-size: 0.75rem;
        }

        .match-valid {
            color: #38a169;
        }

        .match-invalid {
            color: #e53e3e;
        }

        .requirements-panel {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid #006400;
        }

        .requirements-title {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .requirements-grid {
            display: grid;
            gap: 0.75rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .requirement.valid {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
        }

        .requirement-icon {
            margin-right: 0.75rem;
            color: #e53e3e;
            transition: color 0.3s ease;
        }

        .requirement.valid .requirement-icon {
            color: #38a169;
        }

        .requirement-text {
            flex: 1;
            font-size: 1.2rem;
            color: #4a5568;
        }

        .requirement-check {
            opacity: 0;
            color: #38a169;
            transition: opacity 0.3s ease;
        }

        .requirement.valid .requirement-check {
            opacity: 1;
        }

        .error-alert {
            display: none;
            background: #fed7d7;
            border: 1px solid #feb2b2;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
        }

        .error-alert.show {
            display: block;
        }

        .error-content {
            display: flex;
            align-items: center;
            color: #c53030;
        }

        .error-content i {
            margin-right: 0.75rem;
        }

        .btn-security-primary {
            background: linear-gradient(135deg, #006400 0%, #008000 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-security-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 100, 0, 0.3);
        }

        @media (max-width: 768px) {
            .security-body {
                padding: 1.5rem;
            }

            .security-header {
                padding: 1.5rem;
            }

            .security-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
        }
    </style>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.parentElement.querySelector('.password-toggle-btn i');

            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function validatePasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
                number: /\d/.test(password),
                uppercase: /[A-Z]/.test(password)
            };

            // Atualizar requisitos visuais
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                if (requirements[req]) {
                    element.classList.add('valid');
                } else {
                    element.classList.remove('valid');
                }
            });

            // Calcular força
            const strength = Object.values(requirements).filter(Boolean).length;
            const strengthPercent = (strength / 4) * 100;

            updateStrengthMeter(strength, strengthPercent);
            updateStrengthText(strength, password.length);

            checkPasswordMatch();
        }

        function updateStrengthMeter(strength, percent) {
            const segments = document.querySelectorAll('.segment');
            segments.forEach((segment, index) => {
                segment.classList.remove('active', 'weak', 'medium', 'strong');
                if (index < strength) {
                    segment.classList.add('active');
                    if (strength <= 1) segment.classList.add('weak');
                    else if (strength <= 3) segment.classList.add('medium');
                    else segment.classList.add('strong');
                }
            });
        }

        function updateStrengthText(strength, length) {
            const textEl = document.getElementById('passwordStrengthText');
            const scoreEl = document.getElementById('passwordStrengthScore');

            const texts = ['Muito fraca', 'Fraca', 'Média', 'Forte', 'Muito forte'];
            const colors = ['#e53e3e', '#dd6b20', '#d69e2e', '#38a169', '#25855a'];

            textEl.textContent = length === 0 ? 'Digite sua senha' : texts[strength];
            textEl.style.color = length === 0 ? '#718096' : colors[strength];

            scoreEl.textContent = length === 0 ? '' : `${strength}/4`;
            scoreEl.style.color = colors[strength];
        }

        function checkPasswordMatch() {
            const password1 = document.getElementById('senha1').value;
            const password2 = document.getElementById('senha2').value;
            const matchText = document.getElementById('passwordMatchText');
            const matchIcon = document.getElementById('matchIcon');

            if (password2.length === 0) {
                matchText.textContent = '';
                matchIcon.className = 'match-icon';
                return;
            }

            if (password1 === password2 && password1.length > 0) {
                matchText.textContent = 'Senhas coincidem';
                matchText.style.color = '#38a169';
                matchIcon.className = 'match-icon fa fa-check-circle match-valid';
            } else {
                matchText.textContent = 'Senhas não coincidem';
                matchText.style.color = '#e53e3e';
                matchIcon.className = 'match-icon fa fa-times-circle match-invalid';
            }
        }

        function valida_form() {
            const password1 = document.getElementById('senha1').value;
            const password2 = document.getElementById('senha2').value;
            const mensagemErro = document.getElementById('mensagem_erro');
            const mensagem = document.getElementById('mensagem');

            // Reset error
            mensagemErro.classList.remove('show');

            // Verificar se as senhas coincidem
            if (password1 !== password2) {
                mensagem.textContent = 'As senhas não coincidem. Por favor, verifique e tente novamente.';
                mensagemErro.classList.add('show');
                return false;
            }

            // Verificar requisitos da senha
            const requirements = {
                length: password1.length >= 8,
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password1),
                number: /\d/.test(password1),
                uppercase: /[A-Z]/.test(password1)
            };

            const allValid = Object.values(requirements).every(Boolean);

            if (!allValid) {
                mensagem.textContent = 'A senha não atende a todos os requisitos de segurança.';
                mensagemErro.classList.add('show');
                return false;
            }

            return true;
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            validatePasswordStrength('');
            checkPasswordMatch();
        });
    </script>
</div>
</div>
</body>

</html>