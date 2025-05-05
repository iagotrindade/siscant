<?php

$id_especialidade = $_POST['especialidades'];


    if (is_array($id_especialidade) && !empty($id_especialidade)) {
        $lista_especialidades = [];
     
        foreach ($id_especialidade as $id) {
            $result = $conexao->get_especialidade_selecionadas($id);
            $lista_especialidades = array_merge($lista_especialidades, $result);
        }
    }
    else {
        $lista_especialidades = $conexao->get_especialidade();
}

$contador = 1;
foreach ($lista_especialidades as &$especialidade) 
{
    
    $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo($especialidade['id']);  

    ////////////////////////////////////////////////////////////////////////////////////////////
    // Não mostrar especialidade caso não tenha ninguém

    $possui_candidato = false;
    foreach($lista_candidatos_especialidade as &$candidato)
    {
        if($candidato['etapa'] == $etapa) $possui_candidato = true;
    }
    
    if($mostrar_especialidade == 'nao_mostrar_especialidade' && !$possui_candidato) continue;

    ////////////////////////////////////////////////////////////////////////////////////////////
    // Caso não queira que aparece/não apareça médicos ou músicos

    if($tp_especialidade == 'sem_medicos' && $especialidade['ott_stt'] == 'medico') continue;
    if($tp_especialidade == 'somente_medicos' && $especialidade['ott_stt'] != 'medico') continue;
    if($tp_especialidade == 'sem_musicos' && $especialidade['musica'] == '1') continue;
    if($tp_especialidade == 'somente_musicos' && $especialidade['musica'] == '0') continue;

    ////////////////////////////////////////////////////////////////////////////////////////////
    
    $tipo_especialidade = $especialidade['ott_stt'];
    if($tipo_especialidade == 'medico') $tipo_especialidade = "MÉDICO";
    if($tipo_especialidade == 'dentista') $tipo_especialidade = "DENTISTA";
    if($tipo_especialidade == 'veterinario') $tipo_especialidade = "VETERINÁRIO";
    if($tipo_especialidade == 'farmaceutico') $tipo_especialidade = "FARMACÊUTICO";
    if($tipo_especialidade == 'ott') $tipo_especialidade = "OTT";
    if($tipo_especialidade == 'stt') $tipo_especialidade = "STT";
    if($tipo_especialidade == 'cet') $tipo_especialidade = "CET";

    $html = $html . " <br>
    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"4\"> <center><b> $tipo_especialidade - ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
    </tr>";

    $vetor_ordenado_candidatos = null;
    $vetor_ordenado_candidatos = array();

    foreach ($lista_candidatos_especialidade as $linha) 
    {
        
        if($linha['etapa'] != $etapa) continue;
        
        ///////////////////////////////
        //  PONTUAÇÃO

        $pontuacao_curriculo = 0;
        $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$especialidade['id']);  
        if(count($get_pontuacao_avaliada) > 0)
            $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],2);
        
        ///////////////////////////////
        // Nota Prova Teórica Prática
        $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'],$especialidade['id']);
        $nota_prova_teorico_pratico = 0;

        if(count($resultado_verificacao) > 0)
        {
            $nota_prova_teorico_pratico = (float)$resultado_verificacao[0]['nota_prova_teorico_pratico'];
            $pontuacao_curriculo = round ($pontuacao_curriculo + $nota_prova_teorico_pratico,2);
        }
        
       
        
        ////////////////////////////////////////////
        // CASO SEJA DE MÚSICA
        if($especialidade['musica'] == '1')
        {
            if(count($resultado_verificacao) > 0)
            {
                $prova_pratica_musica           = (float)$resultado_verificacao[0]['prova_pratica_musica'];
                $prova_teorica_musica           = (float)$resultado_verificacao[0]['prova_teorica_musica'];
                $prova_oral_musica              = (float)$resultado_verificacao[0]['prova_oral_musica'];
                $usuario_avaliou_provas_musica  = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
            }

            $pontuacao_curriculo = (((($prova_teorica_musica*2) + ($prova_pratica_musica*2) + $prova_oral_musica)/5)+$pontuacao_curriculo)/2;
            
        }
        //
        ////////////////////////////////////////////

        /* CODIGOS MILITAR

        1 - Oficial da Ativa
        2 - Oficial R2
        3 - Aspitante R2
        4 - Praça Ativa
        5 - Reservista de 1ª categoria
        6 - Reservista de 2ª categoria
        7 - Civil

        */

        $militar = 7;

        // Oficiais da Ativa
        if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten" || $linha['posto_grad'] == "asp"))
            $militar = 1;

        // Oficial R2
        if($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten"))
            $militar = 2;

        // Aspirante R2
        if($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "asp"))
            $militar = 3;

        // Praça Ativa
        if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "cd" || $linha['posto_grad'] == "sd"))
            $militar = 4;

        // Reservista de 1ª categoria
        if($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil'))
            $militar = 5;

        // Reservista de 2ª categoria
        if($linha['certificado'] == '2crm')
            $militar = 6;

        ///////////////////////////////
        //  TEMPO SERVIÇO PÚBLICO
        ///////////////////////////////

        $tempo_total_sv_publico_dias = 0;
        $anos_sv_publico = (int)$linha['tempo_sv_mil_anos'];
        $meses_sv_publico = (int)$linha['tempo_sv_mil_meses'];
        $dias_sv_publico = (int)$linha['tempo_sv_mil_dias'];

        $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + ($dias_sv_publico);

        ///////////////////////////////
        //  IDADE
        ///////////////////////////////

        $tempo_total_idade_dias = 0;
        $data_atual = new DateTime(date("Y-m-d"));
        $data_nasc = new DateTime($linha['data_nascimento']);
        $intervalo = $data_atual->diff($data_nasc);

        $anos_vida  = (int)$intervalo->format('%Y');
        $meses_vida = (int)$intervalo->format('%m');
        $dias_vida  = (int)$intervalo->format('%d');

        $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + ($dias_vida);

        ///////////////////////////////
        //  ORDENA
        ///////////////////////////////

        $novo_vetor = array();

        $novo_vetor = 
        [
            "id" => $linha['id'],
            "nome" => $linha['nome_completo'],
            "cpf" => $linha['cpf'],
            "pontos" => $pontuacao_curriculo,
            "militar" => $militar,
            "tempo_sv_pub" => $tempo_total_sv_publico_dias,
            "tempo_idade" => $tempo_total_idade_dias
        ];

        array_push($vetor_ordenado_candidatos,$novo_vetor);
    }


    if(count($lista_candidatos_especialidade) > 0 && $possui_candidato)
    {
        $id_array = null;
        $nome_array = null;
        $pontos_array = null;
        $militar_array = null;
        $tempo_sv_pub = null;
        $tempo_idade = null;

        foreach ($vetor_ordenado_candidatos as $index => $linha2) 
        {
            $id_array[$index]      = $linha2['id'];
            $nome_array[$index]    = $linha2['nome'];
            $pontos_array[$index]  = $linha2['pontos'];
            $militar_array[$index] = $linha2['militar'];
            $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
            $tempo_idade[$index]   = $linha2['tempo_idade'];
        }

        array_multisort(
                $pontos_array,  SORT_DESC, 
                $militar_array, SORT_ASC, 
                $tempo_sv_pub, SORT_ASC, 
                $tempo_idade, SORT_DESC, 
                $vetor_ordenado_candidatos);
    }

    if(count($lista_candidatos_especialidade) == 0 || !$possui_candidato)
    {
        $html = $html . "
        <tr>
            <td colspan='4'>
                Nenhum candidato para esta especialidade.
            </td>
        </tr>";
    }
    else
    {
        $html = $html . "

            <tr>
                <td>
                    <b>CLASSIFICAÇÃO</b>
                </td>
                <td>
                    <b>CPF</b>
                </td>
                <td>
                    <b>NOME</b>
                </td>
                <td align=\"right\">
                    <b>PONTOS</b>
                </td>
            </tr>";


        $lugar = 1;
        foreach ($vetor_ordenado_candidatos as $linha) 
        {

            $cpf = substr($linha['cpf'], 0, -6);
            $cpf = $cpf . "******";

            $pontos = 0;
            $pontos = round($linha['pontos'],2);


            $html = $html . "
            <tr>
                <td>  $lugar º</td>
                <td>".$cpf."</td>
                <td>".mb_strtoupper($linha['nome'],"UTF-8")."</td>
                <td align=\"right\">".$pontos."</td>
            </tr>";

            $lugar ++;
        }
    }

    $html = $html . "</table> ";

    $contador++;
}

?>