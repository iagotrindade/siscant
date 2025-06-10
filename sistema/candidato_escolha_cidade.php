<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

if($_SESSION['perfil'] != 'candidato')
{
    erro("Erro: 347356895465! Não foi possível abrir a página");
    exit();
}

if(!isset($_SESSION['candidato_etapa']) || $_SESSION['candidato_etapa'] < 4)
{
    erro("Erro: 568334767! Não foi possível abrir a página");
    exit();
}

$lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);

$datetime = date('d/m/Y H:i:s');

?>

<script type="text/javascript">
function selecao_cidade()
{
    if($('#cidade_escolheu').val() == '754809')
    {
        $("#mensagem_erro_cidade").text("Tenho ciência que a opção escolhida: Nenhuma das opções (Desistência) – acarretará na minha eliminação do processo seletivo, neste momento, portanto desisto da(s) vagas(s) ofertadas!");
    }
    else
    {
        $("#mensagem_erro_cidade").text("");
    }
}

    
</script>

<div class="content-wrapper">
    <div class="page-title">
      <div>
          <h1>Escolha de Guarnição</h1>
      </div>
      <div>
        <ul class="breadcrumb">
          <li><i class="fa fa-home fa-lg"></i></li>
          <li><a href="index.php">Página Inicial</a></li>
          <li>Cadastra especialidade</li>
        </ul>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12" >
            
            <div class="card">
                
                <legend> ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO</legend>
                
                <div class="alert-danger">
                    
                    <b>1</b>. Eu, <b><?php echo mb_strtoupper($_SESSION['nome_completo'] , 'UTF-8')?></b>, portador do CPF: <b><?php echo mascara($_SESSION['cpf'],'###.###.###-##') ?></b>, DECLARO ter tomado conhecimento das orientações a respeito da ESCOLHA DE GUARNIÇÃO.
		<br><b>2</b>. COMPREENDO que será disponibilizado aos candidatos melhores classificados o universo de GUARNIÇÕES OFERTADAS abaixo, de acordo com a necessidade da Administração Militar.
		<br><b>3</b>. COMPREENDO que é facultado ao candidato o direito de selecionar: “Nenhuma das Opções (Desistência das localidades ofertadas)”. 
		<br><b>4</b>. COMPREENDO que as vagas serão esgotadas à medida que os candidatos melhores pontuados efetuam suas escolhas, até restar uma vaga para o seguinte  candidato melhor pontuado.
		<br><b>5</b>. COMPREENDO que caso o candidato não efetue o procedimento de ESCOLHA DE GUARNIÇÃO dentro do prazo previsto no cronograma, o candidato será considerado DESISTENTE e consequentemente ELIMINADO do certame, para que demais candidatos efetuem o procedimento de ESCOLHA DE GUARNIÇÃO.
<br><br>

<center>
<b>
<?php echo mb_strtoupper($_SESSION['nome_completo'] , 'UTF-8')?>
</b>  

<br>

<?php echo "<b>CPF</b>: " . mascara($_SESSION['cpf'],'###.###.###-##') . " <br><b>DATA</b>: " . $datetime ?> 
<br>
<?php echo mb_strtoupper($_SESSION['assinatura_sistema'] , 'UTF-8')?>

		
		
   
</center>
                

                    
                    </div>
                
            </div>
            
        <div class="card">
            <legend>Minhas inscrições no processo seletivo</legend>
            
            <div class="row">
                <div class="col-lg-12">
                    
                    <?php
                    
                        
                        foreach ($lista_inscricoes as $value) 
                        {
                            
                            //if($value['concorrendo'] == 0) continue;
                            
                            $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'],$value['id_especialidade']);
                            $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);
                            
                            
                            $crip = hash('sha256', $_SESSION['chave']."freitas".$value['id_especialidade']);
                            
                            $ott_stt = null;
                            
                            if($value['ott_stt'] == 'ott')
                                $ott_stt = "Oficial Técnico Temporário - OTT";
                            if($value['ott_stt'] == 'stt')
                                $ott_stt = "Sargento Técnico Temporário - STT";
                            if($value['ott_stt'] == 'medico')
                                $ott_stt = "Médico";
                            if($value['ott_stt'] == 'dentista')
                                $ott_stt = "Dentista";
                            if($value['ott_stt'] == 'veterinario')
                                $ott_stt = "Veterinário";
                            if($value['ott_stt'] == 'farmaceutico')
                                $ott_stt = "Farmacêutico";
                            
                            
                            
                            
