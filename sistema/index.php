<?php
require 'menu.php';
?>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Sistema de Seleção de Candidatos Temporários <i class="fa fa-home"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="#">Página Inicial</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-8">

                        <legend> <?php echo $nome_selecao ?></legend>
                        <div class="card-body">
                            <?php

                            $saudacao = "Boa noite";
                            $hr = date(" H ");
                            if ($hr >= 6 && $hr < 12)
                                $saudacao = "Bom dia";
                            if ($hr >= 12 && $hr < 19)
                                $saudacao = "Boa tarde";

                            if ($perfil == 'candidato')
                                echo $saudacao . " $perfil " . $_SESSION['nome_completo'] . "!";

                            if ($perfil != 'candidato')
                                echo $saudacao . " " . $posto_grad . " " . strtoupper($nome_guerra) . " - Perfil: " . strtoupper($perfil);
                            ?>

                            <?php

                            if (!inscricao()) {
                                echo "<br><font color='red'>Inscrições fechadas!</font>";
                            } else if (dias_restantes_inscricao() > 0) {
                                $dias_restante = "Restam " . dias_restantes_inscricao() . " dias";
                                if (dias_restantes_inscricao() == 1)
                                    $dias_restante = " HOJE";
                                echo "<br>Último dia da inscrição: " . trata_data($_SESSION['selecao_data_final_inscricao']) . " -  <b>" . $dias_restante . " </b>";
                            }

                            ?>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <center>
                            <b>
                                ETAPA ATUAL <br>
                                <font size='14px'>
                                    <?php echo $etapa_atual_selecao; ?>
                                </font>
                            </b>
                        </center>
                    </div>
                    <div class="col-md-2">
                        <small class="pull-right">
                            <img
                                <?php
                                if (isset($_SESSION['eipot']) == 1)  echo 'src="imagens/EIPOT.jpg" width="150" height="100" style="box-shadow: 0px 0px 10px #197249"';
                                else echo 'src="imagens/' . $rm_usuario . 'rm.png" style="box-shadow: 0px 0px 10px #197249"';
                                ?>
                                width="80px">
                        </small>
                    </div>
                </div>
                <br>
            </div>

            <div class="row <?php if ($perfil == 'jise') echo ('hidden') ?>">
                <div class="col-md-12">
                    <?php
                    if (isset($_SESSION['eipot']) && $perfil == 'admin' || $perfil == 'consulta')
                        include_once 'codigos/index_usuario_eipot.php';
                    else if ($perfil == 'candidato' || $candidato == 1)
                        include_once 'codigos/index_candidato.php';
                    else if ($perfil == 'om')
                        include_once 'codigos/index_om.php';
                    else
                        include_once 'codigos/index_usuario.php';
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</body>

</html>
<?php $conexao = null; ?>