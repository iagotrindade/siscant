<a name="recursos"></a>
<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->

<div class="card dashboard-card mb-20" <?= ($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin' || isset($_SESSION['eipot']) == 1) ? 'hidden' : '' ?>>
    <div class="card-header dashboard-header mb-20">
        <span class="card-title mb-0">
            <i class="fa fa-search"></i>
            Análise de Recurso - Avaliador
        </span>
    </div>
    <div class="card-body">
        <?php
        $especialidades_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
        $lista_recursos = $conexao->get_recursos_candidato($id_usuario);

        foreach ($lista_recursos as $linha):
            // Filtro por especialidades do avaliador
            $aparece = true;
            if ($especialidades_avaliador != null) {
                if ($linha['para_avaliador'] == '0') continue;
                $aparece = false;
                foreach ($especialidades_avaliador as $especialidade) {
                    if ($linha['id_especialidade'] == $especialidade['id_especialidade'])
                        $aparece = true;
                }
            }
            if ($aparece == false) continue;

            $crip = hash('sha256', $linha['id']);

            // Processamento de dados
            $ultima_atualizacao = $linha['_data_ultima_atualizacao'] ? trata_data_hora($linha['_data_ultima_atualizacao']) : null;
            $data_de_abertura = $linha['data_abertura'] ? trata_data($linha['data_abertura']) : null;
            $data_analise = $linha['data_analise'] ? trata_data_hora($linha['data_analise']) : null;

            // Status
            $status = null;
            $status_class = 'primary';
            if ($linha['status'] == 'deferido') {
                $status = 'Deferido';
                $status_class = 'primary';
            }
            if ($linha['status'] == 'deferido_parcialmente') {
                $status = 'Deferido Parcialmente';
                $status_class = 'warning';
            }
            if ($linha['status'] == 'indeferido') {
                $status = 'Indeferido';
                $status_class = 'danger';
            }

            // Para avaliador
            $para_avaliador = null;
            $para_avaliador_class = 'primary';
            if ($linha['para_avaliador'] == '1') {
                $para_avaliador = 'Sim';
                $para_avaliador_class = 'primary';
            }
            if ($linha['para_avaliador'] == '0') {
                $para_avaliador = 'Não';
                $para_avaliador_class = 'danger';
            }

            // Arquivo do recurso
            $arquivo_add_candidato_recurso = null;
            if ($linha['arq_nome_arquivo'] != null) {
                $arquivo_add_candidato_recurso = '<a href="arquivos_add_p_cand/recursos/' . $linha['arq_nome_arquivo'] . '" target="_blank" class="btn btn-sm btn-success mr-10">
                    <i class="fa fa-file-pdf-o me-1"></i> Recurso do Candidato
                </a>';
            }
        ?>
            <!-- Card de Análise Individual -->
            <div class="card border-info">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center mb-20">
                    <span class="fw-semibold">
                        <i class="fa fa-file-pdf-o"></i>
                        Recurso Nº <?= $linha['id'] ?>
                    </span>
                    <?= $arquivo_add_candidato_recurso ?>
                </div>
                <div class="card-body">
                    <!-- Informações do Recurso -->
                    <div class="row mb-20">
                        <div class="col-md-3 mb-10">
                            <small class="text-muted">Etapa</small>
                            <div class="fw-medium"><?= $linha['etapa'] ?></div>
                        </div>

                        <div class="col-md-3 mb-10">
                            <small class="text-muted">Data de Abertura</small>
                            <div class="fw-medium"><?= $data_de_abertura ?></div>
                        </div>

                        <div class="col-md-3 mb-10">
                            <small class="text-muted">Especialidade</small>
                            <div class="fw-medium"><?= $linha['nome_especialidade'] ?></div>
                        </div>

                        <div class="col-md-3 mb-10">
                            <small class="text-muted">Parecer do Avaliador</small>
                            <div>
                                <?php if ($status): ?>
                                    <span class="badge bg-<?= $status_class ?>"><?= $status ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pendente</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($linha['analise']): ?>
                            <div class="col-md-12">
                                <small class="text-muted">Análise do Avaliador</small>
                                <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($linha['analise'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Formulário de Análise do Avaliador -->
                    <?php if (!$status || $status == ''): ?>
                        <form action="../banco_dados/avaliador_analisa_recurso.php" method="post">
                            <input type="hidden" name="id_recurso" value="<?= $linha['id'] ?>">
                            <input type="hidden" name="id_candidato" value="<?= $linha['id_candidato'] ?>">
                            <input type="hidden" name="cpf_candidato" value="<?= $cpf ?>">
                            <input type="hidden" name="crip" value="<?= $crip ?>">

                            <div class="row">
                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-gavel me-1"></i>
                                        Status Final
                                    </label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Selecione o status</option>
                                        <option value="deferido">Deferido</option>
                                        <option value="deferido_parcialmente">Deferido Parcialmente</option>
                                        <option value="indeferido">Indeferido</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-20">
                                    <label class="form-label fw-semibold">
                                        <i class="fa fa-edit me-1"></i>
                                        Análise Detalhada
                                    </label>
                                    <textarea name="analise" class="form-control" rows="4" placeholder="Digite sua análise detalhada sobre o recurso..." required><?= htmlspecialchars($linha['paragrafo2']) ?></textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <strong>ATENÇÃO:</strong> Após enviar a análise, não será possível efetuar alteração!
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-paper-plane"></i>
                                        Enviar Análise
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            Esta análise já foi concluída e não pode ser alterada.
                        </div>
                    <?php endif; ?>

                    <!-- Informações de Auditoria -->
                    <?php if ($ultima_atualizacao): ?>
                        <div class="row mt-20 pt-3 border-top">
                            <div class="col-md-12">
                                <small class="text-muted">
                                    <i class="fa fa-history me-1"></i>
                                    Última atualização em <?= $ultima_atualizacao ?>
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($lista_recursos)): ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                Nenhum recurso disponível para análise nas suas especialidades.
            </div>
        <?php endif; ?>
    </div>
</div>