<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$pareceres = $conexao->get_pareceres_heteroidentificacao($id_usuario);

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
                                <th>RM Etapa Presencial</th>
                                <th>Especialidade</th>
                                <th>HC</th>
                                <th>HC - RECURSO</th>
                                <th>Recurso HC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $candidatos = $conexao->get_candidatos_eipot();
                            $recursos = $conexao->get_recursos_eipot($id_selecao, $rm_usuario);

                            foreach ($candidatos as $linha) {
                                if($linha['vaga_reservada'] != 1) {
                                    continue;
                                }
                                $pareceres = $conexao->get_pareceres_heteroidentificacao($linha['id']);

                                $parecerHc = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 1);
                                $parecerRevisora = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 2);

                                $pareceresFase1 = array_filter($pareceres, function ($parecer) {
                                    return $parecer['fase'] == 1;
                                });

                                $pareceresFase2 = array_filter($pareceres, function ($parecer) {
                                    return $parecer['fase'] == 2;
                                });

                                if (count($pareceresFase1) < 5) {
                                    $parecerHc = 'PENDENTE';
                                }

                                if (count($pareceresFase2) < 3) {
                                    $parecerRevisora = 'PENDENTE';
                                }

                                $aparece = true;

                                if ($linha['etapa'] < 4) {
                                    continue;
                                }

                                if ($aparece == false) continue;

                                if ($linha['id_selecao'] != $_SESSION['selecao']) continue;

                                $hc = '';


                                $recursoEtapa5 = null; // <- Inicialização correta

                                foreach ($recursos as $recurso) {
                                    if ($recurso['id_candidato'] == $linha['id'] && $recurso['etapa'] == 5) {
                                        $recursoEtapa5 = 'SIM';
                                        break;
                                    } else {
                                        $recursoEtapa5 = 'NÃO';
                                    }
                                }

                                echo '
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '">' . $linha['cpf'] . '</a></td>
                                <td>' . $linha['nome_completo'] . '</td>
                                <td>_' . $linha['etapa'] . '</td>
                                 <td>_' . $linha['rm_inscricao'] . 'ª RM</td>
                                <td>' . $linha['arma_especialidade'] . '</td>
                                <td>' . $parecerHc . '</td>
                                <td>' . $parecerRevisora . '</td>
                                <td>' . $recursoEtapa5 . '</td>';
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card" <?php if ($_SESSION['perfil'] == 'jise') echo ('hidden') ?>>
                <legend>Gerar Ata Heteroidentificação Complementar
                    <img src="imagens/pdf.png" width="30px">
                </legend>

                <form name="form_ata_heteroidentificacao_eipot" action="mpdf/relatorio_heteroidentificacao.php" method="post">
                    <div style="float: left; width: 100%; margin-right: 4%;">
                        <div class="form-group">
                            <label for="fase">Fase:</label>
                            <select name="fase" class="form-control" required>
                                <option value="1">Heteroidentificação Complementar</option>
                                <option value="2">Heteroidentificação Revisora</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="titulo">Data da Inspeção:</label>
                            <input type="datetime" name="data_inspecao" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-mg-12">
                        <button type="submit" class="btn btn-primary btn-block">GERAR ATA</button>
                    </div>
                </form>
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
</body>

</html>
<?php $conexao = null; ?>