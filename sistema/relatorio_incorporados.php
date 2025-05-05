<?php
    include_once 'menu.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta")
    {
        erro("Erro 56856737! Página não encontrada!");
        exit();
    }
    
    $id_especialidade_selecionada = 0;
    $somente_med_obr = false;
    $selecao_selecionada = $_SESSION['selecao'];
    
    if(isset($_GET['selecao_selecionada']))
        $selecao_selecionada = $_GET['selecao_selecionada'];
    
    if(isset($_GET['selecao_selecionada']) && $_GET['selecao_selecionada'] ==  'somente_med_obr')
        $somente_med_obr = true;
    
    
    if(isset($_GET['id_especialidade']))
        $id_especialidade_selecionada = $_GET['id_especialidade'];
    
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatório dos distribuidos <i class="fa fa-file-text"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatório dos distribuidos</li>
      </ul>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            
         <div class="row">
             <form name="fomulario1" action="relatorio_incorporados.php" method="get">
                 
                <div class="col-md-4">
                    <div class="card-body">
                        <label>Seleção</label>
                        <select onchange="fomulario1.submit()" name="selecao_selecionada" class="form-control" >
                            <option <?php if($somente_med_obr) echo " selected " ?> value="somente_med_obr">MÉDICOS OBRIGATÓRIOS</option>
                            <?php
                                $selecoes = $conexao->get_selecoes(); 
                                foreach ($selecoes as $value) 
                                {
                                    if($selecao_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['codigo'], "UTF-8") . " - ".$value['ano'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['codigo'], "UTF-8") . " - ".$value['ano'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                </form>
                 <form name="fomulario2" action="relatorio_incorporados.php" method="get">
                     <input name="selecao_selecionada" hidden value="<?php echo $selecao_selecionada ?>">
                <div class="col-md-8">
                    <div class="card-body">
                        <label>Selecione a especialidade desejada </label>
                        <select onchange="fomulario2.submit()" name="id_especialidade" class="form-control" >
                            <option value="">Todas</option>
                            
                            <?php
                            
                                $resultado = $conexao->get_especialidade_selecao($selecao_selecionada); 
                                foreach ($resultado as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
        
        <div class="card">
        
            <legend>Candidatos Distribuidos </legend>
        <div class="card-body">
             
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Especialidade</th>
                      <th>Data Incorporação</th>
                      <th>Força</th>
                      <th>Cidade 1º Fase</th>
                      <th>OM 1º Fase</th>
                      <th>Apresentação OM</th>
                      <th>Observação OM</th>
                      <th>Cidade Dist</th>
                      <th>OM Dist</th>
                      <th>Foto</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                    $lista_candidatos = $conexao->get_candidatos_distribuicao($selecao_selecionada,$somente_med_obr); 
                    
                    $total_incorporados = 0;
                    foreach ($lista_candidatos as $linha) 
                    {
                        $foto = "user.jpg";
                        $get_foto = $conexao->get_foto_usuario($linha['id']);  
                        if(count($get_foto) > 0)
                            $foto = $get_foto[0]['nome'];
                        
                        
                        if($id_especialidade_selecionada != null && $id_especialidade_selecionada != $linha['especialidade_incorporacao']) continue;
                        
                        if($linha['incorporado'] != 1) continue;
                        $data_incorporacao = null;
                        if($linha['data_incorporacao'] != null) $data_incorporacao = " em " . trata_data($linha['data_incorporacao']);
                        
                        $distribuicao = null;
                        if($linha['numero_distribuicao'] != null) $distribuicao = $linha['numero_distribuicao'] . "ª Distribuição ";
                        
                        if($somente_med_obr && $linha['medico_obrigatorio'] == null) continue;
                        
                        $total_incorporados ++;
                        
                        echo '
                        <tr>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$concorrendo.$linha['nome_completo'].'</font></a></td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                            <td>'. strtoupper($linha['ott_stt_especializacao']) . " ". $linha['nome_especialidade'].'</td>
                            <td>'. $distribuicao . $data_incorporacao.'</td>
                            <td>'.strtoupper($linha['forca_distribuicao']).'</td>
                            <td>'.$linha['nome_cidade_1_fase'].'</td>
                            <td>'.$linha['abreviatura_om_1_fase'].'</td>
                            <td>'.$linha['apresentacao_candidato_om'].'</td>
                            <td>'.$linha['observacao_om'].'</td>
                            <td>'.$linha['cidade_distribuicao'].'</td>
                            <td>'.$linha['om_dist_abreviatura'].'</td>
                            <td><img class="img-circle" src="fotos/'.$foto.'" width="60px"></td>
                        </tr>';
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <font size="5px">TOTAL DE DISTRIBUIDOS: <?php echo $total_incorporados; ?></font>
    </div>
        
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 0, "asc" ]]});</script>

</body>
</html>
<?php $conexao = null; ?>