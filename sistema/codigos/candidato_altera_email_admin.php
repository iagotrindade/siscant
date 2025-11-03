<a name="alterar_email"></a>
<?php
// 14 MAIO 2024 

if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}
?>

<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <div class="d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-envelope me-2"></i>
                Alterar E-mail do Candidato
            </span>
        </div>
    </div>
    <div class="card-body">
        <!-- Alerta de Atenção -->
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="fa fa-exclamation-triangle fa-2x mr-10"></i>
            <div>
                <strong class="fs-6">Atenção!</strong><br>
                <span class="d-block">Altere o e-mail do candidato somente por solicitação expressa do mesmo.</span>
            </div>
        </div>

        <form method="post" action="../banco_dados/admin_altera_email_candidato.php">
            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
            <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">

            <div class="row">
                <!-- E-mail Atual -->
                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-envelope me-1"></i>
                    </label>
                    <input type="text"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($mail) ?>"
                        disabled
                        required>
                </div>

                <!-- Novo E-mail -->
                <div class="col-md-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-envelope-open me-1"></i>
                        Novo E-mail
                    </label>
                    <input type="email"
                        name="novo_email"
                        class="form-control"
                        placeholder="Digite o novo e-mail do candidato"
                        required>
                </div>

                <!-- Senha do Administrador -->
                <div class="col-md-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-key me-1"></i>
                        Sua Senha (Administrador)
                    </label>
                    <input type="password"
                        name="password"
                        class="form-control"
                        placeholder="Digite sua senha de administrador para confirmar a alteração"
                        required>
                    <small class="text-muted">
                        <i class="fa fa-shield-alt me-1"></i>
                        Confirmação de segurança para alteração
                    </small>
                </div>
            </div>

            <!-- Botão de Submit -->
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                        <i class="fa fa-sync-alt me-2"></i>
                        ATUALIZAR E-MAIL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>