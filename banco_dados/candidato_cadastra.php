<?php

session_start();

include_once '../sistema/funcoes.php';

if (!$_POST) {
    erro_mensagem("Erro 5623444!");
    exit();
}


$nome_completo = null;
$cpf = null;
$identidade = null;
$nome_social = null;
$dependentes = null;
$autodeclaracao = null;
$filiacao_pai = null;
$estado_civil = null;
$companheiro = null;
$sexo = null;
$nascionalidade = null;
$naturalidade = null;
$filiacao_mae = null;
$data_nascimento = null;
$uf = null;
$bairro = null;
$cidade = null;
$cep = null;
$rua = null;
$telefone = null;
$celular = null;
$mail = null;
$mail2 = null;
$tempo_sv_pub = null;
$tempo_sv_pub_anos = null;
$tempo_sv_pub_meses = null;
$tempo_sv_pub_dias = null;
$tempo_sv_mil = null;
$tempo_sv_mil_anos = null;
$tempo_sv_mil_meses = null;
$tempo_sv_mil_dias = null;
$civil_militar = null;
$certificado = null;
$ativa_reserva = null;
$posto_grad = null;
$forca = null;
$arma = null;
$documento = null;
$data_expedicao = null;
$incorporacao = null;
$licenciamento = null;
$cidade_etapas_presenciais = null;
$cidade_exame_musica_12rm = null;

//EIPOT
$curso_graduacao = null;
$ano_formacao_ofor = null;
$nota_ofor = null;
$arma_eipot = null;

if (!isset($_POST['declaracao'])) {
    erro_mensagem("Erro 454236! Declaração de veracidade não preenchida!");
    exit();
}


if (
    trim($_POST['nome_completo']) == null ||
    trim($_POST['cpf']) == null ||
    trim($_POST['identidade']) == null ||
    trim($_POST['filiacao_pai']) == null ||
    trim($_POST['sexo']) == null ||
    trim($_POST['estado_civil']) == null ||
    trim($_POST['data_nascimento']) == null ||
    trim($_POST['nascionalidade']) == null ||
    trim($_POST['naturalidade']) == null ||
    trim($_POST['filiacao_mae']) == null ||
    trim($_POST['uf']) == null ||
    trim($_POST['bairro']) == null ||
    trim($_POST['cidade']) == null ||
    trim($_POST['cep']) == null ||
    trim($_POST['rua']) == null ||
    trim($_POST['telefone']) == null ||
    trim($_POST['celular']) == null ||
    trim($_POST['mail']) == null ||
    trim($_POST['mail2']) == null ||
    //trim($_POST['tempo_sv_pub']) == null ||
    trim($_POST['civil_militar']) == null
) {
    erro_mensagem("Erro 995346! Todos os campos são obrigatorios!");
    exit();
}

if (trim($_POST['nome_social']) == null && isset($_POST['check_nome_social'])) {
    erro_mensagem("Você selecionou que possui nome social! Logo, deve preencher este campo");
    exit();
}
/*
    if($_POST['tempo_sv_pub'] == '1')
    {
        if($_POST['tempo_sv_pub_anos'] == null ||
        $_POST['tempo_sv_pub_meses'] == null ||
        $_POST['tempo_sv_pub_dias'] == null)
        {
            erro_mensagem("Todos os campos do tempo de se serviço público são obrigatorios!");
            exit();
        }
    }
     */

if (isset($_POST['tempo_sv_mil']) && $_POST['tempo_sv_mil'] == null && !isset($_SESSION['eipot'])) {
    erro_mensagem("Erro 2436246456! O campo de tempo de se serviço militar é obrigatorio!");
    exit();
}

if ($_POST['tempo_sv_mil'] == '1') {
    if (
        $_POST['tempo_sv_mil_anos'] == null ||
        $_POST['tempo_sv_mil_meses'] == null ||
        $_POST['tempo_sv_mil_dias'] == null
    ) {
        erro_mensagem("Erro 67867523! Todos os campos do tempo de se serviço militar são obrigatorios!");
        exit();
    }
}

