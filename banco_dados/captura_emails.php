<?php
$dados = $conexao->get_selecao_id();

$imapHost = $dados[0]['mail_imap'] ?? 'imap.webmail.eb.mil.br';
$imapPort = $dados[0]['porta_imap'] ?? '993';

$hostname = sprintf('{%s:%s/imap/ssl}INBOX', $imapHost, $imapPort);

$username = $dados[0]['usuario_email'] ?? 'siscant@3rm.eb.mil.br';
$password = $dados[0]['senha_email'];

$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    error_log('Falha ao conectar no IMAP: ' . imap_last_error());
} else {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
            $structure = imap_fetchstructure($inbox, $email_number);

            $message = '';
            $attachments = [];

            if (!isset($structure->parts)) {
                $message = imap_body($inbox, $email_number);
            } else {
                foreach ($structure->parts as $part_no => $part) {
                    $is_attachment = false;
                    $filename = '';

                    // Verifica se é anexo
                    if (isset($part->dparameters)) {
                        foreach ($part->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $is_attachment = true;
                                $filename = $object->value;
                            }
                        }
                    }

                    if (isset($part->parameters)) {
                        foreach ($part->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $is_attachment = true;
                                $filename = $object->value;
                            }
                        }
                    }

                    if ($is_attachment) {
                        // Decodifica conteúdo do anexo
                        $attachment = imap_fetchbody($inbox, $email_number, $part_no + 1);
                        if ($part->encoding == 3) { // base64
                            $attachment = base64_decode($attachment);
                        } elseif ($part->encoding == 4) { // quoted-printable
                            $attachment = quoted_printable_decode($attachment);
                        }

                        // Garante pasta
                        $upload_dir = '../sistema/arquivos/arquivos_email/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        // Salva arquivo
                        $safe_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
                        file_put_contents($upload_dir . $safe_filename, $attachment);

                        $attachments[] = $safe_filename;
                    } else {
                        // Corpo principal
                        if ($part->subtype == 'PLAIN') {
                            $message .= imap_fetchbody($inbox, $email_number, $part_no + 1);
                        }
                    }
                }
            }

            // Limpa o texto da mensagem
            $mensagem = trim(strip_tags(quoted_printable_decode($message)));
            $assunto = imap_utf8($overview->subject ?? '(Sem assunto)');
            $from = imap_utf8($overview->from ?? '(Desconhecido)');
            $data_envio = null;

            // Extrai nome e email separadamente
            if (preg_match('/(.*)<(.*)>/', $from, $matches)) {
                $remetente = trim(str_replace('"', '', $matches[1]));
                $email_remetente = trim($matches[2]);
            } else {
                $remetente = trim($from);
                $email_remetente = $from;
            }

            // Captura a data do email, converte para formato MySQL (YYYY-MM-DD HH:MM:SS)
            if (!empty($overview->date)) {
                $timestamp = strtotime($overview->date);
                if ($timestamp !== false) {
                    $data_envio = date('Y-m-d H:i:s', $timestamp);
                } else {
                    $data_envio = date('Y-m-d H:i:s'); // Data atual como fallback
                }
            }
            // Salva no banco
            $emailData = $conexao->insere_email($dados[0]['id'] ,$remetente, $email_remetente, $assunto, $mensagem, $data_envio);
            if ($emailData) {
                $id_email = $emailData['id_adicionado'];

                foreach ($attachments as $file) {
                    $conexao->insere_email_arquivo($id_email, $file);
                }
            }

            // Marca como lido
            imap_setflag_full($inbox, $email_number, "\\Seen");
        }
    }

    imap_close($inbox);
}


