<?php
include_once 'menu.php';

$id_usuario = null;

if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
{
    $usuario_visualiza = $conexao->get_usuario_id($_SESSION['id_usuario']);
    $id_usuario = $_SESSION['id_usuario'];
}
else
{
    $usuario_visualiza = $conexao->get_usuario_id($_GET['id_usuario']);
    $id_usuario = $_GET['id_usuario'];
}

$foto_nome1 = $conexao->get_foto_usuario($id_usuario);
$foto_nome = "user.jpg";
if($foto_nome1 != null)
    $foto_nome = $foto_nome1[0]['nome'];

include_once './codigos/variaveis_usuario_visualiza.php';


?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Informações do Usuário  <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><a href="index.php">Página Inicial</a></li>
            <li>Informações do Usuário</li>
      </ul>
    </div>
  </div>
    
    <?php
        if($candidato == 1 || $perfil == 'candidato')
        {
            $id_criptografado = hash('sha256', $id_usuario);
            include_once './codigos/candidato_informacoes.php';
        }
        else
        {
            if($_SESSION['perfil'] != 'candidato')
                include_once './codigos/usuario_informacoes.php';
        }
    ?>
    
    
</div>

<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable();</script>
</body>
</html>