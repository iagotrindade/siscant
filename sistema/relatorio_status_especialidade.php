<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 632457437! Página não encontrada!");
        exit();
    }
    
    $id_especialidade_selecionada = 0;
    $desistencias = false;
    $select_candidatos = null;
    $lista_candidatos = array();
    
    
    
    if(isset($_GET['id_especialidade']) && $_GET['id_especialidade'] != null) $id_especialidade_selecionada = (int)$_GET['id_especialidade'];
    if(isset($_GET['select_desistencia']) && $_GET['select_desistencia'] == 'desistencia') $desistencias = true;
    
    if(isset($_GET['select_candidatos']) && $_GET['select_candidatos'] != null || $desistencias || $id_especialidade_selecionada > 0)
    {
        $lista_candidatos = $conexao->get_candidatos();
        $select_candidatos = $_GET['select_candidatos'];
    }
    
    $todos_candidatos = false;
    $candidatos_concorrendo = false;
    $candidatos_desclassificados = false;
    
    if($select_candidatos == "todos") $todos_candidatos = true;
    if($select_candidatos == "concorrendo") $candidatos_concorrendo = true;
    if($select_candidatos == "desclassificados") $candidatos_desclassificados = true;
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatório completo das especialidades <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatório completo das especialidades</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        
        
    <div class="card">
         <div class="row">
             <form name="fomulario" action="relatorio_status_especialidade.php" method="get">
                 <div class="col-md-4">
                    <div class="card-body">
                        <label>Candidatos </label>
                        <select onchange="submit()" name="select_candidatos" class="form-control" >
                            <option value="">Selecione a opção</option>
                            <option <?php if($todos_candidatos) echo " selected " ?> value="todos">TODOS os candidatos</option>
                            <option <?php if($candidatos_concorrendo) echo " selected " ?> value="concorrendo">Candidatos CONCORRENDO no processo seletivo</option>
                            <option <?php if($candidatos_desclassificados) echo " selected " ?> value="desclassificados">Candidatos DESCLASSIFICADOS do processo seletivo</option>
                        </select>
                    </div>
                </div>
                 
                <div class="col-md-4">
                    <div class="card-body">
                        <label>Selecione a especialidade desejada </label>
                        <select onchange="fomulario.submit()" name="id_especialidade" class="form-control" >
                            <option value="">Todas</option>
                            
                            <?php
                            
                                $resultado = $conexao->get_especialidade(); 
                                foreach ($resultado as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                 
                <div class="col-md-4">
                    <div class="card-body">
                        <label>Desistências do processo</label>
                        <select onchange="fomulario.submit()" name="select_desistencia" class="form-control" >
                            <option value="">Sem restrição</option>
                            <option <?php if($desistencias) echo " selected " ?> value="desistencia">Todas as especialidades com situação de desistências</option>
                            
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
        
        
    <div class="card">
        <legend>Candidatos e especialidades</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Concorrendo no processo</th>
                      <th>Especialidade</th>
                      <th>Cidade escolhida</th>
                      <th>Guarnição de destino</th>
                      <th>Distribuido</th>
                      <th>Última Atualização</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $data_atual = new DateTime(date("Y-m-d"));
                        
                        foreach ($lista_candidatos as $linha) 
                        {
                            $incorporado = null;
                            if($linha['incorporado'] == '1') $incorporado = "_Sim";
                            if($linha['incorporado'] == '0') $incorporado = "_Não";
                            
                            if($linha['concorrendo'] == '1' && $candidatos_desclassificados) continue;
                            if($linha['concorrendo'] == '0' && $candidatos_concorrendo) continue;
                            
                            $concorrendo_processo = '<font color="red">Não</font>';
                            if($linha['concorrendo'] == '1') $concorrendo_processo = '<font color="green">Sim</font>';
                            
                            $inscricoes = $conexao->get_especialidade_candidato($linha['id']);
                            
                            if(count($inscricoes) == 0) continue;

                            $especialidades_do_candidato = null;
                            foreach ($inscricoes as $valor)
                            {
                                
                                if($desistencias && $valor['justificativa'] != 'Cod 754809 - O Sr(a) NÃO OPTOU pelas guarnições oferecidas. Caso não sejam oferecidas novas vagas no futuro, o Sr(a) não será incorporado(a) como militar temporário.') continue;
                                
                                $id_candidato_x_especialidade = null;
                                $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'],$valor['id_especialidade']);

                                if($id_especialidade_selecionada > 0 && $id_especialidade_selecionada != $valor['id_especialidade']) continue;
                                
                                $concorrendo = null;
                                if($valor['concorrendo'] == '1') $concorrendo = "";
                                if($valor['concorrendo'] == '0') $concorrendo = '<font color="red"> Desclassificado: '.$valor['justificativa'].' </font>';
                                
                                if(count($resultado_verificacao) > 0)
                                {
                                    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                                    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
                                    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
                                    
                                    $ultima_atualizacao = null;
                                    if($resultado_verificacao[0]['_data_ultima_atualizacao'] != null)
                                        $ultima_atualizacao = trata_data ($resultado_verificacao[0]['_data_ultima_atualizacao']);
                                    
                                    $especialidades_do_candidato =  strtoupper($ott_stt) . " " . $nome_especialidade . " " . $concorrendo;
                                    
                                    $guarnicao_destino = null;
                                    if($linha['id_cidade_distribuicao'] != null)
                                    {
                                        $resultado_cidade = $conexao->get_cidade_id($linha['id_cidade_distribuicao']);
                                        $guarnicao_destino  = $resultado_cidade[0]['nome'];
                                    }
                                        
                                    
                                    echo '
                                            <tr>
                                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                            <td>'.$linha['nome_completo'].'</td>
                                            <td>'.$concorrendo_processo.'</td>
                                            <td><a href="relatorio_especialidade_candidato.php?id_especialidade='.$id_especialidade.'">'.$especialidades_do_candidato.'</a></td>
                                            <td>'.$resultado_verificacao[0]['nome_cidade'].'</td>
                                            <td>'.$guarnicao_destino.'</td>
                                            <td>'.$incorporado.'</td>
                                            <td>'.$ultima_atualizacao.'</td>
                                            </tr>'
                                        ;
                                    
                                }
                            }
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
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>