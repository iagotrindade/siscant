<?php
// 22/11/2025 -> Iago Silva Criação da área de Questionário de Inscrição
$questionario = $conexao->get_respostas_questionario_candidato($id_usuario);
?>
<a name='questionario'></a>
<div <?= $_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr' && $_SESSION['perfil'] != 'consulta' ? 'hidden' : '' ?>>
    <!-- Questionário de Inscrição -->
    <div class="card dashboard-card mb-4">
        <div class="card-header dashboard-header mb-20 d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">
                <i class="fa fa-edit"></i>
                Questionário de Inscrição

                <span class="badge bg-primary me-3">
                    Perguntas: <?= count($questionario) ?>
                </span>
            </span>
            <div class="d-flex align-items-center">
                <a href="mpdf/relatorio_questionario_candidato.php?id=<?= $id_usuario ?>&fase=1"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="tooltip"
                    title="Baixar Questionário em PDF">
                    <i class="fa fa-file-pdf-o me-1"></i> GERAR PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="accordion" id="accordionQuestionario">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <a data-toggle="collapse" href="#questionario">
                            <i class="fa fa-eye me-2"></i>
                            Mostrar/Esconder Questionário de Inscrição
                        </a>
                    </h3>
                    <div id="questionario" class="accordion-collapse collapse" data-bs-parent="#questionario">
                        <div class="accordion-body">
                            <?php $contador = 0; ?>
                            <?php foreach ($questionario as $pergunta): ?>
                                <?php $contador++; ?>

                                <div class="card shadow-sm border-0">
                                    <div class="card-body">
                                        <!-- Cabeçalho da pergunta -->
                                        <div class="d-flex align-items-start">
                                            <span class="justify-content-center align-items-center badge bg-primary rounded-circle mr-10" style="display:flex; width: 32px; height: 32px; font-weight: bold;">
                                                <?= $contador ?>
                                            </span>
                                            <div class="flex-grow-1">
                                                <h4 class="text-dark mb-10 fw-semibold">
                                                    <?= htmlspecialchars($pergunta['texto_pergunta']) ?>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start">
                                            <span class="justify-content-center align-items-center badge bg-primary rounded-circle mr-10" style="display:flex; width: 32px; height: 32px; font-weight: bold;">
                                                R
                                            </span>
                                            <div class="flex-grow-1">
                                                <!-- Resposta -->
                                                <div class="bg-light rounded p-3 mb-3">
                                                    <p class="text-dark mb-0 ms-4">
                                                        <?= htmlspecialchars($pergunta['resposta']) ?>
                                                    </p>
                                                </div>

                                                <!-- Data da resposta -->
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="fa fa-calendar mr-10"></i>
                                                    <small>
                                                        Respondido em <?= trata_data_hora($pergunta['data_resposta']) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Separador apenas entre itens -->
                                <?php if ($contador < count($questionario)): ?>
                                    <div class="my-2"></div>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>