<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
{
    erro("Erro 544654: Página não encontrada");
    exit();
}

if(isset($_GET['datas_atualizadas']) && $_GET['datas_atualizadas'] == 1)
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " As datas foram atualizadas!"
        },{
                type: "info"
        });
    };
    </script>';
}
if(isset($_GET['sucesso']) && $_GET['sucesso'] == "inscricao")
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " Os comprovantes de Inscrição foram alterados!"
        },{
                type: "info"
        });
    };
    </script>';
}
if(isset($_GET['sucesso']) && $_GET['sucesso'] == "avaliacao_curricular")
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A liberação da avaliação curricular foi atualizada!"
        },{
                type: "info"
        });
    };
    </script>';
}
if(isset($_GET['sucesso']) && $_GET['sucesso'] == "exame_medico")
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O exame médico foi cadastrado!"
        },{
                type: "info"
        });
    };
    </script>';
}
if(isset($_GET['sucesso']) && $_GET['sucesso'] == "selecao_encerrada")
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A status da seleção encerrada foi alterado!"
        },{
                type: "info"
        });
    };
    </script>';
}
if(isset($_GET['sucesso']) && $_GET['sucesso'] == "exame_medico_apagado")
{
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " O exame médico foi cadastrado!"
        },{
                type: "info"
        });
    };
    </script>';
}

$get_selecao = $conexao->get_selecao_id();  
if($get_selecao == null)
{
    erro("Erro 54234544654: Erro fatal");
    exit();
}

$liberado_comprovante = $get_selecao[0]['liberacao_comprovante_inscricao'];
$eliminar_caso_nao_adicione_foto = $get_selecao[0]['eliminar_caso_nao_adicione_foto'];
$eliminar_docs_obrigatorios = $get_selecao[0]['eliminar_docs_obrigatorios'];
$libera_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];
$liberacao_avaliacao_docs_obrigatorios = $get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'];
$data_maxima_nascimento = $get_selecao[0]['data_maxima_nascimento'];
$data_minima_nascimento = $get_selecao[0]['data_minima_nascimento'];

$data_inicio_cidade = $get_selecao[0]['data_inicio_cidade'];
$data_fim_cidade    = $get_selecao[0]['data_fim_cidade'];

$data_inicio_recurso    = $get_selecao[0]['data_inicio_recurso'];
$data_fim_recurso    = $get_selecao[0]['data_fim_recurso'];

$selecao_encerrada = $get_selecao[0]['encerrada'];
$selecao_pagamento = $get_selecao[0]['pagamento'];
$valor_gru = $get_selecao[0]['valor_gru'];
$apelido_ug = $get_selecao[0]['apelido_ug'];

//$verifica_existencia = $conexao->get_recurso_visualiza($id_selecao);

//var_dump($verifica_existencia);//verifica dum do sql para testar o check; 
//exit;
?>

<script>
    function pagamento_para_selecao()
    {
        if($(cobrar_candidato). is(":checked"))
        {
           $(div_valor).show();
           $(div_apelido).show();
        }
        else
        {
           $(div_valor).hide();
           $(div_apelido).hide();
        }
    }
