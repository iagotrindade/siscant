<?php
include_once 'menu.php';

if ($_SESSION['selecao_regiao'] == 7) exit();

$conexao = new Conexao();
$lista_perguntas = $conexao->get_perguntas_assistente();

$define_concorrendo = $concorrendo
    ? 'está concorrendo'
    : 'não está concorrendo pelo seguinte motivo: ' . $justificativa_concorrendo_processo;

$define_especialidades_concorrendo = '';
$define_especialidades_nao_concorrendo = '';
$situacao = '';

// Obtém especialidades do candidato
$especialidades = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);

// Processa cada especialidade
foreach ($especialidades as $especialidade) {
    $linha = '- ' . strtoupper($especialidade['ott_stt']) . ' ' .
        $especialidade['especialidade'] . ' na ETAPA ' . $especialidade['etapa'];

    if ($especialidade['concorrendo'] == 1) {
        $define_especialidades_concorrendo .= $linha . "\n";
    } else {
        $define_especialidades_nao_concorrendo .= $linha .
            ' com a seguinte justificativa: ' . $especialidade['justificativa'] . "\n";
    }
}

// Formata os textos das especialidades
if (!empty($define_especialidades_concorrendo)) {
    $define_especialidades_concorrendo =
        "Especialidade(s) em que você está concorrendo:\n" .
        trim($define_especialidades_concorrendo) . ".";
}

if (!empty($define_especialidades_nao_concorrendo)) {
    $define_especialidades_nao_concorrendo =
        "Especialidade(s) em que NÃO está concorrendo:\n" .
        trim($define_especialidades_nao_concorrendo) . ".
        Acompanhe os prazos previstos para Recurso no Aviso de Convocação disponível abaixo e nas Publicações de Resultado de cada Etapa no site da 3 Região Militar.
        </br>
        Importante: As datas estabelecidas no Anexo 'A' do Aviso de Convocação podem ser alteradas. As datas atualizadas para Interposiçao de Recurso sempre constarão nas publicações de Resultado de cada Etapa";
}

// Monta o texto final da situação
$situacao = "Situação no Processo Seletivo:
- Seu número de Inscrição no Processo Seletivo é <strong>" . $_SESSION['id_usuario'] . "</strong>
- Você " . $define_concorrendo . ".\n"
    . $define_especialidades_concorrendo . "\n"
    . $define_especialidades_nao_concorrendo . "

Mais informações podem ser encontradas na Página Inicial do SiSCanT bem como nas publicações disponíveis no site da 3ª Região Militar.</br>
<a href='documentos/aviso_convocacao.pdf' target='_blank' style='text-decoration:none; display:flex; align-items:center;'>
    <img src='imagens/pdf.png' alt='PDF' style='width:25px; height:25px; margin-right:8px;'>Aviso de Convocação (PDF)
