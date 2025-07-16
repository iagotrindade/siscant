<?php

// VERIFICA SE O CANDIDATO É O PROXÍMO A ESCOLHER A CIDADE
$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);

$vetor_ordenado_candidatos = [];

foreach ($lista_candidatos as $linha) {
    // Critério Geral: Nota Final do EIPOT
    $nota_final_eipot = get_nota_final_eipot($linha['id']);

    // Primeiro Critério de Desempate: Maior Nota do OFOR
    $nota_ofor = $linha['nota_ofor_avaliador'];

    // Segundo Critério de Desempate: Maior pontuação Total do EAF
    $nota_final_eaf   = get_nota_final_eaf($linha['id']);

    // Terceiro Critério de Desempate: Menor Idade
    $tempo_total_idade_dias = 0;
    $data_atual = new DateTime(date("Y-m-d"));
    $data_nasc = new DateTime($linha['data_nascimento']);
    $intervalo = $data_atual->diff($data_nasc);

    $anos_vida  = (int)$intervalo->format('%Y');
    $meses_vida = (int)$intervalo->format('%m');
    $dias_vida  = (int)$intervalo->format('%d');

    $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + $dias_vida;

    $vetor_ordenado_candidatos[] = [
        "id" => $linha['id'],
        "nome" => mb_strtoupper($linha['nome_completo'], "UTF-8"),
        "cpf" => $linha['cpf'],
        "tempo_idade" => (int) $tempo_total_idade_dias,
        "autodeclaracao" => $linha['autodeclaracao'],
        "vaga_reservada" => $linha['vaga_reservada'],
        "mail" => $linha['mail'],
        "etapa" => $linha['etapa'],
        "nota_final_eipot" => (float)$nota_final_eipot,
        "nota_ofor" => (float)$nota_ofor,
        "nota_final_eaf" => (float)$nota_final_eaf,
        "cidade_escolheu_servir" => $linha['cidade_escolheu_servir'],
    ];
}

// Ordenação
if (count($vetor_ordenado_candidatos) > 0) {
    // Preparar arrays para ordenação
    $nota_final = [];
    $nota_ofor = [];
    $nota_eaf = [];
    $tempo_idade = [];

    foreach ($vetor_ordenado_candidatos as $index => $linha) {
        $nota_final[$index] = (float) $linha['nota_final_eipot'];
        $nota_ofor[$index] = (float) $linha['nota_ofor'];
        $nota_eaf[$index] = (float) $linha['nota_final_eaf'];
        $tempo_idade[$index] = (int) $linha['tempo_idade'];
    }

    array_multisort(
        $nota_final,
        SORT_DESC,   // 1º critério: maior nota EIPOT
        $nota_ofor,
        SORT_DESC,    // 2º critério: maior nota OFOR
        $nota_eaf,
        SORT_DESC,     // 3º critério: maior nota EAF
        $tempo_idade,
        SORT_ASC,  // 4º critério: menor idade
        $vetor_ordenado_candidatos
    );
}


// Separa as vagas por Região Militar
$vagas_por_regiao = separa_vaga_rm($lista_cidades_epecialidades);

$totalVagasPorRegiao = [];

foreach ($vagas_por_regiao as $regiao => $cidades) {
    $total = 0;
    foreach ($cidades as $cidade) {
        $total += (int)$cidade['vagas'];
    }
    $totalVagasPorRegiao[$regiao] = $total;
}

$totalVagasAmplaPorRegiao = [];

foreach ($totalVagasPorRegiao as $regiao => $total) {
    if ($total <= 2) {
        $totalVagasAmplaPorRegiao[$regiao] = $total;
    } elseif ($total == 3) {
        $totalVagasAmplaPorRegiao[$regiao] = $total - 1;
    } else {
        $reservadas = round($total * 0.2, 0, PHP_ROUND_HALF_UP);
        $totalVagasAmplaPorRegiao[$regiao] = $total - $reservadas;
    }
}