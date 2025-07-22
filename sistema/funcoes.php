<?php

function get_post_action($name)
{
    $params = func_get_args();
    foreach ($params as $name) {
        if (isset($_POST[$name])) {
            return $name;
        }
    }
}

function sobreposicao_datas($data_inicial_teste, $data_final_teste, $data_inicial_existente, $data_final_existente)
{

    //$data_teste_inicial = "01/01/2001";
    //$data_teste_final = "01/06/2002";
    //$data_existente_inicial = "01/12/1999";
    //$data_existente_final = "01/01/2000";
    if (
        ($data_inicial_teste < $data_inicial_existente && $data_final_teste < $data_inicial_existente)
        || ($data_inicial_teste > $data_final_existente && $data_final_teste > $data_final_existente)
    )
        return false;
    else
        return true;
}





function get_data_extenso()
{
    $mes = "Janeiro";
    if (date("m") == 2) $mes = "Fevereiro";
    if (date("m") == 3) $mes = "Março";
    if (date("m") == 4) $mes = "Abril";
    if (date("m") == 5) $mes = "Maio";
    if (date("m") == 6) $mes = "Junho";
    if (date("m") == 7) $mes = "Julho";
    if (date("m") == 8) $mes = "Agosto";
    if (date("m") == 9) $mes = "Setembro";
    if (date("m") == 10) $mes = "Outubro";
    if (date("m") == 11) $mes = "Novembro";
    if (date("m") == 12) $mes = "Dezembro";

    echo date("d") . " de " . $mes . " de " . date("Y");
}

function get_nota_flexao_braco($quantidade)
{
    $nota = 0;
    if ($quantidade >= 11 && $quantidade <= 14) $nota = 0.5;
    if ($quantidade >= 15 && $quantidade <= 19) $nota = 1;
    if ($quantidade >= 20 && $quantidade <= 24) $nota = 1.5;
    if ($quantidade >= 25 && $quantidade <= 29) $nota = 2;
    if ($quantidade >= 30) $nota = 2.5;
    return $nota;
}

function get_nota_abdominal($quantidade)
{
    $nota = 0;
    if ($quantidade >= 21 && $quantidade <= 30) $nota = 0.5;
    if ($quantidade >= 31 && $quantidade <= 40) $nota = 1;
    if ($quantidade >= 41 && $quantidade <= 50) $nota = 1.5;
    if ($quantidade >= 51 && $quantidade <= 60) $nota = 2;
    if ($quantidade > 60) $nota = 2.5;
    return $nota;
}

function get_nota_flexao_barra($quantidade)
{
    $nota = 0;
    if ($quantidade == 2) $nota = 0.5;
    if ($quantidade == 3) $nota = 1;
    if ($quantidade == 4) $nota = 1.5;
    if ($quantidade == 5) $nota = 2;
    if ($quantidade >= 6) $nota = 2.5;
    return $nota;
}

// 21/06/2025 Iago Silva alterado para arrendondar a nota final para duas casas decimais
function get_nota_final_eipot($id_usuario)
{

    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';

    $conexao = new Conexao();

    $usuario_nota_eipot = $conexao->get_usuario_id($id_usuario);

    $qtd_flexao_braco = $usuario_nota_eipot[0]['qtd_flexao_braco'];
    $qtd_abdominal = $usuario_nota_eipot[0]['qtd_abdominal'];
    $qtd_barra = $usuario_nota_eipot[0]['qtd_barra'];
    $dist_corrida = $usuario_nota_eipot[0]['dist_corrida'];
    //  $ano_formacao_ofor_avaliador = $usuario_nota_eipot[0]['ano_formacao_ofor_avaliador'];
    $nota_ofor_avaliador = $usuario_nota_eipot[0]['nota_ofor_avaliador'];

    if ($nota_ofor_avaliador == 0 || $qtd_flexao_braco == 0 || $qtd_abdominal  == 0 || $qtd_barra == 0 || $dist_corrida == 0) //|| $ano_formacao_ofor_avaliador == 0)
        return null;
    else {
        $nota_flexao_braco = get_nota_flexao_braco($qtd_flexao_braco);
        $nota_abdominal = get_nota_abdominal($qtd_abdominal);
        $nota_barra = get_nota_flexao_barra($qtd_barra);
        $nota_corrida = get_nota_corrida($dist_corrida);
        // $ano_formacao_ofor_avaliador = (int)get_nota_ano_formacao($ano_formacao_ofor_avaliador);
        $nota_ofor_avaliador = (float)$nota_ofor_avaliador;

        return round((($nota_ofor_avaliador * 4) + (($nota_flexao_braco + $nota_abdominal + $nota_barra + $nota_corrida) * 3)) / 7, 2); // + ($ano_formacao_ofor_avaliador * 3);
    }
}

