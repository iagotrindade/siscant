<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Documentos obrigatórios <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Documentos obrigatórios</li>
      </ul>
    </div>
  </div>
    
  <div class="row">
      
    <div class="col-md-12">
        
<div class="card">
    <legend>Digite o nome do documento obrigatório a ser adicionado pelo candidato</legend>
    <form action="../banco_dados/arquivo_obrigatorio_cadastro.php" method="post">
    <div  class="row" <?php if($_SESSION['perfil'] != "admin") echo "hidden" ?>>
        <div  class="col-lg-12">
            <label>Nome do arquivo obrigatório:</label>
            <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave']."ten_freitas"); ?>">
            <input type="text" name="nome_arquivo_obrigatorio" class="form-control" maxlength="350" >
        </div>

        <div class="animated-checkbox col-lg-2 form-group">
            <br>
            <label>
              <input type="checkbox" name="mulher"><span class="label-text">Para Mulheres</span>
            </label>
        </div>
        <div class="animated-checkbox col-lg-2 form-group">
            <br>
            <label>
              <input type="checkbox" name="militar_ativa"><span class="label-text">Para Militares da Ativa</span>
            </label>
        </div>
        <div class="animated-checkbox col-lg-2 form-group">
            <br>
            <label>
              <input type="checkbox" name="reservista"><span class="label-text">Para Reservistas</span>
            </label>
        </div>
        <div class="animated-checkbox col-lg-2 form-group">
            <br>
            <label>
              <input type="checkbox" name="cdi"><span class="label-text">Para Quem tem CDI</span>
            </label>
        </div>
        <div class="animated-checkbox col-lg-2 form-group">
            <br>
            <label>
              <input type="checkbox" name="vaga_reservada"><span class="label-text">Para Vaga Reservada</span>
            </label>
        </div>
        <div class="animated-checkbox col-lg-12 form-group">
            <font color='red'><b>ATENÇÃO:</b> Se não for selecionada nenhuma opção, o documento irá aparecer para todos os candidatos. 
                <br>Se for selecionado 'Para Mulheres' o documento cadastrado só será obrigatório para mulheres. 
                <br>O documento obrigatório só irá aparecer para o candidato caso ele cumpra TODAS as opções selecionadas. 
                <br>Exemplo: Se foi selecionado "Para Mulheres" e também selecionado "Para reservistas" o documento só irá aparecer para mulheres reservistas!
            </font>
        </div>
    </div>
        <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR ARQUIVO OBRIGATÓRIO</button>
    </form> 
</div>
        
        
<div class="row">
    <div class="col-md-12">
      <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="tabela_dinamica">
                  <thead>
                    <tr>
                      <th>Nome do documento Obrigatório (conforme o aviso de convocação)</th>
                      <th>Para Mulheres</th>
                      <th>Para Militares da Ativa</th>
                      <th>Para Reservistas</th>
                      <th>Para quem tem CDI</th>
                      <th>Para Vaga Reservada</th>
                      <th width="20px">Apagar</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                   <?php
                        $lista_docs_obrigatorios = $conexao->get_documentos_obrigatorios_cadastrados();  
                        
                        foreach ($lista_docs_obrigatorios as $linha) 
                        {
                            $mulher = null;
                            if($linha['mulher'] == '1') $mulher = "Sim";
                            $militar_ativa = null;
                            if($linha['militar_ativa'] == '1') $militar_ativa = "Sim";
                            $reservista = null;
                            if($linha['reservista'] == '1') $reservista = "Sim";
                            $cdi = null;
                            if($linha['cdi'] == '1') $cdi = "Sim";
                            $vaga_reservada = null;
                            if($linha['vaga_reservada'] == '1') $vaga_reservada = "Sim";
                            
                            $crip = hash('sha256', $_SESSION['chave']."freitas".$linha['id']);
                            echo '
                            <tr>
                                <td>'.$linha['nome'].'</td>
                                <td>'.$mulher.'</td>
                                <td>'.$militar_ativa.'</td>
                                <td>'.$reservista.'</td>
                                <td>'.$cdi.'</td>
                                <td>'.$vaga_reservada.'</td>
                                <td><a onclick="funcao_apagar(\''.$linha['id'].'\', \'documentacao_obrigatoria\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"ordering": false});</script>


</body>
</html>