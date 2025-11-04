<?php
if (!isset($_SESSION))
    session_start();

if (($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'con') || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

?>

<a name="oficio"></a>
<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
        <span class="card-title mb-0">
            <i class="fa fa-file-pdf-o me-2"></i>
            Gerar Ofício - Médico Obrigatório
        </span>
    </div>
    <div class="card-body">
        <form action="mpdf/oficio_medico_obrigatorio.php" method="post">
            <div class="row">
                <!-- Endereço da OM 1ª Fase -->
                <div class="col-lg-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-map-marker me-1"></i>
                        Endereço e Bairro da OM de 1ª Fase
                    </label>
                    <input type="text"
                        name="endereco_om_1_fase"
                        value="<?= htmlspecialchars($endereco_om_1_fase) ?>"
                        class="form-control"
                        placeholder="Digite o endereço completo da OM">
                </div>

                <!-- Presidente da Comissão -->
                <div class="col-lg-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-user me-1"></i>
                        Presidente da Comissão de Designação
                    </label>
                    <input type="text"
                        name="presidente"
                        value=""
                        maxlength="100"
                        class="form-control"
                        placeholder="Nome do presidente da comissão">
                </div>

                <!-- CEP e Cidade -->
                <div class="col-lg-3 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-location-arrow me-1"></i>
                        CEP e Cidade da OM de 1ª Fase
                    </label>
                    <input type="text"
                        name="cep_om_1_fase"
                        value="<?= htmlspecialchars($cep_om_1_fase . " / " . $guarnicao_om_1_fase) ?>"
                        class="form-control"
                        placeholder="CEP / Cidade">
                </div>

                <!-- Data da Apresentação -->
                <div class="col-lg-3 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-calendar me-1"></i>
                        Data da Apresentação
                    </label>
                    <input type="text"
                        name="data_apresentacao"
                        class="form-control">
                    <small class="text-muted">
                        <i class="fa fa-info-circle me-1"></i>
                        Data para apresentação do candidato
                    </small>
                </div>

                <!-- Hora da Apresentação -->
                <div class="col-lg-3 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-clock-o me-1"></i>
                        Hora da Apresentação
                    </label>
                    <input type="text"
                        name="hora_apresentacao"
                        class="form-control">
                    <small class="text-muted">
                        <i class="fa fa-info-circle me-1"></i>
                        Horário para apresentação
                    </small>
                </div>

                <!-- Telefone da OM -->
                <div class="col-lg-3 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-phone me-1"></i>
                        Telefone da OM de 1ª Fase
                    </label>
                    <input type="text"
                        name="telefone_om_1_fase"
                        value="<?= htmlspecialchars($telefone_om_1_fase) ?>"
                        class="form-control"
                        placeholder="(XX) XXXX-XXXX">
                </div>

                <!-- Número EB -->
                <div class="col-lg-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-hashtag me-1"></i>
                        Número EB
                    </label>
                    <input type="text"
                        name="eb"
                        value="64510.450034/<?= date("Y") ?>-13"
                        class="form-control"
                        readonly>
                    <small class="text-muted">
                        <i class="fa fa-lock me-1"></i>
                        Número automático do processo
                    </small>
                </div>

                <!-- Data do Cabeçalho -->
                <div class="col-lg-6 mb-20">
                    <label class="form-label fw-semibold">
                        <i class="fa fa-calendar me-1"></i>
                        Data do Cabeçalho
                    </label>
                    <input type="text"
                        name="dt_cabecalho"
                        value="<?= get_data_extenso() ?>"
                        class="form-control"
                        readonly>
                    <small class="text-muted">
                        <i class="fa fa-lock me-1"></i>
                        Data atual
                    </small>
                </div>
            </div>

            <!-- Campos Hidden -->
            <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">
            <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
            <input type="hidden" name="c_p_f_candidato" value="<?= $cpf ?>">
            <input type="hidden" name="medico_obrigatorio" value="nao">

            <!-- Botão Submit -->
            <div class="row mb-20">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                        <i class="fa fa-file-pdf me-2"></i>
                        GERAR OFÍCIO EM PDF
                    </button>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="alert alert-info mt-3">
                <div class="d-flex align-items-center">
                    <i class="fa fa-info-circle fa-2x me-3"></i>
                    <div>
                        <strong>Informações sobre o ofício:</strong>
                        <ul class="mb-0 mt-1">
                            <li>O ofício será gerado em formato PDF</li>
                            <li>Preencha todos os campos obrigatórios</li>
                            <li>Verifique as datas e horários antes de gerar</li>
                            <li>O documento seguirá o padrão oficial do Exército Brasileiro</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>