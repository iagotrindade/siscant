<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2342344!");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

if (inscricao()) {
    erro("Erro 24545774! Inscrição em andamento!");
    exit();
}

$criptografia = $_POST['crip'];

if ($criptografia != hash('sha256', $_SESSION['chave'] . "freitas")) {
    erro("Erro 8445324574! Não foi possível executar o arquivo!");
    exit();
}

$script = $_POST['script'];
if ($script == "") {
    erro("Erro 52354! Você deve escolher um script a ser executado!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();

$valor_gru = floatval($get_selecao[0]['valor_gru']);

if ($script == 'inscricao') {
    // Fazer validação da data de inscrição!


    $pagamento_obrigatorio = $get_selecao[0]['pagamento'];
    $selecao_eliminar_caso_nao_adicione_foto = false;
    if ($get_selecao[0]['eliminar_caso_nao_adicione_foto'] == '1')
        $selecao_eliminar_caso_nao_adicione_foto = true;

    $selecao_eliminar_caso_nao_adicione_docs_obrigatorios = false;
    if ($get_selecao[0]['eliminar_docs_obrigatorios'] == '1')
        $selecao_eliminar_caso_nao_adicione_docs_obrigatorios = true;



    $lista_candidatos = $conexao->get_candidatos_concorrendo();

    $usuarios_desclassificados = array("Lista de CPFs desclassificados pelo script de Inscrição");

    foreach ($lista_candidatos as $candidato) {

        // Médico obrigatório não pode ser desclassificado
        if ($candidato['medico_obrigatorio'] == 1) continue;

        /////////////////////////////////////////////////////////////////////////////////////////////
        // Médico não paga! Mas se cadastrou em outra especialidade que não é médico então deve pagar
        /*
        $cadastrou_especialidade_medico = false;
        $tem_especialidade_que_nao_e_medico = false;
        foreach($lista_inscricoes as &$especialidade)
        {
            if($especialidade['ott_stt'] == 'medico')
                $cadastrou_especialidade_medico = true;
        }
        
        foreach($lista_inscricoes as &$especialidade2)
        {
            if($especialidade2['ott_stt'] != 'medico')
                $tem_especialidade_que_nao_e_medico = true;
        }
        if($cadastrou_especialidade_medico == true && $tem_especialidade_que_nao_e_medico == false)
            array_push($arquivo_pagamento, "Médico");
        */
        /////////////////////////////////////////////////////////////////////////////////////////////

        ////////////////////
        // ESPECIALIDADE
        $se_inscreveu_em_especialidade = true;
        $lista_inscricoes = $conexao->get_especialidade_candidato($candidato['id']);
        if (count($lista_inscricoes) == 0)
            $se_inscreveu_em_especialidade = false;
        // EIPOT não tem especialidade
        if (isset($_SESSION['eipot'])) $se_inscreveu_em_especialidade = true;

        ////////////////////
        // FOTO
        $passou_teste_foto = true;
        if ($selecao_eliminar_caso_nao_adicione_foto) {
            $foto_usuario = $conexao->get_foto_usuario($candidato['id']);
            if (count($foto_usuario) == 0)
                $passou_teste_foto = false;
        }
        ////////////////////
        // DOCs OBRIGATÓRIOS
        $passou_teste_docs_obrigatorios = true;
        if ($selecao_eliminar_caso_nao_adicione_docs_obrigatorios) {
            $lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($candidato['id']);
            $filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($candidato, $lista_docs_obrigatorios_sobrando);
            if (count($filtro_lista_docs_obrigatorios_sobrando))
                $passou_teste_docs_obrigatorios = false;
        }
        ////////////////////
        // PAGAMENTO
        $var_pagamento = true;
        if ($_SESSION['selecao_pagamento']) {
            $arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($candidato['id']);
            if (count($arquivo_pagamento) == 0)
                $var_pagamento = false;
        }

        if ($se_inscreveu_em_especialidade && $passou_teste_docs_obrigatorios && $var_pagamento && $passou_teste_foto) {
            $obs = "Candidato validado pelo script de inscrição! Cod: 77942";
            $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
            $alteracoes_detalhadas =  print_r($resultado, true);
            if ($resultado)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "14121", "usuario", "Insert", "Observação adicionada, Candidato validado pelo script de inscricao", $alteracoes_detalhadas);
        } else {
            array_push($usuarios_desclassificados, $candidato['cpf']);

            $justificativa = "";
            if (!$var_pagamento)
                $justificativa = $justificativa . "Cod: 475978! Etapa I - Não adicionou o arquivo de pagamento/isenção!!! ";

            if (!$passou_teste_foto)
                $justificativa = $justificativa . "Cod: 715978! Etapa I - Não adicionou a sua foto!!! ";

            if (!$se_inscreveu_em_especialidade)
                $justificativa = $justificativa . "Cod: 351418! Etapa I -  Não se inscreveu em nenhuma especialidade!!! ";

            if (!$passou_teste_docs_obrigatorios)
                $justificativa = $justificativa . "Cod: 191418! Etapa I -  Não adicionou todos os documentos obrigatórios!!! ";

            $obs = "Candidato DESCLASSIFICADO pelo script de inscrição! Cod: 67945";
            $resultado_obs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
            $alteracoes_detalhadas =  print_r($resultado_obs, true);
            if ($resultado_obs)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "16112", "usuario", "Update", "Observação adicionada, Candidato ELIMINADO pelo script de inscricao", $alteracoes_detalhadas);


            $resultado = $conexao->usuario_desclassificado($candidato['id'], $justificativa);
            $alteracoes_detalhadas =  print_r($resultado, true);
            if ($resultado)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "16113", "usuario", "Update", "Desclassificado pelo script de inscrição", $alteracoes_detalhadas);
        }
    }
    $usuarios_desclassificados =  print_r($usuarios_desclassificados, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16114", "usuario", "Update", "Executou o script de inscricao", $usuarios_desclassificados);
    $conexao = null;
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}

