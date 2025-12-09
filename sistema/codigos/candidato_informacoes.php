<?php
/*ASP SILVA ATUALIZADO EM 28 MAIO 25 */
// var_dump($_SESSION["perfil"]); 
if ($_SESSION['perfil'] == 'avaliador') {
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

    $tem_permissao_para_avaliar = false;

    foreach ($lista_especialidade_avaliador as $especialidade_avaliador) {

        $verifica_usuario_avaliador = $conexao->verifica_usuario_avaliador($especialidade_avaliador['id_especialidade'], $id_usuario);
        if (count($verifica_usuario_avaliador) > 0)
            $tem_permissao_para_avaliar = true;
    }
    if (!$tem_permissao_para_avaliar) {
        erro("Erro 242384! Página não encontrada!");
        exit();
    }
}

$resultado_selecao = $conexao->get_selecao_id();
if (
    $resultado_selecao[0]['codigo'] == 'ott_stt'
    || $resultado_selecao[0]['codigo'] == 'mfdv'
    || $resultado_selecao[0]['codigo'] == 'cet'
    || $resultado_selecao[0]['codigo'] == 'ott'
    || $resultado_selecao[0]['codigo'] == 'stt'
) {
    $_SESSION['eipot'] = 0;
    unset($_SESSION['eipot']);
}

$especialidade_cadastradas_candidato = $conexao->get_especialidade_candidato($id_usuario);

$lista_esp_cadastrada = "";
foreach ($especialidade_cadastradas_candidato as &$esp_cadastrada) {
    $lista_esp_cadastrada = $lista_esp_cadastrada . " | " . mb_strtoupper($esp_cadastrada['ott_stt']) . ' ' . $esp_cadastrada['especialidade'] . "  - Reg Conselho: " . $esp_cadastrada['registro_conselho'];
}

$pagamento_obrigatorio = null;
$get_selecao = $conexao->get_selecao_id();
$pagamento_obrigatorio = $get_selecao[0]['pagamento'];


if ($medico_obrigatorio == 0 && ($id_selecao != $_SESSION['selecao']) && $_SESSION['perfil'] != 'om') {
    erro("Erro 3475368568! Usuário/Candidato de outra seleção!");
    exit();
}
?>

