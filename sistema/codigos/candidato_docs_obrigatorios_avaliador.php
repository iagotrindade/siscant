<a name="arquivos_obrigatorios"></a>
<?php

if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos') {
    erro("Erro 34546");
    exit();
}


?>

<a name="avaliacao_doc_obrigatorio"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="row">
    <div class="">
        <div class="card">
            <div class="">
                <legend>Arquivos Obrigatórios Adicionados</legend>
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Nome do arquivo</th>
                                <th>Válido</th>
                                <th>Inválido</th>
                                <th>Justificativa</th>
                                <th style="width: 100px">Avaliado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($id_usuario);

                            foreach ($lista_docs_obrigatorios as $linha) {

                                $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id']);

                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";

                                $get_foto = $conexao->get_foto_usuario($linha['usuario_avaliou']);
                                if (count($get_foto) > 0) {
                                    $foto = $get_foto[0]['nome'];
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                                }

                                $validado = null;

                                if ($linha['valido'] == '0')
                                    $validado = "<img  src='imagens/no_like.jpg' width='40px'>" . $foto;
                                else if ($linha['valido'] == '1')
                                    $validado = "<img  src='imagens/like.jpg' width='40px'>" . $foto;

                                echo '
                        <tr>
                            <td><a href="baixaPDF.php?codigo=can_doc_obr_aval&nome_arquivo=' . $linha['nome'] . '" target="_blank">' . $linha['label'] . '</a></td>
                            <td>
                                <form action="../banco_dados/valida_doc_obrigatorio.php" method="post">
                                    <input hidden type="text" value="1" name="valido">
                                    <input hidden type="text" value="' . $crip . '" name="criptografia">
                                    <input hidden type="text" value="' . $linha['id'] . '" name="id_documento_obrigatorio">
                                    <input hidden type="text" value="' . $linha['id_candidato'] . '" name="id_candidato">
                                    <input hidden type="text" value="" name="justificativa">
                                    <input type="image" src="imagens/ok.png" border="0" width="30px" alt="Submit" />
                                </form>
                            </td>

                            <form action="../banco_dados/valida_doc_obrigatorio.php" method="post">
                                <td>
                                        <input hidden type="text" value="0" name="valido">
                                        <input hidden type="text" value="' . $crip . '" name="criptografia">
                                        <input hidden type="text" value="' . $linha['id'] . '" name="id_documento_obrigatorio">
                                        <input hidden type="text" value="' . $linha['id_candidato'] . '" name="id_candidato">
                                        <input type="image" src="imagens/apagar.png" border="0" width="30px" alt="Submit" />
                                </td>
                                <td>
                                    <textarea style="width:100%;" name="justificativa">' . $linha['justificativa'] . '</textarea>
                                </td>
                            </form>
                            
                            <td>' . $validado . '</td>
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