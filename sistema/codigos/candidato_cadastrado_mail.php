
<?php
/**
 * @author Asp Volpato
 * 2.0v - 08/06/2022 - recuperação de senha da vpn
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$resultado_selecao = $conexao->get_selecao_id();

include_once '../envia_carta/src/Exception.php';
include_once '../envia_carta/src/PHPMailer.php';
include_once '../envia_carta/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$randomString = 'r@nd0m$tring';

//Create a new PHPMailer instance
$mail_envia = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail_envia->SMTPDebug = 0;

$mail_envia->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => true,
        'allow_self_signed' => true
    )
);

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
    $mail_envia->Subject = 'SiSCanT - Cadastro no Sistema de Seleção de Candidatos Temporários';                       // Assunto do e-mail

    // Configurações do remetente e destinatário    

    $mail_envia->setFrom('siscant@3rm.eb.mil.br', 'Servico Militar');        // Remetente
    //$mail_envia->addAddress('siscant@3rm.eb.mil.br'); // Adiciona um destinatário

    $mail_envia->AddAddress($mail, $nome_completo);

    // Definir como HTML
    $mail_envia->isHTML(true);

    // Conteúdo do e-mail
    $mail_envia->Body = utf8_decode('        
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
            </head>
            <body style="background-color: #bdc3c7; font-family:Arial, Helvetica, sans-serif;">
                <table class="container-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <!-- Header -->
                        <tr>
                            <td class="header-cell" style="background-color: green; padding: 25px 20px; text-align: center; color: #ffffff;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="text-align: center;">
                                                 <h1 style="font-size: 22px; margin: 0 0 5px 0; font-weight: bold;">ALTERAÇÃO DE SENHA</h1>
                                            <p style="font-size: 14px; margin: 0; opacity: 0.9;">Sistema de Seleção de Candidatos Temporários</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                        <td style="padding: 30px 25px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <!-- Greeting -->
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p style="font-size: 16px; color: #333333; margin: 0;">
                                            Prezado(a) <strong>' . $nome_completo . '</strong>,
                                        </p>
                                    </td>
                                </tr>
                                
                                <!-- Intro -->
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <p style="font-size: 14px; color: #555555; margin: 0;">
                                            Seu cadastro foi realizado com sucesso, mas ainda não acabou!
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <center>
                                            <p style="width: 100%; background-color: green; font-size: 20px; color: white; padding: 10px; border-radius: 10px; text-align: center;">
                                                <b>
                                                    O seu usuário é: <u>' . $cpf . '</u> <br>
                                                    E a sua senha é: <u> ' . $senha . '</u>
                                                </b>
                                            </p>
                                        </center>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <p style="font-size: 14px; color: #555555; margin: 0;">
                                            Lembre-se que você deve acessar o SiSCanT e realizar os procedimentos lá descritos para concluir sua Inscrição!
                                        </p>
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
                </table>
            </body>
        </html>
    ');

    $mail_envia->SMTPDebug = 0;
    $enviado = $mail_envia->send();

    $_SESSION['cadastro_candidato_email_enviado'] = false;

    if ($enviado) {
        $_SESSION['cadastro_candidato_email_enviado'] = true;
        $mensagem_formatada = str_replace("'", "`", $mensagem);
        $alteracao = "E-Mail de cadastro enviado para:" . $mail;

        $insere_log = $conexao->insere_log(null, $cpf, $last_id, "14102", "mail", "Insert", $alteracao, $mensagem_formatada);
    } else {
        $detalhe_erro = print_r(error_get_last());
        $alteracao = "ERRO! E-Mail de cadastro de candidato NÃO enviado para:" . $mail;
        $insere_log = $conexao->insere_log(null, $cpf, $last_id, "21100", "mail", "Insert", $alteracao, $detalhe_erro);
    }
} catch (Exception $e) {
    echo "Erro ao e enviar mensagem: {$mail_envia->ErrorInfo}";
}
