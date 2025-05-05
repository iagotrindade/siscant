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
            <legend> Preencha os campos para cadastrar uma nova especialidade <font color="red" size="2px">Todos os campos são obrigatórios</font>   </legend> 
            <form action="../banco_dados/especialidade_cadastra.php" method="post" onsubmit="return validar_formulario()">
                <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                <div  class="row">
                    
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nome da especialidade </label> 
                            <input id="nome_especialidade" name="nome_especialidade" maxlength="240" class="form-control">
                        </div>
                    </div>
                    
                    
                    <div  class="col-lg-6">
                        <div class="form-group"  id="especialidades">
                            <label>Selecione as cidades da especialidade </label><br>
                            <select class="js-example-basic-multiple" name="cidades[]" multiple>
                               <?php
                                    $resultado = $conexao->busca_cidades(); 
                                    foreach ($resultado as $value) 
                                    {
                                        echo '<option value="'.$value['id'].'">'.$value['nome'].' - '.$value['uf'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div  class="col-lg-3" <?php if($codigo_selecao != 'ott_stt') echo "hidden" ?>>
                        <div class=" form-groupcheckbox">
                            <input name="musica" type="checkbox"> <b>É uma especialidade de MÚSICA</b>
                        </div>
                    </div>
                    
                    <div  class="col-lg-3" <?php if($codigo_selecao == 'mfdv') echo "hidden" ?>>
                        <div class=" form-groupcheckbox">
                            <input name="teste_pratico" type="checkbox"> <b>Tem teste prático</b>
                        </div>
                    </div>
                    
                  <!--  <div class="col-lg-6">
                        <div class="form-group"> 
                            <select id="ott_stt" name="ott_stt" class="form-control">
                                <option value="">Selecione a opção</option>
                                
                                <?php
                                    
                                    if($codigo_selecao == 'mfdv')
                                    echo "teste5";
                                    echo '<option value="medico">Médico</option>
                                        <option value="farmaceutico">Farmacêutico</option>
                                        <option value="dentista">Dentista</option>
                                        <option value="veterinario">Veterinário</option>';

                                    if($codigo_selecao == 'ott_stt')
                                        echo '<option value="ott">OTT</option>
                                        <option value="stt">STT</option>'; 

                                    if($_SESSION['selecao_codigo'] == 'eipot')
                                    echo '<option value="eipot">EIPOT</option>'; 
                                    
                                    if($codigo_selecao == 'cet')
                                        echo '<option value="cet">CET</option>';
                                    
                                    if($codigo_selecao == 'ottm')
                                        echo '<option value="ottm">OTTM</option>';
                                    
                                   
                                ?>
                                
                            </select>
                        </div>
                    </div> -->
                    <div class="col-lg-6">
                        <div class="form-group"> 
                            <select id="ott_stt" name="ott_stt" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option value="medico">Médico</option>
                                <option value="farmaceutico">Farmacêutico</option>
                                <option value="dentista">Dentista</option>
                                <option value="veterinario">Veterinário</option>
                                <option value="ott">OTT</option>
                                <option value="stt">STT</option>
                                <option value="eipot">EIPOT</option>
                                <option value="cet">CET</option>
                                <option value="ottm">OTTM</option>
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
                      
                      <?php
                      if($codigo_selecao != 'mfdv')
                          echo '<th>É Música</th>
                            <th>Teste Prático</th>';
                      ?>
                      
                      
                      <th>Cidades</th>
                      <th width="20px">Edit</th>
                      <th width="20px">Del</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                    <?php
                        
                        $lista_epecialidades = $conexao->get_especialidade(); 
                        foreach ($lista_epecialidades as $linha) 
                        {
                            $teste = $linha['teste_pratico'];
                            if($teste == 1)
                                $teste = "Sim_tp";
                            else
                                $teste = "Não_tp";
                            
                            $musica = $linha['musica'];
                            if($musica == 1)
                                $musica = "Sim_m";
                            else
                                $musica = "Não_m";
                            
                            echo '
                            <tr>
                             <td>'.mb_strtoupper($linha['ott_stt'], 'UTF-8').'</td>
                            <td>'.$linha['nome'].'</td>';
                            
                            if($codigo_selecao != 'mfdv')
                                echo ' 
                            <td>'.$musica.'</td>
                            <td>'.$teste.'</td>';
                            
                            $lista_cidades = $conexao->get_cidades_especialidade($linha['id']);
                            if(count($lista_cidades) > 0)
                                echo '<td>';
                            foreach ($lista_cidades as $linha_cidade) 
                            {
                                echo $linha_cidade['nome'] ." | ";
                            }
                             if(count($lista_cidades) > 0)
                                echo '</td>';
                            
                            
                            echo '<td width="30px"><a href ="especialidade_editar.php?id_especialidade='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Editar Especialidade" src="imagens/editar.png" width="30px"></center></a></td>';
                            echo '<td><a onclick="funcao_apagar(\''.$linha['id'].'\', \'especialidade\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';
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