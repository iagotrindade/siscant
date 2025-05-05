<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 235235! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 234235! Página não encontrada!");
        exit();
    }
    
    $lista_curriculos = $conexao->get_curriculo_cadastrados(); 
    
    $curriculo_selecionado = null;
    if(isset($_GET['curriculo_selecionado']))
        $curriculo_selecionado = (int)$_GET['curriculo_selecionado'];
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Perícia Currícular </h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Perícia Currícular</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        <form name="fomulario" action="pericia_curricular.php" method="get">
            <div class="card">
                <div class="card-body">
                    
                    
                        <label>Selecione o currículo</label>
                            <select onchange="fomulario.submit()" name="curriculo_selecionado" class="form-control">
                                <option value="">Selecione a opção</option>    
                                <?php
                                    foreach ($lista_curriculos as $linha) 
                                    {
                                        if($curriculo_selecionado == $linha['id'])
                                            echo '<option selected value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                        else
                                            echo '<option  value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                    }
                                
                                ?>
                            </select> 
                    
                </div>
            </div>
        </form>
        
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Militar?</th>
                      <th>Especialidade</th>
                      <th>Currículo</th>
                      <th>Arquivos adicionados / Até Máximo de</th>
                      <th>Total de multiplicações</th>
                      <th>Somatório total de pontos</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                            
                        $lista_curriculo_acima_permitido = $conexao->get_curriculos_avaliados_acima_permitido($curriculo_selecionado);
                            
                        foreach ($lista_curriculo_acima_permitido as $linha2) 
                        {
                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha2['id_usuario']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];

                            $total_pontos_somados = null;
                            if((int)$linha2['total_pontos_somados'] > 0)
                                $total_pontos_somados = (int)$linha2['total_pontos_somados']/1000;

                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha2['id_usuario'].'">'.$linha2['cpf'].'</a></td>
                                <td>'.$linha2['ativa_reserva'].'</td>
                                <td>'. mb_strtoupper($linha2['ott_stt'], "UTF-8") . " - " . $linha2['especialidade'].'</td>
                                <td>'.$linha2['curriculo'].'</td>
                                <td>'.$linha2['quantidade_arquivo'].' de '.$linha2['quantidade_maxima_uploads'].'</td>
                                <td>'.$linha2['somatorio_multiplicador'].'</td>
                                <td>'.$total_pontos_somados.'</td> 
                                <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha2['id_usuario'].'"><img class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        <div class="card" <?php if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") echo "hidden" ?>>
        <legend>Documentos multiplicados por mais de 1</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>Data Avaliação</th>
                      <th>Especialidade</th>
                      <th>Currículo</th>
                      <th>Multiplicador</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $relacao_docs_nao_avaliados = $conexao->get_multiplicadores_curriculo_maior_1();  
                    
                        foreach ($relacao_docs_nao_avaliados as $linha) 
                        {
                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $data_avaliacao = null;
                            if($linha['data_avaliacao'] != null)
                                $data_avaliacao = trata_data($linha['data_avaliacao']);
                            
                            echo '
                            <tr>
                            <td>'.$data_avaliacao.'</td>
                            <td>'.$linha['especialidade'].'</td>
                            <td>'.$linha['nome'].'</td>
                            <td>'.$linha['multiplicador'].'</td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 5, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 3, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>