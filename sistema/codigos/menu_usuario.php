<?php
if (!isset($_SESSION))
    session_start();

$resultado_selecao = $conexao->get_selecao_id();
$rm_usuario = $conexao->rm_usuario($_SESSION['id_usuario']);
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

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235332446!");
    exit();
}

?>


<li <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden" ?><?php
                                                                            if (!isset($_SESSION))
                                                                                session_start();

                                                                            $resultado_selecao = $conexao->get_selecao_id();
                                                                            $rm_usuario = $conexao->rm_usuario($_SESSION['id_usuario']);
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

                                                                            if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
                                                                                erro("Erro 235332446!");
                                                                                exit();
                                                                            }
                                                                            ?>

    <li <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden" ?> class="treeview">
    <a href="#"><i class="fa fa-cogs"></i><span>Cadastros</span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <li <?php if ($_SESSION['selecao_codigo'] != 'cet') echo "hidden" ?> class="treeview"><a href="admin_candidato_cadastro.php"><i class="fa fa-user-plus"></i>Candidatos</a></li>
        <li <?php if ($_SESSION['selecao_codigo'] != 'mfdv') echo "hidden" ?> class="treeview"><a href="medico_obrigatorio_cadastro.php"><i class="fa fa-user-md"></i> Médico Obrigatório</a></li>
        <li <?php if (isset($_SESSION['eipot']) && $rm_usuario != 3 && $perfil != "admin") echo "hidden" ?> class="treeview"><a href="documentacao_obrigatoria_visualiza.php"><i class="fa fa-files-o"></i> Documentos Obrigatórios</a></li>
        <li <?php if (!isset($_SESSION['eipot']) || $rm_usuario != 3 || $perfil != "admin") echo "hidden" ?> class="treeview"><a href="especialidade_visualiza_eipot.php"><i class="fa fa-shield"></i> Armas</a></li>
        <li <?php if (isset($_SESSION['eipot'])) echo "hidden" ?> class="treeview"><a href="curriculo_visualiza.php"><i class="fa fa-file-text-o"></i>Currículo</a></li>
        <li <?php if (isset($_SESSION['eipot'])) echo " hidden " ?> class="treeview"><a href="especialidade_visualiza.php"><i class="fa fa-wrench"></i> Especialidade</a></li>
        <li class="treeview"><a href="usuario_cadastro.php"><i class="fa fa-user-plus"></i>Usuários</a></li>
    </ul>
</li>

