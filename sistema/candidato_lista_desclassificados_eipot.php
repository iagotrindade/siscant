<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
  erro("Erro 23543! Página não encontrada!");
  exit();
}

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$lista_candidatos = $conexao->get_candidatos_desclassificados_rm($rm_usuario, $id_selecao);
//$lista_candidatos = $conexao->get_candidatos_desclassificados_classificados();  

$lista_candidatos_reserva = $conexao->get_candidatos_eipot_reserva();

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

foreach ($lista_candidatos_reserva as $inscrito) {
  if ($inscrito['etapa'] < 6 || !empty($inscrito['rm_escolheu_servir'])) {
    continue;
  }
  $inscrito['nota_final'] = get_nota_final_eipot($inscrito['id']);
  $inscrito['especialidade_info'] = $conexao->get_especialidade_candidato_eipot($inscrito['id_candidato_especialidade']);
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
      <h1>Candidatos desclassificados <i class="fa fa-user-times"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="index.php">Página Inicial</a></li>
        <li>Candidatos Desclassificados</li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <legend>Candidatos Etapas Presenciais na RM - Desclassificados</legend>
        <div class="card-body">
          <table class="table table-hover table-bordered tabela-dinamica" id="tabela-dinamica">
            <thead>
              <tr>
                <th>ID</th>
                <th>CPF</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Mail</th>
                <th>Etapa</th>
                <th>Del</th>
              </tr>
            </thead>
            <tbody>

              <?php
              foreach ($lista_candidatos as $linha) {
                /*
                            $foto = "user.jpg";
                            
                            $get_foto = $conexao->get_foto_usuario($linha['id']);  
                            if(count($get_foto) > 0)
                                $foto = $get_foto[0]['nome'];
                            */

                //if($linha['concorrendo'] == '1' && $linha['etapa'] >= $_SESSION['etapa_selecao']) continue; -- Mudar para get_candidatos_desclassificados_classificados

                echo '
                                <tr>
                                <td>' . $linha['id'] . '</td>
                                <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id'] . '">' . $linha['cpf'] . '</a></td>
                                <td>' . $linha['nome_completo'] . '</td>
                                <td>' . $linha['tel_celular'] . '</td>
                                <td>' . $linha['mail'] . '</td>
                                <td width="40px">et_' . $linha['etapa'] . '</td>
                                <td width="30px"><a onclick="funcao_apagar(\'' . $linha['id'] . '\', \'candidato\')"><img title="Apagar" src="imagens/apagar.png" width="30px"></a></td>
                                </tr>';
              }
              //<td width="40px"><a href="usuario_visualiza.php?id_usuario='.$linha['id'].'"><img title="Visualizar" class="img-circle" src="fotos/'.$foto.'" width="40px"></a></td>
              ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($inscritos_por_arma as $arma => $candidatos) { ?>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-body">

            <!-- Legenda da tabela -->
            <legend class="mt-2">Candidatos de todo o Brasil na Etapa VI - <?php echo htmlspecialchars($arma); ?></legend>

            <div class="card-body">
              <table class="table table-hover table-bordered tabela-dinamica" id="tabela-dinamica">
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


</div>
</div>
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
</body>

</html>
<?php $conexao = null; ?>