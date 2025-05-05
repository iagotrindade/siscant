<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if(($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos') || $_SESSION['candidato'] == '1')
    {
        erro("Erro 2353463565! Página não encontrada!");
        exit();
    }
?>

    <div class="col-md-6">
        <form action="../banco_dados/candidato_isento.php" method="post">
            <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
            <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
            <div class="row">
                <div  class="col-lg-12">
                    <div class="animated-checkbox form-group">
                        <label>Selecione se ele é isento de pagamento</label>
                        <select name="isento" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option <?php if($isento_pagamento == 1) echo 'selected' ?> value="1">SIM, isento</option>
                            <option <?php if($isento_pagamento == 0 && $isento_pagamento != null) echo 'selected' ?> value="0">NÃO, não é isento</option>
                        </select>
                    </div>
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