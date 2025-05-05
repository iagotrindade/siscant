<?php
    include_once './menu.php';
    include_once 'codigos/funcao_apagar.php';
    if(!isset($_GET['id_usuario']))
    {
        erro("Erro 3426347547! Médico não encontrado!"); 
        exit();
    }
    
    if((int)$_GET['id_usuario'] <= 0)
    {
        erro("Erro 2365325346! Médico não encontrado!"); 
        exit();
    }
    
    $usuario_visualiza = $conexao->get_usuario_id($_GET['id_usuario']);  
    include_once './codigos/variaveis_usuario_visualiza.php';
    
    if($medico_obrigatorio != 1)
    {
        erro("Erro 2534646! Médico não encontrado!"); 
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 235346367! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    if(isset($_GET['salvo']))
    {
        echo '<script type="text/javascript">
        window.onload = function() 
        {
            $.notify({
                    title: "<center><b>SUCESSO!</b><br> ",
                    message: " Informações Salvas"
            },{
                    type: "info"
            });
        };
        </script>';
    }
    
?>

<div class="content-wrapper">
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <form action="../banco_dados/medico_obrigatorio_edita.php" method="post" >
        
        <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
        <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
        
            <section class="invoice">
                <div class="row">
                  <div class="col-xs-12">
                    <legend>Atualizar informações do Médico Obrigatório <i class="fa fa-id-card-o"></i> 
                    </legend> 
                  </div>
                </div>

        <!-- 
        **********************
            DADOS PESSOAIS 
        **********************
        -->      

        <div class="row">
            <div class="col-md-12">
                <div id="div_dados_pessoais" class="alert alert-dismissible alert-info">
                    <legend>Dados Pessoais</legend> 
                    <div  class="row">
                        
                        <div class="col-lg-12">
                            <label>ANO DA SELEÇÃO</label>
                            <select name="ano_selecao_medico_obrigatorio" class="form-control">
                                <option value="">Selecione a opção</option>
                                <?php
                                
                                    for($i=2010; $i <= date('Y')+3; $i++)
                                    {
                                        if($ano_selecao_medico_obrigatorio == $i)
                                            echo '<option selected value="'.$i.'">'.$i.'</option>';
                                        else
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                
                                ?>
                            </select>
                            <br>
                        </div>
                        
                        <div  class="col-lg-4">
                            <div id="div_nome_completo" class="form-group"> 
                                <label for="nome">Nome completo</label> 
                                <input id="nome_completo" name="nome_completo" maxlength="120" value="<?php echo $nome_completo ?>" class="form-control">
                            </div>
                            <div id="div_cpf" class="form-group"> 
                                <label align="right">CPF</label><span id="cpf_mensagem"></span>
                                <input id="cpf" name="cpf" class="form-control" onfocus="limpa_cpf()" disabled value="<?php echo $cpf ?>"  onblur="verifica_cpf()"> 
                            </div>
                            <div id="div_identidade" class="form-group"> 
                                <label>Identidade (Número/ Órgão expedidor)</label> 
                                <input id="identidade" name="identidade" maxlength="20" value="<?php echo $identidade ?>" class="form-control" >
                            </div>

                            <div id="div_nome_usuario" class="form-group"> 
                                <label>Data de nascimento</label>
                                <input id="data_nascimento" name="data_nascimento" value="<?php echo $data_nascimento ?>" maxlength="25" class="form-control" >
                            </div>
                        </div>

                        <div  class="col-lg-4">
                            <div class="form-group"> <label>Sexo</label>
                                <select id="sexo" name="sexo" class="form-control" onchange="select_sexo()">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($sexo == 'masculino') echo 'selected' ?> value="masculino">Masculino</option>
                                    <option <?php if ($sexo == 'feminino') echo 'selected'  ?> value="feminino">Feminino</option>
                                </select>
                            </div>
                            <div id="div_nascionalidade" class="form-group">
                                <label>Nacionalidade (País)</label>
                                <input id="nascionalidade" value="<?php echo $nacionalidade ?>" maxlength="30" name="nascionalidade" class="form-control">
                            </div>
                            <div id="div_naturalidade" class="form-group">
                                <label>Naturalidade (Cidade)</label>
                                <input id="naturalidade" value="<?php echo $naturalidade ?>" maxlength="30" name="naturalidade" class="form-control">
                            </div>


                            <div class="form-group" <?php if($codigo_selecao != 'mfdv') echo'hidden'?>>
                                <label>Dependentes</label>
                                <select name="num_dependentes" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($dependente == '0') echo " selected"; ?> value="0">Não possuo dependentes</option>
                                    <option <?php if($dependente == '1') echo " selected"; ?> value="1">Possuo 1 dependente</option>
                                    <option <?php if($dependente == '2') echo " selected"; ?> value="2">Possuo 2 dependentes</option>
                                    <option <?php if($dependente == '3') echo " selected"; ?> value="3">Possuo 3 dependentes</option>
                                    <option <?php if($dependente == '4') echo " selected"; ?> value="4">Possuo 4 dependentes</option>
                                    <option <?php if($dependente == '5') echo " selected"; ?> value="5">Possuo 5 dependentes</option>
                                    <option <?php if($dependente == '6') echo " selected"; ?> value="6">Possuo 6 dependentes</option>
                                    <option <?php if($dependente == '7') echo " selected"; ?> value="7">Possuo 7 dependentes</option>
                                    <option <?php if($dependente == '8') echo " selected"; ?> value="8">Possuo 8 dependentes</option>
                                </select>
                            </div>

                        </div>

                        <div  class="col-lg-4">


                            <div id="div_estado_civil" class="form-group"> <label>Estado Civil</label>
                                <select id="estado_civil" name="estado_civil" class="form-control">
                                    <option value="">Selecione o Estado Civil</option>
                                    <option <?php if ($estado_civil == 'solteiro') echo 'selected' ?> value="solteiro">Solteiro </option>
                                    <option <?php if ($estado_civil == 'uniao_estavel') echo 'selected' ?> value="uniao_estavel">União Estável </option>
                                    <option <?php if ($estado_civil == 'casado') echo 'selected' ?> value="casado">Casado </option>
                                    <option <?php if ($estado_civil == 'viuvo') echo 'selected' ?> value="viuvo">Viúvo </option>
                                    <option <?php if ($estado_civil == 'outro') echo 'selected' ?> value="outro">Outro</option>
                                </select>
                            </div>

                            <div id="div_filiacao_mae" class="form-group"> 
                                <label>Filiação (Mãe)</label>
                                <input id="filiacao_mae" value="<?php echo $mae ?>" maxlength="120" name="filiacao_mae" class="form-control">
                            </div>

                            <div id="div_filiacao_pai" class="form-group">
                                <label>Filiação (Pai)</label>
                                <input id="filiacao_pai" maxlength="120" value="<?php echo $pai ?>" name="filiacao_pai" class="form-control">
                            </div>

                                <div id="div_nome_social" class="form-group"> 
                                    <label for="nome_social">Nome social</label> 
                                    <input id="nome_social" name="nome_social" value="<?php echo $nome_social ?>" maxlength="120" class="form-control">
                                </div>

                        </div>


                        <div  class="col-lg-12">
                            <div id='div_erro_dados_pessoais' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_dados_pessoais"></p></center></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        **********************
            INSTITUIÇÃO DE ENSINO 
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div id="div_endereco" class="alert alert-dismissible alert-info">
                    <legend>Ensino </legend> 
                    <div class="row">
                        <div  class="col-lg-4">
                            <div class="form-group">
                                <label>Nome do Instituto de Ensino</label>
                                <input value="<?php echo $instituto_ensino ?>" maxlength="200" name="nome_ie" class="form-control">
                            </div>
                        </div>
                        <div  class="col-lg-2">
                            <div id="div_rua" class="form-group">
                                <label>Ano de formação</label>
                                <input value="<?php echo $ano_formacao ?>" maxlength="200" name="ano_formacao" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-2">
                            <div id="div_uf" class="form-group"> <label>UF da IE</label>
                                <select id="uf_ie" name="uf_ie" class="form-control" onchange="busca_cidades_ie()">
                                    <option value="">Selecione a UF</option>
                                    <option <?php if ($uf_instituto_ensino == 'AC') echo 'selected' ?> value="AC">AC</option>
                                    <option <?php if ($uf_instituto_ensino == 'AL') echo 'selected' ?> value="AL">AL</option>
                                    <option <?php if ($uf_instituto_ensino == 'AM') echo 'selected' ?> value="AM">AM</option>
                                    <option <?php if ($uf_instituto_ensino == 'AP') echo 'selected' ?> value="AP">AP</option>
                                    <option <?php if ($uf_instituto_ensino == 'BA') echo 'selected' ?> value="BA">BA</option>
                                    <option <?php if ($uf_instituto_ensino == 'CE') echo 'selected' ?> value="CE">CE</option>
                                    <option <?php if ($uf_instituto_ensino == 'DF') echo 'selected' ?> value="DF">DF</option>
                                    <option <?php if ($uf_instituto_ensino == 'ES') echo 'selected' ?> value="ES">ES</option>
                                    <option <?php if ($uf_instituto_ensino == 'GO') echo 'selected' ?> value="GO">GO</option>
                                    <option <?php if ($uf_instituto_ensino == 'MA') echo 'selected' ?> value="MA">MA</option>
                                    <option <?php if ($uf_instituto_ensino == 'MG') echo 'selected' ?> value="MG">MG</option>
                                    <option <?php if ($uf_instituto_ensino == 'MS') echo 'selected' ?> value="MS">MS</option>
                                    <option <?php if ($uf_instituto_ensino == 'MT') echo 'selected' ?> value="MT">MT</option>
                                    <option <?php if ($uf_instituto_ensino == 'PA') echo 'selected' ?> value="PA">PA</option>
                                    <option <?php if ($uf_instituto_ensino == 'PB') echo 'selected' ?> value="PB">PB</option>
                                    <option <?php if ($uf_instituto_ensino == 'PE') echo 'selected' ?> value="PE">PE</option>
                                    <option <?php if ($uf_instituto_ensino == 'PI') echo 'selected' ?> value="PI">PI</option>
                                    <option <?php if ($uf_instituto_ensino == 'PR') echo 'selected' ?> value="PR">PR</option>
                                    <option <?php if ($uf_instituto_ensino == 'RJ') echo 'selected' ?> value="RJ">RJ</option>
                                    <option <?php if ($uf_instituto_ensino == 'RN') echo 'selected' ?> value="RN">RN</option>
                                    <option <?php if ($uf_instituto_ensino == 'RO') echo 'selected' ?> value="RO">RO</option>
                                    <option <?php if ($uf_instituto_ensino == 'RR') echo 'selected' ?> value="RR">RR</option>
                                    <option <?php if ($uf_instituto_ensino == 'RS') echo 'selected' ?> value="RS">RS</option>
                                    <option <?php if ($uf_instituto_ensino == 'SC') echo 'selected' ?> value="SC">SC</option>
                                    <option <?php if ($uf_instituto_ensino == 'SE') echo 'selected' ?> value="SE">SE</option>
                                    <option <?php if ($uf_instituto_ensino == 'SP') echo 'selected' ?> value="SP">SP</option>
                                    <option <?php if ($uf_instituto_ensino == 'TO') echo 'selected' ?> value="TO">TO</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-lg-2">
                                <div id="div_cidade" class="form-group"> <label>Cidade da IE</label>
                                    <select id="cidade_ie" name="cidade_ie" class="form-control">
                                        <option value="">Selecione primeiramente a UF</option>
                                        <?php
                                            $resultado2 = $conexao->busca_cidade_uf($uf_instituto_ensino); 
                                            foreach ($resultado2 as $value) 
                                            {
                                                if($value['id'] == $id_cidade_instituto_ensino)
                                                    echo '<option selected value="'.$value['id'].'">'.$value['nome'].'</option>';
                                                else
                                                    echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                        <div  class="col-lg-2">
                            <div id="div_rua" class="form-group">
                                <label>Conselho</label>
                                <input value="<?php echo $conselho ?>" maxlength="100" name="conselho" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- 
        **********************
            ENDEREÇO 
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div id="div_endereco" class="alert alert-dismissible alert-info">
                    <legend>Endereço </legend> 
                    <div class="row">
                        <div  class="col-lg-4">
                            <div id="div_uf" class="form-group"> <label>UF</label>
                                <select id="uf" name="uf" class="form-control" onchange="busca_cidades()">
                                    <option value="">Selecione a UF</option>
                                    <option <?php if ($uf == 'AC') echo 'selected' ?> value="AC">AC</option>
                                    <option <?php if ($uf == 'AL') echo 'selected' ?> value="AL">AL</option>
                                    <option <?php if ($uf == 'AM') echo 'selected' ?> value="AM">AM</option>
                                    <option <?php if ($uf == 'AP') echo 'selected' ?> value="AP">AP</option>
                                    <option <?php if ($uf == 'BA') echo 'selected' ?> value="BA">BA</option>
                                    <option <?php if ($uf == 'CE') echo 'selected' ?> value="CE">CE</option>
                                    <option <?php if ($uf == 'DF') echo 'selected' ?> value="DF">DF</option>
                                    <option <?php if ($uf == 'ES') echo 'selected' ?> value="ES">ES</option>
                                    <option <?php if ($uf == 'GO') echo 'selected' ?> value="GO">GO</option>
                                    <option <?php if ($uf == 'MA') echo 'selected' ?> value="MA">MA</option>
                                    <option <?php if ($uf == 'MG') echo 'selected' ?> value="MG">MG</option>
                                    <option <?php if ($uf == 'MS') echo 'selected' ?> value="MS">MS</option>
                                    <option <?php if ($uf == 'MT') echo 'selected' ?> value="MT">MT</option>
                                    <option <?php if ($uf == 'PA') echo 'selected' ?> value="PA">PA</option>
                                    <option <?php if ($uf == 'PB') echo 'selected' ?> value="PB">PB</option>
                                    <option <?php if ($uf == 'PE') echo 'selected' ?> value="PE">PE</option>
                                    <option <?php if ($uf == 'PI') echo 'selected' ?> value="PI">PI</option>
                                    <option <?php if ($uf == 'PR') echo 'selected' ?> value="PR">PR</option>
                                    <option <?php if ($uf == 'RJ') echo 'selected' ?> value="RJ">RJ</option>
                                    <option <?php if ($uf == 'RN') echo 'selected' ?> value="RN">RN</option>
                                    <option <?php if ($uf == 'RO') echo 'selected' ?> value="RO">RO</option>
                                    <option <?php if ($uf == 'RR') echo 'selected' ?> value="RR">RR</option>
                                    <option <?php if ($uf == 'RS') echo 'selected' ?> value="RS">RS</option>
                                    <option <?php if ($uf == 'SC') echo 'selected' ?> value="SC">SC</option>
                                    <option <?php if ($uf == 'SE') echo 'selected' ?> value="SE">SE</option>
                                    <option <?php if ($uf == 'SP') echo 'selected' ?> value="SP">SP</option>
                                    <option <?php if ($uf == 'TO') echo 'selected' ?> value="TO">TO</option>
                                </select>
                            </div>
                            <div id="div_bairro" class="form-group">
                                <label>Bairro</label>
                                <input id="bairro" value="<?php echo $bairro ?>" maxlength="40" name="bairro" class="form-control">
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div id="div_cidade" class="form-group"> <label>Cidade</label>
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
                                    ?> 
                                </select>
                            </div>

                            <div id="div_cep" class="form-group">
                                <label>CEP</label>
                                <input id="cep" value="<?php echo $cep ?>" maxlength="20" name="cep" class="form-control">
                            </div>
                        </div>
                        <div  class="col-lg-4">
                            <div id="div_rua" class="form-group">
                                <label>Avenida/Rua, número e complemento</label>
                                <input id="rua" value="<?php echo $rua_num_complemento ?>" maxlength="200" name="rua" class="form-control">
                            </div>

                        </div>
                        <div  class="col-lg-12">
                            <div id='div_erro_endereco' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_endereco"></p></center></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        **********************
            CONTATO 
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-info">
                    <legend>Contato   </legend> 
                    <div  class="row">

                        <div  class="col-lg-3">
                            <div id="div_celular" class="form-group"> 
                                <label>Telefone para CONTATO</label> <font color="red"> *Coloque o DDD</font>
                                <input id="celular" value="<?php echo $tel_celular ?>" maxlength="50" name="celular" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-3">
                            <div id="div_telefone" class="form-group"> 
                                <label>Telefone para RECADOS</label> <font color="red"> *Coloque o DDD</font>
                                <input id="telefone" value="<?php echo $tel_residencial ?>" maxlength="50" name="telefone" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div id="div_mail" class="form-group"> 
                                <label>E-Mail</label>
                                <input id="mail" value="<?php echo $mail ?>" maxlength="50" name="mail" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div id="div_mail2" class="form-group"> 
                                <label>Repita o seu E-Mail</label>
                                <input id="mail2" value="<?php echo $mail ?>" maxlength="50" name="mail2" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-12">
                            <div id='div_erro_contato' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_contato"></p></center></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- 
        **********************
            TEMPO DE SERVIÇO PÚBLICO 
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-info">
                    <legend>Tempo de serviço público até a data final da inscrição</legend> 
                    <div  class="row">
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_pub" class="form-group"> <label>Possui tempo de serviço público</label>
                                <select id="tempo_sv_pub" name="tempo_sv_pub" class="form-control" onchange="tempo_servico_publico()">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($tempo_sv_pub == '0') echo 'selected' ?> value="0">Não</option>
                                    <option <?php if ($tempo_sv_pub == '1') echo 'selected' ?> value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_pub_anos" class="form-group">  <label>Anos</label>
                                <select id="tempo_sv_pub_anos" name="tempo_sv_pub_anos" class="form-control">
                                    <option value="">Selecione quantos anos</option>
                                    <option <?php if ($tempo_sv_pub_anos == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_pub_anos == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_pub_anos == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_pub_anos == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_pub_anos == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_pub_anos == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_pub_anos == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_pub_anos == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_pub_anos == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_pub_anos == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_pub_anos == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_pub_anos == '11') echo 'selected' ?> value="11">11</option>
                                    <option <?php if ($tempo_sv_pub_anos == '12') echo 'selected' ?> value="12">12</option>
                                    <option <?php if ($tempo_sv_pub_anos == '13') echo 'selected' ?> value="13">13</option>
                                    <option <?php if ($tempo_sv_pub_anos == '14') echo 'selected' ?> value="14">14</option>
                                    <option <?php if ($tempo_sv_pub_anos == '15') echo 'selected' ?> value="15">15</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div  id="div_tempo_sv_pub_meses" class="form-group"> <label>Meses</label>
                                <select id="tempo_sv_pub_meses" name="tempo_sv_pub_meses" class="form-control">
                                    <option value="">Selecione quantos meses</option>
                                    <option <?php if ($tempo_sv_pub_meses == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_pub_meses == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_pub_meses == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_pub_meses == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_pub_meses == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_pub_meses == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_pub_meses == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_pub_meses == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_pub_meses == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_pub_meses == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_pub_meses == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_pub_meses == '11') echo 'selected' ?> value="11">11</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div  id="div_tempo_sv_pub_dias" class="form-group">  <label>Dias</label>
                                <select id="tempo_sv_pub_dias" name="tempo_sv_pub_dias" class="form-control">
                                    <option value="">Selecione quantos dias</option>
                                    <option <?php if ($tempo_sv_pub_dias == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_pub_dias == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_pub_dias == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_pub_dias == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_pub_dias == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_pub_dias == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_pub_dias == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_pub_dias == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_pub_dias == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_pub_dias == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_pub_dias == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_pub_dias == '11') echo 'selected' ?> value="11">11</option>
                                    <option <?php if ($tempo_sv_pub_dias == '12') echo 'selected' ?> value="12">12</option>
                                    <option <?php if ($tempo_sv_pub_dias == '13') echo 'selected' ?> value="13">13</option>
                                    <option <?php if ($tempo_sv_pub_dias == '14') echo 'selected' ?> value="14">14</option>
                                    <option <?php if ($tempo_sv_pub_dias == '15') echo 'selected' ?> value="15">15</option>
                                    <option <?php if ($tempo_sv_pub_dias == '16') echo 'selected' ?> value="16">16</option>
                                    <option <?php if ($tempo_sv_pub_dias == '17') echo 'selected' ?> value="17">17</option>
                                    <option <?php if ($tempo_sv_pub_dias == '18') echo 'selected' ?> value="18">18</option>
                                    <option <?php if ($tempo_sv_pub_dias == '19') echo 'selected' ?> value="19">19</option>
                                    <option <?php if ($tempo_sv_pub_dias == '20') echo 'selected' ?> value="20">20</option>
                                    <option <?php if ($tempo_sv_pub_dias == '21') echo 'selected' ?> value="21">21</option>
                                    <option <?php if ($tempo_sv_pub_dias == '22') echo 'selected' ?> value="22">22</option>
                                    <option <?php if ($tempo_sv_pub_dias == '23') echo 'selected' ?> value="23">23</option>
                                    <option <?php if ($tempo_sv_pub_dias == '24') echo 'selected' ?> value="24">24</option>
                                    <option <?php if ($tempo_sv_pub_dias == '25') echo 'selected' ?> value="25">25</option>
                                    <option <?php if ($tempo_sv_pub_dias == '26') echo 'selected' ?> value="26">26</option>
                                    <option <?php if ($tempo_sv_pub_dias == '27') echo 'selected' ?> value="27">27</option>
                                    <option <?php if ($tempo_sv_pub_dias == '28') echo 'selected' ?> value="28">28</option>
                                    <option <?php if ($tempo_sv_pub_dias == '29') echo 'selected' ?> value="29">29</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-12">
                            <div id='div_erro_tempo_sv_pub' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_tempo_sv_pub"></p></center></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        **********************
            TEMPO DE SERVIÇO MILITAR ANTERIOR
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-info">
                    <legend>Tempo de serviço militar</legend> 
                    <div  class="row">
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_mil" class="form-group" onchange="tempo_servico_militar()"> <label>Possui tempo de serviço militar </label>
                                <select id="tempo_sv_mil" name="tempo_sv_mil" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($tempo_sv_mil == '0') echo 'selected' ?> value="0">Não</option>
                                    <option <?php if ($tempo_sv_mil == '1') echo 'selected' ?> value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_mil_anos" class="form-group">  <label>Anos</label>
                                <select id="tempo_sv_mil_anos" name="tempo_sv_mil_anos" class="form-control">
                                    <option value="">Selecione quantos anos</option>
                                    <option <?php if ($tempo_sv_mil_anos == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_mil_anos == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_mil_anos == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_mil_anos == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_mil_anos == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_mil_anos == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_mil_anos == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_mil_anos == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_mil_anos == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_mil_anos == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_mil_anos == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_mil_anos == '11') echo 'selected' ?> value="11">11</option>
                                    <option <?php if ($tempo_sv_mil_anos == '12') echo 'selected' ?> value="12">12</option>
                                    <option <?php if ($tempo_sv_mil_anos == '13') echo 'selected' ?> value="13">13</option>
                                    <option <?php if ($tempo_sv_mil_anos == '14') echo 'selected' ?> value="14">14</option>
                                    <option <?php if ($tempo_sv_mil_anos == '15') echo 'selected' ?> value="15">15</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_mil_meses" class="form-group">  <label>Meses</label>
                                <select id="tempo_sv_mil_meses" name="tempo_sv_mil_meses" class="form-control">
                                    <option value="">Selecione quantos meses</option>
                                    <option <?php if ($tempo_sv_mil_meses == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_mil_meses == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_mil_meses == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_mil_meses == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_mil_meses == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_mil_meses == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_mil_meses == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_mil_meses == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_mil_meses == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_mil_meses == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_mil_meses == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_mil_meses == '11') echo 'selected' ?> value="11">11</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div id="div_tempo_sv_mil_dias" class="form-group">  <label>Dias</label>
                                <select id="tempo_sv_mil_dias" name="tempo_sv_mil_dias" class="form-control">
                                    <option value="">Selecione quantos dias</option>
                                    <option <?php if ($tempo_sv_mil_dias == '0') echo 'selected' ?> value="0">0</option>
                                    <option <?php if ($tempo_sv_mil_dias == '1') echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($tempo_sv_mil_dias == '2') echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($tempo_sv_mil_dias == '3') echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($tempo_sv_mil_dias == '4') echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($tempo_sv_mil_dias == '5') echo 'selected' ?> value="5">5</option>
                                    <option <?php if ($tempo_sv_mil_dias == '6') echo 'selected' ?> value="6">6</option>
                                    <option <?php if ($tempo_sv_mil_dias == '7') echo 'selected' ?> value="7">7</option>
                                    <option <?php if ($tempo_sv_mil_dias == '8') echo 'selected' ?> value="8">8</option>
                                    <option <?php if ($tempo_sv_mil_dias == '9') echo 'selected' ?> value="9">9</option>
                                    <option <?php if ($tempo_sv_mil_dias == '10') echo 'selected' ?> value="10">10</option>
                                    <option <?php if ($tempo_sv_mil_dias == '11') echo 'selected' ?> value="11">11</option>
                                    <option <?php if ($tempo_sv_mil_dias == '12') echo 'selected' ?> value="12">12</option>
                                    <option <?php if ($tempo_sv_mil_dias == '13') echo 'selected' ?> value="13">13</option>
                                    <option <?php if ($tempo_sv_mil_dias == '14') echo 'selected' ?> value="14">14</option>
                                    <option <?php if ($tempo_sv_mil_dias == '15') echo 'selected' ?> value="15">15</option>
                                    <option <?php if ($tempo_sv_mil_dias == '16') echo 'selected' ?> value="16">16</option>
                                    <option <?php if ($tempo_sv_mil_dias == '17') echo 'selected' ?> value="17">17</option>
                                    <option <?php if ($tempo_sv_mil_dias == '18') echo 'selected' ?> value="18">18</option>
                                    <option <?php if ($tempo_sv_mil_dias == '19') echo 'selected' ?> value="19">19</option>
                                    <option <?php if ($tempo_sv_mil_dias == '20') echo 'selected' ?> value="20">20</option>
                                    <option <?php if ($tempo_sv_mil_dias == '21') echo 'selected' ?> value="21">21</option>
                                    <option <?php if ($tempo_sv_mil_dias == '22') echo 'selected' ?> value="22">22</option>
                                    <option <?php if ($tempo_sv_mil_dias == '23') echo 'selected' ?> value="23">23</option>
                                    <option <?php if ($tempo_sv_mil_dias == '24') echo 'selected' ?> value="24">24</option>
                                    <option <?php if ($tempo_sv_mil_dias == '25') echo 'selected' ?> value="25">25</option>
                                    <option <?php if ($tempo_sv_mil_dias == '26') echo 'selected' ?> value="26">26</option>
                                    <option <?php if ($tempo_sv_mil_dias == '27') echo 'selected' ?> value="27">27</option>
                                    <option <?php if ($tempo_sv_mil_dias == '28') echo 'selected' ?> value="28">28</option>
                                    <option <?php if ($tempo_sv_mil_dias == '29') echo 'selected' ?> value="29">29</option>
                                </select>
                            </div>
                        </div>
                        <div  class="col-lg-12">
                            <div id='div_erro_tempo_sv_mil' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_tempo_sv_mil"></p></center></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        **********************
            CIVIL OU MILITAR 
        **********************
        -->   

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-info">
                    <legend>Civil ou Militar Temporário</legend> 
                    <div  class="row">

                        <div  class="col-lg-3">
                            <div id="div_civil_militar" class="form-group"> <label>Você é civil ou militar temporário?</label>
                                <select id="civil_militar" name="civil_militar" onchange="select_civil_militar()" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($civil_militar == 'civil') echo 'selected' ?>   value="civil">Civil</option>
                                    <option <?php if ($civil_militar == 'militar') echo 'selected' ?> value="militar">Militar Temporário</option>
                                </select>
                            </div>
                        </div>

                        <div  class="col-lg-3"  id="div_ja_foi_militar">
                            <div  class="form-group"> <label>Já foi Militar?</label>
                                <select id="ja_foi_militar" name="ja_foi_militar" onchange="select_ja_foi_militar()" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($ativa_reserva == 'nunca_foi_militar') echo 'selected' ?> value="nao">Não</option>
                                    <option <?php if ($ativa_reserva == 'ja_foi_militar') echo 'selected' ?> value="sim">Sim</option>
                                </select>
                            </div>
                        </div>

                        <div  class="col-lg-3"  id="div_certificado">
                            <div  class="form-group"> <label>Documento Militar</label>
                                <select id="certificado" name="certificado" onchange="select_certificado()" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($certificado == '1crm') echo 'selected' ?> value="1crm">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                                    <option <?php if ($certificado == '2crm') echo 'selected' ?> value="2crm">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
                                    <option <?php if ($certificado == 'cam') echo 'selected' ?> value="cam">Certificado de Alistamento Militar (CAM)</option>
                                    <option <?php if ($certificado == 'csm') echo 'selected' ?> value="csm">Certidão de Situação Militar (CSM)</option>
                                    <option <?php if ($certificado == 'cdi') echo 'selected' ?> value="cdi">Certificado de Dispensa de Incorporação (CDI)</option>
                                    <option <?php if ($certificado == 'ci') echo 'selected' ?> value="ci">Certificado de Isenção (CI)</option>

                                </select>
                            </div>
                        </div>


                        <div  class="col-lg-3" id="div_documento">
                            <div  class="form-group"> 
                                <label>Número do documento militar</label>
                                <input id="documento" value="<?php if ($num_ducumento != '') echo $num_ducumento ?>" maxlength="50" name="documento" class="form-control">
                            </div>
                        </div>

                        <div   class="col-lg-3" id="div_data_expedicao">
                            <div  class="form-group"> 
                                <label>Data de Expedição</label>
                                <input id="data_expedicao" maxlength="10" value="<?php if ($data_expedicao != '') echo $data_expedicao ?>" name="data_expedicao" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3"  id="div_arma">
                            <div  class="form-group"> 
                                <label>Arma/Quadro/Serviço/Especialidade</label>
                                <input id="arma" maxlength="50" name="arma" value="<?php echo $arma_quadro_servico ?>" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-3" id="div_forca">
                            <div  class="form-group"> <label>Força</label>
                                <select id="forca" name="forca" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($forca == 'exercito') echo 'selected' ?> value="exercito">Exército</option>
                                    <option <?php if ($forca == 'marinha') echo 'selected' ?> value="marinha">Marinha</option>
                                    <option <?php if ($forca == 'aeronautica') echo 'selected' ?> value="aeronautica">Aeronáutica</option>
                                </select>
                            </div>
                        </div>

                        <div  class="col-lg-3" id="div_posto_grad">
                            <div  class="form-group"><label>Posto/Graduação</label>
                                <select id="posto_grad" name="posto_grad" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if ($posto_grad == 'sd') echo 'selected' ?> value="sd">Soldado</option>
                                    <option <?php if ($posto_grad == 'cb') echo 'selected' ?> value="cb">Cabo</option>
                                    <option <?php if ($posto_grad == '3_sgt') echo 'selected' ?> value="3_sgt">3º Sargento</option>
                                    <option <?php if ($posto_grad == 'asp') echo 'selected' ?> value="asp">Aspirante</option>
                                    <option <?php if ($posto_grad == '2_ten') echo 'selected' ?> value="2_ten">2º Tenente</option>
                                    <option <?php if ($posto_grad == '1_Ten') echo 'selected' ?> value="1_Ten">1º Tenente</option>
                                </select>
                            </div>
                        </div>

                        <div  class="col-lg-3"  id="div_incorporacao">
                            <div  class="form-group"> 
                                <label>Ano de incorporação</label>
                                <input id="incorporacao" value="<?php if ($ano_incorporacao != '') echo $ano_incorporacao ?>" maxlength="50" name="incorporacao" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-3" id="div_licenciamento">
                            <div  class="form-group"> 
                                <label>Licenciamento</label>
                                <input id="licenciamento" maxlength="50" value="<?php if ($licenciamento != '') echo $licenciamento ?>" name="licenciamento" class="form-control">
                            </div>
                        </div>

                        <div  class="col-lg-12">
                            <div id='div_erro_civil_militar' hidden class="alert alert-dismissible alert-danger">
                                <b><center><p id="erro_civil_militar"></p></center></b>
                            </div>
                        </div>

                    </div>
                </div>
                <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >      
                <button  type="submit" class="btn btn-primary btn-block">Salvar</button>         
                </div>
            </div>
        </section>

        </form>
    </div>
</div>
</div>

    
<!-- 
**********************
    OUTRAS INFORMAÇÕES
**********************
-->   
<a name="outras_info"></a>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <form action="../banco_dados/medico_obrigatorio_outras_info.php" method="post" >
                    <div class="alert alert-dismissible alert-info">
                        <legend>Outras informações</legend>
                        
                        <div  class="row">
                            <div  class="col-lg-4">
                                <div class="form-group"> <label>Voluntário para o Sv Militar</label>
                                    <select name="voluntario_sv_militar" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($voluntario_sv_militar == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($voluntario_sv_militar == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group"> <label>Voluntário 12ª RM </label>
                                    <select name="voluntario_12rm" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($voluntario_12rm == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($voluntario_12rm == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>

                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Prioridade de Força</label>
                                    <select name="prioridade_forca" class="form-control">
                                        <option value="">Selecione as prioridades</option>
                                        <option <?php if($prioridade_forca == 'qualquer') echo 'selected' ?> value="qualquer">Qualquer Força  </option>
                                        <option <?php if($prioridade_forca == 'EAM') echo 'selected' ?> value="EAM">1ª Exército - 2ª Aeronáutica - 3ª Marinha  </option>
                                        <option <?php if($prioridade_forca == 'EMA') echo 'selected' ?> value="EMA">1ª Exército - 2ª Marinha - 3ª Aeronáutica  </option>
                                        <option <?php if($prioridade_forca == 'MEA') echo 'selected' ?> value="MEA">1ª Marinha - 2ª Exército - 3ª Aeronáutica  </option>
                                        <option <?php if($prioridade_forca == 'MAE') echo 'selected' ?> value="MAE">1ª Marinha - 2ª Aeronáutica - 3ª Exército  </option>
                                        <option <?php if($prioridade_forca == 'AME') echo 'selected' ?> value="AME">1ª Aeronáutica - 2ª Marinha - 3ª Exército  </option>
                                        <option <?php if($prioridade_forca == 'AEM') echo 'selected' ?> value="AEM">1ª Aeronáutica - 2ª Exército - 3ª  Marinha </option>
                                        <option <?php if($prioridade_forca == 'EQ')  echo 'selected' ?> value="EQ"> 1ª Exército - 2ª e 3ª Qualquer outra força </option>
                                        <option <?php if($prioridade_forca == 'MQ')  echo 'selected' ?> value="MQ"> 1ª Marinha - 2ª e 3ª Qualquer outra força  </option>
                                        <option <?php if($prioridade_forca == 'AQ')  echo 'selected' ?> value="AQ"> 1ª Aeronáutica - 2ª e 3ª Qualquer outra força </option>
                                    </select>
                                </div>
                            </div>

                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Arrimo</label>
                                    <select name="arrimo" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($arrimo == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($arrimo == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Obrigatório</label>
                                    <select name="obrigatorio" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <!--
                                        <option <?php if($obrigatorio == '1') //echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($obrigatorio == '0') //echo 'selected' ?> value="0">Não</option>
                                        -->
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Situação Militar</label>
                                    <select name="situacao_militar" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($situacao_militar == 'em_dia') echo 'selected' ?> value="em_dia">Em dia</option>
                                        <option <?php if($situacao_militar == 'refratario') echo 'selected' ?> value="refratario">Refratário</option>
                                        <option <?php if($situacao_militar == 'adiado') echo 'selected' ?> value="adiado">Adiado</option>
                                        <option <?php if($situacao_militar == 'impedimento_judicial') echo 'selected' ?> value="impedimento_judicial">Impedimento Judicial</option>
                                        <option <?php if($situacao_militar == 'b1') echo 'selected' ?> value="b1">B1 - Incapaz Temporariamente</option>
                                        <option <?php if($situacao_militar == 'b2') echo 'selected' ?> value="b2">B2 - Incapaz 2 anos consecutivos</option>
                                        <option <?php if($situacao_militar == 'c') echo 'selected' ?> value="c">C - Incapaz defenitivamente</option>
                                        <option <?php if($situacao_militar == 'excesso_contigente') echo 'selected' ?> value="excesso_contigente">Excesso de contigente</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Atestado de Antecedentes</label>
                                    <select name="antecedentes" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($antecedentes == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($antecedentes == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Certidão Negativa de Fórum Civil</label>
                                    <select name="forum_civil" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($forum_civil == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($forum_civil == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-4">
                                <div class="form-group" > <label>Certidão Negativa de Fórum Criminal</label>
                                    <select name="forum_criminal" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($forum_criminal == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($forum_criminal == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >  
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    


<!-- 
**********************
    ADIAMENTO
**********************
--> 
<a name="adiamento"></a>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <form action="../banco_dados/medico_obrigatorio_adiamento.php" method="post" >
                    <div class="alert alert-dismissible alert-info">
                        <legend>Adiamento</legend> 
                        <div  class="row">
                            <div  class="col-lg-3">
                                <div class="form-group"> <label>Solicitou adiamento</label>
                                    <select name="solicitou_adiamento" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($solicitou_adiamento == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($solicitou_adiamento == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Início adiamento</label>
                                    <input id="data_nascimento" name="data_inicio_adiamento" value="<?php if($data_inicio_adiamento != null) echo reverte_data ($data_inicio_adiamento) ?>" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Fim adiamento</label>
                                    <input id="data_nascimento" name="data_fim_adiamento" value="<?php if($data_fim_adiamento != null) echo reverte_data ($data_fim_adiamento) ?>" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            <div  class="col-lg-3" id="div_licenciamento">
                                <div  class="form-group"> 
                                    <label>Especialidade</label>
                                    <input maxlength="250" value="<?php if ($especialidade_adiamento != '') echo $especialidade_adiamento ?>" name="especialidade_adiamento" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >  
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>             
    
<!-- 
**********************
    Exame médico
**********************
-->  
<a name="exame_medico"></a>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <form action="../banco_dados/candidato_edita_exame_medico.php" method="post" >
                    <div class="alert alert-dismissible alert-info">
                        <legend>Exame médico</legend> 
                        <div  class="row">
                            <div  class="col-lg-2">
                                <div class="form-group"> <label>Apto</label>
                                    <select name="apto_saude" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($apto_saude == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($apto_saude == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>

                            <div  class="col-lg-2">
                                <div class="form-group" > <label>Grupo</label>
                                    <select name="grupo_saude" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($grupo_saude == 'a') echo 'selected' ?> value="a">A</option>
                                        <option <?php if($grupo_saude == 'b1') echo 'selected' ?> value="b1">B1</option>
                                        <option <?php if($grupo_saude == 'b2') echo 'selected' ?> value="b2">B2</option>
                                        <option <?php if($grupo_saude == 'c') echo 'selected' ?> value="c">C</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Data da realização do exame</label>
                                    <input name="data_exame_saude" value="<?php if($data_exame_saude != null) echo reverte_data ($data_exame_saude) ?>" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            <div  class="col-lg-5">
                                <div  class="form-group"> 
                                    <label>CID</label>
                                    <textarea maxlength="2000" name="cid_saude" class="form-control"><?php echo $cid_saude?></textarea>
                                </div>
                            </div>
                            <div  class="col-lg-10">
                        <div  class="form-group"> 
                            <label>Observações</label>
                            <textarea maxlength="2000" name="observacao_exame_saude" class="form-control"><?php echo $observacao_exame_saude?></textarea>
                        </div>
                        </div>
                        <div  class="col-lg-2">
                            <div  class="form-group"> 
                                <label>PDF do Exame de saúde</label><br>
                                <a target="_blank" href="mpdf/relatorio_exame_medico.php?codigo=<?php   
                                        echo hash('sha256', $_SESSION['chave']);
                                        echo "&id_candidato=".$id_usuario;?>">
                                <img src="imagens/pdf.png" height="50px">
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >     
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_candidato" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_candidato" hidden >
                    <input value="sim" maxlength="3" name="medico_obrigatorio" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button> 
                    </form>
                </div>
            </div>
        </div>    
    </div>    
</div>    
 

<!-- 
**********************
    TRASFERÊNCIA FISEMI
**********************
--> 
<a name="fisemi"></a>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <form action="../banco_dados/medico_obrigatorio_fisemi.php" method="post" >
                        <div class="alert alert-dismissible alert-info">
                            <legend>Transferência da FISEMI</legend> 
                            <div  class="row">
                                <div  class="col-lg-3">
                                    <div class="form-group"> <label>Transferência da FISEMI</label>
                                        <select name="transferencia_fisemi" class="form-control">
                                            <option value="">Selecione a opção</option>
                                            <option <?php if($transferencia_fisemi == '1') echo 'selected' ?> value="1">Sim</option>
                                            <option <?php if($transferencia_fisemi == '0') echo 'selected' ?> value="0">Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div  class="col-lg-3" id="div_licenciamento">
                                    <div  class="form-group"> 
                                        <label>RM de ORIGEM</label>
                                        <select name="fisemi_rm_origem" class="form-control">
                                            <option value="">Selecione a opção</option>
                                            <option <?php if($fisemi_rm_origem == '1') echo 'selected'  ?> value="1">1ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '2') echo 'selected'  ?> value="2">2ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '3') echo 'selected'  ?> value="3">3ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '4') echo 'selected'  ?> value="4">4ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '5') echo 'selected'  ?> value="5">5ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '6') echo 'selected'  ?> value="6">6ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '7') echo 'selected'  ?> value="7">7ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '8') echo 'selected'  ?> value="8">8ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '9') echo 'selected'  ?> value="9">9ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '10') echo 'selected' ?> value="10">10ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '11') echo 'selected' ?> value="11">11ª RM</option>
                                            <option <?php if($fisemi_rm_origem == '12') echo 'selected' ?> value="12">12ª RM</option>
                                        </select>
                                    </div>
                                </div>
                                <div  class="col-lg-3" id="div_licenciamento">
                                    <div  class="form-group"> 
                                        <label>RM de DESTINO</label>
                                        <select name="fisemi_rm_destino" class="form-control">
                                            <option value="">Selecione a opção</option>
                                            <option <?php if($fisemi_rm_destino == '1') echo 'selected' ?> value="1">1ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '2') echo 'selected' ?> value="2">2ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '3') echo 'selected' ?> value="3">3ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '4') echo 'selected' ?> value="4">4ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '5') echo 'selected' ?> value="5">5ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '6') echo 'selected' ?> value="6">6ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '7') echo 'selected' ?> value="7">7ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '8') echo 'selected' ?> value="8">8ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '9') echo 'selected' ?> value="9">9ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '10') echo 'selected' ?> value="10">10ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '11') echo 'selected' ?> value="11">11ª RM</option>
                                            <option <?php if($fisemi_rm_destino == '12') echo 'selected' ?> value="12">12ª RM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >     
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button> 
                    </form>
                </div>
            </div>            
        </div>    
    </div>    
