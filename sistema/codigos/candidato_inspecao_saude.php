<a name="exame_medico"></a>

<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos' && $_SESSION['perfil'] != 'jise') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

include_once '../sistema/codigos/funcao_apagar.php';
?>

<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-stethoscope me-2"></i>
            Inspeção de Saúde
        </span>
    </div>
    <div class="card-body">
        <!-- Formulário IS - JISE -->
        <div class="border rounded p-4 mb-20 bg-light">
            <form action="../banco_dados/candidato_edita_exame_medico.php" method="post" enctype="multipart/form-data">
                <div class="row mb-20">
                    <div class="col-md-12">
                        <h4 class="text-primary mb-0">
                            <i class="fa fa-user-md me-2"></i>
                            IS - JISE
                        </h4>
                    </div>
                </div>

                <div class="row">
                    <!-- Situação -->
                    <div class="col-lg-2 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-list-alt me-1"></i>
                            Situação
                        </label>
                        <select name="apto_saude" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="1" <?= $apto_saude == '1' ? 'selected' : '' ?>>Apto</option>
                            <option value="0" <?= $apto_saude == '0' ? 'selected' : '' ?>>Inapto</option>
                            <option value="2" <?= $apto_saude == '2' ? 'selected' : '' ?>>Não compareceu</option>
                        </select>
                    </div>

                    <!-- Grupo -->
                    <div class="col-lg-2 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-users me-1"></i>
                            Grupo
                        </label>
                        <select name="grupo_saude" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option value="a" <?= $grupo_saude == 'a' ? 'selected' : '' ?>>A</option>
                            <option value="b1" <?= $grupo_saude == 'b1' ? 'selected' : '' ?>>B1</option>
                            <option value="b2" <?= $grupo_saude == 'b2' ? 'selected' : '' ?>>B2</option>
                            <option value="c" <?= $grupo_saude == 'c' ? 'selected' : '' ?>>C</option>
                        </select>
                    </div>

                    <!-- Data do Exame -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-calendar me-1"></i>
                            Data do Exame/Não Comparecimento
                        </label>
                        <input type="text"
                            name="data_exame_saude"
                            value="<?= $data_exame_saude != null ? trata_data($data_exame_saude) : '' ?>"
                            class="form-control">
                    </div>

                    <!-- CID -->
                    <div class="col-lg-5 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-heartbeat me-1"></i>
                            CID
                        </label>
                        <textarea name="cid_saude"
                            class="form-control"
                            rows="1"
                            maxlength="2000"
                            placeholder="Digite o CID"><?= htmlspecialchars($cid_saude) ?></textarea>
                    </div>

                    <!-- Observações -->
                    <div class="col-lg-12 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-notes-medical me-1"></i>
                            Observações
                        </label>
                        <small class="text-muted d-block mb-2">
                            <i class="fa fa-info-circle me-1"></i>
                            Preencher FC, PA, PESO, ALT e IMC
                        </small>
                        <textarea name="observacao_exame_saude"
                            class="form-control"
                            rows="4"
                            maxlength="2000"
                            placeholder="Digite as observações do exame"><?= htmlspecialchars($observacao_exame_saude) ?></textarea>
                    </div>

                    <!-- ATA IS -->
                    <div class="col-lg-6 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-file-pdf me-1"></i>
                            ATA - IS
                        </label>
                        <?php if ($ata_is == null): ?>
                            <div class="input-group">
                                <input type="file"
                                    name="ata_is"
                                    class="form-control"
                                    accept=".pdf">
                            </div>
                            <small class="text-muted">
                                <i class="fa fa-exclamation-triangle me-1"></i>
                                Máximo 5 Megabytes, formato PDF
                            </small>
                        <?php else: ?>
                            <div class="d-flex align-items-center">
                                <a href="arquivos_add_p_cand/atas_is/<?= $ata_is ?>"
                                    target="_blank"
                                    class="btn btn-success btn-sm mr-10"
                                    data-bs-toggle="tooltip"
                                    title="Visualizar ATA">
                                    <i class="fa fa-file-pdf-o me-1"></i> Visualizar ATA
                                </a>
                                <button type="button"
                                    onclick="funcao_apagar_ata_is('<?= $id_usuario ?>', 'ata_is')"
                                    class="btn btn-sm action-btn btn-success"
                                    data-bs-toggle="tooltip"
                                    title="Apagar ATA">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Campos Hidden -->
                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">
                <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                <input type="hidden" name="c_p_f_candidato" value="<?= $cpf ?>">
                <input type="hidden" name="medico_obrigatorio" value="nao">

                <!-- Botão Submit -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fa fa-save me-2"></i>
                            SALVAR IS - JISE
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Formulário ISGRec - JISR -->
        <div class="border rounded p-4 bg-light">
            <form action="../banco_dados/candidato_edita_exame_medico_recurso.php" method="post" enctype="multipart/form-data">
                <div class="row mb-20">
                    <div class="col-md-12">
                        <h4 class="text-primary mb-0">
                            <i class="fa fa-user-md me-2 me-2"></i>
                            ISGRec - JISR
                        </h4>
                    </div>
                </div>

                <div class="row">
                    <!-- Situação Recurso -->
                    <div class="col-lg-2 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-list-alt me-1"></i>
                            Situação
                        </label>
                        <select name="apto_saude_recurso" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="1" <?= $apto_saude_recurso == '1' ? 'selected' : '' ?>>Apto</option>
                            <option value="0" <?= $apto_saude_recurso == '0' ? 'selected' : '' ?>>Inapto</option>
                            <option value="2" <?= $apto_saude_recurso == '2' ? 'selected' : '' ?>>Não compareceu</option>
                        </select>
                    </div>

                    <!-- Grupo Recurso -->
                    <div class="col-lg-2 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-users me-1"></i>
                            Grupo
                        </label>
                        <select name="grupo_saude_recurso" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option value="a" <?= $grupo_saude_recurso == 'a' ? 'selected' : '' ?>>A</option>
                            <option value="b1" <?= $grupo_saude_recurso == 'b1' ? 'selected' : '' ?>>B1</option>
                            <option value="b2" <?= $grupo_saude_recurso == 'b2' ? 'selected' : '' ?>>B2</option>
                            <option value="c" <?= $grupo_saude_recurso == 'c' ? 'selected' : '' ?>>C</option>
                        </select>
                    </div>

                    <!-- Data do Exame Recurso -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-calendar me-1"></i>
                            Data do Exame/Não Comparecimento
                        </label>
                        <input type="text"
                            name="data_exame_saude_recurso"
                            required
                            value="<?= $data_exame_saude_recurso != null ? trata_data($data_exame_saude_recurso) : '' ?>"
                            class="form-control">
                    </div>

                    <!-- CID Recurso -->
                    <div class="col-lg-5 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-file-medical me-1"></i>
                            CID
                        </label>
                        <textarea name="cid_saude_recurso"
                            class="form-control"
                            rows="1"
                            maxlength="2000"
                            placeholder="Digite o CID"><?= htmlspecialchars($cid_saude_recurso) ?></textarea>
                    </div>

                    <!-- Observações Recurso -->
                    <div class="col-lg-12 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-notes-medical me-1"></i>
                            Observações
                        </label>
                        <small class="text-muted d-block mb-2">
                            <i class="fa fa-info-circle me-1"></i>
                            Preencher FC, PA, PESO, ALT e IMC
                        </small>
                        <textarea name="observacao_exame_saude_recurso"
                            class="form-control"
                            rows="4"
                            maxlength="2000"
                            placeholder="Digite as observações do exame"><?= htmlspecialchars($observacao_exame_saude_recurso) ?></textarea>
                    </div>

                    <!-- ATA IS Recurso -->
                    <div class="col-lg-6 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-file-pdf me-1"></i>
                            ATA - ISGRec
                        </label>
                        <?php if ($ata_is_recurso == null): ?>
                            <div class="input-group">
                                <input type="file"
                                    name="ata_is_recurso"
                                    class="form-control"
                                    accept=".pdf">
                            </div>
                            <small class="text-muted">
                                <i class="fa fa-exclamation-triangle me-1"></i>
                                Máximo 5 Megabytes, formato PDF
                            </small>
                        <?php else: ?>
                            <div class="d-flex align-items-center">
                                <a href="arquivos_add_p_cand/atas_is/<?= $ata_is_recurso ?>"
                                    target="_blank"
                                    class="btn btn-success btn-sm mr-10"
                                    data-bs-toggle="tooltip"
                                    title="Visualizar ATA">
                                    <i class="fa fa-file-pdf-o me-1"></i> Visualizar ATA
                                </a>
                                <button type="button"
                                    onclick="funcao_apagar_ata_is('<?= $id_usuario ?>', 'ata_is_recurso')"
                                    class="btn btn-sm action-btn btn-success"
                                    data-bs-toggle="tooltip"
                                    title="Apagar ATA">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Campos Hidden -->
                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">
                <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                <input type="hidden" name="c_p_f_candidato" value="<?= $cpf ?>">
                <input type="hidden" name="medico_obrigatorio" value="nao">

                <!-- Botão Submit -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100 py-2 text-dark">
                            <i class="fa fa-save me-2"></i>
                            SALVAR ISGRec - JISR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>