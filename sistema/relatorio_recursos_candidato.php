<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'avaliador')
    {
        erro("Erro 632457437! Página não encontrada!");
        exit();
    }
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Recursos <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Recursos</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Nº</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Etapa</th>
                      <th>Data de Abertura</th>
                      <th>Status Aval</th>
                      <th>Status Final</th>
                      <th>Avaliador?</th>
                      <th>Especialidade</th>
                      <th>Justificativa</th>
                      <th>Foto</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        $especialidades_avaliador = null;
                        if($_SESSION['perfil'] == 'avaliador')
                        {
                            $especialidades_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);  
                        }
                        
                        $recursos = $conexao->get_recursos();
                        
                        foreach ($recursos as $linha) 
                        {
                            $aparece = true;
                            if($especialidades_avaliador != null)
                            {
                                if($linha['status'] != null) continue;
                                
                                $aparece = false;
                                foreach($especialidades_avaliador as $especialidade)
                                {
                                    if($linha['id_especialidade'] == $especialidade['id_especialidade'])
                                        $aparece = true;
                                }
                            }
                            
                            if($aparece == false) continue;
                            
                            if($linha['id_selecao'] != $_SESSION['selecao']) continue;
                            
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id_candidato']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $avaliador = null;
                            if($linha['para_avaliador'] == 0) $avaliador = 'Não';
                            if($linha['para_avaliador'] == 1) $avaliador = 'Sim';
                            
                            $status = $linha['status'];
                            if($status == null || $status == '') $status = "-";
                            $status_final = $linha['status_final'];
                            if($status_final == null || $status_final == '') $status_final = "#";
                            
                            $fontColor = "";
                            if($linha['concorrendo'] == 0) $fontColor = '#FF8C73';
                            
                                // 21/08/2025 -> Iago Silva Inserindo campo de data_abertura na tabela
                                echo '
                                <tr bgcolor = '.$fontColor.'>
                                <td>'.$linha['id'].'</td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id_candidato'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>_'.$linha['etapa'].'</td>
                                <td>'.trata_data($linha['data_abertura']).'</td>
                                <td>_'.$status.'_</td>
                                <td>*'.$status_final.'*</td>
                                <td>_'.$avaliador.'</td>
                                <td>'.$linha['nome_especialidade'].'</td>
                                <td>'.$linha['paragrafo1']. $linha['paragrafo2'].'</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id_candidato'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
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
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>