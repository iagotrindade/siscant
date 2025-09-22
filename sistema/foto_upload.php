<?php
require 'menu.php';

?>
<style>
  :root {
    --primary-color: #006400;
    --primary-light: #228B22;
    --secondary-color: #6c757d;
    --accent-color: #32CD32;
    --light-bg: #f0f8f0;
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }

  .upload-container {
    margin: 0 auto;
  }

  .card {
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    border: none;
    margin-bottom: 20px;
    transition: var(--transition);
    background: white;
    overflow: hidden;
  }

  .card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
  }

  .card-body {
    padding: 25px;
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

  .photo-preview {
    width: 150px;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 100, 0, 0.2);
    border: 3px solid white;
    transition: var(--transition);
  }

  .photo-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 100, 0, 0.3);
  }

  .upload-area {
    border: 2px dashed #ccc;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    transition: var(--transition);
    background-color: #f8f9fa;
    cursor: pointer;
  }

  .upload-area:hover {
    border-color: var(--primary-color);
    background-color: #e8f4e8;
  }

  .upload-area.dragover {
    border-color: var(--primary-color);
    background-color: #d8edd8;
    transform: scale(1.02);
  }

  .upload-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 15px;
  }

  .file-input {
    display: none;
  }

  .requirements {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
  }

  .requirement-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
  }

  .requirement-icon {
    color: var(--primary-color);
    margin-right: 10px;
    width: 20px;
  }

  @media (max-width: 768px) {
    .card-body {
      padding: 15px;
    }

    .upload-area {
      padding: 20px;
    }

    .photo-container {
      margin-top: 20px;
    }
  }

  .fade-in {
    animation: fadeIn 0.5s ease-in;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .file-name {
    margin-top: 10px;
    font-weight: 500;
    color: var(--primary-color);
    word-break: break-word;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Minha foto <i class="fa fa-picture-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Página Inicial</a></li>
      </ul>
    </div>
  </div>
  <div class="upload-container">
    <div class="card fade-in">
      <div class="card-header">
        <h4 class="mb-0">
          <i class="fa fa-camera me-2"></i> Upload de Foto
        </h4>
      </div>
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-7">
            <div class="upload-area" id="uploadArea">
              <div class="upload-icon">
                <i class="fa fa-cloud-upload-alt"></i>
              </div>
              <h5>Arraste e solte sua foto aqui</h5>
              <p class="text-muted">ou</p>
              <label for="fotoInput" class="btn btn-primary">
                <i class="fa fa-folder-open me-2"></i>Selecionar Arquivo
              </label>
              <div id="fileName" class="file-name"></div>



              <div class="requirements mt-4">
                <h5><i class="fa fa-info-circle"></i> Requisitos da foto:</h5>
                <div class="requirement-item">
                  <span class="requirement-icon"><i class="fa fa-check-circle"></i></span>
                  <span>Formato: PNG, JPEG ou JPG</span>
                </div>
                <div class="requirement-item">
                  <span class="requirement-icon"><i class="fa fa-check-circle"></i></span>
                  <span>Tamanho máximo: 2MB</span>
                </div>
                <div class="requirement-item">
                  <span class="requirement-icon"><i class="fa fa-check-circle"></i></span>
                  <span>Dimensões recomendadas: 3x4</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-5">
            <div class="photo-container text-center">
              <h5 class="mb-10">Foto atual do perfil</h5>
              <img src="<?php echo "fotos/$usuario_foto" ?>"
                class="photo-preview"
                alt="Foto do perfil"
                id="previewFoto">
              <p class="text-muted mt-2">Pré-visualização</p>
            </div>
          </div>
        </div>

        <form method="post" action="usuario_upload_foto.php" enctype="multipart/form-data" id="uploadForm">
          <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema'] . "freitas"); ?>">
          <input type="file" id="fotoInput" name="foto" class="file-input" accept=".png,.jpg,.jpeg" style="margin-left: -1000px;" />

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg" id="submitButton" disabled>
              <i class="fa fa-upload me-2"></i> Enviar Foto
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fotoInput');
    const fileName = document.getElementById('fileName');
    const submitButton = document.getElementById('submitButton');
    const uploadForm = document.getElementById('uploadForm');
    const previewFoto = document.getElementById('previewFoto');

    // Clique na área de upload abre o input
    uploadArea.addEventListener('click', function(e) {
      // evita conflito caso clique no label dentro da área
      if (!e.target.closest("label")) {
        fileInput.value = ""; // força reset (permite selecionar o mesmo arquivo)
        fileInput.click();
      }
    });

    // Alteração no input de arquivo
    fileInput.addEventListener('change', function(e) {
      handleFiles(e.target.files);
    });

    // Eventos drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    uploadArea.addEventListener('dragenter', () => uploadArea.classList.add('dragover'));
    uploadArea.addEventListener('dragover', () => uploadArea.classList.add('dragover'));
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', () => uploadArea.classList.remove('dragover'));

    // Soltar arquivo na área
    uploadArea.addEventListener('drop', function(e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length > 0) {
        fileInput.files = files; // joga no input
        handleFiles(files);
      }
    });

    // Função para validar e exibir nome + preview
    function handleFiles(files) {
      if (files.length > 0) {
        const file = files[0];

        // Tipos permitidos
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
          alert('Por favor, selecione apenas arquivos PNG, JPEG ou JPG.');
          fileInput.value = '';
          submitButton.disabled = true;
          fileName.textContent = '';
          previewFoto.src = "<?php echo "fotos/$usuario_foto" ?>"; // volta para a original
          return;
        }

        // Tamanho máximo (2MB = 2097152 bytes)
        if (file.size > 2097152) {
          alert('O arquivo é muito grande. O tamanho máximo permitido é 2MB.');
          fileInput.value = '';
          submitButton.disabled = true;
          fileName.textContent = '';
          previewFoto.src = "<?php echo "fotos/$usuario_foto" ?>"; // volta para a original
          return;
        }

        // Mostrar nome
        fileName.textContent = file.name;
        submitButton.disabled = false;

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
          previewFoto.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }

    // Impedir envio sem arquivo selecionado
    uploadForm.addEventListener('submit', function(e) {
      if (!fileInput.files.length) {
        e.preventDefault();
        alert('Por favor, selecione uma foto para enviar.');
      }
    });
  });
</script>


</html>
<?php $conexao = null; ?>