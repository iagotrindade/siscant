<?php

include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

// Bloqueio de acesso temporário
erro_mensagem("Ainda não é possível passar os candidatos do EIPOT de etapa por aqui!!!");

// Verificação inicial de segurança
if (!$_POST) {
    erro_mensagem("Erro 2342344! Método inválido.");
    exit();
}

if (isset($_SESSION['eipot']) != 1) {
    erro("Erro 2342344! Método inválido.");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

if (inscricao()) {
    erro("Erro 24574! Inscrição em andamento!");
    exit();
}

// Sanitização e validação dos inputs
$criptografia = filter_input(INPUT_POST, 'crip', FILTER_SANITIZE_STRING);
if ($criptografia != hash('sha256', $_SESSION['chave'] . "freitas")) {
    erro("Erro 8445324574! Não foi possível executar o arquivo!");
    exit();
}

$conexao = new Conexao();

// Buscando a etapa da seleção
$get_selecao = $conexao->get_selecao_id();
if (empty($get_selecao)) {
    erro("Erro! Não foi possível obter dados da seleção.");
    exit();
}

$rm_usuario = $conexao->rm_usuario($_SESSION['id_usuario']);

if (empty($rm_usuario)) {
    erro("Erro! Não foi possível obter dados do usuário.");
    exit();
}

$etapaSelecao = (int)$get_selecao[0]['etapa'];

// Sanitização dos parâmetros numéricos
$id_especialidade = filter_input(INPUT_POST, 'id_especialidade', FILTER_SANITIZE_NUMBER_INT);
$etapa = filter_input(INPUT_POST, 'etapa', FILTER_SANITIZE_NUMBER_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);

// Validação adicional para números positivos
if ($id_especialidade <= 0 || $etapa <= 0 || $quantidade <= 0) {
    erro("Erro! Parâmetros inválidos!");
    exit();
}

// Verificar se a etapa é válida
if ($etapa > $etapaSelecao) {
    erro('Não é possível passar os candidatos para uma etapa maior que a do sistema.');
    exit();
}

$especialidade = $conexao->get_especialidade_selecionadas($id_especialidade);

if (empty($especialidade)) {
    erro("Erro! Especialidade não encontrada!");
    exit();
}

$lista_inscritos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);

$ordem_arma = [
    'INFANTARIA',
    'CAVALARIA',
    'ARTILHARIA DE CAMPANHA',
    'ARTILHARIA ANTIAÉREA',
    'ENGENHARIA',
    'COMUNICAÇÕES',
    'MATERIAL BÉLICO',
    'INTENDÊNCIA'
];

$inscritos_por_arma = [];

// Agrupa inscritos por arma
foreach ($lista_inscritos as $inscrito) {
    if ($inscrito['arma_especialidade'] == null) continue;
    // Verifica se a especialidade é igual a mandada no POST
    if ($inscrito['arma_especialidade'] != $especialidade[0]['nome']) continue;
    $arma = $inscrito['arma_especialidade'];
    $inscritos_por_arma[$arma][] = $inscrito;
}

uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    // Verificando se a especialidade $a e $b estão na ordem específica
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);

    // Se não encontrar a especialidade, coloca no final e ordena alfabeticamente
    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    // Se ambos os valores estão fora da ordem (não definidos em $ordem_arma), ordena alfabeticamente
    if ($pos_a === PHP_INT_MAX && $pos_b === PHP_INT_MAX) {
        return strcasecmp($a, $b);
    }

    return $pos_a - $pos_b;
});


foreach ($inscritos_por_arma as $arma => $candidatos) {
    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }

    $nota_final_eipot = [];

    foreach ($candidatos as $index => $candidato) {
        // Obtendo a nota final de cada candidato
        $nota_final_eipot[$index] = get_nota_final_eipot($candidato['id']);
        $nota_final_eipot[$index] = (float) $nota_final_eipot[$index];

        // Mascarando o CPF
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
    }

    // Aplicando os critérios de desempate


    // Ordenando os arrays de candidatos e suas notas simultaneamente (decrescente)
    // CÓDIGO AINDA INCOMPLETO NECESSÁRIO VERIFICAR COMO FUNCIONARÁ O EAF 

    usort($candidatos, function ($a, $b) {
        // 1. Maior nota OFOR
        $cmp = $b['nota_ofor_avaliador'] <=> $a['nota_ofor_avaliador'];
        if ($cmp !== 0) return $cmp;

        // 2. Maior pontuação EAF
        $cmp = $b['pontuacao_eaf'] <=> $a['pontuacao_eaf'];
        if ($cmp !== 0) return $cmp;

        // 3. Menor idade (ou seja, maior data_nascimento)
        $dataA = strtotime($a['data_nascimento']);
        $dataB = strtotime($b['data_nascimento']);
        return $dataB <=> $dataA;
    });
}

echo ("<pre>");
print_r($candidatos);
exit;
