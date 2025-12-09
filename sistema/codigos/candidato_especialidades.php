<a name="especialidades"></a>

<?php
$inscricoes = $conexao->get_especialidade_candidato($id_usuario);

foreach ($inscricoes as $valor):
    $id_candidato_x_especialidade = null;
    $resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario, $valor['id_especialidade']);

    if (count($resultado_verificacao) > 0) {
        $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
        $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
        $nome_especialidade = $resultado_verificacao[0]['especialidade'];
        $cpf_candidato      = $resultado_verificacao[0]['cpf'];
        $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
        $ott_stt            = $resultado_verificacao[0]['ott_stt'];

        $prova_pratica_musica           = $resultado_verificacao[0]['prova_pratica_musica'];
        $prova_teorica_musica           = $resultado_verificacao[0]['prova_teorica_musica'];
        $prova_oral_musica              = $resultado_verificacao[0]['prova_oral_musica'];
        $usuario_avaliou_provas_musica  = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
    }

    if ($id_candidato_x_especialidade != null) {
        $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);
    }
?>

    <a name="avaliacao_id_<?= $id_especialidade ?>"></a>

    <!-- Card da Especialidade -->
    <div class="card dashboard-card mb-4" <?= $_SESSION['perfil'] == 'documentos' ? 'hidden' : '' ?>>
        <div class="card-header dashboard-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-graduation-cap me-2"></i>
                Especialidade:
                <?php
                $desclassificado_da_especialidade = "";
                if ($valor['concorrendo'] == 0) {
                    $desclassificado_da_especialidade = "<span class='badge bg-danger ms-2'><i class='fa fa-times me-1'></i>DESCLASSIFICADO: " . htmlspecialchars($valor['justificativa']) . "</span>";
                }

                echo "<a href='relatorio_especialidade_candidato.php?id_especialidade=" . $valor['id_especialidade'] . "' class='' style='color: #FFFFFF;'>" .
                    "<strong>" . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . htmlspecialchars($valor['especialidade']) . "</strong>" .
                    " <small class='text-muted' style='color: #FFFFFF;'>" . htmlspecialchars($valor['data_habilitacao']) . "</small>" .
                    " $desclassificado_da_especialidade</a>";
                ?>
            </span>
        </div>

        <div class="card-body">
            <!-- Prioridades de Cidades -->
            <?php if (!inscricao() || $_SESSION['selecao_codigo'] == 'mfdv'): ?>
                <div class="alert alert-info mb-4">
                    <h5 class="text-info mb-2">
                        <i class="fa fa-map-marker me-1"></i>
                        Prioridades de cidades selecionadas
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($lista_cidades as $linha): ?>
                            <span class="badge bg-primary mr-10">
                                <i class="fa fa-hashtag me-1"></i><?= $linha['prioridade'] ?>ª
                                <?= htmlspecialchars($linha['nome']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabela de Currículos -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="30%"><i class="fa fa-file-text"></i> Nome do Arquivo</th>
                            <th width="15%"><i class="fa fa-calendar"></i> Data Início</th>
                            <th width="15%"><i class="fa fa-calendar"></i> Data Fim</th>
                            <th width="20%"><i class="fa fa-file-text"></i> Resumo do PDF</th>
                            <?php if ($_SESSION['perfil'] == 'consulta'): ?>
                                <th width="15%"><i class="fa fa-pencil-square-o"></i> Justificativa</th>
                                <th width="15%"><i class="fa fa-file-text"></i> Avaliação</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($id_especialidade != null):
                            $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $id_especialidade);
                            $pontuacao_total = 0;

                            foreach ($lista_curriculo_adicionado as $linha):
                                $validado = null;
                                $pontuacao = null;

                                if ($linha['pontuacao'] != null && $linha['pontuacao'] > 0) {
                                    if ($linha['valido'] == '1') {
                                        $pontuacao = $linha['pontuacao'] / 1000;
                                        $pontuacao_total = $pontuacao_total + $pontuacao;
                                    }
                                }

                                $dt_inicio = $linha['data_inicio'] ? trata_data($linha['data_inicio']) : null;
                                $dt_fim = $linha['data_termino'] ? trata_data($linha['data_termino']) : null;

                                // Validado/Avaliador
                                $usuario_avaliou = $conexao->get_usuario_id($linha['usuario_avaliou']);
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='btn btn-sm btn-outline-primary ms-2'><i class='fa fa-user'></i></a>";

                                if (count($usuario_avaliou) > 0) {
                                    $get_foto = $conexao->get_foto_usuario($usuario_avaliou[0]['id']);
                                    if (count($get_foto) > 0) {
                                        $foto_nome = $get_foto[0]['nome'];
                                        $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='ms-2'><img class='img-circle rounded' src='fotos/$foto_nome' width='32px' height='32px'></a>";
                                    }
                                }

                                if ($linha['valido'] == '0')
                                    $validado = "<span class='badge bg-danger me-2 mr-10'  style='font-size: 18px; border-radius: 360px !important; padding: 10px'><i class='fa fa-thumbs-down'></i></span>" . $foto;
                                if ($linha['valido'] == '1')
                                    $validado = "<span class='badge bg-primary me-2 mr-10'  style='font-size: 18px; border-radius: 360px !important; padding: 10px;'><i class='fa fa-thumbs-up'></i></span>" . $foto;
                        ?>
                                <tr>
                                    <!-- Nome do Arquivo -->
                                    <td>
                                        <a href="baixaPDF.php?codigo=cand_esp&nome_arquivo=<?= $linha['nome'] ?>"
                                            target="_blank"
                                            class="text-decoration-none fw-medium">
                                            <i class="fa fa-file-pdf text-danger me-2"></i>
                                            <?= htmlspecialchars($linha['label']) ?>
                                        </a>
                                    </td>

                                    <!-- Data Início -->
                                    <td>
                                        <span><?= $dt_inicio ?></span>
                                    </td>

                                    <!-- Data Fim -->
                                    <td>
                                        <span><?= $dt_fim ?></span>
                                    </td>

                                    <!-- Resumo do PDF -->
                                    <td>
                                        <span class="fw-medium"><?= htmlspecialchars($linha['carga_horaria']) ?></span>
                                    </td>

                                    <?php if ($_SESSION['perfil'] == 'consulta'): ?>
                                        <!-- Justificativa -->
                                        <td>
                                            <span><?= htmlspecialchars($linha['justificativa']) ?></span>
                                        </td>

                                        <!-- Avaliação -->
                                        <td>
                                            <?= $validado ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>

                        <?php if (empty($lista_curriculo_adicionado)): ?>
                            <tr>
                                <td colspan="<?= $_SESSION['perfil'] == 'consulta' ? '6' : '4' ?>" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fa fa-folder-open fa-2x mb-2 opacity-50"></i><br>
                                        Nenhum currículo adicionado
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pontuação Total -->
            <?php if ($pontuacao_total > 0): ?>
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-dark">
                                <i class="fa fa-chart-line me-2"></i>
                                Pontuação Total da Especialidade
                            </span>
                            <span class="badge bg-primary fs-6">
                                <?= number_format($pontuacao_total, 2, ',', '.') ?> pontos
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<?php if (empty($inscricoes)): ?>
    <div class="card dashboard-card mb-4">
        <div class="card-body text-center py-5">
            <i class="fa fa-graduation-cap fa-3x text-muted mb-3 opacity-50"></i>
            <h5 class="text-muted">Nenhuma especialidade encontrada</h5>
            <p class="text-muted mb-0">Não há inscrições em especialidades para este candidato.</p>
        </div>
    </div>
<?php endif; ?>