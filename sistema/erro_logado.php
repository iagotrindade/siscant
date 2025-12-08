<?php
include_once 'menu.php';
$mensagem = $_GET['mensagem'];
?>
<style>
  .header-bar {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    padding: 15px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }

  .header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
  }

  .logo-placeholder img {
    height: 80px;
  }

  .error-container {
    max-width: 600px;
    margin: 0 auto;
  }

  .error-icon {
    animation: pulse 1.5s infinite;
  }

  .error-title {
    font-weight: 700;
    border-bottom: 2px solid #dc3545;
    padding-bottom: 10px;
    display: inline-block;
  }

  .error-message {
    border-left: 4px solid #dc3545;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.05);
    }

    100% {
      transform: scale(1);
    }
  }

  .btn-primary {
    background: linear-gradient(135deg, #006400 0%, #004d00 100%);
    border: none;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 100, 0, 0.3);
  }

  .btn-outline-secondary {
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
  }

  :root {
    --primary-color: #006400;
    --primary-light: #228B22;
    --secondary-color: #6c757d;
    --accent-color: #32CD32;
    --light-bg: #f0f8f0;
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }

  .main-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 0;
  }

  .header-bar {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    padding: 15px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }

  .card {
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    border: none;
    margin-bottom: 20px;
    transition: var(--transition);
  }

  .card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  }

  .section-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    border-radius: 10px 10px 0 0;
    padding: 15px 20px;
    margin: 0;
  }

  .form-section {
    padding: 25px;
    background-color: white;
    border-radius: 0 0 10px 10px;
  }

  .form-label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #444;
  }

  .form-control,
  .form-select {
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #ddd;
    transition: var(--transition);
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(0, 100, 0, 0.15);
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: var(--transition);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 100, 0, 0.3);
    background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
  }

  .btn-default {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: var(--transition);
  }

  .btn-default:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    background: linear-gradient(135deg, #5a6268, #6c757d);
    color: white;
  }

  .alert-info {
    background-color: #e8f4e8;
    border: 1px solid #c0e0c0;
    color: #0c540c;
    border-radius: 10px;
  }

  .header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
  }

  .logo-placeholder img {
    height: 80px;
  }

  .required-field::after {
    content: " *";
    color: #dc3545;
  }

  .fade-in {
    animation: fadeIn 0.5s ease-in;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Erro <i class="fa fa-times"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Erro</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="" style="margin: 40px auto;">
      <div class="card-body text-center p-5">
        <div class="error-container">
          <!-- Ícone de erro moderno -->
          <div class="error-icon mb-4">
            <i class="fa fa-exclamation-circle fa-5x text-danger"></i>
          </div>

          <!-- Título -->
          <h2 class="error-title text-danger mb-20">
            <i class="fa fa-exclamation-triangle me-2"></i>Foi detectado um erro!
          </h2>

          <!-- Mensagem de erro -->
          <div class="error-message mb-20 p-4 bg-light rounded">
            <p class="mb-0 p-20 text-dark" style="font-size: 1.2em;">
              <i class="fa fa-bug me-2 text-danger"></i>
              <?php echo htmlspecialchars($mensagem) ?>
            </p>
          </div>

          <!-- Botão de voltar -->
          <div class="error-actions mb-20">
            <a href="javascript:history.back()" class="btn btn-primary btn-lg">
              <i class="fa fa-arrow-left me-2"></i> VOLTAR
            </a>

            <a href="index.php" class="btn btn-outline-secondary btn-lg ms-2">
              <i class="fa fa-home me-2"></i> PÁGINA INICIAL
            </a>
          </div>

          <!-- Informações adicionais -->
          <div class="error-info text-muted">
            <p class="small">
              <i class="fa fa-info-circle me-1"></i>
              Se você acredita que o erro está no SiSCanT, entre em contato com o suporte.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>

</html>
<?php $conexao = null; ?>