<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 632457437! Página não encontrada!");
        exit();
    }
    
    $lista_candidatos = $conexao->get_candidatos_concorrendo();  
    $lista_medicos_obrigatorias = $conexao->get_medicos_obrigatorios();  
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Tempo de sv público <i class="fa fa-users"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Tempo de sv público</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Todos os Candidatos</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Ativa/Reserva</th>
                      <th>Etapa</th>
                      <th>Especialidades</th>
                      <th>Sv Militar Anos</th>
                      <th>Sv Militar Meses</th>
                      <th>Sv Militar Dias</th>
                      <th>Anos vida</th>
                      <th>Meses vida</th>
                      <th>Dias vida</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $data_atual = new DateTime(date("Y-m-d"));
                        
                        foreach ($lista_candidatos as $linha) 
                        {
                            
                            $data_nasc = new DateTime(reverte_data($linha['data_nascimento']));
                            $intervalo = $data_atual->diff( $data_nasc );

                            $anos_vida = (int)$intervalo->format('%Y');
                            $meses_vida = (int)$intervalo->format('%m');
                            $dias_vida = (int)$intervalo->format('%d');
                            
                            $anos_sv_publico = (int)$linha['tempo_sv_pub_anos'];
                            $meses_sv_publico = (int)$linha['tempo_sv_pub_meses'];
                            $dias_sv_publico = (int)$linha['tempo_sv_pub_dias'];
                            
                            $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                            $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                            $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];
                            
                            $total_dias_sv_militar = $dias_sv_publico + $dias_sv_militar;
                            
                            $total_mese_sv_publico = $meses_sv_publico + $meses_sv_militar;
                            if($total_dias_sv_militar >=30)
                            {
                                $total_mese_sv_publico ++;
                                $total_dias_sv_militar = $total_dias_sv_militar -30;
                            }
                            
                            $total_anos_sv_publico = $anos_sv_publico + $anos_sv_militar;
                            if($total_mese_sv_publico >= 12)
                            {
                                $total_anos_sv_publico ++;
                                $total_mese_sv_publico = $total_mese_sv_publico -12;
                            }
                            
                            ////////////////////////////////
                            // Especialidades do Candidato
                            
                            $inscricoes = $conexao->get_especialidade_candidato($linha['id']);

                            $especialidades_do_candidato = null;
                            foreach ($inscricoes as $valor)
                            {
                                //echo "Especialidade: " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " " .$valor['especialidade'] . "<br>";
                                $id_candidato_x_especialidade = null;
                                $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'],$valor['id_especialidade']);

                                if(count($resultado_verificacao) > 0)
                                {
                                    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                                    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
                                    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
                                    
                                    $especialidades_do_candidato = $especialidades_do_candidato . strtoupper($ott_stt) . " " . $nome_especialidade . " | ";
                                }
                            }
                            
                            /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$linha['ativa_reserva'].'</td>
                                <td>_'.$linha['etapa'].'_</td>
                                <td>'.$especialidades_do_candidato.'</td>
                                <td>'.$total_anos_sv_publico.'</td>
                                <td>'.$total_mese_sv_publico.'</td>
                                <td>'.$total_dias_sv_militar.'</td>
                                <td>'.$anos_vida.'</td>
                                <td>'.$meses_vida.'</td>
                                <td>'.$dias_vida.'</td>
                                </tr>';
                        }
                        //<td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
        
        
        <div class="card" <?php if($_SESSION['selecao_codigo'] != 'mfdv') echo " hidden " ?>>
        <legend>Médicos Obrigatórios</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Especialidades</th>
                      <th>Sv pub Anos</th>
                      <th>Sv pub Meses</th>
                      <th>Sv pub Dias</th>
                      <th>Anos vida</th>
                      <th>Meses vida</th>
                      <th>Dias vida</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $data_atual = new DateTime(date("Y-m-d"));
                        
                        foreach ($lista_medicos_obrigatorias as $linha) 
                        {
                            
                            $data_nasc = new DateTime(reverte_data($linha['data_nascimento']));
                            $intervalo = $data_atual->diff( $data_nasc );

                            $anos_vida = (int)$intervalo->format('%Y');
                            $meses_vida = (int)$intervalo->format('%m');
                            $dias_vida = (int)$intervalo->format('%d');
                            
                            $anos_sv_publico = (int)$linha['tempo_sv_pub_anos'];
                            $meses_sv_publico = (int)$linha['tempo_sv_pub_meses'];
                            $dias_sv_publico = (int)$linha['tempo_sv_pub_dias'];
                            
                            $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                            $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                            $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];
                            
                            $total_dias_sv_militar = $dias_sv_publico + $dias_sv_militar;
                            
                            $total_mese_sv_publico = $meses_sv_publico + $meses_sv_militar;
                            if($total_dias_sv_militar >=30)
                            {
                                $total_mese_sv_publico ++;
                                $total_dias_sv_militar = $total_dias_sv_militar -30;
                            }
                            
                            $total_anos_sv_publico = $anos_sv_publico + $anos_sv_militar;
                            if($total_mese_sv_publico >= 12)
                            {
                                $total_anos_sv_publico ++;
                                $total_mese_sv_publico = $total_mese_sv_publico -12;
                            }
                            
                            ////////////////////////////////
                            // Especialidades do Candidato
                            
                            $inscricoes = $conexao->get_especialidade_candidato($linha['id']);

                            $especialidades_do_candidato = null;
                            foreach ($inscricoes as $valor)
                            {
                                //echo "Especialidade: " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " " .$valor['especialidade'] . "<br>";
                                $id_candidato_x_especialidade = null;
                                $resultado_verificacao = $conexao->verifica_especialidade_candidato($linha['id'],$valor['id_especialidade']);

                                if(count($resultado_verificacao) > 0)
                                {
                                    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                                    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
                                    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                                    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
                                    
                                    $especialidades_do_candidato = $especialidades_do_candidato . strtoupper($ott_stt) . " " . $nome_especialidade . " | ";
                                }
                            }
                            
                            /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */
                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td>'.$especialidades_do_candidato.'</td>
                                <td>'.$total_anos_sv_publico.'</td>
                                <td>'.$total_mese_sv_publico.'</td>
                                <td>'.$total_dias_sv_militar.'</td>
                                <td>'.$anos_vida.'</td>
                                <td>'.$meses_vida.'</td>
                                <td>'.$dias_vida.'</td>
                                </tr>';
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
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>