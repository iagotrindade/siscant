<div class="card">
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

    <legend>Classificação dos candidatos</legend>
    <div style="background: yellow"> <b>Linha com fundo amarelo</b>: Candidato acima da etapa 2 concorrendo em mais de uma especialidade </div>
    <br>
    <div class="card-body">
        <div class="alert alert-dismissible alert-success" style="text-align: justify">
            <font color="black"><b>Códigos Militar: </b></font>
            <u><b> 1 </b>- Oficial da Ativa </u> - | -
            <u><b> 2 </b>- Oficial R2 </u> - | -
            <u><b> 3 </b>- Aspitante R2 </u> - | -
            <u><b> 4 </b>- Praça Ativa </u> - | -
            <u><b> 5 </b>- Reservista de 1ª categoria </u> - | -
            <u><b> 6 </b>- Reservista de 2ª categoria </u> - | -
            <u><b> 7 </b>- Civil </u>
        </div>

        <!-- 21/05/2025 Adicionando o campo etapa do candidato e alterando a etapa para etapa na especialidade -->
        <table class="table table-hover table-bordered" id="tabela_dinamica2">
            <thead>
                <tr>
                    <th>Lugar</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-Mail</th>
                    <th>Pontuação</th>
                    <th>Prática</th>
                    <th>Escrita</th>
                    <th>Militar</th>
                    <th>Dias SV Mil</th>
                    <th>Dias Idade</th>
                    <th>Etapa na Especialidade</th>
                    <th>Etapa Geral</th>
                    <th>Cidade Escolheu</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $lugar = 1;
                foreach ($vetor_ordenado_candidatos as $linha) {
                    $foto = "user.jpg";
                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                    if (count($get_foto) > 0)
                        $foto = $get_foto[0]['nome'];

                    $cor_linha = "";
                    if ($linha['etapa'] > 2) {
                        $resultado = $conexao->get_candidatos_mais_uma_especialidade_id_usuario($linha['id']);
                        if (count($resultado) > 0)
                            $cor_linha = ' bgcolor = "yellow" ';
                    }


                    echo '
                    <tr ' . $cor_linha . '>
                        <td>' . $lugar . 'º</td>
                        <td>' . $linha['nome'] . '</td>
                        <td>' . $linha['cpf'] . '</td>
                        <td>' . $linha['mail'] . '</td>
                        <td>' . $linha['pontos'] . '</td>
                        <td>' . $linha['pratica'] . '</td>
                        <td>' . $linha['escrita'] . '</td>
                        <td>' . $linha['militar'] . '</td>
                        <td>' . $linha['tempo_sv_pub'] . '</td>
                        <td>' . $linha['tempo_idade'] . '</td>
                        <td>et_' . $linha['etapa'] . '</td>
                        <td>et_' . $linha['etapa_candidato'] . '</td>
                    <td>' . $linha['cidade_escolheu_servir'] . '</td>
                        <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '"><img class="img-circle" src="fotos/' . $foto . '" width="40px"></a></td>
                    </tr>';

                    $lugar++;
                }
                ?>

            </tbody>
        </table>
    </div>
</div>