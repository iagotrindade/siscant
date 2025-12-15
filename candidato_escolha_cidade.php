<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

if ($_SESSION['perfil'] != 'candidato') {
    erro("Erro: 347356895465! Não foi possível abrir a página");
    exit();
}

if (!isset($_SESSION['candidato_etapa']) || $_SESSION['candidato_etapa'] < 4) {
    erro("Erro: 568334767! Não foi possível abrir a página");
    exit();
}

$lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
$datetime = date('d/m/Y H:i:s');
?>

<style>
    .guidance-steps .step-badge {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .step-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 8px;
        transition: transform 0.2s ease;
    }

    .step-item:hover {
        transform: translateX(5px);
    }

    .section-title {
        color: #495057;
        font-weight: 600;
        border-color: #dee2e6 !important;
    }

    .inscricoes-grid .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .inscricoes-grid .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .vagas-info .card {
        height: 100%;
    }

    .declaration-signature {
        background-color: #fcf8e3;
        border-color: #faebcc;
        color: #8a6d3b;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-map-marked-alt me-2"></i>Escolha de Guarnição</h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Escolha de Guarnição</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- Card de Orientações - Melhorado -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-compass fa-lg mr-10"></i>
                        <div>
                            <h4 class="card-title mb-0 mt-0">ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO</h4>
                            <small class="opacity-75">Leia atentamente antes de prosseguir</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="guidance-steps mb-20">
                        <div class="step-item mb-4 p-3 border-start border-primary border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">1</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DECLARAÇÃO DE CIÊNCIA</h6>
                                    <p class="mb-0">Eu, <strong class="text-dark"><?= mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></strong>, portador do CPF: <strong class="text-dark"><?= mascara($_SESSION['cpf'], '###.###.###-##') ?></strong>, DECLARO ter tomado conhecimento das orientações a respeito da ESCOLHA DE GUARNIÇÃO.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-info border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">2</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DISPONIBILIDADE DE VAGAS</h6>
                                    <p class="mb-0">COMPREENDO que será disponibilizado aos candidatos melhores classificados o universo de GUARNIÇÕES OFERTADAS abaixo, de acordo com a necessidade da Administração Militar.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-warning border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">3</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DIREITO DE DESISTÊNCIA</h6>
                                    <p class="mb-0">COMPREENDO que é facultado ao candidato o direito de selecionar: "Nenhuma das Opções (Desistência das localidades ofertadas)".</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">4</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">ESGOTAMENTO DE VAGAS</h6>
                                    <p class="mb-0">COMPREENDO que as vagas serão esgotadas à medida que os candidatos melhores pontuados efetuam suas escolhas, até restar uma vaga para o seguinte candidato melhor pontuado.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">5</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">VAGAS DE COTAS</h6>
                                    <p class="mb-0">COMPREENDO que as vagas de COTAS não preenchidas serão revertidas para o critério da AMPLA CONCORRÊNCIA somente após o último candidato COTISTA, da referida especialidade, realizar a sua ESCOLHA DE GUARNIÇÃO.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">6</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">OPÇÕES DE ESCOLHA</h6>
                                    <p class="mb-0">COMPREENDO que caso esteja concorrendo a mais de uma especialidade, ao escolher vaga em uma especialidade, AUTOMATICAMENTE serei DESCLASSIFICADO das demais especialidades que por ventura estiver concorrendo SEM POSSIBILIDADE DE REVERSÃO DESSA ESCOLHA.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="declaration-signature p-10 rounded border">
                        <div class="text-center">
                            <div class="signature-header mb-3">
                                <h5 class="fw-bold text-dark mb-1">DECLARAÇÃO DE CONFIRMAÇÃO</h5>
                                <p class="text-muted mb-0">Candidato abaixo assinado</p>
                            </div>
                            <div class="candidate-info mb-10">
                                <h4 class="text-primary fw-bold mb-2"><?= mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></h4>
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-primary mr-10">
                                        <i class="fa fa-id-card"></i>
                                        CPF: <?= mascara($_SESSION['cpf'], '###.###.###-##') ?>
                                    </span>
                                    <span class="badge bg-primary">
                                        <i class="fa fa-calendar"></i>
                                        DATA: <?= $datetime ?>
                                    </span>
                                </div>
                            </div>
                            <div class="signature-line mt-3 pt-3 border-top">
                                <p class="fw-bold text-uppercase mb-0"><?= mb_strtoupper($_SESSION['assinatura_sistema'], 'UTF-8') ?></p>
                                <small class="text-muted">Assinatura Eletrônica do Sistema</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Inscrições - Melhorado -->
            <div class="card">
                <div class="card-header mb-20">
                    <div class="d-flex align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-clipboard fa-lg me-3"></i>
                            Minhas Inscrições no Processo Seletivo
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-20 p-0" id="timer-area"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if (empty($lista_inscricoes)) : ?>
                                <div class="text-center py-4">
                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma inscrição encontrada</h5>
                                    <p class="text-muted">Você não possui inscrições ativas no processo seletivo.</p>
                                </div>
                            <?php else : ?>
                                <div class="inscricoes-grid">
                                    <?php foreach ($lista_inscricoes as $value) : ?>
                                        <?php
                                        //if($value['concorrendo'] == 0) continue;

                                        $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'], $value['id_especialidade']);
                                        $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);

                                        $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);

                                        $ott_stt = null;
                                        $ott_stt_badge = '';
                                        switch ($value['ott_stt']) {
                                            case 'ott':
                                                $ott_stt = "Oficial Técnico Temporário - OTT";
                                                $ott_stt_badge = 'bg-primary';
                                                break;
                                            case 'stt':
                                                $ott_stt = "Sargento Técnico Temporário - STT";
                                                $ott_stt_badge = 'bg-info';
                                                break;
                                            case 'medico':
                                                $ott_stt = "Médico";
                                                $ott_stt_badge = 'bg-success';
                                                break;
                                            case 'dentista':
                                                $ott_stt = "Dentista";
                                                $ott_stt_badge = 'bg-warning';
                                                break;
                                            case 'veterinario':
                                                $ott_stt = "Veterinário";
                                                $ott_stt_badge = 'bg-secondary';
                                                break;
                                            case 'farmaceutico':
                                                $ott_stt = "Farmacêutico";
                                                $ott_stt_badge = 'bg-dark';
                                                break;
                                        }

                                        $card_class = "border-success";
                                        $status_badge = "bg-primary";
                                        $status_text = "Elegível para Seleção";

                                        if (!empty($value['cidade_escolheu_servir'])) {
                                            $card_class = "border-primary";
                                            $status_badge = "bg-primary";
                                            $status_text = "Guarnição Já Selecionada";
                                        }

                                        if ($value['concorrendo'] == 0) {
                                            $card_class = "border-danger";
                                            $status_badge = "bg-danger";
                                            $status_text = "Desclassificado";
                                        }
                                        ?>

                                        <div class="card <?= $card_class ?>">
                                            <div class="card-header <?= $status_badge ?> text-white mb-20">
                                                <div class="d-flex justify-content-between align-items-center" style="width: 100%">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-graduation-cap me-2"></i>
                                                        <h5 class="card-title mb-0 me-3"><?= mb_strtoupper($value['especialidade'], 'UTF-8') ?></h5>
                                                        <span class="badge <?= $ott_stt_badge ?>"><?= $ott_stt ?></span>
                                                    </div>
                                                    <span class="badge <?=$status_badge?>"><?= $status_text ?></span>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <?php if ($value['concorrendo'] == 0) : ?>
                                                    <div class="alert alert-danger d-flex align-items-center">
                                                        <i class="fa fa-exclamation-triangle fa-2x mr-10"></i>
                                                        <div>
                                                            <h5 class="alert-heading mb-0">DESCLASSIFICADO</h5>
                                                            <p class="mb-0"><?= $value['justificativa'] ?></p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $lista_epecialidades = $conexao->get_cidades_especialidade($value['id_especialidade']);
                                                $get_vagas_especialidade = $conexao->get_vagas_especialidade($value['id_especialidade']);
                                                ?>

                                                <div class="vagas-info mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header mb-20">
                                                                    <h5 class="mb-0"><i class="fa fa-list"></i> Total de vagas Disponibilizadas</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php foreach ($lista_epecialidades as $linha) : ?>
                                                                        <?php
                                                                        $vagas = 0;
                                                                        if ((int)$linha['numero_vagas'] > 0) $vagas = $linha['numero_vagas'];
                                                                        ?>
                                                                        <div class="d-flex justify-content-between align-items-center mb-10 pb-10" style="border-bottom: 1px solid #ddd;">
                                                                            <span class="fw-medium"><?= $linha['nome'] ?></span>
                                                                            <span class="badge bg-primary"><?= $vagas ?> vaga(s)</span>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header mb-20">
                                                                    <h5 class="mb-0"><i class="fa fa-check-circle"></i> Total de vagas Restantes</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php foreach ($get_vagas_especialidade as $vaga) : ?>
                                                                        <?php
                                                                        $vagas = 0;
                                                                        if ((int)$vaga['vagas'] > 0) $vagas = $vaga['vagas'];
                                                                        $vaga_badge = $vagas > 0 ? 'bg-primary' : 'bg-danger';
                                                                        ?>
                                                                        <div class="d-flex justify-content-between align-items-center mb-10 pb-10" style="border-bottom: 1px solid #ddd;">
                                                                            <span class="fw-medium"><?= $vaga['cidade'] ?></span>
                                                                            <span class="badge <?= $vaga_badge ?>"><?= $vagas ?> vaga(s)</span>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($value['cidade_escolheu_servir'] != null) : ?>
                                                    <?php
                                                    $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
                                                    if ($get_cidade_escolhida[0]['nome'] != null) :
                                                    ?>
                                                        <div class="alert alert-success d-flex align-items-center">
                                                            <i class="fa fa-check-circle fa-2x mr-10"></i>
                                                            <div>
                                                                <h5 class="alert-heading mb-0">Guarnição Selecionada</h5>
                                                                <p class="mb-0 fw-bold fs-5"><?= $get_cidade_escolhida[0]['nome'] ?></p>
                                                                <small class="text-muted">Sua escolha foi registrada no sistema</small>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php
                                                $tem_vaga_ = false;
                                                foreach ($get_vagas_especialidade as $vaga) :
                                                    if ((int)$vaga['vagas'] > 0) $tem_vaga_ = true;
                                                endforeach;

                                                $pode_selecionar = true;
                                                if (!seleciona_cidade_vai_servir()) $pode_selecionar = false;
                                                ?>

                                                <?php if ($value['concorrendo'] == 1 && $value['cidade_escolheu_servir'] == null && $tem_vaga_ && $pode_selecionar) : ?>
                                                    <?php $crip = hash('sha256', $value['id_especialidade'] . "escolhe_cidade"); ?>

                                                    <div class="selecao-guarnicao border-top pt-4 mt-3" id="form-container-<?= $value['id_especialidade'] ?>">
                                                        <form action="../banco_dados/candidato_cidade_escolheu_servir.php" method="post" id="form-escolha-<?= $value['id_especialidade'] ?>">
                                                            <div class="row mb-20">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">
                                                                            <i class="fa fa-map-marker me-2"></i>
                                                                            Escolha a cidade onde deseja servir
                                                                        </label>
                                                                        <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                                                                            <option value="">Selecione uma guarnição (ÚNICA VEZ)</option>
                                                                            <?php foreach ($get_vagas_especialidade as $vaga) : ?>
                                                                                <?php if ((int)$vaga['vagas'] > 0) : ?>
                                                                                    <option value="<?= $vaga['id_cidade'] ?>">
                                                                                        <?= $vaga['cidade'] ?> - <?= $vaga['vagas'] ?> vaga(s) disponível(eis)
                                                                                    </option>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <option value="754809" class="text-danger fw-bold">Nenhuma das opções (Desistência das Vagas ofertadas)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-20">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="declaracao_<?= $value['id_especialidade'] ?>" name="declaracao" required>
                                                                            <label class="form-check-label fw-medium" for="declaracao_<?= $value['id_especialidade'] ?>">
                                                                                Declaro sob as penas da lei que li e compreendi todas as ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO acima.
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="crip" value="<?= $crip ?>">
                                                            <input type="hidden" name="id_especialidade" value="<?= $value['id_especialidade'] ?>">

                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                                                    <i class="fa fa-paper-plane me-2"></i> CONFIRMAR ESCOLHA DA GUARNIÇÃO
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <a name="fim_pagina"></a>
        </div>
    </div>
