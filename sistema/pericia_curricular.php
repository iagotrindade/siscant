<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235235! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 234235! Página não encontrada!");
    exit();
}

$lista_curriculos = $conexao->get_curriculo_cadastrados();

$curriculo_selecionado = null;
if (isset($_GET['curriculo_selecionado']))
    $curriculo_selecionado = (int)$_GET['curriculo_selecionado'];


?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Perícia Currícular </h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Perícia Currícular <i class="fa fa-file-text-o"></i></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Filtro de Currículo -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtro de Currículo
                    </span>
                </div>
                <div class="card-body">
                    <form name="fomulario" action="pericia_curricular.php" method="get">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-file-alt me-1"></i>
                                    Selecione o currículo para análise
                                </label>
                                <select onchange="fomulario.submit()" name="curriculo_selecionado" class="form-control">
                                    <option value="">Selecione o currículo</option>
                                    <?php foreach ($lista_curriculos as $linha): ?>
                                        <option value="<?= $linha['id'] ?>" <?= $curriculo_selecionado == $linha['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($linha['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Currículos Acima do Permitido -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Currículos Acima do Limite Permitido
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th width="100px" class="text-center"><i class="fa fa-shield"></i> Status Militar</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th><i class="fa fa-file-text"></i> Currículo</th>
                                    <th width="180px" class="text-center"><i class="fa fa-upload"></i> Arquivos (Atual/Máx)</th>
                                    <th width="140px" class="text-center"><i class="fa fa-times"></i> Multiplicações</th>
                                    <th width="140px" class="text-center"><i class="fa fa-trophy"></i> Pontuação Total</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_curriculo_acima_permitido = $conexao->get_curriculos_avaliados_acima_permitido($curriculo_selecionado);

                                foreach ($lista_curriculo_acima_permitido as $linha2):
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha2['id_usuario']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    $total_pontos_somados = null;
                                    if ((int)$linha2['total_pontos_somados'] > 0)
                                        $total_pontos_somados = (int)$linha2['total_pontos_somados'] / 1000;

                                    // Status dos arquivos
                                    $arquivos_class = 'success';
                                    if ($linha2['quantidade_arquivo'] >= $linha2['quantidade_maxima_uploads']) {
                                        $arquivos_class = 'danger';
                                    } elseif ($linha2['quantidade_arquivo'] > $linha2['quantidade_maxima_uploads'] * 0.8) {
                                        $arquivos_class = 'warning';
                                    }
                                ?>
                                    <tr>
                                        <!-- CPF -->
                                        <td>
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha2['id_usuario'] ?>"
                                                class="text-decoration-none">
                                                <?= $linha2['cpf'] ?>
                                            </a>
                                        </td>

                                        <!-- Status Militar -->
                                        <td class="text-center">
                                            <span class="bg-primary badge">
                                                <?= $linha2['ativa_reserva'] ?>
                                            </span>
                                        </td>

                                        <!-- Especialidade -->
                                        <td>
                                            <div class="especialidade-info">
                                                <span class="badge bg-secondary me-1"><?= mb_strtoupper($linha2['ott_stt'], "UTF-8") ?></span>
                                                <span class="fw-medium"><?= htmlspecialchars($linha2['especialidade']) ?></span>
                                            </div>
                                        </td>

                                        <!-- Currículo -->
                                        <td>
                                            <span class="curriculo-name"><?= htmlspecialchars($linha2['curriculo']) ?></span>
                                        </td>

                                        <!-- Arquivos -->
                                        <td class="text-center">
                                            <span class="arquivos-count badge bg-<?= $arquivos_class ?> text-black">
                                                <?= $linha2['quantidade_arquivo'] ?> / <?= $linha2['quantidade_maxima_uploads'] ?>
                                            </span>
                                        </td>

                                        <!-- Multiplicações -->
                                        <td class="text-center">
                                            <span class="multiplicador-count fw-bold text-primary">
                                                <?= $linha2['somatorio_multiplicador'] ?>
                                            </span>
                                        </td>

                                        <!-- Pontuação -->
                                        <td class="text-center">
                                            <span class="pontuacao-total fw-bold text-success">
                                                <?= $total_pontos_somados ?? '0' ?>
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha2['id_usuario'] ?>"
                                                class="btn btn-sm action-btn"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar candidato">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabela de Documentos Multiplicados -->
            <div class="card dashboard-card mb-4 <?= ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") ? 'd-none' : '' ?>">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-calculator me-2"></i>
                            Documentos com Multiplicador > 1
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica2">
                            <thead class="table-light">
                                <tr>
                                    <th width="140px" class="text-center"><i class="fa fa-calendar"></i> Data Avaliação</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th><i class="fa fa-file-text"></i> Currículo</th>
                                    <th width="120px" class="text-center"><i class="fa fa-times"></i> Multiplicador</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $relacao_docs_nao_avaliados = $conexao->get_multiplicadores_curriculo_maior_1();

                                foreach ($relacao_docs_nao_avaliados as $linha):
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    $data_avaliacao = $linha['data_avaliacao'] ? trata_data($linha['data_avaliacao']) : '-';

                                    // Cor do multiplicador baseado no valor
                                    $multiplicador_class = 'success';
                                    if ($linha['multiplicador'] > 3) {
                                        $multiplicador_class = 'danger';
                                    } elseif ($linha['multiplicador'] > 2) {
                                        $multiplicador_class = 'warning';
                                    }
                                ?>
                                    <tr>
                                        <!-- Data de Avaliação -->
                                        <td class="text-center">
                                            <?= $data_avaliacao ?>
                                        </td>

                                        <!-- Especialidade -->
                                        <td>
                                            <span class="especialidade-text"><?= htmlspecialchars($linha['especialidade']) ?></span>
                                        </td>

                                        <!-- Currículo -->
                                        <td>
                                            <span class="curriculo-text"><?= htmlspecialchars($linha['nome']) ?></span>
                                        </td>

                                        <!-- Multiplicador -->
                                        <td class="text-center">
                                            <span class="multiplicador-badge badge bg-<?= $multiplicador_class ?>">
                                                <?= $linha['multiplicador'] ?>x
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                class="btn btn-sm action-btn"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar candidato">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
            [5, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable({
        "order": [
            [3, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>