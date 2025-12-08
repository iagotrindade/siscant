<?php
include_once './menu_candidato.php';
include_once './codigos/candidato_campos_cadastra.php';
include_once './codigos/candidato_valida_cadastro.php';

$conexao = new Conexao();

$selecao = $conexao->get_selecao_id(); // Seleção
$libera_suporte_inicial = $selecao[0]['liberacao_suporte_inicial'];
$questionario = $conexao->get_perguntas_questionario($selecao[0]['id']); // Perguntas do questionário de inscrição
?>

<!-- 31/08/2025 - Iago Silva Pequenos ajustes e melhorias no Layout -->
<style>
    :root {
        --primary-color: #006400;
        /* Verde escuro como cor primária */
        --primary-light: #228B22;
        /* Verde floresta mais claro */
        --secondary-color: #228B22;
        --accent-color: #32CD32;
        --light-bg: #f0f8f0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        background-color: var(--light-bg);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-x: hidden;
    }

    .main-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 0;
    }

    .header-bar {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .logo-placeholder img {
        height: 80px;
    }

    .content-wrapper {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 20px;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .section-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
        margin: 0;
    }

    .form-section {
        padding: 25px;
        border-radius: 0 0 10px 10px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #444;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 17px 15px;
        border: 1px solid #ddd;
        transition: var(--transition);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 100, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 100, 0, 0.3);
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    }

    .alert-info {
        background-color: #e8f4e8;
        border: 1px solid #c0e0c0;
        color: #0c540c;
        border-radius: 10px;
    }

    .support-banner {
        background: linear-gradient(135deg, #e8f4e8, #d0e8d0);
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-left: 4px solid var(--primary-color);
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .logo {
        height: 60px;
        transition: var(--transition);
    }

    .logo:hover {
        transform: scale(1.05);
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .floating-label {
        position: relative;
        margin-bottom: 20px;
    }

    .floating-input {
        padding: 18px 15px 8px 15px;
        height: 60px;
    }

    .floating-label label {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        transition: var(--transition);
        pointer-events: none;
        color: #6c757d;
        background-color: white;
        padding: 0 5px;
    }

    .floating-input:focus+label,
    .floating-input:not(:placeholder-shown)+label {
        top: 0;
        transform: translateY(-50%) scale(0.85);
        color: var(--primary-color);
    }

    .custom-checkbox .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .footer {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 15px 0;
        text-align: center;
        margin-top: auto;
    }

    @media (max-width: 768px) {
        .support-banner {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .header-container {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
    }

    .conditional-section {
        display: none;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>

<div class="main-container">
    <!-- Cabeçalho -->
    <div class="header-bar">
        <div class="header-container">
            <div>
                <h2 class="mb-1">SiSCanT - <?= $_SESSION['apresentacao_candidato'] ?></h2>
                <p class="mb-0 opacity-75">Data: <?= trata_data_hora(date('Y/m/d H:m')) ?></p>
            </div>
            <div class="text-md-end">
                <a href="../index.php" class="d-inline-block">
                    <div class="logo-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <img src="imagens/brasao_eb.png" alt="">
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- 03/09/2025 -> Iago Silva Validando se a variável existe -->
            <?php if ($libera_suporte_inicial == 1) : ?>
                <!-- Banner de Suporte -->
                <div class="support-banner">
                    <div>
                        <h4 class="mb-1"><i class="fa fa-question-circle me-2"></i>Dificuldade ao se cadastrar?</h4>
                        <p class="mb-0">Entre em contato com nossa equipe de suporte</p>
                    </div>
                    <a class="btn btn-primary" href="suporte_inicial.php">
                        <i class="fa fa-comments me-2"></i>Solicitar Suporte
                    </a>
                </div>
            <?php endif; ?>

            <form action="../banco_dados/candidato_cadastra.php" method="post" onsubmit="return candidato_valida_cadastro()" class="needs-validation" novalidate>
                <!-- DADOS PESSOAIS -->
                <div class="card mb-4 fade-in">
                    <h5 class="section-header"><i class="fa fa-user me-2"></i>Dados Pessoais</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="check_nome_social" name="check_nome_social" onchange="mostra_nome_social()">
                                    <label class="form-check-label" for="check_nome_social">Possuo nome social</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="nome_completo" name="nome_completo" maxlength="120" class="form-control floating-input" placeholder=" " required>
                                    <label for="nome_completo" class="required-field">Nome completo</label>
                                </div>
                            </div>

                            <div class="col-lg-4 conditional-section" id="div_nome_social">
                                <div class="floating-label">
                                    <input type="text" id="nome_social" name="nome_social" maxlength="120" class="form-control floating-input" placeholder=" ">
                                    <label for="nome_social">Nome social</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="cpf" name="cpf" class="form-control floating-input" placeholder=" " onfocus="limpa_cpf()" onblur="verifica_cpf()" required>
                                    <label for="cpf" class="required-field">CPF</label>
                                </div>
                                <span id="cpf_mensagem" class="form-text text-danger small"></span>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="identidade" name="identidade" maxlength="20" class="form-control floating-input" placeholder=" " required>
                                    <label for="identidade" class="required-field">Identidade (Número/Órgão expedidor)</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="data_nascimento" name="data_nascimento" maxlength="25" class="form-control floating-input" placeholder=" " required>
                                    <label for="data_nascimento" class="required-field">Data de nascimento</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <select id="sexo" name="sexo" class="form-select" onchange="select_sexo()" required>
                                    <option value="" selected disabled>Selecione o sexo</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="nascionalidade" maxlength="30" name="nascionalidade" class="form-control floating-input" placeholder=" " required>
                                    <label for="nascionalidade" class="required-field">Nacionalidade (País)</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="naturalidade" maxlength="30" name="naturalidade" class="form-control floating-input" placeholder=" " required>
                                    <label for="naturalidade" class="required-field">Naturalidade (Cidade/UF)</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <select name="num_dependentes" class="form-select" required>
                                    <option value="" selected disabled>Selecione se tem dependentes</option>
                                    <option value="0">Não possuo dependentes</option>
                                    <option value="1">Possuo 1 dependente</option>
                                    <option value="2">Possuo 2 dependentes</option>
                                    <option value="3">Possuo 3 dependentes</option>
                                    <option value="4">Possuo 4 dependentes</option>
                                    <option value="5">Possuo 5 dependentes</option>
                                    <option value="6">Possuo 6 dependentes</option>
                                    <option value="7">Possuo 7 dependentes</option>
                                    <option value="8">Possuo 8 dependentes</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <select id="estado_civil" name="estado_civil" onchange="mostra_companheiro()" class="form-select" required>
                                    <option value="" selected disabled>Selecione o Estado Civil</option>
                                    <option value="solteiro">Solteiro</option>
                                    <option value="uniao_estavel">União Estável</option>
                                    <option value="casado">Casado</option>
                                    <option value="viuvo">Viúvo</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>




                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="filiacao_mae" maxlength="120" name="filiacao_mae" class="form-control floating-input" placeholder=" " required>
                                    <label for="filiacao_mae" class="required-field">Filiação (Mãe)</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="filiacao_pai" maxlength="120" name="filiacao_pai" class="form-control floating-input" placeholder=" " required>
                                    <label for="filiacao_pai" class="required-field">Filiação (Pai)</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div id="div_companheiro" class="floating-label conditional-section">
                                    <input type="text" id="companheiro" maxlength="85" name="nome_companheiro" class="form-control floating-input" placeholder=" ">
                                    <label for="companheiro">Nome do Companheiro(a)</label>
                                </div>
                            </div>


                            <div class="col-lg-12 mb-3">
                                <select id="autodeclaracao" name="autodeclaracao" onchange="mostra_vaga_reservada('<?php echo ($_SESSION['tipo_selecao'] ?? 'ott_stt'); ?>')" class="form-select" required>
                                    <option value="" selected disabled>Autodeclaração</option>
                                    <option value="branco">Branco</option>
                                    <option value="preto">Preto</option>
                                    <option value="pardo">Pardo</option>
                                    <option value="indio">Índio</option>
                                    <option value="amarelo">Amarelo</option>
                                    <option value="quilombola" <?php if (!isset($_SESSION['tipo_selecao'])) echo ('hidden'); ?>>Quilombola</option>
                                </select>
                            </div>

                            <div class="col-lg-8 mb-3">
                                <div id="div_vaga_reservada" class="form-check mt-4 conditional-section">
                                    <input class="form-check-input" type="checkbox" id="check_vaga_reservada" name="vaga_reservada">
                                    <label class="form-check-label" for="check_vaga_reservada">
                                        Quero concorrer às vagas reservadas para Negros (Lei Nr 15.142, de 3 de Junho de 2025)
                                    </label>
                                </div>
                            </div>

                            <div id='div_erro_dados_pessoais' class="alert alert-danger mt-3 conditional-section">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <span id="erro_dados_pessoais"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ENDEREÇO -->
                <div class="card mb-4 fade-in">
                    <h5 class="section-header"><i class="fa fa-home me-2"></i>Endereço</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <select id="uf" name="uf" class="form-select" onchange="busca_cidades()" required>
                                    <option value="" selected disabled>Selecione a UF</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AM">AM</option>
                                    <option value="AP">AP</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MG">MG</option>
                                    <option value="MS">MS</option>
                                    <option value="MT">MT</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="PR">PR</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="RS">RS</option>
                                    <option value="SC">SC</option>
                                    <option value="SE">SE</option>
                                    <option value="SP">SP</option>
                                    <option value="TO">TO</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="bairro" name="bairro" class="form-control floating-input" placeholder=" " required>
                                    <label for="bairro" class="required-field">Bairro</label>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <select id="cidade" name="cidade" class="form-select" required>
                                    <option value="" selected disabled>Selecione a UF primeiro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="cep" maxlength="20" name="cep" class="form-control floating-input" placeholder=" " required>
                                    <label for="cep" class="required-field">CEP</label>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="rua" maxlength="200" name="rua" class="form-control floating-input" placeholder=" " required>
                                    <label for="rua" class="required-field">Avenida/Rua, número e complemento</label>
                                </div>
                            </div>
                        </div>

                        <div id='div_erro_endereco' class="alert alert-danger mt-3 conditional-section">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span id="erro_endereco"></span>
                        </div>
                    </div>
                </div>

                <!-- CONTATO -->
                <div class="card mb-4 fade-in">
                    <h5 class="section-header"><i class="fa fa-phone me-2"></i>Contato</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="celular" maxlength="50" name="celular" class="form-control floating-input" placeholder=" " required>
                                    <label for="celular" class="required-field">Telefone para CONTATO</label>
                                </div>
                                <small class="text-muted">*Coloque o DDD</small>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <div class="floating-label">
                                    <input type="text" id="telefone" maxlength="50" name="telefone" class="form-control floating-input" placeholder=" " required>
                                    <label for="telefone" class="required-field">Telefone para RECADOS</label>
                                </div>
                                <small class="text-muted">*Coloque o DDD</small>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <div class="floating-label">
                                    <input type="email" id="mail" maxlength="50" name="mail" class="form-control floating-input" placeholder=" " required>
                                    <label for="mail" class="required-field">E-Mail</label>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <div class="floating-label">
                                    <input type="email" id="mail2" maxlength="50" name="mail2" class="form-control floating-input" placeholder=" " required>
                                    <label for="mail2" class="required-field">Repita o seu E-Mail</label>
                                </div>
                            </div>
                        </div>

                        <div id='div_erro_contato' class="alert alert-danger mt-3 conditional-section">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span id="erro_contato"></span>
                        </div>
                    </div>
                </div>

                <!-- EIPOT -->
                <div class="card mb-4 fade-in conditional-section" id="eipot-section">
                    <h5 class="section-header"><i class="fa fa-graduation-cap me-2"></i>Informações EIPOT</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="floating-label">
                                    <input type="text" maxlength="200" name="curso_graduacao" class="form-control floating-input" placeholder=" " required>
                                    <label class="required-field">Nome do curso de graduação</label>
                                </div>
                                <small class="text-muted">*Deve ter sido concluído até dia 03 de Julho de 2023</small>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="floating-label">
                                    <input type="text" maxlength="4" name="ano_formacao_ofor" class="form-control floating-input" placeholder=" " required>
                                    <label class="required-field">Ano de formação do CPOR ou NPOR (OFOR)</label>
                                </div>
                                <small class="text-muted">* Ano com 4 dígitos | Exemplo: 2019</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="floating-label">
                                    <input type="text" maxlength="50" name="nota_ofor" class="form-control floating-input" placeholder=" " required>
                                    <label class="required-field">Nota de conclusão do CPOR/NPOR (OFOR)</label>
                                </div>
                                <small class="text-muted">* Exemplo: 07.60</small>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label required-field">Selecione a sua arma de formação</label>
                                <select name="arma_eipot" class="form-select" required>
                                    <option value="" selected disabled>Selecione a arma</option>
                                    <option value="Infantaria">Infantaria (INF)</option>
                                    <option value="Cavalaria">Cavalaria (CAV)</option>
                                    <option value="Artilharia Antiaérea">Artilharia Antiaérea</option>
                                    <option value="Engenharia">Engenharia</option>
                                    <option value="Material Bélico">Material Bélico</option>
                                    <option value="Intendência">Intendência</option>
                                    <option value="outra">Outra</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INSTITUIÇÃO DE ENSINO -->
                <?php if (isset($_SESSION['mfdv'])) : ?>
                    <div class="card mb-4 fade-in" id="instituicao-section">
                        <h5 class="section-header"><i class="fa fa-university me-2"></i>Instituição de Ensino de Formação</h5>
                        <div class="form-section">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="floating-label">
                                        <input type="text" maxlength="200" name="nome_ie" class="form-control floating-input" placeholder=" " required>
                                        <label class="required-field">Nome do Instituto de Ensino</label>
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <select name="ano_formacao" class="form-select" required>
                                        <option value="" selected disabled>Ano de formação</option>
                                        <?php
                                        for ($i = date("Y"); $i >= 1980; $i--) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <select id="uf_ie" name="uf_ie" class="form-select" onchange="busca_cidades_ie()" required>
                                        <option value="" selected disabled>UF da Instituição de Ensino</option>
                                        <option value="AC">AC</option>
                                        <option value="AL">AL</option>
                                        <option value="AM">AM</option>
                                        <option value="AP">AP</option>
                                        <option value="BA">BA</option>
                                        <option value="CE">CE</option>
                                        <option value="DF">DF</option>
                                        <option value="ES">ES</option>
                                        <option value="GO">GO</option>
                                        <option value="MA">MA</option>
                                        <option value="MG">MG</option>
                                        <option value="MS">MS</option>
                                        <option value="MT">MT</option>
                                        <option value="PA">PA</option>
                                        <option value="PB">PB</option>
                                        <option value="PE">PE</option>
                                        <option value="PI">PI</option>
                                        <option value="PR">PR</option>
                                        <option value="RJ">RJ</option>
                                        <option value="RN">RN</option>
                                        <option value="RO">RO</option>
                                        <option value="RR">RR</option>
                                        <option value="RS">RS</option>
                                        <option value="SC">SC</option>
                                        <option value="SE">SE</option>
                                        <option value="SP">SP</option>
                                        <option value="TO">TO</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <select id="cidade_ie" name="cidade_ie" class="form-select" required>
                                        <option value="" selected disabled>Cidade da Instituição de Ensino</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- TEMPO DE SERVIÇO PÚBLICO -->
                <div class="card mb-4 fade-in conditional-section" id="servico-publico-section">
                    <h5 class="section-header"><i class="fa fa-briefcase me-2"></i>Tempo de serviço público até a data final da inscrição</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <select id="tempo_sv_pub" name="tempo_sv_pub" class="form-select" onchange="tempo_servico_publico()" required>
                                    <option value="" selected disabled>Possui tempo de serviço público</option>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_pub_anos" class="conditional-section">
                                    <select id="tempo_sv_pub_anos" name="tempo_sv_pub_anos" class="form-select">
                                        <option value="" selected disabled>Selecione quantos anos</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_pub_meses" class="conditional-section">
                                    <select id="tempo_sv_pub_meses" name="tempo_sv_pub_meses" class="form-select">
                                        <option value="" selected disabled>Selecione quantos meses</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_pub_dias" class="conditional-section">
                                    <select id="tempo_sv_pub_dias" name="tempo_sv_pub_dias" class="form-select">
                                        <option value="" selected disabled>Selecione quantos dias</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id='div_erro_tempo_sv_pub' class="alert alert-danger mt-3 conditional-section">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span id="erro_tempo_sv_pub"></span>
                        </div>
                    </div>
                </div>

                <!-- TEMPO DE SERVIÇO MILITAR -->
                <div class="card mb-4 fade-in" id="servico-militar-section">
                    <h5 class="section-header"><i class="fa fa-clock-o me-2"></i>Tempo de serviço militar (nas Forças Armadas) até a data final da inscrição</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <select id="tempo_sv_mil" name="tempo_sv_mil" class="form-select" onchange="tempo_servico_militar()" required>
                                    <option value="" selected disabled>Possui tempo de serviço militar (nas Forças Armadas)</option>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_mil_anos" class="conditional-section">
                                    <select id="tempo_sv_mil_anos" name="tempo_sv_mil_anos" class="form-select">
                                        <option value="" selected disabled>Selecione quantos anos</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_mil_meses" class="conditional-section">
                                    <select id="tempo_sv_mil_meses" name="tempo_sv_mil_meses" class="form-select">
                                        <option value="" selected disabled>Selecione quantos meses</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div id="div_tempo_sv_mil_dias" class="conditional-section">
                                    <select id="tempo_sv_mil_dias" name="tempo_sv_mil_dias" class="form-select">
                                        <option value="" selected disabled>Selecione quantos dias</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id='div_erro_tempo_sv_mil' class="alert alert-danger mt-3 conditional-section">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span id="erro_tempo_sv_mil"></span>
                        </div>
                    </div>
                </div>

                <!-- CIVIL OU MILITAR -->
                <div class="card mb-4 fade-in">
                    <h5 class="section-header"><i class="fa fa-question-circle-o me-2"></i>Civil ou Militar Temporário das Forças Armada</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <select id="civil_militar" name="civil_militar" onchange="select_civil_militar()" class="form-select" required>
                                    <option value="" selected disabled>Selecione se você é civil ou militar temporário</option>
                                    <option value="civil">Civil</option>
                                    <option value="militar">Militar Temporário das Forças Armadas</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_ja_foi_militar">
                                <select id="ja_foi_militar" name="ja_foi_militar" onchange="select_ja_foi_militar()" class="form-select">
                                    <option value="" selected disabled>Selecione se já foi Militar</option>
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                    <?php if (isset($_SESSION['mfdv'])) : ?>
                                        <option value="sim_eas">Sim, já fiz o EAS</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_certificado">
                                <select id="certificado" name="certificado" onchange="select_certificado()" class="form-select">
                                    <option value="" selected disabled>Selecione o seu Documento Militar</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_documento">
                                <div class="floating-label">
                                    <input type="text" id="documento" maxlength="50" name="documento" class="form-control floating-input" placeholder=" ">
                                    <label for="documento">Número do documento militar</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 mb-3 conditional-section" id="div_data_expedicao">
                                <div class="floating-label">
                                    <input type="text" id="data_expedicao" maxlength="10" name="data_expedicao" class="form-control floating-input" placeholder=" ">
                                    <label for="data_expedicao">Data de Expedição</label>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_arma">
                                <div class="floating-label">
                                    <input type="text" id="arma" maxlength="50" name="arma" class="form-control floating-input" placeholder=" ">
                                    <label for="arma">Arma/Quadro/Serviço/Especialidade</label>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_forca">
                                <select id="forca" name="forca" class="form-select">
                                    <option value="" selected disabled>Selecione a Força</option>
                                    <option value="exercito">Exército</option>
                                    <option value="marinha">Marinha</option>
                                    <option value="aeronautica">Aeronáutica</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_posto_grad">
                                <select id="posto_grad" name="posto_grad" class="form-select">
                                    <option value="" selected disabled>Selecione o Posto/Graduação</option>
                                    <option value="sd">Soldado</option>
                                    <option value="cb">Cabo</option>
                                    <option value="3_sgt">3º Sargento</option>
                                    <option value="asp">Aspirante</option>
                                    <option value="2_ten">2º Tenente</option>
                                    <option value="1_Ten">1º Tenente</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 mb-3 conditional-section" id="div_incorporacao">
                                <div class="floating-label">
                                    <input type="text" id="incorporacao" maxlength="50" name="incorporacao" class="form-control floating-input" placeholder=" ">
                                    <label for="incorporacao">Ano de incorporação</label>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-3 conditional-section" id="div_licenciamento">
                                <div class="floating-label">
                                    <input type="text" id="licenciamento" maxlength="50" name="licenciamento" class="form-control floating-input" placeholder=" ">
                                    <label for="licenciamento">Licenciamento</label>
                                </div>
                            </div>
                        </div>

                        <div id='div_erro_civil_militar' class="alert alert-danger mt-3 conditional-section">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <span id="erro_civil_militar"></span>
                        </div>
                    </div>
                </div>

                <!-- OUTRAS INFORMAÇÕES -->
                <div class="card mb-4 fade-in conditional-section" id="outras-informacoes-section">
                    <h5 class="section-header"><i class="fa fa-info-circle me-2"></i>Outras Informações</h5>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Voluntário para servir na 12ª Região Militar (Amazônia)</label>
                                <select name="voluntario_12rm" class="form-select">
                                    <option value="" selected disabled>Selecione a opção</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Prioridade de Força</label>
                                <select name="prioridade_forca" class="form-select">
                                    <option value="" selected disabled>Selecione as prioridades</option>
                                    <option value="qualquer">Qualquer Força</option>
                                    <option value="EAM">1ª Exército - 2ª Aeronáutica - 3ª Marinha</option>
                                    <option value="EMA">1ª Exército - 2ª Marinha - 3ª Aeronáutica</option>
                                    <option value="MEA">1ª Marinha - 2ª Exército - 3ª Aeronáutica</option>
                                    <option value="MAE">1ª Marinha - 2ª Aeronáutica - 3ª Exército</option>
                                    <option value="AME">1ª Aeronáutica - 2ª Marinha - 3ª Exército</option>
                                    <option value="AEM">1ª Aeronáutica - 2ª Exército - 3ª Marinha</option>
                                    <option value="EQ">1ª Exército - 2ª e 3ª Qualquer outra força</option>
                                    <option value="MQ">1ª Marinha - 2ª e 3ª Qualquer outra força</option>
                                    <option value="AQ">1ª Aeronáutica - 2ª e 3ª Qualquer outra força</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Selecione a cidade na qual você quer participar das etapas presenciais</label>
                                <select name="cidade_etapas_presenciais_6" class="form-select">
                                    <option value="" selected disabled>Selecione a Cidade</option>
                                    <option value="Aracaju-SE">Aracaju-SE</option>
                                    <option value="Barreiras-BA">Barreiras-BA</option>
                                    <option value="Feira de Santana-BA">Feira de Santana-BA</option>
                                    <option value="Ilhéus-BA">Ilhéus - BA</option>
                                    <option value="Paulo Afonso-BA">Paulo Afonso - BA</option>
                                    <option value="Salvador-BA">Salvador-BA</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                                <select name="cidade_etapas_presenciais12" class="form-select">
                                    <option value="" selected disabled>Selecione a Cidade</option>
                                    <option value="Manaus">Manaus</option>
                                    <option value="Boa Vista">Boa Vista</option>
                                    <option value="Porto Velho">Porto Velho</option>
                                    <option value="Rio Branco">Rio Branco</option>
                                    <option value="Tefé">Tefé</option>
                                    <option value="São Gabriel da Cachoeira">São Gabriel da Cachoeira</option>
                                    <option value="Tabatinga">Tabatinga</option>
                                    <option value="Cruzeiro do Sul">Cruzeiro do Sul</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Selecione a cidade na qual você deseja participar prova do exame de comprovação de habilidade musical</label>
                                <select name="cidade_exame_musica_12rm" class="form-select">
                                    <option value="" selected disabled>Selecione a Cidade</option>
                                    <option value="Manaus - AM">Manaus - AM</option>
                                    <option value="Humaitá - AM">Humaitá - AM</option>
                                    <option value="São Gabriel da Cachoeira – AM">São Gabriel da Cachoeira – AM</option>
                                    <option value="Tabatinga – AM">Tabatinga – AM</option>
                                    <option value="Tefé – AM">Tefé – AM</option>
                                    <option value="Porto Velho – RO">Porto Velho – RO</option>
                                    <option value="Guajará Mirim - RO">Guajará Mirim - RO</option>
                                    <option value="Rio Branco - AC">Rio Branco - AC</option>
                                    <option value="Cruzeiro do Sul - AC">Cruzeiro do Sul - AC</option>
                                    <option value="Boa Vista – RR">Boa Vista – RR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUESTIONÁRIO -->
                 <?php if (!empty($questionario) && $selecao[0]['rm'] == 3): ?>
                    <div class="card mb-4 fade-in" id="outras-informacoes-section">
                        <h5 class="section-header"><i class="fa fa-info-circle me-2"></i>Questionário Obrigatório</h5>
                        <div class="form-section">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <?php foreach ($questionario as $p): ?>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                <?= htmlspecialchars($p['texto_pergunta']); ?>
                                            </label>

                                            <?php if ($p['tipo_campo'] === 'texto'): ?>

                                                <input type="text"
                                                    name="resposta[<?= $p['id']; ?>]"
                                                    class="form-control" required>

                                            <?php elseif ($p['tipo_campo'] === 'numero'): ?>

                                                <input type="number"
                                                    name="resposta[<?= $p['id']; ?>]"
                                                    class="form-control" required>

                                            <?php elseif ($p['tipo_campo'] === 'booleano'): ?>

                                                <select name="resposta[<?= $p['id']; ?>]" class="form-control" required>
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não">Não</option>
                                                </select>

                                            <?php endif; ?>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- DECLARAÇÃO E ENVIO -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="declaracao" name="declaracao" required>
                            <label class="form-check-label" for="declaracao">
                                Declaro que li o aviso de convocação e que as informações aqui cadastradas são verdadeiras.
                            </label>
                            <div class="invalid-feedback">
                                Você deve concordar antes de enviar.
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <strong>ATENÇÃO!</strong> Ao clicar em Cadastrar, aguarde! O processo pode levar de 2 a 5 minutos. Não feche a aba ou o navegador.
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fa fa-paper-plane me-2"></i>CADASTRAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p class="mb-0">SiSCanT - Sistema de Seleção de Candidatos Temporários © <?php echo date("Y"); ?></p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Aplicar máscaras
        $("input[name*='nota_ofor']").mask("99.99");
        $("input[name*='ano_formacao_ofor']").mask("9999");
        $("#cpf").mask("999.999.999-99");
        $("#data_nascimento").mask("99/99/9999");
        $("#cep").mask("99999-999");
        $("#celular").mask("(99) 99999-9999");
        $("#telefone").mask("(99) 99999-9999");
        $("#data_expedicao").mask("99/99/9999");


        // Prevenir colar no campo de confirmação de e-mail
        document.getElementById('mail2').addEventListener('paste', function(e) {
            e.preventDefault();
            alert('Colar não é permitido neste campo. Por favor, digite o e-mail novamente.');
        });

        // Inicialmente esconder todas as seções condicionais
        $('.conditional-section').hide();
    });

    // Função para mostrar/ocultar campos (mantida da versão original)


    function mostra_vaga_reservada() {
        const autodeclaracao = $('#autodeclaracao').val();
        const cotistas = ['preto', 'pardo', 'quilombola', 'indio'];

        if (cotistas.includes(autodeclaracao)) {
            $('#div_vaga_reservada').show();
        } else {
            $('#div_vaga_reservada').hide();
        }
    }

    function mostra_nome_social() {
        if ($('#check_nome_social').is(':checked')) {
            $('#div_nome_social').show();
        } else {
            $('#div_nome_social').hide();
        }
    }

    function tempo_servico_publico() {
        const tempoServico = $('#tempo_sv_pub').val();

        if (tempoServico === '1') {
            $('#div_tempo_sv_pub_anos').show();
            $('#div_tempo_sv_pub_meses').show();
            $('#div_tempo_sv_pub_dias').show();
        } else {
            $('#div_tempo_sv_pub_anos').hide();
            $('#div_tempo_sv_pub_meses').hide();
            $('#div_tempo_sv_pub_dias').hide();
        }
    }
</script>
<script type="text/javascript">
    document.getElementById('mail2').addEventListener('paste', function(e) {
        e.preventDefault();
        alert('Colar não é permitido neste campo. Por favor, digite o e-mail novamente.');
    });
    $('body').removeClass("sidebar-mini").addClass("sidebar-collapse");
</script>
</body>

</html>