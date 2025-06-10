<a name="especialidades"></a>
<div class="row">
    <div class="col-md-12">
       
                <?php
                
                    $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                    foreach ($inscricoes as $valor)
                    {
                        
                        //echo "Especialidade: " . mb_strtoupper($valor['ott_stt'], "UTF-8") . " " .$valor['especialidade'] . "<br>";
                        $id_candidato_x_especialidade = null;
                        $resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario,$valor['id_especialidade']);

                        if(count($resultado_verificacao) > 0)
                        {
                            $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
                            $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
                            $nome_especialidade = $resultado_verificacao[0]['especialidade'];
                            $cpf_candidato      = $resultado_verificacao[0]['cpf'];
                            $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
                            $ott_stt            = $resultado_verificacao[0]['ott_stt'];
                            
                            $prova_pratica_musica           = $resultado_verificacao[0]['prova_pratica_musica'];
                            $prova_teorica_musica           = $resultado_verificacao[0]['prova_teorica_musica'];
                            $prova_oral_musica              = $resultado_verificacao[0]['prova_oral_musica'];
                            $usuario_avaliou_provas_musica  = $resultado_verificacao[0]['usuario_avaliou_provas_musica'];
                        }
                                                
                        
                        if($id_candidato_x_especialidade != null)
                            ////////////// CIDADES CADASTRADAS
                            $lista_cidades = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);  
             
                        
                        echo '<a name=avaliacao_id_'.$id_especialidade.'></a>';
                        
                        ?>
                       
        <div class="card" <?php if($_SESSION['perfil'] == 'documentos') echo ' hidden ' ?>>          
            <legend>Especialidade: 
                <?php 
                    $desclassificado_da_especialidade = "";
                    if($valor['concorrendo'] == 0) 
                        $desclassificado_da_especialidade = "<font color = 'red'> DESCLASSIFICADO: Justificativa ". $valor['justificativa']."</font>";
                                
                    echo "<u><a href='relatorio_especialidade_candidato.php?id_especialidade=".$valor['id_especialidade']."'>" 
                    . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'] . " $desclassificado_da_especialidade</a></u><br>"; 
                ?>
                </u></legend>
            <div class="card-body">
                
                <?php 
                    if(!inscricao() || $_SESSION['selecao_codigo'] == 'mfdv')
                    {
                        echo '<label> Prioridades de cidades selecionadas para essa especialidade:</label>';
                        
                        foreach ($lista_cidades as $linha) 
                        {
                            echo " " . $linha['prioridade']."ª "." ".$linha['nome'] . " | ";
                        }
                        echo "<br><br>";
                    }
                ?>
                
                <table class="table table-hover table-bordered" >
                    <thead>
                      <tr>
                        <th>Nome do arquivo</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Resumo do PDF</th>
                        
                      </tr>
                    </thead>
                        <tbody>

                        <?php
                        
                            if($id_especialidade != null)
                            {
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
                                
                                $pontuacao_total = 0;
                                
                                foreach ($lista_curriculo_adicionado as $linha) 
                                {
                                    $validado = null;
                                    $pontuacao = null;
                                    if($linha['pontuacao'] != null && $linha['pontuacao'] > 0)
                                    {
                                        if($linha['valido'] == '1')
                                        {
                                            $pontuacao = $linha['pontuacao'] / 1000;
                                            $pontuacao_total = $pontuacao_total + $pontuacao;
                                        }
                                    }
                                    
                                    $dt_inicio = null;
                                    if($linha['data_inicio'] != null)
                                        $dt_inicio = trata_data ($linha['data_inicio']);

                                    $dt_fim = null;
                                    if($linha['data_termino'] != null)
                                        $dt_fim = trata_data ($linha['data_termino']);

                                    
                                    echo '
                                    <tr>
                                        <td style="width:30%;"><a href="baixaPDF.php?codigo=cand_esp&nome_arquivo='.$linha['nome'].'" target="_blank">'.$linha['label'].'</a></td>
                                        <td>'.$dt_inicio.'</td>
                                        <td>'.$dt_fim.'</td>
                                        <td>'.$linha['carga_horaria'].'</td>
                                    </tr>';
                                }
                            }
                        ?>   
                        
                        
                    </tbody>
                </table>
                
                
                
            </div>
            
           
            
        </div>
                        
                        
                        
                <?php
                
                    }

                ?>
            
            

        
    </div>
</div>






