<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

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

$conexao = new Conexao();
 
 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>
 <table border='0' style='width:100%'>
    <tr>
          <th align='center'><strong>".mb_strtoupper("Seleção de Oficiais e Técnicos Temporários 2019","UTF-8")."</strong></th>
    </tr>
</table> 
<br>
<br>

 <table border='0' style='width:100%'>
  <tr>
    <th align='left'><strong>Relatório de candidatos inscritos</strong>
    <th align='right'><img src='../imagens/3rm.png'  height='50px'> <strong><u>   </strong></th>
  </tr>
</table> 

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
    O Comandante da 3ª Região Militar divulga a relação dos candidatos inscritos para OFICIAL TÉCNICO TEMPORÁRIO (OTT) e SARGENTO TÉCNICO TEMPORÁRIO (STT),
conforme anexo \"A\" (Calendário Geral de Atividades) do Aviso de Convocação nº 1-SSMR/3, de 03 de JUNHO de 2019.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
A presente relação está em ordem alfabética, por Área e Habilitação Técnica.
</p>

" ;
    
$mpdf->WriteHTML($html);
$mpdf->AddPage();

$html = "";


$lista_especialidades = $conexao->get_especialidade(); 
foreach ($lista_especialidades as &$especialidade) 
{
    $html = "";
    $html = $html . "
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

        <tr style='background-color: #D8D8D8'>
            <td colspan=\"3\"> <center><b> ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
        </tr>
        <tr style='background-color: #D8D8D8'>
            <td> </td>
        </tr>";

        
    
        $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo($especialidade['id']);  
        $contador = 1;

        if(count($lista_candidatos_especialidade) == 0)
        {
            $html = $html . "
            <tr>
                <td colspan='3'>
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
                $cpf = substr($candidato['cpf'], 0, -5);
                $cpf = $cpf . "*****";
                $html = $html . "
                    <tr>
                        <td>
                            $contador
                        </td>
                        <td>
                            ".$cpf."
                        </td>
                        <td>
                            ".$candidato['nome_completo']."
                        </td>
                    </tr>";
                    $contador++;
            }
        }
        
        $html = $html . "</table>";
        
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        
}


 //$mpdf->SetDisplayMode('fullwidth');
 
//$mpdf->WriteHTML($html);
$mpdf->Output("inscritos.pdf",'D');

exit();