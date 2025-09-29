<?php
require 'menu.php';
?>

<style>
    .card-header {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .badge {
        background-color: var(--primary-color);
        font-size: 0.85em;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .badge.bg-danger {
        background-color: #dc3545;
    }

    .badge.bg-primary {
        background-color: var(--primary-color);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .fs-4 {
        font-size: 2.5rem !important;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mr-10 {
        margin-right: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    .text-center {
        text-align: center;
    }

    .pull-right {
        float: right;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-header {
            font-size: 18px;
            padding: 12px 15px;
        }

        .fs-4 {
            font-size: 1.50rem !important;
        }

        .mr-10 {
            margin-right: 8px;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            font-size: 16px;
            padding: 10px 12px;
        }

        .fs-4 {
            font-size: 1.3rem !important;
        }

        .mr-10 {
            margin-right: 0;
            margin-bottom: 5px;
        }
    }

    .fw-bold {
        font-weight: 600;
    }

    /* Estilos para os cards de estatísticas */
    .card-checkbox {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .documento-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .estatisticas-info {
        width: 100%;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .text-success {
        color: #198754 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-info {
        color: #0dcaf0 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    /* Cores específicas para os ícones */
    .text-primary {
        color: #006400 !important;
    }

    .text-info {
        color: #0dcaf0 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-success {
        color: #198754 !important;
    }

    /* Espaçamentos */
    .mb-20 {
        margin-bottom: 20px;
    }

    .mr-10 {
        margin-right: 10px;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }

    /* Layout flex para alinhamento */
    .d-flex {
        display: flex !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-checkbox {
            margin-bottom: 15px;
            padding: 12px;
        }

        .documento-info {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

        .fa-2x {
            font-size: 1.5em !important;
        }
    }

    @media (max-width: 576px) {
        .card-checkbox {
            padding: 10px;
        }

        .d-flex {
            flex-direction: column;
            gap: 5px;
        }

        .d-flex.justify-content-between {
            text-align: center;
        }
    }
</style>

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
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-flag"></i> <?php echo $nome_selecao ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mt-3">
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
                                    echo "<br><span class='badge bg-danger'><i class='fa fa-times'></i> Inscrições fechadas!</span>";
                                } else if (dias_restantes_inscricao() > 0) {
                                    $dias_restante = "Restam " . dias_restantes_inscricao() . " dias";
                                    if (dias_restantes_inscricao() == 1)
                                        $dias_restante = " HOJE";
                                    echo "<br>Último dia da inscrição: " . trata_data($_SESSION['selecao_data_final_inscricao']) . " - <b>" . $dias_restante . " </b>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                            <div class="fw-bold">
                                ETAPA ATUAL <br>
                                <span class="fs-4 text-primary">
                                    <?php echo $etapa_atual_selecao; ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-2 text-center" style="display: flex; align-items: center; justify-content: flex-end;">
                            <?php
                            if (isset($_SESSION['eipot']) == 1) {
                                echo '<img src="imagens/EIPOT.jpg" width="100" height="80" class="img-fluid"">';
                            } else {
                                echo '<img src="imagens/' . $rm_usuario . 'rm.png" class="img-fluid" width="100" height="80">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 18/06/2025 - Iago Silva Inlcuido perfil chc e cr na verificação-->
            <div class="row <?php if ($perfil == 'jise' || $perfil == 'chc' || $perfil == 'cr') echo ('hidden') ?>">
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