if ($script == 'aptos_jise') {
    $lista_candidatos = $conexao->get_candidatos_concorrendo();
    $usuarios_passaram_etapa3 = ["Lista de CPFs passaram etapa 4"];
    $candidatos_processados = [];

    foreach ($lista_candidatos as &$candidato) {
        if ($candidato['apto_saude'] != 1 || $candidato['etapa'] != 3) {
            continue;
        }

        $candidato['especialidades'] = $conexao->get_especialidade_candidato($candidato['id']);

        if (!in_array($candidato['id'], $candidatos_processados)) {
            $resultadoEtapaCandidato = $conexao->altera_etapa_candidato($candidato['id'], '4');

            if ($resultadoEtapaCandidato) {
                $usuarios_passaram_etapa3[] = $candidato['cpf'];

                // Observação geral
                $obs = "Cod: 95471. Candidato passou para etapa IV!";
                $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
                $alteracoes_detalhadas = print_r($resultado, true);

                if ($resultado) {
                    $conexao->insere_log(
                        $_SESSION['id_usuario'],
                        $candidato['cpf'],
                        $candidato['id'],
                        "14122",
                        "usuario",
                        "Insert",
                        "Observação adicionada, Candidato passou para ETAPA IV",
                        $alteracoes_detalhadas
                    );
                }
            }

            $candidatos_processados[] = $candidato['id'];
        }

        // Processa cada especialidade
        foreach ($candidato['especialidades'] as $especialidade) {

            if ((int)$especialidade['concorrendo'] === 1 && (int)$especialidade['apagado'] === 0 && (int)$especialidade['etapa'] === 3) {
                $resultadoEspecialidade = $conexao->altera_etapa_especialidade($candidato['id'], $especialidade['id_especialidade'], '4');

                if ($resultadoEspecialidade) {
                    $obsEspecialidade = "Cod: 95471. Candidato passou para etapa IV na especialidade " . $especialidade['especialidade'] . "!";
                    $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $obsEspecialidade, 1);
                    $alteracoes_detalhadas_especialidade = print_r($resultado, true);

                    if ($resultado) {
                        $conexao->insere_log(
                            $_SESSION['id_usuario'],
                            $candidato['cpf'],
                            $candidato['id'],
                            "14122",
                            "usuario",
                            "Insert",
                            "Observação adicionada, Candidato passou para ETAPA IV na especialidade " . $especialidade['especialidade'] . "!",
                            $alteracoes_detalhadas_especialidade
                        );
                    }
                }
            }
        }
    }

    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}

