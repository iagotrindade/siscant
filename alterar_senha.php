<?php 
session_start();
$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session == null || $id_usuario_session == '')
{
     header("Location: index.php?erro=5124");
     exit();
}

if($_SESSION['trocar_senha'] != 1)
{
    header("Location: index.php?erro=5120");
    exit();
}

if($_GET['u'] != hash('sha256', $_SESSION['cpf']))
{
    header("Location: index.php?erro=5127");
    exit();
}

$id_usuario_criptografado = hash('sha256', $id_usuario_session);

// define variables and set to empty values
$senha1 = null;
$senha2 = null;
$mensagem_erro = null;
$mensagem_sucesso = null;

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $senha1 = $_POST["senha1"];
    $senha2 = $_POST["senha2"];
    
    if($senha1 == null || $senha1 == "")
        $mensagem_erro = "PREENCHA OS CAMPOS!";
    
    else if($senha1 != $senha2)
        $mensagem_erro = "AS SENHAS DEVEM SER IGUAIS!";
    
    else if($senha1 == $_SESSION['cpf'])
        $mensagem_erro = "A senha não pode ser igual ao CPF!";
    
    else if($senha1 == '123@siscant' || $senha2 == '123@SISCANT')
        $mensagem_erro = "A senha não pode ser 123@siscant!";
    
    else if(!preg_match('/[A-Z]/', $senha1)) 
                    $mensagem_erro = "A senha deve ter pelo menos uma letra MAIÚSCULA!";
    else if(!preg_match('/[0-9]/', $senha1)) 
                    $mensagem_erro = "A senha deve ter pelo menos UM NÚMERO!";
    else if(!preg_match('/[$*&@#]/', $senha1)) 
                    $mensagem_erro = "A senha deve ter pelo menos um caracter especial!";
    
    /*    
    function senhaValida($senha) 
    {
         return preg_match('/[a-z]/', $senha) // tem pelo menos uma letra minúscula
         && preg_match('/[A-Z]/', $senha) // tem pelo menos uma letra maiúscula
         && preg_match('/[0-9]/', $senha) // tem pelo menos um número
         && preg_match('/^[\w$@]{6,}$/', $senha); // tem 6 ou mais caracteres
    }

        var_dump(senhaValida('aB1@xy$z')); // true
        var_dump(senhaValida('aB1')); // false, não tem 6 caracteres
        var_dump(senhaValida('AB1@XYZ')); // false, não tem letra minúscula
        var_dump(senhaValida('ab1@xyz')); // false, não tem letra maiúscula
        var_dump(senhaValida('ABc@xyz')); // false, não tem número
   */ 
    
    else
    {
        if(($senha1 == $senha2) && $senha2 != null && ($senha1 != $_SESSION['cpf']))
        {
            if(strlen($senha1) >= 8)
            {
                include_once 'banco_dados/conexao.php';
                include_once 'sistema/funcoes.php';

                $conexao = new Conexao();
                $senha = hash('sha256', $senha1);
                $resultado = $conexao->candidato_altera_senha($id_usuario_session, $senha); 

                if($resultado)
                {
                    $alteracao = "Usuário ".$_SESSION['cpf']." necessariamente alterou a sua senha";
                    $alteracoes_detalhadas =  print_r($resultado, true);
                    $conexao->insere_log($id_usuario_session, $_SESSION['cpf'], $id_usuario_session, "14104", "usuario", "update", $alteracao, $alteracoes_detalhadas);
                    $conexao = null;
                    header("Location: senha_alterada.php?c=$id_usuario_criptografado");
                    exit();
                }
                else
                    $mensagem_erro = "Erro 456456 ! Senha não alterada!";
            }
            else 
                $mensagem_erro = "A senha deve ter pelo menos 8 caracteres!";
            $conexao = null;
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="sistema/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>SiSCanT</title>
    
    <script src="sistema/ajax/ajax.js"></script>
    <script src="sistema/ajax/funcoes.js"></script>
      
    
</head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <img src="sistema/imagens/3rm.png" width="90px">
        <font color="white"><b>Sistema de Seleção de Candidatos Temporários</b></font>
        <div class="logo">
            
        </div>
        
    <div id="div_senha" class="login-box">
          
          <center><font color='orange'>A senha deve conter pelo menos 8 caracteres, <br>um caracter especial, um número e uma letra maiúscula</font></center>
          
          <form class="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?u=".$_GET['u'];?>">
              <h2 class="login-head"> <i class="fa fa-lock"></i> Nova Senha <i class="fa fa-lock"></i></h2> 
          
          <div class="form-group">
            <input class="form-control" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" name="senha1" type="password" placeholder="Nova senha" autofocus>
          </div>
          
          <div class="form-group">
            <input class="form-control" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" type="password" name="senha2" placeholder="Digite novamente a senha">
          </div>
          
          
          
          <div id="div_mensagem"> <center><font color="red"><b><span id="mensagem"></span></b></font></center> </div>
          
          <div class="form-group btn-container">
              <span id="sucesso_mensagem">
                <?php
                    if($mensagem_sucesso!=null)
                    {
                        echo "
                            <center>
                                <font color='red'><b>".$mensagem_erro."</b></font>
                            </center>";
                    }
                ?>
              </span>
              
              <span id="erro_mensagem">
                  <?php
                    if($mensagem_erro!=null)
                    {
                        echo "<center>";
                        echo "<font color='red'><b>".$mensagem_erro."</b></font></center>";
                    }
                ?>
              </span>
              
            <button class="btn btn-primary btn-block"></i>ALTERAR SENHA</button>
          </div>
          
        </form>
      </div>
    </section>
      
    <script src="sistema/js/bootstrap.min.js"></script>
    <script src="sistema/js/plugins/pace.min.js"></script>
    <script src="sistema/js/main.js"></script>
  </body>
  
</html>