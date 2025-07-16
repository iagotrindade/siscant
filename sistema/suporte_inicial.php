<?php
// Removido o exit da função para que o código continue executando
include_once './menu_candidato.php';
include_once './codigos/suporte_inicial_valida.php';
?>

<!-- 06/07/2025 - Iago Silva Pequenos ajustes e melhorias no Layout -->
<div class="content-wrapper">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <legend>
                    <b>Suporte</b> <i class="fa fa-comments"></i>
                </legend>
                <p>Sistema de Seleção de Canditados Temporários</p>
            </div>
            <div class="col-md-6">
                <legend>Data: <?php echo date("d/m/Y"); ?><small class="pull-right"><img src="imagens/3rm.png" width="60px"></small></legend>
                <p>Preencha os campos para solicitar um suporte a Comissão de Seleção</p>
            </div>

        </div>
    </div>

    <div class="row">
        <form action="../banco_dados/suporte_inicial_cadastra.php" method="post" onsubmit="return suporte_inicial_valida()">
            <div class="col-md-12">
                <div class="card">
                    <section class="invoice">
                        <div class="row">
                            <div class="col-xs-12">
                                <legend>Preencha os campos para enviar uma mensagem para a administração
                                </legend>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-dismissible alert-info">
                                    <legend>Todos os campos são obrigatórios</legend>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div id="div_motivo" class="form-group"> <label for="posto">Motivo da mensagem</label>
                                                <select id="motivo" name="motivo" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="nao_consigo_me_cadastrar_sistema">Não estou conseguindo me cadastrar no sistema</option>
                                                    <option value="duvida_preenchimento_campo">Estou em dúvida no preenchimento de um campo</option>
                                                    <option value="nao_consigo_fazer_login">Não estou conseguindo fazer o login com a senha fornecida pelo sistema</option>
                                                    <option value="esqueci_senha">Esqueci a minha senha</option>
                                                    <option value="outro">Outro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div id="div_nome_completo" class="form-group">
                                                <label>Nome completo:</label>
                                                <input id="nome_completo" name="nome_completo" maxlength="120" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div id="div_cpf" class="form-group">
                                                <label align="right">CPF:</label><span id="cpf_mensagem"></span>
                                                <input id="cpf" name="cpf" class="form-control" onfocus="limpa_cpf()" onblur="verifica_cpf()">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div id="div_telefone" class="form-group">
                                                <label>Telefone(s)</label>
                                                <input id="telefone" maxlength="50" name="telefone" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div id="div_mail" class="form-group">
                                                <label>E-Mail</label>
                                                <input id="mail" maxlength="45" name="mail" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div id="div_mail2" class="form-group">
                                                <label>Repita o seu E-Mail</label>
                                                <input id="mail2" maxlength="45" name="mail2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div id="div_mensagem" class="form-group">
                                                <label>Mensagem</label>
                                                <textarea id="mensagem" maxlength="1000" name="mensagem" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div id="div_mensagem_erro" hidden>
                                                <font color="red"><b>
                                                        <center>
                                                            <p id="mensagem_erro"></p>
                                                        </center>
                                                    </b></font>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary btn-block">enviar</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </form>
        <div class="col-lg-12">
            <a href="../index.php"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>

    </div>


</div>

<script type="text/javascript">
    $('body').removeClass("sidebar-mini").addClass("sidebar-collapse");
</script>
</body>

</html>