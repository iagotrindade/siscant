<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$lista_candidatos = $conexao->get_candidatos_desclassificados();
//$lista_candidatos = $conexao->get_candidatos_desclassificados_classificados();  

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
            <h1>Candidatos desclassificados <i class="fa fa-user-times"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Candidatos desclassificados</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white mb-20">
                    <span class="mb-0"><i class="fa fa-user-times me-2"></i> Candidatos Desclassificados</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica">
                            <thead class="table-header-custom">
                                <tr>
                                    <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                    <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                    <th><i class="fa fa-user me-1"></i> Nome</th>
                                    <th><i class="fa fa-phone me-1"></i> Telefone</th>
                                    <th><i class="fa fa-envelope me-1"></i> Email</th>
                                    <th><i class="fa fa-list-ol me-1"></i> Etapa</th>
                                    <th width="80px" class="text-center"><i class="fa fa-trash me-1"></i> Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($lista_candidatos as $linha) {
                                    echo '
                                <tr class="table-row-desclassified">
                                    <td><span class="badge bg-secondary">' . $linha['id'] . '</span></td>
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
                                    <td>' . $linha['tel_celular'] . '</td>
                                    <td>
                                        <div class="text-truncate d-inline-block" style="max-width: 200px;" title="' . $linha['mail'] . '">
                                            ' . $linha['mail'] . '
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-etapa-desclassified">et_' . $linha['etapa'] . '</span>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn-modern btn-delete" onclick="funcao_apagar(\'' . $linha['id'] . '\', \'candidato\')" 
                                           data-bs-toggle="tooltip" 
                                           title="Excluir candidato">
                                            <i class="fa fa-trash"></i> Excluir
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
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable({
        "order": [
            [2, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>