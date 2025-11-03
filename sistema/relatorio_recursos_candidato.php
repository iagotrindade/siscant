<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'avaliador') {
    erro("Erro 632457437! Página não encontrada!");
    exit();
}

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Recursos <i class="fa fa-file-text"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Recursos</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela de Recursos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-gavel me-2"></i>
                            Recursos dos Candidatos
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="80px" class="text-center"><i class="fa fa-image"></i> Foto</th>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th width="120px" class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th width="120px" class="text-center"><i class="fa fa-calendar"></i> Data Abertura</th>
                                    <th width="120px" class="text-center"><i class="fa fa-check-circle"></i> Status Aval.</th>
                                    <th width="120px" class="text-center"><i class="fa fa-check-circle"></i> Status Final</th>
                                    <th width="100px" class="text-center"><i class="fa fa-question-circle"></i> Avaliador?</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th><i class="fa fa-comments"></i> Justificativa</th>
                                    <th><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $especialidades_avaliador = null;
                                if ($_SESSION['perfil'] == 'avaliador') {
                                    $especialidades_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
                                }

                                $recursos = $conexao->get_recursos();

                                foreach ($recursos as $linha):
                                    $aparece = true;
                                    if ($especialidades_avaliador != null) {
                                        if ($linha['status'] != null) continue;

                                        $aparece = false;
                                        foreach ($especialidades_avaliador as $especialidade) {
                                            if ($linha['id_especialidade'] == $especialidade['id_especialidade'])
                                                $aparece = true;
                                        }
                                    }

                                    if ($aparece == false) continue;
                                    if ($linha['id_selecao'] != $_SESSION['selecao']) continue;

                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id_candidato']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    // Status do avaliador
                                    $avaliador_text = 'Não';
                                    $avaliador_class = 'danger';
                                    if ($linha['para_avaliador'] == 1) {
                                        $avaliador_text = 'Sim';
                                        $avaliador_class = 'primary';
                                    }

                                    // Status da avaliação
                                    $status_text = $linha['status'] ?? 'Em análise';
                                    $status_class = 'warning';
                                    if ($linha['status'] == 'deferido') {
                                        $status_class = 'primary';
                                    } elseif ($linha['status'] == 'indeferido') {
                                        $status_class = 'danger';
                                    } else {
                                        $status_class = 'warning';
                                    }

                                    // Status final
                                    $status_final_text = $linha['status_final'] ?? 'Em análise';
                                    $status_final_class = 'warning';
                                    if ($linha['status_final'] == 'deferido') {
                                        $status_final_class = 'primary';
                                    } elseif ($linha['status_final'] == 'indeferido') {
                                        $status_final_class = 'danger';
                                    } else {
                                        $status_final_class = 'warning';
                                    }

                                    // Cor de fundo para desclassificados
                                    $bg_color = '';
                                    if ($linha['concorrendo'] == 0) {
                                        $bg_color = 'bg-danger text-white';
                                    }
                                ?>
                                    <tr class="<?= $bg_color ?>">
                                        <!-- Foto -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id_candidato'] ?>"
                                                class="d-inline-block"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar candidato">
                                                <img class="img-circle rounded-circle border"
                                                    src="fotos/<?= $foto ?>"
                                                    width="40"
                                                    height="40"
                                                    style="object-fit: cover;"
                                                    alt="Foto do candidato">
                                            </a>
                                        </td>

                                        <!-- CPF -->
                                        <td>
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id_candidato'] ?>" class="text-decoration-none">
                                                <?= $linha['cpf'] ?>
                                            </a>
                                        </td>

                                        <!-- Nome -->
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                        </td>

                                        <!-- Etapa -->
                                        <td class="text-center">
                                            _<?= htmlspecialchars($linha['etapa']) ?>
                                        </td>

                                        <!-- Data de Abertura -->
                                        <td class="text-center">
                                            <span class="data-text"><?= trata_data($linha['data_abertura']) ?></span>
                                        </td>

                                        <!-- Status Avaliação -->
                                        <td class="text-center">
                                            <span class="status-badge badge bg-<?= $status_class ?>">
                                                <?= mb_strtoupper($status_text) ?>
                                            </span>
                                        </td>

                                        <!-- Status Final -->
                                        <td class="text-center">
                                            <span class="status-final-badge badge bg-<?= $status_final_class ?>">
                                                <?= mb_strtoupper($status_final_text) ?>
                                            </span>
                                        </td>

                                        <!-- Avaliador -->
                                        <td class="text-center">
                                            <span class="avaliador-badge badge bg-<?= $avaliador_class ?>">
                                                <?= mb_strtoupper($avaliador_text) ?>
                                            </span>
                                        </td>

                                        <!-- Especialidade -->
                                        <td>
                                            <span class="especialidade-text"><?= htmlspecialchars($linha['nome_especialidade']) ?></span>
                                        </td>

                                        <!-- Justificativa -->
                                        <td>
                                            <span class="justificativa-text small"><?= htmlspecialchars($linha['paragrafo1'] . $linha['paragrafo2']) ?></span>
                                        </td>

                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id_candidato'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
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
    $('#tabela_dinamica').DataTable();
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>