<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['selecao_codigo'] != 'mfdv')
    {
        erro("Erro 43564775! Página não encontrada!");
        exit();
    }
    
    $lista_medicos = $conexao->get_medicos_obrigatorios();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Médicos Obrigatórios <i class="fa fa-user-md"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Médicos Obrigatórios</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        <!-- 
        <div class="card">
            <div class="row">
                <div  class="col-lg-12">
                    <form action="mpdf/relatorio_medico_obrigatorio_inspecao_saude.php" method="POST">
                        <div  class="col-lg-12">
                            <legend>Relatório de quem realizou o exame médico em determinada data:</legend>
                        </div>
                        <div  class="col-lg-2">
                            <input name="data_ata_saude" maxlength="100" class="form-control" placeholder="Dia dos exames" >
                        </div>
                        <!--
                        <div  class="col-lg-2">
                            <input name="assinante_1" maxlength="100" class="form-control" placeholder="1º assinante/CRM" >
                        </div>
                        <div  class="col-lg-2">
                            <input name="assinante_2" maxlength="100" class="form-control" placeholder="2º assinante/CRM" >
                        </div>
                        <div  class="col-lg-2">
                            <input name="assinante_3" maxlength="100" class="form-control" placeholder="3º assinante/CRM" >
                        </div>
                        <div  class="col-lg-2">
                            <input name="cidade" maxlength="100" class="form-control" placeholder="Cidade" >
                        </div>
                        <div  class="col-lg-2">
                            <input name="sessao" maxlength="100" class="form-control" placeholder="Nº da Sessão" >
                        </div>
                        
                        <div  class="col-lg-10">
                            <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO PDF</button> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        -->
        
    <div class="card"> 
        <legend>PLANILHA COMPLETA --> <a target="_blank" href="excel_medicos_obrigatorios.php"><img src="imagens/ods.png" width="30px"></a>
                PLANILHA REDUZIDA --> <a target="_blank" href="excel_medicos_obrigatorios_reduzida.php"><img src="imagens/ods.png" width="30px"></a>
        </legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Faculdade / Ano Form</th>
                      <th>Ano Seleção</th>
                      <th>Especialidade(s)</th>
                      <th>Fim adiamento</th>
                      <th>Grupo</th>
                      <th>Data Ex Saúde</th>
                      <th>Sit Dist</th>
                      <th>Obs Distribuição</th>
                      <!-- <th>Edit</th> -->
                      <th>Del</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $data_atual = new DateTime(date("Y-m-d"));
                        foreach ($lista_medicos as $linha) 
                        {
                            /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                            
                            $lista_especialidades = "Esp_não_cadastrada";
                            $especialidades = $conexao->get_especialidade_candidato($linha['id']);
                            if(count($especialidades) > 0) $lista_especialidades = "";
                            foreach ($especialidades as $especialidade) 
                            {
                                $lista_especialidades = $lista_especialidades . " _" .  strtoupper($especialidade['ott_stt']). " " . $especialidade['especialidade'];
                            }
                            
                            $trans_fisemi = null;
                            if($linha['transferencia_fisemi'] == 1) $trans_fisemi = "tf_Sim";
                            //if($linha['transferencia_fisemi'] == 0) $trans_fisemi = "tf_Não";
                            
                            $data_nascimento = $linha['data_nascimento'];
                            if($data_nascimento != null) $data_nascimento = trata_data($data_nascimento);
                            
                            $data_ex_saude = $linha['data_exame_saude'];
                            $data_exame_saude_recurso = $linha['data_exame_saude_recurso'];
                            if($data_ex_saude != null) $data_ex_saude = trata_data($data_ex_saude);
                            if($data_exame_saude_recurso != null) $data_ex_saude = trata_data($data_exame_saude_recurso);
                            if($data_ex_saude == null) $data_ex_saude = "//";
                            
                            
                            $apto = "não_feito";
                            if($linha['apto_saude'] == '1') $apto = "_APTO";
                            if($linha['apto_saude'] == '0') $apto = "_INAPTO";

                            $voluntario_sv_militar = "_*_";
                            if($linha['voluntario_sv_militar'] === '1') $voluntario_sv_militar = "_Sim";
                            if($linha['voluntario_sv_militar'] === '0') $voluntario_sv_militar = "_Não";
                            
                            $fisemi_rm_destino = null;
                            if($linha['fisemi_rm_destino'] != null)
                                $fisemi_rm_destino = $linha['fisemi_rm_destino'] . "ª RM";
                            
                            
                            $data_fim_adiamento = null;
                            if($linha['data_fim_adiamento'] != null) $data_fim_adiamento = trata_data($linha['data_fim_adiamento']);
                            
                            $data_liminar = null;
                            if($linha['data_liminar'] != null) $data_liminar = trata_data($linha['data_liminar']);
                            
                            $situacao_pos_cse = $linha['refratario_impedido'];
                            if($situacao_pos_cse == null || $situacao_pos_cse == "") $situacao_pos_cse = '#NULL';
                            
                            $ano_selecao = "NULL";
                            if($linha['ano_selecao_medico_obrigatorio'] != null) $ano_selecao = $linha['ano_selecao_medico_obrigatorio'];
                            
                            $sit_distribuicao = "null_sit";
                            if($linha['titular_reserva_distribuicao'] != null)$sit_distribuicao = $linha['titular_reserva_distribuicao'];
                            
                            $bgcolor = "";
                            $concorrendo = "#ag_fim_CSE";
                            if($linha['concorrendo'] == '0') 
                            {
                                $concorrendo = "#arq";
                                $bgcolor = 'bgcolor="fd8a8a"';
                            }
                            
                            $data_nasc = new DateTime(reverte_data($linha['data_nascimento']));
                            $intervalo = $data_atual->diff( $data_nasc );

                            $anos_vida = (int)$intervalo->format('%Y');
                            $meses_vida = $intervalo->format('%M');
                            $dias_vida = $intervalo->format('%D');
                            
                            
                            /*
                             * 
                             * 
                                voluntario_12rm

                                voluntario_sv_militar

                                data_exame_saude
                                grupo_saude

                                grupo_saude_recurso
                                data_exame_saude_recurso
                             */
                            
                            $voluntario = "Vol_SV_X" ;
                            if($linha['voluntario_sv_militar'] === '1') $voluntario = "Vol_SV_SIM" ;
                            if($linha['voluntario_sv_militar'] === '0') $voluntario = "Vol_SV-NÃO" ;
                            
                            $dependente = 0;
                            if($linha['dependente'] > 0) $dependente = $linha['dependente'];
                            $texto_dependente = "Nr_Dep_" . $dependente;
                            
                            $estado_civil = "";
                            if($linha['estado_civil'] != null) $estado_civil = $linha['estado_civil'];
                            
                            $data_jise = "";
                            if($linha['data_exame_saude'] != null) $data_jise = trata_data($linha['data_exame_saude']);
                            if($linha['data_exame_saude_recurso'] != null) $data_jise = trata_data($linha['data_exame_saude_recurso']);
                            
                            $grupo_jise = "";
                            if($linha['grupo_saude'] != null) $grupo_jise = strtoupper ($linha['grupo_saude']);
                            if($linha['grupo_saude_recurso'] != null) $grupo_jise = strtoupper ($linha['grupo_saude_recurso']);
                            
                            $voluntario_12 = "12_RM_X";
                            if($linha['voluntario_12rm'] != null) $voluntario_12 = $linha['voluntario_12rm'];
                            if($voluntario_12 == '1') $voluntario_12 = "12_RM_SIM";
                            if($voluntario_12 == '0') $voluntario_12 = "12_RM-NÃO";
                            
                            
                                echo '
                                <tr '.$bgcolor.'>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"> '.$concorrendo.' *'.$anos_vida.'a'.$meses_vida.'m'.$dias_vida.' '.
                                $voluntario . ' ' .  $texto_dependente .' '. $estado_civil . ' ' . $voluntario_12 . ' ' .
                                $linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['instituto_ensino'].' / '.$linha['ano_formacao'].'</td>
                                <td>_'.$ano_selecao.'_</td>';
                                //<td>'.$data_nascimento.'</td>
                                //<td>'.$linha['mail'].'</td>
                                echo '<td>'.$lista_especialidades.'</td>
                                <td>'.$data_fim_adiamento.'</td>
                                <td>#'.$grupo_jise.'_</td>
                                <td>'.$data_ex_saude.'</td>
                                <td>#'.$sit_distribuicao.'</td>
                                <td>'.$linha['observacao_distribuicao'].'</td>
                                    ';
                                // <td width="40px"><a href="edita_medico_obrigatorio.php?id_usuario='.$linha['id'].'"><img title="Editar" src="imagens/editar.png" width="30px"></a></td>
                                echo'<td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'candidato\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                </tr>';
                             
                        }
                        // ALTERA SENHA <td width="30px"><a href ="medico_obrigatorio_altera_senha.php?id_usuario='.$linha['id'].'" ><center><img data-toggle="tooltip" title="Resetar senha" src="imagens/senha.png" width="30px"></center></a></td>
                        // FOTO <td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
        <div class="card">
        <legend>
            Situação dos Distribuidos
        </legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica3">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Faculdade / Ano Form</th>
                      <th>Ano Seleção</th>
                      <th>Incorporação</th>
                      <th>Especialidade(s)</th>
                      <th>Titular/Reserva</th>
                      <th>Guar Dest</th>
                      <th>OM 1º Fase</th>
                      <th>OM Dist</th>
                      <th>Apresentação na OM</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                        foreach ($lista_medicos as $linha) 
                        {
                            $lista_especialidades = "Esp_não_cadastrada";
                            $especialidades = $conexao->get_especialidade_candidato($linha['id']);
                            if(count($especialidades) > 0) $lista_especialidades = "";
                            foreach ($especialidades as $especialidade) 
                            {
                                $lista_especialidades = $lista_especialidades . " " .  strtoupper($especialidade['ott_stt']). " " . $especialidade['especialidade'];
                            }
                            
                            $incorporado = null;
                            if($linha['incorporado'] == '1') $incorporado = "_Sim";
                            if($linha['incorporado'] == '0') continue;
                            if($linha['incorporado'] == null) continue;
                            
                            $cor_apresentacao_cand_om = "";
                            if($linha['apresentacao_candidato_om'] == 'apresentou_inapto' || $linha['apresentacao_candidato_om'] == 'faltoso')
                                $cor_apresentacao_cand_om = "#fd8a8a";
                            
                            $guarnicao_destino = null;
                            if($linha['id_cidade_distribuicao'] != null)
                            {
                                $resultado_cidade = $conexao->get_cidade_id($linha['id_cidade_distribuicao']);
                                $guarnicao_destino  = $resultado_cidade[0]['nome'];
                            }
                            
                            $data_incorporacao = null;
                            if($linha['data_incorporacao'] != null) $data_incorporacao = trata_data ($linha['data_incorporacao']);
                            
                            
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['instituto_ensino'].' / '.$linha['ano_formacao'].'</td>
                                <td>_'.$linha['ano_selecao_medico_obrigatorio'].'_</td>
                                <td>_'.$data_incorporacao.'_</td>';
                                echo '<td>'.$lista_especialidades.'</td>
                                <td>'.$linha['titular_reserva_distribuicao'].'</td>
                                <td>'.$guarnicao_destino.'</td>
                                <td>'.$linha['abreviatura_om_1_fase'].'</td>
                                <td>'.$linha['om_distribuicao_abreviatura'].'</td>
                                <td bgcolor="'.$cor_apresentacao_cand_om.'">'.$linha['apresentacao_candidato_om'].'</td>';
                             
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
        
        
        
        
        
        <div class="card">
        <legend>Judicial (Todos os que estão com alguma situação pós CSE)</legend>
        
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Situação/Grupo/Força</th>
                      <th>OM Destino</th>
                      <th>Nº Ação</th>
                      <th>Data Limiar</th>
                      <th>Transitou Julgado</th>
                      <th>Fav/Desv</th>
                      <th>Convocado</th>
                      <th>Pub Bar Reg</th>
                      <th>Obs Dist</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                    
                    foreach ($lista_medicos as $linha) 
                    {
                        
                        //if($linha['refratario_impedido'] == null || $linha['refratario_impedido'] == "") continue;
                        
                        $transitou = null;
                        if($linha['transitou_julgado'] != null && $linha['transitou_julgado'] == 1) $transitou = "t_Sim";
                        if($linha['transitou_julgado'] != null && $linha['transitou_julgado'] == 0) $transitou = "t_Não";
                        
                        $convocado = null;
                        if($linha['convocado'] != null && $linha['convocado'] == 1) $convocado = "c_Sim";
                        if($linha['convocado'] != null && $linha['convocado'] == 0) $convocado = "c_Não";
                        
                        $data_limiar = null;
                        if($linha['data_liminar'] != null) $data_limiar = trata_data($linha['data_liminar']);
                        
                        $grupo_saude = null;
                        if($linha['grupo_saude'] != null) $grupo_saude = "_".$linha['grupo_saude'];
                        if($linha['grupo_saude_recurso'] != null) $grupo_saude = "_".$linha['grupo_saude_recurso'];
                        if($grupo_saude == '_a') $grupo_saude = "_Apto";
                        
                        $forca_distribuicao = null;
                        if($linha['forca_distribuicao'] != null) $forca_distribuicao = "_".$linha['forca_distribuicao'];
                        
                        
                            
                        echo '
                                <tr>
                                    <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                    <td>'.$linha['nome_completo'].'</td>
                                    <td>_'.$linha['refratario_impedido'].' '.$grupo_saude.' '.$forca_distribuicao.' </td>
                                    <td>'.$linha['nome_om_distribuicao'].' - '.$linha['titular_reserva_distribuicao'].'</td>
                                    <td>'.$linha['numero_acao'].'</td>
                                    <td>'.$data_limiar.'</td>
                                    <td>'.$transitou.'</td>
                                    <td>'.$linha['favoravel_desfavoravel'].'</td>
                                    <td>'.$convocado.'</td>
                                    <td>'.$linha['publicacao_bar_reg'].'</td>
                                    <td>'.$linha['observacao_distribuicao'].'</td>
                                </tr>';
                             
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>
        
        
    <div class="card" <?php if($_SESSION['selecao_codigo'] != 'mfdv')    echo ' hidden ' ?>>
        <legend>Planilha de distribuição dos médicos obrigatórios <img src="imagens/ods.png" width="30px"></legend>
        <div class="card-body">
            <form action="excel_distribuicao.php" method="post">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="form-group"> 
                        <font color="red">Será retornado apenas quem está como <b><u>Distribuido</u></b>, Força de distribuição <b><u>Exército</u></b> e que esteja <b><u>Concorrendo</u></b> no processo seletivo</font>
                    </div>
                </div> 
                
                <div class="col-lg-4">
                    <div class="form-group"> 
                       <input name="ano_distribuicao_medico_obrigatorio" maxlength="4" class="form-control" placeholder="Ano da Seleção" >
                    </div>
                </div>
                <div  class="col-lg-12">
                    <button  type="submit" class="btn btn-primary btn-block">GERAR PLANILHA</button> 
                </div>
            </div>
            </form>
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
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[ 2, "asc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"order": [[ 2, "asc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>