function get_nota_final_eaf($id_usuario)
{
    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';

    $conexao = new Conexao();

    $usuario_nota_eipot = $conexao->get_usuario_id($id_usuario);

    $qtd_flexao_braco = $usuario_nota_eipot[0]['qtd_flexao_braco'];
    $qtd_abdominal = $usuario_nota_eipot[0]['qtd_abdominal'];
    $qtd_barra = $usuario_nota_eipot[0]['qtd_barra'];
    $dist_corrida = $usuario_nota_eipot[0]['dist_corrida'];

    $nota_flexao_braco = get_nota_flexao_braco($qtd_flexao_braco);
    $nota_abdominal = get_nota_abdominal($qtd_abdominal);
    $nota_barra = get_nota_flexao_barra($qtd_barra);
    $nota_corrida = get_nota_corrida($dist_corrida);

    $nota_final_eaf = $nota_flexao_braco + $nota_abdominal + $nota_barra + $nota_corrida;

    return $nota_final_eaf;
}

function get_nota_ano_formacao($ano)
{
    $nota = 0;
    $anos_ja_formado = date("Y") - $ano;

    if ($anos_ja_formado >= 1 && $anos_ja_formado <= 4) $nota = 10;
    if ($anos_ja_formado >= 5 && $anos_ja_formado <= 6) $nota = 6;
    if ($anos_ja_formado >= 7 && $anos_ja_formado <= 8) $nota = 4;
    if ($anos_ja_formado >= 9 && $anos_ja_formado <= 10) $nota = 2;
    if ($anos_ja_formado > 10) $nota = 0;

    return $nota;
}

function get_nota_corrida($distancia)
{
    $nota = 0;
    if ($distancia >= 1801 && $distancia <= 2000) $nota = 0.5;
    if ($distancia >= 2001 && $distancia <= 2200) $nota = 1;
    if ($distancia >= 2201 && $distancia <= 2400) $nota = 1.5;
    if ($distancia >= 2401 && $distancia <= 2600) $nota = 2;
    if ($distancia > 2600) $nota = 2.5;
    return $nota;
}

// 11/06/2025 Iago Silva adicionado cálculo do intervalo percorrido
function get_intervalo_corrida($distancia)
{
    $intervalo_corrida = "0 - 1799";

    if ($distancia >= 1801 && $distancia <= 2000) $intervalo_corrida = "1801 - 2000";
    if ($distancia >= 2001 && $distancia <= 2200) $intervalo_corrida = "2001 - 2200";
    if ($distancia >= 2201 && $distancia <= 2400) $intervalo_corrida = "2201 - 2400";
    if ($distancia >= 2401 && $distancia <= 2600) $intervalo_corrida = "2401 - 2600";
    if ($distancia > 2600) $intervalo_corrida = "2601 - 2800";
    return $intervalo_corrida;
}

