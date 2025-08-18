<?php
session_start();
$id_usuario_session = $_SESSION['id_usuario'];

if ($id_usuario_session == null || $id_usuario_session == '') {
  header("Location: index.php?erro=51244");
  exit();
}

if ($_SESSION['trocar_senha'] != 1) {
  header("Location: index.php?erro=51201");
  exit();
}

if ($_GET['c'] != hash('sha256', $id_usuario_session)) {
  header("Location: index.php?erro=51233");
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiSCanT - Senha Alterada</title>
  <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(to bottom, #197249 50%, #f5f5f5 50%);
    }

    .container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }

    .container img {
      width: 80px;
      margin-bottom: 10px;
    }

    h3 {
      margin: 10px 0;
      font-size: 1.5rem;
      color: #197249;
    }

    .success-icon {
      font-size: 2rem;
      color: #197249;
    }

    p {
      color: #555;
      margin-top: 5px;
      font-size: 1rem;
    }

    .btn-primary {
      background-color: #197249;
      border: none;
      padding: 10px;
      font-size: 1rem;
      width: 100%;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 20px;
    }

    .btn-primary:hover {
      background-color: #145a38;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>

  <script src="sistema/js/jquery-3.3.1.min.js"></script>
</head>

<body>

  <div class="container">
    <h4><b>SiSCanT</b></h4>
    <p class="text-muted mb-0">Sistema de Seleção de Candidatos Temporários</p>
    <h3>Tudo Certo <i class="fa fa-check success-icon"></i></h3>
    <p>SENHA ALTERADA COM SUCESSO!</p>
    <form action="sistema/index.php">
      <button class="btn-primary"><i class="fa fa-sign-in"></i> ENTRAR</button>
    </form>
  </div>
  <script src="sistema/js/bootstrap5.3.3.js"></script>
</body>

</html>