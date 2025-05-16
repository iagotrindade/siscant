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

$get_recurso_rm = $conexao->get_recurso_rm($rm_usuario);
$data_inicio_recurso = $get_recurso_rm[0]['data_inicio_recurso'];
$data_fim_recurso = $get_recurso_rm[0]['data_fim_recurso'];
$data_inicio_recurso = trata_data($data_inicio_recurso);
$data_fim_recurso = trata_data($data_fim_recurso);
$mostrar_recurso  = $get_recurso_rm[0]['mostrar_recurso'];

//var_dump($mostrar_recurso); exit;
//   $data_inicio_recurso = $conexao->get_data_recursos($rm_usuario);

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


    <form name="form_etapa_presencial" action="mpdf/relatorio_recursos.php" method="post">
        <div class="card">
            <h3>Resultado Análise de Recursos Etapas I e II</h3> <br><!-- Título do formulário -->

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
                    <input type="text" name="subtitulo_etapa_presencial" class="form-control" value="Relatório da Análise de Recursos Etapas I e II - Xª Região Militar" required>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo_um">Texto:</label>
                <input type="text" name="texto_etapa_presencial" class="form-control" value="O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente às Etapas I e II, conforme Anexo “A” (Calendário de Eventos) do Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025. Os candidatos cujo recurso foi DEFERIDO estão considerados inscritos e com documentação validada." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - XX de XXXX de XXXX" required>
                </div>
            </div>

            <div style="clear: both;"></div>
            <button type="submit" class="btn btn-primary btn-block">RELATÓRIO DE ANÁLISE DE RECURSOS ETAPAS I e II</button>
        </div>
    </form>

    <form name="form_etapa_presencial" action="mpdf/resultado_recursos_etapa_iii.php" method="post">
        <div class="card">
            <h3>
                Resultado Análise de Recursos Etapa III
            </h3>

            <br><!-- Título do formulário -->

            <!-- Campo de texto para digitar o título -->
            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="titulo_um">Subtítulo:</label>
                    <input type="text" name="subtitulo" class="form-control" value="Relatório da Análise de Recursos Etapa III e Convocação ISGRec- Xª Região Militar" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado">Texto Resultado Análise Recursos (1º Parágrafo):</label>
                <textarea type="text" name="paragrafo_um_resultado" class="form-control" required>O Comandante da Xª Região Militar divulga o parecer da análise de recursos referente à Etapa III e convoca para  Inspeção de Saúde em Grau de Recurso (ISGR), conforme Anexo “A” (Calendário de Eventos) do Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025.</textarea>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado">Texto Resultado Análise Recursos (2º Parágrafo):</label>
                <textarea style="height: 40px;" type="text" name="paragrafo_dois_resultado" class="form-control" required>A presente relação NÃO está em ordem de CLASSIFICAÇÃO.</textarea>
            </div>

            <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>

            <div class="form-group">
                <label for="paragrafo_um_convocacao">Texto Convocação ISGRec (1º Parágrafo):</label>
                <textarea style="height: 40px;" type="text" name="paragrafo_um_convocacao" class="form-control" value="">Convoco os candidatos abaixo relacionados para comparecimento nos respectivos locais e datas discriminados a fim de realizarem Inspeção de Saúde em Grau de Recurso.</textarea>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_convocacao">Texto Convocação ISGRec (2º Parágrafo):</label>
                <textarea style="height: 40px;" type="text" name="paragrafo_dois_convocacao" class="form-control" value="">Informo que será ELIMINADO do processo seletivo o candidato CONVOCADO que NÃO COMPARECER na data, horário e local determinado.</textarea>
            </div>

            <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>

            <p class="alert-danger" style="padding: 10px; border-radius: 5px;">PARA A QUEBRA DE LINHAS UTILIZAR " # " ANTES DO ENDEREÇO!</p>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[infantaria]">AGENDAMENTO ISGR - INFANTARIA:</label>
                    <input type="text" name="hora_arma[infantaria]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[cavalaria]">AGENDAMENTO ISGR - CAVALARIA:</label>
                    <input type="text" name="hora_arma[cavalaria]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[artilharia de campanha]">AGENDAMENTO ISGR - ARTILHARIA DE CAMPANHA:</label>
                    <input type="text" name="hora_arma[artilharia de campanha]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[artilharia antiaérea]">AGENDAMENTO ISGR - ARTILHARIA ANTIAÉREA:</label>
                    <input type="text" name="hora_arma[artilharia antiaérea]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[engenharia]">AGENDAMENTO ISGR - ENGENHARIA:</label>
                    <input type="text" name="hora_arma[engenharia]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[comunicações]">AGENDAMENTO ISGR - COMUNICAÇÕES:</label>
                    <input type="text" name="hora_arma[comunicações]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[material bélico]">AGENDAMENTO ISGR - MATERIAL BÉLICO:</label>
                    <input type="text" name="hora_arma[material bélico]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="hora_arma[intendência]">AGENDAMENTO ISGR - INTENDÊNCIA:</label>
                    <input type="text" name="hora_arma[intendência]" class="form-control" value="03 JUNHO 25 ÀS 0800h – POLICLÍNICA MILITAR DE PORTO ALEGRE # Avenida João Pessoa, 651 – Cidade Baixa, Porto Alegre/RS
" required>
                </div>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 30 de Maio de 2025" required>
                </div>
            </div>

            <div style="clear: both;"></div>
            <button type="submit" class="btn btn-primary btn-block">RELATÓRIO DE ANÁLISE DE RECURSOS ETAPA III</button>
        </div>
        <br>
    </form>
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable();
</script>
</body>

</html>

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