// 17/06/2025 -> Iago Silva adicionado função para calcular resultado da heteroidentificação
function get_parecer_final_heteroidentificacao($id_candidato, $pareceres, $fase)
{
    if ($fase == 1) {
        $valorParaAprovacao = 3;
    } else {
        $valorParaAprovacao = 2;
    }

    // Verifica quantos parecer possui no array $pareceres na fase $fase que sejam igual a confirmada

    $quantidadePareceresConfirmados = 0;
    $quantidadePareceresNaoConfirmados = 0;

    foreach ($pareceres as $parecer) {
        if ($parecer['fase'] == $fase && $parecer['parecer'] == 'confirmada') {
            $quantidadePareceresConfirmados++;
        } elseif ($parecer['fase'] == $fase && $parecer['parecer'] == 'nao_confirmada') {
            $quantidadePareceresNaoConfirmados++;
        }
    }

    // Retornar junto um true caso confirmada ou um false caso não confirmada
    return $quantidadePareceresConfirmados >= $valorParaAprovacao ? 'CONFIRMADA (' . $quantidadePareceresConfirmados . ' Confirmada X ' . $quantidadePareceresNaoConfirmados . ' Não confirmada)' : 'NÃO CONFIRMADA (' . $quantidadePareceresConfirmados . ' Confirmada X ' . $quantidadePareceresNaoConfirmados . ' Não confirmada)';
}

function inscricao()
{
    if ($_SESSION['selecao_data_final_inscricao'] == null)
        return false;

    if ($_SESSION['selecao_data_final_inscricao'] != null) {
        if (strtotime(date("Y-m-d")) > strtotime($_SESSION['selecao_data_final_inscricao']))
            return false;
        if (strtotime(date("Y-m-d")) < strtotime($_SESSION['selecao_data_inicial_inscricao']))
            return false;
    }
    return true;
}

function insere_recurso()
{
    if ($_SESSION['data_fim_recurso'] == null)
        return false;

    if ($_SESSION['data_fim_recurso'] != null) {
        if (strtotime(date("Y-m-d")) > strtotime($_SESSION['data_fim_recurso']))
            return false;
        if (strtotime(date("Y-m-d")) < strtotime($_SESSION['data_inicio_recurso']))
            return false;
    }
    return true;
}

function seleciona_cidade_vai_servir()
{
    if ($_SESSION['selecao_data_final_cidade'] == null)
        return false;

    if ($_SESSION['selecao_data_final_cidade'] != null) {
        if (strtotime(date("Y-m-d")) > strtotime($_SESSION['selecao_data_final_cidade']))
            return false;
        if (strtotime(date("Y-m-d")) < strtotime($_SESSION['selecao_data_inicial_cidade']))
            return false;
    }
    return true;
}

function retorna_docs_obrigatorios_sobrando_candidato($candidato, $lista_docs_obrigatorios_candidato_sobrando)
{
    $array_docs_sobrando = array();

    if (isset($candidato['sexo'])) {
        foreach ($lista_docs_obrigatorios_candidato_sobrando as $doc_sobrando) {
            if ($doc_sobrando['mulher'] == '1' && $candidato['sexo'] != 'feminino')
                continue;

            if ($doc_sobrando['militar_ativa'] == '1' && $candidato['ativa_reserva'] != 'militar_ativa')
                continue;

            if ($doc_sobrando['reservista'] == '1' && $candidato['licenciamento'] == null)
                continue;

            if ($doc_sobrando['cdi'] == '1' && $candidato['certificado'] != 'cdi')
                continue;

            if ($doc_sobrando['vaga_reservada'] == '1' && $candidato['vaga_reservada'] != '1')
                continue;

            array_push($array_docs_sobrando, $doc_sobrando);
        }
        return $array_docs_sobrando;
    }

    if (isset($candidato[0]['sexo'])) {
        foreach ($lista_docs_obrigatorios_candidato_sobrando as $doc_sobrando) {
            if ($doc_sobrando['mulher'] == '1' && $candidato[0]['sexo'] != 'feminino')
                continue;

            if ($doc_sobrando['militar_ativa'] == '1' && $candidato[0]['ativa_reserva'] != 'militar_ativa')
                continue;

            if ($doc_sobrando['reservista'] == '1' && $candidato[0]['licenciamento'] == null)
                continue;

            if ($doc_sobrando['cdi'] == '1' && $candidato[0]['certificado'] != 'cdi')
                continue;

            if ($doc_sobrando['vaga_reservada'] == '1' && $candidato[0]['vaga_reservada'] != '1')
                continue;

            array_push($array_docs_sobrando, $doc_sobrando);
        }
        return $array_docs_sobrando;
    }
    return $array_docs_sobrando;
}