</div>

 

<!-- 
**********************
    Refratário/Impedimento Judicial
**********************
-->   
<a name="refratario_impedimento_judicial"></a>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <form action="../banco_dados/medico_obrigatorio_refratario_impedido.php" method="post" >
                    <div class="alert alert-dismissible alert-info">
                        <legend>Situação Pós CSE</legend> 
                        <div  class="row">
                            <div  class="col-lg-3">
                                <div class="form-group"> <label>Situação</label>
                                    <select name="refratario_impedido" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($refratario_impedido == 'desistencia') echo 'selected' ?> value="desistencia">Desistência</option>
                                        <option <?php if($refratario_impedido == 'refratario') echo 'selected' ?> value="refratario">Refratário</option>
                                        <option <?php if($refratario_impedido == 'impedido') echo 'selected' ?> value="impedido">Impedimento Judicial</option>
                                        <option <?php if($refratario_impedido == 'excesso') echo 'selected' ?> value="excesso">Excesso</option>
                                        <option <?php if($refratario_impedido == 'incorporado') echo 'selected' ?> value="incorporado">Incorporado</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Data da Liminar/Ocorrido em</label>
                                    <input name="data_liminar" value="<?php if($data_liminar != null) echo reverte_data ($data_liminar)  ?>" maxlength="25" class="form-control" >
                                </div>
                            </div>
                            
                            <div  class="col-lg-3">
                                <div class="form-group" > <label>Histórico Judicial</label>
                                    <select name="historico_judicial" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option <?php if($historico_judicial == '1') echo 'selected' ?> value="1">Sim</option>
                                        <option <?php if($historico_judicial == '0') echo 'selected' ?> value="0">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div  class="form-group"> 
                                    <label>Número da ação</label>
                                    <input maxlength="100" value="<?php if ($numero_acao != '') echo $numero_acao ?>" name="numero_acao" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3"> <label>Transitou em Julgado</label>
                                <select name="transitou_julgado" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($transitou_julgado == '1') echo 'selected' ?> value="1">Sim</option>
                                    <option <?php if($transitou_julgado == '0') echo 'selected' ?> value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-lg-3"> <label>Favorável/Desfavorável</label>
                                <select name="favoravel_desfavoravel" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($favoravel_desfavoravel == 'favoravel') echo 'selected' ?> value="favoravel">Favorável</option>
                                    <option <?php if($favoravel_desfavoravel == 'desfavoravel') echo 'selected' ?> value="desfavoravel">Desfavorável</option>
                                </select>
                            </div>
                            <div class="col-lg-3"> <label>Convocado</label>
                                <select name="convocado" class="form-control">
                                    <option value="">Selecione a opção</option>
                                    <option <?php if($convocado == '1') echo 'selected' ?> value="1">Sim</option>
                                    <option <?php if($convocado == '0') echo 'selected' ?> value="0">Não</option>
                                </select>
                            </div>
                            <div  class="col-lg-3" id="div_licenciamento">
                                <div  class="form-group"> 
                                    <label>Publicação em BAR Reg, Nº e Data</label>
                                    <input maxlength="100" value="<?php if ($publicacao_bar_reg != '') echo $publicacao_bar_reg ?>" name="publicacao_bar_reg" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >  
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                    <button  type="submit" class="btn btn-primary btn-block">Salvar</button> 
                    </form>
                </div>
            </div>            
            
        </div>    
    </div> 