<li <?php if ($perfil == "ouvidor" || $perfil == "avaliador" || $perfil == "documentos" || $perfil == "om") echo "hidden"; ?> class="treeview">
    <a href="#"><i class="fa fa-search"></i><span><?php if ($_SESSION['perfil'] == "jise") echo ('Resultado IS');
                                                    else echo ('Pesquisa'); ?></span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista_eipot.php"><i class="fa fa-users"></i><span>EIPOT - Ampla Concorrência</span></a></li>

        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista_eipot_vagas_reservadas.php"><i class="fa fa-circle"></i><span>EIPOT - Cotas para Negros</span></a></li>

        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista_eipot_docs_obrigatorios.php"><i class="fa fa-map-marker"></i><span>EIPOT - Etapas Presenciais</span></a></li>

        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="pesquisa_recursos.php"><i class="fa fa-file-text"></i><span>Recursos</span></a></li>

        <li <?php if (isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista.php"><i class="fa fa-users"></i><span>Candidatos participando</span></a></li>

        <li <?php if (isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista_desclassificados.php"><i class="fa fa-user-times"></i><span>Candidatos desclassificados</span></a></li>

        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="candidato_lista_desclassificados_eipot.php"><i class="fa fa-user-times"></i><span>Candidatos desclassificados </span></a></li>

        <li <?php if ($_SESSION['selecao_codigo'] != 'mfdv') echo "hidden" ?> class="treeview"><a href="medicos_obrigatorios.php"><i class="fa fa-user-md"></i><span>Médicos Obrigatórios</span></a></li>

        <li <?php if (!isset($_SESSION['eipot']) || $perfil == "chc" || $perfil == "cr") echo "hidden" ?> class="treeview"><a href="eipot_etapa_III.php"><i class="fa fa-address-book"></i><?php if ($_SESSION['perfil'] == "jise") echo ('JISE');
                                                                                                                                                                                            else echo ('Dados Cadastro IS - SIPMED') ?></a>

        <li <?php if (isset($_SESSION['eipot']) || $perfil == "chc" || $perfil == "cr") echo "hidden" ?> class="treeview"><a href="relatorio_etapa_III.php"><i class="fa fa-address-book"></i><?php if ($_SESSION['perfil'] == "jise") echo ('JISE');
                                                                                                                                                                                                else echo ('Dados Cadastro IS - SIPMED') ?></a>
        <li <?php if (!isset($_SESSION['eipot']) || $perfil != 'admin' && $perfil != 'consulta' && $perfil != 'chc' && $perfil != 'cr') echo "hidden" ?> class="treeview"><a href="eipot_etapa_v.php"><i class="fa fa-check-circle"></i><?php if ($_SESSION['perfil'] == "chc" || $_SESSION['perfil'] == "cr") echo ('Heteroidentificação');
                                                                                                                                                                                                                                        else echo ('Heteroidentificação') ?></a>
        <li <?php if (!isset($_SESSION['eipot']) || $perfil != "admin") echo " hidden " ?> class="treeview"><a href="candidato_lista_escolha_guarnicao_eipot.php"><i class="fa fa-map-pin"></i><span>Escolha de Guarnição </span></a></li>

        <li <?php if ($perfil == "jise" || $perfil == "chc" || $perfil == "cr") echo " hidden " ?> class="treeview"><a href="usuario_lista.php"><i class="fa fa-user"></i>Usuários</a></li>
    </ul>
</li>

<!-- Esconde as Publicações do EIPOT caso a seleção não seja EIPOT -->
<li <?php if ($perfil != "admin" || !isset($_SESSION['eipot'])) echo "hidden" ?> class="treeview">
    <a href="#"><i class="fa fa-pencil"></i><span>Publicações</span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <li class="treeview"><a href="publicacoes_eipot.php"><i class="fa fa-pencil"></i>Etapa I e II</a></li>
        <li class="treeview"><a href="publicacoes_eipot_etapa_III.php"><i class="fa fa-pencil"></i>Etapa III</a></li>
        <li class="treeview"><a href="publicacoes_eipot_etapa_iv.php"><i class="fa fa-pencil"></i>Etapa IV</a></li>
        <li class="treeview"><a href="publicacoes_eipot_etapa_v.php"><i class="fa fa-pencil"></i>Etapa V</a></li>
        <li class="treeview"><a href="publicacoes_eipot_rankings.php"><i class="fa fa-pencil"></i>Etapa VI</a></li>
        <li class="treeview"><a href="publicacoes_recursos.php"><i class="fa fa-pencil"></i>Recursos</a></li>
    </ul>
</li>

<li <?php if ($perfil == "ouvidor" || $perfil == "om" || $perfil == "jise" || isset($_SESSION['eipot'])) echo " hidden " ?>><a href="relatorios_lista.php"><i class="fa fa-file-text-o"></i><span>Relatórios</span></a></li>

<!-- <li <?php if ($perfil != "avaliador" && $perfil != "admin") echo "hidden" ?>><a href="candidato_lista_avaliador.php"><i class="fa fa-check-square-o"></i><span>Avaliação</span></a></li>                         
-->
<?php if ($resultado_selecao[0]['rm'] == 3) : ?>
    <li <?php if ($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="assistente_virtual.php"><i class="fa bi bi-robot"></i><span>Assistente Virtual</span></a></li>
<?php endif; ?>
<!-- Adicionando o item de Aviso aos Candidatos no Menu -->
<li <?php if ($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="cadastra_notificacoes.php"><i class="fa fa-bullhorn"></i><span>Notificações</span></a></li>

<!-- <li <?php if ($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="suporte_inicial_lista.php"><i class="fa fa-comments"></i><span>Suporte</span></a></li>                         -->

<li <?php if ($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="suporte_lista.php"><i class="fa fa-comments"></i><span>Fale conosco</span></a></li>

<li <?php if ($perfil != "admin" && $perfil != "consulta") echo "hidden" ?>><a href="auditoria.php"><i class="fa fa-eye"></i><span>Auditoria</span></a></li>

<li <?php if ($perfil != "admin") echo " hidden " ?> class="treeview">
    <a href="#">
        <i class="fa fa-cogs"></i> <span>Configurações</span> <i class="fa fa-angle-right"></i>
    </a>

    <ul class="treeview-menu">
        <li><a href="configuracoes_recursos.php"><i class="fa fa-file-text"></i><span>Recursos</span></a></li>

        <li <?php if ($rm_usuario != 3 || $perfil != "admin" && $perfil != "consulta") echo "hidden" ?>><a href="etapa_passagem.php"><i class="fa fa-circle-o-notch"></i><span>Passagem de Etapa</span></a></li>

        <li <?php if ($perfil != "admin" && $perfil != "consulta" || $rm_usuario != 3) echo "hidden" ?>><a href="configuracao_selecao.php"><i class="fa fa-cogs"></i><span>Configuração da Seleção</span></a></li>
    </ul>
</li>


<!-- Esconde os Tutoriais do EIPOT caso a seleção não seja EIPOT -->
<li <?php if ($perfil != "admin" && $perfil != "consulta" || !isset($_SESSION['eipot'])) echo "hidden" ?> class="treeview">
    <a href="#"> <i class="fa fa-book"></i> <span>Tutoriais</span> <i class="fa fa-angle-right"></i> </> </a>
    <ul class="treeview-menu">
        <li><a href="tutoriais.php"><i class="fa fa-book"></i><span>Etapa I</span></a></li>
        <li><a href="tutoriais_etapa_ii.php"><i class="fa fa-book" hidden></i><span>Etapa II</span></a></li>
        <li><a href="tutoriais_etapa_recursos.php"><i class="fa fa-book" hidden></i><span>Recursos</span></a></li>
        <li><a href="tutoriais_etapa_iii.php"><i class="fa fa-book" hidden></i><span>Etapa III</span></a></li>
    </ul>
</li>

<a href="../ajuda.php" target="_blank" class="botao-ajuda" title="Precisa de Ajuda?">
    <i class="fa fa-question"></i>
</a>