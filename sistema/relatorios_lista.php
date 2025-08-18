<?php
include_once 'menu.php';

if($perfil == "ouvidor")
{
    erro("Erro: 24373478568! Não foi possível abrir a página");
    exit();
}

?>



<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Relatórios <i class="fa fa-file-text-o"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Relatórios</li>
      </ul>
    </div>
  </div>
    
    
  <div class="row">
    <div class="col-md-12">
        <div class="card" <?php if($perfil == "documentos") echo " hidden " ?>>
            <legend>Candidatos e Especialidades</legend> 
            <div class="row">
                <div class="col-lg-4" <?php if($perfil == "avaliador") echo " hidden " ?>>
                    <a href="relatorio_especialidade_quantidade.php">
                    <div class="widget-small info"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Especialidade &nbsp;  X &nbsp; Quantidade</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a href="relatorio_especialidade_candidato.php">
                    <div class="widget-small info"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Candidato &nbsp; X &nbsp; Especialidade</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4" <?php if($perfil == "avaliador") echo " hidden " ?>>
                    <a href="relatorio_status_especialidade.php">
                    <div class="widget-small info"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Status da Especialidade</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
      
      <div class="card" <?php if($perfil != "admin" && $perfil != "consulta" && $perfil != "avaliador") echo " hidden " ?>>
            <legend>Currículos</legend> 
            <div class="row">

                <div class="col-lg-3" <?php if($perfil != "admin" && $perfil != "consulta") echo " hidden " ?>>
                    <a href="curriculos_faltando_candidatos.php">
                    <div class="widget-small primary"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Currículos Faltantes dos Candidatos</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3" <?php if($perfil != "admin" && $perfil != "consulta") echo " hidden " ?>>
                    <a href="pericia_curricular.php">
                    <div class="widget-small primary"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Perícia Curricular</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="pontuacao_nao_avaliada.php">
                    <div class="widget-small primary"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Auditoria curricular</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3" <?php if($perfil != "admin" && $perfil != "consulta") echo " hidden " ?>>
                    <a href="pontuacao_automatica.php">
                    <div class="widget-small primary"><i class="icon fa fa-file-text-o fa-3x"></i>
                      <div class="info">
                          <h4><b>Pontuação Automática</b></h4>
                      </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card" <?php if($perfil == "avaliador") echo " hidden " ?>>
            <legend>Documentos Obrigatórios</legend> 
            <div class="row">

                <div class="col-lg-6"  >
                    <a href="candidato_lista_docs_obrigatorios.php">
                    <div class="widget-small alert-laranja"><font color="black"><i class="icon fa fa-file-text-o fa-3x"></i></font>
                      <div class="info">
                          <h4><font color="black">Avaliação de Docs Obrigatórios</font></h4>
                      </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-lg-6" <?php if($perfil != "admin" && $perfil != "consulta") echo " hidden " ?>>
                    <a href="doc_obrigatorio_faltando_candidato.php">
                    <div class="widget-small alert-laranja"><font color="black"><i class="icon fa fa-file-text-o fa-3x"></i></font>
                      <div class="info">
                          <h4><font color="black">Documentos Obrigatórios faltantes</font></h4>
                      </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
      <div class="card" <?php if($perfil != "admin" && $perfil != "consulta" || $_SESSION['selecao_pagamento'] == null || $_SESSION['selecao_pagamento'] == 0) echo " hidden " ?>>
            <legend>Pagamentos</legend> 
            <div class="row">
                <div class="col-lg-6">
                    <a href="gru_pagas.php">
                    <div class="widget-small alert-danger"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">Pagamentos da GRU</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-6" <?php if($perfil == "avaliador") echo ' hidden '; ?> >
                    <a href="relatorio_isentos_pagamento.php">
                    <div class="widget-small alert-danger"><font color="black"><i class="icon fa fa-file-text-o fa-3x"></font></i>
                      <div class="info">
                          <h4><font color="black">Isentos do pagamento</font></h4>
                      </div>
                    </div>
                    </a>
                </div>
                
            </div>
        </div>
      <div class="card" <?php if($perfil != "admin" && $perfil != "consulta" && $perfil != 'avaliador') echo " hidden " ?>>
            <legend>Outros</legend> 
            <div class="row">
                <div class="col-lg-4" <?php if($perfil != "admin" && $perfil != "consulta") echo ' hidden ' ?>>
                    <a href="relatorios.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <!-- 04/08/2025 -> Iago Silva Alteração de nome do CARD-->
                          <h4><font color="black">PUBLICAÇÕES E GERAÇÃO DE DOCUMENTOS</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4" <?php if($perfil != "admin" && $perfil != "consulta") echo ' hidden ' ?>>
                    <a href="candidatos_tempo_sv_publico.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">Serviço Militar e Idade</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a href="relatorio_recursos_candidato.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">Recursos</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4" <?php if($perfil != "admin" && $perfil != "consulta") echo ' hidden ' ?>>
                    <a href="relatorio_processos_judiciais.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">Processos Judiciais</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4" <?php if($perfil != "admin" && $perfil != "consulta") echo ' hidden ' ?>>
                    <a href="relatorio_incorporados.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">Distribuidos</h4>
                      </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-4" <?php if($perfil != "admin" && $perfil != "consulta") echo ' hidden ' ?>>
                    <a href="relatorio_prioridades_especialidade.php">
                    <div class="widget-small alert-success"><font color="black"><i class="icon fa fa-file-text-o fa-3x"> </font></i>
                      <div class="info">
                          <h4><font color="black">PRIORIDADE DAS ESPECIALIDADES</h4>
                      </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        
    <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
</body>

</html>
<?php $conexao = null; ?>