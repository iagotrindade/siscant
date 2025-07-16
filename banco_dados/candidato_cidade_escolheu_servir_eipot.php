<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

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

if ($selecao[0]['codigo'] == 'ott_stt' && $get_candidato[0]['etapa'] < 4) {
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
include_once "../sistema/codigos/ordena_candidatos_escolha_cidade.php";

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

            $justificativa = 'Cod 754809 - O Sr(a) NÃO OPTOU pelas guarnições oferecidas. Caso não sejam oferecidas novas vagas no futuro, o Sr(a) não será incorporado(a) como militar temporário.';
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

            if ($_SESSION['eipot']) {
                header("Location: ../sistema/candidato_eipot_escolha_cidade.php");
                exit();
            } else {
                header("Location: ../sistema/candidato_escolha_cidade.php");
                exit();
            }
        }

        // GRAVA CIDADE PARA O CANDIDATO    
        $get_cidade_id = $conexao->get_cidade_id($cidade_escolheu_servir);
        $nome_cidade_escolheu = $get_cidade_id[0]['nome'];

        $cadastra_cidade_vai_servir = $conexao->cadastra_cidade_candidato_vai_servir($id_usuario, $id_especialidade, $cidade_escolheu_servir);
        $alteracoes_detalhadas =  print_r($cadastra_cidade_vai_servir, true);

        if ($cadastra_cidade_vai_servir) {
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16149", "candidato_x_especialidade", "Update", "Candidato escolheu a cidade $nome_cidade_escolheu ID: $cidade_escolheu_servir na especialidade $nome_especialidade ID: $id_especialidade " . $get_candidato[0]['cpf'], "$alteracoes_detalhadas");
            if ($_SESSION['selecao_regiao'] == '3')
                include_once '../sistema/codigos/candidato_escolhe_cidade_mail.php';
        } else {
            $conexao = null;
            erro("Erro 263475475! Não foi possível salvar a escolha!");
            exit();
        }

        header("Location: ../sistema/candidato_eipot_escolha_cidade.php");
        exit();

        break;
    }
}

erro("Erro 3462437345745! Não foi possível gravar a opção escolhida!");
exit();
