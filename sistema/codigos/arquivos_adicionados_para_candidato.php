<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == '1') {
    erro("Erro 2353565! Página não encontrada!");
    exit();
}

$lista_observacaoes = $conexao->get_observacoes_candidato($id_usuario);
include_once '../sistema/codigos/funcao_apagar.php';
?>
<a name="insere_arquivo_candidato"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->

<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-upload me-2"></i>
            Arquivos Adicionados para o Candidato
        </span>
    </div>
    <div class="card-body">
        <form method="post" action="arquivo_upload_adicionado_para_candidato.php" enctype="multipart/form-data">
            <div class="row">
                <!-- Upload de Arquivo -->
                <div class="col-md-8 mb-4">
                    <div class="mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-upload me-1"></i>
                            Adicionar Arquivo
                            <small class="text-danger">*Máximo 5 Megabytes</small>
                        </label>
                        <input type="file" name="arquivo" class="form-control" />
                    </div>

                    <div class="mb-20">
                        <label class="form-label fw-semibold">
                            <i class="fa fa-tag me-1"></i>
                            Descrição do Arquivo
                        </label>
                        <input name="label" maxlength="200" placeholder="Descreva qual arquivo está enviando..." class="form-control">
                    </div>

                    <!-- Campos Ocultos -->
                    <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                    <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                    <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload me-2"></i>
                        Enviar Arquivo
                    </button>
                </div>

                <!-- Lista de Arquivos Existentes -->
                <?php $lista_arquivos_candidato = $conexao->get_arquivos_candidato($id_usuario); ?>
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-3">
                        <i class="fa fa-files me-1"></i>
                        Arquivos Adicionados
                        <span class="badge bg-secondary ms-2"><?= count($lista_arquivos_candidato) ?></span>
                    </label>

                    <?php if (empty($lista_arquivos_candidato)): ?>
                        <div class="text-center text-muted py-4 border rounded bg-light">
                            <i class="fa fa-folder-open fa-2x mb-2 opacity-50"></i><br>
                            Nenhum arquivo adicionado
                        </div>
                    <?php else: ?>
                        <div class="file-list">
                            <?php foreach ($lista_arquivos_candidato as $arquivo):
                                $crip = hash('sha256', "freitas" . $arquivo['id']);

                                // Define cor e ícone baseado na extensão
                                $file_icon = 'fa-file-o';
                                $file_color = 'text-primary';

                                if ($arquivo['extensao'] == 'pdf') {
                                    $file_icon = 'fa-file-pdf-o';
                                    $file_color = 'text-danger';
                                }
                                if ($arquivo['extensao'] == 'odt' || $arquivo['extensao'] == 'doc' || $arquivo['extensao'] == 'docx') {
                                    $file_icon = 'fa-file-word-o';
                                    $file_color = 'text-info';
                                }
                                if ($arquivo['extensao'] == 'xls' || $arquivo['extensao'] == 'xlsx' || $arquivo['extensao'] == 'ods') {
                                    $file_icon = 'fa-file-excel-o';
                                    $file_color = 'text-success';
                                }
                                if ($arquivo['extensao'] == 'jpg' || $arquivo['extensao'] == 'jpeg' || $arquivo['extensao'] == 'png' || $arquivo['extensao'] == 'gif') {
                                    $file_icon = 'fa-file-image-o';
                                    $file_color = 'text-info';
                                }
                            ?>
                                <div class="file-item card border mb-2">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center col-auto mr-10">
                                                <i class="fa <?= $file_icon ?> <?= $file_color ?> fa-2x mr-10"></i>

                                                <div class="col">
                                                    <div class="fw-medium text-dark mb-1">
                                                        <?= htmlspecialchars($arquivo['label']) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fa fa-file-code me-1"></i>
                                                        .<?= $arquivo['extensao'] ?>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <div class="">
                                                    <a href="arquivos_add_p_cand/<?= $arquivo['nome'] ?>"
                                                        target="_blank"
                                                        class="mr-10"
                                                        data-bs-toggle="tooltip"
                                                        title="Visualizar arquivo">
                                                        <i class="fa fa-eye" style="font-size: 20px;"></i>
                                                    </a>
                                                    <a onclick="funcao_apagar('<?= $arquivo['id'] ?>', 'arquivo','<?= $crip ?>')"
                                                        class=""
                                                        data-bs-toggle="tooltip"
                                                        title="Excluir arquivo">
                                                        <i class="fa fa-trash" style="font-size: 20px;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>