function avaliacao()
{

    if ($_SESSION['selecao_data_final_avaliacao'] == null)
        return false;

    if ($_SESSION['selecao_data_final_avaliacao'] != null) {
        if (strtotime(date("Y-m-d")) > strtotime($_SESSION['selecao_data_final_avaliacao']))
            return false;
        if (strtotime(date("Y-m-d")) < strtotime($_SESSION['selecao_data_inicial_avaliacao']))
            return false;
    }
    return true;
}

function ordena_vetor_chave($array, $on, $order)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case "asc":
                asort($sortable_array);
                break;
            case "desc":
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function isencao()
{
    if ($_SESSION['selecao_data_final_isencao'] == null)
        return false;

    if ($_SESSION['selecao_data_final_isencao'] != null) {
        if (strtotime(date("Y-m-d")) > strtotime($_SESSION['selecao_data_final_isencao']))
            return false;
        if (strtotime(date("Y-m-d")) < strtotime($_SESSION['selecao_data_inicial_isencao']))
            return false;
    }
    return true;
}

function dias_restantes_inscricao()
{
    if ($_SESSION['selecao_data_final_inscricao'] != null) {
        $data_final = new DateTime(date($_SESSION['selecao_data_final_inscricao']));
        $data_hoje = new DateTime(date("Y-m-d"));
        $intervalo = $data_hoje->diff($data_final);
        return (int)$intervalo->format('%a') + 1;
    }
    return -1;
}


function erro($mensagem)
{
    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';


    $conexao_erro = new Conexao();
    if (isset($_SESSION['id_usuario']) && isset($_SESSION['cpf']))
        $insere_log = $conexao_erro->insere_erro($_SESSION['id_usuario'], $_SESSION['cpf'], $mensagem);
    else
        $insere_log = $conexao_erro->insere_erro(null, null, $mensagem);
    $conexao_erro = null;

    echo '<meta http-equiv="refresh" content="0; URL=../sistema/erro_logado.php?mensagem=' . urlencode($mensagem) . '">';
}
function erro_relatorio($mensagem)
{
    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';


    $conexao_erro = new Conexao();
    if (isset($_SESSION['id_usuario']) && isset($_SESSION['cpf']))
        $insere_log = $conexao_erro->insere_erro($_SESSION['id_usuario'], $_SESSION['cpf'], $mensagem);
    else
        $insere_log = $conexao_erro->insere_erro(null, null, $mensagem);
    $conexao_erro = null;

    echo '<meta http-equiv="refresh" content="0; URL=../../sistema/erro_logado.php?mensagem=' . urlencode($mensagem) . '">';
}
function erro_mensagem($mensagem)
{
    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';

    $conexao_erro = new Conexao();
    if (isset($_SESSION['id_usuario']) && isset($_SESSION['cpf']))
        $insere_log = $conexao_erro->insere_erro($_SESSION['id_usuario'], $_SESSION['cpf'], $mensagem);
    else
        $insere_log = $conexao_erro->insere_erro(null, null, $mensagem);
    $conexao_erro = null;

    echo '<meta http-equiv="refresh" content="0; URL=../sistema/erro_nao_logado.php?mensagem=' . urlencode($mensagem) . '">';
}
function erro_gerar_relatorio_cadastro_candidato($mensagem)
{

    if (file_exists("../banco_dados/conexao.php"))
        include_once '../banco_dados/conexao.php';

    if (file_exists("../../banco_dados/conexao.php"))
        include_once '../../banco_dados/conexao.php';

    if (file_exists("banco_dados/conexao.php"))
        include_once 'banco_dados/conexao.php';

    if (file_exists("conexao.php"))
        include_once 'conexao.php';


    $conexao_erro = new Conexao();
    if (isset($_SESSION['id_usuario']) && isset($_SESSION['cpf']))
        $insere_log = $conexao_erro->insere_erro($_SESSION['id_usuario'], $_SESSION['cpf'], $mensagem);
    else
        $insere_log = $conexao_erro->insere_erro(null, null, $mensagem);
    $conexao_erro = null;

    echo '<meta http-equiv="refresh" content="0; URL=../erro_nao_logado.php?mensagem=' . urlencode($mensagem) . '">';
}

