<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) || $perfil != 'admin' && $perfil != 'consulta' && $perfil != 'avaliador') {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$lista_curriculos = $conexao->get_curriculo_cadastrados();

$curriculo_selecionado = null;
if (isset($_GET['curriculo_selecionado']))
    $curriculo_selecionado = (int)$_GET['curriculo_selecionado'];

$lista_especialidade_avaliador = null;
if ($perfil == 'avaliador')
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>TODOS os Currículos<i class="fa fa-graduation-cap"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>TODOS os Currículos</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Filtro de Currículo -->
            <div class="card filter-card mb-4">
                <div class="card-header filter-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-filter me-2"></i>
                        Filtro de Currículo
                    </span>
                </div>
                <div class="card-body">
                    <form name="fomulario" action="pontuacao_nao_avaliada.php" method="get">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-file-alt me-1"></i>
                                    Selecione o currículo para análise
                                </label>
                                <select onchange="fomulario.submit()" name="curriculo_selecionado" class="form-control">
                                    <option value="">Selecione o currículo</option>
                                    <?php foreach ($lista_curriculos as $linha): ?>
                                        <option value="<?= $linha['id'] ?>" <?= $curriculo_selecionado == $linha['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($linha['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="0" <?= $curriculo_selecionado === 0 ? 'selected' : '' ?>>TODOS</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Currículos em Concorrência -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
                    <span class="card-title mb-0">
                        <i class="fa fa-users me-2"></i>
                        Currículos em Concorrência
                    </span>
                    <a href="excel_pontuacao_nao_avalida.php" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Exportar para Excel">
                        <i class="fa fa-download me-1"></i>
                        Download
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th width="140px"><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-user"></i> Nome</th>
                                    <th><i class="fa fa-graduation-cap"></i> Especialidade</th>
                                    <th width="300px"><i class="fa fa-file-text"></i> Currículo</th>
                                    <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Início</th>
                                    <th width="100px" class="text-center"><i class="fa fa-calendar"></i> Fim</th>
                                    <th width="120px" class="text-center"><i class="fa fa-calendar"></i> Diferença (meses)</th>
                                    <th width="120px" class="text-center"><i class="fa fa-times"></i> Multiplicador</th>
                                    <th width="100px" class="text-center"><i class="fa fa-user-secret"></i> Suspeito</th>
                                    <th width="100px" class="text-center"><i class="fa fa-check"></i> Validado</th>
                                    <th><i class="fa fa-comment"></i> Justificativa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_candidatos = array();
                                if ($curriculo_selecionado != null || $curriculo_selecionado === 0)
                                    $lista_candidatos = $conexao->get_todos_candidatos();

                                foreach ($lista_candidatos as $linha):
                                    $id_usuario = $linha['id'];

                                    $anos_sv_militar = (int)$linha['tempo_sv_mil_anos'];
                                    $meses_sv_militar = (int)$linha['tempo_sv_mil_meses'];
                                    $dias_sv_militar = (int)$linha['tempo_sv_mil_dias'];

                                    if ($dias_sv_militar >= 30) {
                                        $meses_sv_militar++;
                                        $dias_sv_militar = $dias_sv_militar - 30;
                                    }

                                    if ($meses_sv_militar >= 12) {
                                        $total_anos_sv_publico++;
                                        $meses_sv_militar = $meses_sv_militar - 12;
                                    }

                                    $data_nascimento = trata_data($linha['data_nascimento']);
                                    $inscricoes = $conexao->get_especialidade_candidato($id_usuario);

                                    foreach ($inscricoes as $valor):
                                        // Verifica se avaliador pode ver a especialidade
                                        $avaliador_pode_ver_especialidade = false;
                                        if ($lista_especialidade_avaliador != null) {
                                            if ($valor['concorrendo'] == 0) continue;
                                            foreach ($lista_especialidade_avaliador as $linha_avaliador) {
                                                if ($linha_avaliador['id_especialidade'] == $valor['id_especialidade'])
                                                    $avaliador_pode_ver_especialidade = true;
                                            }
                                        }
                                        if ($lista_especialidade_avaliador != null && !$avaliador_pode_ver_especialidade) continue;

                                        $id_especialidade = $valor['id_especialidade'];
                                        $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $id_especialidade);
                                        $pontuacao_total = 0;

                                        // Status concorrência
                                        $concorrendo_class = 'secondary';
                                        $concorrendo_text = 'Indefinido';
                                        if ($valor['concorrendo'] != null) {
                                            if ($valor['concorrendo'] == '1') {
                                                $concorrendo_class = 'success';
                                                $concorrendo_text = 'Concorrendo';
                                            }
                                            if ($valor['concorrendo'] == '0') {
                                                $concorrendo_class = 'danger';
                                                $concorrendo_text = 'Desclassificado';
                                            }
                                        }

                                        $aux_id_curriculo = null;
                                        $data_inicio_aux = null;
                                        $data_fim_aux = null;

                                        foreach ($lista_curriculo_adicionado as $cirriculo):
                                            if ($curriculo_selecionado > 0) {
                                                if ($curriculo_selecionado != $cirriculo['id_curriculo']) continue;
                                            }

                                            $pontuacao = (int)$cirriculo['pontuacao'] / 1000;

                                            // Cálculo de diferença de datas
                                            $data_inicio = reverte_data($cirriculo['data_inicio']);
                                            $data_fim = reverte_data($cirriculo['data_termino']);

                                            $data_inicio_obj = new DateTime(date($data_inicio));
                                            $data_fim_obj = new DateTime(date($data_fim));
                                            $intervalo = $data_fim_obj->diff($data_inicio_obj);

                                            $anos = (int)$intervalo->format('%Y');
                                            $meses = (int)$intervalo->format('%m');
                                            $dias = (int)$intervalo->format('%d');

                                            $total_dias = null;
                                            if ($data_inicio != null && $data_fim != null) {
                                                $dias_anos = $anos * 365;
                                                $dias_meses = $meses * 30;
                                                $total_dias = $dias_anos + $dias_meses + $dias + 1;
                                            }
                                            $total_meses = $total_dias / 30;
                                            $total_meses = round($total_meses, 2);

                                            // Formatação de datas
                                            $dt_inicio = null;
                                            if ($cirriculo['data_inicio'] != null)
                                                $dt_inicio = trata_data($cirriculo['data_inicio']);
                                            $dt_fim = null;
                                            if ($cirriculo['data_termino'] != null)
                                                $dt_fim = trata_data($cirriculo['data_termino']);

                                            $multiplicador = $cirriculo['multiplicador'];

                                            // Status validação
                                            $validado_class = 'secondary';
                                            $validado_text = '-';
                                            if ($cirriculo['valido'] == '1') {
                                                $validado_class = 'success';
                                                $validado_text = 'Sim';
                                            }
                                            if ($cirriculo['valido'] == '0') {
                                                $validado_class = 'danger';
                                                $validado_text = 'Não';
                                            }

                                            // Verificação de sobreposição suspeita
                                            $suspeito_class = 'success';
                                            $suspeito_text = 'Não';
                                            $bg_color_inicio = '';

                                            if ($aux_id_curriculo == $cirriculo['id_curriculo']) {
                                                if ((strtotime($data_inicio_aux) <= strtotime($cirriculo['data_termino'])) &&
                                                    (strtotime($data_fim_aux) > strtotime($cirriculo['data_inicio'])) &&
                                                    $cirriculo['valido'] == '1'
                                                ) {
                                                    $suspeito_class = 'danger';
                                                    $suspeito_text = 'Sim';
                                                    $bg_color_inicio = 'bg-danger text-white';
                                                }
                                            }

                                            $data_inicio_aux = $cirriculo['data_inicio'];
                                            $data_fim_aux = $cirriculo['data_termino'];
                                            $aux_id_curriculo = $cirriculo['id_curriculo'];

                                            // Verificação de multiplicador suspeito
                                            $multiplicador_class = 'primary';
                                            if ((int)$multiplicador > (int)$total_meses + 1 && $cirriculo['valido'] == '1') {
                                                $multiplicador_class = 'danger';
                                                $suspeito_class = 'danger';
                                                $suspeito_text = 'Sim';
                                            }
                                ?>
                                            <tr>
                                                <!-- CPF -->
                                                <td>
                                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="text-decoration-none">
                                                        <?= $linha['cpf'] ?>
                                                    </a>
                                                </td>

                                                <!-- Nome -->
                                                <td>
                                                    <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                                </td>

                                                <!-- Especialidade -->
                                                <td>
                                                    <div class="especialidade-info">
                                                        <span class="badge bg-secondary me-1"><?= mb_strtoupper($valor['ott_stt'], "UTF-8") ?></span>
                                                        <span class="fw-medium"><?= htmlspecialchars($valor['especialidade']) ?></span>
                                                        <span class="badge bg-<?= $concorrendo_class ?> ms-1"><?= $concorrendo_text ?></span>
                                                    </div>
                                                </td>

                                                <!-- Currículo -->
                                                <td>
                                                    <span class="curriculo-name"><?= htmlspecialchars($cirriculo['nome_curriculo']) ?></span>
                                                </td>

                                                <!-- Data Início -->
                                                <td class="text-center <?= $bg_color_inicio ?>">
                                                    <span class="data-text"><?= $dt_inicio ?? '-' ?></span>
                                                </td>

                                                <!-- Data Fim -->
                                                <td class="text-center">
                                                    <span class="data-text"><?= $dt_fim ?? '-' ?></span>
                                                </td>

                                                <!-- Diferença -->
                                                <td class="text-center">
                                                    <span class="diferenca-meses fw-bold"><?= $total_meses ?? '0' ?></span>
                                                </td>

                                                <!-- Multiplicador -->
                                                <td class="text-center">
                                                    <span class="multiplicador-badge badge bg-<?= $multiplicador_class ?>">
                                                        <?= $multiplicador ?>x
                                                    </span>
                                                </td>

                                                <!-- Suspeito -->
                                                <td class="text-center">
                                                    <span class="suspeito-badge badge bg-<?= $suspeito_class ?>">
                                                        <?= $suspeito_text ?>
                                                    </span>
                                                </td>

                                                <!-- Validado -->
                                                <td class="text-center">
                                                    <span class="validado-badge badge bg-<?= $validado_class ?>">
                                                        <?= $validado_text ?>
                                                    </span>
                                                </td>

                                                <!-- Justificativa -->
                                                <td>
                                                    <span class="justificativa-text"><?= htmlspecialchars($cirriculo['justificativa']) ?></span>
                                                </td>
                                            </tr>
                                <?php
                                        endforeach;
                                    endforeach;
                                endforeach;
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
    $('#tabela_dinamica').DataTable({
        "order": [
            [2, "asc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>