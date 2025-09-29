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

    .card-header i {
        margin-right: 8px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--text-color);
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

    .badge-etapa {
        background: var(--primary-color);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .selecao-badge {
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        color: #495057;
        border: 1px solid #e9ecef;
        font-size: 1.3rem;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Assistente Virtual <i class="fa bi bi-robot"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Assistente Virtual</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Formulário de Cadastro (somente para admin) -->
            <?php if ($_SESSION['perfil'] == "admin"): ?>
                <div class="card mb-20">
                    <div class="card-header mb-20" style="background-color: #006400; color: white;">
                        <span class="">
                            <i class="fa fa-comments me-2"></i> Adicionar Nova Pergunta ao Assistente Virtual
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="../banco_dados/pergunta_cadastra.php" method="post" onsubmit="return validar_formulario()">
                            <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">

                            <div class="mb-10">
                                <label for="etapa" class="form-label"><strong>Etapa</strong></label>
                                <select name="etapa" id="etapa" class="form-control" required>
                                    <option value="" selected>Selecione a Etapa</option>
                                    <option value="0">TODAS</option>
                                    <option value="1">Etapa 1</option>
                                    <option value="2">Etapa 2</option>
                                    <option value="3">Etapa 3</option>
                                    <option value="4">Etapa 4</option>
                                    <option value="5">Etapa 5</option>
                                    <option value="6">Etapa 6</option>
                                    <option value="7">Etapa 7</option>
                                </select>
                            </div>

                            <div class="mb-10">
                                <label for="pergunta" class="form-label"><strong>Pergunta</strong></label>
                                <textarea name="pergunta" id="pergunta" class="form-control"
                                    placeholder="Digite a pergunta que os usuários podem fazer..."
                                    rows="3" style="min-height: 100px;" required></textarea>
                                <div class="form-text">Exemplo: "Como faço para me inscrever no processo?"</div>
                            </div>

                            <div class="mb-10">
                                <label for="resposta" class="form-label"><strong>Resposta</strong></label>
                                <textarea name="resposta" id="resposta" class="form-control mb-10"
                                    placeholder="Digite a resposta que o assistente deve fornecer..."
                                    rows="5" style="min-height: 150px;" required></textarea>
                                <div class="form-text">Forneça uma resposta clara e completa</div>
                            </div>

                            <div class="alert alert-danger" id="mensagem_erro" role="alert" style="display: none;">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                <span id="mensagem"></span>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save me-2"></i> CADASTRAR PERGUNTA E RESPOSTA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Guia de Formatação -->
            <div class="card mb-20">
                <div class="card-header mb-20" style="background-color: #006400; color: white;">
                    <span class="">
                        <i class="fa fa-edit me-2"></i> Guia de Formatação das Respostas
                    </span>
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
                            <ul class="bg-light p-3 rounded" style="border-left: 3px solid #006400; list-style-type: none;">
                                <li>1. Primeiro item</li>
                                <li>2. Segundo item</li>
                                <li>3. Terceiro item</li>
                            </ul>
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

            <!-- Tabela de Perguntas Cadastradas -->
            <div class="card">
                <div class="card-header mb-20" style="background-color: #006400; color: white;">
                    <span class="">
                        <i class="fa fa-list me-2"></i> Perguntas Cadastradas no Assistente Virtual
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern" id="tabela_dinamica">
                            <thead class="table-header-custom">
                                <tr>
                                    <th class="text-center"><i class="fa fa-list-alt me-1"></i> Seleção</th>
                                    <th class="text-center"><i class="fa fa-list-ol me-1"></i> Etapa</th>
                                    <th class="text-center"><i class="fa fa-question-circle me-1"></i> Pergunta</th>
                                    <th class="text-center"><i class="fa fa-comment me-1"></i> Resposta</th>
                                    <th class="text-center"><i class="fa fa-user me-1"></i> Usuário que Cadastrou</th>
                                    <th class="text-center"><i class="fa fa-calendar me-1"></i> Data de Cadastro</th>
                                    <th class="text-center"><i class="fa fa-cogs me-1"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista_perguntas = $conexao->get_perguntas_assistente($_SESSION['selecao_codigo']);
                                ?>

                                <?php foreach ($lista_perguntas as $linha): ?>
                                    <?php
                                    // Busca o nome do usuário que cadastrou a pergunta                         
                                    $usuario_cadastro = $conexao->get_usuario_id($linha['id_usuario_inseriu']);

                                    $etapa = '';
                                    if ($linha['etapa'] == 0) {
                                        $etapa = '<span class="badge badge-all">TODAS</span>';
                                    } else {
                                        $etapa = '<span class="badge badge-etapa">et_' . $linha['etapa'] . '</span>';
                                    }
                                    ?>
                                    <tr class="table-row-custom">
                                        <td class="text-center">
                                            <span class="selecao-badge"><?= strtoupper($linha['selecao']) ?? '-' ?></span>
                                        </td>

                                        <td class="text-center">
                                            <?= $etapa ?>
                                        </td>

                                        <td class="pergunta-cell">
                                            <div class="pergunta-content" title="<?= htmlspecialchars($linha['pergunta']) ?>">
                                                <?= $linha['pergunta'] ?>
                                            </div>
                                        </td>

                                        <td class="resposta-cell">
                                            <div class="resposta-content" title="<?= htmlspecialchars($linha['resposta']) ?>">
                                                <?= $linha['resposta'] ?>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span class="usuario-info">
                                                <?= $usuario_cadastro[0]['posto_grad'] . ' ' . $usuario_cadastro[0]['nome_guerra'] ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span class="data-info">
                                                <?= trata_data_hora($linha['data_insercao']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="atualizar_assistente_virtual.php?id_pergunta=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a onclick="funcao_apagar('<?= $linha['id'] ?>', 'pergunta')" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ativa os tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Função para validar o formulário (mantida do original)
        function validar_formulario() {
            // Sua lógica de validação aqui
            return true;
        }
    </script>
</div>
</div>
<script src="sistema/js/bootstrap5.3.3.js"></script>
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