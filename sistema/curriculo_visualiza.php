<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Currículos (PONTUAÇÃO) <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Currículos</li>
      </ul>
    </div>
  </div>
    
  <div class="row">
    <div class="col-md-12">
        <div  class="row" <?php if($_SESSION['perfil'] != "admin") echo "hidden" ?>>
            <div  class="col-lg-12">
                <div class="card">
                    <legend>Cadastre uma opção de currículo para o candidato</legend>
                    <form action="../banco_dados/curriculo_cadastra.php" method="post">
                    <div  class="row">
                        <div  class="col-lg-12 form-group">
                            <label>Nome do currículo:</label>
                            <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                            <input type="text" name="nome_curriculo" class="form-control" maxlength="400" >
                        </div>
                        
                        <div  class="col-lg-3 form-group">
                            <label>Pontuação:</label>
                            <input type="text" name="pontuacao" class="form-control" maxlength="200" >
                        </div>
                        
                        <div  class="col-lg-3 form-group">
                            <label>Quantidade máxima de uploads:</label>
                            <input type="text" name="quantidade_maxima" class="form-control" maxlength="2" >
                        </div>
                        
                        <div  class="col-lg-3 form-group">
                            <label>Pode ser multiplicado? Quantas Vezes?</label>
                            <select id="ott_stt" name="multiplicador" class="form-control">
                                <option value="">Selecione a opção</option>
                                
                                <?php
                                    for($i = 2; $i <= 10000; $i++)
                                    {
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                ?>
                                
                            </select>
                        </div>
                        
                        <div class="animated-checkbox col-lg-3 form-group">
                            <label>
                              <input type="checkbox" name="carga_horaria_obrigatoria"><span class="label-text">Experiência profissional (Carga horária obrigatória)</span>
                            </label>
                        </div>
                    </div>  
                    <div  class="row">
                        <div  class="col-lg-12 form-group">
                            <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR OPÇÃO DE CURRÍCULO</button>  
                        </div>  
                    </div>  
                        
                        
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
                      <th>ID</th>
                      <th>Nome do currículo</th>
                      <th>Pontuação</th>
                      <th>Qtd Máx Uploads</th>
                      <th>Multi</th>
                      <th>Carga Horária obrigatória</th>
                      <th width="20px">Apagar</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                   <?php
                        $lista_docs_obrigatorios = $conexao->get_curriculo_cadastrados();  
                        
                        foreach ($lista_docs_obrigatorios as $linha) 
                        {
                            $pontuacao = 0;
                            $carga = "Não";
                            if($linha['carga_horaria_obrigatoria'] == 1)
                                $carga = "Sim";
                            
                            $pontuacao = $linha['pontuacao']/1000;
                            $multiplicacao = "Não";
                            
                            if($linha['multiplicacao'] == '1')
                                $multiplicacao = "Sim";
                            
                            if($linha['quantidade_multiplicacao'] != null)
                                $multiplicacao = $linha['quantidade_multiplicacao'] . ' vezes';
                            
                            echo '                            <tr>
                                <td>'.$linha['id'].'</a></td>
                                <td>'.$linha['nome'].'</a></td>
                                <td>'.$pontuacao.'</a></td>
                                <td>'.$linha['quantidade_maxima_uploads'].'</a></td>
                                <td>'.$multiplicacao.'</a></td>
                                <td>'.$carga.'</a></td>
                                <td><a onclick="funcao_apagar(\''.$linha['id'].'\', \'curriculo\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 1, "asc" ]]});</script>
</body>
</html>