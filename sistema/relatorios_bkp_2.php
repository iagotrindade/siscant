<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 3463745! Página não encontrada!");
        exit();
    }
    
    $selecao_atual = $conexao->get_selecao_id(); 
    
    if(count($selecao_atual) < 1 || ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta'))
    {
        erro("Erro 464578! Página não encontrada!");
        exit();
    }
    
    $etapa_atual = $selecao_atual[0]['etapa'];
    $lista_especialidades = $conexao->get_especialidade(); 

    
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>


<div class="content-wrapper">
  <div class="page-title">
    <div>
        <h1>Relatórios <i class="fa fa-files-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatórios</li>
      </ul>
    </div>
  </div>

    
  <div class="row">
    <div class="col-md-12">
        
    <div class="card">
        <legend>Candidatos na Seleção <img src="imagens/pdf.png" height="30px"></legend>
        <div class="card-body">
            <form action="mpdf/relatorio_personalizado.php" method="post">
            <div class="row">
                
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="tipo_relatorio" class="form-control">
                            <option value="nao_concorrendo_lista"> Eliminados da Etapa I</option>
                            <option value="concorrendo_esp"> Concorrendo na seleção (Por Especialidade)</option>
                            <option value="nao_concorrendo_esp"> NÃO concorrendo  na seleção (Por Especialidade)</option>
                            <option selected value="classificacao"> Classificação dos candidatos</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="mostrar_especialidade" class="form-control">
                            <option selected value="mostrar_especialidade"> MOSTRAR epecialidade mesmo quando não tiver candidatos</option>
                             <option value="nao_mostrar_especialidade"> NÃO MOSTRAR especialidade quando não tiver candidatos</option> 
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="etapa" class="form-control">
                            <option <?php if($etapa_atual == '1') echo " selected " ?>value="1"> ETAPA I</option>
                            <option <?php if($etapa_atual == '2') echo " selected " ?>value="2"> ETAPA II</option>
                            <option <?php if($etapa_atual == '3') echo " selected " ?>value="3"> ETAPA III</option>
                            <option <?php if($etapa_atual == '4') echo " selected " ?>value="4"> ETAPA IV</option>
                            <option <?php if($etapa_atual == '5') echo " selected " ?>value="5"> ETAPA V</option>
                            <option <?php if($etapa_atual == '6') echo " selected " ?>value="6"> ETAPA VI</option>
                            <option <?php if($etapa_atual == '7') echo " selected " ?>value="7"> ETAPA VII</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="orientacao" class="form-control">
                            <option selected value="retrato">Retrato</option>
                            <option value="paisagem">Paisagem</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="tipo_especialdiade" class="form-control">
                            <option selected value="todas">Todas especialidades</option>
                            <option <?php if($_SESSION['selecao_codigo'] == 'ott_stt') echo 'hidden' ?> value="sem_medicos">Relatórios sem os médicos</option>
                            <option <?php if($_SESSION['selecao_codigo'] == 'ott_stt') echo 'hidden' ?> value="somente_medicos">Relatório somente com médicos</option>
                            <option <?php if($_SESSION['selecao_codigo'] == 'mfdv')    echo 'hidden' ?> value="sem_musicos">Relatório sem os Músicos</option>
                            <option <?php if($_SESSION['selecao_codigo'] == 'mfdv')    echo 'hidden' ?> value="somente_musicos">Relatório somente Músicos</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="cabecalho" class="form-control">
                            <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                            <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <input name="titulo_1" value="TÍTULO PRINCIPAL" class="form-control" placeholder="TÍTULO PRINCIPAL">
                    </div>
                </div>
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <input name="titulo_2" value="Título Secundário" class="form-control" placeholder="Título Secundário">
                    </div>
                </div>
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <input name="cidade_dt" value="Cidade - Data" class="form-control" placeholder="Cidade - Data">
                    </div>
                </div>
                
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <textarea name="paragrafo_1" placeholder="1º Parágrafo do relatório" class="form-control">O Comandante da 3a Região Militar divulga a relação dos candidatos voluntários no processo seletivo ...</textarea>
                    </div>
                </div>
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <textarea name="paragrafo_2" placeholder="2º Parágrafo do relatório" class="form-control">Outrossim, todos os candidatos elencados na relação abaixo estão convocados a comparecer ...</textarea>
                    </div>
                </div>
                <div  class="col-lg-4">
                    <div class="form-group"> 
                        <textarea name="paragrafo_3" placeholder="3º Parágrafo do relatório" class="form-control"></textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group"> 
                        <label>Selecione as Especialidades (deixe em branco para todas): </label>
                            <select name="especialidades[]" class="select2 form-control" multiple>
                                <?php
                                $ids_selecionados = isset($id_especialidade) && is_array($id_especialidade) ? $id_especialidade : [];
                                foreach ($lista_especialidades as $value) { ?>
                                    <option value="<?php echo htmlspecialchars($value['id']); ?>" 
                                            <?php echo in_array($value['id'], $ids_selecionados) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($value['nome']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                    </div>
                </div>

                <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Selecione as especialidades",
                        allowClear: true
                    });
                });
                </script>

                
                <div class="col-md-12">
                    <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO</button>         
                </div>
            </div>
            </form>
        </div>
    </div>
        
    <div class="card">
        <legend>Quem realizou o exame de saúde em determinada data <img src="imagens/pdf.png" height="30px"></legend>
        <div class="card-body">
            <form action="mpdf/relatorio_inspecao_saude.php" method="post">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group"> 
                       <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data da Inspeção de Saúde" >
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="orientacao" class="form-control">
                            <option value="retrato">Retrato</option>
                            <option selected value="paisagem">Paisagem</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="cabecalho" class="form-control">
                            <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                            <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-12">
                    <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO</button> 
                </div>
            </div>
            </form>
        </div>
    </div>
        
    <div class="card" <?php if($_SESSION['selecao_codigo'] != 'mfdv')    echo ' hidden ' ?>>
        <legend>Quem realizou o exame de saúde em determinada data --- SOMENTE MÉDICOS OBRIGATÓRIOS <img src="imagens/pdf.png" height="30px"></legend>
        <div class="card-body">
            <form action="mpdf/relatorio_inspecao_saude_medicos_obrigatorios.php" method="post">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group"> 
                       <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data da Inspeção de Saúde" >
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="orientacao" class="form-control">
                            <option value="retrato">Retrato</option>
                            <option selected value="paisagem">Paisagem</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="cabecalho" class="form-control">
                            <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                            <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-12">
                    <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO</button> 
                </div>
            </div>
            </form>
        </div>
    </div>
        
        <!--
    <div class="card">
        <legend>Agenda de comparecimento <img src="imagens/pdf.png" height="30px"></legend>
        <div class="card-body">
            <form action="mpdf/agenda_comparecimento.php" method="post">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group"> 
                       <input name="data_inspecao" maxlength="100" class="form-control" placeholder="Data de Comparecimento" >
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="orientacao" class="form-control">
                            <option value="retrato">Retrato</option>
                            <option selected value="paisagem">Paisagem</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group"> 
                        <select name="cabecalho" class="form-control">
                            <option selected value="sim">COM Cabeçalho Ministério da Defesa</option>
                            <option value="nao">SEM Cabeçalho Ministério da Defesa</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-12">
                    <button  type="submit" class="btn btn-primary btn-block">GERAR RELATÓRIO</button> 
                </div>
            </div>
            </form>
        </div>
    </div>
        -->
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
</body>
</html>
<?php $conexao = null; ?>