<?php
// 27/08/2025 -> Iago Silva Adicionado página de notificações para envio de mensagens aos candidatos
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$resultado_selecao = $conexao->get_selecao_id();
if (
    $resultado_selecao[0]['codigo'] == 'ott_stt'
    || $resultado_selecao[0]['codigo'] == 'mfdv'
    || $resultado_selecao[0]['codigo'] == 'cet'
    || $resultado_selecao[0]['codigo'] == 'ott'
    || $resultado_selecao[0]['codigo'] == 'stt'
) {
    $_SESSION['eipot'] = 0;
    unset($_SESSION['eipot']);
}

$feedbacks = $conexao->get_feedbacks($_SESSION['selecao']);
$usuarios = $conexao->get_usuarios();
$candidatos = $conexao->get_candidatos();

//Concatenar usuarios com os candidatos
$usuarios = array_merge($usuarios, $candidatos);
$usuarios_banidos = $conexao->get_usuarios_banidos_feedbacks();

//Verifica se o usuário logado está banido
foreach ($usuarios_banidos as $banido) {
    if ($banido['id_usuario'] == $_SESSION['id_usuario']) {
        erro("Você está banido da área de Feedbacks! Motivo: " . $banido['motivo'] . ". O Feedback que motivou esse banimento foi: " . $banido['feedback_titulo'] . " - " . $banido['feedback_descricao']);
        exit();
    }
}
?>

<style>
    .section-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        font-weight: 700;
    }

    .alert-info {
        background-color: #E8F5E9;
        border-color: #C8E6C9;
        color: #2E7D32;
    }

    .feedback-badge {
        font-size: 0.8em;
        padding: 0.5em 0.8em;
    }

    .bg-accepted {
        background-color: #228B22;
        color: white;
    }

    .bg-info {
        background-color: #17a2b8;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .feedback-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        align-items: start;
    }

    .status-column {
        background: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .status-header {
        padding: 15px 20px;
        color: white;
        font-size: 1.5rem;
    }

    .feedback-cards {
        padding: 15px;
        max-height: 600px;
        overflow-y: auto;
    }

    .feedback-card {
        margin-bottom: 15px;
    }

    .feedback-card .card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .feedback-card .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
    }

    .feedback-type-badge {
        font-size: 1.2rem;
        padding: 4px 8px;
        border-radius: 6px;
        border: none;
    }

    .feedback-container {
        position: relative;
    }

    .feedback-description {
        transition: max-height 0.3s ease;
        max-height: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
    }

    .feedback-description.expanded {
        max-height: none !important;
        -webkit-line-clamp: unset !important;
        overflow: visible;
    }

    .show-more-btn {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        padding: 5px 0;
        font-size: 0.9rem;
        display: none;
        align-items: center;
        gap: 5px;
    }

    .show-more-btn:hover {
        text-decoration: underline;
    }

    .show-more-btn .arrow {
        transition: transform 0.3s ease;
        font-size: 0.8em;
    }

    .feedback-actions {
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }

    .like-btn {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 1.3rem;
        transition: all 0.3s ease;
    }

    .like-btn:hover {
        transform: scale(1.05);
        background-color: var(--primary-color);
    }

    .like-btn.liked {
        background-color: var(--primary-color);
        color: white;
        border-color: #007bff;
    }

    .status-select {
        border-radius: 6px;
        font-size: 0.8rem;
        border: 1px solid #ddd;
    }

    .empty-state {
        background: white;
        border-radius: 8px;
        border: 2px dashed #e0e0e0;
    }

    /* Scrollbar personalizada */
    .feedback-cards::-webkit-scrollbar {
        width: 6px;
    }

    .feedback-cards::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .feedback-cards::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .feedback-cards::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>


