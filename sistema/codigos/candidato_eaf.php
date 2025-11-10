<a name="alterar_eaf"></a>
<?php
// 09 NOVEMBRO 2025

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
                <i class="fa fa-pencil-square-o me-2"></i>
                Resultado EAF (Exame de Aptidão Física)
            </span>
        </div>
    </div>
    <div class="card-body">
        <!-- Alerta de Atenção -->
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="fa fa-exclamation-triangle fa-2x mr-10"></i>
            <div>
                <strong class="fs-6">Atenção!</strong><br>
                <span class="d-block">Essa informação será usada para gerar a Publicação de Resultado do EAF.</span>
            </div>
        </div>

        <form method="post" action="../banco_dados/eaf_cadastra.php">
            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
            <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">

            <div class="row">
                <!-- Apto/Inapto -->
                <div class="col-md-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-pencil-square-o me-1"></i>
                        Resultado
                    </label>
                    <select
                        name="resultado"
                        class="form-control"
                        required>
                        <option value="" disabled selected>Selecione a Situação do EAF</option>
                        <option value="1" <?= $resultado_eaf == 1 ? 'selected' : '' ?>>Apto</option>
                        <option value="0" <?= $resultado_eaf == 0 ? 'selected' : '' ?>>Inapto</option>
                        <option value="2" <?= $resultado_eaf == 2 ? 'selected' : '' ?>>Não compareceu</option>
                    </select>
                </div>
            </div>

            <!-- Botão de Submit -->
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                        <i class="fa fa-sync-alt me-2"></i>
                        ATUALIZAR RESULTADO
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>