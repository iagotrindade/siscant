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

    <?php foreach ($inscritos_por_arma as $arma => $candidatos) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <legend>Candidatos <?php echo htmlspecialchars($arma); ?> - AMPLA CONCORRÊNCIA</legend>

                    <div class="card-body">
                        <table class="table table-hover table-bordered tabela-dinamica">
                            <thead>
                                <tr>
                                    <th>Nota Final</th>
                                    <th>CPF</th>
                                    <th>Nome</th>
                                    <th>Autodeclaração</th>
                                    <th>RM Inscrição</th>
                                    <th>RM de Interesse</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Guarnição Escolhida</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidatos as $linha) {
                                    echo '<tr>';
                                    echo '<td>' . $linha['nota_final'] . '</td>';
                                    echo '<td>' . $linha['cpf'] . '</td>';
                                    echo '<td>' . $linha['nome_completo'] . '</td>';
                                    echo '<td>' . ucwords($linha['autodeclaracao']) . '</td>';
                                    echo '<td>' . $linha['rm_inscricao'] . 'ª Região Militar</td>';
                                    echo '<td>' . $linha['rm_destino'] . '</td>';
                                    echo '<td>' . $linha['tel_residencial'] . '</td>';
                                    echo '<td>' . $linha['mail'] . '</td>';
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
    <?php } ?>
</div>

<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.tabela-dinamica').DataTable({
            "order": [
                [0, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
            }
        });
    });
</script>
</body>

</html>

<?php $conexao = null; ?>