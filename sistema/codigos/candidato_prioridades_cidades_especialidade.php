<?php

if ($selecao_libera_prioridade_candidato != '1') {
    erro("Erro 123462343674! Página não encontrada!");
    exit();
}

$get_prioridade = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

$quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($id_especialidade);
$quantidade_cidades = $quantidade_cidades[0]['quantidade'];
$lista_cidades = $conexao->get_cidades_especialidade_candidato($id_especialidade, $id_candidato_x_especialidade);

?>

<div class="col-12">
    <div class="card shadow-sm">
        <!-- Alertas Informativos -->
        <div class="card-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-map-marker me-2"></i> Priorização de Cidades/Guarnições
            </span>
        </div>

        <div class="card-body">
            <!-- Mensagem de Orientação -->
            <div class="alert alert-warning <?= (!inscricao() || count($lista_cidades) == 0) ? 'd-none' : '' ?>">
                <div style="display: flex; align-items: center;">
                    <i class="fa fa-exclamation-circle fa-lg mr-10 text-warning"></i>
                    <div>
                        <h4 class="alert-heading mb-0">Para liberar a opção de currículo, selecione TODAS as guarnições dentro das suas prioridades!</h4>
                        <p class="mb-0">
                            <em>A priorização das cidades não é garantia de abertura/existência de vagas nas mesmas, porém servirá de base para planejamento de vagas.</em>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Formulário de Cadastro de Prioridade -->
                <div class="col-lg-6 <?= (!inscricao() || count($lista_cidades) == 0) ? 'd-none' : '' ?>">
                    <form method="post" action="../banco_dados/candidato_cadastra_prioridade.php" class="needs-validation" novalidate>
                        <input type="hidden" name="esp" value="<?= $id_especialidade ?>">
                        <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['cpf'] . "freitas") ?>">

                        <div class="card border-0">
                            <div class="card-body">
                                <h4 class="card-title text-primary mb-3">
                                    <i class="fa fa-plus-circle me-2"></i> Adicionar Prioridade
                                </h4>

                                <div class="mb-20">
                                    <label for="id_cidade" class="form-label fw-semibold">Selecione a Cidade</label>
                                    <select name="id_cidade" id="id_cidade" class="form-control" required>
                                        <option value="">Selecione a cidade</option>
                                        <?php foreach ($lista_cidades as $linha): ?>
                                            <option value="<?= $linha['id'] ?>"><?= htmlspecialchars($linha['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-20">
                                    <label for="prioridade" class="form-label fw-semibold">Prioridade</label>
                                    <select name="prioridade" id="prioridade" class="form-control" required>
                                        <?php
                                        $valor = count($get_prioridade) + 1;
                                        if (count($get_prioridade) < $quantidade_cidades) {
                                            echo '<option value="' . $valor . '">Prioridade Nº ' . $valor . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i> Cadastrar Prioridade
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lista de Prioridades Cadastradas -->
                <div class="col-lg-6 <?= (count($get_prioridade) == 0) ? 'd-none' : '' ?>">
                    <div class="card border-0 h-100">
                        <div class="card-body">
                            <h4 class="card-title text-primary mb-3">
                                <i class="fa fa-list-ol me-2"></i> Minhas Prioridades
                            </h4>

                            <?php if (count($get_prioridade) > 0): ?>
                                <div class="prioridades-list">
                                    <?php foreach ($get_prioridade as $linha3): ?>
                                        <div class="prioridade-item d-flex justify-content-between align-items-center mb-10 rounded shadow-sm">
                                            <div class="" style="display: flex; align-items: center;">
                                                <span class="prioridade-badge bg-primary text-white rounded-circle mr-10" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;">
                                                    <?= $linha3['prioridade'] ?>
                                                </span>
                                                <div>
                                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($linha3['nome']) ?></span>
                                                    <br>
                                                    <small class="text-muted"><?= $linha3['prioridade'] ?>ª Prioridade</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Botão Limpar Prioridades -->
                                <?php if (inscricao()): ?>
                                    <div class="mt-4 pt-3 border-top">
                                        <?php $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $id_especialidade); ?>
                                        <button onclick="funcao_apagar('<?= $id_especialidade ?>', 'prioridade_cidade_candidato', '<?= $crip ?>')"
                                            class="btn btn-danger w-100"
                                            data-bs-toggle="tooltip"
                                            title="Remover todas as prioridades cadastradas">
                                            <i class="fa fa-broom me-2"></i>Limpar Todas as Prioridades
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fa fa-map-marker fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Nenhuma prioridade cadastrada</p>
                                    <small class="text-muted">Use o formulário ao lado para adicionar suas prioridades.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Estado Vazio -->
                <?php if ((!inscricao() || count($lista_cidades) == 0) && count($get_prioridade) == 0): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fa fa-map-marker fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Sistema de Priorização</h5>
                            <p class="text-muted mb-0">As opções de priorização estarão disponíveis conforme o andamento do processo seletivo.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>