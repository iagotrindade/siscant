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
    $mail_envia->Password = '12345678'; // Sua senha SMTP
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

    $mail_envia->Subject = "Serviço Técnico Temporário - Suporte ao Candidato"; // Assunto da mensagem
    $mensagem =
        "
<b>" .  mb_strtoupper($resultado_selecao[0]['nome'], 'UTF-8') . " - " . $resultado_selecao[0]['ano'] . "</b><br><br>
    
" . $nome_completo_requerente . ", esta é a resposta da sua solicitação de suporte!<br><br>

<i>Data de envio: " . trata_data($data_enviado_requerente) . " <br>
Suporte Solicitado: " . $mensagem_requerente . " </i><br><br>

<b>Data Resposta: </b> " .  trata_data($datetime) . "<br>
<b>Resposta: </b> " . $resposta . "<br></b>

<br><i> Não responda este E-Mail!<br>
A comunicação deve ser realizada pelo sistema SiSCanT </i>";

    $mail_envia->Body = $mensagem;
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
