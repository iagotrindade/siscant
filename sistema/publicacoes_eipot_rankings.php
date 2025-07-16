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
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Publicações <i class="fa fa-pencil"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Publicações</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <legend>Atenção <img src="imagens/urgente.gif" height="25px"></legend>

        <div class="alert alert-info p-20">
            <b>É necessário que o candidato esteja na etapa VI para ser inserido no Ranking.</b>
        </div>
    </div>

    <form name="form_relacao_classificacao_eipot_ampla" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_ampla_concorrencia">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Classificação Final após Etapas I, II, III IV, e V - Ampla Concorrência</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado_eipot">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III, IV e V - Ampla Concorrência - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III, IV e V - Ampla Concorrência." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_eipot">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_eipot" class="form-control" value="Convoco os candidatos abaixo classificados para escolha de Guarnição, via SISCANT, às 8h do dia 25 Jul 25. Em sua área logada do SISCANT o candidato deverá clicar em “SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR” e na página que abrirá ler as orientações para a escolha da guarnição. Se ainda não estiver na vez da escolha do candidato aparecerá a mensagem AGUARDE A SUA VEZ, atualize a página até que a mensagem altere para ESCOLHA AGORA A SUA GUARNIAÇÃO." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO FINAL APÓS ETAPA I, II, III, IV e V - AMPLA CONCORRÊNCIA</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_cotas_negros" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_cotas_negros">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Classificação Final após Etapas I, II, III, IV e V - Cotas Negros</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III, IV e V - Cotas Negros - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III, IV e V - Cotas Negros." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_eipot">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_eipot" class="form-control" value="Convoco os candidatos abaixo classificados para escolha de Guarnição, via SISCANT, às 8h do dia 25 Jul 25. Em sua área logada do SISCANT o candidato deverá clicar em “SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR” e na página que abrirá ler as orientações para a escolha da guarnição. Se ainda não estiver na vez da escolha do candidato aparecerá a mensagem AGUARDE A SUA VEZ, atualize a página até que a mensagem altere para ESCOLHA AGORA A SUA GUARNIAÇÃO." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO FINAL APÓS ETAPA I, II, III, IV e V - COTAS NEGROS</button>
            </div>
        </div>
    </form>
</div>
</body>

</html>
<?php $conexao = null; ?>