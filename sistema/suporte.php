<?php
include_once 'menu.php';

if ($_SESSION['selecao_regiao'] == 7) exit();

$conexao = new Conexao();
$lista_perguntas = $conexao->get_perguntas_assistente($_SESSION['selecao_codigo']);

$selecao = $conexao->get_selecao_id();

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
$situacao = "Situação no Processo Seletivo: - Seu número de Inscrição no Processo Seletivo é <strong>" . $_SESSION['id_usuario'] . "</strong> - Você " . $define_concorrendo . ".\n" . $define_especialidades_concorrendo . "\n" . $define_especialidades_nao_concorrendo . " Mais informações podem ser encontradas na Página Inicial do SiSCanT bem como nas publicações disponíveis no site da 3ª Região Militar.</br> 
<a href='arquivos/avisos_de_convocacao/" . $selecao[0]['aviso_convocacao'] . "' target='_blank' style='text-decoration:none; display:flex; align-items:center;'>
    <img src='imagens/pdf.png' alt='PDF' style='width:25px; height:25px; margin-right:8px;'> Aviso de Convocação (PDF)
</a>
<br>
https://www.3rm.eb.mil.br - Site da 3ª Região Militar
<br>
https://siscant.3rm.eb.mil.br/sistema/index.php - Página Inicial do SiSCanT";

