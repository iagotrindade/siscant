<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once '../sistema/funcoes.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

if ($_SESSION['perfil'] != 'candidato') {
    erro("Erro: 347356895465! Não foi possível abrir a página");
    exit();
}

if (!isset($_SESSION['candidato_etapa']) || $_SESSION['candidato_etapa'] < 4) {
    erro("Erro: 568334767! Não foi possível abrir a página");
    exit();
}

$get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
$rm_candidato = $conexao->rm_usuario($_SESSION['id_usuario']);
$rms_interesse = explode(",", $rm_destino);

$rms_interesse_formatado = implode(', ', array_map(function ($n) {
    return $n . 'ª';
}, explode(',', $rm_destino)));

$lista_inscricoes = $conexao->get_especialidade_candidato_eipot($_SESSION['id_usuario']);

foreach ($lista_inscricoes as $inscricao) {
    $id_especialidade = (int)$inscricao['id_especialidade'];

    $get_vagas_especialidade = $conexao->get_vagas_especialidade($inscricao['id_especialidade']);
    $lista_cidades_epecialidades = $conexao->get_cidades_especialidade($inscricao['id_especialidade']);
    $vagas_preenchidas = $conexao->get_vagas_preenchidas($inscricao['id_especialidade']);

    include_once './codigos/ordena_candidatos_escolha_cidade.php';
}

foreach ($vetor_ordenado_candidatos as $cand) {
    if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
        $rm_escolheu_servir = $cand['rm_escolheu_servir'];
    }
}

$candidatos_na_rm = $conexao->candidatos_por_rm_escolhida($id_especialidade, $rm_escolheu_servir);

// Inicializa como true (vamos verificar as condições para ver se precisa mudar)
$liberarEscolhaGuarnicao = true;

// 1. Verifica se todos os candidatos já escolheram RM
$todos_escolheram = true;
foreach ($vetor_ordenado_candidatos as $cand) {
    if (empty($cand['rm_escolheu_servir'])) {
        $todos_escolheram = false;
        break;
    }
}

// 2. Verifica se todas as vagas das RMs foram preenchidas
$todas_vagas_preenchidas = true;

foreach ($totalVagasPorRegiao as $regiao => $total_vagas) {
    // Conta quantos já escolheram esta RM
    $escolheram_esta_rm = 0;
    foreach ($vetor_ordenado_candidatos as $cand) {
        if (!empty($cand['rm_escolheu_servir']) && $cand['rm_escolheu_servir'] == $regiao) {
            $escolheram_esta_rm++;
        }
    }

    if ($escolheram_esta_rm < $total_vagas) {
        $todas_vagas_preenchidas = false;
        break;
    }
}

// Libera a escolha de guarnição se:
// - TODOS os candidatos já escolheram SUA RM OU
// - TODAS as vagas de TODAS as RMs foram preenchidas
$liberarEscolhaGuarnicao = $todos_escolheram || $todas_vagas_preenchidas;

// Otimização: Podemos sair do loop assim que encontrar uma vaga não preenchida

