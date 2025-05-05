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
$nome_selecao = mb_strtoupper($selecao_atual[0]['nome'] . " - " . $selecao_atual[0]['ano'],"UTF-8") ;
 
// SELÇÃO DE MÉDICOS
// SELEÇÃO DE FARMACÊUTICOS, DENTISTAS E VETERINÁRIOS

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
    <th align='center'><strong>Relatório de candidatos inscritos</strong>
  </tr>
  <tr>
    <th align='right'><strong> <p style='font-size: 12px; font-family: Times New Roman;'> Porto Alegre - ".$data_hoje."   </p></strong></th>
  </tr>
</table> 

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   O Comandante da 3ª Região Militar divulga a relação dos candidatos voluntários inscritos no processo seletivo de Médicos 2019/2020.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

Outrossim, todos os candidatos elencados na relação abaixo estão convocados a comparecer na Comissão de Seleção Especial de Médicos (CSE), a funcionar nos locais e datas constantes no Anexo \"A\" (Calendário Geral de Atividades) do respectivo Aviso de Convocação.

</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

A presente relação está em ordem alfabética, por Área e Habilitação Técnica.
</p>

" ;
    
$mpdf->WriteHTML($html);
// $mpdf->AddPage();

$html = "";


$lista_especialidades = $conexao->get_especialidade(); 
foreach ($lista_especialidades as &$especialidade) 
{
    
    //if($especialidade['ott_stt'] == 'medico') continue;
    if($especialidade['ott_stt'] != 'medico') continue;
    
    $mfdv_nome = $especialidade['ott_stt'];
    if($mfdv_nome == 'medico') $mfdv_nome = "MÉDICO";
    if($mfdv_nome == 'dentista') $mfdv_nome = "DENTISTA";
    if($mfdv_nome == 'veterinario') $mfdv_nome = "VETERINÁRIO";
    if($mfdv_nome == 'farmaceutico') $mfdv_nome = "FARMACÊUTICO";
    if($mfdv_nome == 'ott') $mfdv_nome = "OTT";
    if($mfdv_nome == 'stt') $mfdv_nome = "STT";
    
    $html = "";
    $html = $html . " <br>
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

        <tr style='background-color: #D8D8D8'>
            <td colspan=\"5\"> <center><b> $mfdv_nome - ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
        </tr>";
    
        $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo($especialidade['id']);  
        $contador = 1;

        
        
        if(count($lista_candidatos_especialidade) == 0)
        {
            $html = $html . "
            <tr>
                <td colspan='5'>
                    Nenhum candidato se inscreveu para esta especialidade.
                </td>
            </tr>";
        }
        else
        {
            $html = $html . "

                <tr>
                    <td>
                        <b>Nº</b>
                    </td>
                    <td>
                        <b>CPF</b>
                    </td>
                    <td>
                        <b>NOME</b>
                    </td>
                </tr>";
            
            foreach($lista_candidatos_especialidade as &$candidato)
            {
                $cpf = substr($candidato['cpf'], 0, -6);
                $cpf = $cpf . "******";
                
                $concorrendo = null;
                if($candidato['concorrendo'] == '1')
                    $concorrendo = "Aprovado";
                else if($candidato['concorrendo'] == '0')
                    $concorrendo = "Reprovado";
                
                $justificativa = null;
                
                if($candidato['concorrendo'] == '0')
                    $justificativa = $candidato['justificativa_concorrendo'];
                
                $html = $html . "
                    <tr>
                        <td>
                            $contador
                        </td>
                        <td>
                            ".$cpf."
                        </td>
                        <td>
                            ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
                        </td>
                    </tr>";
                    $contador++;
            }
        }
        
        $html = $html . "</table> <br>";
        
        $mpdf->WriteHTML($html);
        //$mpdf->AddPage();
        
}


 //$mpdf->SetDisplayMode('fullwidth');
 
//$mpdf->WriteHTML($html);
$mpdf->Output("inscritos.pdf",'D');

exit();