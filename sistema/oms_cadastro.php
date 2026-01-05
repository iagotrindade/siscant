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
            <h1>Organizações Militares <i class="fa fa-building"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Organizações Militares</li>
            </ul>
        </div>
    </div>

    <div class="row" <?php if ($_SESSION['perfil'] != "admin") echo "hidden"; ?>>
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-graduation-cap me-2"></i> Cadastrar Nova OM</span>
                    <small class="opacity-75">Todos os campos são obrigatórios</small>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/om_cadastra.php" method="post" onsubmit="return validar_formulario()" class="needs-validation" novalidate>
                        <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

                        <div class="row mb-20">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-building"></i> Nome</label>
                                    <input id="nome" name="nome" maxlength="240"
                                        class="form-control form-control-lg" placeholder="Digite o nome" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-tag"></i> Abreviatura</label>
                                    <input id="abreviatura" name="abreviatura" maxlength="240"
                                        class="form-control form-control-lg" placeholder="Digite a abreviatura" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-building"></i> RM</label>
                                    <input id="rm" name="rm" type="number" maxlength="12"
                                        class="form-control form-control-lg" placeholder="Digite o RM" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-map"></i> Comando Militar de Área</label>
                                    <select id="comando_militar_area" name="cma" type="number" maxlength="12"
                                        class="form-control form-control-lg" placeholder="Digite o Comando Militar de Área da OM" required>
                                        <option value="" selected disabled>Selecione o Comando</option>
                                        <option value="cma">Comando Militar da Amazônia (CMA)</option>
                                        <option value="cmn">Comando Militar do Norte (CMN)</option>
                                        <option value="cmne">Comando Militar do Nordeste (CMNE)</option>
                                        <option value="cmo">Comando Militar do Oeste (CMO)</option>
                                        <option value="cmp">Comando Militar do Planalto (CMP)</option>
                                        <option value="cml">Comando Militar do Leste (CML)</option>
                                        <option value="cmse">Comando Militar do Sudeste (CMSE)</option>
                                        <option value="cms">Comando Militar do Sul (CMS)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-barcode"></i> CODOM da OM</label>
                                    <input id="codom" name="codom" type="text"
                                        class="form-control form-control-lg" placeholder="Digite o CODOM da OM" required>
                                </div>
                            </div>

                            <!-- UF 1ª Fase -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-map-marker me-1"></i>
                                    UF
                                </label>
                                <select id="uf" name="uf" class="form-control" required>
                                    <option value="">Selecione a UF</option>
                                    <?php
                                    $estados = ['AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO'];
                                    foreach ($estados as $estado):
                                    ?>
                                        <option value="<?= $estado ?>">
                                            <?= $estado ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-envelope"></i> CEP</label>
                                    <input id="cep" name="cep" type="text"
                                        class="form-control form-control-lg" placeholder="Digite o CEP" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-road"></i> Endereço</label>
                                    <input id="endereco" name="endereco" type="text"
                                        class="form-control form-control-lg" placeholder="Digite o endereço" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold"><i class="fa fa-phone"></i> Telefone</label>
                                    <input id="telefone" name="telefone" type="text"
                                        class="form-control form-control-lg" placeholder="Digite o telefone" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i> CADASTRAR OM
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
                    <span class="mb-0"><i class="fa fa-list me-2"></i> OMs Cadastradas</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica">
                            <thead class="table-header-custom">
                                <tr>
                                    <th><i class="fa fa-building"></i> Nome</th>
                                    <th><i class="fa fa-tag"></i> Abreviatura</th>
                                    <th><i class="fa fa-building"></i> RM</th>
                                    <th><i class="fa fa-map"></i> CMA</th>
                                    <th><i class="fa fa-barcode"></i> CODOM</th>
                                    <th><i class="fa fa-map-marker"></i> UF</th>
                                    <th style="width: 90px;"><i class="fa fa-envelope"></i> CEP</th>
                                    <th><i class="fa fa-road"></i> Endereço</th>
                                    <th><i class="fa fa-phone"></i> Telefone</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_oms = $conexao->get_all_oms();
                                ?>
                                <?php foreach ($lista_oms as $linha) : ?>
                                    <tr>
                                        <!-- Nome -->
                                        <td><?php echo htmlspecialchars($linha['nome'] ?? ''); ?></td>

                                        <!-- Abreviatura -->
                                        <td><?php echo htmlspecialchars($linha['abreviatura'] ?? ''); ?></td>

                                        <!-- RM -->
                                        <td><?php echo htmlspecialchars($linha['rm'].'ª RM' ?? ''); ?></td>

                                        <!-- CMA -->
                                        <td><?php echo htmlspecialchars(mb_strtoupper($linha['cma'] ?? '')); ?></td>

                                        <!-- CODOM -->
                                        <td><?php echo htmlspecialchars($linha['codom'] ?? ''); ?></td>

                                        <!-- UF -->
                                        <td><?php echo htmlspecialchars($linha['uf'] ?? ''); ?></td>

                                        <!-- CEP -->
                                        <td><?php echo htmlspecialchars(mascara($linha['cep'] ?? '', '#####-###')); ?></td>

                                        <!-- Endereço -->
                                        <td><?php echo htmlspecialchars($linha['endereco'] ?? ''); ?></td>

                                        <!-- Telefone -->
                                        <td><?php echo htmlspecialchars($linha['telefone'] ?? ''); ?></td>

                                        <!-- Ações -->
                                        <td class="text-center" style="white-space: nowrap;">
                                            <a href="om_editar.php?id_om=<?php echo $linha['id']; ?>"
                                                class="btn btn-sm action-btn"
                                                data-bs-toggle="tooltip"
                                                title="Editar OM">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a onclick="funcao_apagar('<?php echo $linha['id']; ?>', 'om')"
                                                class="btn btn-sm action-btn"
                                                data-bs-toggle="tooltip"
                                                title="Excluir OM">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
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
</body>

</html>
<?php
$conexao = null;
?>