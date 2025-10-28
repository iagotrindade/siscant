<div class="">

    <?php

    $vetor_ordenado_candidatos = array();

    foreach ($lista_candidatos as $linha) {
        if ($voluntario_obrigatorio == 'voluntario'  && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1)) continue;
        if ($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;


        ///////////////////////////////
        //  PONTUAÇÃO
        ///////////////////////////////

        $pontuacao_curriculo = 0;
        $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade_selecionada);
        if (count($get_pontuacao_avaliada) > 0)
            $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);

        ///////////////////////////////
        // Prova Teórico Prática
        ///////////////////////////////
        if ($select_nota_prova_pratica_teorica != 0) {
            $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'], $id_especialidade_selecionada);
            $nota_prova_teorico_pratico = 0;
            if (count($get_pontuacao_provas) > 0) {
                $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
                $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
            }
        }



        ///////////////////////////////

        /* CODIGOS MILITAR

                1 - Oficial da Ativa
                2 - Oficial R2
                3 - Aspitante R2
                4 - Praça Ativa
                5 - Reservista de 1ª categoria
                6 - Reservista de 2ª categoria
                7 - Civil

                */

        $militar = 7;

        // Oficiais da Ativa
        if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten" || $linha['posto_grad'] == "asp"))
            $militar = 1;

        // Oficial R2
        if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten"))
            $militar = 2;

        // Aspirante R2
        if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "asp"))
            $militar = 3;

        // Praça Ativa
        if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "cd" || $linha['posto_grad'] == "sd"))
            $militar = 4;

        // Reservista de 1ª categoria
        if ($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil'))
            $militar = 5;

        // Reservista de 2ª categoria
        if ($linha['certificado'] == '2crm')
            $militar = 6;

        ///////////////////////////////
        //  TEMPO SERVIÇO PÚBLICO
        ///////////////////////////////

        $tempo_total_sv_publico_dias = 0;
        $anos_sv_publico = (int)$linha['tempo_sv_mil_anos'];
        $meses_sv_publico = (int)$linha['tempo_sv_mil_meses'];
        $dias_sv_publico = (int)$linha['tempo_sv_mil_dias'];

        $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + ($dias_sv_publico);

        ///////////////////////////////
        //  IDADE
        ///////////////////////////////

        $tempo_total_idade_dias = 0;
        $data_atual = new DateTime(date("Y-m-d"));
        $data_nasc = new DateTime($linha['data_nascimento']);
        $intervalo = $data_atual->diff($data_nasc);

        $anos_vida  = (int)$intervalo->format('%Y');
        $meses_vida = (int)$intervalo->format('%m');
        $dias_vida  = (int)$intervalo->format('%d');

        $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + ($dias_vida);


        ///////////////////////////////
        //  ORDENA
        ///////////////////////////////


        $novo_vetor = array();

        $novo_vetor =
            [
                "id" => $linha['id'],
                "nome" => mb_strtoupper($linha['nome_completo'], "UTF-8"),
                "cpf" => $linha['cpf'],
                "cotista" => $linha['vaga_reservada'],
                "autodeclaracao" => $linha['autodeclaracao'],
                "pontos" => $pontuacao_curriculo,
                "militar" => $militar,
                "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                "tempo_idade" => $tempo_total_idade_dias,
                "mail" => $linha['mail'],
                "autodeclaracao" => $linha['autodeclaracao'],
                "etapa" => $linha['etapa'],
                "etapa_candidato" => $linha['etapa_candidato'],
                "cidade_escolheu_servir" => $linha['cidade_escolheu_servir'],
                "vaga_reservada" => $linha['vaga_reservada'],
            ];

        array_push($vetor_ordenado_candidatos, $novo_vetor);
    }

    if (count($lista_candidatos) > 0) {

        foreach ($vetor_ordenado_candidatos as $index => $linha2) {
            $pontos_array[$index]  = $linha2['pontos'];
            $militar_array[$index] = $linha2['militar'];
            $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
            $tempo_idade[$index]   = $linha2['tempo_idade'];
        }

        if (count($vetor_ordenado_candidatos) > 0) {

            array_multisort(
                $pontos_array,
                SORT_DESC,
                $militar_array,
                SORT_ASC,
                $tempo_sv_pub,
                SORT_ASC,
                $tempo_idade,
                SORT_DESC,
                $vetor_ordenado_candidatos
            );
        }
    }

    ?>

    <!-- Card de Classificação - Ampla Concorrência -->
    <div class="card classification-card">
        <div class="card-header dashboard-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-trophy"></i>
                Classificação dos Candidatos - Ampla Concorrência
            </span>
        </div>

        <div class="card-body">
            <!-- Alertas Informativos -->
            <div class="alert alert-warning alert-dismissible">
                <div class="d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle mr-10 fa-lg"></i>
                    <div>
                        <strong>Linha com destaque amarelo:</strong> Candidato concorrendo em mais de uma especialidade
                    </div>
                </div>
            </div>

            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-start">
                    <i class="fa fa-info-circle mr-10 mt-10"></i>
                    <div>
                        <strong class="d-block mb-2">Códigos da Coluna Categoria:</strong>
                        <div class="military-codes-grid">
                            <span class="military-code-item">
                                <span class="military-code-badge">1</span>
                                <span class="military-code-text">Oficial da Ativa</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">2</span>
                                <span class="military-code-text">Oficial R2</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">3</span>
                                <span class="military-code-text">Aspirante R2</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">4</span>
                                <span class="military-code-text">Praça Ativa</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">5</span>
                                <span class="military-code-text">Reservista 1ª Cat</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">6</span>
                                <span class="military-code-text">Reservista 2ª Cat</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">7</span>
                                <span class="military-code-text">Civil</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Classificação -->
            <div class="table-responsive">
                <table class="table table-hover table-striped classification-table tabela_dinamica">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><i class="fa fa-trophy"></i> Posição</th>
                            <th><i class="fa fa-user"></i> Candidato</th>
                            <th><i class="fa fa-id-card"></i> CPF</th>
                            <th><i class="fa fa-id-card"></i> Cotista</th>
                            <th class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                            <th class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Dias SV</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                            <th class="text-center"><i class="fa fa-list"></i> Etapa Especialidade</th>
                            <th><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                            <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lugar = 1;
                        foreach ($vetor_ordenado_candidatos as $linha):

                            // Verificar se é candidato com múltiplas especialidades
                            $is_multiple_especialidades = false;

                            $resultado = $conexao->get_candidatos_mais_uma_especialidade_id_usuario($linha['id']);
                            $is_multiple_especialidades = count($resultado) > 0;

                        ?>
                            <tr class="<?= $is_multiple_especialidades ? 'multiple-especialidade' : '' ?>">
                                <!-- Posição -->
                                <td class="text-center">
                                    <div class="position-badge">
                                        <span class="position-number"><?= $lugar ?></span>
                                    </div>
                                </td>

                                <!-- Nome -->
                                <td>
                                    <div class="candidate-info">
                                        <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome']) ?></div>
                                    </div>
                                </td>

                                <!-- CPF -->
                                <td>
                                    <?= $linha['cpf'] ?>
                                </td>

                                <td>
                                    <?= $linha['vaga_reservada'] == 1 ? ucfirst($linha['autodeclaracao']) : ''  ?>
                                </td>

                                <!-- Pontuação -->
                                <td class="text-center">
                                    <?= $linha['pontos'] ?>
                                </td>

                                <!-- Categoria Militar -->
                                <td class="text-center">
                                    <span class="military-badge" data-code="<?= $linha['militar'] ?>">
                                        <?= $linha['militar'] ?>
                                    </span>
                                </td>

                                <!-- Dias Serviço Público -->
                                <td class="text-center">
                                    <span class="days-badge" data-bs-toggle="tooltip" title="Tempo de serviço público em dias">
                                        <?= $linha['tempo_sv_pub'] ?>
                                    </span>
                                </td>

                                <!-- Dias Idade -->
                                <td class="text-center">
                                    <span class="days-badge" data-bs-toggle="tooltip" title="Idade em dias">
                                        <?= $linha['tempo_idade'] ?>
                                    </span>
                                </td>

                                <!-- Etapas -->
                                <td class="text-center">
                                    <span class="etapa-badge etapa-<?= $linha['etapa'] ?>">
                                        et_<?= $linha['etapa'] ?>
                                    </span>
                                </td>

                                <!-- Cidade -->
                                <td>
                                    <span class="city-badge"><?= $linha['cidade_escolheu_servir'] ?: '-' ?></span>
                                </td>

                                <!-- Ações -->
                                <td class="text-center">
                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                        class="btn btn-sm btn-outline-primary view-btn"
                                        data-bs-toggle="tooltip"
                                        title="Visualizar candidato">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $lugar++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card de Classificação - Cotas -->
    <div class="card classification-card mt-4">
        <div class="card-header dashboard-header mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-trophy"></i>
                Classificação dos Candidatos - Cotas
            </span>
        </div>

        <div class="card-body">
            <!-- Alertas Informativos -->
            <div class="alert alert-warning alert-dismissible">
                <div class="d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle mr-10 fa-lg"></i>
                    <div>
                        <strong>Linha com destaque amarelo:</strong> Candidato concorrendo em mais de uma especialidade
                    </div>
                </div>
            </div>

            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-start">
                    <i class="fa fa-info-circle mr-10 mt-10"></i>
                    <div>
                        <strong class="d-block mb-2">Códigos da Coluna Categoria:</strong>
                        <div class="military-codes-grid">
                            <span class="military-code-item">
                                <span class="military-code-badge">1</span>
                                <span class="military-code-text">Oficial da Ativa</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">2</span>
                                <span class="military-code-text">Oficial R2</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">3</span>
                                <span class="military-code-text">Aspirante R2</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">4</span>
                                <span class="military-code-text">Praça Ativa</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">5</span>
                                <span class="military-code-text">Reservista 1ª Cat</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">6</span>
                                <span class="military-code-text">Reservista 2ª Cat</span>
                            </span>

                            <span class="military-code-separator">|</span>

                            <span class="military-code-item">
                                <span class="military-code-badge">7</span>
                                <span class="military-code-text">Civil</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Classificação -->
            <div class="table-responsive">
                <table class="table table-hover table-striped classification-table tabela_dinamica">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><i class="fa fa-trophy"></i> Posição</th>
                            <th><i class="fa fa-user"></i> Candidato</th>
                            <th><i class="fa fa-id-card"></i> CPF</th>
                            <th><i class="fa fa-envelope"></i> E-Mail</th>
                            <th><i class="fa fa-commenting"></i> Autodeclaração</th>
                            <th class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                            <th class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Dias SV</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                            <th class="text-center"><i class="fa fa-list"></i> Etapa Especialidade</th>
                            <th><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                            <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lugar = 1;
                        foreach ($vetor_ordenado_candidatos as $linha):
                            // Mostrar apenas candidatos de cotas
                            if ($linha['vaga_reservada'] == 0) continue;

                            // Verificar se é candidato com múltiplas especialidades
                            $is_multiple_especialidades = false;
                            if ($linha['etapa'] > 2) {
                                $resultado = $conexao->get_candidatos_mais_uma_especialidade_id_usuario($linha['id']);
                                $is_multiple_especialidades = count($resultado) > 0;
                            }
                        ?>
                            <tr class="<?= $is_multiple_especialidades ? 'multiple-especialidade' : '' ?>">
                                <!-- Posição -->
                                <td class="text-center">
                                    <div class="position-badge">
                                        <span class="position-number"><?= $lugar ?></span>
                                    </div>
                                </td>

                                <!-- Nome -->
                                <td>
                                    <div class="candidate-info">
                                        <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome']) ?></div>
                                    </div>
                                </td>

                                <!-- CPF -->
                                <td>
                                    <?= $linha['cpf'] ?>
                                </td>

                                <!-- E-Mail -->
                                <td>
                                    <?= htmlspecialchars($linha['mail']) ?>
                                </td>

                                <!-- Autodeclaração -->
                                <td class="text-center">
                                    <span class="autodeclaracao-badge">
                                        <?= ucfirst($linha['autodeclaracao']) ?>
                                    </span>
                                </td>

                                <!-- Pontuação -->
                                <td class="text-center">
                                    <span class="score-badge"><?= $linha['pontos'] ?></span>
                                </td>

                                <!-- Categoria Militar -->
                                <td class="text-center">
                                    <span class="military-badge" data-code="<?= $linha['militar'] ?>">
                                        <?= $linha['militar'] ?>
                                    </span>
                                </td>

                                <!-- Dias Serviço Público -->
                                <td class="text-center">
                                    <span class="days-badge" data-bs-toggle="tooltip" title="Tempo de serviço público em dias">
                                        <?= $linha['tempo_sv_pub'] ?>
                                    </span>
                                </td>

                                <!-- Dias Idade -->
                                <td class="text-center">
                                    <span class="days-badge" data-bs-toggle="tooltip" title="Idade em dias">
                                        <?= $linha['tempo_idade'] ?>
                                    </span>
                                </td>

                                <!-- Etapas -->
                                <td class="text-center">
                                    <span class="etapa-badge etapa-<?= $linha['etapa'] ?>">
                                        et_<?= $linha['etapa'] ?>
                                    </span>
                                </td>

                                <!-- Cidade -->
                                <td>
                                    <span class="city-badge"><?= $linha['cidade_escolheu_servir'] ?: '-' ?></span>
                                </td>

                                <!-- Ações -->
                                <td class="text-center">
                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                        class="btn btn-sm btn-outline-primary view-btn"
                                        data-bs-toggle="tooltip"
                                        title="Visualizar candidato">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $lugar++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>