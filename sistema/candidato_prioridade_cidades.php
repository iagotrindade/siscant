<?php

include_once 'menu.php';

/*
erro("Erro 47984684: Página não encontrada!");
exit();
*/

include_once 'codigos/funcao_apagar.php';

$id_especialidade = $_GET['esp'];
$id_usuario = $_SESSION['id_usuario'];
    
$resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario,$id_especialidade);

if(count($resultado_verificacao) == 0)
{
    erro("Erro 5442654: Página não encontrada!");
    exit();
}
else
{
    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
    $cpf_candidato      = $resultado_verificacao[0]['cpf'];
    $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
    
}

$get_prioridade = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Especialidade <?php echo strtoupper($ott_stt) . " - " . $nome_especialidade ?> <i class="fa fa-files-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Detalhamento da especialidade</li>
      </ul>
    </div>
  </div>
    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <legend>Selecione a(s) prioridade(s) da(s) cidade(s) para servir</legend>
            <div class="row">
                <form method="post" action="../banco_dados/candidato_cadastra_prioridade.php" >
                <div class="col-md-6">
                    <input hidden name="esp" value="<?php echo $id_especialidade ?>">
                    <div class="form-group"> 
                        <label>Selecione a CIDADE</label>
                        <select name="id_cidade" class="form-control">
                            <option value="">Selecione a cidade</option>
                            <?php
                            
                                $lista_cidades = $conexao->get_cidades_especialidade_candidato($id_especialidade, $id_candidato_x_especialidade);  
                                foreach ($lista_cidades as $linha) 
                                {
                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                }
                            ?> 
                        </select>
                    </div>
                    
                    <div class="form-group"> 
                        <label>Selecione a PRIORIDADE</label>
                        <select name="prioridade" class="form-control">
                            <option value="">Selecione a prioridade</option>
                            <?php
                                $quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($id_especialidade);  
                                $quantidade_cidades = $quantidade_cidades[0]['quantidade'];

                                for($i = 1; $i <= $quantidade_cidades; $i++)
                                {
                                    $imprime = 1;
                                    foreach ($get_prioridade as $linha2) 
                                    {
                                        if($linha2['prioridade'] == $i)
                                            $imprime = 0;
                                    }
                                    if($imprime == 1)
                                        echo '<option value="'.$i.'">Prioridade Nº '.$i.' </option>';
                                }
                            ?> 
                        </select>
                    </div>
                    
                    <input hidden type="text" name="crip" value="<?php echo hash('sha256', $_SESSION['cpf']."freitas") ?>">
                    
                    <div class="form-group"> 
                        <input type="submit" class="btn btn-primary btn-block" value="Cadastrar Prioridade" />
                    </div>
                </div>
                    
                    <div class="col-md-6">
                    <div class="form-group"> 
                        <table class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th>Cidade</th>
                                <th>Prioridade</th>
                                <th>Apagar</th>
                              </tr>
                            </thead>
                                <tbody>

                                <?php
                                    foreach ($get_prioridade as $linha3) 
                                    {
                                        $crip = hash('sha256', $_SESSION['apagado']."freitas".$linha3['id_prioridade_cidade']);
                                        echo '
                                        <tr>
                                            <td>'.$linha3['nome'].'</td>
                                            <td>'.$linha3['prioridade'].'</td>
                                            <td><a onclick="funcao_apagar(\''.$linha3['id_prioridade_cidade'].'\', \'prioridade_cidade_candidato\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                        </tr>';
                                    }
                                ?>   

                            </tbody>
                        </table>
                    </div>
                    </div>
                </form>
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