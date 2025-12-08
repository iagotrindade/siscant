<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 3463745! Página não encontrada!");
    exit();
}

$selecao_atual = $conexao->get_selecao_id();

if (count($selecao_atual) < 1 || ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')) {
    erro("Erro 464578! Página não encontrada!");
    exit();
}

$etapa_atual = $selecao_atual[0]['etapa'];
$lista_especialidades = $conexao->get_especialidade();

?>
<style>
    :root {
        --primary-color: #006400;
        --secondary-color: #228B22;
        --accent-color: #8B0000;
        --light-color: #F5F5F5;
        --text-color: #333333;
        --border-color: #D3D3D3;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--secondary-color);
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #004d00;
        transform: scale(1.02);
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 15px;
        border: none;
    }

    .alert-info {
        background-color: #E8F5E9;
        border-color: #C8E6C9;
        color: #2E7D32;
    }

    .alert-danger {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
    }

    .section-header {
        color: var(--primary-color);
        cursor: pointer;
    }

    .section-header h4 {
        margin: 0;
        padding: 10px 0;
        font-weight: 600;
    }

    .section-header i {
        transition: transform 0.3s ease;
    }

    .section-header[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }

    .pdf-icon {
        color: var(--accent-color);
        font-size: 1.5rem;
        transition: transform 0.2s;
    }

    .pdf-icon:hover {
        transform: scale(1.1);
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        min-height: 42px;
        padding: 5px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: var(--secondary-color);
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
    }

    .requisitos-list {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .requisitos-list li {
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .page-title h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-title">
        <div>
            <h1>Publicações e Geração de Documentos <i class="fa fa-file-pdf-o-o"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Publicações e Geração de Documentos</li>
            </ul>
        </div>
    </div>

    <!-- Etapa I -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_1" role="button" aria-expanded="false" aria-controls="et_1">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES E DOCUMENTOS → ETAPA I
            </span>
        </div>

        <div class="collapse" id="et_1">
            <div class="card-body">
                <!-- Relação Inicial/Final de Inscritos -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-list me-2"></i>
                            Relação Inicial/Final de Inscritos
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Rodar o Script de desclassificação dos candidatos</li>
                                <li>Não ter DESCLASSIFICADO nenhum candidato por CURRÍCULO</li>
                                <li>Período de Inscrições encerrado</li>
                                <li>Se estiver gerando a Relação Final, retornar para Seleção, os candidatos que tiveram seu recurso DEFERIDO antes de Gerar</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_personalizado.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="concorrendo_esp">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Principal</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Secundário</label>
                                    <input name="titulo" value="RELAÇÃO INICIAL DE INSCRITOS" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos para SELEÇÃO DE OFICIAIS TÉCNICOS TEMPORÁRIOS - OTT e SARGENTOS TÉCNICOS TEMPORÁRIOS - STT, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/X, de X de X de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control" rows="3">Os candidatos não relacionados podem verificar o motivo da não inscrição, na tela do candidato, na página inicial do SISCANT e o período para interposição de Recursos será nos dias XX e XX JUL 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial - Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado da Análise de Recursos Etapa I -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-gavel me-2"></i>
                            Resultado da Análise de Recursos Etapa I
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Cadastrar e julgar todos os recursos da Etapa I no SISCANT</li>
                                <li>Serão considerados apenas recursos cadastrados com a Etapa I</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_recursos_ott_stt_et_1_2.php" method="POST">
                            <input type="hidden" name="etapa" value="1">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA I" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa I, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa II -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_2" role="button" aria-expanded="false" aria-controls="et_2">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES E DOCUMENTOS → ETAPA II
            </span>
        </div>

        <div class="collapse" id="et_2">
            <div class="card-body">
                <!-- Convocação Etapa II - Entrevista e Teste Prático -->
                <?php if ($_SESSION['selecao_codigo'] == 'cet'): ?>
                    <div class="card border-info mb-20">
                        <div class="card-header mb-20 bg-info text-white">
                            <span class="fw-semibold">
                                <i class="fa fa-user-check me-2"></i>
                                Convocação Etapa II - Entrevista e Teste Prático
                                
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-20">
                                <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                                <ul class="requisitos-list text-dark">
                                    <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO</li>
                                    <li>Passar o SISCANT para Etapa II</li>
                                    <li>Finalizar as avaliações Curriculares</li>
                                    <li>Gerar a publicação após a Avaliação Curricular</li>
                                </ul>
                            </div>

                            <form action="mpdf/relatorio_convocacao_cet_et_2.php" method="POST">
                                <input name="tipo_relatorio" type="hidden" value="classificacao">
                                <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                                <input name="orientacao" type="hidden" value="retrato">
                                <input name="tipo_especialdiade" type="hidden" value="todas">
                                <input name="cabecalho" type="hidden" value="sim">

                                <div class="row">
                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Título</label>
                                        <input name="titulo" value="PROCESSO SELETIVO SERVIÇO MILITAR ESPECIALISTA TEMPORÁRIO 20XX/20XX" class="form-control">
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Subtítulo</label>
                                        <input name="subtitulo" value="CONVOCAÇÃO ETAPA II - ENTREVISTA E TESTE PRÁTICO" class="form-control">
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Cidade e Data</label>
                                        <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="form-label fw-semibold">1º Parágrafo</label>
                                        <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da XX convoca os candidatos classificados para a realização da Entrevista e Teste Prático, nas especialidades para CABO ESPECIALISTA TEMPORÁRIO, conforme anexo "A" (Calendário Geral de Atividades), do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="form-label fw-semibold">2º Parágrafo</label>
                                        <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="3">A presente relação NÃO está em ordem de Classificação.</textarea>
                                    </div>

                                    <?php foreach ($lista_especialidades as $especialidade):
                                        $pula = true;
                                        $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
                                        foreach ($candidatos as $candidato) {
                                            if ($candidato['etapa'] == 2) {
                                                $pula = false;
                                            }
                                        }
                                        if ($pula) continue;
                                    ?>
                                        <div class="col-lg-12 mb-20">
                                            <label class="form-label fw-semibold">Local de Realização - <?= mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome']) ?></label>
                                            <textarea name="agenda_especialidade[<?= $especialidade['id'] ?>]" class="form-control" rows="3">Os candidatos classificados para o CET/20XX na especialidade <?= mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome']) ?>, abaixo discriminados, deverão apresentar-se no 19° Batalhão de Infantaria Motorizado (19º BI Mtz), localizado na Av. Theodomiro Porto da Fonseca, 894-946 - Centro, São Leopoldo - RS, 93020-654, às 0800h do dia 20 AGO 25.</textarea>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-file-export me-2"></i> GERAR
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Presença Etapa II - Entrevista e Teste Prático -->
                    <div class="card border-info mb-20">
                        <div class="card-header mb-20 bg-info text-white">
                            <span class="fw-semibold">
                                <i class="fa fa-clipboard-list me-2"></i>
                                Lista de Presença Etapa II - Entrevista e Teste Prático
                                
                            </span>
                        </div>
                        <div class="card-body">
                            <form action="mpdf/relatorio_lista_presenca_cet_et_2.php" method="POST">
                                <input name="tipo_relatorio" type="hidden" value="classificacao">
                                <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                                <input name="orientacao" type="hidden" value="retrato">
                                <input name="tipo_especialdiade" type="hidden" value="todas">
                                <input name="cabecalho" type="hidden" value="sim">

                                <div class="row">
                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Título</label>
                                        <input name="titulo" value="PROCESSO SELETIVO SERVIÇO MILITAR ESPECIALISTA TEMPORÁRIO 20XX/20XX" class="form-control">
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Subtítulo</label>
                                        <input name="subtitulo" value="LISTA DE PRESENÇA ETAPA II - ENTREVISTA E TESTE PRÁTICO" class="form-control">
                                    </div>

                                    <?php foreach ($lista_especialidades as $especialidade):
                                        $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
                                        $tem_candidato_etapa2 = false;
                                        foreach ($candidatos as $candidato) {
                                            if ($candidato['etapa'] == 2) {
                                                $tem_candidato_etapa2 = true;
                                                break;
                                            }
                                        }
                                        if (!$tem_candidato_etapa2) continue;
                                    ?>
                                        <div class="col-lg-12 mb-20">
                                            <label class="form-label fw-semibold"><?= mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome']) ?></label>
                                            <input type="text" name="agenda_especialidade[<?= $especialidade['id'] ?>]" class="form-control" value="XX OUT 25, ÀS XX:XXh">
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-file-export me-2"></i> GERAR
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Resultado Inicial/Final Etapa II - Avaliação Curricular -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-bar-chart me-2"></i>
                            Resultado Inicial/Final Etapa II - Avaliação Curricular
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO</li>
                                <li>Passar o SISCANT para Etapa II</li>
                                <li>Finalizar as avaliações Curriculares</li>
                                <li>Se estiver gerando a Relação Final, realizar as alterações decorrentes dos recursos DEFERIDOS na Avaliação Curricular antes de Gerar</li>
                                <li>Bloquear a Avaliação Curricular nas Configurações</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_personalizado.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Principal</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA XX 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Secundário</label>
                                    <input name="titulo" value="RELAÇÃO DE CANDIDATOS POR ORDEM DE CLASSIFICAÇÃO E POR ESPECIALIDADE - ETAPA II" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da XX divulga a relação dos candidatos inscritos, por ordem de classificação nas especialidades para XX, após análise de recursos da Etapa II conforme anexo “A” (Calendário Geral de Atividades), do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado da Análise de Recursos Etapa II -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-gavel me-2"></i>
                            Resultado da Análise de Recursos Etapa II
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Cadastrar e julgar todos os recursos da Etapa II no SISCANT</li>
                                <li>Serão considerados apenas recursos cadastrados com a Etapa II</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_recursos_ott_stt_et_1_2.php" method="POST">
                            <input type="hidden" name="etapa" value="2">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA II" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da XX divulga o parecer da análise de recursos referente à Etapa II, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa III -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_3" role="button" aria-expanded="false" aria-controls="et_3">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES E DOCUMENTOS → ETAPA III
            </span>
        </div>

        <div class="collapse" id="et_3">
            <div class="card-body">
                <!-- Cronograma de Convocação Etapa III -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-calendar me-2"></i>
                            Cronograma de Convocação Etapa III
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Passar o SISCANT para Etapa III</li>
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO e na ETAPA III da ESPECIALIDADE</li>
                                <li>Se o candidato estiver concorrendo em mais de uma especialidade, ele será convocado na menor data de suas especialidades</li>
                                <li>A Lista de Presença será incluida no fim do documento, sendo necessário a separação pelo Operador</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_convocacao_et_3.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O XX 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="CONVOCAÇÃO PARA ETAPA III - INSPEÇÃO DE SAÚDE" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Capacidade Máxima por Turno</label>
                                    <input name="capacidade_turno" type="number" value="50" min="1" max="100" class="form-control">
                                    <div class="form-text">Máximo de candidatos por turno</div>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data de Início</label>
                                    <input name="data_inicio" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('next monday')) ?>">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data Final</label>
                                    <input name="data_final" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('+2 weeks')) ?>">
                                    <div class="form-text">Período para distribuir os agendamentos</div>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2"><i class="fa fa-info-circle me-1"></i> Distribuição Automática</h6>
                                        <p class="mb-2">O sistema distribuirá automaticamente os candidatos por turnos e datas:</p>
                                        <ul class="mb-0">
                                            <li>Máximo de <span id="capacidade-value">50</span> candidatos por turno</li>
                                            <li>Dois turnos por dia (Manhã: 08:00h, Tarde: 13:00h)</li>
                                            <li>Sexta-feira apenas turno da manhã</li>
                                            <li>Candidatos com múltiplas especialidades no mesmo dia</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">De acordo com o Aviso de Convocação Nr XX-SSMR/X, de X de junho de 20XX, CONVOCO os candidatos abaixo relacionados, para comparecimento presencial à Comissão de Seleção Especial para Serviço Técnico Temporário (CSE/SvTT), localizada na Rua Bento Martins Nº 45 - Centro - Porto Alegre-RS, nas DATAS e HORÁRIOS abaixo, munidos da documentação conforme inserida no SISCANT e exames de saúde previstos para a Etapa III do processo seletivo, todas as certidões solicitadas no "Anexo X" deverão estar atualizadas.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">Informo que o candidato deverá observar a especialidade, data e hora de apresentação, munido de caneta azul ou preta para uso individual.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control" rows="2">Será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à chamada para la Etapa III.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control" rows="2">Informo, também, que os candidatos NÃO CONVOCADOS nesta lista poderão ser chamados para convocações posteriores.</textarea>
                                </div>

                                <?php foreach ($lista_especialidades as $especialidade):
                                    $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
                                    foreach ($candidatos as $index => $candidato) {
                                        if ($candidato['etapa'] < 3) {
                                            unset($candidatos[$index]);
                                        }
                                    }
                                    $qtdCandidatos = count($candidatos);
                                ?>
                                    <div class="col-lg-12 mb-20">
                                        <label class="form-label fw-semibold">Total de Convocados - <?= mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome']) ?></label>
                                        <input type="text" name="qtd_especialidade[<?= $especialidade['id'] ?>]" class="form-control" value="<?= $qtdCandidatos ?>">
                                    </div>
                                <?php endforeach; ?>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR CRONOGRAMA AUTOMÁTICO
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Etiquetas Envelopes Etapa III -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-tags me-2"></i>
                            Etiquetas Envelopes Etapa III
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_etiquetas_envelopes_et_3.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <div class="row">
                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="ETIQUETAS ENVELOPES ETAPA III" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Dados cadastro SIPMED III -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-stethoscope me-2"></i>
                            Dados dos Candidatos para Cadastro no SIPMED
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="excel_informacoes_sipmed.php" method="POST">
                            <div class="row">
                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" maxlength="100" class="form-control" value="Dados Cadastro para Inspeção de Saúde no SIPMED - Xª RM">
                                </div>
                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Parágrafo</label>
                                    <input name="paragrafo_um" maxlength="100" class="form-control" value="Tendo em vista a convocação dos candidatos OTT/STT 20XX/20XX, abaixo relacionados, para la realização de Inspeção de Saúde na Xª RM, solicito que os mesmos sejam cadastrados no Sistema de Perícias Médicas - SIPMED para la realização das Inspeções de Saúde no período entre XX a XX Maio XX.">
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Etapa III - Inspeção de Saúde -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-heartbeat me-2"></i>
                            Resultado Etapa III - Inspeção de Saúde
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Lançar os Resultados da Inspeção de saúde no perfil dos candidatos</li>
                                <li>NÃO TER DESCLASSIFICADO nenhum candidato que realizou a inspeção de saúde</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_ott_stt_et_3.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO ETAPA III" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial da Inspeção</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final da Inspeção</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado referente à Etapa III, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="3">O período para interposição de Recursos da Etapa III será nos dias XX e XX de outubro de 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial - Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado da Análise de Recursos Etapa III -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-gavel me-2"></i>
                            Resultado da Análise de Recursos Etapa III
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Cadastrar e julgar todos os recursos da Etapa III no SISCANT</li>
                                <li>Serão considerados apenas recursos cadastrados com a Etapa III</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_recursos_ott_stt_et_3.php" method="POST">
                            <input type="hidden" name="etapa" value="3">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA III E CONVOCAÇÃO PARA ISGREC" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa III e convoca para Inspeção de Saúde em Grau de Recurso (ISGR), conforme anexo "A" (Calendário Geral de Atividades) do AVISO DE CONVOCAÇÃO PARA SELEÇÃO Nr X – SSMR/X, DE X DE JUNHO DE 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial do Recurso</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final do Recurso</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1ª OM</label>
                                    <select name="primeira_om" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <?php
                                        $oms = $conexao->get_oms($rm_usuario);
                                        foreach ($oms as $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial - Primeiro Local</label>
                                    <input name="data_inicial_primeiro_local" type="text" class="form-control" value="27/10/2025">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data Final - Primeiro Local</label>
                                    <input name="data_final_primeiro_local" type="text" class="form-control" value="29/10/2025">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Horário - Primeiro Local</label>
                                    <input name="horario_primeiro_local" type="text" class="form-control" value="0800h">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2ª OM</label>
                                    <select name="segunda_om" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <?php
                                        $oms = $conexao->get_oms($rm_usuario);
                                        foreach ($oms as $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial - Segundo Local</label>
                                    <input name="data_inicial_segundo_local" type="text" class="form-control" value="27/10/2025">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data Final - Segundo Local</label>
                                    <input name="data_final_segundo_local" type="text" class="form-control" value="29/10/2025">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Horário - Segundo Local</label>
                                    <input name="horario_segundo_local" type="text" class="form-control" value="0800h">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado ISGREC -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-stethoscope me-2"></i>
                            Resultado da Inspeção de Saúde em Grau de Recurso
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Rodar o Script de Desclassificação dos candidatos INAPTOS na Etapa III</li>
                                <li>Lançar o Resultado das Inspeções no SISCANT</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_inspecao_saude_recurso.php" method="POST">
                            <input type="hidden" name="etapa" value="3">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO INSPEÇÃO DE SAÚDE EM GRAU DE RECURSO" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o Resultado da Inspeção de Saúde em Grau de Recurso, conforme anexo "A" (Calendário Geral de Atividades) do AVISO DE CONVOCAÇÃO PARA SELEÇÃO Nr X – SSMR/X, DE X DE JUNHO DE 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial da Inspeção</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final da Inspeção</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Final Etapa III -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-flag-checkered me-2"></i>
                            Resultado Final Etapa III
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO</li>
                                <li>Passar o SISCANT para Etapa IV</li>
                                <li>Retornar ao Processo Seletivo os candidato APTOS em JISR</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_final_et3.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Principal</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA XX 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Secundário</label>
                                    <input name="titulo" value="RESULTADO FINAL - ETAPA III" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos, por ordem de classificação nas especialidades para OFICIAL TÉCNICO TEMPORÁRIO (OTT) e SARGENTO TÉCNICO TEMPORÁRIO (STT), conforme anexo "A" (Calendário Geral de Atividades), do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control" rows="3">O período para interposição de Recursos da Etapa III será no dia XX de agosto de 20XX das 0930h às 1130h e das 1300h às 1630h e no dia XX de agosto de 20XX das 0930h às 1130h, na Comissão de Seleção Especial - Rua dos Andradas 551, Centro Histórico, Porto Alegre. O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado da Análise de Recursos Etapa III - DOCUMENTAL -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-gavel me-2"></i>
                            Resultado da Análise de Recursos Etapa III - Documental
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Cadastrar e julgar todos os recursos da Etapa 3 - Documental no SISCANT</li>
                                <li>Serão considerados apenas recursos cadastrados com a Etapa 3 - Documental</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_recursos_ott_stt_et_final_3.php" method="POST">
                            <input type="hidden" name="etapa" value="3">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS FINAL ETAPA III" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente ao Resultado Final da Etapa III, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial</label>
                                    <input name="data_inicial" type="date" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Data Final</label>
                                    <input name="data_final" type="date" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa IV -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_4" role="button" aria-expanded="false" aria-controls="et_4">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES E DOCUMENTOS → ETAPA IV
            </span>
        </div>

        <div class="collapse" id="et_4">
            <div class="card-body">
                <!-- Convocações Teste de Conhecimento -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Convocações Teste de Conhecimento OTT Informática e STT Instrumento Musical
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_convocacao_teste_informatica_musica.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <input name="tipo_relatorio" type="hidden" value="concorrendo_esp">
                                    <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                    <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                                    <input name="orientacao" type="hidden" value="retrato">
                                    <input name="tipo_especialdiade" type="hidden" value="todas">
                                    <input name="cabecalho" type="hidden" value="sim">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">Título Principal</label>
                                        <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Secundário</label>
                                    <input name="titulo" value="RESULTADO DO TESTE DE CONHECIMENTO OTT - INFORMÁTICA E STT - INSTRUMENTO MUSICAL" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" class="form-control" rows="3">De acordo com o Aviso de Convocação Nr XX-SSMR/X, de X de junho de 20XX, CONVOCO os candidatos abaixo relacionados, para comparecimento presencial aos locais de realização das provas teóricas e/ou práticas, nas datas abaixo informadas, munidos de documento de identificação and caneta azul ou preta para uso individual.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">Os candidatos convocados devem observar as orientações descritas no Art 27 do Aviso de Convocação para realização dos testes.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">No caso dos candidatos a STT Instrumento Musical, as provas serão realizadas nos dias 22, 23, 24 and 25. O candidato será orientado sobre os demais horários presencialmente no dia 22 JUL XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">Informo que será ELIMINADO o candidato que NÃO COMPARECER à chamada para o Teste de Conhecimento Teórico/Prático.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">5º Parágrafo</label>
                                    <textarea name="paragrafo_cinco" class="form-control" rows="3">Os candidatos a OTT - INFORMÁTICA (PROGRAMAÇÃO PHP) devem apresentar-se na R. dos Andradas, 551 – Centro Histórico, Porto Alegre – RS, 90020-001 22 JUL 24, às 0930 h</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">6º Parágrafo</label>
                                    <textarea name="paragrafo_seis" class="form-control" rows="3">Os candidatos a OTT - INFORMÁTICA (SERVIDORES E REDES) devem apresentar-se na R. dos Andradas, 551 – Centro Histórico, Porto Alegre – RS, 90020-001 22 JUL 23, às 1030 h</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">7º Parágrafo</label>
                                    <textarea name="paragrafo_sete" class="form-control" rows="3">Os candidatos a STT - INSTRUMENTO MUSICAL devem apresentar-se na R. Corrêa Lima, 550 – Santa Tereza, Porto Alegre – RS, 90850-250 22 JUL 23, às 0800 h</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">8º Parágrafo</label>
                                    <textarea name="paragrafo_oito" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple required>
                                        <?php foreach ($lista_especialidades as $value):
                                            if (!$value['teste_pratico']) continue;
                                        ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Teste de Conhecimento -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado Teste de Conhecimento OTT Informática e STT Instrumento Musical
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_resultado_teste_informatica_musica.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO DO TESTE DE CONHECIMENTO OTT - INFORMÁTICA E STT - INSTRUMENTO MUSICAL" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado do Teste de Conhecimento Teórico, Prático e Oral, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr XX - SSMR/X, de X de junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">A interposição de recursos será nos dias XX and XX de julho de 20XX, das 0930 às 1130 horas and das 1300 às 1630 horas, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple required>
                                        <?php foreach ($lista_especialidades as $value):
                                            if (!$value['teste_pratico']) continue;
                                        ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Convocação EAF -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Convocação para o Exame de Aptidão Física (EAF)   
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa IV</li>
                                <li>Passar o SISCANT para Etapa IV</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_convocacao_et_4.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="CONVOCAÇÃO PARA O EXAME DE APTIDÃO FÍSICA (EAF)" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga a chamada dos candidatos selecionados para a Etapa IV – EXAME DE APTIDÃO FÍSICA, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de 03 de Junho de 2024.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="3">Informo que será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à Etapa IV.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control" rows="3">Todos os candidatos deverão utilizar trajes desportivos para realização dos exames de aptidão física.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control" rows="3">O candidato deverá apresentar, obrigatoriamente, documento de identificação com foto e atestado médico emitido por profissional da área de cardiologia, inscrito no CRM, assinado e carimbado, com data de até 20 (vinte) dias antes do início da Etapa IV, atestando que o candidato está apto a realizar o EAF. A não entrega de atestado médico, quando da apresentação do candidato, conforme acima descrito, inviabiliza sua participação no EAF, tendo por objetivo preservar sua saúde.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">5º Parágrafo</label>
                                    <textarea name="paragrafo_cinco" placeholder="5º Parágrafo do relatório" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Texto Grupo Um</label>
                                    <textarea name="grupo_um_texto" class="form-control" rows="3">XX NOV XX às 0700 h –Rua Corrêa Lima, 140 –Menino Deus, Porto Alegre –RS –Centro de Preparação de Oficiais da Reserva</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Especialidades Grupo Um</label>
                                    <select name="grupo_um_especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Texto Grupo Dois</label>
                                    <textarea name="grupo_dois_texto" class="form-control" rows="3">XX NOV XX às 1300 h –Rua Corrêa Lima, 140 –Menino Deus, Porto Alegre –RS –Centro de Preparação de Oficiais da Reserva</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Especialidades Grupo Dois</label>
                                    <select name="grupo_dois_especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de Presença EAF -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-clipboard me-2"></i>
                            Lista de Presença para o Exame de Aptidão Física (EAF)
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A lista irá considerar somente os candidatos que estão CONCORRENDO na Etapa IV</li>
                                <li>Passar o SISCANT para Etapa IV</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_lista_presenca_et_4.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="LISTA DE PRESENÇA PARA O EXAME DE APTIDÃO FÍSICA (EAF)" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Texto Grupo Um</label>
                                    <textarea name="grupo_um_texto" class="form-control" rows="3">XX NOV XX às 0700 h –Rua Corrêa Lima, 140 –Menino Deus, Porto Alegre –RS –Centro de Preparação de Oficiais da Reserva</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Especialidades Grupo Um</label>
                                    <select name="grupo_um_especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Texto Grupo Dois</label>
                                    <textarea name="grupo_dois_texto" class="form-control" rows="3">XX NOV XX às 1300 h –Rua Corrêa Lima, 140 –Menino Deus, Porto Alegre –RS –Centro de Preparação de Oficiais da Reserva</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Especialidades Grupo Dois</label>
                                    <select name="grupo_dois_especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado EAF -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado do Exame de Aptidão Física (EAF)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa IV</li>
                                <li>Lançar os resultados do EAF</li>
                                <li>Não desclassificar nenhum candidato que esteja na Etapa IV</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_et_4_eaf.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO EXAME DE APTIDÃO FÍSICA (EAF)" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado da Etapa IV – EXAME DE APTIDÃO FÍSICA, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de 03 de Junho de 2024.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O período para interposição de Recursos será nos dias XX NOV XX e XX DEZ XX das 0830h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Teste Práticos (APTO/INAPTO/NÃO COMPARECEU) -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado dos Testes Teóricos/Práticos (APTO/INAPTO/NÃO COMPARECEU)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa IV</li>
                                <li>Lançar os resultados dos Testes</li>
                                <li>Não desclassificar nenhum candidato que esteja na Etapa IV</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_et_4_testes.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">
                            <input name="orientacao" type="hidden" value="retrato">
                            <input name="tipo_especialdiade" type="hidden" value="todas">
                            <input name="cabecalho" type="hidden" value="sim">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO TESTE PRÁTICO/TEÓRICO" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado da Etapa IV – Teste Teórico/Prático, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de XX de Junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O período para interposição de Recursos será nos dias XX NOV XX e XX DEZ XX das 0830h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Teste Práticos (NOTA) -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado dos Testes Teóricos/Práticos (NOTA)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa IV</li>
                                <li>Lançar os resultados dos Testes</li>
                                <li>Não desclassificar nenhum candidato que esteja na Etapa IV</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_et_4_testes_notas.php" method="POST">
                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO TESTE PRÁTICO/TEÓRICO" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado da Etapa IV – Teste Teórico/Prático, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de XX de Junho de 20XX.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O período para interposição de Recursos será nos dias XX NOV XX e XX DEZ XX das 0830h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapa V -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_5" role="button" aria-expanded="false" aria-controls="et_5">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES E DOCUMENTOS → ETAPA V
            </span>
        </div>

        <div class="collapse" id="et_5">
            <div class="card-body">
                <!-- Convocação para Heteroidentificação -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-calendar me-2"></i>
                            Convocação para Heteroidentificação
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>Passar o SISCANT para Etapa V</li>
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO e na ETAPA V da ESPECIALIDADE</li>
                                <li>Se o candidato estiver concorrendo em mais de uma especialidade, ele será convocado na menor data de suas especialidades</li>
                                <li>A Lista de Presença será incluida no fim do documento, sendo necessário a separação pelo Operador</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_convocacao_heteroidentificacao.php" method="POST">
                            <input name="etapa" type="hidden" value="<?= $etapa_atual ?>">

                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O XX 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="CONVOCAÇÃO PARA ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Capacidade Máxima por Turno</label>
                                    <input name="capacidade_turno" type="number" value="50" min="1" max="100" class="form-control">
                                    <div class="form-text">Máximo de candidatos por turno</div>
                                </div>

                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Data de Início</label>
                                    <input name="data_inicio" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('next monday')) ?>" style="height: 42px;">
                                </div>

                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Data Final</label>
                                    <input name="data_final" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('+2 weeks')) ?>" style="height: 42px;">
                                </div>

                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Início do Turno da Manhã</label>
                                    <input name="inicio_manha" type="text" class="form-control" value="0800h">
                                </div>

                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Início do Turno da Tarde</label>
                                    <input name="inicio_tarde" type="text" class="form-control" value="1300h">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2"><i class="fa fa-info-circle me-1"></i> Distribuição Automática</h6>
                                        <p class="mb-2">O sistema distribuirá automaticamente os candidatos por turnos e datas:</p>
                                        <ul class="mb-0">
                                            <li>Máximo de <span id="capacidade-value">50</span> candidatos por turno</li>
                                            <li>Dois turnos por dia (Manhã: 08:00h, Tarde: 13:00h)</li>
                                            <li>Sexta-feira apenas turno da manhã</li>
                                            <li>Candidatos com múltiplas especialidades no mesmo dia</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">De acordo com o Aviso de Convocação Nr XX-SSMR/X, de X de junho de 20XX, CONVOCO os candidatos abaixo relacionados, para comparecimento presencial ao local de realização da Heteroidentificação Complementar, nas datas abaixo informadas.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control" rows="2">Os candidatos convocados devem observar as orientações descritas no Capítulo IX do Aviso de Convocação para realização da Heteroidentificação Complementar.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control" rows="2">Informo que o candidato que NÃO COMPARECER à chamada para ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR, passarão a concorrer as vagas somente no critério da Ampla Concorrência.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control" rows="2">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Heteroidentificação -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado Etapa V - Heteroidentificação Complementar
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa V</li>
                                <li>A Comissão deve lançar os 5 pareceres</li>
                                <li>Não desclassificar nenhum candidato que esteja na Etapa V</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_et_5_heteroidentificacao.php" method="POST">
                            <input name="fase" type="hidden" value="1">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado da Etapa V – HETEROIDENTIFICAÇÃO COMPLEMENTAR, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de 03 de Junho de 2024.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O período para interposição de Recursos será nos dias XX NOV XX e XX DEZ XX das 0830h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultado Heteroidentificação Revisora -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-pencil-square-o me-2"></i>
                            Resultado Etapa V - Heteroidentificação Revisora
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-20">
                            <h6 class="text-dark mb-2"><i class="fa fa-exclamation-circle me-1"></i> Requisitos/Detalhamento</h6>
                            <ul class="requisitos-list text-dark">
                                <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO na Etapa V</li>
                                <li>A Comissão Revisora deve lançar os 3 pareceres</li>
                                <li>Não desclassificar nenhum candidato que esteja na Etapa V</li>
                            </ul>
                        </div>

                        <form action="mpdf/relatorio_resultado_et_5_heteroidentificacao.php" method="POST">
                            <input name="fase" type="hidden" value="2">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" value="RESULTADO ETAPA V - HETEROIDENTIFICAÇÃO REVISORA" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control" rows="3">O Comandante da Xª Região Militar divulga o resultado da Etapa V – HETEROIDENTIFICAÇÃO REVISORA, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação para Seleção Nr 03 –SSMR/3, de 03 de Junho de 2024.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_dois" class="form-control" rows="3">O período para interposição de Recursos será nos dias XX NOV XX e XX DEZ XX das 0830h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_tres" class="form-control" rows="3">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">4º Parágrafo</label>
                                    <textarea name="paragrafo_quatro" class="form-control" rows="3">A presente relação NÃO está em ordem de classificação.</textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publicações Personalizadas -->
    <div class="card dashboard-card mb-20">
        <div class="card-header mb-20 dashboard-header" data-bs-toggle="collapse" href="#et_personalizada" role="button" aria-expanded="false" aria-controls="et_personalizada">
            <span class="card-title mb-0">
                <i class="fa fa-chevron-down me-2"></i>
                PUBLICAÇÕES PERSONALIZADAS/OUTROS
            </span>
        </div>

        <div class="collapse" id="et_personalizada">
            <div class="card-body">
                <!-- Candidatos na Seleção -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-users me-2"></i>
                            Candidatos na Seleção
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_personalizado.php" method="POST">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Tipo de Relatório</label>
                                    <select name="tipo_relatorio" class="form-control">
                                        <option value="nao_concorrendo_lista">Eliminados da Etapa I</option>
                                        <option value="concorrendo_esp">Concorrendo na seleção (Por Especialidade)</option>
                                        <option value="nao_concorrendo_esp">NÃO concorrendo na seleção (Por Especialidade)</option>
                                        <option selected value="classificacao">Classificação dos candidatos</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Mostrar Especialidade</label>
                                    <select name="mostrar_especialidade" class="form-control">
                                        <option selected value="mostrar_especialidade">MOSTRAR especialidade mesmo quando não tiver candidatos</option>
                                        <option value="nao_mostrar_especialidade">NÃO MOSTRAR especialidade quando não tiver candidatos</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Etapa</label>
                                    <select name="etapa" class="form-control">
                                        <option <?= $etapa_atual == '1' ? 'selected' : '' ?> value="1">ETAPA I</option>
                                        <option <?= $etapa_atual == '2' ? 'selected' : '' ?> value="2">ETAPA II</option>
                                        <option <?= $etapa_atual == '3' ? 'selected' : '' ?> value="3">ETAPA III</option>
                                        <option <?= $etapa_atual == '4' ? 'selected' : '' ?> value="4">ETAPA IV</option>
                                        <option <?= $etapa_atual == '5' ? 'selected' : '' ?> value="5">ETAPA V</option>
                                        <option <?= $etapa_atual == '6' ? 'selected' : '' ?> value="6">ETAPA VI</option>
                                        <option <?= $etapa_atual == '7' ? 'selected' : '' ?> value="7">ETAPA VII</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Orientação</label>
                                    <select name="orientacao" class="form-control">
                                        <option selected value="retrato">Retrato</option>
                                        <option value="paisagem">Paisagem</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Tipo de Especialidade</label>
                                    <select name="tipo_especialdiade" class="form-control">
                                        <option selected value="todas">Todas especialidades</option>
                                        <option <?= $_SESSION['selecao_codigo'] == 'ott_stt' ? 'hidden' : '' ?> value="sem_medicos">Relatórios sem os médicos</option>
                                        <option <?= $_SESSION['selecao_codigo'] == 'ott_stt' ? 'hidden' : '' ?> value="somente_medicos">Relatório somente com médicos</option>
                                        <option <?= $_SESSION['selecao_codigo'] == 'mfdv' ? 'hidden' : '' ?> value="sem_musicos">Relatório sem os Músicos</option>
                                        <option <?= $_SESSION['selecao_codigo'] == 'mfdv' ? 'hidden' : '' ?> value="somente_musicos">Relatório somente Músicos</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cabeçalho</label>
                                    <select name="cabecalho" class="form-control">
                                        <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                        <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Principal</label>
                                    <input name="titulo_1" value="TÍTULO PRINCIPAL" class="form-control" placeholder="TÍTULO PRINCIPAL">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Título Secundário</label>
                                    <input name="titulo_2" value="Título Secundário" class="form-control" placeholder="Título Secundário">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cidade e Data</label>
                                    <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">1º Parágrafo</label>
                                    <textarea name="paragrafo_1" class="form-control" rows="3">O Comandante da 3a Região Militar divulga a relação dos candidatos voluntários no processo seletivo ...</textarea>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">2º Parágrafo</label>
                                    <textarea name="paragrafo_2" class="form-control" rows="3">Outrossim, todos os candidatos elencados na relação abaixo estão convocados a comparecer ...</textarea>
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">3º Parágrafo</label>
                                    <textarea name="paragrafo_3" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Selecione as Especialidades</label>
                                    <select name="especialidades[]" class="form-control select2" multiple>
                                        <?php foreach ($lista_especialidades as $value): ?>
                                            <option value="<?= htmlspecialchars($value['id']) ?>">
                                                <?= htmlspecialchars($value['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quem realizou o exame de saúde em determinada data -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-stethoscope me-2"></i>
                            Quem realizou o exame de saúde em determinada data
                            
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_inspecao_saude.php" method="POST">
                            <div class="row">
                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Data Inicial Inspeção</label>
                                    <input name="data_inicial_inspecao" class="form-control" placeholder="Data da Inspeção de Saúde">
                                </div>

                                <div class="col-lg-2 mb-20">
                                    <label class="form-label fw-semibold">Data Final Inspeção</label>
                                    <input name="data_final_inspecao" class="form-control" placeholder="Data da Inspeção de Saúde">
                                </div>

                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Orientação</label>
                                    <select name="orientacao" class="form-control">
                                        <option value="retrato">Retrato</option>
                                        <option selected value="paisagem">Paisagem</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cabeçalho</label>
                                    <select name="cabecalho" class="form-control">
                                        <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                        <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quem realizou o exame de saúde em determinada data - SOMENTE MÉDICOS OBRIGATÓRIOS -->
                <?php if ($_SESSION['selecao_codigo'] == 'mfdv'): ?>
                    <div class="card border-info mb-20">
                        <div class="card-header mb-20 bg-info text-white">
                            <span class="fw-semibold">
                                <i class="fa fa-user-md me-2"></i>
                                Quem realizou o exame de saúde em determinada data - SOMENTE MÉDICOS OBRIGATÓRIOS
                                
                            </span>
                        </div>
                        <div class="card-body">
                            <form action="mpdf/relatorio_inspecao_saude_medicos_obrigatorios.php" method="POST">
                                <div class="row">
                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Data da Inspeção de Saúde</label>
                                        <input name="data_inspecao" class="form-control" placeholder="Data da Inspeção de Saúde">
                                    </div>
                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Orientação</label>
                                        <select name="orientacao" class="form-control">
                                            <option value="retrato">Retrato</option>
                                            <option selected value="paisagem">Paisagem</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-20">
                                        <label class="form-label fw-semibold">Cabeçalho</label>
                                        <select name="cabecalho" class="form-control">
                                            <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                            <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-file-export me-2"></i> GERAR
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Candidatos Autodeclarados Cotistas -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-circle me-2"></i>
                            Candidatos Autodeclarados Cotistas
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/relatorio_cotistas.php" method="POST">
                            <div class="row">
                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input name="titulo" class="form-control" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="form-label fw-semibold">Subtítulo</label>
                                    <input name="subtitulo" class="form-control" value="RELAÇÃO DE CANDIDATOS AUTODECLARADOS COTISTAS">
                                </div>

                                <div class="col-lg-12 mb-20">
                                    <label class="form-label fw-semibold">Parágrafo</label>
                                    <input name="paragrafo_um" class="form-control" value="Nas tableas abaixo estão listados os candidatos que se autodeclararão como cotistas no momento da Inscrição">
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Agenda de comparecimento -->
                <div class="card border-info mb-20">
                    <div class="card-header mb-20 bg-info text-white">
                        <span class="fw-semibold">
                            <i class="fa fa-calendar me-2"></i>
                            Agenda de comparecimento
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="mpdf/agenda_comparecimento.php" method="POST">
                            <div class="row">
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Data de Comparecimento</label>
                                    <input name="data_inspecao" class="form-control" placeholder="Data de Comparecimento">
                                </div>
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Orientação</label>
                                    <select name="orientacao" class="form-control">
                                        <option value="retrato">Retrato</option>
                                        <option selected value="paisagem">Paisagem</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mb-20">
                                    <label class="form-label fw-semibold">Cabeçalho</label>
                                    <select name="cabecalho" class="form-control">
                                        <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                        <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-file-export me-2"></i> GERAR RELATÓRIO
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/plugins/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            allowClear: true,
            width: '100%'
        });

        $('input[name="capacidade_turno"]').on('input', function() {
            $('#capacidade-value').text($(this).val());
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Selecionar todos os headers de acordeão
        const accordionHeaders = document.querySelectorAll('.card-header.dashboard-header');

        // Adicionar evento de clique a cada header
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                // Encontrar o alvo do collapse
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                const icon = this.querySelector('i');

                // Alternar a classe 'show' no alvo
                if (target.classList.contains('show')) {
                    target.classList.remove('show');
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                } else {
                    target.classList.add('show');
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                }
            });
        });

        // Inicializar Select2 (se estiver usando)
        if (typeof $ !== 'undefined' && $.fn.select2) {
            document.querySelectorAll('.select2').forEach(select => {
                $(select).select2({
                    allowClear: true,
                    width: '100%'
                });
            });
        }

        // Atualizar capacidade (se existir)
        const capacidadeInput = document.querySelector('input[name="capacidade_turno"]');
        if (capacidadeInput) {
            capacidadeInput.addEventListener('input', function() {
                const span = document.getElementById('capacidade-value');
                if (span) span.textContent = this.value;
            });
        }
    });
</script>

</body>

</html>
<?php $conexao = null; ?>