</div>    





<!-- 
**********************
    Cadastra especialidade
**********************
-->   
<a name="especialidade"> </a>
<div class="row" >
    <div class="col-md-12">
        <div class="card">
            <form action="../banco_dados/medico_obrigatorio_cadastra_especialidade.php" method="post" onsubmit="return verifica_cadastro_especialidade_candidato()">
                <legend>Especialidades</legend> 
                <div class="alert alert-dismissible alert-info" >
                    <legend>Cadastre uma especialidade</legend> 
                    
                    <div class="row" >
                        <div class="form-group col-lg-6" id="div_ott_stt">
                            <label>Selecione o tipo da especialidade</label>
                            <select id="ott_stt" name="ott_stt" class="form-control" onchange="busca_ott_stt()">
                                <option value="">Selecione a opção</option>
                                <option value="medico">Médico</option>
                                <option value="dentista">Dentista</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6" id="div_especialidade" >
                            <label>Selecione a especialidade</label>
                            <select id="especialidade" name="especialidade" class="form-control">
                                <option value="">Primeiro selecione o tipo da especialidade</option>
                            </select>
                        </div>
                    </div>
                    <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >  
                    <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                    <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                    <button   type="submit" class="btn btn-primary btn-block">Salvar</button> 
                </div>  
            </form>
            
            
                
                <?php
                    $criptografia = hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']);
                    $especialidade_cadastradas_candidato = $conexao->get_especialidade_candidato($id_usuario);
                    
                    foreach ($especialidade_cadastradas_candidato as &$esp_cadastrada)
                    {
                        
                        $get_prioridade_da_especialidade = $conexao->get_prioridade_especialidade_candidato($esp_cadastrada['id_candidato_x_especialidade']);
                ?>
                    <div class="alert alert-dismissible alert-info" >
                        <div class="row" >
                            <div class="form-group col-lg-6"  >
                                <legend>Especialidade: <?php echo $esp_cadastrada['especialidade']; ?></legend>
                            </div>
                            <div class="form-group col-lg-6" id="div_especialidade" >
                                <form action="../banco_dados/medico_obrigatorio_especialidade_apaga.php" method="POST">
                                    <input hidden value="<?php echo $criptografia; ?>" name="crip" >  
                                    <input value="<?php echo $esp_cadastrada['especialidade']; ?>" maxlength="50" name="nome_esp_medico" hidden>
                                    <input value="<?php echo $esp_cadastrada['id_especialidade']; ?>" maxlength="50" name="id_especialidade" hidden>
                                    <input value="<?php echo $id_usuario; ?>" maxlength="50" name="id_medico" hidden>
                                    <input value="<?php echo $cpf; ?>" maxlength="50" name="c_p_f_medico" hidden >
                                    <input value="submit" type="image"src="imagens/apagar.png" width="30px">  <label> Apagar especialidade</label>
                                </form>
                            </div>
                        </div>
                
                <div class="row" >
                    <form method="post" action="../banco_dados/medico_obrigatorio_cadastra_prioridade_cidade.php" >
                        <div class="col-md-6">
                            <input hidden name="esp" value="<?php echo $esp_cadastrada['id_especialidade'] ?>">
                            <input value="<?php echo $id_usuario ?>" maxlength="50" name="id_medico" hidden>
                            <input value="<?php echo $cpf ?>" maxlength="50" name="c_p_f_medico" hidden >
                            <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >
                            <div class="form-group"> 
                                <label>Selecione a CIDADE</label>
                                <select name="id_cidade" class="form-control">
                                    <option value="">Selecione a cidade</option>
                                    <?php
                                        $lista_cidades = $conexao->get_cidades_especialidade_candidato($esp_cadastrada['id_especialidade'], $esp_cadastrada['id_candidato_x_especialidade']);
                                        foreach ($lista_cidades as $linha) 
                                        {
                                            echo '<option value="'.$linha['id'].'">'.$linha['nome'].' </option>';
                                        }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label>Selecione a PRIORIDADE</label>
                                <select name="prioridade" class="form-control">
                                    <option value="">Selecione a prioridade</option>
                                    <?php
                                    
                                        $quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($esp_cadastrada['id_especialidade']);  
                                        $quantidade_cidades = $quantidade_cidades[0]['quantidade'];
                                    
                                        $valor = count($get_prioridade_da_especialidade) +1;
                                        if(count($get_prioridade) < $quantidade_cidades)
                                        {
                                            echo '<option value="'.$valor.'">Prioridade Nº '.$valor.' </option>';
                                        }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <input type="submit" class="btn btn-primary btn-block" value="Cadastrar Prioridade" />
                        </div>
                    </form>
                    
                    <div class="col-md-12">
                        <br><br>
                        <label>Cidades e prioridades cadastradas: </label>
                        <?php 
                            
                            foreach ($get_prioridade_da_especialidade as &$linha) 
                            {
                                echo $linha['prioridade']."ª prioridade "  .  " ". $linha['nome'] . " | " ;
                            }
                       ?> 
                    </div>
                    <div class="form-group col-lg-12" id="div_especialidade" >
                        <form action="../banco_dados/medico_obrigatorio_prioridade_cidade_apagar.php" method="POST">
                            <input hidden value="<?php echo $criptografia; ?>" name="crip" >  
                            <input value="<?php echo $esp_cadastrada['especialidade']; ?>" maxlength="50" name="nome_esp_medico" hidden>
                            <input value="<?php echo $esp_cadastrada['id_especialidade']; ?>" maxlength="50" name="id_especialidade" hidden>
                            <input value="<?php echo $id_usuario; ?>" maxlength="50" name="id_medico" hidden>
                            <input value="<?php echo $cpf; ?>" maxlength="50" name="c_p_f_medico" hidden >
                            <input value="submit" type="image"src="imagens/apagar.png" width="30px">  <label> Apagar prioridades cadastradas</label>
                        </form>
                    </div>
                    
                </div>
                        </div>  
                <br><br>
                    <?php } ?>
            
                </div>  
                
            </div>  
        </div>  


