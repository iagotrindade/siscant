<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajuda - SiSCanT</title>
    <link href="sistema/css/bootstrap5.3.3.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
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

    /* Estilos específicos da página de Ajuda */
    .help-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .help-card h2 {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(0, 100, 0, 0.05);
        color: var(--primary-color);
        font-weight: 500;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 100, 0, 0.1);
    }

    .accordion-item {
        margin-bottom: 0.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px !important;
    }

    .contact-card {
        background: linear-gradient(135deg, rgba(0, 100, 0, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid var(--primary-color);
    }

    .contact-method {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .contact-method i {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-right: 1rem;
        width: 40px;
        text-align: center;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        /* 16:9 */
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        margin: 2rem 0;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
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
<?php
include_once 'banco_dados/conexao.php';
$conexao = new Conexao();
?>

<body>
    <div class="main-container">
        <!-- Sidebar -->
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
                    <a class="nav-link" href="sobre.php">
                        <i class="bi bi-info-circle"></i> Sobre
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="ajuda.php">
                        <i class="bi bi-question-circle"></i> Ajuda
                    </a>
                </li>
            </ul>
        </div>

        <!-- Área de conteúdo -->
        <div class="content-area">
            <div class="header">
                <h1>Central de Ajuda</h1>
            </div>

            <!-- Card de Perguntas Frequentes -->
            <div class="help-card">
                <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes</h2>

                <div class="accordion" id="faqAccordion">
                    <!-- Item 1 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Como acessar um processo seletivo?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Para acessar um processo seletivo:</p>
                                <ol>
                                    <li>Na página inicial, localize o ano desejado</li>
                                    <li>Identifique o tipo de processo (OTT/STT, MFDV, CET, etc.)</li>
                                    <li>Clique no botão correspondente à sua ao processo desejado</li>
                                    <li>O sistema irá redirecioná-lo para a página específica do processo</li>
                                </ol>
                                <p class="text-muted"><small><i class="bi bi-info-circle"></i> Caso não tenha cadastro basta clicar em "QUERO ME CADASTRAR" na página de Login.</small></p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                O que fazer se esquecer minha senha?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Se você esqueceu sua senha:</p>
                                <ol>
                                    <li>Na página de login do processo desejado, clique em "Esqueci minha senha"</li>
                                    <li>Informe seu CPF e e-mail cadastrado</li>
                                    <li>Você receberá uma senha temporária no e-mail cadastrado</li>
                                    <li>Acesse o SiSCanT com essa senha temporária</li>
                                    <li>Siga as instruções para criar uma nova senha</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Como acompanhar o processo?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Para acompanhar sua inscrição no Processo Seletivo:</p>
                                <ol>
                                    <li>Acesse o processo seletivo em que está inscrito</li>
                                    <li>Acompanhe as publicações no site da 3ª Região Militar</li>
                                    <li>Todas as informações sobre as etapas serão publicadas no site</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Posso me inscrever em mais de um processo?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Sim, é possível se inscrever em múltiplos processos seletivos, desde que:</p>
                                <ul>
                                    <li>Você atenda aos requisitos específicos de cada processo</li>
                                    <li>Os processos não tenham conflito de horário nas etapas presenciais</li>
                                    <li>Não haja restrição específica no Aviso de Convocação</li>
                                </ul>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle"></i> <strong>Importante:</strong> Cada processo possui cronograma independente.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Tutoriais -->
            <div class="help-card">
                <h2><i class="bi bi-film"></i> Tutoriais em Vídeo</h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="video-container">
                            <iframe src="https://www.youtube.com/embed/VIDEO_ID_1" title="Tutorial de Inscrição" allowfullscreen=""></iframe>
                        </div>
                        <h4 class="text-center mt-2">Como realizar sua inscrição</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="video-container">
                            <iframe src="https://www.youtube.com/embed/VIDEO_ID_2" title="Acompanhamento de Processo" allowfullscreen=""></iframe>
                        </div>
                        <h4 class="text-center mt-2">Acompanhando seu processo</h4>
                    </div>
                </div>
            </div>

            <!-- Card de Contato -->
            <div class="help-card contact-card">
                <h2><i class="bi bi-envelope-fill"></i> Contato do Suporte</h2>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="contact-method">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <h5>Telefone Principal</h5>
                                <p>(51) 3220-6466<br><small>Segunda a quinta, das 0930h às 1630h e sexta das 0800h às 1130h</small></p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <h5>Telefone Secundário</h5>
                                <p>(51) 3220-6676<br><small>Segunda a quinta, das 0930h às 1630h e sexta das 0800h às 1130h</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="contact-method">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <h5>E-mail</h5>
                                <p>selecao-svtt@3rm.eb.mil.br</p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <i class="bi bi-chat-left-text"></i>
                            <div>
                                <h5>Fale Conosco</h5>
                                <p>Disponível para os candidatos dentro da área logada</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Documentação -->
            <div class="help-card">
                <h2><i class="bi bi-file-earmark-text-fill"></i> Documentação</h2>

                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual do Administrador</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com todas as operações que o Administrador pode/deve realizar</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual do Avaliador de Currículo</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com instruções a respeito da Avaliação Curricular</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual da JISE</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com instruções a respeito do Julgamento de Recursos da Inspeção de Saúde</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual da Comissão de Heteroidentificação</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com com instruções a respeito da Heteroidentificação</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual do Operador de OM</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com instruções sobre os procedimentos que operador de OM deve realizar</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual do Ouvidor</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo para com os procedimentos de resposta do Fale Conosco</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Manual do Candidato</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Guia completo com todas as operações que o candidato pode/deve realizar</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Termos de Uso</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Normas e condições de uso do sistema</p>
                        <small class="text-muted">Atualizado em 09/10/2025</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Política de Privacidade</h5>
                            <small class="text-muted">DOCUMENTO PDF</small>
                        </div>
                        <p class="mb-1">Como seus dados são coletados e protegidos</p>
                        <small class="text-muted">Atualizado em 10/01/2024</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="sistema/js/bootstrap5.3.3.js"></script>
    <script>
        // FAQ accordion
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    </script>
</body>

</html>