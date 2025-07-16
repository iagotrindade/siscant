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

$rms_interesse_formatado = implode(', ', array_map(fn($n) => $n . 'ª', explode(',', $rm_destino)));

$lista_inscricoes = $conexao->get_especialidade_candidato_eipot($_SESSION['id_usuario']);
//var_dump($lista_inscricoes); exit; //159621

foreach ($lista_inscricoes as $inscricao) {
    $id_especialidade = (int)$inscricao['id_especialidade'];

    $get_vagas_especialidade = $conexao->get_vagas_especialidade($inscricao['id_especialidade']);
    $lista_cidades_epecialidades = $conexao->get_cidades_especialidade($inscricao['id_especialidade']);
    $vagas_preenchidas = $conexao->get_vagas_preenchidas($inscricao['id_especialidade']);

    include_once './codigos/ordena_candidatos_escolha_cidade.php';
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

            // Agrupa o total de escolhas feitas por RM
            $escolhas_por_regiao = [];

            foreach ($vagas_preenchidas as $item) {
                $regiao = $item['regiao_militar'];
                $quantidade = (int) $item['preenchidas'];

                if (!isset($escolhas_por_regiao[$regiao])) {
                    $escolhas_por_regiao[$regiao] = 0;
                }

                $escolhas_por_regiao[$regiao] += $quantidade;
            }

            // Percorre candidatos ordenados
            foreach ($vetor_ordenado_candidatos as $linha) {
                $regiao = $linha['rm_inscricao'];

                // Se for o candidato logado
                if ((int)$linha['id'] === (int)$_SESSION['id_usuario']) {
                    $candidato_guarnicao_escolhida = $linha['cidade_escolheu_servir'];
                    $candidato_encontrado = true;

                    // Posição da próxima escolha naquela RM
                    $posicao_vaga = ($escolhas_por_regiao[$regiao] ?? 0) + 1;
                    $eh_vaga_cotista = ($posicao_vaga % 5 === 0);

                    if ($eh_vaga_cotista && !$linha["vaga_reservada"]) {
                        // Vaga reservada para cotistas, e o candidato é ampla → bloqueado
                        $candidato_bloqueado_por_anterior = true;
                    } else {
                        // Pode escolher
                        $candidato_bloqueado_por_anterior = false;
                    }

                    break;
                }

                // Candidato anterior ainda não escolheu e é de ampla → bloqueia os próximos
                if (empty($linha['cidade_escolheu_servir']) && !$linha["vaga_reservada"]) {
                    $candidatos_faltando_a_frente++;
                    $candidato_bloqueado_por_anterior = true;
                }
            }

            // Se o candidato logado não está na lista, assume que já escolheu
            $candidato_ja_escolheu = !$candidato_encontrado;
            ?>
            <div class="card">
                <?php if ($candidato_bloqueado_por_anterior && !$candidato_ja_escolheu): ?>
                    <legend>Aguarde sua vez <img src="imagens/urgente.gif" height="25px"></legend>

                    <div class="alert alert-info p-20">
                        <b>Ainda há <?php echo ($candidatos_faltando_a_frente); ?> candidato(s) na sua frente para realizar a escolha de guarnição.</b>
                    </div>

                <?php elseif (!$candidato_guarnicao_escolhida && !$candidato_ja_escolheu): ?>
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

                <legend>Minhas inscrições no processo seletivo: <?php echo ($rms_interesse_formatado); ?></legend>

                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach ($lista_inscricoes as $value): ?>
                            <?php
                            $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);
                            $ott_stt = $value['ott_stt'] == 'eipot' ? 'EIPOT' : null;

                            $cor_retangulo = "success";
                            if (!empty($value['cidade_escolheu_servir']) || $value['concorrendo'] == 0) $cor_retangulo = "info";
                            ?>

                            <div class="alert alert-<?= $cor_retangulo ?> p-20">
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
                                                <td><b>Guarnição: </b><?= $nome ?> <b>- Vagas:</b> <?= $total ?></td>
                                                <td><b>Guarnição: </b><?= $nome ?> <b>- Vagas:</b> <?= $restantes ?></td>
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
                                                if ((int)$vaga['vagas'] > 0 && in_array($rm, $rms_interesse)):
                                                ?>
                                                    <option value="<?= $vaga['id_cidade'] ?>"><?= $vaga['cidade'] ?> (<?= $vaga['vagas'] ?>)</option>
                                                <?php endif; ?>
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