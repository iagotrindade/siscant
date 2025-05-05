<?php

include_once './menu_candidato.php';
include_once '../banco_dados/conexao.php';

if(!isset($_SESSION['chave']))
{
    erro_mensagem("A sua seção foi finalizada!");
    $conexao = null;
    exit();
}

if(!isset($_SESSION['id_candidato_cadastrado']))
{
    erro_mensagem("A sua seção foi finalizada!");
    $conexao = null;
    exit();
}

$id_candidato = $_SESSION['id_candidato_cadastrado'];
$id_criptografado = $_GET['codigo']; 

if(hash('sha256', $id_candidato) != $id_criptografado)
{
    erro_mensagem("Erro 584544: Seu código é inválido!");
    $conexao = null;
    exit();
}

$conexao = new Conexao();
    
$resultado = $conexao->get_usuario_cpf($_SESSION['cpf_usuario_cadastrado']);
if(count($resultado) != 1)
{
    erro_mensagem("Erro 516412! Não foi possível abrir o comprovante de cadastro");
    exit();
}

$cpf_criptografado = hash('sha256', $resultado[0]['cpf']);

$navegador = getBrowser();
$navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] . "'";
$ip = $_SERVER['REMOTE_ADDR'];
$datetime = date('Y-m-d H:i:s');

$selecao_candidato = $conexao->get_selecao_id();  
$ano_selecao = null;
$rm_selecao = null;
$codigo_selecao = null;
if (count($selecao_candidato) == 1) 
{
    $ano_selecao = $selecao_candidato[0]['ano'];
    $rm_selecao = $selecao_candidato[0]['rm'];
    $codigo_selecao = $selecao_candidato[0]['codigo'];
}

if($ano_selecao == null || $rm_selecao == null || $rm_selecao == null)
{
    erro_mensagem("Erro 321462374327! Não foi possível abrir o comprovante de cadastro");
    exit();
}

?>
          
      <div class="content-wrapper">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <legend><b>SiSCanT</b> <small class="pull-right">...</small> </legend>
                    <p>Sistema de Seleção de Canditados Temporários</p>
                </div>
                <div class="col-md-6">
                    <legend>Data: <?php echo date("d/m/Y"); ?><small class="pull-right"><img src="imagens/3rm.png" width="60px"></small></legend>
                </div>
            </div>
        </div>
          
<div class="row">
    <div class="col-md-12">
      <div class="card">
          <section class="invoice">
              <div class="row">
                <div class="col-xs-12">
                  <legend>Cadastro para acesso a sistema realizado com sucesso <i class="fa fa-child"></i> 
                  </legend> 
                </div>
              </div>
              <div class="row">
                  <div class="col-lg-6">
                      <div class="bs-component">
                        <div class="alert alert-dismissible alert-success">
                              <strong>Usuário para acessar o sistema:</strong> O seu CPF (apenas números)<br>
                              <strong>Senha para acessar o sistema:</strong> <?php echo $_SESSION['senha_cadastrada']; ?><br>
                        </div>
                        <!--  <strong>Download do relatório de cadastro de candidato:</strong>
                          <a target="_blank" href="mpdf/relatorio_candidato.php?codigo=<?php echo $cpf_criptografado; ?>"> Clique aqui <img src="imagens/pdf.jpg" width="50px"></a>
                        -->
                      </div>
                  </div>
                  <div class="col-lg-6">
                      
                    <?php
                        if($_SESSION['cadastro_candidato_email_enviado'])
                            echo '<center>
                                     Foi enviado um e-mail para: <u> '.$resultado[0]['mail'].' </u>
                                     <img src="imagens/mail.png" width="100px"> <br>
                                 </center>';
                        else 
                            echo '<center>
                                     <img src="imagens/urgente.gif" width="100 px">
                                     ATENÇÃO! Por algum motivo não foi enviado para o seu e-mail o lembrete da sua senha temporária!
                                     <br> Por isso anote a senha <b><u>'.$_SESSION['chave'].' </b></u>, acesse o sistema e confirme o seu e-mail.<br>
                                    Caso o seu e-mail esteja correto, não se preocupe! O e-mail que deveria ter sido enviado é APENAS um lembrete da sua senha temporária.
                                 </center>';
                    ?> 
                      
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                      <legend>Ainda não terminou!</legend>
                      <div class="bs-component">
                        <div class="alert alert-dismissible alert-warning">
                            <img src="imagens/urgente.gif" width="40 px"> <strong> Agora você deve acessar o Sistema, <u>cadastrar a(s) especialidade(s)</u> desejada(s) e <u>anexar os documentos</u> exigidos pelo sistema.</strong>
                        </div>
                      </div>
                  </div>
                  
              </div>
          </section>
      </div>
          <div class="row">
              <div class="col-lg-12">
                  
                  <?php
                  
                    $pagina_acesso_sistema = $_SESSION['nome_arquivo'];
                    
                    /*
                    $pagina_acesso_sistema = $rm_selecao."rm_".$codigo_selecao."_".$ano_selecao.".php";
                    if($_SESSION['selecao'] == '5')
                        $pagina_acesso_sistema = "3rm_ott_2020.php";
                    if($_SESSION['selecao'] == '9')
                        $pagina_acesso_sistema = "3rm_ott_2021.php";
                    if($_SESSION['selecao'] == '22')
                        $pagina_acesso_sistema = "3rm_stt_2021.php";
                    if($_SESSION['selecao'] == '23')
                        $pagina_acesso_sistema = "2rm_ott_2021.php";
                    if($_SESSION['selecao'] == '27')
                        $pagina_acesso_sistema = "6rm_ott_2021.php";
                    if($_SESSION['selecao'] == '28')
                        $pagina_acesso_sistema = "2rm_ott__stt_2021.php";
                    if($_SESSION['selecao'] == '30')
                        $pagina_acesso_sistema = "2rm_ott___stt_2021.php";
                    if($_SESSION['selecao'] == '31')
                        $pagina_acesso_sistema = "2rm_ott____stt_2021.php";
                    if($_SESSION['selecao'] == '32')
                        $pagina_acesso_sistema = "3rm_ott__stt_2022.php";
                    if($_SESSION['selecao'] == '33')
                        $pagina_acesso_sistema = "2rm_ott__stt_2022.php";
                    if($_SESSION['selecao'] == '34')
                        $pagina_acesso_sistema = "3rm_mfdv__2022.php";
                     */
                  ?>
                  
                  <a href="../<?php echo $pagina_acesso_sistema ?>" target="_blank"><button  class="btn btn-primary btn-block">Acessar o sistema</button></a>
              </div>
          </div>               
    </div>
</div>
          
         
          
</div>
    <!-- Javascripts-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript">$('body').removeClass("sidebar-mini").addClass("sidebar-collapse");</script>
  </body>
</html>

