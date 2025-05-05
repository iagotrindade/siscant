<?php
include_once 'menu.php';
include_once 'codigos/funcao_restaura.php';

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
{
    erro("Erro 665345! Página não encontrada!");
    exit();
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
      <h1><font color="red">Especialidades Apagadas </font><i class="fa fa-wrench"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Especialidades Apagadas</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
<div class="row">
    <div class="col-md-12">
      <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="tabela_dinamica">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Teste Prático</th>
                      <th>OTT/STT</th>
                      <th>Cidades</th>
                      <th width="20px">Restaurar</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                    <?php
                        $lista_epecialidades = $conexao->get_especialidade_apagadas(); 
                        foreach ($lista_epecialidades as $linha) 
                        {
                            $teste = $linha['teste_pratico'];
                            if($teste == 1)
                                $teste = "Sim";
                            else
                                $teste = "Não";
                            
                            echo '
                            <tr>
                            <td>'.$linha['nome'].'</td>
                            <td>'.$teste.'</td>
                            <td>'.mb_strtoupper($linha['ott_stt'], 'UTF-8').'</td>';
                            
                            $lista_cidades = $conexao->get_cidades_especialidade($linha['id']);
                            if(count($lista_cidades) > 0)
                                echo '<td>';
                            foreach ($lista_cidades as $linha_cidade) 
                            {
                                echo $linha_cidade['nome'] ." | ";
                            }
                             if(count($lista_cidades) > 0)
                                echo '</td>';
                            
                            
                            echo '<td><a onclick="funcao_restaura(\''.$linha['id'].'\', \'especialidade\')"><img title="Apagar" src="imagens/restaurar.png" width="30px"></a></td>';
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