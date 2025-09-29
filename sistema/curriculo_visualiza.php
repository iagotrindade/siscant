<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
?>

<style>
  .card-header {
    font-size: 20px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #006400;
    box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15);
  }

  .input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
  }

  .card-checkbox {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
    height: 100%;
  }

  .card-checkbox:hover {
    background: #e9ecef;
    border-color: #006400;
    transform: translateY(-2px);
  }

  .form-check-input {
    width: 20px;
    height: 20px;
    margin-right: 10px;
  }

  .form-check-input:checked {
    background-color: #006400;
    border-color: #006400;
  }

  .form-check-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    line-height: 1.4;
  }

  .table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    padding: 12px 15px;
  }

  .table td {
    padding: 12px 15px;
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
  }

  .badge {
    background-color: var(--primary-color);
    font-size: 0.85em;
    padding: 6px 10px;
    border-radius: 6px;
  }

  .curriculo-info {
    display: flex;
    align-items: center;
  }

  .fw-semibold {
    font-weight: 600;
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .row.g-3 {
      margin-bottom: -1rem;
    }

    .col-lg-3,
    .col-lg-12 {
      margin-bottom: 1rem;
    }

    .card-checkbox {
      padding: 15px;
      text-align: center;
    }

    .table-responsive {
      font-size: 0.8rem;
    }

    .table-header-custom th {
      padding: 12px 8px;
      font-size: 0.8rem;
    }

    .table-row-custom td {
      padding: 10px 8px;
    }
  }

  .tooltip-inner {
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.8rem;
  }

  /* Melhorias no formulário */
  .form-label {
    margin-bottom: 8px;
  }

  .input-group {
    border-radius: 8px;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Currículos (PONTUAÇÃO) <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Currículos</li>
      </ul>
    </div>
  </div>
  <div class="row" <?php if ($_SESSION['perfil'] != "admin") echo "hidden"; ?>>
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header bg-primary text-white mb-20">
          <span class="mb-0"><i class="fa fa-graduation-cap"></i> Cadastrar Opção de Currículo</span>
        </div>
        <div class="card-body">
          <form action="../banco_dados/curriculo_cadastra.php" method="post">
            <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

            <div class="row g-3">
              <div class="col-lg-12 mb-20">
                <label class="form-label fw-bold">Nome do currículo:</label>
                <input type="text" name="nome_curriculo" class="form-control form-control-lg"
                  maxlength="400" placeholder="Digite o nome da opção de currículo" required>
              </div>

              <div class="col-lg-3 mb-20">
                <label class="form-label fw-bold">Pontuação:</label>
                <div>
                  <input type="text" name="pontuacao" class="form-control" maxlength="200"
                    placeholder="0.00" required>
                </div>
              </div>

              <div class="col-lg-3 mb-20">
                <label class="form-label fw-bold">Quantidade máxima de uploads:</label>
                <input type="number" name="quantidade_maxima" class="form-control"
                  min="1" max="99" placeholder="Ex: 5" required>
              </div>

              <div class="col-lg-3 mb-20">
                <label class="form-label fw-bold">Pode ser multiplicado?</label><br>
                <select name="multiplicador" class="form-control" id="ott_stt">
                  <option value="">Não multiplicar</option>
                  <?php
                  for ($i = 2; $i <= 1000; $i++) {
                    echo '<option value="' . $i . '">' . $i . ' vezes</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-lg-3 mb-20">
                <div class="form-check card-checkbox h-100">
                  <input class="form-check-input" type="checkbox" name="carga_horaria_obrigatoria"
                    id="carga_horaria_obrigatoria">
                  <label class="form-check-label" for="carga_horaria_obrigatoria">
                    <i class="fa fa-clock-o me-2"></i>
                    <span>Experiência profissional<br><small class="text-muted">(Carga horária obrigatória)</small></span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="fa fa-save me-2"></i> CADASTRAR OPÇÃO DE CURRÍCULO
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-success text-white mb-20">
          <span class="mb-0"><i class="fa fa-list me-2"></i> Opções de Currículo Cadastradas</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-modern" id="tabela_dinamica">
              <thead class="table-header-custom">
                <tr>
                  <th><i class="fa fa-hashtag"></i> ID</th>
                  <th><i class="fa fa-graduation-cap"></i> Nome do Currículo</th>
                  <th class="text-center"><i class="fa fa-star"></i> Pontuação</th>
                  <th class="text-center"><i class="fa fa-upload"></i> Qtd Máx Uploads</th>
                  <th class="text-center"><i class="fa fa-copy"></i> Multiplicador</th>
                  <th class="text-center"><i class="fa fa-clock-o"></i> Carga Horária</th>
                  <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $lista_docs_obrigatorios = $conexao->get_curriculo_cadastrados();

                foreach ($lista_docs_obrigatorios as $linha) {
                  $pontuacao = $linha['pontuacao'] / 1000;
                  $carga = $linha['carga_horaria_obrigatoria'] == 1 ?
                    "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" :
                    "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";

                  $multiplicacao = "Não";
                  if ($linha['multiplicacao'] == '1') {
                    $multiplicacao = "<span class='badge bg-info'><i class='fa fa-check'></i> Sim</span>";
                  }
                  if ($linha['quantidade_multiplicacao'] != null) {
                    $multiplicacao = "<span class='badge bg-warning text-dark'>" . $linha['quantidade_multiplicacao'] . "x</span>";
                  }

                  echo '
                                <tr class="table-row-custom">
                                    <td><span class="badge bg-dark">' . $linha['id'] . '</span></td>
                                    <td>
                                        <div class="curriculo-info">
                                            <i class="fa fa-file-alt text-primary me-2"></i>
                                            <span class="fw-semibold">' . $linha['nome'] . '</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge">' . $pontuacao . '</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">' . $linha['quantidade_maxima_uploads'] . '</span>
                                    </td>
                                    <td class="text-center">' . $multiplicacao . '</td>
                                    <td class="text-center">' . $carga . '</td>
                                    <td class="text-center">
                                        <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'curriculo\')" 
                                           class="btn btn-sm action-btn"
                                           data-bs-toggle="tooltip" 
                                           title="Excluir opção de currículo">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
  $('#tabela_dinamica').DataTable({
    "order": [
      [1, "asc"]
    ]
  });
</script>
</body>

</html>