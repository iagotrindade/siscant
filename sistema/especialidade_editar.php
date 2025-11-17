<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 544235654: Página não encontrada");
    exit();
}

$id_especialidade = (int)$_GET['id_especialidade'];

$get_especialidade = $conexao->get_especialidade_id($id_especialidade);

if ($get_especialidade == null) {
    erro("Erro 342354645: Especialidade não encontrado");
    exit();
}

$lista_cidades = $conexao->get_cidades_especialidade($get_especialidade[0]['id']);

$nome_esp = $get_especialidade[0]['nome'];
$ott_stt = $get_especialidade[0]['ott_stt'];
$teste_pratico = $get_especialidade[0]['teste_pratico'];
$nota_av = $get_especialidade[0]['nota_av'];
$musica = $get_especialidade[0]['musica'];

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " As cidades foram atualizadas!"
        },{
                type: "info"
        });
    };
    </script>';
}

?>



<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Especialidades <i class="fa fa-graduation-cap"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Especialidades</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Formulário de Edição de Especialidade (Admin) -->
            <?php if ($_SESSION['perfil'] == "admin"): ?>
                <div class="card dashboard-card mb-4">
                    <div class="card-header dashboard-header mb-20">
                        <span class="card-title mb-0">
                            <i class="fa fa-edit me-2"></i>
                            Editar Especialidade
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="../banco_dados/especialidade_edita.php" method="post" onsubmit="return validar_formulario()">
                            <div class="row">
                                <!-- STT/OTT -->
                                <?php if ($codigo_selecao == 'ott_stt'): ?>
                                    <div class="col-lg-6 mb-20">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold">
                                                <i class="fa fa-code-branch me-1"></i>
                                                Tipo de Especialidade
                                            </label>
                                            <select disabled name="ott_stt" class="form-control">
                                                <option value="">Selecione STT ou OTT</option>
                                                <option <?= $ott_stt == "ott" ? 'selected' : '' ?> value="ott">OTT</option>
                                                <option <?= $ott_stt == "stt" ? 'selected' : '' ?> value="stt">STT</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Nome da Especialidade -->
                                <div class="col-lg-6 mb-20">
                                    <div id="div_nome" class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-tag me-1"></i>
                                            Nome da especialidade
                                        </label>
                                        <input disabled value="<?= htmlspecialchars($nome_esp) ?>" id="nome_especialidade" name="nome_especialidade" maxlength="120" class="form-control">
                                    </div>
                                </div>

                                <!-- Checkboxes -->
                                <div class="col-lg-12 mb-20">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input disabled name="musica" <?= $musica == 1 ? 'checked' : '' ?> type="checkbox" class="form-check-input" id="musica">
                                                <label class="form-check-label fw-semibold" for="musica">
                                                    <i class="fa fa-music me-1"></i>
                                                    Especialidade de MÚSICA
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input disabled name="teste_pratico" <?= $teste_pratico == 1 ? 'checked' : '' ?> type="checkbox" class="form-check-input" id="teste_pratico">
                                                <label class="form-check-label fw-semibold" for="teste_pratico">
                                                    <i class="fa fa-flask me-1"></i>
                                                    Tem teste prático
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input disabled name="teste_pratico" <?= $nota_av== 1 ? 'checked' : '' ?> type="checkbox" class="form-check-input" id="teste_pratico">
                                                <label class="form-check-label fw-semibold" for="teste_pratico">
                                                    <i class="fa fa-percent me-1"></i>
                                                    Considerar NOTA na Avaliação Curricular
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cidades da Especialidade -->
                                <div class="col-lg-12 mb-20">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-map-marker-alt me-1"></i>
                                            Cidades da especialidade
                                        </label>
                                        <select class="js-example-basic-multiple" style="width: 100%" name="cidades[]" multiple>
                                            <?php
                                            $resultado = $conexao->busca_cidades();
                                            foreach ($resultado as $value):
                                                $selected = false;
                                                if (count($lista_cidades) > 0):
                                                    foreach ($lista_cidades as $value_cidade):
                                                        if ($value['id'] == $value_cidade['id']):
                                                            $selected = true;
                                                            break;
                                                        endif;
                                                    endforeach;
                                                endif;
                                            ?>
                                                <option value="<?= $value['id'] ?>" <?= $selected ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($value['nome']) ?> - <?= $value['uf'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Mensagem de Atenção -->
                                <div class="col-lg-12 mb-20">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fa fa-exclamation-triangle me-2"></i>
                                        <strong>Atenção!</strong> A atualização das cidades apaga a quantidade de vagas já cadastradas.
                                    </div>
                                </div>

                                <!-- Campos Hidden -->
                                <input type="hidden" value="<?= $_GET['id_especialidade'] ?>" name="id_especialidade">
                                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['assinatura_sistema']) ?>">

                                <!-- Botão Submit -->
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-save me-2"></i>
                                        ATUALIZAR ESPECIALIDADE
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Formulário de Vagas por Cidade (Admin) -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header dashboard-header mb-20">
                        <span class="card-title mb-0">
                            <i class="fa fa-bar-chart me-2"></i>
                            Gerenciar Vagas por Cidade
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="../banco_dados/quantidade_vagas_cidade_atualiza.php" method="post">
                            <div class="row">
                                <!-- Seleção de Cidade -->
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-city me-1"></i>
                                            Selecione a cidade
                                        </label>
                                        <select style="width: 100%" class="js-example-basic-multiple" name="cidade" required>
                                            <option value="">Selecione a cidade</option>
                                            <?php
                                            $resultado = $conexao->busca_cidades();
                                            foreach ($resultado as $value):
                                                $show_option = false;
                                                if (count($lista_cidades) > 0):
                                                    foreach ($lista_cidades as $value_cidade):
                                                        if ($value['id'] == $value_cidade['id']):
                                                            $show_option = true;
                                                            break;
                                                        endif;
                                                    endforeach;
                                                else:
                                                    $show_option = true;
                                                endif;

                                                if ($show_option):
                                            ?>
                                                    <option value="<?= $value['id'] ?>">
                                                        <?= htmlspecialchars($value['nome']) ?> - <?= $value['uf'] ?>
                                                    </option>
                                            <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Quantidade de Vagas -->
                                <div class="col-lg-6 mb-20">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fa fa-users me-1"></i>
                                            Número de Vagas
                                        </label>
                                        <select name="quantidade_vagas" class="form-control" required>
                                            <?php for ($i = 0; $i <= 100; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Campos Hidden -->
                                <input type="hidden" value="<?= $_GET['id_especialidade'] ?>" name="id_especialidade">
                                <input type="hidden" name="crip" value="<?= hash('sha256', $_SESSION['chave'] . "vagas") ?>">

                                <!-- Botão Submit -->
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-plus-circle me-2"></i>
                                        ATUALIZAR VAGAS
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabela de Cidades e Vagas -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">
                            <i class="fa fa-list me-2"></i>
                            Cidades e Vagas da Especialidade
                        </span>
                        <?php
                        $lista_especialidades = $conexao->get_cidades_especialidade($id_especialidade);
                        ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fa fa-graduation-cap me-1"></i> Especialidade</th>
                                    <th><i class="fa fa-map-marker me-1"></i> Cidade</th>
                                    <th width="120px" class="text-center"><i class="fa fa-users me-1"></i> Nº Vagas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($lista_especialidades) > 0): ?>
                                    <?php foreach ($lista_especialidades as $linha): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-medium"><?= htmlspecialchars($linha['nome_especialidade']) ?></span>
                                            </td>
                                            <td>
                                                <span class="cidade-text"><?= htmlspecialchars($linha['nome']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="vagas-badge badge <?= $linha['numero_vagas'] > 0 ? 'bg-success' : 'bg-secondary' ?> fs-6">
                                                    <?= $linha['numero_vagas'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-info-circle fa-2x mb-20"></i>
                                                <p class="mb-0">Nenhuma cidade cadastrada para esta especialidade.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
</body>

</html>
<?php
$conexao = null;
?>