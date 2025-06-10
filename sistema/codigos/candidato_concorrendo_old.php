<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if(($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos' && $_SESSION['perfil'] != 'avaliador') || $_SESSION['candidato'] == '1')
    {
        erro("Erro 2353565! Página não encontrada!");
        exit();
    }
    
?>
<a name="concorrendo"></a>
<div class="card" <?php if($_SESSION['perfil'] != 'admin') echo ' hidden ' ?>>
    
    <legend>Classificado/Desclassificado</legend>
    <div class="row">
        
        <div class="col-md-6">
        
            <div class="col-md-12">

                <?php 

                    $foto = null;
                    $get_foto_usuario_alterou_concorrendo = $conexao->get_foto_usuario($id_usuario_alterou_concorrendo);

                    if(count($get_foto_usuario_alterou_concorrendo) > 0)
                    {
                        $foto = $get_foto_usuario_alterou_concorrendo[0]['nome'];
                        $foto = "<a href='usuario_visualiza.php?id_usuario=".$id_usuario_alterou_concorrendo."'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                    }
                    else
                        $foto = "<a href='usuario_visualiza.php?id_usuario=".$id_usuario_alterou_concorrendo."'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";

                ?>
                <div <?php if($concorrendo) echo "hidden"; ?> class="alert alert-danger">
                    <b>ELIMINADO!</b> Este candidato NÃO está concorrendo no processo seletivo! <br> <b>Justificativa:</b> <?php echo $justificativa_concorrendo . "<br>".$foto ?>
                </div>
                <div <?php if(!$concorrendo) echo "hidden" ?> class="alert alert-success">
                    <b>Candidato concorrendo no processo seletivo! </b> <?php  if($justificativa_concorrendo != null) echo "<br><b>Justificativa: </b>". $justificativa_concorrendo . "<br>".$foto ?>
                </div>
            </div>
        
            <div class="col-md-12">

                <form action="../banco_dados/candidato_concorrendo.php" method="post">
                    <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input <?php if($concorrendo) echo "checked" ?> type="checkbox" name="concorrendo" >
                                    <span class="label-text">Candidato concorrendo no processo seletivo</span>
                                </label>
                            </div>
                        </div>
                        <div  class="col-lg-12">
                            <label> Justificativa da mudança (será adicionada uma observação)</label><br>
                            <textarea name="justificativa" style="width: 100%"></textarea>
                        </div>
                    </div>
                    <br>
                    <div <?php if($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != 'documentos') echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>

            </div>
        
        </div>
        
        <div class="col-md-6">
            <div class="col-md-12" <?php if($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
                <form action="../banco_dados/candidato_etapa_atualiza.php" method="post">
                    <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave'].$id_usuario."freitas"); ?>">
                    <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>Selecione a ETAPA do candidato</label>
                                <select name="etapa_candidato" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($etapa == 1) echo 'selected' ?> value="1">Etapa I</option>
                                    <option <?php if($etapa == 2) echo 'selected' ?> value="2">Etapa II</option>
                                    <option <?php if($etapa == 3) echo 'selected' ?> value="3">Etapa III</option>
                                    <option <?php if($etapa == 4) echo 'selected' ?> value="4">Etapa IV</option>
                                    <option <?php if($etapa == 5) echo 'selected' ?> value="5">Etapa V</option>
                                    <option <?php if($etapa == 6) echo 'selected' ?> value="6">Etapa VI</option>
                                    <option <?php if($etapa == 7) echo 'selected' ?> value="7">Etapa VII</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            
    </div>
</div>