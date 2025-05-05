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
        <h3>Relatório de Análise de Recursos Etapas I e II</h3> <br><!-- Título do formulário -->
    
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
                <input type="text" name="texto_dia"class="form-control" value="Porto Alegre - XX de XXXX de XXXX" required>
            </div>
        </div>

        <div style="clear: both;"></div>
        <button type="submit" class="btn btn-primary btn-block">RELATÓRIO DE ANÁLISE DE RECURSOS ETAPAS I e II</button>
    </div>
</form>

 
</div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable();</script>
<script type="text/javascript">$('#tabela_dinamica2').DataTable();</script>
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