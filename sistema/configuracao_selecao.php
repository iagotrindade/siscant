<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 544654: Página não encontrada");
    exit();
}

if (isset($_GET['datas_atualizadas']) && $_GET['datas_atualizadas'] == 1) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " As datas foram atualizadas!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso']) && $_GET['sucesso'] == "inscricao") {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " Os comprovantes de Inscrição foram alterados!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso']) && $_GET['sucesso'] == "avaliacao_curricular") {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A liberação da avaliação curricular foi atualizada!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso']) && $_GET['sucesso'] == "exame_medico") {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O exame médico foi cadastrado!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso']) && $_GET['sucesso'] == "selecao_encerrada") {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A status da seleção encerrada foi alterado!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso']) && $_GET['sucesso'] == "exame_medico_apagado") {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O exame médico foi cadastrado!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['sucesso'])) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O arquivo do Aviso de Convocação foi atualizado!"
        },{
                type: "info"
        });
    };
    </script>';
}

$get_selecao = $conexao->get_selecao_id();
if ($get_selecao == null) {
    erro("Erro 54234544654: Erro fatal");
    exit();
}

$aviso_convocacao = $get_selecao[0]['aviso_convocacao'];
$libera_assistente_virtual = $get_selecao[0]['liberacao_assistente_virtual'];
$libera_suporte_inicial = $get_selecao[0]['liberacao_suporte_inicial'];
$liberado_comprovante = $get_selecao[0]['liberacao_comprovante_inscricao'];
$eliminar_caso_nao_adicione_foto = $get_selecao[0]['eliminar_caso_nao_adicione_foto'];
$eliminar_docs_obrigatorios = $get_selecao[0]['eliminar_docs_obrigatorios'];
$libera_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];
$liberacao_avaliacao_docs_obrigatorios = $get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'];
$data_maxima_nascimento = $get_selecao[0]['data_maxima_nascimento'];
$data_minima_nascimento = $get_selecao[0]['data_minima_nascimento'];

$data_inicio_cidade = $get_selecao[0]['data_inicio_cidade'];
$data_fim_cidade    = $get_selecao[0]['data_fim_cidade'];

$data_inicio_recurso    = $get_selecao[0]['data_inicio_recurso'];
$data_fim_recurso    = $get_selecao[0]['data_fim_recurso'];

$selecao_encerrada = $get_selecao[0]['encerrada'];
$selecao_pagamento = $get_selecao[0]['pagamento'];
$valor_gru = $get_selecao[0]['valor_gru'];
$apelido_ug = $get_selecao[0]['apelido_ug'];

?>

<script>
    function pagamento_para_selecao() {
        if ($(cobrar_candidato).is(":checked")) {
            $(div_valor).show();
            $(div_apelido).show();
        } else {
            $(div_valor).hide();
            $(div_apelido).hide();
        }
    }
</script>