$cor_retangulo = "success";
if(!empty($value['cidade_escolheu_servir']) || $value['concorrendo'] == 0) $cor_retangulo = "info";
                            
                            echo '
                                

<div class="alert-'.$cor_retangulo.'">
<b>
<font size=3>
    '.$ott_stt.'  - '.mb_strtoupper($value['especialidade'], 'UTF-8').'
</b>
</font>

';
                            
if($value['concorrendo'] == 0) echo '<br><font color="red"><b> DESCLASSIFICADO: </b> '.$value['justificativa'].' </font>';


$lista_epecialidades = $conexao->get_cidades_especialidade($value['id_especialidade']); 
$get_vagas_especialidade = $conexao->get_vagas_especialidade($value['id_especialidade']);

echo '
<br>
<br>

<b>VAGAS:</b>
<table style="width:100%" border=1>
  <tr>
    <th>Total disponibilizadas</th>
    <th>Restantes</th>
  </tr>
  <tr>
    <td>
';
                            
                                                    


foreach ($lista_epecialidades as $linha) 
{
    $vagas = 0;
    if((int)$linha['numero_vagas'] > 0) $vagas = $linha['numero_vagas'];
        echo '
        <b>Guarnição: </b>'.$linha['nome'].'
        <b> - Vagas:</b> '.$vagas.'</br>';
}

echo '</td>
    <td>
    
';

foreach ($get_vagas_especialidade as $vaga)
{
    $vagas = 0;
    if((int)$vaga['vagas'] > 0) $vagas = $vaga['vagas'];
    echo '
        <b>Guarnição: </b>'.$vaga['cidade'].'
        <b> - Vagas:</b> '.$vagas.'</br>';
}

echo '  
    </td>
</tr>
</table> ';
                            

if($value['cidade_escolheu_servir'] != null)
{
    $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
    if($get_cidade_escolhida[0]['nome'] != null)
    echo '
            <br><font size=3 color="black"><b> Guarnição escolhida para servir: </b> '.$get_cidade_escolhida[0]['nome'].' </font>
        ';
}


$tem_vaga_ = false;
foreach ($get_vagas_especialidade as $vaga)
{
    if((int)$vaga['vagas'] > 0) $tem_vaga_ = true;
}

$pode_selecionar = true;
if(!seleciona_cidade_vai_servir()) $pode_selecionar = false;
                            
if($value['concorrendo'] == 1 && $value['cidade_escolheu_servir'] == null && $tem_vaga_ && $pode_selecionar)
{
    
    $crip= hash('sha256', $value['id_especialidade']."escolhe_cidade");
    
    echo'
        <form action="../banco_dados/candidato_cidade_escolheu_servir.php" method="post"">
                <br>
                
                <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                <option value="">Selecione a Cidade em que deseja servir</option>
        ';
    
    foreach ($get_vagas_especialidade as $vaga)
    {
        if((int)$vaga['vagas'] > 0)
        echo '<option value="'.$vaga['id_cidade'].'">'.$vaga['cidade'].' ('.$vaga['vagas'].')</option>';
    }
   
        echo ' 
                <option value="754809">Nenhuma das opções (Desistência)</option>
                </select>
                <br>

<label>
                        <input type="checkbox" id="declaracao" name="declaracao">
                        <span class="label-text">Declaro que li o aviso ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO.</span>
                    </label>

                <br>
                    <input type="text" hidden name="crip" value="'.$crip.'">
                    <input type="text" hidden name="id_especialidade" value="'.$value['id_especialidade'].'">

<b><font color="red" size="4"><p id="mensagem_erro_cidade"></p></font></b>

                    <button  type="submit"  class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                    <br>

        </form>
        
        ';
}


