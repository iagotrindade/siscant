<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $perfil != 'consulta') {
    erro("Erro 37345757! Página não encontrada!");
    exit();
}

$lista_candidatos = $conexao->get_todos_candidatos();

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

    .badge {
        font-size: 0.9em;
        padding: 6px 10px;
        border-radius: 6px;
        font-weight: 500;
        background-color: var(--primary-color);
    }

    .export-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
        height: 100%;
    }

    .export-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-color: #006400;
    }

    .export-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .export-icon i {
        font-size: 2.3rem;
    }

    .export-content h6 {
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
        font-size: 1.5rem;
    }

    .export-content p {
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 0;
    }

    .card-link {
        text-decoration: none;
        color: inherit;
    }

    .card-link:hover {
        text-decoration: none;
        color: inherit;
    }

    .btn-outline-secondary {
        border-radius: 8px;
        padding: 10px 30px;
        font-weight: 500;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        .table th,
        .table td {
            padding: 8px 10px;
        }

        .export-card {
            flex-direction: column;
            text-align: center;
            padding: 15px;
        }

        .export-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }

        .card-header {
            padding: 12px 15px;
        }
    }

    /* Melhorias na tabela */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 100, 0, 0.05);
    }

    /* Tooltips personalizados */
    .tooltip-inner {
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.8rem;
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Candidatos <i class="fa fa-users"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Card de Candidatos Participando -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center mb-20">
                    <span class="mb-0"><i class="fa fa-users me-2"></i> Candidatos Participando</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                    <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                    <th><i class="fa fa-user me-1"></i> Nome</th>
                                    <th><i class="fa fa-thumb-tack me-1"></i> Localização </th>
                                    <th><i class="fa fa-heartbeat me-1"></i> Grupo</th>
                                    <th><i class="fa fa-heartbeat me-1"></i> Grupo Recurso</th>
                                    <th><i class="fa fa-phone me-1"></i> Telefone</th>
                                    <th><i class="fa fa-envelope me-1"></i> Email</th>
                                    <th><i class="fa fa-group me-1"></i> Etapa</th>
                                    <th><i class="fa fa-check-circle me-1"></i> Distribuído</th>
                                    <th><i class="fa fa-thumb-tack me-1"></i> Guarnição Dest.</th>
                                    <th><i class="fa fa-key me-1"></i> Senha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($lista_candidatos as $linha) {
                                    if ($linha['etapa'] < $_SESSION['etapa_selecao']) continue;
                                    if ($linha['medico_obrigatorio'] == '1') continue;

                                    $cidade = $conexao->get_cidade_id($linha['id_cidade']);

                                    $incorporado = null;
                                    if ($linha['incorporado'] == '1') $incorporado = "<span class='badge bg-success'>Sim</span>";
                                    if ($linha['incorporado'] == '0') $incorporado = "<span class='badge bg-secondary'>Não</span>";

                                    $guarnicao_destino = null;
                                    if ($linha['id_cidade_distribuicao'] != null) {
                                        $resultado_cidade = $conexao->get_cidade_id($linha['id_cidade_distribuicao']);
                                        $guarnicao_destino = $resultado_cidade[0]['nome'];
                                    }

                                    // Determinar cor da etapa baseada no número
                                    $etapa_class = 'bg-primary';
                                    if ($linha['etapa'] >= 5) $etapa_class = 'bg-warning text-dark';
                                    if ($linha['etapa'] >= 8) $etapa_class = 'bg-success';

                                    echo '
                                <tr>
                                    <td><span class="badge bg-dark">' . $linha['id'] . '</span></td>
                                    <td>
                                        <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="text-decoration-none">
                                            <span class="text-primary fw-bold">' . $linha['cpf'] . '</span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold">' . $linha['nome_completo'] . '</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">' . mb_strtoupper($cidade[0]['nome'] . '/' . $linha['uf']) . '</span></td>
                                    <td><span class="badge bg-secondary">' . $linha['grupo_saude'] . '</span></td>
                                    <td><span class="badge bg-warning text-dark">recurso_' . $linha['grupo_saude_recurso'] . '</span></td>
                                    <td>' . $linha['tel_celular'] . '</td>
                                    <td>
                                        <div class="text-truncate d-inline-block" style="max-width: 150px;" title="' . $linha['mail'] . '">
                                            ' . $linha['mail'] . '
                                        </div>
                                    </td>
                                    <td><span class="badge ' . $etapa_class . '">et_' . $linha['etapa'] . '</span></td>
                                    <td>' . $incorporado . '</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">' . ($guarnicao_destino ?: '-') . '</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="usuario_altera_senha.php?id_usuario=' . $linha['id'] . '" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Resetar senha">
                                            <i class="fa fa-key"></i>
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

            <!-- Card de Exportações Adicionais -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-download me-2"></i> Exportações de Arquivos</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="excel_candidatos_participando.php" class="card-link">
                                <div class="export-card card-hover">
                                    <div class="export-icon bg-primary">
                                        <i class="fa fa-group"></i>
                                    </div>
                                    <div class="export-content">
                                        <h6>Candidatos Participando</h6>
                                        <p>Exportar dados de candidatos que estão participando do Processo Seletivo</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-20">
                            <a href="excel_candidatos_participando.php" class="card-link">
                                <div class="export-card card-hover">
                                    <div class="export-icon bg-primary">
                                        <i class="fa fa-group"></i>
                                    </div>
                                    <div class="export-content">
                                        <h6>Todos Candidatos</h6>
                                        <p>Exportar dados todos os candidatos</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="excel_distribuicao.php" class="card-link">
                                <div class="export-card card-hover">
                                    <div class="export-icon bg-primary">
                                        <i class="fa fa-exchange"></i>
                                    </div>
                                    <div class="export-content">
                                        <h6>Planilha de Distribuição</h6>
                                        <p>Exportar dados de distribuição dos candidatos</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="excel_pontuacao_especialidades.php" class="card-link">
                                <div class="export-card card-hover">
                                    <div class="export-icon bg-primary">
                                        <i class="fa fa-percent"></i>
                                    </div>
                                    <div class="export-content">
                                        <h6>Pontuação das Especialidades</h6>
                                        <p>Exportar pontuação por especialidade</p>
                                    </div>
                                </div>
                            </a>
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
    $('#tabela_dinamica').DataTable({
        "order": [
            [2, "asc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable({
        "order": [
            [2, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>