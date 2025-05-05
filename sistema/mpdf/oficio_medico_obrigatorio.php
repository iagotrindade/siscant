<?php 

$data_hoje = date('d/m/Y H:i:s');

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$old = ini_set('memory_limit', '512M'); 

$datetime = date('d/m/Y H:i:s');

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);

set_time_limit(300);

session_start();

if(!isset($_SESSION['perfil']))
{
    erro_relatorio("Erro 23464264! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
{
    erro_relatorio("Erro 23464236! Somente o administrador pode gerar este relatório");
    exit();
}
if($_SESSION['candidato'] == '1')
{
    erro_gerar_relatorio_cadastro_candidato("Erro 234643674357!");
    exit();
}

if(!$_POST)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 234624364775!");
    exit();
}

if(hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) != $_POST['crip'])
{
    erro_gerar_relatorio_cadastro_candidato("Erro 23467432737!");
    exit();
}

$endereco_om_1_fase = $_POST['endereco_om_1_fase'];
$cep_om_1_fase = $_POST['cep_om_1_fase'];
$telefone_om_1_fase = $_POST['telefone_om_1_fase'];

$hora_apresentacao = $_POST['hora_apresentacao'];

$presidente = $_POST['presidente'];

$data_apresentacao = $_POST['data_apresentacao'];

// $data_incorporacao = $_POST['data_incorporacao'];

$data_cabecalho = $_POST['dt_cabecalho'];

$id_candidato = $_POST['id_candidato'];
$cpf_candidato = $_POST['c_p_f_candidato'];
$eb = $_POST['eb'];

$conexao = new Conexao();

$get_candidato = $resultado = $conexao->get_usuario_id($id_candidato);

$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_candidato);
        
$nome_especialidade_candidato = $get_especialidade_candidato['0']['ott_stt'];

if($nome_especialidade_candidato == 'medico') $nome_especialidade_candidato = "médico";

$data_incorporacao = $get_candidato[0]['data_incorporacao'];

if($data_incorporacao != null) $data_incorporacao = trata_data($data_incorporacao);
        

if($cpf_candidato != (int)$get_candidato[0]['cpf'])
{
    erro_gerar_relatorio_cadastro_candidato("Erro 34578458999944!");
    exit();
}    

    $html = "
    <p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>";

$html = $html. "
        <strong>          
        <p align='right'>Porto Alegre $data_cabecalho</p>
        </strong>

<p> Ofício nº ______ SSSMT/SSMR/Esc Pes <br>EB $eb</p>


<table border='0' style='width:100%; font-size: 12px; font-family: Times New Roman; text-align: justify;'>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Do</b> Presidente da Comissão de Designação MFDV</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ao</b> Sr Cmt /Ch / Dir do (a) ".$get_candidato[0]['nome_om_1_fase']."</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Assunto</b> Apresentação de MFDV para a Seleção Complementar para o Estágio de Adaptação e Serviço</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ref</b> - Decreto-Lei Nr 1.001, de 21 Out 69 - Código Penal Militar; <br>
        - R L MFDV; e <br>
        - Súmula 7 do Superior Tribunal Militar.<br>
        - Ordem de Serviço</th>
  </tr>
</table> 


        
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
1. Apresento a esse Comando o Sr ".$get_candidato[0]['nome_completo'].", $nome_especialidade_candidato, que após Seleção Especial de Médicos, Farmacêuticos, Dentistas e Veterinários (MFDV), está convocado à Incorporação para a prestação do Serviço Militar Obrigatório sob a forma da 1ª fase do Estágio de Adaptação e Serviço para o ano de " . date("Y") . " 
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	2. Comunico que as atividades de Seleção Complementar, Convocação à Incorporação, Incorporação e outras medidas administrativas estão reguladas em Ordem de Serviço específica.
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	3. Informo, também, que o $nome_especialidade_candidato convocado tem conhecimento de que, conforme previsto no Art 183 do Decreto-Lei Nr 1.001, de 21 de outubro de 1969 (Código Penal Militar), incorrerá no crime de “insubmissão”, caso deixe de apresentar-se à incorporação, dentro do prazo marcado, ou que, apresentando-se, ausente-se antes do ato oficial de incorporação. Adicionalmente, comunico que, conforme a Súmula 7 do Superior Tribunal Militar, incorrerá no mesmo crime citado (Insubmissão), caso não compareça à Seleção Complementar.
</p>


<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    4. A OM de 1ª Fase, após a realização da Seleção Complementar, deverá expedir Ofício convocando o $nome_especialidade_candidato para a sua Incorporação, informando a data, horário e local de apresentação para a referida Incorporação.
</p>


