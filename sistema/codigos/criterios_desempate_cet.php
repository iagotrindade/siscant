<div class="card classification-card">
    <div class="card-header filter-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-users me-2"></i>
            Classificação dos Candidatos - Ampla concorrência
        </span>
    </div>

    <div class="card-body">
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

            $militar = 8;

            // Os CB da Ativa 08613087000 pronto
            if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "cd"))
                $militar = 1;

            // Os SD da ativa 68965326028 pronto
            if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "sd"))
                $militar = 2;

            // Os Cb Reservistas 1ª Categoria 36396970066 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "cd"))
                $militar = 3;

            // Os Sd Reservistas 1ª Categoria 53842430078 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "sd"))
                $militar = 4;

            // Os Cb Reservistas 2ª Categoria  98454440089 pronto 
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "cd"))
                $militar = 5;

            // Os Sd Reservistas 2ª Categoria 86133549041 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "sd"))
                $militar = 6;

            // Os Civis Portadores de CDI 89447430023 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "cdi"))
                $militar = 7;

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
                    "pontos" => $pontuacao_curriculo,
                    "militar" => $militar,
                    "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                    "tempo_idade" => $tempo_total_idade_dias,
                    "mail" => $linha['mail'],
                    "etapa" => $linha['etapa'],
                    "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
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
                    SORT_ASC,
                    $vetor_ordenado_candidatos
                );
            }
        }
        ?>

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
                            <span class="military-code-text">CB da Ativa</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">2</span>
                            <span class="military-code-text">SD da Ativa</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">3</span>
                            <span class="military-code-text">CB Reservista 1ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">4</span>
                            <span class="military-code-text">SD Reservista 1ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">5</span>
                            <span class="military-code-text">CB Reservista 2ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">6</span>
                            <span class="military-code-text">SD Reservista 2ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">7</span>
                            <span class="military-code-text">Civil CDI</span>
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
                        <th width="80px" class="text-center"><i class="fa fa-trophy"></i> Posição</th>
                        <th><i class="fa fa-user"></i> Candidato</th>
                        <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                        <th width="120px" class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                        <th width="100px" class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                        <th width="110px" class="text-center"><i class="fa fa--clock-o"></i> Dias SV</th>
                        <th width="110px" class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                        <th width="200px"><i class="fa fa-envelope"></i> E-Mail</th>
                        <th width="100px" class="text-center"><i class="fa fa-list"></i> Etapa</th>
                        <th width="150px"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                        <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lugar = 1;
                    foreach ($vetor_ordenado_candidatos as $linha):
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

                            <!-- E-Mail -->
                            <td>
                                <?= htmlspecialchars($linha['mail']) ?>
                            </td>

                            <!-- Etapa -->
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

<div class="card classification-card">
    <div class="card-header filter-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-trophy me-2"></i>
            Classificação dos Candidatos - Cotas
        </span>
    </div>

    <div class="card-body">
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

            $militar = 8;

            // Os CB da Ativa 08613087000 pronto
            if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "cd"))
                $militar = 1;

            // Os SD da ativa 68965326028 pronto
            if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "sd"))
                $militar = 2;

            // Os Cb Reservistas 1ª Categoria 36396970066 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "cd"))
                $militar = 3;

            // Os Sd Reservistas 1ª Categoria 53842430078 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "sd"))
                $militar = 4;

            // Os Cb Reservistas 2ª Categoria  98454440089 pronto 
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "cd"))
                $militar = 5;

            // Os Sd Reservistas 2ª Categoria 86133549041 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "sd"))
                $militar = 6;

            // Os Civis Portadores de CDI 89447430023 pronto
            if ($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "cdi"))
                $militar = 7;

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
                    "pontos" => $pontuacao_curriculo,
                    "militar" => $militar,
                    "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                    "tempo_idade" => $tempo_total_idade_dias,
                    "mail" => $linha['mail'],
                    "etapa" => $linha['etapa'],
                    "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
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
                    SORT_ASC,
                    $vetor_ordenado_candidatos
                );
            }
        }
        ?>

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
                            <span class="military-code-text">CB da Ativa</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">2</span>
                            <span class="military-code-text">SD da Ativa</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">3</span>
                            <span class="military-code-text">CB Reservista 1ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">4</span>
                            <span class="military-code-text">SD Reservista 1ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">5</span>
                            <span class="military-code-text">CB Reservista 2ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">6</span>
                            <span class="military-code-text">SD Reservista 2ª Cat</span>
                        </span>

                        <span class="military-code-separator">|</span>

                        <span class="military-code-item">
                            <span class="military-code-badge">7</span>
                            <span class="military-code-text">Civil CDI</span>
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
                        <th width="80px" class="text-center"><i class="fa fa-trophy"></i> Posição</th>
                        <th><i class="fa fa-user"></i> Candidato</th>
                        <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                        <th width="140px"><i class="fa fa-id-commenting"></i> Autodeclaração</th>
                        <th width="120px" class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                        <th width="100px" class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                        <th width="110px" class="text-center"><i class="fa fa--clock-o"></i> Dias SV</th>
                        <th width="110px" class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                        <th width="200px"><i class="fa fa-envelope"></i> E-Mail</th>
                        <th width="100px" class="text-center"><i class="fa fa-list"></i> Etapa</th>
                        <th width="150px"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                        <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lugar = 1;
                    foreach ($vetor_ordenado_candidatos as $linha):
                        if (!$linha['vaga_reservada']) {
                            continue;
                        }

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

                            <td class="text-center">
                                <span class="autodeclaracao-badge">
                                    <?= ucfirst($linha['autodeclaracao']) ?>
                                </span>
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

                            <!-- E-Mail -->
                            <td>
                                <?= htmlspecialchars($linha['mail']) ?>
                            </td>

                            <!-- Etapa -->
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