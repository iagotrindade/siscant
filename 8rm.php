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
        <img src="sistema/imagens/8rm.png" width="80px">
        <font color="white" size="5px"><b>SiSCanT</b></font><br>
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
      <div class="logo">
      </div>
          <div class="login-form" >
          <br>
          <center>
          <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2025</u>
                               <br>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="8rm_ott_2025.php">OTT 2025</a>
                         <a class="btn btn-warning" href="8rm_mfdv_2025.php">MFDV 2025</a>
                       
                  </div>
                  <br>
                  <br>

          <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2024 / 2º SEMESTRE</u>
                               <br>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="8rm_ott_2024_2sem.php">OTT 2024</a>
                         <a class="btn btn-warning" href="8rm_stt_2024_2sem.php">STT 2024</a>
                         <br>
                         <br>
                         <a class="btn btn-warning" href="8rm_mfdv_2024_2sem.php">MFDV 2024</a>
                         <a class="btn btn-warning" href="8rm_cet_2025.php">CET 2024</a>
                  </div>
                 <br>
                 <br>
             
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2024</u>
                               <br>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="8rm_eipot_2024.php">EIPOT 2024</a>
                         <br>
                         <br>
                         <a class="btn btn-warning" href="8rm_ott_2024.php">OTT 2024</a>
                         <a class="btn btn-warning" href="8rm_ottm_2024.php">OTTM 2024</a>
                         <a class="btn btn-warning" href="8rm_stt_2024.php">STT 2024</a>
                         <br>
                         <br>
                         <a class="btn btn-warning" href="8rm_cet_2024.php">CET 2024</a>
                         <a class="btn btn-warning" href="8rm_cet_motorista_2024.php">CET MOTORISTA 2024</a>
                         <br>
                         <br>
                         <a class="btn btn-warning" href="8rm_mfdv_2024.php">MFDV 2024</a>
                  </div>
             
                    </center>
             <br>
             <br>
                </center>
               <center>
               
                <br>
                <br>
                <br>
            
                <br>
                <br>
                <br>
               
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