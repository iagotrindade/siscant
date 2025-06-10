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
        <img src="sistema/imagens/3rm.png" width="80px">
        <font color="white" size="5px"><b>SiSCanT</b></font><br>
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
        <div class="logo">
        </div>
        <div class="login-form">
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
                
                        <a class="btn btn-warning" href="3rm_ott_stt_2025.php">OTT/STT </a>
                        
                    </center>
                </div>
                <br>
                <br>
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                                <u>2024</u>
                            </b>
                        </font>
                        <br>
                        <a class="btn btn-warning" href="3rm_eipot_2024.php">EIPOT</a>
                        <a class="btn btn-warning" href="3rm_ott_stt_2024.php">OTT/STT </a>
                        <a class="btn btn-warning" href="3rm_mfdv_2024.php">MFDV </a>
                    </center>
                </div>
                <br>
                <br>
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                                <u>CET - 2024</u>
                            </b>
                        </font>
                        <br>
                        <a class="btn btn-warning" href="3rm_cet_cruz_alta_2024.php">CET - CRUZ ALTA</a>
                        <a class="btn btn-warning" href="3rm_cet_santiago_2024.php">CET - SANTIAGO</a>
                        <a class="btn btn-warning" href="3rm_cet_uruguaiana_2024.php">CET - URUGUAIANA</a>
                        <a class="btn btn-warning" href="3rm_cet_bage_2024.php">CET - BAGÉ</a>
                        <a class="btn btn-warning" href="3rm_cet_santa_maria_2024.php">CET - SANTA MARIA</a>
                        <a class="btn btn-warning" href="3rm_cet_porto_alegre_2024.php">CET - PORTO ALEGRE</a>
                        <a class="btn btn-warning" href="3rm_cet_pelotas_2024.php">CET - PELOTAS</a>
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
                            <a class="btn btn-warning" href="3rm_ott_stt_2023.php">OTT/STT</a>
                            <a class="btn btn-warning" href="3rm_mfdv_2023.php">MFDV</a>
                            <a class="btn btn-warning" href="3rm_eipot_2023.php">EIPOT</a>
                            <a class="btn btn-warning" href="3rm_radio_tv_2023.php">STT Rádio e TV (Locutor)</a>
                            <a class="btn btn-warning" href="3rm_capelao_2023.php">Capelão - Católico</a>

                        </center>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="form-group btn-info btn">
                        <center>
                            <font size="5px">
                                <b>
                                    <u>CET - 2023</u>
                                </b>
                            </font>
                            <br>
                            <a class="btn btn-warning" href="3rm_cet_cruz_alta_2023.php">CET - Cruz Alta</a>
                            <a class="btn btn-warning" href="3rm_cet_santiago_2023.php">CET - Santiago</a>
                            <a class="btn btn-warning" href="3rm_cet_uruguaiana_2023.php">CET - Uruguaiana</a>
                            <a class="btn btn-warning" href="3rm_cet_bage_2023.php">CET - Bagé</a>
                            <a class="btn btn-warning" href="3rm_cet_santa_maria_2023.php">CET - Santa Maria</a>
                            <a class="btn btn-warning" href="3rm_cet_porto_alegre_2023.php">CET - Porto Alegre</a>
                            <a class="btn btn-warning" href="3rm_cet_pelotas_2023.php">CET - Pelotas</a>
                        </center>
                    </div>
                <!--
                <div class="form-group btn-info btn">
                    <center>
                        <font size="5px">
                            <b>
                               <u>TESTE</u>
                            </b>
                        </font>
                         <br>
                         <a class="btn btn-warning" href="3rm_cet_teste_2024_2.php">CET Teste</a>
                         <a hidden class="btn btn-warning" href="3rm_cet_teste_2024.php">CET Teste</a>
                         <a hidden class="btn btn-warning" href="3rm_cet_teste_2024_3.php">CET Teste 3 </a>
                    </center>
                </div>
                -->
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
if (isset($_GET['erro'])) {
    echo "<script>alert('A sua sessão foi encerrada!');</script>";
}
?>