$datetime = date('d/m/Y H:i:s');

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Escolha de Guarnição</h1>
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

            <div class="card">

                <legend> ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO</legend>

                <div class="alert alert-info p-20">
                    <b>1</b>. Eu, <b><?php echo mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></b>, portador do CPF: <b><?php echo mascara($_SESSION['cpf'], '###.###.###-##') ?></b>, COMPREENDO que o preenchimento das vagas ocorrerá em 2 (duas) fases:
                    <ul>
                        <li> 1ª Fase: Escolha APENAS de Região Militar (RM); e</li>
                        <li> 2ª Fase: Escolha de Guarnição.</li>
                    </ul>

                    <br>
                    <b>2</b>. COMPREENDO que o candidato deverá atualizar a página até que a mensagem
                    “AGUARDE A SUA VEZ” altere para “ESCOLHA AGORA A SUA RM”. Nesse instante,
                    começará a 1ª Fase em que o candidato escolherá, respeitada a ordem de sua
                    classificação, APENAS uma RM que deseja ocupar vaga dentre aquelas RM que
                    manifestou interesse de ocupar vaga em sua Inscrição e que ainda exista vaga para a sua
                    classificação em sua ARMA/QUADRO/SERVIÇO.
                    <br><br><b>3</b>. COMPREENDO que na 1ª Fase, para o preenchimento das vagas, será observado o
                    percentual de 20% para os candidatos que optaram por concorrer às vagas reservadas
                    para candidatos negros e que a autodeclaração foi confirmada na Heteroidentificação
                    Complementar, cuja ARMA/QUADRO/SERVIÇO de inscrição existam 3 (três) ou mais
                    vagas na RM escolhida.
                    <br><br><b>4</b>. COMPREENDO que na 1ª FASE o candidato poderá optar por “Nenhuma das Opções
                    (Desistência das Vagas Ofertadas)”, sendo por este ato considerado DESISTENTE das
                    vagas ofertadas.
                    <br><br><b>5</b>. COMPREENDO que se na 1ª FASE não houver mais vaga disponível para a sua
                    classificação em sua ARMA/QUADRO/SERVIÇO o candidato não ocupará vaga e
                    aparecerá a mensagem “NÃO HÁ MAIS VAGAS DISPONÍVEIS EM SUA
                    ARMA/QUADRO/SERVIÇO”.
                    <br><br><b>6</b>. COMPREENDO que após a escolha de RM (1ª Fase) os candidatos devem aguardar o
                    próximo momento da escolha de Guarnição (2ª Fase) atualizando a página até que
                    apareça a mensagem “ESCOLHA A SUA GUARNIÇÃO”.
                    <br><br><b>7</b>. COMPREENDO que na 2ª Fase o candidato poderá escolher SOMENTE uma GUARNIÇÃO
                    dentre as pertencentes à Região Militar escolhida na 1ª Fase e que ainda exista vaga
                    disponível para a sua classificação em sua ARMA/QUADRO/SERVIÇO. O candidato
                    poderá optar por “Nenhuma das Opções (Desistência das Vagas Ofertadas)”, sendo por
                    este ato considerado DESISTENTE das vagas ofertadas.
                    <br><br><b>8</b>. DECLARO ter tomado conhecimento das orientações a respeito da ESCOLHA DE
                    GUARNIÇÃO.
                    <br><br>

                    <center>
                        <b>
                            <?php echo mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?>
                        </b>

                        <br>

                        <?php echo "<b>CPF</b>: " . mascara($_SESSION['cpf'], '###.###.###-##') . " <br><b>DATA</b>: " . $datetime ?>
                        <br>
                        <?php echo mb_strtoupper($_SESSION['assinatura_sistema'], 'UTF-8') ?>

                    </center>
                </div>

            </div>

            <?php
            $candidato_bloqueado_por_anterior = false;
            $candidatos_faltando_a_frente = 0;
            $candidato_guarnicao_escolhida = null;
            $candidato_ja_escolheu = false;
            $candidato_logado_encontrado = false;
            $eh_proximo_da_vez = false;
            $posicao_candidato = 0;
            $posicao_atual = 0;

            // Agrupa o total de escolhas feitas por RM (primeira escolha)
            $escolhas_por_regiao = [];

            foreach ($vagas_preenchidas as $item) {
                $regiao = (int) $item['regiao_militar'];
                $quantidade = (int) $item['preenchidas'];
                $escolhas_por_regiao[$regiao] = ($escolhas_por_regiao[$regiao] ?? 0) + $quantidade;
            }

            // Verifica se o candidato logado é o próximo da vez (PRIMEIRA FASE)
            $eh_proximo_da_vez = true;
            foreach ($vetor_ordenado_candidatos as $cand) {
                if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
                    break;
                }

                if (empty($cand['rm_escolheu_servir'])) {
                    $eh_proximo_da_vez = false;
                    break;
                }
            }

            // Função auxiliar
            function vagaEhCotista($posicao_vaga, $total_vagas)
            {
                if ($total_vagas < 3) {
                    return false;
                } elseif ($total_vagas < 5) {
                    return $posicao_vaga === $total_vagas; // última vaga cotista
                } else {
                    return $posicao_vaga % 5 === 0; // múltiplos de 5
                }
            }

            // SE FOR FASE DE ESCOLHA DE GUARNIÇÃO
            if ($liberarEscolhaGuarnicao) {
                // Obtém a RM escolhida pelo candidato logado
                $rm_escolhida = null;
                foreach ($candidatos_na_rm as $index => $cand) {
                    if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
                        $rm_escolhida = (int)$cand['rm_escolheu_servir'];
                        break;
                    }
                }

                if ($rm_escolhida) {
                    // Aqui usamos diretamente os candidatos já filtrados e ordenados
                    $candidato_ja_escolheu = true;
                    $eh_proximo_da_vez = true;
                    $candidato_bloqueado_por_anterior = false;
                    $candidatos_faltando_a_frente = 0;

                    foreach ($candidatos_na_rm as $cand) {
                        if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
                            if (empty($cand['cidade_escolheu_servir'])) {
                                // Alguém antes ainda não escolheu
                                $candidato_ja_escolheu = false;
                            }
                            break; // Chegou na vez do candidato logado
                        }

                        if (empty($cand['cidade_escolheu_servir'])) {
                            // Alguém antes ainda não escolheu
                            $eh_proximo_da_vez = false;
                            $candidato_bloqueado_por_anterior = true;
                            $candidatos_faltando_a_frente++;
                        }
                    }
                }
            } else {
                // PRIMEIRA FASE (ESCOLHA DE RM)
                foreach ($vetor_ordenado_candidatos as $linha) {
                    $posicao_atual++;
                    $regiao = (int) $linha['rm_inscricao'];


                    if ((int)$linha['id'] === (int)$_SESSION['id_usuario']) {
                        $candidato_logado_encontrado = true;
                        $posicao_candidato = $posicao_atual;
                        $candidato_ja_escolheu = !empty($linha['rm_escolheu_servir']);

                        if (!$candidato_bloqueado_por_anterior && $eh_proximo_da_vez) {
                            if (empty($linha['rm_escolheu_servir'])) {
                                $tem_rm_disponivel = false;
                                $rm_disponiveis = [];

                                foreach ($rms_interesse as $regiao_id) {
                                    $total_vagas = $totalVagasPorRegiao[$regiao_id] ?? 0;

                                    // Contar quantas vagas já foram preenchidas (independente do tipo)
                                    $ocupadas = 0;
                                    foreach ($vetor_ordenado_candidatos as $c) {
                                        if (!empty($c['rm_escolheu_servir']) && (int)$c['rm_escolheu_servir'] === $regiao_id) {
                                            $ocupadas++;
                                        }
                                    }

                                    $proxima_posicao = $ocupadas + 1;
                                    $eh_posicao_cotista = vagaEhCotista($proxima_posicao, $total_vagas);

                                    if (!empty($linha['vaga_reservada'])) {
                                        // Cotista pode ocupar vaga cotista
                                        if ($eh_posicao_cotista) {
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        } elseif ($ocupadas === 0 && !$eh_posicao_cotista) {
                                            // Primeira vaga ainda é ampla e ninguém escolheu
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        }
                                    } else {
                                        // Ampla só pode ocupar vaga ampla
                                        if (!$eh_posicao_cotista) {
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        }
                                    }
                                }

                                $candidato_bloqueado_por_anterior = !$tem_rm_disponivel;
                            }
                        } else {
                            $candidato_bloqueado_por_anterior = true;
                        }
                        break;
                    }

                    if (empty($linha['rm_escolheu_servir'])) {
                        $candidatos_faltando_a_frente++;
                    }
                }
            }
            ?>

            <div class="card">
                <?php if ($candidato_logado_encontrado && $candidato_ja_escolheu && !$liberarEscolhaGuarnicao): ?>
                    <legend>Aguarde sua vez <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>Você já escolheu sua Região Militar. A sua escolha ainda NÃO terminou. Aguarde a próxima fase para escolha de Guarnição</b>
                    </div>

                <?php elseif ($candidato_logado_encontrado && !$candidato_ja_escolheu && $candidato_bloqueado_por_anterior): ?>
                    <legend>Aguarde sua vez <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>Há <?php echo $candidatos_faltando_a_frente; ?> candidato(s) na sua frente para realizar a escolha.</b>
                    </div>

                <?php elseif ($candidato_logado_encontrado && $eh_proximo_da_vez && !$candidato_ja_escolheu && !$liberarEscolhaGuarnicao): ?>
                    <legend>Escolha agora sua Região Militar <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>É a sua vez de escolher! Abaixo estão as Regiões Militares disponíveis.</b>
                    </div>

                <?php elseif ($liberarEscolhaGuarnicao && $candidato_ja_escolheu): ?>
                    <legend>Escolha de Guarnição Realizada</legend>
                    <div class="alert alert-info p-20">
                        <b>Aguarde as próximas publicações no site da Região Militar escolhida.</b>
                    </div>

                <?php elseif ($liberarEscolhaGuarnicao && !$eh_proximo_da_vez): ?>
                    <legend>Aguarde sua vez <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>Há <?php echo $candidatos_faltando_a_frente; ?> candidato(s) na sua frente para realizar a escolha de Guarnição.</b>
                    </div>

                <?php elseif ($liberarEscolhaGuarnicao && $eh_proximo_da_vez && !$candidato_ja_escolheu && $get_candidato[0]['concorrendo']): ?>
                    <legend>Escolha agora sua Guarnição <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>É a sua vez de escolher! Abaixo estão as Guarnições disponíveis na RM que você escolheu.</b>
                        <?php if ($candidato_bloqueado_por_anterior): ?>
                            <p>Há <?php echo $candidatos_faltando_a_frente; ?> candidato(s) na sua frente para realizar a escolha de guarnição.</p>
                        <?php endif; ?>
                    </div>

                <?php elseif (!$get_candidato[0]['concorrendo']): ?>
                    <legend>Você não está concorrendo <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-danger p-20">
                        <b>Justificativa: <?= $get_candidato[0]['justificativa_concorrendo'] ?></b>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <legend>Minhas inscrições no processo seletivo <?php if (!$liberarEscolhaGuarnicao || !$get_candidato[0]['concorrendo']) {
                                                                    echo (' (Em ordem de Preferência): ' . $rms_interesse_formatado);
                                                                } ?></legend>

                <div class="row">
                    <?php if ($candidato_logado_encontrado && $eh_proximo_da_vez && !$candidato_ja_escolheu && !$liberarEscolhaGuarnicao): ?>
                        <div class="col-lg-12">
                            <?php foreach ($lista_inscricoes as $value): ?>
                                <?php if ($value['concorrendo'] == 1 && empty($value['rm_escolheu_servir'])): ?>
                                    <?php
                                    $crip = hash('sha256', $value['id_especialidade'] . "escolhe_rm");
                                    $ott_stt = $value['ott_stt'] == 'eipot' ? 'EIPOT' : null;
                                    $cor_retangulo = "success";

                                    $existe_cotista_disponivel = false;
                                    foreach ($vetor_ordenado_candidatos as $cand) {
                                        if ($cand['vaga_reservada'] && empty($cand['rm_escolheu_servir'])) {
                                            $existe_cotista_disponivel = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <div class="alert alert-<?= $cor_retangulo ?> p-20">
                                        <legend>
                                            <b>
                                                <font color="green">Primeira Escolha: Região Militar</font>
                                            </b>
                                        </legend>

                                        <!-- AVISO DE CANDIDATO COTISTA -->

                                        <?php if ($linha['vaga_reservada'] == 1): ?>
                                            <div class="alert alert-danger p-20">
                                                <b>Atenção! Você é um candidato cotista. Sendo assim, pode escolher as vagas reservadas.</b>
                                            </div>

                                        <?php elseif (!$existe_cotista_disponivel): ?>
                                            <div class="alert alert-danger p-20">
                                                <b>Atenção! Você NÃO É um candidato cotista. Porém não existem mais candidatos cotistas para esta especialidade, sendo assim você pode preencher vagas reservadas.</b>
                                            </div>

                                        <?php else: ?>
                                            <div class="alert alert-danger p-20">
                                                <b>Atenção! Você NÃO É um candidato cotista. Sendo assim, NÃO pode escolher as vagas reservadas.</b>
                                            </div>
                                        <?php endif; ?>

                                        <form action="../banco_dados/candidato_rm_escolheu_servir_eipot.php" method="post">
                                            <br>
                                            <select id="rm_escolheu" name="rm_escolheu_servir" class="form-control" onchange="selecao_cidade()" required>
                                                <option value="">Selecione qualquer Região Militar em que deseja servir</option>
                                                <?php $vagasPorRegiaoEncoded = base64_encode(json_encode($totalVagasPorRegiao)); ?>
                                                <?php $rmsInteresse = base64_encode(json_encode($rms_interesse)); ?>
                                                <?php foreach ($rms_interesse as $index => $rm): ?>
                                                    <?php
                                                    $total_vagas = $totalVagasPorRegiao[$rm] ?? 0;
                                                    $vagas_cotistas = calcularVagasCotistas($total_vagas);

                                                    $vagas_ampla = $total_vagas - $vagas_cotistas;


                                                    $ocupadas_cotistas = 0;
                                                    $ocupadas_ampla = 0;
                                                    $cotistas_na_ampla = 0;

                                                    foreach ($vetor_ordenado_candidatos as $c) {
                                                        if (!empty($c['rm_escolheu_servir']) && (int)$c['rm_escolheu_servir'] === (int)$rm) {
                                                            $ordem = (int)$c['ordem_escolha_guarnicao'];
                                                            $total_vagas_rm = (int)$totalVagasPorRegiao[$rm];

                                                            $eh_vaga_cotista = vagaEhCotista($ordem, $total_vagas_rm);

                                                            $eh_cotista = !empty($c['vaga_reservada']);

                                                            if ($eh_vaga_cotista) {
                                                                if ($eh_cotista) {
                                                                    $ocupadas_cotistas++;
                                                                } else {
                                                                    // Ampla ocupando vaga reservada (deveria ser evitado)
                                                                    $ocupadas_ampla++; // ou apenas ignorar
                                                                }
                                                            } else {
                                                                // Vaga ampla
                                                                $ocupadas_ampla++;
                                                                if ($eh_cotista) {
                                                                    $cotistas_na_ampla++;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $vagas_ampla_restantes = $vagas_ampla - $ocupadas_ampla;
                                                    $vagas_cotistas_restantes = $vagas_cotistas - $ocupadas_cotistas;
                                                    $total_restante = $total_vagas - ($ocupadas_ampla + $ocupadas_cotistas);

                                                    // Nova lógica para ocultar opção se só restarem vagas de cotas
                                                    $so_restam_vagas_de_cota = ($total_restante === $vagas_cotistas_restantes);

                                                    // Verifica se o candidato pode ver essa opção
                                                    if ($linha['vaga_reservada']) {
                                                        // Cotista pode ver se houver qualquer vaga restante
                                                        $mostrar_opcao = $total_restante > 0;
                                                    } else {
                                                        // Ampla só pode ver se ainda restar vaga ampla
                                                        $mostrar_opcao = !$so_restam_vagas_de_cota;
                                                    }

                                                    ?>
                                                    <option value="<?= $rm ?>">
                                                        <?= $index + 1 ?>ª Prioridade Declarada na Inscrição - <?= $rm ?>ª RM
                                                        (Total Vagas: <?= $total_restante ?> |
                                                        Ampla: <?= max(0, $vagas_ampla_restantes) ?> |
                                                        Cota: <?= max(0, $vagas_cotistas_restantes) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                                <option value="754809">Nenhuma das Opções (Desistência das Vagas Ofertas)</option>
                                            </select>

                                            <br>

                                            <label>
                                                <input type="checkbox" id="declaracao" name="declaracao" required>
                                                <span class="label-text">Declaro que li o aviso ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO.</span>
                                            </label>

                                            <br>
                                            <input type="hidden" name="crip" value="<?= $crip ?>">
                                            <input type="hidden" name="id_especialidade" value="<?= $value['id_especialidade'] ?>">

                                            <b>
                                                <font color="red" size="4">
                                                    <p id="mensagem_erro_cidade"></p>
                                                </font>
                                            </b>

                                            <input type="hidden" name="vagas_por_regiao" value="<?php echo $vagasPorRegiaoEncoded; ?>">
                                            <input type="hidden" name="rms_interesse" value="<?php echo $rmsInteresse; ?>">
                                            <input type="hidden" name="crip" value="<?= $crip ?>">
                                            <input type="hidden" name="id_especialidade" value="<?= $value['id_especialidade'] ?>">

                                            <button type="submit" class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($liberarEscolhaGuarnicao && $get_candidato[0]['concorrendo']): ?>
                        <div class="col-lg-12">
                            <?php foreach ($lista_inscricoes as $value): ?>
                                <?php
                                $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);
                                $ott_stt = $value['ott_stt'] == 'eipot' ? 'EIPOT' : null;
                                $cor_retangulo = (!empty($value['cidade_escolheu_servir']) || $value['concorrendo'] == 0) ? "info" : "success";

                                // Obtém a RM que o candidato escolheu para esta especialidade
                                $rm_escolhida = (int)$value['rm_escolheu_servir'];
                                ?>

                                <div class="alert alert-<?= $cor_retangulo ?> p-20">
                                    <legend>
                                        <b>
                                            <font color="green">Segunda Escolha: Guarnição - <?= $rm_escolhida ? '' : 'ª Região Militar' ?></font>
                                        </b>
                                    </legend>
                                    <b>
                                        <font size="3"><?= $ott_stt ?> - <?= mb_strtoupper($value['especialidade'], 'UTF-8') ?></font>
                                    </b>

                                    <?php if ($value['concorrendo'] == 0): ?>
                                        <br>
                                        <font color="red"><b>DESCLASSIFICADO:</b> <?= $value['justificativa'] ?></font>
                                    <?php endif; ?>

                                    <br><br>
                                    <b>
                                        <p class="mb-20px bold">VAGAS DISPONÍVEIS:</p>
                                    </b>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Guarnição</th>
                                                <th>Total Vagas Existentes</th>
                                                <th>Vagas Ainda Disponíveis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tem_vaga = false;
                                            foreach ($lista_cidades_epecialidades as $linha):
                                                $rm = (int)$linha['regiao_militar'];
                                                if ($rm !== $rm_escolhida) continue;

                                                $nome = $linha['nome'];
                                                $total = max(0, (int)$linha['numero_vagas']);
                                                $restantes = 0;

                                                foreach ($get_vagas_especialidade as $vaga) {
                                                    if (
                                                        (int)$vaga['id_cidade'] === (int)$linha['id'] &&
                                                        (int)$vaga['regiao_militar'] === $rm
                                                    ) {
                                                        $restantes = max(0, (int)$vaga['vagas']);
                                                        if ($restantes > 0) $tem_vaga = true;
                                                        break;
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td><?= $nome . '/' . $linha['uf'] . ' - ' . $rm . 'ªRM' ?></td>
                                                    <td><?= $total ?></td>
                                                    <td><?= $restantes ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <?php if (!empty($value['cidade_escolheu_servir'])): ?>
                                        <?php
                                        $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
                                        if (!empty($get_cidade_escolhida[0]['nome'])): ?>
                                            <br>
                                            <font size="4" color="Green">
                                                <b>Sucesso! Guarnição - RM Escolhida por <?= $get_candidato[0]['nome_completo'] ?>: <?= $get_cidade_escolhida[0]['nome'] . '/' . $get_cidade_escolhida[0]['uf'] . ' - ' . $value['rm_escolheu_servir'] . 'ª Região Militar' ?>
                                                </b>
                                            </font>
                                            <br>
                                            <font size="4" color="Green">
                                                <b>
                                                    Consulte em 01 AGO 25 no Site da RM escolhida a Publicação de sua Convocação para Seleção Complementar
                                                </b>
                                            </font>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php
                                    $pode_selecionar = seleciona_cidade_vai_servir();
                                    $candidato_habilitado = (
                                        $value['concorrendo'] == 1 &&
                                        empty($value['cidade_escolheu_servir']) &&
                                        $tem_vaga &&
                                        $pode_selecionar &&
                                        !$candidato_bloqueado_por_anterior
                                    );
                                    ?>

                                    <?php if ($candidato_habilitado): ?>

                                        <?php $crip = hash('sha256', $value['id_especialidade'] . "escolhe_cidade"); ?>
                                        <form action="../banco_dados/candidato_cidade_escolheu_servir_eipot.php" method="post">
                                            <br>
                                            <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()" required>
                                                <option value="">Selecione a Guarnição em que deseja servir</option>
                                                <?php
                                                $opcoes_exibidas = false;
                                                foreach ($get_vagas_especialidade as $vaga):
                                                    $rm = (int)$vaga['regiao_militar'];
                                                    if ($rm !== $rm_escolhida) continue;

                                                    $id_cidade = (int)$vaga['id_cidade'];
                                                    $vagas_disponiveis = (int)$vaga['vagas'];

                                                    if ($vagas_disponiveis > 0) {
                                                        $preenchidas_na_rm = 0;
                                                        foreach ($vagas_preenchidas as $vp) {
                                                            if ((int)$vp['regiao_militar'] === $rm) {
                                                                $preenchidas_na_rm += (int)$vp['preenchidas'];
                                                            }
                                                        }

                                                        echo '<option value="' . $id_cidade . '">' . $vaga['cidade'] . ' (' . $vagas_disponiveis . ' vagas)</option>';
                                                        $opcoes_exibidas = true;
                                                    }
                                                endforeach;

                                                if ($opcoes_exibidas): ?>
                                                    <option value="754809">Nenhuma das Opções (Desistência das localidades ofertadas)</option>
                                                <?php endif; ?>
                                            </select>
                                            <br>

                                            <label>
                                                <input type="checkbox" id="declaracao" name="declaracao" required>
                                                <span class="label-text">Declaro que li o aviso ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO.</span>
                                            </label>

                                            <br>
                                            <input type="hidden" name="crip" value="<?= $crip ?>">
                                            <input type="hidden" name="id_especialidade" value="<?= $value['id_especialidade'] ?>">
                                            <input type="hidden" name="rm_escolhida" value="<?= $rm_escolhida ?>">

                                            <b>
                                                <font color="red" size="4">
                                                    <p id="mensagem_erro_cidade"></p>
                                                </font>
                                            </b>

                                            <button type="submit" class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a name="fim_pagina"></a>

        <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
    </div>
</div>
</div>
</body>

</html>