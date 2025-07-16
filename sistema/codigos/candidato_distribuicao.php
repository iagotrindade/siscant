<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}
?>

<a name="distribuicao"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="row">
    <div class="">
        <div class="card">
            <div class="">
                <form action="../banco_dados/candidato_distribuicao.php" method="post">
                    <legend>Distribuição</legend>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group"> <label>Distribuido</label>
                                <select name="incorporado" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($incorporado == '1') echo 'selected' ?> value="1">Distribuido</option>
                                    <option <?php if ($incorporado == '0') echo 'selected' ?> value="0">Aguardando Distribuição</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>Especialidade Incorporou</label>
                                <select name="especialidade_incorporou" class="form-control">
                                    <option value="">Selecione a especialidade</option>
                                    <?php
                                    foreach ($especialidade_cadastradas_candidato as $esp_cadastrada_pelo_cand) {
                                        if ($especialidade_incorporacao == $esp_cadastrada_pelo_cand['id_especialidade'])
                                            echo "<option selected value='" . $esp_cadastrada_pelo_cand['id_especialidade'] . "'>" . strtoupper($esp_cadastrada_pelo_cand['ott_stt']) . ' - ' . $esp_cadastrada_pelo_cand['especialidade'] . "</option>";
                                        else
                                            echo "<option value='" . $esp_cadastrada_pelo_cand['id_especialidade'] . "'>" . strtoupper($esp_cadastrada_pelo_cand['ott_stt']) . ' - ' . $esp_cadastrada_pelo_cand['especialidade'] . "</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>Data incorporação</label>
                                <input name="data_incorporacao" value="<?php if ($data_incorporacao != null) echo trata_data($data_incorporacao); ?>" maxlength="25" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>Selecione a Distribuição</label>
                                <select name="numero_distribuicao" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($numero_distribuicao == '1') echo 'selected' ?> value="1">1ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '2') echo 'selected' ?> value="2">2ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '3') echo 'selected' ?> value="3">3ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '4') echo 'selected' ?> value="4">4ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '5') echo 'selected' ?> value="5">5ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '6') echo 'selected' ?> value="6">6ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '7') echo 'selected' ?> value="7">7ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '8') echo 'selected' ?> value="8">8ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '9') echo 'selected' ?> value="9">9ª Distribuição</option>
                                    <option <?php if ($numero_distribuicao == '10') echo 'selected' ?> value="10">10ª Distribuição</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>Força</label>
                                <select name="forca_distribuicao" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($forca_distribuicao == 'exercito') echo 'selected' ?> value="exercito">Exército</option>
                                    <option <?php if ($forca_distribuicao == 'marinha') echo 'selected' ?> value="marinha">Marinha</option>
                                    <option <?php if ($forca_distribuicao == 'aeronautica') echo 'selected' ?> value="aeronautica">Aeronáutica</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>Titular Reserva Adiado Excesso</label>
                                <select name="titular_reserva" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'titular') echo 'selected' ?> value="titular">Titular</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'titular eis') echo 'selected' ?> value="titular eis">Titular EIS</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'reserva') echo 'selected' ?> value="reserva">Reserva</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'adiado') echo 'selected' ?> value="adiado">Adiado</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'adiado_b1') echo 'selected' ?> value="adiado_b1">Adiado B1</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'adiado_justica') echo 'selected' ?> value="adiado_justica">Adiado Justiça</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'excesso') echo 'selected' ?> value="excesso">Excesso</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'excesso_incapaz') echo 'selected' ?> value="excesso_incapaz">Excesso Incapaz</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'refratario') echo 'selected' ?> value="refratario">Refratário</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'fisemi_transferida') echo 'selected' ?> value="fisemi_transferida">Fisemi Transferida</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'desobrigado') echo 'selected' ?> value="desobrigado">Desobrigado</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'insubmisso') echo 'selected' ?> value="insubmisso">Insubmisso</option>
                                    <option <?php if ($titular_reserva_distribuicao == 'cdi_justica') echo 'selected' ?> value="cdi_justica">CDI Justiça</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>OM 1º Fase</label>
                                <select name="om_distribuicao_1_fase" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <?php
                                    $oms = $conexao->get_oms($rm_usuario);
                                    foreach ($oms as $value) {
                                        if ($om_1_fase == $value['id'])
                                            echo '<option selected value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                        else
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div id="div_uf" class="form-group"> <label>UF 1º Fase</label>
                                <select id="uf2" name="uf2" class="form-control" onchange="busca_cidades2()">
                                    <option value="">Selecione a UF</option>
                                    <option <?php if ($uf_1_fase == 'AC') echo 'selected' ?> value="AC">AC</option>
                                    <option <?php if ($uf_1_fase == 'AL') echo 'selected' ?> value="AL">AL</option>
                                    <option <?php if ($uf_1_fase == 'AM') echo 'selected' ?> value="AM">AM</option>
                                    <option <?php if ($uf_1_fase == 'AP') echo 'selected' ?> value="AP">AP</option>
                                    <option <?php if ($uf_1_fase == 'BA') echo 'selected' ?> value="BA">BA</option>
                                    <option <?php if ($uf_1_fase == 'CE') echo 'selected' ?> value="CE">CE</option>
                                    <option <?php if ($uf_1_fase == 'DF') echo 'selected' ?> value="DF">DF</option>
                                    <option <?php if ($uf_1_fase == 'ES') echo 'selected' ?> value="ES">ES</option>
                                    <option <?php if ($uf_1_fase == 'GO') echo 'selected' ?> value="GO">GO</option>
                                    <option <?php if ($uf_1_fase == 'MA') echo 'selected' ?> value="MA">MA</option>
                                    <option <?php if ($uf_1_fase == 'MG') echo 'selected' ?> value="MG">MG</option>
                                    <option <?php if ($uf_1_fase == 'MS') echo 'selected' ?> value="MS">MS</option>
                                    <option <?php if ($uf_1_fase == 'MT') echo 'selected' ?> value="MT">MT</option>
                                    <option <?php if ($uf_1_fase == 'PA') echo 'selected' ?> value="PA">PA</option>
                                    <option <?php if ($uf_1_fase == 'PB') echo 'selected' ?> value="PB">PB</option>
                                    <option <?php if ($uf_1_fase == 'PE') echo 'selected' ?> value="PE">PE</option>
                                    <option <?php if ($uf_1_fase == 'PI') echo 'selected' ?> value="PI">PI</option>
                                    <option <?php if ($uf_1_fase == 'PR') echo 'selected' ?> value="PR">PR</option>
                                    <option <?php if ($uf_1_fase == 'RJ') echo 'selected' ?> value="RJ">RJ</option>
                                    <option <?php if ($uf_1_fase == 'RN') echo 'selected' ?> value="RN">RN</option>
                                    <option <?php if ($uf_1_fase == 'RO') echo 'selected' ?> value="RO">RO</option>
                                    <option <?php if ($uf_1_fase == 'RR') echo 'selected' ?> value="RR">RR</option>
                                    <option <?php if ($uf_1_fase == 'RS') echo 'selected' ?> value="RS">RS</option>
                                    <option <?php if ($uf_1_fase == 'SC') echo 'selected' ?> value="SC">SC</option>
                                    <option <?php if ($uf_1_fase == 'SE') echo 'selected' ?> value="SE">SE</option>
                                    <option <?php if ($uf_1_fase == 'SP') echo 'selected' ?> value="SP">SP</option>
                                    <option <?php if ($uf_1_fase == 'TO') echo 'selected' ?> value="TO">TO</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-4">
                            <div id="div_cidade" class="form-group"> <label>Guarnição 1º Fase</label>
                                <select id="cidade2" name="cidade2" class="form-control">
                                    <option value="">Selecione a Cidade</option>
                                    <?php
                                    $resultado3 = $conexao->busca_cidade_uf($uf_1_fase);
                                    foreach ($resultado3 as $value3) {
                                        if ($value3['id'] == $id_cidade_1_fase)
                                            echo '<option selected value="' . $value3['id'] . '">' . $value3['nome'] . '</option>';
                                        else
                                            echo '<option value="' . $value3['id'] . '">' . $value3['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group"> <label>OM de Destino</label>
                                <select name="om_distribuicao" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <?php
                                    $oms = $conexao->get_oms($rm_usuario);
                                    foreach ($oms as $value) {
                                        if ($om_distribuicao == $value['id'])
                                            echo '<option selected value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                        else
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div id="div_uf" class="form-group"> <label>UF Destino</label>
                                <select id="uf" name="uf" class="form-control" onchange="busca_cidades()">
                                    <option value="">Selecione a UF</option>
                                    <option <?php if ($uf_distribuicao == 'AC') echo 'selected' ?> value="AC">AC</option>
                                    <option <?php if ($uf_distribuicao == 'AL') echo 'selected' ?> value="AL">AL</option>
                                    <option <?php if ($uf_distribuicao == 'AM') echo 'selected' ?> value="AM">AM</option>
                                    <option <?php if ($uf_distribuicao == 'AP') echo 'selected' ?> value="AP">AP</option>
                                    <option <?php if ($uf_distribuicao == 'BA') echo 'selected' ?> value="BA">BA</option>
                                    <option <?php if ($uf_distribuicao == 'CE') echo 'selected' ?> value="CE">CE</option>
                                    <option <?php if ($uf_distribuicao == 'DF') echo 'selected' ?> value="DF">DF</option>
                                    <option <?php if ($uf_distribuicao == 'ES') echo 'selected' ?> value="ES">ES</option>
                                    <option <?php if ($uf_distribuicao == 'GO') echo 'selected' ?> value="GO">GO</option>
                                    <option <?php if ($uf_distribuicao == 'MA') echo 'selected' ?> value="MA">MA</option>
                                    <option <?php if ($uf_distribuicao == 'MG') echo 'selected' ?> value="MG">MG</option>
                                    <option <?php if ($uf_distribuicao == 'MS') echo 'selected' ?> value="MS">MS</option>
                                    <option <?php if ($uf_distribuicao == 'MT') echo 'selected' ?> value="MT">MT</option>
                                    <option <?php if ($uf_distribuicao == 'PA') echo 'selected' ?> value="PA">PA</option>
                                    <option <?php if ($uf_distribuicao == 'PB') echo 'selected' ?> value="PB">PB</option>
                                    <option <?php if ($uf_distribuicao == 'PE') echo 'selected' ?> value="PE">PE</option>
                                    <option <?php if ($uf_distribuicao == 'PI') echo 'selected' ?> value="PI">PI</option>
                                    <option <?php if ($uf_distribuicao == 'PR') echo 'selected' ?> value="PR">PR</option>
                                    <option <?php if ($uf_distribuicao == 'RJ') echo 'selected' ?> value="RJ">RJ</option>
                                    <option <?php if ($uf_distribuicao == 'RN') echo 'selected' ?> value="RN">RN</option>
                                    <option <?php if ($uf_distribuicao == 'RO') echo 'selected' ?> value="RO">RO</option>
                                    <option <?php if ($uf_distribuicao == 'RR') echo 'selected' ?> value="RR">RR</option>
                                    <option <?php if ($uf_distribuicao == 'RS') echo 'selected' ?> value="RS">RS</option>
                                    <option <?php if ($uf_distribuicao == 'SC') echo 'selected' ?> value="SC">SC</option>
                                    <option <?php if ($uf_distribuicao == 'SE') echo 'selected' ?> value="SE">SE</option>
                                    <option <?php if ($uf_distribuicao == 'SP') echo 'selected' ?> value="SP">SP</option>
                                    <option <?php if ($uf_distribuicao == 'TO') echo 'selected' ?> value="TO">TO</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-4">
                            <div id="div_cidade" class="form-group"> <label>Guarnição Destino</label>
                                <select id="cidade" name="cidade_distribuicao" class="form-control">
                                    <option value="">Selecione a Cidade</option>
                                    <?php
                                    $resultado2 = $conexao->busca_cidade_uf($uf_distribuicao);
                                    foreach ($resultado2 as $value2) {
                                        if ($value2['id'] == $id_cidade_distribuicao)
                                            echo '<option selected value="' . $value2['id'] . '">' . $value2['nome'] . '</option>';
                                        else
                                            echo '<option value="' . $value2['id'] . '">' . $value2['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>




                        <!-- Auto Completa campo aditamento -->
                        <?php
                        $todos_aditamentos = $conexao->get_aditamentos_convocacao();
                        ?>

                        <script>
                            $(function() {
                                var aditamentos_cadastrados = [
                                    <?php

                                    foreach ($todos_aditamentos as $aditamento) {
                                        echo  '"' . $aditamento['aditamento_convocacao'] . '",';
                                    }
                                    ?> " "
                                ];

                                $("#aditamento").autocomplete({
                                    source: aditamentos_cadastrados
                                });
                            });
                        </script>
                        <div class="col-lg-12">
                            <div class="form-group"> <label>Aditamento de convocação</label>
                                <input name="aditamento_convocacao" id="aditamento" value="<?php if ($aditamento_convocacao != null) echo $aditamento_convocacao; ?>" class="form-control" maxlength="250">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Observações</label>
                                <textarea maxlength="2000" name="observacao_distribuicao" class="form-control"><?php echo $observacao_distribuicao ?></textarea>
                            </div>
                        </div>




                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>" name="crip">
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden>
                    <input value="nao" maxlength="3" name="medico_obrigatorio" hidden>
                    <button type="submit" class="btn btn-primary btn-block">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>