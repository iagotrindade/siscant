<?php
include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if (!$_POST) {
    erro_mensagem("Erro 54437457457!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro("Erro 236234646!");
    exit();
}

if (($_SESSION['perfil'] != 'candidato')) {
    erro("Erro 243624747457! Permissão Negada!");
    exit();
}


// Verifica se foi liberado para o candidato escolher a cidade
$selecao = $conexao->get_selecao_id();

if (count($selecao) == 0) {
    erro_mensagem("Erro 86484658!");
    exit();
}

if ($selecao[0]['data_fim_cidade'] == null) {
    erro("Erro 347858548! Não está permitido fazer a escolha ainda!");
    exit();
}

if (strtotime(date("Y-m-d")) > strtotime($selecao[0]['data_fim_cidade'])) {
    erro("Erro 23463474357! O período de escolha já passou!");
    exit();
}

if (strtotime(date("Y-m-d")) < strtotime($selecao[0]['data_inicio_cidade'])) {
    erro("Erro 57357567! Ainda não está permitido para fazer a escolha!");
    exit();
}

if (!isset($_POST['declaracao'])) {
    erro("Erro 23573458387! Você deve declarar que leu o aviso de ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO!");
    exit();
}

$id_especialidade = (int)$_POST['id_especialidade'];
$cidade_escolheu_servir = (int)$_POST['cidade_escolheu_servir'];
$crip = htmlspecialchars($_POST['crip']);

if ($crip == "" || $crip == null || $id_especialidade == "" || $id_especialidade == null || $id_especialidade == 0) {
    erro("Erro 48948456444444!");
    exit();
}

if ($crip != hash('sha256', $id_especialidade . "escolhe_cidade")) {
    erro("Erro 2437358745865!");
    exit();
}

if ($cidade_escolheu_servir == '' || $cidade_escolheu_servir == null) {
    erro("Você deve selecionar a cidade em que deseja servir!");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$get_candidato = $conexao->get_usuario_id($id_usuario);

if (count($get_candidato) != 1) {
    erro("Erro 236536346!");
    exit();
}

if ($get_candidato[0]['concorrendo'] == '0') {
    erro("Erro 23476437457!");
    exit();
}

if ($selecao[0]['codigo'] == 'ott_stt' && $get_candidato[0]['etapa'] < 6) {
    erro("Erro 984946515! Você deve estar pelo menos na Etapa 4 para escolher a cidade!");
    exit();
}
if ($selecao[0]['codigo'] == 'mfdv' && $get_candidato[0]['etapa'] < 4) {
    erro("Erro 32738568! Você deve estar pelo menos na Etapa 4 para escolher a cidade!");
    exit();
}

////////////// VERIFICA SE A CIDADE TEM VAGA
$get_vagas_especialidade = $conexao->get_vagas_especialidade($id_especialidade);
foreach ($get_vagas_especialidade as $vaga) {
    if ($vaga['id_cidade'] == $cidade_escolheu_servir && $vaga['vagas'] == 0) {
        erro("Erro 85673476547! Faça o cadastro da cidade novamente!");
        exit();
    }
}
/////////////////////////////////////////////

$nome_especialidade = null;
$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
$id_candidato_x_especialidade = null;

foreach ($get_especialidade_candidato as $especialidade) {
    if ($id_especialidade == $especialidade['id_especialidade']) {
        $id_candidato_x_especialidade = $especialidade['id_candidato_x_especialidade'];
        $nome_especialidade = $especialidade['especialidade'];
        if ($especialidade['concorrendo'] == '0') {
            erro("Erro 237647457! Candidato desclassificado da especialidade");
            exit();
        }
        if ($especialidade['cidade_escolheu_servir'] != null || $especialidade['cidade_escolheu_servir'] != '') {
            erro("Erro 2473478458! A Cidade só pode ser escolhida uma vez!");
            exit();
        }
    }
}



// VERIFICA SE O CANDIDATO É O PROXÍMO A ESCOLHER A CIDADE
$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);

$vetor_ordenado_candidatos = array();

foreach ($lista_candidatos as $linha) {
    if ($linha['medico_obrigatorio'] == '1') continue;

    $pontuacao_curriculo = 0;
    $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade);
    
    if (count($get_pontuacao_avaliada) > 0)
        $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);


    ///////////////////////////////
    // Prova Teórico Prática
    ///////////////////////////////
    $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'], $id_especialidade);
    $nota_prova_teorico_pratico = 0;


    // ALTERAR PARA CONSIDERAR O CAMPO NOVO QUE DEFINE SE A NOTA DEVE SER USADA NA PONTUAÇÃO OU NÃO "nota_av"
    if (count($get_pontuacao_provas) > 0 && $get_pontuacao_provas['nota_av'] == '1') {
        $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
        $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
    }


    $especialidade_selecionada = $conexao->get_especialidade_id($id_especialidade);

    // SE A ESPECIALIDADE FOR DE MÚSICA
    if ($especialidade_selecionada[0]['musica'] == '1') {
        $prova_pratica_musica = 0;
        $prova_escrita_musica = 0;
        $prova_oral_musica = 0;

        if (count($get_pontuacao_provas) > 0) {
            $prova_pratica_musica = $get_pontuacao_provas[0]['prova_pratica_musica'];
            $prova_escrita_musica = $get_pontuacao_provas[0]['prova_teorica_musica'];
            $prova_oral_musica = $get_pontuacao_provas[0]['prova_oral_musica'];
        }

        $somatorio_total_pontos_musica = 0;
        $somatorio_total_pontos_musica = (((($prova_escrita_musica * 2) + ($prova_pratica_musica * 2) + $prova_oral_musica) / 5) + $pontuacao_curriculo) / 2;
        $pontuacao_curriculo = round($somatorio_total_pontos_musica, 2);
    }

    $militar = 7;

    // Oficiais da Ativa
    if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten" || $linha['posto_grad'] == "asp"))
        $militar = 1;

    // Oficial R2
    if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten"))
        $militar = 2;

    // Aspirante R2
    if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "asp"))
        $militar = 3;

    // Praça Ativa
    if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
        $militar = 4;

    // Reservista de 1ª categoria
    if ($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil'))
        $militar = 5;

    // Reservista de 2ª categoria
    if ($linha['certificado'] == '2crm')
        $militar = 6;

    $tempo_total_sv_publico_dias = 0;
    $anos_sv_publico = (int)$linha['tempo_sv_mil_anos'];
    $meses_sv_publico = (int)$linha['tempo_sv_mil_meses'];
    $dias_sv_publico = (int)$linha['tempo_sv_mil_dias'];

    $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + ($dias_sv_publico);

    $tempo_total_idade_dias = 0;
    $data_atual = new DateTime(date("Y-m-d"));
    $data_nasc = new DateTime($linha['data_nascimento']);
    $intervalo = $data_atual->diff($data_nasc);

    $anos_vida  = (int)$intervalo->format('%Y');
    $meses_vida = (int)$intervalo->format('%m');
    $dias_vida  = (int)$intervalo->format('%d');

    $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + ($dias_vida);

    $novo_vetor = array();

    // Caso o candidato seja cotista, verifica se foi aprovado na heteroidentificação se não converte em ampla
    if ($linha['vaga_reservada'] == 1) {
        $pareceres = $conexao->get_pareceres_heteroidentificacao($linha['id']);

        $fase1_confirmada = 0;
        $fase1_total = 0;
        $fase2_confirmada = 0;
        $fase2_nao_confirmada = 0;
        $fase2_nao_compareceu = 0;
        $fase2_total = 0;

        foreach ($pareceres as $parecer) {
            if ((int)$parecer['fase'] === 1) {
                $fase1_total++;
                if ($parecer['parecer'] === 'confirmada') {
                    $fase1_confirmada++;
                }
            } elseif ((int)$parecer['fase'] === 2) {
                if ($parecer['parecer'] === 'confirmada') {
                    $fase2_confirmada++;
                } elseif ($parecer['parecer'] === 'nao_confirmada') {
                    $fase2_nao_confirmada++;
                } elseif ($parecer['parecer'] === 'nao_compareceu') {
                    $fase2_nao_compareceu++;
                }
                $fase2_total++;
            }
        }

        if ($fase1_total === 5 && $fase1_confirmada >= 3) {
            // Aprovado na primeira Heteroidentificação
            $linha['vaga_reservada'] = 1; // Vaga reservada confirmada
        } elseif ($fase2_total === 3) {
            // Aprovado na primeira Heteroidentificação Revisora
            if ($fase2_confirmada >= 2) {
                $linha['vaga_reservada'] = 1; // Vaga reservada confirmada

            // Resprovado/Não compareceu na primeira Heteroidentificação
            } elseif ($fase2_nao_confirmada >= 2 || $fase2_nao_compareceu >= 2) {
                $linha['vaga_reservada'] = 0; // Vaga reservada não confirmada
            }
        }
        //Se o candidato não tiver revisora ou não comparecer, ele não pode ser considerado para a vaga reservada
        else {
            $linha['vaga_reservada'] = 0; // Define como Ampla se não atendeu nenhuma das fases
        }
    }

    $novo_vetor =
        [
            "id" => $linha['id'],
            "nome" => mb_strtoupper($linha['nome_completo'], "UTF-8"),
            "vaga_reservada" => $linha['vaga_reservada'],
            "cpf" => $linha['cpf'],
            "pontos" => $pontuacao_curriculo,
            "militar" => $militar,
            "tempo_sv_pub" => $tempo_total_sv_publico_dias,
            "tempo_idade" => $tempo_total_idade_dias,
            "mail" => $linha['mail'],
            "etapa" => $linha['etapa'],
            "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
        ];

    array_push($vetor_ordenado_candidatos, $novo_vetor);
}