if ($_POST['civil_militar'] == 'civil' && ($_POST['ja_foi_militar'] != 'sim' && $_POST['ja_foi_militar'] != 'nao' && $_POST['ja_foi_militar'] != 'sim_eas')) {
    erro_mensagem("Erro 26778523! Se você é civil, deve responder ser já foi militar!");
    exit();
}

$ativa_reserva = null;

if ($_POST['ja_foi_militar'] == 'sim')
    $ativa_reserva = "ja_foi_militar";

if ($_POST['ja_foi_militar'] == 'sim_eas')
    $ativa_reserva = "ja_foi_militar_EAS";

if ($_POST['ja_foi_militar'] == 'nao')
    $ativa_reserva = "nunca_foi_militar";

if ($_POST['civil_militar'] == 'militar')
    $ativa_reserva = 'militar_ativa';


////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
//// VALIDAÇÕES CIVIL / MILITAR
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

if ($_POST['civil_militar'] == 'civil') {

    if ($_POST['ja_foi_militar'] == 'sim' || $_POST['ja_foi_militar'] == 'sim_eas') {
        if (
            trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null || trim($_POST['posto_grad']) == null ||
            trim($_POST['forca']) == null || trim($_POST['arma']) == null || trim($_POST['incorporacao']) == null ||
            trim($_POST['licenciamento']) == null
        ) {
            erro_mensagem("Erro 867833! Os campos de EX militar são obrigatórios!");
            exit();
        }

        if ($_POST['sexo'] == 'masculino' && $_POST['certificado'] == null) {
            erro_mensagem("Erro 2334534523! O campo certificado é obrigatório para o sexo masculino!");
            exit();
        }
    }

    if ($_POST['ja_foi_militar'] == 'nao' && $_POST['sexo'] == 'masculino') {
        if ($_POST['certificado'] == null || trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null) {
            erro_mensagem("Erro 234564523! Os campos, Documento Militar,  Nº do documento e data de expedição são obrigatórios!");
            exit();
        }
    }
}
if ($_POST['civil_militar'] == 'militar') {
    if (
        trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null || trim($_POST['posto_grad']) == null ||
        trim($_POST['forca']) == null || trim($_POST['arma']) == null || trim($_POST['incorporacao']) == null
    ) {
        erro_mensagem("Erro 234523! Todos os campos do militar temporário são obrigatórios!");
        exit();
    }
}

if ($_POST['nome_completo'] != "")
    $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
if ($_POST['cpf'] != "")
    $cpf = trim($_POST['cpf']);
if ($_POST['identidade'] != "")
    $identidade = htmlspecialchars(trim($_POST['identidade']));
if ($_POST['data_nascimento'] != "")
    $data_nascimento = htmlspecialchars(reverte_data(trim($_POST['data_nascimento'])));
if ($_POST['nome_social'] != "")
    $nome_social = htmlspecialchars(trim($_POST['nome_social']));
if ($_POST['estado_civil'] != "")
    $estado_civil = htmlspecialchars(trim($_POST['estado_civil']));
if ($_POST['filiacao_pai'] != "")
    $filiacao_pai = htmlspecialchars(trim($_POST['filiacao_pai']));
if ($_POST['sexo'] != "")
    $sexo = htmlspecialchars(trim($_POST['sexo']));
if ($_POST['nascionalidade'] != "")
    $nascionalidade = htmlspecialchars(trim($_POST['nascionalidade']));
if ($_POST['naturalidade'] != "")
    $naturalidade = htmlspecialchars(trim($_POST['naturalidade']));
if ($_POST['filiacao_mae'] != "")
    $filiacao_mae = htmlspecialchars(trim($_POST['filiacao_mae']));

if ($_POST['uf'] != "")
    $uf = htmlspecialchars(trim($_POST['uf']));
if ($_POST['bairro'] != "")
    $bairro = htmlspecialchars(trim($_POST['bairro']));
if ($_POST['cidade'] != "")
    $cidade = htmlspecialchars(trim($_POST['cidade']));
if ($_POST['cep'] != "")
    $cep = htmlspecialchars(trim($_POST['cep']));
if ($_POST['rua'] != "")
    $rua = htmlspecialchars(trim($_POST['rua']));
if ($_POST['telefone'] != "")
    $telefone = htmlspecialchars(trim($_POST['telefone']));
