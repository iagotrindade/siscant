<?php
include_once 'menu.php';

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 234234! Acesso restrito!");
    exit();
}

$id_usuario  = $_GET['id_usuario'];

if($id_usuario == null || $id_usuario == '')
{
    erro("Usuário não encontrado, erro: 7854654 $id_usuario");
    exit();
}

if($_SESSION['selecao_codigo'] != 'mfdv')
{
    erro("Erro 456437457! Página não encontrada!");
    exit();
}

$foto_nome1 = $conexao->get_foto_usuario($id_usuario);
$foto_nome = "user.jpg";
if($foto_nome1 != null)
    $foto_nome = $foto_nome1[0]['nome'];

$usuario_visualiza = $conexao->get_usuario_id($id_usuario);
include_once './codigos/variaveis_usuario_visualiza.php';


if(isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == 1)
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A senha foi alterada!"
        },{
                type: "info"
        });
    };
    </script>';
}





?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Reseta senha <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Resetar senha</li>
      </ul>
    </div>
  </div>
    
     <div class="row">
        <div class="col-md-12">
            <div class="card">
    <div class="row">
                    <div class="col-lg-6">
                        
                        <legend>Digite a nova senha do médico obrigatório</legend>
                        <form action="../banco_dados/medico_obrigatorio_nova_senha.php" method="post">
                            <input hidden id="id_usuario" name="id_usuario" value="<?php echo $id_usuario;?>">
                            <input hidden id="cpf" name="c_p_f" value="<?php echo $cpf;?>">
                            <input type="password" name="senha_medico" maxlength="20" class="form-control" >
                            <br>
                            <button  type="submit" class="btn btn-primary  btn-block">Definir senha</button>
                        </form>
                    </div>
                </div>
                </div>
                </div>
                </div>
    
                <?php

                    if($candidato == 1 || $perfil == 'candidato')
                        include_once './codigos/candidato_informacoes.php';
                    else
                        include_once './codigos/usuario_informacoes.php';

                ?>
    
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
</body>
</html>

                
            