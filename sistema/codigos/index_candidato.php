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

if ($liberado_comprovante == 1 && $concorrendo == 1 && !inscricao() && $etapa > 1) {

    $crip_chave = hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']);

    echo ' 
            <div class="card">
            <legend>Comprovante de Inscrição</legend>
                <div class="row" >
                    <div class="col-lg-12">
                        <div class="alert alert-dismissible alert-success">
                            <a href="mpdf/comprovante_inscricao.php?crip=' . $crip_chave . '" target="_blank">
                                <b>SUCESSO!</b> Imprima o seu comprovante de inscrição! Ele comprova que você realizou todas as pendências da inscrição!
                                <img src="imagens/pdf.png" width="45px">
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
}

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

<div class="row" <?php if ($concorrendo == 0) echo " hidden " ?>>
    <div class="col-lg-12">
        <div <?php if ($liberacao_escolha_cidade == false || $_SESSION['candidato_etapa'] < 4 || !isset($_SESSION['eipot']) != 1) echo "hidden"  ?> class="alert alert-dismissible alert-info">
            <a href="candidato_escolha_cidade.php">
                <center><b><img src="imagens/urgente.gif" height="25px"> SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR <img src="imagens/urgente.gif" height="25px"></b></center>
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['eipot'])): ?>
    <div class="row" <?php if ($concorrendo == 0) echo "hidden"; ?>>
        <div class="col-lg-12">
            <div <?php if ($liberacao_escolha_cidade == false || $_SESSION['candidato_etapa'] < 4) echo "hidden"; ?> class="alert alert-dismissible alert-info">
                <a href="candidato_eipot_escolha_cidade.php">
                    <center><b><img src="imagens/urgente.gif" height="25px"> SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR <img src="imagens/urgente.gif" height="25px"></b></center>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card" <?php if ($concorrendo == 1 || $justificativa_concorrendo_processo == null) echo "hidden" ?>>
    <legend>Você foi eliminado(a) do processo seletivo <img src="imagens/urgente.gif" height="25px"></legend>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-danger">
                <b>Justificativa:</b> <?php echo $justificativa_concorrendo_processo; ?>
            </div>
        </div>
    </div>
