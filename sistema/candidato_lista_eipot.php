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
    

    $id_usuario = $_SESSION['id_usuario'];
    $rm_usuario = $conexao->rm_usuario($id_usuario); 
    $lista_candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario); 
    //$lista_candidatos = $conexao->get_todos_candidatos(); 
    /* $arma_eipot = null;
    if(isset($_GET['select_arma']))
        $arma_eipot = $_GET['select_arma']; */
    
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
        
        <div hidden class="card"> <!--ESCONDE A OPÇÃO DE FILTRO POR ARMA -->
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


<form name="form_etapa_presencial" action="mpdf/relacao_inscritos_eipot_etapa_presencial.php" method="post">
    <div class="card">
        <legend>Candidatos EIPOT - Ampla Concorrência</legend>
        
        <div class="card-body">
        <input type="hidden" name="rm_usuario" value="<?php echo $rm_usuario; ?>">
            <?php
              $id_selecao = $_SESSION['selecao'];
              $lista_armas_cadastradas = $conexao->get_armas_eipot_por_selecao(  $id_selecao);  
               
                // Define as armas que você quer usar como cabeçalho
                //$armas = ["INFANTARIA", "CAVALARIA", "ARTILHARIA", "ENGENHARIA", "INTENDÊNCIA", "COMUNICAÇÕES", "ARTILHARIA ANTIAÉREA", "MATERIAL BÉLICO"];
                $armas = [];
                foreach ($lista_armas_cadastradas as $arma) {
                $armas[] = $arma['nome']; 
                
                $candidatos_encontrados = false; // Flag para verificar se há candidatos
                $tabelas_sem_candidatos = []; // Array para armazenar as armas sem candidatos

                // Cria as tabelas para cada arma
                $i = 0;
                foreach ($armas as $arma) {
                   
                    $candidatos_na_arma = []; // Array para armazenar candidatos da arma atual
                    $i = $i+1;

                    foreach ($lista_candidatos as $linha) {
                   //     var_dump($linha['arma_eipot']);
                        if ($arma_eipot != null && $linha['arma_especialidade'] != $arma_eipot) continue;

                        // Filtra apenas candidatos da arma atual
                        if ($arma === $linha['arma_especialidade']) {
                            $candidatos_na_arma[] = $linha; // Adiciona ao array se corresponder
                        }
                    }
                }

                    // Se houver candidatos, exibe o cabeçalho e a tabela
                    if (count($candidatos_na_arma) > 0) {
                    
                        echo '<h5 style="text-align: center; font-size: 18px; font-weight: bold;">' . $arma . '</h5>'; // Cabeçalho da tabela
                        echo '<table class="table table-hover table-bordered" id="tabela_dinamica'. $i .'">';
                        echo '<thead>
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
                                  <!--  <th>Ver</th> -->
                                </tr>
                              </thead>
                              <tbody>';

                        // Exibe os candidatos na tabela
                        foreach ($candidatos_na_arma as $linha) {
                            $foto = "user.jpg";
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if (count($get_foto) > 0) {
                                $foto = $get_foto[0]['nome'];
                            }
                            
                            $nota_final_eipot = get_nota_final_eipot($linha['id']);
                            
                            if ($linha['etapa'] < $_SESSION['etapa_selecao']) continue;
                            if ($linha['medico_obrigatorio'] == '1') continue;

                            echo '
                            <tr>
                                <td><!--<a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '">-->' . $linha['cpf'] . '</a></td>
                                <td>' . $linha['nome_completo'] . '</td>
                                <td>' . $nota_final_eipot . '</td>
                                <td>' . $linha['arma_especialidade'] . '</td>
                                <td>' . $linha['rm_inscricao'] . 'ªRM </td>
                                <td>' . $linha['rm_destino'] . '</td>
                                <td>' . $linha['tel_celular'] . '</td>
                                <td>' . $linha['mail'] . '</td>
                                <td>et_' . $linha['etapa'] . '</td>
                             <!--   <td width="40px"><!--<a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '">--><!--<img title="Visualizar" class="img-circle" src="fotos/' . $foto . '" width="40px"></a></td> -->
                            </tr>';
                        }
                   

                        echo '</tbody></table><br>'; // Fecha a tabela
                    } else {
                        // Armazena as armas sem candidatos para posterior exibição
                        $tabelas_sem_candidatos[] = $arma;
                    }
                }

                // Se não houver candidatos, exibe uma mensagem
                if (!$candidatos_encontrados) {
                    echo '<p style="text-align: center; font-size: 16px;"></p>';
                }

                // Exibe as tabelas sem candidatos
                foreach ($tabelas_sem_candidatos as $arma) {
                    echo '<h5 style="text-align: center; font-size: 18px; font-weight: bold;">' . $arma . '</h5>'; // Cabeçalho da tabela
                    echo '<div class="tabela-sem-candidatos" style="display: none;">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="text-align: center;"></td>
                                </tr>
                            </table>
                          </div><br>'; //Fecha a tabela
                }
           
            ?>
        </div>
    </div>

    
  
</form>




    <!--<a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a> -->
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica1').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica4').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica5').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica6').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica7').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica8').DataTable({"order": [[2, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>