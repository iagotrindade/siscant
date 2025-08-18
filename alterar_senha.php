<?php
session_start();
$id_usuario_session = $_SESSION['id_usuario'];

if ($id_usuario_session == null || $id_usuario_session == '') {
  header("Location: index.php?erro=5124");
  exit();
}

if ($_SESSION['trocar_senha'] != 1) {
  header("Location: index.php?erro=5120");
  exit();
}

if ($_GET['u'] != hash('sha256', $_SESSION['cpf'])) {
  header("Location: index.php?erro=5127");
  exit();
}

$id_usuario_criptografado = hash('sha256', $id_usuario_session);

$senha1 = null;
$senha2 = null;
$mensagem_erro = null;
$mensagem_sucesso = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $senha1 = $_POST["senha1"];
  $senha2 = $_POST["senha2"];

  if ($senha1 == null || $senha1 == "")
    $mensagem_erro = "PREENCHA OS CAMPOS!";
  else if ($senha1 != $senha2)
    $mensagem_erro = "AS SENHAS DEVEM SER IGUAIS!";
  else if ($senha1 == $_SESSION['cpf'])
    $mensagem_erro = "A senha não pode ser igual ao CPF!";
  else if ($senha1 == '123@siscant' || $senha2 == '123@SISCANT')
    $mensagem_erro = "A senha não pode ser 123@siscant!";
  else if (!preg_match('/[A-Z]/', $senha1))
    $mensagem_erro = "A senha deve ter pelo menos uma letra MAIÚSCULA!";
  else if (!preg_match('/[0-9]/', $senha1))
    $mensagem_erro = "A senha deve ter pelo menos UM NÚMERO!";
  else if (!preg_match('/[$*&@#]/', $senha1))
    $mensagem_erro = "A senha deve ter pelo menos um caracter especial!";
  else {
    if (($senha1 == $senha2) && $senha2 != null && ($senha1 != $_SESSION['cpf'])) {
      if (strlen($senha1) >= 8) {
        include_once 'banco_dados/conexao.php';
        include_once 'sistema/funcoes.php';

        $conexao = new Conexao();
        $senha = hash('sha256', $senha1);
        $resultado = $conexao->candidato_altera_senha($id_usuario_session, $senha);

        if ($resultado) {
          $alteracao = "Usuário " . $_SESSION['cpf'] . " necessariamente alterou a sua senha";
          $alteracoes_detalhadas =  print_r($resultado, true);
          $conexao->insere_log($id_usuario_session, $_SESSION['cpf'], $id_usuario_session, "14104", "usuario", "update", $alteracao, $alteracoes_detalhadas);
          $conexao = null;
          header("Location: senha_alterada.php?c=$id_usuario_criptografado");
          exit();
        } else
          $mensagem_erro = "Erro 456456 ! Senha não alterada!";
      } else
        $mensagem_erro = "A senha deve ter pelo menos 8 caracteres!";
      $conexao = null;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alterar Senha - SiSCanT</title>
  <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(to bottom, #006400 50%, #f4f4f4 50%);
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    .logo {
      color: #000000;
      text-align: center;
      margin-bottom: 15px;
    }

    .title {
      text-align: center;
      margin-bottom: 10px;
      color: #006400;
    }

    p.info {
      font-size: 0.9em;
      color: #555;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #006400;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background: #145a3a;
    }

    .msg {
      text-align: center;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .msg.error {
      color: red;
    }

    .msg.success {
      color: green;
    }
  </style>

  <script src="sistema/js/jquery-3.3.1.min.js"></script>
</head>

<body>
  
  <div class="container">
    <h3 class="logo"><b>SiSCanT</b></h3>
 
    <h3 class="title"><i class="fa fa-lock"></i> Alterar Senha</h3>
    <p class="info">A senha deve conter pelo menos 8 caracteres, um caracter especial, um número e uma letra maiúscula.</p>

    <?php if ($mensagem_erro): ?>
      <div class="alert alert-danger text-center p-2">
        <?= $mensagem_erro ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?u=" . $_GET['u']; ?>">
      <div class="form-group">
        <input type="password" name="senha1" placeholder="Nova senha" autofocus>
      </div>
      <div class="form-group">
        <input type="password" name="senha2" placeholder="Digite novamente a senha">
      </div>
      <button type="submit">Alterar Senha</button>
    </form>
  </div>

  <script src="sistema/js/bootstrap5.3.3.js"></script>
</body>

</html>