</div>
<div class="card" <?php if ($foi_desclassificado_em_especialida === false) echo "hidden" ?>>
    <legend>Eliminação em especialidade <img src="imagens/urgente.gif" height="25px"></legend>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-danger">
                <?php
                foreach ($get_especialidades_candidato as $linha) {
                    if ($linha['concorrendo'] == 0)
                        echo "<b>" . strtoupper($linha['ott_stt']) . " " . $linha['especialidade'] . "</b> Justificativa: " . $linha['justificativa'] . "<br>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div <?php if ($visualizacao_avaliacao_curricular == false || inscricao()) echo "hidden"  ?> class="alert alert-dismissible alert-warning">
            <a href="candidato_especialidade_visualiza.php">
                <center><b>Clique na especialidade desejada e veja a avaliação do seu currículo! <img src="imagens/urgente.gif" height="25px"></b></center>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div <?php if ($concorrendo != 1 || !inscricao()) echo "hidden"  ?> class="alert alert-dismissible alert-warning">
            <center><b>O seu comprovante de inscrição será liberado AQUI após o término das inscrições e a validação dos arquivos adicionados!</b></center>
        </div>
    </div>
</div>

<div class="row" <?php if (!inscricao() || !$liberacao_avaliacao_docs_obrigatorios) echo "hidden" ?>>
    <div class="col-lg-12">
        <div <?php if (!$possui_docs_obrigatorios_invalidos) echo "hidden"  ?> class="alert alert-dismissible alert-warning">
            <center><b> O Sr(a) possui documento(s) de Inscrição(s) inválido(s), que poderão ser substituidos até o final do período da inscrição! <a href="../sistema/documentos_obrigatorios_visualiza.php"><u>Clique aqui para substitui-lo(s)</u></a> <img src="imagens/urgente.gif" width="30px"></b></center>
        </div>
    </div>
</div>


<div class="card">
    <legend>Evolução da Inscrição</legend>
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

        <div class="col-lg-6">
            <center>

                <a href="foto_upload.php">
                    <img src="imagens/<?php echo $imagem_foto ?>" width="75px">
                    <br>Sua foto
                </a>
                <?php if ($pagamento_selecao == '1') echo " <br><br> "; ?>
                <img <?php if ($pagamento_selecao == '0' || $pagamento_selecao == null) echo " hidden "; ?> src="imagens/seta_baixo.png" width="25px">
                <?php if ($pagamento_selecao == '1') echo " <br><br> "; ?>


                <a <?php if ($pagamento_selecao == '0' || $pagamento_selecao == null) echo " hidden "; ?> href="candidato_pagamento_inscricao.php">
                    <img src="imagens/<?php echo $imagem_pagamento ?>" width="60px">
                    <br>Requerimento de Isenção OU Pagar GRU no Banco do Brasil
                </a>
                <?php if ($pagamento_selecao == '1' || $pagamento_selecao == null) echo " <br><br> "; ?>

                <img src="imagens/seta_baixo.png" width="25px">
                <br><br>

                <a href="documentos_obrigatorios_visualiza.php">
                    <img src="imagens/<?php echo $imagem_doc_obrigatorio ?>" width="70px">
                    <br>Documentos de Inscrição (adicione TODOS documentos previstos)
                </a>
                <br><br>

                <img src="imagens/seta_baixo.png" width="25px">
                <br><br>

                <a <?php if (isset($_SESSION['eipot'])) echo " hidden " ?> href="candidato_especialidade_visualiza.php">
                    <img src="imagens/<?php echo $imagem_inscricao ?>" width="70px">
                    <br>Especialidade(s) e Documentos de Currículo (Para pontuação e classificação)
                </a>

                <a <?php if (!isset($_SESSION['eipot'])) echo " hidden " ?> href="candidato_especialidade_visualiza_eipot.php">
                    <img src="imagens/<?php echo $imagem_inscricao ?>" width="70px">
                    <br>Cadastro na Respectiva Arma de Formação e Diploma(s)
                </a>

                <br><br>

            </center>
        </div>

        <div class="col-lg-6">
            <center>

                <div class="bs-component" <?php if (count($foto_usuario) > 0) echo "hidden" ?>>
                    <a class="alert-link" href="foto_upload.php">
                        <div class="alert alert-laranja">
                            <b>FALTANDO a sua foto</b>
                        </div>
                    </a>
                </div>

                <div class="bs-component"
                    <?php
                    //if(!inscricao() || $_SESSION['selecao_codigo'] != 'ott_stt') echo " hidden ";  
                    if (count($lista_docs_obrigatorios_sobrando) == 0) echo " hidden ";
                    ?>>
                    <a class="alert-link" href="documentos_obrigatorios_visualiza.php">
                        <div class="alert alert-laranja">
                            <center><b>FALTANDO Documentos de Inscrição!
                            </center>
                        </div>
                    </a>
                </div>

                <div class="bs-component" <?php if (count($arquivo_pagamento) > 0 || $pagamento_selecao == '0' || $pagamento_selecao == null) echo " hidden " ?>>
                    <a class="alert-link" href="candidato_pagamento_inscricao.php">
                        <div class="alert alert-laranja">
                            <b>FALTANDO Comprovante de pagamento/isenção</b>
                        </div>
                    </a>
                </div>

                <div class="bs-component" <?php if (count($lista_inscricoes) > 0 || isset($_SESSION['eipot'])) echo " hidden " ?>>
                    <a class="alert-link" href="candidato_especialidade_visualiza.php">
                        <div class="alert alert-laranja">
                            <b>FALTANDO Cadastro de Especialidade e Respectivo Currículo</b>
                        </div>
                    </a>
                </div>

                <div class="bs-component" <?php if (count($lista_inscricoes) > 0 ||  !isset($_SESSION['eipot'])) echo " hidden " ?>>
                    <a class="alert-link" href="candidato_especialidade_visualiza_eipot.php">
                        <div class="alert alert-laranja">
                            <b>FALTANDO Cadastro na Arma de Formação e Respectivo Diploma</b>
                        </div>
                    </a>
                </div>

                <!--
            <div class="bs-component" <?php if (!inscricao() || $_SESSION['selecao_codigo'] != 'mfdv') echo "hidden"  ?>>
                <a class="alert-link" href="candidato_especialidade_visualiza.php">
                    <div class="alert alert-laranja">
                        <b>Não esqueça de adicionar o seu currículo (para pontuação) após ter se cadastrado em uma especialidade </b> 
                    </div>
                </a>
            </div>
                -->

                <div>
                    <div class="bs-component">
                        <div class="alert alert-dismissible alert-info">
                            <a target="_blank" href="mpdf/relatorio_completo_candidato.php?codigo=<?php
                                                                                                    echo hash('sha256', $_SESSION['chave']);
                                                                                                    echo "&id_candidato=" . $_SESSION['id_usuario'];
                                                                                                    ?>">
                                <b>Gerar PDF RESUMO do seu cadastro </b>
                                <img src="imagens/pdf.png" width="45px">
                            </a>
                        </div>
                    </div>
                </div>
            </center>
        </div>
    </div>
</div>