<?php
/*ASP SILVA ATUALIZADO EM 28 MAIO 25 */
// var_dump($_SESSION["perfil"]); 
if ($_SESSION['perfil'] == 'avaliador') {
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

    $tem_permissao_para_avaliar = false;

    foreach ($lista_especialidade_avaliador as $especialidade_avaliador) {

        $verifica_usuario_avaliador = $conexao->verifica_usuario_avaliador($especialidade_avaliador['id_especialidade'], $id_usuario);
        if (count($verifica_usuario_avaliador) > 0)
            $tem_permissao_para_avaliar = true;
    }
    if (!$tem_permissao_para_avaliar) {
        erro("Erro 242384! Página não encontrada!");
        exit();
    }
}

$resultado_selecao = $conexao->get_selecao_id();
if (
    $resultado_selecao[0]['codigo'] == 'ott_stt'
    || $resultado_selecao[0]['codigo'] == 'mfdv'
    || $resultado_selecao[0]['codigo'] == 'cet'
    || $resultado_selecao[0]['codigo'] == 'ott'
    || $resultado_selecao[0]['codigo'] == 'stt'
) {
    $_SESSION['eipot'] = 0;
    unset($_SESSION['eipot']);
}

$especialidade_cadastradas_candidato = $conexao->get_especialidade_candidato($id_usuario);

$lista_esp_cadastrada = "";
foreach ($especialidade_cadastradas_candidato as &$esp_cadastrada) {
    $lista_esp_cadastrada = $lista_esp_cadastrada . " | " . strtoupper($esp_cadastrada['ott_stt']) . ' ' . $esp_cadastrada['especialidade'] . "  - Reg Conselho: " . $esp_cadastrada['registro_conselho'];
}

$pagamento_obrigatorio = null;
$get_selecao = $conexao->get_selecao_id();
$pagamento_obrigatorio = $get_selecao[0]['pagamento'];


