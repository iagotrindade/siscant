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
        <img src="sistema/imagens/12rm.png" width="80px">
        <font color="white" size="5px"><b>SiSCanT</b></font><br>
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
      <div class="logo">
      </div>



      <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2025</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="12rm_ott_2025.php">OTT</a>
                         <a class="btn btn-warning" href="12rm_stt_2025.php">STT</a>
                       
                    </center>
              </div>
          <div class="login-form" >
          <br>
          <center>
      <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2024</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="12rm_eipot_2024.php">EIPOT</a>
                         <a class="btn btn-warning" href="12rm_medicos_2024.php">MÉDICOS</a>
                         <a class="btn btn-warning" href="12rm_fdv_2024.php">FDV</a>
                         <a class="btn btn-warning" href="12rm_ott_2024.php">OTT</a>
                         <a class="btn btn-warning" href="12rm_stt_2024.php">STT</a>
                         <a class="btn btn-warning" href="12rm_cet_2024.php">CET</a>
                         <a class="btn btn-warning" href="12rm_cet_musico_2024.php">MÚSICOS</a>
                       
                    </center>
              </div>
          <div class="login-form" >
          <br>
          <center>

         

          <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>2023</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="12rm_eipot_2023.php">EIPOT</a>
                         <a class="btn btn-warning" href="12rm_stt_2023.php">STT</a>
                         <a class="btn btn-warning" href="12rm_ott_2023.php">OTT</a>
                         <a class="btn btn-warning" href="12rm_stt_2_2023.php">STT 2023</a>
                         <a class="btn btn-warning" href="12rm_fdv_2023.php">FDV</a>
                         <a class="btn btn-warning" href="12rm_cet_2023.php">CET</a>
                         <a class="btn btn-warning" href="12rm_cet_musico_2023.php">CET Músico</a>
                    </center>
              </div>
                <br>
                <br>
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>Médicos 2023</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="12rm_medicos_1_2023.php">Médicos 1</a>
                    </center>
                </div>
                 <br>
<br>
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>TESTE</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="12rm_teste_2023.php">Testes</a>
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