echo '
</div>
<br>';











/*

echo '<table width="100%" border ="0" class="alert alert-'.$cor_retangulo.'">
    <tr>
        <td width="35%"><b><u>'.mb_strtoupper($value['especialidade'], 'UTF-8').' </u></b> <br></td>
        <td><b>'.$ott_stt.'</b> <br></td>';

        if(inscricao()) echo '
        <td rowspan="3" width=\'70px\'><a onclick="funcao_apagar(\''.$value['id_especialidade'].'\', \'candidato_especialidade\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';                                        
        echo '
        <td rowspan="3" width=\'70px\'><a href="candidato_especialidade_cadastrada_visualiza.php?esp='.$value['id_especialidade'].'" ><img title="Detalhamento da especialidade" src="imagens/lupa.png" width="30px"></a></td>
    </tr>

    <tr>
        <td width="35%"><b>Arquivos Adicionados: </b> '.$quantidade_curriculo_adicionado.' <br></td>
        <td><b>Registro no Conselho: </b> '.$value['registro_conselho'].' <br></td>
    </tr>
'; 

if($value['cidade_escolheu_servir'] != null)
{
$get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
if($get_cidade_escolhida[0]['nome'] != null)
echo '
    <tr>
        <td colspan="2"><font color="black"><b> Cidade Escolhida para servir: </b> '.$get_cidade_escolhida[0]['nome'].' </font></td>
    </tr>
    ';
}

    
$get_vagas_especialidade = $conexao->get_vagas_especialidade($value['id_especialidade']);

$tem_vaga_ = false;
foreach ($get_vagas_especialidade as $vaga)
{
    if((int)$vaga['vagas'] > 0) $tem_vaga_ = true;
}
                            
if($value['concorrendo'] == 1 && $value['cidade_escolheu_servir'] == null && $tem_vaga_)
{
    
    $crip= hash('sha256', $value['id_especialidade']."escolhe_cidade");
    
    $pode_selecionar = '';
    if(!seleciona_cidade_vai_servir()) $pode_selecionar = ' hidden ';
    
    echo'<tr '.$pode_selecionar.'>
        <form action="../banco_dados/candidato_cidade_escolheu_servir.php" method="post"">
            <td>
                <br>
                
                <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                <option value="">Selecione a Cidade em que deseja servir</option>

';
    
    foreach ($get_vagas_especialidade as $vaga)
    {
        if((int)$vaga['vagas'] > 0)
        echo '<option value="'.$vaga['id_cidade'].'">'.$vaga['cidade'].'</option>';
    }
   
        echo ' 
                <option value="754809">Nenhuma das opções (Desistência)</option>
                </select>
                <br>
            </td>
                <td>
                    <br>
                    <input type="text" hidden name="crip" value="'.$crip.'">
                    <input type="text" hidden name="id_especialidade" value="'.$value['id_especialidade'].'">
                    <button  type="submit"  class="btn btn-primary btn-block">ENVIAR OPÇÃO (Única vez)</button>
                    <br>
                </td>

                

        </form>
        </tr>
        
        <tr>
            <td colspan="2">
                <b><font color="red" size="5"><p id="mensagem_erro_cidade"></p></font></b>
            </td>
        </tr>
        
        ';
}
                            
                                echo '</table>';
                        
                                
                                
             
                                
         */                       
                                
                                
                                
    }

                    ?>
                  
                </div>
            </div>
        </div>
            <a name="fim_pagina"></a>
            
            <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
    </div>
</div>
</div>
</body>
</html>
<?php $conexao = null; ?>