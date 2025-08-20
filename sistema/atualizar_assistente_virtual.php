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
        <div class="col-md-12">
            <!-- Formulário de Cadastro (somente para admin) -->
            <div class="row" <?php if ($_SESSION['perfil'] != "admin") echo "hidden" ?>>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="glyphicon glyphicon-comment"></i> Atualizar Pergunta do Assistente Virtual
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form action="../banco_dados/pergunta_editar.php" method="post">
                                <input type="hidden" name="criptografia" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']); ?>">
                                <input type="hidden" name="id" value="<?php echo($conhecimento['id']); ?>">

                                <div class="form-group">
                                    <label for="pergunta" class="control-label"><strong>Pergunta</strong></label>
                                    <textarea name="pergunta" id="pergunta" class="form-control"
                                        placeholder="Digite a pergunta que os usuários podem fazer..."
                                        rows="3" style="min-height: 100px;"><?= $conhecimento['pergunta'] ?></textarea>
                                    <p class="help-block">Exemplo: "Como faço para me inscrever no processo?"</p>
                                </div>

                                <div class="form-group">
                                    <label for="resposta" class="control-label"><strong>Resposta</strong></label>
                                    <textarea name="resposta" id="resposta" class="form-control"
                                        placeholder="Digite a resposta que o assistente deve fornecer..."
                                        rows="5" style="min-height: 150px;"><?= $conhecimento['resposta'] ?></textarea>
                                    <p class="help-block">Forneça uma resposta clara e completa</p>
                                </div>

                                <div class="alert alert-danger" id="mensagem_erro" style="display: none;">
                                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                                    <span id="mensagem"></span>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block btn-md">
                                    <i class="glyphicon glyphicon-floppy-disk"></i> CADASTRAR PERGUNTA
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info" style="margin-top:20px; border-color:#006400;">
                <div class="panel-heading" style="background-color:#006400; color:white;">
                    <h3 class="panel-title" style="font-weight:bold;">
                        <i class="glyphicon glyphicon-edit" style="margin-right:8px;"></i> Guia de Formatação das Respostas
                    </h3>
                </div>
                <div class="panel-body" style="padding:20px;">
                    <p class="lead" style="margin-bottom:20px; font-size:16px; color:#2c6e3e;">
                        Utilize estas regras simples para formatar suas respostas de forma organizada e profissional:
                    </p>

                    <div class="formatting-guide">
                        <!-- Títulos -->
                        <div class="format-item" style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #ddd;">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-header" style="margin-right:8px;"></span>
                                Títulos
                            </h4>
                            <p>Termine a linha com dois pontos <code class="example-code">:</code></p>
                            <div class="example-box" style="background:#f8f9fa; padding:10px; border-radius:4px; margin-top:5px;">
                                <span style="color:#006400;">Exemplo:</span>
                                <code style="display:block; margin-top:5px; color:#2c6e3e;">Documentos necessários:</code>
                            </div>
                        </div>

                        <!-- Listas ordenadas -->
                        <div class="format-item" style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #ddd;">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-list" style="margin-right:8px;"></span>
                                Lista Numerada
                            </h4>
                            <p>Numere os itens começando com o número seguido de ponto:</p>
                            <pre style="background:#f8f9fa; padding:10px; border-radius:4px; border-left:3px solid #006400; margin-top:5px;">
1. Primeiro item
2. Segundo item
3. Terceiro item</pre>
                        </div>

                        <!-- Listas não ordenadas -->
                        <div class="format-item" style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #ddd;">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-th-list" style="margin-right:8px;"></span>
                                Lista com Marcadores
                            </h4>
                            <p>Use hífen <code class="example-code">-</code> no início de cada linha:</p>
                            <pre style="background:#f8f9fa; padding:10px; border-radius:4px; border-left:3px solid #006400; margin-top:5px;">
- Item A
- Item B
- Item C</pre>
                        </div>

                        <!-- Quebras de linha -->
                        <div class="format-item" style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #ddd;">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-text-height" style="margin-right:8px;"></span>
                                Parágrafos e Espaçamento
                            </h4>
                            <p>Pressione <kbd class="keyboard-key">Enter</kbd> duas vezes para separar parágrafos.</p>
                            <div class="example-box" style="background:#f8f9fa; padding:10px; border-radius:4px; margin-top:5px;">
                                <span style="color:#006400;">Resultado:</span>
                                <p style="margin:5px 0 0 0;">Primeiro parágrafo</p>
                                <p style="margin:5px 0 0 0;">Segundo parágrafo</p>
                            </div>
                        </div>

                        <!-- Destaques -->
                        <div class="format-item" style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #ddd;">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-alert" style="margin-right:8px;"></span>
                                Destaques Importantes
                            </h4>
                            <p>Use <code class="example-code">Importante:</code> ou <code class="example-code">Atenção:</code> no início da linha</p>
                            <div class="alert alert-warning" style="margin-top:10px; padding:10px; background-color:#fff3cd; border-color:#ffeeba;">
                                ⚠️ <strong>Importante:</strong> Será exibido nesta caixa de alerta amarela
                            </div>
                        </div>

                        <!-- Links -->
                        <div class="format-item">
                            <h4 style="color:#006400; margin-top:0;">
                                <span class="glyphicon glyphicon-link" style="margin-right:8px;"></span>
                                Links Externos
                            </h4>
                            <p>Digite o endereço completo começando com <code class="example-code">http://</code> ou <code class="example-code">https://</code></p>
                            <div class="example-box" style="background:#f8f9fa; padding:10px; border-radius:4px; margin-top:5px;">
                                <span style="color:#006400;">Exemplo:</span>
                                <code style="display:block; margin-top:5px; color:#2c6e3e;">https://exemplo.com/edital</code>
                                <span style="display:block; margin-top:5px; font-size:13px; color:#666;">→ Será convertido automaticamente em link clicável</span>
                            </div>
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