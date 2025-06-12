<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 542344654: Página não encontrada");
    exit();
}

?>

<script>
    window.onload = function() {
        $('#especialidades').hide();
    }
</script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>

<script>
    function verifica_perfil() {
        if ($('#perfil').val() == 'avaliador') {
            $('#especialidades').show();
        } else {
            $('#especialidades').hide();
        }
    }
</script>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Cadastra Usuário <i class="fa fa-user-plus"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Cadastra Usuário</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <legend>Preencha os campos para cadastrar um novo Usuário <font color="red" size="2px">Todos os campos são obrigatórios</font>
                </legend>
                <form action="../banco_dados/usuario_cadastra.php" method="post" onsubmit="return validar_formulario()">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="div_nome" class="form-group">
                                <label for="nome">Nome completo:</label>
                                <input id="nome" name="nome_completo" maxlength="120" class="form-control">
                            </div>
                            <div id="div_cpf" class="form-group">
                                <label align="right">CPF:</label><span id="cpf_mensagem"></span>
                                <input id="cpf" name="cpf" class="form-control" onfocus="limpa_cpf()" onblur="verifica_cpf()">
                            </div>
                            <div id="div_nome_guerra" class="form-group">
                                <label>Nome de guerra:</label>
                                <input id="nome_guerra" name="nome_guerra" maxlength="20" class="form-control">
                            </div>
                            <div id="div_mail" class="form-group">
                                <label>E-Mail</label>
                                <input id="mail" maxlength="50" name="mail" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div id="div_posto" class="form-group"> <label for="posto">Posto/Graduação</label>
                                <select id="posto" name="posto_grad" class="form-control">
                                    <option value="">Selecione o Posto/Graduação</option>
                                    <option value="Sd">Soldado</option>
                                    <option value="Cb">Cabo</option>
                                    <option value="3º Sgt">3º Sargento</option>
                                    <option value="2º Sgt">2º Sargento</option>
                                    <option value="1º Sgt">1º Sargento</option>
                                    <option value="ST">Sub Tenente</option>
                                    <option value="Asp">Aspirante</option>
                                    <option value="2º Ten">2º Tenente</option>
                                    <option value="1º Ten">1º Tenente</option>
                                    <option value="Cap">Capitão</option>
                                    <option value="Maj">Major</option>
                                    <option value="TCel">Ten Coronel</option>
                                    <option value="Cel">Coronel</option>
                                    <option value="Gen">General</option>
                                </select>
                            </div>

                            <div <?php //if(isset($_SESSION['eipot'])) { echo "hidden"; } 
                                    ?> id="" class="form-group"> <label>Organização Militar </label>
                                <select id="om" name="om" class="form-control">
                                    <option value="">Selecione a OM</option>
                                    <?php
                                    $oms = $conexao->get_all_oms();
                                    foreach ($oms as $value) {
                                        echo '<option value="' . $value['id'] . '">' . $value['nome'] . ' (' . $value['abreviatura'] . ')</option>';
                                    }
                                    ?>
                                </select>
                            </div>


                            <div id="div_ramal" class="form-group">
                                <label>Ramal/Telefone</label>
                                <input maxlength="20" name="telefone" class="form-control">
                            </div>

                            <!-- 10/06/2025 Adicionando perfil Membro CHC e Membro CR -->
                            <div id="div_perfil" class="form-group"> <label>Perfil</label>
                                <select id="perfil" name="perfil" class="form-control" onchange="verifica_perfil()">
                                    <option value="">Selecione o Perfil</option>
                                    <option value="admin">Administrador </option>
                                    <option value="consulta">Consulta / Auditor</option>
                                    <option value="ouvidor">Ouvidor</option>
                                    <option value="avaliador">Avaliador de currículo</option>
                                    <option value="documentos">Avaliador de docs obrigatórios</option>
                                    <option value="jise">JISE</option>
                                    <option value="chc">Comissão Heteroidentificação</option>
                                    <option value="cr">Comissão Revisional</option>
                                    <option value="om">Organização Militar (OM)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group" id="especialidades">
                                <label>Selecione as especialidade do avaliador:</label><br>
                                <select style="width: 100%" class="js-example-basic-multiple" name="especialidades[]" multiple>
                                    <?php
                                    $resultado = $conexao->get_especialidade();
                                    foreach ($resultado as $value) {
                                        echo '<option value="' . $value['id'] . '">' . mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . $value['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div id="div_mail" class="form-group">
                                <center><br>
                                    <font color='green'><b> A SENHA DO USUÁRIO SERÁ <u>123@siscant</u></b></font>
                                </center>
                            </div>

                            <div id="mensagem_erro" hidden>
                                <font color="red"><b>
                                        <center>
                                            <p id="mensagem"></p>
                                        </center>
                                    </b></font>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">CADASTRAR</button>
                </form>
            </div>
            <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $('#om').select2();
    //$('#secao').select2();
</script>
</body>

</html>
<?php $conexao = null; ?>