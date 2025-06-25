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

    <form name="form_relacao_classificacao_eipot_ampla" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_ampla_concorrencia">
        <div class="card">
            <h3>Ranking após Etapas I, II, III e IV - Ampla Concorrência</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado_eipot">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III e IV - Ampla Concorrência - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III e IV - Ampla Concorrência." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO APÓS ETAPA I, II, III E IV - AMPLA CONCORRÊNCIA</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_cotas_negros" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_cotas_negros">
        <div class="card">
            <h3>Ranking após Etapas I, II, III e IV - Cotas Negros</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III e IV - Cotas Negros - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III e IV - Cotas Negros." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO APÓS ETAPA I, II, III E IV - COTAS NEGROS</button>
            </div>
        </div>
    </form>
</div>
</body>

</html>
<?php $conexao = null; ?>