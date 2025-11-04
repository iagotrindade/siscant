<a name="observacoes"></a>
<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

$lista_observacaoes = $conexao->get_observacoes_candidato($id_usuario);
include_once '../sistema/codigos/funcao_apagar.php';
?>

<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->

<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-sticky-note me-2"></i>
            Observações do Candidato
        </span>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Formulário para Nova Observação -->
            <div class="col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white mb-20">
                        <span class="mb-0">
                            <i class="fa fa-plus-circle me-2"></i>
                            Nova Observação
                        </span>
                    </div>
                    <div class="card-body">
                        <form method="post" action="../banco_dados/candidato_observacao_cadastra.php">
                            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                            <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">

                            <div class="mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-edit me-1"></i>
                                    Escreva uma observação:
                                </label>
                                <textarea name="observacao"
                                    class="form-control"
                                    rows="6"
                                    placeholder="Digite sua observação sobre o candidato..."
                                    required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fa fa-save me-2"></i>
                                CADASTRAR OBSERVAÇÃO
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de Observações Existentes -->
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white mb-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="mb-0">
                                <i class="fa fa-list me-2"></i>
                                Observações Registradas
                            </span>
                            <span class="badge bg-light text-dark">
                                <?= count($lista_observacaoes) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <?php if (count($lista_observacaoes) > 0): ?>
                            <?php foreach ($lista_observacaoes as $linha): ?>
                                <?php
                                // Foto do usuário
                                $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='35' height='35' style='object-fit: cover;'></a>";
                                $get_foto = $conexao->get_foto_usuario($linha['_usuario_ultima_atualizacao']);
                                if (count($get_foto) > 0) {
                                    $foto = $get_foto[0]['nome'];
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['_usuario_ultima_atualizacao'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/$foto' width='35' height='35' style='object-fit: cover;'></a>";
                                }

                                // Informações do usuário
                                $usuario_cadastrou_obs = 'Obs criada pelo sistema';
                                $badge_class = 'bg-warning';
                                if ($linha['sistema'] == 0) {
                                    $usuario_info = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
                                    $usuario_cadastrou_obs = $usuario_info[0]['posto_grad'] . ' ' . $usuario_info[0]['nome_guerra'];
                                    $badge_class = 'bg-primary';
                                }
                                ?>

                                <div class="observacao-item mb-20 p-20 alert alert-success">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge <?= $badge_class ?>">
                                            <i class="fa fa-<?= $linha['sistema'] == 1 ? 'robot' : 'user' ?> me-1"></i>
                                            <?= $linha['sistema'] == 1 ? 'Sistema' : 'Usuário' ?>
                                        </span>

                                        <?php if ($linha['sistema'] == 0): ?>
                                            <button type="button"
                                                onclick="funcao_apagar('<?= $linha['id'] ?>', 'candidato_observacao','<?= $id_usuario ?>')"
                                                class="btn btn-sm action-btn btn-danger"
                                                data-bs-toggle="tooltip"
                                                title="Apagar observação"
                                                style="border-radius: 90px;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <p class="mb-2"><?= htmlspecialchars($linha['observacao']) ?></p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar me-1"></i>
                                            <?= trata_data_hora($linha['_data_ultima_atualizacao']) ?>
                                        </small>
                                        <div class="d-flex align-items-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['_usuario_ultima_atualizacao'] ?>"
                                                class="text-decoration-none mr-10">
                                                <?= $usuario_cadastrou_obs ?>
                                            </a>
                                            <?= $foto ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fa fa-inbox fa-2x mb-3"></i>
                                    <p class="mb-0">Nenhuma observação registrada.</p>
                                    <small>As observações aparecerão aqui quando forem cadastradas.</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>