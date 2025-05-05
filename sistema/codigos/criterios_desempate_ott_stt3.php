<div class="card">
        
        <?php
        
            $vetor_ordenado_candidatos = array();

            $lista_candidatos_desclassificados = $conexao->get_candidatos_especialidade_desclassificados($id_especialidade_selecionada);  
            
            foreach ($lista_candidatos_desclassificados as $linha) 
            {
                if($voluntario_obrigatorio == 'voluntario'  && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1 )) continue;
                if($voluntario_obrigatorio == 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;
                
                
                ///////////////////////////////
                //  PONTUAÇÃO
                ///////////////////////////////
                
                $pontuacao_curriculo = 0;
                $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$id_especialidade_selecionada);  
                if(count($get_pontuacao_avaliada) > 0)
                    $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],2);
                
                ///////////////////////////////
                // Prova Teórico Prática
                ///////////////////////////////
                if($select_nota_prova_pratica_teorica != 0)
                {
                    $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'],$id_especialidade_selecionada);  
                    $nota_prova_teorico_pratico = 0;
                    if(count($get_pontuacao_provas) > 0)
                    {
                        $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
                        $pontuacao_curriculo = round ($pontuacao_curriculo + $nota_prova_teorico_pratico,2);
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
                if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten" || $linha['posto_grad'] == "asp"))
                    $militar = 1;

                // Oficial R2
                if($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten"))
                    $militar = 2;

                // Aspirante R2
                if($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "asp"))
                    $militar = 3;

                // Praça Ativa
                if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd"))
                    $militar = 4;

                // Reservista de 1ª categoria
                if($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil'))
                    $militar = 5;

                // Reservista de 2ª categoria
                if($linha['certificado'] == '2crm')
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
                    "nome" => mb_strtoupper($linha['nome_completo'],"UTF-8"),
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

                array_push($vetor_ordenado_candidatos,$novo_vetor);
            }

            if(count($lista_candidatos) > 0)
            {

                foreach ($vetor_ordenado_candidatos as $index => $linha2) 
                {
                    $pontos_array[$index]  = $linha2['pontos'];
                    $militar_array[$index] = $linha2['militar'];
                    $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
                    $tempo_idade[$index]   = $linha2['tempo_idade'];
                }
                
                if(count($vetor_ordenado_candidatos) > 0)
                {

                    array_multisort(
                        $pontos_array,  SORT_DESC, 
                        $militar_array, SORT_ASC, 
                        $tempo_sv_pub, SORT_ASC, 
                        $tempo_idade, SORT_DESC, 
                        $vetor_ordenado_candidatos);
                }
            }
        
        ?>
        
        <legend>Classificação de TODOS os candidatos (Classificados e desclassificados)</legend>
        <div class="card-body">
            <div class="alert alert-dismissible alert-danger" style="text-align: justify">
                <font color="black"><b>Códigos Militar: </b></font>
                <u><b> 1 </b>- Oficial da Ativa </u> - | -
                <u><b> 2 </b>- Oficial R2 </u>  - | -
                <u><b> 3 </b>- Aspitante R2 </u> - | -
                <u><b> 4 </b>- Praça Ativa </u> - | -
                <u><b> 5 </b>- Reservista de 1ª categoria </u> - | -
                <u><b> 6 </b>- Reservista de 2ª categoria </u> - | -
                <u><b> 7 </b>- Civil  </u>
            </div> 
            <table class="table table-hover table-bordered" id="tabela_dinamica4">
                <thead>
                    <tr>
                      <th>Lugar</th>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Pontuação</th>
                      <th>Militar</th>
                      <th>Dias SV Mil</th>
                      <th>Dias Idade </th>
                      <th>Etapa </th>
                      <th>Status</th>
                      <th>Cidade Escolheu</th>
                      <th>Cidade 1º Fase</th>
                      <th>OM 1º Fase</th>
                      <th>Cidade Dist</th>
                      <th>OM Dist</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                    $lugar = 1;
                    foreach ($vetor_ordenado_candidatos as $linha) 
                    {
                        /*
                        $foto = "user.jpg";
                        $get_foto = $conexao->get_foto_usuario($linha['id']);  
                        if(count($get_foto) > 0)
                            $foto = $get_foto[0]['nome'];
                        */
                        
                        $concorrendo = null;
                        $concorrendo_especialidade = null;
                        
                        if($linha['especialidade_concorrendo'] == '1') $concorrendo_especialidade = "<font color='green'>Concorrendo</font>";
                        if($linha['especialidade_concorrendo'] == '0') $concorrendo_especialidade = "<font color='red'>Desclassificado: ".$linha['justificativa_especialidade']."</font>";
                        
                        if($linha['candidato_concorrendo'] == '1') $concorrendo = "<font color='green'>";
                        if($linha['candidato_concorrendo'] == '0') 
                        {
                            $concorrendo = "<font color='red'>";
                            $concorrendo_especialidade = "<font color='red'>" . $linha['justificativa_concorrendo'] . "</font>";// "<font color='red'>Desclassificado do processo seletivo</font>";
                        }
                        
                        echo '
                        <tr>
                            <td>'.$lugar.'º</td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$concorrendo.$linha['nome'].'</font></a></td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                            <td>'.$linha['pontos'].'</td>
                            <td>'.$linha['militar'].'</td>
                            <td>'.$linha['tempo_sv_pub'].'</td>
                            <td>'.$linha['tempo_idade'].'</td>
                            <td>et_'.$linha['etapa'].'</td>
                            <td>'.$concorrendo_especialidade.'</td>
                            <td>'.$linha['cidade_escolheu_servir'].'</td>
                            <td>'.$linha['nome_cidade_1_fase'].'</td>
                            <td>'.$linha['abreviatura_om_1_fase'].'</td>
                            <td>'.$linha['cidade_distribuicao'].'</td>
                            <td>'.$linha['om_dist_abreviatura'].'</td>
                        </tr>';
                        //<img class="img-circle" src="fotos/'.$foto.'" width="40px">
                        $lugar ++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>