// <editor-fold defaultstate="collapsed" desc="Get Browser">
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    $platform =   "SO Desconhecido";

    $os_array   =   array(
        '/windows nt 10/i'     =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $u_agent)) {
            $platform    =   $value;
        }
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
// </editor-fold>

function arquivo_retorna($id_processo)
{
    $pasta = 'editais/';
    if (is_dir($pasta)) {
        $diretorio = dir($pasta);
        while (($arquivo = $diretorio->read()) !== false) {
            $get_primeiros_char =  substr($arquivo, 0, 5);
            if (strpos($get_primeiros_char, "$id_processo") !== false) {
                echo '<a target="_blank" href=' . $pasta . $arquivo . '>' . $arquivo . '<img src="imagens/file.png" width="40px"></a><br />';
            }
        }
        $diretorio->close();
    } else {
        echo 'A pasta não existe.';
    }
}

function retorna_data_formatada($data, $label)
{
    $data_retorna = "null";
    if ($data != null && $data != '') {
        $data_retorna = $data;
        if (!valida_data($data_retorna)) {
            echo "<br>A data $label e invalida!";
            exit();
        } else {
            $data_retorna = "'" . reverte_data($data_retorna) . "'";
        }
    }
    return $data_retorna;
}

function retorna_campo_formatado($variavel)
{
    $variavel_retorno = "null";
    if (isset($variavel)) {
        if ($variavel != null) {
            $variavel_retorno = '"' . addslashes($variavel) . '"';
        }
    }
    return $variavel_retorno;
}

function retorna_imagem_extensao_arquivo($extensao)
{

    if ($extensao == "pdf")
        return "pdf.png";

    if ($extensao == "odt")
        return "odt.jpg";

    if ($extensao == "ods" || $extensao == "xls" || $extensao == "xlsx")
        return "ods.png";

    if ($extensao == "doc" || $extensao == "docx")
        return "word.png";

    return "file.png";
}

function envia_arquivo($id_processo, $nome_arquivo, $label)
{
    echo '<form method="post" action="recebe_arquivo.php" enctype="multipart/form-data"> 
            <input hidden name="nome_arquivo" value="' . $id_processo . '' . $nome_arquivo . '">
            <input hidden name="id_processo" value="' . $id_processo . '">';
    echo ' 
            <br>
            <div class="col-md-8">
               <label>' . $label . '</label>
               <input type="file" name="arquivo">
            </div>
            <div class="col-md-4">
                <br><input type="submit" class="btn btn-primary btn-block" value="Anexar ' . $label . '" />
            </div>
        </form>';
}

function separa_vaga_rm($vagas)
{
    // Separar as vagas da especialidade por regiao_militar
    $vagas_por_regiao = [];

    foreach ($vagas as $vaga) {
        $regiao = $vaga['regiao_militar'] ?? 'Indefinida'; // Se não tiver valor, usa 'Indefinida'

        // Inicializa a região no array se ainda não existir
        if (!isset($vagas_por_regiao[$regiao])) {
            $vagas_por_regiao[$regiao] = [];
        }

        // Adiciona a cidade e o número de vagas dentro da região
        $vagas_por_regiao[$regiao][] = [
            'cidade' => $vaga['numero_vagas'],
            'vagas' => $vaga['numero_vagas']
        ];
    }

    return $vagas_por_regiao;
}

