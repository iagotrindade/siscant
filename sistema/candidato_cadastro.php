<?php
include_once './menu_candidato.php';
include_once './codigos/candidato_campos_cadastra.php';
include_once './codigos/candidato_valida_cadastro.php';

//   print_r($_SESSION['eipot']);
?>

<script>
    $(document).ready(function() {
        //$("input[name*='data']").mask("99/99/9999");
        //$("input[name*='cpf']").mask("999.999.999-99");

        $("input[name*='nota_ofor']").mask("99.99");
        $("input[name*='ano_formacao_ofor']").mask("9999");

        //$("input[name*='valor']").maskMoney({showSymbol:true, symbol:"R$ ", decimal:",", thousands:"."});

        //$("#cnpj").mask("99.999.999/9999-99"); // Pega pelo ID
        //$("#cpf").mask("999.999.999-99"); // Pega pelo ID
    });
</script>

<div class="content-wrapper">
    <div class="card">
        <div class="row">
            <div class="col-md-6">
                <legend><b>SiSCanT - <?php if (isset($_SESSION['apresentacao_candidato'])) echo $_SESSION['apresentacao_candidato'] ?></b> <small class="pull-right">...</small> </legend>
                <p>Sistema de Seleção de Canditados Temporários</p>
            </div>
            <div class="col-md-6">
                <legend>Data: <?php echo date("d/m/Y"); ?><small class="pull-right"><a href="../index.php"><img src="imagens/forca.jpg" width="60px"></a></small></legend>
                <!-- <font size="5px"><b><a class="btn btn-info" href="suporte_inicial.php">Suporte <i class="fa fa-support"></i></a></b></font> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="../banco_dados/candidato_cadastra.php" method="post" onsubmit="return candidato_valida_cadastro()">

                <div class="card">
                    <section class="invoice">
                        <div class="row">
                            <div class="col-xs-12">
                                <legend>Cadastro de candidato para acesso ao sistema <i class="fa fa-id-card-o"></i>
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
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div id="div_nome_completo" class="form-group">
                                                <label for="nome">Nome completo</label>
                                                <input id="nome_completo" name="nome_completo" maxlength="120" class="form-control">
                                            </div>
                                            <div id="div_cpf" class="form-group">
                                                <label align="right">CPF</label><span id="cpf_mensagem"></span>
                                                <input id="cpf" name="cpf" class="form-control" onfocus="limpa_cpf()" onblur="verifica_cpf()">
                                            </div>
                                            <div id="div_identidade" class="form-group">
                                                <label>Identidade (Número/ Órgão expedidor)</label>
                                                <input id="identidade" name="identidade" maxlength="20" class="form-control">
                                            </div>

                                            <div id="div_nome_usuario" class="form-group">
                                                <label>Data de nascimento</label>
                                                <input id="data_nascimento" name="data_nascimento" maxlength="25" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group"> <label>Sexo</label>
                                                <select id="sexo" name="sexo" class="form-control" onchange="select_sexo()">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="masculino">Masculino</option>
                                                    <option value="feminino">Feminino</option>
                                                </select>
                                            </div>
                                            <div id="div_nascionalidade" class="form-group">
                                                <label>Nacionalidade (País)</label>
                                                <input id="nascionalidade" maxlength="30" name="nascionalidade" class="form-control">
                                            </div>
                                            <div id="div_naturalidade" class="form-group">
                                                <label>Naturalidade (Cidade/UF)</label>
                                                <input id="naturalidade" maxlength="30" name="naturalidade" class="form-control">
                                            </div>

                                            <div class="form-group"> <label>Dependentes</label>
                                                <select name="num_dependentes" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="0">Não possuo dependentes</option>
                                                    <option value="1">Possuo 1 dependente</option>
                                                    <option value="2">Possuo 2 dependentes</option>
                                                    <option value="3">Possuo 3 dependentes</option>
                                                    <option value="4">Possuo 4 dependentes</option>
                                                    <option value="5">Possuo 5 dependentes</option>
                                                    <option value="6">Possuo 6 dependentes</option>
                                                    <option value="7">Possuo 7 dependentes</option>
                                                    <option value="8">Possuo 8 dependentes</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div id="div_estado_civil" class="form-group"> <label>Estado Civil</label>
                                                <select id="estado_civil" name="estado_civil" onchange="mostra_companheiro()" class="form-control">
                                                    <option value="">Selecione o Estado Civil</option>
                                                    <option value="solteiro">Solteiro </option>
                                                    <option value="uniao_estavel">União Estável </option>
                                                    <option value="casado">Casado </option>
                                                    <option value="viuvo">Viúvo </option>
                                                    <option value="outro">Outro</option>
                                                </select>
                                            </div>

                                            <div hidden id="div_companheiro" class="form-group">
                                                <label>Nome do Companheiro(a)</label>
                                                <input id="companheiro" maxlength="85" name="nome_companheiro" class="form-control">
                                            </div>

                                            <div id="div_filiacao_mae" class="form-group">
                                                <label>Filiação (Mãe)</label>
                                                <input id="filiacao_mae" maxlength="120" name="filiacao_mae" class="form-control">
                                            </div>

                                            <div id="div_filiacao_pai" class="form-group">
                                                <label>Filiação (Pai)</label>
                                                <input id="filiacao_pai" maxlength="120" name="filiacao_pai" class="form-control">
                                            </div>


                                            <div class="form-group">
                                                <label>Autodeclaração</label>
                                                <select id="autodeclaracao" name="autodeclaracao" onchange="mostra_vaga_reservada()" class="form-control">
                                                    <option value="">Selecione a Opção</option>
                                                    <option value="branco">Branco</option>
                                                    <option value="preto">Preto</option>
                                                    <option value="pardo">Pardo</option>
                                                    <option value="indio">Índio</option>
                                                    <option value="amarelo">Amarelo</option>
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div id="div_vaga_reservada" class="animated-checkbox form-group" style="display: none;">
                                                        <label>
                                                            <input type="checkbox" id="check_vaga_reservada" name="vaga_reservada">
                                                            <span class="label-text">Quero concorrer as vagas reservadas para Negros (Lei Nr12.990, de 9 de Junho de 2014)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                function mostra_vaga_reservada() {
                                                    if ($('#autodeclaracao').val() == 'preto' || $('#autodeclaracao').val() == 'pardo') {
                                                        $('#div_vaga_reservada').show();
                                                    } else {
                                                        $('#div_vaga_reservada').hide();
                                                    }
                                                }
                                            </script>


                                            <div hidden class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="animated-checkbox form-group">
                                                        <label>
                                                            <input type="checkbox" id="check_nome_social" name="check_nome_social" onchange="mostra_nome_social()">
                                                            <span class="label-text">Possuo nome social</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div id="div_nome_social" hidden class="form-group">
                                                        <label for="nome_social">Nome social</label>
                                                        <input id="nome_social" name="nome_social" maxlength="120" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-lg-12">
                                            <div id='div_erro_dados_pessoais' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_dados_pessoais"></p>
                                                    </center>
                                                </b>
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
                                        <div class="col-lg-4">
                                            <div id="div_uf" class="form-group"> <label>UF</label>
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
                                                    <option value="RR">RR</option>
                                                    <option value="RS">RS</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SE">SE</option>
                                                    <option value="SP">SP</option>
                                                    <option value="TO">TO</option>
                                                </select>
                                            </div>
                                            <div id="div_bairro" class="form-group">
                                                <label>Bairro</label>
                                                <input id="bairro" maxlength="40" name="bairro" class="form-control">
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div id="div_cidade" class="form-group"> <label>Cidade</label>
                                                <select id="cidade" name="cidade" class="form-control">
                                                    <option value="1">Selecione a UF</option>
                                                </select>
                                            </div>

                                            <div id="div_cep" class="form-group">
                                                <label>CEP</label>
                                                <input id="cep" maxlength="20" name="cep" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div id="div_rua" class="form-group">
                                                <label>Avenida/Rua, número e complemento</label>
                                                <input id="rua" maxlength="200" name="rua" class="form-control">
                                            </div>

                                        </div>
                                        <div class="col-lg-12">
                                            <div id='div_erro_endereco' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_endereco"></p>
                                                    </center>
                                                </b>
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
                                    <legend>Contato </legend>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <div id="div_celular" class="form-group">
                                                <label>Telefone para CONTATO</label>
                                                <font color="red"> *Coloque o DDD</font>
                                                <input id="celular" maxlength="50" name="celular" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div id="div_telefone" class="form-group">
                                                <label>Telefone para RECADOS</label>
                                                <font color="red"> *Coloque o DDD</font>
                                                <input id="telefone" maxlength="50" name="telefone" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div id="div_mail" class="form-group">
                                                <label>E-Mail</label>
                                                <input id="mail" maxlength="50" name="mail" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div id="div_mail2" class="form-group">
                                                <label>Repita o seu E-Mail</label>
                                                <input id="mail2" maxlength="50" name="mail2" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div id='div_erro_contato' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_contato"></p>
                                                    </center>
                                                </b>
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

                        <div class="row" <?php if (!isset($_SESSION['eipot'])) echo ' hidden '; ?>>
                            <div class="col-md-12">
                                <div class="alert alert-dismissible alert-info">
                                    <legend>Informações EIPOT </legend>
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group"> <label>Nome do curso de graduação </label>
                                                <font color="red"> *Deve ter sido concluído até dia 03 de Julho de 2023</font>
                                                <input maxlength="200" name="curso_graduacao" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Ano de formação do CPOR ou NPOR (OFOR)</label>
                                                <font color="red"> * Ano com 4 dígitos | Exemplo: 2019</font>
                                                <input maxlength="4" name="ano_formacao_ofor" placeholder="AAAA" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Nota de conclusão do CPOR/NPOR (OFOR) </label>
                                                <font color="red"> * Exemplo: 07.60</font>
                                                <input maxlength="50" name="nota_ofor" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group"> <label>Selecione a sua arma de formação</label>
                                                <select name="arma_eipot" class="form-control"">
                            <option value="">Selecione a arma</option>
                            <option value=" Infantaria">Infantaria (INF)</option>
                                                    <option value="Cavalaria">Cavalaria (CAV)</option>
                                                    <option value="Artilharia Antiaérea">Artilharia Antiaérea</option>
                                                    <option value="Engenharia">Engenharia</option>
                                                    <option value="Material Bélico">Material Bélico</option>
                                                    <option value="Intendência">Intendência</option>
                                                    <option value="outra">Outra</option>
                                                </select>
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
                        <div class="row" <?php if (!isset($_SESSION['mfdv'])) echo " hidden "; ?>>
                            <div class="col-md-12">
                                <div class="alert alert-dismissible alert-info">
                                    <legend>Instituição de Ensino de Formação</legend>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Nome do Instituto de Ensino</label>
                                                <input maxlength="200" name="nome_ie" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div id="div_rua" class="form-group">
                                                <label>Ano de formação</label>
                                                <select name="ano_formacao" class="form-control">
                                                    <option value="">Selecione o ano</option>
                                                    <?php
                                                    for ($i = date("Y"); $i >= 1980; $i--) {
                                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div id="div_uf" class="form-group"> <label>UF da Intituição de Ensino</label>
                                                <select id="uf_ie" name="uf_ie" class="form-control" onchange="busca_cidades_ie()">
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
                                                    <option value="RR">RR</option>
                                                    <option value="RS">RS</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SE">SE</option>
                                                    <option value="SP">SP</option>
                                                    <option value="TO">TO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div id="div_cidade" class="form-group"> <label>Cidade da Intituição de Ensino</label>
                                                <select id="cidade_ie" name="cidade_ie" class="form-control">
                                                    <option value="">Selecione primeiramente a UF</option>
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
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div id="div_tempo_sv_pub" class="form-group"> <label>Possui tempo de serviço público até a data final da inscrição?</label>
                                                <select id="tempo_sv_pub" name="tempo_sv_pub" class="form-control" onchange="tempo_servico_publico()">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="0">Não</option>
                                                    <option value="1">Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_pub_anos" class="form-group"> <label>Anos</label>
                                                <select id="tempo_sv_pub_anos" name="tempo_sv_pub_anos" class="form-control">
                                                    <option value="">Selecione quantos anos</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_pub_meses" class="form-group"> <label>Meses</label>
                                                <select id="tempo_sv_pub_meses" name="tempo_sv_pub_meses" class="form-control">
                                                    <option value="">Selecione quantos meses</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_pub_dias" class="form-group"> <label>Dias</label>
                                                <select id="tempo_sv_pub_dias" name="tempo_sv_pub_dias" class="form-control">
                                                    <option value="">Selecione quantos dias</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div id='div_erro_tempo_sv_pub' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_tempo_sv_pub"></p>
                                                    </center>
                                                </b>
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

                        <div class="row" <?php if (isset($_SESSION['eipot']) && $_SESSION['eipot'] == 1) echo " hidden " ?>>
                            <div class="col-md-12">
                                <div class="alert alert-dismissible alert-info">
                                    <legend>Tempo de serviço militar (nas Forças Armadas) até a data final da inscrição </legend>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div id="div_tempo_sv_mil" class="form-group" onchange="tempo_servico_militar()"> <label>Possui tempo de serviço militar (nas Forças Armadas) até a data final da inscrição?</label>
                                                <select id="tempo_sv_mil" name="tempo_sv_mil" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="0">Não</option>
                                                    <option value="1">Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_mil_anos" class="form-group"> <label>Anos</label>
                                                <select id="tempo_sv_mil_anos" name="tempo_sv_mil_anos" class="form-control">
                                                    <option value="">Selecione quantos anos</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_mil_meses" class="form-group"> <label>Meses</label>
                                                <select id="tempo_sv_mil_meses" name="tempo_sv_mil_meses" class="form-control">
                                                    <option value="">Selecione quantos meses</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div hidden id="div_tempo_sv_mil_dias" class="form-group"> <label>Dias</label>
                                                <select id="tempo_sv_mil_dias" name="tempo_sv_mil_dias" class="form-control">
                                                    <option value="">Selecione quantos dias</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div id='div_erro_tempo_sv_mil' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_tempo_sv_mil"></p>
                                                    </center>
                                                </b>
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
                                    <legend>Civil ou Militar Temporário das Forças Armada</legend>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <div id="div_civil_militar" class="form-group"> <label>Você é civil ou militar temporário?</label>
                                                <select id="civil_militar" name="civil_militar" onchange="select_civil_militar()" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="civil">Civil</option>
                                                    <option value="militar">Militar Temporário das Forças Armadas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_ja_foi_militar">
                                            <div class="form-group"> <label>Já foi Militar?</label>
                                                <select id="ja_foi_militar" name="ja_foi_militar" onchange="select_ja_foi_militar()" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="nao">Não</option>
                                                    <option value="sim">Sim</option>
                                                    <option value="sim_eas" <?php if (!isset($_SESSION['mfdv'])) echo "hidden"; ?>>Sim, já fiz o EAS</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_certificado">
                                            <div class="form-group"> <label>Documento Militar</label>
                                                <select id="certificado" name="certificado" onchange="select_certificado()" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_documento">
                                            <div class="form-group">
                                                <label>Número do documento militar</label>
                                                <input id="documento" maxlength="50" name="documento" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_data_expedicao">
                                            <div class="form-group">
                                                <label>Data de Expedição</label>
                                                <input id="data_expedicao" maxlength="10" name="data_expedicao" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_arma">
                                            <div class="form-group">
                                                <label>Arma/Quadro/Serviço/Especialidade</label>
                                                <input id="arma" maxlength="50" name="arma" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_forca">
                                            <div class="form-group"> <label>Força</label>
                                                <select id="forca" name="forca" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="exercito">Exército</option>
                                                    <option value="marinha">Marinha</option>
                                                    <option value="aeronautica">Aeronáutica</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_posto_grad">
                                            <div class="form-group"><label>Posto/Graduação</label>
                                                <select id="posto_grad" name="posto_grad" class="form-control">
                                                    <option value="">Selecione a opção</option>
                                                    <option value="sd">Soldado</option>
                                                    <option value="cb">Cabo</option>
                                                    <option value="3_sgt">3º Sargento</option>
                                                    <option value="asp">Aspirante</option>
                                                    <option value="2_ten">2º Tenente</option>
                                                    <option value="1_Ten">1º Tenente</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_incorporacao">
                                            <div class="form-group">
                                                <label>Ano de incorporação</label>
                                                <input id="incorporacao" maxlength="50" name="incorporacao" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-3" hidden id="div_licenciamento">
                                            <div class="form-group">
                                                <label>Licenciamento</label>
                                                <input id="licenciamento" maxlength="50" name="licenciamento" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div id='div_erro_civil_militar' hidden class="alert alert-dismissible alert-danger">
                                                <b>
                                                    <center>
                                                        <p id="erro_civil_militar"></p>
                                                    </center>
                                                </b>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <!-- 
**********************
    Outros
**********************
-->

                                <div class="row" <?php
                                                    //if(!isset($_SESSION['mfdv']) || isset($_SESSION['6_regiao']) || isset($_SESSION['12_regiao'])) 
                                                    echo " hidden ";
                                                    ?>>
                                    <div class="col-md-12">
                                        <div class="alert alert-dismissible alert-info">
                                            <legend>Voluntário para 12ª RM / Prioridade de Força</legend>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group"> <label>Voluntário para servir na 12ª Região Militar (Amazonia)</label>
                                                        <select name="voluntario_12rm" class="form-control">
                                                            <option value="">Selecione a opção</option>
                                                            <option value="1">Sim</option>
                                                            <option value="0">Não</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="form-group" <?php if (!isset($_SESSION['mfdv'])) echo "hidden"; ?>> <label>Prioridade de Força</label>
                                                        <select name="prioridade_forca" class="form-control">
                                                            <option value="">Selecione as prioridades</option>
                                                            <option value="qualquer">Qualquer Força</option>
                                                            <option value="EAM">1ª Exército - 2ª Aeronáutica - 3ª Marinha</option>
                                                            <option value="EMA">1ª Exército - 2ª Marinha - 3ª Aeronáutica </option>
                                                            <option value="MEA">1ª Marinha - 2ª Exército - 3ª Aeronáutica </option>
                                                            <option value="MAE">1ª Marinha - 2ª Aeronáutica - 3ª Exército </option>
                                                            <option value="AME">1ª Aeronáutica - 2ª Marinha - 3ª Exército </option>
                                                            <option value="AEM">1ª Aeronáutica - 2ª Exército - 3ª Marinha </option>
                                                            <option value="EQ">1ª Exército - 2ª e 3ª Qualquer outra força </option>
                                                            <option value="MQ">1ª Marinha - 2ª e 3ª Qualquer outra força </option>
                                                            <option value="AQ">1ª Aeronáutica - 2ª e 3ª Qualquer outra força </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" <?php if (!isset($_SESSION['6_regiao'])) echo " hidden "; ?>>
                                    <div class="col-md-12">
                                        <div class="alert alert-dismissible alert-info">
                                            <legend>Cidade das Etapas Presenciais </legend>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group"> <label>Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                                                        <select name="cidade_etapas_presenciais_6" class="form-control">
                                                            <option value="">Selecione a Cidade</option>
                                                            <option value="Aracaju-SE">Aracaju-SE</option>
                                                            <option value="Barreiras-BA">Barreiras-BA</option>
                                                            <option value="Feira de Santana-BA">Feira de Santana-BA</option>
                                                            <option value="Ilhéus-BA">Ilhéus - BA</option>
                                                            <option value="Paulo Afonso-BA">Paulo Afonso - BA</option>
                                                            <option value="Salvador-BA">Salvador-BA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" <?php if (!isset($_SESSION['12_regiao'])) echo " hidden "; ?>>
                                    <div class="col-md-12">
                                        <div class="alert alert-dismissible alert-info">
                                            <legend>Cidades para realização da Inspeção de Saúde (IS) e EAF</legend>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group"> <label>Selecione a cidade na qual você quer participar das etapas presenciais (entrega física da documentação, inspeção de saúde e exame de aptidão física) deste processo seletivo</label>
                                                        <select name="cidade_etapas_presenciais12" class="form-control">
                                                            <option value="">Selecione a Cidade</option>
                                                            <option value="Manaus">Manaus</option>
                                                            <option value="Boa Vista">Boa Vista</option>
                                                            <option value="Porto Velho">Porto Velho</option>
                                                            <option value="Rio Branco">Rio Branco</option>
                                                            <option value="Tefé">Tefé</option>
                                                            <option value="São Gabriel da Cachoeira">São Gabriel da Cachoeira</option>
                                                            <option value="Tabatinga">Tabatinga</option>
                                                            <option value="Cruzeiro do Sul">Cruzeiro do Sul</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" <?php if (!isset($_SESSION['12_regiao_musica'])) echo " hidden "; ?>>
                                    <div class="col-md-12">
                                        <div class="alert alert-dismissible alert-info">
                                            <legend>Cidade da realização da prova do exame de comprovação de habilidade musical</legend>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group"> <label>Selecione a cidade na qual você deseja participar prova do exame de comprovação de habilidade musical</label>
                                                        <select name="cidade_exame_musica_12rm" class="form-control">
                                                            <option value="">Selecione a Cidade</option>
                                                            <option value="Manaus - AM">Manaus - AM</option>
                                                            <option value="Humaitá - AM">Humaitá - AM</option>
                                                            <option value="São Gabriel da Cachoeira – AM">São Gabriel da Cachoeira – AM</option>
                                                            <option value="Tabatinga – AM">Tabatinga – AM</option>
                                                            <option value="Tefé – AM">Tefé – AM</option>
                                                            <option value="Porto Velho – RO">Porto Velho – RO</option>
                                                            <option value="Guajará Mirim - RO">Guajará Mirim - RO</option>
                                                            <option value="Rio Branco - AC">Rio Branco - AC</option>
                                                            <option value="Cruzeiro do Sul - AC">Cruzeiro do Sul - AC</option>
                                                            <option value="Boa Vista – RR">Boa Vista – RR</option>
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
                                                <br>
                                                <font color="red" size="5px"> ATENÇÃO! Ao clicar em Cadastrar, aguarde! O processo pode levar de 2 a 5 minutos. Não feche a aba ou
                                                    o navegador.
                                                </font>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div id='div_erro_declaracao' hidden class="alert alert-dismissible alert-danger">
                                            <b>
                                                <center>
                                                    <p id="erro_declaracao"></p>
                                                </center>
                                            </b>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">CADASTRAR</button>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('mail2').addEventListener('paste', function(e) {
        e.preventDefault();
        alert('Colar não é permitido neste campo. Por favor, digite o e-mail novamente.');
    });
    $('body').removeClass("sidebar-mini").addClass("sidebar-collapse");
</script>
</body>

</html>