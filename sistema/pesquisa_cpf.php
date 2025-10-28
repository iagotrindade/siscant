<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if(hash('sha256', $_SESSION['chave']."pesquisa") != $_POST['criptografia'])
    {
        erro("Erro 4575467! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta' )
    {
        erro("Erro 7325723895! Página não encontrada!");
        exit();
    }
    
    $pesquisa = $_POST['pesquisa'];

    
    
    $lista_usuarios = $conexao->pesquisa_cpf($pesquisa);  
    
    $id_usuario_pesquisado = null;
    if(count($lista_usuarios) == 1)
    {
        $id_usuario_pesquisado = $lista_usuarios[0]['id'];
        echo '<meta http-equiv="refresh" content="0; URL=usuario_visualiza.php?id_usuario='.$id_usuario_pesquisado.'">';
        exit();
    }
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Pesquisa de Usuários <i class="fa fa-user"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Pesquisa de Usuários</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome Completo</th>
                      <th>Telefone</th>
                      <th>Mail</th>
                      <th>Ver</th>
                      <th>Senha</th>
                      <th>Del</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($lista_usuarios as $linha) 
                        {
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                                echo '
                                <tr>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                <td width="30px"><a href ="usuario_altera_senha.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Resetar senha" src="imagens/senha.png" width="30px"></center></a></td>
                                <td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'usuario\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
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