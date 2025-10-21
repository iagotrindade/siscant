<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != "ouvidor" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 7755! Página não encontrada!");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$lista_suporte = $conexao->get_lista_suporte_candidato($rm_usuario);
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Suporte ao Candidato <i class="fa fa-comments"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Suporte Candidato</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <legend>Suporte Candidato</legend>
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CPF</th>
                                <th>Motivo</th>
                                <th>Mensagem</th>
                                <th>Data Enviado</th>
                                <th>Respondido para o candidato?</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $respondidos = 0;
                            $nao_respondidos = 0;
                            $somatorio_dias_resposta = 0;
                            $maior_tempo = 0;

                            foreach ($lista_suporte as $linha) {
                                $dias_resposta = "";

                                $usuario_respondeu = "_" . mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];

                                $respondido = "_Não";
                                if ($linha['respondida'] == 1) {
                                    $respondido = "_Sim";
                                    $respondidos++;

                                    $dias_resposta = 0;
                                    $data_enviado = new DateTime($linha['data_enviado']);
                                    $data_respondido = new DateTime($linha['data_resposta']);
                                    $intervalo = $data_enviado->diff($data_respondido);
                                    $tempo_total = $intervalo->d + $intervalo->h / 24;
                                    $tempo_total = $tempo_total + $intervalo->i / 1440;
                                    $tempo_total = $tempo_total + $intervalo->s / 86400;

                                    if ($intervalo->m > 0) $tempo_total = $tempo_total + (30 * $intervalo->m);

                                    if ($tempo_total > $maior_tempo) $maior_tempo = $tempo_total;

                                    $somatorio_dias_resposta = $somatorio_dias_resposta + $tempo_total;

                                    $dias_resposta = ", em " . round($tempo_total, 2) . " dias por $usuario_respondeu ";
                                } else $nao_respondidos++;

                                echo '
                                <tr>
                                    <td>' . $linha['id'] . '</td>
                                    <td><a href=usuario_visualiza.php?id_usuario=' . $linha['id_usuario_remetente'] . '>' . $linha['cpf'] . '</a></td>
                                    <td>_' . $linha['motivo'] . '</td>
                                    <td>' . $linha['mensagem'] . '</td>
                                    <td>' . trata_data_hora($linha['data_enviado']) . '</td>
                                    <td>' . $respondido . $dias_resposta . '</td>
                                    <td><a href="suporte_candidato_visualiza.php?id_suporte=' . $linha['id'] . '"><img src="imagens/lupa.png" width="30px"></td>
                                </tr>';
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                <div class="row">
                    <font size="4px">
                        <div class="col-md-3">
                            <b>Respondidos:</b> <?php echo $respondidos ?><br>
                        </div>
                        <div class="col-md-3">
                            <b>Não respondidos:</b>
                            <?php
                            if ($respondidos > 0 || $nao_respondidos > 0)
                                $porcentagem = round(($nao_respondidos / ($nao_respondidos + $respondidos)) * 100, 2);

                            if ($nao_respondidos > 0)
                                echo "<font color='red'>" . $nao_respondidos . " ($porcentagem%)</font>";
                            else echo '0';
                            ?>
                        </div>
                        <div class="col-md-3">
                            <b>Média de resp:</b> <?php if ($respondidos > 0 && $somatorio_dias_resposta > 0)  echo round($somatorio_dias_resposta / $respondidos, 2) . " dias"; ?> <br>
                        </div>
                        <div class="col-md-3">
                            <b>Maior tempo:</b> <?php echo round($maior_tempo, 2) . " dias"; ?> <br>
                        </div>
                    </font>
                </div>
            </div>

            <div class="card" <?php if ($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
                <legend>Quantidade de respostas por usuário</legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <table class="table table-hover table-bordered" id="tabela_dinamica">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Qtd Respostas</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    $lista_get_quantidade_x_usuario_suporte_cand = $conexao->get_quantidade_x_usuario_suporte_cand();

                                    foreach ($lista_get_quantidade_x_usuario_suporte_cand as $linha) {
                                        $foto = "user.jpg";
                                        $get_foto = $conexao->get_foto_usuario($linha['id']);
                                        if (count($get_foto) > 0)
                                            $foto = $get_foto[0]['nome'];

                                        $nome_usuario = mb_strtoupper($linha['posto_grad']) . " " . $linha['nome_guerra'];

                                        echo "<tr>
                                            <td>" . $nome_usuario . "</td>
                                            <td>" . $linha['quantidade'] . "</td>
                                            <td width='40px' align='center'><a href='usuario_visualiza.php?id_usuario=" . $linha['id'] . "'><img class='img-circle' src='fotos/" . $foto . "' width='40px'></a></td>
                                        </tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" <?php if ($_SESSION['perfil'] != 'admin') echo "hidden" ?>>
                <legend>Quantidade de suportes por categoria</legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <table class="table table-hover table-bordered" id="tabela_dinamica">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    $lista_quantidade_x_motivo_suporte_cand = $conexao->get_quantidade_x_motivo_suporte_cand();

                                    foreach ($lista_quantidade_x_motivo_suporte_cand as $linha) {
                                        echo "<tr>
                                            <td>" . $linha['motivo'] . "</td>
                                            <td>" . $linha['quantidade'] . "</td>
                                        </tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
</script>
</body>

</html>
<?php $conexao = null; ?>