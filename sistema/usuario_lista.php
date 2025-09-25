<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';
if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Página não encontrada!");
    exit();
}

$lista_usuarios = $conexao->get_usuarios();
$lista_usuarios_perfil_om = $conexao->get_usuarios_perfil_om();

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

    .table td i {
        font-size: 2rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.03);
    }

    /* Badges e elementos visuais */
    .badge {
        font-size: 0.9em;
        padding: 6px 10px;
        border-radius: 6px;
        font-weight: 500;
        background-color: var(--primary-color);
    }

    .om-badge {
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9em;
        font-weight: 600;
    }

    .selecao-badge {
        background: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Elementos de texto */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .especialidades-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 250px;
        line-height: 1.4;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        .table-header-custom th,
        .table-header-custom-om th {
            padding: 12px 8px;
            font-size: 0.8rem;
        }

        .table-row-custom td {
            padding: 10px 8px;
        }

        .especialidades-text {
            max-width: 150px;
            -webkit-line-clamp: 1;
        }

        .text-truncate {
            max-width: 120px;
        }
    }

    /* Estados da tabela */
    .table-modern tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .rounded-circle {
        object-fit: cover;
    }

    .fw-semibold {
        font-weight: 600;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Usuários <i class="fa fa-user"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Usuários</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Primeira Tabela - Usuários Gerais -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-users me-2"></i> Usuários do Sistema</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica">
                            <thead class="table-header-custom">
                                <tr>
                                    <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                    <th><i class="fa fa-user-tie me-1"></i> Nome Guerra</th>
                                    <th><i class="fa fa-building me-1"></i> Organização Militar</th>
                                    <th><i class="fa fa-envelope me-1"></i> E-Mail</th>
                                    <th><i class="fa fa-phone me-1"></i> Telefone</th>
                                    <th><i class="fa fa-tag me-1"></i> Perfil</th>
                                    <th><i class="fa fa-list-alt me-1"></i> Especialidades</th>
                                    <th class="text-center"><i class="fa fa-cogs me-1"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($lista_usuarios as $linha) {
                                    if ($linha['perfil'] == 'om') continue;

                                    $foto = "user.jpg";
                                    $lista_especialidades_avaliador = "";

                                    if ($linha['perfil'] == 'avaliador') {
                                        $lista_especialidades = $conexao->get_especialidades_usuario_avaliador($linha['id']);
                                        foreach ($lista_especialidades as $linha_especialidade) {
                                            $lista_especialidades_avaliador = $lista_especialidades_avaliador . $linha_especialidade['nome'] . " | ";
                                        }
                                    }

                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    // Determinar cor do perfil
                                    $perfil_class = 'badge bg-secondary';
                                    if ($linha['perfil'] == 'admin') $perfil_class = 'badge bg-danger';
                                    if ($linha['perfil'] == 'avaliador') $perfil_class = 'badge bg-warning text-dark';
                                    if ($linha['perfil'] == 'consulta') $perfil_class = 'badge bg-info';

                                    echo '
                        <tr class="table-row-custom">
                            <td>
                                <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="text-decoration-none">
                                    <span class="fw-bold text-primary">' . $linha['cpf'] . '</span>
                                </a>
                            </td>
                            <td>
                                <div class="" style="display: flex; align-items: center;">
                                    <img class="img-circle rounded-circle mr-10" src="fotos/' . $foto . '" width="40" height="40" alt="Foto">
                                    <div>
                                        <div class="fw-semibold">' . mb_strtoupper($linha['posto_grad'], 'UTF-8') . ' ' . mb_strtoupper($linha['nome_guerra'], 'UTF-8') . '</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="om-badge">' . $linha['nome_om'] . '</span></td>
                            <td>
                                <div class="text-truncate d-inline-block" style="max-width: 180px;" title="' . $linha['mail'] . '">
                                    ' . $linha['mail'] . '
                                </div   >
                            </td>
                            <td>' . $linha['tel_celular'] . '</td>
                            <td><span class="' . $perfil_class . '">' . $linha['perfil'] . '</span></td>
                            <td>
                                <small class="especialidades-text" title="' . $lista_especialidades_avaliador . '">
                                    ' . $lista_especialidades_avaliador . '
                                </small>
                            </td>
                            <td class="text-center" style="white-space: nowrap;">
                                <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar usuário">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="usuario_altera_senha.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Resetar senha">
                                    <i class="fa fa-key"></i>
                                </a>

                                <a href="usuario_editar.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Editar usuário">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'usuario\')" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Excluir usuário">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Segunda Tabela - Usuários de OM -->
            <div class="card mb-4" <?php if ($_SESSION['selecao_codigo'] == "cet") echo "hidden"; ?>>
                <div class="card-header bg-success text-white mb-20">
                    <span class="mb-0"><i class="fa fa-building me-2"></i> Usuários de Organização Militar</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica2">
                            <thead class="table-header-custom-om">
                                <tr>
                                    <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                    <th><i class="fa fa-user-tie me-1"></i> Nome Guerra</th>
                                    <th><i class="fa fa-building me-1"></i> Organização Militar</th>
                                    <th><i class="fa fa-tasks me-1"></i> Seleção</th>
                                    <th><i class="fa fa-phone me-1"></i> Telefone</th>
                                    <th class="text-center"><i class="fa fa-eye me-1"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($lista_usuarios_perfil_om as $linha) {
                                    if ($linha['perfil'] != 'om') continue;

                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    echo '
                        <tr class="table-row-custom">
                            <td>
                                <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="text-decoration-none">
                                    <span class="fw-bold text-primary">' . $linha['cpf'] . '</span>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <img class="img-circle rounded-circle me-2" src="fotos/' . $foto . '" width="32" height="32" alt="Foto">
                                    <div>
                                        <div class="fw-semibold">' . mb_strtoupper($linha['posto_grad'], 'UTF-8') . ' ' . mb_strtoupper($linha['nome_guerra'], 'UTF-8') . '</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-block text-muted">' . $linha['nome_om'] . ' (' . $linha['abreviatura_om'] . ')</div>
                            </td>
                            <td>
                                <span class="">' . $linha['nome_selecao'] . ' - ' . $linha['selecao_ano'] . '</span>
                            </td>
                            <td style="white-space: nowrap;">' . $linha['tel_celular'] . '</td>
                            <td class="text-center" style="white-space: nowrap;">
                                <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar usuário">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="usuario_altera_senha.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Resetar senha">
                                    <i class="fa fa-key"></i>
                                </a>

                                <a href="usuario_editar.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Editar usuário">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'usuario\')" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Excluir usuário">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>';
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