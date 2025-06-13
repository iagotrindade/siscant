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

    <form name="form_etapa_presencial" action="mpdf/convocacao_eipot_etapa_v.php" method="post">
        <div class="card">
            <h3>Convocação para Etapa V - Heteroidentificação Complementar</h3> <br><!-- Título do formulário -->

            <!-- Campo de texto para digitar o título -->
            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_etapa_v" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_etapa_v" class="form-control" value="Convocação para Etapa V - Heteroidentificação Complementar- Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_convocacao_v">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_convocacao_v" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, CONVOCA os candidatos abaixo relacionados, para comparecimento presencial à Comissão de Seleção Especial (CSE), localizada na Rua dos Andradas Nº 551 – Centro Histórico – Porto Alegre-RS, nas DATAS e HORÁRIOS abaixo." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_convocacao_iv">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_convocacao_v" class="form-control" value="Informo que será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à Etapa V - Heteroidentificação Complementar." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_tres_convocacao_iv">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_convocacao_v" class="form-control" value="Todos os candidatos convocados deverão utilizar calça, camisa com manga e calçados fechados." required>
            </div>

            <p class="alert-danger" style="padding: 10px; border-radius: 5px;">NECESSÁRIO ALTERAR SOMENTE OS HORÁRIOS DAS ARMAS QUE POSSUEM CANDIDATOS COTISTAS!</p>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - INFANTARIA:</label>
                    <input type="text" name="hora_arma[infantaria]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - CAVALARIA:</label>
                    <input type="text" name="hora_arma[cavalaria]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - ARTILHARIA DE CAMPANHA:</label>
                    <input type="text" name="hora_arma[artilharia de campanha]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - ARTILHARIA ANTIAÉREA:</label>
                    <input type="text" name="hora_arma[artilharia antiaérea]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - ENGENHARIA:</label>
                    <input type="text" name="hora_arma[engenharia]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - COMUNICAÇÕES:</label>
                    <input type="text" name="hora_arma[comunicações]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - MATERIAL BÉLICO:</label>
                    <input type="text" name="hora_arma[material bélico]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">AGENDAMENTO - INTENDÊNCIA:</label>
                    <input type="text" name="hora_arma[intendência]" class="form-control" value="20 MAIO 25 ÀS 0800h" required>
                </div>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 23 de Maio de 2025" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mb-20">PUBLICAÇÃO CONVOCAÇÃO PARA ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR </button>

            <a href="mpdf/relatorio_lista_presenca_eipot_etapa_v.php" type="submit" class="btn btn-primary btn-block">LISTA DE PRESENÇA ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR</a>
        </div>
    </form>

    <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/resultado_eipot_etapa_v.php" method="post">
        <div class="card">
            <h3>Resultado Etapa V - Heteroidentificação Complementar</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_resultado_etapa_v" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_etapa_v" class="form-control" value="Resultado Etapa V - Heteroidentificação Complementar - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_iv">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_v" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado da Etapa V - Heteroidentificação Complementar." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_iv">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_v" class="form-control" value="O período para interposição de Recursos da Etapa IV será nos dias 09 e 10 de julho de 2025 das 0930h às 1130h e das 1300h às 1630h e no dia 11 de julho de 2025 das 0800h as 1130h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_tres_resultado_iv">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_resultado_v" class="form-control" value="O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 08 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO INICIAL ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/resultado_eipot_etapa_v.php" method="post">
        <div class="card">
            <h3>Resultado Etapa V - Heteroidentificação Complementar em Grau de Recurso</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_resultado_recurso_etapa_v" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_recurso_etapa_v" class="form-control" value="Resultado Etapa V - Heteroidentificação Complementar em Grau de Recurso - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_iv">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_recurso_v" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado da Etapa V - Heteroidentificação Complementar em Grau de Recurso." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO INICIAL ETAPA V - HETEROIDENTIFICAÇÃO COMPLEMENTAR REVISORA</button>
            </div>
        </div>
    </form>
</div>
</body>

</html>
<?php $conexao = null; ?>