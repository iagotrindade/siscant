<div class="card classification-card">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-trophy"></i>
            Classificação de TODOS Candidatos (Classificados e Desclassificados)
        </span>
    </div>

    <div class="card-body">
        <?php
        $vetor_ordenado_candidatos = array();

        $lista_candidatos_desclassificados = $conexao->get_candidatos_especialidade_desclassificados($id_especialidade_selecionada);

        foreach ($lista_candidatos_desclassificados as $linha) {
            if ($voluntario_obrigatorio == 'voluntario'  && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1)) continue;
            if ($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;

            // PONTUAÇÃO
            $pontuacao_curriculo = 0;
            $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade_selecionada);
            if (count($get_pontuacao_avaliada) > 0)
                $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);

            // Prova Teórico Prática
            if ($select_nota_prova_pratica_teorica != 0) {
                $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'], $id_especialidade_selecionada);
                $nota_prova_teorico_pratico = 0;
                if (count($get_pontuacao_provas) > 0) {
                    $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
                    $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
                }
            }

            // CÓDIGOS MILITAR
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
            if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
                $militar = 4;

            // Reservista de 1ª categoria
            if ($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil'))
                $militar = 5;

            // Reservista de 2ª categoria
            if ($linha['certificado'] == '2crm')
                $militar = 6;

            // TEMPO SERVIÇO PÚBLICO
            $tempo_total_sv_publico_dias = 0;
            $anos_sv_publico = (int)$linha['tempo_sv_mil_anos'];
            $meses_sv_publico = (int)$linha['tempo_sv_mil_meses'];
            $dias_sv_publico = (int)$linha['tempo_sv_mil_dias'];
            $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + ($dias_sv_publico);

            // IDADE
            $tempo_total_idade_dias = 0;
            $data_atual = new DateTime(date("Y-m-d"));
            $data_nasc = new DateTime($linha['data_nascimento']);
            $intervalo = $data_atual->diff($data_nasc);
            $anos_vida  = (int)$intervalo->format('%Y');
            $meses_vida = (int)$intervalo->format('%m');
            $dias_vida  = (int)$intervalo->format('%d');
            $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + ($dias_vida);

            // ORDENA
            $novo_vetor = [
                "id" => $linha['id'],
                "nome" => mb_strtoupper($linha['nome_completo'], "UTF-8"),
                "cpf" => $linha['cpf'],
                "pontos" => $pontuacao_curriculo,
                "militar" => $militar,
                "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                "tempo_idade" => $tempo_total_idade_dias,
                "mail" => $linha['mail'],
                "etapa" => $linha['etapa'],
                "cidade_escolheu_servir" => $linha['cidade_escolheu_servir'],
                "cidade_distribuicao" => $linha['cidade_distribuicao'],
                "om_dist_abreviatura" => $linha['om_dist_abreviatura'],
                "abreviatura_om_1_fase" => $linha['abreviatura_om_1_fase'],
                "nome_cidade_1_fase" => $linha['nome_cidade_1_fase'],
                "especialidade_concorrendo" => $linha['especialidade_concorrendo'],
                "candidato_concorrendo" => $linha['concorrendo'],
                "justificativa_especialidade" => $linha['justificativa_especialidade'],
                "justificativa_concorrendo" => $linha['justificativa_concorrendo']
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

        <!-- Alertas Informativos -->
        <div class="alert alert-danger mb-4">
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
                        <th width="80px" class="text-center"><i class="fa fa-trophy"></i> Posição</th>
                        <th><i class="fa fa-user"></i> Candidato</th>
                        <th><i class="fa fa-id-card"></i> CPF</th>
                        <th class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                        <th class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                        <th class="text-center"><i class="fa fa-clock-o"></i> Dias SV</th>
                        <th class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                        <th class="text-center"><i class="fa fa-list"></i> Etapa</th>
                        <th class="text-center"><i class="fa fa-info-circle"></i> Status</th>
                        <th class="text-center"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                        <th class="text-center"><i class="fa fa-map-marker"></i> Cidade 1º Fase</th>
                        <th class="text-center"><i class="fa fa-building"></i> OM 1º Fase</th>
                        <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lugar = 1;
                    foreach ($vetor_ordenado_candidatos as $linha):
                        // Status do candidato
                        $status_class = 'success';
                        $status_icon = 'fa-check-circle';
                        $status_text = 'Concorrendo';

                        if ($linha['especialidade_concorrendo'] == '0') {
                            $status_class = 'danger';
                            $status_icon = 'fa-times-circle';
                            $status_text = 'Desclassificado: ' . htmlspecialchars($linha['justificativa_especialidade']);
                        }

                        if ($linha['candidato_concorrendo'] == '0') {
                            $status_class = 'danger';
                            $status_icon = 'fa-ban';
                            $status_text = htmlspecialchars($linha['justificativa_concorrendo']);
                        }

                        $is_desclassificado = $linha['especialidade_concorrendo'] == '0' || $linha['candidato_concorrendo'] == '0';
                    ?>
                        <tr class="<?= $is_desclassificado ? 'table-danger' : '' ?>">
                            <!-- Posição -->
                            <td class="text-center">
                                <div class="position-badge <?= $is_desclassificado ? 'bg-secondary' : '' ?>">
                                    <span class="position-number"><?= $lugar ?></span>
                                </div>
                            </td>

                            <!-- Nome -->
                            <td>
                                <div class="candidate-info">
                                    <span 
                                        class="fw-semibold candidate-name text-decoration-none <?= $is_desclassificado ? 'text-muted' : 'text-dark' ?>">
                                        <?= htmlspecialchars($linha['nome']) ?>
                                    </span>
                                    <br>
                                    <?php if ($is_desclassificado): ?>
                                        <small class="text-danger">
                                            <i class="fa fa-exclamation-triangle me-1"></i>
                                            Desclassificado
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- CPF -->
                            <td>
                                <div><?= $linha['cpf'] ?></div>
                            </td>

                            <!-- Pontuação -->
                            <td class="text-center">
                                <span class="score-badge <?= $is_desclassificado ? 'bg-secondary' : '' ?>">
                                    <?= $linha['pontos'] ?>
                                </span>
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

                            <!-- Etapa -->
                            <td class="text-center">
                                <span class="etapa-badge etapa-<?= $linha['etapa'] ?>">
                                    et_<?= $linha['etapa'] ?>
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <span class="status-badge" <?= $is_desclassificado ? 'style="color: #a94442"' : 'style="color: #006400"' ?>>
                                    <?= $status_text ?>
                                </span>

                            </td>

                            <!-- Cidades e OMs -->
                            <td class="text-center">
                                <span class="location-badge"><?= $linha['cidade_escolheu_servir'] ?: '-' ?></span>
                            </td>

                            <td class="text-center">
                                <span class="location-badge"><?= $linha['nome_cidade_1_fase'] ?: '-' ?></span>
                            </td>

                            <td class="text-center">
                                <span class="om-badge"><?= $linha['abreviatura_om_1_fase'] ?: '-' ?></span>
                            </td>

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