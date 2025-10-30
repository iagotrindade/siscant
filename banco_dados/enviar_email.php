<?php
session_start();

if (!$_POST) {
    erro_mensagem("Erro 6876587644!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'ouvidor') {
    erro("Erro 456432414! Você não tem permissão!");
    exit();
}

include_once '../sistema/funcoes.php';
include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$resposta = null;
$id_email = null;

if ($_POST['resposta'] == "") {
    erro("O campo resposta é obrigatório!");
    exit();
}

if ($_POST['assunto'] == "") {
    erro("O campo assunto é obrigatório!");
    exit();
}

if ($_POST['id_email'] == "") {
    erro("Erro: 456465 Algo errado não está certo!");
    exit();
}

if ($_POST['resposta'] != "")
    $resposta =  nl2br(htmlspecialchars(trim($_POST['resposta'])));

if ($_POST['assunto'] != "")
    $assunto =  htmlspecialchars(trim($_POST['assunto']));

if ($_POST['id_email'] != "")
    $id_email = $_POST['id_email'];

$criptografia = $_POST['criptografia'];

if ($criptografia != hash('sha256', $id_email)) {
    erro("Erro: 453465 Algo errado não está certo!");
    exit();
}

if ($resposta == "" || $resposta == null) {
    erro("Erro: 3254! O campo resposta é obrigatório");
    exit();
}

include_once 'conexao.php';

$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

$get_email = $conexao->get_email_id($id_email);

if ($get_email == null) {
    erro("Erro: 455 Algo errado não está certo!");
    exit();
}

$nome_completo_requerente = $get_email['remetente'];
$mensagem_requerente = $get_email['mensagem'];
$data_enviado_requerente = $get_email['data_criacao'];
$mail = $get_email['email_remetente'];

if (!isset($_SESSION['id_usuario'])) {
    erro("Erro 15235! A sua sessão expirou! Faça novamente o registro");
    exit();
}
if (!isset($_SESSION['cpf'])) {
    erro("Erro 15123645! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}
if ($_SESSION['candidato'] == 1) {
    erro("Erro 6554656! A resposta não pode ser enviada!");
    exit();
}

if ($id_email != null && $resposta != null) {
    $lista_emails = $conexao->get_emails_candidato($mail);

    if ($_POST)
        $resultado = $conexao->insere_resposta_email($id_email, $resposta);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if ($resultado) {
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_email, "16105", "suporte", "Update", "Respondeu o email do $nome_completo_requerente", $alteracoes_detalhadas);

        $dados_selecao = $conexao->get_selecao_id();

        #########################################
        #Inicia a classe PHPMailer
        $mail_envia = new PHPMailer();

        $mail_envia->SMTPDebug = 0;

        $mail_envia->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => true,
                'allow_self_signed' => true
            )
        );
        // 06/07/2025 -> Iago Silva Corrigindo o problema onde o E-Mail não estava sendo enviado por conta do SMTPAuth estar como false
        try {
            $mail_envia->isSMTP();
            $mail_envia->Host = $dados_selecao[0]['mail_smtp'] ?? 'smtp.webmail.eb.mil.br'; // Servidor SMTP
            $mail_envia->SMTPAuth = true;
            $mail_envia->Username = $dados_selecao[0]['usuario_email'] ?? 'siscant@3rm.eb.mil.br'; // Seu usuário SMTP
            $mail_envia->Password = $dados_selecao[0]['senha_email'] ?? 'EMHFXQMNPYQMAWYD'; // Sua senha SMTP
            $mail_envia->SMTPSecure = 'tls';  //'tls' Define o tipo de criptografia para TLS
            $mail_envia->Port = $dados_selecao[0]['porta_smtp'] ?? 587; // Porta TCP para TLS

            $mail_envia->From = 'siscant@3rm.eb.mil.br'; //Set who the message is to be sent from
            $mail_envia->FromName = utf8_decode('Não responda - Comando 3ª RM'); //Nome do Remetente

            // Configurações do remetente e destinatário
            $mail_envia->setFrom('siscant@3rm.eb.mil.br', 'Servico Militar');        // Remetente
            //$mail->addAddress('siscant@3rm.eb.mil.br'); // Adiciona um destinatário

            $mail_envia->AddAddress($mail, $nome_completo_requerente); // Vai enviar o e-mail, E-Mail e Nome

            $mail_envia->IsHTML(true); // Define que o e-mail será enviado como HTML
            $mail_envia->CharSet = 'utf-8'; // Charset da mensagem (opcional)

            $mail_envia->Subject = $assunto; // Assunto da mensagem

            // Conteúdo do e-mail
            $mail_envia->Body = utf8_decode('        
<head>
    <meta charset="utf-8">
    <title>Resposta - SiSCanT</title>
    <!--[if mso]>
    <style>
        .container-table {
            width: 600px;
        }
        .header-cell {
            background-color: green !important;
        }
    </style>
    <![endif]-->
</head>
<body style="margin: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; line-height: 1.4;">
    <center>
        <table class="container-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <!-- Header -->
            <tr>
                <td class="header-cell" style="background-color: green; padding: 25px 20px; text-align: center; color: #ffffff;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="text-align: center;">
                                <h1 style="font-size: 22px; margin: 0 0 5px 0; font-weight: bold;">RESPOSTA RECEBIDA</h1>
                                <p style="font-size: 14px; margin: 0; opacity: 0.9;">Sistema de Seleção de Candidatos Temporários</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <!-- Content -->
            <tr>
                <td style="padding: 30px 25px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <!-- Greeting -->
                        <tr>
                            <td style="padding-bottom: 20px;">
                                <p style="font-size: 16px; color: #333333; margin: 0;">
                                    Prezado(a) <strong>' . $nome_completo_requerente . '</strong>,
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Intro -->
                        <tr>
                            <td style="padding-bottom: 25px;">
                                <p style="font-size: 14px; color: #555555; margin: 0;">
                                    Sua dúvida encaminhada via e-mail foi respondida.
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Message Box -->
                        <tr>
                            <td style="padding-bottom: 25px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFFFFF; border: 1px solid #e9ecef; border-radius: 4px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <!-- Título Resposta -->
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 13px; color: green; font-weight: bold;">
                                                            Resposta:
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <!-- Conteúdo da Resposta -->
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 14px; color: #333333; line-height: 1.6; margin: 0; text-align: left;">
                                                            ' . nl2br($resposta) . '
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- Warning -->
                        <tr>
                            <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
                                    <tr>
                                        <td style="padding: 15px; text-align: center;">
                                            <p style="font-size: 13px; color: #856404; margin: 0;">
                                                <strong>IMPORTANTE:</strong> Não responda a este email, pois sua mensagem não será visualizada. 
                                                A comunicação deve ser feita exclusivamente através do SiSCanT.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <!-- Footer -->
            <tr>
                <td style="background-color: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #e9ecef;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <p style="font-size: 13px; color: #6c757d; margin: 0;">
                                    <strong style="color: green;">SiSCanT</strong><br>
                                    Sistema de Seleção de Candidatos Temporários
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
');
            #Envio da Mensagem
            $mail_envia->SMTPDebug = 0;
            $enviado = $mail_envia->send(); // Envia o e-mail

            if ($enviado) {
                $mensagem_formatada = str_replace("'", "`", $mensagem);
                $alteracao = "E-Mail resposta de suporte enviado para: $nome_completo_requerente e E-Mail: " . $mail;

                $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $nome_completo_requerente, $id_suporte, "14116", "mail", "Insert", $alteracao, $mensagem_formatada);
            }

            if (!$enviado) {
                $detalhe_erro = print_r(error_get_last());
                $alteracao = "ERRO! E-Mail de suporte NÃO enviado para: $nome_completo_requerente e E-Mail: " . $mail;
                $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $nome_completo_requerente, $id_suporte, "21102", "mail", "Insert", $alteracao, $detalhe_erro);
            }
        } catch (Exception $e) {
            echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
        }
    }
    foreach ($lista_emails as $email) {
        if ($email['id'] != $id_email && $email['id_usuario_respondeu'] != $_SESSION['id_usuario'] && empty($email['resposta'])) {
            $resultado = $conexao->insere_resposta_email($email['id'], '');
        }
    }

    $conexao = null;
    header("Location: ../sistema/email_visualiza.php?criptografia=$criptografia&id_email=$id_email");
}
