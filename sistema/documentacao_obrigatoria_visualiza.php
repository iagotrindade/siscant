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

  .form-control:focus {
    border-color: #006400;
    box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15);
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
    width: 18px;
    height: 18px;
    margin-top: 0.2rem;
  }

  .form-check-input:checked {
    background-color: #006400;
    border-color: #006400;
  }

  .form-check-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
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

  .documento-info {
    display: flex;
    align-items: center;
  }

  .fw-semibold {
    font-weight: 600;
  }

  /* Responsividade */
  @media (max-width: 768px) {
    .card-checkbox {
      margin-bottom: 10px;
      padding: 12px;
    }

    .table-responsive {
      font-size: 0.8rem;
    }
  }

  /* Estados da tabela */
  .table-modern tbody tr:nth-child(even) {
    background-color: #fafafa;
  }

  .tooltip-inner {
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.8rem;
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Documentos obrigatórios <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Documentos obrigatórios</li>
      </ul>
    </div>
  </div>

  <div class="row">

    <div class="col-md-12">

      <!-- Card de Cadastro de Documentos Obrigatórios -->
      <div class="card">
        <div class="card-header bg-primary text-white mb-20">
          <span class="mb-0"><i class="fa fa-file-text-o"></i> Cadastrar Documento Obrigatório</span>
        </div>
        <div class="card-body">
          <form action="../banco_dados/arquivo_obrigatorio_cadastro.php" method="post">
            <div class="row" <?php if ($_SESSION['perfil'] != "admin") echo "hidden"; ?>>
              <div class="col-lg-12 mb-20">
                <label class="form-label fw-bold">Nome do documento obrigatório:</label>
                <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['chave'] . "ten_freitas"); ?>">
                <input type="text" name="nome_arquivo_obrigatorio" class="form-control form-control-lg"
                  maxlength="350" placeholder="Digite o nome do documento obrigatório" required>
              </div>

              <div class="col-lg-12 mb-20">
                <label class="form-label fw-bold mb-3">Filtros de aplicação:</label>
                <div class="row">
                  <div class="col-xl-2 col-md-4 col-sm-6 mb-20">
                    <div class="form-check card-checkbox">
                      <input class="form-check-input" type="checkbox" name="mulher" id="mulher">
                      <label class="form-check-label" for="mulher">
                        <i class="fa fa-female"></i> Para Mulheres
                      </label>
                    </div>
                  </div>
                  <div class="col-xl-2 col-md-4 col-sm-6 mb-20">
                    <div class="form-check card-checkbox">
                      <input class="form-check-input" type="checkbox" name="militar_ativa" id="militar_ativa">
                      <label class="form-check-label" for="militar_ativa">
                        <i class="fa fa-shield"></i> Para Militares da Ativa
                      </label>
                    </div>
                  </div>
                  <div class="col-xl-2 col-md-4 col-sm-6 mb-20">
                    <div class="form-check card-checkbox">
                      <input class="form-check-input" type="checkbox" name="reservista" id="reservista">
                      <label class="form-check-label" for="reservista">
                        <i class="fa fa-user"></i> Para Reservistas
                      </label>
                    </div>
                  </div>
                  <div class="col-xl-2 col-md-4 col-sm-6 mb-20">
                    <div class="form-check card-checkbox">
                      <input class="form-check-input" type="checkbox" name="cdi" id="cdi">
                      <label class="form-check-label" for="cdi">
                        <i class="fa fa-id-card"></i> Para Quem tem CDI
                      </label>
                    </div>
                  </div>
                  <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="form-check card-checkbox">
                      <input class="form-check-input" type="checkbox" name="vaga_reservada" id="vaga_reservada">
                      <label class="form-check-label" for="vaga_reservada">
                        <i class="fa fa-star"></i> Para Vaga Reservada
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="alert alert-info">
                  <h5><i class="fa fa-info-circle"></i> Instruções importantes:</h5>
                  <ul class="mb-0">
                    <li>Se não for selecionada nenhuma opção, o documento irá aparecer para <strong>todos</strong> os candidatos.</li>
                    <li>Se for selecionado 'Para Mulheres' o documento cadastrado só será obrigatório para mulheres.</li>
                    <li>O documento obrigatório só irá aparecer para o candidato caso ele cumpra <strong>TODAS</strong> as opções selecionadas.</li>
                    <li><strong>Exemplo:</strong> Se foi selecionado "Para Mulheres" e "Para reservistas" o documento só irá aparecer para mulheres reservistas!</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save"></i> CADASTRAR DOCUMENTO OBRIGATÓRIO
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabela de Documentos Cadastrados -->
      <div class="card">
        <div class="card-header bg-success text-white mb-20">
          <span class="mb-0"><i class="fa fa-list-ol"></i> Documentos Obrigatórios Cadastrados</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-modern" id="tabela_dinamica">
              <thead class="table-header-custom">
                <tr>
                  <th><i class="fa fa-file-text-o me-1"></i> Documento Obrigatório</th>
                  <th class="text-center"><i class="fa fa-female me-1"></i> Mulheres</th>
                  <th class="text-center"><i class="fa fa-shield"></i> Militares Ativa</th>
                  <th class="text-center"><i class="fa fa-user"></i> Reservistas</th>
                  <th class="text-center"><i class="fa fa-id-card me-1"></i> CDI</th>
                  <th class="text-center"><i class="fa fa-star me-1"></i> Vaga Reservada</th>
                  <th class="text-center"><i class="fa fa-cogs me-1"></i> Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $lista_docs_obrigatorios = $conexao->get_documentos_obrigatorios_cadastrados();

                foreach ($lista_docs_obrigatorios as $linha) {
                  $mulher = $linha['mulher'] == '1' ? "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" : "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";
                  $militar_ativa = $linha['militar_ativa'] == '1' ? "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" : "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";
                  $reservista = $linha['reservista'] == '1' ? "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" : "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";
                  $cdi = $linha['cdi'] == '1' ? "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" : "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";
                  $vaga_reservada = $linha['vaga_reservada'] == '1' ? "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" : "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";

                  $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id']);

                  echo '
                        <tr class="table-row-custom">
                            <td>
                                <div class="documento-info">
                                    <i class="fa fa-file-text-o text-primary mr-10"></i>
                                    <span class="fw-semibold">' . $linha['nome'] . '</span>
                                </div>
                            </td>
                            <td class="text-center">' . $mulher . '</td>
                            <td class="text-center">' . $militar_ativa . '</td>
                            <td class="text-center">' . $reservista . '</td>
                            <td class="text-center">' . $cdi . '</td>
                            <td class="text-center">' . $vaga_reservada . '</td>
                            <td class="text-center">
                                <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'documentacao_obrigatoria\',\'' . $crip . '\')" 
                                   class="btn btn-sm action-btn"
                                   data-bs-toggle="tooltip" 
                                   title="Excluir documento">
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
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
  $('#tabela_dinamica').DataTable({
    "ordering": false
  });
</script>


</body>

</html>