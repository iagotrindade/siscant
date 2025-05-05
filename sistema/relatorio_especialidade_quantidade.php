<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'documentos' || $perfil == 'ouvidor')
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    $avaliador = false;
    if($_SESSION['perfil'] == "avaliador")
    {
        $avaliador = true;
        $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
    }
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatório de Especialidade X Número de Candidatos <i class="fa fa-file-text"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Especialidade X Candidatos</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Especialidade X Candidatos</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Categoria</th>
                      <th>Nome da Especialidade</th>
                      <th>Total inscritos</th>
                      <th>Classificados</th>
                      <th>Vagas disponibilizadas</th>
                      <th>Vagas restantes</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $lista_especialidade = $conexao->get_especialidade(); 
                    
                        if($avaliador)
                        {
                            foreach ($lista_especialidade_avaliador as $linha_avaliador) 
                            {            

                                $quantidade_candidatos = 0;
                                $get_quantidade = $conexao->get_quantidade_candidatos_especialidade($linha_avaliador['id_especialidade']); 
                                if(count($get_quantidade) > 0)
                                    $quantidade_candidatos = $get_quantidade[0]['quantidade_candidatos'];

                                echo '
                                <tr>
                                    <td width="90px">'.strtoupper($linha_avaliador['ott_stt']).'</td>
                                    <td>'.$linha_avaliador['nome'].'</td>
                                    <td width="150px">'.$quantidade_candidatos.'</td>
                                    <td width="50px"><center><a href="relatorio_especialidade_candidato.php?id_especialidade='.$linha_avaliador['id_especialidade'].'"><img title="Visualizar" class="img-circle" src="imagens/lupa.png" width="40px"></a></center></td>
                                </tr>';
                            }
                        }
                        else
                        {
                            foreach ($lista_especialidade as $linha) 
                            {
                                
                                $lista_vagas_disponibilizadas = $conexao->get_cidades_especialidade($linha['id']); 
                                $lista_vagas_restantes = $conexao->get_vagas_especialidade($linha['id']);
                                $lista_quantidade_cadastrados = $conexao->get_candidatos_especialidade_desclassificados_nao_med_obr($linha['id']);
                                
                                $quantidade_cadastrados = count($lista_quantidade_cadastrados);
                                
                                $vagas_disponibilizadas = 0;
                                $vagas_restantes = 0;
                                foreach ($lista_vagas_disponibilizadas as $linha5) 
                                {
                                    $vagas_disponibilizadas = $vagas_disponibilizadas + (int)$linha5['numero_vagas'];
                                }
                                foreach ($lista_vagas_restantes as $linha8) 
                                {
                                    $vagas_restantes = $vagas_restantes + (int)$linha8['vagas'];
                                }
                                
                                $cor_vagas_restantes = null;
                                if($vagas_disponibilizadas > 0 && $vagas_restantes == 0) $cor_vagas_restantes = " adf54d ";
                                if($vagas_disponibilizadas > 0 && $vagas_restantes > 0) $cor_vagas_restantes = " fefe85 ";
                                if($vagas_disponibilizadas > 0 && $vagas_restantes == $vagas_disponibilizadas) $cor_vagas_restantes = " fd8a8a ";
                                
                                
                                $quantidade_candidatos = 0;
                                $get_quantidade = $conexao->get_quantidade_candidatos_especialidade($linha['id']); 
                                if(count($get_quantidade) > 0)
                                    $quantidade_candidatos = $get_quantidade[0]['quantidade_candidatos'];
                                echo '
                                <tr>
                                    <td width="90px">'.strtoupper($linha['ott_stt']).'</td>
                                    <td>'.$linha['nome'].'</td>
                                    <td>'.$quantidade_cadastrados.'</td>
                                    <td width="150px">'.$quantidade_candidatos.'</td>
                                    <td width="150px">'.$vagas_disponibilizadas.'</td>
                                    <td bgcolor="'.$cor_vagas_restantes.'" width="150px">'.$vagas_restantes.'</td>
                                    <td width="50px"><center><a href="relatorio_especialidade_candidato.php?id_especialidade='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="imagens/lupa.png" width="40px"></a></center></td>
                                </tr>';
                            }
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
    <div class="card" <?php if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") echo "hidden" ?>>
        <legend>Avaliação de currículos</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>Especialidade</th>
                      <th>Avaliado</th>
                      <th>Não_Avaliado</th>
                      <th>Total</th>
                      <th>Porcentagem</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $relacao_docs_nao_avaliados = $conexao->get_lista_docs_avaliados_por_especialidade();  
                    
                        foreach ($relacao_docs_nao_avaliados as $linha) 
                        {
                            $nome = "";
                            if($linha['nome_tab1'] != null) $nome = $linha['nome_tab1'];
                            else $nome = $linha['nome_tab2'];
                            $id = 0;
                            if($linha['id_tab1'] != null) $id = $linha['id_tab1'];
                            else $id = $linha['id_tab2'];
                            
                            $total = (int)$linha['quantidade_avaliado'] + (int)$linha['quantidade_nao_avaliado'];
                            
                            $porcentagem = null;
                            
                            if((int)$linha['quantidade_avaliado'] != 0 && $total != 0)
                                $porcentagem = ((int)$linha['quantidade_avaliado']/$total) * 100;
                            
                            $cor = null;
                            
                            if($porcentagem < 100)
                                $cor = '#fefe85';
                            
                            if($porcentagem < 60)
                                $cor = '#ffa74f';
                            
                            if($porcentagem < 30)
                                $cor = '#fd8a8a';
                            
                            if($porcentagem == 100)
                                $cor = '#adf54d';
                            
                            echo '
                            <tr>
                            <td>'.$nome.'</td>
                            <td>'.(int)$linha['quantidade_avaliado'].'</td>
                            <td>'.(int)$linha['quantidade_nao_avaliado'].'</td>
                            <td>'.$total.'</td>
                            <td bgcolor="'.$cor.'">'.round($porcentagem,2).' %</td>
                            <td width="40px"><a href="relatorio_especialidade_candidato.php?id_especialidade='.$id.'"><img title="Visualizar" class="img-circle" src="imagens/lupa.png" width="40px"></a></td>
                            </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
    
        
    <div class="card" <?php if(($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") || $_SESSION['selecao_codigo'] == 'mfdv') echo "hidden" ?>>
        <legend>Candidatos com mais de uma especialidade (Concorrendo ou Não na especialdiade)</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica4">
                <thead>
                    <tr>
                      <th>Qtd</th>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Especialidades Cadastradas</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $relacao_docs_nao_avaliados = $conexao->get_candidatos_mais_uma_especialidade();  
                    
                        foreach ($relacao_docs_nao_avaliados as $linha) 
                        {
                            $foto = "user.jpg";

                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $especialidades_cadastradas = "";
                            
                            $especialidades_do_candidato = $conexao->get_especialidade_candidato($linha['id']);  
                            
                            $tem_ott = false;
                            $tem_stt = false;
                            
                            foreach($especialidades_do_candidato as $esp)
                            {
                                if($esp['ott_stt'] == 'ott') $tem_ott = true;
                                if($esp['ott_stt'] == 'stt') $tem_stt = true;
                                $especialidades_cadastradas = $especialidades_cadastradas . strtoupper($esp['ott_stt']) . " - " .  $esp['especialidade'] . " | ";
                            }
                            
                            if($tem_ott && $tem_stt)
                            {
                            
                                echo '
                                <tr>
                                <td>'.$linha['quantidade_especialidades'].'</td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$especialidades_cadastradas.'</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                                </tr>';
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
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 0, "asc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"order": [[ 0, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica4').DataTable({"order": [[ 1, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>