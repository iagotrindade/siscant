<?php
include_once 'menu.php';
$mensagem = $_GET['mensagem'];
?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Erro <i class="fa fa-times"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Erro</a></li>
      </ul>
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
        
    </div>
  </div>
</div>
</div>
</body>
</html>
<?php $conexao = null; ?>