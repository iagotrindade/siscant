<a name="recursos"></a>

<div class="row" <?php if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'jise') echo "hidden"; ?>>
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="alert alert-dismissible ">
                    <legend>Recursos do Candidato</legend>
                    <div class="row">
                        <div class="col-lg-12" <?php if ($_SESSION['perfil'] != 'admin' || $_SESSION['perfil'] != 'jise') echo ('hidden') ?>>
                            <form action="../banco_dados/candidato_cadastra_recurso.php" method="post" enctype="multipart/form-data">
                                <div class="col-lg-2">
                                    <div class="form-group"> <label>Etapa</label>
                                        <select name="etapa" class="form-control">
                                            <option value="">Selecione a etapa</option>
                                            <option value="1">Etapa I</option>
                                            <option value="2">Etapa II</option>
                                            <option value="3 - Documental">Etapa III - Documental</option>
                                            <option value="3 - IS">Etapa III - IS</option>
                                            <option value="4">Etapa IV</option>
                                            <option value="5">Etapa V</option>
                                            <option value="6">Etapa VI</option>
                                            <option value="7">Etapa VII</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group"> <label>Data de abertura</label>
                                        <input name="data_abertura" maxlength="25" class="form-control">
                                    </div>
                                </div>

                                <div <?php if (!isset($_SESSION['eipot']) != "1") echo "hidden" ?> class="col-lg-2">
                                    <label>Para Avaliador realizar análise?</label>
                                    <select name="avaliador" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>

                                <div <?php if (!isset($_SESSION['eipot']) != "1") echo "hidden" ?> class="col-lg-2">
                                    <label>Status </label>
                                    <select name="status" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option value="deferido">Deferido</option>
                                        <option value="deferido_parcialmente">Deferido Parcialmente</option>
                                        <option value="indeferido">Indeferido</option>
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <label>Especialidade</label>
                                    <select name="especialidade" class="form-control">
                                        <option value="">Selecione a especialidade</option>
                                        <?php

                                        $lista_especialidades = $conexao->get_especialidade_candidato($id_usuario);

                                        $somente_uma_especialidade = null;
                                        if (count($lista_especialidades) == 1)
                                            $somente_uma_especialidade = ' selected ';
                                        foreach ($lista_especialidades as $especialidade) {
                                            echo '<option ' . $somente_uma_especialidade . ' value="' . $especialidade['id_especialidade'] . '">' . $especialidade['especialidade'] . '</option>';
                                        }

                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Recurso</label>
                                                <font color="red"> <b>*Máximo 5 MegaBytes</b></font><br>
                                            </div>
                                            <input type="text" hidden name="criptografia" value="<?php echo hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                                            <input type="text" hidden name="cpf_candidato" value="<?php echo $cpf ?>">
                                            <input type="text" hidden name="id_candidato" value="<?php echo $id_usuario ?>">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="file" name="arquivo" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <br>
                                            <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>" name="crip">
                                            <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                                            <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden>
                                        </div>
                                    </div>

                                    <div <?php if (!isset($_SESSION['eipot']) != "1") echo "hidden" ?> class="col-lg-12">
                                        <label>Análise</label>
                                        <textarea style="width:100%;" rows="2" name="analise"></textarea>
                                    </div>

                                    <div class="col-lg-12">
                                        <br>
                                        <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>" name="crip">
                                        <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                                        <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden>
                                        <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                                    </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <br>
                        <?php

                        $lista_recursos = $conexao->get_recursos_candidato($id_usuario);
                        foreach ($lista_recursos as $linha) {
                            $crip = hash('sha256', $linha['id']);

                            $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                            $get_foto = $conexao->get_foto_usuario($linha['_usuario_ultima_atualizacao']);
                            if (count($get_foto) > 0) {
                                $foto = $get_foto[0]['nome'];
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                            }

                            $etapa = $linha['obs_etapa'] ?? $linha['etapa'];



                            $ultima_atualizacao = $linha['_data_ultima_atualizacao'];
                            if ($ultima_atualizacao != null) $ultima_atualizacao = trata_data_hora($ultima_atualizacao);

                            $usuario_ultima_at = null;
                            $usuario_ultima_atualizacao = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
                            if (count($usuario_ultima_atualizacao) == 1)
                                $usuario_ultima_at =  $usuario_ultima_atualizacao[0]['posto_grad'] . ' ' . $usuario_ultima_atualizacao[0]['nome_guerra'];

                            $usuario_realizou_analise = null;
                            $foto_analise = null;
                            $get_usuario_realizou_analise = $conexao->get_usuario_id($linha['id_usuario_analise']);

                            if (count($get_usuario_realizou_analise) == 1) {
                                $usuario_realizou_analise =  $get_usuario_realizou_analise[0]['posto_grad'] . ' ' . $get_usuario_realizou_analise[0]['nome_guerra'];

                                $foto_analise = "<a href='usuario_visualiza.php?id_usuario=" . $linha['id_usuario_analise'] . "'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                $get_foto = $conexao->get_foto_usuario($linha['id_usuario_analise']);
                                if (count($get_foto) > 0) {
                                    $foto_analise = $get_foto[0]['nome'];
                                    $foto_analise = "<a href='usuario_visualiza.php?id_usuario=" . $linha['id_usuario_analise'] . "'><img class='img-circle' src='fotos/$foto_analise' width='40px'></a>";
                                }
                            }

                            $data_de_abertura = null;
                            if ($linha['data_abertura'] != null)
                                $data_de_abertura  =  trata_data($linha['data_abertura']);

                            $para_avaliador = null;
                            if ($linha['para_avaliador'] != null && $linha['para_avaliador'] == '1')
                                $para_avaliador = 'Sim';
                            if ($linha['para_avaliador'] != null && $linha['para_avaliador'] == '0')
                                $para_avaliador = 'Não';

                            $status = null;
                            if ($linha['status_final'] != null && $linha['status_final'] == 'deferido') $status = 'Deferido';
                            if ($linha['status_final'] != null && $linha['status_final'] == 'deferido_parcialmente') $status = 'Deferido Parcialmente';
                            if ($linha['status_final'] != null && $linha['status_final'] == 'indeferido') $status = 'Indeferido';

                            $data_analise = null;
                            if ($linha['data_analise'] != null) $data_analise = trata_data_hora($linha['data_analise']);

                            // Arquivo que o candidato adicionou
                            $arquivo_add_candidato_recurso = null;

                            if ($_SESSION['perfil'] == 'jise' && $linha['obs_etapa'] != '3 - IS')
                                continue;

                            if ($linha['arq_nome_arquivo'] != null)
                                $arquivo_add_candidato_recurso = '<a href="baixaPDF.php?codigo=rec_cand_vis&nome_arquivo=' . $linha['arq_nome_arquivo'] . '" target="_blank">Recurso do Candidato -> <img src="imagens/pdf.png" height="70px"></a>';

                            if (!isset($_SESSION["eipot"])) {
                                $avaliador = '<b>Para avaliador analisar? </b><font color="#000">' . $para_avaliador . '</font><br>';
                            } else {
                                $avaliador = '';
                            }
                            echo '
<div class="alert alert-info">
    <div class="row">
    <div class="col-md-12">
    <legend > Recurso Nº ' . $linha['id'] . '</legend>
    <legend >' . $arquivo_add_candidato_recurso . '</legend>
</div>
        <div class="col-md-2">
            <b>Etapa: </b><font color="#000">' . $etapa . '</font><br>
        </div>
        <div class="col-md-2">
            <b>Data de abertura: </b><font color="#000">' . $data_de_abertura . '</font><br>
        </div>
        <div class="col-md-2">
           ' . $avaliador . '
        </div>
        <div class="col-md-2">
            <b>Status: </b><font color="#000">' . $status . '</font><br>
        </div>
        <div class="col-md-4">
            <b>Especialidade: </b><font color="#000">' . $linha['nome_especialidade'] . '</font><br>
        </div>
        <div class="col-md-12">
        <br>
            <b>Análise: </b><font color="#000">' . $linha['analise'] . '</font><br>
                <br>
        </div>
                                            

        <form action="../banco_dados/candidato_atualiza_oficio_recurso.php" method="post" >
        <input name="id_recurso" value=' . $linha['id'] . ' hidden>
        <input name="obs_etapa" type="text" value=' . $linha['obs_etapa'] . ' hidden>
        <input name="id_candidato" value=' . $linha['id_candidato'] . ' hidden>
        <input name="cpf_candidato" value=' . $cpf . ' hidden>

<div class="col-md-12">
<legend> Geração de Ofício Resposta</legend>
</div>';
                            if ($linha['arq_nome_arquivo'] != null && !isset($_SESSION["eipot"])) {
                                echo ' 
                        <div class="col-md-3">    
                            <label>Enviar para um especialista</label>
                            <select name="especialidade_recurso" class="form-control">
                                <option value="">Não enviar para especialista</option>
                            ';
                                foreach ($lista_especialidades as $especialidade) {
                                    if ($linha['id_especialidade'] == $especialidade['id_especialidade'])
                                        echo '<option selected value="' . $especialidade['id_especialidade'] . '">' . $especialidade['especialidade'] . '</option>';
                                    else
                                        echo '<option  value="' . $especialidade['id_especialidade'] . '">' . $especialidade['especialidade'] . '</option>';
                                }

                                echo '</select>
                             <br>
                        </div>';
                            }

                            echo '<div class="col-md-3">    
                <label>Status </label>
                    <select name="status_final" class="form-control">
                        <option value="">Selecione a opção</option>
                        <option ';
                            if ($linha['status_final'] == "deferido") echo " selected";
                            echo ' value="deferido">Deferido</option>
                        <option ';
                            if ($linha['status_final'] == "deferido_parcialmente") echo " selected";
                            if ($linha['obs_etapa'] == '3 - IS') echo " hidden";
                            echo '  value="deferido_parcialmente">Deferido Parcialmente</option> 
                        <option ';
                            if ($linha['status_final'] == "indeferido") echo " selected";
                            echo '  value="indeferido">Indeferido</option>
                    </select>
            </div>
            <div class="col-md-3">    
                <label>Cidade e Data</label>
                <input name="cidade_dt" value="' . $linha['cidade_data'] . '" class="form-control" >
                <br>
            </div>
             <div class="col-md-3 ' . (($linha['obs_etapa'] == '3 - IS' && $_SESSION['perfil'] != 'admin') ? ' hidden' : '') . '">      
                <label>Presidente da Comissão </label>
                <input name="presidente" value="' . $linha['presidente'] . '" class="form-control" >
                <br>
            </div>
            <div class="col-md-12">
                <label>Parágrafo 1</label>
                <textarea style="width:100%;"  rows="2" name="paragrafo1">' . $linha['paragrafo1'] . '</textarea>
            </div>
            <div class="col-md-12">
                <label>Parágrafo 2</label>
                <textarea style="width:100%;" rows="2" name="paragrafo2">' . $linha['paragrafo2'] . '</textarea>
            </div>
            
            <div class="col-md-12">
            <br>
            <button  type="submit"  class="btn btn-primary btn-block">Salvar</button>
            </div>
        </form>
        <div class="col-md-12"><br></div>
        <div class="col-md-5">
            <i>Última atualização em ' . $ultima_atualizacao . ' - <a href="usuario_visualiza.php?id_usuario=' . $linha['_usuario_ultima_atualizacao'] . '"> ' . $usuario_ultima_at . '</a> ' . $foto . '</i>
        </div>                        
        <div class="col-md-5">
            <i>Análise realizada em ' . $data_analise . ' - <a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario_analise'] . '"> ' . $usuario_realizou_analise . '</a> ' . $foto_analise . '<br></i>
        </div>
        <div class="col-md-2">
        
            <a href="mpdf/oficio_resposta_recurso.php?id_recurso=' . $linha['id'] . '&crip=' . $crip . '" target="_blank"><img title="Gerar Ofício" src="imagens/pdf.png" width="50px"></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'candidato_recurso\',\'' . $id_usuario . '\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>
        </div>
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