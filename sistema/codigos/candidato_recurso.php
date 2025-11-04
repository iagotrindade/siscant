<a name="recursos"></a>

<div class="row" <?= ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'jise' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr') ? 'hidden' : '' ?>>
    <div class="col-md-12">
        <!-- Card de Cadastro de Recurso -->
        <div class="card filter-card mb-4">
            <div class="card-header filter-header mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-plus-circle me-2"></i>
                    Cadastrar Novo Recurso
                </span>
            </div>
            <div class="card-body">
                <form action="../banco_dados/candidato_cadastra_recurso.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Etapa -->
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-list-alt me-1"></i>
                                Etapa
                            </label>
                            <select name="etapa" class="form-control">
                                <option value="">Selecione a etapa</option>
                                <option value="1">Etapa I</option>
                                <option value="2">Etapa II</option>
                                <option value="3 - Documental">Etapa III - Documental</option>
                                <option value="3 - IS">Etapa III - IS</option>
                                <option value="4">Etapa IV</option>
                                <option value="5">Etapa V</option>
                                <option value="6">Etapa VI</option>
                                <option value="7">Etapa VII</option>
                            </select>
                        </div>

                        <!-- Cidade ISGRec -->
                        <div class="col-md-4 mb-20" id="cidade_isgrec">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-map-marker me-1"></i>
                                Cidade ISGRec
                            </label>
                            <select name="cidade_isgrec" class="form-control">
                                <option value="">Selecione a Cidade</option>
                                <option value="Porto Alegre">Porto Alegre</option>
                                <option value="Santa Maria">Santa Maria</option>
                            </select>
                        </div>

                        <!-- Data de Abertura -->
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-calendar me-1"></i>
                                Data de abertura
                            </label>
                            <input name="data_abertura" maxlength="25" class="form-control">
                        </div>

                        <!-- Para Avaliador -->
                        <?php if (!isset($_SESSION['eipot']) || $_SESSION['eipot'] != "1") : ?>
                            <div class="col-md-4 mb-20">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-user-check me-1"></i>
                                    Para Avaliador?
                                </label>
                                <select name="avaliador" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Status -->
                        <div class="col-md-4 mb-20" >
                            <label class="form-label fw-semibold">
                                <i class="fa fa-flag me-1"></i>
                                Status
                            </label>
                            <select name="status" class="form-control">
                                <option value="">Selecione</option>
                                <option value="deferido">Deferido</option>
                                <option value="deferido_parcialmente">Deferido Parcialmente</option>
                                <option value="indeferido">Indeferido</option>
                            </select>
                        </div>

                        <!-- Especialidade -->
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-graduation-cap me-1"></i>
                                Especialidade
                            </label>
                            <select name="especialidade" class="form-control">
                                <option value="">Selecione a especialidade</option>
                                <?php
                                $lista_especialidades = $conexao->get_especialidade_candidato($id_usuario);
                                $somente_uma_especialidade = null;
                                if (count($lista_especialidades) == 1)
                                    $somente_uma_especialidade = ' selected ';
                                foreach ($lista_especialidades as $especialidade):
                                ?>
                                    <option <?= $somente_uma_especialidade ?> value="<?= $especialidade['id_especialidade'] ?>">
                                        <?= htmlspecialchars($especialidade['especialidade']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Arquivo do Recurso -->
                        <div class="col-md-12 mb-20">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-file-upload me-1"></i>
                                Recurso
                                <small class="text-danger">*Máximo 8 Megabytes</small>
                            </label>
                            <input type="file" name="arquivo" class="form-control" />
                        </div>

                        <!-- Análise -->
                        <div class="col-md-12 mb-20" <?= (!isset($_SESSION['eipot']) || $_SESSION['eipot'] != "1") ? 'hidden' : '' ?>>
                            <label class="form-label fw-semibold">
                                <i class="fa fa-edit me-1"></i>
                                Análise
                            </label>
                            <textarea name="analise" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Campos Ocultos -->
                        <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['chave'] . "freitas") ?>">
                        <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                        <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                        <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave']) ?>">

                        <!-- Botão de Envio -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>
                                Cadastrar Recurso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Recursos Existentes -->
        <?php
        $lista_recursos = $conexao->get_recursos_candidato($id_usuario);
        foreach ($lista_recursos as $linha):
            // Filtros por perfil
            if ($_SESSION['perfil'] == 'jise' && $linha['obs_etapa'] != '3 - IS') continue;
            if ($_SESSION['perfil'] == 'cr' && $linha['etapa'] != 5) continue;

            $crip = hash('sha256', $linha['id']);

            // Processamento de dados
            $etapa = $linha['obs_etapa'] ?? $linha['etapa'];
            $ultima_atualizacao = $linha['_data_ultima_atualizacao'] ? trata_data_hora($linha['_data_ultima_atualizacao']) : null;
            $data_de_abertura = $linha['data_abertura'] ? trata_data($linha['data_abertura']) : null;
            $data_analise = $linha['data_analise'] ? trata_data_hora($linha['data_analise']) : null;

            // Status
            $status = null;
            if ($linha['status_final'] == 'deferido') $status = 'Deferido';
            if ($linha['status_final'] == 'deferido_parcialmente') $status = 'Deferido Parcialmente';
            if ($linha['status_final'] == 'indeferido') $status = 'Indeferido';

            // Para avaliador
            $para_avaliador = null;
            if ($linha['para_avaliador'] == '1') $para_avaliador = 'Sim';
            if ($linha['para_avaliador'] == '0') $para_avaliador = 'Não';

            // Usuários
            $usuario_ultima_at = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
            $usuario_ultima_at = count($usuario_ultima_at) == 1 ? $usuario_ultima_at[0]['posto_grad'] . ' ' . $usuario_ultima_at[0]['nome_guerra'] : null;

            $usuario_analise = $conexao->get_usuario_id($linha['id_usuario_analise']);
            $usuario_realizou_analise = count($usuario_analise) == 1 ? $usuario_analise[0]['posto_grad'] . ' ' . $usuario_analise[0]['nome_guerra'] : null;

            // Arquivo
            $arquivo_add_candidato_recurso = null;
            if ($linha['arq_nome_arquivo'] != null) {
                $arquivo_add_candidato_recurso = '<a href="arquivos_add_p_cand/recursos/' . $linha['arq_nome_arquivo'] . '" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-file-pdf me-1"></i>Recurso do Candidato
                </a>';
            }
        ?>
            <!-- Card de Recurso Individual -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-file-alt me-2"></i>
                            Recurso Nº <?= $linha['id'] ?>
                        </span>
                        <div class="btn-group">
                            <a href="mpdf/oficio_resposta_recurso.php?id_recurso=<?= $linha['id'] ?>&crip=<?= $crip ?>"
                                target="_blank" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Gerar Ofício">
                                <i class="fa fa-file-pdf"></i>
                            </a>
                            <a onclick="funcao_apagar('<?= $linha['id'] ?>', 'candidato_recurso','<?= $id_usuario ?>')"
                                class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Apagar Recurso">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informações do Recurso -->
                    <div class="row mb-4">
                        <div class="col-md-12 mb-20">
                            <?= $arquivo_add_candidato_recurso ?>
                        </div>

                        <div class="col-md-2 mb-2">
                            <small class="text-muted">Etapa</small>
                            <div class="fw-medium"><?= $etapa ?></div>
                        </div>

                        <?php if ($linha['cidade_isgrec']): ?>
                            <div class="col-md-2 mb-2">
                                <small class="text-muted">Cidade ISGRec</small>
                                <div class="fw-medium"><?= $linha['cidade_isgrec'] ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-2 mb-2">
                            <small class="text-muted">Data de Abertura</small>
                            <div class="fw-medium"><?= $data_de_abertura ?></div>
                        </div>

                        <?php if (isset($_SESSION["eipot"])): ?>
                            <div class="col-md-2 mb-2">
                                <small class="text-muted">Para Avaliador</small>
                                <div class="fw-medium"><?= $para_avaliador ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-2 mb-2">
                            <small class="text-muted">Status</small>
                            <div class="fw-medium"><?= $status ?></div>
                        </div>

                        <div class="col-md-2 mb-2">
                            <small class="text-muted">Especialidade</small>
                            <div class="fw-medium"><?= $linha['nome_especialidade'] ?></div>
                        </div>

                        <?php if ($linha['analise']): ?>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Análise</small>
                                <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($linha['analise'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Formulário de Ofício Resposta -->
                    <form action="../banco_dados/candidato_atualiza_oficio_recurso.php" method="post">
                        <input type="hidden" name="id_recurso" value="<?= $linha['id'] ?>">
                        <input type="hidden" name="obs_etapa" value="<?= $linha['obs_etapa'] ?>">
                        <input type="hidden" name="id_candidato" value="<?= $linha['id_candidato'] ?>">
                        <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">

                        <div class="row">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-20">
                                    <i class="fa fa-edit me-2"></i>Geração de Ofício Resposta
                                </h6>
                            </div>

                            <?php if ($linha['arq_nome_arquivo'] != null && !isset($_SESSION["eipot"])): ?>
                                <div class="col-md-3 mb-20">
                                    <label class="form-label fw-semibold">Enviar para especialista</label>
                                    <select name="especialidade_recurso" class="form-control">
                                        <option value="">Não enviar</option>
                                        <?php foreach ($lista_especialidades as $especialidade): ?>
                                            <option value="<?= $especialidade['id_especialidade'] ?>"
                                                <?= ($linha['id_especialidade'] == $especialidade['id_especialidade']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($especialidade['especialidade']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-3 mb-20">
                                <label class="form-label fw-semibold">Status Final</label>
                                <select name="status_final" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="deferido" <?= ($linha['status_final'] == "deferido") ? 'selected' : '' ?>>Deferido</option>
                                    <option value="deferido_parcialmente" <?= ($linha['status_final'] == "deferido_parcialmente") ? 'selected' : '' ?> <?= ($linha['obs_etapa'] == '3 - IS') ? 'hidden' : '' ?>>Deferido Parcialmente</option>
                                    <option value="indeferido" <?= ($linha['status_final'] == "indeferido") ? 'selected' : '' ?>>Indeferido</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-20">
                                <label class="form-label fw-semibold">Cidade e Data</label>
                                <input name="cidade_dt" value="<?= $linha['cidade_data'] ?>" class="form-control">
                            </div>

                            <div class="col-md-3 mb-20 <?= (($linha['obs_etapa'] == '3 - IS' && $_SESSION['perfil'] != 'admin') ? 'd-none' : '') ?>">
                                <label class="form-label fw-semibold">Presidente da Comissão</label>
                                <input name="presidente" value="<?= $linha['presidente'] ?>" class="form-control">
                            </div>

                            <div class="col-md-6 mb-20">
                                <label class="form-label fw-semibold">Parágrafo 1</label>
                                <textarea name="paragrafo1" class="form-control" rows="3"><?= htmlspecialchars($linha['paragrafo1']) ?></textarea>
                            </div>

                            <div class="col-md-6 mb-20">
                                <label class="form-label fw-semibold">Parágrafo 2</label>
                                <textarea name="paragrafo2" class="form-control" rows="3"><?= htmlspecialchars($linha['paragrafo2']) ?></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save me-2"></i>
                                    Salvar Ofício
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Rodapé com informações de auditoria -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fa fa-history me-1"></i>
                                Última atualização em <?= $ultima_atualizacao ?> por
                                <a href="usuario_visualiza.php?id_usuario=<?= $linha['_usuario_ultima_atualizacao'] ?>" class="text-decoration-none">
                                    <?= $usuario_ultima_at ?>
                                </a>
                            </small>
                        </div>
                        <?php if ($usuario_realizou_analise): ?>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fa fa-user-check me-1"></i>
                                    Análise realizada em <?= $data_analise ?> por
                                    <a href="usuario_visualiza.php?id_usuario=<?= $linha['id_usuario_analise'] ?>" class="text-decoration-none">
                                        <?= $usuario_realizou_analise ?>
                                    </a>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>