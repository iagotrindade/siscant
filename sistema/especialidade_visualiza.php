<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (!isset($_SESSION))
    session_start();

$resultado_selecao = $conexao->get_selecao_id();
if (
    $resultado_selecao[0]['codigo'] == 'ott_stt'
    || $resultado_selecao[0]['codigo'] == 'mfdv'
    || $resultado_selecao[0]['codigo'] == 'cet'
    || $resultado_selecao[0]['codigo'] == 'ott'
    || $resultado_selecao[0]['codigo'] == 'stt'
) {
    $_SESSION['eipot'] = 0;
    unset($_SESSION['eipot']);
}


if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235332446!");
    exit();
}

?>

<style>
    .form-control:focus {
        border-color: #006400;
        box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15);
    }

    .card-checkbox {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .card-checkbox:hover {
        background: #e9ecef;
        border-color: #006400;
        transform: translateY(-2px);
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0.2rem;
    }

    .form-check-input:checked {
        background-color: #006400;
        border-color: #006400;
    }

    .form-check-label {
        font-weight: 500;
        color: #495057;
        cursor: pointer;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 12px 15px;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .badge {
        background-color: var(--primary-color);
        font-size: 0.85em;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .documento-info {
        display: flex;
        align-items: center;
    }

    .fw-semibold {
        font-weight: 600;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-checkbox {
            margin-bottom: 10px;
            padding: 12px;
        }

        .table-responsive {
            font-size: 0.8rem;
        }
    }

    /* Estados da tabela */
    .table-modern tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .tooltip-inner {
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.8rem;
    }
</style>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Especialidades <i class="fa fa-graduation-cap"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Especialidades</li>
            </ul>
        </div>
    </div>

    <div class="row" <?php if ($_SESSION['perfil'] != "admin") echo "hidden"; ?>>
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-graduation-cap me-2"></i> Cadastrar Nova Especialidade</span>
                    <small class="opacity-75">Todos os campos são obrigatórios</small>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/especialidade_cadastra.php" method="post" onsubmit="return validar_formulario()" class="needs-validation" novalidate>
                        <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

                        <div class="row mb-20">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Nome da especialidade</label>
                                    <input id="nome_especialidade" name="nome_especialidade" maxlength="240"
                                        class="form-control form-control-lg" placeholder="Digite o nome da especialidade" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Categoria</label>
                                    <select name="ott_stt" class="form-control" required>
                                        <option value="" selected disabled>Selecione a categoria</option>
                                        <option value="medico">Médico</option>
                                        <option value="farmaceutico">Farmacêutico</option>
                                        <option value="dentista">Dentista</option>
                                        <option value="veterinario">Veterinário</option>
                                        <option value="ott">OTT</option>
                                        <option value="stt">STT</option>
                                        <option value="cet">CET</option>
                                        <option value="ottm">OTTM</option>
                                        <option value="pctd">PCTD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Cidades da especialidade</label>
                                    <br>
                                    <select class="form-control js-example-basic-multiple" name="cidades[]" multiple="multiple" required>
                                        <?php
                                        $resultado = $conexao->busca_cidades();
                                        foreach ($resultado as $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . ' - ' . $value['uf'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row g-2">
                                    <div class="col-lg-6" <?php if ($codigo_selecao != 'ott_stt') echo "hidden"; ?>>
                                        <div class="form-check card-checkbox">
                                            <input class="form-check-input" name="musica" type="checkbox" id="musica">
                                            <label class="form-check-label" for="musica">
                                                <i class="fa fa-music me-2"></i> É uma especialidade de MÚSICA
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6" <?php if ($codigo_selecao == 'mfdv') echo "hidden"; ?>>
                                        <div class="form-check card-checkbox">
                                            <input class="form-check-input" name="teste_pratico" type="checkbox" id="teste_pratico">
                                            <label class="form-check-label" for="teste_pratico">
                                                <i class="fa fa-flask me-2"></i> Tem teste prático
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i> CADASTRAR ESPECIALIDADE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white mb-20">
                    <span class="mb-0"><i class="fa fa-list me-2"></i> Especialidades Cadastradas</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica">
                            <thead class="table-header-custom">
                                <tr>
                                    <th><i class="fa fa-tag"></i> Categoria</th>
                                    <th><i class="fa fa-graduation-cap"></i> Nome</th>
                                    <?php if ($codigo_selecao != 'mfdv'): ?>
                                        <th class="text-center"><i class="fa fa-music"></i> Música</th>
                                        <th class="text-center"><i class="fa fa-flask"></i> Teste Prático</th>
                                    <?php endif; ?>
                                    <th><i class="fa fa-map-marker"></i> Cidades</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_especialidades = $conexao->get_especialidade();
                                foreach ($lista_especialidades as $linha) {
                                    $teste = $linha['teste_pratico'] == 1 ?
                                        "<span class='badge bg-success'><i class='fa fa-check'></i> Sim</span>" :
                                        "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";

                                    $musica = $linha['musica'] == 1 ?
                                        "<span class='badge bg-info'><i class='fa fa-check'></i> Sim</span>" :
                                        "<span class='badge bg-secondary'><i class='fa fa-times'></i> Não</span>";

                                    $cidades_html = '';
                                    $lista_cidades = $conexao->get_cidades_especialidade($linha['id']);
                                    if (count($lista_cidades) > 0) {
                                        $cidades = [];
                                        foreach ($lista_cidades as $linha_cidade) {
                                            $cidades[] = $linha_cidade['nome'];
                                        }
                                        $cidades_html = '<span class="cidades-list" title="' . implode(', ', $cidades) . '">' . implode(' | ', $cidades) . '</span>';
                                    }

                                    echo '
                                <tr class="table-row-custom">
                                    <td>
                                        <span class="badge categoria-badge">' . mb_strtoupper($linha['ott_stt'], 'UTF-8') . '</span>
                                    </td>
                                    <td>
                                        <div class="especialidade-info">
                                            <i class="fa fa-graduation-cap text-primary me-2"></i>
                                            <span class="fw-semibold">' . $linha['nome'] . '</span>
                                        </div>
                                    </td>';

                                    if ($codigo_selecao != 'mfdv') {
                                        echo '
                                    <td class="text-center">' . $musica . '</td>
                                    <td class="text-center">' . $teste . '</td>';
                                    }

                                    echo '
                                    <td>' . $cidades_html . '</td>
                                   <td class="text-center" style="white-space: nowrap;">
                                        
                                        <a href="especialidade_editar.php?id_especialidade=' . $linha['id'] . '" 
                                            class="btn btn-sm action-btn"
                                            data-bs-toggle="tooltip" 
                                            title="Editar especialidade">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'especialidade\')" 
                                            class="btn btn-sm action-btn"
                                            data-bs-toggle="tooltip" 
                                            title="Excluir especialidade">
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
</body>

</html>
<?php
$conexao = null;
?>