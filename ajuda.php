<?php
include_once 'banco_dados/conexao.php';
$conexao = new Conexao();
$lista_perguntas = $conexao->get_perguntas_assistente();

$usuarioLogado = $conexao->get_usuario_id($_SESSION['id_usuario']);
?>

<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajuda - SiSCanT</title>
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

        /* Estilos do Chat IA */
        .chat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .chat-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .chat-header h2 {
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .chat-header h2 i {
            margin-right: 10px;
        }

        .chat-status {
            margin-left: auto;
            background-color: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }

        .chat-status i {
            margin-right: 5px;
        }

        .chat-container {
            height: 500px;
            overflow-y: auto;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 1rem;
            max-width: 80%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            position: relative;
        }

        .user-message {
            background-color: var(--primary-color);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }

        .bot-message {
            background-color: #e9ecef;
            color: var(--text-dark);
            margin-right: auto;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 0.25rem;
            display: block;
            text-align: right;
        }

        .chat-input {
            display: flex;
            margin-bottom: 1rem;
        }

        .chat-input input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 6px 0 0 6px;
            outline: none;
        }

        .chat-input button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0 1.5rem;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .chat-input button:hover {
            background-color: #004d00;
        }

        .suggested-questions {
            margin-top: 1.5rem;
        }

        .suggested-questions h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .question-chip {
            display: inline-block;
            background-color: rgba(0, 100, 0, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .question-chip:hover {
            background-color: rgba(0, 100, 0, 0.2);
        }

        .typing-indicator {
            display: flex;
            padding: 0.5rem 1rem;
            background-color: #e9ecef;
            border-radius: 12px;
            width: fit-content;
            margin-bottom: 1rem;
            border-bottom-left-radius: 4px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #6c757d;
            border-radius: 50%;
            margin: 0 2px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        #suggestions {
            border: 1px solid #ccc;
            max-width: 400px;
            position: absolute;
            background: white;
            z-index: 1000;
            margin-top: 2px;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        .alert-message {
            display: flex;
            align-items: center;
            background: #F5F5F5;
            border-left: 3px solid #006400;
            margin: .25rem 0;
            padding: .5rem .75rem;
            font-style: italic;
        }

        .alert-icon {
            font-size: 1.3em;
            margin-right: 5px;
            color: #FF9800;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            color: #E65100;
        }

        @keyframes typingAnimation {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-5px);
            }
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

            .message {
                max-width: 90%;
            }
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

            <!-- Card de Chat com IA -->
            <div class="chat-card">
                <div class="chat-header">
                    <h2><i class="bi bi-robot"></i> Assistente Virtual - EM DESENVOLVIMENTO</h2>
                    <span class="chat-status"><i class="bi bi-check-circle"></i> Online</span>
                </div>

                <div class="chat-container" id="chatContainer">
                    <div class="message bot-message">
                        Olá! Sou o assistente virtual do SiSCanT. Posso te ajudar com informações sobre:<br><br>
                        - Processo de inscrição<br>
                        - Documentos necessários<br>
                        - Etapas do processo seletivo<br>
                        - Acompanhamento de resultados<br>
                        - Outras dúvidas sobre o SiSCanT<br>
                        <span class="message-time"><?= date('H:s') ?></span>
                    </div>

                    <!-- Indicador de digitação (será mostrado via JavaScript) -->
                    <div class="typing-indicator" id="typingIndicator" style="display: none;">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>

                <div class="chat-input">
                    <input type="text" id="userInput" placeholder="Digite sua mensagem..." autocomplete="off">
                    <button id="sendButton"><i class="bi bi-send"></i></button>
                </div>

                <div id="suggestions"></div>

                <div class="suggested-questions">
                    <h5>Perguntas sugeridas:</h5>
                    <div class="question-chip" onclick="insertQuestion(this)">Consultar minha situação no Processo Seletivo</div>
                    <div class="question-chip" onclick="insertQuestion(this)">Quais documentos preciso para me inscrever?</div>
                    <div class="question-chip" onclick="insertQuestion(this)">Quais são as etapas do processo seletivo?</div>
                    <div class="question-chip" onclick="insertQuestion(this)">Posso me inscrever em mais de um cargo?</div>
                </div>
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
                            <h5 class="mb-1">Manual do Candidato</h5>
                            <small class="text-muted">PDF - 2.4MB</small>
                        </div>
                        <p class="mb-1">Guia completo com todas as etapas do processo seletivo</p>
                        <small class="text-muted">Atualizado em 15/03/2024</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Termos de Uso</h5>
                            <small class="text-muted">PDF - 1.1MB</small>
                        </div>
                        <p class="mb-1">Normas e condições de uso do sistema</p>
                        <small class="text-muted">Versão 3.1</small>
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Política de Privacidade</h5>
                            <small class="text-muted">PDF - 0.8MB</small>
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
        // Converte o array PHP para JS
        const perguntasRespostas = <?php echo json_encode($lista_perguntas, JSON_UNESCAPED_UNICODE); ?>;

        // Definir regexes e iconMap antes de usar
        const regexes = {
            newline: /\n/g,
            titles: /(^|\n)([^\n:]{3,}):(?=\s|$)/g,
            orderedListItems: /(?:\n|^)(\d+)\.\s+(.*?)(?=\n|$)/g,
            unorderedListItems: /(?:\n|^)[-*+]\s+(.*?)(?=\n|$)/g,
            url: /(https?:\/\/[^\s<]+[^\s<.)])/g,
            codeBlocks: /(`{3})([\s\S]*?)\1/g,
            inlineCode: /`([^`]+)`/g,
            quotes: /^>\s+(.*$)/gm,
            strike: /~~(.*?)~~/g
        };

        const iconMap = {
            'documentos?': '📄',
            'prazos?': '⏰',
            'resultados?': '🏆',
            'inscrições?': '📝'
        };


        // Função Levenshtein
        function levenshteinDistance(a, b) {
            const matrix = [];
            for (let i = 0; i <= b.length; i++) matrix[i] = [i];
            for (let j = 0; j <= a.length; j++) matrix[0][j] = j;

            for (let i = 1; i <= b.length; i++) {
                for (let j = 1; j <= a.length; j++) {
                    if (b.charAt(i - 1) === a.charAt(j - 1)) {
                        matrix[i][j] = matrix[i - 1][j - 1];
                    } else {
                        matrix[i][j] = Math.min(
                            matrix[i - 1][j] + 1,
                            matrix[i][j - 1] + 1,
                            matrix[i - 1][j - 1] + 1
                        );
                    }
                }
            }
            return matrix[b.length][a.length];
        }

        // Calcula similaridade em %
        function calcularSimilaridade(str1, str2) {
            str1 = str1.toLowerCase().trim();
            str2 = str2.toLowerCase().trim();
            const distancia = levenshteinDistance(str1, str2);
            const maxLen = Math.max(str1.length, str2.length);
            return maxLen === 0 ? 100 : ((1 - distancia / maxLen) * 100);
        }

        // Adiciona mensagem ao chat
        function addMessage(text, isUser) {
            const chatContainer = document.getElementById('chatContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;

            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0');

            messageDiv.innerHTML = text + `<span class="message-time">${timeString}</span>`;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Mostra sugestões abaixo do input
        function showSuggestions(query) {
            const suggestionsContainer = document.getElementById('suggestions');
            suggestionsContainer.innerHTML = '';
            if (!query) return;

            const LIMIAR = 40;
            const matches = perguntasRespostas.filter(item => calcularSimilaridade(query, item.pergunta) >= LIMIAR)
                .sort((a, b) => calcularSimilaridade(query, b.pergunta) - calcularSimilaridade(query, a.pergunta))
                .slice(0, 5); // até 5 sugestões

            matches.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.textContent = item.pergunta;
                div.addEventListener('click', () => {
                    document.getElementById('userInput').value = item.pergunta;
                    suggestionsContainer.innerHTML = '';
                    document.getElementById('userInput').focus();
                });
                suggestionsContainer.appendChild(div);
            });
        }

        // Busca resposta do bot
        function getBotResponse(userMessage) {
            const typingIndicator = document.getElementById('typingIndicator');
            typingIndicator.style.display = 'flex';
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.scrollTop = chatContainer.scrollHeight;

            setTimeout(() => {
                typingIndicator.style.display = 'none';

                if (userMessage == "Consultar minha situação no Processo Seletivo") {
                    botResponse = 'a';
                    console.log('consulta');

                    addMessage(formatarResposta(botResponse), false);
                } else {
                    let melhorResposta = null;
                    let maiorSimilaridade = 0;
                    const LIMIAR = 50;

                    perguntasRespostas.forEach(item => {
                        const similaridade = calcularSimilaridade(userMessage, item.pergunta);
                        if (similaridade > maiorSimilaridade) {
                            maiorSimilaridade = similaridade;
                            melhorResposta = item.resposta;
                        }
                    });

                    let botResponse = "";
                    if (melhorResposta && maiorSimilaridade >= LIMIAR) {
                        botResponse = melhorResposta;
                    } else {
                        botResponse = `Não encontrei uma resposta exata 🤔
            <br/>Mas posso te ajudar com informações sobre<br/>
            - Processo de inscrição
            - Documentos necessários
            - Etapas do processo seletivo
            - Resultados
            Tente reformular sua pergunta, ou consulte o Aviso de Convocação disponível logo abaixo<br><br>`;

                        // Adiciona ícone PDF
                        const pdfUrl = 'documentos/aviso_convocacao.pdf'; // caminho do seu PDF
                        botResponse += `
            <br/><a href="${pdfUrl}" target="_blank" style="text-decoration:none; display:flex; align-items:center;">
                <img src="sistema/imagens/pdf.png" alt="PDF" style="width:25px; height:25px; margin-right:8px;">Aviso de Convocação (PDF)
            </a>`;
                    }

                    addMessage(formatarResposta(botResponse), false);
                }
            }, 1200);
        }

        // Eventos
        const userInput = document.getElementById('userInput');
        userInput.addEventListener('input', () => showSuggestions(userInput.value));
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') document.getElementById('sendButton').click();
        });

        document.getElementById('sendButton').addEventListener('click', function() {
            const message = userInput.value.trim();
            if (message) {
                addMessage(message, true);
                userInput.value = '';
                document.getElementById('suggestions').innerHTML = '';
                getBotResponse(message);
            }
        });

        // FAQ accordion
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        // Função para inserir pergunta sugerida no input
        function insertQuestion(element) {
            const userInput = document.getElementById('userInput');
            userInput.value = element.textContent;
            userInput.focus();
        }

        function formatarResposta(resposta) {
            if (!resposta) return "";

            let txt = resposta;

            // 2) Blocos de alerta (Importante / Atenção)
            txt = txt.replace(/^(?:\s*)(Importante:|Atenção:)([\s\S]*?)(?=\n{2,}|$)/gmi, (_, titulo, corpo) => {
                corpo = corpo.replace(/^\s*\n?/, ''); // remove quebra logo após o título
                corpo = corpo.replace(/\n+$/, ''); // remove quebras no final
                return `<div class="alert-message">
  <div class="alert-icon">⚠️</div>
  <div class="alert-content"><strong>${titulo}</strong> ${corpo}</div>
</div>`;
            });

            // 3) Listas ORDENADAS
            txt = txt.replace(/^(?:\s*\d+\.\s+.*(?:\n|$))+?/gm, (bloco) => {
                const itens = bloco.trim().split(/\n/)
                    .map(l => l.replace(/^\s*\d+\.\s+/, '').trim())
                    .filter(Boolean);
                return itens.length ? `<ol><li>${itens.join('</li><li>')}</li></ol>\n` : bloco;
            });

            // 4) Listas NÃO ordenadas
            txt = txt.replace(/^(?:\s*-\s+.*(?:\n|$))+?/gm, (bloco) => {
                const itens = bloco.trim().split(/\n/)
                    .map(l => l.replace(/^\s*-\s+/, '').trim())
                    .filter(Boolean);
                return itens.length ? `<ul><li>${itens.join('</li><li>')}</li></ul>\n` : bloco;
            });

            // 5) Títulos
            txt = txt.replace(/^(?!<)([^<\n]{3,}):\s*$(?!<\/)/gm, '<h5>$1</h5>');

            // 6) Links clicáveis
            txt = txt.replace(/(https?:\/\/[^\s<]+[^\s<.,;:!?)])(?=\s|$)/g, (url) => {
                const clean = url.replace(/\s+/g, '');
                return `<a href="${clean}" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">👉 Acessar</a>`;
            });

            // 7) Quebras de linha restantes -> <br>
            // Evita quebrar dentro de div.alert-message
            txt = txt.replace(/(?<!<\/div>|<\/ul>|<\/ol>|<\/h4>)\n/g, (m, offset, full) => {
                // não insere <br> se estiver logo após <div class="alert-content">
                if (/class="alert-content">[^<]*$/.test(full.slice(0, offset))) return '';
                return '<br>';
            });

            // Colapsa múltiplos <br>
            txt = txt.replace(/(<br>\s*){2,}/g, '<br>');

            // 8) Destaque de palavras-chave
            const keywords = ["documentos", "documento", "prazo", "prazos", "resultado", "resultados", "inscrição", "inscrições", "publicação", "publicações", "etapas", "etapa"];
            keywords.forEach(w => {
                const re = new RegExp(`\\b(${w})\\b`, 'gi');
                txt = txt.replace(re, '<strong>$1</strong>');
            });

            return txt;
        }
    </script>
</body>

</html>