<?php

session_start();
session_destroy();
session_start();

$usuario_senha = null;
if (isset($_GET['usuario_senha']))
  if ($_GET['usuario_senha'] == 'invalido')
    $usuario_senha = 'invalido';

$rand = rand(100, 10000);
$string = "selecao_ott_stt_1145";
$codigo_criptografar = $rand . time() . $string;
$codigo_chave = substr(md5($codigo_criptografar), 0, 6);
$_SESSION['chave'] = $codigo_chave;

$_SESSION['nome_arquivo'] = "3rm_ott_stt_2025.php";
// A seleção é referente ao index da tabela do banco de dados SELEÇÃO
$_SESSION['selecao'] = 1058;
$_SESSION['apresentacao_candidato'] = "Seleção de Oficiais e Sargentos Técnicos Temporários ";

// AMBIENTE DE TESTES
//$_SESSION['pasta_arquivos'] = "arquivos/";

//AMBIENTE DE PRODUÇÃO
$_SESSION['pasta_arquivos'] = "/var/www/html/sistema/pdf/";

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SiSCanT - Login</title>
  <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
  <link rel="stylesheet" href="sistema/css/font-awesome-4.7.0/css/font-awesome.min.css">
  <style>
    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(to bottom, #006400 50%, #ffffff 50%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      z-index: 1;
    }

    .login-card img {
      max-width: 80px;
      margin-bottom: 15px;
    }

    .btn-primary {
      background-color: #006400;
      border: none;
    }

    .btn-primary:hover {
      background-color: #145c3c;
    }

    a {
      color: #006400;
    }

    a:hover {
      color: #145c3c;
    }
  </style>
  <script src="sistema/js/jquery-3.3.1.min.js"></script>
  <script>
    function limpa_mensagem() {
      $('#mensagem').text('');
    }

    function verifica_campos() {
      var usuario = $('#usuario').val();
      var senha = $('#senha').val();
      if (usuario === '' || senha === '') {
        $('#mensagem').text('Preencha os campos para fazer o login');
        return false;
      }
      if (!$.isNumeric(usuario)) {
        $('#mensagem').text('Digite somente números no CPF');
        return false;
      }
      return true;
    }
  </script>
</head>

<body>

  <div class="login-card">
    <div class="text-center">
      <img src="sistema/imagens/3rm.png" alt="Logo">
      <h4><b>SiSCanT</b></h4>
      <p class="text-muted mb-0">Seleção de Oficiais e Sargentos Técnicos Temporários</p>
      <p class="text-muted">Seleção 2025/2026 - OTT/STT</p>
    </div>

    <?php if ($usuario_senha === 'invalido'): ?>
      <div class="alert alert-danger text-center p-2">
        Usuário e/ou senha inválido(s)
      </div>
    <?php endif; ?>

    <div id="mensagem" class="text-danger text-center mb-2"></div>

    <form method="post" action="banco_dados/login.php" onsubmit="return verifica_campos()">
      <div class="mb-3">
        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="CPF, somente números" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" autofocus>
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()">
      </div>
      <input hidden name="pagina_acessada" value="<?php echo $_SERVER['PHP_SELF'] ?>">
      <button class="btn btn-primary w-100" type="submit">
        <i class="fa fa-sign-in fa-lg fa-fw"></i> ENTRAR
      </button>
    </form>

    <div class="text-center mt-3">
      <a href="esqueceu_senha.php">Esqueceu a senha?</a>
    </div>
    <hr>
    <div class="text-center">
      <a class="btn btn-warning w-100" href="sistema/candidato_cadastro.php">Quero me cadastrar <i class="fa fa-id-card-o"></i></a>
    </div>
  </div>

  <script src="sistema/js/bootstrap5.3.3.js"></script>
</body>

</html>

<?php
if (isset($_GET['erro'])) {
  echo "<script>alert('A sua sessão foi encerrada!');</script>";
}
?>