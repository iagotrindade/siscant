<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if(!$_SESSION['selecao_pagamento'])
    {
        erro("Erro: 636321266! Página não encontrada!");
        exit();
    }
    
    $nome_arquivo = "#";
    $id_arquivo_adicionado = 0;
    $arquivo_isento = null;
    $arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($_SESSION['id_usuario']);
    if(count($arquivo_pagamento) > 0)
    {
        $nome_arquivo = $arquivo_pagamento['0']['nome'];
        $id_arquivo_adicionado = $arquivo_pagamento['0']['id'];
        $arquivo_isento = $arquivo_pagamento['0']['isento'];
    }
?>

<script>
    
    function verifica_isento()
    {
        if($('#isento').is(':checked'))
        {
            $('#pagamento').hide();
            $('#comprovante_isencao').show();
        }
        else
        {
            $('#pagamento').show();
            $('#comprovante_isencao').hide();
        }
            
    }
    
    function verifica_medico()
    {
        if($('#medico').val() == '1')
        {
            $('#div_medicos').show();
            $('#div_pagamento').hide();
        }
        if($('#medico').val() == '0')
        {
            $('#div_medicos').hide();
            $('#div_pagamento').show();
        }
            
    }
    
</script>


<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Página Inicial <i class="fa fa-home"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Página Inicial</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <?php if(!inscricao()) echo " <font color = 'red' size='5px'> INSCRIÇÕES ENCERRADAS </font> " ?>
        <div class="card">
            <legend>Arquivo do seu pagamento ou isenção</legend>
            <div class="row">
                
                <!--
                <div class="col-md-12" <?php if(count($arquivo_pagamento) > 0 || $_SESSION['selecao_codigo'] != 'mfdv') echo "hidden" ?>>
                    <div class="animated-checkbox form-group">
                        <label> O Sr(a) é Médico(a)?</label>
                            <select id="medico" class="form-control" onchange="verifica_medico()">
                                <option value="">Selecione a opção</option>
                                <option value="1">Vou me inscrever SOMENTE para as especialidades MÉDICOS</option>
                                <option value="0">Não vou me inscrever para as especialidades de MÉDICOS</option>
                            </select>
                    </div>
                </div>
                
                <div id='div_medicos' class="col-md-6" hidden>
                    <div class="alert alert-dismissible alert-laranja"> 
                        <b>Médicos não realizam pagamento para se inscrever!</b>
                    </div>
                </div>
                -->
                
                  <div id='div_pagamento' class="col-lg-12" <?php if(count($arquivo_pagamento) > 0) echo "hidden" ?>>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="animated-checkbox form-group">
                                    <label>
                                        <div class="alert alert-dismissible alert-laranja"> 
                                            <input type="checkbox" <?php if($arquivo_isento == 1) echo "checked" ?> id="isento" name="isento_pagamento" onchange="verifica_isento()"><span class="label-text"><font size="4px">Requerer ISENÇÃO</font></span>
                                        </div>
                                    </label>
                                </div>
                                <form method="post" action="arquivo_upload_pagamento_inscricao.php" enctype="multipart/form-data">
                                    <div id="pagamento" <?php if($arquivo_isento == 1) echo "hidden" ?>>
                                        <div class="form-group"> 
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="alert alert-dismissible alert-laranja"> 
                                                        
                                                        <?php 
                                                        
                                                            $criptografia_gerar_gru = hash('sha256', $_SESSION['chave']."freitas");
                                                        
                                                            $manual_gru = 'Manual_GRU.pdf';
                                                            if($_SESSION['selecao_codigo'] == 'mfdv')
                                                            {
                                                                $manual_gru = 'Manual_GRU_MFDV.pdf';
                                                            }
                                                        ?>
                                                        <!--
                                                            <a target="_blank" href="imagens/<?php // echo $manual_gru; ?>"><font style='font-size: 20px'>Manual de geração da GRU </font> <img src="imagens/pdf.png" height="40px"></a>
                                                        -->
                                                        
                                                        <a target="_blank" href="gerar_gru.php?crip=<?php echo $criptografia_gerar_gru ?>"><font style='font-size: 20px'>Geração de GRU para pagamento </font> </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="text" hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave']."freitas") ?>">
                                        <div class="row">
                                            
                                            <div class="col-md-6">  
                                                <label>Adicione o comprovante de pagamento da GRU <font color="red"> *Máximo 2 MegaBytes no formato PDF</font></label>
                                                <div class="form-group"> 
                                                    <br> <input type="file" name="arquivo" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"> 
                                                    <input type="submit" class="btn btn-primary btn-block" value="Enviar Comprovante de Pagamento" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div id="comprovante_isencao" <?php if($arquivo_isento != 1) echo "hidden" ?>>
                                    <form method="post" action="arquivo_upload_isencao_pagamento.php" enctype="multipart/form-data">
                                        <div class="form-group"> 
                                            <font color="red"> <b>*Máximo 5 MegaBytes no formato PDF </b></font><br><p><font size="4px">Adicione UM arquivo PDF com TODOS os documentos previstos no edital digitalizados que comprovem a sua ISENÇÃO </font></p>
                                        </div>
                                        <input type="text" hidden name="criptografia" value="<?php echo hash('sha256', $_SESSION['chave']) ?>">
                                        <div class="col-md-6">  
                                            <div class="form-group"> 
                                                <br> <input type="file" name="arquivo" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"> 
                                                <input type="submit" class="btn btn-primary btn-block" value="Enviar Comprovante de Isenção" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                  </div>
                
                
                
                
                
                <div class="col-lg-6" <?php if(count($arquivo_pagamento)== 0) echo "hidden" ?>>
                    <div class="bs-component">
                        <div class="alert alert-success">
                            <div class="row">
                                <div class="col-lg-10">
                                    <strong>
                                        <a target="_blank" href="baixaPDF.php?codigo=can_pag_insc&nome_arquivo=<?php echo $nome_arquivo ?>">Visualizar arquivo adicionado <img src="imagens/pdf.png" height="30px"></a></strong>
                                </div>
                                <div class="col-lg-2" >
                                    <div class="pull-right">
                                        
                                        <?php 
                                            $crip = hash('sha256', $_SESSION['chave']."freitas".$id_arquivo_adicionado);
                                            if(count($arquivo_pagamento)> 0) 
                                                echo '<a onclick="funcao_apagar(\''.$id_arquivo_adicionado.'\', \'pagamento_inscricao\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
            
            
            
            
        </div>

        
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