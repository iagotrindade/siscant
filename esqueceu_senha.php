<?php 
session_start();
    
$erro = null;
if(isset($_GET['erro']))
{
    if($_GET['erro'] == 'nao_encontrado')
        $erro = 'nao_encontrado';
}
$sucesso = null;
if(isset($_GET['senha_alterada']))
{
    if($_GET['senha_alterada'] == 1)
        $sucesso = 'sucesso';
    if($_GET['senha_alterada'] == 0)
        $sucesso = 'erro';
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) 
{
    header("Location: ../index.php?erro=123456");
    exit();
}  

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sistema/css/main.css">
    <link rel="stylesheet" type="text/css" href="sistema/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <title>SiSCanT</title>
    
    <script type="text/javascript" src="sistema/js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript" src="sistema/js/plugins/bootstrap-notify.min.js"></script>
    
    <script src="sistema/ajax/ajax.js"></script>
    <script src="sistema/ajax/funcoes.js"></script>

<script>
function limpa_mensagem()
{
    $('#mensagem').text('');
}

function verifica_campos()
{
    var usuario = $('#usuario').val();
    var mail = $('#mail').val();
    
    if(usuario == '' || mail == '')
    {
        $('#mensagem').text('O Campos CPF e E-Mail são obrigatórios!');
        return false;
    }
    
    if(!$.isNumeric($('#usuario').val()))
    {
        $('#mensagem').text('Digite somente números no CPF');
        return false;
    }
    return true;
}
</script>

  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
        <img src="sistema/imagens/3rm.png" width="80px">
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
      <div class="logo">
      </div>
      <div class="login-box">
          

          
          <form class="login-form" method="post" action="banco_dados/esqueceu_senha.php" onsubmit="return verifica_campos()">
            
              <center><font size="5px"><b>SiSCanT </b></font></center><br>
              
              <div class="form-group btn-container">
                <p class="semibold-text mb-0"><center><b>Uma senha será enviada para o seu E-Mail </b></center></p>
            </div>

            <div class="form-group">
              <input class="form-control" id="usuario" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" name="cpf" type="text" placeholder="CPF" autofocus>
            </div>
            <div class="form-group">
              <input class="form-control" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" id="mail" type="text" name="mail" placeholder="E-Mail">
            </div>

            <div id="div_mensagem"> <center><font color="red"><b><span id="mensagem"></span></b></font></center> </div>

            <div id="div_usuario_senha_invalido" <?php  if($erro != 'nao_encontrado') echo "hidden"; ?> > <center><font color="red"><b>CPF e E-Mail não encontrado!</b></font></center> </div>
            <div id="sucesso" <?php  if($sucesso == null) echo "hidden"; ?> > <center><font color="green"><b>Nova senha enviada para o seu E-Mail!</b></font></center> </div>
            <?php if($sucesso == 'erro') echo '<div> <center><font color="red"><b>ERRO! O E-mail não foi enviado!</b></font></center> </div>' ?>
            <div class="form-group btn-container" <?php  if($sucesso != null) echo "hidden"; ?> >
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>ENVIAR</button>
            </div>
            <div class="form-group btn-container" <?php  if($sucesso == null) echo "hidden"; ?> >
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>ENTRAR</button>
            </div>
        </form>
      </div>
        
        <div class="form-group">
            <br><a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
        
    </section>
      
    <script src="sistema/js/jquery-3.3.1.min.js"></script>
    <script src="sistema/js/bootstrap.min.js"></script>
    <script src="sistema/js/plugins/pace.min.js"></script>
    <script src="sistema/js/main.js"></script>
  </body>
  
</html>
