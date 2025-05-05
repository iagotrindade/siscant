<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 87293578923! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $perfil != 'consulta')
    {
        erro("Erro 48209358! Página não encontrada!");
        exit();
    }
    
    $valor_total_gru_pagas = 0;
    
    $get_valor_total_gru_pagas = $conexao->get_valor_tatal_gru_pagas();  
    if(count($get_valor_total_gru_pagas) > 0)
        $valor_total_gru_pagas = $get_valor_total_gru_pagas[0]['valor_total'];
    
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>GRUs Pagas <i class="fa fa-usd"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Usuários</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        
        <div class="card">
            <div class="row">
                <div class="col-md-8">
                    <legend> Adicione um arquivo no formato CSV ---> DOWNLOAD do Modelo <a href="modelo_csv.csv"><img src="imagens/ods.png" height="30px"></a> <--- </legend> 
                </div>
                <form method="post" action="arquivo_upload_processa_csv.php" enctype="multipart/form-data">
                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['assinatura_sistema']."freitas"); ?>">
                    <div class="col-md-2">
                        <input type="file" name="arquivo_csv"/>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary" value="Enviar" />
                    </div>
                </form>
            </div>
            <font color="red"><b>ATENÇÃO:</b></font> Certifique-se que os CPFs tenham os ZEROS a esquerda (11 dígitos)! O campo CPF e Valor são obrigatórios! Caso seja feita o upload de outra planilha, os dados antigos se apagarão, permanecendo somente os dados da planilha adicionada!
        </div>
        
        
    <div class="card">
        <div class="card-body">
            <legend>Tabela completa recebida pelo financeiro <b> <font size="6px"> Valor total: R$ <?php echo number_format($valor_total_gru_pagas, 2, ',', '.') ?> </font></b></legend> 
            <table class="table table-hover table-bordered" id="tabela_dinamica">
                <thead>
                    <tr>
                      <th>ID GRU</th>
                      <th>CPF</th>
                      <th>Valor</th>
                      <th>Data Pagamento</th>
                      <th>Nº de referência</th>
                      <th>Situação</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $lista_grus_pagas = $conexao->get_gru_pagas();  
                        
                        $total = 0;
                        foreach ($lista_grus_pagas as $linha) 
                        {
                            $data_formatada = null;
                            if($linha['data_pagamento'] != null)
                                $data_formatada = trata_data_hora($linha['data_pagamento']);
                            
                            echo '
                            <tr>
                                <td>'.$linha['id_gru'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['valor'].'</td>
                                <td>'.$data_formatada.'</td>
                                <td>'.$linha['numero_referencia'].'</td>
                                <td>'.$linha['situacao'].'</td>
                            </tr>';
                            $total = $total +1;
                        }
                    ?>

                </tbody>
            </table>
           <font size="5px"><?php echo "<b>Total de linhas: $total</b>"; ?></font>
        </div>
    </div>
        
        <div class="card" <?php if(count($lista_grus_pagas) == 0) echo ' hidden ' ?>>
        <div class="card-body">
            <legend>Candidatos que colocaram o arquivo de pagamento mas não constam na tabela do financeiro com o valor definido a ser pago</legend>
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome Completo</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $get_selecao = $conexao->get_selecao_id();
                        $valor_solicitado = $get_selecao[0]['valor_gru'];
                    
                        $lista_candidatos = $conexao->get_candidatos_pagaram_e_nao_estao_tabela_gru_pagas($valor_solicitado);  
                    
                        foreach ($lista_candidatos as $linha) 
                        {
                            
                            $foto = "user.jpg";
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            
                            echo '
                            <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                                <td>'.$linha['nome_completo'].'</td>
                                <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
                            </tr>';
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
        
    <div class="card">
        <div class="card-body">
            <legend>Candidatos que realizaram mais de uma vez o pagamento</legend>
            <table class="table table-hover table-bordered" id="tabela_dinamica3">
                <thead>
                    <tr>
                      <th>Quantidade</th>
                      <th>CPF</th>
                      <th>Valor</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $lista_grus_pagas_cpf_duplicado = $conexao->get_gru_pagas_cpf_duplicado();  
                    
                        foreach ($lista_grus_pagas_cpf_duplicado as $linha) 
                        {
                            echo '
                            <tr>
                                <td>'.$linha['conta_cpf'].'</td>
                                <td>'.$linha['cpf'].'</td>
                                <td>'.$linha['soma_total'].'</td>
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
<script type="text/javascript">$('#tabela_dinamica3').DataTable();</script>
</body>
</html>
<?php $conexao = null; ?>