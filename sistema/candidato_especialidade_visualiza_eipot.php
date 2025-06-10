<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

    
   $id_selecao = $_SESSION['selecao'];
   $id_candidato = $_SESSION['id_usuario']; 
   $arma_eipot = $conexao->getArmaEipot($id_candidato);
 //  var_dump($arma_eipot);
   $especialidades_armas_eipot = $conexao->getEspecialidadesPorSelecao($id_selecao);
 //  var_dump($especialidades_armas_eipot); exit;
  
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
                <form action="../banco_dados/candidato_cadastra_especialidade_eipot.php" method="post">
                  <legend>Selecione as opções para dar continuidade</legend> 
                    <div class="row" >
                        
                    <div class="form-group col-lg-6" id="div_ott_stt">
                        <label>Selecione o tipo da especialidade</label>
                        <select name="id_especialidade" class="form-control">
                            <option value="">Selecione a opção</option>
                            <?php  
                            foreach($especialidades_armas_eipot as $especialidade) {
                                echo '<option value="' . $especialidade['id'] . '" name="id_especialidade">' . $especialidade['nome'] . '</option>';
                            }
                            ?>
                        </select>
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
            <legend>Minhas inscrições no processo seletivo   </legend>
            
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
                            if($value['ott_stt'] == "eipot ")
                                $ott_stt = "EIPOT";
                        
                            
                            echo '
                                <table width="100%" border ="0" class="alert alert-success">
                                    <tr>
                                        <td width="35%"><b><u>'.mb_strtoupper($value['especialidade'], 'UTF-8').' </u></b> <br></td>';
                                      
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