if ($medico_obrigatorio == 0 && ($id_selecao != $_SESSION['selecao']) && $_SESSION['perfil'] != 'om') {
    erro("Erro 3475368568! Usuário/Candidato de outra seleção!");
    exit();
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="row">
                <div class="col-md-10">
                    <legend>
                        Informações do Candidato<?php if ($concorrendo == 0) echo " <font color='red' > Desclassificado </font>";  ?>
                        <font color="red"><b><?php if ($apagado == 1) echo " - Usuário Apagado - "; ?></b></font>
                        <?php if ($medico_obrigatorio == 1) echo " | OBRIGATÓRIO | "; ?>

                        <?php
                        $crip_chave123 = hash('sha256', $id_usuario . $_SESSION['chave']);
                        if ((int)$etapa > 1)
                            echo ' ---> Comprovante de inscrição 
                                            <a href="mpdf/comprovante_inscricao.php?crip=' . $crip_chave123 . '&id_candidato=' . $id_usuario . '" target="_blank">
                                            <img src="imagens/pdf.png" width="35px"></a> 
                                            <---
                                        ';
                        ?>

                    </legend>
                </div>

                <div class="col-md-1" <?php if ($medico_obrigatorio != 1) echo "hidden" ?>>
                    <a href="edita_medico_obrigatorio.php?id_usuario=<?php echo $id_usuario ?>">
                        <img src="imagens/editar.png" width="50px">
                    </a>
                </div>

                <div class="col-md-1" <?php if ($medico_obrigatorio == 1) echo "hidden" ?>>
                    <a target="_blank" href="mpdf/relatorio_completo_candidato.php?codigo=<?php
                                                                                            echo hash('sha256', $_SESSION['chave']);
                                                                                            echo "&id_candidato=" . $id_usuario;

                                                                                            ?>">
                        <img src="imagens/pdf.png" width="50px">
                    </a>
                </div>

                <div class="col-md-1" <?php if ($medico_obrigatorio != 1) echo "hidden" ?>>
                    <a target="_blank" href="mpdf/relatorio_medico_obrigatorio.php?codigo=<?php
                                                                                            echo hash('sha256', $_SESSION['chave']);
                                                                                            echo "&id_candidato=" . $id_usuario;

                                                                                            ?>">
                        <img src="imagens/pdf.png" width="50px">
                    </a>
                </div>
            </div>

            <div class="table-responsive"> <!-- inFORMAÇÕES BASICAS USUSARIO-->

                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nome Completo: </b><?php echo $nome_completo ?></td>
                            <td><b>CPF: </b><?php echo mascara($cpf, '###.###.###-##') ?></td>
                            <td><b>Nome Social: </b><?php if ($nome_social != null) echo $nome_social ?></td>
                        </tr>
                        <tr>
                            <td><b>Estado Civil: </b><?php echo $estado_civil;
                                                        if ($companheiro != null) echo " com $companheiro "; ?></td>
                            <td><b>Identidade: </b><?php echo $identidade ?></td>
                            <td><b>Data de Nascimento: </b><?php echo $data_nascimento ?></td>

                        </tr>
                        <tr>
                            <td><b>Sexo: </b><?php echo $sexo ?></td>
                            <td><b>Nome da pai: </b><?php echo $pai ?></td>
                            <td><b>Nome da mãe: </b><?php echo $mae ?></td>
                        </tr>
                        <tr>
                            <td><b>Nacionalidade (País): </b><?php echo $nacionalidade ?></td>
                            <td><b>Naturalidade (Cidade): </b><?php echo $naturalidade ?></td>
                            <td><b>E-Mail: </b><?php echo $mail ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Rua/Número/Complemento: </b><?php echo $rua_num_complemento ?></td>
                            <td><b>Bairro: </b><?php echo $bairro ?></td>
                        </tr>


                        <?php
                        if ($_SESSION['selecao_codigo'] == 'mfdv') {

                            if ($voluntario_12rm != null) {
                                if ($voluntario_12rm == 1)
                                    $voluntario_12rm = "Sim";
                                else if ($voluntario_12rm == 0)
                                    $voluntario_12rm = "Não";
                            }

                            echo '<tr>
                                    <td><b>Nome do Instituto de Ensino: </b>' . $instituto_ensino . '</td>
                                    <td><b>Ano de Formação: </b>' . $ano_formacao . '</td>
                                    <td><b>Conselho: </b>' . $conselho . '</td>
                                </tr>';

                            echo '<tr>
                                    <td colspan="2"><b>Cidade Instituto de Ensino: </b>' . $cidade_instituto_ensino . '</td>                                        
                                    <td><b>UF Instituto de Ensino: </b>' . $uf_instituto_ensino . '</td>
                                </tr>';

                            echo '<tr>
                                    <td><b>Dependentes: </b>' . $dependente . '</td>
                                    <td><b>Voluntário para 12ª RM: </b>' . $voluntario_12rm . '</td>
                                    <td><b>Prioridade de Força: </b>' . $prioridade_forca . '</td>
                                </tr>';
                        }


                        ?>
                        <tr>
                            <td><b>UF: </b><?php echo $uf ?></td>
                            <td><b>Cep: </b><?php echo $cep ?></td>
                            <td><b>Cidade: </b><?php echo $cidade ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"><b>Telefone de Contato: </b><?php echo $tel_celular ?></td>
                            <td><b>Telefone de Recados: </b><?php echo $tel_residencial ?></td>
                        </tr>

                        <?php
                        /*
                        if($tempo_sv_pub == 1)
                        {
                            echo "<tr>";
                            echo "<td><b>Tempo de Serviço Público Anos: </b>$tempo_sv_pub_anos </td>";
                            echo "<td><b>Tempo de Serviço Público Meses: </b>$tempo_sv_pub_meses </td>";
                            echo "<td><b>Tempo de Serviço Público Dias: </b>$tempo_sv_pub_dias </td>";
                            echo "</tr>";
                        }
                     
                        if($tempo_sv_pub == 0)
                        {
                            echo "<tr>";
                            echo "<td colspan='3'><b>Tempo de Serviço Público: Não</b></td>";
                            echo "</tr>";
                        }
                        */
                        if ($tempo_sv_mil == 1) {
                            echo "<tr>";
                            echo "<td><b>Tempo de Serviço Militar Anos: </b>$tempo_sv_mil_anos </td>";
                            echo "<td><b>Tempo de Serviço Militar Meses: </b>$tempo_sv_mil_meses </td>";
                            echo "<td><b>Tempo de Serviço Militar Dias: </b>$tempo_sv_mil_dias </td>";
                            echo "</tr>";
                        }
                        if ($tempo_sv_mil == 0) {
                            echo "<tr>";
                            echo "<td colspan='3'><b>Tempo de Serviço Militar: Não</b></td>";
                            echo "</tr>";
                        }

                        echo "<tr>";
                        echo "<td colspan='3'><b>Civil/Militar: </b> $civil_militar </td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td><b>Certificado: </b>$certificado </td>";
                        echo "<td> <b>Nº do Documento: </b>$num_ducumento </td>";
                        echo "<td><b>Data da Expedição: </b>$data_expedicao</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td><b>Ativa/Reserva: </b>$ativa_reserva</td>";
                        echo "<td><b>Força: </b>$forca </td>";
                        echo "<td><b>Ano de incorporação: </b>$ano_incorporacao</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td><b>Posto/Graduação: </b>$posto_grad</td>";
                        echo "<td><b>Arma/Quadro/Serviço: </b>$arma_quadro_servico</td>";
                        echo "<td><b>Licenciamento: </b>$licenciamento</td>";
                        echo "</tr>";

                        echo "<tr><td colspan='3'><b>Especialidade(s) Cadastrada(s): </b> $lista_esp_cadastrada </td></tr>";

                        ?>

                        <tr>
                            <td><b>Autodeclaração: </b><?php echo $autodeclaracao ?></td>
                            <td colspan="2"><b>Concorrendo à Vaga Reservada para Negros - Lei 12.990, 9 Jun 14: </b>
                                <?php
                                if ($vaga_reservada == 0 || ($vaga_reservada == null))  $vaga_reservada = "Não";
                                if ($vaga_reservada == 1) $vaga_reservada = "Sim";
                                echo $vaga_reservada ?></td>
                        </tr>

                        <tr <?php if (isset($_SESSION['eipot']) != 1) echo ' hidden ' ?>>
                            <td colspan="2"><b>RM Etapas Presenciais: </b><?php echo $rm_inscricao ?>ª</td>
                            <td><b>RM's de Interesse: </b><?php echo $rm_destino ?></td>
                        </tr>

                        <tr <?php if (isset($_SESSION['eipot']) != 1) echo ' hidden ' ?>>
                            <td colspan="2"><b>Curso de Graduação: </b><?php echo $curso_graduacao ?></td>
                            <td><b>Arma EIPOT: </b><?php echo $arma_eipot ?></td>
                        </tr>

                        <tr <?php if (isset($_SESSION['eipot']) != 1) echo ' hidden ' ?>>
                            <td colspan="2"><b>Ano de Formação OFOR: </b><?php echo $ano_formacao_ofor ?></td>
                            <td><b>Nota OFOR: </b><?php echo $nota_ofor ?></td>
                        </tr>

                        <tr>
                            <td><b>Apresentação do Candidato na OM: </b><?php echo $apresentacao_om ?></td>
                            <td colspan="2"><b>Observação OM: </b><?php echo $observacao_om ?></td>
                        </tr>

                        <?php
                        if (isset($_SESSION['6_regiao']) || isset($_SESSION['12_regiao']))
                            echo "<tr><td colspan='3'><b>Cidade de Apresentação nas Etapas Presenciais: </b> $cidade_etapas_presenciais </td></tr>";
                        ?>
                        <?php
                        if (isset($_SESSION['12_regiao']) && isset($_SESSION['12_regiao_musica']))
                            echo "<tr><td colspan='3'><b>Cidade do exame de comprovação de habilidade musical: </b> $cidade_exame_musica_12rm </td></tr>";
                        ?>

                        <?php
                        if ($medico_obrigatorio != null)
                            include_once 'medico_obrigatorio_informacoes.php';
                        ?>

                        <tr>
                            <td colspan="3">
                                <center><img style="box-shadow: 0px 0px 10px #197249; border-radius: 5px;" src="fotos/<?php echo $foto_nome ?>" width="200px"></center>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <a href="#especialidades">
                        <legend>Especialidade(s) <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#arquivos_obrigatorios">
                        <legend>Docs Obrigatórios <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#observacoes">
                        <legend>Observações <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#recursos">
                        <legend>Recursos <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#insere_arquivo_candidato">
                        <legend>Arquivos <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#auditoria">
                        <legend>Auditoria <i class="fa fa-arrow-down"></i></legend>
                    </a>
                </div>
            </div>
        </div>
        <?php
        if ($_SESSION['perfil'] == 'om')
            include_once 'codigos/om_preenchimento.php';
        ?>
        <!--silva 26MAIO2025-->
        <a name="isento"></a>
        <div class="card" <?php if ($pagamento_obrigatorio == '0' || $pagamento_obrigatorio == null) echo 'hidden' ?>>
            <legend>Arquivo de Pagamento/Isenção</legend>
            <div class="row">
                <div class="col-md-12">

                    <?php
                    $arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($id_usuario);
                    $isento = null;
                    if (count($arquivo_pagamento) > 0)
                        $isento = (int)$arquivo_pagamento[0]['isento'];

                    if ($isento == 1)
                        $isento = "SELECIONOU ISENTO";
                    else if ($isento == 0)
                        $isento = "";
                    ?>

                    <div class="col-lg-6" <?php if (count($arquivo_pagamento) == 0) echo 'hidden' ?>>
                        <div class="bs-component">
                            <a target="_blank" href="baixaPDF.php?codigo=cand_inf_pag&nome_arquivo=<?php if (count($arquivo_pagamento) > 0) echo $arquivo_pagamento[0]['nome'] ?>">
                                <div class="alert alert-success">
                                    <strong>Visualizar arquivo adicionado - <u><?php echo $isento ?></u></strong> <img src="imagens/pdf.png" height="30px">
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6" <?php if (count($arquivo_pagamento) > 0) echo 'hidden' ?>>
                        <div class="bs-component">
                            <div class="alert alert-laranja">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <strong>Nenhum arquivo adicionado!</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

        if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos')
            include_once 'codigos/isento_pagamento.php';
        ?>


        <!--SILVA 26maio25-->

        <?php

        $resultado_selecao = $conexao->get_selecao_id();
        //  var_dump($resultado_selecao[0]['codigo']);
        if ($resultado_selecao[0]['codigo'] == 'eipot') {

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos' || $_SESSION['perfil'] == 'avaliador') {
                include_once 'codigos/candidato_concorrendo.php';
            }

            // 14 MAIO 2024 
            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_altera_email_admin.php';
            }

            if ($_SESSION['perfil'] == 'admin' && isset($_SESSION["eipot"]) == 1) {
                include_once 'codigos/candidato_eipot.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consultor' || $_SESSION['perfil'] == 'ch' || $_SESSION['perfil'] == 'cr' ) {
                include_once 'codigos/candidato_heteroidentificacao.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'jise') {
                include_once 'codigos/candidato_recurso.php';
            }
            
            //Excluido o perfil jise da avaliação de recurso do candidato ||  $_SESSION['perfil'] == 'jise' Em 28 de MAio de 2025
            if (isset($_SESSION['eipot']) == 1 && $_SESSION['perfil'] == 'avaliador' || $_SESSION['perfil'] == 'admin') {
                include_once 'codigos/avaliador_recurso_candidato.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'saude' || $_SESSION['perfil'] == 'jise') {
                include_once 'codigos/candidato_inspecao_saude.php';
            }

            if (($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'con') && $medico_obrigatorio == 1) {
                include_once 'codigos/oficio_medico_obrigatorio.php';
            }

            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_impedimento_judicial.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos') {
                include_once 'codigos/candidato_docs_obrigatorios_avaliador.php';
            } elseif ($_SESSION['perfil'] != 'jise') {
                include_once 'codigos/arquivos_obrigatorios_usuario.php';
            }

            if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
                include_once 'codigos/candidato_observacoes.php';
            }

            if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
                include_once 'codigos/arquivos_adicionados_para_candidato.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta') {
                include_once 'codigos/logs_usuario.php';
            }
        }


        $resultado_selecao = $conexao->get_selecao_id();
        if (
            $resultado_selecao[0]['codigo'] == 'ott_stt'
            || $resultado_selecao[0]['codigo'] == 'mfdv'
            || $resultado_selecao[0]['codigo'] == 'cet'
            || $resultado_selecao[0]['codigo'] == 'ott'
            || $resultado_selecao[0]['codigo'] == 'stt'
        ) {

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos' || $_SESSION['perfil'] == 'avaliador') {
                include_once 'codigos/candidato_concorrendo.php';
            }

            // 14 MAIO 2024 
            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_altera_email_admin.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'avaliador') {
                include_once 'codigos/candidato_especialidades_avaliador.php';
            } else {
                include_once 'codigos/candidato_especialidades.php';
            }

            if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '0') {
                include_once 'codigos/candidato_especialidades_visualiza.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'saude') {
                include_once 'codigos/candidato_inspecao_saude.php';
            }

            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_distribuicao.php';
            }

            if (($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'con') && $medico_obrigatorio == 1) {
                include_once 'codigos/oficio_medico_obrigatorio.php';
            }

            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_impedimento_judicial.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'documentos') {
                include_once 'codigos/candidato_docs_obrigatorios_avaliador.php';
            } else {
                include_once 'codigos/arquivos_obrigatorios_usuario.php';
            }

            if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
                include_once 'codigos/candidato_observacoes.php';
            }

            if ($_SESSION['perfil'] == 'admin') {
                include_once 'codigos/candidato_recurso.php';
            }

            if ($_SESSION['perfil'] == 'avaliador' || $_SESSION['perfil'] == 'admin') {
                include_once 'codigos/avaliador_recurso_candidato.php';
            }

            if ($_SESSION['perfil'] != 'candidato' && $_SESSION['candidato'] != '1') {
                include_once 'codigos/arquivos_adicionados_para_candidato.php';
            }

            if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta') {
                include_once 'codigos/logs_usuario.php';
            }
        }

        ?>

    </div><!--/DIV FINAL CANDIDATO INFORMAÇÕES -->