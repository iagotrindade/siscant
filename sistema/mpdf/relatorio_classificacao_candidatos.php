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
          <th align='center'><strong>".$nome_selecao."</strong></th>
    </tr>
</table> 
<br>
<br>

 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong>Relatório de candidatos por classificação de pontos</strong>
  </tr>
  <tr>
    <th align='right'><strong> <p style='font-size: 12px; font-family: Times New Roman;'> Porto Alegre - ".$data_hoje."   </p></strong></th>
  </tr>
</table> 

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   O Comandante da 3ª Região Militar divulga a relação dos candidatos inscritos por ordem de classificação para $nome_selecao,
conforme anexo \"A\" (Calendário Geral de Atividades) do Aviso de Convocação.
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

A presente relação está em ordem de classificação.
</p>

" ;
    
$mpdf->WriteHTML($html);
//$mpdf->AddPage();

$html = "";


// <editor-fold defaultstate="collapsed" desc="Especialidades">

$lista_especialidades = $conexao->get_especialidade(); 
$contador = 1;
foreach ($lista_especialidades as &$especialidade) 
{
    if($especialidade['musica'] == 0)
    {
        $html = "";
        $html = $html . " <br>
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

        <tr style='background-color: #D8D8D8'>
            <td colspan=\"4\"> <center><b> ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
        </tr>";
    
        $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo($especialidade['id']);  
        

        $vetor_ordenado_candidatos = null;
        $vetor_ordenado_candidatos = array();

        foreach ($lista_candidatos_especialidade as $linha) 
        {
            ///////////////////////////////
            //  PONTUAÇÃO
            ///////////////////////////////

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


            ///////////////////////////////

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
            if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
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


        if(count($lista_candidatos_especialidade) > 0)
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

        if(count($lista_candidatos_especialidade) == 0)
        {
            $html = $html . "
            <tr>
                <td colspan='5'>
                    Nenhum candidato apto para esta especialidade.
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

        $mpdf->WriteHTML($html);

        //if($contador <= count($lista_especialidades))
        //    $mpdf->AddPage();

    }
}
    
// </editor-fold>    
    
    
// <editor-fold defaultstate="collapsed" desc="Especialidades MÚSICA">    
    
$contador = 1;

foreach ($lista_especialidades as &$especialidade) 
{    
    if($especialidade['musica'] == 1)
    {
        $html = "";
        $html = $html . " <br>
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

        <tr style='background-color: #D8D8D8'>
            <td colspan=\"4\"> <center><b> ".mb_strtoupper($especialidade['nome'],"UTF-8")."</b></center></td>
        </tr>";
    
       $lista_candidatos_especialidade = $conexao->get_candidatos_especialidade_concorrendo($especialidade['id']);
        
        
        
        $vetor_ordenado_candidatos = null;
        $vetor_ordenado_candidatos = array();

        foreach ($lista_candidatos_especialidade as $linha) 
        {
            ///////////////////////////////
            //  PONTUAÇÃO
            ///////////////////////////////

            // Currículo
            $pontuacao_curriculo = 0;
            $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$especialidade['id']);  
            if(count($get_pontuacao_avaliada) > 0)
                $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],2);
            
            // Provas
            $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'],$especialidade['id']);  

            $prova_pratica_musica = 0;
            $prova_escrita_musica = 0;
            $prova_oral_musica = 0;

            if(count($get_pontuacao_provas) > 0)
            {
                $prova_pratica_musica = $get_pontuacao_provas[0]['prova_pratica_musica'];
                $prova_escrita_musica = $get_pontuacao_provas[0]['prova_teorica_musica'];
                $prova_oral_musica = $get_pontuacao_provas[0]['prova_oral_musica'];
            }
            
            $somatorio_total_pontos_musica = 0;
            $somatorio_total_pontos_musica = (((($prova_escrita_musica*2) + ($prova_pratica_musica*2) + $prova_oral_musica)/5)+$pontuacao_curriculo)/2;
            $pontuacao_curriculo = round ($somatorio_total_pontos_musica,2);
            
            ///////////////////////////////

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
            if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
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
                "nome" => mb_strtoupper($linha['nome_completo'],"UTF-8"),
                "cpf" => $linha['cpf'],
                "pontos" => $pontuacao_curriculo,
                "pratica" => $prova_pratica_musica,
                "escrita" => $prova_escrita_musica,
                "oral" => $prova_oral_musica,
                "militar" => $militar,
                "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                "tempo_idade" => $tempo_total_idade_dias
            ];

            array_push($vetor_ordenado_candidatos,$novo_vetor);
        }

        if(count($lista_candidatos_especialidade) > 0)
        {
            $id_array = null;
            $nome_array = null;
            $pontos_array = null;
            $militar_array = null;
            $tempo_sv_pub = null;
            $tempo_idade = null;
            $prova_pratica_array = null;
            $prova_escrita_array = null;
            $prova_oral_array = null;

            foreach ($vetor_ordenado_candidatos as $index => $linha2) 
            {
                $id_array[$index]      = $linha2['id'];
                $nome_array[$index]    = $linha2['nome'];
                $pontos_array[$index]  = $linha2['pontos'];
                $prova_pratica_array[$index]  = $linha2['pratica'];
                $prova_escrita_array[$index]  = $linha2['escrita'];
                $prova_oral_array[$index]  = $linha2['oral'];
                $militar_array[$index] = $linha2['militar'];
                $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
                $tempo_idade[$index]   = $linha2['tempo_idade'];
            }

            array_multisort(
            $pontos_array,  SORT_DESC, 
            $prova_pratica_array,  SORT_DESC, 
            $prova_escrita_array,  SORT_DESC, 
            $militar_array, SORT_ASC, 
            $tempo_sv_pub, SORT_ASC, 
            $tempo_idade, SORT_DESC, 
            $vetor_ordenado_candidatos);
        }

        if(count($lista_candidatos_especialidade) == 0)
        {
            $html = $html . "
            <tr>
                <td colspan='5'>
                    Nenhum candidato apto para esta especialidade.
                </td>
            </tr>";
        }
        else
        {
            $html = $html . "

                <tr>
                    <td>
                        <b>LUGAR</b>
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
    
    
        $html = $html . "</table> <br>";

        $mpdf->WriteHTML($html);
        //$mpdf->AddPage();
    }
    
}

// </editor-fold>   


$mpdf->Output("classificacao_inscritos.pdf",'D');

exit();


