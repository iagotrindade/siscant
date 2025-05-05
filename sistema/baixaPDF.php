<?php

session_start();
include_once '../banco_dados/conexao.php';
include_once './funcoes.php';
$conexao = new Conexao();
if(!isset($_SESSION['id_usuario']))
{
    erro("Erro 785783232333! Página não encontrada!");
    exit();
}

$getCodigo = filter_input(INPUT_GET, "codigo", FILTER_DEFAULT);

$getParametros_nome_arquivo = filter_input(INPUT_GET, "nome_arquivo", FILTER_DEFAULT);

function inputHeader($nome_arquivo, $caminho_arquivo)
{
    header("Content-disposition: inline; filename='{$nome_arquivo}'");
    header('Content-type: application/pdf');
    readfile($caminho_arquivo);
}

if($getCodigo == "doc_obr_vis"      || $getCodigo == "can_pag_insc"  || $getCodigo == "cand_esp_cad_vis" || $getCodigo == "rel_ise_pag"
|| $getCodigo == "cand_inf_pag"     || $getCodigo == "cand_esp_aval" || $getCodigo == "can_esp_vis"      || $getCodigo == "can_doc_obr_aval" 
|| $getCodigo == "arq_obr_usu"      || $getCodigo == "cand_esp" || $getCodigo = "rec_cand_vis")
{
    $nome_arquivo = $getParametros_nome_arquivo;
    $caminho_arquivo = $_SESSION['pasta_arquivos']."{$nome_arquivo}";
    inputHeader($nome_arquivo,$caminho_arquivo);
}

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20104", null, "Open", "Abriu o arquivo: $getParametros_nome_arquivo pelo código: $getCodigo ", null);

/*
switch($getCodigo)
{
    case "doc_obr_vis":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "can_pag_insc":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "cand_esp_cad_vis":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "rel_ise_pag":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "cand_inf_pag":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "cand_esp_aval":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "can_esp_vis":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "can_doc_obr_aval":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "arq_obr_usu":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
    case "cand_esp":
        $nome_arquivo = $getParametros_nome_arquivo;
        $caminho_arquivo = "/mnt/pdf/{$nome_arquivo}";
        inputHeader($nome_arquivo,$caminho_arquivo);
        break;
}
*/


?>