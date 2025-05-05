<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 235235! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 234235! Página não encontrada!");
        exit();
    }
    
    $lista_curriculos = $conexao->get_curriculo_cadastrados(); 
    
    $curriculo_1 = null;
    if(isset($_GET['curriculo_1']))
        $curriculo_1 = (int)$_GET['curriculo_1'];
    
    $curriculo_2 = null;
    if(isset($_GET['curriculo_2']))
        $curriculo_2 = (int)$_GET['curriculo_2'];
    
    $curriculo_3 = null;
    if(isset($_GET['curriculo_3']))
        $curriculo_3 = (int)$_GET['curriculo_3'];
    
    $candidatos_selecionados = null;
    if(isset($_GET['candidatos']))
        $candidatos_selecionados = $_GET['candidatos'];
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Currículos faltantes</h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Currículos faltantes</li>
      </ul>
    </div>
  </div>
    <div class="card">
  <div class="row">
      
      <div class="col-md-12">
      <legend>O relatório irá trazer os candidatos que não possuem os currículos selecionados</legend>
      </div>
        <form name="fomulario" action="curriculos_faltando_candidatos.php" method="get">
            
        <div class="col-md-3">
            <div class="card-body">
                <label>Selecione os candidatos</label>
                    <select name="candidatos" class="form-control">
                        <option value="">Selecione a opção</option>    
                        <?php
                            if($candidatos_selecionados == 'concorrendo')
                                echo '<option selected value="concorrendo">Candidatos Concorrendo</option>';
                            else
                                echo '<option value="concorrendo">Candidatos Concorrendo</option>';
                            
                            if($candidatos_selecionados == 'desclassificados')
                                echo '<option selected value="desclassificados">Candidatos Desclassificados</option>';
                            else
                                echo '<option value="desclassificados">Candidatos Desclassificados</option>';
                            
                            if($candidatos_selecionados == 'todos')
                                echo '<option selected value="todos">Todos</option>';
                            else
                                echo '<option value="todos">Todos</option>';
                        ?>
                    </select> 
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="card-body">
                <label>Selecione o currículo para pesquisa</label>
                    <select name="curriculo_1" class="form-control">
                        <option value="">Selecione a opção</option>    
                        <?php
                            foreach ($lista_curriculos as $linha) 
                            {
                                if($curriculo_1 == $linha['id'])
                                    echo '<option selected value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                else
                                    echo '<option  value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                            }
                        ?>
                    </select> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-body">
                <label>Selecione o currículo para pesquisa</label>
                    <select name="curriculo_2" class="form-control">
                        <option value="">Selecione a opção</option>    
                        <?php
                            foreach ($lista_curriculos as $linha) 
                            {
                                if($curriculo_2 == $linha['id'])
                                    echo '<option selected value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                else
                                    echo '<option  value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                            }
                        ?>
                    </select> 
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-body">
                <label>Selecione o currículo para pesquisa</label>
                    <select  name="curriculo_3" class="form-control">
                        <option value="">Selecione a opção</option>    
                        <?php
                            foreach ($lista_curriculos as $linha) 
                            {
                                if($curriculo_3 == $linha['id'])
                                    echo '<option selected value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                                else
                                    echo '<option  value="'.$linha['id'].'">'.$linha['nome'].'</option>';
                            }
                        ?>
                    </select> 
            </div>
        </div>
            
            <div class="col-md-12">
                <br>
                <button  type="submit"  class="btn btn-primary btn-block">PESQUISAR</button>
            </div>
        
        </form>
    </div>
</div>
        
        
<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica" >
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Status no PS</th>
                      <th>Etapa</th>
                      <th>Qtd Currículos</th>
                      <th>OTT/STT</th>
                      <th>Especialidade(s)</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $lista_candidatos = null;
                        if($candidatos_selecionados != null)
                            $lista_candidatos = $conexao->get_candidatos_desc_class();  
                        
                        foreach ($lista_candidatos as $candidato) 
                        {
                            
                            if($candidato['medico_obrigatorio'] == 1) continue;
                            
                            if($candidatos_selecionados == 'concorrendo' && $candidato['concorrendo'] == 0) continue;
                            if($candidatos_selecionados == 'desclassificados' && $candidato['concorrendo'] == 1) continue;
                            
                            $inscricoes = $conexao->get_especialidade_candidato($candidato['id']);

                            foreach($inscricoes as $especialidade)
                            {
                                
                                $lista_curriculos = $conexao->get_curriculos_inseridos_candidato($candidato['id'],$especialidade['id_especialidade']);
                                
                                $exibe_linha = true;
                                $quantidade_curriculos = 0;
                                foreach ($lista_curriculos as $curriculo)
                                {
                                    if($curriculo_1 != null && ($curriculo['id_curriculo'] == $curriculo_1))
                                        $exibe_linha = false;
                                    
                                    if($curriculo_2 != null && ($curriculo['id_curriculo'] == $curriculo_2))
                                        $exibe_linha = false;
                                    
                                    if($curriculo_3 != null && ($curriculo['id_curriculo'] == $curriculo_3))
                                        $exibe_linha = false;
                                    
                                    $quantidade_curriculos++;
                                }
                                
                                if(!$exibe_linha) continue;

                                $status = null;
                                if($candidato['concorrendo'] == 0)
                                    $status = 'Desclassificado';
                                if($candidato['concorrendo'] == 1)
                                    $status = 'Concorrendo';

                                $concorrendo_na_especialidade = "black";
                                if($especialidade['concorrendo'] == 0) $concorrendo_na_especialidade = "red";

                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$candidato['id'].'">'.$candidato['cpf'].'</a></td>
                                <td>'.$candidato['nome_completo'].'</td>
                                <td>'.$status.'</td>
                                <td>_'.$candidato['etapa'].'</td>
                                <td>'.$quantidade_curriculos.'</td>
                                <td>_'.mb_strtoupper($especialidade['ott_stt'], "UTF-8").'</td>
                                <td><font color="'.$concorrendo_na_especialidade.'">'.$especialidade['especialidade'].'<font></td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$candidato['id'].'">Ver</a></td>
                                </tr>';
                                
                            }
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 3, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>