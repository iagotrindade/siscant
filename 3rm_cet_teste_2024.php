<?php 

session_start();
session_destroy();
session_start();

$usuario_senha = null;
if(isset($_GET['usuario_senha']))
    if($_GET['usuario_senha'] == 'invalido')
        $usuario_senha = 'invalido';
    
$rand = rand(100, 10000);
$string = "3rm_cet_teste_2024";
$codigo_criptografar = $rand.time().$string;
$codigo_chave = substr(md5( $codigo_criptografar) ,0,6);
$_SESSION['chave'] = $codigo_chave;

// Mudar essas variaveis
$_SESSION['nome_arquivo'] = "3rm_cet_teste_2024.php";
$_SESSION['selecao'] = 1086
;
$_SESSION['apresentacao_candidato'] = "Seleção CET 2024";

// AMBIENTE DE TESTES
//$_SESSION['pasta_arquivos'] = "arquivos/";

//AMBIENTE DE PRODUÇÃO
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
        <img src="sistema/imagens/3rm.png" width="80px">
        <center>
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários<br> SELEÇÃO 2024<br>CET (Cabos Especialistas Temporários)</b></font>
        </center>
      <div class="logo">
      </div>
      <div class="login-box">
          
          <form class="login-form" method="post" action="banco_dados/login.php" onsubmit="return verifica_campos()">
            <center><font size="5px"><b>SiSCanT </b></font><font size="2px"></font></center>

            <font size="3px" color="red">
                <center>Acesse somente pelo computador</center>
            </font>    
            
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
        <br>
        <font size="4px" color="red">
                <b>Esta plataforma é de versão exclusiva para computadores</b>
        </font> 
        
        <div class="form-group">
            <br><center><font size="5px"><b><a class="btn btn-warning" href="sistema/candidato_cadastro.php">Quero me cadastrar <i class="fa fa-id-card-o"></i> </a></b></font></center>
        </div>
        
        <!--
        
        <div class="form-group">
            <center><font size="5px"><b><a class="btn btn-info" href="sistema/suporte_inicial.php">Suporte <i class="fa fa-support"></i></a></b></font></center>
        </div>
        
        <p class="semibold-text mb-0"><a target="_blank" href="http://www.3rm.eb.mil.br/index.php/servico-militar-regional?id=911"><u>Página do Processo Seletivo (Aviso de Convocação)</u></a></p>
        
        -->
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