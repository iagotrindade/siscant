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
$lista_candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Publicações<i class="fa fa-pencil"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Publicações</li>
            </ul>
        </div>
    </div>

    <form name="form_etapa_presencial" action="mpdf/convocacao_eipot_etapa_iv.php" method="post">
        <div class="card">
            <h3>Convocação para Etapa IV</h3> <br><!-- Título do formulário -->

            <!-- Campo de texto para digitar o título -->
            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_etapa_iv" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_etapa_iv" class="form-control" value="Convocação para Etapa IV - Exame de Aptidão Física - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_convocacao_iv">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_convocacao_iv" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga a chamada dos candidatos selecionados para a Etapa IV – EXAME DE APTIDÃO FÍSICA." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_convocacao_iv">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_convocacao_iv" class="form-control" value="Informo que será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à Etapa IV." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_tres_convocacao_iv">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_convocacao_iv" class="form-control" value="Todos os candidatos deverão utilizar trajes desportivos para realização dos exames de aptidão física." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_quatro_convocacao_iv">Quarto Parágrafo:</label>
                <input type="text" name="paragrafo_quatro_convocacao_iv" class="form-control" value="O candidato deverá apresentar, obrigatoriamente, documento de identificação com foto e o Anexo F - Termo de Responsabilidade para Participação do EAF. A não entrega do referido anexo, quando da apresentação do candidato, conforme acima descrito, inviabiliza sua participação no EAF, tendo por objetivo preservar sua saúde." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 23 de Maio de 2025" required>
                </div>
            </div>

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
                    <label for="titulo_um">>AGENDAMENTO - ARTILHARIA ANTIAÉREA:</label>
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

            <div style="clear: both;"></div>
            <button type="submit" class="btn btn-primary btn-block mb-20">PUBLICAÇÃO CONVOCAÇÃO PARA ETAPA IV - EAF</button>

            <a href="mpdf/relatorio_lista_presenca_eipot_etapa_iv.php" type="submit" class="btn btn-primary btn-block">LISTA DE PRESENÇA ETAPA IV - EAF</a>
        </div>
        <br>
    </form>

    <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/resultado_eipot_etapa_iv.php" method="post">
        <div class="card">
            <h3>Resultado Etapa IV - EAF</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_resultado_etapa_iv" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_etapa_iv" class="form-control" value="Resultado Etapa IV - EAF - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_iv">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_iv" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado da Etapa IV." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_iv">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_iv" class="form-control" value="O período para interposição de Recursos da Etapa IV será nos dias 18, 23 e 24  de junho de 2025 das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_tres_resultado_iv">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_resultado_iv" class="form-control" value="O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 17 de Junho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO INICIAL ETAPA IV - EAF</button>
            </div>
        </div>
    </form>
    <br>
    <br>
    <!--<a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a> -->
</div>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
</body>

</html>
<?php $conexao = null; ?>