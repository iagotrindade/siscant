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

$liberarEscolhaGuarnicao = true;

foreach ($vetor_ordenado_candidatos as $cand) {
    if (!$cand['rm_escolheu_servir']) {
        $liberarEscolhaGuarnicao = false;
        break; // ← evita iteração desnecessária
    }
}

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
            $candidato_encontrado = false;

            // Agrupa o total de escolhas feitas por RM (primeira escolha)
            $escolhas_por_regiao = [];

            foreach ($vagas_preenchidas as $item) {
                $regiao = $item['regiao_militar'];
                $quantidade = (int) $item['preenchidas'];

                if (!isset($escolhas_por_regiao[$regiao])) {
                    $escolhas_por_regiao[$regiao] = 0;
                }

                $escolhas_por_regiao[$regiao] += $quantidade;
            }

            // Percorre candidatos ordenados pelo ranking
            foreach ($vetor_ordenado_candidatos as $linha) {
                $regiao = $linha['rm_inscricao'];

                // Se for o candidato logado
                if ((int)$linha['id'] === (int)$_SESSION['id_usuario']) {
                    $candidato_guarnicao_escolhida = $linha['cidade_escolheu_servir'];
                    $candidato_encontrado = true;

                    // ✅ Verifica se ainda está na primeira fase (escolha da RM)
                    if (empty($linha['rm_escolheu_servir'])) {
                        
                        // Fase de escolha da RM - aplicar 4x1
                        $posicao_vaga = ($escolhas_por_regiao[$regiao] ?? 0) + 1;

                       
                        $eh_vaga_cotista = ($posicao_vaga % 5 === 0); // 5ª, 10ª, etc.

                        if ($eh_vaga_cotista && !$linha["vaga_reservada"]) {
                            // Vaga reservada e candidato é ampla
                            $candidato_bloqueado_por_anterior = true;
                        } else {
                            $candidato_bloqueado_por_anterior = false;
                        }
                    } else {
                        // ✅ Segunda fase (guarnição) - sem 4x1
                        $candidato_bloqueado_por_anterior = false;
                    }

                    break;
                }

                // Candidato anterior ainda não escolheu guarnição
                if (empty($linha['cidade_escolheu_servir'])) {
                    $candidatos_faltando_a_frente++;

                    // Se ainda está na escolha da RM e anterior é ampla
                    if (empty($linha['rm_escolheu_servir']) && !$linha["vaga_reservada"]) {
                        $candidato_bloqueado_por_anterior = true;
                    }
                }
            }

            // Se o candidato logado não está na lista, assume que já escolheu
            $candidato_ja_escolheu = !$candidato_encontrado;
            ?>

            <div class="card">
                <?php if ($candidato_bloqueado_por_anterior && !$candidato_ja_escolheu): ?>
                    <legend>Aguarde sua vez <img src="imagens/urgente.gif" height="25px"></legend>

                    <div class="alert alert-info p-20">
                        <b>Ainda há <?php echo ($candidatos_faltando_a_frente); ?> candidato(s) na sua frente para realizar a escolha.</b>
                    </div>

                <?php elseif (!$candidato_guarnicao_escolhida && !$candidato_ja_escolheu && !$liberarEscolhaGuarnicao): ?>
                    <legend>Escolha agora sua Região Militar <img src="imagens/urgente.gif" height="25px"></legend>

                    <div class="alert alert-info p-20">
                        <b>Abaixo estão listadas as especialidades nas quais você se inscreveu para este Processo Seletivo.</b>
                    </div>

                <?php elseif (!$candidato_guarnicao_escolhida && !$candidato_ja_escolheu && $liberarEscolhaGuarnicao): ?>
                    <legend>Escolha agora sua Guarnição <img src="imagens/urgente.gif" height="25px"></legend>

                    <div class="alert alert-info p-20">
                        <b>Abaixo estão listadas as especialidades nas quais você se inscreveu para este Processo Seletivo.</b>
                    </div>

                <?php else: ?>
                    <legend>Escolha de Guarnição Realizada</legend>

                    <div class="alert alert-info p-20">
                        <b>Aguarde as próximas publicações no site da Região Militar escolhida para as etapas Presenciais</b>
                    </div>
                <?php endif; ?>
            </div>


            <div class="card">

                <legend>Minhas inscrições no processo seletivo (Em ordem de Preferência): <?php echo ($rms_interesse_formatado); ?></legend>

                <div class="row">
                    <?php if (

                        !$candidato_bloqueado_por_anterior &&
                        $candidato_encontrado
                    ): ?>
                        <div class="col-lg-12">
                            <?php foreach ($lista_inscricoes as $value): ?>
                                <?php if (
                                    $value['concorrendo'] == 1 &&
                                    $value['rm_escolheu_servir'] == null &&
                                    !$candidato_bloqueado_por_anterior &&
                                    $candidato_encontrado
                                ): ?>
                                    <?php

                                    $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);
                                    $ott_stt = $value['ott_stt'] == 'eipot' ? 'EIPOT' : null;

                                    $cor_retangulo = "success";
                                    if (!empty($value['cidade_escolheu_servir']) || $value['concorrendo'] == 0) $cor_retangulo = "info";
                                    ?>

                                    <?php $crip = hash('sha256', $value['id_especialidade'] . "escolhe_cidade"); ?>
                                    <div class="alert alert-<?= $cor_retangulo ?> p-20">

                                        <legend>
                                            <b>
                                                <font color="green">Primeira Escolha: Região Militar</font>
                                            </b>
                                        </legend>
                                        <form action="../banco_dados/candidato_rm_escolheu_servir_eipot.php" method="post">
                                            <br>
                                            <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                                                <option value="">Selecione a Região Militar em que deseja servir</option>
                                                <?php foreach ($rms_interesse as $index => $rm): ?>
                                                    <option value="<?= $rm ?>"><?= $index + 1 ?>ª Opção - <?= $rm ?>ª Região Militar</option>
                                                <?php endforeach; ?>
                                                <option value="754809">Nenhuma das Opções (Desistência do Processo Seletivo)</option>
                                            </select>
                                            <br>

                                            <label>
                                                <input type="checkbox" id="declaracao" name="declaracao">
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

                                            <button type="submit" class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                                            <br>
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

                                $cor_retangulo = "success";
                                if (!empty($value['cidade_escolheu_servir']) || $value['concorrendo'] == 0) $cor_retangulo = "info";
                                ?>

                                <div class="alert alert-<?= $cor_retangulo ?> p-20">
                                    <legend>
                                        <b>
                                            <font color="green">Segunda Escolha: Guarnição</font>
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
                                        <p class="mb-20px bold">VAGAS:</p>
                                    </b>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Total disponibilizadas</th>
                                                <th>Restantes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lista_cidades_epecialidades as $linha): ?>
                                                <?php
                                                $rm = (int) $linha['regiao_militar'];
                                                if (!in_array($rm, $rms_interesse)) {
                                                    continue;
                                                }

                                                $nome = $linha['nome'];
                                                $total = (int) $linha['numero_vagas'] > 0 ? $linha['numero_vagas'] : 0;

                                                // Procurar correspondente no array $get_vagas_especialidade
                                                $restantes = '-';
                                                foreach ($get_vagas_especialidade as $vaga) {
                                                    if (
                                                        (int) $vaga['id_cidade'] === (int) $linha['id'] &&
                                                        (int) $vaga['regiao_militar'] === $rm
                                                    ) {
                                                        $restantes = (int) $vaga['vagas'] > 0 ? $vaga['vagas'] : 0;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><b>Guarnição: </b><?= $nome . '/' . $linha['uf'] .  ' - ' . $rm . 'ªRM' ?> <b>- Vagas:</b> <?= $total ?></td>
                                                    <td><b>Guarnição: </b><?= $nome . '/' . $linha['uf'] .  ' - ' . $rm . 'ªRM' ?> <b>- Vagas:</b> <?= $restantes ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <?php if ($value['cidade_escolheu_servir'] != null): ?>
                                        <?php
                                        $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
                                        $nome_cidade = $get_cidade_escolhida[0]['nome'] ?? null;
                                        ?>
                                        <?php if ($nome_cidade): ?>
                                            <br>
                                            <font size="3" color="black"><b>Guarnição escolhida para servir:</b> <?= $nome_cidade ?></font>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php
                                    $tem_vaga_ = false;
                                    foreach ($get_vagas_especialidade as $vaga) {
                                        if ((int)$vaga['vagas'] > 0) $tem_vaga_ = true;
                                    }

                                    $pode_selecionar = seleciona_cidade_vai_servir();
                                    ?>

                                    <?php if (
                                        $value['concorrendo'] == 1 &&
                                        $value['cidade_escolheu_servir'] == null &&
                                        $tem_vaga_ &&
                                        $pode_selecionar &&
                                        !$candidato_bloqueado_por_anterior &&
                                        $value['concorrendo'] == 1
                                    ): ?>
                                        <?php $crip = hash('sha256', $value['id_especialidade'] . "escolhe_cidade"); ?>

                                        <form action="../banco_dados/candidato_cidade_escolheu_servir_eipot.php" method="post">
                                            <br>
                                            <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                                                <option value="">Selecione a Cidade em que deseja servir</option>
                                                <?php foreach ($get_vagas_especialidade as $vaga): ?>
                                                    <?php
                                                    $rm = (int)$vaga['regiao_militar'];
                                                    $id_cidade = (int)$vaga['id_cidade'];

                                                    if ((int)$vaga['vagas'] > 0 && in_array($rm, $rms_interesse)) {
                                                        // Contar quantas vagas já foram preenchidas nessa RM
                                                        $preenchidas_na_rm = 0;
                                                        foreach ($vagas_preenchidas as $vp) {
                                                            if ((int)$vp['regiao_militar'] === $rm) {
                                                                $preenchidas_na_rm += (int)$vp['preenchidas'];
                                                            }
                                                        }

                                                        $proxima_posicao = $preenchidas_na_rm + 1;
                                                        $eh_vaga_cotista = ($proxima_posicao % 5 === 0); // ou sua regra atualizada

                                                        // Verifica se o candidato atual é cotista
                                                        $candidato_e_cotista = $value['vaga_reservada'] == 1;

                                                        if (!$eh_vaga_cotista || ($eh_vaga_cotista && $candidato_e_cotista)) {
                                                    ?>
                                                            <option value="<?= $id_cidade ?>"><?= $vaga['cidade'] ?> (<?= $vaga['vagas'] ?>)</option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?= $vaga['id_cidade'] ?>"><?= $vaga['cidade'] ?> (<?= $vaga['vagas'] ?>)</option>
                                                <?php endforeach; ?>
                                                <option value="754809">Nenhuma das Opções (Desistência das localidades ofertadas na 1ª RM)</option>
                                            </select>
                                            <br>

                                            <label>
                                                <input type="checkbox" id="declaracao" name="declaracao">
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

                                            <button type="submit" class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                                            <br>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <br>
                            <?php endforeach; ?>

                        </div>
                    <?php endif; ?>
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