if ($_POST['celular'] != "")
    $celular = htmlspecialchars(trim($_POST['celular']));
if ($_POST['mail'] != "")
    $mail = htmlspecialchars(trim($_POST['mail']));
/*
    if($_POST['tempo_sv_pub'] != "")
        $tempo_sv_pub = htmlspecialchars($_POST['tempo_sv_pub']);
    if($_POST['tempo_sv_pub_anos'] != "")
        $tempo_sv_pub_anos = htmlspecialchars($_POST['tempo_sv_pub_anos']);
    if($_POST['tempo_sv_pub_meses'] != "")
        $tempo_sv_pub_meses = htmlspecialchars($_POST['tempo_sv_pub_meses']);
    if($_POST['tempo_sv_pub_dias'] != "")
        $tempo_sv_pub_dias = htmlspecialchars($_POST['tempo_sv_pub_dias']);
*/
if ($_POST['tempo_sv_mil'] != "")
    $tempo_sv_mil = htmlspecialchars($_POST['tempo_sv_mil']);
if ($_POST['tempo_sv_mil_anos'] != "")
    $tempo_sv_mil_anos = htmlspecialchars($_POST['tempo_sv_mil_anos']);
if ($_POST['tempo_sv_mil_meses'] != "")
    $tempo_sv_mil_meses = htmlspecialchars($_POST['tempo_sv_mil_meses']);
if ($_POST['tempo_sv_mil_dias'] != "")
    $tempo_sv_mil_dias = htmlspecialchars($_POST['tempo_sv_mil_dias']);

if ($_POST['civil_militar'] != "")
    $civil_militar = htmlspecialchars($_POST['civil_militar']);
if ($_POST['certificado'] != "")
    $certificado = htmlspecialchars(trim($_POST['certificado']));
if ($_POST['documento'] != "")
    $documento = htmlspecialchars(trim($_POST['documento']));
if ($_POST['data_expedicao'] != "")
    $data_expedicao = htmlspecialchars(reverte_data(trim($_POST['data_expedicao'])));

if ($_POST['posto_grad'] != "")
    $posto_grad = htmlspecialchars($_POST['posto_grad']);
if ($_POST['forca'] != "")
    $forca = htmlspecialchars($_POST['forca']);
if ($_POST['arma'] != "")
    $arma = htmlspecialchars(trim($_POST['arma']));
if ($_POST['incorporacao'] != "")
    $incorporacao = htmlspecialchars(trim($_POST['incorporacao']));
if ($_POST['licenciamento'] != "")
    $licenciamento = htmlspecialchars(trim($_POST['licenciamento']));

if ($_POST['autodeclaracao'] != "")
    $autodeclaracao = isset($_POST['autodeclaracao']) ? $_POST['autodeclaracao'] : '';
if ($_POST['vaga_reservada'] != "")
    $vaga_reservada = 1;

if ($_POST['curso_graduacao'] != "")
    $curso_graduacao = htmlspecialchars(trim($_POST['curso_graduacao']));
if ($_POST['ano_formacao_ofor'] != "")
    $ano_formacao_ofor = (int)htmlspecialchars(trim($_POST['ano_formacao_ofor']));
if ($_POST['nota_ofor'] != "")
    $nota_ofor = (float)htmlspecialchars(trim($_POST['nota_ofor']));
if ($_POST['arma_eipot'] != "")
    $arma_eipot = htmlspecialchars(trim($_POST['arma_eipot']));


if ($estado_civil == 'casado' || $estado_civil == 'uniao_estavel') {
    $companheiro = htmlspecialchars(trim($_POST['nome_companheiro']));
    if ($companheiro == '' || $companheiro == null) {
        erro_mensagem("Erro 38459865797! O nome do companheiro(a) é obrigatório!");
        exit();
    }
} else $companheiro = null;


$cpf = $cpf = str_replace('.', '', $cpf);
$cpf = $cpf = str_replace('-', '', $cpf);

if (!valida_cpf($cpf)) {
    erro_mensagem("Erro 58973! CPF Inválido!");
    exit();
}
$cpf_formatado = retorna_campo_formatado($cpf);

