<?php
include_once 'codigos/funcao_apagar.php';

$resultado_selecao = $conexao->get_selecao_id();

$lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);

$foto_usuario = $conexao->get_foto_usuario($_SESSION['id_usuario']);

$arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($_SESSION['id_usuario']);


$lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($_SESSION['id_usuario']);

$get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
$lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando_candidato);


$docs_obrigatorios_inseridos_candidato = $conexao->get_docs_obrigatorios_inseridos_candidato($_SESSION['id_usuario']);
$possui_docs_obrigatorios_invalidos = false;
foreach ($docs_obrigatorios_inseridos_candidato as $linha) {
    if ($linha['valido'] == '0' && $linha['valido'] != null) {
        $possui_docs_obrigatorios_invalidos = true;
        break;
    }
}

//$pendencias = true;
//if(count($lista_inscricoes) > 0 && count($foto_usuario) > 0 && count($lista_docs_obrigatorios_sobrando) == 0 && count($arquivo_pagamento) > 0)
//    $pendencias = false;

$get_selecao = $conexao->get_selecao_id();
$liberado_comprovante = $get_selecao[0]['liberacao_comprovante_inscricao'];
$visualizacao_avaliacao_curricular = false;

$get_selecao = $conexao->get_selecao_id();
if ($get_selecao[0]['liberacao_avaliacao_curricular'] == 1)
    $visualizacao_avaliacao_curricular = true;

$liberacao_avaliacao_docs_obrigatorios = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == 1)
    $liberacao_avaliacao_docs_obrigatorios = true;

$liberacao_escolha_cidade = false;
if (seleciona_cidade_vai_servir())
    $liberacao_escolha_cidade = true;


// Verifica se ele foi desclassificado em alguma especialidade
$get_especialidades_candidato = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
$foi_desclassificado_em_especialida = false;
foreach ($get_especialidades_candidato as $linha) {
    if ($linha['concorrendo'] == 0) $foi_desclassificado_em_especialida = true;
}
?>

<!-- Escola de Guarnição OTT/STT/MFDV -->
<?php if ($concorrendo != 0 && $liberacao_escolha_cidade == true && $_SESSION['candidato_etapa'] > 5 && !isset($_SESSION['eipot'])): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-warning text-dark mb-20">
                    <div class="documento-info">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="fw-semibold">Escolha de Guarnição</span>
                    </div>
                </div>
                <div class="card-body text-center py-4">
                    <a href="candidato_escolha_cidade.php" class="text-decoration-none">
                        <div class="documento-info" style="justify-content: center;">
                            <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="mr-3">
                            <h4 class="fw-bold text-dark mb-0">
                                Selecione a Guarnição na qual deseja servir
                            </h4>
                            <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="ml-3">
                        </div>
                        <p class="text-muted mt-2 mb-0">Clique aqui para fazer sua escolha</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Escola de Guarnição EIPOT -->
<?php if (isset($_SESSION['eipot'])): ?>
    <?php if ($concorrendo != 0): ?>
        <div class="row">
            <div class="col-lg-12">
                <?php if ($liberacao_escolha_cidade == true && $_SESSION['candidato_etapa'] > 4) : ?>
                    <div class="card">
                        <div class="card-header bg-warning text-dark mb-20">
                            <div class="documento-info">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="fw-semibold">ESCOLHA DE GUARNIÇÃO</span>
                            </div>
                        </div>
                        <div class="card-body text-center py-4">
                            <a href="candidato_eipot_escolha_cidade.php" class="text-decoration-none">
                                <div class="documento-info" style="justify-content: center;">
                                    <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="mr-3">
                                    <h4 class="fw-bold text-dark mb-0">
                                        SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR
                                    </h4>
                                    <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="ml-3">
                                </div>
                                <p class="text-muted mt-2 mb-0">Clique aqui para fazer sua escolha</p>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Aviso de Liberação do Comprovante de Inscrição -->
