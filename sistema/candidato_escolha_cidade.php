<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

if ($_SESSION['perfil'] != 'candidato') {
    erro("Erro: 347356895465! Não foi possível abrir a página");
    exit();
}

if (!isset($_SESSION['candidato_etapa']) || $_SESSION['candidato_etapa'] < 4) {
    erro("Erro: 568334767! Não foi possível abrir a página");
    exit();
}

$lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
$datetime = date('d/m/Y H:i:s');
?>

<style>
    .guidance-steps .step-badge {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .step-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 8px;
        transition: transform 0.2s ease;
    }

    .step-item:hover {
        transform: translateX(5px);
    }

    .section-title {
        color: #495057;
        font-weight: 600;
        border-color: #dee2e6 !important;
    }

    .inscricoes-grid .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .inscricoes-grid .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .vagas-info .card {
        height: 100%;
    }

    .declaration-signature {
        background-color: #fcf8e3;
        border-color: #faebcc;
        color: #8a6d3b;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-map-marked-alt me-2"></i>Escolha de Guarnição</h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Escolha de Guarnição</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- Card de Orientações - Melhorado -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-compass fa-lg mr-10"></i>
                        <div>
                            <h4 class="card-title mb-0 mt-0">ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO</h4>
                            <small class="opacity-75">Leia atentamente antes de prosseguir</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="guidance-steps mb-20">
                        <div class="step-item mb-4 p-3 border-start border-primary border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">1</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DECLARAÇÃO DE CIÊNCIA</h6>
                                    <p class="mb-0">Eu, <strong class="text-dark"><?= mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></strong>, portador do CPF: <strong class="text-dark"><?= mascara($_SESSION['cpf'], '###.###.###-##') ?></strong>, DECLARO ter tomado conhecimento das orientações a respeito da ESCOLHA DE GUARNIÇÃO.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-info border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">2</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DISPONIBILIDADE DE VAGAS</h6>
                                    <p class="mb-0">COMPREENDO que será disponibilizado aos candidatos melhores classificados o universo de GUARNIÇÕES OFERTADAS abaixo, de acordo com a necessidade da Administração Militar.</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-warning border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">3</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">DIREITO DE DESISTÊNCIA</h6>
                                    <p class="mb-0">COMPREENDO que é facultado ao candidato o direito de selecionar: "Nenhuma das Opções (Desistência das localidades ofertadas)".</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-item mb-4 p-3 border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <span class="step-badge bg-primary text-white mr-10" style="border-radius: 90px;">4</span>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">ESGOTAMENTO DE VAGAS</h6>
                                    <p class="mb-0">COMPREENDO que as vagas serão esgotadas à medida que os candidatos melhores pontuados efetuam suas escolhas, até restar uma vaga para o seguinte candidato melhor pontuado.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="declaration-signature p-10 rounded border">
                        <div class="text-center">
                            <div class="signature-header mb-3">
                                <h5 class="fw-bold text-dark mb-1">DECLARAÇÃO DE CONFIRMAÇÃO</h5>
                                <p class="text-muted mb-0">Candidato abaixo assinado</p>
                            </div>
                            <div class="candidate-info mb-10">
                                <h4 class="text-primary fw-bold mb-2"><?= mb_strtoupper($_SESSION['nome_completo'], 'UTF-8') ?></h4>
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-primary mr-10">
                                        <i class="fa fa-id-card"></i>
                                        CPF: <?= mascara($_SESSION['cpf'], '###.###.###-##') ?>
                                    </span>
                                    <span class="badge bg-primary">
                                        <i class="fa fa-calendar"></i>
                                        DATA: <?= $datetime ?>
                                    </span>
                                </div>
                            </div>
                            <div class="signature-line mt-3 pt-3 border-top">
                                <p class="fw-bold text-uppercase mb-0"><?= mb_strtoupper($_SESSION['assinatura_sistema'], 'UTF-8') ?></p>
                                <small class="text-muted">Assinatura Eletrônica do Sistema</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Inscrições - Melhorado -->
            <div class="card border-warning mb-4">
                <div class="card-header mb-20">
                    <div class="d-flex align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-clipboard fa-lg me-3"></i>
                            Minhas Inscrições no Processo Seletivo
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if (empty($lista_inscricoes)) : ?>
                                <div class="text-center py-4">
                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma inscrição encontrada</h5>
                                    <p class="text-muted">Você não possui inscrições ativas no processo seletivo.</p>
                                </div>
                            <?php else : ?>
                                <div class="inscricoes-grid">
                                    <?php foreach ($lista_inscricoes as $value) : ?>
                                        <?php
                                        //if($value['concorrendo'] == 0) continue;

                                        $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'], $value['id_especialidade']);
                                        $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);

                                        $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);

                                        $ott_stt = null;
                                        $ott_stt_badge = '';
                                        switch ($value['ott_stt']) {
                                            case 'ott':
                                                $ott_stt = "Oficial Técnico Temporário - OTT";
                                                $ott_stt_badge = 'bg-primary';
                                                break;
                                            case 'stt':
                                                $ott_stt = "Sargento Técnico Temporário - STT";
                                                $ott_stt_badge = 'bg-info';
                                                break;
                                            case 'medico':
                                                $ott_stt = "Médico";
                                                $ott_stt_badge = 'bg-success';
                                                break;
                                            case 'dentista':
                                                $ott_stt = "Dentista";
                                                $ott_stt_badge = 'bg-warning';
                                                break;
                                            case 'veterinario':
                                                $ott_stt = "Veterinário";
                                                $ott_stt_badge = 'bg-secondary';
                                                break;
                                            case 'farmaceutico':
                                                $ott_stt = "Farmacêutico";
                                                $ott_stt_badge = 'bg-dark';
                                                break;
                                        }

                                        $card_class = "border-success";
                                        $status_badge = "bg-success";
                                        $status_text = "Elegível para Seleção";

                                        if (!empty($value['cidade_escolheu_servir'])) {
                                            $card_class = "border-info";
                                            $status_badge = "bg-info";
                                            $status_text = "Guarnição Já Selecionada";
                                        }

                                        if ($value['concorrendo'] == 0) {
                                            $card_class = "border-danger";
                                            $status_badge = "bg-danger";
                                            $status_text = "Desclassificado";
                                        }
                                        ?>

                                        <div class="card <?= $card_class ?>">
                                            <div class="card-header <?= $status_badge ?> text-white mb-20">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-graduation-cap me-2"></i>
                                                        <h5 class="card-title mb-0 me-3"><?= mb_strtoupper($value['especialidade'], 'UTF-8') ?></h5>
                                                        <span class="badge <?= $ott_stt_badge ?>"><?= $ott_stt ?></span>
                                                    </div>
                                                    <span class="badge bg-light text-dark"><?= $status_text ?></span>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <?php if ($value['concorrendo'] == 0) : ?>
                                                    <div class="alert alert-danger d-flex align-items-center">
                                                        <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                                                        <div>
                                                            <h6 class="alert-heading mb-1">DESCLASSIFICADO</h6>
                                                            <p class="mb-0"><?= $value['justificativa'] ?></p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $lista_epecialidades = $conexao->get_cidades_especialidade($value['id_especialidade']);
                                                $get_vagas_especialidade = $conexao->get_vagas_especialidade($value['id_especialidade']);
                                                ?>

                                                <div class="vagas-info mb-4">
                                                    <h5 class="section-title border-bottom pb-2 mb-3">
                                                        <i class="fa fa-pie-chart me-2"></i>
                                                        Situação das Vagas
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header mb-20">
                                                                    <h5 class="mb-0"><i class="fa fa-list"></i> Total Disponibilizadas</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php foreach ($lista_epecialidades as $linha) : ?>
                                                                        <?php
                                                                        $vagas = 0;
                                                                        if ((int)$linha['numero_vagas'] > 0) $vagas = $linha['numero_vagas'];
                                                                        ?>
                                                                        <div class="d-flex justify-content-between align-items-center mb-10 pb-10" style="border-bottom: 1px solid #ddd;">
                                                                            <span class="fw-medium"><?= $linha['nome'] ?></span>
                                                                            <span class="badge bg-primary"><?= $vagas ?> vaga(s)</span>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header mb-20">
                                                                    <h5 class="mb-0"><i class="fa fa-check-circle"></i> Vagas Restantes</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <?php foreach ($get_vagas_especialidade as $vaga) : ?>
                                                                        <?php
                                                                        $vagas = 0;
                                                                        if ((int)$vaga['vagas'] > 0) $vagas = $vaga['vagas'];
                                                                        $vaga_badge = $vagas > 0 ? 'bg-primary' : 'bg-danger';
                                                                        ?>
                                                                        <div class="d-flex justify-content-between align-items-center mb-10 pb-10" style="border-bottom: 1px solid #ddd;">
                                                                            <span class="fw-medium"><?= $vaga['cidade'] ?></span>
                                                                            <span class="badge <?= $vaga_badge ?>"><?= $vagas ?> vaga(s)</span>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($value['cidade_escolheu_servir'] != null) : ?>
                                                    <?php
                                                    $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
                                                    if ($get_cidade_escolhida[0]['nome'] != null) :
                                                    ?>
                                                        <div class="alert alert-success d-flex align-items-center">
                                                            <i class="fa fa-check-circle fa-2x mr-10"></i>
                                                            <div>
                                                                <h5 class="alert-heading mb-0">Guarnição Selecionada</h5>
                                                                <p class="mb-0 fw-bold fs-5"><?= $get_cidade_escolhida[0]['nome'] ?></p>
                                                                <small class="text-muted">Sua escolha foi registrada no sistema</small>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php
                                                $tem_vaga_ = false;
                                                foreach ($get_vagas_especialidade as $vaga) :
                                                    if ((int)$vaga['vagas'] > 0) $tem_vaga_ = true;
                                                endforeach;

                                                $pode_selecionar = true;
                                                if (!seleciona_cidade_vai_servir()) $pode_selecionar = false;
                                                ?>

                                                <?php if ($value['concorrendo'] == 1 && $value['cidade_escolheu_servir'] == null && $tem_vaga_ && $pode_selecionar) : ?>
                                                    <?php $crip = hash('sha256', $value['id_especialidade'] . "escolhe_cidade"); ?>

                                                    <div class="selecao-guarnicao border-top pt-4 mt-3">
                                                        <form action="../banco_dados/candidato_cidade_escolheu_servir.php" method="post">
                                                            <div class="row mb-20">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">
                                                                            <i class="fa fa-map-marker me-2"></i>
                                                                            Escolha a cidade onde deseja servir
                                                                        </label>
                                                                        <select id="cidade_escolheu" name="cidade_escolheu_servir" class="form-control" onchange="selecao_cidade()">
                                                                            <option value="">Selecione uma guarnição (ÚNICA VEZ)</option>
                                                                            <?php foreach ($get_vagas_especialidade as $vaga) : ?>
                                                                                <?php if ((int)$vaga['vagas'] > 0) : ?>
                                                                                    <option value="<?= $vaga['id_cidade'] ?>">
                                                                                        <?= $vaga['cidade'] ?> - <?= $vaga['vagas'] ?> vaga(s) disponível(eis)
                                                                                    </option>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <option value="754809" class="text-danger fw-bold">Nenhuma das opções (Desistência das Vagas ofertadas)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-20">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="declaracao_<?= $value['id_especialidade'] ?>" name="declaracao" required>
                                                                            <label class="form-check-label fw-medium" for="declaracao_<?= $value['id_especialidade'] ?>">
                                                                                Declaro sob as penas da lei que li e compreendi todas as ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO acima.
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="crip" value="<?= $crip ?>">
                                                            <input type="hidden" name="id_especialidade" value="<?= $value['id_especialidade'] ?>">

                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                                                    <i class="fa fa-paper-plane me-2"></i>
                                                                    CONFIRMAR ESCOLHA DA GUARNIÇÃO
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <a name="fim_pagina"></a>
        </div>
    </div>
</div>

</body>

</html>
<?php $conexao = null; ?>