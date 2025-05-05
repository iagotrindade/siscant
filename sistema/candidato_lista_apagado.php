<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_restaura.php';
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 245345! Página não encontrada!");
        exit();
    }
    
    $lista_candidatos = $conexao->get_candidatos_apagado();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1><font color="red">Candidatos Apagados </font><i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Candidatos Apagados</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Todos os Candidatos apagados</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Telefone</th>
                      <th>Mail</th>
                      <th>Ver</th>
                      <th>Restaurar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_candidatos as $linha) 
                        {
                            
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                                echo '
                                <tr>
                                <td>'.$linha['id'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                <td width="30px"><a onclick="funcao_restaura(\''.$linha['id'].'\', \'candidato\')"><img title="Restaurar" src="imagens/restaurar.png" width="30px"></a></td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>