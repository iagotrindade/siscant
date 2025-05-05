<?php
    session_start();
    
    include_once '../banco_dados/conexao.php';
    include_once 'funcoes.php';
    
    if(!isset($_SESSION['chave']))
    {
        header ("Location: ../index.php") ;
        exit();
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
    
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="js/main.js"></script>
    
    <!-- MÁSCARA -->
    <script src="js/jquery.maskedinput.js"></script>
    <script src="js/maskMoney.js"></script>
    
    <!-- AJAX -->
    <script src="ajax/ajax.js"></script>
    <script src="ajax/funcoes.js"></script>
    
    <!-- ALERTA -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap-notify.min.js"></script>
    
    <!-- COMBO DINAMICO -->
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>
    
    <!-- GRÁFICOS -->
    <script src="js/chartjs.js"></script>

    <script>
        $(document).ready(function () 
        {
            $("input[name*='data']").mask("99/99/9999");
            $("input[name*='cpf']").mask("999.999.999-99");
            
            $("input[name*='valor']").maskMoney({showSymbol:true, symbol:"R$ ", decimal:",", thousands:"."});
            
            $("#cnpj").mask("99.999.999/9999-99"); // Pega pelo ID
            //$("#cpf").mask("999.999.999-99"); // Pega pelo ID
        });
    </script>
    
<title>SiSCanT</title>
</head>
<body >