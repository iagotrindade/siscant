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
    <div style="text-align: center;">
              <img src="sistema/imagens/1rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/2rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/3rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/4rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/5rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/6rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/7rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/8rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/9rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/10rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/11rm.png" width="80px" style="display: inline-block; margin: 5px;">
              <img src="sistema/imagens/12rm.png" width="80px" style="display: inline-block; margin: 5px;">
        </div>
        <center>
        <font color="white" size="5px"><b>SiSCanT</b></font><br>
        <font color="white"> <b> Sistema de Seleção de Candidatos Temporários
            <br> Estágio de Instrução e de Preparação para Oficiais Temporários - EIPOT
        </font>
      <div class="logo">
      </div>
          <div class="login-form" >
          <br>
          <br>
          <br>
          <br>
          <br>
          <center>
            <div class="form-group btn-info btn">
                <center>
                    <font size="5px">
                        <b>
                            <u>2025</u>
                        </b>
                        </font>
                            <br>
                        <a class="btn btn-warning" href="eipot_brasil_2025.php">EIPOT</a>
                        <a class="btn btn-warning" href="eipot_tenr2_2025.php">TEN R/2 </a>
                </center>
                </div>
                <br>
                <br>
          </center>
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