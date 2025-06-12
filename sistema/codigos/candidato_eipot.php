<a name='eipot'></a>
<div class="" <?php if ($_SESSION['selecao_codigo'] != 'eipot') echo ""; ?>>
    <div class="">
        <div class="col-md-12">
            <form action="../banco_dados/candidato_notas_eipot.php" method="post">
                <?php
                $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $id_usuario);

                $qtd_flexao_braco = $usuario_visualiza[0]['qtd_flexao_braco'];
                $qtd_abdominal = $usuario_visualiza[0]['qtd_abdominal'];
                $qtd_barra = $usuario_visualiza[0]['qtd_barra'];
                $dist_corrida = $usuario_visualiza[0]['dist_corrida'];
                $ano_formacao_ofor_avaliador = $usuario_visualiza[0]['ano_formacao_ofor_avaliador'];
                $nota_ofor_avaliador = $usuario_visualiza[0]['nota_ofor_avaliador'];

                $eipot_usuario_avaliou = $usuario_visualiza[0]['eipot_usuario_avaliou'];
                $eipot_data_avaliacao = $usuario_visualiza[0]['eipot_data_avaliacao'];

                // ESPECIALIDADE / ARMA / EIPOT 
                $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                foreach ($inscricoes as $valor) {
                    $id_candidato_x_especialidade = null;
                    $resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario, $valor['id_especialidade']);

                    if (count($resultado_verificacao) > 0) {
                        $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                        $id_especialidade = $resultado_verificacao[0]['id_especialidade'];
                        $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                        $cpf_candidato = $resultado_verificacao[0]['cpf'];
                        $nome_candidato = $resultado_verificacao[0]['nome_completo'];
                        $ott_stt = $resultado_verificacao[0]['ott_stt'];
                        $concorrendo_especialidade = $resultado_verificacao[0]['concorrendo'];
                        $justificativa_especialidade = $resultado_verificacao[0]['justificativa'];
                        $id_usuario_alterou_concorrendo_especialidade = $resultado_verificacao[0]['id_usuario_alterou_concorrendo'];

                        $nota_prova_teorico_pratico = (float)$resultado_verificacao[0]['nota_prova_teorico_pratico'];
                        $prova_pratica_musica = (float)$resultado_verificacao[0]['prova_pratica_musica'];
                        $prova_teorica_musica = (float)$resultado_verificacao[0]['prova_teorica_musica'];
                        $prova_oral_musica = (float)$resultado_verificacao[0]['prova_oral_musica'];
                        $usuario_avaliou_provas_musica = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
                    }

                    if ($id_candidato_x_especialidade != null) {
                        $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);
                    }

                    $cor_fundo = "info";
                    $texto_nao_concorrendo_especialidade = "";
                    if (!$concorrendo_especialidade) {
                        $cor_fundo = "laranja";
                        $texto_nao_concorrendo_especialidade = "<font color='red'>NÃO CONCORRENDO</font>";
                    }

                    echo '<a name="avaliacao_id_' . $id_especialidade . '"></a>';

                    if (isset($avaliador)) {
                        $avaliador_pode_avaliar_id_especialidade = false;
                        $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

                        foreach ($lista_especialidade_avaliador as $linha_avaliador) {
                            if ($linha_avaliador['id_especialidade'] == $id_especialidade) {
                                $avaliador_pode_avaliar_id_especialidade = true;
                            }
                        }
                    }
                ?>
                    <div class="row">
                        <div class="col-md-12"></div>
                        <div class="card" <?php if (isset($avaliador_pode_avaliar_id_especialidade) && !$avaliador_pode_avaliar_id_especialidade) echo "style='display:none;'"; ?>>
                            <legend <?php if ($_SESSION['eipot'] == 1 || $_SESSION['codigo_selecao'] == "eipot") echo " hidden " ?>>Especialidade: <?php echo "<u><a href='relatorio_especialidade_candidato.php?id_especialidade=" . $valor['id_especialidade'] . "'>" . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] . " - " . $valor['data_habilitacao'] . "</a></u> $texto_nao_concorrendo_especialidade<br>"; ?></legend>
                            <div class="card-body">
                                <?php
                                if ($selecao_libera_prioridade_candidato == '1') {
                                    echo '<label>Prioridades de cidades selecionadas para essa especialidade:</label>';
                                    foreach ($lista_cidades as $linha) {
                                        echo " " . $linha['prioridade'] . "ª " . " " . $linha['nome'] . " | ";
                                    }
                                    echo "<br><br>";
                                }
                                ?>
                                <table <?php if ($_SESSION['eipot'] == 1 || $_SESSION['codigo_selecao'] == "eipot") echo " hidden " ?> class="table table-hover table-bordered">
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
                                                    if ($multi > 1) {
                                                        $pontuacao = $pontuacao * $multi;
                                                    }
                                                    $pontuacao_total += $pontuacao;
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

                                            if ($linha['valido'] == '0') {
                                                $validado = "<img src='imagens/no_like.jpg' width='40px'>" . $foto;
                                            }
                                            if ($linha['valido'] == '1') {
                                                $validado = "<img src='imagens/like.jpg' width='40px'>" . $foto;
                                            }

                                            $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_especialidade_curriculo']);
                                            $dt_inicio = $linha['data_inicio'] != null ? trata_data($linha['data_inicio']) : null;
                                            $dt_fim = $linha['data_termino'] != null ? trata_data($linha['data_termino']) : null;
                                            $justificativa = $linha['justificativa'];

                                            echo '
                                                <tr>
                                                    <td style="width:30%;"><a href="baixaPDF.php?codigo=cand_esp_aval&nome_arquivo=' . $linha['nome'] . '" target="_blank">' . $linha['nome_curriculo'] . '</a></td>
                                                    <td>' . $dt_inicio . '</td>
                                                    <td>' . $dt_fim . '</td>
                                                    <td>' . $linha['carga_horaria'] . '</td>
                                                    <form action="../banco_dados/valida_curriculo.php" method="post">
                                                        <td>
                                                            <select name="multiplicador" class="form-control" onchange="submit()">
                                                                <option value="1">1</option>';
                                            if ($linha['multiplicacao'] != '1') echo 'disabled>';
                                            for ($i = 0; $i <= $linha['quantidade_multiplicacao']; $i++) {
                                                echo '<option';
                                                if ($linha['multiplicador'] == $i) echo " selected";
                                                echo ' value="' . $i . '">' . $i . '</option>';
                                            }
                                            echo '</select>
                                                        </td>
                                                        <td><textarea style="width:100%;" name="justificativa">' . $justificativa . '</textarea></td>
                                                        <td>
                                                            <input hidden type="text" value="' . $id_usuario . '" name="id_usuario">
                                                            <input hidden type="text" value="' . $id_especialidade . '" name="id_especialidade">
                                                            <input hidden type="text" value="' . $crip . '" name="criptografia">
                                                            <input hidden type="text" value="' . $linha['id_especialidade_curriculo'] . '" name="id_especialidade_curriculo">
                                                            <input hidden type="text" value="' . $linha['id_curriculo'] . '" name="id_curriculo">
                                                            <select name="valido" class="form-control" onchange="submit()">
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
                                }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                            <input hidden type="text" value="<?php echo $id_usuario; ?>" name="id_usuario_eipot">
                            <input hidden type="text" value="<?php echo $crip; ?>" name="criptografia_eipot">
                            <input value="<?php echo $cpf; ?>" maxlength="50" name="c_p_f_candidato" hidden>

                            <div class="">
                                <legend>Avaliação EIPOT</legend>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Flexão de braço</label>
                                            <select name="qtd_flexao_braco" class="form-control">
                                                <option value="">Selecione a quantidade</option>
                                                <?php for ($i = 1; $i <= 50; $i++) {
                                                    echo "<option value='$i'" . ($qtd_flexao_braco == $i ? " selected" : "") . ">$i</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Abdominal Supra</label>
                                            <select name="qtd_abdominal" class="form-control">
                                                <option value="">Selecione a quantidade</option>
                                                <?php for ($i = 1; $i <= 80; $i++) {
                                                    echo "<option value='$i'" . ($qtd_abdominal == $i ? " selected" : "") . ">$i</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Flexão na Barra Fixa</label>
                                            <select name="qtd_barra" class="form-control">
                                                <option value="">Selecione a quantidade</option>
                                                <?php for ($i = 1; $i <= 20; $i++) {
                                                    echo "<option value='$i'" . ($qtd_barra == $i ? " selected" : "") . ">$i</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Corrida 12 min</label>
                                            <select name="dist_corrida" class="form-control">
                                                <option value="">Selecione a distância</option>
                                                <option value="0" <?php if ($dist_corrida == 0)  echo ("selected"); ?>>0 - 1799</option>
                                                <?php
                                                $b = 1800;

                                                for ($i = 1801; $i <= 3200; $i += 200) {
                                                    $b = $b += 200;
                                                    echo "<option value='$i'" . ($dist_corrida == $i ? " selected" : "") . ">$i - " . $b . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Nota final curso OFOR</label>
                                            <input value="<?php echo $nota_ofor_avaliador ?: ''; ?>" name="notafinal_ofor" class="form-control">
                                        </div>
                                    </div>

                                    <!--
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Ano da turma de formação OFOR</label>
                                            <select name="nota_ano_formacao" class="form-control">
                                                <option value="">Selecione a opção</option>
                                                <?php for ($i = date("Y"); $i >= date("Y") - 22; $i--) {
                                                    echo "<option value='$i'" . ($ano_formacao_ofor_avaliador == $i ? " selected" : "") . ">$i</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                            -->

                                    <?php
                                    // Nota final EIPOT
                                    $nota_final_eipot = get_nota_final_eipot($id_usuario);

                                    // Foto usuário avaliou EIPOT
                                    $foto_usuario_avaliou_eipot = null;
                                    $get_foto_usuario_avaliouEipot = $conexao->get_foto_usuario($eipot_usuario_avaliou);

                                    if (count($get_foto_usuario_avaliouEipot) > 0) {
                                        $foto_usuario_avaliou_eipot = $get_foto_usuario_avaliouEipot[0]['nome'];
                                        $foto_usuario_avaliou_eipot = "<a href='usuario_visualiza.php?id_usuario=" . $eipot_usuario_avaliou . "'><img class='img-circle' src='fotos/$foto_usuario_avaliou_eipot' width='40px'></a>";
                                        if ($eipot_data_avaliacao != null) $eipot_data_avaliacao = trata_data_hora($eipot_data_avaliacao);
                                    }
                                    ?>

                                    <div class="col-lg-3">
                                        <label><br>
                                            <font size="5px">Nota final: <?php echo $nota_final_eipot; ?></font>
                                        </label>
                                    </div>
                                    <div class="col-lg-3" <?php if ($foto_usuario_avaliou_eipot == null) echo "style='display:none;'"; ?>>
                                        <label><br>
                                            <font size="2px">Avaliado por: <?php echo $foto_usuario_avaliou_eipot; ?></font>
                                        </label>
                                        <label><br>
                                            <font size="2px">em: <?php echo $eipot_data_avaliacao; ?></font>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Salvar</button>
            </form>
        </div>

    </div>
</div>
</div>