<br><br>
<p class='center' style='font-size: 12px;'><b> $presidente </b>
    <br>
Presidente da Comissão de Designação     
</p>


".$get_candidato[0]['nome_om_1_fase']." <br>
$endereco_om_1_fase <br>
CEP: $cep_om_1_fase <br>
Telefone: $telefone_om_1_fase <br>
<br>
<p style='font-size: 12px;'>

Eu ".$get_candidato[0]['nome_completo']." Recebi o ORIGINAL e declaro ter tomado ciência nesta data:______/______/______ <br><br>
    
CPF ________._________._________-_________
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Ass: _______________________________________________
</p>
" ;

$mpdf->WriteHTML($html);
$mpdf->AddPage();

$html = "
    <p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>";

$html = $html. "
        <strong>          
        <p align='right'>Porto Alegre $data_cabecalho</p>
        </strong>

<p> Ofício de Convocação nº  ______ SSSMT/SSMR/Esc Pes  <br> EB $eb</p>
    

<table border='0' style='width:100%; font-size: 12px; font-family: Times New Roman; text-align: justify;'>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Do</b> Presidente da Comissão de Designação MFDV</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ao</b>Sr ".$get_candidato[0]['nome_completo']."</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Assunto</b> Convocação à Incorporação para o Estágio de Adaptação e Serviço</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ref</b> - Decreto-Lei Nr 1.001, de 21 Out 69 - Código Penal Militar; <br>
        - R L MFDV; e <br>
        - Súmula 7 do Superior Tribunal Militar.<br>
        </th>
  </tr>
</table> 

        
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
1. Comunico que V Sa está convocado à Incorporação para a prestação do Serviço Militar Obrigatório sob a forma de Estágio de Adaptação e Serviço – EAS.
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	2. Dessa forma, informo que V Sa deverá comparecer para a Seleção Complementar, às $hora_apresentacao h, do dia $data_apresentacao, portando os seguintes documentos:<br>
	a. Original e cópia do diploma de graduação;<br>
	b. Original e cópia do documento militar, RG, CPF e comprovante de residência;<br>
	c. Declaração de tempo de serviço público anterior;<br>
	d. 6 (seis) fotos 3 X 4; e<br>
	e. Cópia do cabeçalho do extrato bancário de conta-corrente, para fins de recebimentos dos proventos.
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	3. Informo o seguinte local para a apresentação e realização da Seleção Complementar e Incorporação:<br>
	".$get_candidato[0]['nome_om_1_fase']." <br>
        $endereco_om_1_fase <br>
        CEP: $cep_om_1_fase <br>
        Telefone: $telefone_om_1_fase 
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	4. Informo, também, que incorrerá no crime de “insubmissão”, conforme previsto no Art 183 do Decreto-Lei Nr 1.001, de 21 de outubro de 1969 (Código Penal Militar), caso deixe de apresentar-se à incorporação, dentro do prazo marcado, ou que, apresentando-se, ausente-se antes do ato oficial de incorporação. Adicionalmente, comunico que, conforme a Súmula 7 do Superior Tribunal Militar, incorrerá no mesmo crime citado (Insubmissão), caso não compareça à Seleção Complementar.
</p>



<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    5. A sua Incorporação ocorrerá em ".$data_incorporacao.".
</p>




<br><br>
<p class='center' style='font-size: 12px;'><b> $presidente </b>
    <br>
Presidente da Comissão de Designação
</p>


<p style='font-size: 12px;'>

Eu ".$get_candidato[0]['nome_completo']." Recebi o ORIGINAL e declaro ter tomado ciência nesta data:______/______/______ <br><br>
    
CPF ________._________._________-_________
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Ass: _______________________________________________
</p>
" ;







$mpdf->WriteHTML($html);



$alteracoes_detalhadas = "endereco_om_1_fase: $endereco_om_1_fase |
                           cep_om_1_fase: $cep_om_1_fase  |  
                           telefone_om_1_fase: $telefone_om_1_fase | 
                           hora_apresentacao: $hora_apresentacao | 
                           presidente: $presidente | 
                           data_apresentacao: $data_apresentacao | 
                           id_candidato: $id_candidato | 
                           cpf_candidato: $cpf_candidato ";


$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20103", "relatorio", "create", "Gerou um ofício do médico obrigatório $cpf_candidato ", $alteracoes_detalhadas);

if($insere_log)
    $mpdf->Output("oficio_medico_obrigatorio_$cpf_candidato.pdf",'D');

exit();