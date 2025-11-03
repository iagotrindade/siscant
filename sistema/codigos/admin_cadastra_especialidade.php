<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}
?>
<a name="admin_cadastra_especialidade"></a>
<?php if ($_SESSION['perfil'] == 'admin'): ?>
    <div class="card dashboard-card mb-4">
        <div class="card-header dashboard-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-graduation-cap me-2"></i>
                Cadastrar Especialidades do Candidato
            </span>
        </div>
        <div class="card-body">
            <form action="../banco_dados/candidato_cadastra_especialidade.php" method="post" onsubmit="return verifica_cadastro_especialidade_candidato()">
                <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                <div class="row">
                    <!-- Tipo da Especialidade -->
                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-list-alt me-1"></i>
                            Tipo da Especialidade
                        </label>
                        <select id="ott_stt" name="ott_stt" class="form-control" onchange="busca_ott_stt()" required>
                            <option value="" selected>Selecione o tipo da especialidade</option>
                            <?php if ($codigo_selecao == 'mfdv'): ?>
                                <option value="medico">Médico</option>
                                <option value="farmaceutico">Farmacêutico</option>
                                <option value="dentista">Dentista</option>
                                <option value="veterinario">Veterinário</option>
                            <?php elseif ($codigo_selecao == "ott_stt"): ?>
                                <option value="ott">OTT</option>
                                <option value="stt">STT</option>
                                <option value="pctd">PCTD</option>
                            <?php elseif ($codigo_selecao == 'cet'): ?>
                                <option value="cet">CET</option>
                            <?php elseif ($codigo_selecao == 'ottm'): ?>
                                <option value="ottm">OTTM</option>
                            <?php elseif ($codigo_selecao == "eipot"): ?>
                                <option value="eipot">EIPOT</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Especialidade Específica -->
                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-bookmark me-1"></i>
                            Especialidade
                        </label>
                        <select id="especialidade" name="especialidade" class="form-control" required>
                            <?php if ($codigo_selecao == 'mfdv'): ?>
                                <option value="">Primeiro selecione o tipo da especialidade</option>
                            <?php else: ?>
                                <option value="">Primeiramente selecione se a especialidade é OTT ou STT</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fa fa-hand-point-up me-1"></i>
                            Selecione o tipo primeiro para carregar as opções
                        </small>
                    </div>

                    <!-- Registro no Conselho Regional -->
                    <?php if ($_SESSION['selecao_codigo'] !== "eipot"): ?>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-id-card me-1"></i>
                                Registro no Conselho Regional
                            </label>
                            <input id="registro_conselho"
                                maxlength="40"
                                name="registro_conselho"
                                class="form-control"
                                placeholder="Digite o número do registro no conselho">
                        </div>
                    <?php endif; ?>

                    <!-- Data de Habilitação -->
                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-calendar me-1"></i>
                            Data de Habilitação
                        </label>
                        <input type="text"
                            name="data_habilitacao"
                            class="form-control"
                            required>
                        <small class="text-muted">
                            <i class="fa fa-graduation-cap me-1"></i>
                            Data de conclusão do curso que habilita para a especialidade
                        </small>
                    </div>
                </div>

                <!-- Botão de Submit -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                            <i class="fa fa-plus-circle me-2"></i>
                            CADASTRAR ESPECIALIDADE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>