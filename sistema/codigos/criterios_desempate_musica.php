<div>
    <?php

    $vetor_ordenado_candidatos = array();

    foreach ($lista_candidatos as $linha) {
        ///////////////////////////////
        //  PONTUAÇÃO
        ///////////////////////////////

        // Currículo
        $pontuacao_curriculo = 0;
        $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade_selecionada);
        if (count($get_pontuacao_avaliada) > 0)
            $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);

        // Provas
        $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'], $id_especialidade_selecionada);

        $prova_pratica_musica = 0;
        $prova_escrita_musica = 0;
        $prova_oral_musica = 0;

        if (count($get_pontuacao_provas) > 0) {
            $prova_pratica_musica = $get_pontuacao_provas[0]['prova_pratica_musica'];
            $prova_escrita_musica = $get_pontuacao_provas[0]['prova_teorica_musica'];
            $prova_oral_musica = $get_pontuacao_provas[0]['prova_oral_musica'];
        }

        $somatorio_total_pontos_musica = 0;
        $somatorio_total_pontos_musica = (((($prova_escrita_musica * 2) + ($prova_pratica_musica * 2) + $prova_oral_musica) / 5) + $pontuacao_curriculo) / 2;
        $pontuacao_curriculo = round($somatorio_total_pontos_musica, 2);


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
        if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
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
                "mail" => $linha['mail'],
                "cotista" => $linha['vaga_reservada'],
                "autodeclaracao" => $linha['autodeclaracao'],
                "pontos" => $pontuacao_curriculo,
                "pratica" => $prova_pratica_musica,
                "escrita" => $prova_escrita_musica,
                "oral" => $prova_oral_musica,
                "militar" => $militar,
                "etapa" => $linha['etapa'],
                "etapa_candidato" => $linha['etapa_candidato'],
                "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                "tempo_idade" => $tempo_total_idade_dias,
                "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
            ];

        array_push($vetor_ordenado_candidatos, $novo_vetor);
    }

    if (count($lista_candidatos) > 0) {
        foreach ($vetor_ordenado_candidatos as $index => $linha2) {
            $id_array[$index]      = $linha2['id'];
            $nome_array[$index]    = $linha2['nome'];
            $pontos_array[$index]  = $linha2['pontos'];

            $prova_pratica_array[$index]  = $linha2['pratica'];
            $prova_escrita_array[$index]  = $linha2['escrita'];
            $prova_oral_array[$index]  = $linha2['oral'];
            $militar_array[$index] = $linha2['militar'];
            $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
            $tempo_idade[$index]   = $linha2['tempo_idade'];
        }

        array_multisort(
            $pontos_array,
            SORT_DESC,
            $prova_pratica_array,
            SORT_DESC,
            $prova_escrita_array,
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
            <div class="alert alert-warning alert-dismissible mb-3">
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
                        <strong class="d-block mb-2">Códigos Militares:</strong>
                        <div class="military-codes">
                            <span class="military-code-item">
                                <strong>1</strong> - Oficial da Ativa
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>2</strong> - Oficial R2
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>3</strong> - Aspirante R2
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>4</strong> - Praça Ativa
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>5</strong> - Reservista de 1ª categoria
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>6</strong> - Reservista de 2ª categoria
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>7</strong> - Civil
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
                            <th width="140px"><i class="fa fa-id-card"></i> Cotista</th>
                            <th width="100px" class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                            <th width="90px" class="text-center"><i class="fa fa-pencil"></i> Prática</th>
                            <th width="90px" class="text-center"><i class="fa fa-pencil"></i> Escrita</th>
                            <th width="80px" class="text-center"><i class="fa fa-shield"></i> Categoria</th>
                            <th width="110px" class="text-center"><i class="fa fa-clock-o"></i> Dias SV</th>
                            <th width="100px" class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                            <th width="130px" class="text-center"><i class="fa fa-list"></i> Etapa Especialidade</th>
                            <th width="150px"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                            <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lugar = 1;
                        foreach ($vetor_ordenado_candidatos as $linha):
                            // Pular candidatos de cotas na ampla concorrência
                            if ($linha['vaga_reservada'] == 1) continue;

                            $foto = "user.jpg";
                            $get_foto = $conexao->get_foto_usuario($linha['id']);
                            if (count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];

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

                                <!-- Candidato -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="fotos/<?= $foto ?>"
                                            class="rounded-circle me-3 candidate-photo"
                                            width="40"
                                            height="40"
                                            alt="Foto">
                                        <div>
                                            <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome']) ?></div>
                                        </div>
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
                                    <span class="score-badge"><?= $linha['pontos'] ?></span>
                                </td>

                                <!-- Notas -->
                                <td class="text-center">
                                    <span class="note-badge"><?= $linha['pratica'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="note-badge"><?= $linha['escrita'] ?></span>
                                </td>

                                <!-- Código Militar -->
                                <td class="text-center">
                                    <span class="military-badge" data-code="<?= $linha['militar'] ?>">
                                        <?= $linha['militar'] ?>
                                    </span>
                                </td>

                                <!-- Dias -->
                                <td class="text-center">
                                    <span class="days-badge"><?= $linha['tempo_sv_pub'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="days-badge"><?= $linha['tempo_idade'] ?></span>
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
            <div class="alert alert-warning alert-dismissible mb-3">
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
                        <strong class="d-block mb-2">Códigos Militares:</strong>
                        <div class="military-codes">
                            <span class="military-code-item">
                                <strong>1</strong> - Oficial da Ativa
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>2</strong> - Oficial R2
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>3</strong> - Aspirante R2
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>4</strong> - Praça Ativa
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>5</strong> - Reservista de 1ª categoria
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>6</strong> - Reservista de 2ª categoria
                            </span>
                            <span class="military-code-separator">|</span>
                            <span class="military-code-item">
                                <strong>7</strong> - Civil
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
                            <th width="200px"><i class="fa fa-envelope"></i> E-Mail</th>
                            <th width="150px" class="text-center"><i class="fa fa-commenting"></i> Autodeclaração</th>
                            <th width="100px" class="text-center"><i class="fa fa-trophy"></i> Pontuação</th>
                            <th width="90px" class="text-center"><i class="fa fa-pencil"></i> Prática</th>
                            <th width="90px" class="text-center"><i class="fa fa-pencil"></i> Escrita</th>
                            <th width="80px" class="text-center"><i class="fa fa-shield"></i> Militar</th>
                            <th width="110px" class="text-center"><i class="fa fa-clock-o"></i> Dias SV</th>
                            <th width="100px" class="text-center"><i class="fa fa-clock-o"></i> Dias Idade</th>
                            <th width="130px" class="text-center"><i class="fa fa-list"></i> Etapa Especialidade</th>
                            <th width="150px"><i class="fa fa-map-marker"></i> Cidade Escolhida</th>
                            <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lugar = 1;
                        foreach ($vetor_ordenado_candidatos as $linha):
                            // Mostrar apenas candidatos de cotas
                            if ($linha['vaga_reservada'] == 0) continue;

                            $foto = "user.jpg";
                            $get_foto = $conexao->get_foto_usuario($linha['id']);
                            if (count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];

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

                                <!-- Candidato -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="fotos/<?= $foto ?>"
                                            class="rounded-circle me-3 candidate-photo"
                                            width="40"
                                            height="40"
                                            alt="Foto">
                                        <div>
                                            <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome']) ?></div>
                                        </div>
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

                                <!-- Notas -->
                                <td class="text-center">
                                    <span class="note-badge"><?= $linha['pratica'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="note-badge"><?= $linha['escrita'] ?></span>
                                </td>

                                <!-- Código Militar -->
                                <td class="text-center">
                                    <span class="military-badge" data-code="<?= $linha['militar'] ?>">
                                        <?= $linha['militar'] ?>
                                    </span>
                                </td>

                                <!-- Dias -->
                                <td class="text-center">
                                    <span class="days-badge"><?= $linha['tempo_sv_pub'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="days-badge"><?= $linha['tempo_idade'] ?></span>
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