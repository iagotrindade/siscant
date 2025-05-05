<?php 
session_start();
$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session == null || $id_usuario_session == '')
{
     header("Location: index.php?erro=51244");
     exit();
}

if($_SESSION['trocar_senha'] != 1)
{
    header("Location: index.php?erro=51201");
    exit();
}

if($_GET['c'] != hash('sha256', $id_usuario_session))
{
    header("Location: index.php?erro=51233");
    exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="sistema/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>SiSCanT</title>
    
    <script src="sistema/ajax/ajax.js"></script>
    <script src="sistema/ajax/funcoes.js"></script>
    
</head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
        
        <img src="sistema/imagens/3rm.png" width="90px">
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
      <div class="logo">
      </div>
        
      <div id="div_senha" class="login-box">
                    
         <form class="login-form" action="sistema/index.php" >
          <h3 class="login-head">Tudo Certo <font color="#3477aa"><i class="fa fa-lg fa-fw fa-check"></i></font></h3>
          <div class="form-group">
           <br><center> <label class="control-label"><font color="#3477aa">SENHA ALTERADA COM SUCESSO!<br><br><br></font></label></center>
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>ENTRAR</button> 
          </div>
        </form>
          
          
      </div>
    </section>
    <script src="sistema/js/bootstrap.min.js"></script>
    <script src="sistema/js/plugins/pace.min.js"></script>
    <script src="sistema/js/main.js"></script>
  </body>
  
</html>
