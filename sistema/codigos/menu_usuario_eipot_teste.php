<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 235332446!");
        exit();
    }
     
?>


<li <?php if($perfil != "admin" && $perfil != "consulta") echo "hidden" ?> class="treeview">
    <a href="#"><i class="fa fa-cogs"></i><span>Cadastros</span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <!-- <li class="treeview"><a href="curriculo_cadastro.php"><i class="fa fa-files-o"></i> Arquivos Currículo</a></li> -->
        <li <?php if($_SESSION['selecao_codigo'] != 'mfdv') echo "hidden" ?> class="treeview"><a href="medico_obrigatorio_cadastro.php"><i class="fa fa-user-md"></i> Médico Obrigatório</a></li>
        <li class="treeview"><a href="documentacao_obrigatoria_visualiza.php"><i class="fa fa-files-o"></i> Documentos Obrigatórios</a></li>
        <li <?php if(isset($_SESSION['eipot'])) echo "  " ?>  class="treeview"><a href="curriculo_visualiza.php"><i class="fa fa-file-text-o"></i>Currículo</a></li>
        <li <?php if(isset($_SESSION['eipot'])) echo " hidden " ?> class="treeview"><a href="especialidade_visualiza.php"><i class="fa fa-wrench"></i> Especialidade</a></li>
        <li <?php if (isset($_SESSION['eipot'])): ?>
         <li class="treeview">
        <a href="especialidade_visualiza.php">
            <i class="fa fa-shield"></i> Armas
                </a>
            </li>
        <?php endif; ?>
        <li class="treeview"><a href="usuario_cadastro.php"><i class="fa fa-user-plus"></i> Usuário</a></li>
    </ul>
</li>

<li <?php if($perfil == "ouvidor" || $perfil == "avaliador" || $perfil == "documentos" || $perfil == "om") echo "hidden" ?> class="treeview">
    <a href="#"><i class="fa fa-search"></i><span>Pesquisa</span><i class="fa fa-angle-right"></i></a>
    <ul class="treeview-menu">
        <li class="treeview"><a href="candidato_lista.php"><i class="fa fa-users"></i><span>Candidatos participando</span></a></li>
        <li <?php if($_SESSION['selecao_codigo'] != 'eipot') echo "hidden" ?> class="treeview"><a href="candidato_lista_eipot.php"><i class="fa fa-users"></i><span>EIPOT</span></a></li>
        <li class="treeview"><a href="candidato_lista_desclassificados.php"><i class="fa fa-user-times"></i><span>Candidatos desclassificados</span></a></li>
        <li <?php if($_SESSION['selecao_codigo'] != 'mfdv') echo "hidden" ?> class="treeview"><a href="medicos_obrigatorios.php"><i class="fa fa-user-md"></i><span>Médicos Obrigatórios</span></a></li>
        <li class="treeview"><a href="usuario_lista.php"><i class="fa fa-user"></i>Usuário</a></li>
    </ul>
</li>


<li <?php if($perfil == "ouvidor" || $perfil == "om") echo " hidden " ?>><a href="relatorios_lista.php"><i class="fa fa-file-text-o"></i><span>Relatórios</span></a></li>                         


<!-- <li <?php if($perfil != "avaliador" && $perfil != "admin") echo "hidden" ?>><a href="candidato_lista_avaliador.php"><i class="fa fa-check-square-o"></i><span>Avaliação</span></a></li>                         
-->

<!-- <li <?php if($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="suporte_inicial_lista.php"><i class="fa fa-support"></i><span>Suporte</span></a></li>                         -->

<li <?php if($perfil != "admin" && $perfil != "ouvidor" && $perfil != "consulta") echo "hidden" ?>><a href="suporte_lista.php"><i class="fa fa-support"></i><span>Fale conosco</span></a></li>                         
<li <?php if($perfil != "admin" && $perfil != "consulta") echo "hidden" ?>><a href="configuracao_selecao.php"><i class="fa fa-cogs"></i><span>Configuração da Seleção</span></a></li>                         
<li <?php if(isset($_SESSION['eipot']) || $perfil != "admin" && $perfil != "consulta") echo "hidden" ?>><a href="etapa_passagem.php"><i class="fa fa-circle-o-notch"></i><span>Passagem de Etapa</span></a></li>                         
<li <?php if($perfil != "admin" && $perfil != "consulta") echo "hidden" ?>><a href="auditoria.php"><i class="fa fa-eye"></i><span>Auditoria</span></a></li>                         

