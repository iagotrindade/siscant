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
    
    $lista_docs_obrigatorios = $conexao->get_documentos_obrigatorios_cadastrados(); 
    
    $doc_selecionado = null;
    if(isset($_GET['curriculo_selecionado']))
        $doc_selecionado = (int)$_GET['curriculo_selecionado'];
    
    $candidatos_selecionados = null;
    if(isset($_GET['candidatos']))
        $candidatos_selecionados = $_GET['candidatos'];
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Documento Obrigatório Faltando </h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Documento Obrigatório Faltando</li>
      </ul>
    </div>
  </div>
    <div class="card">
  <div class="row">
      
        <form name="fomulario" action="doc_obrigatorio_faltando_candidato.php" method="get">
            
        <div class="col-md-6">
            <div class="card-body">
                <label>Selecione os candidatos <font color="red"> *Obrigatório</font></label>
                    <select onchange="fomulario.submit()" name="candidatos" class="form-control">
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
            
        <div class="col-md-6">
            <div class="card-body">
                <label>Selecione o Documento Obrigatório NÃO ANEXADO pelo candidato</label>
                    <select name="curriculo_selecionado" class="form-control">
                        <option value="">Selecione a opção</option>    
                        <?php
                            foreach ($lista_docs_obrigatorios as $linha) 
                            {
                                if($doc_selecionado == $linha['id'])
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
                      <th>Status</th>
                      <th>Nº Docs Faltando</th>
                      <th>Especialidade(s)</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $lista_candidatos = array();
                        if($candidatos_selecionados != null)
                            //$lista_candidatos = $conexao->get_todos_candidatos();  
                            $lista_candidatos = $conexao->get_candidatos_desc_class();  
                        
                        foreach ($lista_candidatos as $candidato) 
                        {
                            
                            if($candidato['medico_obrigatorio'] == 1) continue;
                            
                            if($candidatos_selecionados == 'concorrendo' && $candidato['concorrendo'] == 0) continue;
                            if($candidatos_selecionados == 'desclassificados' && $candidato['concorrendo'] == 1) continue;
                            
                            $lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($candidato['id']);  
                            $get_candidato = $conexao->get_usuario_id($candidato['id']);
                            $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato,$lista_docs_obrigatorios_sobrando_candidato);
                            
                            $possui_documento = true;
                            
                            foreach($lista_docs_obrigatorios_sobrando as $documento)
                            {
                                //$lista_docs = $lista_docs. $documento['nome'];
                                if($doc_selecionado == $documento['id'])
                                {
                                    $possui_documento = false;
                                    break;
                                }
                            }
                            
                            $quantidade_docs_faltando = count($lista_docs_obrigatorios_sobrando);
                            
                            $nome_especialidades = null;
                            
                            $especialidades = $conexao->get_especialidade_candidato($candidato['id']);
                            $i = 1;
                            foreach($especialidades as $especialidade)
                            {
                                $nome_especialidades = $nome_especialidades . $i . "ª " . mb_strtoupper($especialidade['ott_stt'], "UTF-8") ." " . $especialidade['especialidade'] . " ";
                                $i++;
                            }
                            
                            if($doc_selecionado != null)
                                if($possui_documento) continue;
                            
                            if($doc_selecionado == null && $quantidade_docs_faltando == 0) continue;
                            
                            $status = null;
                            if($candidato['concorrendo'] == 0)
                                $status = 'Desclassificado';
                            if($candidato['concorrendo'] == 1)
                                $status = 'Concorrendo';

                            echo '
                            <tr>
                            <td><a href="usuario_visualiza.php?id_usuario='.$candidato['id'].'">'.$candidato['cpf'].'</a></td>
                            <td>'.$candidato['nome_completo'].'</td>
                            <td>'.$status.'</td>
                            <td>_'.$quantidade_docs_faltando.'_</td>
                            <td>'.$nome_especialidades.'</td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$candidato['id'].'">Ver</a></td>
                            </tr>';
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 4, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>