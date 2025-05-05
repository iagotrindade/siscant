<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

?>

<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Armas <i class="fa fa-shield"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Armas</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
<div class="row" <?php if($_SESSION['perfil'] != "admin") echo "hidden" ?>>
    <div class="col-md-12">
      <div class="card">
            <legend> Preencha os campos para cadastrar uma nova Arma <font color="red" size="2px">Todos os campos são obrigatórios</font>   </legend> 
            <form action="../banco_dados/especialidade_eipot_cadastra.php" method="post">
                <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                <div  class="row">
                    
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nome da Arma </label> 
                            <input name="nome_especialidade" maxlength="240" class="form-control">
                        </div>
                    </div>

                     <br>
                    
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <select name="eipot" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option value="eipot">EIPOT</option>
                                
                            </select>
                        </div>
                    </div>
                    
                    <div  class="col-lg-12">
                        <div id="mensagem_erro" hidden>
                            <font color="red"><b><center><p id="mensagem"></p></center></b></font>
                        </div>
                    </div>
                </div>
                <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR</button>
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
                      <th>Categoria</th>
                      <th>Nome</th>
                      <th width="20px">Edit</th>
                      <th width="20px">Del</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                    <?php
                        
                        $lista_epecialidades = $conexao->get_especialidade(); 
                        foreach ($lista_epecialidades as $linha) 
                        {
                            
                            echo '
                            <tr>
                             <td>'.mb_strtoupper($linha['ott_stt'], 'UTF-8').'</td>
                            <td>'.$linha['nome'].'</td>';
                            
                            echo '<td width="30px"><a href ="especialidade_eipot_editar.php?id_especialidade='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Editar Especialidade" src="imagens/editar.png" width="30px"></center></a></td>';
                            echo '<td><a href ="../banco_dados/especialidade_eipot_apaga.php?id_especialidade='.$linha['id'].'" ><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';
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