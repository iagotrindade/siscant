<a name="impedimento_judicial"></a>
<div class="row" <?php if($medico_obrigatorio == '1') echo "hidden"; ?>>
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                    <form action="../banco_dados/candidato_impedido_judicial.php" method="post" >
                    <div class="alert alert-dismissible ">
                        <legend>Situação Pós CSE</legend> 
                        <div  class="row">
                            <div  class="col-lg-3">
                                <div class="form-group"> <label>Impedimento Judicial</label>
                                    <select name="refratario_impedido" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($refratario_impedido == 'desistencia') echo 'selected' ?> value="desistencia">Desistência</option>
                                        <option <?php if($refratario_impedido == 'refratario') echo 'selected' ?> value="refratario">Refratário</option>
                                        <option <?php if($refratario_impedido == 'impedido') echo 'selected' ?> value="impedido">Impedimento Judicial</option>
                                        <option <?php if($refratario_impedido == 'excesso') echo 'selected' ?> value="excesso">Excesso</option>
                                        <option <?php if($refratario_impedido == 'incorporado') echo 'selected' ?> value="incorporado">Incorporado</option>
                                    </select>
                                </div>
                            </div>

                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Histórico Judicial</label>
                                    <select name="historico_judicial" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($historico_judicial == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($historico_judicial == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div  class="form-group"> 
                                    <label>Número da ação</label>
                                    <input maxlength="100" value="<?php if ($numero_acao != '') echo $numero_acao ?>" name="numero_acao" class="form-control">
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Data da Liminar</label>
                                    <input name="data_liminar" value="<?php if($data_liminar != null) echo reverte_data ($data_liminar)  ?>" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-3"> <label>Transitou em Julgado</label>
                                <select name="transitou_julgado" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($transitou_julgado == '1') echo 'selected' ?> value="1">Sim</option>
                                    <option <?php if($transitou_julgado == '0') echo 'selected' ?> value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-lg-3"> <label>Favorável/Desfavorável</label>
                                <select name="favoravel_desfavoravel" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($favoravel_desfavoravel == 'favoravel') echo 'selected' ?> value="favoravel">Favorável</option>
                                    <option <?php if($favoravel_desfavoravel == 'desfavoravel') echo 'selected' ?> value="desfavoravel">Desfavorável</option>
                                </select>
                            </div>
                            <div class="col-lg-3"> <label>Convocado</label>
                                <select name="convocado" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($convocado == '1') echo 'selected' ?> value="1">Sim</option>
                                    <option <?php if($convocado == '0') echo 'selected' ?> value="0">Não</option>
                                </select>
                            </div>
                            <div  class="col-lg-3" id="div_licenciamento">
                                <div  class="form-group"> 
                                    <label>Publicação em BAR Reg, Nº e Data</label>
                                    <input maxlength="100" value="<?php if ($publicacao_bar_reg != '') echo $publicacao_bar_reg ?>" name="publicacao_bar_reg" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >  
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button> 
                    </form>
            </div>            
            
        </div>    
    </div> 
</div>