?>
<style>
    :root {
        --primary-color: #006400;
        /* Verde militar */
        --secondary-color: #f8f9fa;
        --primary-light: #4c8c4a;
        --accent-color: #ffc107;
        --text-dark: #212529;
        --text-light: #f8f9fa;
        --user-message-bg: #f0f8ff;
        --user-message-border: #d1e7ff;
        --bot-message-bg: #f8f9f4;
        --bot-message-border: #e2e8d8;
    }

    /* Estilos do Chat IA */
    .chat-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 3rem;
        margin-bottom: 2rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .chat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
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
        padding: 2rem;
        border-radius: 12px;
        position: relative;
    }

    .user-message {
        background-color: var(--user-message-bg);
        color: var(--text-dark);
        margin-left: auto;
        border-bottom-right-radius: 6px;
        flex-direction: row-reverse;
        border: 1px solid var(--user-message-border);
        box-shadow: 0 2px 8px rgba(0, 100, 0, 0.08);
        border-right: 3px solid #4453feff;
    }

    .bot-message {
        background-color: var(--bot-message-bg);
        color: var(--text-dark);
        margin-right: auto;
        border-bottom-left-radius: 6px;
        border: 1px solid var(--bot-message-border);
        box-shadow: 0 2px 8px rgba(0, 100, 0, 0.05);
        border-left: 3px solid var(--primary-light);
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

    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
        background: white;
    }

    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

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

    .card-header-modern {
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body-modern {
        padding: 20px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .status-pending {
        background-color: #fff4e0;
        color: #e0a800;
    }

    .status-answered {
        background-color: #e6f7f1;
        color: #1cc88a;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--primary-color);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-btn {
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 1.2rem;
    }

    .divider {
        height: 1px;
        background: #e3e6f0;
        margin: 15px 0;
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

    <div class="row" <?php if ($_SESSION['selecao_regiao'] != 3) echo 'hidden'; ?>>
        <div class="col-lg-12">
            <div class="chat-card col-md-12">
                <div class="chat-header">
                    <h2><i class="bi bi-robot"></i> Assistente Virtual</h2>

                    <?php if ($selecao[0]['liberacao_assistente_virtual']) { ?>
                        <span class="chat-status"><i class="bi bi-check-circle"></i> Online</span>
                    <?php } else { ?>
                        <span class="chat-status" style="background-color: #dc3545;"><i class="bi bi-x-circle"></i> Offline</span>
                    <?php } ?>
                </div>

                <div class="chat-container" id="chatContainer">
                    <div class="message bot-message">
                        <?php if ($selecao[0]['liberacao_assistente_virtual']) { ?>
                            Olá <strong><?= ucwords(strtolower($_SESSION['nome_completo'])) . "!" ?></strong> Sou o Assistente Virtual do SiSCanT. Posso te ajudar com informações sobre:<br><br>
                            <ul>
                                <li>Processo de Inscrição</li>
                                <li>Etapas do processo seletivo</li>
                                <li>Acompanhamento de resultados</li>
                                <li>Assuntos específicos de cada Etapa</li>
                                <li>Outras dúvidas sobre o Processo Seletivo</li>
                            </ul>
                            Se precisar de ajuda, digite sua dúvida ou clique nas perguntas sugeridas abaixo. Caso eu não consiga lhe ajudar, vou te direcionar para o suporte.
                            <br>
                            <br>
                            Recomendamos sempre que consulte o Aviso de Convocação, disponível logo abaixo e no site da 3ª RM, pois todas as etapas e procedimentos do Processo Seletivo estão descritos de maneira detalhada nele.
                            <br>
                            <br>
                            <a href="<?php echo 'arquivos/avisos_de_convocacao/' . $selecao[0]['aviso_convocacao']; ?>" target='_blank' style='text-decoration:none; display:flex; align-items:center;'>
                                <img src='imagens/pdf.png' alt='PDF' style='width:25px; height:25px; margin-right:8px;'>Aviso de Convocação (PDF)
                            </a>
                        <?php } else { ?>
                            Olá <strong><?= ucwords(strtolower($_SESSION['nome_completo'])) . "!" ?></strong> Sou o Assistente Virtual do SiSCanT.<br><br>

                            No momento estou <strong>OFFLINE</strong> para manutenção e/ou Atualização da minha Base de Dados e não posso responder suas dúvidas.<br><br>

                            Essa indisponibilidade é temporária e em breve estarei de volta para te ajudar com informações sobre o Processo Seletivo.
                        <?php } ?>


                        <span class="message-time"><?= date('H:s') ?></span>
                    </div>
                </div>

                <!-- Indicador de digitação (será mostrado via JavaScript) -->
                <div class="typing-indicator" id="typingIndicator" style="display: none;">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>



                <?php if ($selecao[0]['liberacao_assistente_virtual']): ?>
                    <div class="chat-input">
                        <input type="text" id="userInput" placeholder="Digite sua mensagem..." autocomplete="off" minlength="20" required>
                        <button id="sendButton"><i class="bi bi-send"></i></button>
                    </div>


                    <div id="suggestions"></div>

                    <div class="suggested-questions">
                        <h5>Perguntas sugeridas:</h5>
                        <div class="question-chip" onclick="insertQuestion(this)">Consultar minha situação no Processo Seletivo</div>
                        <div class="question-chip" onclick="insertQuestion(this)">Quais documentos preciso para me inscrever?</div>
                        <div class="question-chip" onclick="insertQuestion(this)">Quais são as etapas do processo seletivo?</div>
                        <div class="question-chip" onclick="insertQuestion(this)">Posso me inscrever em mais de uma especialidade?</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-comments"></i> Mensagens com o Suporte</span>
                </div>
                <div class="card-body-modern">
                    <div class="filter-buttons">
                        <button class="btn btn-primary filter-btn active" data-filter="all">Todas</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="answered">Respondidas</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="pending">Pendentes</button>
                    </div>

                    <div class="chat-container" id="chatContainerSupport">
                        <?php
                        $lista_suporte = $conexao->get_suporte_candidato($_SESSION['id_usuario']);
                        ?>

                        <?php if (empty($lista_suporte)): ?>
                            <div class="empty-state">
                                <i class="fa fa-comments"></i>
                                <h4>Nenhuma mensagem encontrada</h4>
                                <p>Você ainda não enviou nenhuma mensagem.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($lista_suporte as $index => $linha): ?>
                                <?php
                                $data_envio    = $linha['data_enviado']   ? trata_data_hora($linha['data_enviado'])   : '-';
                                $mensagem      = $linha['mensagem']       ? nl2br(htmlspecialchars($linha['mensagem'])) : '-';
                                $resposta      = $linha['resposta']       ? nl2br(htmlspecialchars($linha['resposta'])) : '-';
                                $data_resposta = $linha['data_resposta']  ? trata_data_hora($linha['data_resposta'])  : '-';
                                $status        = $linha['data_resposta']  ? 'answered' : 'pending';
                                $status_text   = $linha['data_resposta']  ? 'Respondido' : 'Pendente';
                                ?>

                                <div class="message user-message <?= $status ?>">
                                    <div><?= $mensagem ?></div>
                                    <span class="message-time"><?= $data_envio ?></span>
                                </div>

                                <?php if ($resposta && $resposta !== '-'): ?>
                                    <div class="message bot-message <?= $status ?>">
                                        <div><?= $resposta ?></div>
                                        <span class="message-time"><?= $data_resposta ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="status-badge status-<?= $status ?>"><?= $status_text ?></span>
                                    </div>
                                </div>

                                <?php if ($index < count($lista_suporte) - 1): ?>
                                    <div class="divider"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form action="../banco_dados/suporte_cadastra.php"
                        method="post"
                        onsubmit="return valida_suporte()"
                        <?php if ($_SESSION['selecao_regiao'] == 3) echo 'hidden'; ?>
                        id="suporte">

                        <input type="hidden"
                            value="<?= hash('sha256', $_SESSION['chave'] . 'freitas') ?>"
                            name="crip">

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            Sua dúvida será respondida por um membro da Comissão de Seleção e você poderá visualizar a resposta nesta mesma página.
                        </div>

                        <div class="chat-input">
                            <select id="motivo" name="motivo" class="form-control mb-10">
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

                        <div class="chat-input">
                            <input type="text"
                                placeholder="Digite sua mensagem..."
                                autocomplete="off"
                                name="mensagem">
                            <button type="submit"><i class="bi bi-send"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap5.3.3.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /**
             * ===============================
             * CONSTANTES/HELPERS
             * ===============================
             */
            // ATENÇÃO: seu código usa jQuery ($). Garanta que jQuery está carregado.
            const perguntasRespostas = <?php echo json_encode($lista_perguntas, JSON_UNESCAPED_UNICODE); ?>;

            // Mantidos, mesmo que não usados diretamente, para não perder nenhuma lógica declarada
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

            const filterButtons = document.querySelectorAll('.filter-btn');
            const messages = document.querySelectorAll('#chatContainerSupport .message');

            /**
             * ===============================
             * UI
             * ===============================
             */
            function scrollToBottom() {
                const chatContainer = document.querySelector('#chatContainerSupport');
                if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
            }
            scrollToBottom();
            setTimeout(scrollToBottom, 100);

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

            function showSuggestions(query) {
                const suggestionsContainer = document.getElementById('suggestions');
                suggestionsContainer.innerHTML = '';
                if (!query) return;

                const LIMIAR = 40;
                const matches = perguntasRespostas
                    .filter(item => calcularSimilaridade(query, item.pergunta) >= LIMIAR)
                    .sort((a, b) => calcularSimilaridade(query, b.pergunta) - calcularSimilaridade(query, a.pergunta))
                    .slice(0, 5);

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

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.filter;

                    // atualiza os botões ativos
                    filterButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                    filterButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
                    button.classList.add('active', 'btn-primary');
                    button.classList.remove('btn-outline-primary');

                    // aplica filtro
                    messages.forEach(msg => {
                        if (filter === 'all') {
                            msg.style.display = '';
                        } else {
                            msg.style.display = msg.classList.contains(filter) ? '' : 'none';
                        }
                    });

                    // também esconder/mostrar status-badges e dividers junto
                    document.querySelectorAll('#chatContainerSupport .status-badge, #chatContainerSupport .divider')
                        .forEach(el => {
                            if (filter === 'all') {
                                el.style.display = '';
                            } else {
                                // pega o status do badge/divider pelo elemento anterior
                                const parent = el.closest('.d-flex, .divider');
                                const relatedMsg = parent?.previousElementSibling;
                                if (relatedMsg && relatedMsg.classList.contains(filter)) {
                                    el.style.display = '';
                                } else {
                                    el.style.display = 'none';
                                }
                            }
                        });
                });
            });

            /**
             * ===============================
             * VALIDAÇÃO (exposta globalmente)
             * ===============================
             */
            function valida_suporte() {
                if (window.jQuery) {
                    $('#div_mensagem_erro').hide();
                    if ($('#motivo').val() === '') {
                        $('#mensagem_erro').text('Por favor, selecione o motivo do contato.');
                        $('#div_mensagem_erro').show();
                        return false;
                    }
                    if ($('#mensagem_suporte').val().trim() === '') {
                        $('#mensagem_erro').text('Por favor, descreva sua dúvida ou problema.');
                        $('#div_mensagem_erro').show();
                        return false;
                    }
                    return true;
                } else {
                    // fallback sem jQuery, para não quebrar
                    const divErro = document.getElementById('div_mensagem_erro');
                    const msgErro = document.getElementById('mensagem_erro');
                    const motivo = document.getElementById('motivo');
                    const mensagem = document.getElementById('mensagem_suporte');

                    if (divErro) divErro.style.display = 'none';
                    if (motivo && motivo.value === '') {
                        if (msgErro) msgErro.textContent = 'Por favor, selecione o motivo do contato.';
                        if (divErro) divErro.style.display = 'block';
                        return false;
                    }
                    if (mensagem && mensagem.value.trim() === '') {
                        if (msgErro) msgErro.textContent = 'Por favor, descreva sua dúvida ou problema.';
                        if (divErro) divErro.style.display = 'block';
                        return false;
                    }
                    return true;
                }
            }
            // torna disponível para onsubmit="return valida_suporte()"
            window.valida_suporte = valida_suporte;

            /**
             * ===============================
             * SIMILARIDADE
             * ===============================
             */
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

            function calcularSimilaridade(str1, str2) {
                str1 = str1.toLowerCase().trim();
                str2 = str2.toLowerCase().trim();
                const distancia = levenshteinDistance(str1, str2);
                const maxLen = Math.max(str1.length, str2.length);
                return maxLen === 0 ? 100 : ((1 - distancia / maxLen) * 100);
            }

            /**
             * ===============================
             * BOT
             * ===============================
             */
            let tentativas = 0;

            function getBotResponse(userMessage) {
                const typingIndicator = document.getElementById('typingIndicator');
                const chatContainer = document.getElementById('chatContainer');
                if (typingIndicator) typingIndicator.style.display = 'flex';
                if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;

                setTimeout(() => {
                    if (typingIndicator) typingIndicator.style.display = 'none';

                    if (userMessage === "Consultar minha situação no Processo Seletivo") {
                        const botResponse = <?php echo json_encode($situacao, JSON_UNESCAPED_UNICODE); ?>;
                        addMessage(formatarResposta(botResponse), false);
                        return;
                    }

                    let melhorResposta = null,
                        maiorSimilaridade = 0;
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
                        tentativas++;
                        if (tentativas === 3) {
                            botResponse = `Parece que não consegui resolver sua dúvida desta vez 🤔
<br/>Mas não se preocupe! Você pode enviar sua pergunta diretamente abaixo desta conversa, e um dos membros da Comissão responsável vai analisar e te responder da melhor forma possível.<br/>
Fique à vontade para detalhar ao máximo sua questão — assim, poderemos ajudar você com mais precisão!`;

                            document.querySelectorAll('#suporte').forEach(div => div.removeAttribute('hidden'));
                        } else {
                            const pdfUrl = '<?php echo 'arquivos/avisos_de_convocacao/' . $selecao[0]['aviso_convocacao']; ?>';
                            botResponse = `Não encontrei uma resposta exata 🤔
<br/>Mas posso te ajudar com informações sobre<br/>
- Processo de Inscrição
- Etapas do processo seletivo
- Acompanhamento de Resultados
- Assuntos específicos de cada Etapa
- Outras dúvidas sobre o Processo Seletivo
Tente reformular sua pergunta, ou consulte o Aviso de Convocação disponível logo abaixo<br><br>
<a href="${pdfUrl}" target="_blank" style="text-decoration:none; display:flex; align-items:center;">
    <img src="imagens/pdf.png" alt="PDF" style="width:25px; height:25px; margin-right:8px;">Aviso de Convocação (PDF)
</a>`;
                        }
                    }
                    addMessage(formatarResposta(botResponse), false);
                }, 2000);
            }

            // se quiser chamar getBotResponse via onclick/html, exponha:
            // window.getBotResponse = getBotResponse;

            /**
             * ===============================
             * EVENTOS
             * ===============================
             */
            const userInput = document.getElementById('userInput');
            const sendButton = document.getElementById('sendButton');

            if (userInput) {
                userInput.addEventListener('input', () => showSuggestions(userInput.value));
                userInput.addEventListener('keypress', e => {
                    if (e.key === 'Enter' && sendButton) sendButton.click();
                });
            }
            if (sendButton) {
                sendButton.addEventListener('click', () => {
                    const message = (userInput?.value || '').trim();
                    if (message) {
                        addMessage(message, true);
                        if (userInput) userInput.value = '';
                        const sug = document.getElementById('suggestions');
                        if (sug) sug.innerHTML = '';
                        getBotResponse(message);
                    }
                });
            }

            // Accordion
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });

            // FUNÇÃO QUE VOCÊ CITOU — agora exposta globalmente
            function insertQuestion(element) {
                const userInput = document.getElementById('userInput');
                if (!userInput || !element) return;
                userInput.value = element.textContent;
                userInput.focus();
            }
            // torna disponível para onclick="insertQuestion(this)"
            window.insertQuestion = insertQuestion;

            /**
             * ===============================
             * FORMATADOR
             * ===============================
             */
            function formatarResposta(resposta) {
                if (!resposta) return "";
                let txt = resposta;

                // Alertas (Importante/Atenção)
                txt = txt.replace(/^(?:\s*)(Importante:|Atenção:)([\s\S]*?)(?=\n{2,}|$)/gmi, (_, titulo, corpo) => {
                    corpo = corpo.replace(/^\s*\n?/, '').replace(/\n+$/, '');
                    return `<div class="alert-message">
  <div class="alert-icon">⚠️</div>
  <div class="alert-content"><strong>${titulo}</strong> ${corpo}</div>
</div>`;
                });

                // Listas ordenadas
                txt = txt.replace(/^(?:\s*\d+\.\s+.*(?:\n|$))+?/gm, (bloco) => {
                    const itens = bloco.trim().split(/\n/)
                        .map(l => l.replace(/^\s*\d+\.\s+/, '').trim())
                        .filter(Boolean);
                    return itens.length ? `<ol><li>${itens.join('</li><li>')}</li></ol>\n` : bloco;
                });

                // Listas não ordenadas
                txt = txt.replace(/^(?:\s*-\s+.*(?:\n|$))+?/gm, (bloco) => {
                    const itens = bloco.trim().split(/\n/)
                        .map(l => l.replace(/^\s*-\s+/, '').trim())
                        .filter(Boolean);
                    return itens.length ? `<ul><li>${itens.join('</li><li>')}</li></ul>\n` : bloco;
                });

                // Títulos
                txt = txt.replace(/^(?!<)([^<\n]{3,}):\s*$(?!<\/)/gm, '<h5>$1</h5>');

                // Links clicáveis
                txt = txt.replace(/(https?:\/\/[^\s<]+[^\s<.,;:!?)])(?=\s|$)/g, (url) => {
                    const clean = url.replace(/\s+/g, '');
                    return `<a href="${clean}" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">👉 Acessar</a>`;
                });

                // Quebras de linha -> <br> (com cuidados)
                txt = txt.replace(/(?<!<\/div>|<\/ul>|<\/ol>|<\/h4>)\n/g, (m, offset, full) => {
                    if (/class="alert-content">[^<]*$/.test(full.slice(0, offset))) return '';
                    return '<br>';
                });
                txt = txt.replace(/(<br>\s*){2,}/g, '<br>');

                // Destaque de palavras-chave
                const keywords = ["documentos", "documento", "prazo", "prazos", "resultado", "resultados",
                    "inscrição", "inscrições", "publicação", "publicações", "etapas", "etapa",
                    "Aviso de Convocação"
                ];
                keywords.forEach(w => {
                    const re = new RegExp(`\\b(${w})\\b`, 'gi');
                    txt = txt.replace(re, '<strong>$1</strong>');
                });

                return txt;
            }
        });
    </script>

</div>
</div>
</body>

</html>