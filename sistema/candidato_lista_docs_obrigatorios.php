<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'avaliador' || $perfil == 'ouvidor')
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    
    
    $id_especialidade_selecionada = null;
    if(isset($_GET['id_especialidade']))
        $id_especialidade_selecionada = $_GET['id_especialidade'];
    
    if($id_especialidade_selecionada != null) 
        $lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);
    else
        $lista_candidatos = $conexao->get_candidatos_concorrendo();
    
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatório de documentos obrigatórios <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Candidatos</li>
      </ul>
    </div>
  </div>
    
    
    
    <div class="row">
    <div class="col-md-12">
        <form name="fomulario" action="candidato_lista_docs_obrigatorios.php" method="get">
            <div class="card">
                <div class="card-body">
                    <label>Selecione a especialidade desejada </label>
                    
                    <select onchange="fomulario.submit()" name="id_especialidade" class="form-control" >
                        <option value="">Selecione a especialidade</option>
                        <?php
                        
                            if($avaliador)
                            {
                                foreach ($lista_especialidade_avaliador as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id_especialidade'])
                                        echo '<option selected value="'.$value['id_especialidade'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id_especialidade'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            }
                            else
                            {
                                $resultado = $conexao->get_especialidade(); 
                                foreach ($resultado as $value) 
                                {
                                    if($id_especialidade_selecionada == $value['id'])
                                        echo '<option selected value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value['id'].'">'. mb_strtoupper($value['ott_stt'], "UTF-8") . " - ".$value['nome'].'</option>';
                                }
                            }
                        ?>
                    </select>
                    
                </div>
            </div>
            </form>
    </div>
    </div>
    
    
    
  <div class="row">
    <div class="col-md-12">
    <div class="card">
        <legend>Avaliação dos documentos obrigatórios</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Código</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Arq Pag</th>
                      <th>Etapa</th>
                      <th>Especialdiades</th>
                      <th>Docs Faltando</th>
                      <th>Docs Adicionados</th>
                      <th>Docs Válidos</th>
                      <th>% avaliado</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $total_docs_adicionados = 0;
                        $total_docs_avaliados = 0;
                        foreach ($lista_candidatos as $linha) 
                        {
                            $arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($linha['id']);
                            $add_arqu_pag = "_Não";
                            if(count($arquivo_pagamento) > 0) $add_arqu_pag = "_Sim";
                            /*
                            $get_pagamento_candidato = $conexao->get_arquivo_pagamento($linha['id']); 
                            
                            $pagou = "";
                            if(count($get_pagamento_candidato) > 0)
                                $pagou = "Sim";
                            else 
                                $pagou = "Não";
                             */
                            
                            // DOCs Faltando
                            $quantidade_docs_faltando = 0;
                            $lista_docs_obrigatorios_faltando = $conexao->get_documentos_obrigatorios_sobrando_candidato($linha['id']); 
                            $get_candidato = $conexao->get_usuario_id($linha['id']);
                            $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato,$lista_docs_obrigatorios_faltando);
                            $quantidade_docs_faltando = count($lista_docs_obrigatorios_sobrando);
                            
                            // Especialidades
                            $lista_especialidades_candidato = $conexao->get_especialidade_candidato($linha['id']); 
                            $quantidade_especialidades_candidato = count($lista_especialidades_candidato);
                            
                                                        
                            //
                            
                            $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($linha['id']);  
                            $quantidade_docs_adicionados = count($lista_docs_obrigatorios);
                            
                            $quantidade_docs_avaliados = 0;
                            $quantidade_docs_validos = 0;
                            
                            foreach ($lista_docs_obrigatorios as &$doc)
                            {
                                if($doc['valido'] != null)
                                    $quantidade_docs_avaliados++;
                                
                                if($doc['valido'] == '1')
                                    $quantidade_docs_validos++;
                            }
                            
                            
                            $total_docs_adicionados = $total_docs_adicionados + (int)$quantidade_docs_adicionados;
                            $total_docs_avaliados = $total_docs_avaliados + $quantidade_docs_avaliados;
                            
                            $porcentagem = null;
                            
                            if($quantidade_docs_avaliados != 0 && $quantidade_docs_adicionados != 0)
                                $porcentagem = ($quantidade_docs_avaliados/$quantidade_docs_adicionados) * 100;
                            
                            $cor = null;
                            
                            if($porcentagem < 100)
                                $cor = '#fefe85';
                            
                            if($porcentagem < 60)
                                $cor = '#ffa74f';
                            
                            if($porcentagem < 30)
                                $cor = '#fd8a8a';
                            
                            if($porcentagem == 100)
                                $cor = '#adf54d';
                            
                            /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                            
                            $codigo_final = substr((string)$linha['id'], -1);
                            
                            $cor_docs_validos = "";
                            
                            if($quantidade_docs_validos != $quantidade_docs_adicionados && $porcentagem == 100)
                                $cor_docs_validos = '#fd8a8a';
                            
                                echo '
                                <tr>
                                    <td>'.$linha['id'].'-'.$codigo_final.'</td>
                                    <td>'.$linha['cpf'].'</td>
                                    <td>'.$linha['nome_completo'].'</td>
                                    <td>'.$add_arqu_pag.'</td>                                    
                                    <td>_'.$linha['etapa'].'</td>                                    
                                    <td>'.$quantidade_especialidades_candidato.'</td>                                    
                                    <td>'.$quantidade_docs_faltando.'</td>                                    
                                    <td>'.$quantidade_docs_adicionados.'</td>
                                    <td bgcolor="'.$cor_docs_validos.'">'.$quantidade_docs_validos.'</td>
                                    <td bgcolor="'.$cor.'">'.round($porcentagem,2).' %</td>
                                    <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">Ver</a></td>
                                </tr>';
                        }
                        // <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        <div class="card">
            <div class="row">
            <font size="5px"> 
            <div class="col-md-4">
                Total de docs adicionados: <?php echo $total_docs_adicionados ?> 
            </div>
            <div class="col-md-4">    
                Total de docs avaliados: <?php echo $total_docs_avaliados ?> 
            </div>
            <div class="col-md-4">    
                Total: 
                    <?php 
                        if($total_docs_avaliados != 0 && $total_docs_adicionados != 0 )
                        {
                            $porcentagem = ($total_docs_avaliados/$total_docs_adicionados) * 100;
                            $porcentagem = number_format($porcentagem, 2, ',', '');
                            echo $porcentagem . "%";
                        }
                    ?> 
            </div>
            </font>
        </div>
        </div>
        
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 0, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>