<?php
    include_once './menu.php';
    include_once './codigos/candidato_campos_cadastra.php';
    include_once './codigos/candidato_valida_cadastro.php';
    
    // <editor-fold defaultstate="collapsed" desc=" CRIA SELECT CERTIFICADO E POSTO">
    
    $select_certificado = null;
    $select_posto_grad = null;
    $select_posto_grad = ' <option value="">Selecione a opção</option>';

    if($sexo == 'masculino')
    {
        $select_posto_grad.='<option '; if($posto_grad == 'sd') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="sd">Soldado</option>';
    }

    $select_posto_grad.='<option '; if($posto_grad == 'cb') $select_posto_grad.=' selected ';
    $select_posto_grad .='value="cb">Cabo</option>

    <option '; if($posto_grad == '3_sgt') $select_posto_grad.=' selected ';
    $select_posto_grad .='value="3_sgt">3º Sargento</option>

    <option '; if($posto_grad == 'asp') $select_posto_grad.=' selected ';
    $select_posto_grad .='value="asp">Aspirante</option>

    <option '; if($posto_grad == '2_ten') $select_posto_grad.=' selected ';
    $select_posto_grad .='value="2_ten">2º Tenente</option>

    <option '; if($posto_grad == '1_Ten') $select_posto_grad.=' selected ';
    $select_posto_grad .='value="1_Ten">1º Tenente</option>';

    if($certificado == 'csm')
    {
        $select_posto_grad = ' <option value="">Selecione a opção</option>
        <option '; if($posto_grad == 'asp') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="asp">Aspirante</option>

        <option '; if($posto_grad == '2_ten') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="2_ten">2º Tenente</option>

        <option '; if($posto_grad == '1_Ten') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="1_Ten">1º Tenente</option>';
    }

    if($certificado == '1crm')
    {
        $select_posto_grad = ' <option value="">Selecione a opção</option>
        <option '; if($posto_grad == 'sd') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="sd">Soldado</option>

        <option '; if($posto_grad == 'cb') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="cb">Cabo</option>

        <option '; if($posto_grad == '3_sgt') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="3_sgt">3º Sargento</option>

        <option '; if($posto_grad == 'asp') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="asp">Aspirante</option>';
    }

    if($certificado == '2crm')
    {
        $select_posto_grad = ' <option value="">Selecione a opção</option>
        <option '; if($posto_grad == 'sd') $select_posto_grad.=' selected ';
        $select_posto_grad .='value="sd">Soldado</option>';
    }
    
    
    if($certificado == 'csm' || $certificado == '1crm' || $certificado == '2crm')
    {
        $select_certificado = ' 
            <option value="">Selecione a opção</option>
            
            <option'; if($certificado == 'csm') $select_certificado.=' selected ';
            $select_certificado .= ' value="csm">Certidão de Situação Militar (CSM)</option>
            
            <option '; if($certificado == '1crm') $select_certificado.=' selected ';
            $select_certificado .= ' value="1crm">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
            
            <option '; if($certificado == '2crm') $select_certificado.=' selected ';
            $select_certificado .= ' value="2crm">Certificado de Reservista Militar 2ª Categoria (CRM)</option>';
    }
    
    if($certificado == 'cdi')
    {
        $select_certificado = ' 
            <option value="">Selecione a opção</option>
            
            <option'; if($certificado == 'cdi') $select_certificado.=' selected ';
            $select_certificado .= ' value="cdi">Certificado de Dispensa de Incorporação (CDI)</option>

            <option '; if($certificado == 'ci') $select_certificado.=' selected ';
            $select_certificado .= ' value="ci">Certificado de Isenção (CI)</option>';
    }
    
     // </editor-fold>
    
