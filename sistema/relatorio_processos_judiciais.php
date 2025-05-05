<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $perfil != 'consulta')
    {
        erro("Erro 24763457575! Página não encontrada!");
        exit();
    }
    
    $lista_candidatos = $conexao->get_candidatos_desc_class();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Candidatos <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Candidatos</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        <div class="card">
        <legend>JUDICIAL</legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Concorrendo</th>
                      <th>Ref/Impd</th>
                      <th>Nº Ação</th>
                      <th>Data Liminar</th>
                      <th>Transitou Julgado</th>
                      <th>Fav/Desv</th>
                      <th>Convocado</th>
                      <th>Pub Bar Reg</th>
                      <th>Obs Dist</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                    
                    foreach ($lista_candidatos as $linha) 
                    {
                        
                        if($linha['refratario_impedido'] == null || $linha['refratario_impedido'] == "") continue;
                        
                        $transitou = null;
                        if($linha['transitou_julgado'] != null && $linha['transitou_julgado'] == 1) $transitou = "t_Sim";
                        if($linha['transitou_julgado'] != null && $linha['transitou_julgado'] == 0) $transitou = "t_Não";
                        
                        $convocado = null;
                        if($linha['convocado'] != null && $linha['convocado'] == 1) $convocado = "c_Sim";
                        if($linha['convocado'] != null && $linha['convocado'] == 0) $convocado = "c_Não";
                        
                        $data_limiar = null;
                        if($linha['data_liminar'] != null) $data_limiar = trata_data($linha['data_liminar']);
                            
                        $concorrendo = null;
                        if($linha['concorrendo'] == '1') $concorrendo = 'Concorrendo';
                        if($linha['concorrendo'] == '0') $concorrendo = 'Desclassificado';
                        
                        echo '
                                <tr>
                                    <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                    <td>'.$linha['nome_completo'].'</td>
                                    <td>'.$concorrendo.'</td>
                                    <td>'.$linha['refratario_impedido'].'</td>
                                    <td>'.$linha['numero_acao'].'</td>
                                    <td>'.$data_limiar.'</td>
                                    <td>'.$transitou.'</td>
                                    <td>'.$linha['favoravel_desfavoravel'].'</td>
                                    <td>'.$convocado.'</td>
                                    <td>'.$linha['publicacao_bar_reg'].'</td>
                                    <td>'.$linha['observacao_distribuicao'].'</td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 2, "asc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 2, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>