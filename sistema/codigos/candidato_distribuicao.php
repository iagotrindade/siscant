<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}
?>

<a name="distribuicao"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->

<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-map-marker me-2"></i>
            Distribuição do Candidato
        </span>
    </div>
    <div class="card-body">
        <form action="../banco_dados/candidato_distribuicao.php" method="post">
            <div class="row">
                <!-- Status de Distribuição -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-check-circle me-1"></i>
                        Status de Distribuição
                    </label>
                    <select name="incorporado" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <option value="1" <?= $incorporado == '1' ? 'selected' : '' ?>>Distribuído</option>
                        <option value="0" <?= $incorporado == '0' ? 'selected' : '' ?>>Aguardando Distribuição</option>
                    </select>
                </div>

                <!-- Especialidade de Incorporação -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-graduation-cap me-1"></i>
                        Especialidade de Incorporação
                    </label>
                    <select name="especialidade_incorporou" class="form-control" required>
                        <option value="">Selecione a especialidade</option>
                        <?php foreach ($especialidade_cadastradas_candidato as $esp_cadastrada_pelo_cand): ?>
                            <option value="<?= $esp_cadastrada_pelo_cand['id_especialidade'] ?>"
                                <?= $especialidade_incorporacao == $esp_cadastrada_pelo_cand['id_especialidade'] ? 'selected' : '' ?>>
                                <?= mb_strtoupper($esp_cadastrada_pelo_cand['ott_stt']) ?> - <?= htmlspecialchars($esp_cadastrada_pelo_cand['especialidade']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Data de Incorporação -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-calendar me-1"></i>
                        Data de Incorporação
                    </label>
                    <input type="text"
                        name="data_incorporacao"
                        value="<?= $data_incorporacao != null ? trata_data($data_incorporacao) : '' ?>"
                        class="form-control">
                </div>

                <!-- Número da Distribuição -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-list-ol me-1"></i>
                        Número da Distribuição
                    </label>
                    <select name="numero_distribuicao" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $numero_distribuicao == $i ? 'selected' : '' ?>>
                                <?= $i ?>ª Distribuição
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Força -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-shield me-1"></i>
                        Força
                    </label>
                    <select name="forca_distribuicao" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <option value="exercito" <?= $forca_distribuicao == 'exercito' ? 'selected' : '' ?>>Exército</option>
                        <option value="marinha" <?= $forca_distribuicao == 'marinha' ? 'selected' : '' ?>>Marinha</option>
                        <option value="aeronautica" <?= $forca_distribuicao == 'aeronautica' ? 'selected' : '' ?>>Aeronáutica</option>
                    </select>
                </div>

                <!-- Status Militar -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-info-circle me-1"></i>
                        Status Militar
                    </label>
                    <select name="titular_reserva" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <option value="titular" <?= $titular_reserva_distribuicao == 'titular' ? 'selected' : '' ?>>Titular</option>
                        <option value="titular eis" <?= $titular_reserva_distribuicao == 'titular eis' ? 'selected' : '' ?>>Titular EIS</option>
                        <option value="reserva" <?= $titular_reserva_distribuicao == 'reserva' ? 'selected' : '' ?>>Reserva</option>
                        <option value="adiado" <?= $titular_reserva_distribuicao == 'adiado' ? 'selected' : '' ?>>Adiado</option>
                        <option value="adiado_b1" <?= $titular_reserva_distribuicao == 'adiado_b1' ? 'selected' : '' ?>>Adiado B1</option>
                        <option value="adiado_justica" <?= $titular_reserva_distribuicao == 'adiado_justica' ? 'selected' : '' ?>>Adiado Justiça</option>
                        <option value="excesso" <?= $titular_reserva_distribuicao == 'excesso' ? 'selected' : '' ?>>Excesso</option>
                        <option value="excesso_incapaz" <?= $titular_reserva_distribuicao == 'excesso_incapaz' ? 'selected' : '' ?>>Excesso Incapaz</option>
                        <option value="refratario" <?= $titular_reserva_distribuicao == 'refratario' ? 'selected' : '' ?>>Refratário</option>
                        <option value="fisemi_transferida" <?= $titular_reserva_distribuicao == 'fisemi_transferida' ? 'selected' : '' ?>>Fisemi Transferida</option>
                        <option value="desobrigado" <?= $titular_reserva_distribuicao == 'desobrigado' ? 'selected' : '' ?>>Desobrigado</option>
                        <option value="insubmisso" <?= $titular_reserva_distribuicao == 'insubmisso' ? 'selected' : '' ?>>Insubmisso</option>
                        <option value="cdi_justica" <?= $titular_reserva_distribuicao == 'cdi_justica' ? 'selected' : '' ?>>CDI Justiça</option>
                    </select>
                </div>

                <!-- OM 1ª Fase -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-building me-1"></i>
                        OM 1ª Fase
                    </label>
                    <select name="om_distribuicao_1_fase" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <?php foreach ($conexao->get_oms($rm_usuario) as $value): ?>
                            <option value="<?= $value['id'] ?>" <?= $om_1_fase == $value['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($value['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- UF 1ª Fase -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-map me-1"></i>
                        UF 1ª Fase
                    </label>
                    <select id="uf2" name="uf2" class="form-control" onchange="busca_cidades2()" required>
                        <option value="">Selecione a UF</option>
                        <?php
                        $estados = ['AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO'];
                        foreach ($estados as $estado):
                        ?>
                            <option value="<?= $estado ?>" <?= $uf_1_fase == $estado ? 'selected' : '' ?>>
                                <?= $estado ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Guarnição 1ª Fase -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-map-marker me-1"></i>
                        Guarnição 1ª Fase
                    </label>
                    <select id="cidade2" name="cidade2" class="form-control" required>
                        <option value="">Selecione a Cidade</option>
                        <?php foreach ($conexao->busca_cidade_uf($uf_1_fase) as $value3): ?>
                            <option value="<?= $value3['id'] ?>" <?= $value3['id'] == $id_cidade_1_fase ? 'selected' : '' ?>>
                                <?= htmlspecialchars($value3['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- OM de Destino -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-building me-1"></i>
                        OM de Destino
                    </label>
                    <select name="om_distribuicao" class="form-control" required>
                        <option value="">Selecione a opção</option>
                        <?php foreach ($conexao->get_oms($rm_usuario) as $value): ?>
                            <option value="<?= $value['id'] ?>" <?= $om_distribuicao == $value['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($value['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- UF Destino -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-map me-1"></i>
                        UF Destino
                    </label>
                    <select id="uf" name="uf" class="form-control" onchange="busca_cidades()" required>
                        <option value="">Selecione a UF</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>" <?= $uf_distribuicao == $estado ? 'selected' : '' ?>>
                                <?= $estado ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Guarnição Destino -->
                <div class="col-lg-4 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-map-marker me-1"></i>
                        Guarnição Destino
                    </label>
                    <select id="cidade" name="cidade_distribuicao" class="form-control" required>
                        <option value="">Selecione a Cidade</option>
                        <?php foreach ($conexao->busca_cidade_uf($uf_distribuicao) as $value2): ?>
                            <option value="<?= $value2['id'] ?>" <?= $value2['id'] == $id_cidade_distribuicao ? 'selected' : '' ?>>
                                <?= htmlspecialchars($value2['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Aditamento de Convocação -->
                <div class="col-md-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-file-contract me-1"></i>
                        Aditamento de Convocação
                    </label>
                    <input type="text"
                        name="aditamento_convocacao"
                        id="aditamento"
                        value="<?= $aditamento_convocacao != null ? htmlspecialchars($aditamento_convocacao) : '' ?>"
                        class="form-control"
                        maxlength="250"
                        placeholder="Digite o aditamento de convocação">
                </div>

                <!-- Observações -->
                <div class="col-md-12 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-sticky-note me-1"></i>
                        Observações
                    </label>
                    <textarea name="observacao_distribuicao"
                        class="form-control"
                        rows="4"
                        maxlength="2000"
                        placeholder="Digite observações sobre a distribuição"><?= htmlspecialchars($observacao_distribuicao) ?></textarea>
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
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                        <i class="fa fa-save me-2"></i>
                        SALVAR DISTRIBUIÇÃO
                    </button>
                </div>
            </div>
        </form>

        <!-- Script Autocomplete -->
        <script>
            $(function() {
                var aditamentos_cadastrados = [
                    <?php foreach ($todos_aditamentos as $aditamento): ?> "<?= $aditamento['aditamento_convocacao'] ?>",
                    <?php endforeach; ?> ""
                ];

                $("#aditamento").autocomplete({
                    source: aditamentos_cadastrados
                });
            });
        </script>
    </div>
</div>