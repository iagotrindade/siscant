<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$id_especialidade = $_GET['esp'];
$id_usuario = $_SESSION['id_usuario'];

$resultado_verificacao = $conexao->verifica_especialidade_candidato($id_usuario, $id_especialidade);

if (count($resultado_verificacao) == 0) {
    erro("Erro 5442654: Página não encontrada!");
    exit();
} else {
    $id_candidato_x_especialidade = $resultado_verificacao[0]['id_candidato_x_especialidade'];
    $id_especialidade   = $resultado_verificacao[0]['id_especialidade'];
    $nome_especialidade = $resultado_verificacao[0]['especialidade'];
    $cpf_candidato      = $resultado_verificacao[0]['cpf'];
    $nome_candidato     = $resultado_verificacao[0]['nome_completo'];
    $ott_stt            = $resultado_verificacao[0]['ott_stt'];
    $concorrendo_esp    = $resultado_verificacao[0]['concorrendo'];
    $justificativa_con  = $resultado_verificacao[0]['justificativa'];
}

$get_prioridade = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_curricular = 0;
if (count($get_selecao) > 0)
    $liberacao_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];
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

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .prioridade-badge {
        width: 40px;
        height: 40px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .prioridade-item {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }

    .prioridade-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    @media (max-width: 768px) {
        .prioridade-item {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .prioridade-badge {
            margin-right: 0 !important;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <!-- 22/06/2025 -> Iago Silva Alterado o ícone -->
            <h1>Especialidade <?php echo strtoupper($ott_stt) . " - " . $nome_especialidade ?> <i class="fa fa-graduation-cap"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Detalhamento da especialidade</li>
            </ul>
        </div>
    </div>

    <?php

    if ($selecao_libera_prioridade_candidato == '1')
        include_once './codigos/candidato_prioridades_cidades_especialidade.php';
    ?>

    <?php if ($concorrendo_esp == 0) : ?>
        <div class="col-12">
            <div class="card border-danger shadow-sm mb-4">
                <div class="card-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-info-circle me-2"></i>
                        Status da Inscrição
                    </span>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger border-0 mb-0">
                        <div style="display: flex; align-items: center;">
                            <i class="fa fa-exclamation-triangle fa-2x mr-10 mt-1"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-0">DESCLASSIFICADO</h5>
                                <p class="mb-0 fs-6">
                                    <strong>Justificativa:</strong> <?= htmlspecialchars($justificativa_con) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <!-- Card de Upload de Currículo -->
        <div class="card shadow-sm mb-4"
            <?php
            if ($_SESSION['selecao_regiao'] == 3) {
                if ($concorrendo_esp == 0 || !inscricao() || (count($lista_cidades) != 0 && $selecao_libera_prioridade_candidato == '1'))
                    echo 'hidden';
            } else {
                if ($concorrendo_esp == 0 || !inscricao() || (count($lista_cidades) == $quantidade_cidades && $selecao_libera_prioridade_candidato == '1'))
                    echo 'hidden';
            }
            ?>>

            <div class="card-header bg-primary text-white mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-upload me-2"></i> Adicionar Arquivos de Currículo
                </span>
                <small class="opacity-75">Especialidade: <strong><?= htmlspecialchars($nome_especialidade) ?></strong></small>
            </div>

            <div class="card-body">
                <form method="post" action="arquivo_upload_curriculo.php" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="user" value="<?= $id_especialidade ?>">
                    <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">

                    <div class="alert alert-warning mb-4">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Os arquivos de diplomas devem conter frente e verso!
                    </div>

                    <div class="row g-3">
                        <!-- Seleção do Tipo de Currículo -->
                        <div class="col-md-12 mb-20">
                            <label for="id_curriculo" class="form-label">
                                Tipo de Documento
                                <span class="text-danger">*</span>
                            </label>
                            <select name="id_curriculo" class="form-control" required>
                                <option value="">Selecione o arquivo a ser adicionado</option>
                                <?php
                                $lista_curriculos = $conexao->get_curriculo_cadastrados();
                                foreach ($lista_curriculos as $linha) {
                                    echo '<option value="' . $linha['id'] . '">' . htmlspecialchars($linha['nome']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Datas e Resumo -->
                        <div class="col-md-3 mb-20">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="text" id="data_inicio" name="data_inicio" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                        </div>

                        <div class="col-md-3 mb-20">
                            <label for="data_fim" class="form-label">Data de Finalização</label>
                            <input type="text" id="data_fim" name="data_fim" maxlength="120" class="form-control" placeholder="DD/MM/AAAA">
                        </div>

                        <div class="col-md-6 mb-20">
                            <label for="carga_horaria" class="form-label">
                                Resumo do PDF
                                <small class="text-muted">(Max 200 caracteres)</small>
                            </label>
                            <input type="text" id="carga_horaria" name="carga_horaria" maxlength="120" class="form-control" placeholder="Breve descrição do documento">
                        </div>

                        <!-- Upload do Arquivo -->
                        <div class="col-md-6 mb-20">
                            <label for="arquivo" class="form-label">
                                Arquivo PDF
                                <small class="text-danger">* Máximo 5MB</small>
                            </label>
                            <input type="file" id="arquivo" name="arquivo" class="form-control" accept=".pdf" required>
                            <div class="invalid-feedback">Por favor, selecione um arquivo PDF de até 5MB.</div>
                        </div>

                        <!-- Botão de Envio -->
                        <div class="col-md-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-upload me-2"></i> ENVIAR ARQUIVO
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_usuario, $id_especialidade);
        $mostra_pontuacao = false;
        foreach ($lista_docs_obrigatorios as $linha) {
            if ($linha['valido'] === '1') $mostra_pontuacao = true;
        }
        ?>

        <!-- Lista de Documentos Enviados -->
        <div class="card shadow-sm">
            <div class="card-header mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-folder-open me-2"></i>
                    Documentos de Currículo Enviados
                </span>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="fa fa-info-circle me-2"></i>
                    <strong>Lembre-se do Diploma (pré-requisito) e do Registro do Conselho Profissional se for o caso</strong>
                </div>

                <?php if (empty($lista_docs_obrigatorios)): ?>
                    <div class="text-center py-5">
                        <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum documento de currículo enviado ainda.</p>
                        <small class="text-muted">Use o formulário acima para adicionar seus documentos.</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-file-text"></i> Documento</th>
                                    <th width="120px"><i class="fa fa-calendar"></i> Início</th>
                                    <th width="120px"><i class="fa fa-calendar"></i> Término</th>
                                    <th><i class="fa fa-file-text"></i> Resumo</th>
                                    <?php if ($liberacao_avaliacao_curricular == 1): ?>
                                        <th width="120px"><i class="fa fa-thumbs-o-up"></i> Avaliação</th>
                                        <th width="200px"><i class="fa fa-pencil-square-o"></i> Justificativa</th>
                                        <?php if (isset($_SESSION['12_regiao']) && $mostra_pontuacao): ?>
                                            <th width="100px"><i class="fa fa-pencil-edit"></i> Pontuação</th>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (inscricao()): ?>
                                        <th width="80px" class="text-center"> <i class="fa fa-cogs"></i> Ação</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pontuacao_total = 0;
                                foreach ($lista_docs_obrigatorios as $linha) {
                                    // Status do documento
                                    $status_icon = 'fa-clock text-warning';
                                    $status_text = 'Pendente';
                                    $status_badge = 'warning';

                                    if ($linha['valido'] === '1') {
                                        $status_icon = 'fa-check-circle text-success';
                                        $status_text = 'Válido';
                                        $status_badge = 'success';
                                    } elseif ($linha['valido'] === '0') {
                                        $status_icon = 'fa-times-circle text-danger';
                                        $status_text = 'Inválido';
                                        $status_badge = 'danger';
                                    }

                                    // Cálculo da pontuação
                                    $pontuacao = $linha['pontuacao'] ?? 0;
                                    $multiplicacao = $linha['multiplicador'] ?? 1;
                                    $pontuacao_calculada = ($pontuacao / 1000) * $multiplicacao;
                                    if ($linha['valido'] === '0') $pontuacao_calculada = 0;
                                    $pontuacao_total += $pontuacao_calculada;

                                    $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_especialidade_curriculo']);

                                    $dt_inicio = $linha['data_inicio'] ? trata_data($linha['data_inicio']) : '-';
                                    $dt_fim = $linha['data_termino'] ? trata_data($linha['data_termino']) : '-';
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="baixaPDF.php?codigo=cand_esp_cad_vis&nome_arquivo=<?= urlencode($linha['nome']) ?>"
                                                        target="_blank"
                                                        class="text-decoration-none fw-semibold">
                                                        <?= htmlspecialchars($linha['label']) ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class=""><?= $dt_inicio ?></span>
                                        </td>
                                        <td>
                                            <span class=""><?= $dt_fim ?></span>
                                        </td>
                                        <td>
                                            <span class=""><?= htmlspecialchars($linha['carga_horaria']) ?></span>
                                        </td>

                                        <?php if ($liberacao_avaliacao_curricular == 1): ?>
                                            <td>
                                                <span class="">
                                                    <span class="">
                                                        <?php if ($linha['valido'] === '1'): ?>
                                                            <img class="me-1" src="imagens/like.jpg" alt="Ícone Indeferido" style="width:36px; height:36px;">
                                                        <?php elseif ($linha['valido'] === '0'): ?>
                                                            <img class="me-1" src="imagens/no_like.jpg" alt="Ícone Indeferido" style="width:36px; height:36px;">
                                                        <?php endif; ?>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?= !empty($linha['justificativa']) ? htmlspecialchars($linha['justificativa']) : '-' ?>
                                                </span>
                                            </td>
                                            <?php if (isset($_SESSION['12_regiao']) && $mostra_pontuacao): ?>
                                                <td>
                                                    <span class="fw-bold text-success"><?= number_format($pontuacao_calculada, 2) ?></span>
                                                </td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (inscricao()): ?>
                                            <td class="text-center">
                                                <button onclick="funcao_apagar('<?= $linha['id_especialidade_curriculo'] ?>', 'candidato_curriculo', '<?= $crip ?>')"
                                                    class="btn btn-sm action-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Excluir documento">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total de Pontuação -->
                    <?php if (isset($_SESSION['12_regiao']) && $mostra_pontuacao): ?>
                        <div class="alert alert-success mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="fs-5">Total de Pontos Avaliados:</strong>
                                <span class="fs-4 fw-bold"><?= number_format($pontuacao_total, 2) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
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