if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
    erro_mensagem("Erro 254783! E-Mail Inválido!");
    exit();
}
if (!filter_var($_POST['mail2'], FILTER_VALIDATE_EMAIL)) {
    erro_mensagem("Erro 254235345783! E-Mail Inválido!");
    exit();
}

if ($_POST['mail'] != $_POST['mail2']) {
    erro_mensagem("Erro 4575465! E-Mails diferentes!");
    exit();
}

if (!valida_data($_POST['data_nascimento'])) {
    erro_mensagem("Erro 2589923! Data de nascimento inválida!");
    exit();
}
if (trim($_POST['data_expedicao']) != null && !valida_data($_POST['data_expedicao'])) {
    erro_mensagem("Erro 267865783! Data de expedição inválida!");
    exit();
}

$data_atual = new DateTime(date("Y-12-31"));
$data_nasc = new DateTime(reverte_data($_POST['data_nascimento']));
$intervalo = $data_atual->diff($data_nasc);

/*
    $anos_vida = (int)$intervalo->format('%Y');

    if($anos_vida < 19)
    {
        erro_mensagem("Erro 23890! A idade mínima é de 19 anos!");
        exit();
    }
    */
if ($certificado == 'ci') {
    erro_mensagem("Erro 234658! Quem possui Certificado de Isenção não pode se inscrever!");
    exit();
}

////// Tempo de serviço público
/*
    $anos_servico_publico = 0;
    $meses_servico_publico = 0;
    $dias_servico_publico = 0;
    $anos_servico_publico = (int)$tempo_sv_pub_anos + (int)$tempo_sv_mil_anos;
    $meses_servico_publico = (int)$tempo_sv_pub_meses + (int)$tempo_sv_mil_meses;
    $dias_servico_publico = (int)$tempo_sv_pub_dias + (int)$tempo_sv_mil_dias;
    
    if($dias_servico_publico > 30)
        $meses_servico_publico ++;
    if($meses_servico_publico >= 12)
        $anos_servico_publico ++;
    
    if($anos_servico_publico >= 5)
    {
        erro_mensagem("Erro 45723! Não é permitido a inscrição de quem possuir 5 anos de serviço público ou mais!");
        exit();
    }
    */

//if($tempo_sv_mil_anos >= 6 && $tempo_sv_mil_meses >= 1)
//{
//    erro_mensagem("Erro 45723! Não é permitido a inscrição de quem possuir 6 anos e 1 mês de serviço militar ou mais!");
//    exit();
//}
if ($tempo_sv_mil_anos > 6 && ($tempo_sv_mil_meses > 0 || $tempo_sv_mil_dias > 0)) {
    erro_mensagem("Erro 45723! Não é permitido a inscrição de quem possuir mais de 7 anos de serviço militar!");
    exit();
}


//////////////////////////////////////////////////////////////
///////////
/////////    SOMENTE PARA EIPOT
//////////
//////////////////////////////////////////////////////////////

// Seleção de Capelão
if ($_SESSION['selecao'] == 1013 && $sexo != 'masculino') {
    erro_mensagem("Erro 23587685! Esta seleção é permitida apenas para o sexo masculino!");
    exit();
}

if (isset($_SESSION['eipot']) && $_SESSION['eipot'] === 1) {

    if ($ativa_reserva == "nunca_foi_militar") {
        erro_mensagem("Erro 3474575678! Esta seleção é permitida apenas para quem fez EIPOT (CPOR ou NPOR)!");
        exit();
    }

    if ($sexo != 'masculino') {
        erro_mensagem("Erro 2322221! Esta seleção é permitida apenas para quem fez EIPOT (CPOR ou NPOR)!");
        exit();
    }

    if ($curso_graduacao == null) {
        erro_mensagem("Erro 326326436! O campo Curso de graduação é obrigatório!");
        exit();
    }
    if ($arma_eipot == "outra") {
        erro_mensagem("Erro 3262347457! Nenhuma outra arma além das disponibilizadas para seleção tem vagas disponíveis!");
        exit();
    }
    if ($arma_eipot == "") {
        erro_mensagem("Erro 97658568! A arma de formação é um campo obrigatório!");
        exit();
    }
    if ($ano_formacao_ofor == null) {
        erro_mensagem("Erro 2363426747! O ano de formação do CPOR/NPOR (OFOR) é obrigatório!");
        exit();
    }
    if ($ano_formacao_ofor >= date('Y') || $ano_formacao_ofor < 1980) {
        erro_mensagem("Erro 367437457! O ano de formação do OFOR é inválido!");
        exit();
    }
    if ($nota_ofor > 10 || $nota_ofor < 0) {
        erro_mensagem("Erro 25325346! A nota do OFOR é inválida! Deve ser menor ou igual a 10");
        exit();
    }
}



