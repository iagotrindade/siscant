<?php

include_once 'funcao_mostrar_recurso.php';

if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'candidato' || $_SESSION['candidato'] != 1) {
    erro("Erro 2423532!");
    exit();
}

?>

<li class="treeview">
    <!-- 22/06/2025 -> Iago Silva Alterado o nome do menu -->
    <a href="#"><i class="fa fa-user"></i><span>Minhas informações</span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="usuario_visualiza.php"><i class="fa fa-id-card-o"></i> Meu Cadastro</a></li>
        <li <?php if ($_SESSION['medico_obrigatorio'] == '1' || isset($_SESSION['eipot'])) echo " hidden " ?>><a href="candidato_editar.php"><i class="fa fa-pencil"></i> Editar Cadastro</a></li>
        <li <?php if (!isset($_SESSION['eipot'])) echo " hidden " ?>><a href="candidato_editar_eipot.php"><i class="fa fa-pencil"></i>Editar Cadastro</a></li>
        <li <?php if ($_SESSION['medico_obrigatorio'] == '') echo "hidden" ?>><a href="edita_medico_obrigatorio.php?id_usuario=<?php echo $_SESSION['id_usuario'] ?>"><i class="fa fa-pencil"></i> Editar Cadastro</a></li>
        <li><a href="candidato_altera_senha.php"><i class="fa fa-lock"></i> Alterar a minha senha</a></li>
    </ul>
</li>

<li <?php if (!$pagamento_selecao) echo " hidden " ?> class="treeview"><a href="candidato_pagamento_inscricao.php"><i class="fa fa-usd"></i><span>Pagamento Inscrição </span></a></li>

<!-- 22/06/2025 -> Iago Silva Alterado o ícone -->
<li <?php if (isset($_SESSION['eipot'])) echo " hidden " ?> class="treeview"><a href="candidato_especialidade_visualiza.php"><i class="fa fa-graduation-cap"></i><span>Inscrição de Especialidade</span></a></li>

<li class="treeview"><a href="documentos_obrigatorios_visualiza.php"><i class="fa fa-upload"></i><span>Documentos de Inscrição</span></a></li>

<?php
/*
    if($_SESSION['selecao_codigo'] == 'ott_stt')
    {
        echo'<li><a href="duvidas_frequentes.php"><i class="fa fa-info-circle"></i><span>Dúvidas Frequentes</span></a></li>';
    }
    if($_SESSION['selecao_codigo'] == 'cet')
    {
        echo'<li><a href="duvidas_frequentes.php"><i class="fa fa-info-circle"></i><span>Dúvidas Frequentes</span></a></li>';
    }
    if($_SESSION['selecao_codigo'] == 'mfdv')
    {
        echo'<li><a href="duvidas_frequentes_mfdv.php"><i class="fa fa-info-circle"></i><span>Dúvidas Frequentes</span></a></li>';
    }
 */
?>

<li><a href="candidato_recurso.php"><i class="fa fa-file-text"></i><span>Recursos</span></a></li>
<!-- 22/06/2025 -> Iago Silva Alterado o ícone -->
<li <?php if ($_SESSION['selecao_regiao'] == 7) echo "hidden" ?>><a href="suporte.php"><i class="fa fa-comments"></i><span>Fale conosco</span></a></li>