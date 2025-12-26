<?php
include_once 'menu.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 56856737! Página não encontrada!");
    exit();
}

$id_especialidade_selecionada = 0;
$somente_med_obr = false;
$selecao_selecionada = $_SESSION['selecao'];

if (isset($_GET['selecao_selecionada']))
    $selecao_selecionada = $_GET['selecao_selecionada'];

if (isset($_GET['selecao_selecionada']) && $_GET['selecao_selecionada'] ==  'somente_med_obr')
    $somente_med_obr = true;


if (isset($_GET['id_especialidade']))
    $id_especialidade_selecionada = $_GET['id_especialidade'];
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Relatório dos distribuidos <i class="fa fa-exchange"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Relatório dos distribuidos</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Filtros de Seleção e Especialidade -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtros
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Filtro de Seleção -->
                        <div class="col-md-6">
                            <form name="fomulario1" action="relatorio_incorporados.php" method="get">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-list-alt me-1"></i>
                                    Selecione a seleção
                                </label>
                                <select onchange="fomulario1.submit()" name="selecao_selecionada" class="form-control">
                                    <option value="somente_med_obr" <?= $somente_med_obr ? 'selected' : '' ?>>MÉDICOS OBRIGATÓRIOS</option>
                                    <?php foreach ($conexao->get_selecoes() as $value): ?>
                                        <option value="<?= $value['id'] ?>" <?= $selecao_selecionada == $value['id'] ? 'selected' : '' ?>>
                                            <?= mb_strtoupper($value['codigo'], "UTF-8") ?> - <?= $value['ano'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>

                        <!-- Filtro de Especialidade -->
                        <div class="col-md-6">
                            <form name="fomulario2" action="relatorio_incorporados.php" method="get">
                                <input name="selecao_selecionada" type="hidden" value="<?= $selecao_selecionada ?>">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Selecione a especialidade
                                </label>
                                <select onchange="fomulario2.submit()" name="id_especialidade" class="form-control">
                                    <option value="">Todas as especialidades</option>
                                    <?php foreach ($conexao->get_especialidade_selecao($selecao_selecionada) as $value): ?>
                                        <option value="<?= $value['id'] ?>" <?= $id_especialidade_selecionada == $value['id'] ? 'selected' : '' ?>>
                                            <?= mb_strtoupper($value['ott_stt'], "UTF-8") ?> - <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Candidatos Distribuídos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-users me-2"></i>
                            Candidatos Distribuídos
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="80px" class="text-center"><i class="fa fa-image"></i> Foto</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th width="140px" class="text-center"><i class="fa fa-calendar"></i> Data Incorporação</th>
                                    <th width="100px" class="text-center"><i class="fa fa-shield"></i> Força</th>
                                    <th width="120px"><i class="fa fa-map-marker"></i> Cidade 1º Fase</th>
                                    <th width="100px" class="text-center"><i class="fa fa-building"></i> OM 1º Fase</th>
                                    <th><i class="fa fa-file-text"></i> Apresentação OM</th>
                                    <th><i class="fa fa-comment"></i> Observação OM</th>
                                    <th width="120px"><i class="fa fa-map-marker"></i> Cidade Dist.</th>
                                    <th width="100px" class="text-center"><i class="fa fa-building"></i> OM Dist.</th>
                                    <th width="100px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_candidatos = $conexao->get_candidatos_distribuicao($selecao_selecionada, $somente_med_obr);
                                $total_incorporados = 0;

                                foreach ($lista_candidatos as $linha):
                                    if ($id_especialidade_selecionada != null && $id_especialidade_selecionada != $linha['especialidade_incorporacao']) continue;
                                    if ($linha['incorporado'] != 1) continue;
                                    if ($somente_med_obr && $linha['medico_obrigatorio'] == null) continue;

                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];

                                    // Formatação da data de incorporação
                                    $data_incorporacao = '';
                                    if ($linha['data_incorporacao'] != null) {
                                        $data_incorporacao = trata_data($linha['data_incorporacao']);
                                    }

                                    // Formatação da distribuição
                                    $distribuicao_text = '';
                                    if ($linha['numero_distribuicao'] != null) {
                                        $distribuicao_text = $linha['numero_distribuicao'] . 'ª Distribuição';
                                    }

                                    $total_incorporados++;
                                ?>
                                    <tr>
                                        <!-- Foto -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                class="d-inline-block"
                                                data-bs-toggle="tooltip"
                                                title="Visualizar candidato">
                                                <img class="img-circle rounded-circle border"
                                                    src="fotos/<?= $foto ?>"
                                                    width="50"
                                                    height="50"
                                                    style="object-fit: cover;"
                                                    alt="Foto do candidato">
                                            </a>
                                        </td>

                                        <!-- Nome -->
                                        <td>
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none fw-medium">
                                                <?= htmlspecialchars($linha['nome_completo']) ?>
                                            </a>
                                        </td>

                                        <!-- CPF -->
                                        <td>
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                <?= $linha['cpf'] ?>
                                            </a>
                                        </td>

                                        <!-- Especialidade -->
                                        <td>
                                            <div class="especialidade-info">
                                                <span class="badge bg-primary me-1"><?= mb_strtoupper($linha['ott_stt_especializacao']) ?> - <?= htmlspecialchars($linha['nome_especialidade']) ?></span>
                                            </div>
                                        </td>

                                        <!-- Data Incorporação -->
                                        <td class="text-center">
                                            <div class="incorporacao-info">
                                                <?php if ($distribuicao_text): ?>
                                                    <span class="badge bg-primary mb-1"><?= $distribuicao_text ?></span><br>
                                                <?php endif; ?>
                                                <?php if ($data_incorporacao): ?>
                                                    <small class="text-muted"><?= $data_incorporacao ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Força -->
                                        <td class="text-center">
                                            <span class="forca-badge badge bg-primary">
                                                <?= mb_strtoupper($linha['forca_distribuicao']) ?>
                                            </span>
                                        </td>

                                        <!-- Cidade 1º Fase -->
                                        <td>
                                            <span class="cidade-text"><?= htmlspecialchars($linha['nome_cidade_1_fase']) ?></span>
                                        </td>

                                        <!-- OM 1º Fase -->
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $linha['abreviatura_om_1_fase'] ?></span>
                                        </td>

                                        <!-- Apresentação OM -->
                                        <td>
                                            <span class="apresentacao-text"><?= htmlspecialchars($linha['apresentacao_candidato_om']) ?></span>
                                        </td>

                                        <!-- Observação OM -->
                                        <td>
                                            <span class="observacao-text"><?= htmlspecialchars($linha['observacao_om']) ?></span>
                                        </td>

                                        <!-- Cidade Distribuição -->
                                        <td>
                                            <span class="cidade-dist-text"><?= htmlspecialchars($linha['cidade_distribuicao']) ?></span>
                                        </td>

                                        <!-- OM Distribuição -->
                                        <td class="text-center">
                                            <span class="om-dist-badge badge bg-primary"><?= $linha['om_dist_abreviatura'] ?></span>
                                        </td>

                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar candidato">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Distribuição em massa -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-map-marker"></i>
                        Distribuir Candidatos
                    </span>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/distribuicao_executa.php" method="post">
                        <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">
                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-graduation-cap me-1"></i>
                                    Especialidade (Opcional. Se não selecionada, todos os candidatos da Guarnição Selecionada serão distribuídos)
                                </label>
                                <select name="id_especialidade" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <?php foreach ($conexao->get_especialidade() as $value): ?>
                                        <option value="<?= $value['id'] ?>">
                                            <?= mb_strtoupper($value['ott_stt'], "UTF-8") ?> - <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-check-circle me-1"></i>
                                    Status de Distribuição
                                </label>
                                <select name="incorporado" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <option value="1">Distribuído</option>
                                    <option value="0">Aguardando Distribuição</option>
                                </select>
                            </div>

                            <!-- Número da Distribuição -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-list-ol me-1"></i>
                                    Número da Distribuição
                                </label>
                                <select name="numero_distribuicao" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?= $i ?>">
                                            <?= $i ?>ª Distribuição
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Força -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-shield me-1"></i>
                                    Força
                                </label>
                                <select name="forca_distribuicao" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <option value="exercito">Exército</option>
                                    <option value="marinha">Marinha</option>
                                    <option value="aeronautica">Aeronáutica</option>
                                </select>
                            </div>

                            <!-- Status Militar -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Status Militar
                                </label>
                                <select name="titular_reserva" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <option value="titular">Titular</option>
                                    <option value="titular eis">Titular EIS</option>
                                    <option value="reserva">Reserva</option>
                                    <option value="adiado">Adiado</option>
                                    <option value="adiado_b1">Adiado B1</option>
                                    <option value="adiado_justica">Adiado Justiça</option>
                                    <option value="excesso">Excesso</option>
                                    <option value="excesso_incapaz">Excesso Incapaz</option>
                                    <option value="refratario">Refratário</option>
                                    <option value="fisemi_transferida">Fisemi Transferida</option>
                                    <option value="desobrigado">Desobrigado</option>
                                    <option value="insubmisso">Insubmisso</option>
                                    <option value="cdi_justica">CDI Justiça</option>
                                </select>
                            </div>

                            <!-- UF 1ª Fase -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-map me-1"></i>
                                    UF 1ª Fase
                                </label>
                                <select id="uf2" name="uf2" class="form-control" onchange="busca_cidades2()" required>
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

                            <!-- Guarnição 1ª Fase -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-map-marker me-1"></i>
                                    Guarnição 1ª Fase
                                </label>
                                <select id="cidade2" name="id_guarnicao" class="form-control" required>
                                    <option value="">Selecione a Cidade</option>
                                    <?php foreach ($conexao->busca_cidades() as $value): ?>
                                        <option value="<?= $value['id'] ?>">
                                            <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- OM 1ª Fase -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-building me-1"></i>
                                    OM 1ª Fase
                                </label>
                                <select name="om_distribuicao_1_fase" class="form-control" required>
                                    <option value="">Selecione a opção</option>
                                    <?php foreach ($conexao->get_oms($rm_usuario) as $value): ?>
                                        <option value="<?= $value['id'] ?>">
                                            <?= htmlspecialchars($value['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Data de Incorporação -->
                            <div class="col-lg-3 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-calendar me-1"></i>
                                    Data de Incorporação
                                </label>
                                <input
                                    type="text"
                                    name="data_incorporacao"
                                    class="form-control">
                            </div>

                            <div class="col-md-12 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-file-contract me-1"></i>
                                    Aditamento de Convocação
                                </label>
                                <input type="text"
                                    name="aditamento_convocacao"
                                    id="aditamento"
                                    class="form-control"
                                    maxlength="250"
                                    placeholder="Digite o aditamento de convocação">
                            </div>

                            <!-- Observações -->
                            <div class="col-md-12 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-sticky-note me-1"></i>
                                    Observações
                                </label>
                                <textarea name="observacao_distribuicao"
                                    class="form-control"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Digite observações sobre a distribuição"></textarea>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary py-2 fs-5">
                                    <i class="fa fa-save me-2"></i>
                                    INICIAR DISTRIBUIÇÃO
                                </button>
                            </div>

                        </div>
                    </form>
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
            [0, "asc"]
        ]
    });
</script>

</body>

</html>
<?php $conexao = null; ?>