<a name="observacoes"></a>
<?php
// 14 MAIO 2024 

if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}
?>

<div class="card">
    <legend>Alterar email do Candidato</legend>
    <h5 class="text-danger mb-10">*Atenção! Altere o email do candidato somente por solicitação expressa do mesmo.</h5>
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="../banco_dados/admin_altera_email_candidato.php">
                <input type="text" value="<?php echo $id_usuario ?>" name="id_usuario" hidden>
                <input type="text" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']) ?>" name="criptografia" hidden>
                <div style="float: left; width: 49%; margin-right: 2%;">
                    <div class="form-group">
                        <label for="titulo">Email do Candidato:</label>
                        <input type="text" name="email" class="form-control" value="<?php echo ($mail); ?>" disabled required>
                    </div>
                </div>

                <div style="float: left; width: 49%;">
                    <div class="form-group">
                        <label for="titulo_um">Novo email:</label>
                        <input type="text" name="novo_email" class="form-control" required>
                    </div>
                </div>

                <div style="float: left; width: 100%;">
                    <div class="form-group">
                        <label for="titulo_um">Sua senha (administrador):</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <br>
                <br>
                <button type="submit" class="btn btn-primary btn-block">Atualizar</button>
            </form>
        </div>
    </div>
</div>