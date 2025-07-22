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

$rm_candidato = $conexao->rm_usuario($_SESSION['id_usuario']);
$rms_interesse = explode(",", $rm_destino);

$rms_interesse_formatado = implode(', ', array_map(function ($n) {
    return $n . 'ª';
}, explode(',', $rm_destino)));

$lista_inscricoes = $conexao->get_especialidade_candidato_eipot($_SESSION['id_usuario']);
//var_dump($lista_inscricoes); exit; //159621


foreach ($lista_inscricoes as $inscricao) {
    $id_especialidade = (int)$inscricao['id_especialidade'];

    $get_vagas_especialidade = $conexao->get_vagas_especialidade($inscricao['id_especialidade']);
    $lista_cidades_epecialidades = $conexao->get_cidades_especialidade($inscricao['id_especialidade']);
    $vagas_preenchidas = $conexao->get_vagas_preenchidas($inscricao['id_especialidade']);

    include_once './codigos/ordena_candidatos_escolha_cidade.php';
}

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

                    <b>1</b>. Eu, <b><?php echo mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></b>, portador do CPF: <b><?php echo mascara($_SESSION['cpf'], '###.###.###-##') ?></b>, COMPREENDO que o preenchimento das vagas seguirá a sequência numérica das Regiões Militares (RM), ou seja, serão preenchidas primeiramente as vagas da 1ª RM, seguindo-se o preenchimento das vagas da 2ª RM, e assim sucessivamente, sendo a 12ª RM a última a ter preenchidas as suas vagas.
                    <br><b>2</b>. COMPREENDO que será respeitada a ordem de classificação dos candidatos que poderão optar por qualquer RM que tenha manifestado interesse no momento de sua inscrição. Para tanto, caso o candidato não queira, por exemplo, vaga na 1ª RM bastará escolher “Nenhuma das Opções (Desistência das localidades ofertadas na 1ª RM)”.
                    <br><b>3</b>. COMPREENDO que após o término do preenchimento das vagas da 1ª RM ocorrerá automaticamente outra rodada de escolhas para as vagas da 2ª RM para os candidatos que não se inscreveram para a 1ª RM bem como para aqueles que optaram por “Nenhuma das Opções (Desistência das localidades ofertadas na 1ª RM)”, sempre seguindo a sequência dos melhores classificados. Sucessivas rodadas de escolhas ocorrerão seguindo-se a sequência numérica das Regiões Militares, sempre permitindo o candidato optar por “Nenhuma das Opções (Desistência das localidades ofertadas na Xª RM)”.
                    <br><b>4</b>. COMPREENDO que as vagas serão esgotadas à medida que os candidatos melhores pontuados efetuam suas escolhas, até restar uma vaga para o seguinte candidato melhor pontuado.
                    <br><b>5</b>. COMPREENDO que caso o candidato não efetue o procedimento de escolha de guarnição, será considerado DESISTENTE e consequentemente ELIMINADO do certame, para que os demais candidatos efetuem o procedimento de escolha de guarnição.
                    <br><b>6</b>. DECLARO ter tomado conhecimento das orientações a respeito da ESCOLHA DE GUARNIÇÃO.
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

            // SE FOR FASE DE ESCOLHA DE GUARNIÇÃO
            if ($liberarEscolhaGuarnicao) {
                // Obtém a RM escolhida pelo candidato logado
                $rm_escolhida = null;
                foreach ($vetor_ordenado_candidatos as $cand) {
                    if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
                        $rm_escolhida = (int)$cand['rm_escolheu_servir'];
                        break;
                    }
                }

                if ($rm_escolhida) {
                    // Filtra candidatos que escolheram a mesma RM
                    $candidatos_rm_escolhida = array_filter($vetor_ordenado_candidatos, function ($c) use ($rm_escolhida) {
                        return !empty($c['rm_escolheu_servir']) && (int)$c['rm_escolheu_servir'] === $rm_escolhida;
                    });

                    // Ordena pela ordem_escolha_guarnicao
                    usort($candidatos_rm_escolhida, function ($a, $b) {
                        return $a['ordem_escolha_guarnicao'] <=> $b['ordem_escolha_guarnicao'];
                    });

                    // Verifica se há alguém na frente que ainda não escolheu guarnição
                    $eh_proximo_da_vez = true;
                    $candidato_bloqueado_por_anterior = false;
                    $candidatos_faltando_a_frente = 0;

                    foreach ($candidatos_rm_escolhida as $cand) {
                        if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
                            break;
                        }

                        if (empty($cand['cidade_escolheu_servir'])) {
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

                    // Se for o candidato logado
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
                                    $vagas_cotistas = calcularVagasCotistas($total_vagas);
                                    $vagas_ampla = $total_vagas - $vagas_cotistas;

                                    $ocupadas = $escolhas_por_regiao[$regiao_id] ?? 0;
                                    $ocupadas_ampla = 0;
                                    $ocupadas_cotistas = 0;

                                    // Contar quantas vagas já foram preenchidas por tipo
                                    foreach ($vetor_ordenado_candidatos as $c) {
                                        if (!empty($c['rm_escolheu_servir']) && $c['rm_escolheu_servir'] == $regiao_id) {
                                            if (!empty($c['vaga_reservada'])) {
                                                $ocupadas_cotistas++;
                                            } else {
                                                $ocupadas_ampla++;
                                            }
                                        }
                                    }

                                    // Verificar disponibilidade
                                    if ($linha['vaga_reservada']) {
                                        // Candidato cotista - pode pegar vaga cotista ou ampla (se não houver cotista)
                                        if ($ocupadas_cotistas < $vagas_cotistas) {
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        } elseif ($ocupadas_cotistas == 0 && $ocupadas_ampla < $vagas_ampla) {
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        }
                                    } else {
                                        // Candidato ampla - só pode pegar vaga ampla
                                        if ($ocupadas_ampla < $vagas_ampla) {
                                            $tem_rm_disponivel = true;
                                            $rm_disponiveis[] = $regiao_id;
                                        }
                                    }
                                }

                                if (!$tem_rm_disponivel) {
                                    $candidato_bloqueado_por_anterior = true;
                                } else {
                                    $candidato_bloqueado_por_anterior = false;
                                }
                            }
                        } else {
                            $candidato_bloqueado_por_anterior = true;
                        }
                        break;
                    }

                    // Conta candidatos anteriores que ainda não escolheram
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
                        <b>Você já escolheu sua Região Militar. Aguarde a próxima fase.</b>
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
                        <p>Você é o candidato número <?php echo $posicao_candidato; ?> no ranking.</p>
                    </div>

                <?php elseif ($candidato_logado_encontrado && !$candidato_guarnicao_escolhida && $liberarEscolhaGuarnicao): ?>
                    <legend>Escolha agora sua Guarnição <img src="imagens/urgente.gif" height="25px"></legend>
                    <div class="alert alert-info p-20">
                        <b>Abaixo estão listadas as especialidades nas quais você se inscreveu.</b>
                        <?php if ($candidato_bloqueado_por_anterior): ?>
                            <p>Há <?php echo $candidatos_faltando_a_frente; ?> candidato(s) na sua frente para realizar a escolha de guarnição.</p>
                        <?php endif; ?>
                    </div>

                <?php elseif ($candidato_logado_encontrado): ?>
                    <legend>Escolha de Guarnição Realizada</legend>
                    <div class="alert alert-info p-20">
                        <b>Aguarde as próximas publicações no site da Região Militar escolhida.</b>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <legend>Minhas inscrições no processo seletivo <?php if(!$liberarEscolhaGuarnicao) {echo (' (Em ordem de Preferência): ' . $rms_interesse_formatado);} ?></legend>

                <div class="row">
                    <?php if ($candidato_logado_encontrado && $eh_proximo_da_vez && !$candidato_ja_escolheu && !$liberarEscolhaGuarnicao): ?>
                        <div class="col-lg-12">
                            <?php foreach ($lista_inscricoes as $value): ?>
                                <?php if ($value['concorrendo'] == 1 && empty($value['rm_escolheu_servir'])): ?>
                                    <?php
                                    $crip = hash('sha256', $value['id_especialidade'] . "escolhe_rm");
                                    $ott_stt = $value['ott_stt'] == 'eipot' ? 'EIPOT' : null;
                                    $cor_retangulo = "success";
                                    ?>

                                    <div class="alert alert-<?= $cor_retangulo ?> p-20">
                                        <legend>
                                            <b>
                                                <font color="green">Primeira Escolha: Região Militar</font>
                                            </b>
                                        </legend>
                                        <form action="../banco_dados/candidato_rm_escolheu_servir_eipot.php" method="post">
                                            <br>
                                            <select id="rm_escolheu" name="rm_escolheu_servir" class="form-control" onchange="selecao_cidade()" required>
                                                <option value="">Selecione a Região Militar em que deseja servir</option>
                                                <?php $vagasPorRegiaoEncoded = base64_encode(json_encode($totalVagasPorRegiao)); ?>
                                                <?php $rmsInteresse = base64_encode(json_encode($rms_interesse)); ?>
                                                <?php foreach ($rms_interesse as $index => $rm): ?>
                                                    <?php
                                                    $total_vagas = $totalVagasPorRegiao[$rm] ?? 0;
                                                    $vagas_cotistas = calcularVagasCotistas($total_vagas);
                                                    $vagas_ampla = $total_vagas - $vagas_cotistas;

                                                    $ocupadas_ampla = 0;
                                                    $ocupadas_cotistas = 0;
                                                    $cotistas_na_ampla = 0;

                                                    foreach ($vetor_ordenado_candidatos as $c) {
                                                        if (!empty($c['rm_escolheu_servir']) && $c['rm_escolheu_servir'] == $rm) {
                                                            if (!empty($c['vaga_reservada'])) {
                                                                if ($c['ordemEscolhaGuarnicao'] <= $vagas_cotistas) {
                                                                    $ocupadas_cotistas++;
                                                                } else {
                                                                    $cotistas_na_ampla++;
                                                                    $ocupadas_ampla++;
                                                                }
                                                            } else {
                                                                $ocupadas_ampla++;
                                                            }
                                                        }
                                                    }

                                                    $vagas_ampla_restantes = $vagas_ampla - $ocupadas_ampla;
                                                    $vagas_cotistas_restantes = $vagas_cotistas - $ocupadas_cotistas;
                                                    $total_restante = $total_vagas - ($ocupadas_ampla + $ocupadas_cotistas + $cotistas_na_ampla);

                                                    if ($linha['vaga_reservada']) {
                                                        $mostrar_opcao = ($vagas_cotistas_restantes > 0) || ($vagas_ampla_restantes > 0);
                                                    } else {
                                                        $mostrar_opcao = ($vagas_ampla_restantes > 0) || ($ocupadas_cotistas < $vagas_cotistas);
                                                    }

                                                    if ($mostrar_opcao && ($total_restante > 0)): ?>
                                                        <option value="<?= $rm ?>">
                                                            <?= $index + 1 ?>ª Opção - <?= $rm ?>ª RM
                                                            (Total: <?= $total_restante ?> |
                                                            Ampla: <?= max(0, $vagas_ampla_restantes) ?> |
                                                            Cota: <?= max(0, $vagas_cotistas_restantes) ?>)
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <option value="754809">Nenhuma das Opções (Desistência do Processo Seletivo)</option>
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

                    <?php if ($liberarEscolhaGuarnicao): ?>
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
                                            <font color="green">Segunda Escolha: Guarnição - <?= $rm_escolhida ?>ª RM</font>
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
                                                <th>Total Vagas</th>
                                                <th>Vagas Restantes</th>
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
                                            <font size="3" color="black">
                                                <b>Guarnição escolhida para servir:</b> <?= $get_cidade_escolhida[0]['nome'] ?>
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

                                                        $proxima_posicao = $preenchidas_na_rm + 1;
                                                        $eh_vaga_cotista = ($proxima_posicao % 5 === 0);
                                                        $candidato_e_cotista = !empty($value['vaga_reservada']);

                                                        if (!$eh_vaga_cotista || ($eh_vaga_cotista && $candidato_e_cotista)) {
                                                            echo '<option value="' . $id_cidade . '">' . $vaga['cidade'] . ' (' . $vagas_disponiveis . ' vagas)</option>';
                                                            $opcoes_exibidas = true;
                                                        }
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
<?php $conexao = null; ?>