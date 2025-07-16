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
<div class="content-wrapper">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <legend>
                    <b>SiSCanT</b>
                 </legend>
                <p>Sistema de Seleção de Canditados Temporários</p>
            </div>
            <div class="col-md-6">
                <legend>Data: <?php echo date("d/m/Y"); ?><small class="pull-right"><img src="imagens/3rm.png" width="60px"></small></legend>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <section class="invoice">
                    <div class="row">
                        <div class="col-xs-12">
                            <legend>Suporte enviado com sucesso <i class="fa fa-comments"></i>
                            </legend>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="bs-component">
                                <div class="alert alert-dismissible alert-success">
                                    <strong>Sua mensagem foi enviada para a Comissão de Seleção!</strong><br>
                                    <strong>Em breve estaremos respondendo para o seu E-Mail.</strong><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a href="../index.php"><button class="btn btn-primary btn-block">VOLTAR</button></a>
                </div>
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