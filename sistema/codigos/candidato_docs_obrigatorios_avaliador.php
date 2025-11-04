<a name="arquivos_obrigatorios"></a>
<?php

if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos') {
    erro("Erro 34546");
    exit();
}
?>

<a name="avaliacao_doc_obrigatorio"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="card dashboard-card mb-4">
    <div class="card-header dashboard-header mb-20">
        <div class="d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-file-text me-2"></i>
                Arquivos Obrigatórios Adicionados
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th width="53%"><i class="fa fa-file-text me-2"></i> Nome do Arquivo</th>
                        <th width="6%" class="text-center"><i class="fa fa-check me-2"></i> Válido</th>
                        <th width="6%" class="text-center"><i class="fa fa-times me-2"></i> Inválido</th>
                        <th width="25%"><i class="fa fa-comment me-2"></i> Justificativa</th>
                        <th width="10%" class="text-center"><i class="fa fa-info-circle me-2"></i> Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($id_usuario);

                    foreach ($lista_docs_obrigatorios as $linha):
                        $crip = hash('sha256', $_SESSION['chave'] . "freitas" . $linha['id']);

                        // Foto do avaliador
                        $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/user.jpg' width='35' height='35' style='object-fit: cover;'></a>";
                        $get_foto = $conexao->get_foto_usuario($linha['usuario_avaliou']);
                        if (count($get_foto) > 0) {
                            $foto = $get_foto[0]['nome'];
                            $foto = "<a href='usuario_visualiza.php?id_usuario=" . $linha['usuario_avaliou'] . "' class='d-inline-block' data-bs-toggle='tooltip' title='Visualizar avaliador'><img class='img-circle rounded-circle border' src='fotos/$foto' width='35' height='35' style='object-fit: cover;'></a>";
                        }

                        // Status de validação
                        $validado = '';
                        if ($linha['valido'] == '0') {
                            $validado = "<span style='font-size: 14px;' class='badge bg-danger mr-10 p-10'>Inválido <i class='fa fa-thumbs-down'></i></span>" . $foto;
                        } else if ($linha['valido'] == '1') {
                            $validado = "<span style='font-size: 14px;' class='badge bg-primary mr-10 p-10'>Válido <i class='fa fa-thumbs-up'></i></span>" . $foto;
                        }
                    ?>
                        <tr>
                            <!-- Nome do Arquivo -->
                            <td>
                                <a href="baixaPDF.php?codigo=can_doc_obr_aval&nome_arquivo=<?= $linha['nome'] ?>"
                                    target="_blank"
                                    class="text-decoration-none fw-medium">
                                    <i class="fa fa-file-pdf me-1 text-danger"></i>
                                    <?= htmlspecialchars($linha['label']) ?>
                                </a>
                            </td>

                            <!-- Botão Válido -->
                            <td class="text-center">
                                <form action="../banco_dados/valida_doc_obrigatorio.php" method="post" class="d-inline">
                                    <input type="hidden" name="valido" value="1">
                                    <input type="hidden" name="criptografia" value="<?= $crip ?>">
                                    <input type="hidden" name="id_documento_obrigatorio" value="<?= $linha['id'] ?>">
                                    <input type="hidden" name="id_candidato" value="<?= $linha['id_candidato'] ?>">
                                    <input type="hidden" name="justificativa" value="">

                                    <button type="submit"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="tooltip"
                                        title="Marcar como Válido">
                                        <i class="fa fa-check" style="font-size: 24px;"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- Botão Inválido e Justificativa -->
                            <form action="../banco_dados/valida_doc_obrigatorio.php" method="post">
                                <td class="text-center">
                                    <input type="hidden" name="valido" value="0">
                                    <input type="hidden" name="criptografia" value="<?= $crip ?>">
                                    <input type="hidden" name="id_documento_obrigatorio" value="<?= $linha['id'] ?>">
                                    <input type="hidden" name="id_candidato" value="<?= $linha['id_candidato'] ?>">

                                    <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip"
                                        title="Marcar como Inválido">
                                        <i class="fa fa-times" style="font-size: 24px;"></i>
                                    </button>
                                </td>
                                <td>
                                    <textarea name="justificativa"
                                        class="form-control form-control-sm"
                                        rows="2"
                                        placeholder="Digite a justificativa..."
                                        style="width:100%;"><?= htmlspecialchars($linha['justificativa']) ?></textarea>
                                </td>
                            </form>

                            <!-- Status -->
                            <td class="text-center">
                                <?= $validado ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($lista_docs_obrigatorios) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fa fa-folder-open fa-2x mb-3"></i>
                                    <p class="mb-0">Nenhum arquivo obrigatório adicionado.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>