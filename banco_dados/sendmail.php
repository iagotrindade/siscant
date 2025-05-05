
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
        



/**
 * sendmail function
 * envia email para o usuário com um código para confirmação
 *
 * @param [string] $user_mail - email informado do usuário
 * @param [string] $idt  - identidade militar do usuário
 * @return void
 */

/*
function sendmail($mail, $cpf_usuario) {

    // Valor randomico de 5 digitos para ser enviado por email
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    session_start();
    $_SESSION['code'] = password_hash($randomString, PASSWORD_DEFAULT);

    //Create a new PHPMailer instance
    $mail = new PHPMailer();
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Custom connection options
    //Note that these settings are INSECURE
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false
        )
    );
    */
    $randomString = 'r@nd0m$tring';

    //Create a new PHPMailer instance
    $mail = new PHPMailer();
    //Tell PHPMailer to use SMTP
    $mail->SMTPDebug = 0;
   
    $mail->SMTPOptions = array(
        'ssl' => array(
           'verify_peer' => false,
            'verify_peer_name' => true,
            'allow_self_signed' => true
        )); 

try {
    
    $mail->isSMTP();
    $mail->Host = 'smtp.webmail.eb.mil.br'; // Servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'jhartmann@3rm.eb.mil.br'; // Seu usuário SMTP
    $mail->Password = '12345678'; // Sua senha SMTP
    $mail->SMTPSecure = 'tls';  //'tls' Define o tipo de criptografia para TLS
    $mail->Port = 587; // Porta TCP para TLS
   
    $mail->From = 'jhartmann@3rm.eb.mil.br'; //Set who the message is to be sent from
    $mail->FromName = utf8_decode('Não responda - Comando 3ª RM'); //Nome do Remetente
    $mail->Subject = utf8_decode('Recuperação de senha da VPN'); //Assunto da mensagem

      // Configurações do remetente e destinatário
      $mail->setFrom('siscant@3rm.eb.mil.br', 'Servico Militar');        // Remetente
      //$mail->addAddress('jhartmann@3rm.eb.mil.br'); // Adiciona um destinatário

      $mail->AddAddress($mail_usuario, $cpf_candidato);
  
      // Conteúdo do e-mail
      $mail->isHTML(true);                                        // Definir como HTML
      $mail->Subject = 'Solicitacao de Reset de Senha';                       // Assunto do e-mail
      $mail->Body = '<b>Sua nova senha do Siscant:</b>' . $nova_senha . '<br><br>Entre com seu CPF e sua nova senha. Em seguida escolha uma senha de 
      sua preferência!<br>Este é um email automático, não responda.'; // Corpo da mensagem em HTML
      $mail->AltBody = 'Este é o corpo alternativo em texto simples para clientes de e-mail sem suporte HTML.';
 
      $mail->SMTPDebug = 0;
      $enviado = $mail->send();

      if(!$enviado) {
        echo 'Erro ao enviar o email: ' . $mail->ErrorInfo;
        } else {
        echo 'Email enviado com sucesso!';
        }
     
      if ($enviado) {
        $foi_enviado_email = true;
      }
      else {
        echo 'Falha ao enviar mensagem: ' . $mail->ErrorInfo;
    }

    } catch (Exception $e) {
        echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
        }


    //Set the hostname of the mail server
    // $mail->Host = 'lugia.1cta.eb.mil.br';
    //Set the SMTP port number - likely to be 25, 465 or 587
    // $mail->Port = 25;
    //Set who the message is to be sent to

    /* $mensagem = 
   
                "
                <b>".  mb_strtoupper($resultado_selecao[0]['nome'], 'UTF-8'). " - ".$resultado_selecao[0]['ano']."</b><br><br>
                    
                ".$nome.", a sua senha do SiSCanT foi resetada!<br><br>

                <b>Usuário: </b>".$cpf_candidato."<br>
                <b>Nova senha: </b>".$nova_senha."<br><br>";

                if($candidato == 1)
                $mensagem = $mensagem . "
                <i> Não responda este E-Mail!<br>
                A comunicação deve ser realizada pelo sistema SiSCanT </i>";

                $mail->Body = $mensagem;
              
                #Envio da Mensagem
                $enviado = $mail->Send();

                #Limpa os destinatários e os anexos
                $mail->ClearAllRecipients();
                $mail->ClearAttachments();
                #Exibe uma mensagem de resultado
            
                if($enviado)
                {
                    $foi_enviado_email = true;
                    $mensagem_formatada = str_replace("'","`",$mensagem);
                    $alteracao = "Senha resetada e enviada para $nome, E-Mail: $mail_usuario, CPF: $cpf_candidato";
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_candidato, null, "14117", "mail", "Insert", $alteracao, $mensagem_formatada);
                }

               /* else
                {
                    $alteracao = "ERRO! E-Mail de reset de senha não foi enviado para $nome, E-Mail: $mail_usuario, CPF: $cpf_candidato";
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_candidato, null, "21101", "mail", "Insert", $alteracao, null);
                }

  // Replace the plain text body with one created manually
    $mail->Body = utf8_decode('        
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
            </head>
            <body style="background-color: #bdc3c7; font-family:Arial, Helvetica, sans-serif;">
                <div style="padding: 20px;">
                    <div style="width: 600px; background-color: white; padding: 20px; border-radius: 5px;">
                        Prezado.<br>
                        O código para a recuperação de senha é: <br>
                        <center><p style="width: 15%; background-color: green; font-size: 20px; color: white; padding: 10px; border-radius: 10px; text-align: center;">
                            <b>'.$randomString.'</b>
                        </p></center>
                        Caso você não tenha feito esta solicitação recomendamos que avise nossa Central de Serviços e ignore este email.
                        <center>
                        <br><br><br>
                            <p>1º Centro de Telemática de Área</p>
                            <img style="width: 50px;" src="https://www.1cta.eb.mil.br/images/phocagallery/1cta.png" alt="1º CTA">
                        </center>
                    </div>
                </div>
            </body>
        </html>
    ');

    //Corpo da mensagem em texto
    $mail->AltBody = utf8_decode('Comando da 3ª Região Militar');

    //send the message, check for errors
    $ret = $mail->Send();

    
               // var_dump($ret);
              // exit;
    // $ret = true;
    if ($ret) {
        return ['status' => 'success', 'msg' => 'E-mail enviado com sucesso.'];
    } else {
       echo "ERRO!";
        exit;
       saveLog('sendmail error: ' . $mail->ErrorInfo);
        return ['status' => 'danger', 'msg' => 'Erro ao enviar email. Favor, informe nossa central de serviços.<br>' . $mail->ErrorInfo];
    }

 

