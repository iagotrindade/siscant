<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
include_once './codigos/verifica_cadastro_especialidade_candidato.php';

//$codigo_selecao_my = $conexao->get_candidato_codigo_selecao($_SESSION['id_usuario']);
//var_dump($codigo_selecao_my); exit;

?>
<style>
    .card-header {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .border-danger {
        border: 2px solid #dc3545 !important;
    }

    .especialidade-card {
        border-radius: 12px;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .especialidade-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .card-desclassificado {
        border-left: 4px solid #dc3545;
        background-color: #fff5f5;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }

    .action-btn {
        color: white;
    }

    .info-box {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        height: 100%;
    }

    .info-icon {
        width: 54px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.8rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 1.2rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
    }

    .score-value {
        color: #059669;
    }

    .alert-action {
        border: none;
        border-radius: 10px;
        padding: 1.25rem;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .alert-warning .alert-icon {
        color: #f59e0b;
    }

    .alert-info .alert-icon {
        color: #0ea5e9;
    }

    .alert-action-btn {
        margin-left: auto;
        padding-left: 1rem;
    }

    @media (max-width: 768px) {
        .info-box {
            margin-bottom: 1rem;
        }

        .alert-action .d-flex {
            flex-direction: column;
            text-align: center;
        }

        .alert-action-btn {
            margin-left: 0;
            padding-left: 0;
            margin-top: 1rem;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-header .d-flex {
            margin-bottom: 1rem;
        }
    }
</style>

<script type="text/javascript">
    function selecao_cidade() {
        if ($('#cidade_escolheu').val() == '754809') {
            $("#mensagem_erro_cidade").text("Tem ciência que a opção escolhida: Nenhuma das opções (Desistência) - o eliminará do processo seletivo, neste momento, sendo que o senhor(a) desistiu da(s) vaga(s) ofertada(s)!");
        } else {
            $("#mensagem_erro_cidade").text("");
        }
    }
</script>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <!-- 22/06/2025 -> Iago Silva Alterado o ícone -->
            <h1>Cadastrar uma especialidade <i class="fa fa-graduation-cap"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Cadastra especialidade</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Alertas de Status -->
            <?php if (!inscricao()): ?>
                <div class="alert alert-danger text-center mb-4">
                    <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                    <h4 class="alert-heading mb-0">INSCRIÇÕES ENCERRADAS</h4>
                </div>
            <?php endif; ?>

            <!-- Formulário de Seleção de Especialidade -->
            <div class="card shadow-sm mb-4 <?= !inscricao() ? 'd-none' : '' ?>">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-graduation-cap me-2"></i>
                        Cadastre uma especialidade
                    </span>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/candidato_cadastra_especialidade.php" method="post" onsubmit="return verifica_cadastro_especialidade_candidato()" class="needs-validation" novalidate>
                        <input type="hidden" name="id_candidato" value="<?= $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                        <div class="row g-3">
                            <!-- Tipo de Especialidade -->
                            <div class="col-md-6 mb-20">
                                <label for="ott_stt" class="form-label">Tipo da especialidade</label>
                                <select id="ott_stt" name="ott_stt" class="form-control" onchange="busca_ott_stt()" required>
                                    <option value="" selected>Selecione a opção</option>
                                    <?php
                                    if ($codigo_selecao == 'mfdv'):
                                        echo '<option value="medico">Médico</option>
                                          <option value="farmaceutico">Farmacêutico</option>
                                          <option value="dentista">Dentista</option>
                                          <option value="veterinario">Veterinário</option>';
                                    elseif ($codigo_selecao == "ott_stt"):
                                        echo '<option value="ott">OTT</option>
                                          <option value="stt">STT</option>
                                          <option value="pctd">PCTD</option>';
                                    elseif ($codigo_selecao == 'cet'):
                                        echo '<option value="cet">CET</option>';
                                    elseif ($codigo_selecao == 'ottm'):
                                        echo '<option value="ottm">OTTM</option>';
                                    elseif ($codigo_selecao == "eipot"):
                                        echo '<option value="eipot">EIPOT</option>';
                                    endif;
                                    ?>
                                </select>
                            </div>

                            <!-- Registro no Conselho -->
                            <?php if ($_SESSION['selecao_codigo'] !== "eipot "): ?>
                                <div class="col-md-6 mb-20">
                                    <label for="registro_conselho" class="form-label">Registro no Conselho</label>
                                    <input type="text" id="registro_conselho" name="registro_conselho" maxlength="40" class="form-control">
                                </div>
                            <?php endif; ?>

                            <!-- Especialidade -->
                            <div class="col-md-6">
                                <label for="especialidade" class="form-label">Especialidade</label>
                                <select id="especialidade" name="especialidade" class="form-control" required>
                                    <?php if ($codigo_selecao == 'mfdv'): ?>
                                        <option value="mfdv">Primeiro selecione o tipo da especialidade</option>
                                    <?php else: ?>
                                        <option value="">Primeiramente selecione se a especialidade é OTT ou STT</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Data de Habilitação -->
                            <div class="col-md-6">
                                <label for="data_habilitacao" class="form-label">
                                    Data que habilita a concorrer na especialidade
                                    <small class="text-muted">(Conclusão de curso que habilita)</small>
                                </label>
                                <input type="text" id="data_habilitacao" name="data_habilitacao" maxlength="40" class="form-control" required>
                            </div>
                        </div>

                        <!-- Botão de Submit -->
                        <div class="mt-20">
                            <button type="submit" class="btn btn-primary btn-md w-100">
                                <i class="fa fa-save me-2"></i> CADASTRAR ESPECIALIDADE
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Inscrições -->
            <div class="card shadow-sm">
                <div class="card-header bg-light mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-list-alt me-2"></i>
                        Minhas inscrições no processo seletivo
                    </span>
                </div>
                <div class="card-body">
                    <?php if (inscricao()): ?>
                        <div class="alert alert-info mb-4">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Entre em cada especialidade para cadastrar os documentos que pontuam!</strong>
                        </div>
                    <?php endif; ?>

                    <div>
                        <?php
                        $lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);

                        if (empty($lista_inscricoes)): ?>
                            <div class="col-12 text-center py-4">
                                <i class="fa fa-graduation-cap fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Nenhuma especialidade cadastrada até o momento.</p>
                            </div>
                            <?php else:
                            foreach ($lista_inscricoes as $value):
                                $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($_SESSION['id_usuario'], $value['id_especialidade']);
                                $quantidade_curriculo_adicionado = count($lista_docs_obrigatorios);
                                $pontuacao_final = 0;

                                foreach ($lista_docs_obrigatorios as $curriculo) {
                                    $data_inicio_original = null;
                                    $data_fim_original = null;
                                    $total_de_dias = null;
                                    $pontuacao = null;

                                    if ($curriculo['carga_horaria_obrigatoria'] == '1') {
                                        if ($curriculo['data_inicio'] != null)
                                            $data_inicio_original = reverte_data($curriculo['data_inicio']);
                                        if ($curriculo['data_termino'] != null)
                                            $data_fim_original = reverte_data($curriculo['data_termino']);

                                        $data_inicio = new DateTime(date($data_inicio_original));
                                        $data_fim = new DateTime(date($data_fim_original));
                                        $intervalo = $data_fim->diff($data_inicio);
                                        $total_de_dias = (int)$intervalo->format('%a');
                                        $pontuacao = ($curriculo['pontuacao'] * $total_de_dias) / 1000;
                                    } else {
                                        $pontuacao = $curriculo['pontuacao'] / 1000;
                                    }

                                    $pontuacao_final = $pontuacao_final + $pontuacao;
                                }

                                $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $value['id_especialidade']);

                                // Mapeamento dos tipos
                                $tipos_especialidade = [
                                    'ott' => "Oficial Técnico Temporário - OTT",
                                    'stt' => "Sargento Técnico Temporário - STT",
                                    'medico' => "Médico",
                                    'dentista' => "Dentista",
                                    'veterinario' => "Veterinário",
                                    'farmaceutico' => "Farmacêutico",
                                    'eipot ' => "EIPOT"
                                ];

                                $ott_stt = $tipos_especialidade[$value['ott_stt']] ?? $value['ott_stt'];
                            ?>
                                <div class="col-12">
                                    <div class="card especialidade-card <?= $value['concorrendo'] == 0 ? 'card-desclassificado' : '' ?>">
                                        <div class="card-header mb-10" style="display: flex; justify-content: space-between; align-items: center;">
                                            <div class="" style="display: flex; align-items: center;">
                                                <i class="fa fa-graduation-cap text-white mr-10 fa-lg"></i>
                                                <div>
                                                    <h4 class="card-title text-white mb-0">
                                                        <?= mb_strtoupper($value['especialidade'], 'UTF-8') ?>
                                                    </h4>
                                                    <span class="badge bg-secondary"><?= $ott_stt ?></span>
                                                </div>
                                            </div>

                                            <div style="display: flex; gap: 0.5rem;">
                                                <?php if (inscricao()): ?>
                                                    <a onclick="funcao_apagar('<?= $value['id_especialidade'] ?>', 'candidato_especialidade', '<?= $crip ?>')"
                                                        class="btn btn-sm action-btn"
                                                        data-bs-toggle="tooltip"
                                                        title="Remover especialidade">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="candidato_especialidade_cadastrada_visualiza.php?esp=<?= $value['id_especialidade'] ?>"
                                                    class="btn btn-sm action-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Ver detalhes da especialidade">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <!-- Status e Informações Principais -->
                                            <div class="row g-4 mb-20">
                                                <div class="col-md-3">
                                                    <div class="info-box">
                                                        <div class="info-icon bg-primary">
                                                            <i class="fa fa-folder-open"></i>
                                                        </div>
                                                        <div class="info-content">
                                                            <span class="info-label">Documentos</span>
                                                            <span class="info-value"><?= $quantidade_curriculo_adicionado ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="info-box">
                                                        <div class="info-icon bg-primary">
                                                            <i class="fa fa-id-card"></i>
                                                        </div>
                                                        <div class="info-content">
                                                            <span class="info-label">Registro</span>
                                                            <span class="info-value"><?= $value['registro_conselho'] ?: '--' ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($_SESSION['selecao_regiao'] == 12 || $_SESSION['selecao_regiao'] == 7): ?>
                                                    <div class="col-md-3">
                                                        <div class="info-box">
                                                            <div class="info-icon bg-primary">
                                                                <i class="fa fa-chart-line"></i>
                                                            </div>
                                                            <div class="info-content">
                                                                <span class="info-label">Pontuação</span>
                                                                <span class="info-value score-value"><?= number_format($pontuacao_final, 2) ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="col-md-3">
                                                    <div class="info-box">
                                                        <div class="info-icon bg-primary">
                                                            <i class="fa <?= $value['concorrendo'] == 0 ? 'fa-times' : 'fa-check' ?>"></i>
                                                        </div>
                                                        <div class="info-content">
                                                            <span class="info-label">Status</span>
                                                            <span class="info-value"><?= $value['concorrendo'] == 0 ? 'Desclassificado' : 'Concorrendo' ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Alertas de Ação -->
                                            <?php if ($quantidade_curriculo_adicionado == 0 && inscricao()): ?>
                                                <div class="alert alert-warning alert-action">
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="alert-icon">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h4 class="alert-title mb-0">Documentação Pendente</h4>
                                                            <p class="alert-message mb-0">
                                                                É necessário adicionar os documentos curriculares de Pré-requisitos e também, se possuir, para pontuação e classificação.
                                                            </p>
                                                        </div>
                                                        <div class="alert-action-btn">
                                                            <a href="candidato_especialidade_cadastrada_visualiza.php?esp=<?= $value['id_especialidade'] ?>"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="fa fa-plus me-1"></i> Adicionar Documentos
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif (inscricao() && $quantidade_curriculo_adicionado > 0): ?>
                                                <div class="alert alert-info alert-action">
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="alert-icon">
                                                            <i class="fa fa-info-circle"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h4 class="alert-title mb-0">Documentação Parcial</h4>
                                                            <p class="alert-message mb-0">
                                                                Você já adicionou <?= $quantidade_curriculo_adicionado ?> documento(s).
                                                                Você pode adicionar ou remover documentos até o final do prazo de Inscrição.
                                                            </p>
                                                        </div>
                                                        <div class="alert-action-btn">
                                                            <a href="candidato_especialidade_cadastrada_visualiza.php?esp=<?= $value['id_especialidade'] ?>"
                                                                class="btn btn-info btn-sm">
                                                                <i class="fa fa-plus me-1"></i>Adicionar Mais
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Motivo da Desclassificação -->
                                            <?php if ($value['concorrendo'] == 0): ?>
                                                <div class="alert alert-danger">
                                                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                                                        <i class="fa fa-ban text-danger mt-1 mr-10 fa-lg"></i>
                                                        <div>
                                                            <h4 class="alert-title mb-0">Desclassificado</h4>
                                                            <p class="mb-0 text-dark"><?= $value['justificativa'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Cidade Escolhida -->
                                            <?php if ($value['cidade_escolheu_servir'] != null):
                                                $get_cidade_escolhida = $conexao->get_cidade_id($value['cidade_escolheu_servir']);
                                                if (!empty($get_cidade_escolhida[0]['nome'])): ?>
                                                    <div class="alert alert-success">
                                                        <div style="display: flex; align-items: center; justify-content: flex-start;">
                                                            <i class="fa fa-map-marker text-success mr-10 fa-lg"></i>
                                                            <div>
                                                                <h4 class="mb-0">Localidade de Serviço</h4>
                                                                <p class="mb-0 text-dark">
                                                                    <strong><?= $get_cidade_escolhida[0]['nome'] ?></strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php endif;
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>

</html>