?>
<form action="../banco_dados/candidato_edita_eipot.php" method="post" onsubmit="return candidato_valida_cadastro()">
<div class="content-wrapper">
<div class="row">
  <div class="col-md-12">
    <div class="card">
        
    <section class="invoice">
        <div class="row">
          <div class="col-xs-12">
            <legend>Atualizar minhas informações<i class="fa fa-id-card-o"></i> 
                <font color="red" size="2px"> Todos os campos são obrigatórios!</font> 
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
                    <div id="div_nacionalidade" class="form-group">
                        <label>Nacionalidade (País)</label>
                        <input id="nacionalidade" value="<?php echo $nacionalidade ?>" maxlength="30" name="nacionalidade" class="form-control">
                    </div>
                    <div id="div_naturalidade" class="form-group">
                        <label>Naturalidade (Cidade)</label>
                        <input id="naturalidade" value="<?php echo $naturalidade ?>" maxlength="30" name="naturalidade" class="form-control">
                    </div>
                        
                    <div class="form-group" <?php if($codigo_selecao != 'mfdv') echo'hidden'?>>
                        <label>Dependentes</label>
                        <select name="dependente" class="form-control">
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
                        <select id="estado_civil" name="estado_civil" onchange="mostra_companheiro()" class="form-control">
                            <option value="">Selecione o Estado Civil</option>
                            <option <?php if ($estado_civil == 'solteiro') echo 'selected' ?> value="solteiro">Solteiro </option>
                            <option <?php if ($estado_civil == 'uniao_estavel') echo 'selected' ?> value="uniao_estavel">União Estável </option>
                            <option <?php if ($estado_civil == 'casado') echo 'selected' ?> value="casado">Casado </option>
                            <option <?php if ($estado_civil == 'viuvo') echo 'selected' ?> value="viuvo">Viúvo </option>
                            <option <?php if ($estado_civil == 'outro') echo 'selected' ?> value="outro">Outro</option>
                        </select>
                    </div>
                    
                    <div <?php if($companheiro == '' || $companheiro == null) echo ' hidden ' ?>  id="div_companheiro" class="form-group"> 
                        <label>Nome do Companheiro(a)</label>
                        <input id="companheiro" maxlength="85" name="nome_companheiro" value="<?php echo $companheiro ?>" class="form-control">
                    </div>
                    
                    <div id="div_filiacao_mae" class="form-group"> 
                        <label>Filiação (Mãe)</label>
                        <input id="filiacao_mae" value="<?php echo $mae ?>" maxlength="120" name="filiacao_mae" class="form-control">
                    </div>
                    
                    <div id="div_filiacao_pai" class="form-group">
                        <label>Filiação (Pai)</label>
                        <input id="filiacao_pai" maxlength="120" value="<?php echo $pai ?>" name="filiacao_pai" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Autodeclaração</label>
                        <select id="autodeclaracao" name="autodeclaracao" onchange="mostra_vaga_reservada()" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option value="branco" <?php echo ($autodeclaracao == 'branco') ? 'selected' : ''; ?>>Branca</option>
                            <option value="preto" <?php echo ($autodeclaracao == 'preto') ? 'selected' : ''; ?>>Preta</option>
                            <option value="pardo" <?php echo ($autodeclaracao == 'pardo') ? 'selected' : ''; ?>>Parda</option>
                            <option value="indio" <?php echo ($autodeclaracao == 'indio') ? 'selected' : ''; ?>>Indígena</option>
                            <option value="amarelo" <?php echo ($autodeclaracao == 'amarelo') ? 'selected' : ''; ?>>Amarela</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <br>
                            <div id="div_vaga_reservada" class="animated-checkbox form-group" style="display: <?php echo ($vaga_reservada == 1) ? 'block' : 'none'; ?>;">
                                <label>
                                    <input type="checkbox" id="check_vaga_reservada" name="vaga_reservada" <?php echo ($vaga_reservada == 1) ? 'checked' : ''; ?>>
                                    <span class="label-text">Quero concorrer às vagas reservadas para Negros (Lei Nr12.990, de 9 de Junho de 2014)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <script>
                        function mostra_vaga_reservada() {
                            // Verifica o valor do campo select e exibe/oculta a div
                            if ($('#autodeclaracao').val() == 'preto' || $('#autodeclaracao').val() == 'pardo') {
                                $('#div_vaga_reservada').show();
                            } else {
                                $('#div_vaga_reservada').hide();
                                // Desmarca o checkbox quando a div for oculta
                                $('#check_vaga_reservada').prop('checked', false);
                            }
                        }

                        // Chama a função mostra_vaga_reservada ao carregar a página, para garantir que a lógica seja aplicada.
                        $(document).ready(function() {
                            mostra_vaga_reservada();
                        });
                    </script>
                    
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

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible alert-info">
                <legend>Seleção de Região Militar</legend>
                <div class="row">
                <div id="rm" class="col-lg-12 form-group"> 
                <label>PROCESSO SELETIVO – EIPOT / 2025 – 2º SEMESTRE <br>
                        O candidato poderá inscrever-se abaixo para concorrer às vagas de EIPOT de uma ou mais
                        Regiões Militares (RM) de interesse. <br>Não será necessário utilizar-se de outro sistema ou
                        inscrever-se mais de uma vez, bastando para isso selecionar as RM na ordem de preferência. <br>
                        </label> <br>
                <font color="red"> *“Selecione uma ou mais
                        Regiões para a(s) qual(ais) deseja se inscrever, em ORDEM DE PREFERÊNCIA, e confira a
                        sequência da ordem no quadro após as opções de seleção”</font>
                <br>
                
                <div id="checkboxes" class="mt-2">
                    <!-- As checkboxes serão preenchidas com base na variável PHP $rm_destino -->
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="1" <?php if (in_array(1, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 1ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="2" <?php if (in_array(2, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 2ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="3" <?php if (in_array(3, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 3ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="4" <?php if (in_array(4, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 4ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="5" <?php if (in_array(5, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 5ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="6" <?php if (in_array(6, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 6ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="7" <?php if (in_array(7, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 7ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="8" <?php if (in_array(8, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 8ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="9" <?php if (in_array(9, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 9ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="10" <?php if (in_array(10, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 10ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="11" <?php if (in_array(11, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 11ª Região Militar
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="12" <?php if (in_array(12, explode(',', $rm_destino))) echo 'checked'; ?> onchange="updateSelectedOptions(this)"> 12ª Região Militar
                        </label>
                    </div>
                </div>

                <label>Região(ões) Militar(es) escolhidas para a Inscrição pelo candidato:</label>
                <textarea id="rm_destino" class="form-control mt-3" rows="3" readonly placeholder="Opções selecionadas aparecerão aqui..."></textarea>
                <input type="hidden" name="rm_destino_valores" id="rm_destino_valores">

                <script>
                // Variável global para armazenar as opções selecionadas
                const selectedOptionsArray = [];

                // Função que atualiza a textarea e o campo oculto com as opções selecionadas
                function updateSelectedOptions(checkbox) {
                    const textarea = document.getElementById('rm_destino');
                    const hiddenInput = document.getElementById('rm_destino_valores');
                    
                    if (checkbox.checked) {
                        // Adiciona a opção no array
                        selectedOptionsArray.push(checkbox.value);
                    } else {
                        // Remove a opção se desmarcada
                        const index = selectedOptionsArray.indexOf(checkbox.value);
                        if (index > -1) {
                            selectedOptionsArray.splice(index, 1);
                        }
                    }

                    // Atualiza a textarea com as opções selecionadas
                    textarea.value = selectedOptionsArray.join(', ');

                    // Atualiza o campo oculto com as opções selecionadas (separadas por vírgulas)
                    hiddenInput.value = selectedOptionsArray.join(',');
                }

                // Função que preenche a textarea com as opções selecionadas ao carregar a página
                function populateSelectedOptions() {
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    const selectedRegions = <?php echo json_encode(explode(',', $rm_destino)); ?>;
                    
                    selectedRegions.forEach(function(region) {
                        // Marca a checkbox correspondente
                        const checkbox = document.querySelector('input[type="checkbox"][value="' + region + '"]');
                        if (checkbox) {
                            checkbox.checked = true;
                            updateSelectedOptions(checkbox); // Atualiza a textarea com a região selecionada
                        }
                    });
                }

                // Chama a função para preencher a textarea e o campo oculto ao carregar a página
                document.addEventListener("DOMContentLoaded", populateSelectedOptions);
                </script>

                <br>
                <div  class="col-lg-6">
                    <div id="rm_inscricao" class="form-group"> 
                        <label>O candidato deverá escolher abaixo a única Região Militar na qual deverá realizar as etapas
                            presenciais, devendo para isso acompanhar o Aviso de Convocação (AC) e a página eletrônica
                            da Região Militar escolhida para verificar datas, horários e locais das atividades. Tal medida
                            facilitará o processo seletivo do candidato, assim, não será necessária a entrega de
                            documentação, fazer exames ou entrar com recursos em mais de uma RM.</label> <br> 
                            <font color="red"> *RM escolhida pelo participante para realizar as etapas PRESENCIAIS:</font>
                        <select name="rm_inscricao" class="form-control">
                            <option value="">Selecione a RM Inscrição</option>
                            <option value="1" <?php if ($rm_inscricao == '1') echo 'selected'; ?>>1ª RM - Rio de Janeiro (RJ)</option>
                            <option value="2" <?php if ($rm_inscricao == '2') echo 'selected'; ?>>2ª RM - São Paulo (SP)</option>
                            <option value="3" <?php if ($rm_inscricao == '3') echo 'selected'; ?>>3ª RM - Porto Alegre (RS)</option>
                            <option value="4" <?php if ($rm_inscricao == '4') echo 'selected'; ?>>4ª RM - Belo Horizonte (MG)</option>
                            <option value="5" <?php if ($rm_inscricao == '5') echo 'selected'; ?>>5ª RM - Curitiba (PR)</option>
                            <option value="6" <?php if ($rm_inscricao == '6') echo 'selected'; ?>>6ª RM - Salvador (BA)</option>
                            <option value="7" <?php if ($rm_inscricao == '7') echo 'selected'; ?>>7ª RM - Recife (PE)</option>
                            <option value="8" <?php if ($rm_inscricao == '8') echo 'selected'; ?>>8ª RM - Belém (PA)</option>
                            <option value="9" <?php if ($rm_inscricao == '9') echo 'selected'; ?>>9ª RM - Campo Grande (MS)</option>
                            <option value="10" <?php if ($rm_inscricao == '10') echo 'selected'; ?>>10ª RM - Fortaleza (CE)</option>
                            <option value="11" <?php if ($rm_inscricao == '11') echo 'selected'; ?>>11ª RM - Brasília (DF)</option>
                            <option value="12" <?php if ($rm_inscricao == '12') echo 'selected'; ?>>12ª RM - Manaus (AM)</option>
                        </select>
                    </div>
                </div>
    </div>
</div>


<!-- 
**********************
    INSTITUIÇÃO DE ENSINO
**********************
--> 

<div class="row" <?php if(!isset($_SESSION['mfdv'])) echo "hidden"; ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Instituição de Ensino</legend> 
            <div class="row">
                <div  class="col-lg-4">
                    <div class="form-group">
                        <label>Nome da Instituição de Ensino</label>
                        <input value="<?php echo $instituto_ensino ?>" maxlength="200" name="nome_ie" class="form-control">
                    </div>
                </div>
                <div  class="col-lg-2">
                    <div class="form-group">
                        <label>Ano de formação</label>
                        <select name="ano_formacao" class="form-control" >
                        <option value="">Selecione o ano</option>
                        <?php 
                            for($i = date("Y"); $i>=1980; $i--)
                            {
                                if($ano_formacao == $i)
                                    echo '<option selected value="'.$i.'">'.$i.'</option>';
                                else
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>

                <div  class="col-lg-2">
                    <div class="form-group"> <label>UF da Instituição de Ensino</label>
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

                 <div class="col-lg-4">
                    <div id="div_cidade" class="form-group"> <label>Cidade da Instituição de Ensino</label>
                        <select id="cidade_ie" name="cidade_ie" class="form-control">
                            <option value="">Selecione primeiramente a UF</option>
                            <?php
                                $resultado2 = $conexao->busca_cidade_uf($uf_instituto_ensino); 
                                foreach ($resultado2 as $value4) 
                                {
                                    if($value4['id'] == $id_cidade_instituto_ensino)
                                        echo '<option selected value="'.$value4['id'].'">'.$value4['nome'].'</option>';
                                    else
                                        echo '<option value="'.$value4['id'].'">'.$value4['nome'].'</option>';
                                }
                            ?> 
                        </select>
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

<div class="row" hidden>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Tempo de serviço público até a data final da inscrição</legend> 
            <div  class="row">
                <div  class="col-lg-3">
                    <div id="div_tempo_sv_pub" class="form-group"> <label>Possui tempo de serviço público até a data final da inscrição?</label>
                        <select id="tempo_sv_pub" name="tempo_sv_pub" class="form-control" onchange="tempo_servico_publico()">
                            <option value="">Selecione a opção</option>
                            <option <?php if ($tempo_sv_pub == '0') echo 'selected' ?> value="0">Não</option>
                            <option <?php if ($tempo_sv_pub == '1') echo 'selected' ?> value="1">Sim</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-3">
                    <div <?php if ($tempo_sv_pub == '0') echo 'hidden' ?> id="div_tempo_sv_pub_anos" class="form-group">  <label>Anos</label>
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
                    <div <?php if ($tempo_sv_pub == '0') echo 'hidden' ?> id="div_tempo_sv_pub_meses" class="form-group"> <label>Meses</label>
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
                    <div <?php if ($tempo_sv_pub == '0') echo 'hidden' ?>  id="div_tempo_sv_pub_dias" class="form-group">  <label>Dias</label>
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

<div class="row" <?php if(isset($_SESSION['eipot']) && $_SESSION['eipot'] == 1) echo " hidden " ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Tempo de serviço militar (nas Forças Armadas) até a data final da inscrição </legend> 
            <div  class="row">
                <div  class="col-lg-3">
                    <div id="div_tempo_sv_mil" class="form-group" onchange="tempo_servico_militar()"> <label>Possui tempo de serviço militar (nas Forças Armadas) até a data final da inscrição?</label>
                        <select id="tempo_sv_mil" name="tempo_sv_mil" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option <?php if ($tempo_sv_mil == '0') echo 'selected' ?> value="0">Não</option>
                            <option <?php if ($tempo_sv_mil == '1') echo 'selected' ?> value="1">Sim</option>
                        </select>
                    </div>
                </div>
                <div  class="col-lg-3">
                    <div <?php if ($tempo_sv_mil == '0') echo 'hidden' ?> id="div_tempo_sv_mil_anos" class="form-group">  <label>Anos</label>
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
                    <div <?php if ($tempo_sv_mil == '0') echo 'hidden' ?> id="div_tempo_sv_mil_meses" class="form-group">  <label>Meses</label>
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
                    <div <?php if ($tempo_sv_mil == '0') echo 'hidden' ?> id="div_tempo_sv_mil_dias" class="form-group">  <label>Dias</label>
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
</div>

<!-- 
**********************
    EIPOT
**********************
--> 

<div class="row" <?php if(!isset($_SESSION['eipot'])) echo " hidden " ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Informações EIPOT   </legend> 
            <div  class="row">
                
               <!-- <div  class="col-lg-6">
                    <div  class="form-group"> <label>Nome do curso de graduação</label>
                        <select name="curso_graduacao" class="form-control"">
                            <option value="">Selecione o nome do curso da sua formação</option>
                            <option <?php if ($curso_graduacao == 'Agronomia (ou Engenharia Agronômica)') echo 'selected' ?> value="Agronomia (ou Engenharia Agronômica)">Agronomia (ou Engenharia Agronômica)</option>
                            <option <?php if ($curso_graduacao == 'Zootecnia') echo 'selected' ?> value="Zootecnia">Zootecnia</option>
                            <option <?php if ($curso_graduacao == 'Biomedicina') echo 'selected' ?> value="Biomedicina">Biomedicina</option>
                            <option <?php if ($curso_graduacao == 'Ciências Biológicas') echo 'selected' ?> value="Ciências Biológicas">Ciências Biológicas</option>
                            <option <?php if ($curso_graduacao == 'Educação Física') echo 'selected' ?> value="Educação Física">Educação Física</option>
                            <option <?php if ($curso_graduacao == 'Fisioterapia') echo 'selected' ?> value="Fisioterapia">Fisioterapia</option>
                            <option <?php if ($curso_graduacao == 'Fonoaudiologia') echo 'selected' ?> value="Fonoaudiologia">Fonoaudiologia</option>
                            <option <?php if ($curso_graduacao == 'Terapia Ocupacional') echo 'selected' ?> value="Terapia Ocupacional">Terapia Ocupacional</option>
                            <option <?php if ($curso_graduacao == 'Enfermagem') echo 'selected' ?> value="Enfermagem">Enfermagem</option>
                            <option <?php if ($curso_graduacao == 'Nutrição') echo 'selected' ?> value="Nutrição">Nutrição</option>
                            <option <?php if ($curso_graduacao == 'Desenho Industrial') echo 'selected' ?> value="Desenho Industrial">Desenho Industrial</option>
                            <option <?php if ($curso_graduacao == 'Estatística') echo 'selected' ?> value="Estatística">Estatística</option>
                            <option <?php if ($curso_graduacao == 'Física') echo 'selected' ?> value="Física">Física</option>
                            <option <?php if ($curso_graduacao == 'Informática – Ciência da Computação (Computação e Informática)') echo 'selected' ?> value="Informática – Ciência da Computação (Computação e Informática)">Informática – Ciência da Computação (Computação e Informática)</option>
                            <option <?php if ($curso_graduacao == 'Informática – Engenharia de Computação (Computação e Informática)') echo 'selected' ?> value="Informática – Engenharia de Computação (Computação e Informática)">Informática – Engenharia de Computação (Computação e Informática)</option>
                            <option <?php if ($curso_graduacao == 'Informática – Sistemas de Informação (Computação e Informática)') echo 'selected' ?> value="Informática – Sistemas de Informação (Computação e Informática)">Informática – Sistemas de Informação (Computação e Informática)</option>
                            <option <?php if ($curso_graduacao == 'Meteorologia') echo 'selected' ?> value="Meteorologia">Meteorologia</option>
                            <option <?php if ($curso_graduacao == 'Química') echo 'selected' ?> value="Química">Química</option>
                            <option <?php if ($curso_graduacao == 'Arquitetura e Urbanismo') echo 'selected' ?> value="Arquitetura e Urbanismo">Arquitetura e Urbanismo</option>
                            <option <?php if ($curso_graduacao == 'Arquivologia') echo 'selected' ?> value="Arquivologia">Arquivologia</option>
                            <option <?php if ($curso_graduacao == 'Biblioteconomia (Ciência da Informação e Documentação)') echo 'selected' ?> value="Biblioteconomia (Ciência da Informação e Documentação)">Biblioteconomia (Ciência da Informação e Documentação)</option>
                            <option <?php if ($curso_graduacao == 'Comunicação Social') echo 'selected' ?> value="Comunicação Social">Comunicação Social</option>
                            <option <?php if ($curso_graduacao == 'Direito') echo 'selected' ?> value="Direito">Direito</option>
                            <option <?php if ($curso_graduacao == 'Geofísica') echo 'selected' ?> value="Geofísica">Geofísica</option>
                            <option <?php if ($curso_graduacao == 'Geologia') echo 'selected' ?> value="Geologia">Geologia</option>
                            <option <?php if ($curso_graduacao == 'Gestão em Saúde') echo 'selected' ?> value="Gestão em Saúde">Gestão em Saúde</option>
                            <option <?php if ($curso_graduacao == 'Gestão Ambiental') echo 'selected' ?> value="Gestão Ambiental">Gestão Ambiental</option>
                            <option <?php if ($curso_graduacao == 'História') echo 'selected' ?> value="História">História</option>
                            <option <?php if ($curso_graduacao == 'Museologia') echo 'selected' ?> value="Museologia">Museologia</option>
                            <option <?php if ($curso_graduacao == 'Relações Internacionais') echo 'selected' ?> value="Relações Internacionais">Relações Internacionais</option>
                            <option <?php if ($curso_graduacao == 'Secretariado Executivo') echo 'selected' ?> value="Secretariado Executivo">Secretariado Executivo</option>
                            <option <?php if ($curso_graduacao == 'Serviço Social') echo 'selected' ?> value="Serviço Social">Serviço Social</option>
                            <option <?php if ($curso_graduacao == 'Administração') echo 'selected' ?> value="Administração">Administração</option>
                            <option <?php if ($curso_graduacao == 'Ciências Contábeis') echo 'selected' ?> value="Ciências Contábeis">Ciências Contábeis</option>
                            <option <?php if ($curso_graduacao == 'Ciências Econômicas') echo 'selected' ?> value="Ciências Econômicas">Ciências Econômicas</option>
                            <option <?php if ($curso_graduacao == 'Ciências Sociais Habilitação em Antropologia') echo 'selected' ?> value="Ciências Sociais Habilitação em Antropologia">Ciências Sociais Habilitação em Antropologia</option>
                            <option <?php if ($curso_graduacao == 'Ciências Sociais Habilitação em Ciência Política') echo 'selected' ?> value="Ciências Sociais Habilitação em Ciência Política">Ciências Sociais Habilitação em Ciência Política</option>
                            <option <?php if ($curso_graduacao == 'Ciências Sociais Habilitação em Sociologia') echo 'selected' ?> value="Ciências Sociais Habilitação em Sociologia">Ciências Sociais Habilitação em Sociologia</option>
                            <option <?php if ($curso_graduacao == 'Ciências Biológicas') echo 'selected' ?> value="Ciências Biológicas">Ciências Biológicas</option>
                            <option <?php if ($curso_graduacao == 'Ciências Sociais') echo 'selected' ?> value="Ciências Sociais">Ciências Sociais</option>
                            <option <?php if ($curso_graduacao == 'Educação Física') echo 'selected' ?> value="Educação Física">Educação Física</option>
                            <option <?php if ($curso_graduacao == 'Enfermagem') echo 'selected' ?> value="Enfermagem">Enfermagem</option>
                            <option <?php if ($curso_graduacao == 'Magistério Física') echo 'selected' ?> value="Magistério Física">Magistério Física</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Física') echo 'selected' ?> value="Magistério - Física">Magistério - Física</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Informática - Licenciatura em Computação e Informática') echo 'selected' ?> value="Magistério - Informática - Licenciatura em Computação e Informática">Magistério - Informática - Licenciatura em Computação e Informática</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Matemática') echo 'selected' ?> value="Magistério - Matemática">Magistério - Matemática</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Química') echo 'selected' ?> value="Magistério - Química">Magistério - Química</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Comunicação Social') echo 'selected' ?> value="Magistério - Comunicação Social">Magistério - Comunicação Social</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Educação Artística') echo 'selected' ?> value="Magistério - Educação Artística">Magistério - Educação Artística</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Filosofia') echo 'selected' ?> value="Magistério - Filosofia">Magistério - Filosofia</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Geografia') echo 'selected' ?> value="Magistério - Geografia">Magistério - Geografia</option>
                            <option <?php if ($curso_graduacao == 'Magistério - História') echo 'selected' ?> value="Magistério - História">Magistério - História</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Música') echo 'selected' ?> value="Magistério - Música">Magistério - Música</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Pedagogia') echo 'selected' ?> value="Magistério - Pedagogia">Magistério - Pedagogia</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Português') echo 'selected' ?> value="Magistério - Letras - Português">Magistério - Letras - Português</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Inglês') echo 'selected' ?> value="Magistério - Letras - Inglês">Magistério - Letras - Inglês</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Espanhol') echo 'selected' ?> value="Magistério - Letras - Espanhol">Magistério - Letras - Espanhol</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Francês') echo 'selected' ?> value="Magistério - Letras - Francês">Magistério - Letras - Francês</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Italiano') echo 'selected' ?> value="Magistério - Letras - Italiano">Magistério - Letras - Italiano</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Alemão') echo 'selected' ?> value="Magistério - Letras - Alemão">Magistério - Letras - Alemão</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Russo') echo 'selected' ?> value="Magistério - Letras - Russo">Magistério - Letras - Russo</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Japonês') echo 'selected' ?> value="Magistério - Letras - Japonês">Magistério - Letras - Japonês</option>
                            <option <?php if ($curso_graduacao == 'Magistério - Letras - Chinês') echo 'selected' ?> value="Magistério - Letras - Chinês">Magistério - Letras - Chinês</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Aeronáutica') echo 'selected' ?> value="Engenharia Aeronáutica">Engenharia Aeronáutica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Ambiental') echo 'selected' ?> value="Engenharia Ambiental">Engenharia Ambiental</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Automotiva') echo 'selected' ?> value="Engenharia Automotiva">Engenharia Automotiva</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Biomédica') echo 'selected' ?> value="Engenharia Biomédica">Engenharia Biomédica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Bioquímica') echo 'selected' ?> value="Engenharia Bioquímica">Engenharia Bioquímica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Civil') echo 'selected' ?> value="Engenharia Civil">Engenharia Civil</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Alimentos') echo 'selected' ?> value="Engenharia de Alimentos">Engenharia de Alimentos</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Automação e Controle (Mecatrônica)') echo 'selected' ?> value="Engenharia de Automação e Controle (Mecatrônica)">Engenharia de Automação e Controle (Mecatrônica)</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Comunicações') echo 'selected' ?> value="Engenharia de Comunicações">Engenharia de Comunicações</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Materiais') echo 'selected' ?> value="Engenharia de Materiais">Engenharia de Materiais</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Produção (civil, de qualidade, de sistemas, elétrica, mecânica, metalúrgica, química).') echo 'selected' ?> value="Engenharia de Produção (civil, de qualidade, de sistemas, elétrica, mecânica, metalúrgica, química).">Engenharia de Produção (civil, de qualidade, de sistemas, elétrica, mecânica, metalúrgica, química).</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Elétrica – Eletrônica') echo 'selected' ?> value="Engenharia Elétrica – Eletrônica">Engenharia Elétrica – Eletrônica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Elétrica – Eletrotécnica') echo 'selected' ?> value="Engenharia Elétrica – Eletrotécnica">Engenharia Elétrica – Eletrotécnica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Mecânica') echo 'selected' ?> value="Engenharia Mecânica">Engenharia Mecânica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Metalúrgica') echo 'selected' ?> value="Engenharia Metalúrgica">Engenharia Metalúrgica</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Naval') echo 'selected' ?> value="Engenharia Naval">Engenharia Naval</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Química') echo 'selected' ?> value="Engenharia Química">Engenharia Química</option>
                            <option <?php if ($curso_graduacao == 'Engenharia Sanitária e Ambiental') echo 'selected' ?> value="Engenharia Sanitária e Ambiental">Engenharia Sanitária e Ambiental</option>
                            <option <?php if ($curso_graduacao == 'Engenharia de Software') echo 'selected' ?> value="Engenharia de Software">Engenharia de Software</option>
                            <option <?php if ($curso_graduacao == 'TeologiaEngenharia Elétrica – El') echo 'selected' ?> value="TeologiaEngenharia Elétrica – El">TeologiaEngenharia Elétrica – El</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em produção audiovisual') echo 'selected' ?> value="Curso superior de tecnologia em produção audiovisual">Curso superior de tecnologia em produção audiovisual</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em produção fonográfica') echo 'selected' ?> value="Curso superior de tecnologia em produção fonográfica">Curso superior de tecnologia em produção fonográfica</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em produção multimídia') echo 'selected' ?> value="Curso superior de tecnologia em produção multimídia">Curso superior de tecnologia em produção multimídia</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em produção publicitária') echo 'selected' ?> value="Curso superior de tecnologia em produção publicitária">Curso superior de tecnologia em produção publicitária</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão da qualidade') echo 'selected' ?> value="Curso superior de tecnologia em gestão da qualidade">Curso superior de tecnologia em gestão da qualidade</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão de recursos humanos') echo 'selected' ?> value="Curso superior de tecnologia em gestão de recursos humanos">Curso superior de tecnologia em gestão de recursos humanos</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão financeira') echo 'selected' ?> value="Curso superior de tecnologia em gestão financeira">Curso superior de tecnologia em gestão financeira</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de logística') echo 'selected' ?> value="Curso superior de logística">Curso superior de logística</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em processos gerenciais') echo 'selected' ?> value="Curso superior de tecnologia em processos gerenciais">Curso superior de tecnologia em processos gerenciais</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão de turismo') echo 'selected' ?> value="Curso superior de tecnologia em gestão de turismo">Curso superior de tecnologia em gestão de turismo</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão desportiva e de lazer') echo 'selected' ?> value="Curso superior de tecnologia em gestão desportiva e de lazer">Curso superior de tecnologia em gestão desportiva e de lazer</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em hotelaria (ou hotelaria hospitalar)') echo 'selected' ?> value="Curso superior de tecnologia em hotelaria (ou hotelaria hospitalar)">Curso superior de tecnologia em hotelaria (ou hotelaria hospitalar)</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em análise e desenvolvimento de sistemas') echo 'selected' ?> value="Curso superior de tecnologia em análise e desenvolvimento de sistemas">Curso superior de tecnologia em análise e desenvolvimento de sistemas</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em banco de dados') echo 'selected' ?> value="Curso superior de tecnologia em banco de dados">Curso superior de tecnologia em banco de dados</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em geoprocessamento') echo 'selected' ?> value="Curso superior de tecnologia em geoprocessamento">Curso superior de tecnologia em geoprocessamento</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão da tecnologia de informação') echo 'selected' ?> value="Curso superior de tecnologia em gestão da tecnologia de informação">Curso superior de tecnologia em gestão da tecnologia de informação</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão de telecomunicações') echo 'selected' ?> value="Curso superior de tecnologia em gestão de telecomunicações">Curso superior de tecnologia em gestão de telecomunicações</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em redes de computadores') echo 'selected' ?> value="Curso superior de tecnologia em redes de computadores">Curso superior de tecnologia em redes de computadores</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em redes de telecomunicações') echo 'selected' ?> value="Curso superior de tecnologia em redes de telecomunicações">Curso superior de tecnologia em redes de telecomunicações</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em segurança da informação') echo 'selected' ?> value="Curso superior de tecnologia em segurança da informação">Curso superior de tecnologia em segurança da informação</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em sistemas de telecomunicações') echo 'selected' ?> value="Curso superior de tecnologia em sistemas de telecomunicações">Curso superior de tecnologia em sistemas de telecomunicações</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em sistemas para internet') echo 'selected' ?> value="Curso superior de tecnologia em sistemas para internet">Curso superior de tecnologia em sistemas para internet</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em telemática') echo 'selected' ?> value="Curso superior de tecnologia em telemática">Curso superior de tecnologia em telemática</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão ambiental') echo 'selected' ?> value="Curso superior de tecnologia em gestão ambiental">Curso superior de tecnologia em gestão ambiental</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em gestão hospitalar') echo 'selected' ?> value="Curso superior de tecnologia em gestão hospitalar">Curso superior de tecnologia em gestão hospitalar</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em oftálmica') echo 'selected' ?> value="Curso superior de tecnologia em oftálmica">Curso superior de tecnologia em oftálmica</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em radiologia') echo 'selected' ?> value="Curso superior de tecnologia em radiologia">Curso superior de tecnologia em radiologia</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em saneamento ambiental') echo 'selected' ?> value="Curso superior de tecnologia em saneamento ambiental">Curso superior de tecnologia em saneamento ambiental</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em segurança no trabalho') echo 'selected' ?> value="Curso superior de tecnologia em segurança no trabalho">Curso superior de tecnologia em segurança no trabalho</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em sistemas biomédicos') echo 'selected' ?> value="Curso superior de tecnologia em sistemas biomédicos">Curso superior de tecnologia em sistemas biomédicos</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em agrimensura') echo 'selected' ?> value="Curso superior de tecnologia em agrimensura">Curso superior de tecnologia em agrimensura</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em construção de edifícios') echo 'selected' ?> value="Curso superior de tecnologia em construção de edifícios">Curso superior de tecnologia em construção de edifícios</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em controle de obras') echo 'selected' ?> value="Curso superior de tecnologia em controle de obras">Curso superior de tecnologia em controle de obras</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em estradas') echo 'selected' ?> value="Curso superior de tecnologia em estradas">Curso superior de tecnologia em estradas</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em material de construção') echo 'selected' ?> value="Curso superior de tecnologia em material de construção">Curso superior de tecnologia em material de construção</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em obras hidráulicas') echo 'selected' ?> value="Curso superior de tecnologia em obras hidráulicas">Curso superior de tecnologia em obras hidráulicas</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em sistemas de navegação fluvial') echo 'selected' ?> value="Curso superior de tecnologia em sistemas de navegação fluvial">Curso superior de tecnologia em sistemas de navegação fluvial</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em transporte terrestre') echo 'selected' ?> value="Curso superior de tecnologia em transporte terrestre">Curso superior de tecnologia em transporte terrestre</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em automação industrial') echo 'selected' ?> value="Curso superior de tecnologia em automação industrial">Curso superior de tecnologia em automação industrial</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em eletrônica industrial') echo 'selected' ?> value="Curso superior de tecnologia em eletrônica industrial">Curso superior de tecnologia em eletrônica industrial</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em manutenção de aeronaves') echo 'selected' ?> value="Curso superior de tecnologia em manutenção de aeronaves">Curso superior de tecnologia em manutenção de aeronaves</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em manutenção industrial') echo 'selected' ?> value="Curso superior de tecnologia em manutenção industrial">Curso superior de tecnologia em manutenção industrial</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em mecatrônica industrial') echo 'selected' ?> value="Curso superior de tecnologia em mecatrônica industrial">Curso superior de tecnologia em mecatrônica industrial</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em processos ambientais') echo 'selected' ?> value="Curso superior de tecnologia em processos ambientais">Curso superior de tecnologia em processos ambientais</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em processos metalúrgicos') echo 'selected' ?> value="Curso superior de tecnologia em processos metalúrgicos">Curso superior de tecnologia em processos metalúrgicos</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em processos químicos') echo 'selected' ?> value="Curso superior de tecnologia em processos químicos">Curso superior de tecnologia em processos químicos</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em sistemas elétricos') echo 'selected' ?> value="Curso superior de tecnologia em sistemas elétricos">Curso superior de tecnologia em sistemas elétricos</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em construção naval') echo 'selected' ?> value="Curso superior de tecnologia em construção naval">Curso superior de tecnologia em construção naval</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em fabricação mecânica') echo 'selected' ?> value="Curso superior de tecnologia em fabricação mecânica">Curso superior de tecnologia em fabricação mecânica</option>
                            <option <?php if ($curso_graduacao == 'Curso superior de tecnologia em produção gráfica') echo 'selected' ?> value="Curso superior de tecnologia em produção gráfica">Curso superior de tecnologia em produção gráfica</option>
                            <option <?php if ($curso_graduacao == 'outro') echo 'selected' ?> value="outro">outro</option>
                        </select>
                    </div>
                </div> -->

                <div  class="col-lg-6">
                        <label>Curso de Formação</label> 
                        <input name="curso_graduacao" value="<?php echo $curso_graduacao ?>" maxlength="50" class="form-control">
                </div>

             <!--   <div  class="col-lg-6">
                    <div  class="form-group"> <label>Selecione a sua arma</label>
                        <select name="arma_eipot" class="form-control">
                            <option value="">Selecione a arma</option>
                            <option <?php if ($arma_eipot == 'Infantaria') echo 'selected' ?> value="Infantaria">Infantaria (INF)</option>
                            <option <?php if ($arma_eipot == 'Cavalaria') echo 'selected' ?> value="Cavalaria">Cavalaria (CAV)</option>
                            <option <?php if ($arma_eipot == 'Artilharia Antiaérea') echo 'selected' ?> value="Artilharia Antiaérea">Artilharia Antiaérea</option>
                            <option <?php if ($arma_eipot == 'Comunicações') echo 'selected' ?> value="Comunicações">Comunicações (COM)</option>
                            <option <?php if ($arma_eipot == 'Intendência') echo 'selected' ?> value="Intendência">Intendência</option>
                            <option value="outra">Outra</option>
                        </select>
                    </div>
                </div> -->
                
                <div  class="col-lg-6">
                    <div class="form-group"> 
                        <label>Ano de formação do OFOR</label> <font color="red"> * Ano com 4 dígitos | Exemplo: 2019</font>
                        <input maxlength="4" name="ano_formacao_ofor" placeholder="AAAA" value="<?php if($ano_formacao_ofor != null) echo $ano_formacao_ofor ?>" class="form-control">
                    </div>
                </div>
                <br>
               
              <!--  <div class="col-lg-6">
                    <div class="form-group"> 
                        <label>Nota OFOR: </label>
                        <input name="nota_ofor" value="<?php echo number_format((float)$nota_ofor, 2, '.', ''); ?>" class="form-control">
                    </div>
                </div> -->

                
            </div>
        </div>
    </div>