if ($script == 'inaptos_jise') {
    $lista_candidatos = $conexao->get_candidatos_concorrendo();
    $usuarios_passaram_etapa3 = ["Lista de CPFs passaram etapa 4"];
    $candidatos_processados = [];

    foreach ($lista_candidatos as $candidato) {
        // pula quem está apto ou não está na etapa 3
        if ((int)$candidato['apto_saude'] === 1 || (int)$candidato['etapa'] != 3) {
            continue;
        }

        (int)$candidato['apto_saude'] == 0 ? $justificativa = 'Candidato INAPTO na Inspeção de Saúde' : 'Candidato NÃO COMPARECEU a Inspeção de Saúde';

        // carrega as especialidades UMA vez
        $candidato['especialidades'] = $conexao->get_especialidade_candidato($candidato['id']);
        if (!is_array($candidato['especialidades'])) {
            $candidato['especialidades'] = [];
        }

        // carrega dados do usuário UMA vez
        $get_candidato = $conexao->get_usuario_id($candidato['id']);
        $usuario_concorrendo = false;
        if (isset($get_candidato[0]['concorrendo']) && (int)$get_candidato[0]['concorrendo'] === 1) {
            $usuario_concorrendo = true;
        }

        // Processa cada especialidade do candidato
        foreach ($candidato['especialidades'] as $especialidade) {
            // valida que temos o id do relacionamento candidato_x_especialidade
            if ($especialidade['etapa' != 3]) {
                continue;
            }

            // Pega nome da especialidade (usa o id da especialidade do array)
            $nome_especialidade = "";
            if (!empty($especialidade['id_especialidade'])) {
                $get_especialidade_id = $conexao->get_especialidade_id($especialidade['id_especialidade']);
                if (isset($get_especialidade_id[0]['nome'])) {
                    $nome_especialidade = $get_especialidade_id[0]['nome'];
                }
            }

            // atualiza status 'concorrendo' na especialidade
            $resultado_concorrendo = $conexao->status_concorrendo_especialidade(
                $especialidade['id_candidato_x_especialidade'],
                0,
                $justificativa
            );

            $alteracoes_detalhadas = print_r($resultado_concorrendo, true);
            if ($resultado_concorrendo) {
                // log com concatenação correta
                $conexao->insere_log(
                    $_SESSION['id_usuario'],
                    $_SESSION['cpf'],
                    $candidato['id'],
                    "16120",
                    "candidato_x_especialidade",
                    "Update",
                    "Alterou o status do candidato " . ($get_candidato[0]['cpf'] ?? $candidato['cpf']) . " para DESCLASSIFICADO na especialidade $nome_especialidade! Justificativa: $justificativa",
                    $alteracoes_detalhadas
                );
            } else {
                $conexao = null;
                erro("Erro 4575634 O status não mudou!");
                exit();
            }

            // agora verifica se o candidato ainda está concorrendo em alguma outra especialidade
            $get_especialidade_candidato = $conexao->get_especialidade_candidato($candidato['id']);
            $esta_concorrendo_em_outra_especialidade = false;
            if (is_array($get_especialidade_candidato)) {
                foreach ($get_especialidade_candidato as $esp_check) {
                    if ((int)$esp_check['concorrendo'] === 1) {
                        $esta_concorrendo_em_outra_especialidade = true;
                        break;
                    }
                }
            }

            // se não está concorrendo em nenhuma especialidade, marca o usuário como não concorrendo
            if ($esta_concorrendo_em_outra_especialidade === false) {
                $observacao = "Não está concorrendo em nenhuma especialidade! Justificativa: $justificativa";
                $resultado_concorrendo_usuario = $conexao->status_concorrendo($candidato['id'], 0, $observacao);
                $alteracoes_detalhadas_usuario = print_r($resultado_concorrendo_usuario, true);
                if ($resultado_concorrendo_usuario) {
                    $conexao->insere_log(
                        $_SESSION['id_usuario'],
                        $_SESSION['cpf'],
                        $candidato['id'],
                        "16116",
                        "usuario",
                        "Update",
                        "Alterou o status do candidato " . ($get_candidato[0]['cpf'] ?? $candidato['cpf']) . " para DESCLASSIFICADO no processo seletivo! Justificativa: $observacao",
                        $alteracoes_detalhadas_usuario
                    );
                } else {
                    // se não conseguiu alterar o status do usuário, continua para próxima especialidade/candidato
                    continue;
                }
            }
        } // foreach especialidades
    } // foreach candidatos

    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}


