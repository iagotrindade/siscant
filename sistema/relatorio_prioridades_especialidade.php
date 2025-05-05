<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 5856856! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta")
    {
        erro("Erro 4573475! Página não encontrada!");
        exit();
    }
    
    $id_especialidade_selecionada = 0;
    
    if(isset($_GET['id_especialidade']))
        $id_especialidade_selecionada = (int)$_GET['id_especialidade'];
    
    $get_especialidade_selecionada = [];
    
     if($id_especialidade_selecionada != 0)
        $get_especialidade_selecionada = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);  
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatorio Prioridade das especialidades<i class="fa fa-file-text"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatorio Prioridade das especialidades</li>
      </ul>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-12">
        <form name="fomulario" action="relatorio_prioridades_especialidade.php" method="get">
            <div class="card">
                <div class="card-body">
                    <label>Selecione a especialidade desejada </label>
                    
                    <select onchange="fomulario.submit()" name="id_especialidade" class="form-control" >
                        <option value="">Selecione a especialidade</option>
                        <?php
                        
                                $resultado = $conexao->get_especialidade(); 
                                foreach ($resultado as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                        ?>
                    </select>
                    <br>
                                        
                </div>
            </div>
            </form>
        
   
        
    <div class="card">
        
        <legend>Candidatos da especialidade selecionada que estão na etapa corrente do processo</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Prioridades</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($get_especialidade_selecionada as $linha) 
                        {
                            $id_candidato_x_especialidade = 0;
                            
                            $id_candidato_x_especialidade = $linha['id_ce'];
                            
                            $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade); 
                            
                            $string_lista_cidade = "";
                            
                            foreach ($lista_cidades as $linha2) 
                            {
                               $string_lista_cidade .= "_". $linha2['prioridade']."ª".$linha2['nome'] . " - ";
                            }

                            if($linha['etapa'] != $_SESSION['etapa_selecao']) continue;
                            
                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['id'];
                            
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>                                
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$string_lista_cidade.'</td> 
                               <!-- <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td> -->
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 1, "asc" ]]});</script>

</body>
</html>
<?php $conexao = null; ?>