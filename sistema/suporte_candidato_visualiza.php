<?php
include_once 'menu.php';

if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 23948! Página não encontrada!");
    exit();
}

$foto_candidato = "user.jpg";
$foto_resposta = "user.jpg";

$id_suporte = null;
$id_suporte = $_GET['id_suporte'];

$get_suporte_candidato_id = $conexao->get_suporte_candidato_id($id_suporte);

$id_usuario_remetente = $get_suporte_candidato_id[0]['id_usuario_remetente'];
$id_usuario_respondeu = $get_suporte_candidato_id[0]['id_usuario_respondeu'];

$usuario_remetente = $conexao->get_usuario_id($id_usuario_remetente);
$usuario_resposta  = $conexao->get_usuario_id($id_usuario_respondeu);

$nome_candidato = null;
$cpf_candidato = null;      
$nome_resposta = null;   
$cpf_resposta = null;
$leu_resposta = null;
$data_enviado = null;
$mensagem = null;
$motivo = null;
$resposta = null;
$data_resposta = null;
$respondida = null;

$nome_candidato = $usuario_remetente[0]['nome_completo'];
$cpf_candidato = $usuario_remetente[0]['cpf'];
$mail_candidato = $usuario_remetente[0]['mail'];

if($usuario_resposta != null)
{
    $nome_resposta = $usuario_resposta[0]['posto_grad'];
    $nome_resposta = $nome_resposta . " " . $usuario_resposta[0]['nome_guerra'];
    $cpf_resposta = $usuario_resposta[0]['cpf'];
    
    $foto_resposta1 = $conexao->get_foto_usuario($id_usuario_respondeu); 
    if($foto_resposta1 != null)
        $foto_resposta = $foto_resposta1[0]['nome'];
}

$leu_resposta = $get_suporte_candidato_id[0]['leu_resposta'];
$data_enviado = $get_suporte_candidato_id[0]['data_enviado'];
$mensagem = $get_suporte_candidato_id[0]['mensagem'];
$motivo = $get_suporte_candidato_id[0]['motivo'];
$resposta = $get_suporte_candidato_id[0]['resposta'];
$data_resposta = $get_suporte_candidato_id[0]['data_resposta'];
$respondida = $get_suporte_candidato_id[0]['respondida'];

if($cpf_candidato == "" || $cpf_candidato == null)
{
    erro("Erro 5646! Suporte não encontrado!");
    exit();
}



$foto_candidato1 = $conexao->get_foto_usuario($id_usuario_remetente); 
    if($foto_candidato1 != null)
        $foto_candidato = $foto_candidato1[0]['nome'];




if($data_resposta != null)
    $data_resposta = trata_data_hora($data_resposta);
if($data_enviado != null)
    $data_enviado = trata_data_hora($data_enviado);

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Informações do suporte <i class="fa fa-file-text-o"></i></h1>
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
                            <td width='250px'><b>CPF: </b><a href="usuario_visualiza.php?id_usuario=<?php echo $id_usuario_remetente ?>"><?php echo mascara($cpf_candidato,'###.###.###-##') ?></a></td>
                            <td><b>Nome: </b><?php echo $nome_candidato ?></td>
                            <td><b>Data da solicitação </b><?php echo $data_enviado ?></td>
                            <td align='right'><a href="usuario_visualiza.php?id_usuario=<?php echo $id_usuario_remetente ?>"><img src="fotos/<?php echo $foto_candidato ?>" width="100px"></a></td>
                        </tr>
                        <tr>
                            
                            <td><b>E-Mail: </b><?php echo  $mail_candidato?></td>
                            <td><b>Motivo: </b><?php echo  $motivo?></td>
                            <td colspan="2"><b>Mensagem: </b><?php echo  $mensagem?></td>
                        </tr>
                    </tbody>
                </table>
               </div>
        </div>
    </div>
    <div class="row" <?php if($respondida == 0) echo "hidden" ?>>
        <div class="col-md-12">
            <div class="card">
                <legend>Resposta
                    <font color="red" ><b></b></font>
                </legend>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td width='250px'><b>CPF: </b><?php echo mascara($cpf_resposta,'###.###.###-##') ?></td>
                            <td><b>Nome: </b><?php echo $nome_resposta ?></td>
                            <td><b>Data da Resposta </b><?php echo $data_resposta ?></td>
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
                <legend>Resposta</legend>
                <div class="row">
                    <form action="../banco_dados/suporte_candidato_resposta.php" method="post">
                        <input hidden type="text" name='id_suporte' value="<?php echo $id_suporte ?>">
                        <div id="div_mensagem_suporte" class="col-lg-12">
                            <div  class="form-group"> 
                                <label>Mensagem</label>
                                <textarea maxlength="2000" id="mensagem_suporte" name="resposta" class="form-control"></textarea>
                            </div>
                        </div>

                        <div  class="col-lg-12">
                            <button name="enviar" type="submit"  class="btn btn-primary btn-block">Enviar Resposta</button>
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