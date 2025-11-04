<a name="impedimento_judicial"></a>
<?php if($medico_obrigatorio != '1'): ?>
    <div class="card dashboard-card mb-20">
        <div class="card-header dashboard-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-gavel me-2"></i>
                Informações Judiciais
            </span>
        </div>
        <div class="card-body">
            <form action="../banco_dados/candidato_impedido_judicial.php" method="post">
                <div class="row">
                    <!-- Impedimento Judicial -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-ban me-1"></i>
                            Impedimento Judicial
                        </label>
                        <select name="refratario_impedido" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="desistencia" <?= $refratario_impedido == 'desistencia' ? 'selected' : '' ?>>Desistência</option>
                            <option value="refratario" <?= $refratario_impedido == 'refratario' ? 'selected' : '' ?>>Refratário</option>
                            <option value="impedido" <?= $refratario_impedido == 'impedido' ? 'selected' : '' ?>>Impedimento Judicial</option>
                            <option value="excesso" <?= $refratario_impedido == 'excesso' ? 'selected' : '' ?>>Excesso</option>
                            <option value="incorporado" <?= $refratario_impedido == 'incorporado' ? 'selected' : '' ?>>Incorporado</option>
                        </select>
                    </div>

                    <!-- Histórico Judicial -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-history me-1"></i>
                            Histórico Judicial
                        </label>
                        <select name="historico_judicial" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="1" <?= $historico_judicial == '1' ? 'selected' : '' ?>>Sim</option>
                            <option value="0" <?= $historico_judicial == '0' ? 'selected' : '' ?>>Não</option>
                        </select>
                    </div>

                    <!-- Número da Ação -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-hashtag me-1"></i>
                            Número da Ação
                        </label>
                        <input type="text"
                            name="numero_acao"
                            value="<?= $numero_acao != '' ? htmlspecialchars($numero_acao) : '' ?>"
                            maxlength="100"
                            class="form-control"
                            placeholder="Número do processo judicial">
                    </div>

                    <!-- Data da Liminar -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-calendar me-1"></i>
                            Data da Liminar
                        </label>
                        <input type="text"
                            name="data_liminar"
                            value="<?= $data_liminar != null ? reverte_data($data_liminar) : '' ?>"
                            class="form-control">
                    </div>

                    <!-- Transitou em Julgado -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-balance-scale me-1"></i>
                            Transitou em Julgado
                        </label>
                        <select name="transitou_julgado" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="1" <?= $transitou_julgado == '1' ? 'selected' : '' ?>>Sim</option>
                            <option value="0" <?= $transitou_julgado == '0' ? 'selected' : '' ?>>Não</option>
                        </select>
                    </div>

                    <!-- Favorável/Desfavorável -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-thumbs-up me-1"></i>
                            Favorável/Desfavorável
                        </label>
                        <select name="favoravel_desfavoravel" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="favoravel" <?= $favoravel_desfavoravel == 'favoravel' ? 'selected' : '' ?>>Favorável</option>
                            <option value="desfavoravel" <?= $favoravel_desfavoravel == 'desfavoravel' ? 'selected' : '' ?>>Desfavorável</option>
                        </select>
                    </div>

                    <!-- Convocado -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-bell me-1"></i>
                            Convocado
                        </label>
                        <select name="convocado" class="form-control" required>
                            <option value="">Selecione a opção</option>
                            <option value="1" <?= $convocado == '1' ? 'selected' : '' ?>>Sim</option>
                            <option value="0" <?= $convocado == '0' ? 'selected' : '' ?>>Não</option>
                        </select>
                    </div>

                    <!-- Publicação BAR Reg -->
                    <div class="col-lg-3 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-file-text me-1"></i>
                            Publicação BAR Reg
                        </label>
                        <input type="text"
                            name="publicacao_bar_reg"
                            value="<?= $publicacao_bar_reg != '' ? htmlspecialchars($publicacao_bar_reg) : '' ?>"
                            maxlength="100"
                            class="form-control"
                            placeholder="Nº e data da publicação">
                        <small class="text-muted">
                            <i class="fa fa-info-circle me-1"></i>
                            Número e data da publicação
                        </small>
                    </div>
                </div>

                <!-- Campos Hidden -->
                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">
                <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                <input type="hidden" name="c_p_f_candidato" value="<?= $cpf ?>">

                <!-- Botão Submit -->
                <div class="row mb-20">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fa fa-save me-2"></i>
                            SALVAR SITUAÇÃO JUDICIAL
                        </button>
                    </div>
                </div>

                <!-- Informações Legais -->
                <div class="alert alert-warning mt-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <strong>Informações sobre a situação judicial:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Preencha todos os campos obrigatórios</li>
                                <li>Verifique as informações judiciais antes de salvar</li>
                                <li>As alterações serão registradas no sistema</li>
                                <li>Consulte o setor jurídico em caso de dúvidas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>