<!-- 
**********************
    OBSERVAÇÕES
**********************
-->       
<a name="observacoes">  </a>  
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row" <?php if($codigo_selecao != 'mfdv') echo "hidden"; ?>>
                <div class="col-md-12">
                    <div class="alert alert-dismissible">
                        <legend>Observações do candidato</legend> 
                        <div  class="row">

                            <div class="col-md-6">
                                <form method="post" action="../banco_dados/candidato_observacao_cadastra.php">
                                    <input type="text" value="<?php echo $id_usuario ?>" name="id_usuario" hidden>
                                    <input type="text" value="medico_obrigatorio_obs" name="medico_obrigatorio" hidden>
                                    <input type="text" value="<?php echo hash('sha256', $_SESSION['assinatura_sistema']) ?>" name="criptografia" hidden>
                                    <label>Escreva uma observação:</label>
                                    <br>
                                    <textarea style="width:100%;" rows="6" name="observacao"></textarea>
                                    <br>
                                    <br>
                                    <button  type="submit"  class="btn btn-primary btn-block">CADASTRAR</button>
                                </form>
                            </div>
                        <div class="col-md-6">

                            <div class="alert alert-">

                            <?php

                            $lista_observacaoes = $conexao->get_observacoes_candidato($id_usuario); 

                            foreach ($lista_observacaoes as $linha) 
                            {

                                $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['_usuario_ultima_atualizacao']."'><img class='img-circle' src='fotos/user.jpg' width='40px'></a>";
                                $get_foto = $conexao->get_foto_usuario($linha['_usuario_ultima_atualizacao']);  
                                if(count($get_foto) > 0)
                                {
                                    $foto = $get_foto[0]['nome'];
                                    $foto = "<a href='usuario_visualiza.php?id_usuario=".$linha['_usuario_ultima_atualizacao']."'><img class='img-circle' src='fotos/$foto' width='40px'></a>";
                                }

                                $usuario_cadastrou_obs = 'Obs criada pelo sistema';

                                if($linha['sistema'] == 0)
                                {
                                    $usuario_cadastrou_obs = $conexao->get_usuario_id($linha['_usuario_ultima_atualizacao']);
                                    $usuario_cadastrou_obs = $usuario_cadastrou_obs[0]['posto_grad'].' '.$usuario_cadastrou_obs[0]['nome_guerra'];
                                }

                                $alerta = "success";
                                if($linha['sistema'] == 1)
                                    $alerta = "laranja";

                                echo '
                                <div class="alert alert-'.$alerta.'">
                                    <div class="row">
                                        <div class="col-md-10">
                                        <b>Observação: </b><font color="#111">'.$linha['observacao'].
                                           '</font><br>
                                         <i>Cadastrado em '.trata_data_hora($linha['_data_ultima_atualizacao']).' - <a href="usuario_visualiza.php?id_usuario='.$linha['_usuario_ultima_atualizacao'].'"> '.$usuario_cadastrou_obs.'</a> '.$foto.'</i>
                                        </div>
                                        ';
                                        /*if($linha['sistema'] == 0)
                                            echo '
                                        <div class="col-md-2">
                                            <a onclick="funcao_apagar(\''.$linha['id'].'\', \'candidato_observacao\',\''.$id_usuario.'\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a>
                                        </div>';*/
                                        echo' 
                                    </div>
                                </div>';
                            }

                            ?>

                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<a href="usuario_visualiza.php?id_usuario=<?php echo $id_usuario?>"><button class="btn btn-default btn-block">VOLTAR</button></a>
    
</div>

</div>
  </body>
</html>