<?php if ($concorrendo == 1 && inscricao()): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info text-white mb-20">
                    <div class="documento-info">
                        <i class="fa fa-info-circle"></i>
                        <span class="mb-0">Comprovante de Inscrição</span>
                    </div>
                </div>
                <div class="card-body text-center py-3">
                    <div class="alert alert-info alert-dismissible border-0 mb-0">
                        <div class="documento-info justify-content-center">
                            <i class="fa fa-clock-o mr-3 text-info"></i>
                            <h5 class="fw-bold text-info mb-0">
                                O seu comprovante de inscrição será liberado AQUI após o término das inscrições e a validação dos arquivos adicionados!
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Comprovante de Inscrição -->
<?php if ($liberado_comprovante == 1 && $concorrendo == 1 && !inscricao() && $etapa > 1):
    $crip_chave = hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']);
?>
    <div class="card">
        <div class="card-header bg-success text-white mb-20">
            <span class="mb-0"><i class="fa fa-check-circle"></i> Comprovante de Inscrição</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success alert-dismissible">
                        <div class="documento-info">
                            <a href="mpdf/comprovante_inscricao.php?crip=<?php echo $crip_chave; ?>" target="_blank" class="text-decoration-none" style="display: flex; align-items: center;">
                                <i class="fa fa-file-pdf-o fa-3x text-danger mr-10"></i>
                                <div>

                                    <h5 class="fw-bold text-success mb-2">
                                        <i class="fa fa-check"></i> Comprovante de Inscrição Disponível
                                    </h5>
                                    <p class="mb-0 text-dark">
                                        Imprima e/ou Salve o seu comprovante de inscrição clicando aqui!
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Avisso de Desclassificação do Processo ou em Especialidades -->
<?php if ($concorrendo == 0 && $justificativa_concorrendo_processo): ?>
    <div class="card">
        <div class="card-header bg-danger text-white mb-20">
            <div class="documento-info">
                <i class="fa fa-exclamation-triangle"></i>
                <span class="mb-0">Você foi eliminado(a) do processo seletivo <img src="imagens/urgente.gif" height="25px"></span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger alert-dismissible">
                        <div class="documento-info">
                            <i class="fa fa-ban fa-2x"></i>
                            <div>
                                <h5 class="fw-bold mb-2">Justificativa:</h5>
                                <p class="mb-0"><?php echo $justificativa_concorrendo_processo; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($foi_desclassificado_em_especialida === true): ?>
    <div class="card">
        <div class="card-header bg-danger text-white mb-20">
            <div class="documento-info">
                <i class="fa fa-times-circle"></i>
                <span class="mb-0">Eliminação em especialidade <img src="imagens/urgente.gif" height="25px"></span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger alert-dismissible">
                        <div class="documento-info" style="align-items: flex-start;">
                            <i class="fa fa-ban mt-1 fa-2x"></i>
                            <div>
                                <h5 class="fw-bold mb-3">Especialidades com eliminação:</h5>
                                <?php foreach ($get_especialidades_candidato as $linha): ?>
                                    <?php if ($linha['concorrendo'] == 0): ?>
                                        <div class="mb-3 p-3 border-start border-4 border-danger bg-light">
                                            <h6 class="fw-semibold text-danger mb-2">
                                                <?php echo mb_strtoupper($linha['ott_stt']) . " - " . $linha['especialidade']; ?>
                                            </h6>
                                            <p class="mb-0">
                                                <span class="fw-semibold">Justificativa:</span>
                                                <?php echo $linha['justificativa']; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Avaliação Curricular -->
