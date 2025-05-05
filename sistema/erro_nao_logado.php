<?php

$mensagem = $_GET['mensagem'];
include_once './menu_candidato.php';
?>

<div class="content-wrapper">
  <div class="card">
      <div class="row">
          <div class="col-md-6">
              <legend><b>SiSCanT - <?php if(isset($_SESSION['apresentacao_candidato'])) echo $_SESSION['apresentacao_candidato'] ?></b> <small class="pull-right">...</small> </legend>
              <p>Sistema de Seleção de Canditados Temporários</p>
          </div>
          <div class="col-md-6">
              <legend>Data <?php echo date("d/m/Y"); ?><small class="pull-right"><img src="imagens/forca.jpg" width="60px"></small></legend>
              <!-- <a href="suporte_inicial.php">Estou precisando de SUPORTE <i class="fa fa-support"></i></a> -->
          </div>
      </div>
  </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <section class="invoice">
                        <div class="row">
                          <div class="col-xs-12">
                          </div>
                        </div>
                        <legend>Foi detectado um erro!</legend> 
                        <div  class="row">
                            <div  class="col-lg-6">
                                <font color="red"><?php echo $mensagem ?></font>
                            </div>
                            <div  class="col-lg-6">
                                <img src="imagens/erro.jpg" width="100px">
                            </div>
                        </div>
                        <div  class="row">
                            <div  class="col-lg-12">
                                <br>
                               <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    <script type="text/javascript">$('body').removeClass("sidebar-mini").addClass("sidebar-collapse");</script>
  </body>
</html>
