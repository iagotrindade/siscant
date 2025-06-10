<a name="recursos"></a>
<div class="row" <?php if($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin' && isset($_SESSION['eipot']) == 1) echo "hidden"; ?>>
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                    <div class="alert alert-dismissible ">
                        <legend>Análise de Recurso</legend> 
                        <div  class="row">
                        <div class="col-lg-12">
                        <br>
                        <?php
                                $especialidades_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);  
                        
                                $lista_recursos = $conexao->get_recursos_candidato($id_usuario); 
                                foreach ($lista_recursos as $linha) 
                                {
                                    $aparece = true;
                                    if($especialidades_avaliador != null)
                                    {
                                        if($linha['para_avaliador'] == '0') continue;
                                        $aparece = false;
                                        
                                        foreach($especialidades_avaliador as $especialidade)
                                        {
                                            if($linha['id_especialidade'] == $especialidade['id_especialidade'])
                                                $aparece = true;
                                        }
                                    }
                                    if($aparece == false) continue;
                                    
                                    $crip = hash('sha256', $linha['id']);

                                    $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['_usuario_ultima_atualizacao']."'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                    $get_foto = $conexao->get_foto_usuario($linha['_usuario_ultima_atualizacao']);  
                                    if(count($get_foto) > 0)
                                    {
                                        $foto = $get_foto[0]['nome'];
                                        $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['_usuario_ultima_atualizacao']."'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                                    }

                                    $usuario_ultima_at = null;
                                    $usuario_ultima_atualizacao = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
                                    if(count($usuario_ultima_atualizacao) == 1)
                                        $usuario_ultima_at =  $usuario_ultima_atualizacao[0]['posto_grad'].' '.$usuario_ultima_atualizacao[0]['nome_guerra'];

                                    $usuario_realizou_analise = null;
                                    $foto_analise = null;
                                    $get_usuario_realizou_analise = $conexao->get_usuario_id($linha['id_usuario_analise']); 
                                    
                                    if(count($get_usuario_realizou_analise) == 1)
                                    {
                                        $usuario_realizou_analise =  $get_usuario_realizou_analise[0]['posto_grad'].' '.$get_usuario_realizou_analise[0]['nome_guerra'];
                                        
                                        $foto_analise = "<a href='usuario_visualiza.php?id_usuario=".$linha['id_usuario_analise']."'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                        $get_foto = $conexao->get_foto_usuario($linha['id_usuario_analise']);  
                                        if(count($get_foto) > 0)
                                        {
                                            $foto_analise = $get_foto[0]['nome'];
                                            $foto_analise = "<a href='usuario_visualiza.php?id_usuario=".$linha['id_usuario_analise']."'><img class='img-circle' src='fotos/$foto_analise' width='40px'></a>";
                                        }
                                        
                                    }
                                    
                                    $data_de_abertura = null;
                                    if($linha['data_abertura'] != null)
                                        $data_de_abertura  =  trata_data ($linha['data_abertura']);
                                    
                                    $para_avaliador = null;
                                    if($linha['para_avaliador'] != null && $linha['para_avaliador'] == '1')
                                        $para_avaliador = 'Sim';
                                    if($linha['para_avaliador'] != null && $linha['para_avaliador'] == '0')
                                        $para_avaliador = 'Não';
                                    
                                    $status = null;
                                    if($linha['status'] != null && $linha['status'] == 'deferido') $status = 'Deferido';
                                    if($linha['status'] != null && $linha['status'] == 'deferido_parcialmente') $status = 'Deferido Parcialmente';
                                    if($linha['status'] != null && $linha['status'] == 'indeferido') $status = 'Indeferido';
                                    
                                    $data_analise = null;
                                    if($linha['data_analise'] != null) $data_analise = trata_data_hora($linha['data_analise']);
                                    
                                    // Arquivo que o candidato adicionou
                                    $arquivo_add_candidato_recurso = null;
                                    if($linha['arq_nome_arquivo'] != null)
                                        $arquivo_add_candidato_recurso = '<a href="baixaPDF.php?codigo=rec_cand_vis&nome_arquivo='.$linha['arq_nome_arquivo'].'" target="_blank">Recurso adicionado pelo candidato -> <img src="imagens/pdf.png" height="70px"></a>';
                                    
                                    
                                    echo '
<div class="alert alert-info">
    <div class="row">
    <div class="col-md-12">
<legend> Recurso Nº '.$linha['id'].' '.$arquivo_add_candidato_recurso.' </legend>
</div>
        <div class="col-md-2">
            <b>Etapa: </b><font color="#000">'.$linha['etapa'].'</font><br>
        </div>
        <div class="col-md-2">
            <b>Data de abertura: </b><font color="#000">'.$data_de_abertura.'</font><br>
        </div>
        <div class="col-md-2">
            <b>Para avaliador analisar? </b><font color="#000">'.$para_avaliador.'</font><br>
        </div>
        <div class="col-md-2">
            <b>Status: </b><font color="#000">'.$status.'</font><br>
        </div>
        <div class="col-md-4">
            <b>Especialidade: </b><font color="#000">'.$linha['nome_especialidade'].'</font><br>
        </div>
        <div class="col-md-12">
        <br>
            <b>Análise: </b><font color="#000">'.$linha['analise'].'</font><br>
                <br>
        </div>
                                            

        <form action="../banco_dados/avaliador_analisa_recurso.php" method="post" >
        <input name="id_recurso" value='.$linha['id'].' hidden>
        <input name="id_candidato" value='.$linha['id_candidato'].' hidden>
        <input name="cpf_candidato" value='.$cpf.' hidden>
        <input name="crip" value='.$crip.' hidden>

<div class="col-md-12" ';

if($status != null && $status != '') echo ' hidden ';
                                    
echo '>
<legend> Geração de Ofício Resposta</legend>


    <div class="col-lg-4">
        <label>Status </label>
        <select name="status" class="form-control">
            <option value="">Selecione a opção</option>
            <option value="deferido">Deferido</option>
            <option value="deferido_parcialmente">Deferido Parcialmente</option>
            <option value="indeferido">Indeferido</option>
        </select>
    </div>
            
    <div class="col-md-8">
        <label>Análise</label>
        <textarea style="width:100%;" rows="4" name="analise">'.$linha['paragrafo2'].'</textarea>
    </div>
            
    <div class="col-md-12">
        <br>
        <center><font color="red"><b>ATENÇÃO:</b> Após enviar a análise, não será possível efetuar alteração!</font></center>
        <button  type="submit"  class="btn btn-primary btn-block">Enviar análise</button>
    </div>
    </form>
    <div class="col-md-12"><br></div>
        </div>
    </div>
</div>
';
                                }

                                ?>

                        </div> 
                            
                    </div>
                </div>
            </div>            
        </div>    
    </div> 
</div>