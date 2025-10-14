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

$especialidades = $conexao->get_especialidade();
?>
<style>
    :root {
        --primary-color: #006400;
        --primary-light: #228B22;
        --secondary-color: #6c757d;
        --accent-color: #32CD32;
        --light-bg: #f0f8f0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .card-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #444;
    }

    .form-control,
    .form-control {
        border: 1px solid #ddd;
        transition: var(--transition);
    }

    .form-control:focus,
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 100, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
        width: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 100, 0, 0.3);
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    }

    .alert-info {
        background-color: #e8f4e8;
        border: 1px solid #c0e0c0;
        color: #0c540c;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }

    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
    }

    .info-details {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        border-left: 4px solid var(--primary-light);
    }

    .script-option {
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: var(--transition);
    }

    .script-option:hover {
        background-color: #f0f8f0;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    function script_selecionado() {
        if ($('#script').val() == 'inscricao')
            $('#div_inscricao').show();
        else
            $('#div_inscricao').hide();
        if ($('#script').val() == 'aptos_jise')
            $('#div_aptos_jise').show();
        else
            $('#div_aptos_jise').hide();

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
        <!-- Execução de Script -->
        <div class="col-md-6">
            <div class="card fade-in">
                <div class="card-header">
                    <span class="mb-0">
                        <i class="fa fa-terminal"></i> Execução de Script
                    </span>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/script_execucao.php" method="post">
                        <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="mb-20">
                            <label class="form-label" style="width: 100%;">Selecione o Script a ser executado</label>
                            <select id="script" name="script" class="form-control" onchange="script_selecionado()" style="width: 100%;">
                                <option value="">Selecione o script a ser executado</option>
                                <option value="inscricao">1 - Desclassificação dos candidatos com pendências na inscrição</option>
                                <option value="aptos_jise">2 - Passar para Etapa IV todos candidatos APTOS em JISE</option>
                                <option value="inaptos_jise">3 - Eliminar os candidatos que foram INAPTOS ou NÃO compareceram a IS</option>
                                
                                <?php
                                if ($pagamento_obrigatorio == '1') {
                                    echo '<option value="pagamento">4 - Desclassificação dos candidatos que não realizaram o pagamento da GRU</option>';
                                    echo '<option value="isentos">5 - Desclassificação dos candidatos que foram considerados NÃO ISENTOS e não realizaram pagamento</option>';
                                }
                                ?>
                                <option value="ctrl_z">Ctrl + Z | Classifica todos os desclassificados</option>
                            </select>
                        </div>

                        <div class="script-details mb-20">
                            <div id="div_inscricao" class="info-details" style="display: none;">
                                <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                                <p class="mb-20">Este script irá eliminar o candidato caso ele não cumpra os requisitos configurados no sistema que são:
                                    <?php
                                    if ($eliminar_caso_nao_adicione_foto == '1')
                                        echo '<span class="text-danger"><i class="fa fa-times-circle mr-10"></i>Adicionar uma foto;</span>';
                                    if ($pagamento_obrigatorio == '1')
                                        echo '<br><span class="text-danger"><i class="fa fa-times-circle mr-10"></i>Adicionar um arquivo de isenção ou pagamento;</span>';
                                    if ($eliminar_caso_nao_adicione_todos_documentos_obrigatorios == '1')
                                        echo '<br><span class="text-danger"><i class="fa fa-times-circle mr-10"></i>Adicionar todos os documentos obrigatórios;</span>';
                                    ?>
                                    <br><span class="text-danger"><i class="fa fa-times-circle mr-10"></i>Cadastrar pelo menos uma especialidade.</span>
                                </p>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa fa-exclamation-triangle mr-10"></i>
                                    <strong>ATENÇÃO:</strong> Este script só poderá ser executado após a finalização da data de inscrição.
                                </div>
                            </div>

                            <div id="div_aptos_jise" class="info-details" style="display: none;">
                                <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                                <p class="mb-5">Este script irá passar para Etapa IV os candidatos que constam no SiSCanT como aptos em JISE e que estão CONCORRENDO!</p>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa fa-exclamation-triangle mr-10"></i>
                                    <strong>ATENÇÃO:</strong> Este script só poderá ser executado após passar o SiSCanT para Etapa IV.
                                </div>
                            </div>

                            <div id="div_pagamento" class="info-details" style="display: none;">
                                <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                                <p class="mb-5">Este script irá desclassificar os candidatos que não constam na tabela de pagamento do banco de dados!</p>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa fa-exclamation-triangle mr-10"></i>
                                    <strong>ATENÇÃO:</strong> Este script só poderá ser executado após a adição no banco de dados da tabela de excel adquirida pela DA onde consta a relação dos candidatos que efetuaram o pagamento da GRU.
                                </div>
                            </div>

                            <div id="div_isentos" class="info-details" style="display: none;">
                                <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                                <p class="mb-5">Este script irá desclassificar os candidatos que se declaram isentos e foram avaliados como NÃO ISENTOS e ainda não adicionaram nenhum arquivo de pagamento!</p>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle mr-10"></i>
                                    <strong>ATENÇÃO:</strong> Este script só poderá ser executado após a finalização das inscrições e a execução do script de pagamento.
                                </div>
                            </div>

                            <div id="div_ctrl_z" class="info-details" style="display: none;">
                                <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                                <p class="mb-5">Este Script irá classificar todos os candidatos que foram desclassificados!</p>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle mr-10"></i>
                                    <strong>ATENÇÃO:</strong> Este script só poderá ser executado caso a seleção esteja na ETAPA I.
                                </div>
                            </div>
                        </div>

                        <div class="admin-only" <?php if ($perfil != "admin") echo "hidden" ?>>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-play-circle mr-10"></i>EXECUTAR SCRIPT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Passagem de Etapa da Seleção -->
        <div class="col-md-6">
            <div class="card fade-in">
                <div class="card-header">
                    <span class="mb-0">
                        <i class="fa fa-forward"></i> Passagem de Etapa da Seleção
                    </span>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/passagem_etapa.php" method="post">
                        <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="mb-20">
                            <label class="form-label">Selecione a etapa</label>
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

                        <div id="div_etapa2" class="info-details" style="display: none;">
                            <h6 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h6>
                            <p class="mb-0">O script da Etapa II irá atualizar todos os candidatos para a Etapa II, aqueles que estão concorrendo no processo, ou seja, passaram pelos scripts de desclassificação.</p>
                            <div class="alert alert-warning mt-3">
                                <i class="fa fa-exclamation-triangle mr-10"></i>
                                <strong>ATENÇÃO:</strong> Este script só poderá ser executado após a execução dos scripts de desclassificação de candidato referente às suas obrigações na inscrição.
                            </div>
                        </div>

                        <div class="mt-4 admin-only" <?php if ($perfil != "admin") echo "hidden" ?>>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-forward mr-10"></i>EXECUTAR PASSAGEM DE ETAPA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Passagem de Etapa de Especialidade -->
        <div class="col-md-12">
            <div class="card fade-in">
                <div class="card-header">
                    <span class="mb-0">
                        <i class="fa fa-forward"></i> Passagem de Etapa de Especialidade
                    </span>
                </div>
                <div class="card-body">
                    <form action="<?php echo (isset($_SESSION['eipot']) == 1) ? "../banco_dados/passagem_etapa_arma.php" : "../banco_dados/passagem_etapa_especialidade.php"; ?>" method="post">
                        <input type="hidden" name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label class="form-label">Selecione a Especialidade</label>
                                <select name="id_especialidade" class="form-control">
                                    <option value="">Selecione a Especialidade</option>
                                    <?php
                                    foreach ($especialidades as $especialidade) {
                                        echo ("<option value='" . $especialidade['id'] . "'>" . $especialidade['nome'] . "</option>");
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Quantidade de Candidatos</label>
                                <select name="quantidade" class="form-control">
                                    <option value="">Selecione a quantidade</option>
                                    <?php
                                    for ($i = 1; $i <= 1000; $i++) {
                                        echo ("<option value='" . $i . "'>" . $i . "</option>");
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Selecione a etapa</label>
                                <select name="etapa" class="form-control">
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

                        <div class="info-details mb-20">
                            <h5 class="text-success"><i class="fa fa-info-circle mr-10"></i>Detalhamento:</h5>
                            <p class="mb-20">Esta ação passará os candidatos da especialidade selecionada para a etapa selecionada. Tanto a ETAPA DO CANDIDATO quanto a ETAPA DA ESPECIALIDADE serão atualizadas.</p>
                            <div class="alert alert-warning mt-3">
                                <i class="fa fa-exclamation-triangle mr-10"></i>
                                <strong>ATENÇÃO:</strong> Este script só poderá ser executado após a execução dos scripts de desclassificação de candidato referente às suas obrigações na inscrição.
                            </div>
                        </div>

                        <div class="admin-only" <?php if ($perfil != "admin") echo "hidden" ?>>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-cogs mr-10"></i>EXECUTAR PASSAGEM DE ETAPA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

</body>

</html>
<?php $conexao = null; ?>