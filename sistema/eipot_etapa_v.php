<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

//$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr') {
    erro("Erro 632457437! Página não encontrada!");
    exit();
}

?>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>
                Heteroidentificação Complementar <i class="fa fa-check-circle"></i>
            </h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>
                    Heteroidentificação Complementar
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                                <th>CPF</th>
                                <th>Nome</th>
                                <th>Etapa</th>
                                <th>Especialidade</th>
                                <th>HC</th>
                                <th>HC - RECURSO</th>
                                <th>Recurso Etapa 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);
                            $recursos = $conexao->get_recursos_eipot($id_selecao, $rm_usuario);

                            foreach ($candidatos as $linha) {
                                $aparece = true;

                                if ($linha['etapa'] < 3 || $linha['rm_inscricao'] != $rm_usuario) {
                                    continue;
                                }

                                if ($aparece == false) continue;

                                if ($linha['id_selecao'] != $_SESSION['selecao']) continue;

                                $fontColor = "";
                                if ($linha['concorrendo'] == 0) {
                                    $fontColor = '#FF8C73';
                                }

                                $saude = "";

                                if ($linha['apto_saude'] == 1) {
                                    $saude = 'APTO';
                                } elseif ($linha['apto_saude'] == 0  && $linha['grupo_saude']) {
                                    $saude = 'INAPTO';
                                } elseif ($linha['apto_saude'] == 2) {
                                    $saude = 'NÃO COMPARECEU';
                                } elseif ($linha['apto_saude'] == NULL) {
                                    $saude = 'PENDENTE';
                                }

                                if ($linha['apto_saude_recurso'] == 1) {
                                    $recurso_saude = 'APTO';
                                } elseif ($linha['apto_saude_recurso'] == 0  && $linha['grupo_saude_recurso']) {
                                    $recurso_saude = 'INAPTO';
                                } elseif ($linha['apto_saude_recurso'] == 2) {
                                    $recurso_saude = 'NÃO COMPARECEU';
                                } elseif ($linha['apto_saude_recurso'] == NULL) {
                                    $recurso_saude = 'NÃO REALIZADA';
                                }

                                $recursoEtapa3 = null; // <- Inicialização correta

                                foreach ($recursos as $recurso) {
                                    if ($recurso['id_candidato'] == $linha['id'] && $recurso['etapa'] >= 3) {
                                        if ($recurso['obs_etapa'] == '3 - IS') {
                                            $recursoEtapa3 = '3 - IS';
                                            break;
                                        } elseif ($recurso['obs_etapa'] == '3 - Documental') {
                                            $recursoEtapa3 = '3 - Documental';
                                            break;
                                        }
                                    } else {
                                        $recursoEtapa3 = 'NÃO';
                                    }
                                }

                                echo '
                                <tr bgcolor = ' . $fontColor . '>
                                <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario'] . '">' . $linha['cpf'] . '</a></td>
                                <td>' . $linha['nome_completo'] . '</td>
                                <td>_' . $linha['etapa'] . '</td>
                                <td>' . $linha['arma_especialidade'] . '</td>
                                <td>' . $saude . '</td>
                                <td>' . $recurso_saude . '</td>
                                <td>' . $recursoEtapa3 . '</td>';
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>