if ($script == 'pagamento' && $_SESSION['selecao_pagamento']) {
    $usuarios_desclassificados = array("Lista de CPFs desclassificados pelo script de Pagamento");
    $lista_candidatos = $conexao->get_candidatos_pagaram_e_nao_estao_tabela_gru_pagas($valor_gru);

    foreach ($lista_candidatos as $candidato) {
        if ($candidato['medico_obrigatorio'] == 1) continue;

        $justificativa = "Cod: 328451! Etapa I -  Pagamento não efetivado!!!";
        $justificativa_antiga = null;
        if ($candidato['concorrendo'] == 0)
            $justificativa_antiga = $candidato['justificativa_concorrendo'];

        $justificativa = $justificativa_antiga . " | " . $justificativa;

        /////////////////////////////////////////////////////////////////////////////////////////////
        // Médico não paga! Mas se cadastrou em outra especialidade que não é médico então deve pagar
        /*
        $lista_inscricoes = $conexao->get_especialidade_candidato($candidato['id']);
        
        $cadastrou_especialidade_medico = false;
        $tem_especialidade_que_nao_e_medico = false;
        foreach($lista_inscricoes as $especialidade)
        {
            if($especialidade['ott_stt'] == 'medico')
                $cadastrou_especialidade_medico = true;
        }
        
        foreach($lista_inscricoes as $especialidade2)
        {
            if($especialidade2['ott_stt'] != 'medico')
                $tem_especialidade_que_nao_e_medico = true;
        }
        if($cadastrou_especialidade_medico == true && $tem_especialidade_que_nao_e_medico == false)
            continue;
        */
        /////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////

        $resultado_desclassifica = $conexao->usuario_desclassificado($candidato['id'], $justificativa);
        if ($resultado_desclassifica) {
            array_push($usuarios_desclassificados, $candidato['cpf']);
            $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $justificativa, 1);
            $alteracoes_detalhadas =  print_r($resultado, true);
            if ($resultado)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "14121", "usuario", "Insert", "Observação adicionada, Candidato desclassificado pelo scrip de Pagamento", $alteracoes_detalhadas);
        }
    }

    $usuarios_desclassificados =  print_r($usuarios_desclassificados, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16125", "usuario", "Update", "Executou o script de pagamento", $usuarios_desclassificados);
    $conexao = null;
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}

if ($script == 'isentos' && $_SESSION['selecao_pagamento']) {
    $usuarios_desclassificados = array("Lista de CPFs desclassificados pelo script de Isentos");

    $lista_candidatos_isentos = $conexao->get_candidatos_disseram_isentos_mas_nao_sao();

    foreach ($lista_candidatos_isentos as $candidato) {
        $justificativa = "Cod: 245876! Etapa I - Candidato NÃO ISENTO!!!";
        if ($candidato['medico_obrigatorio'] == 1) continue;

        $justificativa_antiga = null;
        if ($candidato['concorrendo'] == 0)
            $justificativa_antiga = $candidato['justificativa_concorrendo'];

        $justificativa = $justificativa_antiga . " | " . $justificativa;


        $resultado_desclassifica = $conexao->usuario_desclassificado($candidato['id'], $justificativa);

        if ($resultado_desclassifica) {
            array_push($usuarios_desclassificados, $candidato['cpf']);

            $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $justificativa, 1);
            $alteracoes_detalhadas =  print_r($resultado, true);
            if ($resultado)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "14121", "usuario", "Insert", "Observação adicionada, Candidato desclassificado pelo scrip de Isentos", $alteracoes_detalhadas);
        }
    }

    $usuarios_desclassificados =  print_r($usuarios_desclassificados, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16126", "usuario", "Update", "Executou o script de Isentos", $usuarios_desclassificados);
    $conexao = null;
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}

if ($script == 'ctrl_z') {
    if ((int)$_SESSION['etapa_selecao'] != 1) {
        erro("Erro 327245845! A seleção já passou da ETAPA I! Logo não é possível executar o script!");
        exit();
    }

    $usuarios_ctrl_z = array("Lista de CPFs desclassificados pelo script de Isentos");

    $lista_candidatos_desclassificados = $conexao->get_candidatos_desclassificados();

    foreach ($lista_candidatos_desclassificados as $candidato) {
        $justificativa = "Cod: 954741! Etapa I - Candidato Retornou Pelo Script CTRL Z!!!";
        if ($candidato['medico_obrigatorio'] == 1) continue;

        $resultado_classifica = $conexao->status_concorrendo($candidato['id'], '1', $justificativa);

        if ($resultado_classifica) {
            array_push($usuarios_ctrl_z, $candidato['cpf']);

            $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $justificativa, 1);
            $alteracoes_detalhadas =  print_r($resultado, true);
            if ($resultado)
                $insere_log = $conexao->insere_log($candidato['id'], $candidato['cpf'], $candidato['id'], "14131", "usuario", "Insert", "Observação adicionada, Candidato retornou para seleção pelo script CTRL + Z", $alteracoes_detalhadas);
        }
    }

    $usuarios_ctrl_z =  print_r($usuarios_ctrl_z, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "161503", "usuario", "Update", "Executou o script CTRL + Z", $usuarios_ctrl_z);
    $conexao = null;
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}


header("Location: ../sistema/etapa_passagem.php?sucesso=0");
