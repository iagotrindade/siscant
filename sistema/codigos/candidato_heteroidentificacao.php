<?php
// 17/06/2025 -> Iago Silva Criação da área de Heteroidentificação
$pareceres = $conexao->get_pareceres_heteroidentificacao($id_usuario);

$pareceresFase1 = array_filter($pareceres, function ($parecer) {
    return $parecer['fase'] == 1;
});

$pareceresFase2 = array_filter($pareceres, function ($parecer) {
    return $parecer['fase'] == 2;
});

$totalFase1 = count($pareceresFase1);
$totalFase2 = count($pareceresFase2);

// Se já houver 5 pareceres da fase 1, bloqueia
$bloquearInclusao = $totalFase1 >= 5;
$bloquearInclusaoRevisora = $totalFase2 >= 3;

// Verificar se o usuário logado possui alguma análise feita no candidato se sim, bloqueia a inserção
if (in_array($_SESSION['id_usuario'], array_column($pareceresFase1, 'id_avaliador'))) {
    $bloquearInclusao = true;
}

if (in_array($_SESSION['id_usuario'], array_column($pareceresFase2, 'id_avaliador'))) {
    $bloquearInclusaoRevisora = true;
}

?>
<a name='heteroidentificacao'></a>
<div <?= $_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr' && $_SESSION['perfil'] != 'consulta' ? 'hidden' : '' ?>>
    <!-- Comissão Heteroidentificação -->
    <div class="card dashboard-card mb-4">
        <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-users me-2"></i>
                Comissão de Heteroidentificação

                <span class="badge bg-primary me-3">
                    <i class="fa fa-file-alt me-1"></i>
                    Pareceres: <?= count($pareceresFase1) ?>
                </span>
            </span>
            <div class="d-flex align-items-center">
                <a href="mpdf/relatorio_heteroidentificacao_candidato_eipot.php?id=<?= $id_usuario ?>&fase=1"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="tooltip"
                    title="Baixar Relatório de Pareceres">
                    <i class="fa fa-file-pdf me-1"></i>GERAR ATA
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Lista de Pareceres com Collapse -->
            <div class="accordion" id="accordionHetero">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <a data-toggle="collapse" href="#hetero">
                            <i class="fa fa-eye me-2"></i>
                            Mostrar/Esconder Pareceres Comissão Heteroidentificação (<?= count($pareceresFase1) ?>)
                        </a>
                    </h3>
                    <div id="hetero" class="accordion-collapse collapse" data-bs-parent="#hetero">
                        <div class="accordion-body">
                            <?php $contadorHetero = 0; ?>
                            <?php foreach ($pareceresFase1 as $parecer): ?>
                                <?php $contadorHetero++; ?>
                                <div class="parecer-item mb-20 p-3 border rounded">
                                    <?php if ($parecer['id_avaliador'] == $_SESSION['id_usuario']): ?>
                                        <!-- Formulário Edição (próprio avaliador) -->
                                        <form action="../banco_dados/candidato_edita_heteroidentificacao.php" method="post">
                                            <input type="hidden" name="id_parecer" value="<?= $parecer['id'] ?>">
                                            <input type="hidden" name="id_avaliador" value="<?= $parecer['id_avaliador'] ?>">
                                            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                                            <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                                            <input type="hidden" name="fase" value="1">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="text-primary">
                                                        <i class="fa fa-check me-2"></i>
                                                        Parecer Nº <?= $contadorHetero ?> (Seu Parecer)
                                                        <span class="badge bg-warning">
                                                            <i class="fa fa-edit me-1"></i>Editável
                                                        </span>
                                                    </h5>
                                                    <p class="text-muted mb-20">
                                                        <i class="fa fa-calendar me-1"></i>
                                                        Análise realizada em <?= trata_data_hora($parecer['data_avaliacao']) ?>
                                                        por <?= $parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador'] ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-20">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-edit me-1"></i>
                                                        Justificativa do Parecer
                                                    </label>
                                                    <textarea name="justificativa" class="form-control" rows="4" required><?= htmlspecialchars($parecer['justificativa'] ?? '') ?></textarea>
                                                </div>

                                                <div class="col-md-12 mb-20">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-balance-scale me-1"></i>
                                                        Resultado da Autodeclaração
                                                    </label>
                                                    <select name="parecer" class="form-control" required>
                                                        <option value="">Selecione uma opção</option>
                                                        <option value="confirmada" <?= $parecer['parecer'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                        <option value="nao_confirmada" <?= $parecer['parecer'] == 'nao_confirmada' ? 'selected' : '' ?>>Não confirmada</option>
                                                        <option value="nao_compareceu" <?= $parecer['parecer'] == 'nao_compareceu' ? 'selected' : '' ?>>Não compareceu</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fa fa-save me-2"></i>
                                                        ATUALIZAR PARECER
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <!-- Visualização (outros avaliadores) -->
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="text-secondary">
                                                    <i class="fa fa-user me-2"></i>
                                                    Parecer Nº <?= $contadorHetero ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fa fa-eye me-1"></i> Somente leitura
                                                    </span>
                                                </h5>
                                                <p class="text-muted mb-20">
                                                    <i class="fa fa-calendar me-1"></i>
                                                    Análise realizada em <?= trata_data_hora($parecer['data_avaliacao']) ?>
                                                    por <?= $parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador'] ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-20">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa fa-edit me-1"></i>
                                                    Justificativa do Parecer
                                                </label>
                                                <textarea class="form-control" rows="4" disabled><?= htmlspecialchars($parecer['justificativa'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-12 mb-20">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa fa-balance-scale me-1"></i>
                                                    Resultado da Autodeclaração
                                                </label>
                                                <?php
                                                $resultado_text = '';
                                                if ($parecer['parecer'] == 'confirmada') $resultado_text = 'Confirmada';
                                                elseif ($parecer['parecer'] == 'nao_confirmada') $resultado_text = 'Não Confirmada';
                                                elseif ($parecer['parecer'] == 'nao_compareceu') $resultado_text = 'Não compareceu';

                                                $resultado_class = 'danger';
                                                if ($parecer['parecer'] == 'confirmada') $resultado_class = 'primary';
                                                elseif ($parecer['parecer'] == 'nao_confirmada') $resultado_class = 'danger';
                                                elseif ($parecer['parecer'] == 'nao_compareceu') $resultado_class = 'warning';
                                                ?>
                                                <div class="form-control" disabled>
                                                    <span class="badge bg-<?= $resultado_class ?> fs-6"><?= $resultado_text ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($contadorHetero < count($pareceresFase1)): ?>
                                    <hr class="my-4">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultado Final -->
            <?php if ($totalFase1 >= 5): ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-bar-chart fa-2x mr-10"></i>
                                <div>
                                    <h5 class="mb-0">Resultado Final da Comissão</h5>
                                    <p class="mb-0 fs-5 fw-bold"><?= get_parecer_final_heteroidentificacao($id_usuario, $pareceres, 1) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulário Inserir Novo Parecer -->
            <?php if (!$bloquearInclusao && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'chc')): ?>
                <div class="card border-primary mt-4">
                    <div class="card-header bg-primary text-white mb-20">
                        <span class="mb-0">
                            <i class="fa fa-plus-circle me-2"></i>
                            Inserir Novo Parecer
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="../banco_dados/candidato_heteroidentificacao.php" method="post">
                            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                            <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                            <input type="hidden" name="fase" value="1">

                            <div class="row">
                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-edit me-1"></i>
                                        Justificativa do Parecer
                                    </label>
                                    <textarea name="justificativa" class="form-control" rows="4" placeholder="Digite a justificativa para seu parecer aqui..." required></textarea>
                                </div>

                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-balance-scale me-1"></i>
                                        Resultado da Autodeclaração
                                    </label>
                                    <select name="parecer" class="form-control" required>
                                        <option value="">Selecione uma opção</option>
                                        <option value="confirmada">Confirmada</option>
                                        <option value="nao_confirmada">Não confirmada</option>
                                        <option value="nao_compareceu">Não compareceu</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="fa fa-save me-2"></i>
                                        SALVAR PARECER
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Comissão Revisora (estrutura similar à acima) -->
    <div class="card dashboard-card mb-20">
        <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-user-shield me-2"></i>
                Comissão Revisora
            </span>

            <span class="badge bg-primary me-3">
                <i class="fa fa-file-alt me-1"></i>
                Pareceres: <?= count($pareceresFase2) ?>
            </span>
            <div class="d-flex align-items-center">
                <a href="mpdf/relatorio_heteroidentificacao_candidato_eipot.php?id=<?= $id_usuario ?>&fase=2"
                    class="btn btn-sm btn-success"
                    data-bs-toggle="tooltip"
                    title="Baixar Relatório de Pareceres Revisores">
                    <i class="fa fa-file-pdf me-1"></i>GERAR ATA
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Lista de Pareceres Revisores com Collapse -->
            <div class="accordion" id="accordionRevisora">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <a data-toggle="collapse" href="#heteroRevisora">
                            <i class="fa fa-eye me-2"></i>
                            Mostrar/Esconder Pareceres Comissão Revisora (<?= count($pareceresFase2) ?>)
                        </a>
                    </h3>
                    <div id="heteroRevisora" class="accordion-collapse collapse" data-bs-parent="#heteroRevisora">
                        <div class="accordion-body">
                            <?php $contadorRevisora = 0; ?>
                            <?php foreach ($pareceresFase2 as $parecer): ?>
                                <?php $contadorRevisora++; ?>
                                <div class="parecer-item mb-4 p-3 border rounded">
                                    <?php if ($parecer['id_avaliador'] == $_SESSION['id_usuario']): ?>
                                        <!-- Formulário Edição (próprio avaliador) -->
                                        <form action="../banco_dados/candidato_edita_heteroidentificacao.php" method="post">
                                            <input type="hidden" name="id_parecer" value="<?= $parecer['id'] ?>">
                                            <input type="hidden" name="id_avaliador" value="<?= $parecer['id_avaliador'] ?>">
                                            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                                            <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                                            <input type="hidden" name="fase" value="2">

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h5 class="text-primary">
                                                        <i class="fa fa-user-shield me-2"></i>
                                                        Parecer Revisor Nº <?= $contadorRevisora ?> (Seu Parecer)
                                                        <span class="badge bg-warning">
                                                            <i class="fa fa-edit me-1"></i>Editável
                                                        </span>
                                                    </h5>
                                                    <p class="text-muted mb-20">
                                                        <i class="fa fa-calendar me-1"></i>
                                                        Análise realizada em <?= trata_data_hora($parecer['data_avaliacao']) ?>
                                                        por <?= $parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador'] ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-20">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-edit me-1"></i>
                                                        Justificativa do Parecer Revisor
                                                    </label>
                                                    <textarea name="justificativa" class="form-control" rows="4" required><?= htmlspecialchars($parecer['justificativa'] ?? '') ?></textarea>
                                                </div>

                                                <div class="col-md-12 mb-20">
                                                    <label class="form-label fw-semibold">
                                                        <i class="fa fa-balance-scale me-1"></i>
                                                        Resultado da Autodeclaração
                                                    </label>
                                                    <select name="parecer" class="form-control" required>
                                                        <option value="">Selecione uma opção</option>
                                                        <option value="confirmada" <?= $parecer['parecer'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                        <option value="nao_confirmada" <?= $parecer['parecer'] == 'nao_confirmada' ? 'selected' : '' ?>>Não confirmada</option>
                                                        <option value="nao_compareceu" <?= $parecer['parecer'] == 'nao_compareceu' ? 'selected' : '' ?>>Não compareceu</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fa fa-save me-2"></i>
                                                        ATUALIZAR PARECER REVISOR
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <!-- Visualização (outros avaliadores) -->
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="text-secondary">
                                                    <i class="fa fa-user-shield me-2"></i>
                                                    Parecer Revisor Nº <?= $contadorRevisora ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fa fa-eye me-1"></i> Somente leitura
                                                    </span>
                                                </h5>
                                                <p class="text-muted mb-20">
                                                    <i class="fa fa-calendar me-1"></i>
                                                    Análise realizada em <?= trata_data_hora($parecer['data_avaliacao']) ?>
                                                    por <?= $parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador'] ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-20">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa fa-edit me-1"></i>
                                                    Justificativa do Parecer Revisor
                                                </label>
                                                <textarea class="form-control" rows="4" disabled><?= htmlspecialchars($parecer['justificativa'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa fa-balance-scale me-1"></i>
                                                    Resultado da Autodeclaração
                                                </label>
                                                <?php
                                                $resultado_text = '';
                                                if ($parecer['parecer'] == 'confirmada') $resultado_text = 'Confirmada';
                                                elseif ($parecer['parecer'] == 'nao_confirmada') $resultado_text = 'Não Confirmada';
                                                elseif ($parecer['parecer'] == 'nao_compareceu') $resultado_text = 'Não compareceu';

                                                $resultado_class = 'secondary';
                                                if ($parecer['parecer'] == 'confirmada') $resultado_class = 'success';
                                                elseif ($parecer['parecer'] == 'nao_confirmada') $resultado_class = 'danger';
                                                elseif ($parecer['parecer'] == 'nao_compareceu') $resultado_class = 'warning';
                                                ?>
                                                <div class="form-control" disabled>
                                                    <span class="badge bg-<?= $resultado_class ?> fs-6"><?= $resultado_text ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($contadorRevisora < count($pareceresFase2)): ?>
                                    <hr class="my-4">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultado Final Revisora -->
            <?php if ($totalFase2 >= 3): ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-bar-chart fa-2x mr-10"></i>
                                <div>
                                    <h5 class="mb-0">Resultado Final da Comissão Revisora</h5>
                                    <p class="mb-0 fs-5 fw-bold"><?= get_parecer_final_heteroidentificacao($id_usuario, $pareceres, 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulário Inserir Novo Parecer Revisor -->
            <?php if (!$bloquearInclusaoRevisora && ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'cr')): ?>
                <div class="card border-warning mt-4">
                    <div class="card-header bg-warning text-dark mb-20">
                        <span class="mb-0">
                            <i class="fa fa-plus-circle me-2"></i>
                            Inserir Novo Parecer Revisor
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="../banco_dados/candidato_heteroidentificacao.php" method="post">
                            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                            <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                            <input type="hidden" name="fase" value="2">

                            <div class="row">
                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-edit me-1"></i>
                                        Justificativa do Parecer Revisor
                                    </label>
                                    <textarea name="justificativa" class="form-control" rows="4" placeholder="Digite a justificativa para seu parecer revisor aqui..." required></textarea>
                                </div>

                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-balance-scale me-1"></i>
                                        Resultado da Autodeclaração
                                    </label>
                                    <select name="parecer" class="form-control" required>
                                        <option value="">Selecione uma opção</option>
                                        <option value="confirmada">Confirmada</option>
                                        <option value="nao_confirmada">Não confirmada</option>
                                        <option value="nao_compareceu">Não compareceu</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100 py-2 text-dark">
                                        <i class="fa fa-save me-2"></i>
                                        SALVAR PARECER REVISOR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<!-- Bootstrap 3 JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>