<?php
session_start();

$raiz = getcwd() . "/";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php?erro=123");
    exit();
}
if (!isset($_SESSION['assinatura_sistema'])) {
    header("Location: ../index.php?erro=456");
    exit();
}
if (!isset($_SESSION['chave'])) {
    header("Location: ../index.php?erro=789");
    exit();
}

/*
    define ("dir_banco",  "/var/www/html/siscant/banco_dados/conexao.php");
    define ("dir_funcoes","/var/www/html/siscant/sistema/funcoes.php");
    define ("dir_sistema","/var/www/html/siscant/sistema/");
    */

include_once "../banco_dados/conexao.php";
include_once "funcoes.php";

$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);
if ($usuario_logado == null) {
    header("Location: ../index.php?erro=4162345");
    exit();
}

$selecao_usuario_logado = $conexao->get_selecao_id();
if ($selecao_usuario_logado == null) {
    header("Location: ../index.php?erro=4163345");
    exit();
}

$pagamento_selecao = false;
if ($_SESSION['selecao_pagamento'])
    $pagamento_selecao = true;

$libera_suporte_inicial = $selecao_usuario_logado[0]['liberacao_suporte_inicial'];
$aviso_convocacao = $selecao_usuario_logado[0]['aviso_convocacao'];

$data_inicio_inscricao = $selecao_usuario_logado[0]['data_inicio_inscricao'];
$data_fim_inscricao = $selecao_usuario_logado[0]['data_fim_inscricao'];

$data_inicio_avaliacao = $selecao_usuario_logado[0]['data_inicio_avaliacao'];
$data_fim_avaliacao = $selecao_usuario_logado[0]['data_fim_avaliacao'];

$data_inicio_isencao = $selecao_usuario_logado[0]['data_inicio_isencao'];
$data_fim_isencao = $selecao_usuario_logado[0]['data_fim_isencao'];

$data_inicio_cidade = $selecao_usuario_logado[0]['data_inicio_cidade'];
$data_fim_cidade = $selecao_usuario_logado[0]['data_fim_cidade'];

$etapa_atual_selecao = $selecao_usuario_logado[0]['etapa'];
$rm_atual_selecao = $selecao_usuario_logado[0]['rm'];
$codigo_atual_selecao = $selecao_usuario_logado[0]['codigo'];
$ano_atual_selecao = $selecao_usuario_logado[0]['ano'];
$selecao_libera_prioridade_candidato = $selecao_usuario_logado[0]['liberacao_prioridade_candidato'];

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    header("Location: ../index.php?erro=987");
    exit();
}
if ($usuario_logado[0]['id_selecao'] != $_SESSION['selecao']) {
    header("Location: ../index.php?erro=654");
    exit();
}