//////////////////////////////////////////////////////////////
///////////
/////////    SOMENTE PARA MFDV
//////////
//////////////////////////////////////////////////////////////
$num_dependentes  = null;
$prioridade_forca = null;
$voluntario_12rm  = null;

$nome_instituto_ensino = null;
$ano_formacao = null;
$uf_instituto_ensino = null;
$cidade_instituto_ensino = null;



if (isset($_SESSION['mfdv'])) {
    if ($_POST['nome_ie'] != '')
        $nome_instituto_ensino = htmlspecialchars(trim($_POST['nome_ie']));
    if ($nome_instituto_ensino == null || $nome_instituto_ensino == '') {
        erro_mensagem("Erro 4373487358! O nome da Instituição de ensino é obrigatório!");
        exit();
    }
    if ($_POST['ano_formacao'] != '')
        $ano_formacao = (int)htmlspecialchars(trim($_POST['ano_formacao']));
    if ($ano_formacao == null || $ano_formacao == '') {
        erro_mensagem("Erro 4373487358! O ano de formação é obrigatório!");
        exit();
    }
    if ($_POST['uf_ie'] != '')
        $uf_instituto_ensino = htmlspecialchars(trim($_POST['uf_ie']));
    if ($uf_instituto_ensino == null || $uf_instituto_ensino == '') {
        erro_mensagem("Erro 4373487358! A UF da Instituição de Ensino é obrigatória!");
        exit();
    }
    if ($_POST['cidade_ie'] != '')
        $cidade_instituto_ensino = (int)htmlspecialchars(trim($_POST['cidade_ie']));
    if ($cidade_instituto_ensino == null || $cidade_instituto_ensino == '') {
        erro_mensagem("Erro 4373487358! A Cidade da Instituição de Ensino é obrigatória!");
        exit();
    }

    if (!isset($_POST['num_dependentes'])) {
        erro_mensagem("Erro 346364! O número de dependentes é obrigatório!");
        exit();
    }

    /*
        if(!isset($_POST['autodeclaracao']))
        {
            erro_mensagem("Erro 6548976! A autodeclaração é obrigatória!");
            exit();
        } */


    /*
        if(!isset($_SESSION['6_regiao']) && !isset($_SESSION['12_regiao']))
        {
            if(!isset($_POST['prioridade_forca']))
            {
                erro_mensagem("Erro 57868! A prioridade da força é obrigatório!");
                exit();
            }
            
            if(!isset($_POST['voluntario_12rm']))
            {
                erro_mensagem("Erro 34653465! A opção de SIM ou NÃO para voluntário em servir na 12ª Região Militar é obrigatória!");
                exit();
            }
            
            if($_POST['voluntario_12rm'] != "")
                $voluntario_12rm = htmlspecialchars(trim($_POST['voluntario_12rm']));

            if($voluntario_12rm == null)
            {
                erro_mensagem("Erro 476587678! O campo Voluntário para 12 RM é obrigatório!");
                exit();
            }
            
            if($_POST['prioridade_forca'] != "")
            $prioridade_forca = htmlspecialchars(trim($_POST['prioridade_forca']));
            
            if($prioridade_forca == null)
            {
                erro_mensagem("Erro 343465789! O campo Prioridade da força é obrigatório!");
                exit();
            }
        }
        */

    if ($_POST['num_dependentes'] != "")
        $num_dependentes = htmlspecialchars(trim($_POST['num_dependentes']));

    if ($num_dependentes == null) {
        erro_mensagem("Erro 7890789! O campo Número de dependentes é obrigatório!");
        exit();
    }
}

// SOMENTE PARA A 6ª e 12ª REGIÃO 

