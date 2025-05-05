<?php

$smtpServer = "smtp.webmail.eb.mil.br";
$assunto = "SisCanT / Solicitação de Reset de Senha"; 
$mensagem = "A sua senha do SiSCanT foi resetada!<br><br>

Usuário: ".$cpf_candidato."
Nova senha: ".$nova_senha."

Não responda este E-Mail!
A comunicação deve ser realizada pelo sistema SiSCanT ";

function enviarEmailComSwaks($mail_usuario, $assunto, $mensagem, $smtpServer, $nova_senha) {
   
    // Cria o comando para enviar o e-mail usando swaks
    // $comando = "swaks --to siscant@3rm.eb.mil.br --from jhartmann@3rm.eb.mil.br --server smtp.webmail.eb.mil.br --port 587 --auth LOGIN --auth-user jhartmann@3rm.eb.mil.br --auth-password 12345678 --tls --header 'Subject: Teste de E-mail HTML' --header 'Content-Type: text/html' --body 'Sua nova senha é " . $mensagem . "'  ";
    $comando = "swaks --to " . $mail_usuario . " --from siscant@3rm.eb.mil.br --server smtp.webmail.eb.mil.br --port 587 --auth LOGIN --auth-user jhartmann@3rm.eb.mil.br --auth-password '12345678' --tls --header 'Subject: Nova senha de Usuário do SisCanT' --header 'Content-Type: text/html' --body 'Sua nova senha é " . $nova_senha . "'";
    $output = [];
    $return_var = 0;
    exec($comando, $output, $return_var);

   /* if ($return_var === 0) {
        echo "E-mail enviado com sucesso!\n";
        exit;
    } else {
        echo "Falha ao enviar e-mail. Código de retorno: $return_var\n";
        exit;
    }
        */
}

// Exemplo de uso
enviarEmailComSwaks($mail_usuario, $assunto, $mensagem, $smtpServer, $nova_senha);


?>
