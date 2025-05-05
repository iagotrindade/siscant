<?php
include_once 'menu.php';

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 544235654: Página não encontrada");
    exit();
}

$id_especialidade = (int)$_GET['id_especialidade'];

$get_especialidade = $conexao->get_especialidade_id($id_especialidade);  

if($get_especialidade == null)
{
    erro("Erro 342354645: Especialidade não encontrado");
    exit();
}

$lista_cidades = $conexao->get_cidades_especialidade($get_especialidade[0]['id']);

$nome_esp = $get_especialidade[0]['nome'];
$ott_stt = $get_especialidade[0]['ott_stt'];
$teste_pratico = $get_especialidade[0]['teste_pratico'];
$musica = $get_especialidade[0]['musica'];

if(isset($_GET['sucesso']) && $_GET['sucesso'] == 1)
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " As cidades foram atualizadas!"
        },{
                type: "info"
        });
    };
    </script>';
}

?>



<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Especialidades <i class="fa fa-wrench"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Especialidades</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
<div class="row" <?php if($_SESSION['perfil'] != "admin") echo "hidden" ?>>
    <div class="col-md-12">
      <div class="card">
            <legend>Preencha os campos para cadastrar uma nova especialidade </legend> 
            <form action="../banco_dados/especialidade_edita.php" method="post" onsubmit="return validar_formulario()">
                
                <div  class="row">
                    
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nome da especialidade </label> 
                            <input disabled value="<?php echo"$nome_esp"?>" id="nome_especialidade" name="nome_especialidade" maxlength="120" class="form-control">
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class=" form-groupcheckbox">
                            <input disabled name="musica" <?php if($musica == 1 ) echo"checked" ?> type="checkbox"> <b>É uma especialidade de MÚSICA</b>
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class=" form-groupcheckbox">
                            <input disabled name="teste_pratico" <?php if($teste_pratico == 1 ) echo"checked" ?> type="checkbox"> <b>Tem teste prático</b>
                        </div>
                    </div>
                    
                    <div <?php if($codigo_selecao != 'ott_stt') echo "hidden" ?> class="col-lg-6">
                        <div class="form-group"> 
                            <select disabled name="ott_stt" class="form-control">
                                <option value="">Selecione STT ou OTT</option>
                                <option <?php if($ott_stt == "ott" ) echo "selected" ?> value="ott">OTT</option>
                                <option <?php if($ott_stt == "stt" ) echo "selected" ?> value="stt">STT</option>
                            </select>
                        </div>
                    </div>
                    
                    <input hidden value="<?php echo $_GET['id_especialidade']?>" name="id_especialidade">
                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                    
                    <div class="col-lg-12" >
                    <br>
                        <div class="form-group" id="especialidades">
                            <label>Selecione as cidades da especialidade </label><br>
                            <select class="js-example-basic-multiple" style="width: 100%" name="cidades[]" multiple>
                               
                               <?php
                                    $resultado = $conexao->busca_cidades(); 
                                    foreach ($resultado as $value) 
                                    {
                                        $continuar = false;
                                        if(count($lista_cidades) > 0)
                                        {
                                            foreach ($lista_cidades as $value_cidade) 
                                            {
                                                if($value['id'] == $value_cidade['id'])
                                                {
                                                    echo '<option selected value="'.$value['id'].'">' .$value['nome'].' - '.$value['uf'].'</option>';
                                                    $continuar = true;
                                                }
                                            }
                                        }
                                        if($continuar == false)
                                            echo '<option value="'.$value['id'].'">' .$value['nome'].' - '.$value['uf'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div  class="col-lg-12">
                        <div id="mensagem_erro">
                            <font color="red"><b><center><p id="mensagem"> Atenção! A atualização das cidades apaga a quantidade de vagas já cadastradas</p></center></b></font>
                        </div>
                    </div>
                </div>
                <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
            </form>
        </div>
    </div>
</div>
        
        
<div class="row" <?php if($_SESSION['perfil'] != "admin") echo "hidden" ?>>
    <div class="col-md-12">
      <div class="card">
            <legend>Quantidade de vagas por cidade</legend> 
            <form action="../banco_dados/quantidade_vagas_cidade_atualiza.php" method="post">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div class="form-group">
                            <label>Selecione a cidade </label><br>
                            <select style="width: 100%" class="js-example-basic-multiple" name="cidade">
                            <option value="">Selecione a cidade</option>
                               <?php
                                    $resultado = $conexao->busca_cidades(); 
                                    foreach ($resultado as $value) 
                                    {
                                        //echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
                                        if(count($lista_cidades) > 0)
                                        {
                                            foreach ($lista_cidades as $value_cidade) 
                                            {
                                                if($value['id'] == $value_cidade['id'])
                                                    echo '<option value="'.$value['id'].'">' .$value['nome'].' - '.$value['uf'].'</option>';
                                            }
                                        }
                                        else
                                            echo '<option value="'.$value['id'].'">' .$value['nome'].' - '.$value['uf'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <input hidden value="<?php echo $_GET['id_especialidade']?>" name="id_especialidade">
                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."vagas"); ?>">
                    
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <label>Número de Vagas </label><br>
                            <select name="quantidade_vagas" class="form-control">
                                <?php
                                    for($i=0;$i<=100;$i++)
                                    {
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                    
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div  class="col-lg-12">
                        <div id="mensagem_erro" hidden>
                            <font color="red"><b><center><p id="mensagem"></p></center></b></font>
                        </div>
                    </div>
                </div>
                <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
            </form>
        </div>
        
    </div>
</div>



<div class="row">
    <div class="col-md-12">
      <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="tabela_dinamica">
                  <thead>
                    <tr>
                      <th>Especialidade</th>
                      <th>Cidade</th>
                      <th>Nº Vagas</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                    <?php
                        
                        $lista_epecialidades = $conexao->get_cidades_especialidade($id_especialidade); 
                        foreach ($lista_epecialidades as $linha) 
                        {
                            echo '
                            <tr>
                                <td>'.$linha['nome_especialidade'].'</td>
                                <td>'.$linha['nome'].'</td>
                                <td>'.$linha['numero_vagas'].'</td>
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
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
</body>
</html>
<?php
    $conexao = null;
?>    