if (isset($_SESSION['6_regiao'])) {
    if ($_POST['cidade_etapas_presenciais_6'] == "" || $_POST['cidade_etapas_presenciais_6'] == null) {
        erro_mensagem("Erro 4573585468! O campo Cidade das Etapas Presenciais é obrigatório!");
        exit();
    }

    $cidade_etapas_presenciais = htmlspecialchars(trim($_POST['cidade_etapas_presenciais_6']));
}

if (isset($_SESSION['12_regiao'])) {
    if ($_POST['cidade_etapas_presenciais12'] == "" || $_POST['cidade_etapas_presenciais12'] == null) {
        erro_mensagem("Erro 4573585468! O campo Cidade das Etapas Presenciais é obrigatório!");
        exit();
    }

    $cidade_etapas_presenciais = htmlspecialchars(trim($_POST['cidade_etapas_presenciais12']));

    if (isset($_SESSION['12_regiao_musica'])) {
        if ($_POST['cidade_exame_musica_12rm'] == "" || $_POST['cidade_exame_musica_12rm'] == null) {
            erro_mensagem("Erro 7845743575! O campo Cidade do exame de comprovação de habilidade musical é obrigatório!");
            exit();
        }

        $cidade_exame_musica_12rm = htmlspecialchars(trim($_POST['cidade_exame_musica_12rm']));
    }
}

// </editor-fold>

include_once 'conexao.php';
$datetime = date('Y-m-d H:i:s');
$conexao = new Conexao();

