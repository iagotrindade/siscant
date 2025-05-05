<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
   
    $conexao = new Conexao();

    // Obtém o id_selecao da sessão
    $id_selecao = $_SESSION['selecao'];
    $id_usuario = $_SESSION['id_usuario'];
    $rm_candidato = $conexao->get_selecao_rm($id_usuario);

    $recurso_visualiza = $conexao->get_recurso_visualiza($id_selecao, $rm_candidato);
    $mostrar_recursos = $recurso_visualiza[0]['mostrar_recurso'];
    $data_inicio_recurso = $recurso_visualiza[0]['data_inicio_recurso'];
    $data_fim_recurso = $recurso_visualiza[0]['data_fim_recurso'];
    $data_inicio_recurso = trata_data($data_inicio_recurso);
    $data_fim_recurso = trata_data($data_fim_recurso);
    $data_hoje = date('d-m-Y');


    if($mostrar_recursos == "1") $mostrar_recursos = true;
    if($mostrar_recursos == "0") $mostrar_recursos = false;
    if($data_hoje > $data_fim_recurso || $data_hoje < $data_inicio_recurso) $mostrar_recursos = false;
    
?>

      <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1>Recurso <i class="fa fa-file-o"></i></h1>
          </div>
          <div>
            <ul class="breadcrumb">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li><a href="index.php">Página Inicial</a></li>
            </ul>
          </div>
        </div>
        <div <?php if ($mostrar_recursos == false) echo " hidden "; ?> class="row">
            <div  class="col-md-12">
              <div class="card" >
                  <legend>Adicionar recurso</legend>
                  <div class="row">
                      <div class="col-lg-12" <?php //if (insere_recurso()) echo ""; else echo "hidden"; ?>>
                          <div class="row">
                              <div class="col-md-12">
                                  <form method="post" action="arquivo_upload_recurso_candidato.php" enctype="multipart/form-data">
                                      <div>
                                          <div class="form-group"> 
                                          </div>
                                          <input type="text" hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave']."freitas") ?>">
                                          <div class="row">
                                              <div class="col-md-6">  
                                                  <label>Adicione seu recurso<font color="red"> *Máximo 5 MegaBytes no formato PDF</font></label>
                                                  <div class="form-group"> 
                                                      <br>
                                                      <input type="file" name="arquivo" />
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group"> 
                                                      <input type="submit" class="btn btn-primary btn-block" value="Enviar recurso" />
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
                
                <div class="col-md-12" <?php// if(insere_recurso()) echo " hidden " ?>>  
                    <label><font color="red" size="5px">O Período para recurso esta fechado!</font></label>
                </div>
          </div>
        </div>
        <div class="card">
                <div class="card-body">
                  <table class="table table-hover table-bordered" id="tabela_dinamica">
                    <thead>
                      <tr>
                        <th>Data do Recurso</th>
                        <th>Etapa</th>
                        <th>Status</th>
                        <th>Justificativa</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                            $lista_recursos = $conexao->get_recursos_candidato($_SESSION['id_usuario']); 
                            foreach ($lista_recursos as $linha) 
                            {
                                $data_de_abertura = null;
                                if($linha['data_abertura'] != null)
                                    $data_de_abertura  =  trata_data ($linha['data_abertura']);

                                $status = "Pendente";
                                if($linha['status_final'] != null && $linha['status_final'] == 'deferido') $status = 'Deferido';
                                if($linha['status_final'] != null && $linha['status_final'] == 'deferido_parcialmente') $status = 'Deferido Parcialmente';
                                if($linha['status_final'] != null && $linha['status_final'] == 'indeferido') $status = 'Indeferido';

                                $justificativa = $linha['paragrafo1'] . " " . $linha['paragrafo2'];
                                $analise = $linha['analise'];
                                
                                $crip = hash('sha256', $_SESSION['chave']."freitas".$linha['id']);
                                echo '
                                <tr>
                                    <td>'.$data_de_abertura.'</td>
                                    <td>'.$linha['etapa'].'</td>
                                    <td>'.$status.'</td>
                                    <td>'.$justificativa.'</td>';
                                echo '</tr>';
                            }
                        ?>   
                  </tbody>
                </table>
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
<?php $conexao = null; ?>