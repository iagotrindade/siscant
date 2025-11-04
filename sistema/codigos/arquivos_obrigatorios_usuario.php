<?php
/*
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 235345345!");
        exit();
    }
 * 
 */
?>

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
            <table class="table table-hover table-striped" id="tabela_dinamica2">
                <thead class="table-light">
                    <tr>
                        <th>
                            <i class="fa fa-file-pdf me-1"></i>
                            Nome do Arquivo
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($id_usuario);

                    if (count($lista_docs_obrigatorios) > 0):
                        foreach ($lista_docs_obrigatorios as $linha):
                    ?>
                            <tr>
                                <td>
                                    <a href="baixaPDF.php?codigo=arq_obr_usu&nome_arquivo=<?= $linha['nome'] ?>"
                                        target="_blank"
                                        class="text-decoration-none fw-medium">
                                        <i class="fa fa-file-pdf me-2 text-danger"></i>
                                        <?= htmlspecialchars($linha['label']) ?>
                                    </a>
                                    <small class="text-muted d-block ms-4 mt-1">
                                        <i class="fa fa-external-link-alt me-1"></i>
                                        Clique para visualizar o documento
                                    </small>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fa fa-folder-open fa-2x mb-3"></i>
                                    <p class="mb-0">Nenhum arquivo obrigatório adicionado.</p>
                                    <small>Os documentos aparecerão aqui quando forem enviados.</small>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Informações -->
        <div class="alert alert-info mt-20">
            <div class="d-flex align-items-center">
                <i class="fa fa-info-circle fa-2x mr-10"></i>
                <div>
                    <strong>Documentos Obrigatórios</strong>
                    <p class="mb-0 mt-1">Esta lista contém todos os documentos obrigatórios enviados pelo candidato conforme o Aviso de Convocação do Processo Seletivo.</p>
                </div>
            </div>
        </div>
    </div>
</div>