</script>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Configuração da Seleção <i class="fa fa-cogs"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Configuração da Seleção</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
            <legend>Agenda da Inscrição </legend> 
            <form action="../banco_dados/agenda_inscricao_atualiza.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Data de início da inscrição</label> 
                            <input value="<?php if($data_inicio_inscricao != null) echo trata_data ($data_inicio_inscricao) ?>" name="data_inicio_inscricoes" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Último dia para inscrição</label>
                            <input value="<?php if($data_inicio_inscricao != null) echo trata_data ($data_fim_inscricao) ?>" maxlength="20" name="data_fim_inscricoes" class="form-control">
                        </div>
                    </div>
                </div>
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
      
    <div class="col-md-6">
      <div class="card">
            <legend>Agenda da adição de arquivos de isenção </legend> 
            <form action="../banco_dados/agenda_isencao_atualiza.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Data de início da isenção</label> 
                            <input value="<?php if($data_inicio_isencao != null) echo trata_data ($data_inicio_isencao) ?>" name="data_inicio_isencao" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Último dia para isenção</label>
                            <input value="<?php if($data_fim_isencao != null) echo trata_data ($data_fim_isencao) ?>" maxlength="20" name="data_fim_isencao" class="form-control">
                        </div>
                    </div>
                </div>
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
      
    <div class="col-md-6">
      <div class="card">
            <legend>Agenda da avaliação de currículos e docs obrigatórios</legend> 
            <form action="../banco_dados/agenda_avaliacao_atualiza.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Data de início das avaliações</label> 
                            <input value="<?php if($data_inicio_avaliacao != null) echo trata_data ($data_inicio_avaliacao) ?>" name="data_inicio_avaliacao" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Último dia para avaliações</label>
                            <input value="<?php if($data_inicio_avaliacao != null) echo trata_data ($data_fim_avaliacao) ?>" maxlength="20" name="data_fim_avaliacao" class="form-control">
                        </div>
                    </div>
                </div>
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
      
      
    <div class="col-md-6">
      <div class="card">
            <legend>Data de nascimento para inscrição</legend> 
            <form action="../banco_dados/data_maxima_nascimento.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Deve ser menor do que</label> 
                            <input value="<?php if($data_minima_nascimento != null) echo trata_data($data_minima_nascimento) ?>" name="data_minima_nascimento" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Deve ser maior do que (limite de idade)</label> 
                            <input value="<?php if($data_maxima_nascimento != null) echo trata_data($data_maxima_nascimento) ?>" name="data_maxima_nascimento" maxlength="120" class="form-control">
                        </div>
                    </div>
                </div>
                
                    
                
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
      
          
      <div class="col-md-6">
            <div class="card">
                <legend>Cobrar pagamento do candidato</legend> 
                <form action="../banco_dados/cobrar_pagamento_candidato.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            
                            <div class="alert alert-dismissible alert-info">
                                <font color="red"><b>ATENÇÃO:</b></font> Ao selecionar esta opção, o campo para o candidato adicionar o arquivo de pagamento vai aparecer e o candidato não passará para próxima etapa caso ele não tenha efetuado o pagamento ou comprovado e aprovada a isenção.
                            </div> 
                            
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input id='cobrar_candidato' type="checkbox" onclick="pagamento_para_selecao()" name="pagamento" <?php if($selecao_pagamento) echo "checked" ?>>
                                    <span class="label-text">Cobrar pagamento do candidato</span>
                                </label>
                            </div>
                            
                            <div  <?php if(!$selecao_pagamento) echo ' hidden '; ?> id='div_valor'>
                                <div class="form-group">
                                <label>Valor a ser cobrado</label>
                                <input maxlength="10" value="<?php echo 'R$ ' .$valor_gru ?>" name="valor_cobrado" class="form-control">
                                </div>
                            </div>

                            <div  <?php if(!$selecao_pagamento) echo ' hidden '; ?> id='div_apelido'>
                                <div class="form-group">
                                <label>Apelido da UG/Gestão responsável pela arrecadação (5 dígitos). Exemplo da 3ª RM: 02435</label>
                                <input value='<?php if($_SESSION['selecao_regiao'] == '3') echo '02435'; else echo $apelido_ug ?>' maxlength="10" name="apelido" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      <div class="col-md-6">
            <div class="card">
                <legend>Encerrar seleção</legend> 
                <form action="../banco_dados/encerra_selecao.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="alert alert-dismissible alert-info">
                                    <font color="red"><b>ATENÇÃO:</b></font> Quando a seleção estiver encerrada, somente o administrador poderá fazer o login!
                            </div>
                            
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="encerra_selecao" <?php if($selecao_encerrada == 1) echo "checked" ?>>
                                    <span class="label-text">Encerrar seleção</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      <div class="col-md-6">
            <div class="card">
                <legend>Liberar para o candidato a visualização da avaliação curricular</legend> 
                <form action="../banco_dados/liberar_candidato_avaliacao_curricular.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            
                            <div <?php if($libera_avaliacao_curricular == 1) echo "hidden" ?> class="alert alert-dismissible alert-info">
                                    <font color="red"><b>ATENÇÃO:</b></font> Quando liberado o candidato visualizará a avaliação do seu currículo adicionado.
                            </div>  
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="liberacao" <?php if($libera_avaliacao_curricular == 1) echo "checked" ?>>
                                    <span class="label-text">Liberar a visualização da avaliação curricular</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
          <div class="col-md-6">
            <div class="card">
                <legend>Liberar para o candidato a visualização da avaliação dos Documentos Obrigatórios</legend> 
                <form action="../banco_dados/liberar_candidato_avaliacao_docs_obrigatorios.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            
                            <div <?php if($liberacao_avaliacao_docs_obrigatorios == 1) echo "hidden" ?> class="alert alert-dismissible alert-info">
                                    <font color="red"><b>ATENÇÃO:</b></font> Quando liberado o candidato visualizará a avaliação dos Documentos Obrigatórios assim como a justificativa adicionada.
                            </div>  
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="liberacao" <?php if($liberacao_avaliacao_docs_obrigatorios == 1) echo "checked" ?>>
                                    <span class="label-text">Liberar a visualização de avaliação de Docs Obrigatórios</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> 
      
      <div class="col-md-6">
            <div class="card">
                <legend>Eliminar candidato na etapa 1 caso ele não tenha adicionado todos os documentos obrigatórios</legend> 
                <form action="../banco_dados/eliminar_caso_nao_adicione_docs_obrigatorios.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="eliminar" <?php if($eliminar_docs_obrigatorios == 1) echo "checked" ?>>
                                    <span class="label-text">Eliminar candidado caso não adicione os docs obrigatórios</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      <div class="col-md-6">
            <div class="card">
                <legend>Liberar para o candidato a visualização do comprovante de inscrição</legend> 
                <form action="../banco_dados/liberar_comprovante_inscricao.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="liberacao" <?php if($liberado_comprovante == 1) echo "checked" ?>>
                                    <span class="label-text">Liberar a visualização do comprovante de inscrição para o candidato</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      
      
      <div class="col-md-6">
            <div class="card">
                <legend>Eliminar candidato na etapa 1 caso ele não adicione a sua foto</legend> 
                <form action="../banco_dados/eliminar_caso_nao_adicione_foto.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="eliminar" <?php if($eliminar_caso_nao_adicione_foto == 1) echo "checked" ?>>
                                    <span class="label-text">Eliminar candidado caso não adicione foto</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      
      
      <div class="col-md-6">
            <div class="card">
                <legend>Liberar para o candidato selecionar as prioridades da especialidade</legend> 
                <form action="../banco_dados/liberar_prioridade_candidato.php" method="post">

                    <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                    <div class="row">
                        <div  class="col-lg-12">
                            <div class="animated-checkbox form-group">
                                <label>
                                    <input type="checkbox" name="liberacao" <?php if($selecao_libera_prioridade_candidato == 1) echo "checked" ?>>
                                    <span class="label-text">Liberar para o candidato visualizar e cadastrar as prioridades de cada especialidade</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                        <div  class="col-lg-12">
                            <button   type="submit"  class="btn btn-primary btn-block">SALVAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
      
      <div class="col-md-6">
      <div class="card">
            <legend>Liberação para o candidato selecionar a cidade onde quer servir</legend> 
            <form action="../banco_dados/agenda_cidade_candidato.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label>Data de início</label> 
                            <input value="<?php if($data_inicio_cidade != null) echo trata_data ($data_inicio_cidade) ?>" name="data_inicio_cidade" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Data de fim</label>
                            <input value="<?php if($data_fim_cidade != null) echo trata_data ($data_fim_cidade) ?>" maxlength="20" name="data_fim_cidade" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
      
      
      <div class="col-md-6">
      <div class="card">
            <legend>Liberação para o candidato adicionar recursos</legend> 
            <form action="../banco_dados/candidato_adiciona_recurso.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label>Data de início</label> 
                            <input value="<?php if($data_inicio_recurso != null) echo trata_data ($data_inicio_recurso) ?>" name="data_inicio_recurso" maxlength="120" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Data de fim</label>
                            <input value="<?php if($data_fim_recurso != null) echo trata_data($data_fim_recurso) ?>" maxlength="20" name="data_fim_recurso" class="form-control">
                        </div>
                    </div>
                </div>
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">ATUALIZAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--
    <div class="col-md-6">
    <div class="card">
        <legend>Liberação para o candidato VISUALIZAR recursos - Por Região</legend> 
        <form action="../banco_dados/candidato_visualiza_recurso.php" method="post">
            <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
            <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
         
            <div class="row">
                <div class="col-lg-12">
                    <label>Selecione as opções:</label>
                    <div class="form-check">
                <input type="checkbox" class="form-check-input" name="opcoes[]" value="um_regiao" id="um_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['um_regiao'] === '1') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="um_regiao">1ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="dois_regiao" id="dois_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['dois_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="dois_regiao">2ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="tres_regiao" id="tres_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['tres_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="tres_regiao">3ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="quatro_regiao" id="quatro_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['quatro_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="quatro_regiao">4ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="cinco_regiao" id="cinco_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['cinco_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="cinco_regiao">5ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="seis_regiao" id="seis_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['seis_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="seis_regiao">6ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="sete_regiao" id="sete_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['sete_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="sete_regiao">7ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="oito_regiao" id="oito_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['oito_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="oito_regiao">8ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="nove_regiao" id="nove_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['nove_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="nove_regiao">9ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="dez_regiao" id="dez_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['dez_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="dez_regiao">10ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="onze_regiao" id="onze_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['onze_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="onze_regiao">11ª Região Militar</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="opcoes[]" value="doze_regiao" id="doze_regiao" <?php foreach ($verifica_existencia as $verifica) echo ($verifica['doze_regiao'] === '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="doze_regiao">12ª Região Militar</label>
                    </div>

                </div>
            </div> 

            <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-block">ATUALIZAR</button>
                </div>-->
          
        </form>
   
      
     
      <div class="col-md-12">
            <div class="card">
                 <a name="exame_medico"></a>
                <legend>Exame de Médico</legend> 
                <form action="../banco_dados/exame_medico_cadastra.php" method="post">
                
                <input hidden name="crip" value="<?php echo  hash('sha256', $_SESSION['chave']."freitas"); ?>">
                <input hidden name="cod" value="<?php echo $_SESSION['selecao'] ?>">
                
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nº da Sessão</label> 
                            <input value="" name="sessao" maxlength="40" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Dia do Exame</label>
                            <input value="" maxlength="20" name="data" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Cidade</label>
                            <input value="" maxlength="140" name="cidade" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>Presidente</label>
                            <input value="" maxlength="140" name="presidente" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>1º Membro</label>
                            <input value="" maxlength="140" name="membro_1" class="form-control">
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        <div id="div_ramal" class="form-group">
                            <label>2º Membro</label>
                            <input value="" maxlength="140" name="membro_2" class="form-control">
                        </div>
                    </div>
                    
                </div>
                
                <div <?php if($perfil != "admin") echo "hidden" ?> class="row">
                    <div  class="col-lg-12">
                        <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR</button>
                    </div>
                </div>
                
                
                <br>
                <div class="row">
                    <div  class="col-lg-12">
                        <legend>Cadastros dos exames de saúde</legend>
                        <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                              <th>Sessão</th>
                              <th>Dia do Exame</th>
                              <th>Cidade</th>
                              <th>Presidente</th>
                              <th>1º Membro</th>
                              <th>2º Membro</th>
                              <th>Deletar</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $get_exames_saude = $conexao->get_exames_medico();  
                            
                                foreach($get_exames_saude as $linha)
                                {
                                    $dia_exame = "";
                                    if($linha['dia_exame'] != null) $dia_exame = trata_data ($linha['dia_exame']);
                                    
                                    echo '
                                    <tr>
                                    <td>'.$linha['sessao'].'</td>
                                    <td>'.$dia_exame.'</td>
                                    <td>'.$linha['cidade'].'</td>
                                    <td>'.$linha['presidente'].'</td>
                                    <td>'.$linha['membro_1'].'</td>
                                    <td>'.$linha['membro_2'].'</td>
                                    <td width="30px"><a onclick="funcao_apagar(\''.$linha['id'].'\', \'exame_medico\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                    </tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div> 
                </div>
            </form>
       
      
      <div class="col-md-12">
        <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
      </div>
</div>
</div>

</div>
<script type="text/javascript">
     //$('#om').select2();
     //$('#secao').select2();
</script> 

</body>
</html>
<?php $conexao = null; ?>