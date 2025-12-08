<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 242342!");
    exit();
}
$lista_especialidades = $conexao->get_especialidades_usuario_avaliador($id_usuario);
?>
<div class="row">
    <div class="col-12">
        <!-- Card de Informações do Usuário -->
        <div class="card dashboard-card mb-4">
            <div class="card-header dashboard-header mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-user-circle me-2"></i>
                    Informações do Usuário
                    <?php if ($apagado == 1): ?>
                        <span class="badge bg-danger ms-2">
                            <i class="fa fa-exclamation-triangle me-1"></i>Usuário Apagado
                        </span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tbody>
                            <!-- Linha 1: Nome, CPF, Nome de Guerra e Posto -->
                            <tr>
                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-user text-primary"></i> Nome Completo</label>
                                        <div class="fw-medium text-dark"><?= htmlspecialchars($nome_completo) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-id-card text-primary"></i> CPF</label>
                                        <div class="fw-medium text-dark"><?= mascara($cpf, '###.###.###-##') ?></div>
                                    </div>
                                </td>

                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-shield text-primary"></i> Nome de Guerra</label>
                                        <div class="fw-medium text-dark"><?= htmlspecialchars($nome_guerra) ?></div>
                                    </div>
                                </td>

                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-shield text-primary"></i> Posto/Graduação</label>
                                        <div class="fw-medium text-dark"><?= htmlspecialchars($posto_grad) ?></div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Linha 2: Perfil, Telefone, E-mail e OM -->
                            <tr>
                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-tag text-primary"></i> Perfil</label>
                                        <div>
                                            <span class="badge bg-primary"><?= htmlspecialchars($perfil) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-mobile text-primary"></i> Telefone Celular</label>
                                        <div class="fw-medium text-dark">
                                            <?= htmlspecialchars($tel_celular) ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <label class=""><i class="fa fa-envelope text-primary"></i> E-Mail</label>
                                        <div class="fw-medium text-dark">
                                            <?= htmlspecialchars($mail) ?>
                                        </div>
                                    </div>
                                </td>

                                <td colspan="2">
                                    <div class="info-item">
                                        <label><i class="fa fa-building text-primary"></i> Organização Militar (OM)</label>
                                        <div class="fw-medium text-dark">
                                            </i><?= htmlspecialchars($om_nome) ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Linha 4: Especialidades (se aplicável) -->
                            <?php if (count($lista_especialidades) > 0 && $perfil == "avaliador"): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="info-item">
                                            <label class=""><i class="fa fa-graduation-cap text-primary"></i> Especialidades com autorização para avaliar</label>
                                            <div class="mt-2">
                                                <?php foreach ($lista_especialidades as $linha_especialidade): ?>
                                                    <span class="badge bg-primary mr-10 mb-2">
                                                        <i class="fa fa-check-circle"></i> <?= htmlspecialchars($linha_especialidade['nome']) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <!-- Linha 5: Foto -->
                            <tr>
                                <td colspan="4">
                                    <div class="text-center">
                                        <div class="info-item">
                                            <small class="">Foto do Perfil</small>
                                            <div class="mt-3">
                                                <img src="fotos/<?= $foto_nome ?>"
                                                    class="img-thumbnail rounded-circle"
                                                    width="200"
                                                    height="200"
                                                    alt="Foto de <?= htmlspecialchars($nome_completo) ?>"
                                                    style="object-fit: cover; box-shadow: 0px 0px 10px #006400; border-radius: 10px;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inclusão dos Logs -->
        <?php if ($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta'): ?>
            <div class="mb-4">
                <?php include_once 'codigos/logs_usuario.php'; ?>
            </div>
        <?php endif; ?>

        <!-- Inclusão dos Logs de Acesso ao Candidato -->
        <?php if ($_SESSION['perfil'] == 'admin'): ?>
            <div class="mb-4">
                <?php include_once 'codigos/acesso_candidato_logs.php'; ?>
            </div>
        <?php endif; ?>
    </div>
</div>