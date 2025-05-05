<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'avaliador')
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    $lista_candidatos = $conexao->get_candidatos_isentos();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Isentos de Pagamento <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Tempo de Pagamento</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Candidatos</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>Cód</th>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>E-Mail</th>
                      <th>Isento</th>
                      <th>Confirmada Isenção</th>
                      <th>Arquivo</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $i = 0;
                        foreach ($lista_candidatos as $linha) 
                        {
                            $foto = "user.jpg";
                            
                            if($linha['isento'] != '1')
                                continue;
                            
                            $deferido = "Indeferido";
                            /*
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                            if($i == 10) $i = 0;
                            
                            $confirmacao_isento = $linha['isento_pagamento'];
                            if($confirmacao_isento == '1')
                            {
                                $confirmacao_isento = "<img  src='imagens/like.jpg' width='40px'>";
                                $deferido = '_Deferido';
                            }
                            else if($confirmacao_isento == '0')
                                $confirmacao_isento = "<img  src='imagens/no_like.jpg' width='40px'>";
                            
                                echo '
                                <tr>
                                <td>'.$linha['id'].'-'.$i.'</td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td>'.$deferido.'</td>
                                <td>'.$confirmacao_isento.'</td>
                                <td width="40px"><a href="baixaPDF.php?codigo=rel_ise_pag&nome_arquivo='.$linha['nome_arquivo_pagamento'].'" target="_blank"><img title="Visualizar" src="imagens/pdf.png" width="40px"></a></td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">Ver</a></td>
                                </tr>';
                                
                                $i++;
                        }
                        //<td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
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
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 2, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>