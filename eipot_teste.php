<?php 

session_start();
session_destroy();
session_start();

$usuario_senha = null;
if(isset($_GET['usuario_senha']))
    if($_GET['usuario_senha'] == 'invalido')
        $usuario_senha = 'invalido';
    
$rand = rand(100, 10000);
$string = "eipot_teste";
$codigo_criptografar = $rand.time().$string;
$codigo_chave = substr(md5($codigo_criptografar) , 0, 6);
$_SESSION['chave'] = $codigo_chave;
    
$_SESSION['nome_arquivo'] = "eipot_teste.php";
// A seleção é referente ao index da tabela do banco de dados SELEÇÃO
$_SESSION['selecao'] = 1061; //1124; 
$_SESSION['eipot'] = 1;
$_SESSION['apresentacao_candidato'] = "Seleção EIPOT TESTE";

//AMBIENTE DE PRODUÇÃO 2024
$_SESSION['pasta_arquivos'] = "/var/www/html/sistema/pdf/";

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
    var senha = $('#senha').val();
    
    if(usuario == '' || senha == '')
    {
        $('#mensagem').text('Preencha os campos para fazer o login');
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
              <img src="sistema/imagens/12rm_old.png" width="80px" style="display: inline-block; margin: 5px;">
        </div>

        <center>
        <font color="white">
            <b> Sistema de Seleção de Candidatos Temporários
            <br> Estágio de Instrução e de Preparação para Oficiais Temporários
            <br> EIPOT  2024
            </b>
        </font>
        </center>
      <div class="logo">
      </div>
      <div class="login-box">
          
          <form class="login-form" method="post" action="banco_dados/login.php" onsubmit="return verifica_campos()">
            <center>
                <font size="5px"><b>SiSCanT </b></font><font size="2px"></font>
            </center>
            <br>
            
            <div class="form-group">
              <input class="form-control" id="usuario" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" name="usuario" type="text" placeholder="CPF, somente números" autofocus>
            </div>
            <div class="form-group">
              <input class="form-control" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" id="senha" type="password" name="senha" placeholder="Senha">
            </div>

            <input hidden name="pagina_acessada" value="<?php echo $_SERVER['PHP_SELF'] ?>" >
            
            
            <div id="div_mensagem"> <center><font color="red"><b><span id="mensagem"></span></b></font></center> </div>

            <div id="div_usuario_senha_invalido" <?php  if($usuario_senha != 'invalido') echo "hidden"; ?> > <center><font color="red"><b>Usuário e/ou senha inválido(s)</b></font></center> </div>

            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>ENTRAR</button>
            </div>
            <div class="form-group btn-container">
                <br><p class="semibold-text mb-0"><a href="esqueceu_senha.php">Esqueceu a senha?</a></p>
            </div>
        </form>
      </div>
        <div class="form-group">
            <br><center><font size="5px"><b><a class="btn btn-warning" href="sistema/candidato_cadastro_eipot.php">Quero me cadastrar <i class="fa fa-id-card-o"></i> </a></b></font></center>
        </div>
        
  </section>
    <script src="sistema/js/jquery-3.3.1.min.js"></script>
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