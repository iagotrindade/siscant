<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($_SESSION['id_usuario']);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Documentos de Inscrição <i class="fa fa-upload"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Documentos de Inscrição</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if (!inscricao()) echo " <font color = 'red' size='5px'> INSCRIÇÕES ENCERRADAS </font> " ?>
            <div class="card" <?php if (!inscricao()) echo " hidden " ?>>
                <legend>Documentos de Inscrição a serem adicionados.</legend>
                <div class="row">
                    <div class="col-lg-12" <?php if ($quantidade_docs_faltantes == 0) echo "hidden" ?>>
                        <form method="post" action="arquivo_upload_doc_obrigatorio.php" enctype="multipart/form-data">
                            <input hidden name="id_candidato" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Documentos conforme o aviso de convocação <font color="red"> *Máximo 5 MegaBytes no formato PDF</font></label>
                                        <select name="id_arquivo_obrigatorio" class="form-control">
                                            <option value="">Selecione o arquivo a ser adicionado</option>
                                            <?php
                                            foreach ($filtro_lista_docs_obrigatorios_sobrando as $linha) {
                                                echo '<option value="' . $linha['id'] . '">' . $linha['nome'] . ' </option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <input type="text" hidden name="crip" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br> <input type="file" name="arquivo" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary btn-block" value="Enviar Arquivo" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if ($quantidade_docs_faltantes == 0)
                        echo '
                            <div class="col-lg-12">
                                <div class="alert alert-dismissible alert-success">
                                    <b>Todos os Documentos de Inscrição foram adicionados! </b> <img src="imagens/ok.png" width="35px">
                                </div>
                            </div>';
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-bordered" id="tabela_dinamica">
                                <thead>
                                    <tr>
                                        <th>Nome do arquivo</th>
                                        <?php
                                        if ($liberado_para_visualizar_avaliacao_docs_obr)
                                            echo '<th width="20px">Avaliado</th>
                                <th width="20px">Justificativa</th>';
                                        ?>
                                        <th width="20px">Apagar</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($_SESSION['id_usuario']);

                                    foreach ($lista_docs_obrigatorios as $linha) {

                                        $validado = null;

                                        if ($linha['valido'] == '0' && $linha['valido'] != null)
                                            $validado = "<img  src='imagens/no_like.jpg' width='40px'>";
                                        else if ($linha['valido'] == '1')
                                            $validado = "<img  src='imagens/like.jpg' width='40px'>";

                                        $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_documentacao_obrigatoria']);
                                        echo '
                                <tr>
                                    <td><a href="baixaPDF.php?codigo=doc_obr_vis&nome_arquivo=' . $linha['nome'] . '" target="_blank">' . $linha['label'] . '</a></td>';
                                        if ($liberado_para_visualizar_avaliacao_docs_obr)
                                            echo '<td>' . $validado . '</td>
                                    <td>' . $linha['justificativa'] . '</td>';
                                        echo '<td><a onclick="funcao_apagar(\'' . $linha['id_documentacao_obrigatoria'] . '\', \'documentos_obrigatorios\',\'' . $crip . '\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <a name="fim_pagina"></a>
                    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>