</div>

<script>
    // Variáveis globais para controle do timer
    let timerInterval = null;
    let tempoRestante = 30; // segundos
    let verificacaoEmAndamento = false;

    // Função principal para verificar se é a vez do candidato
    async function verificarVezEscolha(idEspecialidade, userId) {
        try {
            // Verificar no servidor se é a vez do candidato
            const resultado = await verificarVezNoServidor(idEspecialidade, userId);

            if (!resultado.podeEscolher) {
                // Exibir mensagem de quem é a vez
                exibirMensagemNaoVezParaForm(resultado.mensagem, resultado.proximoCandidato, resultado.informacoesAdicionais);
                return false;
            } else {
                // Exibir mensagem que pode escolher
                exibirMensagemPodeEscolherParaForm(resultado.mensagem, resultado.informacoesAdicionais);
                return true;
            }
        } catch (error) {
            console.error('Erro ao verificar vez:', error);
            exibirMensagemErro('Erro ao verificar se é sua vez. Tente novamente.');
            return false;
        }
    }

    // Função para verificar no servidor a ordem dos candidatos
    async function verificarVezNoServidor(idEspecialidade, userId) {
        const formData = new FormData();
        formData.append('id_especialidade', idEspecialidade);
        formData.append('user_id', userId);
        formData.append('verificar_vez', 'true');

        const response = await fetch('../banco_dados/verifica_vez_candidato.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Erro na requisição');
        }

        const data = await response.json();

        // Verificar se a resposta tem o formato esperado
        if (typeof data.podeEscolher === 'undefined') {
            throw new Error('Resposta do servidor inválida');
        }

        return data;
    }

    // Função para exibir mensagem de "não é sua vez" para um formulário específico
    function exibirMensagemNaoVezParaForm(form, mensagem, proximoCandidato, informacoesAdicionais = null) {
        // Remover mensagens anteriores deste formulário específico
        const existingAlert = form.parentNode.querySelector('.alert-vez-container');
        if (existingAlert) existingAlert.remove();

        // Criar mensagem de alerta
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-vez-container';
        alertDiv.dataset.formId = form.id || form.dataset.especialidadeId;

        let html = `<h4 class="alert-heading"><i class="fa fa-clock-o me-2"></i> Aguarde sua vez!</h4>`;
        html += `<p>${mensagem}</p>`;

        if (proximoCandidato) {
            html += `<p class="mb-1"><strong>Próximo a escolher:</strong> ${proximoCandidato}</p>`;
        }

        // Adicionar informações detalhadas se disponíveis
        if (informacoesAdicionais) {
            html += `<hr class="my-2">`;
            html += `<div class="text-muted">`;
            if (informacoesAdicionais.condicao_candidato) {
                html += `<p class="mb-1"><strong>Seu critério de Escolha:</strong> ${informacoesAdicionais.condicao_candidato}</p>`;
            }
            if (informacoesAdicionais.tipo_proxima_vaga) {
                html += `<p class="mb-1"><strong>Critério da próxima vaga:</strong> ${informacoesAdicionais.tipo_proxima_vaga}</p>`;
            }
            if (informacoesAdicionais.posicao_usuario) {
                html += `<p class="mb-1"><strong>Sua posição na fila:</strong> ${informacoesAdicionais.posicao_usuario}º</p>`;
            }
            if (informacoesAdicionais.candidatos_na_frente > 0) {
                html += `<p class="mb-1"><strong>Candidatos na sua frente:</strong> ${informacoesAdicionais.candidatos_na_frente}</p>`;
            }
            if (informacoesAdicionais.vagas_restantes) {
                html += `<p class="mb-1"><strong>Vagas restantes:</strong> ${informacoesAdicionais.vagas_restantes} de ${informacoesAdicionais.total_vagas}</p>`;
            }
            html += `</div>`;
        }

        alertDiv.innerHTML = html;

        // Inserir antes do formulário
        form.parentNode.insertBefore(alertDiv, form);

        // Desabilitar botão de envio DESTE formulário específico
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-clock-o me-2"></i> Aguardando vez...';
            submitButton.classList.remove('btn-primary');
            submitButton.classList.add('btn-secondary');
        }
    }

    // Função similar para "pode escolher" por formulário
    function exibirMensagemPodeEscolherParaForm(form, mensagem, informacoesAdicionais = null) {
        // Remover mensagens anteriores deste formulário específico
        const existingAlert = form.parentNode.querySelector('.alert-vez-container');
        if (existingAlert) existingAlert.remove();

        // Criar mensagem de sucesso
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-vez-container';
        alertDiv.dataset.formId = form.id || form.dataset.especialidadeId;

        let html = `<h4 class="alert-heading"><i class="fa fa-check-circle me-2"></i> É sua vez! Recarregue/Atualize a página antes de realizar sua escolha</h4>`;
        html += `<p>${mensagem}</p>`;

        // Adicionar informações detalhadas se disponíveis
        if (informacoesAdicionais) {
            html += `<hr class="my-2">`;
            html += `<div class="text-muted">`;
            if (informacoesAdicionais.condicao_candidato) {
                html += `<p class="mb-1"><strong>Sua condição:</strong> ${informacoesAdicionais.condicao_candidato}</p>`;
            }
            if (informacoesAdicionais.tipo_proxima_vaga) {
                html += `<p class="mb-1"><strong>Critério da próxima vaga:</strong> ${informacoesAdicionais.tipo_proxima_vaga}</p>`;
            }
            if (informacoesAdicionais.sua_posicao) {
                html += `<p class="mb-1"><strong>Sua posição na fila:</strong> ${informacoesAdicionais.sua_posicao}º</p>`;
            }
            if (informacoesAdicionais.vagas_restantes) {
                html += `<p class="mb-1"><strong>Vagas restantes:</strong> ${informacoesAdicionais.vagas_restantes} de ${informacoesAdicionais.total_vagas}</p>`;
            }
            if (informacoesAdicionais.proxima_vaga_numero) {
                html += `<p class="mb-1"><strong>Número da vaga:</strong> ${informacoesAdicionais.proxima_vaga_numero}ª vaga</p>`;
            }
            html += `</div>`;
        }
        alertDiv.innerHTML = html;

        // Inserir antes do formulário
        form.parentNode.insertBefore(alertDiv, form);

        // Habilitar botão de envio DESTE formulário específico
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa fa-paper-plane me-2"></i> CONFIRMAR ESCOLHA DA GUARNIÇÃO';
            submitButton.classList.remove('btn-secondary');
            submitButton.classList.add('btn-primary');
        }
    }

    function exibirMensagemErro(mensagem) {
        // Remover mensagens anteriores
        const existingAlerts = document.querySelectorAll('.alert-vez-container');
        existingAlerts.forEach(alert => alert.remove());

        // Criar mensagem de erro
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-vez-container';
        alertDiv.innerHTML = `
            <h4 class="alert-heading"><i class="fa fa-exclamation-triangle me-2"></i>Não é possível escolher agora</h4>
            <p class="mb-0">${mensagem}</p>
        `;

        // Inserir no início do content-wrapper
        const contentWrapper = document.querySelector('.content-wrapper');
        if (contentWrapper) {
            contentWrapper.insertBefore(alertDiv, contentWrapper.firstChild);
        }

        // Desabilitar todos os botões de envio
        document.querySelectorAll('form[action*="candidato_cidade_escolheu_servir.php"] button[type="submit"]').forEach(submitButton => {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-clock-o"></i> Aguardando verificação...';
            submitButton.classList.remove('btn-primary');
            submitButton.classList.add('btn-warning');
        });
    }

    function removerMensagens() {
        const alerts = document.querySelectorAll('.alert-vez-container');
        alerts.forEach(alert => alert.remove());
    }

    async function verificarFormularioEspecifico(form) {
        const idEspecialidade = form.querySelector('input[name="id_especialidade"]').value;
        const userId = <?= $_SESSION['id_usuario'] ?? 0 ?>;

        if (!idEspecialidade || !userId) {
            // Usar a função por formulário
            exibirMensagemErroParaForm(form, 'Dados insuficientes para verificação.');
            return false;
        }

        try {
            const resultado = await verificarVezNoServidor(idEspecialidade, userId);

            if (!resultado.podeEscolher) {
                exibirMensagemNaoVezParaForm(form, resultado.mensagem, resultado.proximoCandidato, resultado.informacoesAdicionais);
                return false;
            } else {
                exibirMensagemPodeEscolherParaForm(form, resultado.mensagem, resultado.informacoesAdicionais);
                return true;
            }
        } catch (error) {
            console.error('Erro ao verificar vez:', error);
            exibirMensagemErroParaForm(form, 'Erro ao verificar se é sua vez. Tente novamente.');
            return false;
        }
    }

    // Função para verificar todos os formulários
    async function verificarTodosFormularios() {
        if (verificacaoEmAndamento) {
            return;
        }

        verificacaoEmAndamento = true;
        const forms = document.querySelectorAll('form[action*="candidato_cidade_escolheu_servir.php"]');

        if (forms.length === 0) {
            verificacaoEmAndamento = false;
            return;
        }

        console.log(`[${new Date().toLocaleTimeString()}] Verificando ${forms.length} formulário(s)...`);

        try {
            // Verificar cada formulário em paralelo (Promise.all) ou sequencial
            for (const form of forms) {
                await verificarFormularioEspecifico(form);
                await new Promise(resolve => setTimeout(resolve, 100)); // Pequeno delay entre verificações
            }
        } catch (error) {
            console.error('Erro na verificação automática:', error);
        } finally {
            verificacaoEmAndamento = false;
        }
    }

    // Função para criar e gerenciar o timer de atualização
    function criarTimerAtualizacao() {
        // Criar container do timer
        const timerContainer = document.createElement('div');
        timerContainer.id = 'timer-atualizacao-container';
        timerContainer.className = 'timer-atualizacao';
        timerContainer.innerHTML = `
            <div class="d-flex align-items-center">
                <span class="mr-10">Próxima atualização de vagas restantes em:</span>
                <span id="timer-contador" class="badge bg-primary">30s</span>
            </div>
        `;

        // Adicionar ao header da página
        const pageHeader = document.querySelector('.page-title');
        if (pageHeader) {
            const timerArea = document.querySelector('#timer-area');
            if (timerArea) {
                timerArea.appendChild(timerContainer);
            } else {
                timerArea.appendChild(timerContainer);
            }
        }

        // Iniciar o timer
        iniciarTimer();
    }

    // Função para iniciar o timer
    function iniciarTimer() {
        tempoRestante = 30; // Reset para 30 segundos

        if (timerInterval) {
            clearInterval(timerInterval);
        }

        timerInterval = setInterval(() => {
            tempoRestante--;
            atualizarContadorTimer();

            if (tempoRestante <= 0) {
                // Executar verificação
                verificarTodosFormularios();

                // Resetar timer
                tempoRestante = 30;
                atualizarContadorTimer();
            }
        }, 1000); // Atualizar a cada segundo
    }

    // Função para atualizar o contador do timer
    function atualizarContadorTimer() {
        const timerContador = document.getElementById('timer-contador');
        if (timerContador) {
            timerContador.textContent = `${tempoRestante}s`;

            // Mudar cor baseado no tempo restante
            if (tempoRestante <= 5) {
                timerContador.className = 'badge bg-danger';
            } else if (tempoRestante <= 10) {
                timerContador.className = 'badge bg-warning';
            } else {
                timerContador.className = 'badge bg-primary';
            }
        }
    }

    // Função para atualizar status da verificação
    function atualizarStatusVerificacao(verificando) {
        const timerContador = document.getElementById('timer-contador');

        if (verificando) {
            if (timerContador) {
                timerContador.textContent = 'Verificando...';
                timerContador.className = 'badge bg-primary';
            }
        }
    }

    // Função para inicializar a verificação em todos os formulários
    function inicializarVerificacaoVez() {
        const forms = document.querySelectorAll('form[action*="candidato_cidade_escolheu_servir.php"]');

        forms.forEach((form, index) => {
            // Adicionar ID único ao formulário para referência
            if (!form.id) {
                form.id = `form-escolha-${index}`;
            }

            // Adicionar evento de submit
            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Impedir envio imediato

                const idEspecialidade = form.querySelector('input[name="id_especialidade"]').value;
                const userId = <?= $_SESSION['id_usuario'] ?? 0 ?>;

                // Verificar declaração
                const declaracaoCheckbox = form.querySelector('input[name="declaracao"]');
                if (!declaracaoCheckbox || !declaracaoCheckbox.checked) {
                    exibirMensagemErro('Você deve declarar que leu o aviso de ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO!');
                    return;
                }

                // Verificar cidade selecionada
                const cidadeSelect = form.querySelector('select[name="cidade_escolheu_servir"]');
                if (!cidadeSelect || !cidadeSelect.value) {
                    exibirMensagemErro('Você deve selecionar a cidade em que deseja servir!');
                    return;
                }

                // Verificar se é a vez
                const podeEscolher = await verificarVezEscolha(idEspecialidade, userId);

                if (podeEscolher) {
                    // Se pode escolher, submeter o formulário
                    this.submit();
                }
            });
        });

        // Criar timer de atualização
        criarTimerAtualizacao();

        // Verificar todos os formulários imediatamente
        setTimeout(() => {
            verificarTodosFormularios();
        }, 1000);
    }

    // Quando o DOM estiver carregado
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar cada formulário individualmente
        const forms = document.querySelectorAll('form[action*="candidato_cidade_escolheu_servir.php"]');

        forms.forEach((form) => {
            // Adicionar evento de submit específico
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                // Verificar declaração
                const declaracaoCheckbox = form.querySelector('input[name="declaracao"]');
                if (!declaracaoCheckbox || !declaracaoCheckbox.checked) {
                    exibirMensagemErroParaForm(form, 'Você deve declarar que leu o aviso de ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO!');
                    return;
                }

                // Verificar cidade selecionada
                const cidadeSelect = form.querySelector('select[name="cidade_escolheu_servir"]');
                if (!cidadeSelect || !cidadeSelect.value) {
                    exibirMensagemErroParaForm(form, 'Você deve selecionar a cidade em que deseja servir!');
                    return;
                }

                // Verificar se é a vez para ESTA especialidade
                const podeEscolher = await verificarFormularioEspecifico(form);

                if (podeEscolher) {
                    // Se pode escolher, submeter ESTE formulário específico
                    this.submit();
                }
            });

            // Inicialmente desabilitar botão DESTE formulário
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa fa-clock-o me-2"></i> Aguardando verificação...';
                submitButton.classList.remove('btn-primary');
                submitButton.classList.add('btn-secondary');
            }
        });

        // Criar timer de atualização
        criarTimerAtualizacao();

        // Verificar todos os formulários imediatamente
        setTimeout(() => {
            verificarTodosFormularios();
        }, 1000);
    });

    // Limpar intervalos quando a página for descarregada
    window.addEventListener('beforeunload', function() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
    });

    // Adicionar estilo para as mensagens e timer
    const style = document.createElement('style');
    style.textContent = `
        .alert-vez-container {
            animation: fadeIn 0.3s ease-in-out;
            margin-bottom: 15px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-vez-container hr {
            margin: 8px 0;
            border-color: rgba(0,0,0,0.1);
        }
        .alert-vez-container .small {
            font-size: 0.85rem;
        }
        button[disabled] {
            cursor: not-allowed;
            opacity: 0.7;
        }
        .timer-atualizacao {
            font-size: 1.6rem;
            color: #6c757d;
            padding: 5px 10px;
            background: rgba(108, 117, 125, 0.1);
            border-radius: 4px;
            border-left: 3px solid #17a2b8;
        }
        .timer-atualizacao .badge {
            font-size: 1.4rem;
            padding: 3px 8px;
            font-weight: 600;
            min-width: 45px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
        @media (max-width: 768px) {
            .timer-atualizacao {
                margin-top: 10px;
                font-size: 0.8rem;
            }
            .timer-atualizacao .badge {
                font-size: 0.75rem;
                min-width: 40px;
            }
        }
    `;
    document.head.appendChild(style);
</script>

</body>

</html>
<?php $conexao = null; ?>