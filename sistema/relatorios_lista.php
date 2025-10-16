<?php
include_once 'menu.php';

if ($perfil == "ouvidor") {
  erro("Erro: 24373478568! Não foi possível abrir a página");
  exit();
}

?>

<style>
  .dashboard-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
  }

  .card-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-color: #006400;
  }

  .card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.3rem;
    flex-shrink: 0;
    background: linear-gradient(135deg, #006400, #228B22);
  }

  .card-content {
    flex: 1;
  }

  .card-content h5 {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
    font-size: 1.6rem;
  }

  .card-content p {
    font-size: 1.3rem;
    color: #666;
    margin-bottom: 0;
    line-height: 1.4;
  }

  .card-link {
    text-decoration: none;
    color: inherit;
  }

  .card-link:hover {
    text-decoration: none;
    color: inherit;
  }

  /* Ajustes de espaçamento */
  .card-body {
    padding: 25px;
  }

  /* Estados dos cards */
  .dashboard-card {
    position: relative;
    overflow: hidden;
  }

  .dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 100, 0, 0.03), transparent);
    transition: left 0.6s ease;
  }

  .dashboard-card:hover::before {
    left: 100%;
  }

  @media (max-width: 768px) {
    .dashboard-card {
      flex-direction: column;
      text-align: center;
      padding: 20px 15px;
      gap: 12px;
    }

    .card-icon {
      width: 50px;
      height: 50px;
      font-size: 1.25rem;
    }

    .card-content h5 {
      font-size: 0.95rem;
    }

    .card-body {
      padding: 20px 15px;
    }

    .card-header {
      padding: 15px 20px;
    }
  }

  /* Melhorias na tipografia */
  .card-content h5 {
    font-weight: 600;
    letter-spacing: -0.01em;
  }

  .card-content p {
    opacity: 0.8;
  }

  /* Efeitos de foco para acessibilidade */
  .card-link:focus {
    outline: 2px solid #006400;
    outline-offset: 2px;
    border-radius: 12px;
  }

  /* Transições suaves */
  .dashboard-card,
  .card-icon,
  .card-header {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  /* Sombra mais suave no hover */
  .card-hover:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  }
