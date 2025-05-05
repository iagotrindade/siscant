<?php
include_once 'menu.php';
include_once 'codigos/funcao_restaura.php';

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
{
    erro("Erro 9232345345! Página não encontrada!");
    exit();
}

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1><font color="red">Documentos obrigatórios apagados </font><i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Arquivos obrigatórios apagados</li>
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
                      <th>Nome do documento Obrigatório (conforme o aviso de convocação)</th>
                      <th width="20px">Restaurar</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                   <?php
                        $lista_docs_obrigatorios = $conexao->get_documentos_obrigatorios_apagados();  
                        
                        foreach ($lista_docs_obrigatorios as $linha) 
                        {
                            
                            echo '
                            <tr>
                                <td>'.$linha['nome'].'</a></td>
                                <td><a onclick="funcao_restaura(\''.$linha['id'].'\', \'documentacao_obrigatoria\')"><img title="Restaurar" src="imagens/restaurar.png" width="30px"></a></td>
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