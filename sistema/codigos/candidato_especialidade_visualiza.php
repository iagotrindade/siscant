<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';





?>

<script type="text/javascript">
function selecao_cidade()
{
    if($('#cidade_escolheu').val() == '754809')
    {
        $("#mensagem_erro_cidade").text("Tem ciência que a opção escolhida: Nenhuma das opções (Desistência) - o eliminará do processo seletivo, neste momento, sendo que o senhor(a) desistiu da(s) vaga(s) ofertada(s)!");
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
          <h1>Cadastrar uma especialidade <i class="fa fa-wrench"></i></h1>
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
            <?php if(!inscricao()) echo " <font color = 'red' size='5px'> INSCRIÇÕES ENCERRADAS </font> " ?>
            <div class="card" <?php if(!inscricao()) echo ' hidden ' ?>>
                <form action="../banco_dados/candidato_cadastra_especialidade.php" method="post" onsubmit="return verifica_cadastro_especialidade_candidato()">
                  <legend>Selecione as opções para dar continuidade</legend> 
                    <div class="row" >
                        
                        <div class="form-group col-lg-6" id="div_ott_stt">
                            <label>Selecione o tipo da especialidade</label>
                            <select id="ott_stt" name="ott_stt" class="form-control" onchange="busca_ott_stt()">
                                <option value="">Selecione a opção</option>
                                
                                <?php

                                    if($codigo_selecao == 'mfdv')
                                    echo "teste5";
                                    echo '<option value="medico">Médico</option>
                                        <option value="farmaceutico">Farmacêutico</option>
                                        <option value="dentista">Dentista</option>
                                        <option value="veterinario">Veterinário</option>';

                                    if($codigo_selecao == 'ott_stt')
                                        echo '<option value="ott">OTT</option>
                                        <option value="stt">STT</option>'; 

                                    if($codigo_selecao == 'eipot')
                                    echo '<option value="ott">OTT</option>
                                    <option value="stt">STT</option>'; 
                                    
                                    if($codigo_selecao == 'cet')
                                        echo '<option value="cet">CET</option>';
                                    
                                    if($codigo_selecao == 'ottm')
                                        echo '<option value="ottm">OTTM</option>';
                                    
                                   
                                ?>
                                
                            </select>
                        </div>
                        
                        
                        <div class="form-group col-lg-6">
                            <label>Registro no Conselho Regional</label>
                            <input id="registro_conselho" maxlength="40" name="registro_conselho" class="form-control">
                        </div>
                        
                        
                        <div class="form-group col-lg-6" id="div_especialidade" >
                            <label>Selecione a especialidade?</label>
                            <select id="especialidade" name="especialidade" class="form-control">
                                <?php
                                
                                    if($codigo_selecao == 'mfdv')
                                        echo '<option value="mfdv">Primeiro selecione o tipo da especialidade</option>';
                                    else
                                        echo '<option value="">Primeiramente selecione se a especialidade é OTT ou STT</option>';
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Data que habilita a concorrer na especialidade (Conclusão de curso que habilita)</label>
                            <input  maxlength="40" name="data_habilitacao" class="form-control">
                        </div>
                        
                    </div>
                  
                    <div class="row" id="div_mensagem_erro" hidden>
                        <div class="form-group col-lg-12">
                            <font color="red"><b><center><p id="mensagem_erro">Selecione uma opção!</p></b></center></font>
                        </div>
                    </div>
                  <input hidden value="<?php echo hash('sha256', $_SESSION['chave']."freitas") ?>" name="crip" >      
                  <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR</button>
                </form>
                
            </div>
            
            
        <div class="card">
            <legend>Minhas inscrições no processo seletivo   <?php if(inscricao()) echo '<font color="red" size="3px"><b> ! Entre em cada especialidade para cadastrar os documentos que pontuam ! </b></font>' ?> </legend>
            
            <div class="row">
                <div class="col-lg-12">
                    
                    <?php
                    
                        $lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
                        foreach ($lista_inscricoes as $value) 
                        {
                            
                            $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'],$value['id_especialidade']);
                            $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);
                            
                            $pontuacao_final = 0; 
                            
                            foreach ($lista_docs_obrigatorios as $curriculo) 
                            {
                                $data_inicio_original = null;
                                $data_fim_original = null;
                                $total_de_dias = null;
                                $pontuacao = null;
                                    
                                if($curriculo['carga_horaria_obrigatoria'] == '1')
                                {
                                    if($curriculo['data_inicio'] != null)
                                        $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                    if($curriculo['data_termino'] != null)
                                        $data_fim_original = reverte_data($curriculo['data_termino']);

                                    $data_inicio = new DateTime(date($data_inicio_original));
                                    $data_fim = new DateTime(date($data_fim_original));
                                    $intervalo = $data_fim->diff($data_inicio);

                                    $total_de_dias = (int)$intervalo->format('%a');

                                    $pontuacao = ($curriculo['pontuacao'] * $total_de_dias)/1000;
                                }
                                else
                                {
                                    $pontuacao = $curriculo['pontuacao']/1000;
                                }

                                $pontuacao_final = $pontuacao_final + $pontuacao;
                            }
                            
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
                            
                            echo '
                                <table width="100%" border ="0" class="alert alert-success">
                                    <tr>
                                        <td width="35%"><b><u>'.mb_strtoupper($value['especialidade'], 'UTF-8').' </u></b> <br></td>
                                        <td><b>'.$ott_stt.'</b> <br></td>';
                            
                                        if($_SESSION['selecao_regiao'] == 12 || $_SESSION['selecao_regiao'] == 7)
                                            echo '<td rowspan="3" width=\'300px\'> <b>Pontuação gerada automaticamente: </b>'.$pontuacao_final.' </td>';
                            
                                        if(inscricao()) 
                                            echo  ' <td rowspan="3" width=\'70px\'><b> <a onclick="funcao_apagar(\''.$value['id_especialidade'].'\', \'candidato_especialidade\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';                                        
                                        echo '<td rowspan="3" width=\'70px\'><a href="candidato_especialidade_cadastrada_visualiza.php?esp='.$value['id_especialidade'].'" ><img title="Detalhamento da especialidade" src="imagens/lupa.png" width="30px"></a></td>';
                                    echo '</tr>

                                    <tr>
                                        <td width="35%"><b>Arquivos Adicionados: </b> '.$quantidade_curriculo_adicionado.' <br></td>
                                        <td><b>Registro no Conselho: </b> '.$value['registro_conselho'].' <br></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">'; 
                                            if($quantidade_curriculo_adicionado == 0 && inscricao()) 
                                                echo ' <a href="candidato_especialidade_cadastrada_visualiza.php?esp='.$value['id_especialidade'].'"> <img src="imagens/urgente.gif" height="35px"> <font color="red"> Clique aqui para adicionar documentos de currículo para sua pontuação e classificação!</font> <img src="imagens/urgente.gif" height="35px"> </a>'; 
                                            if(inscricao() && $quantidade_curriculo_adicionado > 0)
                                                echo '<a href="candidato_especialidade_cadastrada_visualiza.php?esp='.$value['id_especialidade'].'">Clique aqui para adicionar MAIS documentos de currículo!</a>'; 
                            echo'</td>
                                    </tr>'; 
                            
                            if($value['concorrendo'] == 0)
                            {
                                echo '<tr>
                                        <td colspan="2"><font color="red"><b> DESCLASSIFICADO: </b> '.$value['justificativa'].' </font></td>
                                    </tr>';
                            }
                            
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

/*    
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

*/
                            
                                echo '</table>';
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