</div>

<!-- 
**********************
    CIVIL OU MILITAR 
**********************
-->   

               <!-- 
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
                
                <div  class="col-lg-3" <?php if ($ativa_reserva == '') echo 'hidden' ?> id="div_ja_foi_militar">
                    <div  class="form-group"> <label>Já foi Militar?</label>
                        <select id="ja_foi_militar" name="ja_foi_militar" onchange="select_ja_foi_militar()" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option <?php if ($ativa_reserva == 'nunca_foi_militar') echo 'selected' ?> value="nao">Não</option>
                            <option <?php if ($ativa_reserva == 'ja_foi_militar') echo 'selected' ?> value="sim">Sim</option>
                        </select>
                    </div>
                </div>
                
                <div  class="col-lg-3" <?php if ($certificado == '') echo 'hidden' ?> id="div_certificado">
                    <div  class="form-group"> <label>Documento Militar</label>
                        <select id="certificado" name="certificado" onchange="select_certificado()" class="form-control">
                            <?php echo $select_certificado; ?>
                        </select>
                    </div>
                </div>
                
                <div <?php if ($num_ducumento == '') echo 'hidden' ?> class="col-lg-3" id="div_documento">
                    <div  class="form-group"> 
                        <label>Número do documento militar</label>
                        <input id="documento" value="<?php if ($num_ducumento != '') echo $num_ducumento ?>" maxlength="50" name="documento" class="form-control">
                    </div>
                </div>
                
                <div <?php if ($data_expedicao == '') echo 'hidden' ?>  class="col-lg-3" id="div_data_expedicao">
                    <div  class="form-group"> 
                        <label>Data de Expedição</label>
                        <input id="data_expedicao" maxlength="10" value="<?php if ($data_expedicao != '') echo $data_expedicao ?>" name="data_expedicao" class="form-control">
                    </div>
                </div>

                <div class="col-lg-3" <?php if ($arma_quadro_servico == '') echo 'hidden' ?> id="div_arma">
                    <div  class="form-group"> 
                        <label>Arma/Quadro/Serviço/Especialidade</label>
                        <input id="arma" maxlength="50" name="arma" value="<?php echo $arma_quadro_servico ?>" class="form-control">
                    </div>
                </div>
                
                <div <?php if ($forca == '') echo 'hidden' ?> class="col-lg-3" id="div_forca">
                    <div  class="form-group"> <label>Força</label>
                        <select id="forca" name="forca" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option <?php if ($forca == 'exercito') echo 'selected' ?> value="exercito">Exército</option>
                            <option <?php if ($forca == 'marinha') echo 'selected' ?> value="marinha">Marinha</option>
                            <option <?php if ($forca == 'aeronautica') echo 'selected' ?> value="aeronautica">Aeronáutica</option>
                        </select>
                    </div>
                </div>
                
                <div <?php if ($posto_grad == '') echo 'hidden' ?> class="col-lg-3" id="div_posto_grad">
                    <div  class="form-group"><label>Posto/Graduação</label>
                        <select id="posto_grad" name="posto_grad" class="form-control">
                            <?php echo $select_posto_grad ?>
                        </select>
                    </div>
                </div>
                
                <div  class="col-lg-3" <?php if ($ano_incorporacao == '') echo 'hidden' ?> id="div_incorporacao">
                    <div  class="form-group"> 
                        <label>Ano de incorporação</label>
                        <input id="incorporacao" value="<?php if ($ano_incorporacao != '') echo $ano_incorporacao ?>" maxlength="50" name="incorporacao" class="form-control">
                    </div>
                </div>
                
                <div  class="col-lg-3" <?php if ($licenciamento == '') echo 'hidden' ?> id="div_licenciamento">
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
        </div>-->   
        
        
        
        
               <!-- 
