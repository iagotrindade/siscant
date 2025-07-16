<a name="observacoes"></a>
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

<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="row">
    <div class="">
        <div class="card">
            <div class="">
                <legend>Observações do candidato</legend>
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="../banco_dados/candidato_observacao_cadastra.php">
                            <input type="text" value="<?php echo $id_usuario ?>" name="id_usuario" hidden>
                            <input type="text" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']) ?>" name="criptografia" hidden>
                            <label>Escreva uma observação:</label>
                            <br>
                            <textarea style="width:100%;" rows="6" name="observacao"></textarea>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">CADASTRAR</button>
                        </form>
                    </div>
                    <div class="col-md-6">

                        <div class="alert alert-">

                            <?php

                            foreach ($lista_observacaoes as $linha) {

                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                $get_foto = $conexao->get_foto_usuario($linha['_usuario_ultima_atualizacao']);
                                if (count($get_foto) > 0) {
                                    $foto = $get_foto[0]['nome'];
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                                }

                                $usuario_cadastrou_obs = 'Obs criada pelo sistema';

                                if ($linha['sistema'] == 0) {
                                    $usuario_cadastrou_obs = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
                                    $usuario_cadastrou_obs = $usuario_cadastrou_obs[0]['posto_grad'] . ' ' . $usuario_cadastrou_obs[0]['nome_guerra'];
                                }

                                $alerta = "info";
                                if ($linha['sistema'] == 1)
                                    $alerta = "laranja";

                                echo '
                <div class="alert alert-' . $alerta . '">
                    <div class="row">
                        <div class="col-md-10">
                        <b>Observação: </b><font color="#111">' . $linha['observacao'] .
                                    '</font><br>
                         <i>Cadastrado em ' . trata_data_hora($linha['_data_ultima_atualizacao']) . ' - <a href="usuario_visualiza.php?id_usuario=' . $linha['_usuario_ultima_atualizacao'] . '"> ' . $usuario_cadastrou_obs . '</a> ' . $foto . '</i>
                        </div>
                        ';
                                if ($linha['sistema'] == 0)
                                    echo '
                        <div class="col-md-2">
                            <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'candidato_observacao\',\'' . $id_usuario . '\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>
                        </div>';
                                echo ' 
                    </div>
                </div>';
                            }

                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>