// 21/06/2025 Iago Silva adicionado função para retornar o nome do posto graduacao abreviado
function calcularVagasCotistas($totalVagasRM)
{
    if ($totalVagasRM <= 2) {
        return 0; // RM com 1 ou 2 vagas não tem cotas reservadas
    } elseif ($totalVagasRM <= 4) {
        return 1; // RM com 3 ou 4 vagas tem 1 cota reservada
    } else {
        return floor($totalVagasRM / 5); // Para 5+ vagas, aplica regra 4:1
    }
}

// 21/06/2025 Iago Silva adicionado função para definir as vagas cotistas
function definirVagasCotistas($total_vagas) {
    $vagas_cotistas = [];
    
    if ($total_vagas <= 4) {
        // Última vaga é cota (ex: 3 vagas → vaga 3; 4 vagas → vaga 4)
        $vagas_cotistas[] = $total_vagas;
    } else {
        // Proporção 4x1 (ex: 5 vagas → vaga 5; 10 vagas → 5 e 10; 18 vagas → 5, 10, 15)
        $intervalo = 5;
        for ($i = $intervalo; $i <= $total_vagas; $i += $intervalo) {
            $vagas_cotistas[] = $i;
        }
    }
    
    return $vagas_cotistas;
}

// ============= FUNÇÃO PARA DEFINIR POSIÇÕES DE COTAS =============
function definirPosicoesCotistas($total_vagas)
{
    $posicoes = [];

    if ($total_vagas <= 4) {
        // Última vaga é cota (3 vagas → vaga 3; 4 vagas → vaga 4)
        $posicoes[] = $total_vagas;
    } else {
        // Proporção 4x1 (5 vagas → vaga 5; 10 vagas → 5 e 10; 18 vagas → 5, 10, 15)
        for ($i = 5; $i <= $total_vagas; $i += 5) {
            $posicoes[] = $i;
        }
    }

    return $posicoes;
}

function get_abrev_posto($posto_grad_abreviado)
{
    if ($posto_grad_abreviado == "Sv Civil")
        return  "Servidor Civil";
    else if ($posto_grad_abreviado == "Sd")
        return  "Soldado";
    else if ($posto_grad_abreviado == "Cb")
        return  "Cabo";
    else if ($posto_grad_abreviado == "3º Sgt")
        return  "3º Sargento";
    else if ($posto_grad_abreviado == "2º Sgt")
        return  "2º Sargento";
    else if ($posto_grad_abreviado == "1º Sgt")
        return  "1º Sargento";
    else if ($posto_grad_abreviado == "ST")
        return  "Sub Tenente";
    else if ($posto_grad_abreviado == "Asp")
        return  "Aspirante";
    else if ($posto_grad_abreviado == "2º Ten")
        return  "2º Tenente";
    else if ($posto_grad_abreviado == "1º Ten")
        return  "1º Tenente";
    else if ($posto_grad_abreviado == "Cap")
        return  "Capitão";
    else if ($posto_grad_abreviado == "Maj")
        return  "Major";
    else if ($posto_grad_abreviado == "TCel")
        return  "Tenente Coronel";
    else if ($posto_grad_abreviado == "Cel")
        return  "Coronel";
    else if ($posto_grad_abreviado == "Gen")
        return  "General";
}

function get_perfil($perfil_velho)
{
    if ($perfil_velho == "admin")
        return "Administrador";
    else if ($perfil_velho == "op")
        return  "Operador";
    else if ($perfil_velho == "con")
        return  "Consulta";
}




