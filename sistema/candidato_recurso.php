<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$conexao = new Conexao();

$selecao = $conexao->get_selecao_id($_SESSION['selecao']);

$data_inicio_recurso = $selecao[0]['data_inicio_recurso'];
$data_fim_recurso = $selecao[0]['data_fim_recurso'];
$data_inicio_recurso = trata_data($data_inicio_recurso);
$data_fim_recurso = trata_data($data_fim_recurso);
$data_hoje = date('d-m-Y');

$mostrar_recursos = false;

if (isset($data_inicio_recurso) && isset($data_fim_recurso) && $data_hoje < $data_fim_recurso && $data_hoje > $data_inicio_recurso) {
  $mostrar_recursos = true;
}
?>

<style>
  .badge {
    background-color: var(--primary-color);
    font-size: 1em;
    padding: 6px 10px;
    border-radius: 6px;
  }

  .badge.bg-danger {
    background-color: #dc3545;
  }

  .badge.bg-primary {
    background-color: var(--primary-color);
  }

  .text-primary {
    color: var(--primary-color) !important;
  }

  .text-center {
    text-align: center;
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .card-header {
      font-size: 18px;
      padding: 12px 15px;
    }
  }

  @media (max-width: 576px) {
    .card-header {
      font-size: 16px;
      padding: 10px 12px;
    }
  }

  .text-danger {
    color: #dc3545 !important;
  }

  .text-info {
    color: #0dcaf0 !important;
  }

  .text-warning {
    color: #ffc107 !important;
  }

  .text-primary {
    color: var(--primary-color) !important;
  }

  /* Cores específicas para os ícones */
  .text-primary {
    color: #006400 !important;
  }

  .text-info {
    color: #0dcaf0 !important;
  }

  .text-warning {
    color: #ffc107 !important;
  }

  .text-danger {
    color: #dc3545 !important;
  }

  /* Layout flex para alinhamento */
  .d-flex {
    display: flex !important;
  }

  .justify-content-between {
    justify-content: space-between !important;
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .documento-info {
      flex-direction: column;
      text-align: center;
      gap: 8px;
    }
  }

  .recurso-card {
    transition: transform 0.2s ease;
  }

  .recurso-card:hover {
    transform: translateY(-2px);
  }

  @media (max-width: 576px) {
    .d-flex {
      flex-direction: column;
      gap: 5px;
    }

    .d-flex.justify-content-between {
      text-align: center;
    }
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Recurso <i class="fa fa-file-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
      </ul>
    </div>
  </div>
  <!-- Seção de Upload de Recurso -->

  <div class="card">
    <div class="card-header mb-20">
      <span class="card-title mb-0"><i class="fa fa-file-text"></i> Adicionar Recurso</span>
    </div>
    <div class="card-body">
      <?php if (insere_recurso()): ?>
        <form method="post" action="arquivo_upload_recurso_candidato.php" enctype="multipart/form-data" class="needs-validation" novalidate>
          <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">

          <div class="row g-3">
            <div class="col-md-12 mb-20">
              <label for="arquivo" class="form-label mb-20">
                Adicione seu recurso
                <small class="text-danger">* Máximo 5MB no formato PDF</small>
              </label>
              <input type="file"
                class="form-control"
                id="arquivo"
                name="arquivo"
                accept=".pdf"
                required>
              <div class="invalid-feedback">
                Por favor, selecione um arquivo PDF de até 5MB.
              </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-upload me-2"></i> Enviar Recurso
              </button>
            </div>
          </div>
        </form>
      <?php else: ?>
        <div class="alert alert-warning text-center mb-0">
          <i class="fa fa-exclamation-triangle me-2"></i>
          <strong>O período para recursos está fechado!</strong>
        </div>
      <?php endif; ?>
    </div>
  </div>



  <!-- Listagem de Recursos -->
  <div class="card mt-4">
    <div class="card-header mb-20">
      <span class="card-title mb-0"><i class="fa fa-file-text"></i> Meus Recursos</span>
    </div>
    <div class="card-body">
      <?php
      $lista_recursos = $conexao->get_recursos_candidato($_SESSION['id_usuario']);

      if (empty($lista_recursos)): ?>
        <div class="text-center py-4">
          <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
          <p class="text-muted">Nenhum recurso enviado até o momento.</p>
        </div>
      <?php else: ?>

        <?php foreach ($lista_recursos as $recurso):
          $data_abertura = $recurso['data_abertura'] ? trata_data($recurso['data_abertura']) : '--';

          $status_config = [
            'deferido' => ['label' => 'Deferido', 'class' => 'success', 'icon' => 'fa-check-circle'],
            'deferido_parcialmente' => ['label' => 'Deferido Parcialmente', 'class' => 'info', 'icon' => 'fa-adjust'],
            'indeferido' => ['label' => 'Indeferido', 'class' => 'danger', 'icon' => 'fa-times-circle'],
            'pendente' => ['label' => 'Pendente', 'class' => 'warning', 'icon' => 'fa-clock']
          ];

          $status_key = $recurso['status_final'] ?? 'pendente';
          $status = $status_config[$status_key] ?? $status_config['pendente'];
          $justificativa = trim($recurso['paragrafo1'] . ' ' . $recurso['paragrafo2']);
        ?>
          <div class="col-12">
            <div class="card border-0 shadow-sm recurso-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <h4 class="card-title text-primary mb-1">Etapa <?= htmlspecialchars($recurso['etapa']) ?></h4>
                    <p class="text-muted">Data de abertura: <?= $data_abertura ?></p>
                  </div>
                  <span class="badge bg-<?= $status['class'] ?>" style="height: 30px;">
                    <i class="fa <?= $status['icon'] ?> me-1"></i>
                    <?= $status['label'] ?>
                  </span>
                </div>

                <div class="recurso-preview">
                  <?php if (!empty($justificativa)): ?>
                    <p class="card-text text-dark">
                      <?= nl2br(htmlspecialchars($recurso['paragrafo1'])) ?><br>
                      <?= nl2br(htmlspecialchars($recurso['paragrafo2'])) ?>
                    </p>
                  <?php endif; ?>
                </div>

                <div class="recurso-detalhes collapse">
                  <?php if (!empty($justificativa)): ?>
                    <p class="card-text text-dark">
                      <?= nl2br(htmlspecialchars($justificativa)) ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

      <?php endif; ?>
    </div>
  </div>

</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
  $('#tabela_dinamica').DataTable();
</script>

<script>
  document.querySelectorAll('.toggle-detalhes').forEach(button => {
    button.addEventListener('click', function() {
      const target = this.closest('.card-body').querySelector('.recurso-detalhes');
      const verMais = this.querySelector('.ver-mais');
      const verMenos = this.querySelector('.ver-menos');

      if (target.classList.contains('show')) {
        verMais.style.display = 'inline';
        verMenos.style.display = 'none';
      } else {
        verMais.style.display = 'none';
        verMenos.style.display = 'inline';
      }
    });
  });
</script>
</body>

</html>
<?php $conexao = null; ?>