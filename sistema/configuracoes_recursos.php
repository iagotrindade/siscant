<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $perfil != 'consulta') {
    erro("Erro 37345757! Página não encontrada!");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$get_recurso_rm = $conexao->get_recurso_rm($rm_usuario);
$data_inicio_recurso = $get_recurso_rm[0]['data_inicio_recurso'] ?? '';
$data_fim_recurso = $get_recurso_rm[0]['data_fim_recurso'] ?? '';
$data_inicio_recurso = trata_data($data_inicio_recurso);
$data_fim_recurso = trata_data($data_fim_recurso);
$mostrar_recurso  = $get_recurso_rm[0]['mostrar_recurso'] ?? '';
// NÃO SEI COMO ISSO AQUI ESTÁ FUNCIONANDO, MAS NÃO MEXE!!!
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Configuração de Recursos <i class="fa fa-file-text"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Configurações</li>
            </ul>
        </div>
    </div>

    <form name="form_etapa_presencial" action="../banco_dados/candidato_visualiza_recurso.php" method="post" class="modern-form">
        <div class="card settings-card">
            <div class="card-header settings-header mb-20">
                <span class="card-title mb-0">
                    <i class="fa fa-file-text me-2"></i>
                    Configuração de Recursos Digitais
                </span>
            </div>

            <div class="card-body">
                <input type="hidden" name="rm" value="<?= $rm_usuario ?>">

                <!-- Switch de Ativação -->
                <div class="form-group-switch mb-20">
                    <div class="form-check form-switch">
                        <input type="checkbox"
                            class="form-check-input"
                            name="mostrar_recurso"
                            value="1"
                            id="check-recurso"
                            <?= (isset($get_recurso_rm[0]['mostrar_recurso']) && $get_recurso_rm[0]['mostrar_recurso'] == "1") ? 'checked' : '' ?>>
                        <label class="form-check-label" for="check-recurso">
                            <span class="switch-label">Ativar upload de recursos digitais</span>
                            <small class="form-text text-muted">
                                Permite que os candidatos façam upload de recursos digitalmente
                            </small>
                        </label>
                    </div>
                </div>

                <!-- Período do Recurso -->
                <div class="period-section">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="data_inicio_recurso" class="form-label">
                                    Data de Início
                                </label>
                                <input type="text"
                                    id="data_inicio_recurso"
                                    name="data_inicio_recurso"
                                    maxlength="120"
                                    class="form-control date-input"
                                    value="<?= isset($data_inicio_recurso) ? $data_inicio_recurso : '' ?>"
                                    placeholder="DD/MM/AAAA">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="data_fim_recurso" class="form-label">
                                    Data de Término
                                </label>
                                <input type="text"
                                    id="data_fim_recurso"
                                    name="data_fim_recurso"
                                    maxlength="20"
                                    class="form-control date-input"
                                    value="<?= isset($data_fim_recurso) ? $data_fim_recurso : '' ?>"
                                    placeholder="DD/MM/AAAA">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão de Atualização -->
                <div class="form-actions mt-20">
                    <button type="submit" class="btn btn-primary btn-lg w-100 action-button">
                        <i class="fa fa-refresh me-2"></i>
                        ATUALIZAR CONFIGURAÇÕES
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</body>

</html>

</body>

</html>
<?php $conexao = null; ?>