</style>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Relatórios <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatórios</li>
      </ul>
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      <!-- Candidatos e Especialidades -->
      <div class="card mb-4" <?php if ($perfil == "documentos") echo "hidden"; ?>>
        <div class="card-header bg-primary text-white">
          <span class="mb-0"><i class="fa fa-users me-2"></i> Candidatos e Especialidades</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="relatorio_especialidade_quantidade.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-bar-chart"></i>
                  </div>
                  <div class="card-content">
                    <h5>Especialidade X Quantidade</h5>
                    <p>Relatório de quantitativo por especialidade</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6">
              <a href="relatorio_especialidade_candidato.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-graduation-cap"></i>
                  </div>
                  <div class="card-content">
                    <h5>Candidato X Especialidade</h5>
                    <p>Relação de candidatos por especialidade</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="relatorio_status_especialidade.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-tasks"></i>
                  </div>
                  <div class="card-content">
                    <h5>Status da Especialidade</h5>
                    <p>Status atual das especialidades</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="relatorio_etapa_III.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-stethoscope"></i>
                  </div>
                  <div class="card-content">
                    <h5>Inspeção de Saúde</h5>
                    <p>Status atual das Inspeções de Saúde</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="candidato_lista.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="card-content">
                    <h5>Candidatos Participando</h5>
                    <p>Candidatos que estão concorrendo na Seleção</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="candidato_lista_desclassificados.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="card-content">
                    <h5>Candidatos Desclassificados</h5>
                    <p>Candidatos que NÃO estão concorrendo na Seleção</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="relatorio_cotistas.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="card-content">
                    <h5>Cotistas da Seleção</h5>
                    <p>Informações sobre os Cotistas</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-xl-4 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="usuario_lista.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="card-content">
                    <h5>Usuários</h5>
                    <p>Informações dos usuários cadastrados na Seleção</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Documentos Obrigatórios -->
      <div class="card mb-4" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
        <div class="card-header bg-primary text-dark">
          <span class="mb-0"><i class="fa fa-file-text-o me-2"></i> Documentos Obrigatórios</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-xl-6 col-md-6">
              <a href="candidato_lista_docs_obrigatorios.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-check"></i>
                  </div>
                  <div class="card-content">
                    <h5>Avaliação de Docs Obrigatórios</h5>
                    <p>Verificação de documentos obrigatórios</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-6 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="doc_obrigatorio_faltando_candidato.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-exclamation-triangle"></i>
                  </div>
                  <div class="card-content">
                    <h5>Documentos Faltantes</h5>
                    <p>Documentos obrigatórios em falta</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagamentos -->
      <div class="card mb-4" <?php if ($perfil != "admin" && $perfil != "consulta" || $_SESSION['selecao_pagamento'] == null || $_SESSION['selecao_pagamento'] == 0) echo "hidden"; ?>>
        <div class="card-header bg-primary text-white">
          <span class="mb-0"><i class="fa fa-usd me-2"></i> Pagamentos</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-xl-6 col-md-6">
              <a href="gru_pagas.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-money"></i>
                  </div>
                  <div class="card-content">
                    <h5>Pagamentos da GRU</h5>
                    <p>Controle de pagamentos realizados</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-6 col-md-6" <?php if ($perfil == "avaliador") echo "hidden"; ?>>
              <a href="relatorio_isentos_pagamento.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-check"></i>
                  </div>
                  <div class="card-content">
                    <h5>Isentos do Pagamento</h5>
                    <p>Candidatos com isenção de taxa</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Currículos -->
      <div class="card mb-4" <?php if ($perfil != "admin" && $perfil != "consulta" && $perfil != "avaliador") echo "hidden"; ?>>
        <div class="card-header bg-primary text-white">
          <span class="mb-0"><i class="fa fa-graduation-cap me-2"></i> Currículos</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-xl-3 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="curriculos_faltando_candidatos.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-search"></i>
                  </div>
                  <div class="card-content">
                    <h5>Currículos Faltantes</h5>
                    <p>Candidatos sem currículo cadastrado</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-3 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="pericia_curricular.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-exclamation"></i>
                  </div>
                  <div class="card-content">
                    <h5>Perícia Curricular</h5>
                    <p>Análise detalhada dos currículos</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-3 col-md-6">
              <a href="pontuacao_nao_avaliada.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-eye"></i>
                  </div>
                  <div class="card-content">
                    <h5>Auditoria Curricular</h5>
                    <p>Verificação de pontuações</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-3 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="pontuacao_automatica.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-percent"></i>
                  </div>
                  <div class="card-content">
                    <h5>Pontuação Automática</h5>
                    <p>Cálculo automático de pontuações</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Outros -->
      <div class="card mb-4" <?php if ($perfil != "admin" && $perfil != "consulta" && $perfil != 'avaliador') echo "hidden"; ?>>
        <div class="card-header bg-primary text-white">
          <span class="mb-0"><i class="fa fa-th-large me-2"></i> Outros Relatórios</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-xl-4 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="relatorios.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-print"></i>
                  </div>
                  <div class="card-content">
                    <h5>Publicações e Documentos</h5>
                    <p>Geração de documentos oficiais</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="candidatos_tempo_sv_publico.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <div class="card-content">
                    <h5>Serviço Militar e Idade</h5>
                    <p>Análise de tempo de serviço</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6">
              <a href="relatorio_recursos_candidato.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-gavel"></i>
                  </div>
                  <div class="card-content">
                    <h5>Recursos</h5>
                    <p>Gerenciamento de recursos</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="relatorio_processos_judiciais.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-balance-scale"></i>
                  </div>
                  <div class="card-content">
                    <h5>Processos Judiciais</h5>
                    <p>Controle de processos judiciais</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="relatorio_incorporados.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-exchange"></i>
                  </div>
                  <div class="card-content">
                    <h5>Distribuídos</h5>
                    <p>Candidatos distribuídos</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 col-md-6" <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden"; ?>>
              <a href="relatorio_prioridades_especialidade.php" class="card-link">
                <div class="dashboard-card card-hover">
                  <div class="card-icon bg-primary">
                    <i class="fa fa-star"></i>
                  </div>
                  <div class="card-content">
                    <h5>Prioridade das Especialidades</h5>
                    <p>Hierarquia de especialidades</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Adicionar efeitos de hover dinâmicos
    const cards = document.querySelectorAll('.dashboard-card');

    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
      });

      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
      });
    });
  });
</script>
</body>

</html>
<?php $conexao = null; ?>