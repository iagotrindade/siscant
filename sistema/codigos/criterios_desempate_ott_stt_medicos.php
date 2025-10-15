<div class="card">
        
        <?php
        
            $vetor_ordenado_candidatos = array();
                    
            foreach ($lista_candidatos as $linha) 
            {
                if($voluntario_obrigatorio == 'voluntario' && ($linha['medico_obrigatorio'] == '1' || $linha['medico_obrigatorio'] == 1 )) continue;
                if($voluntario_obrigatorio== 'obrigatorio' && ($linha['medico_obrigatorio'] == '0' || $linha['medico_obrigatorio'] == null)) continue;
                
                
                ///////////////////////////////
                //  PONTUAÇÃO
                ///////////////////////////////
                
                $pontuacao_curriculo = 0;
                $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'],$id_especialidade_selecionada);  
                if(count($get_pontuacao_avaliada) > 0)
                    $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'],2);


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
                    "adiamento_data_inicio" => $linha['data_inicio_adiamento'],
                    "adiamento_data_fim" => $linha['data_fim_adiamento'],
                    "historico_judicial" => $linha['historico_judicial'],
                    "situacao_pos_cse" => $linha['refratario_impedido'],
                    "pontos" => $pontuacao_curriculo,
                    "militar" => $militar,
                    "tempo_sv_pub" => $tempo_total_sv_publico_dias,
                    "tempo_idade" => $tempo_total_idade_dias,
                    "mail" => $linha['mail'],
                    "etapa" => $linha['etapa']
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
        
        <legend>Classificação dos candidatos MÉDICOS</legend>
        <div class="card-body">
            <div class="alert alert-dismissible alert-success" style="text-align: justify">
            <font color="black"><b>Códigos Militar: </b></font>
            <u><b> 1 </b>- Oficial da Ativa </u> - | -
            <u><b> 2 </b>- Oficial R2 </u>  - | -
            <u><b> 3 </b>- Aspitante R2 </u> - | -
            <u><b> 4 </b>- Praça Ativa </u> - | -
            <u><b> 5 </b>- Reservista de 1ª categoria </u> - | -
            <u><b> 6 </b>- Reservista de 2ª categoria </u> - | -
            <u><b> 7 </b>- Civil  </u>
        </div> 
            <table class="table table-hover table-bordered tabela_dinamica">
                <thead>
                    <tr>
                      <th>Lugar</th>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Dt Ini Adiamento</th>
                      <th>Dt Fim Adiamento</th>
                      <th>Pontuação</th>
                      <th>Militar</th>
                      <th>Dias SV Mil</th>
                      <th>Dias Idade </th>
                      <th>Histórico Judicial</th>
                      <th>Etapa </th>
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
                        $data_inicio_adiamento = null;
                        if($linha['adiamento_data_inicio'] != null || $linha['adiamento_data_inicio'] != '')
                            $data_inicio_adiamento = trata_data ($linha['adiamento_data_inicio']);
                        
                        $data_fim_adiamento = null;
                        if($linha['adiamento_data_fim'] != null || $linha['adiamento_data_fim'] != '')
                            $data_fim_adiamento = trata_data ($linha['adiamento_data_fim']);
                        
                        $historico_judicial = null;
                        if($linha['historico_judicial'] == '0') $historico_judicial = 'Não';
                        if($linha['historico_judicial'] == '1') $historico_judicial = 'Sim';
                        
                        echo '
                        <tr>
                            <td>'.$lugar.'</td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['nome'].'</a></td>
                            <td><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['cpf'].'</a></td>
                            <td>'.$data_inicio_adiamento.'</td>
                            <td>'.$data_fim_adiamento.'</td>
                            <td>'.$linha['pontos'].'</td>
                            <td>'.$linha['militar'].'</td>
                            <td>'.$linha['tempo_sv_pub'].'</td>
                            <td>'.$linha['tempo_idade'].'</td>
                            <td>'.$historico_judicial.'</td>
                            <td>et_'.$linha['etapa'].'</td>
                        </tr>';
                        //<img class="img-circle" src="fotos/'.$foto.'" width="40px">
                        $lugar ++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>