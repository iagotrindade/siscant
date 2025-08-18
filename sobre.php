<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sobre - SiSCanT</title>
    <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #006400;
            /* Verde militar */
            --secondary-color: #f8f9fa;
            --accent-color: #ffc107;
            --text-dark: #212529;
            --text-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 280px;
            background-color: white;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar img {
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .sidebar .nav {
            width: 100%;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar .nav-link {
            color: var(--text-dark);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(0, 100, 0, 0.1);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .content-area {
            flex: 1;
            background-color: var(--secondary-color);
            padding: 2rem;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
        }

        /* Estilos específicos da página Sobre */
        .about-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .about-card h2 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .team-member {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            margin-bottom: 1rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
            margin-left: 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 2px solid white;
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1.5rem;
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .sidebar img {
                max-width: 140px;
                margin-bottom: 1rem;
            }

            .content-area {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="sidebar">
            <img src="sistema/imagens/3rm.png" alt="Logo SiSCanT" class="logo">

            <h5 class="mb-4 text-center">SiSCanT<br><small class="text-muted">Sistema de Seleção de Candidatos Temporários</small></h5>

            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="3rm.php">
                        <i class="bi bi-house-door"></i> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="sobre.php">
                        <i class="bi bi-info-circle"></i> Sobre
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ajuda.php">
                        <i class="bi bi-question-circle"></i> Ajuda
                    </a>
                </li>
            </ul>
        </div>

        <!-- Área de conteúdo -->
        <div class="content-area">
            <div class="header">
                <h1>Sobre o SiSCanT</h1>
            </div>

            <!-- Card de Introdução -->
            <div class="about-card">
                <h2><i class="bi bi-cpu"></i> O Sistema</h2>
                <p class="lead">O SiSCanT (Sistema de Seleção de Candidatos Temporários) é uma plataforma desenvolvida para otimizar o processo seletivo de pessoal temporário no ambito da 3ª Região Militar.</p>

                <div class="row mt-4">
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h4>Agilidade</h4>
                        <p>Candidatos não precisam mais comparecer á Comissão em diversas Etapas do Processo Seletivo</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4>Segurança</h4>
                        <p>Sistema com criptografia e auditoria completa de todas as ações.</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Transparência</h4>
                        <p>Processo totalmente rastreável com relatórios detalhados e informações precisas.</p>
                    </div>
                </div>
            </div>

            <!-- Card de História -->
            <div class="about-card">
                <h2><i class="bi bi-clock-history"></i> Nossa História</h2>

                <div class="timeline">
                    <div class="timeline-item">
                        <h4>2018 - Concepção</h4>
                        <p>Primeiros estudos de viabilidade para modernização dos processos seletivos.</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2019 - Versão Piloto</h4>
                        <p>Implantação experimental em 2 Processos Seletivos com excelentes resultados.</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2021 - Ampliação</h4>
                        <p>Novas funcionalidades com expansão para outros Processos Seletivos</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2025 - Integração Nacional</h4>
                        <p>Realização de Processo Seletivo em 12 Regiões Militar ao mesmo tempo.</p>
                    </div>
                </div>
            </div>

            <!-- Card de Equipe -->
            <!--<div class="about-card">
                <h2><i class="bi bi-people-fill"></i> Equipe Responsável</h2>

                <div class="row mt-4">
                    <div class="col-md-4 team-member">
                        <img src="https://via.placeholder.com/150" alt="Cel. Silva" class="img-fluid">
                        <h4>Cel. Silva</h4>
                        <p class="text-muted">Coordenador Geral</p>
                        <small>eng.silva@siscont.mil.br</small>
                    </div>
                    <div class="col-md-4 team-member">
                        <img src="https://via.placeholder.com/150" alt="Maj. Oliveira" class="img-fluid">
                        <h4>Maj. Oliveira</h4>
                        <p class="text-muted">Desenvolvimento</p>
                        <small>ti.oliveira@siscont.mil.br</small>
                    </div>
                    <div class="col-md-4 team-member">
                        <img src="https://via.placeholder.com/150" alt="Cap. Santos" class="img-fluid">
                        <h4>Cap. Santos</h4>
                        <p class="text-muted">Suporte Técnico</p>
                        <small>suporte.santos@siscont.mil.br</small>
                    </div>
                </div>
            </div>-->

            <!-- Card de Estatísticas -->
            <div class="about-card">
                <h2><i class="bi bi-bar-chart-fill"></i> Em Números</h2>

                <div class="row text-center mt-4">
                    <div class="col-md-4">
                        <h3 class="text-primary">62+</h3>
                        <p>Processos realizados</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-primary">620k+</h3>
                        <p>Candidatos avaliados</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-primary">24/7</h3>
                        <p>Disponibilidade do sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="sistema/js/bootstrap5.3.3.js"></script>
    <script>
        // Ativar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>