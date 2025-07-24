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
        <?php
        $candidato_escolhendo = null;
        $etapa = 'rm';

        // Verifica se existe algum candidato que ainda não escolheu a RM
        $proximo_rm = null;
        foreach ($candidatos as $cand) {
            if (empty($cand['rm_escolheu_servir'])) {
                $proximo_rm = $cand;
                break;
            }
        }

        // Se ainda há alguém sem RM, esse é o próximo a escolher
        if ($proximo_rm) {
            $candidato_escolhendo = $proximo_rm;
            $etapa = 'rm';
        } else {
            // Todos já escolheram RM, agora procura quem não escolheu a cidade
            foreach ($candidatos as $cand) {
                if (empty($cand['cidade_escolheu_servir'])) {
                    $candidato_escolhendo = $cand;
                    $etapa = 'cidade';
                    break;
                }
            }
        }

        // Formata o campo rm_destino (se houver candidato atual)
        if ($candidato_escolhendo && !empty($candidato_escolhendo['rm_destino'])) {
            $candidato_escolhendo['rm_destino'] = implode(', ', array_map(function ($n) {
                return $n . 'ª';
            }, explode(',', $candidato_escolhendo['rm_destino'])));

            $candidato_escolhendo['cpf'] = mascara($candidato_escolhendo['cpf'], '###.###.###-##');

            $candidato_escolhendo['foto'] = $conexao->get_foto_usuario($candidato_escolhendo['id']);
        }

        // Encontrar o candidato anterior ao atual
        $anterior = null;
        foreach ($candidatos as $cand) {
            if ($candidato_escolhendo && $cand['id'] == $candidato_escolhendo['id']) {
                break;
            }
            $anterior = $cand;
        }

        // Calcula o tempo decorrido
        $tempo_em_segundos = 0;

        if ($candidato_escolhendo && !empty($candidato_escolhendo['_data_ultima_atualizacao'])) {
            $ultimaAtualizacao = new DateTime($candidato_escolhendo['_data_ultima_atualizacao']);
            $agora = new DateTime();
            $tempo_em_segundos = max(0, $agora->getTimestamp() - $ultimaAtualizacao->getTimestamp());
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Candidato em escolha -->
                        <?php if ($candidato_escolhendo): ?>
                            <div class="p-10 alert-info col-md-12 text-center mb-20" style="border-radius: 10px;">
                                <h4 class="mb-10 col-md-12">
                                    Candidato escolhendo <?php echo $etapa === 'rm' ? 'a Região Militar (RM)' : 'a Guarnição (cidade)'; ?> no momento
                                </h4>

                                <h4 class="mb-10 col-md-12">
                                    <?php echo $candidato_escolhendo['nome_completo'] ?? 'Nome do Candidato'; ?>
                                </h4>

                                <h4>
                                    RM Etapa Presencial:</strong> <?php echo $candidato_escolhendo['rm_inscricao'] ?? '-'; ?>ª Região Militar
                                </h4>

                                <b class="mb-20 col-md-12">
                                    Tempo decorrido: <span id="timer">00:00</span>
                                </b>

                                <div class="mb-20 col-md-12">
                                    <img src="fotos/<?php echo $candidato_escolhendo['foto'][0]['nome'] ?? '../imagens/user.jpg'; ?>"
                                        alt="Foto do Candidato"
                                        class="rounded-circle me-4"
                                        style="width: 140px; height: 140px; object-fit: cover; border-radius: 5px;">
                                </div>

                                <div class="col-md-4 mt-20">
                                    <P class="mb-1"><strong>CPF:</strong> <?php echo $candidato_escolhendo['cpf'] ?? 'CPF não informado'; ?></P>
                                    <p class="mb-1"><strong>Email:</strong> <?php echo $candidato_escolhendo['mail'] ?? 'email@exemplo.com'; ?></p>
                                </div>

                                <div class="col-md-4 mt-20">
                                    <p class="mb-1"><strong>Telefone:</strong> <?php echo $candidato_escolhendo['tel_celular'] ?? 'Não informado'; ?></p>
                                    <p class="mb-1"><strong>Telefone de Recados:</strong> <?php echo $candidato_escolhendo['tel_residencial'] ?? 'Não informado'; ?></p>
                                </div>

                                <div class="col-md-4 mt-20">
                                    <p class="mb-1"><strong>Regiões de Interesse:</strong> <?php echo $candidato_escolhendo['rm_destino'] ?? '-'; ?></p>
                                    <p class="mb-0"><strong>Nota Final:</strong> <?php echo $candidato_escolhendo['nota_final'] ?? '-'; ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

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
                                        <th>Guarnição - RM Escolhida</th>
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
                                        echo '<td>' . $linha['nome_cidade_escolhida'] . '/' . $linha['uf_cidade_escolhida'] . ' - ' . $linha['rm_escolheu_servir'] . 'ª RM</td>';
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
<script>
    // Tempo já decorrido vindo do PHP
    let tempoDecorrido = <?php echo $tempo_em_segundos; ?>;

    function formatarTempo(segundos) {
        const minutos = Math.floor(segundos / 60);
        const segundosRestantes = segundos % 60;
        return `${String(minutos).padStart(2, '0')}:${String(segundosRestantes).padStart(2, '0')}`;
    }

    function atualizarTimer() {
        document.getElementById('timer').textContent = formatarTempo(tempoDecorrido);
        tempoDecorrido++;
    }

    // Atualiza imediatamente e depois a cada segundo
    atualizarTimer();
    setInterval(atualizarTimer, 1000);
</script>
</body>

</html>

<?php $conexao = null; ?>