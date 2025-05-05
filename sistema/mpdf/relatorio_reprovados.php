<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$old = ini_set('memory_limit', '512M'); 

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);

set_time_limit(300);

session_start();

if(!isset($_SESSION['perfil']))
{
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if($_SESSION['perfil'] != 'admin')
{
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if($_SESSION['candidato'] == '1')
{
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

$data_hoje = date('d/m/Y');


$conexao = new Conexao();
 
$selecao_atual = $conexao->get_selecao_id();
$nome_selecao = mb_strtoupper($selecao_atual[0]['nome'] . " - " . $selecao_atual[0]['ano'],"UTF-8");

 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>
 <table border='0' style='width:100%'>
    <tr>
          <th align='center'><strong>SELEÇÃO DE MÉDICOS</strong></th>
    </tr>
</table> 
<br>
<br>

 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong>Relatório de candidatos reprovados na inscrição</strong>
  </tr>
  <tr>
    <th align='right'><strong> <p style='font-size: 12px; font-family: Times New Roman;'> Porto Alegre - ".$data_hoje."   </p></strong></th>
  </tr>
</table> 

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

O Comandante da 3ª Região Militar divulga a relação dos candidatos REPROVADOS na inscrição para SELEÇÃO DE MÉDICOS,
conforme anexo \"A\" (Calendário Geral de Atividades) do respectivo Aviso de Convocação.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
Código 35141: Candidato(a) não se inscreveu em nenhuma especialidade.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
Código 47597: Candidato(a) não adicionou o arquivo de pagamento/isenção.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
Código 328451: O pagamento não foi efetivado.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
Código 245876: Candidato(a) avaliado(a) como NÃO ISENTO(A).
</p>

" ;
    
$mpdf->WriteHTML($html);
//$mpdf->AddPage();

$html = "";


$lista_candidatos_desclassificados = $conexao->get_candidatos_desclassificados(); 

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
foreach ($lista_candidatos_desclassificados as &$candidato) 
{
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////
    // SOMENTE QUEM SE INSCREVEU PARA MÉDICO
    $lista_inscricoes = $conexao->get_especialidade_candidato($candidato['id']);

    $cadastrou_especialidade_medico = false;
    foreach($lista_inscricoes as &$especialidade)
    {
        if($especialidade['ott_stt'] == 'medico')
            $cadastrou_especialidade_medico = true;
    }
    if($cadastrou_especialidade_medico == false)
        continue;
    
    
    
            
    $cpf = substr($candidato['cpf'], 0, -6);
    $cpf = $cpf . "******";

    $justificativa = null;

    if($candidato['concorrendo'] == '0')
        $justificativa = $candidato['justificativa_concorrendo'];
    
    
    ////////////////////////////////////////
    // Verifica código na justificativa
    
    $codigo_final_usuario = null;

    $codigo = '35141';
    $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
    if(preg_match($pattern, $justificativa)) 
      $codigo_final_usuario = ' | 35141 | ';
    
    $codigo = '47597';
    $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
    if(preg_match($pattern, $justificativa)) 
      $codigo_final_usuario = $codigo_final_usuario . ' | 47597 | ';
    
    $codigo = '328451';
    $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
    if(preg_match($pattern, $justificativa)) 
      $codigo_final_usuario = $codigo_final_usuario . ' | 328451 |';
    
    $codigo = '245876';
    $pattern = '/' . $codigo . '/'; //Padrão a ser encontrado na string $tags
    if(preg_match($pattern, $justificativa)) 
      $codigo_final_usuario = $codigo_final_usuario . ' | 245876 | ';
    
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
$mpdf->WriteHTML($html);


 //$mpdf->SetDisplayMode('fullwidth');
 
//$mpdf->WriteHTML($html);
$mpdf->Output("desclassificados.pdf",'D');

exit();