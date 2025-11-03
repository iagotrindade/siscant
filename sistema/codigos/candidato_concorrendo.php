<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos' && $_SESSION['perfil'] != 'avaliador') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

?>
<a name="concorrendo"></a>
<div class="card dashboard-card mb-20" <?= $_SESSION['perfil'] != 'admin' ? 'hidden' : '' ?>>
    <div class="card-header dashboard-header mb-20">
        <div class="d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-trophy me-2"></i>
                Classificado/Desclassificado
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Coluna Esquerda - Status de Concorrência -->
            <div class="col-md-7">
                <div class="mb-20">
                    <?php
                    $foto = null;
                    $get_foto_usuario_alterou_concorrendo = $conexao->get_foto_usuario($id_usuario_alterou_concorrendo);

                    if (count($get_foto_usuario_alterou_concorrendo) > 0) {
                        $foto = $get_foto_usuario_alterou_concorrendo[0]['nome'];
                        $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/$foto' width='40' height='40' style='object-fit: cover;'></a>";
                    } else {
                        $foto_html = "<a href='usuario_visualiza.php?id_usuario=" . $id_usuario_alterou_concorrendo . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar usuário'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='40' height='40' style='object-fit: cover;'></a>";
                    }
                    ?>

                    <!-- Alertas de Status -->
                    <?php if (!$concorrendo): ?>
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fa fa-times-circle fa-2x mr-10"></i>
                            <div class="flex-grow-1 mr-10">
                                <strong class="fs-5">ELIMINADO!</strong><br>
                                <span class="d-block">Este candidato NÃO está concorrendo no processo seletivo!</span>
                                <?php if ($justificativa_concorrendo): ?>
                                    <div class="mt-10">
                                        <strong>Justificativa:</strong><br>
                                        <span class="text-muted"><?= htmlspecialchars($justificativa_concorrendo) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?= $foto_html ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($concorrendo): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fa fa-check-circle fa-2x mr-10"></i>
                            <div class="flex-grow-1 mr-10">
                                <strong class="fs-5">Candidato concorrendo no processo seletivo!</strong>
                                <?php if ($justificativa_concorrendo): ?>
                                    <div class="mt-2">
                                        <strong>Justificativa:</strong><br>
                                        <span class="text-muted"><?= htmlspecialchars($justificativa_concorrendo) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ms-3">
                                <?= $foto_html ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Formulário de Concorrência -->
                <div class="border rounded p-3 bg-light">
                    <form action="../banco_dados/candidato_concorrendo.php" method="post">
                        <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                        <div class="mb-20">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="concorrendo" id="concorrendo" <?= $concorrendo ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="concorrendo">
                                    Candidato concorrendo no processo seletivo
                                </label>
                            </div>
                        </div>

                        <div class="mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-edit me-1"></i>
                                Justificativa da mudança
                            </label>
                            <small class="text-muted d-block mb-2">
                                <i class="fa fa-info-circle me-1"></i>
                                Será adicionada uma observação automática
                            </small>
                            <textarea name="justificativa" class="form-control" rows="3" placeholder="Digite a justificativa para a mudança de status..."></textarea>
                        </div>

                        <div <?= $_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != 'documentos' ? 'hidden' : '' ?>>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fa fa-save me-2"></i>
                                SALVAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Coluna Direita - Etapa do Candidato -->
            <div class="col-md-5">
                <div class="border rounded p-3 bg-light" <?= $_SESSION['perfil'] != 'admin' ? 'hidden' : '' ?>>
                    <form action="../banco_dados/candidato_etapa_atualiza.php" method="post">
                        <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas") ?>">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                        <div class="mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-list-ol me-1"></i>
                                Etapa do Candidato
                            </label>
                            <select name="etapa_candidato" class="form-control" required>
                                <option value="">Selecione a etapa</option>
                                <option value="1" <?= $etapa == 1 ? 'selected' : '' ?>>Etapa I</option>
                                <option value="2" <?= $etapa == 2 ? 'selected' : '' ?>>Etapa II</option>
                                <option value="3" <?= $etapa == 3 ? 'selected' : '' ?>>Etapa III</option>
                                <option value="4" <?= $etapa == 4 ? 'selected' : '' ?>>Etapa IV</option>
                                <option value="5" <?= $etapa == 5 ? 'selected' : '' ?>>Etapa V</option>
                                <option value="6" <?= $etapa == 6 ? 'selected' : '' ?>>Etapa VI</option>
                                <option value="7" <?= $etapa == 7 ? 'selected' : '' ?>>Etapa VII</option>
                                <option value="8" <?= $etapa == 8 ? 'selected' : '' ?>>Etapa VIII</option>
                                <option value="9" <?= $etapa == 9 ? 'selected' : '' ?>>Etapa IX</option>
                                <option value="10" <?= $etapa == 10 ? 'selected' : '' ?>>Etapa X</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fa fa-save me-2"></i>
                            ATUALIZAR ETAPA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>