<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 5856856! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 4573475! Página não encontrada!");
    exit();
}

$id_especialidade_selecionada = 0;

if (isset($_GET['id_especialidade']))
    $id_especialidade_selecionada = (int)$_GET['id_especialidade'];

$get_especialidade_selecionada = [];

if ($id_especialidade_selecionada != 0)
    $get_especialidade_selecionada = $conexao->get_candidatos_especialidade($id_especialidade_selecionada);
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatorio Prioridade das especialidades <i class="fa fa-file-text"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Relatorio Prioridade das especialidades</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Filtro de Especialidade -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtro de Especialidade
                    </span>
                </div>
                <div class="card-body">
                    <form name="fomulario" action="relatorio_prioridades_especialidade.php" method="get">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade para análise
                                </label>
                                <select onchange="fomulario.submit()" name="id_especialidade" class="form-control">
                                    <option value="">Selecione a especialidade</option>
                                    <?php foreach ($conexao->get_especialidade() as $value): ?>
                                        <option value="<?= $value['id'] ?>" <?= $id_especialidade_selecionada == $value['id'] ? 'selected' : '' ?>>
                                            <?= mb_strtoupper($value['ott_stt'], "UTF-8") ?> - <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Prioridades por Especialidade -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-list-ol me-2"></i>
                            Prioridades dos Candidatos
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th><i class="fa fa-map-marker"></i> Prioridades de Cidades</th>
                                    <th style="width: 80px;"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $candidatos_exibidos = 0;

                                if ($id_especialidade_selecionada):
                                    foreach ($get_especialidade_selecionada as $linha):
                                        if ($linha['etapa'] != $_SESSION['etapa_selecao']) continue;

                                        $id_candidato_x_especialidade = $linha['id_ce'];
                                        $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);
                                        $candidatos_exibidos++;

                                        // Processar prioridades
                                        $prioridades_array = [];
                                        foreach ($lista_cidades as $linha2) {
                                            $prioridades_array[] = [
                                                'prioridade' => $linha2['prioridade'],
                                                'cidade' => $linha2['nome']
                                            ];
                                        }

                                        // Ordenar por prioridade
                                        usort($prioridades_array, function ($a, $b) {
                                            return $a['prioridade'] - $b['prioridade'];
                                        });
                                ?>
                                        <tr>
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

                                            <!-- Prioridades -->
                                            <td>
                                                <div class="prioridades-list">
                                                    <?php if (!empty($prioridades_array)): ?>
                                                        <?php foreach ($prioridades_array as $prioridade): ?>
                                                            <span class="badge bg-primary text-dark border me-2 mb-2">
                                                                <strong class=""><?= $prioridade['prioridade'] ?>ª</strong>
                                                                <?= htmlspecialchars($prioridade['cidade']) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Nenhuma prioridade definida</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                endif;
                                ?>

                                <?php if (!$id_especialidade_selecionada): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-filter fa-2x mb-3"></i>
                                                <p class="mb-0">Selecione uma especialidade para visualizar as prioridades.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php elseif ($candidatos_exibidos == 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-users fa-2x mb-3"></i>
                                                <p class="mb-0">Nenhum candidato encontrado na etapa <?= htmlspecialchars($_SESSION['etapa_selecao']) ?>.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
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
    $('#tabela_dinamica').DataTable({
        "order": [
            [1, "asc"]
        ]
    });
</script>

</body>

</html>
<?php $conexao = null; ?>