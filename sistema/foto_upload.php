<?php
require 'menu.php';

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Minha foto <i class="fa fa-picture-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Página Inicial</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                   
                    
                    <legend> Adicione uma foto 3X4</legend> 
                    <font color="red" size="2px"> *Máximo 2 MegaBytes nos formatos PNG, JPEG ou JPG</font>
                    <div class="card-body">
                        <form method="post" action="usuario_upload_foto.php" enctype="multipart/form-data">
                            <center>
                            <table style="width:60%" border="0">
                                <tr>
                                    <th width="100%"><label>Troque a foto do seu perfil</label>
                                    <input type="file" name="foto"/></th>
                                    <th rowspan="3" >
                                        <center><img src="<?php echo"fotos/$usuario_foto" ?>" width="150px" style="box-shadow: 0px 0px 10px #006400"></center>
                                    </th>
                                </tr>
                                <tr>
                                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']."freitas"); ?>">
                                    <th>
                                        <input type="submit" class="btn btn-primary" value="Enviar" />
                                    </th>
                                </tr>
                            </table>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
<?php $conexao = null; ?>