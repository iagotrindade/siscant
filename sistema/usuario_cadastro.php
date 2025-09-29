<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 542344654: Página não encontrada");
    exit();
}

?>

<script>
    window.onload = function() {
        $('#especialidades').hide();
    }
</script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>

<script>
    function verifica_perfil() {
        if ($('#perfil').val() == 'avaliador') {
            $('#especialidades').show();
        } else {
            $('#especialidades').hide();
        }
    }
</script>

<style>
    .card-header {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #006400;
        box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #495057;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .alert-info {
        background-color: #e8f4e8;
        border-left: 4px solid #006400;
        color: #0c540c;
    }

    .invalid-feedback {
        display: block;
        font-size: 0.85rem;
    }

    .was-validated .form-control:invalid,
    .was-validated .form-select:invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v2'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .was-validated .form-control:valid,
    .was-validated .form-select:valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* Select2 customização */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 6px;
        min-height: 46px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #006400;
        box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #006400;
        border: 1px solid #005300;
        border-radius: 4px;
        color: white;
        padding: 2px 8px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }

        .form-control,
        .form-select {
            padding: 10px 12px;
        }

        .btn-primary,
        .btn-outline-secondary {
            padding: 10px 20px;
            font-size: 0.9rem;
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        .text-center .btn {
            margin-right: 0 !important;
        }
    }

    /* Melhorias visuais */
    .form-group {
        margin-bottom: 1.5rem;
    }

    #cpf_mensagem {
        font-size: 1.3rem;
        font-weight: 500;
    }

    .text-muted {
        font-size: 0.85rem;
    }

    .fw-bold {
        font-weight: 600;
    }

    .opacity-75 {
        opacity: 0.75;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Cadastra Usuário <i class="fa fa-user-plus"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Cadastra Usuário</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white mb-20">
                    <span class="mb-0"><i class="fa fa-user-plus me-2"></i> Cadastrar Novo Usuário</span>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/usuario_cadastra.php" method="post" onsubmit="return validar_formulario()" class="needs-validation">
                        <div class="row">
                            <!-- Coluna da Esquerda -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Nome completo</label>
                                    <input id="nome" name="nome_completo" maxlength="120"
                                        class="form-control" placeholder="Digite o nome completo" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">CPF</label>
                                    <span id="cpf_mensagem" class="float-end"></span>
                                    <input id="cpf" name="cpf" class="form-control"
                                        placeholder="000.000.000-00" onfocus="limpa_cpf()" onblur="verifica_cpf()" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Nome de guerra</label>
                                    <input id="nome_guerra" name="nome_guerra" maxlength="20"
                                        class="form-control" placeholder="Digite o nome de guerra" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">E-Mail</label>
                                    <input id="mail" maxlength="50" name="mail" type="email"
                                        class="form-control" placeholder="email@exemplo.com" required>
                                </div>
                            </div>

                            <!-- Coluna da Direita -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Posto/Graduação</label>
                                    <select id="posto" name="posto_grad" class="form-control" required>
                                        <option value="" selected disabled>Selecione o Posto/Graduação</option>
                                        <option value="Sd">Soldado</option>
                                        <option value="Cb">Cabo</option>
                                        <option value="3º Sgt">3º Sargento</option>
                                        <option value="2º Sgt">2º Sargento</option>
                                        <option value="1º Sgt">1º Sargento</option>
                                        <option value="ST">Sub Tenente</option>
                                        <option value="Asp">Aspirante</option>
                                        <option value="2º Ten">2º Tenente</option>
                                        <option value="1º Ten">1º Tenente</option>
                                        <option value="Cap">Capitão</option>
                                        <option value="Maj">Major</option>
                                        <option value="TCel">Ten Coronel</option>
                                        <option value="Cel">Coronel</option>
                                        <option value="Gen">General</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Ramal/Telefone</label>
                                    <input maxlength="20" name="telefone" class="form-control"
                                        placeholder="(00) 00000-0000" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Perfil</label>
                                    <select id="perfil" name="perfil" class="form-control" onchange="verifica_perfil()" required>
                                        <option value="" selected disabled>Selecione o Perfil</option>
                                        <option value="admin">Administrador</option>
                                        <option value="consulta">Consulta / Auditor</option>
                                        <option value="ouvidor">Ouvidor</option>
                                        <option value="avaliador">Avaliador de currículo</option>
                                        <option value="documentos">Avaliador de docs obrigatórios</option>
                                        <option value="jise">JISE</option>
                                        <option value="chc">Comissão Heteroidentificação</option>
                                        <option value="cr">Comissão Revisora</option>
                                        <option value="om">Organização Militar (OM)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fw-bold">Organização Militar</label>
                                    <select id="om" name="om" class="select2-container--default" required>
                                        <option value="" selected disabled>Selecione a OM</option>
                                        <?php
                                        $oms = $conexao->get_all_oms();
                                        foreach ($oms as $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['nome'] . ' (' . $value['abreviatura'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Campos em Linha Completa -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Especialidades do avaliador (Somente para perfil Avaliador)</label>
                                    <select class="js-example-basic-multiple form-control" name="especialidades[]" multiple="multiple">
                                        <?php
                                        $resultado = $conexao->get_especialidade();
                                        foreach ($resultado as $value) {
                                            echo '<option value="' . $value['id'] . '">' . mb_strtoupper($value['ott_stt'], "UTF-8") . " - " . $value['nome'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div id="mensagem_erro" class="alert alert-danger" role="alert" style="display: none;">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <span id="mensagem"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="text-center">
                                    <i class="fa fa-key me-2"></i>
                                    <strong>A SENHA DO USUÁRIO SERÁ <u>123@siscant</u></strong>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg me-3">
                                    <i class="fa fa-save me-2"></i> CADASTRAR USUÁRIO
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $('#om').select2({
        width: '100%',
        placeholder: "Selecione a OM",
        allowClear: true,
        height: '200px'
    });
    $('#especialidades').select2();
</script>
</body>

</html>
<?php $conexao = null; ?>