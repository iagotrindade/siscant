<?php
include_once 'menu.php';

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'ouvidor')
{
    erro("Erro 544654: Página não encontrada");
    exit();
}



$id_suporte = null;
$id_suporte = $_GET['id_suporte'];
$criptografia = $_GET['criptografia'];

if($_GET['criptografia'] != hash('sha256', $id_suporte))
{
    erro("Erro 345345! Página não encontrada!");
    exit();
}

$get_suporte_id = $conexao->get_suporte_inicial_id($id_suporte);

$nome_completo = null;
$motivo = null;
$cpf = null;
$telefone = null;
$mail = null;
$mensagem = null;
$data_enviado = null;
$resposta = null;
$data_resposta = null;
$id_usuario_resposta = null;

$cpf_reposta = null;
$nome_reposta = null;

if($get_suporte_id != null)
{
    $nome_completo = $get_suporte_id[0]['nome_completo'];
    $motivo = $get_suporte_id[0]['motivo'];
    $cpf = $get_suporte_id[0]['cpf'];
    $telefone = $get_suporte_id[0]['telefone'];
    $mail = $get_suporte_id[0]['mail'];
    $mensagem = $get_suporte_id[0]['mensagem'];
    $data_enviado = $get_suporte_id[0]['data_enviado'];
    $resposta = $get_suporte_id[0]['resposta'];
    $data_resposta = $get_suporte_id[0]['data_resposta'];
    $id_usuario_resposta = $get_suporte_id[0]['usuario_resposta'];
    
    $foto_resposta = "user.jpg";
    $foto_resposta1 = $conexao->get_foto_usuario($id_usuario_resposta);
    if($foto_resposta1 != null)
        $foto_resposta = $foto_resposta1[0]['nome'];
    
    $usuario_resposta = $conexao->get_usuario_id($id_usuario_resposta);
    if($usuario_resposta != null)
    {
        $cpf_reposta = $usuario_resposta[0]['cpf'];
        $nome_reposta = $usuario_resposta[0]['posto_grad'];
        $nome_reposta = $nome_reposta ." ". $usuario_resposta[0]['nome_guerra'];
    }
}


if($data_resposta != null)
    $data_resposta = trata_data_hora($data_resposta);
if($data_enviado != null)
    $data_enviado = trata_data_hora($data_enviado);

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Informações do suporte inicial <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Cadastro de arquivo obrigatório</li>
      </ul>
    </div>
  </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <legend>Solicitação de suporte
                    <font color="red" ><b></b></font>
                </legend>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nome: </b><?php echo $nome_completo ?></td>
                            <td width='250px'><b>CPF: </b><?php echo mascara($cpf,'###.###.###-##') ?></td>
                            <td><b>Data da solicitação </b><?php echo $data_enviado ?></td>
                        </tr>
                        <tr>
                            <td><b>Motivo: </b><?php echo  $motivo?></td>
                            <td><b>Telefone: </b><?php echo  $telefone?></td>
                            <td><b>E-Mail: </b><?php echo  $mail?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>Mensagem: </b><?php echo  $mensagem?></td>
                        </tr>
                    </tbody>
                </table>
               </div>
        </div>
    </div>
    <div class="row"  <?php if($resposta == null) echo "hidden" ?>>
        <div class="col-md-12">
            <div class="card">
                <legend>Resposta
                    <font color="red" ><b></b></font>
                </legend>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nome: </b><?php echo "$nome_reposta" ?></td>
                            <td width='250px'><b>CPF: </b><?php echo "$cpf_reposta" ?></td>
                            <td><b>Data da Resposta </b><?php echo "$data_resposta" ?></td>
                            <td align='right'><img src="fotos/<?php echo $foto_resposta ?>" width="100px"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>Resposta: </b><?php echo  $resposta ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row" <?php if($resposta != null || (($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'ouvidor'))) echo "hidden" ?>>
        <div class="col-md-12">
            <div class="card">
                <legend>A resposta será enviada por e-mail</legend>
                <div class="row">
                    <form action="../banco_dados/suporte_inicial_resposta.php" method="post">
                        <input hidden type="text" name='id_suporte' value="<?php echo $id_suporte ?>">
                        <input hidden type="text" name='criptografia' value="<?php echo $criptografia ?>">
                        <div id="div_mensagem_suporte" class="col-lg-12">
                            <div  class="form-group"> 
                                <label>Mensagem</label>
                                <textarea maxlength="2000" id="mensagem_suporte" name="resposta" class="form-control"></textarea>
                            </div>
                        </div>
                        <div  class="col-lg-12">
                            <button name="enviar" type="submit"  class="btn btn-primary btn-block">Enviar Resposta por E-Mail</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>