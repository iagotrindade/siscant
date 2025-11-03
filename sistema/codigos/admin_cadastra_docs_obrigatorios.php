<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($_SESSION['id_usuario']);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($id_usuario);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);
$lista_docs_obrigatorios_sobrando = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_usuario);
$get_selecao = $conexao->get_selecao_id();
$liberado_para_visualizar_avaliacao_docs_obr = false;
if ($get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'] == '1')
    $liberado_para_visualizar_avaliacao_docs_obr = true;

$get_candidato = $conexao->get_usuario_id($id_usuario);
$filtro_lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato, $lista_docs_obrigatorios_sobrando);
$quantidade_docs_faltantes = count($filtro_lista_docs_obrigatorios_sobrando);

?>
<a name="admin_cadastra_especialidade"></a>
<div class="card dashboard-card mb-4" <?= $_SESSION['perfil'] != 'admin' ? 'hidden' : '' ?>>
    <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
        <span class="card-title mb-0">
            <i class="fa fa-upload me-2"></i>
            Enviar Documentos Obrigatórios do Candidato
        </span>
        <?php if ($quantidade_docs_faltantes > 0): ?>
            <span class="badge bg-warning fs-6">
                <i class="fa fa-file-alt me-1"></i>
                Faltantes: <?= $quantidade_docs_faltantes ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <?php if ($quantidade_docs_faltantes > 0): ?>
            <form method="post" action="arquivo_upload_doc_obrigatorio.php" enctype="multipart/form-data">
                <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">

                <div class="row">
                    <!-- Seleção do Documento -->
                    <div class="col-md-6 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-file-pdf me-1"></i>
                            Documento Obrigatório
                        </label>
                        <select name="id_arquivo_obrigatorio" class="form-control" required>
                            <option value="">Selecione o documento a ser enviado</option>
                            <?php foreach ($filtro_lista_docs_obrigatorios_sobrando as $linha): ?>
                                <option value="<?= $linha['id'] ?>"><?= htmlspecialchars($linha['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fa fa-list me-1"></i>
                            Documentos conforme o aviso de convocação
                        </small>
                    </div>

                    <!-- Upload do Arquivo -->
                    <div class="col-md-6 mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-paperclip me-1"></i>
                            Selecionar Arquivo
                        </label>
                        <div class="input-group  col-md-12">
                            <input type="file"
                                name="arquivo"
                                class="form-control"
                                accept=".pdf"
                                required>
                        </div>
                        <small class="text-muted">
                            <i class="fa fa-info-circle me-1"></i>
                            Máximo 5MB, formato PDF
                        </small>
                    </div>

                    <!-- Botão de Envio -->
                    <div class="col-md-6 mb-20 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fa fa-upload me-2"></i>
                            ENVIAR DOCUMENTO
                        </button>
                    </div>
                </div>

                <!-- Alerta de Informações -->
                <div class="alert alert-info mt-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-circle fa-2x me-3"></i>
                        <div>
                            <strong>Informações importantes:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Arquivo máximo de <strong>5 Megabytes</strong></li>
                                <li>Formato permitido: <strong>PDF</strong></li>
                                <li>Documentos devem estar legíveis e completos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-4">
                <div class="alert alert-success">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fa fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">Todos os documentos obrigatórios foram enviados!</h5>
                            <p class="mb-0">Não há documentos pendentes para este candidato.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>