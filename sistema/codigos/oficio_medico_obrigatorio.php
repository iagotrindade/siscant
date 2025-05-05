<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if(($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'con') || $_SESSION['candidato'] == '1')
    {
        erro("Erro 2353565! Página não encontrada!");
        exit();
    }
    
?>

<a name="oficio"></a>
<div class="card">
    <div class="row">
        <div class="col-md-12">
            <form action="mpdf/oficio_medico_obrigatorio.php" method="post" >
                <legend>Gerar Ofício <img src="imagens/pdf.png" height="40px"></legend> 
                <div  class="row">
                    
                    
                    <div  class="col-lg-6">
                        <div class="form-group" > <label>Endereço e bairro da OM de 1º Fase</label>
                            <input name="endereco_om_1_fase" value="<?php echo $endereco_om_1_fase ?>" class="form-control" >
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div class="form-group"  > <label>Presidente da Comissão de Designação</label>
                            <input name="presidente" value="" maxlength="100" class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>CEP e Cidade da OM de 1º Fase</label>
                            <input name="cep_om_1_fase" value="<?php echo $cep_om_1_fase . " / " . $guarnicao_om_1_fase ?>"  class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>Data da apresentação</label>
                            <input name="data_apresentacao"  class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>Hora da apresentação</label>
                            <input name="hora_apresentacao"  class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>Telefone da OM de 1º Fase</label>
                            <input name="telefone_om_1_fase" value="<?php echo $telefone_om_1_fase ?>" class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>EB</label>
                            <input name="eb" value="64510.450034/<?php echo date("Y") ?>-13" class="form-control" >
                        </div>
                    </div>
                    
                    <div  class="col-lg-3">
                        <div class="form-group" > <label>Data do Cabeçalho</label>
                            <input name="dt_cabecalho" value="<?php echo get_data_extenso() ?>" class="form-control" >
                        </div>
                    </div>
                    
                    
                    
                </div>
                <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >     
                <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden >
                <input value="nao" maxlength="3" name="medico_obrigatorio" hidden >
                <button  type="submit" class="btn btn-primary btn-block">Gerar PDF</button> 
            </form>
            
        </div>
    </div>
</div>    
