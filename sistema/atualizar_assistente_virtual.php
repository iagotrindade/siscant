<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (!isset($_SESSION))
    session_start();

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


if ($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 235332446!");
    exit();
}

$conhecimento = $conexao->get_pergunta_resposta_id($_GET['id_pergunta']);
?>
<style>
    :root {
        --primary-color: #006400;
        /* Verde escuro como cor primária */
        --secondary-color: #228B22;
        /* Verde mar como cor de sucesso */
        --text-color: #333333;
        /* Cor do texto principal */
        --border-color: #D3D3D3;
        /* Cor das bordas */
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        padding: 5px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-header i {
        margin-right: 8px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--text-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #004d00;
        /* Tom mais escuro do verde primário */
        transform: scale(1.01);
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 15px;
        border: none;
    }


    .section-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        font-weight: 700;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(34, 139, 34, 0.1);
    }
</style>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Atualizar Assistente Virtual <i class="fa bi bi-robot"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Atualizar Assistente Virtual</li>
            </ul>
        </div>
    </div>
    <div class="row">

        <!-- Formulário de Atualização (somente para admin) -->
        <?php if ($_SESSION['perfil'] == "admin"): ?>
            <div class="card mb-20">
                <div class="card-header mb-20" style="background-color: #006400; color: white;">
                    <h4 class="">
                        <i class="fa fa-comments me-2"></i> Atualizar Pergunta do Assistente Virtual
                    </h4>
                </div>
                <div class="card-body">
                    <form action="../banco_dados/pergunta_editar.php" method="post" onsubmit="return validar_formulario()">
                        <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                        <input type="hidden" name="id" value="<?php echo ($conhecimento['id']); ?>">

                        <div class="mb-10">
                            <label for="etapa" class="form-label"><strong>Etapa</strong></label>
                            <select name="etapa" id="etapa" class="form-control">
                                <option value="" <?= empty($conhecimento['etapa']) ? 'selected' : '' ?>>Selecione a Etapa</option>
                                <option value="0" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 0 ? 'selected' : '' ?>>TODAS</option>
                                <option value="1" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 1 ? 'selected' : '' ?>>Etapa 1</option>
                                <option value="2" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 2 ? 'selected' : '' ?>>Etapa 2</option>
                                <option value="3" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 3 ? 'selected' : '' ?>>Etapa 3</option>
                                <option value="4" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 4 ? 'selected' : '' ?>>Etapa 4</option>
                                <option value="5" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 5 ? 'selected' : '' ?>>Etapa 5</option>
                                <option value="6" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 6 ? 'selected' : '' ?>>Etapa 6</option>
                                <option value="7" <?= isset($conhecimento['etapa']) && $conhecimento['etapa'] == 7 ? 'selected' : '' ?>>Etapa 7</option>
                            </select>
                        </div>

                        <div class="mb-10">
                            <label for="pergunta" class="form-label"><strong>Pergunta</strong></label>
                            <textarea name="pergunta" id="pergunta" class="form-control"
                                placeholder="Digite a pergunta que os usuários podem fazer..."
                                rows="3" style="min-height: 100px;"><?= $conhecimento['pergunta'] ?></textarea>
                            <div class="form-text">Exemplo: "Como faço para me inscrever no processo?"</div>
                        </div>

                        <div class="mb-10">
                            <label for="resposta" class="form-label"><strong>Resposta</strong></label>
                            <textarea name="resposta" id="resposta" class="form-control mb-10"
                                placeholder="Digite a resposta que o assistente deve fornecer..."
                                rows="5" style="min-height: 150px;"><?= $conhecimento['resposta'] ?></textarea>
                            <div class="form-text">Forneça uma resposta clara e completa</div>
                        </div>

                        <div class="alert alert-danger" id="mensagem_erro" role="alert" style="display: none;">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <span id="mensagem"></span>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fa fa-refresh me-2"></i> ATUALIZAR PERGUNTA
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Guia de Formatação -->
        <div class="card mb-20">
            <div class="card-header mb-20" style="background-color: #006400; color: white;">
                <h4 class="">
                    <i class="fa fa-edit me-2"></i> Guia de Formatação das Respostas
                </h4>
            </div>
            <div class="card-body">
                <p class="lead mb-10" style="color: #2E8B57;">
                    Utilize estas regras simples para formatar suas respostas de forma organizada e profissional:
                </p>

                <div class="row">
                    <!-- Títulos -->
                    <div class="col-md-6 mb-10">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Títulos</h5>
                        </div>
                        <p class="mb-10">Termine a linha com dois pontos <code>:</code></p>
                        <div class="bg-light p-3 rounded">
                            <small class="text-success">Exemplo:</small>
                            <code class="d-block mt-1 text-dark">Documentos necessários:</code>
                        </div>
                    </div>

                    <!-- Listas ordenadas -->
                    <div class="col-md-6 mb-10">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Lista Numerada</h5>
                        </div>
                        <p class="mb-10">Numere os itens começando com o número seguido de ponto:</p>
                        <ol class="bg-light p-3 rounded" style="border-left: 3px solid #006400;">
                            <li>Primeiro item</li>
                            <li>Segundo item</li>
                            <li>Terceiro item </li>
                        </ol>
                    </div>

                    <!-- Listas não ordenadas -->
                    <div class="col-md-6 mb-10">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Lista com Marcadores</h5>
                        </div>
                        <p class="mb-10">Use hífen <code>-</code> no início de cada linha:</p>
                        <ul class="bg-light p-3 rounded" style="border-left: 3px solid #006400; list-style-type: none;">
                            <li>- Item A</li>
                            <li>- Item B</li>
                            <li>- Item C</li>
                        </ul>
                    </div>

                    <!-- Quebras de linha -->
                    <div class="col-md-6 mb-20">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Parágrafos e Espaçamento</h5>
                        </div>
                        <p class="mb-10">Pressione <kbd class="bg-secondary">Enter</kbd> duas vezes para separar parágrafos.</p>
                        <div class="bg-light p-3 rounded">
                            <small class="text-success">Resultado:</small>
                            <p class="mb-1 mt-2">Primeiro parágrafo</p>
                            <p class="">Segundo parágrafo</p>
                        </div>
                    </div>

                    <!-- Destaques -->
                    <div class="col-md-6 mb-20">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Destaques Importantes</h5>
                        </div>
                        <p class="mb-10">Use <code>Importante:</code> ou <code>Atenção:</code> no início da linha</p>
                        <div class="alert alert-warning mt-2 ">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Será exibido nesta caixa de alerta amarela
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="col-md-6 mb-20">
                        <div class="d-flex align-items-center mb-10">
                            <h5 class="" style="color: #006400;">Links Externos</h5>
                        </div>
                        <p class="mb-10">Digite o endereço completo começando com <code>http://</code> ou <code>https://</code></p>
                        <div class="bg-light p-3 rounded">
                            <small class="text-success">Exemplo:</small>
                            <code class="d-block mt-1 text-dark">https://exemplo.com/edital</code>
                            <small class="d-block mt-1 text-muted">→ Será convertido automaticamente em link clicável</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
</body>

</html>
<?php
$conexao = null;
?>