<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "avaliador" && $_SESSION['perfil'] != "consulta")
    {
        erro("Erro 23523523543! Página não encontrada!");
        exit();
    }
    
    $avaliador = false;
    if($_SESSION['perfil'] == "avaliador")
        $avaliador = true;
    
    $id_especialidade_selecionada = 0;
    $select_nota_prova_pratica_teorica = 1;
    
    if(isset($_GET['select_nota_prova_pratica_teorica']) && $_GET['select_nota_prova_pratica_teorica'] == '0')
        $select_nota_prova_pratica_teorica = 0;
    
    if(isset($_GET['id_especialidade']))
        $id_especialidade_selecionada = $_GET['id_especialidade'];
    
    $avaliador_pode_avaliar_id_especialidade = false;
    if($avaliador)
    {
        $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
        
        foreach ($lista_especialidade_avaliador as $linha_avaliador) 
        {
            if($linha_avaliador['id_especialidade'] == $id_especialidade_selecionada)
                $avaliador_pode_avaliar_id_especialidade = true;
        }
        if(!$avaliador_pode_avaliar_id_especialidade && $id_especialidade_selecionada != 0)
        {
            erro("Erro 48923543! Você não tem permissão para avaliar essa especialidade!");
            exit();
        }
    }
    
    $lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);  
    
    
    $voluntario_obrigatorio = '';
    if(isset($_GET['voluntario_obrigatorio']))
        $voluntario_obrigatorio = $_GET['voluntario_obrigatorio'];
    
    $especialidade_medico = false;
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatorio Candidatos Especialidade <i class="fa fa-file-text"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatorio Candidatos Especialidade</li>
      </ul>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-12">
        <form name="fomulario" action="relatorio_especialidade_candidato.php" method="get">
            <div class="card">
                <div class="card-body">
                    <label>Selecione a especialidade desejada </label>
                    
                    <select onchange="fomulario.submit()" name="id_especialidade" class="form-control" >
                        <option value="">Selecione a especialidade</option>
                        <?php
                        
                            if($avaliador)
                            {
                                foreach ($lista_especialidade_avaliador as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id_especialidade'])
                                        echo '<option selected value="'.$value['id_especialidade'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id_especialidade'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            }
                            else
                            {
                                $resultado = $conexao->get_especialidade(); 
                                foreach ($resultado as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            }
                        ?>
                    </select>
                    
                    <?php 
                        if($_SESSION['perfil'] == 'admin')
                        {
                    ?>                            
                    <br>
                    <select onchange="fomulario.submit()" name="select_nota_prova_pratica_teorica" class="form-control" >
                            <option value="1">Considerar as notas da prova Teórica/Prática</option>;
                            <option <?php if($select_nota_prova_pratica_teorica == '0') echo " selected " ?> value="0">NÃO considerar as notas da prova Teórica/Prática</option>;
                    </select>
                     <?php 
                        }
                    ?>
                    
                    <?php
                    
                        if($id_especialidade_selecionada != 0)
                        {
                            $get_especialidade_selecionada = $conexao->get_especialidade_id($id_especialidade_selecionada);  
                            if($get_especialidade_selecionada[0]['ott_stt'] == 'medico' || $get_especialidade_selecionada[0]['ott_stt'] == 'dentista')
                            {
                               $especialidade_medico = true;
                            }
                        }

                        if($especialidade_medico == false)
                            $voluntario_obrigatorio = "";
                    
                    ?>
                    
                    <br>
                    <div <?php if($especialidade_medico == false) echo 'hidden';?>>
                        <label>Selecione volunarios ou/e obrigatórios </label>
                            <select onchange="fomulario.submit()" name="voluntario_obrigatorio" class="form-control">
                                <option value="">Selecione a opção</option>                                
                                <option <?php if($voluntario_obrigatorio == 'voluntario')             echo 'selected'; ?> value="voluntario">Somente voluntários</option>                                
                                <option <?php if($voluntario_obrigatorio == 'obrigatorio')            echo 'selected';?>  value="obrigatorio">Somente obrigatórios</option>
                                <option <?php if($voluntario_obrigatorio == 'voluntario_obrigatorio') echo 'selected'; ?> value="voluntario_obrigatorio">Obrigatórios e voluntários</option>
                            </select> 
                    </div>
                </div>
            </div>
            </form>
        
    <?php

        ////////////////////////////
        // Critérios de desempate
        ////////////////////////////
        if($id_especialidade_selecionada != 0)
        {
            
            if(count($get_especialidade_selecionada) > 0 && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta'))
            {
                if($get_especialidade_selecionada[0]['musica'] == 1)
                    include_once 'codigos/criterios_desempate_musica.php';
                //else if(!$especialidade_medico)
                else
                    include_once 'codigos/criterios_desempate_ott_stt.php';
                
                //if($especialidade_medico)
                    //include_once 'codigos/criterios_desempate_ott_stt_medicos.php';
            }
        }
    
    ?>  
        
    <?php

        ////////////////////////////
        // Critérios de desempate com mais colunas
        ////////////////////////////
        if($id_especialidade_selecionada != 0)
        {
            
            if(count($get_especialidade_selecionada) > 0 && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta'))
            {
                if($get_especialidade_selecionada[0]['musica'] != 1)
                {
                    include_once 'codigos/criterios_desempate_ott_stt3.php';
                    //include_once 'codigos/criterios_desempate_ott_stt2.php';
                }
            }
        }
    
    ?>
        
        
    <div class="card">
        
        <legend>Candidatos da especialidade selecionada <a <?php if($perfil != "admin" && $perfil != "consulta") echo " hidden " ?> href="excel_candidatos_especialidade.php?id_especialidade=<?php echo $id_especialidade_selecionada?>"><img src="imagens/ods.png" width="30px"></a></legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Pagou?</th>
                      <th>Parecer</th>
                      <th>Grupo</th>
                      <th>Data Ex médico</th>
                      <th>Pts Prova</th>
                      <th>Adicionado</th>
                      <th>Validado</th>
                      <th>% Avaliado</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_candidatos as $linha) 
                        {
                                                        
                            if($voluntario_obrigatorio == 'voluntario' && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1 )) continue;
                            if($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;
                            
                            $get_pagamento_candidato = $conexao->get_arquivo_pagamento($linha['id']); 
                            
                            $pagou = "";
                            if(count($get_pagamento_candidato) > 0)
                                $pagou = "_Sim";
                            else $pagou = "_Não";
                            
                            $curriculos_candidato = $conexao->get_avaliado_especialidade($linha['id'],$id_especialidade_selecionada);  
                            
                            $quantidade_validado = 0;
                            
                            $quantidade_total = count($curriculos_candidato);
                            
                            foreach ($curriculos_candidato as &$curriculo_candidato)
                            {
                                if($curriculo_candidato['valido'] != null)
                                    $quantidade_validado++;
                            }
                            
                            $porcentagem_validacao = 0;
                            if($quantidade_validado != 0 && $quantidade_total != 0)
                                $porcentagem_validacao = ($quantidade_validado / ($quantidade_total)) * 100;
                            
                            $cor = null;
                            
                            if($porcentagem_validacao < 100)
                                $cor = '#fefe85';
                            
                            if($porcentagem_validacao < 60)
                                $cor = '#ffa74f';
                            
                            if($porcentagem_validacao < 30)
                                $cor = '#fd8a8a';
                            
                            if($porcentagem_validacao == 100)
                                $cor = '#adf54d';
                            
                            if($quantidade_total == 0)
                            {
                                $porcentagem_validacao = null;
                                $cor = null;
                            }
                            
                            $pontuacao_avaliada = null;
                            $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$id_especialidade_selecionada);  
                            
                            if(count($get_pontuacao_avaliada)>0)
                                $pontuacao_avaliada = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],2);

                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $data_ex_saude = $linha['data_exame_saude'];
                            if($data_ex_saude != null) $data_ex_saude = trata_data($data_ex_saude);
                            
                            $apto = "não_feito";
                            if($linha['apto_saude'] == '1') $apto = "APTO";
                            if($linha['apto_saude'] == '0') $apto = "INAPTO";
                            
                                echo '
                                <tr>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$pagou.'</td>
                                <td>'.$apto.'</td>
                                <td>'.strtoupper($linha['grupo_saude']).'</td>
                                <td>'.$data_ex_saude.'</td>
                                <td>'.$linha['nota_prova_teorico_pratico'].'</td>
                                <td>'.$quantidade_total.'</td>
                                <td>'.$quantidade_validado.'</td>
                                <td bgcolor="'.$cor.'">'.round($porcentagem_validacao).'%</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
    <div class="card">
        <legend><font color="red">Candidatos desclassificados da especialidade com justificativa</font></legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica44">
                <thead>
                    <tr>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Justificativa</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $lista_candidatos_desclassificados = $conexao->get_candidatos_desclassificados_especialidade($id_especialidade_selecionada);  
                        
                        foreach ($lista_candidatos_desclassificados as $linha) 
                        {
                            if($voluntario_obrigatorio == 'voluntario' && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1 )) continue;
                            if($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;
                            
                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                                echo '
                                <tr>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['justificativa_ce'].'</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                </tr>';
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
<script type="text/javascript">$('#tabela_dinamica4').DataTable({"ordering": false});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"ordering": false});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"ordering": false});</script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 5, "asc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica44').DataTable({"order": [[ 0, "asc" ]]});</script>

</body>
</html>
<?php $conexao = null; ?>