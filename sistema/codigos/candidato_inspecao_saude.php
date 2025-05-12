<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos' && $_SESSION['perfil'] != 'jise') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

include_once '../sistema/codigos/funcao_apagar.php';
?>

<a name="exame_medico"></a>
<div class="card">
    <div class="row">
        <div class="col-md-12">
            <form action="../banco_dados/candidato_edita_exame_medico.php" method="post" enctype="multipart/form-data">
                <legend>IS - JISE <i class="fa fa-user-md"></i></legend>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group"> <label>Situação</label>
                            <select name="apto_saude" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option <?php if ($apto_saude == '1') echo 'selected' ?> value="1">Apto</option>
                                <option <?php if ($apto_saude == '0') echo 'selected' ?> value="0">Inapto</option>
                                <option <?php if ($apto_saude == '2') echo 'selected' ?> value="2">Não compareceu</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group"> <label>Grupo</label>
                            <select name="grupo_saude" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option <?php if ($grupo_saude == 'a') echo 'selected' ?> value="a">A</option>
                                <option <?php if ($grupo_saude == 'b1') echo 'selected' ?> value="b1">B1</option>
                                <option <?php if ($grupo_saude == 'b2') echo 'selected' ?> value="b2">B2</option>
                                <option <?php if ($grupo_saude == 'c') echo 'selected' ?> value="c">C</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group"> <label>Data da realização do exame</label>
                            <input name="data_exame_saude" value="<?php if ($data_exame_saude != null) echo reverte_data($data_exame_saude) ?>" maxlength="25" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label>CID</label>
                            <textarea maxlength="2000" name="cid_saude" class="form-control"><?php echo $cid_saude ?></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Observações (Preencher FC, PA, PESO, ALT e IMC) </label>
                            <textarea maxlength="2000" name="observacao_exame_saude" class="form-control"><?php echo $observacao_exame_saude ?></textarea>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="form-group">
                            <?php

                            if ($ata_is == null) {
                                echo (
                                    '<div class="col-md-6">
                                            <div class="form-group">
                                                <label>ATA - IS</label>
                                                <font color="red"> <b>*Máximo 5 MegaBytes</b></font><br>
                                            </div>
                                            
                                            <div class="">
                                                <div class="form-group">
                                                    <input type="file" name="ata_is" />
                                                </div>
                                            </div>
                                        </div>'
                                );
                            } else {
                                echo (
                                    '<div class="col-md-6">
                                        <div class="form-group">
                                            <label>ATA - IS</label>
                                        </div>

                                        <div class="">
                                            <div class="form-group">
                                                <a href="arquivos_add_p_cand/atas_is/' . $ata_is . '" target="_blank" href="">
                                                    <img src="imagens/pdf.png" height="70px">

                                                <a onclick="funcao_apagar_ata_is(\'' . $id_usuario . '\', \'ata_is\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>
                                            </div>
                                        </div>
                                    </div>'
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>" name="crip">
                <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden>
                <input value="nao" maxlength="3" name="medico_obrigatorio" hidden>
                <button type="submit" class="btn btn-primary btn-block">Salvar IS</button>
            </form>

            <br>

            <form action="../banco_dados/candidato_edita_exame_medico_recurso.php" method="post" enctype="multipart/form-data">
                <legend>ISGRec - JISR <i class="fa fa-file-text-o"></i> <i class="fa fa-user-md"></i></legend>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group"> <label>Situação</label>
                            <select name="apto_saude_recurso" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option <?php if ($apto_saude_recurso == '1') echo 'selected' ?> value="1">Apto</option>
                                <option <?php if ($apto_saude_recurso == '0') echo 'selected' ?> value="0">Inapto</option>
                                <option <?php if ($apto_saude_recurso == '2') echo 'selected' ?> value="2">Não compareceu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group"> <label>Grupo</label>
                            <select name="grupo_saude_recurso" class="form-control">
                                <option value="">Selecione a opção</option>
                                <option <?php if ($grupo_saude_recurso == 'a') echo 'selected' ?> value="a">A</option>
                                <option <?php if ($grupo_saude_recurso == 'b1') echo 'selected' ?> value="b1">B1</option>
                                <option <?php if ($grupo_saude_recurso == 'b2') echo 'selected' ?> value="b2">B2</option>
                                <option <?php if ($grupo_saude_recurso == 'c') echo 'selected' ?> value="c">C</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group"> <label>Data da realização do exame</label>
                            <input name="data_exame_saude_recurso" value="<?php if ($data_exame_saude_recurso != null) echo reverte_data($data_exame_saude_recurso) ?>" maxlength="25" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label>CID</label>
                            <textarea maxlength="2000" name="cid_saude_recurso" class="form-control"><?php echo $cid_saude_recurso ?></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Observações (Preencher FC, PA, PESO, ALT e IMC)</label>
                            <textarea maxlength="2000" name="observacao_exame_saude_recurso" class="form-control"><?php echo $observacao_exame_saude_recurso ?></textarea>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="form-group">
                            <?php

                            if ($ata_is_recurso == null) {
                                echo (
                                    '<div class="col-md-6">
                                        <div class="form-group">
                                            <label>ATA - ISGRec</label>
                                            <font color="red"> <b>*Máximo 5 MegaBytes</b></font><br>
                                        </div>
                                        
                                        <div class="">
                                            <div class="form-group">
                                                <input type="file" name="ata_is_recurso" />
                                            </div>
                                        </div>
                                    </div>'
                                );
                            } else {
                                echo (
                                    '<div class="col-md-6">
                                        <div class="form-group">
                                            <label>ATA - ISGRec</label>
                                        </div>

                                        <div class="">
                                            <div class="form-group">
                                                <a href="arquivos_add_p_cand/atas_is/' . $ata_is_recurso . '" target="_blank" href="">
                                                    <img src="imagens/pdf.png" height="70px">

                                                <a onclick="funcao_apagar_ata_is(\'' . $id_usuario . '\', \'ata_is_recurso\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>
                                            </div>
                                        </div>
                                    </div>'
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>" name="crip">
                <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden>
                <input value="nao" maxlength="3" name="medico_obrigatorio" hidden>
                <button type="submit" class="btn btn-primary btn-block">Salvar ISGRec</button>
            </form>


        </div>
    </div>
</div>