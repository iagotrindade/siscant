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

<!-- Calculadora de Datas -->
<div class="card dashboard-card mb-4" <?= isset($_SESSION['eipot']) == 1 ? "hidden" : "" ?>>
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-calculator me-2"></i>
            Calculadora Rápida de Datas
        </span>
    </div>
    <div class="card-body">
        <!-- Calculadora de Meses -->
        <div class="row" <?= $_SESSION['selecao_regiao'] != 3 ? 'hidden' : '' ?>>
            <form action="<?= 'usuario_visualiza.php?=' . $id_usuario . '#calcular_data' ?>" method="GET">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Data de Início</label>
                    <input id="data_inicio_calcular" name="data_inicio_calcular" onblur="calcula_data()" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Data de Fim</label>
                    <input id="data_fim_calcular" name="data_fim_calcular" onblur="calcula_data()" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Resultado</label>
                    <div class="form-control bg-light">
                        <p id='diferenca_datas' class="mb-0 fw-bold text-primary">Diferença em Meses: </p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Calculadora de Dias -->
        <a name="calcula_data_dias"></a>
        <div class="row" <?= $_SESSION['selecao_regiao'] == 3 ? 'hidden' : '' ?>>
            <div class="col-md-12 mb-3">
                <h5 class="text-primary">
                    <i class="fa fa-calendar-day me-2"></i>
                    Calculadora de Dias
                </h5>
            </div>
            <form action="<?= 'usuario_visualiza.php?=' . $id_usuario . '#calcula_data_dias' ?>" method="GET">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Data de Início</label>
                    <input id="data_inicio_calcular_dias" name="data_inicio_calcular_dias" onblur="calcula_data_dias()" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Data de Fim</label>
                    <input id="data_fim_calcular_dias" name="data_fim_calcular_dias" onblur="calcula_data_dias()" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Resultado</label>
                    <div class="form-control bg-light">
                        <p id='diferenca_datas_dias' class="mb-0 fw-bold text-primary">Diferença em Dias: </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$inscricoes = $conexao->get_especialidade_candidato($id_usuario);

