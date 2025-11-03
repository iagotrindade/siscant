<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $perfil != 'consulta') {
    erro("Erro 24763457575! Página não encontrada!");
    exit();
}

$lista_candidatos = $conexao->get_candidatos_desc_class();
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Candidatos <i class="fa fa-users"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela de Candidatos Judicial -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-balance-scale me-2"></i>
                            Situação Judicial
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
                                    <th width="120px" class="text-center"><i class="fa fa-info"></i> Status</th>
                                    <th width="120px" class="text-center"><i class="fa fa-list"></i> Ref./Impd.</th>
                                    <th width="120px" class="text-center"><i class="fa fa-file-text"></i> Nº Ação</th>
                                    <th width="120px" class="text-center"><i class="fa fa-calendar"></i> Data Liminar</th>
                                    <th width="120px" class="text-center"><i class="fa fa-gavel"></i> Transitou</th>
                                    <th width="120px" class="text-center"><i class="fa fa-balance-scale"></i> Fav./Desv.</th>
                                    <th width="120px" class="text-center"><i class="fa fa-question-circle"></i> Convocado</th>
                                    <th><i class="fa fa-book"></i> Pub. Bar. Reg.</th>
                                    <th><i class="fa fa-comment"></i> Observação Dist.</th>
                                    <th width="120px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $judicial_count = 0;
                                foreach ($lista_candidatos as $linha):
                                    if ($linha['refratario_impedido'] == null || $linha['refratario_impedido'] == "") continue;
                                    $judicial_count++;

                                    // Status de concorrência
                                    $concorrendo_text = 'Indefinido';
                                    $concorrendo_class = 'primary';
                                    if ($linha['concorrendo'] == '1') {
                                        $concorrendo_text = 'Concorrendo';
                                        $concorrendo_class = 'primary';
                                    } elseif ($linha['concorrendo'] == '0') {
                                        $concorrendo_text = 'Desclassificado';
                                        $concorrendo_class = 'danger';
                                    }

                                    // Status refratário/impedido
                                    $refratario_class = 'warning';
                                    $refratario_text = htmlspecialchars($linha['refratario_impedido']);

                                    // Transitou julgado
                                    $transitou_text = '-';
                                    $transitou_class = 'secondary';
                                    if ($linha['transitou_julgado'] != null) {
                                        if ($linha['transitou_julgado'] == 1) {
                                            $transitou_text = 'Sim';
                                            $transitou_class = 'success';
                                        } elseif ($linha['transitou_julgado'] == 0) {
                                            $transitou_text = 'Não';
                                            $transitou_class = 'danger';
                                        }
                                    }

                                    // Convocado
                                    $convocado_text = '-';
                                    $convocado_class = 'primary';
                                    if ($linha['convocado'] != null) {
                                        if ($linha['convocado'] == 1) {
                                            $convocado_text = 'Sim';
                                            $convocado_class = 'primary';
                                        } elseif ($linha['convocado'] == 0) {
                                            $convocado_text = 'Não';
                                            $convocado_class = 'danger';
                                        }
                                    }

                                    // Favorável/Desfavorável
                                    $favoravel_text = htmlspecialchars($linha['favoravel_desfavoravel'] ?? '-');
                                    $favoravel_class = 'primary';
                                    if (strpos(strtolower($favoravel_text), 'favor') !== false) {
                                        $favoravel_class = 'primary';
                                    } elseif (strpos(strtolower($favoravel_text), 'desfavor') !== false) {
                                        $favoravel_class = 'danger';
                                    }

                                    // Data liminar
                                    $data_liminar = '-';
                                    if ($linha['data_liminar'] != null) {
                                        $data_liminar = trata_data($linha['data_liminar']);
                                    }
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

                                        <!-- Status Concorrência -->
                                        <td class="text-center">
                                            <span class="status-badge badge bg-<?= $concorrendo_class ?>">
                                                <?= $concorrendo_text ?>
                                            </span>
                                        </td>

                                        <!-- Refratário/Impedido -->
                                        <td class="text-center">
                                            <span class="refratario-badge badge bg-<?= $refratario_class ?>">
                                                <?= mb_strtoupper($refratario_text) ?>
                                            </span>
                                        </td>

                                        <!-- Número Ação -->
                                        <td class="text-center">
                                            <span class="numero-acao fw-bold text-primary">
                                                <?= htmlspecialchars($linha['numero_acao'] ?? '-') ?>
                                            </span>
                                        </td>

                                        <!-- Data Liminar -->
                                        <td class="text-center">
                                            <span class="data-liminar"><?= $data_liminar ?></span>
                                        </td>

                                        <!-- Transitou Julgado -->
                                        <td class="text-center">
                                            <span class="transitou-badge badge bg-<?= $transitou_class ?>">
                                                <?= mb_strtoupper($transitou_text) ?>
                                            </span>
                                        </td>

                                        <!-- Favorável/Desfavorável -->
                                        <td class="text-center">
                                            <span class="favoravel-badge badge bg-<?= $favoravel_class ?>">
                                                <?= mb_strtoupper($favoravel_text) ?>
                                            </span>
                                        </td>

                                        <!-- Convocado -->
                                        <td class="text-center">
                                            <span class="convocado-badge badge bg-<?= $convocado_class ?>">
                                                <?= mb_strtoupper($convocado_text) ?>
                                            </span>
                                        </td>

                                        <!-- Publicação Bar Reg -->
                                        <td>
                                            <span class="publicacao-text"><?= htmlspecialchars($linha['publicacao_bar_reg'] ?? '-') ?></span>
                                        </td>

                                        <!-- Observação Distribuição -->
                                        <td>
                                            <span class="observacao-text"><?= htmlspecialchars($linha['observacao_distribuicao'] ?? '-') ?></span>
                                        </td>

                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if ($judicial_count == 0): ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-info-circle fa-2x mb-3"></i>
                                                <p class="mb-0">Nenhum candidato com situação judicial encontrada.</p>
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
            [2, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>