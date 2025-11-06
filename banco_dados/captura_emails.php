<?php
$dados = $conexao->get_selecao_id();

$imapHost = $dados[0]['mail_imap'];
$imapPort = $dados[0]['porta_imap'];
$hostname = sprintf('{%s:%s/imap/ssl}INBOX', $imapHost, $imapPort);

$username = $dados[0]['usuario_email'];
$password = $dados[0]['senha_email'];

$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    $erroImap = true;
} else {
    $emails = imap_search($inbox, 'UNSEEN');

    if ($emails) {
        rsort($emails);

        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
            $structure = imap_fetchstructure($inbox, $email_number);

            $message = '';
            $attachments = [];

            // Fila de partes para processar (simula recursão)
            $partsQueue = [['structure' => $structure, 'part_number' => null]];

            while ($partsQueue) {
                $current = array_shift($partsQueue);
                $part = $current['structure'];
                $partNumber = $current['part_number'];

                // Se tiver subpartes, adiciona na fila
                if (isset($part->parts) && count($part->parts)) {
                    $i = 1;
                    foreach ($part->parts as $subpart) {
                        $newPartNum = $partNumber ? $partNumber . '.' . $i : (string)$i;
                        $partsQueue[] = ['structure' => $subpart, 'part_number' => $newPartNum];
                        $i++;
                    }
                    continue;
                }

                // Verifica se é anexo
                $is_attachment = false;
                $filename = '';

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

                // Busca o corpo/arquivo da parte
                $data = imap_fetchbody($inbox, $email_number, $partNumber ?? 1);

                if ($part->encoding == 3) {
                    $data = base64_decode($data);
                } elseif ($part->encoding == 4) {
                    $data = quoted_printable_decode($data);
                }

                if ($is_attachment) {
                    $upload_dir = '../sistema/arquivos/arquivos_email/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $safe_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
                    file_put_contents($upload_dir . $safe_filename, $data);

                    $attachments[] = $safe_filename;
                } else {
                    if (isset($part->subtype)) {
                        $subtype = strtoupper($part->subtype);

                        // Dá prioridade ao HTML se existir, senão usa PLAIN
                        if ($subtype === 'HTML' && empty($message)) {
                            $message = $data;
                        } elseif ($subtype === 'PLAIN' && empty($message)) {
                            $message = $data;
                        }
                    }
                }
            }

            // Limpa corpo e metadados
            $mensagem = trim(strip_tags($message));
            if (!empty($overview->subject)) {
                $decoded_subject = imap_mime_header_decode($overview->subject);
                $assunto = '';
                foreach ($decoded_subject as $obj) {
                    $assunto .= $obj->text;
                }
            } else {
                $assunto = '(Sem assunto)';
            }
            $from = imap_utf8($overview->from ?? '(Desconhecido)');
            $data_envio = !empty($overview->date) ? date('Y-m-d H:i:s', strtotime($overview->date)) : date('Y-m-d H:i:s');

            if (preg_match('/(.*)<(.*)>/', $from, $matches)) {
                $remetente = trim(str_replace('"', '', $matches[1]));
                $email_remetente = trim($matches[2]);
            } else {
                $remetente = trim($from);
                $email_remetente = $from;
            }

            // Salva no banco
            $emailData = $conexao->insere_email(
                $dados[0]['id'],
                $remetente,
                $email_remetente,
                $assunto,
                $mensagem,
                $data_envio
            );

            if ($emailData) {
                $id_email = $emailData['id_adicionado'];
                foreach ($attachments as $file) {
                    $conexao->insere_email_arquivo($id_email, $file);
                }
            }

            imap_setflag_full($inbox, $email_number, "\\Seen");
        }
    }

    imap_close($inbox);
}
