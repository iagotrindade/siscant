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

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <!-- 04/08/2025 -> Iago Silva Alterando o título da página -->
            <h1>Publicações e Geração de Documentos <i class="fa fa-file-pdf-o"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <!-- 04/08/2025 -> Iago Silva Alterando o título da página -->
                <li>Publicações e Geração de Documentos</li>
            </ul>
        </div>
    </div>

    <!-- Publicações Etapa I -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <h4 class="col-md-12">
                        <a data-toggle="collapse" href="#et_1">
                            PUBLICAÇÕES E DOCUMENTOS -> ETAPA I
                        </a>
                    </h4>
                </div>


                <div class="row collapse" id="et_1">
                    <div class="col-md-12">
                        <!-- Relação Inicial/Final de Inscritos -->
                        <div class="alert alert-info">
                            <legend>Relação Inicial/Final de Inscritos <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">

                                <label class="text-danger">Requisitos/Detalhamento</label>
                                <div class="alert" style="text-align: left;">
                                    <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                        <li>Rodar o Script de desclassificação dos candidatos</li>
                                        <li>Não ter DESCLASSIFICADO nenhum candidato por CURRÍCULO</li>
                                        <li>Período de Inscrições encerrado</li>
                                        <li>Se estiver gerando a Relação Final, retornar para Seleção, os candidatos que tiveram seu recurso DEFERIDO antes de Gerar</li>
                                    </ul>
                                </div>

                                <form action="mpdf/relatorio_personalizado.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <input name="tipo_relatorio" type="hidden" value="concorrendo_esp">
                                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                            <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                            <input name="orientacao" type="hidden" value="retrato">
                                            <input name="tipo_especialdiade" type="hidden" value="todas">
                                            <input name="cabecalho" type="hidden" value="sim">
                                            <div class="form-group">
                                                <input name="titulo_1" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo_2" value="RELAÇÃO INICIAL DE INSCRITOS" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos para SELEÇÃO DE OFICIAIS TÉCNICOS TEMPORÁRIOS - OTT e SARGENTOS TÉCNICOS TEMPORÁRIOS - STT, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/X, de X de X de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control">Os candidatos não relacionados podem verificar o motivo da não inscrição, na tela do candidato, na página inicial do SISCANT e o período para interposição de Recursos será nos dias XX e XX JUL 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Selecione as Especialidades (deixe em branco para todas): </label>
                                                <select style="width: 100%;" name="especialidades[]" class="select2 form-control" multiple>
                                                    <?php
                                                    $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                                    foreach ($lista_especialidades as $value) { ?>
                                                        <option value="<?php echo htmlspecialchars($value['id']); ?>"
                                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($value['nome']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('.select2').select2({
                                                    allowClear: true
                                                });
                                            });
                                        </script>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Resultado da Análise de Recursos Etapa I -->
                        <div class="alert alert-info">
                            <legend>Resultado da Análise de Recursos Etapa I <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <div class="card">
                                    <div class="alert alert-danger">
                                        <label class="text-danger">Requisitos/Detalhamento</label>
                                        <div class="" style="text-align: left;">
                                            <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                                <li>Cadastrar e julgar todos os recursos da Etapa I no SISCANT</li>
                                                <li>Serão considerados apenas recursos cadastrados com a Etapa I</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <form action="mpdf/relatorio_recursos_ott_stt_et_1_2.php" method="POST">
                                    <input type="hidden" name="etapa" value="1">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA I" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa I, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">A presente relação NÃO está em ordem de classificação.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="data_inicial" type="date" class="form-control" style="height: 40px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="data_final" type="date" class="form-control" style="height: 40px;">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publicações Etapa II -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <h4 class="col-md-12">
                        <a data-toggle="collapse" href="#et_2">
                            PUBLICAÇÕES E DOCUMENTOS -> ETAPA II
                        </a>
                    </h4>
                </div>


                <div class="row collapse" id="et_2">
                    <div class="col-md-12">
                        <!-- Resultado Inicial/Final Etapa II - Avaliação Curricular -->
                        <div class="alert alert-info">
                            <legend>Resultado Inicial/Final Etapa II - Avaliação Curricular <img src="imagens/pdf.png" height="30px"></legend>

                            <label class="text-danger">Requisitos/Detalhamento</label>
                            <div class="alert" style="text-align: left;">
                                <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                    <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO</li>
                                    <li>Passar o SISCANT para Etapa II</li>
                                    <li>Finalizar as avaliações Curriculares</li>
                                    <li>Se estiver gerando a Relação Final, realizar as alterações decorrentes dos recursos DEFERIDOS na Avaliação Curricular antes de Gerar</li>
                                    <li>Bloquear a Avaliação Curricular nas Configurações</li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <form action="mpdf/relatorio_personalizado.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <input name="tipo_relatorio" type="hidden" value="classificacao">
                                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                            <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                            <input name="orientacao" type="hidden" value="retrato">
                                            <input name="tipo_especialdiade" type="hidden" value="todas">
                                            <input name="cabecalho" type="hidden" value="sim">
                                            <div class="form-group">
                                                <input name="titulo_1" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo_2" value="RELAÇÃO DE CANDIDATOS POR ORDEM DE CLASSIFICAÇÃO E POR ESPECIALIDADE - ETAPA II" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos, por ordem de classificação nas especialidades para OFICIAL TÉCNICO TEMPORÁRIO (OTT) e SARGENTO TÉCNICO TEMPORÁRIO (STT), conforme anexo “A” (Calendário Geral de Atividades), do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control">Conforme o § 4º, Art. 125 da Portaria 407-DGP, de 25 de Julho de 2022, ressalto a precedência da seguinte candidata gestante do Processo Seletivo Anterior (20XX/20XX) no atual Processo Seletivo (20XX/20XX): 1) OTT – SERVIÇO SOCIAL: ROSE MEIRE ANDRADE DA SILVA</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control">O período para interposição de Recursos da Etapa II será no dia XX de agosto de 20XX das 0930h às 1130h e das 1300h às 1630h e no dia XX de agosto de 20XX das 0930h às 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre. O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Selecione as Especialidades (deixe em branco para todas): </label>
                                                <select name="especialidades[]" class="select2 form-control" multiple>
                                                    <?php
                                                    $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                                    foreach ($lista_especialidades as $value) { ?>
                                                        <option value="<?php echo htmlspecialchars($value['id']); ?>"
                                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($value['nome']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('.select2').select2({
                                                    allowClear: true
                                                });
                                            });
                                        </script>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Resultado da Análise de Recursos Etapa II -->
                        <div class="alert alert-info">
                            <legend>Resultado da Análise de Recursos Etapa II <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">

                                <label class="text-danger">Requisitos/Detalhamento</label>
                                <div class="alert" style="text-align: left;">
                                    <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                        <li>Cadastrar e julgar todos os recursos da Etapa II no SISCANT</li>
                                        <li>Serão considerados apenas recursos cadastrados com a Etapa II</li>
                                    </ul>
                                </div>

                                <form action="mpdf/relatorio_recursos_ott_stt_et_1_2.php" method="POST">
                                    <input type="hidden" name="etapa" value="2">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA II" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa II, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">A presente relação NÃO está em ordem de classificação.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="data_inicial" type="date" class="form-control" style="height: 40px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="data_final" type="date" class="form-control" style="height: 40px;">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publicações Etapa III -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <h4 class="col-md-12">
                        <a data-toggle="collapse" href="#et_3">
                            PUBLICAÇÕES E DOCUMENTOS -> ETAPA III
                        </a>
                    </h4>
                </div>

                <div class="row collapse" id="et_3">
                    <div class="col-md-12">
                        <!-- Cronograma de Convocação Etapa III -->
                        <div class="alert alert-info">
                            <legend>Cronograma de Convocação Etapa III <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <label class="text-danger">Requisitos/Detalhamento</label>
                                <div class="alert" style="text-align: left;">
                                    <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                        <li>Passar o SISCANT para Etapa III</li>
                                        <li>A publicação irá considerar somente os candidatos que estão CONCORRENDO e na ETAPA III da ESPECIALIDADE</li>
                                        <li>Se o candidato estiver concorrendo em mais de uma especialidade, ele será convocado na menor data de suas especialidades</li>
                                        <li>A Lista de Presença será incluida no fim do documento, sendo necessário a separação pelo Operador</li>
                                    </ul>
                                </div>

                                <form action="mpdf/relatorio_convocacao_et_3.php" method="POST">
                                    <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="CONVOCAÇÃO PARA ETAPA III – CONFERÊNCIA PRESENCIAL DE DOCUMENTAÇÃO, ENTREVISTA E INSPEÇÃO DE SAÚDE" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Capacidade Máxima por Turno</label>
                                                <input name="capacidade_turno" type="number" value="50" min="1" max="100" class="form-control">
                                                <small class="text-muted">Máximo de candidatos por turno</small>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Data de Início</label>
                                                <input name="data_inicio" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('next monday')) ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Data Final</label>
                                                <input name="data_final" type="date" class="form-control" value="<?= date('Y-m-d', strtotime('+2 weeks')) ?>">
                                                <small class="text-muted">Período para distribuir os agendamentos</small>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="alert alert-info">
                                                <strong>⚠️ Atenção - Nova Funcionalidade</strong>
                                                <p>O sistema agora distribui automaticamente os candidatos por turnos e datas:</p>
                                                <ul>
                                                    <li>Máximo de <span id="capacidade-value">50</span> candidatos por turno</li>
                                                    <li>Dois turnos por dia (Manhã: 08:00h, Tarde: 13:00h)</li>
                                                    <li>Sexta-feira apenas turno da manhã</li>
                                                    <li>Candidatos com múltiplas especialidades no mesmo dia</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">De acordo com o Aviso de Convocação Nr XX-SSMR/X, de X de junho de 20XX, CONVOCO os candidatos abaixo relacionados, para comparecimento presencial à Comissão de Seleção Especial para Serviço Técnico Temporário (CSE/SvTT), localizada na Rua Bento Martins Nº 45 – Centro – Porto Alegre-RS, nas DATAS e HORÁRIOS abaixo, munidos da documentação conforme inserida no SISCANT e exames de saúde previstos para a Etapa III do processo seletivo, todas as certidões solicitadas no "Anexo C" deverão estar atualizadas.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">Informo que o candidato deverá observar a especialidade, data e hora de apresentação, munido de caneta azul ou preta para uso individual.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control">Será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à chamada para a Etapa III.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control">Informo, também, que os candidatos NÃO CONVOCADOS nesta lista poderão ser chamados para convocações posteriores, caso haja necessidade na área da 3ª Região Militar.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR CRONOGRAMA AUTOMÁTICO</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Etiquetas Envelopes Etapa III -->
                        <div class="alert alert-info">
                            <legend>Etiquetas Envelopes Etapa III <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_etiquetas_envelopes_et_3.php" method="POST">
                                    <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="subtitulo" value="ETIQUETAS ENVELOPES ETAPA III" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <legend>Dados dos Candidatos para Cadastro no SIPMED <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="excel_informacoes_sipmed.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="titulo">título</label>
                                                <input name="titulo" maxlength="100" class="form-control" value="Dados Cadastro para Inspeção de Saúde no SIPMED - Xª RM">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="paragrafo_um">Parágrafo</label>
                                                <input name="paragrafo_um" maxlength="100" class="form-control" value="Tendo em vista a convocação dos candidatos OTT/STT 20XX/20XX, abaixo relacionados, para a realização de Inspeção de Saúde na Xª RM, solicito que os mesmos sejam cadastrados no Sistema de Perícias Médicas - SIPMED para a realização das Inspeções de Saúde no período entre XX a XX Maio XX.">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--Resultado Etapa III -->
                        <div class="alert alert-info">
                            <legend>Resultado Etapa III <img src="imagens/pdf.png" height="30px"></legend>

                            <label class="text-danger">Requisitos/Detalhamento</label>

                            <div class="alert" style="text-align: left;">
                                <ul class="text-danger" style="padding: 0; font-weight: 600;">
                                    <li>Lançar os Resultados da Inspeção de saúde no perfil dos candidatos</li>
                                    <li>NÃO TER DESCLASSIFICADO nenhum candidato que realizou a inspeção de saúde</li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <form action="mpdf/relatorio_resultado_ott_stt_et_3.php" method="POST">
                                    <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="RESULTADO ETAPA III" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga o resultado referente à Etapa III, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/X, de XX de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">O período para interposição de Recursos da Etapa III será nos dias XX e XX de outubro de 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Resultado da Análise de Recursos Etapa III -->
                        <div class="alert alert-info">
                            <legend>Resultado da Análise de Recursos Etapa III <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_analise_recursos_ott_stt.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="RESULTADO DA ANÁLISE DE RECURSOS ETAPA I" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa I, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de X de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">A presente relação NÃO está em ordem de classificação.</textarea>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('.select2').select2({
                                                    placeholder: "Selecione as especialidades",
                                                    allowClear: true
                                                });
                                            });
                                        </script>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publicações Etapa IV -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <h4 class="col-md-12">
                        <a data-toggle="collapse" href="#et_4">
                            PUBLICAÇÕES E DOCUMENTOS -> ETAPA IV
                        </a>
                    </h4>
                </div>


                <div class="row collapse" id="et_4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <legend>Convocação Teste de Conhecimento OTT Informática e STT Instrumento Musical <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_convocacao_teste_informatica_musica.php" method="POST">
                                    <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <input name="tipo_relatorio" type="hidden" value="concorrendo_esp">
                                            <input name="mostrar_especialidade" type="hidden" value="nao_mostrar_especialidade">
                                            <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                            <input name="orientacao" type="hidden" value="retrato">
                                            <input name="tipo_especialdiade" type="hidden" value="todas">
                                            <input name="cabecalho" type="hidden" value="sim">
                                            <div class="form-group">
                                                <input name="titulo_1" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo_2" value="RESULTADO DO TESTE DE CONHECIMENTO OTT - INFORMÁTICA E STT - INSTRUMENTO MUSICAL" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">De acordo com o Aviso de Convocação Nr XX-SSMR/X, de X de junho de 20XX, CONVOCO os candidatos abaixo relacionados, para comparecimento presencial aos locais de realização das provas teóricas e/ou práticas, nas datas abaixo informadas, munidos de documento de identificação e caneta azul ou preta para uso individual.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">Os candidatos convocados devem observar as orientações descritas no Art 27 do Aviso de Convocação para realização dos testes.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control">No caso dos candidatos a STT Instrumento Musical, as provas serão realizadas nos dias 22, 23, 24 e 25. O candidato será orientado sobre os demais horários presencialmente no dia 22 JUL XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control">Informo que será ELIMINADO o candidato que NÃO COMPARECER à chamada para o Teste de Conhecimento Teórico/Prático.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_cinco" placeholder="5º Parágrafo do relatório" class="form-control">Os candidatos a OTT - INFORMÁTICA (PROGRAMAÇÃO PHP) devem apresentar-se na R. dos Andradas, 551 – Centro Histórico, Porto Alegre – RS, 90020-001 22 JUL 24, às 0930 h</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_seis" placeholder="6º Parágrafo do relatório" class="form-control">Os candidatos a OTT - INFORMÁTICA (SERVIDORES E REDES) devem apresentar-se na R. dos Andradas, 551 – Centro Histórico, Porto Alegre – RS, 90020-001 22 JUL 23, às 1030 h</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_sete" placeholder="7º Parágrafo do relatório" class="form-control">Os candidatos a STT - INSTRUMENTO MUSICAL devem apresentar-se na R. Corrêa Lima, 550 – Santa Tereza, Porto Alegre – RS, 90850-250 22 JUL 23, às 0800 h</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_oito" placeholder="8º Parágrafo do relatório" class="form-control">A presente relação NÃO está em ordem de classificação.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Selecione as Especialidades</label>
                                                <select name="especialidades[]" class="form-control" multiple required>
                                                    <?php
                                                    $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                                    foreach ($lista_especialidades as $value) {
                                                        if (!$value['teste_pratico']) {
                                                            continue;
                                                        } ?>
                                                        <option value="<?php echo htmlspecialchars($value['id']); ?>"
                                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($value['nome']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <legend>Resultado Teste de Conhecimento OTT Informática e STT Instrumento Musical <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_resultado_teste_informatica_musica.php" method="POST">
                                    <input name="etapa" type="hidden" value="<?php echo ($etapa_atual); ?>">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="subtitulo" value="RESULTADO DO TESTE DE CONHECIMENTO OTT - INFORMÁTICA E STT - INSTRUMENTO MUSICAL" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_um" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da Xª Região Militar divulga o resultado do Teste de Conhecimento Teórico, Prático e Oral, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr XX - SSMR/X, de X de junho de 20XX.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_dois" placeholder="2º Parágrafo do relatório" class="form-control">O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial, não serão aceitos recursos entregues fora do prazo ou no local errado.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_tres" placeholder="3º Parágrafo do relatório" class="form-control">A interposição de recursos será nos dias XX e XX de julho de 20XX, das 0930 às 1130 horas e das 1300 às 1630 horas, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="paragrafo_quatro" placeholder="4º Parágrafo do relatório" class="form-control">A presente relação NÃO está em ordem de classificação.</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Selecione as Especialidades</label>
                                                <select name="especialidades[]" class="form-control" multiple required>
                                                    <?php
                                                    $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                                    foreach ($lista_especialidades as $value) {
                                                        if (!$value['teste_pratico']) {
                                                            continue;
                                                        } ?>
                                                        <option value="<?php echo htmlspecialchars($value['id']); ?>"
                                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($value['nome']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Publicações Personalizadas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <h4 class="col-md-12">
                        <a data-toggle="collapse" href="#et_personalizada">
                            PUBLICAÇÕES PERSONALIZADAS/OUTROS
                        </a>
                    </h4>
                </div>

                <div class="row collapse" id="et_personalizada">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h3 style="font-weight: 500;">Candidatos na Seleção <img src="imagens/pdf.png" height="30px"></h3>
                            <div class="card-body">
                                <form action="mpdf/relatorio_personalizado.php" method="POST">
                                    <div class="row">

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="tipo_relatorio" class="form-control">
                                                    <option value="nao_concorrendo_lista"> Eliminados da Etapa I</option>
                                                    <option value="concorrendo_esp"> Concorrendo na seleção (Por Especialidade)</option>
                                                    <option value="nao_concorrendo_esp"> NÃO concorrendo na seleção (Por Especialidade)</option>
                                                    <option selected value="classificacao"> Classificação dos candidatos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="mostrar_especialidade" class="form-control">
                                                    <option selected value="mostrar_especialidade"> MOSTRAR epecialidade mesmo quando não tiver candidatos</option>
                                                    <option value="nao_mostrar_especialidade"> NÃO MOSTRAR especialidade quando não tiver candidatos</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="etapa" class="form-control">
                                                    <option <?php if ($etapa_atual == '1') echo " selected " ?>value="1"> ETAPA I</option>
                                                    <option <?php if ($etapa_atual == '2') echo " selected " ?>value="2"> ETAPA II</option>
                                                    <option <?php if ($etapa_atual == '3') echo " selected " ?>value="3"> ETAPA III</option>
                                                    <option <?php if ($etapa_atual == '4') echo " selected " ?>value="4"> ETAPA IV</option>
                                                    <option <?php if ($etapa_atual == '5') echo " selected " ?>value="5"> ETAPA V</option>
                                                    <option <?php if ($etapa_atual == '6') echo " selected " ?>value="6"> ETAPA VI</option>
                                                    <option <?php if ($etapa_atual == '7') echo " selected " ?>value="7"> ETAPA VII</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="orientacao" class="form-control">
                                                    <option selected value="retrato">Retrato</option>
                                                    <option value="paisagem">Paisagem</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="tipo_especialdiade" class="form-control">
                                                    <option selected value="todas">Todas especialidades</option>
                                                    <option <?php if ($_SESSION['selecao_codigo'] == 'ott_stt') echo 'hidden' ?> value="sem_medicos">Relatórios sem os médicos</option>
                                                    <option <?php if ($_SESSION['selecao_codigo'] == 'ott_stt') echo 'hidden' ?> value="somente_medicos">Relatório somente com médicos</option>
                                                    <option <?php if ($_SESSION['selecao_codigo'] == 'mfdv')    echo 'hidden' ?> value="sem_musicos">Relatório sem os Músicos</option>
                                                    <option <?php if ($_SESSION['selecao_codigo'] == 'mfdv')    echo 'hidden' ?> value="somente_musicos">Relatório somente Músicos</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="cabecalho" class="form-control">
                                                    <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                                    <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo_1" value="TÍTULO PRINCIPAL" class="form-control" placeholder="TÍTULO PRINCIPAL">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="titulo_2" value="Título Secundário" class="form-control" placeholder="Título Secundário">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da 3a Região Militar divulga a relação dos candidatos voluntários no processo seletivo ...</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control">Outrossim, todos os candidatos elencados na relação abaixo estão convocados a comparecer ...</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <!-- 04/08/2025 -> Iago Silva Corrigindo tamanho do input -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Selecione as Especialidades (deixe em branco para todas): </label>
                                                <select name="especialidades[]" class="select2 form-control" multiple>
                                                    <?php
                                                    $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                                    foreach ($lista_especialidades as $value) { ?>
                                                        <option value="<?php echo htmlspecialchars($value['id']); ?>"
                                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($value['nome']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('.select2').select2({
                                                    allowClear: true
                                                });
                                            });
                                        </script>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <legend>Quem realizou o exame de saúde em determinada data <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_inspecao_saude.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data da Inspeção de Saúde">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="orientacao" class="form-control">
                                                    <option value="retrato">Retrato</option>
                                                    <option selected value="paisagem">Paisagem</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="cabecalho" class="form-control">
                                                    <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                                    <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info" <?php if ($_SESSION['selecao_codigo'] != 'mfdv')    echo ' hidden ' ?>>
                            <legend>Quem realizou o exame de saúde em determinada data --- SOMENTE MÉDICOS OBRIGATÓRIOS <img src="imagens/pdf.png" height="30px"></legend>
                            <div class="card-body">
                                <form action="mpdf/relatorio_inspecao_saude_medicos_obrigatorios.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data da Inspeção de Saúde">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="orientacao" class="form-control">
                                                    <option value="retrato">Retrato</option>
                                                    <option selected value="paisagem">Paisagem</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="cabecalho" class="form-control">
                                                    <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                                    <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <legend>Candidatos Autodeclarados Cotistas <img src="imagens/pdf.png" height="30px"></legend>

                            <div class="card-body">
                                <form action="mpdf/relatorio_cotistas.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="titulo" maxlength="100" class="form-control" value="PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20XX/20XX">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input name="subtitulo" class="form-control" value="RELAÇÃO DE CANDIDATOS AUTODECLARADOS COTISTAS">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input name="paragrafo_um" class="form-control" value="Nas tableas abaixo estão listados os candidatos que se autodeclararão como cotistas no momento da Inscrição">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary btn-block">GERAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--
                            <div class="card">
                                <legend>Agenda de comparecimento <img src="imagens/pdf.png" height="30px"></legend>
                                <div class="card-body">
                                    <form action="mpdf/agenda_comparecimento.php" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                            <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data de Comparecimento" >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <select name="orientacao" class="form-control">
                                                    <option value="retrato">Retrato</option>
                                                    <option selected value="paisagem">Paisagem</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"> 
                                                <select name="cabecalho" class="form-control">
                                                    <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                                                    <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div  class="col-lg-12">
                                            <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO</button> 
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<!-- Bootstrap 3 JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/plugins/select2.min.js"></script>
</body>

</html>
<?php $conexao = null; ?>