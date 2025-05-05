<script>
    $(document).ready(function () 
    {
        $("#cpf").mask("999.999.999-99"); // Pega pelo ID
    });
</script>
<?php
session_start();

include_once '../funcoes.php';

$cpf = $_GET['cpf'];

$cpf = $cpf = str_replace('.','',$cpf);
$cpf = $cpf = str_replace('-','',$cpf);

//if($cpf != null && $cpf != $_SESSION['cpf_usuario'])
if($cpf != null)
{
    if(valida_cpf($cpf))
    {
        echo"<span id=\"cpf_mensagem\"><font color=\"green\"></span> <i class=\"fa fa-check\"></i></font>";
    }
    else
    {
        echo"<span id=\"cpf_mensagem\"><font color=\"red\">Inválido</span> <i class=\"fa fa-ban\"></i></font>"
        . "<input hidden id=\"erro_cpf\" value=\"125\">";
    }
}


?>



    