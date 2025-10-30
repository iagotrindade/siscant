<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

$lista_observacaoes = $conexao->get_observacoes_candidato($id_usuario);
include_once '../sistema/codigos/funcao_apagar.php';
?>
<a name="insere_arquivo_candidato"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->

<div class="card">
    <div class="">
        <form method="post" action="arquivo_upload_adicionado_para_candidato.php" enctype="multipart/form-data">
            <legend>Arquivos adicionados para o Candidato</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Adicione um arquivo para o candidato</label>
                        <font color="red"> <b>*Máximo 5 MegaBytes</b></font><br>
                    </div>
                    <input type="text" hidden name="criptografia" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                    <input type="text" hidden name="cpf_candidato" value="<?php echo $cpf ?>">
                    <input type="text" hidden name="id_candidato" value="<?php echo $id_usuario ?>">
                    <div class="col-md-6">
                        <div class="form-group">
                            <br> <input type="file" name="arquivo" />
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Arquivos adicionados</label>
                    <br>
                    <br>
                    <?php

                    $lista_arquivos_candidato = $conexao->get_arquivos_candidato($id_usuario);

                    foreach ($lista_arquivos_candidato as $arquivo) {
                        $crip = hash('sha256', "freitas" . $arquivo['id']);
                        $imagem = 'imagem.png';

                        if ($arquivo['extensao'] == 'pdf') $imagem = 'pdf.png';
                        if ($arquivo['extensao'] == 'odt') $imagem = 'odt.jpg';
                        if ($arquivo['extensao'] == 'doc' || $arquivo['extensao'] == 'docx') $imagem = 'word.png';
                        if ($arquivo['extensao'] == 'xls' || $arquivo['extensao'] == 'xlsx' || $arquivo['extensao'] == 'ods') $imagem = 'ods.png';


                        echo ' <a href="arquivos_add_p_cand/' . $arquivo['nome'] . '" target="_blank">' . $arquivo['label'] . '
                                <img src="imagens/' . $imagem . '" height="55px">
                                </a>';
                        echo ' <a onclick="funcao_apagar(\'' . $arquivo['id'] . '\', \'arquivo\',\'' . $crip . '\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>';
                        echo ' <br>';
                    }
                    ?>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input name="label" maxlength="200" placeholder="ESCREVA QUE ARQUIVO É ESTE" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-block" value="Enviar arquivo" />
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>