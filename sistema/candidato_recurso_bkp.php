<?php
    include_once 'menu.php';
   include_once 'codigos/funcao_apagar.php';
  
   /*
    $conexao = new Conexao();
    $id_selecao = $_SESSION['selecao'];
    $selecao = $conexao->get_selecao_rm($id_selecao);
   */
    
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
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <legend>Adicionar recurso</legend>
            <div class="row">
                <div  class="col-lg-12" <?php if(!insere_recurso()); echo " hidden " ?>>
                        <div  class="row">
                            <div  class="col-md-12">
                                
                                <!-- <form method="post" action="arquivo_upload_pagamento_inscricao.php" enctype="multipart/form-data"> -->
                                <form method="post" action="arquivo_upload_recurso_candidato.php" enctype="multipart/form-data">
                                  <div>
                                      <div class="form-group"> 
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
                                  </div>
                              </form>

                                                              
                            </div>
                        </div>
                  </div>
                
                <div class="col-md-12" <?php if(insere_recurso()) echo " hidden " ?>>  
                    <label><font color="red" size="5px">O Período para recurso esta fechado!</font></label>
                </div>
            </div>
        </div>
        
        
        <div class="card">
                <div class="card-body">
                  <table class="table table-hover table-bordered" id="tabela_dinamica">
                    <thead>
                      <tr>
                        <th>Arquivo</th>
                        <th>Data</th>
                        <th>Etapa</th>
                        <th>Status</th>
                        <th>Justificativa</th>
                        <?php if(insere_recurso()) echo "<th>Análise</th> " ?>
                        <?php if(insere_recurso()) echo "<th>Apagar</th> " ?>
                      </tr>
                    </thead>
                    <tbody>
                      
                        <?php
                     //   var_dump($_SESSION['selecao_regiao']);
                 

                            $lista_recursos = $conexao->get_recursos_candidato($_SESSION['id_usuario']); 
                            foreach ($lista_recursos as $linha) 
                            {
                                $data_de_abertura = null;
                                if($linha['data_abertura'] != null)
                                    $data_de_abertura  =  trata_data ($linha['data_abertura']);

                                $status = "Pendente";
                                if($linha['status'] != null && $linha['status'] == 'deferido') $status = 'Deferido';
                                if($linha['status'] != null && $linha['status'] == 'deferido_parcialmente') $status = 'Deferido Parcialmente';
                                if($linha['status'] != null && $linha['status'] == 'indeferido') $status = 'Indeferido';

                                $justificativa = $linha['paragrafo1'] . " " . $linha['paragrafo2'];
                                $analise = $linha['analise'];
                                
                                $crip = hash('sha256', $_SESSION['chave']."freitas".$linha['id']);
                                echo '
                                <tr>
                                    <td><a href="baixaPDF.php?codigo=rec_cand_vis&nome_arquivo='.$linha['arq_nome_arquivo'].'" target="_blank">'.$linha['arq_nome_original'].'</a></td>
                                    <td>'.$data_de_abertura.'</td>
                                    <td>'.$linha['etapa'].'</td>
                                    <td>'.$status.'</td>
                                    <td>'.$justificativa.'</td>
                                     <td>'.$analise.'</td>';
                                    if(insere_recurso()) echo '<td><a onclick="funcao_apagar(\''.$linha['id'].'\', \'recurso_candidato\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';
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