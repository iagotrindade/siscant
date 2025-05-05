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
    
    $arma_eipot = null;
    
    if(isset($_GET['select_arma']))
        $arma_eipot = $_GET['select_arma'];
    
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
         <div class="row">
             <form name="fomulario" action="candidato_lista_eipot.php" method="get">
                <div class="col-md-12">
                    <div class="card-body">
                        <label>Arma</label>
                        <select onchange="fomulario.submit()" name="select_arma" class="form-control" >
                            <option value="">Selecione a arma</option>
                            <option <?php if ($arma_eipot == 'Infantaria') echo 'selected' ?> value="Infantaria">Infantaria (INF)</option>
                            <option <?php if ($arma_eipot == 'Cavalaria') echo 'selected' ?> value="Cavalaria">Cavalaria (CAV)</option>
                            <option <?php if ($arma_eipot == 'Engenharia') echo 'selected' ?> value="Engenharia">Engenharia (ENG)</option>
                            <option <?php if ($arma_eipot == 'Material Bélico') echo 'selected' ?> value="Material Bélico">Material Bélico (MB)</option>
                            <option <?php if ($arma_eipot == 'Intendência') echo 'selected' ?> value="Intendência">Intendência (INT)</option>
                            <option <?php if ($arma_eipot == 'Comunicações') echo 'selected' ?> value="Comunicações">Comunicações (COM)</option>
                            <option <?php if ($arma_eipot == 'Artilharia Antiaérea') echo 'selected' ?> value="Artilharia Antiaérea">Artilharia Antiaérea (ART)</option>
                    </div>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
        

    <form name="tabela" action="mpdf/inscritos_eipot.php" method="post">
    <div class="card">
        <legend>Candidatos EIPOT</legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Nota final</th>
                      <th>Arma</th>
                      <th>RM de ETAPA PRESENCIAL</th>
                      <th>RMs de Interesse</th>
                      <th>Telefone</th>
                      <th>Mail</th>
                      <th>Etapa</th>
                      <th>Ver</th>
                      <!-- <th>Del</th> -->
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach ($lista_candidatos as $linha) 
                        {
                            

                            if($arma_eipot != null && $linha['arma_eipot'] != $arma_eipot) continue;
                            
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $nota_final_eipot = get_nota_final_eipot($linha['id']);
                            
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
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$nota_final_eipot.'</td>
                                <td>'.$linha['arma_eipot'].'</td>
                                <td>'.$linha['rm_inscricao'].'ªRM </td>
                                <td>'.$linha['rm_destino'].'</td>
                                <td>'.$linha['tel_celular'].'</td>
                                <td>'.$linha['mail'].'</td>
                                <td>et_'.$linha['etapa'].'</td>
                                <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                            </tr>';
                        }
                    ?>

                </tbody>
            </table>
            
        </div>
    </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary btn-block">RELAÇÃO DE INSCRITOS</button>
    </div>
</form>

        <br>
        <br>
        <br>

    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[2, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>