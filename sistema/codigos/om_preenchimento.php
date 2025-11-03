<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'om') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

?>
<a name="apresentacao_om"></a>

<div class="card dashboard-card mb-20">
    <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
        <span class="card-title mb-0">
            <i class="fa fa-edit me-2"></i>
            Preencha os Campos Abaixo
        </span>
        <span class="badge bg-danger fs-6">
            <i class="fa fa-exclamation-triangle me-1"></i>
            URGENTE
        </span>
    </div>
    <div class="card-body">
        <form action="../banco_dados/om_informacoes.php" method="post">
            <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . $id_usuario . "freitas") ?>">
            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

            <div class="row">
                <!-- Seleção de Apresentação -->
                <div class="col-lg-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-clipboard me-1"></i>
                        Situação do Candidato na OM
                    </label>
                    <select name="apresentacao_candidato_om" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <option value="apresentou_apto" <?= $apresentacao_om == "apresentou_apto" ? 'selected' : '' ?>>
                            Apresentado e APTO
                        </option>
                        <option value="apresentou_inapto" <?= $apresentacao_om == "apresentou_inapto" ? 'selected' : '' ?>>
                            Apresentado e INAPTO
                        </option>
                        <option value="apresentou_e_nao_realizada_IS" <?= $apresentacao_om == "apresentou_e_nao_realizada_IS" ? 'selected' : '' ?>>
                            Apresentado e NÃO realizada inspeção saúde
                        </option>
                        <option value="faltoso" <?= $apresentacao_om == "faltoso" ? 'selected' : '' ?>>
                            Faltoso
                        </option>
                        <option value="insubmisso" <?= $apresentacao_om == "insubmisso" ? 'selected' : '' ?>>
                            Insubmisso
                        </option>
                        <option value="desertor" <?= $apresentacao_om == "desertor" ? 'selected' : '' ?>>
                            Desertor
                        </option>
                        <option value="incorporado" <?= $apresentacao_om == "incorporado" ? 'selected' : '' ?>>
                            Incorporado
                        </option>
                    </select>
                </div>

                <!-- Campo de Observações -->
                <div class="col-lg-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-sticky-note me-1"></i>
                        Observação Livre
                    </label>
                    <small class="text-muted d-block mb-2">
                        <i class="fa fa-info-circle me-1"></i>
                        Exemplo: Data de apresentação, Data da falta, Inapto, ETC...
                    </small>
                    <textarea name="observacao_om" class="form-control" rows="4" placeholder="Digite as observações aqui..."><?= htmlspecialchars($observacao_om) ?></textarea>
                </div>
            </div>

            <!-- Botão de Submit -->
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                        <i class="fa fa-save me-2"></i>
                        SALVAR INFORMAÇÕES
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>