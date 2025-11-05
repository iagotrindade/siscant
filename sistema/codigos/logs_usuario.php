<a name="auditoria"></a>
<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 2353567545! Página não encontrada!");
    exit();
}
?>

<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-history me-2"></i>
            Auditoria do Usuário
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="tabela_dinamica">
                <thead class="table-light">
                    <tr>
                        <th width="80px" class="text-center"><i class="fa fa-hashtag"></i> ID</th>
                        <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                        <th width="120px" class="text-center"><i class="fa fa-cog"></i> Operação</th>
                        <th width="150px"><i class="fa fa-list"></i> Tabela</th>
                        <th><i class="fa fa-code"></i> Alteração</th>
                        <th width="160px" class="text-center"><i class="fa fa-calendar"></i> Data/Hora</th>
                        <th width="120px" class="text-center"><i class="fa fa-tv"></i> Sistema</th>
                        <th width="140px" class="text-center"><i class="fa fa-server"></i> IP</th>
                        <th width="100px" class="text-center"><i class="fa fa-code"></i> Código</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lista_logs = $conexao->get_logs_usuario($id_usuario);
                    foreach ($lista_logs as $linha):
                        // Define badge color based on operation type
                        $operacao_class = 'primary';
                        if ($linha['operacao'] == 'Insert') $operacao_class = 'primary';
                        if ($linha['operacao'] == 'Update') $operacao_class = 'warning';
                        if ($linha['operacao'] == 'Delete') $operacao_class = 'danger';
                    ?>
                        <tr>
                            <!-- ID -->
                            <td class="text-center">
                                <span class="fw-medium text-muted">#<?= $linha['id'] ?></span>
                            </td>

                            <!-- CPF -->
                            <td>
                                <code class="text-dark"><?= $linha['cpf'] ?></code>
                            </td>

                            <!-- Operação -->
                            <td class="text-center">
                                <span class="badge bg-<?= $operacao_class ?>">
                                    <?= mb_strtoupper($linha['operacao']) ?>
                                </span>
                            </td>

                            <!-- Tabela -->
                            <td>
                                <span class="font-monospace text-primary">tb_<?= $linha['tabela'] ?></span>
                            </td>

                            <!-- Alteração -->
                            <td>
                                <span class="alteracao-text"><?= htmlspecialchars($linha['alteracao']) ?></span>
                            </td>

                            <!-- Data/Hora -->
                            <td class="text-center">
                                <?= trata_data_hora($linha['data']) ?>
                            </td>

                            <!-- Sistema -->
                            <td class="text-center">
                                <span class="badge bg-primary text-dark"><?= $linha['sistema'] ?></span>
                            </td>

                            <!-- IP -->
                            <td class="text-center">
                                <?= $linha['ip'] ?>
                            </td>

                            <!-- Código -->
                            <td class="text-center">
                                <span class="fw-medium text-dark"><?= $linha['codigo'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($lista_logs)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fa fa-inbox fa-2x mb-2 opacity-50"></i><br>
                                    Nenhum registro de auditoria encontrado
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>