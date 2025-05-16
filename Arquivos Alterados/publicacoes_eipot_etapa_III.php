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


//$lista_candidatos = $conexao->get_todos_candidatos(); 
/* $arma_eipot = null;
    if(isset($_GET['select_arma']))
        $arma_eipot = $_GET['select_arma']; */

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

    <form name="form_etapa_presencial" action="mpdf/convocacao_eipot_etapa_presencial.php" method="post">
        <div class="card">
            <h3>Convocação para Etapa III</h3> <br><!-- Título do formulário -->

            <!-- Campo de texto para digitar o título -->
            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_etapa_presencial" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_etapa_presencial" class="form-control" value="Convocação para Etapa III - Inspeção de Saúde - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo_um">Texto:</label>
                <input type="text" name="texto_etapa_presencial" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, CONVOCA os candidatos abaixo relacionados, para comparecimento presencial à Comissão de Seleção Especial (CSE), localizada na Rua Bento Martins Nº 45 – Centro – Porto Alegre-RS, nas DATAS e HORÁRIOS abaixo, munidos dos exames de saúde previstos no aviso de convocação. Será ELIMINADO o candidato CONVOCADO que NÃO COMPARECER à chamada para a Etapa III." required>
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
            <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO CONVOCAÇÃO PARA ETAPA III - INSPEÇÃO DE SAÚDE</button>
        </div>
        <br>
    </form>


    <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/resultado_eipot_etapa_III.php" method="post">
        <div class="card">
            <h3>Resultado Etapa III - Inspeção de Saúde</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo_resultado_etapa_presencial" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_etapa_presencial" class="form-control" value=" Resultado Etapa III - Inspeção de Saúde - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo_um">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_etapa_presencial" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado da Etapa III." required>
            </div>

            <div class="form-group">
                <label for="titulo_um">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_etapa_presencial" class="form-control" value="O período para interposição de Recursos da Etapa III será nos dias 26 a 28 de maio de 2025 das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre." required>
            </div>

            <div class="form-group">
                <label for="titulo_um">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_resultado_etapa_presencial" class="form-control" value="O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 23 de Maio de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO INICIAL ETAPA III - INSPEÇÃO DE SAÚDE</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/resultado_eipot_isgrec.php" method="post">
        <div class="card">
            <h3>Resultado Inspeção de Saúde em Grau de Recurso</h3>
            
            <br>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo" class="form-control" value="Resultado Inspeção de Saúde em Grau de Recurso - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo_um">Texto:</label>
                <input type="text" name="texto_resultado_isgrec" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado da Inspeção de Saúde em Grau de Recurso (ISGRec)." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS,  6 de junho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO ISGRec</button>
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
<script type="text/javascript">
    $('#tabela_dinamica1').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica3').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica4').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica5').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica6').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica7').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica8').DataTable({
        "order": [
            [2, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>