**********************
    Outros
**********************
-->   

<div class="row" <?php if($codigo_selecao != 'mfdv' || isset($_SESSION['6_regiao'])) echo "hidden"; ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Voluntário para 12ª RM / Prioridade de Força   </legend> 
            <div  class="row">
                <div  class="col-lg-4">
                    <div class="form-group"> <label>Voluntário para servir na 12ª Região Militar (Amazonia)</label>
                        <select name="voluntario_12rm" class="form-control">
                            <option value="">Selecione a opção</option>
                            <option <?php if($voluntario_12rm == '1') echo 'selected' ?> value="1">Sim</option>
                            <option <?php if($voluntario_12rm == '0') echo 'selected' ?> value="0">Não</option>
                        </select>
                    </div>
                </div>
                
                <div  class="col-lg-8">
                    <div class="form-group" <?php if(!isset($_SESSION['mfdv'])) echo "hidden"; ?> > <label>Prioridade de Força</label>
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
            </div>
        </div>
    </div>
</div>

<div class="row" <?php if(!isset($_SESSION['6_regiao'])) echo " hidden "; ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Cidade das Etapas Presenciais </legend> 
            <div  class="row">
                
                <div  class="col-lg-12">
                    <div class="form-group"  > <label>Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                        <select name="cidade_etapas_presenciais_6" class="form-control">
                            <option value="">Selecione a Cidade</option>
                            <option <?php if($cidade_etapas_presenciais == 'Aracaju-SE')  echo 'selected' ?> value="Aracaju-SE">Aracaju-SE</option>
                            <option <?php if($cidade_etapas_presenciais == 'Barreiras-BA')  echo 'selected' ?> value="Barreiras-BA">Barreiras-BA</option>
                            <option <?php if($cidade_etapas_presenciais == 'Ilhéus-BA')  echo 'selected' ?> value="Ilhéus-BA">Ilhéus-BA</option>
                            <option <?php if($cidade_etapas_presenciais == 'Feira de Santana-BA')  echo 'selected' ?> value="Feira de Santana-BA">Feira de Santana-BA</option>
                            <option <?php if($cidade_etapas_presenciais == 'Paulo Afonso-BA')  echo 'selected' ?> value="Paulo Afonso-BA">Paulo Afonso-BA</option>
                            <option <?php if($cidade_etapas_presenciais == 'Salvador-BA')  echo 'selected' ?> value="Salvador-BA">Salvador-BA</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="row" <?php if(!isset($_SESSION['8_regiao'])) echo " hidden "; ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Cidade das Etapas Presenciais </legend> 
            <div  class="row">
                
                <div  class="col-lg-12">
                    <div class="form-group"  > <label>Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                        <select name="cidade_etapas_presenciais_8" class="form-control">
                            <option value="">Selecione a Cidade</option>
                            <option <?php if($cidade_etapas_presenciais == 'Belém-PA')  echo 'selected' ?> value="Belém-PA">Belém-PA</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="row" <?php if(!isset($_SESSION['12_regiao']) || $_SESSION['selecao_regiao'] != 12) echo " hidden "; ?>>
    <div class="col-md-12">
        <div class="alert alert-dismissible alert-info">
            <legend>Cidade das Etapas Presenciais </legend> 
            <div  class="row">
                
                <div  class="col-lg-12">
                    <div class="form-group"  > <label>Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                        <select name="cidade_etapas_presenciais12" class="form-control">
                            <option value="">Selecione a Cidade</option>
                            <option <?php if($cidade_etapas_presenciais == 'Manaus')  echo 'selected' ?> value="Manaus">Manaus</option>
                            <option <?php if($cidade_etapas_presenciais == 'Boa Vista')  echo 'selected' ?> value="Boa Vista">Boa Vista</option>
                            <option <?php if($cidade_etapas_presenciais == 'Porto Velho')  echo 'selected' ?> value="Porto Velho">Porto Velho</option>
                            <option <?php if($cidade_etapas_presenciais == 'Rio Branco')  echo 'selected' ?> value="Rio Branco">Rio Branco</option>
                            <option <?php if($cidade_etapas_presenciais == 'Tefé')  echo 'selected' ?> value="Tefé">Tefé</option>
                            <option <?php if($cidade_etapas_presenciais == 'São Gabriel da Cachoeira')  echo 'selected' ?> value="São Gabriel da Cachoeira">São Gabriel da Cachoeira</option>
                            <option <?php if($cidade_etapas_presenciais == 'Tabatinga')  echo 'selected' ?> value="Tabatinga">Tabatinga</option>
                            <option <?php if($cidade_etapas_presenciais == 'Cruzeiro do Sul')  echo 'selected' ?> value="Cruzeiro do Sul">Cruzeiro do Sul</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
        
        
        
        <div class="row">
            <div class="col-lg-12">
                <br>
                <div class="animated-checkbox form-group" id="div_declaracao">
                    <label>
                        <input type="checkbox" id="declaracao" name="declaracao">
                        <span class="label-text">Declaro que li o aviso de convocação e que as informações aqui cadastradas são verdadeiras.</span>
                    </label>
                </div>
            </div>
            <div  class="col-lg-12">
                <div id='div_erro_declaracao' hidden class="alert alert-dismissible alert-danger">
                    <b><center><p id="erro_declaracao"></p></center></b>
                </div>
            </div>
        </div>
        <input hidden value="<?php echo hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']) ?>" name="crip" >      
        <button  type="submit" class="btn btn-primary btn-block">Salvar</button>         
    </div>
</div>
</section>
    
        
    </div>
</div>
        
        
    </div>
</div>
</form>
</div>
  </body>
</html>