<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($_SESSION['id_usuario']);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);
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

    .bg-warning {
        background-color: #ffc107 !important;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Documentos de Inscrição <i class="fa fa-upload"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Documentos de Inscrição</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Alertas de Status -->
            <?php if (!inscricao()): ?>
                <div class="alert alert-danger text-center mb-4">
                    <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                    <h4 class="alert-heading mb-0">INSCRIÇÕES ENCERRADAS</h4>
                </div>
            <?php endif; ?>

            <!-- Card de Upload de Documentos -->
            <div class="card <?= !inscricao() ? 'd-none' : '' ?> shadow-sm mb-4">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-upload me-2"></i>
                        Documentos de Inscrição
                    </span>
                </div>
                <div class="card-body">
                    <?php if ($quantidade_docs_faltantes > 0): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Faltam <?= $quantidade_docs_faltantes ?> documento(s) para completar sua inscrição</strong>
                        </div>

                        <form method="post" action="arquivo_upload_doc_obrigatorio.php" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <input type="hidden" name="id_candidato" value="<?= $_SESSION['id_usuario'] ?>">
                            <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                            <div class="row g-3">
                                <div class="col-md-12 mb-20">
                                    <label for="id_arquivo_obrigatorio" class="">
                                        Selecione o documento
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="id_arquivo_obrigatorio" class="form-control" required>
                                        <option value="">Selecione o arquivo a ser adicionado</option>
                                        <?php foreach ($filtro_lista_docs_obrigatorios_sobrando as $linha): ?>
                                            <option value="<?= $linha['id'] ?>"><?= htmlspecialchars($linha['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-20">
                                    <label for="arquivo" class="form-label">
                                        Arquivo PDF
                                        <small class="text-danger">* Máximo 5MB</small>
                                    </label>
                                    <input type="file"
                                        class="form-control"
                                        name="arquivo"
                                        accept=".pdf"
                                        required>
                                    <div class="invalid-feedback">Por favor, selecione um arquivo PDF de até 5MB.</div>
                                </div>

                                <div class="col-md-12 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-upload me-2"></i> ENVIAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-success" style="display: flex; align-items: center;">
                            <i class="fa fa-check-circle fa-2x mr-10 text-success"></i>
                            <div>
                                <h5 class="mb-1">Todos os Documentos de Inscrição foram adicionados!</h5>
                                <p class="mb-0">Sua documentação está completa e será analisada pela comissão.</p>
                                <p class="mb-0">Se o período para inscrição estiver aberto e seu documento for reprovado, você poderá enviar uma nova versão.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lista de Documentos Enviados -->
            <div class="card shadow-sm">
                <div class="card-header bg-light mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-folder me-2"></i>
                        Documentos Enviados
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php
                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($_SESSION['id_usuario']);

                    if (empty($lista_docs_obrigatorios)): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum documento enviado até o momento.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="tabela_dinamica">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fa fa-file-text"></i> Documento</th>
                                        <?php if ($liberado_para_visualizar_avaliacao_docs_obr): ?>
                                            <th width="120px"><i class="fa fa-thumbs-o-up"></i> Avaliação</th>
                                            <th width="200px"><i class="fa fa-pencil-square-o"></i> Justificativa</th>
                                        <?php endif; ?>
                                        <th width="80px" class="text-center"><i class="fa fa-cogs"></i> Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lista_docs_obrigatorios as $linha):
                                        $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id_documentacao_obrigatoria']);

                                        // Status do documento
                                        $status_icon = 'fa-clock text-warning';
                                        $status_text = 'Pendente';
                                        $status_badge = 'warning';

                                        if ($linha['valido'] === '1') {
                                            $status_icon = 'fa-check-circle text-success';
                                            $status_badge = 'success';
                                            $status_text = '';
                                        } elseif ($linha['valido'] === '0') {
                                            $status_icon = 'fa-thumbs-down text-danger';
                                            $status_badge = 'danger';
                                            $status_text = '';
                                        }
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a href="baixaPDF.php?codigo=doc_obr_vis&nome_arquivo=<?= urlencode($linha['nome']) ?>"
                                                            target="_blank"
                                                            class="text-decoration-none">
                                                            <strong class="text-primary"><?= htmlspecialchars($linha['label']) ?></strong>
                                                        </a>
                                                        <br>
                                                        <small class="text-muted">Enviado em: <?= date('d/m/Y H:i') ?></small>
                                                    </div>
                                                </div>
                                            </td>

                                            <?php if ($liberado_para_visualizar_avaliacao_docs_obr): ?>
                                                <td>
                                                    <span class="">
                                                        <?php if ($linha['valido'] === '1'): ?>
                                                            <img class="me-1" src="imagens/like.jpg" alt="Ícone Indeferido" style="width:36px; height:36px;">
                                                        <?php elseif ($linha['valido'] === '0'): ?>
                                                            <img class="me-1" src="imagens/no_like.jpg" alt="Ícone Indeferido" style="width:36px; height:36px;">
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($linha['justificativa'])): ?>
                                                        <span class=""
                                                            data-bs-toggle="tooltip"
                                                            title="<?= htmlspecialchars($linha['justificativa']) ?>">
                                                            <?= htmlspecialchars($linha['justificativa']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted small">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>

                                            <td class="text-center">
                                                <button onclick="funcao_apagar('<?= $linha['id_documentacao_obrigatoria'] ?>', 'documentos_obrigatorios', '<?= $crip ?>')"
                                                    class="btn btn-sm action-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Excluir documento">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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
<?php $conexao = null; ?>