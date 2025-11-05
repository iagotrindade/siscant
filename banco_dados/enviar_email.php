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

if ($_POST['destinatarios'] == "") {
    erro("Selecione pelo menos 1 destinatário!");
    exit();
}

if ($_POST['mensagem'] == "") {
    erro("O campo mensagem é obrigatório!");
    exit();
}

if ($_POST['assunto'] == "") {
    erro("O campo assunto é obrigatório!");
    exit();
}

if ($_POST['mensagem'] != "")
    $mensagem =  nl2br(htmlspecialchars(trim($_POST['mensagem'])));

if ($_POST['assunto'] != "")
    $assunto =  htmlspecialchars(trim($_POST['assunto']));

if (!isset($_SESSION['id_usuario'])) {
    erro("Erro 15235! A sua sessão expirou! Faça novamente o registro");
    exit();
}
if (!isset($_SESSION['cpf'])) {
    erro("Erro 15123645! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}
if ($_SESSION['candidato'] == 1) {
    erro("Erro 6554656! O email não pode ser enviado!");
    exit();
}

include_once 'conexao.php';

$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

$destinatarios = $_POST['destinatarios'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

foreach ($destinatarios as $destinatario) {
    $usuario = $conexao->get_usuario_email($destinatario);

    if ($usuario != null && $usuario != null) {
        $origem = "webmail";

        $lista_emails = $conexao->get_emails_candidato($usuario['mail']);

        if ($_POST)
            $resultado = $conexao->insere_email_enviado(
                $id_selecao,
                $usuario['nome_completo'],
                $usuario['mail'],
                $assunto,
                $mensagem,
                $datetime,
                $origem,
                $_SESSION['id_usuario']
            );

        $alteracoes_detalhadas =  print_r($resultado, true);

        if ($resultado) {
            // PROCESSAMENTO DOS ANEXOS DA RESPOSTA
            $anexos_processados = [];

            if (isset($_FILES['anexos']['name']) && is_array($_FILES['anexos']['name'])) {
                $uploadDir = '../sistema/arquivos/arquivos_email/';

                // Verifica se o diretório existe, se não, cria
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['anexos']['name'] as $key => $name) {
                    $error = $_FILES['anexos']['error'][$key];
                    $tmp_name = $_FILES['anexos']['tmp_name'][$key];
                    $file_size = $_FILES['anexos']['size'][$key];

                    if (is_uploaded_file($tmp_name)) {

                        // Valida tamanho máximo (10 MB)
                        $max_size = 10 * 1024 * 1024;
                        if ($file_size > $max_size) {
                            error_log("Arquivo muito grande: $name");
                            continue;
                        }

                        // Gera nome seguro e único
                        $file_extension = end(explode('.', $_FILES['anexos']['name'][$key]));
                        $file_name = uniqid() . '_' . date('Ymd_His') . '.' . $file_extension;
                        $file_path = $uploadDir .  $file_name;

                        // Move arquivo para o diretório final
                        if (move_uploaded_file($tmp_name, $file_path)) {
                            // Insere no banco de dados
                            $insere_anexo = $conexao->insere_email_arquivo($id_email, $file_name, 'resposta');

                            if ($insere_anexo) {
                                $anexos_processados[] = $file_name;
                            } else {
                                error_log("Erro ao inserir anexo no banco: $file_name");
                            }
                        } else {
                            error_log("Falha ao mover arquivo: $name");
                        }
                    } else {
                        error_log("Erro no upload de $name - Código: $error");
                    }
                }
            }

            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_email, "16105", "suporte", "Update", "Enviou email para $remetente", $alteracoes_detalhadas);

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

            // ADICIONAR ANEXOS AO E-MAIL
            if (!empty($anexos_processados)) {
                foreach ($anexos_processados as $anexo_nome) {
                    $anexo_path = $uploadDir . $anexo_nome;
                    if (file_exists($anexo_path)) {
                        $mail_envia->addAttachment($anexo_path, $anexo_nome);
                    }
                }
            }

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

                // ADICIONA INFORMAÇÃO SOBRE ANEXOS NO CORPO DO E-MAIL
                $info_anexos = '';
                if (!empty($anexos_processados)) {
                    $info_anexos = '
                <tr>
                    <td style="padding-bottom: 15px;">
                        <p style="font-size: 14px; color: #555555; margin: 0 0 10px 0;">
                            <strong>Anexos incluídos no email:</strong>
                        </p>
                        <ul style="font-size: 14px; color: #555555; margin: 0; padding-left: 20px;">';

                    foreach ($anexos_processados as $anexo) {
                        $info_anexos .= '<li>' . htmlspecialchars($anexo) . '</li>';
                    }

                    $info_anexos .= '
                        </ul>
                    </td>
                </tr>';
                }

                // Conteúdo do e-mail
                $mail_envia->Body = utf8_decode('        
<head>
    <meta charset="utf-8">
    <title>Email - SiSCanT</title>
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
                                <h1 style="font-size: 22px; margin: 0 0 5px 0; font-weight: bold;">EMAIL RECEBIDO</h1>
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
                                    Prezado(a) <strong>' . $usuario['nome_completo'] . '</strong>,
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Intro -->
                        <tr>
                            <td style="padding-bottom: 25px;">
                                <p style="font-size: 14px; color: #555555; margin: 0;">
                                    Você recebeu um e-mail da Comissão de Seleção Especial
                                </p>
                            </td>
                        </tr>
                        
                        ' . $info_anexos . '
                        
                        <!-- Message Box -->
                        <tr>
                            <td style="padding-bottom: 25px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFFFFF; border: 1px solid #e9ecef; border-radius: 4px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <!-- Título Mensagem -->
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 13px; color: green; font-weight: bold;">
                                                            Mensagem:
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <!-- Conteúdo da Resposta -->
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 14px; color: #333333; line-height: 1.6; margin: 0; text-align: left;">
                                                            ' . nl2br($mensagem) . '
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
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
                    $alteracao = "E-Mail enviado para: $nome_completo_requerente e E-Mail: " . $mail;
                    if (!empty($anexos_processados)) {
                        $alteracao .= " com " . count($anexos_processados) . " anexo(s)";
                    }

                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $nome_completo_requerente, $id_suporte, "14116", "mail", "Insert", $alteracao, $mensagem_formatada);
                }

                if (!$enviado) {
                    $detalhe_erro = print_r(error_get_last());
                    $alteracao = "ERRO! E-Mail NÃO enviado para: $nome_completo_requerente e E-Mail: " . $mail;
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
}