foreach ($inscricoes as $valor) {
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
        $teste_pratico     = $resultado_verificacao[0]['teste_pratico'];
        $nota_av     = $resultado_verificacao[0]['nota_av'];
        $nota_prova_teorico_pratico     = (float)$resultado_verificacao[0]['nota_prova_teorico_pratico'];
        $resultado_prova_teorico_pratico     = $resultado_verificacao[0]['apto_prova_teorico_pratico'];
        $prova_pratica_musica           = (float)$resultado_verificacao[0]['prova_pratica_musica'];
        $prova_teorica_musica           = (float)$resultado_verificacao[0]['prova_teorica_musica'];
        $prova_oral_musica              = (float)$resultado_verificacao[0]['prova_oral_musica'];
        $usuario_avaliou_provas_musica  = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
    }

    if ($id_candidato_x_especialidade != null)
        $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

    $cor_fundo = "info";
    $texto_nao_concorrendo_especialidade = "";
    if (!$concorrendo_especialidade) {
        $cor_fundo = "laranja";
        $texto_nao_concorrendo_especialidade = "<span class='badge bg-danger'>NÃO CONCORRENDO</span>";
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
    <!-- Card da Especialidade -->
    <div class="card dashboard-card mb-20" <?= isset($avaliador_pode_avaliar_id_especialidade) && !$avaliador_pode_avaliar_id_especialidade ? "hidden" : "" ?>>
        <div class="card-header dashboard-header d-flex justify-content-between align-items-center mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-graduation-cap me-2"></i>
                Especialidade: <?= mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " . $valor['especialidade'] . " - " . $valor['data_habilitacao'] ?>
                <?= $texto_nao_concorrendo_especialidade ?>
            </span>
            <a href='relatorio_especialidade_candidato.php?id_especialidade=<?= $valor['id_especialidade'] ?>'
                class="btn btn-success btn-sm"
                data-bs-toggle="tooltip"
                title="Ver relatório completo">
                <i class="fa fa-bar-chart me-1"></i> VER ESPECIALIDADE
            </a>
        </div>

        <div class="card-body">
            <!-- Prioridades de Cidades -->
            <?php if ($selecao_libera_prioridade_candidato == '1'): ?>
                <div class="alert alert-info mb-20">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-map-marker fa-2x mr-10"></i>
                        <div>
                            <strong>Prioridades de cidades selecionadas:</strong>
                            <div class="mt-2">
                                <?php foreach ($lista_cidades as $linha): ?>
                                    <span class="badge bg-primary me-2 mb-2">
                                        <?= $linha['prioridade'] ?>ª <?= $linha['nome'] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabela de Documentos -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="25%"><i class="fa fa-file-text"></i> Nome do Arquivo</th>
                            <th width="10%"><i class="fa fa-calendar"></i> Data Início</th>
                            <th width="10%"><i class="fa fa-calendar"></i> Data Fim</th>
                            <th width="15%"><i class="fa fa-file-text"></i> Resumo do PDF</th>
                            <th width="8%"><i class="fa fa-times"></i> Multiplicar</th>
                            <th width="12%"><i class="fa fa-comment"></i> Justificativa</th>
                            <th width="10%"><i class="fa fa-thumbs-up"></i> Avaliar Doc</th>
                            <th width="5%"><i class="fa fa-trophy"></i> Pts</th>
                            <th width="5%"><i class="fa fa-user"></i> Avaliação</th>
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
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='40' height='40' style='object-fit: cover;'></a>";
                                if (count($usuario_avaliou) > 0) {
                                    $get_foto = $conexao->get_foto_usuario($usuario_avaliou[0]['id']);
                                    if (count($get_foto) > 0) {
                                        $foto = $get_foto[0]['nome'];
                                        $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/$foto' width='40' height='40' style='object-fit: cover;'></a>";
                                    }
                                }

                                if ($linha['valido'] == '0')
                                    $validado = "<span class='badge bg-danger me-2 mr-10'  style='font-size: 18px; border-radius: 360px !important; padding: 10px'><i class='fa fa-thumbs-down'></i></span>" . $foto;
                                if ($linha['valido'] == '1')
                                    $validado = "<span class='badge bg-primary me-2 mr-10'  style='font-size: 18px; border-radius: 360px !important; padding: 10px;'><i class='fa fa-thumbs-up'></i></span>" . $foto;

                                $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_especialidade_curriculo']);
                                $dt_inicio = $linha['data_inicio'] != null ? trata_data($linha['data_inicio']) : null;
                                $dt_fim = $linha['data_termino'] != null ? trata_data($linha['data_termino']) : null;
                                $justificativa = $linha['justificativa'];
                        ?>
                                <tr>
                                    <td>
                                        <a href="baixaPDF.php?codigo=cand_esp_aval&nome_arquivo=<?= $linha['nome'] ?>" target="_blank" class="text-decoration-none">
                                            <i class="fa fa-file-pdf me-1 text-danger"></i>
                                            <?= htmlspecialchars($linha['nome_curriculo']) ?>
                                        </a>
                                    </td>
                                    <td><?= $dt_inicio ?></td>
                                    <td><?= $dt_fim ?></td>
                                    <td><?= htmlspecialchars($linha['carga_horaria']) ?></td>

                                    <form action="../banco_dados/valida_curriculo.php" method="post">
                                        <td>
                                            <select class="form-control form-control-sm" name="multiplicador" <?= $linha['multiplicacao'] != '1' ? 'disabled' : '' ?>>
                                                <?php for ($i = 0; $i <= $linha['quantidade_multiplicacao']; $i++): ?>
                                                    <option value="<?= $i ?>" <?= $linha['multiplicador'] == $i ? 'selected' : '' ?>>
                                                        <?= $i ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" name="justificativa" rows="2" style="width:100%;"><?= htmlspecialchars($justificativa) ?></textarea>
                                        </td>
                                        <td>
                                            <input type="hidden" value="<?= $id_usuario ?>" name="id_usuario">
                                            <input type="hidden" value="<?= $id_especialidade ?>" name="id_especialidade">
                                            <input type="hidden" value="<?= $crip ?>" name="criptografia">
                                            <input type="hidden" value="<?= $linha['id_especialidade_curriculo'] ?>" name="id_especialidade_curriculo">
                                            <input type="hidden" value="<?= $linha['id_curriculo'] ?>" name="id_curriculo">

                                            <select name="valido" class="form-control form-control-sm" onchange="submit()">
                                                <option value="">Selecionar</option>
                                                <option value="1">VÁLIDO</option>
                                                <option value="0">INVÁLIDO</option>
                                            </select>
                                        </td>
                                    </form>

                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $pontuacao ?></span>
                                    </td>
                                    <td class="d-flex text-center"><?= $validado ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Somatório de Pontos -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-bar-chart fa-2x mr-10"></i>
                            <div>
                                <h5 class="mb-0">
                                    <?= $_SESSION['selecao_codigo'] == "cet" ? 'SOMATÓRIO DOS PONTOS (APÓS TESTES DE CONHECIMENTOS)' : 'SOMATÓRIO DOS PONTOS' ?>
                                </h5>
                                <p class="mb-0 fs-4" style="font-weight: 600;">
                                    <?php
                                    if (!empty($valor['musica'])) {
                                        echo number_format((float)$somatorio_total_pontos_musica, 2, '.', '');
                                    } elseif (!empty($_SESSION['selecao_codigo']) && $_SESSION['selecao_codigo'] === 'cet') {
                                        $nota_calculada = (((float)$nota_prova_teorico_pratico * 2) + (float)$pontuacao_total) / 3;
                                        echo number_format($nota_calculada, 2, '.', '');
                                    } elseif ($teste_pratico == '1' && $nota_av == '1') {
                                        $total = (float)$pontuacao_total + (float)$nota_prova_teorico_pratico;
                                        echo number_format($total, 2, '.', '');
                                    } else {
                                        echo number_format($pontuacao_total, 2, '.', '');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Música -->
            <?php if ($valor['musica'] == '1'): ?>
                <?php

                $avaliador = $conexao->get_usuario_id($usuario_avaliou_provas_musica);
                $foto_html = null;
                if ($usuario_avaliou_provas_musica > 0) {
                    $foto_avaliador_musica = $conexao->get_foto_usuario($usuario_avaliou_provas_musica);
                    if (count($foto_avaliador_musica) > 0)
                        $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $usuario_avaliou_provas_musica . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/" . $foto_avaliador_musica[0]['nome'] . "' width='50' height='50' style='object-fit: cover;'></a>";
                    else
                        $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $usuario_avaliou_provas_musica . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='50' height='50' style='object-fit: cover;'></a>";
                }
                $crip_musica = hash('sha256', $_SESSION['chave'] . "freitas");
                ?>
                <div class="alert alert-info">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white mb-20">
                            <span class="mb-0">
                                <i class="fa fa-music me-2"></i>
                                Avaliação de Provas - Especialidade Musical
                            </span>
                        </div>
                        <div class="card-body">
                            <form action="../banco_dados/prova_musica_salvar.php" method="post">
                                <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                                <input type="hidden" name="id_especialidade" value="<?= $id_especialidade ?>">
                                <input type="hidden" name="criptografia" value="<?= $crip_musica ?>">
                                <input type="hidden" name="id_especialidade_curriculo" value="<?= $linha['id_especialidade_curriculo'] ?>">
                                <input type="hidden" name="id_candidato_x_especialidade" value="<?= $id_candidato_x_especialidade ?>">

                                <div class="row">
                                    <div class="col-lg-3 mb-20">
                                        <label class="form-label fw-semibold"><i class="fa fa-pencil"></i> Prova Escrita</label>
                                        <input type="text" value="<?= $prova_teorica_musica ?>" name="pontuacao_teorica" class="form-control">
                                    </div>
                                    <div class="col-lg-3 mb-20">
                                        <label class="form-label fw-semibold"><i class="fa fa-pencil"></i> Prova Oral</label>
                                        <input type="text" value="<?= $prova_oral_musica ?>" name="pontuacao_oral" class="form-control">
                                    </div>
                                    <div class="col-lg-3 mb-20">
                                        <label class="form-label fw-semibold"><i class="fa fa-pencil"></i> Prova Prática</label>
                                        <input type="text" value="<?= $prova_pratica_musica ?>" name="pontuacao_pratica" class="form-control">
                                    </div>
                                    <div class="col-lg-3 mb-20 d-flex flex-column align-items-center justify-content-end">
                                        <?= $foto_html ?>
                                        <?= $avaliador[0]['posto_grad'] ?>
                                        <?= $avaliador[0]['nome_guerra'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100 py-2">
                                            <i class="fa fa-save me-2"></i>
                                            SALVAR PONTUAÇÃO DAS PROVAS
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Teste Teórico/Prático -->
            <?php if ($valor['teste_pratico'] == 1): ?>
                <div class="alert alert-warning">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark mb-20">
                            <span class="mb-0">
                                <i class="fa fa-flask me-2"></i>
                                Teste Teórico/Prático
                            </span>
                        </div>
                        <div class="card-body">
                            <?php
                            $id_cand_esp = $conexao->get_id_candidato_x_especialidade($id_usuario, $id_especialidade);
                            $nota = (float) $nota_prova_teorico_pratico;
                            $nota_formatada = number_format($nota, 2, '.', '');
                            if ($nota < 10) {
                                $nota_formatada = '0' . $nota_formatada;
                            }


                            ?>
                            <form method="post" action="../banco_dados/resultado_prova_cadastra.php">
                                <input type="hidden" value="<?= $id_usuario ?>" name="id_usuario">
                                <input type="hidden" value="<?= $crip ?>" name="criptografia">
                                <input type="hidden" value="<?= $id_especialidade ?>" name="id_especialidade">
                                <input type="hidden" value="<?= $linha['id_especialidade_curriculo'] ?>" name="id_especialidade_curriculo">
                                <input type="hidden" value="<?= $id_cand_esp[0]['id'] ?>" name="id_candidato_x_especialidade">

                                <div class="row">
                                    <!-- Apto/Inapto -->
                                    <div class="col-md-12 mb-20">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-pencil-square-o me-1"></i>
                                            Resultado
                                        </label>
                                        <select
                                            name="resultado_teorico_pratica"
                                            class="form-control"
                                            required>
                                            <option value="" <?= $resultado_prova_teorico_pratico == null ? 'selected' : '' ?>>Selecione a Situação da Prova</option>
                                            <option value="1" <?= $resultado_prova_teorico_pratico == "1" ? 'selected' : '' ?>>Apto</option>
                                            <option value="0" <?= $resultado_prova_teorico_pratico == "0" ? 'selected' : '' ?>>Inapto</option>
                                            <option value="2" <?= $resultado_prova_teorico_pratico == "2" ? 'selected' : '' ?>>Não compareceu</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Botão de Submit -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                            <i class="fa fa-sync-alt me-2"></i>
                                            ATUALIZAR RESULTADO
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form method="post" action="../banco_dados/nota_teorico_pratica.php" enctype="multipart/form-data">
                                <input type="hidden" value="<?= $id_usuario ?>" name="id_usuario">
                                <input type="hidden" value="<?= $crip ?>" name="criptografia">
                                <input type="hidden" value="<?= $id_especialidade ?>" name="id_especialidade">
                                <input type="hidden" value="<?= $linha['id_especialidade_curriculo'] ?>" name="id_especialidade_curriculo">
                                <input type="hidden" value="<?= $id_cand_esp[0]['id'] ?>" name="id_candidato_x_especialidade">

                                <div class="row mt-20">
                                    <div class="col-md-12 mb-20">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-edit me-1"></i>
                                            Pontuação do Teste Teórico/Prático
                                        </label>
                                        <input name="pontuacao_teorico_pratica"
                                            value="<?= htmlspecialchars($nota_formatada, ENT_QUOTES, 'UTF-8') ?>"
                                            class="form-control"
                                            placeholder="Digite a pontuação">
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100 py-2">
                                            <i class="fa fa-save me-2"></i>
                                            SALVAR NOTA
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Adicionar Currículo (Admin) -->
            <?php if ($_SESSION['perfil'] == 'admin' && ($_SESSION['selecao_regiao'] == 6 || $_SESSION['codigo'] == 'cet')): ?>
                <div class="card border-primary mt-4">
                    <div class="card-header bg-primary text-white mb-20">
                        <span class="mb-0">
                            <i class="fa fa-plus-circle me-2"></i>
                            Adicionar Currículo para o Candidato
                        </span>
                    </div>
                    <div class="card-body">
                        <form method="post" action="arquivo_upload_operador_candidato.php" enctype="multipart/form-data">
                            <input type="hidden" name="user" value="<?= $id_especialidade ?>">
                            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                            <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">

                            <div class="row">
                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-file-pdf-o me-1"></i>
                                        Selecione o Currículo
                                    </label>
                                    <select name="id_curriculo" class="form-control" required>
                                        <option value="">Selecione o arquivo a ser adicionado</option>
                                        <?php
                                        $lista_curriculos = $conexao->get_curriculo_cadastrados();
                                        foreach ($lista_curriculos as $linha) {
                                            echo '<option value="' . $linha['id'] . '">' . htmlspecialchars($linha['nome']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Máximo 5MB, formato PDF
                                    </small>
                                </div>

                                <div class="col-md-4 mb-20">
                                    <label class="form-label fw-semibold"><i class="fa fa-calendar"></i> Data de Início</label>
                                    <input type="text" name="data_inicio" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold"><i class="fa fa-calendar"></i> Data de Finalização</label>
                                    <input type="text" name="data_fim" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold"><i class="fa fa-clock-o"></i> Carga Horária</label>
                                    <input name="carga_horaria" class="form-control" placeholder="Horas totais">
                                </div>

                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-paperclip me-1"></i>
                                        Selecionar Arquivo
                                    </label>
                                    <input type="file" name="arquivo" class="form-control" accept=".pdf" required>
                                </div>

                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="fa fa-upload me-2"></i>
                                        ENVIAR ARQUIVO
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gerenciamento da Especialidade -->
            <?php if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "avaliador") echo " hidden "; ?>
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white mb-20">
                    <span class="mb-0">
                        <i class="fa fa-cogs me-2"></i>
                        Gerenciamento da Especialidade
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Concorrência na Especialidade -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header mb-20">
                                    <span class="mb-0">
                                        <i class="fa fa-flag me-2"></i>
                                        Concorrendo/Desclassificado
                                    </span>
                                </div>

                                <!-- Alertas de Status -->
                                <?php if ($justificativa_especialidade != null): ?>
                                    <?php
                                    $get_foto = $conexao->get_foto_usuario($id_usuario_alterou_concorrendo_especialidade);
                                    if ($id_usuario_alterou_concorrendo_especialidade != null)
                                        $foto = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo_especialidade . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='40' height='40' style='object-fit: cover;'></a>";
                                    if (count($get_foto) > 0)
                                        $foto = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo_especialidade . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/" . $get_foto[0]['nome'] . "' width='40' height='40' style='object-fit: cover;'></a>";
                                    ?>

                                    <?php if (!$concorrendo_especialidade): ?>
                                        <div class="alert alert-danger d-flex align-items-center">
                                            <i class="fa fa-times-circle fa-2x mr-10"></i>
                                            <div class="flex-grow-1">
                                                <strong>ELIMINADO desta especialidade!</strong><br>
                                                <span class="text-muted"><?= htmlspecialchars($justificativa_especialidade) ?></span>
                                            </div>
                                            <div class="ms-3">
                                                <?= $foto ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($concorrendo_especialidade): ?>
                                        <div class="alert alert-success d-flex align-items-center">
                                            <i class="fa fa-check-circle fa-2x mr-10"></i>
                                            <div class="flex-grow-1">
                                                <strong>CONCORRENDO nesta especialidade!</strong><br>
                                                <span class="text-muted"><?= htmlspecialchars($justificativa_especialidade) ?></span>
                                            </div>
                                            <div class="ms-3">
                                                <?= $foto ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="card-body">
                                    <form action="../banco_dados/candidato_concorrendo_especialidade.php" method="post">
                                        <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas") ?>">
                                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                                        <input type="hidden" name="id_candidato_x_especialidade" value="<?= $id_candidato_x_especialidade ?>">
                                        <input type="hidden" name="id_especialidade" value="<?= $id_especialidade ?>">

                                        <div class="mb-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="concorrendo" id="concorrendo_<?= $id_especialidade ?>" <?= $concorrendo_especialidade ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-semibold" for="concorrendo_<?= $id_especialidade ?>">
                                                    Candidato concorrendo na especialidade
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-20">
                                            <label class="form-label fw-semibold"><i class="fa fa-comment-o"></i> Justificativa</label>
                                            <textarea name="justificativa" class="form-control" rows="3" placeholder="Digite a justificativa para a mudança de status"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-save me-2"></i>
                                            SALVAR STATUS
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Etapa e Cidade de Serviço -->
                        <div class="col-md-6">
                            <!-- Etapa da Especialidade -->
                            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                                <div class="card mb-4">
                                    <div class="card-header mb-20">
                                        <span class="mb-0">
                                            <i class="fa fa-list-ol me-2"></i>
                                            Etapa da Especialidade
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <form action="../banco_dados/candidato_especialidade_etapa_atualiza.php" method="post">
                                            <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas") ?>">
                                            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                                            <input type="hidden" name="id_especialidade" value="<?= $valor['id_especialidade'] ?>">
                                            <input type="hidden" name="etapa_atual" value="<?= $valor['etapa'] ?>">
                                            <input type="hidden" name="nome_especialidade" value="<?= $valor['especialidade'] ?>">

                                            <div class="mb-10">
                                                <label class="form-label fw-semibold"> <i class="fa fa-list-ol me-2"></i> Etapa do Candidato</label>
                                                <select name="etapa_especialidade" class="form-control" required>
                                                    <option value="">Selecione a etapa</option>
                                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                                        <option value="<?= $i ?>" <?= $valor['etapa'] == $i ? 'selected' : '' ?>>
                                                            Etapa <?= $i ?>
                                                        </option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa fa-save me-2"></i>
                                                SALVAR ETAPA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Cidade de Serviço -->
                            <?php if ($etapa >= 4 && $concorrendo_especialidade): ?>
                                <div class="card">
                                    <div class="card-header mb-20">
                                        <span class="mb-0">
                                            <i class="fa fa-map-marker me-2"></i>
                                            Guarnição EST/EBST
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <form action="../banco_dados/candidato_altera_cidade_vai_servir.php" method="post">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold"><i class="fa fa-map-marker"></i> Cidade Escolhida</label>
                                                <select name="cidade_candidato" class="form-control" required>
                                                    <option value="">CIDADE NÃO ESCOLHIDA</option>
                                                    <?php
                                                    $lista_cidades = $conexao->get_cidades_especialidade($valor['id_especialidade']);
                                                    foreach ($lista_cidades as $linha_cidade) {
                                                        $selected = $valor['cidade_escolheu_servir'] == $linha_cidade['id'] ? 'selected' : '';
                                                        echo "<option value='" . $linha_cidade['id'] . "' $selected>" . htmlspecialchars($linha_cidade['nome']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="id_especialidade_servir" value='<?= $valor['id_especialidade'] ?>'>
                                            <input type="hidden" name="c_p_f_candidato_servir" value='<?= $cpf ?>'>
                                            <input type="hidden" name="id_candidato_servir" value='<?= $id_usuario ?>'>

                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa fa-save me-2"></i>
                                                SALVAR CIDADE
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>