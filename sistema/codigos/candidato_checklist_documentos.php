<!-- Checklist de Documentos -->
<?php if ($_SESSION['perfil'] == 'admin' || $avaliador_pode_avaliar_id_especialidade): ?>
    <a name="checklist_documentos"></a>
    <style>
        .checklist-item {
            transition: all 0.3s ease;
        }

        .checklist-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .checklist_checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-switch .checklist_checkbox {
            width: 3em;
            height: 1.5em;
        }

        .inscricao-container {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e9ecef;
        }

        .inscricao-container:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
    </style>

    <div class="card dashboard-card mb-4">
        <div class="card-header dashboard-header bg-primary text-white mb-20">
            <span class="card-title mb-0">
                <i class="fa fa-clipboard me-2"></i>
                Anexo "C" Conferência Presencial Documentos Inseridos na Inscrição
            </span>
        </div>
        <div class="accordion" id="accordionChecklist">
            <h3 class="accordion-header">
                <a data-toggle="collapse" href="#checklist">
                    <i class="fa fa-eye me-2"></i>
                    Mostrar/Esconder Anexo "C"
                </a>
            </h3>
            <div id="checklist" class="accordion-collapse collapse" data-bs-parent="#checklist">
                <div class="card-body">
                    <?php foreach ($inscricoes as $inscricao) : ?>
                        <?php if ($_SESSION['perfil'] == 'admin' || $avaliador_pode_avaliar_id_especialidade): ?>
                            <?php
                            // Buscar TODOS os documentos do checklist para esta especialidade
                            $checklist_completo = $conexao->get_checklist_por_especialidade($id_usuario, $inscricao['id_especialidade']);

                            // Calcular percentual para esta especialidade específica
                            $total_documentos = 0;
                            $documentos_entregues = 0;

                            // Preparar array para fácil consulta
                            $checklist_map = [];
                            foreach ($checklist_completo as $item) {
                                $checklist_map[$item['tipo_documento']][$item['id_documento']] = $item['status'];
                                $total_documentos++;
                                if ($item['status'] == 1) {
                                    $documentos_entregues++;
                                }
                            }

                            $percentual_entregue = $total_documentos > 0 ? round(($documentos_entregues / $total_documentos) * 100) : 0;
                            ?>

                            <!-- Container específico para cada inscrição -->
                            <div class="inscricao-container" data-inscricao-id="<?= $inscricao['id_especialidade'] ?>">
                                <!-- Informações do Candidato -->
                                <div class="mb-20">
                                    <div class="alert alert-info">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="text-info mt-0">
                                                    <i class="fa fa-user me-2"></i>
                                                    <?= htmlspecialchars($nome_completo) ?>
                                                </h5>
                                                <p class="mb-0 text-muted">
                                                    CPF: <?= mascara($cpf, '###.###.###-##') ?> |
                                                    Especialidade: <?= htmlspecialchars(mb_strtoupper($inscricao['ott_stt']) . ' - ' . $inscricao['especialidade']) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="badge bg-<?= $percentual_entregue >= 100 ? 'primary' : ($percentual_entregue >= 50 ? 'warning' : 'danger') ?> fs-6">
                                                    <?= $percentual_entregue ?>% Entregue
                                                </span>
                                            </div>

                                            <div class="col-md-2">
                                                <a href="mpdf/relatorio_checklist_candidato.php?id_especialidade=<?= $inscricao['id_especialidade'] ?>&id_candidato=<?= $id_usuario ?>" target="_blank" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Ver relatório completo">
                                                    <i class="fa fa-file-pdf-o me-1"></i> GERAR RELATÓRIO
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="../banco_dados/atualizar_checklist_documentos.php" method="POST">
                                        <!-- Inputs Ocultos para Dados Fixos -->
                                        <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                                        <input type="hidden" name="id_candidato" value="<?= $id_usuario ?>">
                                        <input type="hidden" name="id_especialidade" value="<?= $inscricao['id_especialidade'] ?>">
                                        <input type="hidden" name="id_usuario_avaliador" value="<?= $_SESSION['id_usuario'] ?>">

                                        <div class="row">
                                            <!-- Documentos Pessoais -->
                                            <div class="col-lg-6">
                                                <h4 class="fw-semibold border-bottom pb-2 mb-3">
                                                    <i class="fa fa-id-card me-2 text-primary"></i>
                                                    Documentos Obrigatórios
                                                </h4>

                                                <div class="checklist-group">
                                                    <?php
                                                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($id_usuario);
                                                    ?>
                                                    <?php foreach ($lista_docs_obrigatorios as $documento) : ?>
                                                        <?php
                                                        $entregue = isset($checklist_map['obrigatorio'][$documento['id_documentacao_obrigatoria']]) && $checklist_map['obrigatorio'][$documento['id_documentacao_obrigatoria']] == 1;
                                                        $id_checklist_existente = null;

                                                        foreach ($checklist_completo as $item) {
                                                            if ($item['tipo_documento'] == 'obrigatorio' && $item['id_documento'] == $documento['id_documentacao_obrigatoria']) {
                                                                $id_checklist_existente = $item['id'];
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <div class="checklist-item card border-0 shadow-sm mb-2">
                                                            <div class="card-body py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input checklist_checkbox"
                                                                        type="checkbox"
                                                                        id="doc_<?= $inscricao['id_especialidade'] ?>_<?= $documento['id_documentacao_obrigatoria'] ?>"
                                                                        name="checklist[<?= $inscricao['id_especialidade'] ?>][obrigatorio][<?= $documento['id_documentacao_obrigatoria'] ?>][status]"
                                                                        value="1"
                                                                        <?= $entregue ? 'checked' : '' ?>>

                                                                    <!-- Inputs ocultos para metadados -->
                                                                    <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][obrigatorio][<?= $documento['id_documentacao_obrigatoria'] ?>][id_documento]" value="<?= $documento['id_documentacao_obrigatoria'] ?>">
                                                                    <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][obrigatorio][<?= $documento['id_documentacao_obrigatoria'] ?>][tipo_documento]" value="obrigatorio">
                                                                    <?php if ($id_checklist_existente): ?>
                                                                        <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][obrigatorio][<?= $documento['id_documentacao_obrigatoria'] ?>][id_checklist]" value="<?= $id_checklist_existente ?>">
                                                                    <?php endif; ?>

                                                                    <label class="form-check-label fw-medium" for="doc_<?= $inscricao['id_especialidade'] ?>_<?= $documento['id_documentacao_obrigatoria'] ?>">
                                                                        <i class="fa fa-address-card me-2 text-muted"></i>
                                                                        <?= $documento['label'] ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- Documentos Curriculares -->
                                            <div class="col-lg-6">
                                                <h4 class="fw-semibold border-bottom pb-2 mb-3">
                                                    <i class="fa fa-graduation-cap me-2 text-success"></i>
                                                    Documentos Curriculares
                                                </h4>

                                                <div class="checklist-group">
                                                    <?php
                                                    $lista_curriculo_adicionado = $conexao->get_curriculos_inseridos_candidato($id_usuario, $inscricao['id_especialidade']);
                                                    ?>
                                                    <?php foreach ($lista_curriculo_adicionado as $curriculo) : ?>
                                                        <?php
                                                        $entregue = isset($checklist_map['curricular'][$curriculo['id_curriculo']]) && $checklist_map['curricular'][$curriculo['id_curriculo']] == 1;
                                                        $id_checklist_existente = null;

                                                        foreach ($checklist_completo as $item) {
                                                            if ($item['tipo_documento'] == 'curricular' && $item['id_documento'] == $curriculo['id_curriculo']) {
                                                                $id_checklist_existente = $item['id'];
                                                                break;
                                                            }
                                                        }
                                                        ?>

                                                        <div class="checklist-item card border-0 shadow-sm mb-2">
                                                            <div class="card-body py-3">
                                                                <div class="form-check form-switch">
                                                                    <!-- NÃO tem o input hidden problemático - está correto -->
                                                                    <input class="form-check-input checklist_checkbox"
                                                                        type="checkbox"
                                                                        id="cur_<?= $inscricao['id_especialidade'] ?>_<?= $curriculo['id_curriculo'] ?>"
                                                                        name="checklist[<?= $inscricao['id_especialidade'] ?>][curricular][<?= $curriculo['id_curriculo'] ?>][status]"
                                                                        value="1"
                                                                        <?= $entregue ? 'checked' : '' ?>>

                                                                    <!-- Inputs ocultos para metadados -->
                                                                    <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][curricular][<?= $curriculo['id_curriculo'] ?>][id_documento]" value="<?= $curriculo['id_curriculo'] ?>">
                                                                    <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][curricular][<?= $curriculo['id_curriculo'] ?>][tipo_documento]" value="curricular">
                                                                    <?php if ($id_checklist_existente): ?>
                                                                        <input type="hidden" name="checklist[<?= $inscricao['id_especialidade'] ?>][curricular][<?= $curriculo['id_curriculo'] ?>][id_checklist]" value="<?= $id_checklist_existente ?>">
                                                                    <?php endif; ?>

                                                                    <label class="form-check-label fw-medium" for="cur_<?= $inscricao['id_especialidade'] ?>_<?= $curriculo['id_curriculo'] ?>">
                                                                        <i class="fa fa-graduation-cap me-2 text-muted"></i>
                                                                        <?= $curriculo['nome_curriculo'] ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isset($checklist_completo[0]['data_entrega'])): ?>
                                            <div class="row mt-20">
                                                <div class="col-md-12">
                                                    <p class="text-muted mb-20">

                                                        <i class="fa fa-calendar me-1"></i>
                                                        Análise realizada em <?= trata_data_hora($checklist_completo[0]['data_entrega']) ?> por <?= $checklist_completo[0]['avaliador_posto_grad'] . ' - ' . $checklist_completo[0]['avaliador_nome_guerra'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Botões de Ação -->
                                        <div class="row mt-20">
                                            <div class="col-md-12">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <button type="button" class="btn btn-secondary mr-20"
                                                        onclick="marcarTodos(<?= $inscricao['id_especialidade'] ?>, false)">
                                                        <i class="fa fa-times me-2"></i>
                                                        Desmarcar Todos
                                                    </button>
                                                    <button type="button" class="btn btn-success mr-20"
                                                        onclick="marcarTodos(<?= $inscricao['id_especialidade'] ?>, true)">
                                                        <i class="fa fa-check me-2"></i>
                                                        Marcar Todos
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-save me-2"></i>
                                                        Salvar Checklist
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para o Checklist -->
    <script>
        // Função para atualizar o status de uma inscrição específica
        function atualizarStatus(inscricaoId, documentoId, entregue) {
            // Encontrar o container específico da inscrição
            const inscricaoContainer = document.querySelector(`[data-inscricao-id="${inscricaoId}"]`);
            if (!inscricaoContainer) return;

            // Encontrar elementos dentro desta inscrição específica
            const badge = inscricaoContainer.querySelector('.badge.fs-6');
            const checkboxes = inscricaoContainer.querySelectorAll('.checklist_checkbox:checked');
            const totalCheckboxes = inscricaoContainer.querySelectorAll('.checklist_checkbox').length;

            if (totalCheckboxes === 0) return;

            const percentual = Math.round((checkboxes.length / totalCheckboxes) * 100);

            // Atualizar badge
            if (badge) {
                badge.textContent = percentual + '% Entregue';

                // Atualizar cor do badge
                if (percentual >= 100) {
                    badge.className = 'badge bg-primary fs-6';
                } else if (percentual >= 50) {
                    badge.className = 'badge bg-warning fs-6';
                } else {
                    badge.className = 'badge bg-danger fs-6';
                }
            }

            console.log(`Inscrição ${inscricaoId} - Documento ${documentoId} ${entregue ? 'marcado' : 'desmarcado'}`);
        }

        // Função para marcar/desmarcar todos os documentos de uma inscrição específica
        function marcarTodos(inscricaoId, marcar) {
            const inscricaoContainer = document.querySelector(`[data-inscricao-id="${inscricaoId}"]`);
            if (!inscricaoContainer) return;

            const checkboxes = inscricaoContainer.querySelectorAll('.checklist_checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = marcar;
            });

            // Atualizar o percentual para esta inscrição
            atualizarStatus(inscricaoId, 'todos', marcar);
        }

        // Inicializar o percentual para cada inscrição ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            // Encontrar todos os containers de inscrição
            const inscricoesContainers = document.querySelectorAll('[data-inscricao-id]');

            inscricoesContainers.forEach(container => {
                const inscricaoId = container.getAttribute('data-inscricao-id');
                const checkboxes = container.querySelectorAll('.checklist_checkbox:checked');
                const totalCheckboxes = container.querySelectorAll('.checklist_checkbox').length;

                if (totalCheckboxes === 0) return;

                const percentual = Math.round((checkboxes.length / totalCheckboxes) * 100);
                const badge = container.querySelector('.badge.fs-6');

                if (badge) {
                    badge.textContent = percentual + '% Entregue';

                    if (percentual >= 100) {
                        badge.className = 'badge bg-primary fs-6';
                    } else if (percentual >= 50) {
                        badge.className = 'badge bg-warning fs-6';
                    } else {
                        badge.className = 'badge bg-danger fs-6';
                    }
                }
            });
        });
    </script>
<?php endif; ?>