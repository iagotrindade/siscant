<?php
include_once 'menu.php';

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 544654: Página não encontrada");
    exit();
}



$get_usuario = $conexao->get_usuario_id($_GET['id_usuario']);  

if($get_usuario == null)
{
    erro("Erro 34645: Usuário não encontrado");
    exit();
}

$lista_especialidades = $conexao->get_especialidades_usuario_avaliador($get_usuario[0]['id']);

if($get_usuario[0]['perfil'] != "avaliador")
{
    echo "
    <script>
        window.onload = function()
        {
            $('#especialidades').hide();
        }
    </script>";
}

?>

<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>

<script>
    function avaliador()
    {
        if($('#perfil').val() == 'avaliador')
        {
            $('#especialidades').show();
        }
        else
        {
            $('#especialidades').hide();
        }
    }
</script>



<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Editar Usuário <i class="fa fa-pencil"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Cadastra Usuário</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
            <legend>Clique em salvar assim que terminar as alterações <font color="red" size="2px">Todos os campos são obrigatórios</font>   </legend> 
            <form action="../banco_dados/usuario_edita.php" method="post" onsubmit="return validar_formulario()">
                <div  class="row">
                    
                    <input hidden value="<?php echo $get_usuario[0]['id']?>" name="id_usuario">
                    <input hidden value="<?php echo hash('sha256', $get_usuario[0]['assinatura_sistema'])?>" name="crip">
                    
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nome completo:</label> 
                            <input value="<?php echo $get_usuario[0]['nome_completo']?>" id="nome" name="nome_completo" maxlength="120" class="form-control">
                        </div>
                        <div id="div_cpf" class="form-group"> 
                            <label align="right">CPF:</label><span id="cpf_mensagem"></span>
                            <input id="cpf" disabled name="cpf" class="form-control" value="<?php echo $get_usuario[0]['cpf']?>" onfocus="limpa_cpf()" onblur="verifica_cpf()"> 
                        </div>
                        <div id="div_nome_guerra" class="form-group"> 
                            <label>Nome de guerra:</label> 
                            <input id="nome_guerra" name="nome_guerra" value="<?php echo $get_usuario[0]['nome_guerra']?>" maxlength="20" class="form-control" >
                        </div>
                        <div id="div_mail" class="form-group"> 
                            <label>E-Mail</label>
                            <input id="mail" maxlength="50" value="<?php echo $get_usuario[0]['mail']?>" name="mail" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_posto" class="form-group"> <label for="posto">Posto/Graduação</label>
                            <select id="posto" name="posto_grad" class="form-control">
                                <option value="">Selecione o Posto/Graduação</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Sd") echo "selected" ?> value="Sd">Soldado</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Cb") echo "selected" ?> value="Cb">Cabo</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "3º Sgt") echo "selected" ?> value="3º Sgt">3º Sargento</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "2º Sgt") echo "selected" ?> value="2º Sgt">2º Sargento</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "1º Sgt") echo "selected" ?> value="1º Sgt">1º Sargento</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "ST") echo "selected" ?> value="ST">Sub Tenente</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Asp") echo "selected" ?> value="Asp">Aspirante</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "2º Ten") echo "selected" ?> value="2º Ten">2º Tenente</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "1º Ten") echo "selected" ?> value="1º Ten">1º Tenente</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Cap") echo "selected" ?> value="Cap">Capitão</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Maj") echo "selected" ?> value="Maj">Major</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "TCel") echo "selected" ?> value="TCel">Ten Coronel</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Cel") echo "selected" ?> value="Cel">Coronel</option>
                                <option <?php if($get_usuario[0]['posto_grad'] == "Gen") echo "selected" ?> value="Gen">General</option>
                            </select>
                        </div>
                        
                        <div id="" class="form-group"> <label>Organização Militar </label> 
                            <select id="om" name="om" class="form-control">
                                <option value="">Selecione a OM</option>
                                <?php
                                    $id_usuario = $_SESSION['id_usuario'];
                                    $rm_usuario = $conexao->rm_usuario($id_usuario ); 
                                    
                                    $oms = $conexao->get_oms($rm_usuario);
                                    foreach ($oms as $value) 
                                    {
                                        if($value['id'] == $get_usuario[0]['id_om'])
                                            echo '<option selected value="'.$value['id'].'">'.$value['nome'].'</option>';
                                        else
                                            echo '<option  value="'.$value['id'].'">'.$value['nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div id="div_ramal" class="form-group">
                            <label>Ramal/Telefone</label>
                            <input maxlength="20" value="<?php echo $get_usuario[0]['tel_celular']?>" name="telefone" class="form-control">
                        </div>
                        
                        <div id="div_perfil" class="form-group"> <label>Perfil</label>
                            <select id="perfil" name="perfil" class="form-control" onchange="avaliador()">
                                <option value="">Selecione o Perfil</option>
                                <option <?php if($get_usuario[0]['perfil'] == "admin") echo "selected" ?> value="admin">Administrador </option>
                                <option <?php if($get_usuario[0]['perfil'] == "consulta") echo "selected" ?> value="consulta">Consulta / Auditor</option>
                                <option <?php if($get_usuario[0]['perfil'] == "ouvidor") echo "selected" ?> value="ouvidor">Ouvidor</option>
                                <option <?php if($get_usuario[0]['perfil'] == "avaliador") echo "selected" ?> value="avaliador">Avaliador de currículo</option>
                                <option <?php if($get_usuario[0]['perfil'] == "documentos") echo "selected" ?> value="documentos">Avaliador de docs obrigatórios</option>
                                <option <?php if($get_usuario[0]['perfil'] == "om") echo "selected" ?> value="om">Organização Militar (OM)</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    <div  class="col-lg-12">
                        
                        <div class="form-group" id="especialidades" <?php if($get_usuario[0]['perfil'] != "avaliador") echo "hidden" ?>>
                            <label>Selecione as especialidade do avaliador:</label><br>
                            <select class="js-example-basic-multiple" name="especialidades[]" multiple>
                                <option selected> Selecione as especialidades para o usuária avaliar </option>>
                                <?php
                                    $resultado = $conexao->get_especialidade(); 
                                    
                                    foreach ($resultado as $value) 
                                    {
                                        $select = "";
                                        if(count($lista_especialidades) > 0)
                                        {
                                            foreach ($lista_especialidades as $value_especialidade) 
                                            {
                                                if($value['id'] == $value_especialidade['id_especialidade'])
                                                    $select = "selected";
                                            }
                                        }
                                        echo '<option '.$select.' value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        
                        
                        <div id="mensagem_erro" hidden>
                            <font color="red"><b><center><p id="mensagem"></p></center></b></font>
                        </div>
                    </div>
                </div>
                <button  type="submit"  class="btn btn-primary btn-block">SALVAR</button>
            </form>
        </div>
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript">
     //$('#om').select2();
     //$('#secao').select2();
</script> 
</body>
</html>
<?php $conexao = null; ?>