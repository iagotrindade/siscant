<?php

$resultado_selecao = $conexao->get_selecao_id();

//include_once '../PHPMailer/class.phpmailer.php';

//Funcionava até 24/02/2023
//include_once '../PHPMailer/PHPMailerAutoload.php';


include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
    $mail_envia->Host = 'smtp.webmail.eb.mil.br'; // Servidor SMTP
    $mail_envia->SMTPAuth = true;
    $mail_envia->Username = 'siscant@3rm.eb.mil.br'; // Seu usuário SMTP
    $mail_envia->Password = 'EMHFXQMNPYQMAWYD'; // Sua senha SMTP
    $mail_envia->SMTPSecure = 'tls';  //'tls' Define o tipo de criptografia para TLS
    $mail_envia->Port = 587; // Porta TCP para TLS

    $mail_envia->From = 'siscant@3rm.eb.mil.br'; //Set who the message is to be sent from
    $mail_envia->FromName = utf8_decode('Não responda - Comando 3ª RM'); //Nome do Remetente

    // Configurações do remetente e destinatário
    $mail_envia->setFrom('siscant@3rm.eb.mil.br', 'Servico Militar');        // Remetente
    //$mail->addAddress('siscant@3rm.eb.mil.br'); // Adiciona um destinatário

    $mail_envia->AddAddress($mail, $cpf_requerente); // Vai enviar o e-mail, E-Mail e Nome

    $mail_envia->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail_envia->CharSet = 'utf-8'; // Charset da mensagem (opcional)

    $mail_envia->Subject = "SiSCanT - Suporte ao Candidato"; // Assunto da mensagem

    $mail_envia->Body = utf8_decode('        
        <!DOCTYPE html>
        <html lang="pt-BR">
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
        <body style="margin: 0; padding: 20px; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; line-height: 1.4;">
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
                                            Sua dúvida encaminhada através vai E-mail foi respondida.
                                        </p>
                                    </td>
                                </tr>
                                
                                <!-- Message Box -->
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: green; border-left: 4px solid green; border-radius: 4px;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <!-- Data Envio -->
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                                        <tr>
                                                            <td width="120" style="font-size: 14px; color: green; font-weight: bold; vertical-align: top;">
                                                                Data de Envio:
                                                            </td>
                                                            <td style="font-size: 14px; color: #555555;">
                                                                ' . trata_data($data_enviado_requerente) . '
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Mensagem Enviada -->
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                                        <tr>
                                                            <td width="120" style="font-size: 14px; color: green; font-weight: bold; vertical-align: top;">
                                                                Mensagem Enviada:
                                                            </td>
                                                            <td style="font-size: 14px; color: #555555;">
                                                                ' . $mensagem_requerente . '
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Divider -->
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0;">
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #dddddd;"></td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Data Resposta -->
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                                        <tr>
                                                            <td width="120" style="font-size: 14px; color: green; font-weight: bold; vertical-align: top;">
                                                                Data Resposta:
                                                            </td>
                                                            <td style="font-size: 14px; color: #555555;">
                                                                ' . trata_data($datetime) . '
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Resposta -->
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td width="120" style="font-size: 14px; color: green; font-weight: bold; vertical-align: top;">
                                                                Resposta:
                                                            </td>
                                                            <td style="font-size: 14px; color: #555555;">
                                                                ' . $resposta . '
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
        $alteracao = "E-Mail resposta de suporte enviado para CPF: $cpf_requerente e E-Mail: " . $mail;

        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_requerente, $id_suporte, "14116", "mail", "Insert", $alteracao, $mensagem_formatada);
    }

    if (!$enviado) {
        $detalhe_erro = print_r(error_get_last());
        $alteracao = "ERRO! E-Mail de suporte NÃO enviado para CPF: $cpf_requerente e E-Mail: " . $mail;
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_requerente, $id_suporte, "21102", "mail", "Insert", $alteracao, $detalhe_erro);
    }
} catch (Exception $e) {
    echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}
