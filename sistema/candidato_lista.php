<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $perfil != 'consulta')
    {
        erro("Erro 37345757! Página não encontrada!");
        exit();
    }
    
    $lista_candidatos = $conexao->get_todos_candidatos();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Candidatos <i class="fa fa-users"></i></h1>
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
        
    <div class="card">
        <legend>Candidatos Participando
            | Planilha com os Candidatos PARTICIPANDO <a href="excel_candidatos_participando.php"><img src="imagens/ods.png" width="30px"></a>|
             Planilha com TODOS os Candidatos  <a href="excel_candidatos.php"><img src="imagens/ods.png" width="30px"></a>
            
        </legend>
        
        
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Arma</th>
                      <th>Grupo</th>
                      <th>Grupo Recurso</th>
                      <th>Telefone</th>
                      <th>Mail</th>
                      <th>Etapa</th>
                      <th>Distribuido</th>
                      <th>Guar Dest</th>
                      <th>Senha</th>
                      <!-- <th>Del</th> -->
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        foreach ($lista_candidatos as $linha) 
                        {
                            /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                            
                            if($linha['etapa'] < $_SESSION['etapa_selecao']) continue;
                            
                            if($linha['medico_obrigatorio'] == '1') continue;
                            
                            $incorporado = null;
                            if($linha['incorporado'] == '1') $incorporado = "_Sim";
                            if($linha['incorporado'] == '0') $incorporado = "_Não";
                            
                            $guarnicao_destino = null;
                            if($linha['id_cidade_distribuicao'] != null)
                            {
                                $resultado_cidade = $conexao->get_cidade_id($linha['id_cidade_distribuicao']);
                                $guarnicao_destino  = $resultado_cidade[0]['nome'];
                            }
                            
                                echo '
                                <tr>
                                <td>'.$linha['id'].'</td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['arma_quadro_servico'].'</td>
                                <td>_'.$linha['grupo_saude'].'_</td>
                                <td>recurso_'.$linha['grupo_saude_recurso'].'_</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td>et_'.$linha['etapa'].'</td>
                                <td>'.$incorporado.'</td>
                                <td>'.$guarnicao_destino.'</td>
                                <td width="30px"><a href ="usuario_altera_senha.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Resetar senha" src="imagens/senha.png" width="30px"></center></a></td>';
                                //<td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'candidato\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                echo '</tr>';
                        }
                        
                        // <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
        <div class="card">
            <legend>Planilha de distribuição <a href="excel_distribuicao.php"><img src="imagens/ods.png" width="30px"></a></legend>
            <legend>Planilha de pontuação das especialidades <a href="excel_pontuacao_especialidades.php"><img src="imagens/ods.png" width="30px"></a></legend>
        </div>
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 2, "asc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 2, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>