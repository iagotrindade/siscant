<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta' && $_SESSION['perfil'] != 'avaliador') {
    erro("Erro 632457437! Página não encontrada!");
    exit();
}

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Cadastro Dados IS - SIPMED<i class="fa fa-address-book"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Cadastro Dados IS - SIPMED</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                                <th>CPF</th>
                                <th>Nome</th>
                                <th>Etapa</th>
                                <th>Especialidade</th>
                                <th>Situação IS</th>
                                <th>Situação ISGR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);

                            foreach ($candidatos as $linha) {
                                $aparece = true;

                                if ($linha['etapa'] < 3) {
                                    continue;
                                }

                                if ($aparece == false) continue;

                                if ($linha['id_selecao'] != $_SESSION['selecao']) continue;

                                $fontColor = "";
                                if ($linha['concorrendo'] == 0) {
                                    $fontColor = '#FF8C73';
                                }

                                $saude = "";

                                if ($linha['apto_saude'] == 1) {
                                    $saude = 'APTO';
                                } elseif ($linha['apto_saude'] == 0  && $linha['grupo_saude']) {
                                    $saude = 'INAPTO';
                                } elseif ($linha['apto_saude'] == 2) {
                                    $saude = 'NÃO COMPARECEU';
                                } elseif ($linha['apto_saude'] == NULL) {
                                    $saude = 'PENDENTE';
                                }

                                if ($linha['apto_saude_recurso'] == 1) {
                                    $recurso_saude = 'APTO';
                                } elseif ($linha['aapto_saude_recurso'] == 0  && $linha['grupo_saude_recurso']) {
                                    $recurso_saude = 'INAPTO';
                                } elseif ($linha['apto_saude_recurso'] == 2) {
                                    $recurso_saude = 'NÃO COMPARECEU';
                                } elseif ($linha['apto_saude_recurso'] == NULL) {
                                    $recurso_saude = 'NÃO REALIZADA';
                                }

                                echo '
                                <tr bgcolor = ' . $fontColor . '>
                                <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario'] . '">' . $linha['cpf'] . '</a></td>
                                <td>' . $linha['nome_completo'] . '</td>
                                <td>_' . $linha['etapa'] . '</td>
                                <td>' . $linha['arma_especialidade'] . '</td>
                                <td>' . $saude . '</td>
                                <td>' . $recurso_saude . '</td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <legend>Cadastro Dados IS - SIPMED
                    <img src="imagens/pdf.png" width="30px">
                </legend>

                <form name="form_relacao_classificacao_eipot_pontuacao" action="mpdf/relatorio_sipmed.php" method="post">

                    <div style="float: left; width: 100%; margin-right: 4%;">
                        <div class="form-group">
                            <label for="titulo_sipmed">Título:</label>
                            <input type="text" name="titulo_sipmed" class="form-control" value="Dados Cadastro para Inspeção de Saúde no SIPMED - Xª RM" required>
                        </div>

                        <div class="form-group">
                            <label for="texto_sipmed">Texto:</label>
                            <input type="text" name="texto_sipmed" class="form-control" value="Tendo em vista a convocação dos candidatos EIPOT/2025, abaixo relacionados, para a realização de Inspeção de Saúde na Xª RM, solicito que os mesmos sejam cadastrados no Sistema de Perícias Médicas - SIPMED para a realização das Inspeções de Saúde no período entre 19 a 21 Maio 25." required>
                        </div>
                    </div>

                    <div class="col-mg-12">
                        <button type="submit" class="btn btn-primary btn-block">GERAR ANEXO</button>
                    </div>
                </form>
            </div>
        </div>
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
<?php $conexao = null; ?>