$id_selecao = $usuario_logado[0]['id_selecao'];
$cpf = $usuario_logado[0]['cpf'];
$perfil = $usuario_logado[0]['perfil'];
$super_admin = $usuario_logado[0]['super_admin'];
$candidato = $usuario_logado[0]['candidato'];
$nome_completo = $usuario_logado[0]['nome_completo'];
$trocar_senha = $usuario_logado[0]['trocar_senha'];
$concorrendo = $usuario_logado[0]['concorrendo'];
$justificativa_concorrendo_processo = $usuario_logado[0]['justificativa_concorrendo'];
$senha = $usuario_logado[0]['senha'];
$desistencia = $usuario_logado[0]['desistencia'];
$estado_civil = $usuario_logado[0]['estado_civil'];
$companheiro = $usuario_logado[0]['companheiro'];
$sexo = $usuario_logado[0]['sexo'];
$nome_social = $usuario_logado[0]['nome_social'];
$pai = $usuario_logado[0]['pai'];
$mae = $usuario_logado[0]['mae'];
$identidade = $usuario_logado[0]['identidade'];
$nacionalidade = $usuario_logado[0]['nacionalidade'];
$naturalidade = $usuario_logado[0]['naturalidade'];
$dependente = $usuario_logado[0]['dependente'];
$data_nascimento = $usuario_logado[0]['data_nascimento'];
$uf = $usuario_logado[0]['uf'];
$cep = $usuario_logado[0]['cep'];
$cidade = $usuario_logado[0]['nome_cidade'];
$id_cidade = $usuario_logado[0]['id_cidade'];
$rua_num_complemento = $usuario_logado[0]['rua_num_complemento'];
$bairro = $usuario_logado[0]['bairro'];
$tel_residencial = $usuario_logado[0]['tel_residencial'];
$tel_celular = $usuario_logado[0]['tel_celular'];
$mail = $usuario_logado[0]['mail'];
$tempo_sv_pub = $usuario_logado[0]['tempo_sv_pub'];
$tempo_sv_pub_anos = $usuario_logado[0]['tempo_sv_pub_anos'];
$tempo_sv_pub_meses = $usuario_logado[0]['tempo_sv_pub_meses'];
$tempo_sv_pub_dias = $usuario_logado[0]['tempo_sv_pub_dias'];
$tempo_sv_mil = $usuario_logado[0]['tempo_sv_mil'];
$tempo_sv_mil_anos = $usuario_logado[0]['tempo_sv_mil_anos'];
$tempo_sv_mil_meses = $usuario_logado[0]['tempo_sv_mil_meses'];
$tempo_sv_mil_dias = $usuario_logado[0]['tempo_sv_mil_dias'];
$certificado = $usuario_logado[0]['certificado'];
$num_ducumento = $usuario_logado[0]['num_ducumento'];
$data_expedicao = $usuario_logado[0]['data_expedicao'];
$civil_militar = $usuario_logado[0]['civil_militar'];
$ativa_reserva = $usuario_logado[0]['ativa_reserva'];
$forca = $usuario_logado[0]['forca'];
$ano_incorporacao = $usuario_logado[0]['ano_incorporacao'];
$posto_grad = $usuario_logado[0]['posto_grad'];
$arma_quadro_servico = $usuario_logado[0]['arma_quadro_servico'];
$licenciamento = $usuario_logado[0]['licenciamento'];
$assinatura_sistema = $usuario_logado[0]['assinatura_sistema'];
$apagado = $usuario_logado[0]['apagado'];
$nome_selecao =  $usuario_logado[0]['nome_selecao'] . " / " . $usuario_logado[0]['ano_selecao'];
$codigo_selecao = $usuario_logado[0]['codigo_selecao'];
$rm_usuario = $usuario_logado[0]['rm_selecao'];
$nome_guerra = $usuario_logado[0]['nome_guerra'];
$etapa = $usuario_logado[0]['etapa'];
$instituto_ensino = $usuario_logado[0]['instituto_ensino'];
$ano_formacao = $usuario_logado[0]['ano_formacao'];
$uf_instituto_ensino = $usuario_logado[0]['uf_instituto_ensino'];
$id_cidade_instituto_ensino = $usuario_logado[0]['id_cidade_instituto_ensino'];
$cidade_etapas_presenciais = $usuario_logado[0]['cidade_etapas_presenciais'];

$voluntario_12rm = $usuario_logado[0]['voluntario_12rm'];
$prioridade_forca = $usuario_logado[0]['prioridade_forca'];

$nota_ofor = $usuario_logado[0]['nota_ofor'];
//var_dump($nota_ofor); exit;

$ano_formacao_ofor = $usuario_logado[0]['ano_formacao_ofor'];
$arma_eipot = $usuario_logado[0]['arma_eipot'];
$curso_graduacao = $usuario_logado[0]['curso_graduacao'];
$rm_inscricao = $usuario_logado[0]['rm_inscricao'];
$rm_destino = $usuario_logado[0]['rm_destino'];
$autodeclaracao = $usuario_logado[0]['autodeclaracao'];
$vaga_reservada = $usuario_logado[0]['vaga_reservada'];

/*
$data_inicio_recurso = $usuario_logado[0]['data_inicio_recurso'];
$data_fim_recurso = $usuario_logado[0]['data_fim_recurso'];
*/

if ($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if ($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);

$usuario_foto = $_SESSION['usuario_foto'];


///////////////////////////////////
// REGISTRA ACESSO

$pagina_acessada = $_SERVER['PHP_SELF'];
$endereco_completo = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$tagsArray = explode('/', $pagina_acessada);
$termo = 'usuario_visualiza.php';

if (in_array($termo, $tagsArray)) {
    $id_usuario = null;
    if (isset($_GET['id_usuario']))
        $id_usuario = $_GET['id_usuario'];

    $insere_log = $conexao->insere_acesso_pagina($_SESSION['id_usuario'], $_SESSION['cpf'], $id_usuario, "19101", $pagina_acessada, $endereco_completo);
} else {
    $insere_log = $conexao->insere_acesso_pagina($_SESSION['id_usuario'], $_SESSION['cpf'], null, "19100", $pagina_acessada, $endereco_completo);
}

// 27/08/2025 -> Iago Silva Adicionado contagem de notificações recentes
$todasNotificacoes = $conexao->get_notificacoes($_SESSION['selecao']);

$notificacoes = [];

foreach ($todasNotificacoes as $notificacao) {
    if (!$notificacao['id_especialidade']) {
        $notificacoes[] = $notificacao;
    }

    if ($perfil == 'candidato') {
        $lista_inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);

        foreach ($lista_inscricoes as $especialidade) {
            if ($notificacao['id_especialidade'] == $especialidade['id_especialidade']) {
                $notificacoes[] = $notificacao;
            }
        }
    }
}

