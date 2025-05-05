<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sistema/css/main.css">
    <link rel="stylesheet" type="text/css" href="sistema/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <title>SiSCanT</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
        <center>
              <img src="sistema/imagens/brasao_eb.png" width="170px"><br>
        
        <font size="6px">
        <b>SiSCanT </b><br>
        <b>Sistema de Seleção de Candidatos Temporários</b>
        </font>
        <br>
        <font size="3px">
            Para acessar o sistema, click no link disponibilizado na página do processo seletivo
        </font>
        </center>
      <div class="logo">
      </div>
          <div class="login-form" >
          <br>
          <br>
          
        </div>
    </section>
    <script src="sistema/js/bootstrap.min.js"></script>
    <script src="sistema/js/plugins/pace.min.js"></script>
    <script src="sistema/js/main.js"></script>
  </body>
  
</html>
<?php 
    if(isset($_GET['erro']))
    {
        echo "<script>alert('A sua sessão foi encerrada!');</script>";
    }
?>