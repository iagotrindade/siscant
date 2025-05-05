<?php

    $usuarios_destinados = null;
    
    $usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    
    
    if(count($usuario_logado) == 1)
        $usuarios_destinados = $conexao->get_candidatos_concorrendo_om($usuario_logado[0]['om_id']);
    
    $ano_atual = date("Y");
    
    $ano_selecao = null;
    if(isset($_GET['ano_selecao']))
        $ano_selecao = $_GET['ano_selecao'];
    
    $incorporados = null;
    if(isset($_GET['incorporados']))
        $incorporados = $_GET['incorporados'];
    
    if($incorporados == "medicos")
    {
        $usuarios_destinados = null;
        if(count($usuario_logado) == 1)
            $usuarios_destinados = $conexao->get_candidatos_concorrendo_om_medicos_obr($usuario_logado[0]['om_id']);
    }
?>


    <div class="card">
        <form name="fomulario" action="<?php $_SERVER["PHP_SELF"] ?>" method="get">
            <div  class="row">
                <div class="col-md-6">
                    <label>Selecione o ano da Incorporação</label>
                    <select onchange="fomulario.submit()" name="ano_selecao" class="form-control">
                        <option value="todos">TODOS</option>    
                            <?php 
                                
                                for($i = $ano_atual; $i >= 2019; $i--)
                                {
                                    echo '<option'; 
                                    if($ano_selecao == $i) echo " selected "; 
                                    if($ano_selecao == null && $ano_atual == $i) echo " selected "; 
                                    echo ' value="'.$i.'"> '.$i.' </option>';
                                }
                            ?>
                    </select> 
                </div>
                <div class="col-md-6">
                    <label>Selecione os Incorporados</label>
                    <select onchange="fomulario.submit()" name="incorporados" class="form-control">
                        <option value="voluntarios">SOMENTE OS VOLUNTÁTIOS</option>    
                        <option <?php if($incorporados == "medicos") echo " selected " ?> value="medicos">SOMENTE MÉDICOS OBRIGATÓRIOS</option>    
                    </select> 
                </div>
            </div>
        </form>
    </div>



<div class="card">
        <legend><?php echo $usuario_logado[0]['om_nome'] . " (" . $usuario_logado[0]['om_abreviatura'] . ")" ; ?></legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Especialidade</th>
                      <th>Aditamento</th>
                      <th>Observação</th>
                      <th>Data Incorporação</th>
                      <th>Apresentação</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        foreach($usuarios_destinados as $linha) 
                        {
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            $medico_obrigatorio = "";
                            if($linha['medico_obrigatorio'] == 1) $medico_obrigatorio = "<font color='red'>Médico Obrigatório</font>";
                            
                            if($ano_selecao != "todos")
                            {
                                $ano_incorp = (int) substr($linha['data_incorporacao'], 0, 4);
                                if($ano_selecao != null && $ano_incorp != $ano_selecao) continue;
                                if($ano_selecao == null && $ano_incorp != $ano_atual) continue;
                            }
                            
                            $data_incorporacao = null;
                            if($linha['data_incorporacao'] != null)
                                $data_incorporacao = trata_data($linha['data_incorporacao']);
                            
                            $cor_apresentacao_cand_om = "";
                            if($linha['apresentacao_candidato_om'] == 'apresentou_inapto' || $linha['apresentacao_candidato_om'] == 'faltoso')
                                $cor_apresentacao_cand_om = "#fd8a8a";
                            
                            echo '
                            <tr>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                            <td>'.$linha['nome_completo'].'</td>
                            <td>'. strtoupper($linha['ott_stt']) . " - " . $linha['nome_especialidade'].' '.$medico_obrigatorio.'</td>
                            <td>'.$linha['aditamento_convocacao'].'</td>
                            <td>'.$linha['observacao_distribuicao'].'</td>
                            <td width="40px">'.$data_incorporacao.'</td>
                            <td bgcolor="'.$cor_apresentacao_cand_om.'">'.$linha['apresentacao_candidato_om'].'</td>
                            <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="60px"></a></td>
                            </tr>';
                        }
                        //
                    ?>

                </tbody>
            </table>
        </div>
    </div>

<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">$('#tabela_dinamica').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],"iDisplayLength": -1, "order": [[ 1, "asc" ]]
    });</script>