<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$id_especialidade = $_GET['esp'];
$id_usuario = $_SESSION['id_usuario'];
    
$resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario,$id_especialidade);

if(count($resultado_verificacao) == 0)
{
    erro("Erro 5442654: Página não encontrada!");
    exit();
}
else
{
    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
    $cpf_candidato      = $resultado_verificacao[0]['cpf'];
    $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
    $concorrendo_esp    = $resultado_verificacao[0]['concorrendo'];
    $justificativa_con  = $resultado_verificacao[0]['justificativa'];
}

$get_prioridade = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_curricular = 0;
if(count($get_selecao) > 0)
    $liberacao_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];
?>
<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Especialidade <?php echo strtoupper($ott_stt) . " - " . $nome_especialidade ?> <i class="fa fa-files-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Detalhamento da especialidade</li>
      </ul>
    </div>
  </div>
    
<?php

    if($selecao_libera_prioridade_candidato == '1')
        include_once './codigos/candidato_prioridades_cidades_especialidade.php';

?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card" <?php if($concorrendo_esp != 0) echo "hidden"  ?>>
                <legend><font color='red'><?php echo " DESCLASSIFICADO! Justificativa: " . $justificativa_con; ?> </font> </legend>
            </div>
        </div>
    </div> 
<div class="row">
    <div class="col-md-12">
        <div class="card" 
            <?php 
            
                if($_SESSION['selecao_regiao'] == 3)
                {
                    if($concorrendo_esp == 0 || !inscricao() || (count($lista_cidades) != 0 && $selecao_libera_prioridade_candidato == '1')) 
                        echo "hidden";
                }
                else
                {
                    if($concorrendo_esp == 0 || !inscricao() || (count($lista_cidades) == $quantidade_cidades && $selecao_libera_prioridade_candidato == '1')) 
                        echo "hidden";
                }
                
            ?>
        >
                
            <legend>Adicione arquivos de currículo para a especialidade <u><?php echo $nome_especialidade ?> </u></legend>

            <div class="row">
                  <div class="col-lg-12">
                      <form method="post" action="arquivo_upload_curriculo.php" enctype="multipart/form-data">
                        <input hidden name="user" value="<?php echo $id_especialidade ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <label>Arquivos de Currículos <font color="red"> *Máximo 5 MegaBytes no formato PDF</font></label>
                                    <p><font color="red"><b>Atenção!</b></font> Os arquivos de diplomas devem conter frente e verso!</p>
                                    
                                    
                                    <select name="id_curriculo" class="form-control">
                                        <option value="">Selecione o arquivo a ser adicionado</option>
                                        <?php
                                            
                                            //$lista_curriculos = $conexao->get_curriculo_sobrando_candidato($id_usuario,$id_especialidade);  
                                            $lista_curriculos = $conexao->get_curriculo_cadastrados();  

                                            foreach ($lista_curriculos as $linha) 
                                            {
                                                echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">  
                                <div class="form-group">
                                    <label>Data de Início</label>
                                    <input id="nome" name="data_inicio" maxlength="120" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">  
                                <div class="form-group">
                                    <label>Data de Finalização</label>
                                    <input id="nome" name="data_fim" maxlength="120" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">  
                                <div class="form-group">
                                    <label>Resumo do PDF inserido (Max 200 caracteres)</label>
                                    <input id="carga_horaria" name="carga_horaria" maxlength="120" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-6">  
                                <div class="form-group"> 
                                    <br> <input type="file" name="arquivo" />
                                </div>
                            </div>
                            
                            <input hidden type="text" name="crip" value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>">
                            
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <input type="submit" class="btn btn-primary btn-block" value="Enviar Arquivo" />
                                </div>
                            </div>
                        </div>
                    </form>
                  </div>
            </div>
        </div>
        
        <?php
            $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_usuario,$id_especialidade);  
            $mostra_pontuacao = false;
            foreach ($lista_docs_obrigatorios as $linha) 
            {
                if($linha['valido'] === '1') $mostra_pontuacao = true;
            }
        
        ?>
        
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <legend><font color="red">Atenção!</font> Lembre-se do Diploma (pré-requisito) e do Registro do Conselho Profissional se for o caso </legend>
            <div class="card-body">
                <table class="table table-hover table-bordered" id="tabela_dinamica">
                    <thead>
                      <tr>
                        <th>Nome do arquivo</th>
                        <th>Data de Início</th>
                        <th>Data de Fim</th>
                        <th>Resumo do PDF</th>
                        <?php 
                            if($liberacao_avaliacao_curricular == 1)
                            {
                                echo "<th>Avaliação</th>";
                                echo "<th>Justificativa da invalidez</th>";
                                if(isset($_SESSION['12_regiao']) && $mostra_pontuacao) echo "<th>Pontuação</th>";
                            }
                            else if(inscricao()) echo '<th width="20px">Apagar</th>';
                        ?>
                        
                      </tr>
                    </thead>
                        <tbody>

                        <?php
                            
                            $pontuacao_total = 0;
                            foreach ($lista_docs_obrigatorios as $linha) 
                            {
                                $valido = $linha['valido'];
                                if($valido === '0')
                                    $valido = "<img  src='imagens/no_like.jpg' width='40px'>";
                                if($valido == 1)
                                    $valido = "<img  src='imagens/like.jpg' width='40px'>";
                                
                                //////////////////
                                //Pontuação
                                
                                $pontuacao = $linha['pontuacao'];
                                $multiplicacao = $linha['multiplicador'];
                                
                                if($pontuacao == "" || $pontuacao == null) $pontuacao = 0;
                                $pontuacao = ($pontuacao/1000) * $multiplicacao ;
                                if($linha['valido'] === '0') $pontuacao = 0;
                                
                                $pontuacao_total = $pontuacao_total + $pontuacao;
                                
                                //////////////////
                                //END Pontuação
                                
                                $crip = hash('sha256', $_SESSION['chave']."freitas".$linha['id_especialidade_curriculo']);
                                
                                $dt_inicio = null;
                                if($linha['data_inicio'] != null)
                                    $dt_inicio = trata_data ($linha['data_inicio']);
                                
                                $dt_fim = null;
                                if($linha['data_termino'] != null)
                                    $dt_fim = trata_data ($linha['data_termino']);
                                
                                echo '
                                <tr>
                                    <td><a href="baixaPDF.php?codigo=cand_esp_cad_vis&nome_arquivo='.$linha['nome'].'" target="_blank">'.$linha['label'].'</a></td>
                                    <td>'.$dt_inicio.'</td>
                                    <td>'.$dt_fim.'</td>
                                    <td>'.$linha['carga_horaria'].'</td>
                                    ';
                                
                                if($liberacao_avaliacao_curricular == 1)
                                {
                                    echo '<td>'.$valido.' </td>';
                                    echo '<td>'.$linha['justificativa'].'</td>';
                                    if(isset($_SESSION['12_regiao']) && $mostra_pontuacao) echo '<td>'.$pontuacao.'</td>';
                                }
                                else if(inscricao())
                                    echo'<td><a onclick="funcao_apagar(\''.$linha['id_especialidade_curriculo'].'\', \'candidato_curriculo\',\''.$crip.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>';
                                
                                echo ' </tr>';
                            }
                        ?>   

                    </tbody>
                </table>
                <?php if(isset($_SESSION['12_regiao']) && $mostra_pontuacao) echo "<b><font size = '5px'>Total de pontos avaliados: " . $pontuacao_total . " </font></b>"; ?>
            </div>
        </div>
        <a name="fim_pagina"></a>
        <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
        
    </div>
  </div>
    
    
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
</body>
</html>