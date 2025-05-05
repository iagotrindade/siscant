<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 23543! Página não encontrada!");
        exit();
    }
    
    if($perfil != 'admin' && $perfil != 'consulta')
    {
        erro("Erro 37345757! Página não encontrada!");
        exit();
    }
    
 /*
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

  <form name="form_etapa_presencial" action="mpdf/relacao_inscritos_eipot_etapa_presencial.php" method="post">
    <div class="card">
        <h3>Relação de Candidatos Inscritos e Documentos Validados - Etapas Presenciais</h3> <br><!-- Título do formulário -->
    
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
                <input type="text" name="subtitulo_etapa_presencial" class="form-control" value="Relatório de Candidatos Inscritos e Documentos Validados para Etapa Presencial - Xª Região Militar" required>
            </div>
        </div>

        <div class="form-group">
            <label for="titulo_um">Texto:</label>
            <input type="text" name="texto_etapa_presencial" class="form-control" value="O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos e com documentos validados para as Etapas Presenciais na Xª RM no Processo Seletivo Simplificado para o Estágio de Instrução e de Preparação para Oficiais Temporários (EIPOT), conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025. A presente relação está em ordem alfabética, NÃO está em ordem de classificação. Os candidatos não relacionados podem verificar o motivo da não inscrição e/ou da documentação invalidada, na tela do candidato, na página inicial do SISCANT. O período para interposição de Recursos das Etapas I e II será nos dias 8 a 10 Abr 25 das 0930h às 1130h e das 1330h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre. O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
        </div>

        <div style="float: left; width: 48%; margin-right: 4%;">
            <div class="form-group">
                <label for="titulo">Data:</label>
                <input type="text" name="texto_dia"class="form-control" value="Porto Alegre - 27 de março de 2025" required>
            </div>
        </div>

        <div style="clear: both;"></div>
        <button type="submit" class="btn btn-primary btn-block">Relação de Candidatos Inscritos e Documentos Validados - Etapas Presenciais</button>
    </div>
    <br>
</form>


<form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/relacao_classificacao_eipot_pontuacao.php" method="post">
    <div class="card">
        <h3>Relação de Candidatos Inscritos e Documentos Validados- Ampla Concorrência</h3><br> <!-- Título do formulário -->
        
        <div style="float: left; width: 48%; margin-right: 4%;">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo_pontos" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
            </div>
        </div>

        <div style="float: left; width: 48%;">
            <div class="form-group">
                <label for="titulo_um">Subtítulo:</label>
                <input type="text" name="subtitulo_pontos" class="form-control" value="Relatório de Candidatos Inscritos e Documentos Validados - Ampla Concorrência - Xª Região Militar" required>
            </div>
        </div>

        <div class="form-group">
            <label for="titulo_um">Texto:</label>
            <input type="text" name="texto_pontos" class="form-control" value="O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos e com documentos validados no Processo Seletivo Simplificado para o Estágio de Instrução e de Preparação para Oficiais Temporários (EIPOT) - Ampla Concorrência, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025. A presente relação está em ordem alfabética, NÃO está em ordem de classificação. Os candidatos não relacionados podem verificar o motivo da não inscrição e/ou da documentação invalidada, na tela do candidato, na página inicial do SISCANT. O período para interposição de Recursos das Etapas I e II será nos dias 8 a 10 Abr 25 das 0930h às 1130h e das 1330h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre. O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
        </div>

        <div style="float: left; width: 48%; margin-right: 4%;">
            <div class="form-group">
                <label for="titulo">Data:</label>
                <input type="text" name="texto_dia"class="form-control" value="Porto Alegre - 27 de março de 2025" required>
            </div>
        </div>

        <div class="col-mg-12">
            <button type="submit" class="btn btn-primary btn-block">Relação de Candidatos Inscritos e Documentos Validados- Ampla Concorrência</button>
        </div>
    </div>
</form>

<form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/relacao_classificacao_eipot_pontuacao_vagas_reservadas.php" method="post">
    <div class="card">
    <h3>Relação de Candidatos Inscritos e Documentos Validados- Cotas para Negros</h3> <br>
        <div style="float: left; width: 48%; margin-right: 4%;">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo_pontos"class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
            </div>
        </div>

        <div style="float: left; width: 48%;">
            <div class="form-group">
                <label for="titulo_um">Subtítulo:</label>
                <input type="text" name="subtitulo_pontos" class="form-control" value="Relatório de Candidatos Inscritos e Documentos Validados- Cotas para Negros - Xª Região Militar" required>
            </div>
        </div>

        <div class="form-group">
                <label for="titulo_um">Texto:</label>
                <input type="text" name="texto_pontos"  class="form-control" value="O Comandante da Xª Região Militar divulga a relação dos candidatos inscritos e com documentos validados no Processo Seletivo Simplificado para o Estágio de Instrução e de Preparação para Oficiais Temporários (EIPOT) - Cotas para Negros, conforme anexo “A” (Calendário Geral de Atividades) do Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025. A presente relação está em ordem alfabética, NÃO está em ordem de classificação. Os candidatos não relacionados podem verificar o motivo da não inscrição e/ou da documentação invalidada, na tela do candidato, na página inicial do SISCANT. O período para interposição de Recursos das Etapas I e II será nos dias 8 a 10 Abr 25 das 0930h às 1130h e das 1330h às 1630h, na Comissão de Seleção Especial – Rua dos Andradas 551, Centro Histórico, Porto Alegre. O recurso deverá ser entregue presencialmente pelo candidato ou seu procurador devidamente constituído, para um dos militares integrantes da Comissão de Seleção Especial. Não serão aceitos recursos entregues fora do prazo ou no local errado." required>
        </div>
        
        <div style="float: left; width: 48%; margin-right: 4%;">
            <div class="form-group">
                <label for="titulo">Data:</label>
                <input type="text" name="texto_dia"class="form-control" value="Porto Alegre - 27 de março de 2025" required>
            </div>
        </div>

        <div class="col-mg-12">
             <button type="submit" class="btn btn-primary btn-block">Relação de Candidatos Inscritos e Documentos Validados- Cotas para Negros</button>
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
<script type="text/javascript">$('#tabela_dinamica1').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica3').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica4').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica5').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica6').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica7').DataTable({"order": [[2, "desc" ]]});</script>
<script type="text/javascript">$('#tabela_dinamica8').DataTable({"order": [[2, "desc" ]]});</script>
</body>
</html>
<?php $conexao = null; ?>