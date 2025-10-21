<?php

$usuarios_destinados = null;

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if (count($usuario_logado) == 1)
    $usuarios_destinados = $conexao->get_candidatos_concorrendo_om($usuario_logado[0]['om_id']);

$ano_atual = date("Y");

$ano_selecao = null;
if (isset($_GET['ano_selecao']))
    $ano_selecao = $_GET['ano_selecao'];

$incorporados = null;
if (isset($_GET['incorporados']))
    $incorporados = $_GET['incorporados'];

if ($incorporados == "medicos") {
    $usuarios_destinados = null;
    if (count($usuario_logado) == 1)
        $usuarios_destinados = $conexao->get_candidatos_concorrendo_om_medicos_obr($usuario_logado[0]['om_id']);
}
?>


<div class="card">
    <div class="card-header bg-primary text-white mb-20">
        <span class="mb-0"><i class="fa fa-filter"></i> Filtros de Pesquisa</span>
    </div>
    <div class="card-body">
        <form name="fomulario" action="<?php $_SERVER["PHP_SELF"] ?>" method="get">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Selecione o ano da Incorporação:</label>
                    <select onchange="fomulario.submit()" name="ano_selecao" class="form-control form-control-lg">
                        <option value="todos">TODOS OS ANOS</option>
                        <?php
                        for ($i = $ano_atual; $i >= 2019; $i--) {
                            echo '<option value="' . $i . '"';
                            if ($ano_selecao == $i) echo " selected";
                            if ($ano_selecao == null && $ano_atual == $i) echo " selected";
                            echo '>' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Selecione os Incorporados:</label>
                    <select onchange="fomulario.submit()" name="incorporados" class="form-control form-control-lg">
                        <option value="voluntarios">SOMENTE OS VOLUNTÁRIOS</option>
                        <option value="medicos" <?php if ($incorporados == "medicos") echo "selected"; ?>>SOMENTE MÉDICOS OBRIGATÓRIOS</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-success text-white mb-20">
        <div class="documento-info">
            <i class="fa fa-building fa-2x"></i>
            <div>
                <span class="fw-semibold"><?php echo $usuario_logado[0]['om_nome'] . " (" . $usuario_logado[0]['om_abreviatura'] . ")"; ?></span>
                <br>
                <small>Lista de Incorporados</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern table-hover" id="tabela_dinamica">
                <thead class="table-header-custom">
                    <tr>
                        <th><i class="fa fa-id-card me-1"></i> CPF</th>
                        <th><i class="fa fa-user me-1"></i> Nome</th>
                        <th><i class="fa fa-graduation-cap me-1"></i> Especialidade</th>
                        <th><i class="fa fa-file-text me-1"></i> Aditamento</th>
                        <th><i class="fa fa-comment me-1"></i> Observação</th>
                        <th><i class="fa fa-calendar me-1"></i> Data Incorporação</th>
                        <th><i class="fa fa-check-circle me-1"></i> Apresentação</th>
                        <th><i class="fa fa-cogs me-1"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($usuarios_destinados as $linha) {
                        $foto = "user.jpg";
                        $get_foto = $conexao->get_foto_usuario($linha['id']);
                        if (count($get_foto) > 0)
                            $foto = $get_foto[0]['nome'];

                        $medico_obrigatorio = "";
                        if ($linha['medico_obrigatorio'] == 1)
                            $medico_obrigatorio = '<span class="badge bg-danger">Médico Obrigatório</span>';

                        // Filtro por ano
                        if ($ano_selecao != "todos") {
                            $ano_incorp = (int) substr($linha['data_incorporacao'], 0, 4);
                            if ($ano_selecao != null && $ano_incorp != $ano_selecao) continue;
                            if ($ano_selecao == null && $ano_incorp != $ano_atual) continue;
                        }

                        $data_incorporacao = null;
                        if ($linha['data_incorporacao'] != null)
                            $data_incorporacao = trata_data($linha['data_incorporacao']);

                        $cor_apresentacao_cand_om = "";
                        $status_class = "";
                        if ($linha['apresentacao_candidato_om'] == 'apresentou_inapto' || $linha['apresentacao_candidato_om'] == 'faltoso') {
                            $cor_apresentacao_cand_om = "#fd8a8a";
                            $status_class = "text-danger";
                        }

                        echo '
                        <tr>
                            <td>
                                <a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '" class="text-decoration-none">
                                    ' . $linha['cpf'] . '
                                </a>
                            </td>
                            <td class="fw-semibold">' . $linha['nome_completo'] . '</td>
                            <td>
                                <div class="documento-info">
                                    <span class="badge bg-primary">' . mb_strtoupper($linha['ott_stt']) . '</span>
                                    <span>' . $linha['nome_especialidade'] . '</span>
                                    ' . $medico_obrigatorio . '
                                </div>
                            </td>
                            <td>' . $linha['aditamento_convocacao'] . '</td>
                            <td>' . $linha['observacao_distribuicao'] . '</td>
                            <td><span class="badge bg-info">' . $data_incorporacao . '</span></td>
                            <td class="' . $status_class . '">
                                <span class="badge" style="background-color: ' . $cor_apresentacao_cand_om . '">' . $linha['apresentacao_candidato_om'] . '</span>
                            </td>
                            <td class="text-center">
                                <a href="usuario_visualiza.php?id_usuario=159644" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar usuário">
                                        <i class="fa fa-eye"></i>
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

<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#tabela_dinamica').DataTable({
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
        "iDisplayLength": -1,
        "order": [
            [1, "asc"]
        ]
    });
</script>