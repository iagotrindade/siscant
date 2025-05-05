<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Página não encontrada!");
        exit();
    }
    
    $lista_usuarios = $conexao->get_usuarios();  
    $lista_usuarios_perfil_om = $conexao->get_usuarios_perfil_om();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Usuários <i class="fa fa-user"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Usuários</li>
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
                      <th>Nome Guerra</th>
                      <th>Organização Militar</th>
                      <th>E-Mail</th>
                      <th>Telefone</th>
                      <th>Perfil</th>
                      <th>Lista Especialidades</th>
                      <th>Ver</th>
                      <th>Senha</th>
                      <th>Editar</th>
                      <th>Del</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_usuarios as $linha) 
                        {
                            
                            $foto = "user.jpg";
                            
                            $lista_especialidades = null;
                            $lista_especialidades_avaliador = "";
                            
                            if($linha['perfil'] == 'om') continue;
                            
                            if($linha['perfil'] == 'avaliador')
                            {
                                $lista_especialidades = $conexao->get_especialidades_usuario_avaliador($linha['id']);
                                foreach ($lista_especialidades as $linha_especialidade) 
                                {
                                    $lista_especialidades_avaliador = $lista_especialidades_avaliador . $linha_especialidade['nome'] . " | ";
                                }
                            }
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'. mb_strtoupper($linha['posto_grad'], 'UTF-8').' ' .mb_strtoupper($linha['nome_guerra'], 'UTF-8') . ' </td>
                                <td>'.$linha['nome_om'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['perfil'].'</td>
                                <td>'.$lista_especialidades_avaliador.'</td>
                                <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                <td width="30px"><a href ="usuario_altera_senha.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Resetar senha" src="imagens/senha.png" width="30px"></center></a></td>
                                <td width="30px"><a href ="usuario_editar.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Editar Usuário" src="imagens/editar.png" width="30px"></center></a></td>
                                <td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'usuario\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
    <div class="card" <?php if($_SESSION['selecao_codigo'] == "cet") echo " hidden " ?>>
        <div class="card-body">
            <legend>Usuário de OM</legend>
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome Guerra</th>
                      <th>Organização Militar</th>
                      <th>Seleção</th>
                      <th>Telefone</th>
                      <th>Ver</th>
                      <th>Senha</th>
                      <th>Editar</th>
                      <th>Del</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_usuarios_perfil_om as $linha) 
                        {
                            
                            $foto = "user.jpg";
                            
                            $lista_especialidades = null;
                            $lista_especialidades_avaliador = "";
                            if($linha['perfil'] != 'om') continue;
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'. mb_strtoupper($linha['posto_grad'], 'UTF-8').' ' .mb_strtoupper($linha['nome_guerra'], 'UTF-8') . ' </td>
                                <td>'.$linha['nome_om'].' ('.$linha['abreviatura_om'].')</td>
                                <td>'.$linha['nome_selecao'].' - '.$linha['selecao_ano'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                <td width="30px"><a href ="usuario_altera_senha.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Resetar senha" src="imagens/senha.png" width="30px"></center></a></td>
                                <td width="30px"><a href ="usuario_editar.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Editar Usuário" src="imagens/editar.png" width="30px"></center></a></td>
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
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>