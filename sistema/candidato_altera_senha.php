<?php

include_once 'menu.php';

if($_SESSION['perfil'] != 'candidato')
{
    erro("Erro: 2353432455! Não foi possível abrir a página");
    exit();
}

$id_usuario  = $_SESSION['id_usuario'];

if($id_usuario == null || $id_usuario == '')
{
    erro("Usuário não encontrado, erro: 7854654 $id_usuario");
    exit();
}

if(isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == 1)
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A senha foi atualizada! <br>"
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
      <h1>Resetar senha <i class="fa fa-lock"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Resetar senha</li>
      </ul>
    </div>
  </div>
    
    <script>
    function valida_form()
    {
        $("#mensagem").text("");
        if($('#senha1').val() == '' || $('#senha2').val() == '')
        {
            $("#mensagem").text("Os dois campos são obrigatórios");
            return false;
        }
        if($('#senha1').val() !=  $('#senha2').val())
        {
            $("#mensagem").text("Os dois campos devem conter a mesma senha");
            return false;
        }
        
        
        if($('#senha1').val().length < 8)
        {
            $("#mensagem").text("A senha deve ter pelo menos 8 caracteres");
            return false;
        }
        
        return true;
    }
    </script>
    

    
     <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <div class="col-lg-6">
                        <legend>Digite duas vezes a nova senha senha</legend>
                        <font color='red'><b>A senha deve conter pelo menos 8 caracteres, um caracter especial, um número e uma letra maiúscula</b></font>
                        <form action="../banco_dados/candidato_reseta_senha.php" method="post" onsubmit="return valida_form()">
                            <div id="div_nome" class="form-group"> 
                                <label for="nome">Digite a nova senha</label> 
                                <input type="password" id="senha1" name="senha1" maxlength="120" class="form-control">
                            </div>
                            <div id="div_cpf" class="form-group"> 
                                <label align="right">Digite novamente a senha</label><span id="cpf_mensagem"></span>
                                <input type="password" id="senha2" name="senha2" class="form-control" > 
                            </div>
                            <div id="mensagem_erro">
                                <font color="red"><b><center><p id="mensagem"></p></center></b></font>
                            </div>
                            
                            <input hidden type="text" name="crip" value="<?php echo hash('sha256', $_SESSION['chave']."freitas") ?>" > 
                            
                            <button  type="submit" class="btn btn-primary  btn-block">ALTERAR SENHA</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
</div>
</div>
</body>
</html>

                
            