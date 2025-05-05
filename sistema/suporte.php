<?php
    include_once 'menu.php';

    if($_SESSION['selecao_regiao'] == 7) exit();
?>
<script type="text/javascript">
function valida_suporte()
{
    $('#div_motivo').attr('class','col-lg-12');
    $('#div_mensagem_suporte').attr('class',' col-lg-12');
    
    $("#mensagem_erro").text("");
    $("#div_mensagem_erro").hide();
    
    if($('#motivo').val() == '')
    {
        $('#div_motivo').attr('class','col-lg-12 form-group has-error');
        $('#div_motivo').focus();
        $("#mensagem_erro").text("O campo MOTIVO é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    
    if($('#mensagem_suporte').val() == '')
    {
        $('#div_mensagem_suporte').attr('class','col-lg-12 form-group has-error');
        $('#mensagem_suporte').focus();
        $("#mensagem_erro").text("O campo MENSAGEM é obrigatório!");
        $("#div_mensagem_erro").show();
        return false;
    }
    return true;
}

</script>
<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Suporte <i class="fa fa-support"></i></h1>
    </div>
    <div>
        <ul class="breadcrumb">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><a href="index.php">Página Inicial</a></li>
            <li>Suporte </li>
        </ul>
    </div>
  </div>
            
            <div  class="row">
            <div  class="col-lg-12">
                <div class="card">
                    <legend>Preencha os campos para enviar uma mensagem para o suporte</legend>
                    <form action="../banco_dados/suporte_cadastra.php" method="post" onsubmit="return valida_suporte()">
                        <div  class="row">
                            <div  class="col-lg-12">
                                <div id="div_mail" class="form-group"> 
                                    <label> <img height ="40px" src="imagens/urgente.gif"><font color="red">Certifique-se que sua dúvida não se encontre na página de <a href="duvidas_frequentes.php">DÚVIDAS FREQUENTES</a></font><img height ="40px" src="imagens/urgente.gif"></label>
                                    <br><label>Será enviado um e-mail de resposta para: <u><?php echo $mail ?> </u>. Caso o seu e-mail esteje desatualizado, edite o seu cadastro.</label>
                                </div>
                            </div>
                            
                            <div id="div_motivo" class="col-lg-12">
                                <div class="form-group"> <label for="motivo">Motivo da solicitação do suporte</label>
                                    <select id="motivo" name="motivo" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option value="duvida_pagamento_isento">Estou em dúvida se sou isento de pagamento para inscrição</option>
                                        <option value="duvida_ex_medico">Estou em dúvida sobre os Exames Médicos</option>
                                        <option value="duvida_autenticacao">Estou em dúvida sobre autenticação de documentos</option>
                                        
                                        <?php
                                        
                                        if($codigo_selecao != 'mfdv')
                                            echo '<option value="duvida_teste_fisico">Estou em dúvida sobre o Teste Físico</option>
                                                  <option value="duvida_teste_pratico">Estou em dúvida sobre o Teste Prático</option>';
                                        ?>
                                       
                                        <option value="duvida_docs_obrigatorios">Estou em dúvida sobre sobre um ou mais documentos obrigatórios </option>
                                        <option value="dificuldade_curriculo">Estou com dificuldade em fazer upload de arquivos para uma especialidade</option>
                                        <option value="dificuldade_doc_obrigatorios">Estou com dificuldade em anexar um ou mais documentos obrigatórios</option>
                                        <option value="dificuldade_dados_cadastro">Estou com dificuldade em editar os dados do meu cadastro</option>
                                        <option value="dificuldade_especialidade">Estou com dificuldade em cadastrar uma especialidade</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div id="div_mensagem_suporte" class="col-lg-12">
                                <div  class="form-group"> 
                                    <label>Mensagem</label>
                                    <textarea maxlength="2000" id="mensagem_suporte" name="mensagem" class="form-control"></textarea>
                                </div>
                            </div>
                    
                            <div id="div_mensagem_erro" hidden class="col-lg-12">
                                <div>
                                    <font color="red"><b><center><p id="mensagem_erro"></p></center></b></font>
                                </div>
                            </div>
                            <input hidden value="<?php echo hash('sha256', $_SESSION['chave']."freitas") ?>" name="crip" >      
                            <div  class="col-lg-12">
                                <br><button name="enviar" type="submit"  class="btn btn-primary btn-block">Enviar</button>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>
    
    <div class="row">
    <div class="col-md-12">
      <div class="card">
              <div class="card-body">
                <table class="table table-hover table-bordered" id="tabela_dinamica">
                  <thead>
                    <tr>
                      <th>Mensagem</th>
                      <th>Data Enviado</th>
                      <th>Resposta</th>
                      <th>Data Resposta</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php
                        $lista_suporte = $conexao->get_suporte_candidato($_SESSION['id_usuario']);  
                        foreach ($lista_suporte as $linha) 
                        {
                            
                            $data_envio = null;
                            if($linha['data_enviado'] != null)
                                $data_envio = trata_data ($linha['data_enviado']);
                        
                            $mensagem = null;
                            if($linha['mensagem'] != null)
                                $mensagem = $linha['mensagem'];
                           
                            $resposta = null;
                            if($linha['resposta'] != null)
                                $resposta = $linha['resposta'];
                            
                            $data_resposta = null;
                            if($linha['data_resposta'] != null)
                                $data_resposta = trata_data ($linha['data_resposta']);
                        
                            echo '
                            <tr>
                                <td>'.$mensagem.'</a></td>
                                <td>'.$data_envio.'</a></td>
                                <td>'.$resposta.'</a></td>
                                <td>'.$data_resposta.'</a></td>
                            </tr>';
                        }
                        ?>  
                  </tbody>
                </table>
              </div>
          <a name="fimpagina"></a>
</div>
        <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
    
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>       
                    
</div>
</div>
</body>
</html>