<a name="especialidades"></a>
<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador') {
    erro("Erro 23455345!");
    exit();
}

$avaliador = false;
if ($_SESSION['perfil'] == "avaliador") {
    $avaliador = true;
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
}

?>


<div class="card" <?php if (isset($_SESSION['eipot']) == 1) echo " hidden "; ?>>
    <a name="calcular_data"></a>
    <div class="row" <?php if ($_SESSION['selecao_regiao'] != 3) echo ' hidden ' ?>>
        <div class="col-md-12">
            <legend>Calculadora rápida de meses </legend>
        </div>

        <form action="<?php echo 'usuario_visualiza.php?=' . $id_usuario . '#calcular_data' ?>" method="GET">
            <div class="col-md-4">
                <label>Data de início</label>
                <input id="data_inicio_calcular" name="data_inicio_calcular" onblur="calcula_data()" maxlength="120" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Data de Fim</label>
                <input id="data_fim_calcular" name="data_fim_calcular" onblur="calcula_data()" maxlength="120" class="form-control">
            </div>
            <div class="col-md-4">
                <br>
                <p id='diferenca_datas'><b>
                        <legend>Diferença em Meses: </legend>
                    </b></p>
            </div>
        </form>
    </div>
    <a name="calcula_data_dias"></a>
    <div class="row" <?php if ($_SESSION['selecao_regiao'] == 3) echo ' hidden ' ?>>
        <div class="col-md-12">
            <legend>Calculadora rápida de Dias </legend>
        </div>

        <form action="<?php echo 'usuario_visualiza.php?=' . $id_usuario . '#calcula_data_dias' ?>" method="GET">
            <div class="col-md-4">
                <label>Data de início</label>
                <input id="data_inicio_calcular_dias" name="data_inicio_calcular_dias" onblur="calcula_data_dias()" maxlength="120" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Data de Fim</label>
                <input id="data_fim_calcular_dias" name="data_fim_calcular_dias" onblur="calcula_data_dias()" maxlength="120" class="form-control">
            </div>
            <div class="col-md-4">
                <br>
                <p id='diferenca_datas_dias'><b>
                        <legend>Diferença em dias: </legend>
                    </b></p>
            </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-md-12 ">

        <?php

        $inscricoes = $conexao->get_especialidade_candidato($id_usuario);


        foreach ($inscricoes as $valor) {

            //echo "Especialidade: " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " " .$valor['especialidade'] . "<br>";
            $id_candidato_x_especialidade = null;
            $resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario, $valor['id_especialidade']);


            if (count($resultado_verificacao) > 0) {
                $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
                $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                $cpf_candidato      = $resultado_verificacao[0]['cpf'];
                $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
                $ott_stt            = $resultado_verificacao[0]['ott_stt'];
                $concorrendo_especialidade          = $resultado_verificacao[0]['concorrendo'];
                $justificativa_especialidade        = $resultado_verificacao[0]['justificativa'];
                $id_usuario_alterou_concorrendo_especialidade     = $resultado_verificacao[0]['id_usuario_alterou_concorrendo'];


                $nota_prova_teorico_pratico     = (float)$resultado_verificacao[0]['nota_prova_teorico_pratico'];
                $prova_pratica_musica           = (float)$resultado_verificacao[0]['prova_pratica_musica'];
                $prova_teorica_musica           = (float)$resultado_verificacao[0]['prova_teorica_musica'];
                $prova_oral_musica              = (float)$resultado_verificacao[0]['prova_oral_musica'];
                $usuario_avaliou_provas_musica  = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
            }


            if ($id_candidato_x_especialidade != null)
                ////////////// CIDADES CADASTRADAS
                $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

            $cor_fundo = "info";
            $texto_nao_concorrendo_especialidade = "";
            if (!$concorrendo_especialidade) {
                $cor_fundo = "laranja";
                $texto_nao_concorrendo_especialidade = "<font color = 'red'>NÃO CONCORRENDO</font>";
            }

            echo '<a name=avaliacao_id_' . $id_especialidade . '></a>';

            if ($avaliador) {
                $avaliador_pode_avaliar_id_especialidade = false;
                $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

                foreach ($lista_especialidade_avaliador as $linha_avaliador) {
                    if ($linha_avaliador['id_especialidade'] == $id_especialidade)
                        $avaliador_pode_avaliar_id_especialidade = true;
                }
            }

        ?>

            <div class="card" <?php if (isset($avaliador_pode_avaliar_id_especialidade) && !$avaliador_pode_avaliar_id_especialidade) echo "hidden" ?>>
                <legend <?php if (isset($_SESSION['eipot']) == 1) echo " hidden "; ?>>Especialidade: <?php echo " <u><a href='relatorio_especialidade_candidato.php?id_especialidade=" . $valor['id_especialidade'] . "'>" . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] . " - " . $valor['data_habilitacao'] . "</a></u> $texto_nao_concorrendo_especialidade<br>"; ?></u></legend>
                <div class="card-body ">

                    <?php
                    if ($selecao_libera_prioridade_candidato == '1') {
                        echo '<label> Prioridades de cidades selecionadas para essa especialidade:</label>';

                        foreach ($lista_cidades as $linha) {
                            echo " " . $linha['prioridade'] . "ª " . " " . $linha['nome'] . " | ";
                        }
                        echo "<br><br>";
                    }
                    ?>


                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Nome do arquivo</th>
                                <th>Data Início</th>
                                <th>Data Fim</th>
                                <th>Resumo do PDF</th>
                                <th>Multiplicar</th>
                                <th>Justificativa</th>
                                <th>Avaliar Doc</th>
                                <th>Pts</th>
                                <th>Avaliação</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            if ($id_especialidade != null) {
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $id_especialidade);
                                $pontuacao_total = 0;

                                foreach ($lista_curriculo_adicionado as $linha) {
                                    $validado = null;
                                    $pontuacao = null;

                                    if ($linha['pontuacao'] != null && $linha['pontuacao'] > 0) {
                                        if ($linha['valido'] == '1') {
                                            $pontuacao = $linha['pontuacao'] / 1000;

                                            $multi = (int)$linha['multiplicador'];
                                            if ($multi > 1)
                                                $pontuacao = $pontuacao * $multi;

                                            $pontuacao_total = $pontuacao_total + $pontuacao;
                                        }
                                    }

                                    $usuario_avaliou = $conexao->get_usuario_id($linha['usuario_avaliou']);
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                    if (count($usuario_avaliou) > 0) {
                                        $get_foto = $conexao->get_foto_usuario($usuario_avaliou[0]['id']);
                                        if (count($get_foto) > 0) {
                                            $foto = $get_foto[0]['nome'];
                                            $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                                        }
                                    }

                                    if ($linha['valido'] == '0')
                                        $validado = "<img  src='imagens/no_like.jpg' width='40px'>" . $foto;
                                    if ($linha['valido'] == '1')
                                        $validado = "<img  src='imagens/like.jpg' width='40px'>" . $foto;


                                    $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_especialidade_curriculo']);

                                    $dt_inicio = null;
                                    if ($linha['data_inicio'] != null)
                                        $dt_inicio = trata_data($linha['data_inicio']);

                                    $dt_fim = null;
                                    if ($linha['data_termino'] != null)
                                        $dt_fim = trata_data($linha['data_termino']);

                                    $justificativa = $linha['justificativa'];

                                    echo '
                                    <tr>
                                        <td style="width:30%;"><a href="baixaPDF.php?codigo=cand_esp_aval&nome_arquivo=' . $linha['nome'] . '" target="_blank">' . $linha['nome_curriculo'] . '</a></td>
                                        <td>' . $dt_inicio . '</td>
                                        <td>' . $dt_fim . '</td>
                                        <td>' . $linha['carga_horaria'] . '</td>

                                        <form action="../banco_dados/valida_curriculo.php" method="post">
                                        <td>
                                            <select class="form-control" name="multiplicador" ';
                                    if ($linha['multiplicacao'] != '1') echo ' disabled  >';
                                    for ($i = 0; $i <= $linha['quantidade_multiplicacao']; $i++) {
                                        echo '<option';
                                        if ($linha['multiplicador'] == $i) echo " selected ";
                                        echo ' value="' . $i . '"> ' . $i . ' </option>';
                                    }
                                    echo '
                                            </select>
                                        </td>
                                        <td><textarea style="width:100%;" name="justificativa">' . $justificativa . '</textarea></td>
                                        <td>
                                                <input hidden type="text" value="' . $id_usuario . '" name="id_usuario">
                                                <input hidden type="text" value="' . $id_especialidade . '" name="id_especialidade">
                                                <input hidden type="text" value="' . $crip . '" name="criptografia">
                                                <input hidden type="text" value="' . $linha['id_especialidade_curriculo'] . '" name="id_especialidade_curriculo">
                                                <input hidden type="text" value="' . $linha['id_curriculo'] . '" name="id_curriculo">

                                                <select name="valido" class="form-control" onchange="submit()" >
                                                  <option selected value=""></option> 
                                                  <option value="1">VÁLIDO</option> 
                                                  <option value="0">INVÁLIDO</option>
                                                </select>

                                        </td>
                                            
                                        </form>

                                        <td>' . $pontuacao . '</td>
                                        <td>' . $validado . '</td>
                                    </tr>';
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>



                <?php

                $somatorio_total_pontos_musica = (((($prova_teorica_musica * 2) + ($prova_pratica_musica * 2) + $prova_oral_musica) / 5) + $pontuacao_total) / 2;



                //$prova_pratica_musica = number_format($prova_pratica_musica, 2);

                if ($prova_pratica_musica < 10)
                    $prova_pratica_musica = "0" . $prova_pratica_musica . "00";
                else
                    $prova_pratica_musica = number_format($prova_pratica_musica, 2);


                if ($prova_oral_musica < 10)
                    $prova_oral_musica = "0" . $prova_oral_musica . "00";
                else
                    $prova_oral_musica = number_format($prova_oral_musica, 2);


                if ($prova_teorica_musica < 10)
                    $prova_teorica_musica = "0" . $prova_teorica_musica . "00";
                else
                    $prova_teorica_musica = number_format($prova_teorica_musica, 2);


                //if($valor['musica'] == '1' && count($lista_curriculo_adicionado) > 0) Verifica se tem pelo menos um currículo
                if ($valor['musica'] == '1') {
                    $foto_html = null;
                    if ($usuario_avaliou_provas_musica > 0) {
                        $foto_avaliador_musica = $conexao->get_foto_usuario($usuario_avaliou_provas_musica);

                        if (count($foto_avaliador_musica) > 0)
                            $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $usuario_avaliou_provas_musica . "'><img class='img-circle' src='fotos/" . $foto_avaliador_musica[0]['nome'] . "' width='40px'></a>";
                        else
                            $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $usuario_avaliou_provas_musica . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                    }
                    $crip_musica = hash('sha256', $_SESSION['chave'] . "freitas");



                    echo '
                    <div class="alert alert-info">

                        <form action="../banco_dados/prova_musica_salvar.php" method="post">
                        <input hidden type="text" value="' . $id_usuario . '" name="id_usuario">
                        <input hidden type="text" value="' . $id_especialidade . '" name="id_especialidade">
                        <input hidden type="text" value="' . $crip_musica . '" name="criptografia">
                        <input hidden type="text" value="' . $linha['id_especialidade_curriculo'] . '" name="id_especialidade_curriculo">
                        <input hidden type="text" value="' . $id_candidato_x_especialidade . '" name="id_candidato_x_especialidade">

                        <div class="row">
                            <div class="col-lg-3">
                                <b>Pontuação da Prova Prática:</b><br>
                                <input type="text" value="' . $prova_pratica_musica . '" name="pontuacao_pratica">
                            </div>
                            <div class="col-lg-3">
                                <b>Pontuação da Prova Escrita:</b><br>
                                <input type="text"value="' . $prova_teorica_musica . '"  name="pontuacao_teorica">
                            </div>
                            <div class="col-lg-3">
                                <b>Pontuação da Prova Oral:</b><br>
                                <input type="text" value="' . $prova_oral_musica . '" name="pontuacao_oral">
                            </div>
                            <div class="col-lg-3">
                                 ' . $foto_html . ' 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary btn-block">SALVAR PONTUAÇÃO DAS PROVAS</button>
                            </div>
                        </div>
                        </form>
                    </div>';
                }

                ?>

                <a name='concorrendo_especialidade_id_<?php echo $id_especialidade ?>'></a>
                <table border='1' style="width: 100%">
                    <tbody>
                        <tr>
                            <td <?php if (isset($_SESSION['eipot']) == 1);
                                echo "hidden" ?>><b>SOMATÓRIO DOS PONTOS VÁLIDOS</b></td>
                            <td><b>
                                    <?php

                                    if ($valor['musica'])
                                        echo round($somatorio_total_pontos_musica, 2);
                                    else
                                        echo $pontuacao_total + $nota_prova_teorico_pratico
                                    ?> </b></td>
                        </tr>
                    </tbody>
                </table>

                <?php if ($valor['teste_pratico'] == 1) echo ' <br><br><br>   '; ?>

                <div class="alert alert-warning" <?php if ($valor['teste_pratico'] == 0) echo ' hidden '; ?>>
                    <div class="row">
                        <form method="post" action="../banco_dados/nota_teorico_pratica.php" enctype="multipart/form-data">

                            <?php
                            $id_cand_esp = $conexao->get_id_candidato_x_especialidade($id_usuario, $id_especialidade);
                            echo ' 
                            <input hidden type="text" value="' . $id_usuario . '" name="id_usuario">
                            <input hidden type="text" value="' . $crip . '" name="criptografia">
                            <input hidden type="text" value="' . $id_especialidade . '" name="id_especialidade">
                            <input hidden type="text" value="' . $linha['id_especialidade_curriculo'] . '" name="id_especialidade_curriculo">
                            <input hidden type="text" value="' . $id_cand_esp[0]['id'] . '" name="id_candidato_x_especialidade">
                        '; ?>
                            <div class="col-md-4">

                                <?php
                                if ($nota_prova_teorico_pratico < 10)
                                    $nota_prova_teorico_pratico = "0" . $nota_prova_teorico_pratico . "00";
                                else
                                    $nota_prova_teorico_pratico = number_format($nota_prova_teorico_pratico, 2);
                                ?>

                                <label>Adicionar pontuação do teste Teórico/Prático</label>
                                <input name="pontuacao_teorico_pratica" value="<?php echo $nota_prova_teorico_pratico ?>" class="form-control">
                            </div>
                            <div class="col-md-8">
                                <br>
                                <input type="submit" class="btn btn-primary btn-block" value="Adicionar Nota" />
                            </div>
                        </form>
                    </div>
                </div>



                <div class="row"
                    <?php
                    //if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador') echo " hidden "; 
                    //if($_SESSION['perfil'] != 'admin') echo " hidden "; 
                    if ($_SESSION['selecao_regiao'] != 6) echo " hidden ";
                    //echo " hidden ";
                    ?>>

                    <br>
                    <br>
                    <div class="col-md-12">
                        <div <?php // echo " hidden " 
                                ?> class="alert alert-info">
                            <legend>Adicionar currículo para o candidato na especialidade<u><?php echo mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] ?></u></legend>

                            <form method="post" action="arquivo_upload_operador_candidato.php" enctype="multipart/form-data">
                                <input hidden name="user" value="<?php echo $id_especialidade ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                <font color="red"> *Máximo 5 MegaBytes no formato PDF</font>
                                            </label>

                                            <select name="id_curriculo" class="form-control">
                                                <option value="">Selecione o arquivo a ser adicionado</option>
                                                <?php
                                                $lista_curriculos = $conexao->get_curriculo_cadastrados();
                                                foreach ($lista_curriculos as $linha) {
                                                    //if($linha['id'] == 42)
                                                    echo '<option value="' . $linha['id'] . '">' . $linha['nome'] . ' </option>';

                                                    /*
                                                if($linha['id'] == 20)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 32)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 33)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 34)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 35)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 21 && $valor['ott_stt'] == "stt")
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                
                                                if($linha['id'] == 36 && $id_especialidade == 7)
                                                    echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                                 */
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data de Início</label>
                                            <input id="nome" name="data_inicio" maxlength="120" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data de Finalização</label>
                                            <input id="nome" name="data_fim" maxlength="120" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Carga Horária</label>
                                            <input id="carga_horaria" name="carga_horaria" maxlength="120" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <br> <input type="file" name="arquivo" />
                                        </div>
                                    </div>

                                    <input hidden type="text" name="id_candidato" value="<?php echo $id_usuario ?>">
                                    <input hidden type="text" name="crip" value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">

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

                <div <?php if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "avaliador") echo " hidden " ?> class="alert alert-info">
                    <legend>Concorrendo/Não concorrendo na especialidade <?php echo mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] ?></legend>
                    <div class="row">
                        <div class="col-md-6">

                            <form action="../banco_dados/candidato_concorrendo_especialidade.php" method="post">
                                <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas"); ?>">
                                <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
                                <input hidden name="id_candidato_x_especialidade" value="<?php echo $id_candidato_x_especialidade ?>">
                                <input hidden name="id_especialidade" value="<?php echo $id_especialidade ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="animated-checkbox form-group">
                                            <label>
                                                <input <?php if ($concorrendo_especialidade) echo "checked" ?> type="checkbox" name="concorrendo">
                                                <span class="label-text">Candidato concorrendo no especialidade</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <label> Justificativa da mudança </label><br>
                                        <textarea name="justificativa" style="width: 100%"></textarea>
                                    </div>
                                </div>
                                <br>
                                <div <?php if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "avaliador") echo "hidden" ?> class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary btn-block">SALVAR</button>
                                    </div>
                                </div>
                            </form>

                        </div>



                        <div <?php if ($justificativa_especialidade == null) echo "hidden" ?>>

                            <?php

                            $get_foto = $conexao->get_foto_usuario($id_usuario_alterou_concorrendo_especialidade);
                            if ($id_usuario_alterou_concorrendo_especialidade != null)
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo_especialidade . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                            if (count($get_foto) > 0)
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo_especialidade . "'><img class='img-circle' src='fotos/" . $get_foto[0]['nome'] . "' width='40px'></a>";

                            ?>

                            <div class="col-md-6">
                                <div <?php if (!$concorrendo_especialidade) echo "hidden" ?> class="alert alert-success">
                                    <b>CONCORRENDO nesta especialidade!
                                        <br><br>
                                        Justificativa:</b> <?php echo $justificativa_especialidade . " - " . $foto ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div <?php if ($concorrendo_especialidade) echo "hidden" ?> class="alert alert-danger">
                                    <b>ELIMINADO desta especialidade!
                                        <br><br>
                                        Justificativa:</b> <?php echo $justificativa_especialidade . " - " . $foto ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="col-md-12" <?php if ($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
                                <form action="../banco_dados/candidato_especialidade_etapa_atualiza.php" method="post">
                                    <input hidden name="criptografia" value="<?php echo  hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas"); ?>">
                                    <input hidden name="id_usuario" value="<?php echo $id_usuario ?>">
                                    <input hidden name="id_especialidade" value="<?php echo $valor['id_candidato_x_especialidade'] ?>">
                                    <input hidden name="etapa_atual" value="<?php echo $valor['etapa'] ?>">
                                    <input hidden name="nome_especialidade" value="<?php echo $valor['especialidade'] ?>">
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="animated-checkbox form-group">
                                                <label>Selecione a ETAPA do candidato na especialidade</label>
                                                <select name="etapa_especialidade" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option <?php if ($valor['etapa'] == 1) echo 'selected' ?> value="1">Etapa I</option>
                                                    <option <?php if ($valor['etapa'] == 2) echo 'selected' ?> value="2">Etapa II</option>
                                                    <option <?php if ($valor['etapa'] == 3) echo 'selected' ?> value="3">Etapa III</option>
                                                    <option <?php if ($valor['etapa'] == 4) echo 'selected' ?> value="4">Etapa IV</option>
                                                    <option <?php if ($valor['etapa'] == 5) echo 'selected' ?> value="5">Etapa V</option>
                                                    <option <?php if ($valor['etapa'] == 6) echo 'selected' ?> value="6">Etapa VI</option>
                                                    <option <?php if ($valor['etapa'] == 7) echo 'selected' ?> value="7">Etapa VII</option>
                                                    <option <?php if ($valor['etapa'] == 8) echo "selected" ?> value="8">Etapa VIII</option>
                                                    <option <?php if ($valor['etapa'] == 9) echo "selected" ?> value="9">Etapa IX</option>
                                                    <option <?php if ($valor['etapa'] == 10) echo "selected" ?>value="10">Etapa X</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary btn-block">SALVAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6"
                            <?php
                            //if($etapa < 4 || !$concorrendo_especialidade) 
                            echo " hidden ";
                            ?>>
                            <form action="../banco_dados/candidato_altera_cidade_vai_servir.php" method="post">
                                <div class="form-group">
                                    <label>Cidade onde o candidato escolheu servir</label>
                                    <select name="cidade_candidato" class="form-control">

                                        <option value="">CIDADE NÃO ESCOLHIDA</option>
                                        <?php
                                        $lista_cidades = $conexao->get_cidades_especialidade($valor['id_especialidade']);
                                        foreach ($lista_cidades as $linha_cidade) {
                                            if ($valor['cidade_escolheu_servir'] == $linha_cidade['id'])
                                                echo "<option selected value='" . $linha_cidade['id'] . "'>" . $linha_cidade['nome'] . "</option>";
                                            else
                                                echo "<option value='" . $linha_cidade['id'] . "'>" . $linha_cidade['nome'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <input name="id_especialidade_servir" hidden value='<?php echo $valor['id_especialidade']; ?>'>
                                    <input name="c_p_f_candidato_servir" hidden value='<?php echo $cpf; ?>'>
                                    <input name="id_candidato_servir" hidden value='<?php echo $id_usuario; ?>'>
                                    <button type="submit" class="btn btn-primary btn-block">SALVAR</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>

            </div>



        <?php

        }

        ?>




    </div>
</div>