</a>
https://www.3rm.eb.mil.br - Site da 3ª Região Militar
https://siscant.3rm.eb.mil.br/sistema/index.php - Página Inicial do SiSCanT";
?>
<style>
    :root {
        --primary-color: #006400;
        /* Verde militar */
        --secondary-color: #f8f9fa;
        --accent-color: #ffc107;
        --text-dark: #212529;
        --text-light: #f8f9fa;
    }

    /* Estilos do Chat IA */
    .chat-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 3rem;
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
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .chat-status i {
        margin-right: 5px;
    }

    .chat-container {
        height: 400px;
        overflow-y: auto;
        padding: 3rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .message {
        font-size: 15px;
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
        font-size: 1.2rem;
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
        padding: 1.2rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 6px 0 0 6px;
        outline: none;
    }

    .chat-input button {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 0 2.5rem;
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
        font-size: 15px;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .question-chip {
        display: inline-block;
        background-color: rgba(0, 100, 0, 0.1);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 15px;
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
        font-size: 15px;
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
</style>
<script type="text/javascript">
    function valida_suporte() {
        $('#div_motivo').attr('class', 'col-lg-12');
        $('#div_mensagem_suporte').attr('class', ' col-lg-12');

        $("#mensagem_erro").text("");
        $("#div_mensagem_erro").hide();

        if ($('#motivo').val() == '') {
            $('#div_motivo').attr('class', 'col-lg-12 form-group has-error');
            $('#div_motivo').focus();
            $("#mensagem_erro").text("O campo MOTIVO é obrigatório!");
            $("#div_mensagem_erro").show();
            return false;
        }

        if ($('#mensagem_suporte').val() == '') {
            $('#div_mensagem_suporte').attr('class', 'col-lg-12 form-group has-error');
            $('#mensagem_suporte').focus();
            $("#mensagem_erro").text("O campo MENSAGEM é obrigatório!");
            $("#div_mensagem_erro").show();
            return false;
        }
        return true;
    }
</script>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <!-- 22/06/2025 -> Iago Silva Alterado o ícone -->
            <h1>Suporte <i class="fa fa-comments"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Suporte </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="chat-card col-md-12">
                <div class="chat-header">
                    <h2><i class="bi bi-robot"></i> Assistente Virtual</h2>
                    <span class="chat-status"><i class="bi bi-check-circle"></i> Online</span>
                </div>

                <div class="chat-container" id="chatContainer">
                    <div class="message bot-message">
                        Olá <?= ucwords(strtolower($_SESSION['nome_completo'])) . "!" ?>! Sou o assistente virtual do SiSCanT. Posso te ajudar com informações sobre:<br><br>
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
        </div>
    </div>

    <div class="row" hidden id="suporte">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-envelope"></i> Contato com o Suporte</h3>
                </div>
                <div class="panel-body">
                    <form action="../banco_dados/suporte_cadastra.php" method="post" onsubmit="return valida_suporte()">
                        <div class="alert alert-info">
                            <i class="glyphicon glyphicon-info-sign"></i> Será enviado um e-mail de resposta para: <strong><?php echo $mail ?></strong>.
                            Caso seu e-mail esteja desatualizado, edite seu cadastro.
                        </div>

                        <div class="form-group">
                            <label for="motivo" class="control-label">Motivo do Contato</label>
                            <select id="motivo" name="motivo" class="form-control">
                                <option value="">Selecione o motivo...</option>
                                <option value="duvida_pagamento_isento">Dúvida sobre isenção de pagamento</option>
                                <option value="duvida_ex_medico">Dúvida sobre Exames Médicos</option>
                                <option value="duvida_autenticacao">Dúvida sobre autenticação de documentos</option>

                                <?php if ($codigo_selecao != 'mfdv'): ?>
                                    <option value="duvida_teste_fisico">Dúvida sobre o Teste Físico</option>
                                    <option value="duvida_teste_pratico">Dúvida sobre o Teste Prático</option>
                                <?php endif; ?>

                                <option value="duvida_docs_obrigatorios">Dúvida sobre documentos obrigatórios</option>
                                <option value="dificuldade_curriculo">Dificuldade com upload de arquivos</option>
                                <option value="dificuldade_doc_obrigatorios">Dificuldade com documentos obrigatórios</option>
                                <option value="dificuldade_dados_cadastro">Dificuldade em editar cadastro</option>
                                <option value="dificuldade_especialidade">Dificuldade em cadastrar especialidade</option>
                                <option value="outro">Outro assunto</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mensagem_suporte" class="control-label">Mensagem</label>
                            <textarea maxlength="2000" id="mensagem_suporte" name="mensagem" class="form-control" rows="5"
                                placeholder="Descreva detalhadamente sua dúvida ou problema..."></textarea>
                        </div>

                        <div class="alert alert-danger" id="div_mensagem_erro" style="display: none;">
                            <i class="glyphicon glyphicon-exclamation-sign"></i>
                            <span id="mensagem_erro"></span>
                        </div>

                        <input type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>" name="crip">

                        <button name="enviar" type="submit" class="btn btn-primary btn-block btn-md">
                            <i class="glyphicon glyphicon-send"></i> ENVIAR MENSAGEM
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-comments"></i> Histórico de Mensagens</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="tabela_dinamica">
                            <thead>
                                <tr class="active">
                                    <th width="30%">Mensagem</th>
                                    <th width="15%">Data Envio</th>
                                    <th width="30%">Resposta</th>
                                    <th width="15%">Data Resposta</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_suporte = $conexao->get_suporte_candidato($_SESSION['id_usuario']);
                                foreach ($lista_suporte as $linha) {
                                    $data_envio = $linha['data_enviado'] ? trata_data($linha['data_enviado']) : '-';
                                    $mensagem = $linha['mensagem'] ? $linha['mensagem'] : '-';
                                    $resposta = $linha['resposta'] ? $linha['resposta'] : '-';
                                    $data_resposta = $linha['data_resposta'] ? trata_data($linha['data_resposta']) : '-';
                                    $status = $linha['data_resposta'] ? '<span class="label label-success">Respondido</span>' : '<span class="label label-warning">Pendente</span>';

                                    echo '
                                <tr>
                                    <td>' . nl2br(htmlspecialchars($mensagem)) . '</td>
                                    <td>' . $data_envio . '</td>
                                    <td>' . nl2br(htmlspecialchars($resposta)) . '</td>
                                    <td>' . $data_resposta . '</td>
                                    <td class="text-center">' . $status . '</td>
                                </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <a name="fimpagina"></a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap5.3.3.js"></script>
    <script>
        // Função para validar o formulário (adaptada para o novo layout)
        function valida_suporte() {
            // Limpa mensagens de erro anteriores
            $('#div_mensagem_erro').hide();

            // Validação do motivo
            if ($('#motivo').val() === '') {
                $('#mensagem_erro').text('Por favor, selecione o motivo do contato.');
                $('#div_mensagem_erro').show();
                return false;
            }

            // Validação da mensagem
            if ($('#mensagem_suporte').val().trim() === '') {
                $('#mensagem_erro').text('Por favor, descreva sua dúvida ou problema.');
                $('#div_mensagem_erro').show();
                return false;
            }

            return true;
        }

        let tentativas = 0;
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
                    botResponse = '';

                    botResponse = <?php echo json_encode($situacao, JSON_UNESCAPED_UNICODE); ?>;

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
                        tentativas = 0;
                        botResponse = melhorResposta;
                    } else {
                        tentativas++; // Incrementa no JavaScript

                        if (tentativas == 3) {
                            botResponse = `Parece que não consegui resolver sua dúvida desta vez 🤔
                <br/>Mas não se preocupe! Você pode enviar sua pergunta diretamente abaixo desta conversa, e um dos membros da Comissão responsável vai analisar e te responder da melhor forma possível.<br/>
                Fique à vontade para detalhar ao máximo sua questão — assim, poderemos ajudar você com mais precisão!`;

                            //remover atributo hidden das divs de suporte

                            document.querySelectorAll('#suporte').forEach(function(div) {
                                console.log(div);
                                div.removeAttribute('hidden');
                            });
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
                    <img src="imagens/pdf.png" alt="PDF" style="width:25px; height:25px; margin-right:8px;">Aviso de Convocação (PDF)
                </a>`;
                        }


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
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $('#tabela_dinamica').DataTable();
    </script>

</div>
</div>
</body>

</html>