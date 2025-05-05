<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if(($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) || $perfil != 'admin' && $perfil != 'consulta' && $perfil != 'avaliador')
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    $especialidade_selecionado = null;
    if(isset($_GET['especialidade_selecionado']))
        $especialidade_selecionado = (int)$_GET['especialidade_selecionado'];
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Pontuação automática <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Pontuação Automática</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        
            <div class="card">
                <div class="row">
                <div class="col-md-6">
                    
                        <label>Selecione a especialidade</label>
                        <form name="fomulario" action="pontuacao_automatica.php" method="get">
                            <select onchange="fomulario.submit()" name="especialidade_selecionado" class="form-control">
                                <option value="">Selecione a especialidade</option>
                                <?php
                                   
                                    $resultado = $conexao->get_especialidade(); 
                                    foreach ($resultado as $value) 
                                    {
                                        if($especialidade_selecionado == $value['id'])
                                            echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                        else
                                            echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    }
                                    
                                    
                                ?>
                            </select> 
                     </form>
                </div>
                
                <div class="col-md-6">
                Todas as especialidades
                <a href="excel_pontuacao_automatica.php" target="blank"><img src="imagens/ods.png" height="50px"></a>
                    <br>
                </div>
            </div>
            </div>
            
       
        
        
    <div class="card">
        <legend>
            Classificação por pontuação automática
        </legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>Especialidade</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Pontuação</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    if($especialidade_selecionado != null)
                    {
                        $lista_candidatos = $conexao->get_todos_candidatos();
                    
                        foreach ($lista_candidatos as $linha) 
                        {
                            $id_usuario = $linha['id'];
                            
                            $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                            foreach ($inscricoes as $valor)
                            {
                                $pontuacao_final = null;        
                                $id_especialidade = $valor['id_especialidade'];
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
                                $pontuacao_total = 0;
                                
                                if($especialidade_selecionado != null)
                                {
                                    if($id_especialidade != $especialidade_selecionado) continue;
                                }

                                //$data_habilitacao = $valor['data_habilitacao'];

                                $data_habilitacao = new DateTime(date($valor['data_habilitacao']));
                                
                                foreach ($lista_curriculo_adicionado as $curriculo) 
                                {
                                    $data_inicio_original = null;
                                    $data_fim_original = null;
                                    $total_de_dias = 0;
                                    $pontuacao = 0;

                                    if($curriculo['carga_horaria_obrigatoria'] == '1')
                                    {
                                        if($curriculo['data_inicio'] != null)
                                            $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                        if($curriculo['data_termino'] != null)
                                            $data_fim_original = reverte_data($curriculo['data_termino']);

                                        $data_inicio = new DateTime(date($data_inicio_original));

                                        if ($_SESSION['selecao_regiao'] != 7) {
                                           
                                            if ($data_inicio < $data_habilitacao) {
                                                $data_inicio = $data_habilitacao;
                                            }
                                        }
                                       
                                        $data_fim = new DateTime(date($data_fim_original));
                                        $intervalo = $data_fim->diff($data_inicio);
                                        
                                        if($data_inicio > $data_fim) continue;

                                        $total_de_dias = (int)$intervalo->format('%a');

                                        $pontuacao = ($curriculo['pontuacao'] * $total_de_dias)/1000;
                                    }
                                    else
                                    {
                                        $pontuacao = $curriculo['pontuacao']/1000;
                                    }

                                    $pontuacao_final = $pontuacao_final + $pontuacao;
                                }

                                if($pontuacao_final != null)
                                    $pontuacao_final = str_replace('.', ',', $pontuacao_final);             
                                
                                echo '
                                    <tr>
                                        <td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].'</td>
                                        <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                        <td>'.$linha['nome_completo'].'</td>
                                        <td>'.$pontuacao_final.'</td>
                                    </tr>';
                                
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        
        
    </div>
        
        <div class="card">
            <legend>
                Todos os currículos da especialidade selecionada
            </legend>
            
            <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Especialidade</th>
                      <th>Currículo</th>
                      <th>Habilitação</th>
                      <th>Data Início</th>
                      <th>Data Fim</th>
                      <th>Dias</th>
                      <th>Válido</th>
                      <th>Pontuação</th>
                    </tr>
                </thead>
                <tbody>

                 
                    <?php
                     ####PONTUAÇÃO AUTOMATICA 7RM
                     $lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
                        foreach ($lista_inscricoes as $value) 
                        {
                            
                            $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'],$value['id_especialidade']);
                            $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);
                            
                            $pontuacao_final = 0; 
                            
                            foreach ($lista_docs_obrigatorios as $curriculo) 
                            {
                                $data_inicio_original = null;
                                $data_fim_original = null;
                                $total_de_dias = null;
                                $pontuacao = null;
                                    
                                if($curriculo['carga_horaria_obrigatoria'] == '1')
                                {
                                    if($curriculo['data_inicio'] != null)
                                        $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                    if($curriculo['data_termino'] != null)
                                        $data_fim_original = reverte_data($curriculo['data_termino']);

                                    $data_inicio = new DateTime(date($data_inicio_original));
                                    $data_fim = new DateTime(date($data_fim_original));
                                    $intervalo = $data_fim->diff($data_inicio);

                                    $total_de_dias = (int)$intervalo->format('%a');

                                    $pontuacao = ($curriculo['pontuacao'] * $total_de_dias)/1000;
                                }
                                else
                                {
                                    $pontuacao = $curriculo['pontuacao']/1000;
                                }

                                $pontuacao_final = $pontuacao_final + $pontuacao;
                                

                               if($_SESSION['selecao_regiao'] == 7) 
                                {
                                    echo '
                                        <tr>
                                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                            <td>'.$linha['nome_completo'].'</td>
                                            <td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].'</td>
                                            <td>'.$cirriculo['nome_curriculo'].'</td>
                                            <td>'.$data_habilitacao->format('d/m/Y').'</td>
                                            <td>'.$data_inicio_original .'</td>
                                            <td>'.$data_fim->format('d/m/Y').'</td>
                                            <td>'.$total_de_dias.'</td>
                                            <td>'.$validado.'</td>
                                            <td>'.$pontuacao_final.' </td>
                                        </tr>';
                                }

                            }
                        }


                    ####PONTUAÇÃO AUTOMATICA NORMAL
                    if($especialidade_selecionado != null)
                    {
                        $lista_candidatos = $conexao->get_todos_candidatos();
                    
                        foreach ($lista_candidatos as $linha) 
                        {
                            $id_usuario = $linha['id'];
                            
                            $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                            foreach ($inscricoes as $valor)
                            {
                                        
                                $id_especialidade = $valor['id_especialidade'];
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
                                $pontuacao_total = 0;

                                $data_habilitacao = new DateTime(date($valor['data_habilitacao']));
                                
                                if($id_especialidade != $especialidade_selecionado) continue;
                                
                                foreach ($lista_curriculo_adicionado as $cirriculo) 
                                {
                                    
                                    $data_inicio_original = null;
                                    $data_fim_original = null;
                                    $total_meses = null;
                                    $total_de_dias = null;
                                    $total_dias = null;
                                    $pontuacao = null;
                                    
                                    if($cirriculo['carga_horaria_obrigatoria'] == '1')
                                   
                                    {
                                        //$pontuacao = (int)$cirriculo['pontuacao']/1000;
                                        
                                        if($cirriculo['data_inicio'] != null)
                                            $data_inicio_original = reverte_data($cirriculo['data_inicio']);
                                        if($cirriculo['data_termino'] != null)
                                            $data_fim_original = reverte_data($cirriculo['data_termino']);

                                        $data_inicio = new DateTime(date($data_inicio_original));
                                        $data_fim = new DateTime(date($data_fim_original));
                                        
                                    //  if($data_inicio < $data_habilitacao) $data_inicio = $data_habilitacao;

                                        $intervalo = $data_fim->diff($data_inicio);

                                        $anos = (int)$intervalo->format('%Y');
                                        $meses = (int)$intervalo->format('%m');
                                        $dias = (int)$intervalo->format('%d');
                                        
                                        $total_de_dias = (int)$intervalo->format('%a');

                                        if($data_inicio > $data_fim) $total_de_dias = 0;

                                        $total_dias = null;
                                        if($data_inicio != null && $data_fim != null)
                                        {
                                            $dias_anos = $anos*365;
                                            $dias_meses = $meses*30;
                                            $total_dias = $dias_anos + $dias_meses + $dias +1;
                                        }
                                        $total_meses = $total_dias/30;
                                        $total_meses = round($total_meses,2);

                                        $pontuacao = ($cirriculo['pontuacao'] * $total_de_dias)/1000;
                                    }

                                    else

                                    {
                                        $pontuacao = $cirriculo['pontuacao']/1000;
                                    }
                                    
                                    $validado = null;
                                    if($cirriculo['valido'] == '1') $validado = "_Sim";
                                    if($cirriculo['valido'] == '0') $validado = "_Não";
                                    
                                    if($_SESSION['selecao_regiao'] != 7)
                                        {
                                        echo '
                                        <tr>
                                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                            <td>'.$linha['nome_completo'].'</td>
                                            <td>'.mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'].'</td>
                                            <td>'.$cirriculo['nome_curriculo'].'</td>
                                            <td>'.$data_habilitacao->format('d/m/Y').'</td>
                                            <td>'.$data_inicio_original .'</td>
                                            <td>'.$data_fim->format('d/m/Y').'</td>
                                            <td>'.$total_de_dias.'</td>
                                            <td>'.$validado.'</td>
                                            <td>'.$pontuacao.' TESTE</td>
                                        </tr>';
                                        }
                                }
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
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 3, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 1, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>