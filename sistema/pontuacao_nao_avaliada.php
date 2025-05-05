<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if(($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) || $perfil != 'admin' && $perfil != 'consulta' && $perfil != 'avaliador')
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    $lista_curriculos = $conexao->get_curriculo_cadastrados(); 
    
    $curriculo_selecionado = null;
    if(isset($_GET['curriculo_selecionado']))
        $curriculo_selecionado = (int)$_GET['curriculo_selecionado'];
    
    $lista_especialidade_avaliador = null;
    if($perfil == 'avaliador')
        $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>TODOS os Currículos<i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>TODOS os Currículos</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        <form name="fomulario" action="pontuacao_nao_avaliada.php" method="get">
            <div class="card">
                <div class="card-body">
                    
                    
                        <label>Selecione o currículo</label>
                            <select onchange="fomulario.submit()" name="curriculo_selecionado" class="form-control">
                                <option value="">Selecione um currículo</option>
                                <?php
                                   
                                    foreach ($lista_curriculos as $linha) 
                                    {
                                        if($curriculo_selecionado == $linha['id'])
                                            echo '<option selected value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                        else
                                            echo '<option  value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                    }
                                    
                                    if($curriculo_selecionado === 0)
                                        echo '<option selected value="0">TODOS</option>';
                                    else
                                        echo '<option value="0">TODOS</option>';
                                ?>
                            </select> 
                    
                </div>
            </div>
        </form>
        
        
    <div class="card">
        <legend>TODOS os currículos dos candidados que estão CONCORRENDO --> Download <a href="excel_pontuacao_nao_avalida.php"><img src="imagens/ods.png" width="30px"></a> <--
        </legend>
        
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <!--
                        <th>Dt Nasc</th>
                        <th>Temp Sv Mil</th>
                      -->
                      <th>Especialidade</th>
                      <th width="300px">Currículo</th>
                      <th>Início</th>
                      <th>Fim</th>
                      <th>Diferença</th>
                      <th>Multiplicado</th>
                      <th>Suspeito</th>
                      <th>Validado</th>
                      <th>Justificativa</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $lista_candidatos = array();
                        if($curriculo_selecionado != null || $curriculo_selecionado === 0)
                            $lista_candidatos = $conexao->get_todos_candidatos();
                    
                        foreach ($lista_candidatos as $linha) 
                        {
                            $id_usuario = $linha['id'];
                            
                            $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                            $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                            $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];
                                                        
                            if($dias_sv_militar >=30)
                            {
                                $meses_sv_militar ++;
                                $dias_sv_militar = $dias_sv_militar -30;
                            }
                            
                            if($meses_sv_militar >= 12)
                            {
                                $total_anos_sv_publico ++;
                                $meses_sv_militar = $meses_sv_militar -12;
                            }
                            
                            $data_nascimento = trata_data($linha['data_nascimento']);
                            
                            $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                            foreach ($inscricoes as $valor)
                            {
                                ////////////////////////////////////////////////////////////////////////////////////////////////////
                                // Verifica se avaliador pode ver a especialidade
                                $avaliador_pode_ver_especialidade = false;
                                if($lista_especialidade_avaliador != null)
                                {
                                    if($valor['concorrendo'] == 0) continue;
                                    foreach ($lista_especialidade_avaliador as $linha_avaliador) 
                                    {
                                        if($linha_avaliador['id_especialidade'] == $valor['id_especialidade'])
                                            $avaliador_pode_ver_especialidade = true;
                                    }
                                }
                                if($lista_especialidade_avaliador != null && !$avaliador_pode_ver_especialidade) continue;
                                ////////////////////////////////////////////////////////////////////////////////////////////////////
                                        
                                $id_especialidade = $valor['id_especialidade'];
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
                                $pontuacao_total = 0;
                                
                                $concorrendo = null;
                                if($valor['concorrendo'] != null)
                                {
                                    if($valor['concorrendo'] == '1') $concorrendo = "<font color='green'>Concorrendo</font>";
                                    if($valor['concorrendo']  == '0') $concorrendo = "<font color='red'>Desclassificado</font>";
                                }
                                $aux_id_curriculo = null;
                                $data_inicio_aux = null;
                                $data_fim_aux = null;
                                foreach ($lista_curriculo_adicionado as $cirriculo) 
                                {
                                    
                                    if($curriculo_selecionado > 0)
                                    {
                                        if($curriculo_selecionado != $cirriculo['id_curriculo']) continue;
                                    }
                                    
                                    $pontuacao = (int)$cirriculo['pontuacao']/1000;
                                    
                                    
                                    /////////////////////////////////////////
                                    // Diferença de data
                                    $data_inicio = reverte_data($cirriculo['data_inicio']);
                                    $data_fim = reverte_data($cirriculo['data_termino']);

                                    $data_inicio = new DateTime(date($data_inicio));
                                    $data_fim = new DateTime(date($data_fim));
                                    $intervalo = $data_fim->diff($data_inicio);

                                    $anos = (int)$intervalo->format('%Y');
                                    $meses = (int)$intervalo->format('%m');
                                    $dias = (int)$intervalo->format('%d');

                                    $total_dias = null;
                                    if($data_inicio != null && $data_fim != null)
                                    {
                                        $dias_anos = $anos*365;
                                        $dias_meses = $meses*30;
                                        $total_dias = $dias_anos + $dias_meses + $dias +1;
                                    }
                                    $total_meses = $total_dias/30;
                                    $total_meses = round($total_meses,2);
                                    /////////////////////////////////////////
                                    
                                    
                                    
                                    $dt_inicio = null;
                                    if($cirriculo['data_inicio'] != null)
                                        $dt_inicio = trata_data($cirriculo['data_inicio']);
                                    $dt_fim = null;
                                    if($cirriculo['data_termino'] != null)
                                        $dt_fim = trata_data ($cirriculo['data_termino']);
                                    
                                    $multiplicador = $cirriculo['multiplicador'];
                                    
                                    $validado = null;
                                    if($cirriculo['valido'] == '1') $validado = "_Sim";
                                    if($cirriculo['valido'] == '0') $validado = "_Não";
                                    
                                    $data_inicio = null;
                                    if(!empty($cirriculo['data_inicio']))
                                        $data_inicio = trata_data ($cirriculo['data_inicio']);
                                    $data_termino = null;
                                    if(!empty($cirriculo['data_termino']))
                                        $data_termino = trata_data ($cirriculo['data_termino']);
                                    
                                    $suspeito = "*Não";
                                    $color = null;
                                    
                                    if($aux_id_curriculo == $cirriculo['id_curriculo'])
                                    {
                                        if((strtotime($data_inicio_aux) <= strtotime($cirriculo['data_termino'])) && (strtotime($data_fim_aux) > strtotime($cirriculo['data_inicio'])) && $cirriculo['valido'] == '1')
                                        {
                                            $suspeito = "*Sim";
                                            $color = "red";
                                        }
                                    }
                                    
                                    $data_inicio_aux = $cirriculo['data_inicio'];
                                    $data_fim_aux = $cirriculo['data_termino'];
                                    $aux_id_curriculo = $cirriculo['id_curriculo'];
                                    
                                    $cor_meses = null;
                                    if((int)$multiplicador > (int)$total_meses+1 && $cirriculo['valido'] == '1')
                                    {
                                        $cor_meses = "red";
                                        $suspeito = "*Sim";
                                    }
                                    
                                    echo '
                                    <tr>
                                        <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                        <td>'.$linha['nome_completo'].'</td>'
                                        //<td>'.$data_nascimento.'</td>
                                        //<td>A:'.$anos_sv_militar.', M:'.$meses_sv_militar.', D:'.$dias_sv_militar.'</td>
                                        .'
                                        <td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].' | '.$concorrendo.'_</td>
                                        <td>'.$cirriculo['nome_curriculo'].'</td>
                                        <td bgcolor="'.$color.'">'.$data_inicio.'</td>
                                        <td>'.$data_termino.'</td>
                                        <td>'.$total_meses.'</td>
                                        <td bgcolor="'.$cor_meses.'">'.$multiplicador.'</td>
                                        <td>'.$suspeito.'</td>
                                        <td>'.$validado.'</td>
                                        <td>'.$cirriculo['justificativa'].'</td>
                                    </tr>';
                                }
                                
                            }
                            
                            
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 2, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>