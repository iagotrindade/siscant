<?php

$lista_especialidades = $conexao->get_especialidade(); 
foreach ($lista_especialidades as &$especialidade) 
{
    if($tp_especialidade == 'sem_medicos' && $especialidade['ott_stt'] == 'medico') continue;
    if($tp_especialidade == 'somente_medicos' && $especialidade['ott_stt'] != 'medico') continue;
    if($tp_especialidade == 'sem_musicos' && $especialidade['musica'] == '1') continue;
    if($tp_especialidade == 'somente_musicos' && $especialidade['musica'] == '0') continue;

    $tipo_especialidade = $especialidade['ott_stt'];
    if($tipo_especialidade == 'medico') $tipo_especialidade = "MÉDICO";
    if($tipo_especialidade == 'dentista') $tipo_especialidade = "DENTISTA";
    if($tipo_especialidade == 'veterinario') $tipo_especialidade = "VETERINÁRIO";
    if($tipo_especialidade == 'farmaceutico') $tipo_especialidade = "FARMACÊUTICO";
    if($tipo_especialidade == 'ott') $tipo_especialidade = "OTT";
    if($tipo_especialidade == 'stt') $tipo_especialidade = "STT";
    if($tipo_especialidade == 'cet') $tipo_especialidade = "CET";

    $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo_nao_concorrendo($especialidade['id']);  

    $contador = 1;

    /////////////////////////////////////////////////////////////
    // VERIFICA SE POSSUI ALGUM CANDIDATO NAQUELA ESPECIALIDADE
    /////////////////////////////////////////////////////////////

    $possui_candidato = false;
    foreach($lista_candidatos_especialidade as &$candidato)
    {
        if($candidato['etapa'] == $etapa)
        {
            if($tipo_relatorio == 'concorrendo_esp'         && ($candidato['concorrendo'] == '1' && $candidato['concorrendo_especialidade'] == '1'))
                $possui_candidato = true;
            if($tipo_relatorio == 'nao_concorrendo_esp' && ($candidato['concorrendo'] == '0' || $candidato['concorrendo_especialidade'] == '0'))
                $possui_candidato = true;
        }
    }

    if($mostrar_especialidade == 'nao_mostrar_especialidade' && !$possui_candidato) continue;

    $html = $html . "
    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"5\"> <center><b> $tipo_especialidade - ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
    </tr>";

    if(!$possui_candidato)
    {
        $html = $html . "
        <tr>
            <td colspan='3'>
                Nenhum candidato nesta especialidade.
            </td>
        </tr>";
    }
    else
    {
        $html = $html . "
            <tr>";
        
                if($tipo_relatorio != 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        <b>Nº</b>
                                    </td>";
                
                $html = $html . "
                <td>
                    <b>CPF</b>
                </td>
                <td>
                    <b>NOME</b>
                </td>";
        
                if($tipo_relatorio == 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        <b>MOTIVO</b>
                                    </td>";
        
            $html = $html . "</tr>";

        foreach($lista_candidatos_especialidade as &$candidato)
        {
            if($candidato['etapa'] != $etapa) continue;

            if($tipo_relatorio == 'concorrendo_esp'     && ($candidato['concorrendo'] == '0' || $candidato['concorrendo_especialidade'] == '0' || $candidato['apagado'] == '1')) continue;
            if($tipo_relatorio == 'nao_concorrendo_esp' && ($candidato['concorrendo'] == '1' && $candidato['concorrendo_especialidade'] == '1' || $candidato['apagado'] == '1')) continue;

            $cpf = substr($candidato['cpf'], 0, -6);
            $cpf = $cpf . "******";

            $justificativa = null;

            if($candidato['concorrendo'] == '0') $justificativa = $candidato['justificativa_concorrendo'];
            if($candidato['concorrendo_especialidade'] == '0') $justificativa = $candidato['justificativa_especialiade'];

            $html = $html . "<tr>";

                    if($tipo_relatorio != 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        $contador
                                    </td>";
        
                    $html = $html . "
                    <td>
                        ".$cpf."
                    </td>
                    <td>
                        ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
                    </td>";

                    if($tipo_relatorio == 'nao_concorrendo_esp')
                    $html = $html ."<td>
                                        $justificativa
                                    </td>";
        
                    $html = $html . "</tr>";

                $contador++;
        }
    }

    $html = $html . "</table> <br>";
}

?>