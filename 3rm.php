<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiSCanT - Sistema de Seleção de Candidatos Temporários</title>
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

        .header .badge {
            background-color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .year-section {
            margin-bottom: 2.5rem;
        }

        .year-title {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .year-title h2 {
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .year-title .badge {
            background-color: var(--primary-color);
            margin-left: 1rem;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-link {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            background-color: rgba(0, 100, 0, 0.05);
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-link:hover {
            background-color: rgba(0, 100, 0, 0.15);
            color: var(--primary-color);
        }

        .btn-link i {
            margin-right: 8px;
            color: var(--primary-color);
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

        @media (max-width: 576px) {
            .card-container {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }

        /* Animação de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .year-section {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .year-section:nth-child(2) {
            animation-delay: 0.1s;
        }

        .year-section:nth-child(3) {
            animation-delay: 0.2s;
        }

        .year-section:nth-child(4) {
            animation-delay: 0.3s;
        }

        .card-header {
            position: relative;
            overflow: hidden;
        }

        .card-header::after {
            content: 'NOVO';
            position: absolute;
            top: 10px;
            right: -30px;
            background-color: var(--accent-color);
            color: var(--text-dark);
            padding: 2px 30px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .year-section:first-child .card-header::after {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="sistema/imagens/3rm.png" alt="Logo SiSCanT" class="logo">

            <h5 class="mb-4 text-center">SiSCanT<br><small class="text-muted">Sistema de Seleção de Candidatos Temporários</small></h5>

            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="3rm.php">
                        <i class="bi bi-house-door"></i> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">
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
                <h1>Processos Seletivos</h1>
                <span class="badge rounded-pill">
                    <i class="bi bi-calendar-check"></i> <?php echo date('d/m/Y'); ?>
                </span>

                <div class="search-bar">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Pesquisar processo..." id="searchInput">
                    </div>
                </div>
            </div>

            <!-- Seção 2025 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2025</h2>
                    <span class="badge rounded-pill">Atual</span>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="3rm_ott_stt_2025.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="3rm_ott_stt_2025_2.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Eng. Minas e Biblioteconomia
                            </a>

                            <a href="eipot_brasil_2025.php" class="btn-link">
                                <i class="bi bi-shield-shaded"></i> EIPOT Brasil
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="3rm_mfdv_2025.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card CET -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-tools"></i> CET - 2025
                        </div>
                        <div class="card-body">
                            <a href="3rm_cet_cruz_alta_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Cruz Alta
                            </a>
                            <a href="3rm_cet_santiago_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santiago
                            </a>
                            <a href="3rm_cet_uruguaiana_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Uruguaiana
                            </a>
                            <a href="3rm_cet_bage_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Bagé
                            </a>
                            <a href="3rm_cet_santa_maria_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santa Maria
                            </a>
                            <a href="3rm_cet_porto_alegre_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Porto Alegre
                            </a>
                            <a href="3rm_cet_pelotas_2025.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Pelotas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2024 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2024</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="3rm_ott_stt_2024.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="3rm_eipot_2024.php" class="btn-link">
                                <i class="bi bi-shield-shaded"></i> EIPOT
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="3rm_mfdv_2024.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card CET -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-tools"></i> CET - 2024
                        </div>
                        <div class="card-body">
                            <a href="3rm_cet_cruz_alta_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Cruz Alta
                            </a>
                            <a href="3rm_cet_santiago_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santiago
                            </a>
                            <a href="3rm_cet_uruguaiana_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Uruguaiana
                            </a>
                            <a href="3rm_cet_bage_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Bagé
                            </a>
                            <a href="3rm_cet_santa_maria_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santa Maria
                            </a>
                            <a href="3rm_cet_porto_alegre_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Porto Alegre
                            </a>
                            <a href="3rm_cet_pelotas_2024.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Pelotas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2023 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2023</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="3rm_ott_stt_2023.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="3rm_eipot_2023.php" class="btn-link">
                                <i class="bi bi-shield-shaded"></i> EIPOT
                            </a>
                            <a href="3rm_radio_tv_2023.php" class="btn-link">
                                <i class="bi bi-mic-fill"></i> STT Rádio e TV (Locutor)
                            </a>
                            <a href="3rm_capelao_2023.php" class="btn-link">
                                <i class="bi bi-cross"></i> Capelão - Católico
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="3rm_mfdv_2023.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card CET -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-tools"></i> CET - 2023
                        </div>
                        <div class="card-body">
                            <a href="3rm_cet_cruz_alta_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Cruz Alta
                            </a>
                            <a href="3rm_cet_santiago_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santiago
                            </a>
                            <a href="3rm_cet_uruguaiana_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Uruguaiana
                            </a>
                            <a href="3rm_cet_bage_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Bagé
                            </a>
                            <a href="3rm_cet_santa_maria_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santa Maria
                            </a>
                            <a href="3rm_cet_porto_alegre_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Porto Alegre
                            </a>
                            <a href="3rm_cet_pelotas_2023.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Pelotas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2022 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2022 - Intranet</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_stt_2022.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott__stt_2022.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Arquivologia
                            </a>

                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_eipot_2022.php" class="btn-link">
                                <i class="bi bi-shield-shaded"></i> EIPOT
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv_2022.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>

                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv__2022.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> MFDV (EAS 3)
                            </a>
                        </div>
                    </div>

                    <!-- Card CET -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-tools"></i> CET - 2022
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_01_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Cruz Alta
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_02_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santiago
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_03_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Uruguaiana
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_04_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Bagé
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_05_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santa Maria
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_06_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Porto Alegre
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_07_2022.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Pelotas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2021 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2021 - Intranet</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_stt_2021.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_stt_2021.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Gastronomia
                            </a>

                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_2021.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Museu
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv_2021.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card CET -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-tools"></i> CET - 2022
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2001.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Cruz Alta
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2002.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santiago
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2003.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Uruguaiana
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2004.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Bagé
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2005.php.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Santa Maria
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2006.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Porto Alegre
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2007.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> Pelotas
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_cet_2021.php" class="btn-link">
                                <i class="bi bi-geo-alt"></i> CET (TESTES)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2020 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2020 - Intranet</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_stt_2020.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_2020.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Eng. Minhas, Pscicologia, Museologia e Fonoaudiologia
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv_2020.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção 2019 -->
            <div class="year-section">
                <div class="year-title">
                    <h2>2019 - Intranet</h2>
                </div>

                <div class="card-container">
                    <!-- Card OTT/STT/EIPOT -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-mortarboard"></i> OTT/STT/EIPOT
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_ott_stt_2019.php" class="btn-link">
                                <i class="bi bi-mortarboard"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv_2019.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> Processo Regular
                            </a>
                        </div>
                    </div>

                    <!-- Card MFDV SISEL SMO -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-heart-pulse"></i> MFDV Permanente
                        </div>
                        <div class="card-body">
                            <a href="http://siscantintranet.3rm.eb.mil.br/3rm_mfdv_2019.php" class="btn-link">
                                <i class="bi bi-heart-pulse"></i> SMO Permanente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.btn-link').forEach(link => {

                const text = link.textContent.toLowerCase();
                link.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    </script>
    <script src="sistema/js/bootstrap5.3.3.js"></script>
</body>
</html>