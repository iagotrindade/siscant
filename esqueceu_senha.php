<?php
session_start();

$erro = null;
if (isset($_GET['erro']) && $_GET['erro'] == 'nao_encontrado') {
  $erro = 'nao_encontrado';
}

$sucesso = null;
if (isset($_GET['senha_alterada'])) {
  if ($_GET['senha_alterada'] == 1) $sucesso = 'sucesso';
  if ($_GET['senha_alterada'] == 0) $sucesso = 'erro';
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
  header("Location: ../index.php?erro=123456");
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SiSCanT - Reset de Senha</title>
  <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
  <link rel="stylesheet" href="sistema/css/font-awesome-4.7.0/css/font-awesome.min.css">
  <style>
    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(to bottom, #197249 50%, #ffffff 50%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
    }

    .reset-card {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      z-index: 1;
    }

    .reset-card img {
      max-width: 80px;
      margin-bottom: 15px;
    }

    .btn-primary {
      background-color: #197249;
      border: none;
    }

    .btn-primary:hover {
      background-color: #145c3c;
    }

    a {
      color: #197249;
      text-decoration: none;
    }

    a:hover {
      color: #145c3c;
    }
  </style>
  <script src="sistema/js/bootstrap5.3.3.js"></script>
  <script>
    function limpa_mensagem() {
      $('#mensagem').text('');
    }

    function verifica_campos() {
      var usuario = $('#usuario').val();
      var mail = $('#mail').val();

      if (usuario === '' || mail === '') {
        $('#mensagem').text('Os campos CPF e E-Mail são obrigatórios!');
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

  <div class="reset-card">
    <div class="text-center">
      <img src="sistema/imagens/3rm.png" alt="Logo">
      <h4><b>SiSCanT</b></h4>
      <p class="text-muted mb-0">Sistema de Seleção de Candidatos Temporários</p>
      <small>Resetar Senha</small>
    </div>

    <?php if ($erro === 'nao_encontrado'): ?>
      <div class="alert alert-danger text-center p-2 mt-3">
        CPF e E-Mail não encontrado!
      </div>
    <?php endif; ?>

    <?php if ($sucesso === 'sucesso'): ?>
      <div class="alert alert-success text-center p-2 mt-3">
        Nova senha enviada para o seu E-Mail!
      </div>
    <?php elseif ($sucesso === 'erro'): ?>
      <div class="alert alert-danger text-center p-2 mt-3">
        ERRO! O E-mail não foi enviado!
      </div>
    <?php endif; ?>

    <div id="mensagem" class="text-danger text-center mb-2"></div>

    <form method="post" action="banco_dados/esqueceu_senha.php" onsubmit="return verifica_campos()">
      <div class="mb-3">
        <input class="form-control" required id="usuario" name="cpf" type="text" placeholder="CPF" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()" autofocus>
      </div>
      <div class="mb-3">
        <input class="form-control" required id="mail" name="mail" type="text" placeholder="E-Mail" onfocus="limpa_mensagem()" onkeypress="limpa_mensagem()">
      </div>

      <?php if ($sucesso === null): ?>
        <button class="btn btn-primary w-100" type="submit">
          <i class="fa fa-sign-in fa-lg fa-fw"></i> ENVIAR
        </button>
      <?php else: ?>
        <a href="<?=$_SESSION['nome_arquivo']?>" class="btn btn-primary w-100">
          <i class="fa fa-sign-in fa-lg fa-fw"></i> ENTRAR
        </a>
      <?php endif; ?> 
    </form>

    <hr>
    <div class="text-center">
      <a class="btn btn-outline-secondary w-100" href="javascript:history.back()">VOLTAR</a>
    </div>
  </div>

  <script src="sistema/js/bootstrap5.3.3.js"></script>
</body>

</html>