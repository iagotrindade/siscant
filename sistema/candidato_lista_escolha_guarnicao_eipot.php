<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1 || $perfil == 'avaliador' || $perfil == 'ouvidor') {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$rm_usuario = $conexao->rm_usuario($id_usuario);

$candidatos_eipot = $conexao->get_candidatos_eipot();
$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);

$ordem_arma = [
    'INFANTARIA',
    'CAVALARIA',
    'ARTILHARIA DE CAMPANHA',
    'ARTILHARIA ANTIAÉREA',
    'ENGENHARIA',
    'COMUNICAÇÕES',
    'MATERIAL BÉLICO',
    'INTENDÊNCIA'
];

$inscritos_por_arma = [];

foreach ($candidatos_eipot as $inscrito) {
    if ($inscrito['etapa'] < 6) {
        continue;
    }
    $inscrito['nota_final'] = get_nota_final_eipot($inscrito['id']);
    $arma = $inscrito['arma_especialidade'] ?? 'Não Informada';
    $inscritos_por_arma[$arma][] = $inscrito;
}

// Ordena os grupos de armas conforme a ordem definida
uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);
    $pos_a = $pos_a === false ? PHP_INT_MAX : $pos_a;
    $pos_b = $pos_b === false ? PHP_INT_MAX : $pos_b;
    return $pos_a <=> $pos_b;
});

// Ordena candidatos por nota dentro de cada arma
foreach ($inscritos_por_arma as &$candidatos) {
    usort($candidatos, function ($a, $b) {
        return $b['nota_final'] <=> $a['nota_final'];
    });

    foreach ($candidatos as $i => &$inscrito) {
        $inscrito['classificacao'] = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    }
    unset($inscrito);
}
unset($candidatos);
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Escolha de Guarnição <i class="fa fa-map-pin"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Escolha de Guarnição</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <legend>Atenção <img src="imagens/urgente.gif" height="25px"></legend>

                <div class="alert alert-info p-20">
                    <b>É necessário que o candidato esteja na etapa VI para ser inserido no Ranking.</b>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($inscritos_por_arma as $arma => $candidatos) { ?>
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Candidato em escolha -->
                        <div class="p-10 alert-info col-md-12 text-center mb-20" style="border-radius: 10px;" hidden>
                            <h4 class="mb-10 col-md-12">Candidato escolhendo no momento (Tempo decorrido: 15:32)</h4>
                            <h4 class="mb-20 col-md-12">Rodada atual: 1ª Região Militar</h4>

                            <div class="col-md-3">
                                <img src="caminho_para_foto/<?php echo $candidato_escolhendo['foto'] ?? '../imagens/user.jpg'; ?>"
                                    alt="Foto do Candidato"
                                    class="rounded-circle me-4"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </div>

                            <div class="col-md-3 mt-20">
                                <h5 class="mb-1"><?php echo $candidato_escolhendo['nome_completo'] ?? 'Nome do Candidato'; ?></h5>
                                <p class="mb-1"><strong>Telefone:</strong> <?php echo $candidato_escolhendo['telefone'] ?? '(00) 00000-0000'; ?></p>
                            </div>

                            <div class="col-md-3 mt-20">

                                <p class="mb-1"><strong>Email:</strong> <?php echo $candidato_escolhendo['email'] ?? 'email@exemplo.com'; ?></p>
                                <p class="mb-0"><strong>RM Etapa Presencial:</strong> <?php echo $candidato_escolhendo['rm_inscricao'] ?? '-'; ?>ª Região Militar</p>
                            </div>

                            <div class="col-md-3 mt-20">

                                <p class="mb-1"><strong>Regiões de Interesse:</strong> 1, 2, 3</p>
                                <p class="mb-0"><strong>Nota Final:</strong> 8,50</p>
                            </div>
                        </div>

                        <!-- Legenda da tabela -->
                        <legend class="mt-2">Candidatos de todo o Brasil na Etapa VI - <?php echo htmlspecialchars($arma); ?></legend>

                        <div class="card-body">
                            <table class="table table-hover table-bordered tabela-dinamica">
                                <thead>
                                    <tr>
                                        <th>Nota Final</th>
                                        <th>CPF</th>
                                        <th>Nome</th>
                                        <th>Concorrendo Cotas</th>
                                        <th>RM Etapa Presencial</th>
                                        <th>RM de Interesse</th>
                                        <th>Guarnição Escolhida</th>
                                        <th>Ver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($candidatos as $linha) {
                                        if ($linha['vaga_reservada'] == 1) {
                                            $recursos = $conexao->get_recursos_eipot($id_selecao, $rm_usuario);

                                            $pareceres = $conexao->get_pareceres_heteroidentificacao($linha['id']);

                                            $parecerHc = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 1);
                                            $parecerRevisora = get_parecer_final_heteroidentificacao($linha['id'], $pareceres, 2);

                                            $pareceresFase1 = array_filter($pareceres, function ($parecer) {
                                                return $parecer['fase'] == 1;
                                            });

                                            $pareceresFase2 = array_filter($pareceres, function ($parecer) {
                                                return $parecer['fase'] == 2;
                                            });

                                            $quantidadeFase1Confirmados = 0;
                                            $quantidadeFase2Confirmados = 0;

                                            foreach ($pareceresFase1 as $parecer) {
                                                if ($parecer['parecer'] == 'confirmada') {
                                                    $quantidadeFase1Confirmados++;
                                                }
                                            }

                                            foreach ($pareceresFase2 as $parecer) {
                                                if ($parecer['parecer'] == 'confirmada') {
                                                    $quantidadeFase2Confirmados++;
                                                }
                                            }

                                            if ($quantidadeFase1Confirmados >= 3 || $quantidadeFase2Confirmados >= 2) {
                                                $vaga_reservada = 'SIM';
                                            } else {
                                                $vaga_reservada = 'NÃO';
                                            }
                                        } else {
                                            $vaga_reservada = 'NÃO';
                                        }

                                        echo '<tr>';
                                        echo '<td>' . $linha['nota_final'] . '</td>';
                                        echo '<td>' . $linha['cpf'] . '</td>';
                                        echo '<td>' . $linha['nome_completo'] . '</td>';
                                        echo '<td>' . $vaga_reservada . '</td>';
                                        echo '<td>' . $linha['rm_inscricao'] . 'ª Região Militar</td>';
                                        echo '<td>' . $linha['rm_destino'] . '</td>';
                                        echo '<td>' . $linha['nome_cidade_escolhida'] . '</td>';
                                        echo '<td width="40px"><a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '">Ver</a></td>';
                                        echo '</tr>';
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        

        <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.tabela-dinamica').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
                    }
                });
            });
        </script>
</div>
</body>

</html>

<?php $conexao = null; ?>