<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] != 'om')
    {
        erro("Erro 2353565! Página não encontrada!");
        exit();
    }
    
?>
<a name="apresentacao_om"></a>
<div class="card">
    
    <legend>PREENCHA OS CAMPOS ABAIXO <img src="imagens/urgente.gif" height="30px"></legend>
    <div class="row">
        <div class="col-md-12">
            <form action="../banco_dados/om_informacoes.php" method="post">
                <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave'].$id_usuario."freitas"); ?>">
                <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
                <div class="row">
                    <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <select name="apresentacao_candidato_om" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($apresentacao_om == "apresentou_apto") echo 'selected' ?> value="apresentou_apto"">Apresentado e APTO</option>
                                    <option <?php if($apresentacao_om == "apresentou_inapto") echo 'selected' ?> value="apresentou_inapto">Apresentado e INAPTO</option>
                                    <option <?php if($apresentacao_om == "apresentou_e_nao_realizada_IS") echo 'selected' ?> value="apresentou_e_nao_realizada_IS">Apresentado e NÃO realizada inspeção saúde</option>
                                    <option <?php if($apresentacao_om == "faltoso") echo 'selected' ?> value="faltoso">Faltoso</option>
                                    <option <?php if($apresentacao_om == "insubmisso") echo 'selected' ?> value="insubmisso">Insubmisso</option>
                                    <option <?php if($apresentacao_om == "desertor") echo 'selected' ?> value="desertor">Desertor</option>
                                    <option <?php if($apresentacao_om == "incorporado") echo 'selected' ?> value="incorporado">Incorporado</option>
                                    
                                </select>
                            </div>
                        </div>
                    <div  class="col-lg-12">
                        <label> Observação livre | Exemplo: Data de apresentação, Data da falta, CDI, ETC...</label><br>
                        <textarea name="observacao_om" style="width: 100%"><?php echo $observacao_om ?></textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div  class="col-lg-12">
                        <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                    </div>
                </div>
            </form>
        </div>
            
    </div>
</div>