function valida_data($data)
{
    $data_numero = str_replace('/', '', $data);
    if (!is_numeric($data_numero)) {
        return false;
    }

    $data_troca_separador = str_replace('-', '/', $data);

    $exploded = multiexplode(array("/", "-", ":", " "), $data_troca_separador);
    $dia = $exploded[0];
    $mes = $exploded[1];
    $ano = $exploded[2];

    if (strlen($dia) > 2 || strlen($dia) < 1)
        return false;
    if (strlen($mes) > 2 || strlen($mes) < 1)
        return false;
    if (strlen($ano) > 4 || strlen($ano) < 4)
        return false;
    if ($dia > 31 || $dia < 1 || $mes < 1 || $mes > 12 || $ano < 1900 || $ano > 2050) {
        return false;
    }
    return true;
}

function reverte_data($data)
{
    if ($data != null || $data != "") {
        $data_troca_separador = str_replace('-', '/', $data);
        $exploded = multiexplode(array("/", "-", ":", " "), $data_troca_separador);
        return $exploded[2] . "-" . $exploded[1] . "-" . $exploded[0];
    } else return null;
}

function trata_data_hora($data)
{
    $data_troca_separador = str_replace('-', '/', $data);
    $exploded = multiexplode(array("/", "-", ":", " "), $data_troca_separador);
    if ((strlen($data) == 10))
        return $exploded[2] . "/" . $exploded[1] . "/" . $exploded[0];
    if ((strlen($data) == 16))
        return $exploded[2] . "/" . $exploded[1] . "/" . $exploded[0] . " " . $exploded[3] . ":" . $exploded[4];
    else
        return $exploded[2] . "/" . $exploded[1] . "/" . $exploded[0] . " " . $exploded[3] . ":" . $exploded[4] . ":" . $exploded[5];
}

function trata_data($data)
{
    $data_troca_separador = str_replace('-', '/', $data);
    $exploded = multiexplode(array("/", "-", ":", " "), $data_troca_separador);
    return $exploded[2] . "/" . $exploded[1] . "/" . $exploded[0];
}

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function valida_cpf($cpf)
{
    // verifica se e numerico
    if (!is_numeric($cpf)) {
        return false;
    }

    // verifica se esta usando a repeticao de um numero
    if (($cpf == '11111111111') ||
        ($cpf == '22222222222') ||
        ($cpf == '33333333333') ||
        ($cpf == '44444444444') ||
        ($cpf == '55555555555') ||
        ($cpf == '66666666666') ||
        ($cpf == '77777777777') ||
        ($cpf == '88888888888') ||
        ($cpf == '99999999999') ||
        ($cpf == '00000000000')
    ) {
        return false;
    }

    //PEGA O DIGITO VERIFIACADOR
    $dv_informado = substr($cpf, 9, 2);

    for ($i = 0; $i <= 8; $i++) {
        $digito[$i] = substr($cpf, $i, 1);
    }

    //CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
    $posicao = 10;
    $soma = 0;

    for ($i = 0; $i <= 8; $i++) {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
    }

    $digito[9] = $soma % 11;

    if ($digito[9] < 2) {
        $digito[9] = 0;
    } else {
        $digito[9] = 11 - $digito[9];
    }

    //CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
    $posicao = 11;
    $soma = 0;

    for ($i = 0; $i <= 9; $i++) {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
    }

    $digito[10] = $soma % 11;

    if ($digito[10] < 2) {
        $digito[10] = 0;
    } else {
        $digito[10] = 11 - $digito[10];
    }

    //VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
    $dv = $digito[9] * 10 + $digito[10];
    if ($dv != $dv_informado) {
        return false;
    }

    return true;
}

function mascara($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function normaliza_texto($texto)
{
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = preg_replace('/[áàãâä]/u', 'a', $texto);
    $texto = preg_replace('/[éèêë]/u', 'e', $texto);
    $texto = preg_replace('/[íìîï]/u', 'i', $texto);
    $texto = preg_replace('/[óòõôö]/u', 'o', $texto);
    $texto = preg_replace('/[úùûü]/u', 'u', $texto);
    $texto = preg_replace('/[ç]/u', 'c', $texto);
    $texto = preg_replace('/[^a-z0-9 ]+/u', '', $texto); // remove símbolos
    return trim($texto);
}
