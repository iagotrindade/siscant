<?php
include_once 'menu.php';

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 4574576: Página não encontrada");
    exit();
}

if($_SESSION['selecao_codigo'] != 'mfdv')
{
    erro("Erro 45644235: Página não encontrada");
    exit();
}

?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Cadastra Médico <i class="fa fa-user-md"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Cadastra Usuário</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
            <legend>Preencha os campos para cadastrar um Médico Obrigatório</legend> 
            <form action="../banco_dados/medico_obrigatorio_cadastro.php" method="post">
                <div  class="row">
                    <div  class="col-lg-6">
                        <div id="div_nome" class="form-group"> 
                            <label for="nome">Nome completo</label> 
                            <input id="nome" name="nome_completo" maxlength="120" class="form-control">
                        </div>
                        <div id="div_nome_mae" class="form-group"> 
                            <label>Nome da Mãe</label> 
                            <input id="nome_mae" name="nome_mae" maxlength="50" class="form-control" >
                        </div>
                        
                        <div  class="row">
                            <div  class="col-lg-6">
                                <div id="div_cpf" class="form-group"> 
                                    <label align="right">CPF</label><span id="cpf_mensagem"></span>
                                    <input id="cpf" name="cpf" class="form-control" onfocus="limpa_cpf()" onblur="verifica_cpf()"> 
                                </div>
                            </div>
                            <div  class="col-lg-6">
                                <div id="div_ra" class="form-group"> 
                                    <label>RA:</label>
                                    <input id="ra" maxlength="50" name="ra" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div  class="row">
                            <div  class="col-lg-6">
                                <div id="div_data_nascimento" class="form-group"> 
                                    <label>Data de nascimento</label>
                                    <input id="data_nascimento" name="data_nascimento" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            <div  class="col-lg-6">
                                <div id="div_ano_formacao" class="form-group"> 
                                    <label>Ano de Formação</label>
                                    <input id="ano_formacao" maxlength="50" name="ano_formacao" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  class="col-lg-6">
                        
                        <div id="div_mail" class="form-group"> 
                            <label>Nome do Instituto de Ensino</label>
                            <input id="mail" maxlength="50" name="nome_instituto_ensino" class="form-control">
                        </div>
                        
                        <div id="div_uf" class="form-group"> <label>UF do Instituto de Ensino</label>
                            <select id="uf" name="uf" class="form-control" onchange="busca_cidades()">
                                <option value="">Selecione a UF</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AM">AM</option>
                                <option value="AP">AP</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MG">MG</option>
                                <option value="MS">MS</option>
                                <option value="MT">MT</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="PR">PR</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RO">RO</option>
                                <option value="PR">RR</option>
                                <option value="RS">RS</option>
                                <option value="SC">SC</option>
                                <option value="SE">SE</option>
                                <option value="SP">SP</option>
                                <option value="TO">TO</option>
                            </select>
                        </div>
                        
                        <div id="div_cidade" class="form-group"> <label>Município sede da Instituição de Ensino</label>
                            <select id="cidade" name="cidade" class="form-control">
                                <option value="">Selecione primeiramente a UF</option>
                                <?php
                                    $resultado = $conexao->busca_cidade_uf($uf); 
                                    foreach ($resultado as $value) 
                                    {
                                        if($value['id'] == $id_cidade)
                                            echo '<option selected value="'.$value['id'].'">'.$value['nome'].'</option>';
                                        else
                                            echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
                                    }
                                    $conexao = null;
                                ?> 
                            </select>
                        </div>
                        
                        
                            
                        <div id="div_conselho" class="form-group"> 
                            <label>Conselho</label>
                            <input id="conselho" maxlength="100" name="conselho" class="form-control">
                        </div>
                           
                    </div>
                    
                    <div class="form-group col-lg-12" >
                        <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR</button>
                    </div>
            
                </div>
            </form>
    
</div>
        <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
</div>
</div>
</div>
</div>
<script type="text/javascript">
     //$('#om').select2();
     //$('#secao').select2();
</script> 
</body>

</html>
<?php $conexao = null; ?>