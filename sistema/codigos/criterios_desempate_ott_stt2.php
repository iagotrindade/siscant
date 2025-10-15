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
                
                $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'],$id_especialidade_selecionada);  
                $nota_prova_teorico_pratico = 0;
                if(count($get_pontuacao_provas) > 0)
                {
                    $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
                    $pontuacao_curriculo = round ($pontuacao_curriculo + $nota_prova_teorico_pratico,2);
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
                    "estado_civil" => $linha['estado_civil'],
                    "dependente" => $linha['dependente'],
                    "forca_distribuicao" => $linha['forca_distribuicao'],
                    "voluntario_sv_militar" => $linha['voluntario_sv_militar'],
                    "observacao_distribuicao" => $linha['observacao_distribuicao'],
                    "om_distribuicao" => $linha['om_distribuicao'],
                    "om_1_fase" => $linha['om_1_fase'],
                    "id_cidade_1_fase" => $linha['id_cidade_1_fase'],
                    "uf_1_fase" => $linha['uf_1_fase'],
                    "titular_reserva_distribuicao" => $linha['titular_reserva_distribuicao'],
                    "grupo_saude" => $linha['grupo_saude'],
                    "grupo_saude_recurso" => $linha['grupo_saude_recurso'],
                    "mail" => $linha['mail']
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
        
        <legend>Classificação dos candidatos 2</legend>
        <div class="card-body">
            
            <table class="table table-hover table-bordered tabela_dinamica">
                <thead>
                    <tr>
                      <th>Lugar</th>
                      <th>Nome</th>
                      <th>Pontos</th>
                      <th>Volntr</th>
                      <th>Est Civil</th>
                      <th>Nº Depnd</th>
                      <th>Força</th>
                      <th>Localidades</th>
                      <th>OM</th>
                      <th>Situação</th>
                      <th>OM 1ª Fase</th>
                      <th>Cidade 1º Fase</th>
                      <th>Grupo Saúde</th>
                      <th>OBS Distribuição</th>
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
                        
                        $id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($linha['id'],$id_especialidade_selecionada);
                        
                        $get_cidades_candidato_esp = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade[0]['id'])  ;
                        
                        $cidades_do_candidato = null;
                        
                        foreach ($get_cidades_candidato_esp as $linha35) 
                        {
                            $cidades_do_candidato = $cidades_do_candidato . " |_" . $linha35['prioridade'] . "ª_" . $linha35['nome'];
                        }
                        
                        $grupo_saude = null;
                        if($linha['grupo_saude'] != null)
                            $grupo_saude = mb_strtoupper($linha['grupo_saude'], 'UTF-8');
                        if($linha['grupo_saude_recurso'] != null)
                            $grupo_saude = $grupo_saude . " R: ". mb_strtoupper($linha['grupo_saude_recurso'], 'UTF-8');
                        
                        $nome_om_destino = null;
                        if($linha['om_distribuicao'] != null)
                        {
                            $get_om_id = $conexao->get_om_id($linha['om_distribuicao']);
                            $nome_om_destino = $get_om_id[0]['nome'];
                        }
                        
                        $om_1_fase_ = null;
                        if($linha['om_1_fase'] != null)
                        {
                            $get_om_1_fase_ = $conexao->get_om_id($linha['om_1_fase']);
                            $om_1_fase_ = $get_om_1_fase_[0]['nome'];
                        }
                        
                        $cidade_1_fase_ = null;
                        if($linha['id_cidade_1_fase'] != null)
                        {
                            $get_cidade_1_fase_ = $conexao->get_cidade_id($linha['id_cidade_1_fase']);
                            $cidade_1_fase_ = $get_cidade_1_fase_[0]['nome'];
                        }
                        
                        echo '
                        <tr>
                            <td>'.$lugar.'</td>
                            <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">'.$linha['nome'].'</a></td>
                            <td>'.$linha['pontos'].'</td>
                            <td>'.$linha['voluntario_sv_militar'].'</td>
                            <td>'.$linha['estado_civil'].'</td>
                            <td>'.$linha['dependente'].'</td>
                            <td>'.$linha['forca_distribuicao'].'</td>
                            <td>'.$cidades_do_candidato.'</td>
                            <td>'.$nome_om_destino.'</td>
                            <td>'.$linha['titular_reserva_distribuicao'].'</td>
                            <td>'.$om_1_fase_.'</td>
                            <td>_'.$cidade_1_fase_.'</td>
                            <td>'.$grupo_saude.'</td>
                            <td>'.$linha['observacao_distribuicao'].'</td>
                            
                        </tr>';
                        //<img class="img-circle" src="fotos/'.$foto.'" width="40px">
                        $lugar ++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>