if (count($lista_candidatos) > 0) {

    foreach ($vetor_ordenado_candidatos as $index => $linha2) {
        $pontos_array[$index]  = $linha2['pontos'];
        $militar_array[$index] = $linha2['militar'];
        $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
        $tempo_idade[$index]   = $linha2['tempo_idade'];
    }

    if (count($vetor_ordenado_candidatos) > 0) {

        array_multisort(
            $pontos_array,
            SORT_DESC,
            $militar_array,
            SORT_ASC,
            $tempo_sv_pub,
            SORT_ASC,
            $tempo_idade,
            SORT_DESC,
            $vetor_ordenado_candidatos
        );
    }
}

$lugar = 0;
$candidato_na_frente_nao_escolheu = false;
foreach ($vetor_ordenado_candidatos as $linha) {
    $lugar++;

    if ($linha['cidade_escolheu_servir'] == null && $linha['id'] != $_SESSION['id_usuario'])
        $candidato_na_frente_nao_escolheu = true;

    if ($linha['id'] == $_SESSION['id_usuario']) {

        if ($candidato_na_frente_nao_escolheu) {
            $conexao = null;
            erro("Erro 4575384323523! Existe candidato(s) na sua frente que devem escolher a cidade antes do Sr(a)!");
            exit();
        }

        // SE ESCOLHEU DESISTÊNCIA É DESCLASSIFICADO
        if ($cidade_escolheu_servir == 754809) {

            if ($id_candidato_x_especialidade == null) {
                erro("Erro 2473568469659! Não foi possível registrar a sua opção");
                exit();
            }

            //DESCLASSIFICA ELE DA ESPECIALIDADE
            $justificativa = 'Cod 754809 - NÃO OPTOU pelas guarnições oferecidas. Caso não sejam oferecidas novas vagas no futuro, você não será incorporado(a) como militar temporário.';
            $resultado_concorrendo = $conexao->status_concorrendo_especialidade($id_candidato_x_especialidade, 0, $justificativa);
            $alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
            if ($resultado_concorrendo)
                $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_candidato_x_especialidade", "16150", "candidato_x_especialidade", "Update", "Candidato escolheu NENHUMA DAS OPÇÕES ao selecionar a cidade de destino da especialidade $nome_especialidade", "$alteracoes_detalhadas");

            // VERIFICA SE ELE ESTÁ CONCORRENDO EM ALGUMA OUTRA ESPECIALIDADE
            // SE NÃO ESTIVER DESCLASSIFICA ELE DO PROCESSO SELETIVO
            $get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);

            $esta_concorrendo_em_outa_especialidade = false;
            foreach ($get_especialidade_candidato as $especialidade) {
                if ($especialidade['concorrendo'] === '1') {
                    $esta_concorrendo_em_outa_especialidade = true;
                    break;
                }
            }

            if ($esta_concorrendo_em_outa_especialidade == false) {
                $observacao = "Não está concorrendo em nenhuma especialidade! $justificativa";
                $resultado_concorrendo = $conexao->status_concorrendo($id_usuario, 0, $observacao);
                $alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
                if ($resultado_concorrendo) {
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "161501", "usuario", "Update", "Foi mudado o status para DESCLASSIFICADO pois não está participando de nenhuma especialidade", "$alteracoes_detalhadas");
                    if ($_SESSION['selecao_regiao'] == '3')
                        include_once '../sistema/codigos/candidato_escolhe_cidade_mail.php';
                } else {
                    $conexao = null;
                    erro("Erro 423345634 Não mudou o status!");
                    exit();
                }
            }

            header("Location: ../sistema/candidato_escolha_cidade.php");
            exit();
        }

        $get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);

        // GRAVA CIDADE PARA O CANDIDATO    
        $get_cidade_id = $conexao->get_cidade_id($cidade_escolheu_servir);
        $nome_cidade_escolheu = $get_cidade_id[0]['nome'];

        $cadastra_cidade_vai_servir = $conexao->cadastra_cidade_candidato_vai_servir($id_usuario, $id_especialidade, $cidade_escolheu_servir);
        $alteracoes_detalhadas =  print_r($cadastra_cidade_vai_servir, true);

        if ($cadastra_cidade_vai_servir) {
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16149", "candidato_x_especialidade", "Update", "Candidato(a) escolheu a cidade $nome_cidade_escolheu ID: $cidade_escolheu_servir na especialidade $nome_especialidade ID: $id_especialidade " . $get_candidato[0]['cpf'], "$alteracoes_detalhadas");

            // Verifica se o candidato concorre em outro especialidade na etapa VI e desclassifica por ter escolhido vaga em outra especialidade
            $get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);

            foreach ($get_especialidade_candidato as $especialidade) {
                if ($especialidade['concorrendo'] === '1' && $especialidade['etapa'] == 6) {
                    if ($especialidade['id'] != $id_especialidade) {
                        $justificativa = 'Cod 754809 - Candidato(a) optou por escolher Guarnição em outra Especialidade.';
                        $resultado_concorrendo = $conexao->status_concorrendo_especialidade($especialidade['id'], 0, $justificativa);
                        $alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
                        if ($resultado_concorrendo)
                            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$especialidade[id]", "16150", "candidato_x_especialidade", "Update", "Candidato(a) optou por escolher Guarnição em outra Especialidade.", "$alteracoes_detalhadas");
                        else {
                            $conexao = null;
                            erro("Erro 263475475! Não foi possível salvar a escolha!");
                            exit();
                        }
                    }
                }
            }

            /*VERIFICAR NECESSIDADE*/
            if ($_SESSION['selecao_regiao'] == '3')
                include_once '../sistema/codigos/candidato_escolhe_cidade_mail.php';
        } else {
            $conexao = null;
            erro("Erro 263475475! Não foi possível salvar a escolha!");
            exit();
        }


        if ($_SESSION['eipot']) {
            header("Location: ../sistema/candidato_eipot_escolha_cidade.php");
            exit();
        } else {
            header("Location: ../sistema/candidato_escolha_cidade.php");
            exit();
        }

        break;
    }
}

erro("Erro 3462437345745! Não foi possível gravar a opção escolhida!");
exit();
