<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

?>
<a name="admin_cadastra_especialidade"></a>
<div class="card" <?php if ($_SESSION['perfil'] != 'admin') echo ' hidden ' ?>>

    <legend>Cadastrar especialidades do candidato</legend>
    <div class="row">

        <div class="col-md-12">

            <form action="../banco_dados/candidato_cadastra_especialidade.php" method="post" onsubmit="return verifica_cadastro_especialidade_candidato()">
                <input hidden name="id_candidato" value="<?php echo $id_usuario ?>">
                <div class="row">

                    <div class="form-group col-lg-6" id="div_ott_stt">
                        <label>Selecione o tipo da especialidade</label>
                        <select id="ott_stt" name="ott_stt" class="form-control" onchange="busca_ott_stt()">


                            <?php
                            //var_dump($codigo_selecao); exit;

                            if ($codigo_selecao == 'mfdv')
                                echo '<option value="medico">Médico</option>
                                        <option value="farmaceutico">Farmacêutico</option>
                                        <option value="dentista">Dentista</option>
                                        <option value="veterinario">Veterinário</option>';


                            if ($codigo_selecao == "ott_stt")
                                echo '<option value="ott">OTT</option>
                                        <option value="stt">STT</option>
                                        <option value="pctd">PCTD</option>';

                            if ($codigo_selecao == 'cet')
                                echo '<option value="cet">CET</option>';

                            if ($codigo_selecao == 'ottm')
                                echo '<option value="ottm">OTTM</option>';

                            if ($codigo_selecao == "eipot")
                                echo '<option value="eipot">EIPOT</option>';
                            ?>
                            <!-- 09/06/2025 Iago Silva // Adicionando o selected para forçar o candidato a selecionar uma opção-->
                            <option value="" selected>Selecione a opção</option>
                        </select>
                    </div>

                    <?php if ($_SESSION['selecao_codigo'] !== "eipot "): ?>
                        <div class="form-group col-lg-6">
                            <label>Registro no Conselho Regional</label>
                            <input id="registro_conselho" maxlength="40" name="registro_conselho" class="form-control">
                        </div>
                    <?php endif; ?>

                    <div class="form-group col-lg-6" id="div_especialidade">
                        <label>Selecione a especialidade?</label>
                        <select id="especialidade" name="especialidade" class="form-control">
                            <?php

                            if ($codigo_selecao == 'mfdv')
                                echo '<option value="mfdv">Primeiro selecione o tipo da especialidade</option>';
                            else
                                echo '<option value="">Primeiramente selecione se a especialidade é OTT ou STT</option>';
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label>Data que habilita a concorrer na especialidade (Conclusão de curso que habilita)</label>
                        <input maxlength="40" name="data_habilitacao" class="form-control">
                    </div>
                </div>

                <div class="row" id="div_mensagem_erro" hidden>
                    <div class="form-group col-lg-12">
                        <font color="red"><b>
                                <center>
                                    <p id="mensagem_erro">Selecione uma opção!</p>
                            </b></center>
                        </font>
                    </div>
                </div>
                <input hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>" name="crip">
                <button type="submit" class="btn btn-primary btn-block">CADASTRAR</button>
            </form>
        </div>
    </div>
</div>