<!--31/08/2025 -> Iago Silva Remodelando layout -->
<style>
    :root {
        --primary-color: #006400;
        /* Verde escuro como cor primária */
        --secondary-color: #228B22;
        /* Verde floresta como secundária */
        --accent-color: #8B0000;
        /* Vermelho escuro como acento */
        --light-color: #F5F5F5;
        /* Cinza muito claro */
        --success-color: #2E8B57;
        /* Verde mar como cor de sucesso */
        --text-color: #333333;
        /* Cor do texto principal */
        --border-color: #D3D3D3;
        /* Cor das bordas */
    }
    .card-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--text-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #004d00;
        /* Tom mais escuro do verde primário */
        transform: scale(1.02);
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 15px;
        border: none;
    }


    .section-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        font-weight: 700;
    }

    .form-control {
        border-radius: 6px;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
    }

    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(34, 139, 34, 0.1);
    }

    .table-dark {
        background-color: var(--primary-color) !important;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .config-group {
        margin-bottom: 25px;
    }

    .pdf-icon {
        color: var(--accent-color);
        width: 30px;
        transition: transform 0.2s;
    }

    .pdf-icon:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Configuração da Seleção <i class="fa fa-cogs"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Configuração da Seleção</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Agrupamento por categorias -->

        <!-- Documentos e Arquivos -->
        <div class="col-12 ">
            <h3 class="section-title mt-0"><i class="fa fa-files-o"></i> Documentos e Arquivos</h3>
        </div>

        <!-- Aviso de Convocação -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <span><i class="fa fa-file-text-o"></i> Aviso de Convocação</span>

                    <!-- 03/09/2025 -> Iago Silva Validando se o Aviso de Convocação existe para exibi-lo -->
                    <?php if ($aviso_convocacao) : ?>
                        <a href="arquivos/avisos_de_convocacao/<?php echo $aviso_convocacao; ?>" target="_blank">
                            <img src="../sistema/imagens/pdf.png" class="pdf-icon" title="Visualizar Aviso de Convocação">
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> O Assistente Virtual mostrará esse documento aos candidatos em determinadas situações.
                    </div>
                    <form action="../banco_dados/aviso_convocacao_atualiza.php" method="POST" enctype="multipart/form-data">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="mb-10">
                            <label for="avisoFile" class="form-label">Aviso de Convocação Assinado</label>
                            <input type="file" class="form-control" id="avisoFile" name="arquivo">
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Comprovante de Inscrição -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-ticket"></i> Comprovante de Inscrição
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_comprovante_inscricao.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="comprovanteSwitch" name="liberacao" <?php if ($liberado_comprovante == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="comprovanteSwitch">
                                    Liberar a visualização do comprovante de inscrição para o candidato
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agenda e Prazos -->
        <div class="col-12">
            <h3 class="section-title"><i class="fa fa-calendar"></i> Agenda e Prazos</h3>
        </div>

        <!-- Agenda da Inscrição -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-pencil-square-o"></i> Agenda da Inscrição
                </div>
                <div class="card-body">
                    <form action="../banco_dados/agenda_inscricao_atualiza.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataInicioInscricao" class="form-label">Data de início da inscrição</label>
                                <input type="text" class="form-control" id="dataInicioInscricao" name="data_inicio_inscricoes" value="<?php if ($data_inicio_inscricao != null) echo trata_data($data_inicio_inscricao); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataFimInscricao" class="form-label">Último dia para inscrição</label>
                                <input type="text" class="form-control" id="dataFimInscricao" name="data_fim_inscricoes" value="<?php if ($data_fim_inscricao != null) echo trata_data($data_fim_inscricao); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agenda da Isenção -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-dollar"></i> Agenda da Isenção
                </div>
                <div class="card-body">
                    <form action="../banco_dados/agenda_isencao_atualiza.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataInicioIsencao" class="form-label">Data de início da isenção</label>
                                <input type="text" class="form-control" id="dataInicioIsencao" name="data_inicio_isencao" value="<?php if ($data_inicio_isencao != null) echo trata_data($data_inicio_isencao); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataFimIsencao" class="form-label">Último dia para isenção</label>
                                <input type="text" class="form-control" id="dataFimIsencao" name="data_fim_isencao" value="<?php if ($data_fim_isencao != null) echo trata_data($data_fim_isencao); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agenda da Avaliação -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-check-square-o"></i> Agenda da Avaliação
                </div>
                <div class="card-body">
                    <form action="../banco_dados/agenda_avaliacao_atualiza.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataInicioAvaliacao" class="form-label">Data de início das avaliações</label>
                                <input type="text" class="form-control" id="dataInicioAvaliacao" name="data_inicio_avaliacao" value="<?php if ($data_inicio_avaliacao != null) echo trata_data($data_inicio_avaliacao); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataFimAvaliacao" class="form-label">Último dia para avaliações</label>
                                <input type="text" class="form-control" id="dataFimAvaliacao" name="data_fim_avaliacao" value="<?php if ($data_fim_avaliacao != null) echo trata_data($data_fim_avaliacao); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agenda da Cidade -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-map"></i> Agenda da Seleção de Cidade
                </div>
                <div class="card-body">
                    <form action="../banco_dados/agenda_cidade_candidato.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataInicioCidade" class="form-label">Data de início</label>
                                <input type="text" class="form-control" id="dataInicioCidade" name="data_inicio_cidade" value="<?php if ($data_inicio_cidade != null) echo trata_data($data_inicio_cidade); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataFimCidade" class="form-label">Data de fim</label>
                                <input type="text" class="form-control" id="dataFimCidade" name="data_fim_cidade" value="<?php if ($data_fim_cidade != null) echo trata_data($data_fim_cidade); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agenda de Recursos -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-gavel"></i> Agenda de Recursos
                </div>
                <div class="card-body">
                    <form action="../banco_dados/candidato_adiciona_recurso.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataInicioRecurso" class="form-label">Data de início</label>
                                <input type="text" class="form-control" id="dataInicioRecurso" name="data_inicio_recurso" value="<?php if ($data_inicio_recurso != null) echo trata_data($data_inicio_recurso); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataFimRecurso" class="form-label">Data de fim</label>
                                <input type="text" class="form-control" id="dataFimRecurso" name="data_fim_recurso" value="<?php if ($data_fim_recurso != null) echo trata_data($data_fim_recurso); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liberações e Configurações -->
        <div class="col-12">
            <h3 class="section-title"><i class="fa fa-unlock-alt"></i> Liberações e Configurações</h3>
        </div>

        <!-- Status do Assistente Virtual -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-comments-o"></i> Status Assistente Virtual
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_assistente_virtual.php" method="POST">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <?php if ($libera_assistente_virtual != 1): ?>
                            <div class="alert alert-info">
                                <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Quando liberado, o candidato poderá interagir com o Assistente Virtual.
                            </div>
                        <?php endif; ?>

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="assistenteSwitch" name="liberacao" <?php if ($libera_assistente_virtual == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="assistenteSwitch">
                                    Liberar para os candidatos interagirem com o Assistente Virtual
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Suporte Inicial -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-comments-o"></i> Suporte Inicial
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_suporte_inicial.php" method="POST">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <?php if ($libera_suporte_inicial != 1): ?>
                            <div class="alert alert-info">
                                <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Quando liberado, o candidato não inscrito visualizará o formulário de Suporte.
                            </div>
                        <?php endif; ?>

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="suporteSwitch" name="liberacao" <?php if ($libera_suporte_inicial == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="suporteSwitch">
                                    Liberar para os candidatos ainda não inscritos enviarem mensagens ao Suporte
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Visualização da Avaliação Curricular -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-graduation-cap"></i> Visualização da Avaliação Curricular
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_candidato_avaliacao_curricular.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <?php if ($libera_avaliacao_curricular != 1): ?>
                            <div class="alert alert-info">
                                <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Quando liberado, o candidato visualizará a avaliação do seu currículo adicionado.
                            </div>
                        <?php endif; ?>

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="avaliacaoCurricularSwitch" name="liberacao" <?php if ($libera_avaliacao_curricular == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="avaliacaoCurricularSwitch">
                                    Liberar a visualização da avaliação curricular
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Visualização de Docs Obrigatórios -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-files-o"></i> Visualização de Docs Obrigatórios
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_candidato_avaliacao_docs_obrigatorios.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <?php if ($liberacao_avaliacao_docs_obrigatorios != 1): ?>
                            <div class="alert alert-info">
                                <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Quando liberado, o candidato visualizará a avaliação dos Documentos Obrigatórios assim como a justificativa adicionada.
                            </div>
                        <?php endif; ?>

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="docsObrigatoriosSwitch" name="liberacao" <?php if ($liberacao_avaliacao_docs_obrigatorios == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="docsObrigatoriosSwitch">
                                    Liberar a visualização de avaliação de Docs Obrigatórios
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Prioridades de Especialidade -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-list-ol"></i> Prioridades de Especialidade
                </div>
                <div class="card-body">
                    <form action="../banco_dados/liberar_prioridade_candidato.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="prioridadeSwitch" name="liberacao" <?php if ($selecao_libera_prioridade_candidato == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="prioridadeSwitch">
                                    Liberar para o candidato visualizar e cadastrar as prioridades de cada especialidade
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Regras e Validações -->
        <div class="col-12">
            <h3 class="section-title"><i class="fa fa-legal"></i> Regras e Validações</h3>
        </div>

        <!-- Data de Nascimento -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-birthday-cake"></i> Data de Nascimento para Inscrição
                </div>
                <div class="card-body">
                    <form action="../banco_dados/data_maxima_nascimento.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-10">
                                <label for="dataMinimaNascimento" class="form-label">Deve ser menor do que</label>
                                <input type="text" class="form-control" id="dataMinimaNascimento" name="data_minima_nascimento" value="<?php if ($data_minima_nascimento != null) echo trata_data($data_minima_nascimento); ?>">
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="dataMaximaNascimento" class="form-label">Deve ser maior do que (limite de idade)</label>
                                <input type="text" class="form-control" id="dataMaximaNascimento" name="data_maxima_nascimento" value="<?php if ($data_maxima_nascimento != null) echo trata_data($data_maxima_nascimento); ?>">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-refresh"></i> ATUALIZAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pagamento do Candidato -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-dollar"></i> Pagamento do Candidato
                </div>
                <div class="card-body">
                    <form action="../banco_dados/cobrar_pagamento_candidato.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="alert alert-info">
                            <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Ao selecionar esta opção, o campo para o candidato adicionar o arquivo de pagamento vai aparecer e o candidato não passará para próxima etapa caso ele não tenha efetuado o pagamento ou comprovado e aprovada a isenção.
                        </div>

                        <div class=" mb-10">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pagamentoSwitch" name="pagamento" onclick="pagamento_para_selecao()" <?php if ($selecao_pagamento) echo "checked"; ?>>
                                <label class="form-check-label" for="pagamentoSwitch">
                                    Cobrar pagamento do candidato
                                </label>
                            </div>
                        </div>

                        <div id="div_valor" <?php if (!$selecao_pagamento) echo 'hidden'; ?> class="mb-10">
                            <label for="valorCobrado" class="form-label">Valor a ser cobrado</label>
                            <input type="text" class="form-control" id="valorCobrado" name="valor_cobrado" value="<?php echo 'R$ ' . $valor_gru; ?>">
                        </div>

                        <div id="div_apelido" <?php if (!$selecao_pagamento) echo 'hidden'; ?> class="mb-10">
                            <label for="apelidoUg" class="form-label">Apelido da UG/Gestão responsável pela arrecadação (5 dígitos)</label>
                            <input type="text" class="form-control" id="apelidoUg" name="apelido" value="<?php if ($_SESSION['selecao_regiao'] == '3') echo '02435';
                                                                                                            else echo $apelido_ug; ?>">
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Eliminação por Documentos Obrigatórios -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-ban"></i> Eliminação por Documentos Obrigatórios
                </div>
                <div class="card-body">
                    <form action="../banco_dados/eliminar_caso_nao_adicione_docs_obrigatorios.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="eliminarDocsSwitch" name="eliminar" <?php if ($eliminar_docs_obrigatorios == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="eliminarDocsSwitch">
                                    Eliminar candidato caso não adicione os docs obrigatórios
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Eliminação por Foto -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-user-times"></i> Eliminação por Foto
                </div>
                <div class="card-body">
                    <form action="../banco_dados/eliminar_caso_nao_adicione_foto.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="eliminarFotoSwitch" name="eliminar" <?php if ($eliminar_caso_nao_adicione_foto == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="eliminarFotoSwitch">
                                    Eliminar candidato caso não adicione foto
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Encerramento de Seleção -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-lock"></i> Encerramento de Seleção
                </div>
                <div class="card-body">
                    <form action="../banco_dados/encerra_selecao.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">

                        <div class="alert alert-info">
                            <strong><i class="fa fa-exclamation-circle"></i> ATENÇÃO:</strong> Quando a seleção estiver encerrada, somente o administrador poderá fazer o login!
                        </div>

                        <div class="">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="encerrarSelecaoSwitch" name="encerra_selecao" <?php if ($selecao_encerrada == 1) echo "checked"; ?>>
                                <label class="form-check-label" for="encerrarSelecaoSwitch">
                                    Encerrar seleção
                                </label>
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?> class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Exame Médico -->
        <div class="col-12">
            <h3 class="section-title"><i class="fa fa-stethoscope"></i> Exame Médico</h3>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-plus-circle"></i> Cadastrar Nova JISE
                </div>
                <div class="card-body">
                    <form action="../banco_dados/exame_medico_cadastra.php" method="post">
                        <input hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas"); ?>">
                        <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">

                        <div class="row">
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="sessaoExame" class="form-label">Nº da Sessão</label>
                                <input type="text" class="form-control" id="sessaoExame" name="sessao">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="dataExame" class="form-label">Dia do Exame</label>
                                <input type="text" class="form-control" id="dataExame" name="data">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="cidadeExame" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidadeExame" name="cidade">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="presidenteExame" class="form-label">Presidente</label>
                                <input type="text" class="form-control" id="presidenteExame" name="presidente">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="membro1Exame" class="form-label">1º Membro</label>
                                <input type="text" class="form-control" id="membro1Exame" name="membro_1">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-10">
                                <label for="membro2Exame" class="form-label">2º Membro</label>
                                <input type="text" class="form-control" id="membro2Exame" name="membro_2">
                            </div>
                        </div>

                        <div <?php if ($perfil != "admin") echo "hidden"; ?>>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i> CADASTRAR
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <h5><i class="fa fa-list"></i> JISE's Cadastradas</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="tabela_dinamica">
                                <thead class="">
                                    <tr>
                                        <th>Sessão</th>
                                        <th>Dia do Exame</th>
                                        <th>Cidade</th>
                                        <th>Presidente</th>
                                        <th>1º Membro</th>
                                        <th>2º Membro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get_exames_saude = $conexao->get_exames_medico();

                                    foreach ($get_exames_saude as $linha) {
                                        $dia_exame = "";
                                        if ($linha['dia_exame'] != null) $dia_exame = trata_data($linha['dia_exame']);

                                        echo '
                                            <tr>
                                                <td>' . $linha['sessao'] . '</td>
                                                <td>' . $dia_exame . '</td>
                                                <td>' . $linha['cidade'] . '</td>
                                                <td>' . $linha['presidente'] . '</td>
                                                <td>' . $linha['membro_1'] . '</td>
                                                <td>' . $linha['membro_2'] . '</td>
                                                <td>
                                                    <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'exame_medico\')" class="btn btn-sm btn-danger">
                                                        X
                                                    </a>
                                                </td>
                                            </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Função para mostrar/ocultar campos de pagamento
    function pagamento_para_selecao() {
        const checkbox = document.getElementById('pagamentoSwitch');
        const divValor = document.getElementById('div_valor');
        const divApelido = document.getElementById('div_apelido');

        if (checkbox.checked) {
            divValor.removeAttribute('hidden');
            divApelido.removeAttribute('hidden');
        } else {
            divValor.setAttribute('hidden', 'true');
            divApelido.setAttribute('hidden', 'true');
        }
    }

    // Inicialização para garantir estado correto ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        pagamento_para_selecao();
    });
</script>
</div>
<script type="text/javascript">
    //$('#om').select2();
    //$('#secao').select2();
</script>

</body>

</html>
<?php $conexao = null; ?>