$novas_notificacoes = false;
$novas_notificacoes_count = 0;

foreach ($notificacoes as $notificacao) {
    if (strtotime($notificacao['data_envio']) >= strtotime('-5 days')) {
        $novas_notificacoes = true;
        $novas_notificacoes_count++;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <!-- JQUERY -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="js/main.js"></script>

    <!-- AUTOCOMPLETA -->
    <script src="js/jquery-ui.min.js"></script>
    <link href="js/jquery-ui.min.css" rel="stylesheet">

    <!-- MÁSCARA -->
    <script src="js/jquery.maskedinput.js"></script>
    <script src="js/maskMoney.js"></script>

    <!-- AJAX -->
    <script src="ajax/ajax.js"></script>
    <script src="ajax/funcoes.js"></script>

    <!-- ALERTA -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap-notify.min.js"></script>

    <!-- COMBO DINAMICO -->
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>

    <!-- GRÁFICOS -->
    <script src="js/chartjs.js"></script>

    <script>
        $(document).ready(function() {
            $("input[name*='data']").mask("99/99/9999");
            $("input[name*='cpf']").mask("999.999.999-99");
            $("input[name*='pontuacao']").mask("99.99");

            $("input[name*='ano_formacao_ofor']").mask("9999");
            $("input[name*='nota_ofor']").mask("99.99");

            $("input[name*='valor']").maskMoney({
                showSymbol: true,
                symbol: "R$ ",
                decimal: ",",
                thousands: "."
            });

            $("#cnpj").mask("99.999.999/9999-99"); // Pega pelo ID
            //$("#cpf").mask("999.999.999-99"); // Pega pelo ID
        });
    </script>


    <title>SiSCanT</title>
</head>

<body class="sidebar-mini fixed">
    <a name="topo"></a>
    <div class="wrapper">
        <!-- Navbar-->
        <header class="main-header hidden-print">
            <a class="logo" href="index.php"> <i class="fa fa-home"></i> <b>SiSCanT </b></a>
            <nav class="navbar navbar-static-top">
                <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
                <div class="navbar-custom-menu">
                    <ul class="top-nav">
                        <!-- 27/08/2025 -> Iago Silva Adicionando botão para notificações -->
                        <li>
                            <a href="notificacoes.php" title="Notificações">
                                <i class="fa fa-2x fa-bell"></i>
                                <?php if ($novas_notificacoes) echo "<span class='badge badge-success' style='margin-left: -10px; background-color:red;'>" . $novas_notificacoes_count . "</span>"; ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-toggle" href="../<?= $_SESSION['nome_arquivo'] ?>" title="Sair">
                                <i class="fa fa-2x fa-sign-out"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Side-Nav-->
        <aside class="main-sidebar hidden-print">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left "><img height="50px" style="box-shadow: 0px 0px 10px #006400" class="img-circle" src="<?php echo "fotos/$usuario_foto" ?>" alt="User Image"></div>
                    <div class="pull-left info">
                        <p class="designation">
                            <?php
                            if ($candidato == 1)
                                echo "Candidato";
                            else
                                echo $posto_grad;
                            ?>
                        </p>
                        <p>
                            <b>
                                <?php
                                if ($candidato == 1) {
                                    $primeiro_nome = explode(" ", $_SESSION['nome_completo']);
                                    echo $primeiro_nome[0];
                                } else
                                    echo $nome_guerra;

                                ?></b>
                        </p>

                    </div>
                </div>
                <!-- Sidebar Menu-->
                <ul class="sidebar-menu">
                    <?php if ($perfil == "admin" || $perfil == "consulta") : ?>
                        <li>
                            <form action="pesquisa_cpf.php" method="POST">
                                <div class="input-group" style="margin-left: 14px;margin-right: 8px;">
                                    <input type="text" name="pesquisa" class="form-control" maxlength="25" placeholder="Parte do CPF/Nome" style="height: 40px;;">
                                    <input type="text" name="criptografia" hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "pesquisa")  ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-pesquisa">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </li>
                    <?php endif; ?>

                    <li class="treeview"><a href="index.php"><i class="fa fa-home"></i><span>Página Inicial</span><i class=""></i></a></li>
                    <li class="treeview"><a href="foto_upload.php"><i class="fa fa-picture-o"></i><span>Minha Foto</span><i class=""></i></a></li>


                    <?php

                    if ($perfil == "candidato" || $candidato == 1)
                        include_once 'codigos/menu_candidato.php';
                    else
                        include_once 'codigos/menu_usuario.php';

                    ?>

                </ul>
            </section>
        </aside>