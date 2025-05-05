<div class="card">
        
        <?php
        
            $vetor_ordenado_candidatos = array();
                    
            foreach ($lista_candidatos as $linha) 
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

                $militar = 8;

                // Os CB da Ativa 08613087000 pronto
                if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "cd"))
                    $militar = 1;
 
                // Os SD da ativa 68965326028 pronto
                if($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "sd"))
                    $militar = 2;

                // Os Cb Reservistas 1ª Categoria 36396970066 pronto
                if($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "cd"))
                    $militar = 3;

                // Os Sd Reservistas 1ª Categoria 53842430078 pronto
                if($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "1crm" && $linha['posto_grad'] == "sd"))
                    $militar = 4;

                // Os Cb Reservistas 2ª Categoria  98454440089 pronto 
                if($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "cd"))
                    $militar = 5;

                // Os Sd Reservistas 2ª Categoria 86133549041 pronto
                if($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "2crm" && $linha['posto_grad'] == "sd"))
                    $militar = 6;
 
                // Os Civis Portadores de CDI 89447430023 pronto
                if($linha['civil_militar'] == 'civil' && ($linha['certificado'] == "cdi"))
                    $militar = 7;

                // O de Menor tempo de Serviço  37602159047 pronto

                 // O de Menor idade  94306177025 pronto

                 // O Mil Sd 2a cat de maior idade 39387225097 pronto

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
                    "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
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
                        $tempo_idade, SORT_ASC, 
                        $vetor_ordenado_candidatos);
                }
            }
        
        ?>
        
        <legend>Classificação dos candidatos</legend>
        <div style="background: yellow"> <b>Linha com fundo amarelo</b>: Candidato acima da etapa 2 concorrendo em mais de uma especialidade</div>
        <br>
        <div class="card-body">
            <div class="alert alert-dismissible alert-success" style="text-align: justify">
            <font color="black"><b>Códigos da coluna Militar: </b></font>
            <u><b> 1 </b>- CB da Ativa </u> - | -
            <u><b> 2 </b>- SD da Ativa </u>  - | -
            <u><b> 3 </b>- CB Reservista 1ª Cat </u> - | -
            <u><b> 4 </b>- SD Reservista 1ª Cat </u> - | -
            <u><b> 5 </b>- CB Reservista 2ª Cat </u> - | -
            <u><b> 6 </b>- SD Reservista 2ª Cat </u> - | -
            <u><b> 7 </b>- Civil CDI </u>
        </div> 
            <table class="table table-hover table-bordered" id="tabela_dinamica2">
                <thead>
                    <tr>
                      <th>Lugar</th>
                      <th>Nome</th>
                      <th>CPF</th>
                      <th>Pontuação</th>
                      <th>Militar</th>
                      <th>Dias SV Mil</th>
                      <th>Dias Idade </th>
                      <th>E-Mail </th>
                      <th>Etapa </th>
                      <th>Cidade Escolheu</th>
                      <th>Ver</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    
                    $lugar = 1;
                    foreach ($vetor_ordenado_candidatos as $linha) 
                    {
                        
                        $cor_linha = "";
                        if($linha['etapa'] > 2)
                        {
                            $resultado = $conexao->get_candidatos_mais_uma_especialidade_id_usuario($linha['id']);
                            if(count($resultado) > 0)
                                $cor_linha = ' bgcolor = "yellow" ';
                        }
                        
                        /*
                        $foto = "user.jpg";
                        $get_foto = $conexao->get_foto_usuario($linha['id']);  
                        if(count($get_foto) > 0)
                            $foto = $get_foto[0]['nome'];
                        */
                        echo '
                        <tr '.$cor_linha.'>
                            <td>'.$lugar.'º</td>
                            <td>'.$linha['nome'].'</td>
                            <td>'.$linha['cpf'].'</td>
                            <td>'.$linha['pontos'].'</td>
                            <td>'.$linha['militar'].'</td>
                            <td>'.$linha['tempo_sv_pub'].'</td>
                            <td>'.$linha['tempo_idade'].'</td>
                            <td>'.$linha['mail'].'</td>
                            <td>et_'.$linha['etapa'].'</td>
                            <td>'.$linha['cidade_escolheu_servir'].'</td>
                            <td width="40px" align="center"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'">ver</a></td>
                        </tr>';
                        //<img class="img-circle" src="fotos/'.$foto.'" width="40px">
                        $lugar ++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>