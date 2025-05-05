<?php

$lista_candidatos = $conexao->get_candidatos_desclassificados(); 

$get_selecao = $conexao->get_selecao_id();
$pagamento_obrigatorio = $get_selecao[0]['pagamento'];
$selecao_eliminar_caso_nao_adicione_docs_obrigatorios = $get_selecao[0]['eliminar_docs_obrigatorios'];
$selecao_eliminar_caso_nao_adicione_sua_foto = $get_selecao[0]['eliminar_caso_nao_adicione_foto'];

$html = $html . "
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
Código 351418: Candidato(a) não se inscreveu em nenhuma especialidade.
</p>";
if($selecao_eliminar_caso_nao_adicione_sua_foto == '1')
    $html = $html . "
    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    Código 715978: Candidato(a) não adicionou a sua foto.
    </p>";

if($selecao_eliminar_caso_nao_adicione_docs_obrigatorios == '1')
    $html = $html . "
    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    Código 191418: Candidato(a) não adicionou todos os documentos obrigatórios.
    </p>";

if($pagamento_obrigatorio == '1')
    $html = $html . "
    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    Código 475978: Candidato(a) não adicionou o arquivo de pagamento/isenção.
    </p>

    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    Código 328451: O pagamento não foi efetivado.
    </p>

    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    Código 245876: Candidato(a) avaliado(a) como NÃO ISENTO(A).";

$html = $html . " <br>
    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >
        <tr>
            <td style='background-color: #D8D8D8'>
                <b>Nº</b>
            </td>
            <td style='background-color: #D8D8D8'>
                <b>CPF</b>
            </td>
            <td style='background-color: #D8D8D8'>
                <b>NOME</b>
            </td>
            <td style='background-color: #D8D8D8'>
                <b>MOTIVO</b>
            </td>
        </tr>";
    
$contador = 1;

foreach ($lista_candidatos as $candidato) 
{
    /////////////////////////////////////////////////////////////////////////////////////////////
    // SOMENTE QUEM SE INSCREVEU PARA MÉDICO
    /*
    sem_medicos
    somente_medicos
    sem_musicos
    somente_musicos
    */
    
    $lista_inscricoes = $conexao->get_especialidade_candidato($candidato['id']);
    if($tp_especialidade == 'somente_medicos')
    $cadastrou_especialidade_medico = false;
    
    foreach($lista_inscricoes as &$especialidade)
    {
        if($especialidade['ott_stt'] == 'medico')
            $cadastrou_especialidade_medico = true;
        if($especialidade['ott_stt'] != 'medico')
            $cadastrou_especialidade_medico = false;
    }
    
    if($cadastrou_especialidade_medico == false && $tp_especialidade == 'somente_medicos')
        continue;
    if($cadastrou_especialidade_medico == true && $tp_especialidade == 'sem_medicos')
        continue;
    
    $cpf = substr($candidato['cpf'], 0, -6);
    $cpf = $cpf . "******";

    $justificativa = null;

    if($candidato['concorrendo'] == '0')
        $justificativa = $candidato['justificativa_concorrendo'];
    
    
    ////////////////////////////////////////
    // Verifica código na justificativa
    
    $codigo_final_usuario = null;

    $codigo = '351418'; // Não se inscreveu em nenhuma especialidade
    $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
    if(preg_match($pattern, $justificativa)) 
      $codigo_final_usuario = $codigo_final_usuario.  ' | 351418 ';
    
    if($selecao_eliminar_caso_nao_adicione_docs_obrigatorios == '1')
    {
        $codigo = '191418'; // Não adicionou todos os documentos obrigatórios
        $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
        if(preg_match($pattern, $justificativa)) 
          $codigo_final_usuario = $codigo_final_usuario. ' | 191418 ';
    }
    
    if($pagamento_obrigatorio == '1')
    {
    
        $codigo = '475978'; // Não adicionou o arquivo de pagamento/isenção
        $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
        if(preg_match($pattern, $justificativa)) 
          $codigo_final_usuario = $codigo_final_usuario . ' | 475978 ';
    
        $codigo = '328451'; // O pagamento não foi efetivado
        $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
        if(preg_match($pattern, $justificativa)) 
          $codigo_final_usuario = $codigo_final_usuario . ' | 328451';

        $codigo = '245876'; // Candidato(a) avaliado(a) como NÃO ISENTO(A)
        $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
        if(preg_match($pattern, $justificativa)) 
          $codigo_final_usuario = $codigo_final_usuario . ' | 245876 ';
    }
    
    if($selecao_eliminar_caso_nao_adicione_sua_foto == '1')
    {
        $codigo = '715978'; // Não adicionou a sua foto
        $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
        if(preg_match($pattern, $justificativa)) 
          $codigo_final_usuario = $codigo_final_usuario . ' | 715978 ';
    }
    
    $codigo_final_usuario = "Código(s): " . $codigo_final_usuario;
    
    if($codigo_final_usuario != null)
    {
        $justificativa = $codigo_final_usuario;
    }
    
    // FIM
    ////////////////////////////////////////
    
    $html = $html . "
        <tr>
            <td>
                ".$contador."
            </td>
            <td>
                ".$cpf."
            </td>
            <td>
                ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
            </td>
            <td>
                ".$justificativa."
            </td>
        </tr>";
    
    $contador++;
}
$html = $html . "</table>";

?>