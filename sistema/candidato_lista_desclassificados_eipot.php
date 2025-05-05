<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    $id_usuario = $_SESSION['id_usuario'];
    $rm_usuario = $conexao->rm_usuario($id_usuario); 
    $id_selecao =  $_SESSION['selecao'];
    $lista_candidatos = $conexao->get_candidatos_desclassificados_rm($rm_usuario, $id_selecao);  

?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Candidatos desclassificados <i class="fa fa-user-times"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Candidatos desclassificados</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Candidatos Desclassificados</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Telefone</th>
                      <th>Mail</th>
                      <th>Etapa</th>
                      <!--<th>Del</th>-->
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_candidatos as $linha) 
                        {       
                                echo '
                                <tr>
                                <td>'.$linha['id'].'</td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td width="40px">et_'.$linha['etapa'].'</td>
                                <!--<td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'candidato\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>-->
                                </tr>';
                        }
                        //<td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
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