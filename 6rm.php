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
        <img src="sistema/imagens/6rm.png" width="80px">
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
                               <u>2024</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="6rm_mfdv_2024.php">MFDV</a>
                         <a class="btn btn-warning" href="6rm_mfdv_2024_2.php">MFDV 2º SEM</a>
                         <br>
                         <br>
                         <a class="btn btn-warning" href="6rm_ott_2024.php">OTT - Eng, Arq, Inf</a>
                         <a class="btn btn-warning" href="6rm_ott_2024_saude.php">OTT - Saúde</a>
                         <a class="btn btn-warning" href="6rm_ott_2024_mag_afins.php">OTT - Magistério e afins</a>
                         <a class="btn btn-warning" href="6rm_ott_2024_admin_contab_diversos.php">OTT - Admin, Contab e Diversos</a>
                         <a class="btn btn-warning" href="6rm_ott_2024_direito.php">OTT - Direito</a>
                        <br>
                        <br>
                         <a class="btn btn-warning" href="6rm_stt_2024.php">STT - Saúde</a>
                         <a class="btn btn-warning" href="6rm_stt_2024_construcao.php">STT - Construção</a>
                         <a class="btn btn-warning" href="6rm_stt_2024_diversos.php">STT - Diversos</a>
                        <br>
                        <br>
                         <a class="btn btn-warning" href="6rm_cet_2024.php">CET - Automotiva, Mot e Op Maq</a>
                         <a class="btn btn-warning" href="6rm_cet_2024_const_civil.php">CET - Const. Civil, Saúde e diversos</a>
                         <a class="btn btn-warning" href="6rm_cet_2024_musicos.php">CET - Músicos</a>
                        
                    </center>
                </div>
            <br>
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
                         <a class="btn btn-warning" href="6rm_mfdv_2023.php">MFDV</a>
                         <a class="btn btn-warning" href="6rm_cet_p_2023.php">Cet Pago</a>
                         <a class="btn btn-warning" href="6rm_cet_2023.php">Cet</a>
                         <a class="btn btn-warning" href="6rm_ott_stt_p_2023.php">OTT/STT Pago</a>
                         <a class="btn btn-warning" href="6rm_ott_stt_2023.php">OTT/STT</a>
                         <a class="btn btn-warning" href="6rm_stt_2023.php">STT</a>
                         <a class="btn btn-warning" href="6rm_direito_2023.php">Direito</a>
                         <a class="btn btn-warning" href="6rm_licenciatura_2023.php">Licenciatura</a>
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
                         <a class="btn btn-warning" href="6rm_mfdv_teste.php">MFDV</a>
                         <br>
                    </center>
                </div>
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