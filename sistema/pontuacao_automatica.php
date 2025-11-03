<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) || $perfil != 'admin' && $perfil != 'consulta' && $perfil != 'avaliador') {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$especialidade_selecionado = null;
if (isset($_GET['especialidade_selecionado']))
    $especialidade_selecionado = (int)$_GET['especialidade_selecionado'];

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Pontuação automática <i class="fa fa-users"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Pontuação Automática</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Filtro de Especialidade -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20 d-flex justify-content-between align-items-center">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtro de Especialidade
                    </span>

                    <div class="export-section">
                        <a href="excel_pontuacao_automatica.php" target="_blank" class="btn btn-success" data-bs-toggle="tooltip" title="Exportar para Excel">
                            <i class="fa fa-download me-1"></i>
                            Download Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-12">
                            <form name="fomulario" action="pontuacao_automatica.php" method="get">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade para análise
                                </label>
                                <select onchange="fomulario.submit()" name="especialidade_selecionado" class="form-control">
                                    <option value="">Selecione a especialidade</option>
                                    <?php foreach ($conexao->get_especialidade() as $value): ?>
                                        <option value="<?= $value['id'] ?>" <?= $especialidade_selecionado == $value['id'] ? 'selected' : '' ?>>
                                            <?= mb_strtoupper($value['ott_stt'], "UTF-8") ?> - <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Classificação por Pontuação Automática -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-trophy me-2"></i>
                            Classificação por Pontuação Automática
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica2">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-graduation-cap"></i>Especialidade</th>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($especialidade_selecionado != null):
                                    $lista_candidatos = $conexao->get_todos_candidatos();

                                    foreach ($lista_candidatos as $linha):
                                        $id_usuario = $linha['id'];
                                        $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                                        foreach ($inscricoes as $valor):
                                            if ($valor['id_especialidade'] != $especialidade_selecionado) continue;

                                            $pontuacao_final = null;
                                            $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $valor['id_especialidade']);
                                            $data_habilitacao = new DateTime(date($valor['data_habilitacao']));

                                            foreach ($lista_curriculo_adicionado as $curriculo):
                                                if ($curriculo['carga_horaria_obrigatoria'] == '1'):
                                                    $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                                    $data_fim_original = reverte_data($curriculo['data_termino']);

                                                    $data_inicio = new DateTime(date($data_inicio_original));

                                                    if ($_SESSION['selecao_regiao'] != 7 && $data_inicio < $data_habilitacao):
                                                        $data_inicio = $data_habilitacao;
                                                    endif;

                                                    $data_fim = new DateTime(date($data_fim_original));
                                                    $intervalo = $data_fim->diff($data_inicio);

                                                    if ($data_inicio > $data_fim) continue;

                                                    $total_de_dias = (int)$intervalo->format('%a');
                                                    $pontuacao = ($curriculo['pontuacao'] * $total_de_dias) / 1000;
                                                else:
                                                    $pontuacao = $curriculo['pontuacao'] / 1000;
                                                endif;

                                                $pontuacao_final += $pontuacao;
                                            endforeach;

                                            // Formatação da pontuação
                                            $pontuacao_display = $pontuacao_final !== null ? number_format($pontuacao_final, 2, ',', '.') : '0,00';
                                            $pontuacao_class = 'primary';
                                            if ($pontuacao_final > 1000) $pontuacao_class = 'success';
                                            if ($pontuacao_final > 5000) $pontuacao_class = 'warning';
                                ?>
                                            <tr>
                                                <!-- Especialidade -->
                                                <td>
                                                    <div class="especialidade-info">
                                                        <span class="fw-medium"><?= mb_strtoupper($valor['ott_stt'], "UTF-8") . ' - ' . htmlspecialchars($valor['especialidade']) ?></span>
                                                    </div>
                                                </td>

                                                <!-- CPF -->
                                                <td>
                                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                        <?= $linha['cpf'] ?>
                                                    </a>
                                                </td>

                                                <!-- Nome -->
                                                <td>
                                                    <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                                </td>

                                                <!-- Pontuação -->
                                                <td class="text-center">
                                                    <span class="pontuacao-badge badge bg-<?= $pontuacao_class ?> fs-6">
                                                        <?= $pontuacao_display ?>
                                                    </span>
                                                </td>
                                            </tr>
                                <?php
                                        endforeach;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabela de Detalhes dos Currículos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-list-alt me-2"></i>
                            Detalhes dos Currículos da Especialidade
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th><i class="fa fa-file-text"></i> Currículo</th>
                                    <th width="120px" class="text-center"><i class="fa fa-check-circle"></i> Habilitação</th>
                                    <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Início</th>
                                    <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Fim</th>
                                    <th width="80px" class="text-center"><i class="fa fa-calendar"></i> Dias</th>
                                    <th width="100px" class="text-center"><i class="fa fa-circle"></i> Status</th>
                                    <th width="120px" class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // PONTUAÇÃO AUTOMATICA 7RM
                                $lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
                                foreach ($lista_inscricoes as $value):
                                    $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'], $value['id_especialidade']);

                                    foreach ($lista_docs_obrigatorios as $curriculo):
                                        if ($curriculo['carga_horaria_obrigatoria'] == '1'):
                                            $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                            $data_fim_original = reverte_data($curriculo['data_termino']);

                                            $data_inicio = new DateTime(date($data_inicio_original));
                                            $data_fim = new DateTime(date($data_fim_original));
                                            $intervalo = $data_fim->diff($data_inicio);

                                            $total_de_dias = (int)$intervalo->format('%a');
                                            $pontuacao = ($curriculo['pontuacao'] * $total_de_dias) / 1000;
                                        else:
                                            $pontuacao = $curriculo['pontuacao'] / 1000;
                                        endif;

                                        $pontuacao_final += $pontuacao;

                                        if ($_SESSION['selecao_regiao'] == 7):
                                            $validado_class = $curriculo['valido'] == '1' ? 'success' : 'danger';
                                            $validado_text = $curriculo['valido'] == '1' ? 'Sim' : 'Não';
                                ?>
                                            <tr>
                                                <td>
                                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                        <?= $linha['cpf'] ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($linha['nome_completo']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary me-1"><?= mb_strtoupper($valor['ott_stt'], "UTF-8") ?></span>
                                                    <?= htmlspecialchars($valor['especialidade']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($curriculo['nome_curriculo']) ?></td>
                                                <td class="text-center"><?= $data_habilitacao->format('d/m/Y') ?></td>
                                                <td class="text-center"><?= $data_inicio_original ?></td>
                                                <td class="text-center"><?= $data_fim->format('d/m/Y') ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-info"><?= $total_de_dias ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?= $validado_class ?>"><?= $validado_text ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-success"><?= number_format($pontuacao_final, 2, ',', '.') ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                endforeach;

                                // PONTUAÇÃO AUTOMATICA NORMAL
                                if ($especialidade_selecionado != null):
                                    $lista_candidatos = $conexao->get_todos_candidatos();

                                    foreach ($lista_candidatos as $linha):
                                        $id_usuario = $linha['id'];
                                        $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                                        foreach ($inscricoes as $valor):
                                            if ($valor['id_especialidade'] != $especialidade_selecionado) continue;

                                            $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $valor['id_especialidade']);
                                            $data_habilitacao = new DateTime(date($valor['data_habilitacao']));

                                            foreach ($lista_curriculo_adicionado as $cirriculo):
                                                if ($cirriculo['carga_horaria_obrigatoria'] == '1'):
                                                    $data_inicio_original = reverte_data($cirriculo['data_inicio']);
                                                    $data_fim_original = reverte_data($cirriculo['data_termino']);

                                                    $data_inicio = new DateTime(date($data_inicio_original));
                                                    $data_fim = new DateTime(date($data_fim_original));
                                                    $intervalo = $data_fim->diff($data_inicio);

                                                    $total_de_dias = (int)$intervalo->format('%a');
                                                    if ($data_inicio > $data_fim) $total_de_dias = 0;

                                                    $pontuacao = ($cirriculo['pontuacao'] * $total_de_dias) / 1000;
                                                else:
                                                    $pontuacao = $cirriculo['pontuacao'] / 1000;
                                                endif;

                                                $validado_class = $cirriculo['valido'] == '1' ? 'success' : 'danger';
                                                $validado_text = $cirriculo['valido'] == '1' ? 'Sim' : 'Não';

                                                if ($_SESSION['selecao_regiao'] != 7):
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                                <?= $linha['cpf'] ?>
                                                            </a>
                                                        </td>
                                                        <td><?= htmlspecialchars($linha['nome_completo']) ?></td>
                                                        <td>
                                                            <span class="badge bg-secondary me-1"><?= mb_strtoupper($valor['ott_stt'], "UTF-8") ?></span>
                                                            <?= htmlspecialchars($valor['especialidade']) ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($cirriculo['nome_curriculo']) ?></td>
                                                        <td class="text-center"><?= $data_habilitacao->format('d/m/Y') ?></td>
                                                        <td class="text-center"><?= $data_inicio_original ?></td>
                                                        <td class="text-center"><?= $data_fim->format('d/m/Y') ?></td>
                                                        <td class="text-center">
                                                            <span class="badge bg-info"><?= $total_de_dias ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-<?= $validado_class ?>"><?= $validado_text ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fw-bold text-success"><?= number_format($pontuacao, 2, ',', '.') ?></span>
                                                        </td>
                                                    </tr>
                                <?php
                                                endif;
                                            endforeach;
                                        endforeach;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable({
        "order": [
            [3, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable({
        "order": [
            [1, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>