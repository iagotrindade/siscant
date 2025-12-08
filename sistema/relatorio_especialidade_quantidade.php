<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'documentos' || $perfil == 'ouvidor') {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$avaliador = false;
if ($_SESSION['perfil'] == "avaliador") {
    $avaliador = true;
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
}
?>
<style>
    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #006400 0%, #008000 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 12px 15px;
        font-size: 1.3rem;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 1.4rem;
    }

    .table td a:hover {
        background-color: #006400;
        color: white;
    }

    .table td i {
        font-size: 2rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.03);
    }

    .badge-category {
        background-color: var(--primary-color);
        font-size: 1.4rem;
        padding: 0.35em 0.65em;
    }

    .badge-vagas {
        display: inline-flex;
        align-items: center;
        font-size: 1.4rem;
        padding: 0.4em 0.8em;
    }

    .badge-available {
        background-color: var(--primary-color);
        color: white;
    }

    .badge-filled {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-partial {
        background-color: #fd7e14;
        color: white;
    }

    .badge-empty {
        background-color: #dc3545;
        color: white;
    }

    .progress-container {
        position: relative;
        margin-top: 20px;
    }

    .progress-text {
        position: absolute;
        top: -20px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 600;
        color: #495057;
    }

    .progress {
        height: 10px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
        background-color: var(--primary-color);
    }

    .especialidades-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.04);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .especialidades-list {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .classification-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .classification-header {
        background: linear-gradient(135deg, #006400 0%, #008000 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .classification-table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6c757d;
        background-color: #f8f9fa;
        vertical-align: middle;
    }

    .multiple-especialidade {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }

    .position-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #006400, #008000);
        color: white;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .candidate-photo {
        border: 2px solid #e9ecef;
    }

    .candidate-name {
        font-size: 0.95rem;
        color: #2d3748;
    }

    .cpf-code {
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #495057;
    }

    .score-badge {
        display: inline-block;
        background: #006400;
        color: white;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.9rem;
        min-width: 60px;
    }

    .note-badge {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
        min-width: 40px;
    }

    .military-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #6c757d;
        color: white;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .days-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .etapa-badge {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        color: white;
    }

    .etapa-1 {
        background: #dc3545;
    }

    .etapa-2 {
        background: #fd7e14;
    }

    .etapa-3 {
        background: #ffc107;
        color: #212529;
    }

    .etapa-4 {
        background: #20c997;
    }

    .etapa-5 {
        background: #0d6efd;
    }

    .city-badge {
        background: #f8f9fa;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #495057;
    }

    .autodeclaracao-badge {
        background: #fff3cd;
        color: #856404;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: capitalize;
    }

    .view-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .military-codes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

    .military-code-item {
        font-size: 0.85rem;
        color: #495057;
    }

    .military-code-separator {
        color: #6c757d;
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .classification-table {
            font-size: 0.8rem;
        }

        .military-codes {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .military-code-separator {
            display: none;
        }
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatório de Especialidade X Número de Candidatos <i class="fa fa-file-text"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Especialidade X Candidatos</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Card de Especialidades e Candidatos -->
            <div class="card dashboard-card">
                <div class="card-header dashboard-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-bar-chart"></i>
                        Especialidades X Candidatos
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="100px"><i class="fa fa-tag"></i> Categoria</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th width="120px"><i class="fa fa-users"></i> Total Inscritos</th>
                                    <th width="100px"><i class="fa fa-percent"></i> Ampla</th>
                                    <th width="100px"><i class="fa fa-percent"></i> Cotas</th>
                                    <th width="120px"><i class="fa fa-percent"></i> Classificados</th>
                                    <th width="140px"><i class="fa fa-map-marker"></i> Vagas Disponíveis</th>
                                    <th width="140px"><i class="fa fa-map-marker"></i> Vagas Restantes</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_especialidade = $conexao->get_especialidade();

                                if ($avaliador) {
                                    foreach ($lista_especialidade_avaliador as $linha_avaliador) {
                                        $quantidade_candidatos = 0;
                                        $get_quantidade = $conexao->get_quantidade_candidatos_especialidade($linha_avaliador['id_especialidade']);
                                        if (count($get_quantidade) > 0)
                                            $quantidade_candidatos = $get_quantidade[0]['quantidade_candidatos'];
                                ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-category"><?= mb_strtoupper($linha_avaliador['ott_stt']) ?></span>
                                            </td>
                                            <td class="fw-semibold"><?= htmlspecialchars($linha_avaliador['nome']) ?></td>
                                            <td>
                                                <span class="fw-semibold"><?= $quantidade_candidatos ?></span>
                                            </td>
                                            <td colspan="5" class="text-muted text-center">Visão limitada para avaliador</td>
                                            <td class="text-center">
                                                <a href="relatorio_especialidade_candidato.php?id_especialidade=<?= $linha_avaliador['id_especialidade'] ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Visualizar detalhes">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    foreach ($lista_especialidade as $linha) {
                                        $lista_vagas_disponibilizadas = $conexao->get_cidades_especialidade($linha['id']);
                                        $lista_vagas_restantes = $conexao->get_vagas_especialidade($linha['id']);
                                        $lista_quantidade_cadastrados = $conexao->get_candidatos_especialidade_desclassificados_nao_med_obr($linha['id']);

                                        $quantidade_cadastrados = count($lista_quantidade_cadastrados);
                                        $qtdAmpla = 0;
                                        $qtdCotas = 0;

                                        foreach ($lista_quantidade_cadastrados as $candidato) {
                                            if ($candidato['vaga_reservada']) {
                                                $qtdCotas++;
                                            } else {
                                                $qtdAmpla++;
                                            }
                                        }

                                        $vagas_disponibilizadas = 0;
                                        $vagas_restantes = 0;
                                        foreach ($lista_vagas_disponibilizadas as $linha5) {
                                            $vagas_disponibilizadas += (int)$linha5['numero_vagas'];
                                        }
                                        foreach ($lista_vagas_restantes as $linha8) {
                                            $vagas_restantes += (int)$linha8['vagas'];
                                        }

                                        $quantidade_candidatos = 0;
                                        $get_quantidade = $conexao->get_quantidade_candidatos_especialidade($linha['id']);
                                        if (count($get_quantidade) > 0)
                                            $quantidade_candidatos = $get_quantidade[0]['quantidade_candidatos'];
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-category"><?= mb_strtoupper($linha['ott_stt']) ?></span>
                                            </td>
                                            <td class="fw-semibold"><?= htmlspecialchars($linha['nome']) ?></td>
                                            <td>
                                                <span class="fw-semibold"><?= $quantidade_cadastrados ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= $qtdAmpla ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= $qtdCotas ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= $quantidade_candidatos ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= $vagas_disponibilizadas ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                // Calcular porcentagem de vagas preenchidas
                                                $porcentagem_preenchida = 0;
                                                $vagas_preenchidas = 0;
                                                if ($vagas_disponibilizadas > 0) {
                                                    $vagas_preenchidas = $vagas_disponibilizadas - $vagas_restantes;
                                                    $porcentagem_preenchida = ($vagas_preenchidas / $vagas_disponibilizadas) * 100;
                                                }

                                                // Definir cor baseada na porcentagem
                                                $cor_barra = 'bg-danger'; // Verde
                                                if ($porcentagem_preenchida >= 100) {
                                                    $cor_barra = 'bg-success'; // Verde - totalmente preenchido
                                                } elseif ($porcentagem_preenchida >= 50) {
                                                    $cor_barra = 'bg-warning'; // Amarelo - 75% ou mais
                                                } elseif ($porcentagem_preenchida >= 25) {
                                                    $cor_barra = 'bg-danger'; // Vermelho - 25% ou mais
                                                }
                                                ?>

                                                <div class="progress-container">
                                                    <div class="progress-text"><?= $vagas_restantes ?></div>
                                                    <div class="progress">
                                                        <div class="progress-bar <?= $cor_barra ?>"
                                                            title="<?= number_format($porcentagem_preenchida, 1) ?>% preenchido (<?= $vagas_preenchidas ?? 0 ?>/<?= $vagas_disponibilizadas ?>)"
                                                            role="progressbar"
                                                            style="width: <?= min($porcentagem_preenchida, 100) ?>%"
                                                            aria-valuenow="<?= $porcentagem_preenchida ?>"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="relatorio_especialidade_candidato.php?id_especialidade=<?= $linha['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Visualizar detalhes">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card de Avaliação de Currículos -->
            <div class="card dashboard-card mt-4 <?= ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") ? 'd-none' : '' ?>">
                <div class="card-header dashboard-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-graduation-cap"></i>
                        Avaliação de Currículos
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica2">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th width="120px"><i class="fa fa-check"></i> Avaliados</th>
                                    <th width="140px"><i class="fa fa-times"></i> Não Avaliados</th>
                                    <th width="100px"><i class="fa fa-list"></i> Total</th>
                                    <th width="140px"><i class="fa fa-line-chart"></i> Progresso</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $relacao_docs_nao_avaliados = $conexao->get_lista_docs_avaliados_por_especialidade();

                                foreach ($relacao_docs_nao_avaliados as $linha) {
                                    $nome = $linha['nome_tab1'] ?? $linha['nome_tab2'];
                                    $id = $linha['id_tab1'] ?? $linha['id_tab2'];

                                    $total = (int)$linha['quantidade_avaliado'] + (int)$linha['quantidade_nao_avaliado'];
                                    $porcentagem = $total > 0 ? ((int)$linha['quantidade_avaliado'] / $total) * 100 : 0;

                                    // Status do progresso
                                    $progress_class = 'danger';
                                    if ($porcentagem >= 100) $progress_class = 'success';
                                    elseif ($porcentagem >= 60) $progress_class = 'warning';
                                    elseif ($porcentagem >= 30) $progress_class = 'info';
                                ?>
                                    <tr>
                                        <td class="fw-semibold"><?= htmlspecialchars($nome) ?></td>
                                        <td>
                                            <span class="fw-semibold"><?= (int)$linha['quantidade_avaliado'] ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold"><?= (int)$linha['quantidade_nao_avaliado'] ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold"><?= $total ?></span>
                                        </td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-text"><?= round($porcentagem, 1) ?>%</div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary"
                                                        role="progressbar"
                                                        style="width: <?= $porcentagem ?>%"
                                                        aria-valuenow="<?= $porcentagem ?>"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="relatorio_especialidade_candidato.php?id_especialidade=<?= $id ?>"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar avaliações">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card de Candidatos com Múltiplas Especialidades -->
            <div class="card dashboard-card mt-4 <?= (($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") || $_SESSION['selecao_codigo'] == 'mfdv') ? 'd-none' : '' ?>">
                <div class="card-header dashboard-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-users me-2"></i>
                        Candidatos com Múltiplas Especialidades
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica4">
                            <thead class="table-light">
                                <tr>
                                    <th width="80px"><i class="fa fa-list"></i> Qtd</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-tags"></i> Especialidades Cadastradas</th>
                                    <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $relacao_docs_nao_avaliados = $conexao->get_candidatos_mais_uma_especialidade();

                                foreach ($relacao_docs_nao_avaliados as $linha) {
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    $especialidades_cadastradas = "";
                                    $especialidades_do_candidato = $conexao->get_especialidade_candidato($linha['id']);

                                    $tem_ott = false;
                                    $tem_stt = false;

                                    foreach ($especialidades_do_candidato as $esp) {
                                        if ($esp['ott_stt'] == 'ott') $tem_ott = true;
                                        if ($esp['ott_stt'] == 'stt') $tem_stt = true;
                                        $especialidades_cadastradas .= '<span class="badge text-dark me-1 mb-1" style="background-color: var(--primary-color);">' . mb_strtoupper($esp['ott_stt']) . ' - ' . htmlspecialchars($esp['especialidade']) . '</span>';
                                    }

                                    if ($tem_ott && $tem_stt) {
                                ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold"><?= $linha['quantidade_especialidades'] ?></span>
                                            </td>
                                            <td class="fw-semibold"><?= htmlspecialchars($linha['nome_completo']) ?></td>
                                            <td>
                                                <?= $linha['cpf'] ?>
                                            </td>
                                            <td>
                                                <div class="especialidades-list">
                                                    <?= $especialidades_cadastradas ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                    data-bs-toggle="tooltip"
                                                    title="Visualizar candidato">
                                                    <img src="fotos/<?= $foto ?>"
                                                        class="img-circle rounded-circle"
                                                        width="45"
                                                        height="45"
                                                        alt="Foto">
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
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
    $('#tabela_dinamica').DataTable();
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable({
        "order": [
            [0, "asc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica3').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica4').DataTable({
        "order": [
            [1, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>