<?php
    include_once 'menu.php';
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != "ouvidor")
    {
        erro("Erro 544654: Página não encontrada");
        exit();
    }
    
    $lista_suporte_inicial = $conexao->get_suporte();  
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Suporte <i class="fa fa-support"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Suporte (antes de se cadastrar no sistema)</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Suporte Civil</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Motivo</th>
                      <th>Mensagem</th>
                      <th>Data Enviado</th>
                      <th>Suporte_respondido_para_candidato?</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $respondidos = 0;
                        $nao_respondidos = 0;
                        $somatorio_dias_resposta = 0;
                        $maior_tempo = 0;
                        
                        foreach ($lista_suporte_inicial as $linha) 
                        {
                            
                            $dias_resposta = "";
                            
                            $usuario_respondeu = "_".mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];
                            
                            $respondido = "_Não";
                            if($linha['resposta'] != null)
                            {
                                $respondido = "_Sim";
                                $respondidos++;
                                
                                $dias_resposta = 0;
                                $data_enviado = new DateTime($linha['data_enviado']);
                                $data_respondido = new DateTime($linha['data_resposta']);
                                $intervalo = $data_enviado->diff( $data_respondido );
                                $tempo_total = $intervalo->d+$intervalo->h/24;
                                $tempo_total = $tempo_total + $intervalo->i/1440;
                                $tempo_total = $tempo_total + $intervalo->s/86400;
                                
                                if($intervalo->m > 0) $tempo_total = $tempo_total + (30*$intervalo->m);
                                
                                if($tempo_total > $maior_tempo) $maior_tempo = $tempo_total;
                                
                                $somatorio_dias_resposta = $somatorio_dias_resposta + $tempo_total;
                                
                                $dias_resposta = ", em " . round($tempo_total,2) ." dias por $usuario_respondeu ";
                                
                            }
                            else $nao_respondidos++;
                            
                                echo '
                                <tr>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['motivo'].'</td>
                                <td>'.$linha['mensagem'].'</td>
                                <td>'.trata_data_hora($linha['data_enviado']).'</td>
                                <td>'.$respondido.$dias_resposta.'</td>
                                    
                                <td><a href="suporte_inicial_visualiza.php?criptografia='.hash('sha256', $linha['id']).'&id_suporte='.$linha['id'].'"><img src="imagens/lupa.png" width="30px"></td>
                                </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
        <br>
        <br>
        <div class="row">
            <font size="4px">
                <div class="col-md-3">
                    <b>Respondidos:</b> <?php echo $respondidos ?><br>
                </div>
                <div class="col-md-3">
                    <b>Não respondidos:</b> <?php
                        if($respondidos > 0 || $nao_respondidos > 0)
                            $porcentagem = round(($nao_respondidos/($nao_respondidos+$respondidos))*100,2);
                            
                            if($nao_respondidos > 0) 
                                echo "<font color='red'>" . $nao_respondidos . " ($porcentagem%)</font>"; 
                            else echo '0';  
                        ?> 
                </div>
                <div class="col-md-3">
                    <b>Média de resp:</b> <?php if($respondidos > 0) echo round($somatorio_dias_resposta/$respondidos,2) . " dias"; ?> <br>
                </div>
                <div class="col-md-3">
                    <b>Maior tempo:</b> <?php echo round($maior_tempo,2) . " dias"; ?> <br>
                </div>
            </font>
        </div> 
    </div>
        
    <div class="card" <?php if($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
        <legend>Quantidade de respostas por usuário</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                              <th>Usuário</th>
                              <th>Qtd Respostas</th>
                              <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                                $lista_get_quantidade_x_usuario_suporte = $conexao->get_quantidade_x_usuario_suporte(); 

                                foreach ($lista_get_quantidade_x_usuario_suporte as $linha) 
                                {
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);  
                                    if(count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    $nome_usuario = mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];

                                    echo"<tr>
                                            <td>". $nome_usuario . "</td>
                                            <td>". $linha['quantidade'] . "</td>
                                            <td width='40px' align='center'><a href='usuario_visualiza.php?id_usuario=".$linha['id']."'><img class='img-circle' src='fotos/".$foto."' width='40px'></a></td>
                                        </tr>";
                                }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        
    <div class="card" <?php if($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
        <legend>Quantidade de suportes por categoria</legend>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                              <th>Motivo</th>
                              <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                                $lista_quantidade_x_motivo_suporte = $conexao->get_quantidade_x_motivo_suporte(); 

                                foreach ($lista_quantidade_x_motivo_suporte as $linha) 
                                {
                                    echo"<tr>
                                            <td>". $linha['motivo'] . "</td>
                                            <td>". $linha['quantidade'] . "</td>
                                        </tr>";
                                }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
</body>
</html>
<?php $conexao = null; ?>