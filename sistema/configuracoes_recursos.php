<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $perfil != 'consulta')
    {
        erro("Erro 37345757! Página não encontrada!");
        exit();
    }
    

    $id_usuario = $_SESSION['id_usuario'];
    $rm_usuario = $conexao->rm_usuario($id_usuario); 

    $get_recurso_rm = $conexao->get_recurso_rm($rm_usuario);
    $data_inicio_recurso = $get_recurso_rm[0]['data_inicio_recurso'];
    $data_fim_recurso = $get_recurso_rm[0]['data_fim_recurso'];
    $data_inicio_recurso = trata_data($data_inicio_recurso);
    $data_fim_recurso = trata_data($data_fim_recurso);
    $mostrar_recurso  = $get_recurso_rm[0]['mostrar_recurso'];
 // NÃO SEI COMO ISSO AQUI ESTÁ FUNCIONANDO, MAS NÃO MEXE!!!

    //var_dump($mostrar_recurso); exit;
 //   $data_inicio_recurso = $conexao->get_data_recursos($rm_usuario);

?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Configurações <i class="fa fa-pencil"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Configurações</li>
      </ul>
    </div>
  </div>

  <form name="form_etapa_presencial" action="../banco_dados/candidato_visualiza_recurso.php" method="post">
    <div class="card">
        <h3>Recursos Digitais</h3> <br><!-- Título do formulário -->

        <input type="hidden" class="form-check-input" name="rm" value=<?php echo $rm_usuario ?> >
    
        <div class="form-check"> <!--09ABRIL25 SILVA -->
            <input type="checkbox" class="form-check-input" name="mostrar_recurso" value="1" id="check-recurso" <?php if (isset($get_recurso_rm[0]['mostrar_recurso']) && $get_recurso_rm[0]['mostrar_recurso'] == "1") echo "checked"; ?>>
            <label class="form-check-label" for="check-recurso">Ativar para o candidato fazer upload do Recurso digitalmente</label>
        </div>
            <br>
            <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label>Data de início</label> 
                            <input value=<?php if(isset($data_inicio_recurso)) echo $data_inicio_recurso; ?> name="data_inicio_recurso" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Data de fim</label>
                            <input value=<?php if(isset($data_fim_recurso)) echo $data_fim_recurso; ?> maxlength="20" name="data_fim_recurso" class="form-control">
                        </div>
                    </div>
                </div>
        <div style="clear: both;"></div>
        <button type="submit" class="btn btn-primary btn-block">ATIVAR</button>
    </div>
    <br>
</form>

  <div class="row">
    <div class="col-md-12">
        
   
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
</body>
</html>

<br>
<br>
    <!--<a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a> -->
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica1').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica4').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica5').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica6').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica7').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica8').DataTable({"order": [[2, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>