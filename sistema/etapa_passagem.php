<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 544654: Página não encontrada");
    exit();
}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O script foi executado!"
        },{
                type: "info"
        });
    };
    </script>';
}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 0) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>NADA ACONTECEU!!!</b><br> ",
                message: " Nenhum script foi executado!"
        },{
                type: "warning"
        });
    };
    </script>';
}


$get_selecao = $conexao->get_selecao_id();
$etapa = null;
if (count($get_selecao) > 0)
    $etapa = $get_selecao[0]['etapa'];

$eliminar_caso_nao_adicione_foto = $get_selecao[0]['eliminar_caso_nao_adicione_foto'];
$pagamento_obrigatorio = $get_selecao[0]['pagamento'];
$eliminar_caso_nao_adicione_todos_documentos_obrigatorios = $get_selecao[0]['eliminar_docs_obrigatorios'];

?>


<script>
    function script_selecionado() {
        if ($('#script').val() == 'inscricao')
            $('#div_inscricao').show();
        else
            $('#div_inscricao').hide();

        if ($('#script').val() == 'pagamento')
            $('#div_pagamento').show();
        else
            $('#div_pagamento').hide();

        if ($('#script').val() == 'isentos')
            $('#div_isentos').show();
        else
            $('#div_isentos').hide();

        if ($('#script').val() == 'ctrl_z')
            $('#div_ctrl_z').show();
        else
            $('#div_ctrl_z').hide();
    }

    function passagem_etapa() {
        if ($('#etapa').val() == '2')
            $('#div_etapa2').show();
        else
            $('#div_etapa2').hide();
    }
</script>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Passagem de etapa <i class="fa fa-circle-o-notch"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Passagem de etapa</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <legend>Execução de Script </legend>
                <form action="../banco_dados/script_execucao.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group"> <label>Selecione o Script a ser executado</label>
                                <select id="script" name="script" class="form-control" onchange="script_selecionado()">
                                    <option value="">Selecione o script a ser executado</option>
                                    <option value="inscricao">1 - Desclassificação dos candidatos com pendencias na inscrição</option>
                                    <?php
                                    if ($pagamento_obrigatorio == '1') {
                                        echo '<option value="pagamento">2 - Desclassificação dos candidatos que não realizaram o pagamento da GRU</option>';
                                        echo '<option value="isentos">3 - Desclassificação dos candidatos que foram considerados NÃO ISENTOS e não realizaram pagamento</option>';
                                    }
                                    ?>
                                    <option value="ctrl_z">Ctrl + Z | Classifica todos os desclassificados</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div hidden id="div_inscricao" class="col-lg-12">
                            <label>Detalhamento:</label>
                            <div class="alert alert-dismissible alert-info">
                                <p style="text-align: justify">Este script irá eliminar o candidato caso ele não cumpra os requisitos configurados no sistema que são:
                                    <?php
                                    if ($eliminar_caso_nao_adicione_foto == '1')
                                        echo '<br>* Adicionar uma foto;';
                                    if ($pagamento_obrigatorio == '1')
                                        echo '<br>* Adicionar um arquivo de isenção ou pagamento;';
                                    if ($eliminar_caso_nao_adicione_todos_documentos_obrigatorios == '1')
                                        echo '<br>* Adicionar todos os documentos obrigatórios';
                                    ?>

                                    <br>* Cadastrar pelo menos uma especialidade.

                                    <font color="red"><br><br><b>ATENÇÃO:</b></font> Este script só poderá ser executado após a finalização da data de inscrição.
                                </p>
                            </div>
                        </div>

                        <div hidden id="div_pagamento" class="col-lg-12">
                            <label>Detalhamento:</label>
                            <div class="alert alert-dismissible alert-info">
                                <p style="text-align: justify">Este script irá desclassificar os candidatos que não constam na tabela de pagamento do banco de dados!
                                    <font color="red"><br><br><b>ATENÇÃO:</b></font> Este script só poderá ser executado após a adição no banco de dados da tabela de excel adiquirida pela DA onde consta a relação dos candidatos que efetuaram o pagamento da GRU.
                                </p>
                            </div>
                        </div>

                        <div hidden id="div_isentos" class="col-lg-12">
                            <label>Detalhamento:</label>
                            <div class="alert alert-dismissible alert-info">
                                <p style="text-align: justify">Este script irá desclassificar os candidatos que se declaram isentos e foram avaliados como NÃO ISENTOS e ainda não adicionaram nenhum arquivo de pagamento!
                                    <font color="red"><br><br><b>ATENÇÃO:</b></font> Este script só poderá ser executado após a finalização das inscrições e a execução do script de pagamento.
                                </p>
                            </div>
                        </div>

                        <div hidden id="div_ctrl_z" class="col-lg-12">
                            <label>Detalhamento:</label>
                            <div class="alert alert-dismissible alert-info">
                                <p style="text-align: justify">Este Script irá classificar todos os candidatos que foram desclassificados!
                                    <font color="red"><br><br><b>ATENÇÃO:</b></font> Este script só poderá ser executado caso a seleção esteja na ETAPA I
                                </p>
                            </div>
                        </div>

                    </div>
                    <div <?php if ($perfil != "admin") echo "hidden" ?> class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary btn-block">EXECUTAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <legend>Passagem de etapa da Seleção</legend>
                    <form action="../banco_dados/passagem_etapa.php" method="post">

                        <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group"> <label>Selecione a etapa</label>
                                    <select id="etapa" name="etapa" class="form-control" onchange="passagem_etapa()">
                                        <option value="">Selecione a Etapa</option>
                                        <option <?php if ($etapa == 1) echo "selected" ?> value="1">Etapa I</option>
                                        <option <?php if ($etapa == 2) echo "selected" ?> value="2">Etapa II</option>
                                        <option <?php if ($etapa == 3) echo "selected" ?> value="3">Etapa III</option>
                                        <option <?php if ($etapa == 4) echo "selected" ?> value="4">Etapa IV</option>
                                        <option <?php if ($etapa == 5) echo "selected" ?> value="5">Etapa V</option>
                                        <option <?php if ($etapa == 6) echo "selected" ?> value="6">Etapa VI</option>
                                        <option <?php if ($etapa == 7) echo "selected" ?> value="7">Etapa VII</option>
                                        <option <?php if ($etapa == 8) echo "selected" ?> value="8">Etapa VIII</option>
                                        <option <?php if ($etapa == 9) echo "selected" ?> value="9">Etapa IX</option>
                                        <option <?php if ($etapa == 10) echo "selected" ?> value="10">Etapa X</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div hidden id="div_etapa2" class="col-lg-12">
                                <label>Detalhamento:</label>
                                <div class="alert alert-dismissible alert-info">
                                    <p style="text-align: justify">O escript da Etapa II irá atualizar todos os candidatos para a Etapa II, aqueles que:
                                        <br><br> Estão concorrendo no processo, ou seja, passaram pelos scripts de desclassificação.
                                        <font color="red"><br><br><b>ATENÇÃO:</b></font> Este script só poderá ser executado após a execução dos scripts de desclassificação de candidato referente as suas obrigações na inscrição.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden" ?> class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary btn-block">EXECUTAR</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
    </div>

</div>



</div>
<script type="text/javascript">
    //$('#om').select2();
    //$('#secao').select2();
</script>
</body>

</html>
<?php $conexao = null; ?>