<div class="col-12">
    <!-- Card Principal de Informações do Candidato -->
    <div class="card dashboard-card mb-4">
        <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-user me-2"></i>
                Informações do Candidato
                <?php if ($concorrendo == 0): ?>
                    <span class="badge bg-danger ms-2">Desclassificado</span>
                <?php endif; ?>
                <?php if ($apagado == 1): ?>
                    <span class="badge bg-danger ms-2">Usuário Apagado</span>
                <?php endif; ?>
                <?php if ($medico_obrigatorio == 1): ?>
                    <span class="badge bg-warning ms-2">OBRIGATÓRIO</span>
                <?php endif; ?>
            </span>
            <div class="d-flex">
                <?php if ((int)$etapa > 1): ?>
                    <?php
                    $crip_chave123 = hash('sha256', $id_usuario . $_SESSION['chave']);
                    ?>
                    <a href="mpdf/comprovante_inscricao.php?crip=<?= $crip_chave123 ?>&id_candidato=<?= $id_usuario ?>"
                        target="_blank"
                        class="btn btn-success btn-sm mr-10"
                        data-bs-toggle="tooltip"
                        title="Comprovante de Inscrição">
                        <i class="fa fa-file-pdf-o me-1"></i> Comprovante
                    </a>
                <?php endif; ?>

                <?php if ($medico_obrigatorio == 1): ?>
                    <a href="edita_medico_obrigatorio.php?id_usuario=<?= $id_usuario ?>"
                        class="btn btn-success btn-sm"
                        data-bs-toggle="tooltip"
                        title="Editar Médico Obrigatório">
                        <i class="fa fa-edit"></i>
                    </a>
                <?php endif; ?>

                <?php if ($medico_obrigatorio != 1): ?>
                    <a target="_blank"
                        href="mpdf/relatorio_completo_candidato.php?codigo=<?= hash('sha256', $_SESSION['chave']) ?>&id_candidato=<?= $id_usuario ?>"
                        class="btn btn-success btn-sm"
                        data-bs-toggle="tooltip"
                        title="Relatório Completo">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                <?php endif; ?>

                <?php if ($medico_obrigatorio == 1): ?>
                    <a target="_blank"
                        href="mpdf/relatorio_medico_obrigatorio.php?codigo=<?= hash('sha256', $_SESSION['chave']) ?>&id_candidato=<?= $id_usuario ?>"
                        class="btn btn-success btn-sm"
                        data-bs-toggle="tooltip"
                        title="Relatório Médico Obrigatório">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <!-- Tabela de Informações Básicas -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                        <!-- Linha 1: Nome, CPF, RG, Nome Social -->
                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-user me-1 text-primary"></i> Nome Completo:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($nome_completo) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-id-card me-1 text-primary"></i> CPF:</label><br>
                                <span class="ms-3"><?= mascara($cpf, '###.###.###-##') ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-address-card me-1 text-primary"></i> Identidade:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($identidade) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-id-badge me-1 text-primary"></i> Nome Social:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($nome_social ?? 'Não Informado') ?></span>
                            </td>
                        </tr>

                        <!-- Linha 2: Estado Civil, Data Nascimento, Sexo, Autodeclaração/Vaga Reservada -->
                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-heart me-1 text-primary"></i> Estado Civil:</label><br>
                                <span class="ms-3"><?= htmlspecialchars(ucfirst($estado_civil)) ?>
                                    <?php if ($companheiro != null): ?>
                                        <br><small class="text-muted">Com <?= htmlspecialchars($companheiro) ?></small>
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-birthday-cake me-1 text-primary"></i> Data de Nascimento:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($data_nascimento) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-venus-mars me-1 text-primary"></i> Sexo:</label><br>
                                <span class="ms-3"><?= htmlspecialchars(ucfirst($sexo)) ?></span>
                            </td>

                            <td>
                                <?php
                                if ($vaga_reservada == 0 || ($vaga_reservada == null)) $vaga_reservada = "Não";
                                if ($vaga_reservada == 1) $vaga_reservada = "Sim";
                                ?>
                                <label class="fw-semibold"><i class="fa fa-check me-1 text-primary"></i> Autodeclaração/Vaga Reservada - Lei 12.990:</label><br>
                                <span class="ms-3"><?= htmlspecialchars(ucfirst($autodeclaracao) . ' / ' . $vaga_reservada) ?></span>
                            </td>
                        </tr>

                        <!-- Linha 3: Pai, Mãe, Nacionalidade, Naturalidade -->
                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-male me-1 text-primary"></i> Nome do Pai:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($pai) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-female me-1 text-primary"></i> Nome da Mãe:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($mae) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-globe me-1 text-primary"></i> Nacionalidade (País):</label><br>
                                <span class="ms-3"><?= htmlspecialchars($nacionalidade) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-map-marker me-1 text-primary"></i> Naturalidade (Cidade):</label><br>
                                <span class="ms-3"><?= htmlspecialchars($naturalidade) ?></span>
                            </td>
                        </tr>

                        <!-- Linha 4: Endereço -->
                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-map me-1 text-primary"></i> UF/Cidade:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($uf . ' / ' . $cidade) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-road me-1 text-primary"></i> Endereço:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($rua_num_complemento . ' - ' . $bairro . ' / ' . $cep) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-envelope me-1 text-primary"></i> E-Mail:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($mail) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-mobile me-1 text-primary"></i> Telefone/Recado:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($tel_celular . ' / ' . $tel_residencial) ?></span>
                            </td>
                        </tr>

                        <!-- Informações específicas MFDV -->
                        <?php if ($_SESSION['selecao_codigo'] == 'mfdv'): ?>
                            <?php
                            if ($voluntario_12rm != null) {
                                if ($voluntario_12rm == 1)
                                    $voluntario_12rm = "Sim";
                                else if ($voluntario_12rm == 0)
                                    $voluntario_12rm = "Não";
                            }
                            ?>
                            <tr>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-university me-1 text-primary"></i> Instituto de Ensino:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($instituto_ensino) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-graduation-cap me-1 text-primary"></i> Ano de Formação:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($ano_formacao) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-balance-scale me-1 text-primary"></i> Conselho:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($conselho) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label class="fw-semibold"><i class="fa fa-building me-1 text-primary"></i> Cidade Instituto de Ensino:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($cidade_instituto_ensino) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-map me-1 text-primary"></i> UF Instituto de Ensino:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($uf_instituto_ensino) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-users me-1 text-primary"></i> Dependentes:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($dependente) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-hand-paper-o me-1 text-primary"></i> Voluntário para 12ª RM:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($voluntario_12rm) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-star me-1 text-primary"></i> Prioridade de Força:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($prioridade_forca) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Linha 6: Serviço Militar -->
                        <tr>
                            <!-- Serviço Militar -->
                            <?php if ($tempo_sv_mil == 1): ?>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-shield me-1 text-primary"></i> Civil/Militar:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($civil_militar) ?></span>
                                </td>

                                <td>
                                    <label class="fw-semibold"><i class="fa fa-calendar me-1 text-primary"></i> Tempo de Serviço Militar:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($tempo_sv_mil_anos . ' anos, ' . $tempo_sv_mil_meses . ' meses e ' . $tempo_sv_mil_dias . ' dias') ?></span>
                                </td>
                            <?php else: ?>
                                <td colspan="2">
                                    <label class="fw-semibold"><i class="fa fa-calendar me-1 text-primary"></i> Tempo de Serviço Militar:</label><br>
                                    <span class="ms-3 text-muted">Não</span>
                                </td>
                            <?php endif; ?>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-star me-1 text-primary"></i> Posto/Graduação:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($posto_grad) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-cogs me-1 text-primary"></i> Arma/Quadro/Serviço:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($arma_quadro_servico) ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-flag me-1 text-primary"></i> Ativa/Reserva:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($ativa_reserva) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-shield me-1 text-primary"></i> Força:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($forca) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-calendar me-1 text-primary"></i> Ano de incorporação:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($ano_incorporacao) ?></span>
                            </td>

                            <td>
                                <label class="fw-semibold"><i class="fa fa-clock-o me-1 text-primary"></i> Licenciamento:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($licenciamento) ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-certificate me-1 text-primary"></i> Certificado:</label><br>
                                <span class="ms-3"><?= htmlspecialchars(mb_strtoupper($certificado)) ?></span>
                            </td>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-file me-1 text-primary"></i> Nº do Documento:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($num_ducumento) ?></span>
                            </td>
                            <td colspan="2">
                                <label class="fw-semibold"><i class="fa fa-calendar me-1 text-primary"></i> Data da Expedição:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($data_expedicao) ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <label class="fw-semibold"><i class="fa fa-graduation-cap me-1 text-primary"></i> Especialidade(s) Cadastrada(s):</label><br>
                                <span class="ms-3"><?= htmlspecialchars($lista_esp_cadastrada) ?></span>
                            </td>
                        </tr>



                        <!-- Informações EIPOT -->
                        <?php if (isset($_SESSION['eipot']) == 1): ?>
                            <tr>
                                <td colspan="2">
                                    <label class="fw-semibold"><i class="fa fa-map-marker me-1 text-primary"></i> RM Etapas Presenciais:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($rm_inscricao) ?>ª</span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-bullseye me-1 text-primary"></i> RM's de Interesse:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($rm_destino) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label class="fw-semibold"><i class="fa fa-book me-1 text-primary"></i> Curso de Graduação:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($curso_graduacao) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-shield me-1 text-primary"></i> Arma EIPOT:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($arma_eipot) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label class="fw-semibold"><i class="fa fa-calendar me-1 text-primary"></i> Ano de Formação OFOR:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($ano_formacao_ofor) ?></span>
                                </td>
                                <td>
                                    <label class="fw-semibold"><i class="fa fa-line-chart me-1 text-primary"></i> Nota OFOR:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($nota_ofor) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Apresentação e Observação OM -->
                        <tr>
                            <td>
                                <label class="fw-semibold"><i class="fa fa-clipboard me-1 text-primary"></i> Apresentação do Candidato na OM:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($apresentacao_om) ?></span>
                            </td>
                            <td colspan="3">
                                <label class="fw-semibold"><i class="fa fa-sticky-note me-1 text-primary"></i> Observação OM:</label><br>
                                <span class="ms-3"><?= htmlspecialchars($observacao_om) ?></span>
                            </td>
                        </tr>

                        <!-- Cidade de Apresentação -->
                        <?php if (isset($_SESSION['6_regiao']) || isset($_SESSION['12_regiao'])): ?>
                            <tr>
                                <td colspan="4">
                                    <label class="fw-semibold"><i class="fa fa-map-pin me-1 text-primary"></i> Cidade de Apresentação nas Etapas Presenciais:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($cidade_etapas_presenciais) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Exame de Música 12RM -->
                        <?php if (isset($_SESSION['12_regiao']) && isset($_SESSION['12_regiao_musica'])): ?>
                            <tr>
                                <td colspan="4">
                                    <label class="fw-semibold"><i class="fa fa-music me-1 text-primary"></i> Cidade do exame de comprovação de habilidade musical:</label><br>
                                    <span class="ms-3"><?= htmlspecialchars($cidade_exame_musica_12rm) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Informações Médico Obrigatório -->
                        <?php if ($medico_obrigatorio != null): ?>
                            <?php include_once 'medico_obrigatorio_informacoes.php'; ?>
                        <?php endif; ?>

                        <!-- Foto do Candidato -->
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="photo-container">
                                    <img style="box-shadow: 0px 0px 10px #006400; border-radius: 10px;"
                                        src="fotos/<?= $foto_nome ?>"
                                        width="200"
                                        alt="Foto do candidato"
                                        class="img-thumbnail">
                                    <div class="mt-2">
                                        <small class="text-muted">Foto do Candidato</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Navegação Rápida -->
            <div class="row">
                <div class="col-md-2 text-center">
                    <a href="#especialidades" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap me-1"></i> Especialidades
                        </h4>
                    </a>
                </div>

                <div class="col-md-2 text-center">
                    <a href="#arquivos_obrigatorios" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-file-text me-1"></i> Docs Obrigatórios
                        </h4>
                    </a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#observacoes" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-sticky-note me-1"></i> Observações
                        </h4>
                    </a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#recursos" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-gavel me-1"></i> Recursos
                        </h4>
                    </a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#insere_arquivo_candidato" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-folder me-1"></i> Arquivos
                        </h4>
                    </a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#auditoria" class="">
                        <h4 class="mb-0">
                            <i class="fa fa-history me-1"></i> Auditoria
                        </h4>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção OM (se aplicável) -->
    <?php if ($_SESSION['perfil'] == 'om'): ?>
        <?php include_once 'codigos/om_preenchimento.php'; ?>
    <?php endif; ?>

    <!-- Seção de Pagamento/Isenção -->
    <a name="isento"></a>
    <?php if ($pagamento_obrigatorio == '1'): ?>
        <div class="card dashboard-card mb-4">
            <div class="card-header dashboard-header mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-credit-card me-2"></i>
                    Arquivo de Pagamento/Isenção
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $arquivo_pagamento = $conexao->get_arquivo_pagamento($id_usuario);
                    $isento = null;
                    if (count($arquivo_pagamento) > 0)
                        $isento = (int)$arquivo_pagamento[0]['isento'];

                    if ($isento == 1)
                        $isento_text = "SELECIONOU ISENTO";
                    else if ($isento == 0)
                        $isento_text = "";
                    ?>

                    <?php if (count($arquivo_pagamento) > 0): ?>
                        <div class="col-lg-6">
                            <a target="_blank"
                                href="baixaPDF.php?codigo=cand_inf_pag&nome_arquivo=<?= $arquivo_pagamento[0]['nome'] ?>"
                                class="text-decoration-none">
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="fa fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <strong>Visualizar arquivo adicionado</strong>
                                        <?php if ($isento_text): ?>
                                            <br><small class="text-muted"><?= $isento_text ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <i class="fa fa-file-pdf-o fa-2x ms-auto"></i>
                                </div>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-6">
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <strong>Nenhum arquivo adicionado!</strong>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Inclusões condicionais restantes -->
    <?php

    $resultado_selecao = $conexao->get_selecao_id();
    //  var_dump($resultado_selecao[0]['codigo']);
    if ($resultado_selecao[0]['codigo'] == 'eipot') {

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos' || $_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/candidato_concorrendo.php';
        }

        // 14 MAIO 2024 
        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/admin_altera_contatos.php';
        }

        if ($_SESSION['perfil'] == 'admin' && isset($_SESSION["eipot"]) == 1) {
            include_once 'codigos/candidato_eipot.php';
        }

        //13 JUNHO 2024 - IAGO SILVA
        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consultor' || $_SESSION['perfil'] == 'chc' || $_SESSION['perfil'] == 'cr') {
            include_once 'codigos/candidato_heteroidentificacao.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'jise' || $_SESSION['perfil'] == 'chc' || $_SESSION['perfil'] == 'cr') {
            include_once 'codigos/candidato_recurso.php';
        }

        //Excluido o perfil jise da avaliação de recurso do candidato ||  $_SESSION['perfil'] == 'jise' Em 28 de Maio de 2025
        if ($_SESSION['perfil'] == 'avaliador' || $_SESSION['perfil'] == 'admin') {
            include_once 'codigos/avaliador_recurso_candidato.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'saude' || $_SESSION['perfil'] == 'jise') {
            include_once 'codigos/candidato_inspecao_saude.php';
        }

        if (($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'con') && $medico_obrigatorio == 1) {
            include_once 'codigos/oficio_medico_obrigatorio.php';
        }

        if ($_SESSION['perfil'] == 'admin' && $medico_obrigatorio == 1) {
            include_once 'codigos/candidato_impedimento_judicial.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_distribuicao.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos') {
            include_once 'codigos/candidato_docs_obrigatorios_avaliador.php';
        } elseif ($_SESSION['perfil'] != 'jise') {
            include_once 'codigos/arquivos_obrigatorios_usuario.php';
        }

        if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
            include_once 'codigos/candidato_observacoes.php';
        }

        if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
            include_once 'codigos/arquivos_adicionados_para_candidato.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta') {
            include_once 'codigos/logs_usuario.php';
        }
    }


    $resultado_selecao = $conexao->get_selecao_id();
    if (
        $resultado_selecao[0]['codigo'] == 'ott_stt'
        || $resultado_selecao[0]['codigo'] == 'mfdv'
        || $resultado_selecao[0]['codigo'] == 'cet'
        || $resultado_selecao[0]['codigo'] == 'ott'
        || $resultado_selecao[0]['codigo'] == 'stt'
    ) {

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos' || $_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/candidato_concorrendo.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos' || $_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/candidato_questionario_inscricao.php';
        }

        // 14 MAIO 2025 
        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/admin_altera_contatos.php';
        }

        if ($resultado_selecao[0]['codigo'] == 'cet' && $_SESSION['perfil'] == 'admin') {
            include_once 'codigos/admin_cadastra_especialidade.php';
        }

        if ($resultado_selecao[0]['codigo'] == 'cet' && $_SESSION['perfil'] == 'admin') {
            include_once 'codigos/admin_cadastra_docs_obrigatorios.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/candidato_especialidades_avaliador.php';
        } else {
            include_once 'codigos/candidato_especialidades.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_especialidades.php';
        } 

        if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '0') {
            include_once 'codigos/candidato_especialidades_visualiza.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/candidato_checklist_documentos.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'saude' || $_SESSION['perfil'] == 'jise') {
            include_once 'codigos/candidato_inspecao_saude.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_eaf.php';
        }

        //13 AGOSTO 2025 - IAGO SILVA
        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consultor' || $_SESSION['perfil'] == 'chc' || $_SESSION['perfil'] == 'cr') {
            include_once 'codigos/candidato_heteroidentificacao.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_distribuicao.php';
        }

        if (($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'con') && $medico_obrigatorio == 1) {
            include_once 'codigos/oficio_medico_obrigatorio.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_impedimento_judicial.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos') {
            include_once 'codigos/candidato_docs_obrigatorios_avaliador.php';
        } else {
            include_once 'codigos/arquivos_obrigatorios_usuario.php';
        }

        if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
            include_once 'codigos/candidato_observacoes.php';
        }

        if ($_SESSION['perfil'] == 'admin') {
            include_once 'codigos/candidato_recurso.php';
        }

        if ($_SESSION['perfil'] == 'avaliador') {
            include_once 'codigos/avaliador_recurso_candidato.php';
        }

        if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
            include_once 'codigos/arquivos_adicionados_para_candidato.php';
        }

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta') {
            include_once 'codigos/logs_usuario.php';
        }
    }

    ?>
</div>
<!--/DIV FINAL CANDIDATO INFORMAÇÕES -->