if (!isset($_SESSION['selecao'])) {
    erro_mensagem("Erro 153455! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if (!isset($_SESSION['chave'])) {
    erro_mensagem("Erro 167845! Faça novamente o cadastro clicando na página incial o botão QUERO ME CADASTRAR");
    exit();
}


$selecao = $conexao->get_selecao_id();

if (count($selecao) == 0) {
    erro_mensagem("Erro 1563245!");
    exit();
}

if ($selecao[0]['data_fim_inscricao'] == null) {
    erro_mensagem("Erro 2134234! A sua inscrição está fora do período!");
    exit();
}
if (strtotime(date("Y-m-d")) > strtotime($selecao[0]['data_fim_inscricao'])) {
    erro_mensagem("Erro 3546745! As inscrições já finalizaram!");
    exit();
}
if (strtotime(date("Y-m-d")) < strtotime($selecao[0]['data_inicio_inscricao'])) {
    erro_mensagem("Erro 94568567945! As inscrições não iniciaram!");
    exit();
}

if ($selecao[0]['data_maxima_nascimento'] != '' && $selecao[0]['data_maxima_nascimento'] != null) {
    if (strtotime($data_nascimento) <= strtotime($selecao[0]['data_maxima_nascimento'])) {
        // CASO ESPECÍFICO DA SELEÇÃO MFDV 
        if (isset($_SESSION['mfdv'])) {
            if (($posto_grad == '2_ten' || $posto_grad == '1_ten' || $posto_grad == 'asp') && $ativa_reserva == 'ja_foi_militar_EAS') {
                $data_nasc = new DateTime($data_nascimento);
                $intervalo = $data_atual->diff($data_nasc);

                $anos_vida = (int)$intervalo->format('%Y');
                $meses_vida = (int)$intervalo->format('%m');
                $dias_vida = (int)$intervalo->format('%d');
                if ($anos_vida >= 45) {
                    erro_mensagem("Erro 35783585678! A data máxima de nascimento foi atingida (Os candidatos que já fizeram o EAS não podem ter 45 anos ou mais!");
                    exit();
                }
            } else {
                erro_mensagem("Erro 2346236246! A data máxima de nascimento foi atingida (" . $selecao[0]['data_maxima_nascimento'] . ")!");
                exit();
            }
        } else {
            erro_mensagem("Erro 2346236246! A data máxima de nascimento foi atingida (" . $selecao[0]['data_maxima_nascimento'] . ")!");
            exit();
        }
    }
}
if ($selecao[0]['data_minima_nascimento'] != '' && $selecao[0]['data_minima_nascimento'] != null) {
    if (strtotime($data_nascimento) >= strtotime($selecao[0]['data_minima_nascimento'])) {
        erro_mensagem("Erro 2362467437457! A data mínima de nascimento não foi atingida (" . $selecao[0]['data_minima_nascimento'] . ")!");
        exit();
    }
}

$chave = $_SESSION['chave'];

$senha = hash('sha256', $chave . $cpf);
$senha = substr($senha, 0, 6);
$senha_hash =  hash('sha256', $senha);

$_SESSION['senha_cadastrada'] = $senha;

$assinatura =   "2506"
    . "F"
    . date('dmY')
    . "R"
    . substr($_POST['identidade'], 0, 5)
    . "E"
    . $_POST['data_nascimento']
    . "I"
    . substr($cpf, 0, 9)
    . "T"
    . $chave
    . "A"
    . substr($_POST['cep'], 0, 5)
    . "S"
    . "1988";

$assinatura = mb_strtoupper($assinatura, 'UTF-8');
$assinatura = str_replace('/', '', $assinatura);
$assinatura = str_replace('-', '', $assinatura);
$assinatura = str_replace('"', '', $assinatura);
$assinatura = str_replace(' ', '', $assinatura);

//////////////////////////////////////////////////////
// Verifica se existe usuário na seleção
//////////////////////////////////////////////////////
/*asp silva*/

$resultado = $conexao->get_usuario_cpf($cpf);
if (count($resultado) > 0) {
    erro_mensagem("Erro 3463457456! Candidato já cadastrado nesta seleção! Nome: " . $resultado[0]['nome_completo'] . " e CPF:" . $resultado[0]['cpf']);
    exit();
}

////////////////////////////////////////////////////////
// Insere candidato no sistemas
//////////////////////////////////////////////////////


if (isset($vaga_reservada)) $vaga_reservada = 1;

if ($_POST)
    $resultado = $conexao->insere_candidato(
        $senha_hash,
        $nome_completo,
        $cpf,
        $identidade,
        $data_nascimento,
        $nome_social,
        $estado_civil,
        $companheiro,
        $filiacao_pai,
        $sexo,
        $nascionalidade,
        $naturalidade,
        $filiacao_mae,
        $uf,
        $bairro,
        $cidade,
        $cep,
        $rua,
        $telefone,
        $celular,
        $mail,
        $tempo_sv_pub,
        $tempo_sv_pub_anos,
        $tempo_sv_pub_meses,
        $tempo_sv_pub_dias,
        $tempo_sv_mil,
        $tempo_sv_mil_anos,
        $tempo_sv_mil_meses,
        $tempo_sv_mil_dias,
        $civil_militar,
        $certificado,
        $documento,
        $data_expedicao,
        $ativa_reserva,
        $posto_grad,
        $forca,
        $arma,
        $incorporacao,
        $licenciamento,
        $num_dependentes,
        $autodeclaracao,
        $vaga_reservada,
        $prioridade_forca,
        $voluntario_12rm,
        $nome_instituto_ensino,
        $ano_formacao,
        $uf_instituto_ensino,
        $cidade_instituto_ensino,
        $cidade_etapas_presenciais,
        $curso_graduacao,
        $ano_formacao_ofor,
        $nota_ofor,
        $arma_eipot,
        $cidade_exame_musica_12rm,
        $datetime,
        $assinatura
    );

if ($resultado) {
    $alteracoes_detalhadas =  print_r($resultado, true);
    $last_id = (int)$resultado['id_adicionado'];

    $_SESSION['id_candidato_cadastrado'] = $last_id;
    $_SESSION['cpf_usuario_cadastrado'] = $cpf;

    if ($resultado)
        $insere_log = $conexao->insere_log($last_id, $cpf, null, "14101", "usuario", "Insert", "Candidato se cadastrou", $alteracoes_detalhadas);
    // Caso não seja seleção CET envia e-mail de confirmação com a senha
    $_SESSION['mail_usuario_enviado'] = false;
    if ($selecao[0]['codigo'] != 'cet') {
        include_once '../sistema/codigos/candidato_cadastrado_mail.php';
    }

    $conexao = null;

    $id_criptografado = hash('sha256', $last_id);

    header("Location: ../sistema/candidato_cadastrado_sucesso.php?codigo=$id_criptografado");

    exit();
}
erro_mensagem("Erro 2763473457! Candidato não cadastrado!");
exit();
$conexao = null;