<div class="">
    <div class="content-wrapper">
        <div class="page-title">
            <div>
                <h1>FeedBacks <i class="fa fa-commenting-o"></i></h1>
            </div>
            <div>
                <ul class="breadcrumb">
                    <li><i class="fa fa-home fa-lg"></i></li>
                    <li><a href="index.php">Página Inicial</a></li>
                    <li>Envio de Notificações</li>
                </ul>
            </div>
        </div>

        <!-- Card de Avisos - Feedback -->
        <div class="card dashboard-card">
            <div class="card-header dashboard-header bg-warning text-dark mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    Regras de uso dos FeedBacks
                </span>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle fa-2x mr-10 text-succses"></i>
                        <div>
                            <h5 class="alert-heading mb-0 text-succses">Espaço Colaborativo</h5>
                            <p class="mb-0">Este é um espaço colaborativo para sugestões e discussões construtivas</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mb-20">
                        <div class="rules-list">
                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> Seja respeitoso e profissional em todas as sugestões e não abuse do sistema de votação
                            </div>

                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> Não use linguagem ofensiva, agressiva ou discriminatória nem faça spam criando sugestões repetidas
                            </div>

                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> Não existem prazos e nem garantia de análise nem implementação das sugestões
                            </div>

                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> O envio de FeedBacks é voluntário e não tem qualquer impacto no Processo Seletivo.
                            </div>

                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> A Comissão Organizadora reserva-se o direito de moderar e excluir feedbacks que não estejam de acordo com as regras.
                            </div>

                            <div class="rule-item mb-10">
                                <i class="fa fa-check-circle text-success"></i> Caso visualize algum feedback inadequado, informe a Comissão de Seleção para que sejam tomadas as devidas providências.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="alert alert-danger border-0">
                            <div class="text-center">
                                <i class="fa fa-ban fa-3x text-danger mb-3"></i>
                                <h5 class="alert-heading text-danger mb-2">AVISO IMPORTANTE</h5>
                                <p class="mb-2">
                                    Qualquer comentário abusivo, spam ou sugestão inadequada resultará em
                                    <strong>BANIMENTO PERMANENTE</strong> da área de Feedbacks desta Seleção.
                                </p>
                                <p class="mb-0 fw-bold text-danger">
                                    NÃO HAVERÁ SEGUNDA CHANCE.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a name="banidos_feedbacks"></a>
        <!-- Card usuários Banidos - Feedback -->
        <?php if ($_SESSION['perfil'] == 'admin') : ?>
            <!-- Card de Banimento -->
            <div class="card dashboard-card">
                <div class="card-header dashboard-header bg-danger text-white mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-user-times me-2"></i>
                        Banir Usuário
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="../banco_dados/feedback_banir.php">
                        <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">

                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <label for="id_feedback" class="form-label fw-semibold">
                                    <i class="fa fa-comment me-1"></i>
                                    Selecione o Feedback
                                </label>
                                <select class="form-control" id="id_feedback" name="id_feedback" required>
                                    <option value="">Selecione o Feedback</option>
                                    <?php foreach ($feedbacks as $feedback) : ?>
                                        <option value="<?= $feedback['id'] ?>">
                                            <?= htmlspecialchars($feedback['titulo']) ?> - <?= htmlspecialchars($feedback['nome_completo']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label for="motivo_banimento" class="form-label fw-semibold">
                                    <i class="fa fa-edit me-1"></i>
                                    Motivo do Banimento (Será mostrado ao usuário banido)
                                </label>
                                <textarea class="form-control" id="motivo_banimento" name="motivo_banimento"
                                    placeholder="Descreva o motivo do banimento..."
                                    rows="4" required></textarea>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-ban me-2"></i>
                                    Banir Usuário
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card de Usuários Banidos -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header bg-dark text-white mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-list me-2"></i>
                        Usuários Banidos
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_banimentos">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-user"></i> Nome do Usuário</th>
                                    <th><i class="fa fa-id-card"></i> CPF</th>
                                    <th><i class="fa fa-edit"></i> Motivo do Banimento</th>
                                    <th><i class="fa fa-calendar"></i> Data do Banimento</th>
                                    <th><i class="fa fa-cogs"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($usuarios_banidos)) : ?>
                                    <?php foreach ($usuarios_banidos as $banido) : ?>
                                        <tr>
                                            <td>
                                                <span class="fw-medium text-dark"><?= htmlspecialchars($banido['nome_completo']) ?></span>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($banido['cpf']) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($banido['motivo']) ?>
                                            </td>
                                            <td>
                                                <?= trata_data_hora($banido['criado_em']) ?>
                                            </td>

                                            <td>
                                                <a href="../banco_dados/feedback_banir_apagar.php?id_feedback_ban=<?= $banido['id'] ?>&usuario_id=<?= $banido['id_usuario'] ?>&criptografia=<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>"
                                                    class="btn btn-sm action-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Desbanir Usuário"
                                                    onclick="return confirm('Tem certeza que deseja desbanir este usuário?');">
                                                    <i class="fa fa-undo"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-check-circle fa-2x mb-2 opacity-50"></i><br>
                                                Nenhum usuário banido
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <a name="cadastro_feedbacks"></a>
        <!-- Card de Envio - Feedback -->
        <div class="row">
            <div class="col-xl-6 col-md-7">
                <div class="card mb-20">
                    <div class="card-header mb-20" style="background-color: #006400; color: white;">
                        <span>
                            <i class="fa fa-commenting me-2"></i> Enviar FeedBack
                        </span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="../banco_dados/feedback_cadastra.php">
                            <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

                            <div class="mb-10">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                    placeholder="Ex: Notificações por email quando há novas vagas" required
                                    style="border-radius: 6px; padding: 10px 15px; border: 1px solid #D3D3D3;">
                            </div>

                            <div class="mb-10">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="5"
                                    placeholder="Descreva sua ideia em detalhes..." maxlength="2000" required
                                    style="border-radius: 6px; padding: 10px 15px; border: 1px solid #D3D3D3;"></textarea>
                                <div class="form-text text-end"><span id="charCount">0</span>/2000 caracteres</div>
                            </div>

                            <div class="mb-10">
                                <label for="tipo" class="form-label">Categoria</label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="">Selecione a categoria</option>
                                    <option value="funcionalidade">Funcionalidade</option>
                                    <option value="erro">Erro</option>
                                    <option value="melhoria">Melhoria</option>
                                    <option value="design">Design</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-paper-plane"></i> ENVIAR FEEDBACK
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-5">
                <div class="card mb-20">
                    <div class="card-header mb-20" style="background-color: #006400; color: white;">
                        <span>
                            <i class="fa fa-info-circle me-2"></i> Informações
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <strong>Atenção:</strong> Após o envio, o feedback não poderá ser editado, apenas excluído.
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Feedbacks enviados
                                <span class="badge rounded-pill" style="background-color: #006400;"><?= count($feedbacks) ?></span>
                            </li>
                            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Último envio
                                    <span class="text-muted" id="lastSent"><?= !empty($feedbacks) ? trata_data_hora(end($feedbacks)['criado_em']) : 'Nenhum' ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <a name="feedbacks"></a>
        <div class="row">
            <div class="col-md-12">
                <div class="card col-md-12">
                    <div class="card-header d-flex justify-content-between align-items-center mb-20" style="background: linear-gradient(135deg, #006400 0%, #228B22 100%); color: white; border: none;">
                        <span class="fw-bold">
                            <i class="fa fa-comments me-2"></i> Feedbacks dos Usuários
                        </span>
                        <button class="btn btn-sm btn-outline-light" id="toggleCards" style="background: transparent !important;">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="feedbackList" class="feedback-grid">

                            <?php
                            //Remove feedbacks de usuários banidos de forma eficiente
                            $ids_banidos = array_column($usuarios_banidos, 'id_usuario');

                            $feedbacks = array_filter($feedbacks, function ($feedback) use ($ids_banidos) {
                                return !in_array($feedback['id_usuario'], $ids_banidos);
                            });
                            // Organizar feedbacks por status
                            $feedbacks_por_status = [
                                'analise' => [],
                                'rejeitado' => [],
                                'aceito' => [],
                                'desenvolvimento' => [],
                                'concluido' => []
                            ];

                            foreach ($feedbacks as $feedback) {
                                if (isset($feedbacks_por_status[$feedback['status']])) {
                                    $feedbacks_por_status[$feedback['status']][] = $feedback;
                                }
                            }

                            // Configurações para cada status
                            $status_config = [
                                'analise' => [
                                    'badge_class' => 'bg-warning',
                                    'icon' => 'fa-clock-o',
                                    'label' => 'Em análise',
                                    'card_header' => 'linear-gradient(135deg, #FFA500 0%, #FFB74D 100%)'
                                ],
                                'rejeitado' => [
                                    'badge_class' => 'bg-danger',
                                    'icon' => 'fa-ban',
                                    'label' => 'Rejeitado',
                                    'card_header' => 'linear-gradient(135deg, #DC3545 0%, #E57373 100%)'
                                ],
                                'aceito' => [
                                    'badge_class' => 'bg-success',
                                    'icon' => 'fa-check',
                                    'label' => 'Aceito',
                                    'card_header' => 'linear-gradient(135deg, #28A745 0%, #66BB6A 100%)'
                                ],
                                'desenvolvimento' => [
                                    'badge_class' => 'bg-info',
                                    'icon' => 'fa-code',
                                    'label' => 'Em desenvolvimento',
                                    'card_header' => 'linear-gradient(135deg, #17A2B8 0%, #4FC3F7 100%)'
                                ],
                                'concluido' => [
                                    'badge_class' => 'bg-primary',
                                    'icon' => 'fa-check-square-o',
                                    'label' => 'Concluído',
                                    'card_header' => 'linear-gradient(135deg, #007BFF 0%, #64B5F6 100%)'
                                ]
                            ];

                            // Cores para tipos de feedback
                            $cores_tipo = [
                                'funcionalidade' => 'background: linear-gradient(135deg, #104e64 0%, #1976D2 100%); color: white;',
                                'erro' => 'background: linear-gradient(135deg, #82181a 0%, #D32F2F 100%); color: white;',
                                'melhoria' => 'background: linear-gradient(135deg, #733e0a 0%, #FF9800 100%); color: white;',
                                'design' => 'background: linear-gradient(135deg, #59168b 0%, #7B1FA2 100%); color: white;',
                                'outro' => 'background: linear-gradient(135deg, #1c398e 0%, #303F9F 100%); color: white;'
                            ];

                            // Gerar cada seção de status
                            foreach ($status_config as $status => $config):
                                $feedbacks_status = $feedbacks_por_status[$status];
                            ?>
                                <div class="status-column">
                                    <div class="status-header" style="background: <?= $config['card_header'] ?>;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="">
                                                <i class="fa <?= $config['icon'] ?> text-white"></i> <?= $config['label'] ?>
                                            </div>
                                            <span class="badge bg-light text-dark"><?= count($feedbacks_status) ?></span>
                                        </div>
                                    </div>

                                    <div class="feedback-cards">
                                        <?php if (empty($feedbacks_status)) : ?>
                                            <div class="empty-state text-center p-4">
                                                <i class="fa fa-comments fa-3x mb-3" style="color: #E0E0E0;"></i>
                                                <h6 class="text-muted mb-2">Nenhum feedback</h6>
                                                <small class="text-muted">Os feedbacks aparecerão aqui</small>
                                            </div>
                                        <?php endif; ?>

                                        <?php foreach ($feedbacks_status as $feedback) :
                                            $classBadge = $cores_tipo[$feedback['tipo']] ?? '';
                                            if (empty($classBadge)) continue;

                                            if ($_SESSION['perfil'] != 'admin' && $feedback['perfil'] == 'admin') continue;

                                            $userLiked = false;

                                            if (!empty($feedback['likes']) && is_array($feedback['likes'])) {
                                                foreach ($feedback['likes'] as $like) {
                                                    if (isset($like['id_usuario']) && $like['id_usuario'] == $_SESSION['id_usuario']) {
                                                        $userLiked = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            $likeCount = $feedback['votos'] ?? 0;
                                        ?>
                                            <div class="feedback-card">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-header border-0 d-flex justify-content-between p-0 mb-10" style="background: none; color: #333; align-items: start;">
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-0"><?= $feedback['titulo'] ?></h5>
                                                            <span class="badge feedback-type-badge" style="<?= $classBadge ?>">
                                                                <?= $feedback['tipo'] == null ? 'TODAS' : mb_strtoupper($feedback['tipo']) ?>
                                                            </span>
                                                        </div>
                                                        <?php if ($feedback['id_usuario'] == $_SESSION['id_usuario'] || $_SESSION['perfil'] == 'admin') : ?>
                                                            <a
                                                                class="btn btn-sm action-btn"
                                                                onclick="funcao_apagar('<?= $feedback['id'] ?>', 'feedback')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="feedback-container">
                                                            <p class="card-text feedback-description mb-0">
                                                                <?= nl2br(htmlspecialchars($feedback['descricao'])) ?>
                                                            </p>
                                                            <button class="show-more-btn">
                                                                <span class="arrow">▼</span> Ver mais
                                                            </button>
                                                        </div>

                                                        <div class="feedback-actions">
                                                            <form method="post" action="../banco_dados/feedback_like.php" class="d-inline">
                                                                <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">
                                                                <input type="hidden" name="id_feedback" value="<?= $feedback['id'] ?>">
                                                                <input type="hidden" name="action" value="<?= $userLiked ? 'dislike' : 'like' ?>">
                                                                <button class="btn btn-sm btn-outline-primary like-btn <?= $userLiked ? 'liked' : '' ?>"
                                                                    data-feedback-id="<?= $feedback['id'] ?>">
                                                                    <i class="fa fa-thumbs-up me-1"></i>
                                                                    <span class="like-count"><?= $likeCount ?></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="card-footer bg-transparent mb-0" style="border: none;">
                                                        <div class="d-flex flex-column justify-content-between align-items-center mb-10">
                                                            <?php if ($_SESSION['perfil'] == 'admin'): ?>
                                                                <div class="w-100 mb-10">
                                                                    <small class="text-muted p-0">
                                                                        <i class="fa fa-user me-1"></i>
                                                                        <?= $feedback['nome_completo'] ?>
                                                                    </small>

                                                                    <br>

                                                                    <small class="text-muted p-0">
                                                                        <i class="fa fa-clock-o me-1"></i>
                                                                        <?= trata_data_hora($feedback['criado_em']) ?>
                                                                    </small>
                                                                </div>

                                                                <form action="../banco_dados/feedback_atualiza_status.php" method="post" class="mt-2 w-100">
                                                                    <input type="hidden" name="criptografia" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">
                                                                    <input type="hidden" name="id_feedback" value="<?= $feedback['id'] ?>">
                                                                    <select name="status" onchange="this.form.submit()" class="form-select form-control mb-10">
                                                                        <option value="analise" <?= $feedback['status'] == 'analise' ? 'selected' : '' ?>>Em análise</option>
                                                                        <option value="rejeitado" <?= $feedback['status'] == 'rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
                                                                        <option value="aceito" <?= $feedback['status'] == 'aceito' ? 'selected' : '' ?>>Aceito</option>
                                                                        <option value="desenvolvimento" <?= $feedback['status'] == 'desenvolvimento' ? 'selected' : '' ?>>Em desenvolvimento</option>
                                                                        <option value="concluido" <?= $feedback['status'] == 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                                                    </select>

                                                                    <textarea name="resposta" class="form-control" placeholder="Resposta (Opcional)" rows="2"><?= $feedback['resposta'] ?></textarea>
                                                                </form>
                                                            <?php endif; ?>

                                                            <?php if ($_SESSION['perfil'] != 'admin'): ?>
                                                                <?php if (!empty($feedback['resposta'])): ?>
                                                                    <div class="w-100">
                                                                        <strong>Resposta da Comissão:</strong>
                                                                        <p class="text-muted"><?= nl2br(htmlspecialchars($feedback['resposta'])) ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/plugins/select2.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script>
    // Contador de caracteres
    document.getElementById('descricao').addEventListener('input', function() {
        const charCount = this.value.length;
        document.getElementById('charCount').textContent = charCount;
    });

    // Alternar visibilidade das notificações
    document.getElementById('toggleCards').addEventListener('click', function() {
        const feedbackList = document.getElementById('feedbackList');
        const icon = this.querySelector('i');

        if (feedbackList.style.display === 'none') {
            feedbackList.style.display = 'grid';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            feedbackList.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const descriptionMessage = document.getElementById('descricao');
        const charCount = document.getElementById('charCount');
        const feedbackList = document.getElementById('feedbackList');
        const toggleCards = document.getElementById('toggleCards');

        // Contador de caracteres
        descriptionMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Alternar visualização da lista de notificações
        toggleCards.addEventListener('click', function() {
            const feedbacksCardBody = feedbackList.parentElement;
            if (feedbacksCardBody.style.display === 'none') {
                feedbacksCardBody.style.display = 'block';
                toggleCards.innerHTML = '<i class="fa fa-chevron-down"></i>';
            } else {
                feedbacksCardBody.style.display = 'none';
                toggleCards.innerHTML = '<i class="fa fa-chevron-up"></i>';
            }
        });

        const MIN_CHARACTERS = 200; // Define o mínimo de caracteres para mostrar botão

        document.querySelectorAll('.feedback-description').forEach(description => {
            const button = description.nextElementSibling;
            const fullText = description.dataset.text || description.textContent;

            console.log(fullText.length > MIN_CHARACTERS);
            const arrow = button.querySelector('.arrow');

            // Verifica se o texto tem mais caracteres que o mínimo
            if (fullText.length > MIN_CHARACTERS) {
                button.style.display = 'block'; // Mostra o botão

                button.addEventListener('click', function() {
                    if (description.style.maxHeight && description.style.maxHeight !== '245px') {
                        // Recolher
                        description.style.maxHeight = '245px';
                        description.style.webkitLineClamp = '5';
                        description.style.overflow = 'hidden';
                        arrow.textContent = '▼';
                        button.innerHTML = '<span class="arrow">▼</span> Ver mais';
                    } else {
                        // Expandir
                        description.style.maxHeight = description.scrollHeight + '20px';
                        description.style.webkitLineClamp = 'unset';
                        description.style.overflow = 'visible';
                        arrow.textContent = '▲';
                        button.innerHTML = '<span class="arrow">▲</span> Ver menos';
                    }
                });
            }
        });


        $('#tabela_banimentos').DataTable();
    });
</script>

</div>
</body>

</html>
<?php
$conexao = null;
?>