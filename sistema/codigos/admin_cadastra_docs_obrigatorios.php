<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($_SESSION['id_usuario']);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($id_usuario);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);
$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_usuario);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($id_usuario);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);

?>
<a name="admin_cadastra_especialidade"></a>
<div class="card" <?php if ($_SESSION['perfil'] != 'admin') echo ' hidden ' ?>>

    <legend>Enviar documentos obrigatórios do candidato</legend>
    <div class="row">
        <div class="col-lg-12" <?php if ($quantidade_docs_faltantes == 0) echo "hidden" ?>>
            <form method="post" action="arquivo_upload_doc_obrigatorio.php" enctype="multipart/form-data">
                <input hidden name="id_candidato" value="<?php echo $id_usuario ?>">
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
    </div>
</div>