<?php
//APS-SILVA-14-MAIO-2025
class Conexao
{
    var $pdo;
    function __construct()
    {
        //Local
        $this->pdo = new PDO('mysql:host=localhost;dbname=siscant;charset=utf8', 'root', '');

        // CTA SISTEMAS TESTE
        //$this->pdo = new PDO('mysql:host=10.25.60.31;dbname=siscant_hom;charset=utf8', 'root', 'suporte');

        //Produção
        //$this->pdo = new PDO('mysql:host=localhost;dbname=siscant;charset=utf8', 'root', '123@ati3rm');

        //Produção 2025
        //$this->pdo = new PDO('mysql:host=localhost;dbname=siscant;charset=utf8', 'root', 'ati@root@mysql');
    }

    // <editor-fold defaultstate="collapsed" desc="Get Browser">
    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'Mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'Windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern
        );

        $ua = getBrowser();
        return $yourbrowser = $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . " reports: " . $ua['userAgent'];
    }

    public function login($usuario, $senha, $id_selecao)
    {
        $stmt = $this->pdo->prepare(
            "select u.* 
                from usuario u 
                inner join selecao s on u.id_selecao = s.id
                where u.cpf = :cpf and u.senha = :senha and u.apagado = 0
                and s.id = :id_selecao"
        );
        $stmt->bindValue(':cpf', $usuario);
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':id_selecao', $id_selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="QUANTIDADE TENTATIVAS LOGIN">
    public function quantidades_tentativas_login($ip)
    {
        /*$stmt = $this->pdo->prepare(
                'select * from tentativa_login where DATE_FORMAT(data, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") and ip = :ip'
                );
        $stmt->bindValue(':ip', $ip);
        $run = $stmt->execute();*/
        return [];
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Reseta a senha">
    public function usuario_reseta_senha($id_usuario, $senha)
    {

        $nova_senha = hash('sha256', $senha);
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE usuario SET senha= :senha  where id = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":senha", $nova_senha);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario_senha_resetada' => $id_usuario,
                        'nova_senha_usuario' => $nova_senha,
                        'id_usuario_resetou' => $_SESSION['id_usuario'],
                        'cpf_usuario_resetou' => $_SESSION['cpf'],
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Muda senha Médico obrigatório">
    public function altera_senha_medico_obrigatorio($id_usuario, $senha)
    {

        $nova_senha = hash('sha256', $senha);
        $datetime = date('Y-m-d H:i:s');


        try {
            $sql = "UPDATE usuario SET senha= :senha  where id = :id_usuario and medico_obrigatorio = 1 and id_selecao =:selecao ";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":senha", $nova_senha);
            $query->bindValue(':selecao', $_SESSION['selecao']);

            if ($query->execute()) {

                $data =
                    [
                        'id_usuario_senha_resetada' => $id_usuario,
                        'nova_senha_usuario' => $nova_senha,
                        'id_usuario_resetou' => $_SESSION['id_usuario'],
                        'cpf_usuario_resetou' => $_SESSION['cpf'],
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Reseta a senha do candidato">
    public function esqueci_reseta_senha($id_usuario, $cpf, $senha)
    {

        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE usuario SET senha= :senha, trocar_senha = 1  where id = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":senha", $senha);

            if ($query->execute()) {

                $data =
                    [
                        'id_usuario_esqueceu_resetou_senha' => $id_usuario,
                        'cpf_usuario_esqueceu_resetou_senha' => $cpf,
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Reseta a senha do candidato">
    public function reseta_senha_candidato($id_usuario, $cpf, $senha)
    {

        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE usuario SET senha= :senha, trocar_senha = 1  where id = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":senha", $senha);

            if ($query->execute()) {

                $data =
                    [
                        'id_usuario_senha_resetada' => $id_usuario,
                        'cpf_usuario_senha_resetada' => $cpf,
                        'id_usuario_resetou' => $_SESSION['id_usuario'],
                        'cpf_usuario_resetou' => $_SESSION['cpf'],
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Foto Antiga">

    public function apaga_foto_antiga()
    {
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE foto SET apagado = '1' where id_usuario = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario_apagou_foto_antiga' => $_SESSION['id_usuario'],
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga CSV Antigo">

    public function apaga_csv_antigo()
    {
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE gru_pagas SET apagado = '1', _usuario_ultima_atualizacao = :id_usuario,
                _data_ultima_atualizacao = :datetime
                where id_selecao = :id_selecao";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_selecao", $_SESSION['selecao']);
            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Resposta Suporte Candidato">

    public function insere_resposta_candidato($id_suporte, $resposta)
    {
        $id_ususario = $_SESSION['id_usuario'];
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE suporte_candidato SET 
            respondida='1', id_usuario_respondeu=:id_ususario, 
            resposta=:resposta, data_resposta=:datetime WHERE id=:id_suporte";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_suporte", $id_suporte);
            $query->bindValue(":resposta", $resposta);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_ususario", $id_ususario);

            if ($query->execute()) {

                $data =
                    [
                        'id_suporte' => $id_suporte,
                        'resposta' => $resposta,
                        'id_usuario_respondeu' => $datetime,
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Resposta Suporte Inicial">

    public function insere_resposta_inicial($id_suporte, $resposta)
    {
        $id_ususario = $_SESSION['id_usuario'];
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "UPDATE suporte SET 
            usuario_resposta=:id_ususario, 
            resposta=:resposta, data_resposta=:datetime WHERE id=:id_suporte";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_suporte", $id_suporte);
            $query->bindValue(":resposta", $resposta);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_ususario", $id_ususario);

            if ($query->execute()) {

                $data =
                    [
                        'id_suporte' => $id_suporte,
                        'resposta' => $resposta,
                        'id_usuario_respondeu' => $datetime,
                        'datetime' => $datetime,
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Candidato altera senha">

    public function candidato_altera_senha($id_usuario, $senha)
    {
        $data =
            [
                'id_usuario' => $id_usuario,
                'senha' => $senha,
            ];

        try {
            $sql = "UPDATE usuario SET senha= :senha, trocar_senha = 0  where id = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":senha", $senha);

            if ($query->execute()) {
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
    // </editor-fold>    

    // <editor-fold defaultstate="collapsed" desc="Tentativa de Login">
    public function tentativa_login($usuario, $senha, $selecao)
    {
        $navegador = getBrowser();
        $navegador = $navegador['platform'] . " - " . $navegador['name'] . " " . $navegador['version'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $datetime = date('Y-m-d H:i:s');

        $data =
            [
                'selecao' => $selecao,
                'usuario' => $usuario,
                'senha' => $senha,
                'data' => $datetime,
                'ip' => $ip,
                'sistema' => $navegador
            ];

        $sql = "INSERT INTO tentativa_login 
        (id_selecao, usuario, senha, data, ip, sistema)
        VALUES
        (:selecao, :usuario, :senha, :data, :ip, :sistema)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $this->pdo->beginTransaction();
            $stmt->execute($data);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
        return false;
    }


    // 14 MAIO 2024 -> IAGO SILVA
    public function altera_email_candidato($id_candidato, $novo_email, $id_admin, $admin_password)
    {
        try {
            $stmt = $this->pdo->prepare(
                "select senha from usuario where id = :id_admin"
            );
            $stmt->bindValue(':id_admin', $id_admin);
            $run = $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($admin_password == $result[0]['senha']) {
                $sqlInsert = "UPDATE usuario SET mail = :novo_email WHERE id = :id_candidato";

                $this->pdo->beginTransaction();

                $query = $this->pdo->prepare($sqlInsert);

                $query->bindValue(":id_candidato", $id_candidato);
                $query->bindValue(":novo_email", $novo_email);

                if ($query->execute()) {
                    $data =
                        [
                            'id_candidato' => $id_candidato,
                            'novo_email' => $novo_email,
                        ];

                    $this->pdo->commit();
                    return $data;
                } else {
                    echo ('nops');
                    exit;
                    $this->pdo->rollBack();
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Cidade UF">
    public function busca_cidade_uf($uf)
    {
        $stmt = $this->pdo->prepare(
            "SELECT `id`,`nome` FROM `cidade` where uf = :uf"
        );
        $stmt->bindValue(':uf', $uf);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Cidade UF">
    public function busca_cidades()
    {
        $stmt = $this->pdo->prepare("SELECT `id`,`nome`, uf FROM `cidade`");
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function busca_oms()
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, abreviatura, rm FROM om");
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function busca_rms()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT id, nome, abreviatura, rm
                                            FROM om
                                            WHERE rm BETWEEN 1 AND 14   
                                            GROUP BY rm;
                                            ");
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Foto do Usuário">
    public function get_foto_usuario($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "select id, id_usuario, nome, extensao
                from foto 
                where apagado = 0 and id_usuario = :id_usuario"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Nome Especialidade">
    public function get_nome_especialidade($nome, $ott_stt)
    {
        $stmt = $this->pdo->prepare(
            "select * from especialidade where nome = :nome and ott_stt = :ott_stt and id_selecao = :selecao and apagado = 0"
        );
        $stmt->bindValue(':ott_stt', $ott_stt);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_nome_especialidade_por_id_selecao($id_selecao)
    {
        $stmt = $this->pdo->prepare(
            "select * from especialidade where id_selecao = :selecao and apagado = 0"
        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Nome Currículo">
    public function get_nome_curriculo($nome)
    {
        $stmt = $this->pdo->prepare(
            "select * from curriculo where nome = :nome and id_selecao = :selecao and apagado = 0"
        );
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Nome DOC Obrigatório">
    public function get_nome_doc_obrigatorio($nome)
    {
        $stmt = $this->pdo->prepare(
            "select * from documentacao_obrigatoria where nome = :nome and id_selecao = :selecao and apagado = 0"
        );
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Pesquisa CPF">
    public function pesquisa_cpf($cpf)
    {
        $stmt = $this->pdo->prepare(
            "select u.*, c.nome nome_cidade, s.nome nome_selecao, s.codigo codigo_selecao, s.ano ano_selecao, rm rm_selecao
                from usuario u
                left join cidade c on c.id = u.id_cidade
                inner join selecao s on s.id = u.id_selecao
                where (cpf like :cpf or nome_completo like :cpf) and id_selecao = :selecao and u.apagado = 0"
        );
        $stmt->bindValue(':cpf', "%$cpf%", PDO::PARAM_STR);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuario por CPF e Seleção">
    public function get_usuario_cpf($cpf)
    {
        $stmt = $this->pdo->prepare(
            "select u.*, c.nome nome_cidade, s.nome nome_selecao, s.codigo codigo_selecao, s.ano ano_selecao, rm rm_selecao
                from usuario u
                left join cidade c on c.id = u.id_cidade
                inner join selecao s on s.id = u.id_selecao
                where cpf = :cpf and id_selecao = :selecao and u.apagado = 0"
        );
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verifica usuário avaliador">
    public function verifica_usuario_avaliador($id_especialidade, $id_candidato)
    {
        $stmt = $this->pdo->prepare(
            "select * from candidato_x_especialidade where id_candidato = :id_candidato and id_especialidade = :id_especialidade"
        );
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':id_candidato', $id_candidato);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get candidato_x_especialidade por ID">
    public function get_candidato_x_especialidade_id($id)
    {
        $stmt = $this->pdo->prepare(
            "select * from candidato_x_especialidade where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidade_x_Curriculo por ID">
    public function get_especialidade_curriculo_id($id)
    {
        $stmt = $this->pdo->prepare(
            "select * from especialidade_curriculo where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuario por ID">
    public function get_usuario_id($id)
    {
        $stmt = $this->pdo->prepare(
            "select om.nome om_nome, om.abreviatura om_abreviatura, om.id om_id, u.*, c.nome nome_cidade, c.id id_cidade, s.nome nome_selecao, om_1_fase.nome nome_om_1_fase,
                                        om_1_fase.cep cep_om_1_fase, om_1_fase.endereco endereco_om_1_fase, om_1_fase.guarnicao guarnicao_om_1_fase, 
                                        om_1_fase.telefone telefone_om_1_fase, om_1_fase.uf uf_om_1_fase,
                                        cid.nome cidade_instituto_ensino, cid.id id_cidade_instituto_ensino, s.codigo codigo_selecao, s.ano ano_selecao, s.rm rm_selecao
                                        from usuario u
                                        left join cidade c on c.id = u.id_cidade
                                        inner join selecao s on s.id = u.id_selecao
                                        left join om on om.id = u.id_om
                                        left join cidade cid on cid.id = u.id_cidade_instituto_ensino
                                        left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                        where u.id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_candidato_codigo_selecao($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT s.codigo
                                        FROM usuario u
                                        LEFT JOIN cidade c ON c.id = u.id_cidade
                                        INNER JOIN selecao s on s.id = u.id_selecao
                                        LEFT JOIN om on om.id = u.id_om
                                        LEFT JOIN cidade cid on cid.id = u.id_cidade_instituto_ensino
                                        LEFT JOIN om om_1_fase on om_1_fase.id = u.om_1_fase
                                        WHERE u.id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Seleção por ID">
    public function get_selecao_id()
    {
        $id = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare(
            "select * from selecao where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_selecao_rm($id)
    {
        $stmt = $this->pdo->prepare("SELECT rm_inscricao FROM usuario WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['rm_inscricao'] : null;
    }





    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Seleções">
    public function get_selecoes()
    {

        $rm = $_SESSION['selecao_regiao'];

        $stmt = $this->pdo->prepare(
            "select * from selecao where rm = :rm order by id "
        );
        $stmt->bindValue(':rm', $rm);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade X Motivo suporte candidato">
    public function get_quantidade_x_motivo_suporte_cand()
    {
        $id = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare(
            "select sc.motivo, count(sc.motivo) quantidade
                                        from suporte_candidato sc
                                        inner join usuario u on u.id = sc.id_usuario_remetente
                                        inner join selecao s on s.id = u.id_selecao
                                        where sc.apagado = 0
                                        and s.id = :id_selecao
                                        group by motivo
                                        order by quantidade desc"
        );
        $stmt->bindValue(':id_selecao', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade X Motivo suporte candidato">
    public function get_quantidade_x_motivo_suporte()
    {
        $id = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare(
            "select motivo, count(motivo) quantidade
                                    from suporte
                                    where apagado = 0
                                    and id_selecao = :id_selecao
                                    group by motivo
                                    order by quantidade desc"
        );
        $stmt->bindValue(':id_selecao', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade X Usuário de respostas de suporte candidato">
    public function get_quantidade_x_usuario_suporte_cand()
    {
        $id = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare(
            "select u.id,u.posto_grad,u.nome_guerra,count(id_usuario_respondeu) quantidade
                                        from suporte_candidato sc
                                        inner join usuario u on u.id = sc.id_usuario_respondeu
                                        where id_usuario_respondeu is not null
                                        and sc.apagado = 0
                                        and u.id_selecao = :id_selecao
                                        group by sc.id_usuario_respondeu
                                        order by quantidade desc"
        );
        $stmt->bindValue(':id_selecao', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade X Usuário de respostas de suporte candidato">
    public function get_quantidade_x_usuario_suporte()
    {
        $id = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare(
            "select u.id,u.posto_grad,u.nome_guerra,count(s.usuario_resposta) quantidade
                                        from suporte s
                                        inner join usuario u on u.id = s.usuario_resposta
                                        where usuario_resposta is not null
                                        and s.apagado = 0
                                        and s.id_selecao = :id_selecao
                                        group by s.usuario_resposta
                                        order by quantidade desc"
        );
        $stmt->bindValue(':id_selecao', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Suporte Inicial ID">
    public function get_suporte_inicial_id($id)
    {
        $stmt = $this->pdo->prepare(
            "select *
                                    from suporte
                                    where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Suporte Candidato por ID">
    public function get_suporte_candidato_id($id)
    {
        $stmt = $this->pdo->prepare(
            "select u.cpf, u.nome_completo, u.mail mail_usuario, sc.* 
                                    from suporte_candidato sc
                                    inner join usuario u on u.id = sc.id_usuario_remetente
                                    where sc.id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Suporte">
    public function get_suporte()
    {

        $stmt = $this->pdo->prepare("select suporte.*, u.posto_grad, u.nome_guerra
                                    from suporte
                                    left join usuario u on u.id = suporte.usuario_resposta
                                    where suporte.id_selecao = :selecao");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Logs">
    public function get_logs_operadores($limite)
    {
        if ($limite > 0)
            $stmt = $this->pdo->prepare("select log.*
                                        from log
                                        inner join usuario u on u.id = log.id_usuario
                                        where u.candidato = 0 and log.id_selecao = :selecao order by log.id desc limit $limite");
        else
            $stmt = $this->pdo->prepare("select log.*
                                        from log
                                        inner join usuario u on u.id = log.id_usuario
                                        where u.candidato = 0 and log.id_selecao = :selecao order by log.id desc ");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);


        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Logs Avancado">
    public function get_logs_avancado($cpf, $id_usuario)
    {
        $stmt = $this->pdo->prepare("select *
                                        from log
                                        where (cpf = :cpf and id_selecao = :selecao)
                                        or alteracao like '%" . $cpf . "%'
                                        or alteracao_detalhada like '%" . $cpf . "%'
                                        or id in (select id from log where id_alterado = :id_usuario and tabela = 'usuario')
                                        or id in (select id from log where id_usuario = :id_usuario and tabela = 'usuario')");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':id_usuario', $id_usuario);


        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Logs Avancado">
    public function get_logs_codigo($codigo, $limite)
    {
        $stmt = $this->pdo->prepare("select log.*
                                    from log
                                    inner join usuario u on u.id = log.id_usuario
                                    where log.id_selecao = :selecao and log.codigo = :codigo order by log.id desc limit $limite");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':codigo', $codigo);

        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Acessos ao Candidato">
    public function get_acessos_candidato($id)
    {
        $stmt = $this->pdo->prepare("SELECT a.id_usuario id_acessou, a.cpf cpf_acessou, count(*) quantidade_acessos,
                                    a.id_visualizado, u.cpf cpf_candidato, u.nome_completo nome_candidato, foto.nome foto
                                    FROM acesso_pagina a
                                    inner join usuario u on u.id = id_visualizado
                                    inner join foto on foto.id_usuario = id_visualizado
                                    where a.id_usuario = :id_usuario
                                    and a.codigo = 19101
                                    and u.candidato = 1
                                    and foto.apagado = 0
                                    group by a.id_visualizado");

        $stmt->bindValue(':id_usuario', $id);


        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Logs Usuário">
    public function get_logs_usuario($id)
    {
        $stmt = $this->pdo->prepare("select * from log where id_usuario = :id_usuario");

        $stmt->bindValue(':id_usuario', $id);


        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades">
    public function get_especialidade()
    {
        $stmt = $this->pdo->prepare(
            "
                    select e.*
                    from especialidade e
                    where e.apagado = 0 and id_selecao = :selecao order by e.ott_stt, e.nome"
        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_inscritos_eipot_rm_inscricao($rm_usuario)
    {

        $stmt = $this->pdo->prepare(
            " SELECT u.*
                    FROM usuario u
                    INNER JOIN candidato_x_especialidade AS ce WHERE ce.id_candidato = u.id
                    AND u.apagado = 0 
                    AND u.id_selecao = :id_selecao
                    AND u.rm_inscricao = :rm_inscricao"
        );

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_armas_eipot_por_selecao($id_selecao)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM especialidade WHERE id_selecao = :id_selecao");
        $stmt->bindValue(':id_selecao', $id_selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_inscritos_eipot_tabelas($rm_usuario)
    {
        $stmt = $this->pdo->prepare("
           SELECT  u.*, 
            u.id AS id_usuario, 
            ce.id AS id_candidato_especialidade, 
            e.nome AS arma_especialidade
            FROM usuario u
            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
            INNER JOIN especialidade e ON ce.id_especialidade = e.id
            WHERE u.apagado = 0 
            AND u.id_selecao = :selecao
            AND (
                FIND_IN_SET(:rm_usuario, u.rm_destino) 
                OR FIND_IN_SET(:rm_usuario, u.rm_inscricao)
            )
            AND u.concorrendo = 1
            ORDER BY u.nome_completo ASC;

        ");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }

    public function relatorio_inscritos_eipot_tabelas_ampla_concorrencia($rm_usuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, ce.id, e.nome AS arma_especialidade
            FROM usuario u
            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
            INNER JOIN especialidade e ON ce.id_especialidade = e.id
            WHERE u.apagado = 0 
            AND u.id_selecao = :selecao
            AND FIND_IN_SET(:rm_usuario, u.rm_destino)
            OR FIND_IN_SET(:rm_usuario, u.rm_inscricao)
           #AND rm_inscricao = :rm_usuario
            AND u.concorrendo = 1
            ORDER BY u.nome_completo ASC;
        ");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }


    public function relatorio_inscritos_eipot_tabelas($rm_usuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, ce.id, e.nome AS arma_especialidade
            FROM usuario u
            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
            INNER JOIN especialidade e ON ce.id_especialidade = e.id
            WHERE u.apagado = 0 
            AND u.id_selecao = :selecao
          # AND FIND_IN_SET(:rm_usuario, u.rm_inscricao)
           # OR FIND_IN_SET(:rm_usuario, u.rm_inscricao)
           AND rm_inscricao = :rm_usuario
            AND u.concorrendo = 1
            ORDER BY u.nome_completo ASC;
        ");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }

    // lista cadidatos com recurso 31MAR25
    public function get_candidatos_recurso($rm_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT u.nome_completo, u.cpf, ce.*, u.rm_inscricao, e.nome AS arma_especialidade, r.*  FROM siscant.recurso r
                                            INNER JOIN usuario u ON u.id = r.id_candidato 
                                            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
                                            INNER JOIN especialidade e ON ce.id_especialidade = e.id
                                            WHERE r.apagado = 0 
                                            AND u.id_selecao = :selecao
                                            AND u.rm_inscricao = :rm_usuario
                                            AND u.concorrendo = 1
                                            ORDER BY u.nome_completo ASC;");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }

    public function get_candidatos_isgrec($rm_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT u.nome_completo, u.apto_saude_recurso, u.cpf, ce.*, u.rm_inscricao, e.nome AS arma_especialidade, r.*  FROM siscant.recurso r
                                            INNER JOIN usuario u ON u.id = r.id_candidato 
                                            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
                                            INNER JOIN especialidade e ON ce.id_especialidade = e.id
                                            WHERE r.apagado = 0 
                                            AND r.etapa = 3
                                            AND r.obs_etapa = '3 - IS'
                                            AND r.status_final = 'deferido'
                                            AND u.id_selecao = :selecao
                                            AND u.rm_inscricao = :rm_usuario
                                            ORDER BY u.nome_completo ASC;");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }

    //12/06/2025 Adicionando o id do usuário no retorno
    public function get_inscritos_eipot_vagas_reservadas_tabelas($rm_usuario)
    {
        $stmt = $this->pdo->prepare("
         SELECT 
            u.*, 
            u.id AS usuario_id, 
            ce.id AS id_candidato_especialidade, 
            e.nome AS arma_especialidade
            FROM usuario u
            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
            INNER JOIN especialidade e ON ce.id_especialidade = e.id
            WHERE u.apagado = 0 
            AND u.id_selecao = :selecao
            AND (
                FIND_IN_SET(:rm_usuario, u.rm_destino) 
                OR FIND_IN_SET(:rm_usuario, u.rm_inscricao)
            )
            AND u.concorrendo = 1
            AND u.vaga_reservada = 1
            ORDER BY u.nome_completo ASC;
           
        ");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }


    public function relatorio_inscritos_eipot_vagas_reservadas_tabelas($rm_usuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, ce.id, e.nome AS arma_especialidade
            FROM usuario u
            INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
            INNER JOIN especialidade e ON ce.id_especialidade = e.id
            WHERE u.apagado = 0 
            AND u.id_selecao = :selecao
            AND FIND_IN_SET(:rm_usuario, u.rm_destino)
            OR FIND_IN_SET(:rm_usuario, u.rm_inscricao)
            AND u.concorrendo = 1
            AND vaga_reservada = 1;
            ORDER BY u.nome_completo ASC
           
        ");

        $stmt->bindValue(':rm_usuario', $rm_usuario, PDO::PARAM_INT); // Especificando o tipo
        $stmt->bindValue(':selecao', $_SESSION['selecao'], PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        if ($run) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Lidar com o erro se necessário
            return [];
        }
    }


    public function rm_usuario($id_usuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT o.rm FROM usuario u  
            INNER JOIN om o ON o.id = u.id_om
            WHERE u.id = :id_usuario;
        ");

        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT); // Especificando o tipo
        $run = $stmt->execute();

        // Verifica se há resultados
        if ($run) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['rm'] : 0; // Retorna o rm como inteiro ou 0 se não houver resultados
        } else {
            // Lidar com o erro se necessário
            return 0; // Retorna 0 em caso de erro
        }
    }




    public function get_especialidade_selecionadas($id)
    {

        $stmt = $this->pdo->prepare(
            "
                    SELECT e.*
                    FROM especialidade e
                    WHERE e.apagado = 0
                    AND id_selecao = :selecao
                    AND id = :id
                    ORDER BY e.ott_stt, e.nome;"
        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades Seleção">
    public function get_especialidade_selecao($selecao)
    {

        if ($selecao == null) $selecao = $_SESSION['selecao'];

        $stmt = $this->pdo->prepare(
            "
                    select e.*
                    from especialidade e
                    where e.apagado = 0 and id_selecao = :selecao order by e.ott_stt, e.nome"
        );
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades Apagadas">
    public function get_especialidade_apagadas()
    {
        $stmt = $this->pdo->prepare(
            "
                    select e.id, e.nome, e.teste_pratico, e.ott_stt
                    from especialidade e
                    where e.apagado = 1 and id_selecao = :selecao"
        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get OM ID">
    public function get_om_id($id)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from om
                    where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Cidade ID">
    public function get_cidade_id($id)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from cidade
                    where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get OMs">
    public function get_oms($rm)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from om
                    where apagado = 0 and rm = :rm order by nome"
        );
        $stmt->bindValue(':rm', $rm);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_all_oms()
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from om
                    where apagado = 0 order by nome"
        );
        //     $stmt->bindValue(':rm', $rm);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades ID">
    public function get_especialidade_id($id)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from especialidade 
                    where id= :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidade_Curriculo ID">
    public function get_especialidade_curriculo($id)
    {
        $stmt = $this->pdo->prepare(
            "
                    select ec.id, e.id id_especialidade, ec.label
                    from especialidade_curriculo  ec
                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                    inner join especialidade e on e.id = ce.id_especialidade
                    where ec.id= :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verifica se candudato já colocou currículo">
    public function verifica_se_candidato_ja_colocou_curriculo($id_candidato_x_especialidade, $id_curriculo)
    {
        $stmt = $this->pdo->prepare(
            "
                    select c.carga_horaria_obrigatoria, ec.* 
                    from especialidade_curriculo ec
                    inner join curriculo c on c.id = ec.id_curriculo
                    where ec.id_candidato_x_especialidade = :id_candidato_x_especialidade
                    and ec.id_curriculo = :id_curriculo
                    and ec.apagado = 0"
        );
        $stmt->bindValue(':id_candidato_x_especialidade', $id_candidato_x_especialidade);
        $stmt->bindValue(':id_curriculo', $id_curriculo);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Arquivos de experiência profissional">
    public function arquivos_experiencia_profissional_especialidade($id_candidato_x_especialidade)
    {
        $stmt = $this->pdo->prepare(
            "
                    select c.carga_horaria_obrigatoria,  ec.* 
                    from especialidade_curriculo ec
                    inner join curriculo c on c.id = ec.id_curriculo
                    where ec.id_candidato_x_especialidade = :id_candidato_x_especialidade
                    and ec.apagado = 0
                    and c.carga_horaria_obrigatoria = 1"
        );
        $stmt->bindValue(':id_candidato_x_especialidade', $id_candidato_x_especialidade);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verifica Usuário apaga Prioridade">
    public function verifica_prioridade_cidade_usuario($id_prioridade_cidade, $id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select ce.id_candidato, ce.id_especialidade, pc.prioridade, pc.apagado
                    from prioridade_cidade pc
                    inner join candidato_x_especialidade ce on ce.id = pc.id_candidato_x_especialidade
                    where ce.id_candidato = :id_usuario and pc.id = :id_prioridade_cidade and pc.apagado = 0"
        );
        $stmt->bindValue(':id_prioridade_cidade', $id_prioridade_cidade);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Arquivo Pagamento ID">
    public function get_arquivo_pagamento_id($id_arquivo)
    {
        $stmt = $this->pdo->prepare(
            "
                    select * from pagamento_inscricao where id = :id"
        );
        $stmt->bindValue(':id', $id_arquivo);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Arquivo Pagamento">
    public function get_arquivo_pagamento($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select * from pagamento_inscricao where id_candidato = :id and apagado = 0"
        );
        $stmt->bindValue(':id', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatórios ID">
    public function get_documentos_obrigatorios_id($id)
    {
        //$id = $_SESSION['id_usuario'];
        $stmt = $this->pdo->prepare(
            "
                    select * from documento_obrigatorio where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentação Obrigatória ID">
    public function get_documentacao_obrigatoria($id)
    {
        //$id = $_SESSION['id_usuario'];
        $stmt = $this->pdo->prepare(
            "
                    select * from documentacao_obrigatoria where id = :id"
        );
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades OTT STT">
    public function get_especialidade_ott_stt($ott_stt)
    {
        $stmt = $this->pdo->prepare("
                                        select e.id, e.nome, e.teste_pratico, e.ott_stt
                                        from especialidade e
                                        where e.apagado = 0 and ott_stt = :ott_stt and id_selecao = :selecao
                                        and e.id not in 
                                        (select id_especialidade from candidato_x_especialidade
                                        where id_candidato = :id_candidato and apagado = 0)
                                        order by nome
                                        ");

        $stmt->bindValue(':ott_stt', $ott_stt);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':id_candidato', $_SESSION['id_usuario']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade de Cidades">
    public function get_quantidade_cidades_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("
                                    select count(c.id)quantidade
                                    from 
                                    cidade c
                                    inner join cidade_x_especialidade ce on ce.id_cidade = c.id
                                    where ce.id_especialidade = :id_especialidade and ce.apagado = 0");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Cidades da Especialidade">

    // 11/07/2025 -> Iago Silva Incluindo o campo regiao_militar na Query
    public function get_cidades_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            c.id, 
            c.nome, 
            c.uf,                          
            sel.id AS id_selecao, 
            ce.numero_vagas, 
            ce.regiao_militar,
            e.nome AS nome_especialidade
        FROM cidade c
        INNER JOIN cidade_x_especialidade ce ON ce.id_cidade = c.id
        INNER JOIN especialidade e ON e.id = ce.id_especialidade
        INNER JOIN selecao sel ON sel.id = e.id_selecao
        WHERE ce.id_especialidade = :id_especialidade 
          AND ce.apagado = 0
    ");

        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_oms_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("SELECT 
                                                    ome.numero_vagas, ome.id_om, ome.id_especialidade, ome.id, om.nome AS nome_om, e.nome AS nome_especialidade
                                                FROM 
                                                    om_x_especialidade ome
                                                INNER JOIN 
                                                    om ON om.id = ome.id_om
                                                INNER JOIN 
                                                    especialidade e ON e.id = ome.id_especialidade AND ome.apagado = 0
                                                AND ome.id_especialidade = :id_especialidade");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_vagas_oms_especialidade_eipot($id_especialidade)
    {
        /* $stmt = $this->pdo->prepare("SELECT 
                                                    ome.numero_vagas, ome.id_om, ome.id_especialidade, ome.id, ome.numero_vagas, om.nome AS nome_om, e.nome AS nome_especialidade
                                                FROM 
                                                    om_x_especialidade ome
                                                INNER JOIN 
                                                    om ON om.id = ome.id_om
                                                INNER JOIN 
                                                    especialidade e ON e.id = ome.id_especialidade AND ome.apagado = 0
                                                AND ome.id_especialidade = :id_especialidade"); */

        $stmt = $this->pdo->prepare("SELECT * FROM siscant.om_x_especialidade ome 
                                    INNER JOIN om ON ome.id_om = om.id
                                    WHERE ome.id_especialidade = :id_especialidade");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Cidades da Especialidade por cidade">
    public function get_cidades_especialidade_por_cidade($id_especialidade, $id_cidade)
    {
        $stmt = $this->pdo->prepare("
                                    select c.id, c.nome, sel.id id_selecao, ce.numero_vagas, e.nome nome_especialidade
                                    from cidade c
                                    inner join cidade_x_especialidade ce on ce.id_cidade = c.id
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join selecao sel on sel.id = e.id_selecao
                                    where ce.id_especialidade = :id_especialidade and ce.id_cidade = :id_cidade and ce.apagado = 0");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':id_cidade', $id_cidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function get_especialidade_por_oms($id_especialidade, $id_om, $id_selecao)
    {
        $stmt = $this->pdo->prepare("
                                   SELECT om.id, om.nome AS nome_om, e.nome AS nome_especialidade, e.id FROM om
                                    INNER JOIN especialidade e WHERE id_selecao = :id_selecao ");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':id_om', $id_om);
        $stmt->bindValue(':id_selecao', $id_selecao);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades Usuário avaliador">
    public function get_especialidades_usuario_avaliador($id_usuario)
    {
        $stmt = $this->pdo->prepare("
                                    select a.id, a.id_especialidade, e.nome, e.ott_stt
                                    from avaliador a
                                    inner join especialidade e on e.id = a.id_especialidade
                                    where
                                    a.apagado = 0 and a.id_usuario = :id_usuario and e.apagado = 0");
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Prioridade Cidade ID">
    public function get_prioridade_cidade_id($id)
    {
        $stmt = $this->pdo->prepare("
                                    select e.id id_especialidade, pc.id_candidato_x_especialidade, e.nome nome_especialidade, e.ott_stt, c.nome nome_cidade, pc. prioridade
                                    from prioridade_cidade pc
                                    inner join cidade c on c.id  = pc.id_cidade
                                    inner join candidato_x_especialidade ce on ce.id = pc.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    where pc.id = :id");
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>  

    // <editor-fold defaultstate="collapsed" desc="Get Prioridade da Especialidade Candidato">
    public function get_prioridade_especialidade_candidato($id_candidato_x_especialidade)
    {
        $stmt = $this->pdo->prepare("
                                    select pc.id id_prioridade_cidade, c.id id_cidade, c.nome, pc.prioridade
                                    from prioridade_cidade pc
                                    inner join cidade c on c.id  = pc.id_cidade
                                    where pc.id_candidato_x_especialidade = :id_candidato_x_especialidade
                                    and pc.apagado = 0
                                    order by pc.prioridade");
        $stmt->bindValue(':id_candidato_x_especialidade', $id_candidato_x_especialidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Get Cidades da Especialidade Candidato">
    public function get_cidades_especialidade_candidato($id_especialidade, $id_candidato_x_especialidade)
    {
        $stmt = $this->pdo->prepare("
                                    select c.id, c.nome
                                    from 
                                    cidade c
                                    inner join cidade_x_especialidade ce on ce.id_cidade = c.id
                                    where ce.id_especialidade = :id_especialidade and ce.apagado = 0 
                                    and
                                    c.id not in
                                    (
                                            select id_cidade
                                            from prioridade_cidade
                                            where id_candidato_x_especialidade = :id_candidato_x_especialidade
                                            and apagado = 0 
                                    )");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':id_candidato_x_especialidade', $id_candidato_x_especialidade);
        $run = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Participantes do Processo">
    public function get_quantidade_candidatos_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select count(u.id) quantidade_candidatos
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and e.apagado = 0
                                    and u.concorrendo = 1
                                    and u.apagado = 0
                                    and u.medico_obrigatorio is null
                                    and u.id_selecao = :selecao
                                    and e.id = :id_especialidade
                                    order by e.ott_stt, e.nome");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade concorrendo">
    public function get_candidatos_especialidade_concorrendo($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select u.* 
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and u.id_selecao = :selecao
                                    and u.concorrendo = 1
                                    and u.apagado = 0
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade concorrendo">
    public function get_candidatos_especialidade_concorrendo_nao_concorrendo($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select u.* , ce.concorrendo as concorrendo_especialidade, ce.justificativa as justificativa_especialiade
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and u.id_selecao = :selecao
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade do Processo">

    // 21/05/2025 - Adicionando o campo etapa criado na tabela candidato_x_especialidade e alterando o nome atribuido a etapa do candidato
    public function get_candidatos_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            u.*, 
            u.etapa AS etapa_candidato,
            c.nome AS cidade_escolheu_servir, 
            ce.id AS id_ce, 
            ce.nota_prova_teorico_pratico,
            ce.etapa AS etapa,
            ce.rm_escolheu_servir
        FROM candidato_x_especialidade ce
        INNER JOIN usuario u ON u.id = ce.id_candidato
        LEFT JOIN cidade c ON c.id = ce.cidade_escolheu_servir
        WHERE ce.id_especialidade = :id_especialidade
            AND ce.apagado = 0
            AND ce.concorrendo = 1
            AND u.apagado = 0
            AND u.concorrendo = 1
            AND u.id_selecao = :selecao
        ORDER BY u.nome_completo
    ");

        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_candidatos_especialidade_eipot($id_especialidade, $rm_usuario)
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_escolheu_servir, ce.id id_ce, ce.nota_prova_teorico_pratico
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    left join cidade c on c.id = ce.cidade_escolheu_servir
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and u.id_selecao = :selecao
                                    and rm_inscricao = :rm_usuario
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':rm_usuario', $rm_usuario);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos desclassificados Especialidade do Processo">
    public function get_candidatos_desclassificados_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_escolheu_servir, ce.id id_ce, ce.justificativa justificativa_ce, ce.nota_prova_teorico_pratico
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    left join cidade c on c.id = ce.cidade_escolheu_servir
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and ce.concorrendo = 0
                                    and u.apagado = 0
                                    and u.id_selecao = :selecao
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade do Processo Desclassificados">
    public function get_candidatos_especialidade_desclassificados($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_escolheu_servir, cid.nome cidade_distribuicao, ce.concorrendo especialidade_concorrendo, 
                                    ce.justificativa justificativa_especialidade, om_dist.abreviatura om_dist_abreviatura, 
                                    esp.nome nome_especialidade, esp.ott_stt ott_stt_especializacao, 
                                    cid_1_fase.nome nome_cidade_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    left join cidade c on c.id = ce.cidade_escolheu_servir
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om om_dist on om_dist.id = u.om_distribuicao
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and u.apagado = 0
                                    and u.id_selecao = :selecao
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade do Processo Desclassificados">
    public function get_candidatos_especialidade_desclassificados_nao_med_obr($id_especialidade)
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_escolheu_servir, cid.nome cidade_distribuicao, ce.concorrendo especialidade_concorrendo, 
                                    ce.justificativa justificativa_especialidade, om_dist.abreviatura om_dist_abreviatura, 
                                    esp.nome nome_especialidade, esp.ott_stt ott_stt_especializacao, 
                                    cid_1_fase.nome nome_cidade_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase
                                    from candidato_x_especialidade ce
                                    inner join usuario u on u.id = ce.id_candidato
                                    left join cidade c on c.id = ce.cidade_escolheu_servir
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om om_dist on om_dist.id = u.om_distribuicao
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    where ce.id_especialidade = :id_especialidade
                                    and ce.apagado = 0
                                    and u.apagado = 0
                                    and u.id_selecao = :selecao
                                    and u.medico_obrigatorio is null
                                    order by u.nome_completo");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Especialidade do Processo Desclassificados">
    public function get_candidatos_distribuicao($selecao, $somente_med_obr)
    {
        if ($selecao == null) $selecao = $_SESSION['selecao'];

        if ($somente_med_obr) {
            $stmt = $this->pdo->prepare("select u.*, cid.nome cidade_distribuicao, om_dist.abreviatura om_dist_abreviatura, 
                                    esp.nome nome_especialidade, esp.ott_stt ott_stt_especializacao, 
                                    cid_1_fase.nome nome_cidade_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase
                                    from usuario u
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om om_dist on om_dist.id = u.om_distribuicao
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    where 
                                    u.incorporado = 1 and 
                                    u.medico_obrigatorio = 1
                                    order by u.nome_completo");
        } else {
            $stmt = $this->pdo->prepare("select u.*, cid.nome cidade_distribuicao, om_dist.abreviatura om_dist_abreviatura, 
                                    esp.nome nome_especialidade, esp.ott_stt ott_stt_especializacao, 
                                    cid_1_fase.nome nome_cidade_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase
                                    from usuario u
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om om_dist on om_dist.id = u.om_distribuicao
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    where 
                                    u.incorporado = 1 and 
                                    u.medico_obrigatorio is null and 
                                    u.id_selecao = :selecao
                                    order by u.nome_completo");
        }
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Docs não avaliados">
    public function get_lista_docs_avaliados_por_especialidade()
    {
        $stmt = $this->pdo->prepare("
                                    select * from (

                                    select * -- tab1.id_tab1, tab1.nome, tab1.quantidade_nao_avaliado, tab2.quantidade_avaliado 
                                    from 

                                    (select e.id id_tab1, CONCAT(UPPER(e.ott_stt), ' - ', e.nome) nome_tab1  , count(e.id) quantidade_nao_avaliado
                                    from especialidade_curriculo ec
                                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where 
                                    ec.valido is null
                                    and ec.apagado = 0
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and e.apagado = 0
                                    and e.id_selecao = :selecao
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.concorrendo = 1
                                    group by e.id
                                    order by e.id desc)tab1

                                    left join

                                    (select e.id id_tab2, CONCAT(UPPER(e.ott_stt), ' - ', e.nome) nome_tab2, count(e.id) quantidade_avaliado
                                    from especialidade_curriculo ec
                                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where 
                                    ec.valido is not null
                                    and ec.apagado = 0
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and e.apagado = 0
                                    and e.id_selecao = :selecao
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.concorrendo = 1
                                    group by e.id
                                    order by e.id desc) tab2

                                    on tab2.id_tab2 = tab1.id_tab1

                                    union


                                    select *   from 

                                    (select e.id id_tab1, CONCAT(UPPER(e.ott_stt), ' - ', e.nome) nome_tab1, count(e.id) quantidade_nao_avaliado
                                    from especialidade_curriculo ec
                                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where 
                                    ec.valido is null
                                    and ec.apagado = 0
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and e.apagado = 0
                                    and e.id_selecao = :selecao
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.concorrendo = 1
                                    group by e.id
                                    order by e.id desc)tab1

                                    right join

                                    (select e.id id_tab2, CONCAT(UPPER(e.ott_stt), ' - ', e.nome) nome_tab2, count(e.id) quantidade_avaliado
                                    from especialidade_curriculo ec
                                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where 
                                    ec.valido is not null
                                    and ec.apagado = 0
                                    and ce.apagado = 0
                                    and ce.concorrendo = 1
                                    and e.apagado = 0
                                    and e.id_selecao = :selecao
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.concorrendo = 1
                                    group by e.id
                                    order by e.id desc) tab2

                                    on tab2.id_tab2 = tab1.id_tab1

                                    )tabela
                                    order by quantidade_nao_avaliado desc
");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get multiplicadores maiores que 1">
    public function get_multiplicadores_curriculo_maior_1()
    {
        $stmt = $this->pdo->prepare("select ec.data_avaliacao, u.id, u.cpf, e.nome especialidade, c.nome, ec.multiplicador
                                    from especialidade_curriculo ec
                                    inner join candidato_x_especialidade ce on ce.id = ec.id_candidato_x_especialidade
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    inner join curriculo c on c.id = ec.id_curriculo
                                    where ec.apagado = 0
                                    and ce.apagado = 0
                                    and e.apagado = 0
                                    and e.id_selecao = :selecao
                                    and u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.concorrendo = 1
                                    and ec.multiplicador > 1
                                    and ec.valido = 1
                                    order by ec._data_ultima_atualizacao asc
");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuários com mais de uma especialidade cadastrada">
    public function get_candidatos_mais_uma_especialidade()
    {
        $stmt = $this->pdo->prepare("select count(u.id) quantidade_especialidades, u.id,u.cpf, u.nome_completo
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.apagado = 0
                                    and e.apagado = 0
                                    and u.id_selecao = :selecao
                                    group by u.id
                                    having count(u.id) > 1
                                    ");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuários com mais de uma especialidade cadastrada">
    public function get_candidatos_mais_uma_especialidade_id_usuario($id_usuario)
    {
        $stmt = $this->pdo->prepare("select count(u.id) quantidade_especialidades, u.id,u.cpf, u.nome_completo
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0
                                    and u.concorrendo = 1
                                    and ce.apagado = 0
                                    and e.apagado = 0
                                    and ce.concorrendo = 1
                                    and u.id_selecao = :selecao
                                    and u.id = :id_usuario
                                    group by u.id
                                    having count(u.id) > 1
                                    ");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verificação se foi avaliado em especialidade">
    public function get_avaliado_especialidade($id_usuario, $id_especialidade)
    {
        $stmt = $this->pdo->prepare("select ec.id id_especialidade_curriculo,u.cpf, ec.label, ec.data_inicio, ec.data_termino, ec.carga_horaria, 
                    c.pontuacao, ec.valido, ec.justificativa, ec.nome
                    from candidato_x_especialidade ce
                    inner join especialidade_curriculo ec on ce.id = ec.id_candidato_x_especialidade
                    inner join curriculo c on c.id = ec.id_curriculo
                    inner join usuario u on u.id = ce.id_candidato
                    where ce.id_especialidade = :id_especialidade
                    and ce.apagado = 0
                    and ec.apagado = 0
                    and u.apagado = 0
                    and u.id_selecao = :selecao
                    and u.id = :id_usuario");

        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get pontuação avaliada">
    public function get_pontuacao_avaliada($id_usuario, $id_especialidade)
    {
        $stmt = $this->pdo->prepare("select sum((c.pontuacao * ec.multiplicador)/1000) pontuacao_avaliada
                    from candidato_x_especialidade ce
                    inner join especialidade_curriculo ec on ce.id = ec.id_candidato_x_especialidade
                    inner join curriculo c on c.id = ec.id_curriculo
                    inner join usuario u on u.id = ce.id_candidato
                    where ce.id_especialidade = :id_especialidade
                    and ec.valido = 1
                    and ce.apagado = 0
                    and ec.apagado = 0
					and c.apagado = 0
                    and u.apagado = 0
                    and u.id_selecao = :selecao
                    and u.id = :id_usuario");

        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);

        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos DESCLASSIFICADOS do Processo">
    public function get_candidatos_desclassificados()
    {
        $stmt = $this->pdo->prepare("select * from usuario where perfil = 'candidato' and candidato = 1 and concorrendo = 0 and apagado = 0 and id_selecao = :selecao order by nome_completo");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // 02ABRIL2025 lista candidatos desclassificados por rm
    public function get_candidatos_desclassificados_rm($rm_usuario, $id_selecao)
    {

        $stmt = $this->pdo->prepare("select * from usuario where perfil = 'candidato' and candidato = 1 and concorrendo = 0 and apagado = 0 and rm_inscricao = :rm_usuario and id_selecao = :id_selecao order by nome_completo");
        $stmt->bindValue(':id_selecao', $id_selecao);
        $stmt->bindValue(':rm_usuario', $rm_usuario);


        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos DESCLASSIFICADOS do Processo">
    public function get_arquivos_candidato($id_candidato)
    {
        $stmt = $this->pdo->prepare("select * from arquivo where id_usuario = :id_usuario and apagado = 0");
        $stmt->bindValue(':id_usuario', $id_candidato);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos DESCLASSIFICADOS do Processo">
    public function get_arquivos_candidato_id($id_arquivo)
    {
        $stmt = $this->pdo->prepare("select * from arquivo where id = :id_arquivo");
        $stmt->bindValue(':id_arquivo', $id_arquivo);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos DESCLASSIFICADOS e CLASSIFICADOS do Processo">
    public function get_candidatos_desclassificados_classificados()
    {
        $stmt = $this->pdo->prepare("select * from usuario where perfil = 'candidato' and candidato = 1 and apagado = 0 and id_selecao = :selecao order by nome_completo");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos adicionaram arquivo de isento mas não são isentos">
    public function get_candidatos_disseram_isentos_mas_nao_sao()
    {
        $stmt = $this->pdo->prepare(
            "select u.id, u.concorrendo, u.cpf, u.medico_obrigatorio, u.justificativa_concorrendo, pi.isento arquivo_isento, u.isento_pagamento usuario_isento 
                from pagamento_inscricao pi
                inner join usuario u on u.id = pi.id_candidato
                where pi.isento = 1 
                and pi.apagado = 0
                and u.isento_pagamento = 0
                and u.apagado = 0
                and u.id_selecao = :selecao"
        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get get_candidatos_pagaram_e_nao_estao_tabela_gru_pagas">
    public function get_candidatos_pagaram_e_nao_estao_tabela_gru_pagas($valor_gru)
    {
        $stmt = $this->pdo->prepare(

            "select u.id, u.concorrendo, u.medico_obrigatorio, u.justificativa_concorrendo, u.cpf, u.nome_completo, pi.nome
                from pagamento_inscricao pi
                inner join usuario u on u.id = pi.id_candidato
                where pi.apagado = 0 
                and pi.isento = 0
                and u.id_selecao = :selecao
                and (u.isento_pagamento != 1 or u.isento_pagamento is null) 
                and u.cpf not in 
                (
                    select cpf
                    from gru_pagas
                    where id_selecao = :selecao
                    and apagado = 0
                    group by cpf
                    having sum(valor) >= :valor_gru
                )"

        );
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':valor_gru', $valor_gru);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GRUs PAGAS">
    public function get_gru_pagas()
    {
        $stmt = $this->pdo->prepare("select * from gru_pagas where id_selecao = :selecao and apagado = 0");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get valor Total GRUs Pagas">
    public function get_valor_tatal_gru_pagas()
    {
        $stmt = $this->pdo->prepare("select sum(valor) valor_total from gru_pagas where id_selecao = :selecao and apagado = 0");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GRUs PAGAS CPF DUPLICADO">
    public function get_gru_pagas_cpf_duplicado()
    {
        $stmt = $this->pdo->prepare("select cpf, count(cpf) conta_cpf, sum(valor) soma_total
                                    from gru_pagas
                                    where id_selecao = :selecao
                                    and apagado = 0
                                    group by cpf
                                    having conta_cpf > 1");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get ATA DIA EXAME MÉDICO">
    public function get_ata_dia_exame_medico($data_ata)
    {
        $stmt = $this->pdo->prepare("select * from usuario 
                                    where perfil = 'candidato' and candidato = 1 
                                    and concorrendo = 1 and apagado = 0 
                                    and (data_exame_saude = :data_ata or data_exame_saude_recurso = :data_ata)
                                    and medico_obrigatorio is null
                                    and id_selecao = :selecao order by nome_completo");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':data_ata', "$data_ata", PDO::PARAM_STR);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get ATA DIA EXAME MÉDICO Para Médicos Obrigatórios">
    public function get_ata_dia_exame_medico_para_medicos_obrigatorios($data_ata)
    {
        $stmt = $this->pdo->prepare("select * from usuario 
                                    where medico_obrigatorio = 1 and apagado = 0 
                                    and data_exame_saude = :data_ata
                                    order by nome_completo");

        $stmt->bindValue(':data_ata', "$data_ata", PDO::PARAM_STR);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Médicos Obrigatórios">
    public function get_medicos_obrigatorios()
    {
        $stmt = $this->pdo->prepare("select sel.rm, u.*, om.nome nome_om_distribuicao, c.nome cidade_endereco, cid.nome cidade_distribuicao,om.abreviatura, esp.nome nome_especialidade, esp.ott_stt,
                                    om_1_fase.nome nome_om_1_fase, om_distribuicao.abreviatura om_distribuicao_abreviatura, om_1_fase.abreviatura abreviatura_om_1_fase, cid_1_fase.nome nome_cidade_1_fase
                                    from usuario u
                                    left join om on om.id = u.om_distribuicao
                                    left join cidade c on c.id = u.id_cidade
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join om om_distribuicao on om_distribuicao.id = u.om_distribuicao
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    inner join selecao sel on sel.id = u.id_selecao
                                    where u.medico_obrigatorio = 1 
                                    and u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.apagado = 0 
                                    and sel.rm = :selecao_regiao");

        $stmt->bindValue(':selecao_regiao', $_SESSION['selecao_regiao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos">
    public function get_candidatos()
    {
        $stmt = $this->pdo->prepare("select * from usuario 
                                    where perfil = 'candidato' 
                                    and candidato = 1 
                                    and apagado = 0 
                                    and medico_obrigatorio is null
                                    and id_selecao = :selecao");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Concorrendo">
    // 30/06/2025 - Iago Silva Modificando a query para trazer junto o id da especialidade
    public function get_candidatos_concorrendo()
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            u.*, 
            GROUP_CONCAT(e.nome SEPARATOR ', ') AS especialidades,
            GROUP_CONCAT(e.id SEPARATOR ',') AS ids_especialidades
        FROM usuario u
        INNER JOIN candidato_x_especialidade ce 
            ON ce.id_candidato = u.id 
            AND ce.apagado = 0 
            AND ce.concorrendo = 1
        INNER JOIN especialidade e 
            ON e.id = ce.id_especialidade 
            AND e.apagado = 0
        WHERE u.perfil = 'candidato' 
            AND u.candidato = 1 
            AND u.concorrendo = 1 
            AND u.apagado = 0 
            AND u.medico_obrigatorio IS NULL 
            AND u.id_selecao = :selecao
        GROUP BY u.id
    ");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function get_candidatos_concorrendo_eipot($rm_usuario)
    {
        $stmt = $this->pdo->prepare("select * from usuario 
                                    where perfil = 'candidato' 
                                    and candidato = 1 
                                    and concorrendo = 1 
                                    and apagado = 0 
                                    and medico_obrigatorio is null
                                    and id_selecao = :selecao
                                    and rm_inscricao = :rm_usuario");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':rm_usuario', $rm_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // 11/06/2025 -> Iago Silva Trazendo todos candidatos EIPOT independente da RM
    // 15/07/2025 -> Iago Silva Adicionando o campo cidade_escolheu_servir 
    public function get_candidatos_eipot()
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            u.*, 
            u.id AS id_usuario, 
            ce.id AS id_candidato_especialidade, 
            ce.cidade_escolheu_servir,
            c.nome AS nome_cidade_escolhida,
            e.nome AS arma_especialidade
        FROM usuario u
        INNER JOIN candidato_x_especialidade ce ON ce.id_candidato = u.id
        INNER JOIN especialidade e ON ce.id_especialidade = e.id
        LEFT JOIN cidade c ON c.id = ce.cidade_escolheu_servir
        WHERE u.apagado = 0 
        AND u.id_selecao = :selecao
        AND u.concorrendo = 1
        ORDER BY u.nome_completo ASC
    ");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Concorrendo por OM">
    public function get_candidatos_concorrendo_om($id_om)
    {
        $stmt = $this->pdo->prepare("select e.ott_stt, e.nome nome_especialidade, u.* 
                                    from usuario u 
                                    left join especialidade e on e.id = u.especialidade_incorporacao
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.concorrendo = 1 
                                    and u.medico_obrigatorio is null
                                    and u.apagado = 0 
                                    and u.om_1_fase = :id_om
                                    ");
        $stmt->bindValue(':id_om', $id_om);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Concorrendo por OM médicos obrigatórios">
    public function get_candidatos_concorrendo_om_medicos_obr($id_om)
    {
        $stmt = $this->pdo->prepare("select e.ott_stt, e.nome nome_especialidade, u.* 
                                    from usuario u 
                                    left join especialidade e on e.id = u.especialidade_incorporacao
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.medico_obrigatorio = 1
                                    and u.apagado = 0 
                                    and u.om_1_fase = :id_om
                                    ");
        $stmt->bindValue(':id_om', $id_om);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Todos os Candidatos">
    public function get_todos_candidatos()
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_endereco, cid.nome cidade_distribuicao, om.nome nome_om, 
                                    om_1_fase.nome nome_om_1_fase, cid_1_fase.nome nome_cidade_1_fase
                                    from usuario u 
                                    left join cidade c on c.id = u.id_cidade
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om on om.id = u.om_distribuicao
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.concorrendo = 1
                                    and u.apagado = 0 
                                    and u.id_selecao = :selecao
                                    order by u.nome_completo");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos desclassificados e classificados">
    public function get_candidatos_desc_class()
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_endereco, cid.nome cidade_distribuicao, om.nome nome_om,  om.abreviatura abreviatura_om,
                                    om_1_fase.nome nome_om_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase, cid_1_fase.nome nome_cidade_1_fase,
                                    esp.nome nome_especialidade, esp.ott_stt
                                    from usuario u 
                                    left join cidade c on c.id = u.id_cidade
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om on om.id = u.om_distribuicao
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join especialidade esp on esp.id = u.especialidade_incorporacao
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.apagado = 0 
                                    and u.id_selecao = :selecao
                                    order by u.nome_completo");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos desclassificados e classificados Distribuição">
    public function get_candidatos_desc_class_distribuicao()
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_endereco, cid.nome cidade_distribuicao, om.nome nome_om, om.abreviatura abreviatura_om,
                                    om_1_fase.nome nome_om_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase, cid_1_fase.nome nome_cidade_1_fase,
                                    especialidade.nome nome_especialidade_distribuicao, especialidade.ott_stt, cid_inst_ensino.nome nome_cidade_inst_ensino
                                    from usuario u 
                                    left join cidade c on c.id = u.id_cidade
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om on om.id = u.om_distribuicao
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join especialidade on especialidade.id = u.especialidade_incorporacao
                                    left join cidade cid_inst_ensino on cid_inst_ensino.id = u.id_cidade_instituto_ensino
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1 
                                    and u.apagado = 0 
                                    and u.id_selecao = :selecao
                                    order by u.numero_distribuicao, cid_1_fase.nome, om_1_fase.abreviatura, cid.nome, om.abreviatura, u.nome_completo ");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos desclassificados e classificados Distribuição Medicos obrigatórios">
    public function get_candidatos_desc_class_distribuicao_med_obr($ano_distribuicao_medico_obrigatorio)
    {
        $stmt = $this->pdo->prepare("select u.*, c.nome cidade_endereco, cid.nome cidade_distribuicao, om.nome nome_om, om.abreviatura abreviatura_om,
                                    om_1_fase.nome nome_om_1_fase, om_1_fase.abreviatura abreviatura_om_1_fase, cid_1_fase.nome nome_cidade_1_fase,
                                    especialidade.nome nome_especialidade_distribuicao, especialidade.ott_stt, cid_inst_ensino.nome nome_cidade_inst_ensino
                                    from usuario u 
                                    left join cidade c on c.id = u.id_cidade
                                    left join cidade cid on cid.id = u.id_cidade_distribuicao
                                    left join om on om.id = u.om_distribuicao
                                    left join om om_1_fase on om_1_fase.id = u.om_1_fase
                                    left join cidade cid_1_fase on cid_1_fase.id = u.id_cidade_1_fase
                                    left join especialidade on especialidade.id = u.especialidade_incorporacao
                                    left join cidade cid_inst_ensino on cid_inst_ensino.id = u.id_cidade_instituto_ensino
                                    where u.perfil = 'candidato' 
                                    and u.candidato = 1
                                    and u.medico_obrigatorio = 1
                                    and u.ano_selecao_medico_obrigatorio = :ano_selecao
                                    and u.apagado = 0 
                                    order by u.numero_distribuicao, cid_1_fase.nome, om_1_fase.abreviatura, cid.nome, om.abreviatura, u.nome_completo ");
        $stmt->bindValue(':ano_selecao', $ano_distribuicao_medico_obrigatorio);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos Isentos">
    public function get_candidatos_isentos()
    {
        $stmt = $this->pdo->prepare("select u.id,u.nome_completo, u.mail, u.cpf, pi.isento, u.isento_pagamento, pi.nome nome_arquivo_pagamento
                                    from usuario u
                                    inner join pagamento_inscricao pi on pi.id_candidato = u.id
                                    where u.apagado = 0 and u.concorrendo = 1 and u.candidato = 1
                                    and u.id_selecao = :selecao and pi.apagado = 0");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Candidatos APAGADOS">
    public function get_candidatos_apagado()
    {
        $stmt = $this->pdo->prepare("select * from usuario where perfil = 'candidato' and candidato = 1 and apagado = 1 and id_selecao = :selecao");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuários APAGADOS">
    public function get_usuarios_apagados()
    {
        $stmt = $this->pdo->prepare("select * from usuario where perfil != 'candidato' and candidato = 0 and apagado = 1 and id_selecao = :selecao");
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuários">
    public function get_usuarios()
    {
        $stmt = $this->pdo->prepare("select u.*, om.nome nome_om
                                    from usuario u
                                    left join om on u.id_om = om.id
                                    where u.perfil != 'candidato' and u.candidato = 0 
                                    and u.apagado = 0 and u.id_selecao = :id_selecao");


        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Usuários OM">
    public function get_usuarios_perfil_om()
    {
        $stmt = $this->pdo->prepare("select u.*, om.nome nome_om, om.abreviatura abreviatura_om, s.rm, s.nome nome_selecao, s.ano selecao_ano
                                    from usuario u
                                    left join om on u.id_om = om.id
                                    left join selecao s on s.id = u.id_selecao
                                    where u.perfil = 'om' and u.candidato = 0 
                                    and s.rm = :selecao_regiao
                                    and u.apagado = 0");

        $stmt->bindValue(':selecao_regiao', $_SESSION['selecao_regiao']);

        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Curriculos acima do permitido">
    public function get_curriculos_avaliados_acima_permitido($curriculo)
    {
        $stmt = $this->pdo->prepare("select u.id id_usuario, u.cpf, u.nome_completo, e.id id_especialidade, e.nome especialidade, e.ott_stt, 
c.id id_curriculo, c.nome curriculo, u.ativa_reserva,
c.quantidade_maxima_uploads, count(ec.id_curriculo) quantidade_arquivo, 
sum(ec.multiplicador) somatorio_multiplicador, (c.pontuacao*sum(ec.multiplicador)) total_pontos_somados
from candidato_x_especialidade ce
inner join especialidade_curriculo ec on ce.id = ec.id_candidato_x_especialidade
inner join curriculo c on c.id = ec.id_curriculo
inner join usuario u on u.id = ce.id_candidato
inner join especialidade e on e.id = ce.id_especialidade
where ce.apagado = 0
and ec.apagado = 0
and u.apagado = 0
and u.id_selecao = :selecao
and ec.valido = 1
and ec.id_curriculo = :curriculo
and u.concorrendo = 1
group by ec.id_candidato_x_especialidade
order by total_pontos_somados desc");

        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $stmt->bindValue(':curriculo', $curriculo);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Especialidades Candidato">
    public function get_especialidade_candidato($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select e.teste_pratico, ce.id id_candidato_x_especialidade, ce.cidade_escolheu_servir, ce.concorrendo, ce.justificativa, ce.id_especialidade, u.nome_completo, u.cpf, 
                    e.nome especialidade, e.musica, e.ott_stt, ce.registro_conselho, ce.data_habilitacao, ce.etapa
                    from candidato_x_especialidade ce
                    inner join usuario u on u.id = ce.id_candidato
                    inner join especialidade e on e.id = ce.id_especialidade
                    where u.id = :id_usuario and ce.apagado = 0 and e.apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_especialidade_candidato_eipot($id_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT 
        ce.id AS id_candidato_x_especialidade,
        ce.cidade_escolheu_servir,
        ce.concorrendo,
        ce.justificativa,
        ce.rm_escolheu_servir,
        ce._data_ultima_atualizacao,
        u.nome_completo,
        u.cpf,
        u.rm_destino,
        e.id AS id_especialidade,
        e.nome AS especialidade,
        e.ott_stt
    FROM candidato_x_especialidade ce 
    INNER JOIN usuario u ON u.id = ce.id_candidato
    INNER JOIN especialidade e ON e.id = ce.id_especialidade
    WHERE u.id = :id_usuario AND u.apagado = 0");

        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios do Candidato">
    public function get_docs_obrigatorios_inseridos_candidato($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from documento_obrigatorio doc
                    where doc.id_candidato = :id_usuario and doc.apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Observacoes do Candidato">
    public function get_observacoes_candidato($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from observacao
                    where id_usuario = :id_usuario and apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Recursos do Candidato">
    public function get_recursos_candidato($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select r.*, e.nome nome_especialidade
                    from recurso r
                    left join especialidade e on e.id = r.id_especialidade
                    where r.id_candidato = :id_usuario and r.apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Recurso ID">
    public function get_recurso_id($id_recurso)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from recurso
                    where id = :id_recurso"
        );
        $stmt->bindValue(':id_recurso', $id_recurso);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Recurso ID">
    public function get_aditamentos_convocacao()
    {
        $stmt = $this->pdo->prepare(
            "
                    select aditamento_convocacao 
                    from usuario
                    where apagado = 0 
                    and aditamento_convocacao is not null
                    and id_selecao = :id_selecao
                    group by aditamento_convocacao
                    "

        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Recursos do Candidato">
    public function get_recursos()
    {
        $stmt = $this->pdo->prepare(
            "
                    select r.*, e.nome nome_especialidade, u.cpf, u.nome_completo, u.id_selecao, u.concorrendo
                    from recurso r
                    left join especialidade e on e.id = r.id_especialidade
                    inner join usuario u on u.id = r.id_candidato
                    where r.apagado = 0 and u.apagado = 0"
        );

        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>
    //03ABRIL2025-SILVA
    public function get_recursos_eipot($id_selecao, $rm_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select u.rm_inscricao, r.*, e.nome nome_especialidade, u.cpf, u.nome_completo, u.id_selecao, u.concorrendo
                    from recurso r
                    inner join especialidade e on e.id = r.id_especialidade
                    inner join usuario u on u.id = r.id_candidato
                    where r.apagado = 0 and u.apagado = 0
                    and u.rm_inscricao = :rm_usuario
                    and u.id_selecao = :selecao"
        );
        $stmt->bindValue(':selecao', $id_selecao);
        $stmt->bindValue(':rm_usuario', $rm_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios do Candidato">
    public function get_observacao_id($id_obs)
    {
        $stmt = $this->pdo->prepare(
            "
                    select *
                    from observacao
                    where id = :id_obs"
        );
        $stmt->bindValue(':id_obs', $id_obs);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get id_candidato_x_especialidade">
    public function get_id_candidato_x_especialidade($id_usuario, $id_especialidade)
    {
        $stmt = $this->pdo->prepare(
            "
                                    select * from 
                                    candidato_x_especialidade
                                    where id_candidato = :id_usuario 
                                    and id_especialidade = :id_especialidade
                                    and apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios do Candidato">
    public function get_curriculos_inseridos_candidato($id_usuario, $id_especialidade)
    {
        $stmt = $this->pdo->prepare("
                    select ec.id id_especialidade_curriculo,u.cpf, ec.label, ec.data_inicio, ec.data_termino, ec.carga_horaria, c.nome nome_curriculo, c.id id_curriculo, c.quantidade_multiplicacao,
                    c.pontuacao, c.multiplicacao, c.carga_horaria_obrigatoria, ec.valido, ec.justificativa, ec.nome, ec.usuario_avaliou, ec.id_candidato_x_especialidade, ec.multiplicador
                    from candidato_x_especialidade ce
                    inner join especialidade_curriculo ec on ce.id = ec.id_candidato_x_especialidade
                    inner join curriculo c on c.id = ec.id_curriculo
                    inner join usuario u on u.id = ce.id_candidato
                    where ce.id_especialidade = :id_especialidade
                    and c.apagado = 0
                    and ce.apagado = 0
                    and ec.apagado = 0
                    and u.apagado = 0
                    and u.id_selecao = :selecao
                    and u.id = :id_usuario order by c.id, ec.data_termino
                    ");
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Vagas Especialidade">

    // 11/07/2025 -> Iago Silva Incluindo o campo regiao_militar na Query
    public function get_vagas_especialidade($id_especialidade)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            c.id AS id_cidade, 
            c.nome AS cidade, 
            ce.regiao_militar,
            ce.numero_vagas - (
                SELECT COUNT(*) 
                FROM candidato_x_especialidade
                WHERE id_especialidade = :id_especialidade
                AND cidade_escolheu_servir = c.id
                AND concorrendo = 1
            ) AS vagas
        FROM cidade_x_especialidade ce
        INNER JOIN cidade c ON c.id = ce.id_cidade
        WHERE ce.id_especialidade = :id_especialidade
        AND ce.apagado = 0
    ");

        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_vagas_especialidade_eipot($id_especialidade)
    {
        $stmt = $this->pdo->prepare("
                        select c.id id_cidade, c.nome cidade, 
                        ce.numero_vagas - 

                        (select count(*) 
                        from candidato_x_especialidade
                        where id_especialidade = :id_especialidade
                        and cidade_escolheu_servir = c.id
                        and candidato_x_especialidade.concorrendo = 1)
                        vagas

                        from cidade_x_especialidade ce
                        inner join cidade c on c.id = ce.id_cidade
                        where ce.id_especialidade = :id_especialidade
                        and ce.apagado = 0
                    ");
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios para o candidato cadastrar">
    public function get_documentos_obrigatorios_cadastrados()
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from documentacao_obrigatoria
                        where id_selecao = :id_selecao and apagado = 0 order by nome"
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios Apagados">
    public function get_documentos_obrigatorios_apagados()
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from documentacao_obrigatoria
                        where id_selecao = :id_selecao and apagado = 1"
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get suporte Candidato">
    public function get_suporte_candidato($id_usuario_remetente)
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from suporte_candidato
                        where id_usuario_remetente = :id_usuario_remetente and apagado = 0"
        );
        $stmt->bindValue(':id_usuario_remetente', $id_usuario_remetente);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Lista de Suporte">
    public function get_lista_suporte_candidato($rm_usuario)
    {
        $id_selecao = $_SESSION['selecao'];

        $stmt = $this->pdo->prepare(
            "
                         select sc.* ,u.cpf, u.mail, u.rm_inscricao, user.posto_grad, user.nome_guerra
                        from suporte_candidato sc
                        inner join usuario u on u.id = sc.id_usuario_remetente
                        left join usuario user on user.id = sc.id_usuario_respondeu
                        where sc.apagado = 0 
                        and u.id_selecao = :id_selecao
                        and u.rm_inscricao = :rm_usuario
                        order by sc.id"
        );
        $stmt->bindValue(':id_selecao', $id_selecao);
        $stmt->bindValue(':rm_usuario', $rm_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>
    //ASP SILVA
    public function get_lista_suporte_todos_candidatos()
    {
        $id_selecao = $_SESSION['selecao'];

        $stmt = $this->pdo->prepare(
            "
                         select sc.* ,u.cpf, u.mail, u.rm_inscricao, user.posto_grad, user.nome_guerra
                        from suporte_candidato sc
                        inner join usuario u on u.id = sc.id_usuario_remetente
                        left join usuario user on user.id = sc.id_usuario_respondeu
                        where sc.apagado = 0 
                        and u.id_selecao = :id_selecao
                        order by sc.id"
        );
        $stmt->bindValue(':id_selecao', $id_selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // <editor-fold defaultstate="collapsed" desc="Get currículo para o candidato cadastrar">
    public function get_curriculo_cadastrados()
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from curriculo
                        where id_selecao = :id_selecao and apagado = 0 order by nome"
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Exames Saúde ">
    public function get_exames_medico()
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from exame_medico
                        where id_selecao = :id_selecao and apagado = 0"
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get currículos apagados">
    public function get_curriculo_apagados()
    {
        $stmt = $this->pdo->prepare(
            "
                        select * from curriculo
                        where id_selecao = :id_selecao and apagado = 1"
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documentos obrigatorios do Candidato Cadastados">
    public function get_documentos_obrigatorios_sobrando_candidato($id_candidadto)
    {
        $stmt = $this->pdo->prepare(
            "
                                    select * 
                                    from documentacao_obrigatoria docs
                                    where id_selecao = :id_selecao and apagado = 0
                                    and id not in (select id_documentacao_obrigatoria
                                    from documento_obrigatorio
                                    where id_candidato = :id_candidato and apagado = 0)
                                    order by nome
                                    "
        );
        $stmt->bindValue(':id_selecao', $_SESSION['selecao']);
        $stmt->bindValue(':id_candidato', $id_candidadto);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEspecialidadesPorSelecao($id_selecao)
    {

        $stmt = $this->pdo->prepare("SELECT id, nome FROM siscant.especialidade WHERE id_selecao = :id_selecao");

        $stmt->bindValue(':id_selecao', $id_selecao);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getArmaEipot($id_usuario)
    {

        $stmt = $this->pdo->prepare("SELECT arma_eipot FROM siscant.usuario WHERE id = :id_usuario");

        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['arma_eipot'] : null;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Currículo sobrando do Candidato">
    public function get_curriculo_sobrando_candidato($id_candidadto, $id_especialidade)
    {
        $stmt = $this->pdo->prepare(
            "
                                        select * 
                                        from curriculo
                                        where id_selecao = :selecao and apagado = 0
                                        and id not in 
                                        (select id_curriculo from especialidade_curriculo
                                        where apagado = 0 and id_candidato_x_especialidade in 
                                        (
                                        select id from 
                                        candidato_x_especialidade
                                        where id_candidato = :id_candidato and id_especialidade = :id_especialidade and apagado = 0
                                        ))
                                    "
        );
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $stmt->bindValue(':id_candidato', $id_candidadto);
        $stmt->bindValue(':selecao', $_SESSION['selecao']);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade Candidatos Concorrendo">
    public function get_quantidade_candidatos_concorrendo()
    {
        $id_selecao = $_SESSION['selecao'];

        $stmt = $this->pdo->prepare(
            "
                        select count(*) quantidade 
                        from usuario
                        where perfil = 'candidato' 
                        and concorrendo = 1 
                        and medico_obrigatorio is null
                        and candidato = 1 
                        and apagado = 0 
                        and id_selecao = :id_selecao"
        );
        $stmt->bindValue(':id_selecao', $id_selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade Candidatos">
    public function get_quantidade_candidatos()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(*) quantidade from usuario where perfil = 'candidato' and medico_obrigatorio is null and candidato = 1 and apagado = 0 and id_selecao = :selecao");
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get total segmentos">
    public function get_total_segmento()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(sexo) quantidade, sexo 
                                    from usuario 
                                    where perfil = 'candidato' 
                                    and candidato = 1 
                                    and apagado = 0 
                                    and sexo is not null
                                    and id_selecao = :selecao
                                    group by sexo
                                    order by sexo");
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get total segmentos">
    public function get_total_segmento_concorrendo()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(sexo) quantidade, sexo 
                                    from usuario 
                                    where perfil = 'candidato' 
                                    and candidato = 1 
                                    and apagado = 0 
                                    and concorrendo = 1
                                    and sexo is not null
                                    and id_selecao = :selecao
                                    group by sexo
                                    order by sexo");
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_total_etapa_presencial_rm_old($selecao, $i)
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE rm_inscricao = :rm_inscricao AND candidato = 1;");
        $stmt->bindValue(':selecao', $selecao);
        //  $stmt->bindValue(':rm_inscricao', $rm_inscricao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_total_etapa_presencial_rm()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("SELECT 
                                            COUNT(CASE WHEN rm_inscricao = 1 THEN 1 END) AS rm_inscricao_1,
                                            COUNT(CASE WHEN rm_inscricao = 2 THEN 1 END) AS rm_inscricao_2,
                                            COUNT(CASE WHEN rm_inscricao = 3 THEN 1 END) AS rm_inscricao_3,
                                            COUNT(CASE WHEN rm_inscricao = 4 THEN 1 END) AS rm_inscricao_4,
                                            COUNT(CASE WHEN rm_inscricao = 5 THEN 1 END) AS rm_inscricao_5,
                                            COUNT(CASE WHEN rm_inscricao = 6 THEN 1 END) AS rm_inscricao_6,
                                            COUNT(CASE WHEN rm_inscricao = 7 THEN 1 END) AS rm_inscricao_7,
                                            COUNT(CASE WHEN rm_inscricao = 8 THEN 1 END) AS rm_inscricao_8,
                                            COUNT(CASE WHEN rm_inscricao = 9 THEN 1 END) AS rm_inscricao_9,
                                            COUNT(CASE WHEN rm_inscricao = 10 THEN 1 END) AS rm_inscricao_10,
                                            COUNT(CASE WHEN rm_inscricao = 11 THEN 1 END) AS rm_inscricao_11,
                                            COUNT(CASE WHEN rm_inscricao = 12 THEN 1 END) AS rm_inscricao_12,
                                            COUNT(CASE WHEN rm_inscricao BETWEEN 1 AND 12 THEN 1 END) AS total_concorrendo
                                        FROM usuario
                                        WHERE candidato = 1
                                        AND concorrendo = 1
                                        AND id_selecao = :selecao
                                        AND apagado = 0");
        $stmt->bindValue(':selecao', $selecao);
        //  $stmt->bindValue(':rm_inscricao', $rm_inscricao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function get_total_rm_interesse()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("SELECT 
                                        COUNT(CASE WHEN rm_destino = 1 THEN 1 END) AS rm_destino_1,
                                        COUNT(CASE WHEN rm_destino = 2 THEN 1 END) AS rm_destino_2,
                                        COUNT(CASE WHEN rm_destino = 3 THEN 1 END) AS rm_destino_3,
                                        COUNT(CASE WHEN rm_destino = 4 THEN 1 END) AS rm_destino_4,
                                        COUNT(CASE WHEN rm_destino = 5 THEN 1 END) AS rm_destino_5,
                                        COUNT(CASE WHEN rm_destino = 6 THEN 1 END) AS rm_destino_6,
                                        COUNT(CASE WHEN rm_destino = 7 THEN 1 END) AS rm_destino_7,
                                        COUNT(CASE WHEN rm_destino = 8 THEN 1 END) AS rm_destino_8,
                                        COUNT(CASE WHEN rm_destino = 9 THEN 1 END) AS rm_destino_9,
                                        COUNT(CASE WHEN rm_destino = 10 THEN 1 END) AS rm_destino_10,
                                        COUNT(CASE WHEN rm_destino = 11 THEN 1 END) AS rm_destino_11,
                                        COUNT(CASE WHEN rm_destino = 12 THEN 1 END) AS rm_destino_12,
                                        COUNT(CASE WHEN rm_destino BETWEEN 1 AND 12 THEN 1 END) AS total_interesse
                                    FROM usuario
                                    WHERE candidato = 1
                                    AND concorrendo = 1
                                    AND id_selecao = :selecao
                                    AND apagado = 0");
        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verifica_documento_obrigatorio_candidato">
    public function verifica_documento_obrigatorio_candidato($id_documento_obrigatorio, $id_usuario)
    {
        $stmt = $this->pdo->prepare("select * from documento_obrigatorio
                                    where id_documentacao_obrigatoria = :id_documento 
                                    and id_candidato = :id_candidato and apagado = 0");

        $stmt->bindValue(':id_documento', $id_documento_obrigatorio);
        $stmt->bindValue(':id_candidato', $id_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get EXAME MÉDICO ID">
    public function get_exame_medico_id($id)
    {
        $stmt = $this->pdo->prepare("select * from exame_medico where id = :id");
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Curriculo ID">
    public function get_curriculo_id($id)
    {
        $stmt = $this->pdo->prepare("select * from curriculo where id = :id");
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Documento Obrigatório ID">
    public function get_documentacao_obrigatoria_id($id)
    {
        $stmt = $this->pdo->prepare("select * from documentacao_obrigatoria where id = :id");
        $stmt->bindValue(':id', $id);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade OTT">
    public function get_quantidade_ott()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 and ce.apagado = 0 and e.apagado = 0 and u.id_selecao = :selecao and e.ott_stt = 'ott'");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade OTT">
    public function get_quantidade_ott_stt_mfdv()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade, ott_stt
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 
                                    and ce.apagado = 0 
                                    and e.apagado = 0 
                                    and u.id_selecao = :selecao
                                    group by ott_stt");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade OTT">
    public function get_quantidade_ott_stt_mfdv_concorrendo()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade, ott_stt
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 
                                    and ce.apagado = 0 
                                    and u.concorrendo = 1 
                                    and e.apagado = 0 
                                    and u.id_selecao = :selecao
                                    group by ott_stt");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade OTT CONCORRENDO">
    public function get_quantidade_ott_concorrendo()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 and ce.apagado = 0 and u.concorrendo = 1 and e.apagado = 0 and u.id_selecao = :selecao and e.ott_stt = 'ott'");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade STT">
    public function get_quantidade_stt()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 and ce.apagado = 0 and e.apagado = 0 and u.id_selecao = :selecao and e.ott_stt = 'stt'");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get Quantidade STT CONCORRENDO">
    public function get_quantidade_stt_concorrendo()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(e.ott_stt) quantidade
                                    from candidato_x_especialidade ce
                                    inner join especialidade e on e.id = ce.id_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.apagado = 0 and ce.apagado = 0 and u.concorrendo= 1 and e.apagado = 0 and u.id_selecao = :selecao and e.ott_stt = 'stt'");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GIGAS DOCS OBRIGATÓRIOS">
    public function get_gigas_docs()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(*) quantidade, sum(tamanho)/(1024*1024*1024) gigas 
                                    from documento_obrigatorio
                                    inner join usuario u on u.id = documento_obrigatorio.id_candidato
                                    where u.id_selecao = :selecao");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GIGAS CURRÍCULO">
    public function get_gigas_curriculo()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(*) quantidade, sum(tamanho)/(1024*1024*1024) gigas 
                                    from especialidade_curriculo
                                    inner join candidato_x_especialidade ce on ce.id = especialidade_curriculo.id_candidato_x_especialidade
                                    inner join usuario u on u.id = ce.id_candidato
                                    where u.id_selecao = :selecao");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GIGAS FOTOS">
    public function get_gigas_foto()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(*) quantidade, sum(tamanho)/(1024*1024*1024) gigas
                                    from foto
                                    inner join usuario u on u.id = foto.id_usuario
                                    where u.id_selecao = :selecao");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Get GIGAS PAGAMENTO">
    public function get_gigas_pagamento()
    {
        $selecao = $_SESSION['selecao'];
        $stmt = $this->pdo->prepare("select count(*) quantidade, sum(tamanho)/(1024*1024*1024) gigas
                                    from pagamento_inscricao
                                    inner join usuario u on u.id = pagamento_inscricao.id_candidato
                                    where u.id_selecao = :selecao");

        $stmt->bindValue(':selecao', $selecao);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere LOG ERRO">
    public function insere_erro($id_usuario, $cpf, $descricao)
    {
        if (!isset($_SESSION))
            session_start();
        $navegador = getBrowser();
        $navegador = $navegador['platform'] . " - " . $navegador['name'] . " " . $navegador['version'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $datetime = date('Y-m-d H:i:s');
        $id_selecao = $_SESSION['selecao'];
        $data =
            [
                'id_usuario' => $id_usuario,
                'id_selecao' => $id_selecao,
                'cpf' => $cpf,
                'descricao' => $descricao,
                'data' => $datetime,
                'ip' => $ip,
                'sistema' => $navegador
            ];

        $sql = "INSERT INTO erro 
        (id_selecao, id_usuario, cpf, descricao, data, ip, sistema)
        VALUES
        (:id_selecao, :id_usuario, :cpf, :descricao, :data, :ip, :sistema)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $this->pdo->beginTransaction();
            $stmt->execute($data);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere LOG">
    public function insere_log($id_usuario, $cpf, $id_alterado, $codigo, $tabela, $operacao, $alteracao, $alteracao_detalhada)
    {
        $navegador = getBrowser();
        $navegador = $navegador['platform'] . " - " . $navegador['name'] . " " . $navegador['version'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $datetime = date('Y-m-d H:i:s');
        $id_selecao = $_SESSION['selecao'];
        $data =
            [
                'id_usuario' => $id_usuario,
                'cpf' => $cpf,
                'id_alterado' => $id_alterado,
                'id_selecao' => $id_selecao,
                'codigo' => $codigo,
                'tabela' => $tabela,
                'operacao' => $operacao,
                'alteracao' => $alteracao,
                'alteracao_detalhada' => $alteracao_detalhada,
                'data' => $datetime,
                'ip' => $ip,
                'sistema' => $navegador
            ];

        $sql = "INSERT INTO log 
        (id_usuario, cpf, id_alterado, id_selecao, codigo, tabela, operacao, alteracao, alteracao_detalhada, data, ip, sistema)
        VALUES
        (:id_usuario, :cpf, :id_alterado, :id_selecao, :codigo, :tabela, :operacao, :alteracao, :alteracao_detalhada, :data, :ip, :sistema)";

        $stmt = $this->pdo->prepare($sql);
        try {
            $this->pdo->beginTransaction();
            $stmt->execute($data);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere ACESSO PÁGINA">

    public function insere_acesso_pagina($id_usuario, $cpf, $id_visualizado, $codigo, $pagina_acessada, $endereco_completo)
    {
        $navegador = getBrowser();
        $navegador = $navegador['platform'] . " - " . $navegador['name'] . " " . $navegador['version'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $datetime = date('Y-m-d H:i:s');
        $id_selecao = $_SESSION['selecao'];
        $data =
            [
                'id_usuario' => $id_usuario,
                'cpf' => $cpf,
                'id_visualizado' => $id_visualizado,
                'id_selecao' => $id_selecao,
                'codigo' => $codigo,
                'pagina_acessada' => $pagina_acessada,
                'url' => $endereco_completo,
                'data' => $datetime,
                'ip' => $ip,
                'sistema' => $navegador
            ];

        $sql = "INSERT INTO acesso_pagina 
        (id_usuario, cpf, id_visualizado, id_selecao, codigo, pagina, url, data, ip, sistema)
        VALUES
        (:id_usuario, :cpf, :id_visualizado, :id_selecao, :codigo, :pagina_acessada, :url, :data, :ip, :sistema)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $this->pdo->beginTransaction();
            $stmt->execute($data);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Suporte inicial">
    public function insere_suporte_inicial($selecao, $motivo, $nome, $cpf, $telefone, $mail, $mensagem)
    {

        $datetime = date('Y-m-d H:i:s');
        try {

            $data =
                [
                    'id_selecao' => $selecao,
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'telefone' => $telefone,
                    'mail' => $mail,
                    'motivo' => $motivo,
                    'mensagem' => $mensagem,
                    'apagado' => "0",
                    'data_enviado' => $datetime
                ];

            $sqlInsert = "INSERT INTO suporte 
            (id_selecao, nome_completo, cpf, telefone, mail, motivo, mensagem, apagado, data_enviado)
            VALUES
            (:id_selecao, :nome, :cpf, :telefone, :mail, :motivo, :mensagem, :apagado, :data_enviado)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $selecao);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":motivo", $motivo);
            $query->bindValue(":apagado", "0");
            $query->bindValue(":data_enviado", $datetime);
            $query->bindValue(":mensagem", $mensagem);

            if ($query->execute()) {
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_recurso_visualiza($id, $rm)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recurso_rm WHERE id_selecao = :id AND rm = :rm");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':rm', $rm);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function get_recurso_rm($rm_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recurso_rm WHERE rm = :rm_usuario;");
        $stmt->bindValue(':rm_usuario', $rm_usuario);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert_recurso_visualiza(
        $id_selecao,
        $rm,
        $data_inicio_recurso,
        $data_fim_recurso,
        $mostrar_recurso
    ) {

        try {

            $sqlInsert = "INSERT INTO recurso_rm
                                                    (id_selecao, rm, data_inicio_recurso, data_fim_recurso, mostrar_recurso) 
                                                    VALUES 
                                                    (:id_selecao, :rm, :data_inicio_recurso, :data_fim_recurso, :mostrar_recurso);
                                                    ";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);
            $query->bindValue(":id_selecao", $id_selecao);
            $query->bindValue(":rm", $rm);
            $query->bindValue(":data_inicio_recurso", $data_inicio_recurso);
            $query->bindValue(":data_fim_recurso", $data_fim_recurso);
            $query->bindValue(":mostrar_recurso", $mostrar_recurso);
            if ($query->execute()) {

                $this->pdo->commit();
                return true;
            } else {
                return false;
                print_r($this->errorInfo());
                exit;
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
            exit;
            return false;
        }
    }

    public function update_recurso_visualiza(
        $id_selecao,
        $rm,
        $data_inicio_recurso,
        $data_fim_recurso,
        $mostrar_recurso
    ) {

        try {
            $sqlInsert = "UPDATE recurso_rm
                                                        SET data_inicio_recurso = :data_inicio_recurso,
                                                            data_fim_recurso = :data_fim_recurso,
                                                            mostrar_recurso = :mostrar_recurso,
                                                            rm = :rm
                                                        WHERE id_selecao = :id_selecao AND rm =:rm ;";
            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);
            $query->bindValue(":id_selecao", $id_selecao);
            $query->bindValue(":rm", $rm);
            $query->bindValue(":data_inicio_recurso", $data_inicio_recurso);
            $query->bindValue(":data_fim_recurso", $data_fim_recurso);
            $query->bindValue(":mostrar_recurso", $mostrar_recurso);

            if ($query->execute()) {
                $this->pdo->commit();
                return true;
            } else {
                return false;
                print_r($this->errorInfo());
                exit;
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Candidato">
    public function insere_candidato(
        $senha,
        $nome_completo,
        $cpf,
        $identidade,
        $data_nascimento,
        $nome_social,
        $estado_civil,
        $companheiro,
        $filiacao_pai,
        $sexo,
        $nascionalidade,
        $naturalidade,
        $filiacao_mae,
        $uf,
        $bairro,
        $cidade,
        $cep,
        $rua,
        $telefone,
        $celular,
        $mail,
        $tempo_sv_pub,
        $tempo_sv_pub_anos,
        $tempo_sv_pub_meses,
        $tempo_sv_pub_dias,
        $tempo_sv_mil,
        $tempo_sv_mil_anos,
        $tempo_sv_mil_meses,
        $tempo_sv_mil_dias,
        $civil_militar,
        $certificado,
        $documento,
        $data_expedicao,
        $ativa_reserva,
        $posto_grad,
        $forca,
        $arma,
        $incorporacao,
        $licenciamento,
        $num_dependentes,
        $autodeclaracao,
        $vaga_reservada,
        $prioridade_forca,
        $voluntario_12rm,
        $nome_instituto_ensino,
        $ano_formacao,
        $uf_instituto_ensino,
        $cidade_instituto_ensino,
        $cidade_etapas_presenciais,
        $curso_graduacao,
        $ano_formacao_ofor,
        $nota_ofor,
        $arma_eipot,
        $cidade_exame_musica_12rm,
        $datetime,
        $assinatura
    ) {
        try {

            if (!isset($_SESSION['selecao'])) {
                return false;
                exit();
            }

            $perfil = "candidato";
            $valor_um = 1;
            $valor_zero = 0;

            $sqlInsert = "INSERT INTO usuario 
            (
            id_selecao, cpf, perfil, candidato, etapa, nome_completo, trocar_senha, concorrendo, senha, desistencia,  
            estado_civil, companheiro, sexo, nome_social, pai, mae, identidade, nacionalidade, naturalidade, data_nascimento, uf, 
            cep, id_cidade, rua_num_complemento, bairro, tel_residencial, tel_celular, mail, tempo_sv_pub, tempo_sv_pub_anos, 
            tempo_sv_pub_meses, tempo_sv_pub_dias, tempo_sv_mil, tempo_sv_mil_anos, tempo_sv_mil_meses, tempo_sv_mil_dias, 
            certificado, num_ducumento, data_expedicao,civil_militar, ativa_reserva, forca, ano_incorporacao, posto_grad, arma_quadro_servico, 
            licenciamento, dependente, autodeclaracao, vaga_reservada, voluntario_12rm, instituto_ensino, ano_formacao, uf_instituto_ensino, id_cidade_instituto_ensino,
            prioridade_forca, cidade_etapas_presenciais, curso_graduacao, nota_ofor, ano_formacao_ofor,  arma_eipot, cidade_exame_musica_12rm, assinatura_sistema, apagado, _data_ultima_atualizacao
            )
            VALUES
            (:selecao, :cpf, :perfil, :valor_um, :valor_um, :nome_completo, :valor_um, :valor_um, :senha, :valor_zero, :estado_civil, :companheiro, :sexo, :nome_social,
            :filiacao_pai, :filiacao_mae, :identidade, :nascionalidade, :naturalidade, :data_nascimento,:uf, :cep,
            :cidade, :rua, :bairro, :telefone, :celular, :mail, :tempo_sv_pub, :tempo_sv_pub_anos, :tempo_sv_pub_meses,
            :tempo_sv_pub_dias, :tempo_sv_mil, :tempo_sv_mil_anos, :tempo_sv_mil_meses, :tempo_sv_mil_dias,
            :certificado, :documento, :data_expedicao, :civil_militar, :ativa_reserva, :forca, :incorporacao, :posto_grad,
            :arma, :licenciamento, :num_dependentes, :autodeclaracao, :vaga_reservada, :voluntario_12rm, :instituto_ensino, :ano_formacao, :uf_instituto_ensino, :id_cidade_instituto_ensino,
            :prioridade_forca, :cidade_etapas_presenciais, :curso_graduacao, :nota_ofor, :ano_formacao_ofor, UPPER(:arma_eipot), :cidade_exame_musica_12rm, :assinatura, :valor_zero, :datetime)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":selecao", $_SESSION['selecao']);
            $query->bindValue(":senha", $senha);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":perfil", $perfil);
            $query->bindValue(":valor_um", $valor_um);
            $query->bindValue(":valor_zero", $valor_zero);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":identidade", $identidade);
            $query->bindValue(":data_nascimento", $data_nascimento);
            $query->bindValue(":nome_social", $nome_social);
            $query->bindValue(":estado_civil", $estado_civil);
            $query->bindValue(":companheiro", $companheiro);
            $query->bindValue(":filiacao_pai", $filiacao_pai);
            $query->bindValue(":sexo", $sexo);
            $query->bindValue(":nascionalidade", $nascionalidade);
            $query->bindValue(":naturalidade", $naturalidade);
            $query->bindValue(":filiacao_mae", $filiacao_mae);
            $query->bindValue(":uf", $uf);
            $query->bindValue(":bairro", $bairro);
            $query->bindValue(":cidade", $cidade);
            $query->bindValue(":cep", $cep);
            $query->bindValue(":rua", $rua);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":celular", $celular);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":tempo_sv_pub", $tempo_sv_pub);
            $query->bindValue(":tempo_sv_pub_anos", $tempo_sv_pub_anos);
            $query->bindValue(":tempo_sv_pub_meses", $tempo_sv_pub_meses);
            $query->bindValue(":tempo_sv_pub_dias", $tempo_sv_pub_dias);
            $query->bindValue(":tempo_sv_mil", $tempo_sv_mil);
            $query->bindValue(":tempo_sv_mil_anos", $tempo_sv_mil_anos);
            $query->bindValue(":tempo_sv_mil_meses", $tempo_sv_mil_meses);
            $query->bindValue(":tempo_sv_mil_dias", $tempo_sv_mil_dias);
            $query->bindValue(":civil_militar", $civil_militar);
            $query->bindValue(":certificado", $certificado);
            $query->bindValue(":documento", $documento);
            $query->bindValue(":data_expedicao", $data_expedicao);
            $query->bindValue(":ativa_reserva", $ativa_reserva);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":forca", $forca);
            $query->bindValue(":arma", $arma);
            $query->bindValue(":incorporacao", $incorporacao);
            $query->bindValue(":licenciamento", $licenciamento);
            $query->bindValue(":num_dependentes", $num_dependentes);
            $query->bindValue(":autodeclaracao", $autodeclaracao);
            $query->bindValue(":vaga_reservada", $vaga_reservada);
            $query->bindValue(":prioridade_forca", $prioridade_forca);
            $query->bindValue(":voluntario_12rm", $voluntario_12rm);
            $query->bindValue(":instituto_ensino", $nome_instituto_ensino);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":uf_instituto_ensino", $uf_instituto_ensino);
            $query->bindValue(":id_cidade_instituto_ensino", $cidade_instituto_ensino);
            $query->bindValue(":cidade_etapas_presenciais", $cidade_etapas_presenciais);
            $query->bindValue(":ano_formacao_ofor", $ano_formacao_ofor);
            $query->bindValue(":curso_graduacao", $curso_graduacao);
            $query->bindValue(":nota_ofor", $nota_ofor);
            $query->bindValue(":arma_eipot", $arma_eipot);
            $query->bindValue(":cidade_exame_musica_12rm", $cidade_exame_musica_12rm);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":assinatura", $assinatura);


            if ($query->execute()) {

                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'selecao' => $_SESSION['selecao'],
                        'senha' => $senha,
                        'perfil' => $perfil,
                        'nome_completo' => $nome_completo,
                        'cpf' => $cpf,
                        'identidade' => $identidade,
                        'data_nascimento' => $data_nascimento,
                        'nome_social' => $nome_social,
                        'companheiro' => $companheiro,
                        'estado_civil' => $estado_civil,
                        'filiacao_pai' => $filiacao_pai,
                        'sexo' => $sexo,
                        'nascionalidade' => $nascionalidade,
                        'naturalidade' => $naturalidade,
                        'filiacao_mae' => $filiacao_mae,
                        'uf' => $uf,
                        'bairro' => $bairro,
                        'cidade' => $cidade,
                        'cep' => $cep,
                        'rua' => $rua,
                        'telefone' => $telefone,
                        'celular' => $celular,
                        'mail' => $mail,
                        'tempo_sv_pub' => $tempo_sv_pub,
                        'tempo_sv_pub_anos' => $tempo_sv_pub_anos,
                        'tempo_sv_pub_meses' => $tempo_sv_pub_meses,
                        'tempo_sv_pub_dias' => $tempo_sv_pub_dias,
                        'tempo_sv_mil' => $tempo_sv_mil,
                        'tempo_sv_mil_anos' => $tempo_sv_mil_anos,
                        'tempo_sv_mil_meses' => $tempo_sv_mil_meses,
                        'tempo_sv_mil_dias' => $tempo_sv_mil_dias,
                        'civil_militar' => $civil_militar,
                        'certificado' => $certificado,
                        'documento' => $documento,
                        'data_expedicao' => $data_expedicao,
                        'ativa_reserva' => $ativa_reserva,
                        'posto_grad' => $posto_grad,
                        'forca' => $forca,
                        'arma' => $arma,
                        'incorporacao' => $incorporacao,
                        'licenciamento' => $licenciamento,
                        'num_dependentes' => $num_dependentes,
                        'autodeclaracao' => $autodeclaracao,
                        'vaga_reservada' => $vaga_reservada,
                        'prioridade_forca' => $prioridade_forca,
                        'voluntario_12rm' => $voluntario_12rm,
                        'instituto_ensino' => $nome_instituto_ensino,
                        'ano_formacao' => $ano_formacao,
                        'uf_instituto_ensino' => $uf_instituto_ensino,
                        'id_cidade_instituto_ensino' => $cidade_instituto_ensino,
                        'cidade_etapas_presenciais' => $cidade_etapas_presenciais,
                        'nota_ofor' => $nota_ofor,
                        'curso_graduacao' => $curso_graduacao,
                        'ano_formacao_ofor' => $ano_formacao_ofor,
                        'arma_eipot' => $arma_eipot,
                        'cidade_exame_musica_12rm' => $cidade_exame_musica_12rm,
                        'data_cadastro' => $datetime,
                        'assinatura' => $assinatura
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                return false;
                //print_r($this->errorInfo());

                //$this->pdo->rollBack();
                //return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function insere_candidato_eipot(
        $senha,
        $nome_completo,
        $cpf,
        $identidade,
        $data_nascimento,
        $nome_social,
        $estado_civil,
        $companheiro,
        $filiacao_pai,
        $sexo,
        $nascionalidade,
        $naturalidade,
        $filiacao_mae,
        $uf,
        $bairro,
        $cidade,
        $cep,
        $rua,
        $telefone,
        $celular,
        $mail,
        $tempo_sv_pub,
        $tempo_sv_pub_anos,
        $tempo_sv_pub_meses,
        $tempo_sv_pub_dias,
        $tempo_sv_mil,
        $tempo_sv_mil_anos,
        $tempo_sv_mil_meses,
        $tempo_sv_mil_dias,
        $civil_militar,
        $certificado,
        $documento,
        $data_expedicao,
        $ativa_reserva,
        $posto_grad,
        $forca,
        $arma,
        $incorporacao,
        $licenciamento,
        $num_dependentes,
        $autodeclaracao,
        $vaga_reservada,
        $prioridade_forca,
        $voluntario_12rm,
        $nome_instituto_ensino,
        $ano_formacao,
        $uf_instituto_ensino,
        $cidade_instituto_ensino,
        $cidade_etapas_presenciais,
        $curso_graduacao,
        $ano_formacao_ofor,
        $nota_ofor,
        //  $arma_eipot,
        $rm_inscricao,
        $rm_destino,
        $cidade_exame_musica_12rm,
        $datetime,
        $assinatura
    ) {
        try {

            if (!isset($_SESSION['selecao'])) {
                return false;
                exit();
            }

            $perfil = "candidato";
            $valor_um = 1;
            $valor_zero = 0;

            $sqlInsert = "INSERT INTO usuario 
                        (
                        id_selecao, cpf, perfil, candidato, etapa, nome_completo, trocar_senha, concorrendo, senha, desistencia,  
                        estado_civil, companheiro, sexo, nome_social, pai, mae, identidade, nacionalidade, naturalidade, data_nascimento, uf, 
                        cep, id_cidade, rua_num_complemento, bairro, tel_residencial, tel_celular, mail, tempo_sv_pub, tempo_sv_pub_anos, 
                        tempo_sv_pub_meses, tempo_sv_pub_dias, tempo_sv_mil, tempo_sv_mil_anos, tempo_sv_mil_meses, tempo_sv_mil_dias, 
                        certificado, num_ducumento, data_expedicao,civil_militar, ativa_reserva, forca, ano_incorporacao, posto_grad, arma_quadro_servico, 
                        licenciamento, dependente, autodeclaracao, vaga_reservada, voluntario_12rm, instituto_ensino, ano_formacao, uf_instituto_ensino, id_cidade_instituto_ensino,
                        prioridade_forca, cidade_etapas_presenciais, curso_graduacao, nota_ofor, ano_formacao_ofor, rm_inscricao, rm_destino, cidade_exame_musica_12rm, assinatura_sistema, apagado, _data_ultima_atualizacao
                        )
                        VALUES
                        (:selecao, :cpf, :perfil, :valor_um, :valor_um, :nome_completo, :valor_um, :valor_um, :senha, :valor_zero, :estado_civil, :companheiro, :sexo, :nome_social,
                        :filiacao_pai, :filiacao_mae, :identidade, :nascionalidade, :naturalidade, :data_nascimento,:uf, :cep,
                        :cidade, :rua, :bairro, :telefone, :celular, :mail, :tempo_sv_pub, :tempo_sv_pub_anos, :tempo_sv_pub_meses,
                        :tempo_sv_pub_dias, :tempo_sv_mil, :tempo_sv_mil_anos, :tempo_sv_mil_meses, :tempo_sv_mil_dias,
                        :certificado, :documento, :data_expedicao, :civil_militar, :ativa_reserva, :forca, :incorporacao, :posto_grad,
                        :arma, :licenciamento, :num_dependentes, :autodeclaracao, :vaga_reservada, :voluntario_12rm, :instituto_ensino, :ano_formacao, :uf_instituto_ensino, :id_cidade_instituto_ensino,
                        :prioridade_forca, :cidade_etapas_presenciais, :curso_graduacao, :nota_ofor, :ano_formacao_ofor, :rm_inscricao, :rm_destino, :cidade_exame_musica_12rm, :assinatura, :valor_zero, :datetime)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":selecao", $_SESSION['selecao']);
            $query->bindValue(":senha", $senha);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":perfil", $perfil);
            $query->bindValue(":valor_um", $valor_um);
            $query->bindValue(":valor_zero", $valor_zero);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":identidade", $identidade);
            $query->bindValue(":data_nascimento", $data_nascimento);
            $query->bindValue(":nome_social", $nome_social);
            $query->bindValue(":estado_civil", $estado_civil);
            $query->bindValue(":companheiro", $companheiro);
            $query->bindValue(":filiacao_pai", $filiacao_pai);
            $query->bindValue(":sexo", $sexo);
            $query->bindValue(":nascionalidade", $nascionalidade);
            $query->bindValue(":naturalidade", $naturalidade);
            $query->bindValue(":filiacao_mae", $filiacao_mae);
            $query->bindValue(":uf", $uf);
            $query->bindValue(":bairro", $bairro);
            $query->bindValue(":cidade", $cidade);
            $query->bindValue(":cep", $cep);
            $query->bindValue(":rua", $rua);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":celular", $celular);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":tempo_sv_pub", $tempo_sv_pub);
            $query->bindValue(":tempo_sv_pub_anos", $tempo_sv_pub_anos);
            $query->bindValue(":tempo_sv_pub_meses", $tempo_sv_pub_meses);
            $query->bindValue(":tempo_sv_pub_dias", $tempo_sv_pub_dias);
            $query->bindValue(":tempo_sv_mil", $tempo_sv_mil);
            $query->bindValue(":tempo_sv_mil_anos", $tempo_sv_mil_anos);
            $query->bindValue(":tempo_sv_mil_meses", $tempo_sv_mil_meses);
            $query->bindValue(":tempo_sv_mil_dias", $tempo_sv_mil_dias);
            $query->bindValue(":civil_militar", $civil_militar);
            $query->bindValue(":certificado", $certificado);
            $query->bindValue(":documento", $documento);
            $query->bindValue(":data_expedicao", $data_expedicao);
            $query->bindValue(":ativa_reserva", $ativa_reserva);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":forca", $forca);
            $query->bindValue(":arma", $arma);
            $query->bindValue(":incorporacao", $incorporacao);
            $query->bindValue(":licenciamento", $licenciamento);
            $query->bindValue(":num_dependentes", $num_dependentes);
            $query->bindValue(":autodeclaracao", $autodeclaracao);
            $query->bindValue(":vaga_reservada", $vaga_reservada);
            $query->bindValue(":prioridade_forca", $prioridade_forca);
            $query->bindValue(":voluntario_12rm", $voluntario_12rm);
            $query->bindValue(":instituto_ensino", $nome_instituto_ensino);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":uf_instituto_ensino", $uf_instituto_ensino);
            $query->bindValue(":id_cidade_instituto_ensino", $cidade_instituto_ensino);
            $query->bindValue(":cidade_etapas_presenciais", $cidade_etapas_presenciais);
            $query->bindValue(":ano_formacao_ofor", $ano_formacao_ofor);
            $query->bindValue(":curso_graduacao", $curso_graduacao);
            $query->bindValue(":nota_ofor", $nota_ofor);
            // $query->bindValue(":arma_eipot",$arma_eipot);
            $query->bindValue(":rm_inscricao", $rm_inscricao);
            $query->bindValue(":rm_destino", $rm_destino);
            $query->bindValue(":cidade_exame_musica_12rm", $cidade_exame_musica_12rm);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":assinatura", $assinatura);


            if ($query->execute()) {

                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'selecao' => $_SESSION['selecao'],
                        'senha' => $senha,
                        'perfil' => $perfil,
                        'nome_completo' => $nome_completo,
                        'cpf' => $cpf,
                        'identidade' => $identidade,
                        'data_nascimento' => $data_nascimento,
                        'nome_social' => $nome_social,
                        'companheiro' => $companheiro,
                        'estado_civil' => $estado_civil,
                        'filiacao_pai' => $filiacao_pai,
                        'sexo' => $sexo,
                        'nascionalidade' => $nascionalidade,
                        'naturalidade' => $naturalidade,
                        'filiacao_mae' => $filiacao_mae,
                        'uf' => $uf,
                        'bairro' => $bairro,
                        'cidade' => $cidade,
                        'cep' => $cep,
                        'rua' => $rua,
                        'telefone' => $telefone,
                        'celular' => $celular,
                        'mail' => $mail,
                        'tempo_sv_pub' => $tempo_sv_pub,
                        'tempo_sv_pub_anos' => $tempo_sv_pub_anos,
                        'tempo_sv_pub_meses' => $tempo_sv_pub_meses,
                        'tempo_sv_pub_dias' => $tempo_sv_pub_dias,
                        'tempo_sv_mil' => $tempo_sv_mil,
                        'tempo_sv_mil_anos' => $tempo_sv_mil_anos,
                        'tempo_sv_mil_meses' => $tempo_sv_mil_meses,
                        'tempo_sv_mil_dias' => $tempo_sv_mil_dias,
                        'civil_militar' => $civil_militar,
                        'certificado' => $certificado,
                        'documento' => $documento,
                        'data_expedicao' => $data_expedicao,
                        'ativa_reserva' => $ativa_reserva,
                        'posto_grad' => $posto_grad,
                        'forca' => $forca,
                        'arma' => $arma,
                        'incorporacao' => $incorporacao,
                        'licenciamento' => $licenciamento,
                        'num_dependentes' => $num_dependentes,
                        'autodeclaracao' => $autodeclaracao,
                        'vaga_reservada' => $vaga_reservada,
                        'prioridade_forca' => $prioridade_forca,
                        'voluntario_12rm' => $voluntario_12rm,
                        'instituto_ensino' => $nome_instituto_ensino,
                        'ano_formacao' => $ano_formacao,
                        'uf_instituto_ensino' => $uf_instituto_ensino,
                        'id_cidade_instituto_ensino' => $cidade_instituto_ensino,
                        'cidade_etapas_presenciais' => $cidade_etapas_presenciais,
                        'nota_ofor' => $nota_ofor,
                        'curso_graduacao' => $curso_graduacao,
                        'ano_formacao_ofor' => $ano_formacao_ofor,
                        //  'arma_eipot'=>$arma_eipot,
                        'rm_inscricao' => $rm_inscricao,
                        'rm_destino' => $rm_destino,
                        'cidade_exame_musica_12rm' => $cidade_exame_musica_12rm,
                        'data_cadastro' => $datetime,
                        'assinatura' => $assinatura
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                return false;
                //print_r($this->errorInfo());

                //$this->pdo->rollBack();
                //return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function edita_candidato_eipot(
        $id_usuario,
        $nome_completo,
        $identidade,
        $data_nascimento,
        $estado_civil,
        $companheiro,
        $pai,
        $mae,
        $sexo,
        $nacionalidade,
        $naturalidade,
        $autodeclaracao,
        $vaga_reservada,
        $uf,
        $bairro,
        $id_cidade,
        $cep,
        $rua_num_complemento,
        $telefone,
        $celular,
        $mail,
        $incorporacao,
        $licenciamento,
        $prioridade_forca,
        $voluntario_12rm,
        $nome_instituto_ensino,
        $ano_formacao,
        $uf_instituto_ensino,
        $cidade_instituto_ensino,
        $cidade_etapas_presenciais,
        $curso_graduacao,
        $ano_formacao_ofor,
        // $nota_ofor,
        //  $arma_eipot,
        $rm_inscricao,
        $rm_destino,
        $datetime
    ) {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("UPDATE usuario SET 
                        nome_completo                  =   :nome_completo,
                        identidade                     =   :identidade,
                        data_nascimento                =   :data_nascimento,
                        estado_civil                   =   :estado_civil,
                        companheiro                    =   :companheiro,
                        pai                   =   :pai,
                        mae                   =   :mae,
                        sexo                     =   :sexo,
                        nacionalidade            =   :nacionalidade,
                        naturalidade             =   :naturalidade,
                        autodeclaracao           =   :autodeclaracao,
                        vaga_reservada           =   :vaga_reservada,
                        uf                       =   :uf,
                        bairro                   =   :bairro,
                        id_cidade                =   :id_cidade,
                        cep                      =   :cep,
                        rua_num_complemento      =   :rua_num_complemento,
                        tel_residencial          =   :tel_residencial,
                        tel_celular              =   :tel_celular,
                        mail                     =   :mail,
                        ano_incorporacao         =   :incorporacao,
                        licenciamento            =   :licenciamento,
                        prioridade_forca         =   :prioridade_forca,
                        voluntario_12rm          =   :voluntario_12rm,
                        instituto_ensino         =   :nome_instituto_ensino,
                        ano_formacao             =   :ano_formacao,
                        uf_instituto_ensino      =   :uf_instituto_ensino,
                        id_cidade_instituto_ensino =   :cidade_instituto_ensino,
                        cidade_etapas_presenciais =   :cidade_etapas_presenciais,
                        curso_graduacao          =   :curso_graduacao,
                        ano_formacao_ofor        =   :ano_formacao_ofor,
                        rm_inscricao             =   :rm_inscricao,
                        rm_destino               =   :rm_destino
                         WHERE id = :id_candidato");


            $stmt->bindParam(":id_candidato", $id_usuario);
            $stmt->bindParam(":nome_completo", $nome_completo);
            $stmt->bindParam(":identidade", $identidade);
            $stmt->bindParam(":data_nascimento", $data_nascimento);
            $stmt->bindParam(":estado_civil", $estado_civil);
            $stmt->bindParam(":companheiro", $companheiro);
            $stmt->bindParam(":pai", $pai);
            $stmt->bindParam(":mae", $mae);
            $stmt->bindParam(":sexo", $sexo);
            $stmt->bindParam(":nacionalidade", $nacionalidade);
            $stmt->bindParam(":naturalidade", $naturalidade);
            $stmt->bindParam(":autodeclaracao", $autodeclaracao);
            $stmt->bindParam(":vaga_reservada", $vaga_reservada);
            $stmt->bindParam(":uf", $uf);
            $stmt->bindParam(":bairro", $bairro);
            $stmt->bindParam(":id_cidade", $id_cidade);
            $stmt->bindParam(":cep", $cep);
            $stmt->bindParam(":rua_num_complemento", $rua_num_complemento);
            $stmt->bindParam(":tel_residencial", $telefone);
            $stmt->bindParam(":tel_celular", $celular);
            $stmt->bindParam(":mail", $mail);
            $stmt->bindParam(":incorporacao", $incorporacao);
            $stmt->bindParam(":licenciamento", $licenciamento);
            $stmt->bindParam(":prioridade_forca", $prioridade_forca);
            $stmt->bindParam(":voluntario_12rm", $voluntario_12rm);
            $stmt->bindParam(":nome_instituto_ensino", $nome_instituto_ensino);
            $stmt->bindParam(":ano_formacao", $ano_formacao);
            $stmt->bindParam(":uf_instituto_ensino", $uf_instituto_ensino);
            $stmt->bindParam(":cidade_instituto_ensino", $cidade_instituto_ensino);
            $stmt->bindParam(":cidade_etapas_presenciais", $cidade_etapas_presenciais);
            $stmt->bindParam(":curso_graduacao", $curso_graduacao);
            $stmt->bindParam(":ano_formacao_ofor", $ano_formacao_ofor);
            //   $stmt->bindParam(":nota_ofor", $nota_ofor);
            //  $stmt->bindParam(":arma_eipot", $arma_eipot);
            $stmt->bindParam(":rm_inscricao", $rm_inscricao);
            $stmt->bindParam(":rm_destino", $rm_destino);
            //   $stmt->bindParam(":datetime", $datetime);
            $stmt->execute();

            // var_dump($data_nascimento); exit;
            if ($stmt->execute()) {
                //  echo "teste 1"; exit;
                $data =
                    [
                        'nome_completo' => $nome_completo,
                        'identidade' => $identidade,
                        'data_nascimento' => $data_nascimento,
                        'estado_civil'             => $estado_civil,
                        'companheiro'              => $companheiro,
                        'pai'             => $pai,
                        'mae'             => $mae,
                        'sexo'                     => $sexo,
                        'nacionalidade'            => $nacionalidade,
                        'naturalidade'             => $naturalidade,
                        'autodeclaracao'           => $autodeclaracao,
                        'vaga_reservada'           => $vaga_reservada,
                        'uf'                       => $uf,
                        'bairro'                   => $bairro,
                        'cidade'                   => $id_cidade,
                        'cep'                      => $cep,
                        'rua'                      => $rua_num_complemento,
                        'telefone'                 => $telefone,
                        'celular'                  => $celular,
                        'mail'                     => $mail,
                        'incorporacao'             => $incorporacao,
                        'licenciamento'            => $licenciamento,
                        'prioridade_forca'        => $prioridade_forca,
                        'voluntario_12rm'          => $voluntario_12rm,
                        'nome_instituto_ensino'    => $nome_instituto_ensino,
                        'ano_formacao'             => $ano_formacao,
                        'uf_instituto_ensino'     => $uf_instituto_ensino,
                        'cidade_instituto_ensino' => $cidade_instituto_ensino,
                        'cidade_etapas_presenciais' => $cidade_etapas_presenciais,
                        'curso_graduacao'          => $curso_graduacao,
                        'ano_formacao_ofor'        => $ano_formacao_ofor,
                        'rm_inscricao'             => $rm_inscricao,
                        'rm_destino'               => $rm_destino,
                    ];


                $this->pdo->commit();
                //  echo "teste 2"; exit;
                return $data;
            } else {
                $this->pdo->rollBack();
                print_r($stmt->errorInfo());
                exit;
                return false;
            }
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
            exit;
            return false;
        }
    }



    public function edita_candidato(
        $id_candidato,
        $nome_completo,
        $identidade,
        $data_nascimento,
        $nome_social,
        $estado_civil,
        $companheiro,
        $dependente,
        $autodeclaracao,
        $vaga_reservada,
        $filiacao_pai,
        $filiacao_mae,
        $sexo,
        $nacionalidade,
        $naturalidade,
        $uf,
        $bairro,
        $cidade,
        $cep,
        $rua,
        $telefone,
        $celular,
        $mail,
        $tempo_sv_pub,
        $tempo_sv_pub_anos,
        $tempo_sv_pub_meses,
        $tempo_sv_pub_dias,
        $tempo_sv_mil,
        $tempo_sv_mil_anos,
        $tempo_sv_mil_meses,
        $tempo_sv_mil_dias,
        $civil_militar,
        $certificado,
        $documento,
        $data_expedicao,
        $ativa_reserva,
        $posto_grad,
        $forca,
        $arma,
        $incorporacao,
        $licenciamento,
        $prioridade_forca,
        $voluntario_12rm,
        $nome_instituto_ensino,
        $ano_formacao,
        $uf_instituto_ensino,
        $cidade_instituto_ensino,
        $cidade_etapas_presenciais,
        $curso_graduacao,
        $ano_formacao_ofor,
        $nota_ofor,
        $arma_eipot
    ) {
        try {

            $perfil = "candidato";

            $sqlInsert = "UPDATE usuario SET 
                     nome_completo                  =   :nome_completo,
                     identidade                     =   :identidade,
                     data_nascimento                =   :data_nascimento,
                     nome_social                    =   :nome_social, 
                     estado_civil                   =   :estado_civil,
                     companheiro                    =   :companheiro,
                     dependente                     =   :dependente,
                     autodeclaracao                 =   :autodeclaracao,
                     vaga_reservada                 =   :vaga_reservada,
                     pai                            =   :pai,
                     mae                            =   :mae, 
                     sexo                           =   :sexo,
                     nacionalidade                  =   :nacionalidade,
                     naturalidade                   =   :naturalidade,
                     uf                             =   :uf,
                     bairro                         =   :bairro,
                     id_cidade                      =   :cidade, 
                     cep                            =   :cep,
                     rua_num_complemento            =   :rua,
                     tel_residencial                =   :telefone,
                     tel_celular                    =   :celular,
                     mail                           =   :mail,
                     tempo_sv_pub                   =   :tempo_sv_pub,
                     tempo_sv_pub_anos              =   :tempo_sv_pub_anos,
                     tempo_sv_pub_meses             =   :tempo_sv_pub_meses,
                     tempo_sv_pub_dias              =   :tempo_sv_pub_dias,
                     tempo_sv_mil                   =   :tempo_sv_mil,
                     tempo_sv_mil_anos              =   :tempo_sv_mil_anos,
                     tempo_sv_mil_meses             =   :tempo_sv_mil_meses,
                     tempo_sv_mil_dias              =   :tempo_sv_mil_dias,
                     civil_militar                  =   :civil_militar,
                     certificado                    =   :certificado,
                     num_ducumento                  =   :documento,
                     data_expedicao                 =   :data_expedicao,
                     ativa_reserva                  =   :ativa_reserva,
                     posto_grad                     =   :posto_grad,
                     forca                          =   :forca,
                     arma_quadro_servico            =   :arma,
                     ano_incorporacao               =   :incorporacao,
                     licenciamento                  =   :licenciamento,
                     prioridade_forca               =   :prioridade_forca,
                     voluntario_12rm                =   :voluntario_12rm,
                     instituto_ensino               =   :instituto_ensino,
                     ano_formacao                   =   :ano_formacao,
                     uf_instituto_ensino            =   :uf_instituto_ensino,
                     id_cidade_instituto_ensino     =   :id_cidade_instituto_ensino,
                     cidade_etapas_presenciais      =   :cidade_etapas_presenciais,
                     curso_graduacao                =   :curso_graduacao,
                     ano_formacao_ofor              =   :ano_formacao_ofor,
                     nota_ofor                      =   :nota_ofor,
                     arma_eipot                     =   :arma_eipot
                  /*   rm_inscricao                  =   :rm_inscricao, */
                   /*  rm_destino                    =   :rm_destino, */
                 /*    _data_cadastro                 =   :datetime, */
                  /*   _assinatura                    =   :assinatura, */
                  /*   _data_ultima_atualizacao       =   :datetime*/
                   /*  _usuario_ultima_atualizacao    =   :id_user_atualizou */
                 WHERE id = :id_candidato";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);
            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":identidade", $identidade);
            $query->bindValue(":data_nascimento", $data_nascimento);
            $query->bindValue(":nome_social", $nome_social);
            $query->bindValue(":estado_civil", $estado_civil);
            $query->bindValue(":companheiro", $companheiro);
            $query->bindValue(":dependente", $dependente);
            $query->bindValue(":autodeclaracao", $autodeclaracao);
            $query->bindValue(":vaga_reservada", $vaga_reservada);
            $query->bindValue(":pai", $filiacao_pai);
            $query->bindValue(":mae", $filiacao_mae);
            $query->bindValue(":sexo", $sexo);
            $query->bindValue(":nacionalidade", $nacionalidade);
            $query->bindValue(":naturalidade", $naturalidade);
            $query->bindValue(":uf", $uf);
            $query->bindValue(":bairro", $bairro);
            $query->bindValue(":cidade", $cidade);
            $query->bindValue(":cep", $cep);
            $query->bindValue(":rua", $rua);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":celular", $celular);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":tempo_sv_pub", $tempo_sv_pub);
            $query->bindValue(":tempo_sv_pub_anos", $tempo_sv_pub_anos);
            $query->bindValue(":tempo_sv_pub_meses", $tempo_sv_pub_meses);
            $query->bindValue(":tempo_sv_pub_dias", $tempo_sv_pub_dias);
            $query->bindValue(":tempo_sv_mil", $tempo_sv_mil);
            $query->bindValue(":tempo_sv_mil_anos", $tempo_sv_mil_anos);
            $query->bindValue(":tempo_sv_mil_meses", $tempo_sv_mil_meses);
            $query->bindValue(":tempo_sv_mil_dias", $tempo_sv_mil_dias);
            $query->bindValue(":civil_militar", $civil_militar);
            $query->bindValue(":certificado", $certificado);
            $query->bindValue(":documento", $documento);
            $query->bindValue(":data_expedicao", $data_expedicao);
            $query->bindValue(":ativa_reserva", $ativa_reserva);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":forca", $forca);
            $query->bindValue(":arma", $arma);
            $query->bindValue(":incorporacao", $incorporacao);
            $query->bindValue(":licenciamento", $licenciamento);
            $query->bindValue(":prioridade_forca", $prioridade_forca);
            $query->bindValue(":voluntario_12rm", $voluntario_12rm);
            $query->bindValue(":instituto_ensino", $nome_instituto_ensino);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":uf_instituto_ensino", $uf_instituto_ensino);
            $query->bindValue(":id_cidade_instituto_ensino", $cidade_instituto_ensino);
            $query->bindValue(":cidade_etapas_presenciais", $cidade_etapas_presenciais);
            $query->bindValue(":curso_graduacao", $curso_graduacao);
            $query->bindValue(":ano_formacao_ofor", $ano_formacao_ofor);
            $query->bindValue(":nota_ofor", $nota_ofor);
            $query->bindValue(":arma_eipot", $arma_eipot);
            //    $query->bindValue(":rm_inscricao",$rm_inscricao);
            //    $query->bindValue(":rm_destino",$rm_destino);
            //    $query->bindValue(":datetime",$datetime);
            //    $query->bindValue(":id_user_atualizou",$_SESSION['id_usuario']);  

            if ($query->execute()) {

                $data =
                    [
                        'nome_completo' => $nome_completo,
                        'identidade' => $identidade,
                        'data_nascimento' => $data_nascimento,
                        'nome_social' => $nome_social,
                        'estado_civil' => $estado_civil,
                        'companheiro' => $companheiro,
                        'dependente' => $dependente,
                        'autodeclaracao' => $autodeclaracao,
                        'vaga_reservada' => $vaga_reservada,
                        'filiacao_pai' => $filiacao_pai,
                        'filiacao_mae' => $filiacao_mae,
                        'sexo' => $sexo,
                        'nacionalidade' => $nacionalidade,
                        'naturalidade' => $naturalidade,
                        'uf' => $uf,
                        'bairro' => $bairro,
                        'cidade' => $cidade,
                        'cep' => $cep,
                        'rua' => $rua,
                        'telefone' => $telefone,
                        'celular' => $celular,
                        'mail' => $mail,
                        'tempo_sv_pub' => $tempo_sv_pub,
                        'tempo_sv_pub_anos' => $tempo_sv_pub_anos,
                        'tempo_sv_pub_meses' => $tempo_sv_pub_meses,
                        'tempo_sv_pub_dias' => $tempo_sv_pub_dias,
                        'tempo_sv_mil' => $tempo_sv_mil,
                        'tempo_sv_mil_anos' => $tempo_sv_mil_anos,
                        'tempo_sv_mil_meses' => $tempo_sv_mil_meses,
                        'tempo_sv_mil_dias' => $tempo_sv_mil_dias,
                        'civil_militar' => $civil_militar,
                        'certificado' => $certificado,
                        'documento' => $documento,
                        'data_expedicao' => $data_expedicao,
                        'ativa_reserva' => $ativa_reserva,
                        'posto_grad' => $posto_grad,
                        'forca' => $forca,
                        'arma' => $arma,
                        'incorporacao' => $incorporacao,
                        'licenciamento' => $licenciamento,
                        'prioridade_forca' => $prioridade_forca,
                        'voluntario_12rm' => $voluntario_12rm,
                        'instituto_ensino' => $nome_instituto_ensino,
                        'ano_formacao' => $ano_formacao,
                        'uf_instituto_ensino' => $uf_instituto_ensino,
                        'id_cidade_instituto_ensino' => $cidade_instituto_ensino,
                        'cidade_etapas_presenciais' => $cidade_etapas_presenciais,
                        'curso_graduacao' => $curso_graduacao,
                        'ano_formacao_ofor' => $ano_formacao_ofor,
                        'nota_ofor' => $nota_ofor,
                        'arma_eipot' => $arma_eipot
                    ];
                //  'rm_inscricao'=>$rm_inscricao,
                //   'rm_destino'=>$rm_destino,
                //   'data_editado'=>$datetime,
                //   'usuario_editou'=>$_SESSION['id_usuario']


                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                // print_r($this->errorInfo());
                //     exit;
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Médico Obrigatório">
    public function insere_medico_obrigatorio(
        $cpf,
        $nome_completo,
        $nome_mae,
        $ra,
        $data_nascimento,
        $ano_formacao,
        $nome_instituto_ensino,
        $uf_instituto_ensino,
        $municipio_instituto_ensino,
        $conselho,
        $assinatura
    ) {
        try {

            $datetime = date('Y-m-d H:i:s');
            $perfil = "candidato";
            $valor_um = 1;
            $valor_zero = 0;
            $usuario_cadastrou = $_SESSION['id_usuario'];
            $selecao = $_SESSION['selecao'];

            $sqlInsert = "INSERT INTO usuario 
            (
                id_selecao, cpf, perfil, candidato, nome_completo, num_ducumento, medico_obrigatorio, concorrendo, etapa,
                mae, data_nascimento, ano_formacao, instituto_ensino, uf_instituto_ensino, id_cidade_instituto_ensino, conselho,
                assinatura_sistema, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao 
            )
            VALUES
            (
                :selecao, :cpf, :perfil, :valor_um, :nome_completo, :num_ducumento, :valor_um, :valor_um, :valor_um,
                :mae, :data_nascimento, :ano_formacao, :nome_instituto_ensino, :uf_instituto_ensino, :municipio_instituto_ensino, :conselho,
                :assinatura_sistema, :valor_zero, :datetime, :usuario_cadastrou
            )";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":selecao", $selecao);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":perfil", $perfil);
            $query->bindValue(":valor_zero", $valor_zero);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":valor_um", $valor_um);
            $query->bindValue(":mae", $nome_mae);
            $query->bindValue(":data_nascimento", $data_nascimento);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":num_ducumento", $ra);
            $query->bindValue(":nome_instituto_ensino", $nome_instituto_ensino);
            $query->bindValue(":uf_instituto_ensino", $uf_instituto_ensino);
            $query->bindValue(":municipio_instituto_ensino", $municipio_instituto_ensino);
            $query->bindValue(":assinatura_sistema", $assinatura);
            $query->bindValue(":conselho", $conselho);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'selecao' => $selecao,
                        'perfil' => $perfil,
                        'candidato' => $valor_um,
                        'etapa' => $valor_um,
                        'medico_obrigatorio' => $valor_um,
                        'nome_completo' => $nome_completo,
                        'cpf' => $cpf,
                        'nome_mae' => $nome_mae,
                        'ra' => $ra,
                        'data_nascimento' => $data_nascimento,
                        'ano_formacao' => $ano_formacao,
                        'nome_instituto_ensino' => $nome_instituto_ensino,
                        'uf_instituto_ensino' => $uf_instituto_ensino,
                        'municipio_instituto_ensino' => $municipio_instituto_ensino,
                        'conselho' => $conselho,
                        'assinatura_sistema' => $assinatura,
                        'apagado' => $valor_zero,
                        'data_cadastro' => $datetime,
                        'usuario_cadastrou' => $usuario_cadastrou
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita MÉDICO OBRIGATÓRIO">
    public function edita_medico_obrigatorio(
        $id_candidato,
        $nome_completo,
        $identidade,
        $data_nascimento,
        $nome_social,
        $estado_civil,
        $dependentes,
        $filiacao_pai,
        $sexo,
        $nascionalidade,
        $naturalidade,
        $filiacao_mae,
        $uf,
        $bairro,
        $cidade,
        $cep,
        $rua,
        $telefone,
        $celular,
        $mail,
        $tempo_sv_pub,
        $tempo_sv_pub_anos,
        $tempo_sv_pub_meses,
        $tempo_sv_pub_dias,
        $tempo_sv_mil,
        $tempo_sv_mil_anos,
        $tempo_sv_mil_meses,
        $tempo_sv_mil_dias,
        $civil_militar,
        $certificado,
        $documento,
        $data_expedicao,
        $ativa_reserva,
        $posto_grad,
        $forca,
        $arma,
        $incorporacao,
        $licenciamento,
        $num_dependentes,
        $nome_ie,
        $uf_ie,
        $ano_formacao,
        $cidade_ie,
        $ano_selecao_medico_obrigatorio,
        $conselho,
        $datetime
    ) {
        try {

            $perfil = "candidato";

            $sqlInsert = "UPDATE usuario SET 
                     nome_completo                  =   :nome_completo,
                     estado_civil                   =   :estado_civil, 
                     sexo                           =   :sexo,
                     nome_social                    =   :nome_social,
                     pai                            =   :pai,
                     mae                            =   :mae, 
                     identidade                     =   :identidade, 
                     nacionalidade                  =   :nascionalidade,
                     naturalidade                   =   :naturalidade,
                     dependente                     =   :dependentes,
                     data_nascimento                =   :data_nascimento,
                     uf                             =   :uf,
                     id_cidade                      =   :cidade, 
                     cep                            =   :cep,
                     rua_num_complemento            =   :rua,
                     bairro                         =   :bairro,
                     tel_residencial                =   :telefone,
                     tel_celular                    =   :celular,
                     mail                           =   :mail, 
                     tempo_sv_pub                   =   :tempo_sv_pub, 
                     tempo_sv_pub_anos              =   :tempo_sv_pub_anos,
                     tempo_sv_pub_meses             =   :tempo_sv_pub_meses,
                     tempo_sv_pub_dias              =   :tempo_sv_pub_dias,
                     tempo_sv_mil                   =   :tempo_sv_mil,
                     tempo_sv_mil_anos              =   :tempo_sv_mil_anos,
                     tempo_sv_mil_meses             =   :tempo_sv_mil_meses,
                     tempo_sv_mil_dias              =   :tempo_sv_mil_dias,
                     civil_militar                  =   :civil_militar,
                     certificado                    =   :certificado,
                     num_ducumento                  =   :documento,
                     data_expedicao                 =   :data_expedicao,
                     ativa_reserva                  =   :ativa_reserva,
                     forca                          =   :forca,
                     ano_incorporacao               =   :incorporacao,
                     posto_grad                     =   :posto_grad,
                     arma_quadro_servico            =   :arma,
                     licenciamento                  =   :licenciamento,
                     dependente                     =   :num_dependentes,
                     ano_formacao                   =   :ano_formacao,
                     instituto_ensino               =   :nome_ie,
                     uf_instituto_ensino            =   :uf_ie,
                     id_cidade_instituto_ensino     =   :cidade_ie,
                     ano_selecao_medico_obrigatorio =   :ano_selecao_medico_obrigatorio,
                     conselho                       =   :conselho,
                     _data_ultima_atualizacao       =   :datetime,
                     _usuario_ultima_atualizacao    =   :id_user_atualizou
                     WHERE id                       =   :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":identidade", $identidade);
            $query->bindValue(":data_nascimento", $data_nascimento);
            $query->bindValue(":nome_social", $nome_social);
            $query->bindValue(":estado_civil", $estado_civil);
            $query->bindValue(":dependentes", $dependentes);
            $query->bindValue(":pai", $filiacao_pai);
            $query->bindValue(":sexo", $sexo);
            $query->bindValue(":nascionalidade", $nascionalidade);
            $query->bindValue(":naturalidade", $naturalidade);
            $query->bindValue(":mae", $filiacao_mae);
            $query->bindValue(":uf", $uf);
            $query->bindValue(":bairro", $bairro);
            $query->bindValue(":cidade", $cidade);
            $query->bindValue(":cep", $cep);
            $query->bindValue(":rua", $rua);
            $query->bindValue(":telefone", $telefone);
            $query->bindValue(":celular", $celular);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":tempo_sv_pub", $tempo_sv_pub);
            $query->bindValue(":tempo_sv_pub_anos", $tempo_sv_pub_anos);
            $query->bindValue(":tempo_sv_pub_meses", $tempo_sv_pub_meses);
            $query->bindValue(":tempo_sv_pub_dias", $tempo_sv_pub_dias);
            $query->bindValue(":tempo_sv_mil", $tempo_sv_mil);
            $query->bindValue(":tempo_sv_mil_anos", $tempo_sv_mil_anos);
            $query->bindValue(":tempo_sv_mil_meses", $tempo_sv_mil_meses);
            $query->bindValue(":tempo_sv_mil_dias", $tempo_sv_mil_dias);
            $query->bindValue(":civil_militar", $civil_militar);
            $query->bindValue(":certificado", $certificado);
            $query->bindValue(":documento", $documento);
            $query->bindValue(":data_expedicao", $data_expedicao);
            $query->bindValue(":ativa_reserva", $ativa_reserva);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":forca", $forca);
            $query->bindValue(":arma", $arma);
            $query->bindValue(":incorporacao", $incorporacao);
            $query->bindValue(":licenciamento", $licenciamento);
            $query->bindValue(":num_dependentes", $num_dependentes);
            $query->bindValue(":nome_ie", $nome_ie);
            $query->bindValue(":uf_ie", $uf_ie);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":cidade_ie", $cidade_ie);
            $query->bindValue(":ano_selecao_medico_obrigatorio", $ano_selecao_medico_obrigatorio);
            $query->bindValue(":conselho", $conselho);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);


            if ($query->execute()) {

                $data =
                    [
                        'nome_completo' => $nome_completo,
                        'identidade' => $identidade,
                        'data_nascimento' => $data_nascimento,
                        'nome_social' => $nome_social,
                        'estado_civil' => $estado_civil,
                        'dependentes' => $dependentes,
                        'filiacao_pai' => $filiacao_pai,
                        'sexo' => $sexo,
                        'nascionalidade' => $nascionalidade,
                        'naturalidade' => $naturalidade,
                        'filiacao_mae' => $filiacao_mae,
                        'uf' => $uf,
                        'bairro' => $bairro,
                        'cidade' => $cidade,
                        'cep' => $cep,
                        'rua' => $rua,
                        'telefone' => $telefone,
                        'celular' => $celular,
                        'mail' => $mail,
                        'tempo_sv_pub' => $tempo_sv_pub,
                        'tempo_sv_pub_anos' => $tempo_sv_pub_anos,
                        'tempo_sv_pub_meses' => $tempo_sv_pub_meses,
                        'tempo_sv_pub_dias' => $tempo_sv_pub_dias,
                        'tempo_sv_mil' => $tempo_sv_mil,
                        'tempo_sv_mil_anos' => $tempo_sv_mil_anos,
                        'tempo_sv_mil_meses' => $tempo_sv_mil_meses,
                        'tempo_sv_mil_dias' => $tempo_sv_mil_dias,
                        'civil_militar' => $civil_militar,
                        'certificado' => $certificado,
                        'documento' => $documento,
                        'data_expedicao' => $data_expedicao,
                        'ativa_reserva' => $ativa_reserva,
                        'posto_grad' => $posto_grad,
                        'forca' => $forca,
                        'arma' => $arma,
                        'incorporacao' => $incorporacao,
                        'licenciamento' => $licenciamento,
                        'num_dependentes' => $num_dependentes,
                        'nome_ie' => $nome_ie,
                        'uf_ie' => $uf_ie,
                        'ano_formacao' => $ano_formacao,
                        'cidade_ie' => $cidade_ie,
                        'ano_selecao_medico_obrigatorio' => $ano_selecao_medico_obrigatorio,
                        'conselho' => $conselho,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita MÉDICO OBRIGATÓRIO OUTRAS INFO">
    public function edita_medico_obrigatorio_outras_info(
        $id_medico,
        $voluntario_12rm,
        $voluntario_sv_militar,
        $prioridade_forca,
        $obrigatorio,
        $situacao_militar,
        $antecedentes,
        $forum_civil,
        $forum_criminal,
        $arrimo
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                     voluntario_12rm               =   :voluntario_12rm,
                     voluntario_sv_militar         =   :voluntario_sv_militar, 
                     prioridade_forca              =   :prioridade_forca,
                     obrigatorio                   =   :obrigatorio,
                     situacao_militar              =   :situacao_militar,
                     antecedentes                  =   :antecedentes,
                     forum_civil                   =   :forum_civil,
                     forum_criminal                =   :forum_criminal,
                     arrimo                        =   :arrimo,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_medico";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_medico", $id_medico);
            $query->bindValue(":voluntario_12rm", $voluntario_12rm);
            $query->bindValue(":voluntario_sv_militar", $voluntario_sv_militar);
            $query->bindValue(":prioridade_forca", $prioridade_forca);
            $query->bindValue(":obrigatorio", $obrigatorio);
            $query->bindValue(":situacao_militar", $situacao_militar);
            $query->bindValue(":antecedentes", $antecedentes);
            $query->bindValue(":forum_civil", $forum_civil);
            $query->bindValue(":forum_criminal", $forum_criminal);
            $query->bindValue(":arrimo", $arrimo);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_medico' => $id_medico,
                        'voluntario_12rm' => $voluntario_12rm,
                        'voluntario_sv_militar' => $voluntario_sv_militar,
                        'prioridade_forca' => $prioridade_forca,
                        'obrigatorio' => $obrigatorio,
                        'situacao_militar' => $situacao_militar,
                        'antecedentes' => $antecedentes,
                        'forum_civil' => $forum_civil,
                        'forum_criminal' => $forum_criminal,
                        'arrimo' => $arrimo,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita EXAME MÉDICO">
    public function candidato_edita_exame_medico(
        $id_candidato,
        $apto_saude,
        $grupo_saude,
        $data_exame_saude,
        $cid_saude,
        $observacao,
        $ata_is
    ) {
        try {
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                        apto_saude             =   :apto_saude,
                        grupo_saude            =   :grupo_saude, 
                        data_exame_saude       =   :data_exame_saude,
                        cid_saude              =   :cid_saude,
                        observacao_exame_saude =   :obs,
                        ata_is               =   :ata_is,
                        _data_ultima_atualizacao      =   :datetime,
                        _usuario_ultima_atualizacao   =   :id_user_atualizou
                        WHERE id                      =   :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":apto_saude", $apto_saude);
            $query->bindValue(":grupo_saude", $grupo_saude);
            $query->bindValue(":data_exame_saude", $data_exame_saude);
            $query->bindValue(":cid_saude", $cid_saude);
            $query->bindValue(":obs", $observacao);
            $query->bindValue(":ata_is", $ata_is);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'apto_saude' => $apto_saude,
                        'grupo_saude' => $grupo_saude,
                        'data_exame_saude' => $data_exame_saude,
                        'cid_saude' => $cid_saude,
                        'observacao' => $observacao,
                        'ata_is' => $ata_is,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita EXAME MÉDICO">
    public function candidato_edita_distribuicao(
        $id_candidato,
        $incorporado,
        $numero_distribuicao,
        $forca_distribuicao,
        $om_distribuicao,
        $uf_distribuicao,
        $titular_reserva_distribuicao,
        $id_cidade_distribuicao,
        $uf2,
        $cidade2,
        $om_distribuicao_1_fase,
        $data_incorporacao,
        $especialidade_incorporou,
        $aditamento_convocacao,
        $observacao_distribuicao
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                        incorporado                  =   :incorporado,
                        numero_distribuicao          =   :distribuicao,
                        forca_distribuicao           =   :forca, 
                        om_distribuicao              =   :om,
                        uf_distribuicao              =   :uf,
                        titular_reserva_distribuicao =   :titular_reserva,
                        id_cidade_distribuicao       =   :id_cidade,
                        
                        om_1_fase = :om_1_fase1,
                        uf_1_fase = :uf_1_fase1,
                        id_cidade_1_fase = :id_cidade_1_fase1,

                        especialidade_incorporacao = :especialidade_incorporacao,
                        data_incorporacao = :data_incorporacao,

                        aditamento_convocacao = :aditamento_convocacao,
                        observacao_distribuicao = :observacao_distribuicao,

                        _data_ultima_atualizacao     =   :datetime,
                        _usuario_ultima_atualizacao  =   :id_user_atualizou
                        WHERE id                     =   :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":incorporado", $incorporado);
            $query->bindValue(":distribuicao", $numero_distribuicao);
            $query->bindValue(":forca", $forca_distribuicao);
            $query->bindValue(":om", $om_distribuicao);
            $query->bindValue(":uf", $uf_distribuicao);
            $query->bindValue(":titular_reserva", $titular_reserva_distribuicao);
            $query->bindValue(":id_cidade", $id_cidade_distribuicao);

            $query->bindValue(":om_1_fase1", $om_distribuicao_1_fase);
            $query->bindValue(":uf_1_fase1", $uf2);
            $query->bindValue(":id_cidade_1_fase1", $cidade2);

            $query->bindValue(":especialidade_incorporacao", $especialidade_incorporou);
            $query->bindValue(":data_incorporacao", $data_incorporacao);

            $query->bindValue(":aditamento_convocacao", $aditamento_convocacao);
            $query->bindValue(":observacao_distribuicao", $observacao_distribuicao);

            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            //echo $sqlInsert; exit();

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'incorporado' => $incorporado,
                        'distribuicao' => $numero_distribuicao,
                        'forca' => $forca_distribuicao,
                        'om' => $om_distribuicao,
                        'uf' => $uf_distribuicao,
                        'titular_reserva' => $titular_reserva_distribuicao,
                        'id_cidade' => $id_cidade_distribuicao,

                        'om_1_fase' => $om_distribuicao_1_fase,
                        'uf_1_fase' => $uf2,
                        'id_cidade_1_fase' => $cidade2,

                        'especialidade_incorporacao' => $especialidade_incorporou,
                        'data_incorporacao' => $data_incorporacao,

                        'aditamento_convocacao' => $aditamento_convocacao,
                        'observacao_distribuicao' => $observacao_distribuicao,

                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita EXAME MÉDICO RECUSRO">
    public function candidato_edita_exame_medico_recurso(
        $id_candidato,
        $apto_saude,
        $grupo_saude,
        $data_exame_saude,
        $cid_saude,
        $observacao,
        $ata_is_recurso
    ) {
        try {
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                        apto_saude_recurso             =   :apto_saude,
                        grupo_saude_recurso            =   :grupo_saude, 
                        data_exame_saude_recurso       =   :data_exame_saude,
                        cid_saude_recurso              =   :cid_saude,
                        observacao_exame_saude_recurso =   :obs,
                        ata_is_recurso                 =   :ata_is_recurso,
                        _data_ultima_atualizacao      =   :datetime,
                        _usuario_ultima_atualizacao   =   :id_user_atualizou
                        WHERE id                      =   :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":apto_saude", $apto_saude);
            $query->bindValue(":grupo_saude", $grupo_saude);
            $query->bindValue(":data_exame_saude", $data_exame_saude);
            $query->bindValue(":cid_saude", $cid_saude);
            $query->bindValue(":obs", $observacao);
            $query->bindValue(":ata_is_recurso", $ata_is_recurso);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'apto_saude_recurso' => $apto_saude,
                        'grupo_saude_recurso' => $grupo_saude,
                        'data_exame_saude_recurso' => $data_exame_saude,
                        'cid_saude_recurso' => $cid_saude,
                        'observacao_recurso' => $observacao,
                        'ata_is_recurso' => $ata_is_recurso,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita CANDIDATO IMPEDIDO JUDICIAL">
    public function edita_candidato_impedido_judicial(
        $id_candidato,
        $refratario_impedido,
        $historico_judicial,
        $numero_acao,
        $data_liminar,
        $transitou_julgado,
        $favoravel_desfavoravel,
        $convocado,
        $publicacao_bar_reg
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                     refratario_impedido           =   :refratario_impedido,
                     historico_judicial            =   :historico_judicial, 
                     numero_acao                   =   :numero_acao,
                     data_liminar                  =   :data_liminar,
                     transitou_julgado             =   :transitou_julgado,
                     favoravel_desfavoravel        =   :favoravel_desfavoravel,
                     convocado                     =   :convocado,
                     publicacao_bar_reg            =   :publicacao_bar_reg,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":refratario_impedido", $refratario_impedido);
            $query->bindValue(":historico_judicial", $historico_judicial);
            $query->bindValue(":numero_acao", $numero_acao);
            $query->bindValue(":data_liminar", $data_liminar);
            $query->bindValue(":transitou_julgado", $transitou_julgado);
            $query->bindValue(":favoravel_desfavoravel", $favoravel_desfavoravel);
            $query->bindValue(":convocado", $convocado);
            $query->bindValue(":publicacao_bar_reg", $publicacao_bar_reg);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'refratario_impedido' => $refratario_impedido,
                        'historico_judicial' => $historico_judicial,
                        'numero_acao' => $numero_acao,
                        'data_liminar' => $data_liminar,
                        'transitou_julgado' => $transitou_julgado,
                        'favoravel_desfavoravel' => $favoravel_desfavoravel,
                        'convocado' => $convocado,
                        'publicacao_bar_reg' => $publicacao_bar_reg,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita RECURSO OFÍCIO">
    public function edita_recurso_oficio(
        $id_recurso,
        $id_candidato,
        $status_final,
        $cidade_data,
        $presidente,
        $paragrafo1,
        $paragrafo2,
        $id_especialidade,
        $para_avaliador
    ) {
        try {

            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE recurso SET 
                     id_candidato           =   :id_candidato,
                     status_final           =   :status_final, 
                     cidade_data            =   :cidade_data, 
                     presidente             =   :presidente,
                     paragrafo1             =   :paragrafo1,
                     paragrafo2             =   :paragrafo2,
                     para_avaliador             =   :para_avaliador,
                     id_especialidade             =   :id_especialidade,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_recurso";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_recurso", $id_recurso);
            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":status_final", $status_final);
            $query->bindValue(":cidade_data", $cidade_data);
            $query->bindValue(":presidente", $presidente);
            $query->bindValue(":paragrafo1", $paragrafo1);
            $query->bindValue(":paragrafo2", $paragrafo2);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":para_avaliador", $para_avaliador);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $data =
                    [
                        'id_recurso' => $id_recurso,
                        'id_candidato' => $id_candidato,
                        'status_final' => $status_final,
                        'cidade_data' => $cidade_data,
                        'presidente' => $presidente,
                        'paragrafo1' => $paragrafo1,
                        'paragrafo2' => $paragrafo2,
                        'id_especialidade' => $id_especialidade,
                        'para_avaliador' => $para_avaliador,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Analise recurso do Avaliador RECURSO OFÍCIO">
    public function avaliador_analisa_recurso(
        $id_recurso,
        $status,
        $analise
    ) {
        try {

            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE recurso SET 
                     status            =   :status, 
                     analise             =   :analise,
                     data_analise = :datetime,
                     id_usuario_analise = :id_user_atualizou,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_recurso";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_recurso", $id_recurso);
            $query->bindValue(":status", $status);
            $query->bindValue(":analise", $analise);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $data =
                    [
                        'id_recurso' => $id_recurso,
                        'status' => $status,
                        'analise' => $analise,
                        'data_analise' => $datetime,
                        'id_usuario_analise' => $_SESSION['id_usuario'],
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita MÉDICO OBRIGATÓRIO REFRATÁRIO/IMPEDIDO">
    public function edita_medico_obrigatorio_refratario_impedido(
        $id_medico,
        $refratario_impedido,
        $historico_judicial,
        $numero_acao,
        $data_liminar,
        $transitou_julgado,
        $favoravel_desfavoravel,
        $convocado,
        $publicacao_bar_reg
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                     refratario_impedido           =   :refratario_impedido,
                     historico_judicial            =   :historico_judicial, 
                     numero_acao                   =   :numero_acao,
                     data_liminar                  =   :data_liminar,
                     transitou_julgado             =   :transitou_julgado,
                     favoravel_desfavoravel        =   :favoravel_desfavoravel,
                     convocado                     =   :convocado,
                     publicacao_bar_reg            =   :publicacao_bar_reg,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_medico";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_medico", $id_medico);
            $query->bindValue(":refratario_impedido", $refratario_impedido);
            $query->bindValue(":historico_judicial", $historico_judicial);
            $query->bindValue(":numero_acao", $numero_acao);
            $query->bindValue(":data_liminar", $data_liminar);
            $query->bindValue(":transitou_julgado", $transitou_julgado);
            $query->bindValue(":favoravel_desfavoravel", $favoravel_desfavoravel);
            $query->bindValue(":convocado", $convocado);
            $query->bindValue(":publicacao_bar_reg", $publicacao_bar_reg);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_medico' => $id_medico,
                        'refratario_impedido' => $refratario_impedido,
                        'historico_judicial' => $historico_judicial,
                        'numero_acao' => $numero_acao,
                        'data_liminar' => $data_liminar,
                        'transitou_julgado' => $transitou_julgado,
                        'favoravel_desfavoravel' => $favoravel_desfavoravel,
                        'convocado' => $convocado,
                        'publicacao_bar_reg' => $publicacao_bar_reg,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita MÉDICO OBRIGATÓRIO EXAME MÉDICO">
    public function edita_medico_obrigatorio_adiamento(
        $id_medico,
        $solicitou_adiamento,
        $data_inicio_adiamento,
        $data_fim_adiamento,
        $especialidade_adiamento
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                     solicitou_adiamento           =   :solicitou_adiamento,
                     data_inicio_adiamento         =   :data_inicio_adiamento, 
                     data_fim_adiamento            =   :data_fim_adiamento,
                     especialidade_adiamento       =   :especialidade_adiamento,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_medico";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_medico", $id_medico);
            $query->bindValue(":solicitou_adiamento", $solicitou_adiamento);
            $query->bindValue(":data_inicio_adiamento", $data_inicio_adiamento);
            $query->bindValue(":data_fim_adiamento", $data_fim_adiamento);
            $query->bindValue(":especialidade_adiamento", $especialidade_adiamento);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_medico' => $id_medico,
                        'solicitou_adiamento' => $solicitou_adiamento,
                        'data_inicio_adiamento' => $data_inicio_adiamento,
                        'data_fim_adiamento' => $data_fim_adiamento,
                        'especialidade_adiamento' => $especialidade_adiamento,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita MÉDICO OBRIGATÓRIO FISEMI">
    public function edita_medico_obrigatorio_fisemi(
        $id_medico,
        $transferencia_fisemi,
        $fisemi_rm_origem,
        $fisemi_rm_destino
    ) {
        try {

            $perfil = "candidato";
            $datetime = date('Y-m-d H:i:s');

            $sqlInsert = "UPDATE usuario SET 
                     transferencia_fisemi             =   :transferencia_fisemi,
                     fisemi_rm_origem            =   :fisemi_rm_origem, 
                     fisemi_rm_destino       =   :fisemi_rm_destino,
                     _data_ultima_atualizacao      =   :datetime,
                     _usuario_ultima_atualizacao   =   :id_user_atualizou
                     WHERE id                      =   :id_medico";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_medico", $id_medico);
            $query->bindValue(":transferencia_fisemi", $transferencia_fisemi);
            $query->bindValue(":fisemi_rm_origem", $fisemi_rm_origem);
            $query->bindValue(":fisemi_rm_destino", $fisemi_rm_destino);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_user_atualizou", $_SESSION['id_usuario']);

            if ($query->execute()) {

                $data =
                    [
                        'id_medico' => $id_medico,
                        'transferencia_fisemi' => $transferencia_fisemi,
                        'fisemi_rm_origem' => $fisemi_rm_origem,
                        'fisemi_rm_destino' => $fisemi_rm_destino,
                        'data_editado' => $datetime,
                        'usuario_editou' => $_SESSION['id_usuario'],
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Usuario">
    public function insere_usuario(
        $selecao,
        $cpf,
        $perfil,
        $nome_completo,
        $senha,
        $nome_guerra,
        $om,
        $posto_grad,
        $telefone,
        $mail,
        $assinatura,
        $datetime
    ) {
        try {

            $valor_um = 1;
            $valor_zero = 0;
            $usuario_cadastrou = $_SESSION['id_usuario'];

            $sqlInsert = "INSERT INTO usuario 
            (
                id_selecao, cpf, perfil, candidato, nome_completo, trocar_senha, senha, 
                nome_guerra, id_om, posto_grad, tel_celular, mail,
                assinatura_sistema, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao 
            )
            VALUES
            (
                :selecao, :cpf, :perfil, :valor_zero, :nome_completo, :valor_um, :senha, 
                :nome_guerra, :id_om, :posto_grad, :tel_residencial, :mail,
                :assinatura_sistema, :valor_zero, :datetime, :usuario_cadastrou
            )";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":selecao", $selecao);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":perfil", $perfil);
            $query->bindValue(":valor_zero", $valor_zero);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":valor_um", $valor_um);
            $query->bindValue(":senha", $senha);
            $query->bindValue(":nome_guerra", $nome_guerra);
            $query->bindValue(":id_om", $om);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":tel_residencial", $telefone);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":assinatura_sistema", $assinatura);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);



            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'selecao' => $selecao,
                        'senha' => $senha,
                        'perfil' => $perfil,
                        'nome_completo' => $nome_completo,
                        'cpf' => $cpf,
                        'telefone' => $telefone,
                        'mail' => $mail,
                        'posto_grad' => $posto_grad,
                        'nome_guerra' => $nome_guerra,
                        'om' => $om,
                        'assinatura_sistema' => $assinatura,
                        'data_cadastro' => $datetime,
                        'usuario_cadastrou' => $usuario_cadastrou
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Edita Usuario">
    public function edita_usuario(
        $id_usuario,
        $perfil,
        $nome_completo,
        $nome_guerra,
        $om,
        $posto_grad,
        $telefone,
        $mail
    ) {
        try {

            $datetime = date('Y-m-d H:i:s');
            $usuario_editou = $_SESSION['id_usuario'];
            $valor_um = 1;
            $valor_zero = 0;

            $sqlInsert = "
                    UPDATE usuario SET 
                    perfil                      = :perfil,
                    nome_completo               = :nome_completo, 
                    nome_guerra                 = :nome_guerra,
                    id_om                       = :id_om,
                    posto_grad                  = :posto_grad,
                    tel_celular                 = :tel_residencial,
                    mail                        = :mail,
                    _data_ultima_atualizacao    = :datetime,
                    _usuario_ultima_atualizacao = :usuario_editou
                    WHERE id                    = :id_usuario";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_usuario", $id_usuario);
            $query->bindValue(":perfil", $perfil);
            $query->bindValue(":nome_completo", $nome_completo);
            $query->bindValue(":nome_guerra", $nome_guerra);
            $query->bindValue(":id_om", $om);
            $query->bindValue(":posto_grad", $posto_grad);
            $query->bindValue(":tel_residencial", $telefone);
            $query->bindValue(":mail", $mail);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_editou", $usuario_editou);



            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id_usuario,
                        'perfil' => $perfil,
                        'nome_completo' => $nome_completo,
                        'telefone' => $telefone,
                        'mail' => $mail,
                        'posto_grad' => $posto_grad,
                        'nome_guerra' => $nome_guerra,
                        'om' => $om,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_editou
                    ];

                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Especialidade">
    public function insere_especialidade($nome, $teste, $ott_stt, $musica)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "INSERT INTO especialidade
            (id_selecao, nome, teste_pratico, ott_stt, musica, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
            VALUES
            (:id_selecao, :nome, :teste, :ott_stt, :musica, :zero, :datetime, :usuario_cadastrou)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $id_selecao);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":teste", $teste);
            $query->bindValue(":ott_stt", $ott_stt);
            $query->bindValue(":musica", $musica);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
            $query->bindValue(":zero", $zero);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_selecao' => $id_selecao,
                        'nome' => $nome,
                        'teste' => $teste,
                        'ott_stt' => $ott_stt,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    public function insere_especialidade_eipot($nome, $teste, $eipot, $musica)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;
        $teste = 0;
        $musica = 0;

        try {
            $sqlInsert = "INSERT INTO especialidade
            (id_selecao, nome, teste_pratico, ott_stt, musica, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
            VALUES
            (:id_selecao, UPPER(:nome), :teste, :eipot, :musica, :zero, :datetime, :usuario_cadastrou)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $id_selecao);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":teste", $teste);
            $query->bindValue(":eipot", $eipot);
            $query->bindValue(":musica", $musica);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
            $query->bindValue(":zero", $zero);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_selecao' => $id_selecao,
                        'nome' => $nome,
                        'teste' => $teste,
                        'ott_stt' => $eipot,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Suporte">
    public function insere_suporte($id_remetente, $motivo, $mensagem)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "
            INSERT INTO suporte_candidato (id_usuario_remetente, motivo, mensagem, data_enviado, apagado, respondida, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
            VALUES (:remetente, :motivo, :mensagem, :datetime, :zero, :zero, :datetime, :usuario_cadastrou)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":remetente", $id_remetente);
            $query->bindValue(":motivo", $motivo);
            $query->bindValue(":mensagem", $mensagem);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
            $query->bindValue(":zero", $zero);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'remetente' => $id_remetente,
                        'motivo' => $motivo,
                        'mensagem' => $mensagem,
                        'datetime' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Especialidade para Candidato">
    public function insere_especialidade_candidato($id_candidato, $id_especialidade, $registro_conselho, $data_habilitacao, $etapa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $zero = 0;
        $um = 1;

        try {
            $sqlInsert = "INSERT INTO candidato_x_especialidade 
                (id_candidato, id_especialidade, etapa, registro_conselho, data_habilitacao, concorrendo, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
                VALUES (:id_candidato, :id_especialidade, :etapa, :registro_conselho, :data_habilitacao, :um, :zero, :datetime, :usuario_cadastrou)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":etapa", $etapa);
            $query->bindValue(":data_habilitacao", $data_habilitacao);
            $query->bindValue(":registro_conselho", $registro_conselho);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":um", $um);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'id_especialidade' => $id_especialidade,
                        'etapa' => $etapa,
                        'data_habilitacao' => $data_habilitacao,
                        'registro_conselho' => $registro_conselho,
                        'concorrendo' => $um,
                        'usuario_cadastrou' => $usuario_cadastrou,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    public function insere_especialidade_candidato_eipot($id_candidato, $id_especialidade)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $zero = 0;
        $um = 1;

        try {
            $sqlInsert = "INSERT INTO candidato_x_especialidade 
                (id_candidato, id_especialidade, concorrendo, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
                VALUES (:id_candidato, :id_especialidade, :um, :zero, :datetime, :usuario_cadastrou)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":um", $um);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'id_especialidade' => $id_especialidade,
                        'concorrendo' => $um,
                        'usuario_cadastrou' => $usuario_cadastrou,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Especialidade_X_Cidade">
    public function insere_especialidade_x_cidade($especialidade, $cidades)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;
        $data[] = null;

        $data =
            [
                'id_especialidade' => $especialidade,
                'datetime' => $datetime,
                'apagado' => $zero,
                '_data_ultima_atualizacao' => $datetime,
                '_usuario_ultima_atualizacao' => $usuario_cadastrou,
            ];
        $count = 1;
        foreach ($cidades as &$id_cidade) {
            try {
                $sqlInsert = "INSERT INTO cidade_x_especialidade
                (id_especialidade, id_cidade, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
                VALUES
                (:id_especialidade, :id_cidade, :zero, :datetime, :usuario_cadastrou)";

                $this->pdo->beginTransaction();

                $query = $this->pdo->prepare($sqlInsert);

                $query->bindValue(":id_especialidade", $especialidade);
                $query->bindValue(":id_cidade", $id_cidade);
                $query->bindValue(":datetime", $datetime);
                $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
                $query->bindValue(":zero", $zero);

                if ($query->execute()) {
                    $this->pdo->commit();
                    $nome = "id_cidade_adicionada" . $count;
                    $data[$nome] = $id_cidade;
                    $count++;
                } else {
                    $this->pdo->rollBack();
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return $data;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Avaliador">
    public function insere_avaliador($usuario, $especialidades)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_cadastrou = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;
        $data[] = null;

        $data =
            [
                'id_usuario' => $usuario,
                'datetime' => $datetime,
                'apagado' => $zero,
                '_data_ultima_atualizacao' => $datetime,
                '_usuario_ultima_atualizacao' => $usuario_cadastrou,
            ];
        $count = 1;
        foreach ($especialidades as &$id_especialidade) {
            if ($id_especialidade != 0 && $id_especialidade != null) {
                try {
                    $sqlInsert = "INSERT INTO avaliador
                    (id_especialidade, id_usuario, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao)
                    VALUES
                    (:id_especialidade, :id_usuario, :zero, :datetime, :usuario_cadastrou)";

                    $this->pdo->beginTransaction();

                    $query = $this->pdo->prepare($sqlInsert);

                    $query->bindValue(":id_usuario", $usuario);
                    $query->bindValue(":id_especialidade", $id_especialidade);
                    $query->bindValue(":datetime", $datetime);
                    $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);
                    $query->bindValue(":zero", $zero);

                    if ($query->execute()) {
                        $this->pdo->commit();
                        $nome = "id_especialidade_adicionada_" . $count;
                        $data[$nome] = $id_especialidade;
                        $count++;
                    } else {
                        $this->pdo->rollBack();
                        return false;
                    }
                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return $data;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade">
    public function apaga_especialidade_usuario($id)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE avaliador SET apagado='1' WHERE id_usuario= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'id_especialidade' => "Apagou todas as especialidades",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Cidades da Especialidade">
    public function apaga_cidades_especialidade($id)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE cidade_x_especialidade SET apagado='1' WHERE id_especialidade= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade' => $id,
                        'id_cidade' => "Apagou todas as cidades",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Verifica Especialidades Candidato">
    public function verifica_especialidade_candidato($id_usuario, $id_especialidade)
    {
        $stmt = $this->pdo->prepare(
            "
                    select ce.id id_candidato_x_especialidade, cidade.nome nome_cidade, ce._data_ultima_atualizacao, e.id id_especialidade, u.nome_completo, u.cpf, e.nome especialidade, e.ott_stt,
                    ce.prova_pratica_musica, ce.nota_prova_teorico_pratico, ce.prova_teorica_musica ,ce.prova_oral_musica, ce.usuario_avaliou_provas_musica, ce.concorrendo, ce.justificativa, ce.id_usuario_alterou_concorrendo
                    from candidato_x_especialidade ce
                    left join cidade on cidade.id = ce.cidade_escolheu_servir
                    inner join usuario u on u.id = ce.id_candidato
                    inner join especialidade e on e.id = ce.id_especialidade
                    where u.id = :id_usuario and e.id = :id_especialidade and ce.apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function verifica_especialidade_candidato_eipot($id_usuario)
    {
        $stmt = $this->pdo->prepare(
            "
                    select ce.id id_candidato_x_especialidade, cidade.nome nome_cidade, ce._data_ultima_atualizacao, e.id id_especialidade, u.nome_completo, u.cpf, e.nome especialidade, e.ott_stt,
                    ce.prova_pratica_musica, ce.nota_prova_teorico_pratico, ce.prova_teorica_musica ,ce.prova_oral_musica, ce.usuario_avaliou_provas_musica, ce.concorrendo, ce.justificativa, ce.id_usuario_alterou_concorrendo
                    from candidato_x_especialidade ce
                    left join cidade on cidade.id = ce.cidade_escolheu_servir
                    inner join usuario u on u.id = ce.id_candidato
                    inner join especialidade e on e.id = ce.id_especialidade
                    where u.id = :id_usuario and ce.apagado = 0"
        );
        $stmt->bindValue(':id_usuario', $id_usuario);
        //  $stmt->bindValue(':id_especialidade', $id_especialidade);
        $run = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade">
    public function apaga_especialidade($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE especialidade SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade' => $id,
                        'nome' => $nome,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade">
    public function apaga_curriculo($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE curriculo SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_curriculo' => $id,
                        'nome' => $nome,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Exame Médico">
    public function apaga_exame_medico($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE exame_medico SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id' => $id,
                        'exame' => $nome,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Arquivo Obrigatório">

    public function insere_arquivo_obrigatorio($id_candidato, $id_documentacao_obrigatoria, $label, $nome, $extensao, $nome_original, $tamanho_do_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_cadastrou = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "INSERT INTO documento_obrigatorio
            (id_candidato, id_documentacao_obrigatoria, label, nome, extensao, nome_original, tamanho, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao ) 
            VALUES (:id_candidato, :id_documentacao_obrigatoria, :label, :nome, :extensao, :nome_original, :tamanho_do_arquivo, :zero, :datetime, :usuario_cadastrou )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_documentacao_obrigatoria", $id_documentacao_obrigatoria);
            $query->bindValue(":label", $label);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho_do_arquivo", $tamanho_do_arquivo);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'id_documentacao_obrigatoria' => $id_documentacao_obrigatoria,
                        'label' => $label,
                        'nome' => $nome,
                        'tamanho_do_arquivo' => $tamanho_do_arquivo,
                        'extensao' => $extensao,
                        'nome_original' => $nome_original,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Currículo">

    public function insere_curriculo($id_candidato_x_especialidade, $id_curriculo, $label, $nome, $extensao, $nome_original, $tamanho_arquivo, $data_inicio, $data_fim, $carga_horaria)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_cadastrou = $_SESSION['id_usuario'];
        try {

            $sqlInsert = "INSERT INTO especialidade_curriculo
            (id_candidato_x_especialidade, id_curriculo, label, nome, extensao, nome_original, tamanho, data_inicio,data_termino, carga_horaria, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao) 
            VALUES (:id_candidato_x_especialidade, :id_curriculo, :label, :nome, :extensao, :nome_original, :tamanho, :data_inicio, :data_fim, :carga_horaria, :zero, :datetime, :usuario_cadastrou )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato_x_especialidade", $id_candidato_x_especialidade);
            $query->bindValue(":id_curriculo", $id_curriculo);
            $query->bindValue(":label", $label);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho", $tamanho_arquivo);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":data_inicio", $data_inicio);
            $query->bindValue(":data_fim", $data_fim);
            $query->bindValue(":carga_horaria", $carga_horaria);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato_x_especialidade' => $id_candidato_x_especialidade,
                        'id_curriculo' => $id_curriculo,
                        'label' => $label,
                        'nome' => $nome,
                        'extensao' => $extensao,
                        'data_inicio' => $data_inicio,
                        'data_fim' => $data_fim,
                        'carga_horaria' => $carga_horaria,
                        'nome_original' => $nome_original,
                        'tamanho' => $tamanho_arquivo,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Arquivo Pagamento">

    public function insere_arquivo_pagamento($id_candidato, $nome, $extensao, $nome_original, $tamanho_do_arquivo, $isento)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_cadastrou = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "INSERT INTO pagamento_inscricao
            (id_candidato, nome, extensao, nome_original, tamanho, isento, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao ) 
            VALUES (:id_candidato, :nome, :extensao, :nome_original, :tamanho_do_arquivo, :isento, :zero, :datetime, :usuario_cadastrou )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho_do_arquivo", $tamanho_do_arquivo);
            $query->bindValue(":isento", $isento);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'nome' => $nome,
                        'isento' => $isento,
                        'tamanho_do_arquivo' => $tamanho_do_arquivo,
                        'extensao' => $extensao,
                        'nome_original' => $nome_original,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Arquivo Pagamento">

    public function insere_arquivo_recurso($id_candidato, $nome, $extensao, $nome_original, $tamanho_do_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_cadastrou = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "INSERT INTO recurso
            (id_candidato, etapa, data_abertura,  arq_nome_arquivo, arq_extensao, arq_nome_original, arq_tamanho, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao ) 
            VALUES (:id_candidato, :etapa, :data_abertura, :nome, :extensao, :nome_original, :tamanho_do_arquivo, :zero, :datetime, :usuario_cadastrou )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":etapa", $_SESSION['etapa_selecao']);
            $query->bindValue(":data_abertura", $datetime);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho_do_arquivo", $tamanho_do_arquivo);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);

            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'etapa' => $_SESSION['etapa_selecao'],
                        'nome' => $nome,
                        'tamanho_do_arquivo' => $tamanho_do_arquivo,
                        'extensao' => $extensao,
                        'nome_original' => $nome_original,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Arquivo Pagamento">

    public function insere_arquivo_para_candidato($id_candidato, $label, $nome, $extensao, $nome_original, $tamanho_do_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_cadastrou = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "INSERT INTO arquivo
            (id_usuario, label, nome, extensao, nome_original, tamanho, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao ) 
            VALUES (:id_candidato, :label, :nome, :extensao, :nome_original, :tamanho_do_arquivo, :zero, :datetime, :usuario_cadastrou )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":label", $label);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho_do_arquivo", $tamanho_do_arquivo);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_cadastrou", $usuario_cadastrou);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'label' => $label,
                        'nome' => $nome,
                        'tamanho_do_arquivo' => $tamanho_do_arquivo,
                        'extensao' => $extensao,
                        'nome_original' => $nome_original,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_cadastrou,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Foto">

    public function insere_foto($nome, $extensao, $nome_original, $tamanho_do_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "
            INSERT INTO foto
            (id_usuario, nome, extensao, nome_original, tamanho, apagado, _usuario_ultima_atualizacao, _data_ultima_atualizacao) 
            VALUES (:id_usuario, :nome, :extensao, :nome_original, :tamanho_do_arquivo, :zero, :_usuario_ultima_atualizacao, :datetime )";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":extensao", $extensao);
            $query->bindValue(":nome_original", $nome_original);
            $query->bindValue(":tamanho_do_arquivo", $tamanho_do_arquivo);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_usuario' => $_SESSION['id_usuario'],
                        'nome' => $nome,
                        'extensao' => $extensao,
                        'nome_original' => $nome_original,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere Foto">

    public function insere_linha_csv($id_gru, $cpf, $valor, $data_pagamento, $numero_ref, $situacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        if ($data_pagamento == '') $data_pagamento = null;

        try {
            $sqlInsert = "
            INSERT INTO gru_pagas
            (id_selecao, id_gru, cpf, valor, data_pagamento, numero_referencia, situacao, apagado, _usuario_cadastrou) 
            VALUES (:id_selecao, :id_gru, :cpf, :valor, :data_pagamento, :numero_referencia, :situacao, :zero, :_usuario_cadastrou)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $_SESSION['selecao']);
            $query->bindValue(":id_gru", $id_gru);
            $query->bindValue(":cpf", $cpf);
            $query->bindValue(":valor", $valor);
            $query->bindValue(":data_pagamento", $data_pagamento);
            $query->bindValue(":numero_referencia", $numero_ref);
            $query->bindValue(":situacao", $situacao);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":_usuario_cadastrou", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $this->pdo->commit();
                return true;
            } else {
                //print_r($this->errorInfo());
                //exit();
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Insere EXAME MÉDICO">

    public function insere_exame_medico($sessao, $dia_exame, $cidade, $presidente, $membro_1, $membro_2)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "
            INSERT INTO exame_medico
            (id_selecao, sessao, dia_exame,cidade,presidente,membro_1,membro_2,apagado,_data_ultima_atualizacao,_usuario_ultima_atualizacao) 
            VALUES (:id_selecao, :sessao, :dia_exame,:cidade,:presidente,:membro_1,:membro_2,:zero,:datetime, :_usuario_ultima_atualizacao)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $_SESSION['selecao']);
            $query->bindValue(":sessao", $sessao);
            $query->bindValue(":dia_exame", $dia_exame);
            $query->bindValue(":cidade", $cidade);
            $query->bindValue(":presidente", $presidente);
            $query->bindValue(":membro_1", $membro_1);
            $query->bindValue(":membro_2", $membro_2);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario']);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_selecao' => $_SESSION['selecao'],
                        'sessao' => $sessao,
                        'dia_exame' => $dia_exame,
                        'cidade' => $cidade,
                        'presidente' => $presidente,
                        'membro_1' => $membro_1,
                        'membro_2' => $membro_2,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade Candidadto">
    public function apaga_especialidade_candidato($id_especialidade, $id_usuario)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET apagado='1' WHERE id_candidato = :id_candidato and id_especialidade = :id_especialidade";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":id_candidato", $id_usuario);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade' => $id_especialidade,
                        'id_candidato' => $id_usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade Curriculo">
    public function apaga_curriculo_especialidade($id_especialidade_curriculo)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE especialidade_curriculo SET apagado='1' WHERE id = :id_especialidade_curriculo";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_especialidade_curriculo", $id_especialidade_curriculo);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade_curriculo' => $id_especialidade_curriculo,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Especialidade Curriculo">
    public function apaga_recurso_candidato($id_recurso)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE recurso SET apagado='1' WHERE id = :id_recurso";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_recurso", $id_recurso);

            if ($query->execute()) {
                $data =
                    [
                        'id_recurso' => $id_recurso,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Prioridade Candidato">
    public function apaga_prioridade_candidato($id)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE prioridade_cidade SET apagado='1' WHERE id_candidato_x_especialidade = :id";
            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);
            $query->bindValue(":id", $id);
            if ($query->execute()) {
                $data =
                    [
                        'id_candidato_x_especialidade' => $id,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Usuário">
    public function apaga_usuario($id_usuario)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE usuario SET apagado='1' WHERE id = :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_usuario);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario_apagado' => $id_usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Usuário desclassificado">
    public function usuario_desclassificado($id_usuario, $justificativa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE usuario SET concorrendo='0', justificativa_concorrendo =:justificativa WHERE id = :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_usuario);
            $query->bindValue(":justificativa", $justificativa);

            if ($query->execute()) {
                $data =
                    [
                        'concorrendo' => $id_usuario,
                        'justificativa' => $justificativa,
                        'id_usuario_alterou_concorrendo' => "Script do sistema",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Arquivo Obrigatório">

    // 17/06/2025 - Iago Silva Criando função para cadastrar parecer de heteroidentificação
    public function cadastra_parecer_heteroidentificacao($id_candidato, $parecer, $justificativa, $fase, $id_avaliador)
    {
        $datetime = date('Y-m-d H:i:s');

        try {
            $sqlInsert = "INSERT INTO heteroidentificacao
            (id_candidato, parecer, justificativa, id_avaliador, fase, data_avaliacao) 
            VALUES (:id_candidato, :parecer, :justificativa, :id_avaliador, :fase, :datetime)";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":parecer", $parecer);
            $query->bindValue(":justificativa", $justificativa);
            $query->bindValue(":id_avaliador", $id_avaliador);
            $query->bindValue(":fase", $fase);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $this->pdo->commit();
                return [
                    'id_candidato' => $id_candidato,
                    'parecer' => $parecer,
                    'justificativa' => $justificativa,
                    'fase' => $fase,
                    'id_avaliador' => $id_avaliador,
                    'data_avaliacao' => $datetime,
                ];
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->pdo->rollBack(); // boa prática: rollback também no catch
            return false;
        }
    }

    // 17/06/2025 - Iago Silva
    public function editar_parecer_heteroidentificacao($id_candidato, $parecer, $justificativa, $id_parecer)
    {
        $datetime = date('Y-m-d H:i:s');

        try {
            $sql = "
            UPDATE heteroidentificacao 
            SET parecer = :parecer, justificativa = :justificativa, data_avaliacao = :datetime 
            WHERE id = :id_parecer
        ";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sql);

            $query->bindValue(":id_parecer", $id_parecer, PDO::PARAM_INT);
            $query->bindValue(":parecer", $parecer);
            $query->bindValue(":justificativa", $justificativa);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $this->pdo->commit();
                return [
                    'id_candidato'    => $id_candidato,
                    'parecer'         => $parecer,
                    'justificativa'   => $justificativa,
                    'id_parecer'      => $id_parecer,
                    'data_avaliacao'  => $datetime,
                ];
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // 17/06/2025 - Iago Silva
    public function get_pareceres_heteroidentificacao($id_candidato)
    {
        $sql = "
        SELECT h.*, u.nome_guerra AS nome_avaliador, u.posto_grad AS graduacao_avaliador
        FROM heteroidentificacao h
        LEFT JOIN usuario u ON u.id = h.id_avaliador
        WHERE h.id_candidato = :id_candidato
        ORDER BY h.data_avaliacao DESC
    ";

        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id_candidato", $id_candidato);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastra_arquivo_obrigatorio($nome_arquivo_obrigatorio, $mulher, $militar_ativa, $reservista, $cdi, $vaga_reservada)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "INSERT INTO documentacao_obrigatoria
                (id_selecao, nome, mulher, militar_ativa, reservista, cdi, vaga_reservada, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao) 
                VALUES (:id_selecao, :nome, :mulher, :militar_ativa, :reservista, :cdi, :vaga_reservada, :zero, :datetime, :id_usuario)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $_SESSION['selecao']);
            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":nome", $nome_arquivo_obrigatorio);
            $query->bindValue(":mulher", $mulher);
            $query->bindValue(":militar_ativa", $militar_ativa);
            $query->bindValue(":reservista", $reservista);
            $query->bindValue(":cdi", $cdi);
            $query->bindValue(":vaga_reservada", $vaga_reservada);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_selecao' => $_SESSION['selecao'],
                        'nome' => $nome_arquivo_obrigatorio,
                        'mulher' => $mulher,
                        'militar_ativa' => $militar_ativa,
                        'reservista' => $reservista,
                        'cdi' => $cdi,
                        'vaga_reservada' => $vaga_reservada,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Observação Candidato">

    public function cadastra_observacao_candidato($id_candidato, $observacao, $sistema)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;
        $usuario_ultima_atualizacao = $_SESSION['id_usuario'];

        try {

            $sqlInsert = "INSERT INTO observacao
                (id_usuario, observacao, sistema, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao) 
                VALUES (:id_candidato, :observacao, :sistema, :zero, :datetime, :usuario_ultima_atualizacao)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":observacao", $observacao);
            $query->bindValue(":sistema", $sistema);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":usuario_ultima_atualizacao", $usuario_ultima_atualizacao);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'observacao' => $observacao,
                        'sistema' => $sistema,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario_ultima_atualizacao,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Prioridade">

    public function cadastra_prioridade($id_candidato_x_especialidade, $prioridade, $id_cidade)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "INSERT INTO prioridade_cidade
                (id_candidato_x_especialidade, id_cidade, prioridade, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao) 
                VALUES (:id_candidato_x_especialidade, :id_cidade, :prioridade, :zero, :datetime, :id_usuario)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato_x_especialidade", $id_candidato_x_especialidade);
            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":prioridade", $prioridade);
            $query->bindValue(":id_cidade", $id_cidade);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_candidato_x_especialidade' => $id_candidato_x_especialidade,
                        'id_cidade' => $id_cidade,
                        'prioridade' => $prioridade,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Recurso">

    public function cadastra_recurso(
        $id_candidato,
        $id_especialidade,
        $etapa,
        $obs_etapa,
        $data_abertura,
        $avaliador,
        $status,
        $id_usuario_analise,
        $data_analise,
        $analise,
        $nome_original,
        $nomeFinalArquivo,
        $extensao,
        $tamanho_do_arquivo
    ) {

        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "INSERT INTO recurso
        (id_candidato, id_especialidade, etapa, obs_etapa, data_abertura, para_avaliador, status, analise, data_analise, id_usuario_analise, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao, arq_nome_original, arq_nome_arquivo, arq_extensao, arq_tamanho) 
        VALUES (:id_candidato, :id_especialidade, :etapa, :obs_etapa, :data_abertura, :avaliador, :status, :analise, :data_analise, :id_usuario_analise, :zero, :datetime, :id_usuario, :arq_nome_original, :arq_nome_arquivo, :arq_extensao, :arq_tamanho)";


            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":etapa", $etapa);
            $query->bindValue(":obs_etapa", $obs_etapa);
            $query->bindValue(":data_abertura", $data_abertura);
            $query->bindValue(":avaliador", $avaliador);
            $query->bindValue(":analise", $analise);
            $query->bindValue(":data_analise", $data_analise);
            $query->bindValue(":status", $status);
            $query->bindValue(":id_usuario_analise", $id_usuario_analise);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);
            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":arq_nome_original", $nome_original);
            $query->bindValue(":arq_nome_arquivo", $nomeFinalArquivo);
            $query->bindValue(":arq_extensao", $extensao);
            $query->bindValue(":arq_tamanho", $tamanho_do_arquivo);

            if ($query->execute()) {
                $data =
                    [
                        'last_insert_id' => $this->pdo->lastInsertId(),
                        'id_candidato' => $id_candidato,
                        'id_especialidade' => $id_especialidade,
                        'etapa' => $etapa,
                        'obs_etapa' => $obs_etapa,
                        'data_abertura' => $data_abertura,
                        'avaliador' => $avaliador,
                        'status' => $status,
                        'analise' => $analise,
                        'data_analise' => $data_analise,
                        'id_usuario_analise' => $id_usuario_analise,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                        'arq_nome_original' => $nome_original,
                        'arq_nome_arquivo' => $nomeFinalArquivo,
                        'arq_extensao' => $extensao,
                        'arq_tamanho' => $tamanho_do_arquivo,
                    ];


                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Currículo">

    public function cadastra_curriculo($nome_curriculo, $pontuacao, $obrigatorio, $quantidade_maxima, $multiplicador, $quantidade_multiplicacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $zero = 0;

        try {
            $sqlInsert = "INSERT INTO curriculo
                (id_selecao, nome, pontuacao, carga_horaria_obrigatoria, quantidade_maxima_uploads, multiplicacao, quantidade_multiplicacao, apagado, _data_ultima_atualizacao, _usuario_ultima_atualizacao) 
                VALUES (:id_selecao, :nome, :pontuacao, :carga_horaria_obrigatoria, :quantidade_maxima, :multiplicacao, :quantidade_multiplicacao, :zero, :datetime, :id_usuario)";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $_SESSION['selecao']);
            $query->bindValue(":id_usuario", $_SESSION['id_usuario']);
            $query->bindValue(":nome", $nome_curriculo);
            $query->bindValue(":quantidade_maxima", $quantidade_maxima);
            $query->bindValue(":pontuacao", $pontuacao);
            $query->bindValue(":carga_horaria_obrigatoria", $obrigatorio);
            $query->bindValue(":multiplicacao", $multiplicador);
            $query->bindValue(":quantidade_multiplicacao", $quantidade_multiplicacao);
            $query->bindValue(":zero", $zero);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_adicionado' => $this->pdo->lastInsertId(),
                        'id_selecao' => $_SESSION['selecao'],
                        'nome_curriculo' => $nome_curriculo,
                        'pontuacao' => $pontuacao,
                        'quantidade_maxima' => $quantidade_maxima,
                        'carga_horaria_obrigatoria' => $obrigatorio,
                        'multiplicacao' => $multiplicador,
                        'quantidade_multiplicacao' => $quantidade_multiplicacao,
                        'apagado' => $zero,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $_SESSION['id_usuario'],
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            //return $e->getMessage();
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Observação id">
    public function apaga_observacao_id($id)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE observacao SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_observacao' => $id,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Altera Etapa do Candidato">
    public function altera_etapa_candidato($id_candidato, $etapa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE usuario SET etapa=:etapa WHERE id = :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":etapa", $etapa);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'etapa' => $etapa,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    public function altera_etapa_especialidade($id_candidato, $id_especialidade, $etapa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlUpdate = "UPDATE candidato_x_especialidade 
                      SET etapa = :etapa,
                          _data_ultima_atualizacao = :data_atualizacao,
                          _usuario_ultima_atualizacao = :usuario
                      WHERE id_especialidade = :id_especialidade 
                        AND id_candidato = :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlUpdate);

            $query->bindValue(":etapa", $etapa);
            $query->bindValue(":data_atualizacao", $datetime);
            $query->bindValue(":usuario", $usuario);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":id_candidato", $id_candidato);

            if ($query->execute()) {
                $this->pdo->commit();
                return true;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Altera Etapa do Candidato">
    public function om_informacoes_atualiza($id_usuario, $apresentacao_candidato_om, $observacao_om)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE usuario SET 
                        apresentacao_candidato_om=:apresentacao_candidato_om,  
                        observacao_om = :observacao_om
                        WHERE id = :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_usuario);
            $query->bindValue(":apresentacao_candidato_om", $apresentacao_candidato_om);
            $query->bindValue(":observacao_om", $observacao_om);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_usuario,
                        'apresentacao_candidato_om' => $apresentacao_candidato_om,
                        'observacao_om' => $observacao_om,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Altera Etapa da Seleção">
    public function altera_etapa_selecao($etapa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET etapa=:etapa WHERE id = :id_selecao";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_selecao", $id_selecao);
            $query->bindValue(":etapa", $etapa);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'etapa' => $etapa,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Muda status candidato ISENTO PAGAMENTO">
    public function status_isento_pagamento($id, $isento)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE usuario SET isento_pagamento=:isento WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);
            $query->bindValue(":isento", $isento);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'isento' => $isento,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Muda status candidato concorrendo">
    public function status_concorrendo($id, $concorrendo, $justificativa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE usuario SET concorrendo=:concorrendo,
                          justificativa_concorrendo =:justificativa, 
                          id_usuario_alterou_concorrendo = :id_usuario WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);
            $query->bindValue(":id_usuario", $usuario);
            $query->bindValue(":concorrendo", $concorrendo);
            $query->bindValue(":justificativa", $justificativa);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'concorrendo' => $concorrendo,
                        'justificativa' => $justificativa,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cadastra Cidade que candidato vai servir">
    public function cadastra_cidade_candidato_vai_servir($id_candidato, $id_especialidade, $cidade_escolheu_servir)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET cidade_escolheu_servir=:cidade_escolheu_servir,
                          _usuario_ultima_atualizacao =:id_candidato, 
                          _data_ultima_atualizacao = :data WHERE id_candidato= :id_candidato 
                          and id_especialidade = :id_especialidade and apagado = 0";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":cidade_escolheu_servir", $cidade_escolheu_servir);
            $query->bindValue(":data", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'id_especialidade' => $id_especialidade,
                        'cidade_escolheu_servir' => $cidade_escolheu_servir,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $id_candidato,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function cadastra_rm_candidato_vai_servir($id_candidato, $id_especialidade, $rm_escolheu_servir)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET rm_escolheu_servir = :rm_escolheu_servir,
                      _usuario_ultima_atualizacao = :id_candidato, 
                      _data_ultima_atualizacao = :data 
                      WHERE id_candidato = :id_candidato 
                      AND id_especialidade = :id_especialidade 
                      AND apagado = 0";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":rm_escolheu_servir", $rm_escolheu_servir);
            $query->bindValue(":data", $datetime);

            if ($query->execute()) {
                $data = [
                    'id_candidato' => $id_candidato,
                    'id_especialidade' => $id_especialidade,
                    'rm_escolheu_servir' => $rm_escolheu_servir,
                    '_data_ultima_atualizacao' => $datetime,
                    '_usuario_ultima_atualizacao' => $id_candidato,
                ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            // Log do erro pode ser adicionado aqui
            $this->pdo->rollBack();
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Muda status concorrendo especialidade">
    public function status_concorrendo_especialidade($id_candidato_x_especialidade, $concorrendo, $justificativa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET 
                          concorrendo=:concorrendo,
                          justificativa= :justificativa, 
                          id_usuario_alterou_concorrendo= :id_usuario_alterou_concorrendo  
                          WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_candidato_x_especialidade);
            $query->bindValue(":concorrendo", $concorrendo);
            $query->bindValue(":justificativa", $justificativa);
            $query->bindValue(":id_usuario_alterou_concorrendo", $usuario);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato_x_especialidade' => $id_candidato_x_especialidade,
                        'concorrendo' => $concorrendo,
                        'justificativa' => $justificativa,
                        'id_usuario_alterou_concorrendo' => $usuario,

                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Arquivo Obrigatório cadastrado">
    public function apaga_arquivo_obrigatorio_cadastrado($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE documentacao_obrigatoria SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_documentacao_obrigaria' => $id,
                        'nome' => $nome,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Candidato">
    public function apaga_candidato($id, $cpf)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE usuario SET apagado='1' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'cpf' => $cpf,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Arquivo Obrigatório Candidato">
    public function apaga_arquivo_obrigatorio_inserido($id_candidato, $id_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE documento_obrigatorio SET apagado='1' WHERE id_candidato = :id_candidato and id_documentacao_obrigatoria = :id_arquivo";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_arquivo", $id_arquivo);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'id_arquivo' => $id_arquivo,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Arquivo Candidato">
    public function apaga_arquivo_candidato($id_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE arquivo SET apagado='1' WHERE id = :id_arquivo";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_arquivo", $id_arquivo);

            if ($query->execute()) {
                $data =
                    [
                        'id_arquivo' => $id_arquivo,
                        'apagado' => "1",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    public function get_candidato_atas_is($id_usuario)
    {
        //selecionar o campo ata_is e ata_is_recurso
        $sql = "SELECT ata_is, ata_is_recurso FROM usuario WHERE id = :id_usuario";
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id_usuario", $id_usuario);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function apaga_ata_is_candidato($id_candidato, $datetime, $usuario, $tipo)
    {
        try {
            $sqlInsert = "UPDATE usuario SET $tipo = null WHERE id = :id_candidato";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        $tipo => "null",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario
                    ];
                $this->pdo->commit();

                // devolver o nome do arquivo apagado

                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Apaga Arquivo pagamento">
    public function apaga_arquivo_pagamento($id_candidato, $id_arquivo)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE pagamento_inscricao SET apagado='1' WHERE id_candidato = :id_candidato and id = :id_arquivo";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato", $id_candidato);
            $query->bindValue(":id_arquivo", $id_arquivo);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato' => $id_candidato,
                        'id_arquivo' => $id_arquivo,
                        'apagado' => '1',
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Restaura Candidato">
    public function restaura_candidato($id, $cpf)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];

        try {
            $sqlInsert = "UPDATE usuario SET apagado='0' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'cpf' => $cpf,
                        'apagado' => "0",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Restaura Arquivo Obrigatório">
    public function restaura_arquivo_obrigatorio($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE documentacao_obrigatoria SET apagado='0' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_documentacao_obrigaria' => $id,
                        'nome' => $nome,
                        'apagado' => "0",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Restaura Currículo">
    public function restaura_curriculo($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE curriculo SET apagado='0' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_curriculo' => $id,
                        'nome' => $nome,
                        'apagado' => "0",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Restaura Especialidade">
    public function restaura_especialidade($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE especialidade SET apagado='0' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade' => $id,
                        'nome' => $nome,
                        'apagado' => "0",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Restaura Usuário">
    public function restaura_usuario($id, $nome)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE usuario SET apagado='0' WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id,
                        'nome' => $nome,
                        'apagado' => "0",
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data de início e fim da inscricao">
    public function selecao_atualiza_data_inscricao($data_inicio, $data_fim)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET data_inicio_inscricao=:data_inicio_inscricao, data_fim_inscricao=:data_fim_inscricao WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_inicio_inscricao", $data_inicio);
            $query->bindValue(":data_fim_inscricao", $data_fim);

            if ($query->execute()) {
                $_SESSION['selecao_data_inicial_inscricao'] = $data_inicio;
                $_SESSION['selecao_data_final_inscricao'] = $data_fim;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_inicio_inscricao' => $data_inicio,
                        'data_fim_inscricao' => $data_fim,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data de início e fim da escolha da Cidade">
    public function selecao_atualiza_data_cidade($data_inicio, $data_fim)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET data_inicio_cidade=:data_inicio_cidade, data_fim_cidade=:data_fim_cidade WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_inicio_cidade", $data_inicio);
            $query->bindValue(":data_fim_cidade", $data_fim);

            if ($query->execute()) {
                $_SESSION['selecao_data_inicial_cidade'] = $data_inicio;
                $_SESSION['selecao_data_final_cidade'] = $data_fim;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_inicio_cidade' => $data_inicio,
                        'data_fim_cidade' => $data_fim,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data de início e fim do recurso">
    public function selecao_atualiza_data_recurso($data_inicio, $data_fim)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET data_inicio_recurso=:data_inicio_recurso, data_fim_recurso=:data_fim_recurso WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_inicio_recurso", $data_inicio);
            $query->bindValue(":data_fim_recurso", $data_fim);

            if ($query->execute()) {
                $_SESSION['selecao_data_inicial_recurso'] = $data_inicio;
                $_SESSION['selecao_data_final_recurso'] = $data_fim;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_inicio_recurso' => $data_inicio,
                        'data_fim_recurso' => $data_fim,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }


    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data de início e fim da isenção">
    public function selecao_atualiza_data_isencao($data_inicio, $data_fim)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;
        try {
            $sqlInsert = "UPDATE selecao SET data_inicio_isencao=:data_inicio_isencao, data_fim_isencao=:data_fim_isencao WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_inicio_isencao", $data_inicio);
            $query->bindValue(":data_fim_isencao", $data_fim);

            if ($query->execute()) {
                $_SESSION['selecao_data_inicial_isencao'] = $data_inicio;
                $_SESSION['selecao_data_final_isencao'] = $data_fim;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_inicio_isencao' => $data_inicio,
                        'data_fim_isencao' => $data_fim,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data de início e fim das avaliações">
    public function selecao_atualiza_data_avaliacao($data_inicio, $data_fim)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET data_inicio_avaliacao=:data_inicio_avaliacao, data_fim_avaliacao=:data_fim_avaliacao WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_inicio_avaliacao", $data_inicio);
            $query->bindValue(":data_fim_avaliacao", $data_fim);

            if ($query->execute()) {
                $_SESSION['selecao_data_inicial_avaliacao'] = $data_inicio;
                $_SESSION['selecao_data_final_avaliacao'] = $data_fim;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_inicio_avaliacao' => $data_inicio,
                        'data_fim_avaliacao' => $data_fim,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza notas EIPOT">
    public function atualiza_notas_eipot($id_usuario_eipot, $quantidade_flexao_braco, $quantidade_abdominal, $quantidade_barra, $distancia_corrida, $ano_formacao, $nota_ofor)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario_avaliador = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE usuario SET 
                qtd_flexao_braco=:quantidade_flexao_braco, 
                qtd_abdominal=:quantidade_abdominal, 
                qtd_barra=:quantidade_barra, 
                dist_corrida=:distancia_corrida, 
                ano_formacao_ofor_avaliador=:ano_formacao, 
                nota_ofor_avaliador=:nota_ofor,
                eipot_usuario_avaliou=:usuario_avaliador,
                eipot_data_avaliacao=:datetime 
                WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_usuario_eipot);
            $query->bindValue(":quantidade_flexao_braco", $quantidade_flexao_braco);
            $query->bindValue(":quantidade_abdominal", $quantidade_abdominal);
            $query->bindValue(":quantidade_barra", $quantidade_barra);
            $query->bindValue(":distancia_corrida", $distancia_corrida);
            $query->bindValue(":ano_formacao", $ano_formacao);
            $query->bindValue(":nota_ofor", $nota_ofor);
            $query->bindValue(":usuario_avaliador", $usuario_avaliador);
            $query->bindValue(":datetime", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_usuario' => $id_usuario_eipot,
                        'quantidade_flexao_braco' => $quantidade_flexao_braco,
                        'quantidade_abdominal' => $quantidade_abdominal,
                        'quantidade_barra' => $quantidade_barra,
                        'distancia_corrida' => $distancia_corrida,
                        'ano_formacao' => $ano_formacao,
                        'nota_ofor' => $nota_ofor,
                        'usuario_avaliador' => $usuario_avaliador,
                        'datetime' => $datetime,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                //print_r($this->errorInfo());
                //$this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza data máxima de nascimento">
    public function selecao_atualiza_data_maxima_nascimento($data_max_nasc, $data_min_nasc)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];
        $zero = 0;

        try {
            $sqlInsert = "UPDATE selecao SET data_maxima_nascimento=:data_max_nasc, data_minima_nascimento =:data_min_nasc WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":data_max_nasc", $data_max_nasc);
            $query->bindValue(":data_min_nasc", $data_min_nasc);

            if ($query->execute()) {
                $_SESSION['data_max_nasc'] = $data_max_nasc;
                $_SESSION['data_min_nasc'] = $data_min_nasc;

                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'data_max_nasc' => $data_max_nasc,
                        'data_min_nasc' => $data_min_nasc,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="LIBERA COMPROVANTE DE INSCRIÇÃO">
    public function libera_comprovante_inscricao($libera)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];


        try {
            $sqlInsert = "UPDATE selecao SET liberacao_comprovante_inscricao=:libera WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":libera", $libera);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'liberacao_comprovante_inscricao' => $libera,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="LIBERA Cobrar candidato">
    public function cobrar_candidato($cobrar, $valor, $apelido)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];


        try {
            $sqlInsert = "UPDATE selecao SET pagamento=:cobrar, valor_gru=:valor, apelido_ug = :apelido WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":cobrar", $cobrar);
            $query->bindValue(":valor", $valor);
            $query->bindValue(":apelido", $apelido);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'pagamento' => $cobrar,
                        'valor' => $valor,
                        'apelido' => $apelido,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Atualiza Número de vagas da cidade e epecialidade">

    // 11/07/2025 -> Iago Silva Adicionando o campo região_militar na query
    public function numero_vagas_cidade_especialidade_atualiza($id_especialidade, $id_cidade, $num_vagas, $regiao_militar = null)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlUpdate = "
            UPDATE cidade_x_especialidade 
            SET 
                numero_vagas = :num_vagas,
                regiao_militar = :regiao_militar,
                _data_ultima_atualizacao = :data,
                _usuario_ultima_atualizacao = :id_usuario
            WHERE 
                id_especialidade = :id_especialidade 
                AND id_cidade = :id_cidade
        ";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlUpdate);

            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":id_cidade", $id_cidade);
            $query->bindValue(":num_vagas", $num_vagas);
            $query->bindValue(":regiao_militar", $regiao_militar);
            $query->bindValue(":id_usuario", $usuario);
            $query->bindValue(":data", $datetime);

            if ($query->execute()) {
                $this->pdo->commit();
                return [
                    'id_selecao' => $id_selecao,
                    'id_especialidade' => $id_especialidade,
                    'id_cidade' => $id_cidade,
                    'numero_vagas' => $num_vagas,
                    'regiao_militar' => $regiao_militar,
                    '_data_ultima_atualizacao' => $datetime,
                    '_usuario_ultima_atualizacao' => $usuario,
                ];
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // 14/07/2025 -> Iago Silva Criando a função que busca as vagas preenchidas
    public function get_vagas_preenchidas($id_especialidade)
    {
        try {
            $sql = "
            SELECT 
                c.id AS id_cidade,
                c.nome AS cidade,
                cxesp.regiao_militar,
                COUNT(DISTINCT cxe.id_candidato) AS preenchidas
            FROM 
                candidato_x_especialidade AS cxe
            INNER JOIN 
                cidade_x_especialidade AS cxesp
                ON cxesp.id_cidade = cxe.cidade_escolheu_servir
                AND cxesp.id_especialidade = cxe.id_especialidade
            INNER JOIN 
                cidade AS c ON c.id = cxe.cidade_escolheu_servir
            WHERE 
                cxe.id_especialidade = :id_especialidade
                AND cxe.cidade_escolheu_servir IS NOT NULL
            GROUP BY 
                c.id, c.nome, cxesp.regiao_militar
            ORDER BY 
                c.nome
        ";

            $query = $this->pdo->prepare($sql);
            $query->bindValue(':id_especialidade', $id_especialidade);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar vagas preenchidas: " . $e->getMessage());
            return false;
        }
    }

    /*public function numero_vagas_om_especialidade_atualiza($id_especialidade, $id_om, $quantidade_vagas) 
    {
        
        try 
        {
            $this->pdo->exec("SET foreign_key_checks = 0;");
            $sqlInsert = "UPDATE om_x_especialidade
                            SET numero_vagas = :quantidade_vagas,
                                id_especialidade = :id_especialidade,
                                id_om = :id_om,
                            WHERE id_especialidade = :id_especialidade AND id_om = :id_om;";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":id_om", $id_om);
            $query->bindValue(":quantidade_vagas", $quantidade_vagas);

            if($query->execute())
            {
                $data = 
                [
                    'id_especialidade' => $id_especialidade,
                    'id_om' => $id_om,
                    'numero_vagas' => $quantidade_vagas,
                ];
               $this->pdo->commit();
               return $data;
            } 
            else 
            {
               $this->pdo->rollBack();
               return false;
            }
        } 
        catch (Exception $e) 
        { 
            return false;
        }
        return true;
    } 

    public function numero_vagas_om_especialidade_insere($id_especialidade, $id_om, $quantidade_vagas) 
    {
        $datetime = date('Y-m-d H:i:s');
        $id_selecao = $_SESSION['selecao'];
        
     
        try 
        {    
            $this->pdo->exec("SET foreign_key_checks = 0;");
            $sqlInsert = "INSERT INTO om_x_especialidade (id_especialidade, id_om, numero_vagas)
                        VALUES (:id_especialidade, :id_om, :quantidade_vagas);";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_especialidade", $id_especialidade);
            $query->bindValue(":id_om", $id_om);
            $query->bindValue(":numero_vagas", $quantidade_vagas);
            $query->bindValue(":data", $datetime);

            
            if($query->execute())
            {
                $data = 
                [
                    'id_especialidade' => $id_especialidade,
                    'id_om' => $id_om,
                    'quantidade_vagas' => $quantidade_vagas,
                ];
               $this->pdo->commit();
               return $data;
            } 
            else 
            {
               $this->pdo->rollBack();
               return false;
            }
        } 
        catch (Exception $e) 
        { 
            return false;
        }
        return true;
    }*/

    public function numero_vagas_om_especialidade_atualiza($id_especialidade, $id_om, $quantidade_vagas)
    {
        // Preparar a consulta SQL de update
        $this->pdo->exec("SET foreign_key_checks = 0;");

        // Preparar a instrução no PDO
        try {
            // Preparar a consulta no banco
            $stmt = $this->pdo->prepare("UPDATE om_x_especialidade
                                    SET numero_vagas = :quantidade_vagas,
                                        id_especialidade = :id_especialidade,
                                        id_om = :id_om
                                    WHERE id_especialidade = :id_especialidade 
                                    AND id_om = :id_om;");

            // Bind dos parâmetros
            $stmt->bindParam(':quantidade_vagas', $quantidade_vagas, PDO::PARAM_INT);
            $stmt->bindParam(':id_especialidade', $id_especialidade, PDO::PARAM_INT);
            $stmt->bindParam(':id_om', $id_om, PDO::PARAM_INT);

            // Executar a consulta
            //$stmt->execute();

            $this->pdo->commit();

            // Verificar se a atualização foi bem-sucedida
            if ($stmt->execute()) {
                return true;
            } else {
                var_dump($stmt->errorInfo()); // Verifique a descrição do erro
                return false;
            }
        } catch (PDOException $e) {
            // Tratar o erro, caso ocorra
            echo "Erro ao atualizar o número de vagas: " . $e->getMessage();
            return false;
        }
    }

    public function numero_vagas_om_especialidade_insere($id_especialidade, $id_om, $quantidade_vagas)
    {
        // Preparar a consulta SQL de update
        //$this->pdo->exec("SET foreign_key_checks = 0;");

        // Preparar a instrução no PDO
        try {
            // Preparar a consulta no banco
            $stmt = $this->pdo->prepare("INSERT INTO om_x_especialidade (id_especialidade, id_om, numero_vagas)
                        VALUES (:id_especialidade, :id_om, :quantidade_vagas);");

            // Bind dos parâmetros
            $stmt->bindParam(':quantidade_vagas', $quantidade_vagas, PDO::PARAM_INT);
            $stmt->bindParam(':id_especialidade', $id_especialidade, PDO::PARAM_INT);
            $stmt->bindParam(':id_om', $id_om, PDO::PARAM_INT);

            // Executar a consulta
            //  $stmt->execute();
            $this->pdo->commit();

            // Verificar se a atualização foi bem-sucedida
            if ($stmt->execute()) {
                return true;
            } else {
                var_dump($stmt->errorInfo()); // Verifique a descrição do erro
                return false;
            }
        } catch (PDOException $e) {
            // Tratar o erro, caso ocorra
            echo "Erro ao atualizar o número de vagas: " . $e->getMessage();
            return false;
        }
    }


    public function consulta_especialidade_tabela_om_x_especialidade($id_om, $id_especialidade)
    {
        try {
            $sqlInsert = "SELECT * FROM siscant.om_x_especialidade 
            WHERE id_om = :id_om 
            AND id_especialidade = :id_especialidade 
            AND apagado = 0;";

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sqlInsert);
            $query->bindValue(":id_om", $id_om);
            $query->bindValue(":id_especialidade", $id_especialidade);

            if ($query->execute()) {
                if ($query->rowCount() > 0) {
                    return true;  // Encontrou registros
                } else {
                    return false; // Nenhum registro encontrado
                }
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="LIBERA VISUALIZAÇÃO DA AVALIAÇÃO CURRICULAR DO CANDIDATO">
    public function libera_avaliacao_curricular($liberacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET liberacao_avaliacao_curricular=:libera WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":libera", $liberacao);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'liberacao_avaliacao_curricular' => $liberacao,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="LIBERA VISUALIZAÇÃO DA AVALIAÇÃO DOS DOCS OBRIGATORIOS DO CANDIDATO">
    public function libera_avaliacao_docs_obrigatorios($liberacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET liberacao_avaliacao_docs_obrigatorios=:libera WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":libera", $liberacao);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'liberacao_avaliacao_docs_obrigatorios' => $liberacao,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Elimina caso não colocou foto">
    public function elimina_caso_nao_colocou_foto($elimina)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET eliminar_caso_nao_adicione_foto=:elimina WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":elimina", $elimina);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'eliminar_caso_nao_adicione_foto' => $elimina,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Elimina caso não colocou todos os docs obrigatórios">
    public function elimina_caso_nao_colocou_todos_docs_obrigatorios($elimina)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET eliminar_docs_obrigatorios=:elimina WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":elimina", $elimina);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'eliminar_docs_obrigatorios' => $elimina,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="LIBERA PRIORIDADE CANDIDATO">
    public function libera_prioridade_candidato($liberacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET liberacao_prioridade_candidato=:libera WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":libera", $liberacao);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'liberacao_prioridade_candidato' => $liberacao,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ENCERRA SELEÇÃO">
    public function encerra_selecao($encerrada, $observacao)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE selecao SET encerrada=:encerrada, observacao = :obs WHERE id= :id";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id", $id_selecao);
            $query->bindValue(":obs", $observacao);
            $query->bindValue(":encerrada", $encerrada);

            if ($query->execute()) {
                $data =
                    [
                        'id_selecao' => $id_selecao,
                        'encerrada' => $encerrada,
                        'observacao' => $observacao,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Avalia Doc Obrigatório">
    public function avalia_doc_obrigatorio($id_documentacao_obrigatoria, $valido, $justificativa)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE documento_obrigatorio SET valido=:valido, justificativa=:justificativa, usuario_avaliou=:usuario_avaliou, data_avaliacao=:data  WHERE id=:id_documentacao_obrigatoria";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_documentacao_obrigatoria", $id_documentacao_obrigatoria);
            $query->bindValue(":valido", $valido);
            $query->bindValue(":justificativa", $justificativa);
            $query->bindValue(":usuario_avaliou", $usuario);
            $query->bindValue(":data", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_documentacao_obrigatoria' => $id_documentacao_obrigatoria,
                        'valido' => $valido,
                        'justificativa' => $justificativa,
                        'usuario_avaliou' => $usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Avalia Currículo">
    public function avalia_curriculo($id_especialidade_curriculo, $valido, $justificativa, $multiplicador)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];

        try {
            $sqlInsert = "UPDATE especialidade_curriculo SET valido=:valido, justificativa=:justificativa,
                   multiplicador=:multiplicador ,usuario_avaliou=:usuario_avaliou, data_avaliacao=:data  WHERE id=:id_especialidade_curriculo";

            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_especialidade_curriculo", $id_especialidade_curriculo);
            $query->bindValue(":valido", $valido);
            $query->bindValue(":multiplicador", $multiplicador);
            $query->bindValue(":justificativa", $justificativa);
            $query->bindValue(":usuario_avaliou", $usuario);
            $query->bindValue(":data", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_especialidade_curriculo' => $id_especialidade_curriculo,
                        'valido' => $valido,
                        'multiplicador' => $multiplicador,
                        'justificativa' => $justificativa,
                        'usuario_avaliou' => $usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,
                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Avalia Provas Música">
    public function adiciona_nota_teorico_pratico($id_candidato_x_especialidade, $pontuacao_teorico_pratica)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];


        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET nota_prova_teorico_pratico=:pontuacao_teorico_pratica, 
                           usuario_add_nota_prova_teorico_pratica = :usuario_avaliou,
                           _data_ultima_atualizacao=:data_atualizacao, _usuario_ultima_atualizacao=:usuario_avaliou
                           WHERE id=:id_candidato_x_especialidade";



            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato_x_especialidade", $id_candidato_x_especialidade);
            $query->bindValue(":pontuacao_teorico_pratica", $pontuacao_teorico_pratica);
            $query->bindValue(":usuario_avaliou", $usuario);
            $query->bindValue(":data_atualizacao", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato_x_especialidade' => $id_candidato_x_especialidade,
                        'pontuacao_teorico_pratica' => $pontuacao_teorico_pratica,
                        'usuario_avaliou' => $usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,

                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Adiciona nota de proca teórico/prático">
    public function avalia_provas_musica($id_candidato_x_especialidade, $pontuacao_oral, $pontuacao_teorica, $pontuacao_pratica)
    {
        $datetime = date('Y-m-d H:i:s');
        $usuario = $_SESSION['id_usuario'];
        $id_selecao = $_SESSION['selecao'];


        try {
            $sqlInsert = "UPDATE candidato_x_especialidade SET prova_pratica_musica=:pontuacao_pratica, 
                           prova_teorica_musica=:pontuacao_teorica, prova_oral_musica=:pontuacao_oral, 
                           usuario_avaliou_provas_musica = :usuario_avaliou,
                           _data_ultima_atualizacao=:data_atualizacao, _usuario_ultima_atualizacao=:usuario_avaliou
                           WHERE id=:id_candidato_x_especialidade";



            $this->pdo->beginTransaction();

            $query = $this->pdo->prepare($sqlInsert);

            $query->bindValue(":id_candidato_x_especialidade", $id_candidato_x_especialidade);
            $query->bindValue(":pontuacao_oral", $pontuacao_oral);
            $query->bindValue(":pontuacao_teorica", $pontuacao_teorica);
            $query->bindValue(":pontuacao_pratica", $pontuacao_pratica);
            $query->bindValue(":usuario_avaliou", $usuario);
            $query->bindValue(":data_atualizacao", $datetime);

            if ($query->execute()) {
                $data =
                    [
                        'id_candidato_x_especialidade' => $id_candidato_x_especialidade,
                        'pontuacao_oral' => $pontuacao_oral,
                        'pontuacao_teorica' => $pontuacao_teorica,
                        'pontuacao_pratica' => $pontuacao_pratica,
                        'usuario_avaliou_provas_musica' => $usuario,
                        '_data_ultima_atualizacao' => $datetime,
                        '_usuario_ultima_atualizacao' => $usuario,

                    ];
                $this->pdo->commit();
                return $data;
            } else {
                $this->pdo->rollBack();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    // </editor-fold>


}