<?php if ($visualizacao_avaliacao_curricular == true || !inscricao()) : ?>
    <div class="card">
        <div class="card-header bg-warning text-dark mb-20">
            <div class="documento-info">
                <i class="fa fa-eye"></i>
                <span class="mb-0">Avaliação Curricular</span>
            </div>
        </div>

        <div class="card-body text-center py-3">
            <a href="candidato_especialidade_visualiza.php" class="text-decoration-none">
                <div class="documento-info" style="justify-content: center;">
                    <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="mr-3">
                    <h5 class="fw-bold text-dark mb-0">
                        Clique aqui e veja a avaliação do seu currículo!
                    </h5>
                    <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="ml-3">
                </div>
                <p class="text-muted mt-2 mb-0">Acesse para visualizar a avaliação detalhada</p>
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Documentos Obrigatórios -->
<?php if ($liberacao_avaliacao_docs_obrigatorios): ?>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div <?php if (!$possui_docs_obrigatorios_invalidos) echo "hidden"; ?> class="card">
                <div class="card-header text-dark mb-20">
                    <div class="documento-info">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="mb-0">Documentos Obrigatórios</span>
                    </div>
                </div>
                <div class="card-body text-center py-3">
                    <div class="alert alert-dismissible border-0 mb-0">
                        <a href="../sistema/documentos_obrigatorios_visualiza.php" class="text-decoration-none">
                            <div class="documento-info" style="justify-content: center;">
                                <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="mr-3">
                                <h5 class="fw-bold text-dark mb-0">
                                    O Sr(a) possui documento(s) de Inscrição(s) inválido(s), que poderão ser substituídos até o final do período da inscrição!
                                </h5>
                                <img src="imagens/urgente.gif" height="30px" alt="Urgente" class="ml-3">
                            </div>
                            <p class="text-muted mt-2 mb-0">
                                <u>Caso as inscrições estejam encerradas, você deve levar os documentos corrigidos na Etapa III, caso convocado.</u>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Evolução da Inscrição -->
