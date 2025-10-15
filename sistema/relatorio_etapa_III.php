<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'jise') {
    erro("Erro 632457437! Página não encontrada!");
    exit();
}
?>

<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 12px 15px;
        font-size: 1.3rem;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 1.4rem;
    }

    .table td a:hover {
        background-color: #006400;
        color: white;
    }

    .table td i {
        font-size: 2rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.03);
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1><?php if ($_SESSION['perfil'] == "jise") echo ('Resultado IS');
                else echo ('Cadastro Dados IS - SIPMED'); ?> <i class="fa fa-address-book"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li><?php if ($_SESSION['perfil'] == "jise") echo ('Resultado IS');
                    else echo ('Cadastro Dados IS - SIPMED') ?></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela de Candidatos EIPOT -->
            <div class="card dashboard-card mb-4">
                <div class="card-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-users me-2"></i>
                            Candidatos <?= $_SESSION['selecao_nome'] ?> - Inspeção de Saúde
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Candidato</th>
                                    <th class="text-center"><i class="fa fa-list"></i> Etapa</th>
                                    <th>Especialidade</th>
                                    <th class="text-center"><i class="fa fa-graduation-cap"></i> IS - JISE</th>
                                    <th class="text-center"><i class="fa fa-stethoscope"></i> ISGRec - JISR</th>
                                    <th class="text-center"><i class="fa fa-file-text"></i> Recurso Etapa 3</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_especialidades = $conexao->get_especialidade();

                                foreach ($lista_especialidades as $especialidade) :
                                    $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
                                    if (count($candidatos) === 0) {
                                        continue;
                                    }


                                    $recursos = $conexao->get_recursos();

                                    foreach ($candidatos as $linha):
                                        // Filtros
                                        if ($linha['etapa'] < 3 || $linha['id_selecao'] != $_SESSION['selecao']) {
                                            continue;
                                        }

                                        // Status do candidato
                                        $is_desclassificado = $linha['concorrendo'] == 0;

                                        // Status da saúde - IS JISE
                                        $saude_class = 'secondary';
                                        $saude_text = 'PENDENTE';

                                        if ($linha['apto_saude'] == 1) {
                                            $saude_class = 'success';
                                            $saude_text = 'APTO';
                                        } elseif ($linha['apto_saude'] == 0 && $linha['grupo_saude']) {
                                            $saude_class = 'danger';
                                            $saude_text = 'INAPTO';
                                        } elseif ($linha['apto_saude'] == 2) {
                                            $saude_class = 'warning';
                                            $saude_text = 'NÃO COMPARECEU';
                                        }

                                        // Status da saúde - ISGRec JISR
                                        $recurso_saude_class = 'secondary';
                                        $recurso_saude_text = 'NÃO REALIZADA';

                                        if ($linha['apto_saude_recurso'] == 1) {
                                            $recurso_saude_class = 'success';
                                            $recurso_saude_text = 'APTO';
                                        } elseif ($linha['apto_saude_recurso'] == 0 && $linha['grupo_saude_recurso']) {
                                            $recurso_saude_class = 'danger';
                                            $recurso_saude_text = 'INAPTO';
                                        } elseif ($linha['apto_saude_recurso'] == 2) {
                                            $recurso_saude_class = 'warning';
                                            $recurso_saude_text = 'NÃO COMPARECEU';
                                        }

                                        // Recurso Etapa 3
                                        $recursoEtapa3 = 'NÃO';
                                        $recurso_class = 'secondary';

                                        foreach ($recursos as $recurso) {
                                            if ($recurso['id_especialidade'] == $especialidade['id'] && $recurso['id_candidato'] == $linha['id'] && $recurso['etapa'] >= 3) {
                                                if ($recurso['obs_etapa'] == '3 - IS') {
                                                    $recursoEtapa3 = '3 - IS';
                                                    $recurso_class = 'info';
                                                    break;
                                                } elseif ($recurso['obs_etapa'] == '3 - Documental') {
                                                    $recursoEtapa3 = '3 - Documental';
                                                    $recurso_class = 'info';
                                                    break;
                                                }
                                            }
                                        }
                                ?>

                                        <tr>
                                            <!-- CPF -->
                                            <td>
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                    class="text-decoration-none">
                                                    <?= $linha['cpf'] ?>
                                                </a>
                                            </td>

                                            <!-- Nome -->
                                            <td>
                                                <div class="candidate-info">
                                                    <div class="fw-semibold candidate-name"><?= htmlspecialchars($linha['nome_completo']) ?></div>
                                                </div>
                                            </td>

                                            <!-- Etapa -->
                                            <td class="text-center">
                                                <span class="etapa-badge etapa-<?= $linha['etapa'] ?>">
                                                    _<?= $linha['etapa'] ?>
                                                </span>
                                            </td>

                                            <!-- Especialidade -->
                                            <td>
                                                <span class="especialidade-text"><?= htmlspecialchars($especialidade['nome']) ?></span>
                                            </td>

                                            <!-- IS - JISE -->
                                            <td class="text-center">
                                                <?= $saude_text ?>
                                            </td>

                                            <!-- ISGRec - JISR -->
                                            <td class="text-center">
                                                <?= $recurso_saude_text ?>
                                            </td>

                                            <!-- Recurso Etapa 3 -->
                                            <td class="text-center">
                                                <span class="badge badge bg-primary">
                                                    <?= $recursoEtapa3 ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary view-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Visualizar candidato">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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