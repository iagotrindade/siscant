
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
                                                
                        /*
                        if($id_candidato_x_especialidade != null)
                        {
                            ////////////// CIDADES CADASTRADAS
                            $lista_cidades = $conexao->get_cidades_especialidade_candidato($id_especialidade, $id_candidato_x_especialidade);  
                            foreach ($lista_cidades as $linha) 
                            {
                                //echo $linha['nome']."<br>";
                            }
                        }
                        */
                        
                        echo '<a name=avaliacao_id_'.$id_especialidade.'></a>';
                        
                        ?>
                       
        <div class="card">          
            <legend>Currículo da <?php echo "especialidade: <u><a href='relatorio_especialidade_candidato.php?id_especialidade=".$valor['id_especialidade']."'>" . mb_strtoupper($valor['ott_stt'], "UTF-8") . " - " .$valor['especialidade'] . "</a></u><br>"; ?></u></legend>
            <div class="card-body">
                <table class="table table-hover table-bordered" >
                    <thead>
                      <tr>
                        <th>Nome do arquivo</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Resumo do PDF</th>
                        <th>Justificativa da invalidez</th>
                        <th>Pts</th>
                        <th>Avaliado</th>
                        
                      </tr>
                    </thead>
                        <tbody>

                        <?php
                        
                        $pontuacao_total = 0;
                        $pontuacao = 0;
                        
                            if($id_especialidade != null)
                            {
                                $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  

                                foreach ($lista_curriculo_adicionado as $linha) 
                                {
                                    $validado = null;
                                    
                                    if($linha['pontuacao'] != null && $linha['pontuacao'] > 0)
                                    {
                                        if($linha['valido'] == '1')
                                        {
                                            $pontuacao = $linha['pontuacao'] / 1000;
                                            $pontuacao_total = $pontuacao_total + $pontuacao;
                                        }
                                    }
                                    
                                    
                                    $usuario_avaliou = $conexao->get_usuario_id($linha['usuario_avaliou']);  
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['usuario_avaliou']."'><img class='img-circle' src='fotos/user.jpg' width='40px'>";
                                    if(count($usuario_avaliou) > 0)
                                    {
                                        $get_foto = $conexao->get_foto_usuario($usuario_avaliou[0]['id']);  
                                        if(count($get_foto) > 0)
                                        {
                                            $foto = $get_foto[0]['nome'];
                                            $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['usuario_avaliou']."'><img class='img-circle' src='fotos/$foto' width='40px'>";
                                        }
                                    }
                                    
                                    if($linha['valido'] == '0')
                                        $validado = "<img  src='imagens/no_like.jpg' width='40px'>".$foto;
                                    if($linha['valido'] == '1')
                                        $validado = "<img  src='imagens/like.jpg' width='40px'>".$foto;
                                    
                                    $crip = hash('sha256', $_SESSION['chave']."freitas".$linha['id_especialidade_curriculo']);

                                    $dt_inicio = null;
                                    if($linha['data_inicio'] != null)
                                        $dt_inicio = trata_data ($linha['data_inicio']);

                                    $dt_fim = null;
                                    if($linha['data_termino'] != null)
                                        $dt_fim = trata_data ($linha['data_termino']);

                                    $justificativa = $linha['justificativa'];
                                    
                                    echo '
                                    <tr>
                                        <td style="width:30%;"><a href="baixaPDF.php?codigo=can_esp_vis&nome_arquivo='.$linha['nome'].'" target="_blank">'.$linha['label'].'</a></td>
                                        <td>'.$dt_inicio.'</td>
                                        <td>'.$dt_fim.'</td>
                                        <td>'.$linha['carga_horaria'].'</td>
                                        <td>'.$linha['justificativa'].'</td>
                                        <td>'.$pontuacao.'</td>
                                        <td>'.$validado.'</td>
                                    </tr>';
                                }
                            }
                        ?>   
                        
                        
                    </tbody>
                </table>
                
                
                
            </div>
            
            <?php
            
                $somatorio_total_pontos_musica = (((($prova_teorica_musica*2) + ($prova_pratica_musica*2) + $prova_oral_musica)/5)+$pontuacao)/2;
                        
                if(strlen($prova_pratica_musica) < 5)
                  $prova_pratica_musica = $prova_pratica_musica . "0";
                if(strlen($prova_pratica_musica) < 4)
                  $prova_pratica_musica = $prova_pratica_musica . "00";
                
                if(strlen($prova_oral_musica) < 5)
                  $prova_oral_musica = $prova_oral_musica . "0";
                if(strlen($prova_oral_musica) < 4)
                  $prova_oral_musica = $prova_oral_musica . "00";
                
                if(strlen($prova_teorica_musica) < 5)
                  $prova_teorica_musica = $prova_teorica_musica . "0";
                if(strlen($prova_teorica_musica) < 4)
                  $prova_teorica_musica = $prova_teorica_musica . "00";
            
                        
                if($valor['musica'] == '1')
                {
                    echo '<form action="../banco_dados/prova_musica_salvar.php" method="post">

                    <input hidden type="text" value="'.$id_usuario.'" name="id_usuario">
                    <input hidden type="text" value="'.$id_especialidade.'" name="id_especialidade">
                    <input hidden type="text" value="'.$crip.'" name="criptografia">
                    <input hidden type="text" value="'.$linha['id_especialidade_curriculo'].'" name="id_especialidade_curriculo">
                    <input hidden type="text" value="'.$linha['id_candidato_x_especialidade'].'" name="id_candidato_x_especialidade">

                        <table border = 0 style="width: 100%">
                            <tbody>
                            <tr>
                                <td><b>Pontuação da Prova Prática:</b> '.$prova_pratica_musica.'</td>
                                <td><b>Pontuação da Prova Oral: </b>'.$prova_oral_musica.'</td>
                                <td><b>Pontuação da Prova Teórica: </b>'.$prova_teorica_musica.'</td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </form>';
                }
                        
            ?>
            
            <br>
            <br>
            
            <table border = 1 style="width: 100%">
                <tbody>
                <tr>
                    <td >SOMATÓRIO DOS PONTO VÁLIDOS</td>
                    <td ><b><?php 
                            if($valor['musica'] )
                                echo $somatorio_total_pontos_musica;
                            else 
                                echo $pontuacao 
                        ?></b></td>
                </tr>
                </tbody>
            </table>
            
            
        </div>
                        
                        
                        
                <?php
                
                    }

                ?>
            
            

        
    </div>
</div>