<div class="card">
    <div class="card-header bg-primary text-white mb-20">
        <span class="mb-0"><i class="fa fa-tasks"></i> Evolução da Inscrição</span>
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            ///////////////////// FOTO
            $imagem_foto = null;
            if (count($foto_usuario) > 0)
                $imagem_foto = "camera.png";
            else
                $imagem_foto = "camera_red.png";

            ///////////////////// PAGAMENTO
            $imagem_pagamento = null;
            if (count($arquivo_pagamento) > 0)
                $imagem_pagamento = "pagamento.png";
            else
                $imagem_pagamento = "pagamento_red.png";

            ///////////////////// DOCS OBRIGATÓRIOS
            $imagem_doc_obrigatorio = null;
            if (count($lista_docs_obrigatorios_sobrando) == 0)
                $imagem_doc_obrigatorio = "doc_obrigario.png";
            else
                $imagem_doc_obrigatorio = "doc_obrigario_red.png";

            ///////////////////// INSCRIÇÃO
            $imagem_inscricao = null;
            if (count($lista_inscricoes) > 0)
                $imagem_inscricao = "especialidade.png";
            else
                $imagem_inscricao = "especialidade_red.png";
            ?>

            <!-- Coluna da Esquerda - Preservada com imagens -->
            <div class="col-lg-6">
                <center>
                    <!-- Foto -->
                    <a href="foto_upload.php" class="text-decoration-none">
                        <img src="imagens/<?php echo $imagem_foto ?>" width="75px" class="mb-2">
                        <br>
                        <span class="fw-semibold">Sua foto</span>
                    </a>

                    <?php if ($pagamento_selecao == '1'): ?>
                        <br><br>
                        <img src="imagens/seta_baixo.png" width="25px">
                        <br><br>
                    <?php endif; ?>

                    <!-- Pagamento -->
                    <?php if ($pagamento_selecao == '1'): ?>
                        <a href="candidato_pagamento_inscricao.php" class="text-decoration-none">
                            <img src="imagens/<?php echo $imagem_pagamento ?>" width="60px" class="mb-2">
                            <br>
                            <span class="fw-semibold">Requerimento de Isenção OU Pagar GRU no Banco do Brasil</span>
                        </a>
                    <?php endif; ?>

                    <br><br>
                    <img src="imagens/seta_baixo.png" width="25px">
                    <br><br>

                    <!-- Documentos Obrigatórios -->
                    <a href="documentos_obrigatorios_visualiza.php" class="text-decoration-none">
                        <img src="imagens/<?php echo $imagem_doc_obrigatorio ?>" width="70px" class="mb-2">
                        <br>
                        <span class="fw-semibold">Documentos de Inscrição</span>
                        <br>
                        <small class="text-muted">(adicione TODOS documentos previstos)</small>
                    </a>
                    <br><br>

                    <img src="imagens/seta_baixo.png" width="25px">
                    <br><br>

                    <!-- Especialidade/Currículo -->
                    <?php if (!isset($_SESSION['eipot'])): ?>
                        <a href="candidato_especialidade_visualiza.php" class="text-decoration-none">
                            <img src="imagens/<?php echo $imagem_inscricao ?>" width="70px" class="mb-2">
                            <br>
                            <span class="fw-semibold">Especialidade(s) e Documentos de Currículo</span>
                            <br>
                            <small class="text-muted">(Para pontuação e classificação)</small>
                        </a>
                    <?php else: ?>
                        <a href="candidato_especialidade_visualiza_eipot.php" class="text-decoration-none">
                            <img src="imagens/<?php echo $imagem_inscricao ?>" width="70px" class="mb-2">
                            <br>
                            <span class="fw-semibold">Cadastro na Respectiva Arma de Formação e Diploma(s)</span>
                        </a>
                    <?php endif; ?>
                    <br><br>
                </center>
            </div>

            <!-- Coluna da Direita - Modernizada -->
            <div class="col-lg-6">
                <!-- Alertas de Pendências -->
                <?php if (count($foto_usuario) == 0): ?>
                    <div class="alert alert-warning mb-3">
                        <div class="documento-info">
                            <i class="fa fa-exclamation-circle mr-10"></i>
                            <div>
                                <a href="foto_upload.php" class="text-decoration-none text-dark">
                                    <strong>FALTANDO a sua foto</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (count($lista_docs_obrigatorios_sobrando) > 0): ?>
                    <div class="alert alert-warning mb-3">
                        <div class="documento-info">
                            <i class="fa fa-exclamation-circle mr-10"></i>
                            <div>
                                <a href="documentos_obrigatorios_visualiza.php" class="text-decoration-none text-dark">
                                    <strong>FALTANDO Documentos de Inscrição!</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pagamento_selecao == '1' && count($arquivo_pagamento) == 0): ?>
                    <div class="alert alert-warning mb-3">
                        <div class="documento-info">
                            <i class="fa fa-exclamation-circle mr-10"></i>
                            <div>
                                <a href="candidato_pagamento_inscricao.php" class="text-decoration-none text-dark">
                                    <strong>FALTANDO Comprovante de pagamento/isenção</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (count($lista_inscricoes) == 0): ?>
                    <div class="alert alert-warning mb-3">
                        <div class="documento-info">
                            <i class="fa fa-exclamation-circle mr-10"></i>
                            <div>
                                <?php if (!isset($_SESSION['eipot'])): ?>
                                    <a href="candidato_especialidade_visualiza.php" class="text-decoration-none text-dark">
                                        <strong>FALTANDO Cadastro de Especialidade e Respectivo Currículo</strong>
                                    </a>
                                <?php else: ?>
                                    <a href="candidato_especialidade_visualiza_eipot.php" class="text-decoration-none text-dark">
                                        <strong>FALTANDO Cadastro na Arma de Formação e Respectivo Diploma</strong>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- PDF Resumo -->
                <div class="card-checkbox mt-4">
                    <a target="_blank" href="mpdf/relatorio_completo_candidato.php?codigo=<?php
                                                                                            echo hash('sha256', $_SESSION['chave']);
                                                                                            echo "&id_candidato=" . $_SESSION['id_usuario'];
                                                                                            ?>" class="text-decoration-none">
                        <div class="documento-info">
                            <i class="fa fa-file-pdf-o fa-2x text-danger mr-10"></i>
                            <div>
                                <h5 class="fw-semibold mb-1">Gerar PDF Resumo</h5>
                                <p class="text-muted">Resumo completo do seu cadastro</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>