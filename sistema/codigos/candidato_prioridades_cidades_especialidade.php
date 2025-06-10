<?php

if($selecao_libera_prioridade_candidato != '1')
{
    erro("Erro 123462343674! Página não encontrada!");
    exit();
}

    $get_prioridade = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);
    
    $quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($id_especialidade);  
    $quantidade_cidades = $quantidade_cidades[0]['quantidade'];
    $lista_cidades = $conexao->get_cidades_especialidade_candidato($id_especialidade, $id_candidato_x_especialidade);  
    
?>
 
<div class="row" >
        
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <legend <?php if(!inscricao() || count($lista_cidades) == 0) echo "hidden" ?>>
                <font color="red" size="4px">
                Para liberar a opção de currículo, selecione <b>todas</b> as guarnições dentro das suas prioridades!
                <br>
                (A priorização das cidades não é garantia de abertura/existência de vagas nas mesmas, porém servirá de base para planejamento de vagas)
                </font> 
            </legend>
            <div class="row">
                
                <!--
                
                <div class="col-lg-12">
                    <div class="alert alert-dismissible alert-warning">
                        <center><b><u><font color ='red'>ATENÇÃO! </font> O Sr(a) estará concorrendo apenas para as cidades selecionadas <font color ='red'>ATENÇÃO! </u></font></b></center>
                        <br>
                        <b> Visualize as cidades disponíveis para Médicos e Dentistas </b> <a target="_blank" href="imagens/localidades_medicos.pdf"> <img src="imagens/pdf.png" width="35px"> </a>
                        <br><br>
                        <b> Visualize as cidades disponíveis para Veterinários </b> <a target="_blank" href="imagens/localidades_veterinarios.pdf"> <img src="imagens/pdf.png" width="35px"> </a>
                        <br><br>
                        <b> Visualize as cidades disponíveis para Farmacêuticos</b> <a target="_blank" href="imagens/localidades_farmaceuticos.pdf"> <img src="imagens/pdf.png" width="35px"> </a>
                        
                    </div>
                </div>
                
                -->
                
               
                
                <form method="post" action="../banco_dados/candidato_cadastra_prioridade.php" >
                    <div class="col-md-6" <?php if(!inscricao() || count($lista_cidades) == 0) echo "hidden" ?>>
                        <input hidden name="esp" value="<?php echo $id_especialidade ?>">
                        <div class="form-group"> 
                            <label>Selecione a CIDADE</label>
                            <select name="id_cidade" class="form-control">
                                <option value="">Selecione a cidade</option>
                                <?php
                                    foreach ($lista_cidades as $linha) 
                                    {
                                        echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                    }
                                ?> 
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label>PRIORIDADE</label>
                            <select name="prioridade" class="form-control">
                                <?php
                                    
                                    

                                    $valor = count($get_prioridade) +1;
                                    if(count($get_prioridade) < $quantidade_cidades)
                                    {
                                        echo '<option value="'.$valor.'">Prioridade Nº '.$valor.' </option>';
                                    }
                                    /*
                                    for($i = 1; $i <= $quantidade_cidades; $i++)
                                    {
                                        $imprime = 1;
                                        foreach ($get_prioridade as $linha2) 
                                        {
                                            if($linha2['prioridade'] == $i)
                                                $imprime = 0;
                                        }
                                        if($imprime == 1)
                                            echo '<option value="'.$i.'">Prioridade Nº '.$i.' </option>';
                                    }
                                     */
                                ?> 
                            </select>
                        </div>


                        <input hidden type="text" name="crip" value="<?php echo hash('sha256', $_SESSION['cpf']."freitas") ?>">

                        <div class="form-group"> 
                            <input type="submit" class="btn btn-primary btn-block" value="Cadastrar Prioridade" />
                        </div>
                    </div>
                </form>
                
                
                    
                <div class="col-md-6" <?php if(count($get_prioridade) == 0) echo " hidden " ?>>
                        <div class="form-group"> 
                            <table class="table table-hover table-bordered">
                                <thead>
                                  <tr>
                                        <th>Prioridade</th>
                                        <th>Cidade</th>
                                    <!-- <th>Apagar</th> -->
                                  </tr>
                                </thead>
                                    <tbody>

                                    <?php
                                        foreach ($get_prioridade as $linha3) 
                                        {
                                            
                                            echo '
                                            <tr>
                                                <td>'.$linha3['prioridade'].'ª</td>                                                
                                                <td>'.$linha3['nome'].'</td>';
                                                
                                                //<td><a onclick="funcao_apagar(\''.$linha3['id_prioridade_cidade'].'\', \'prioridade_cidade_candidato\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                            echo'</tr>';
                                        }
                                    ?>   
                                        <tr <?php if(!inscricao()) echo "hidden" ?>>
                                            <td colspan="2">
                                                <form method="post" action="../banco_dados/candidato_cadastra_prioridade.php" >
                                                    <?php $crip = hash('sha256', $_SESSION['chave']."freitas".$id_especialidade); ?>
                                                    <center><?php echo '<a onclick="funcao_apagar(\''.$id_especialidade.'\', \'prioridade_cidade_candidato\',\''.$crip.'\')"><input class="btn btn-info" value="Limpar Prioridades!" /></a>' ?></center>
                                                </form>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                
                
                <!--
                <div class="col-md-6">
                    <div class="alert alert-dismissible alert-success">
                        <b><u>MARINHA DO BRASIL, CIDADES DISPONÍVEIS:</u></b>
                        <br>
                        <br>
                        PORTO ALEGRE
                        <br>
                        RIO GRANDE
                        <br>
                        URUGUAIANA
                        
                        <br>
                        <br>
                        
                        <b><u>FORÇA AÉREA BRASILEIRA, CIDADES DISPONÍVEIS:</u></b>
                        <br><br>
                        CANGUÇU
                        <br>
                        CANOAS
                        <br>
                        SANTA MARIA
                        <br>
                        SANTIAGO
                        <br>
                        URUGUAIANA
                        
                        <br>
                        <br>
                        
                        <b><u>EXÉRCITO BRASILEIRO, CIDADES DISPONÍVEIS:</u></b>
                        
                        <br><br>
                        ALEGRETE
                        <br>
                        BAGÉ
                        <br>
                        BENTO GONÇALVES
                        <br>
                        BUTIÁ
                        <br>
                        CACHOEIRA DO SUL<br>
                        CAXIAS DO SUL<br>
                        CRUZ ALTA<br>
                        DOM PEDRITO<br>
                        GENERAL CÂMARA<br>
                        IJUÍ<br>
                        ITAARA<br>
                        ITAQUI<br>
                        JAGUARÃO<br>
                        NOVA SANTA RITA<br>
                        PELOTAS<br>
                        PORTO ALEGRE<br>
                        QUARAÍ<br>
                        RIO GRANDE<br>
                        ROSÁRIO DO SUL<br>
                        SANTA CRUZ DO SUL<br>
                        SANTA MARIA<br>
                        SANTA ROSA<br>
                        SANTANA DO LIVRAMENTO<br>
                        SANTIAGO<br>
                        SANTO ÂNGELO<br>
                        SÃO BORJA<br>
                        SÃO GABRIEL<br>
                        SÃO LEOPOLDO<br>
                        SÃO LUIZ GONZAGA<br>
                        SAPUCAIA DO SUL<br>
                        URUGUAIANA<br>
                        
                    </div>
                </div>
                -->
                
                
            </div>
        </div>
    </div>
</div>
