<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 2353! Página não encontrada!");
        exit();
    }


   //  print_r($_SESSION['eipot']);
   //  print_r($_SESSION['selecao']);

    // SEGMENTO
    
    $quantidade_masculino = 0;
    $quantidade_feminino = 0;
    $quantidade_segmento = $conexao->get_total_segmento();
    
    if(count($quantidade_segmento) > 0)
    {
        if(isset($quantidade_segmento[0]['quantidade']))
            $quantidade_feminino = $quantidade_segmento[0]['quantidade'];
        
        if(isset($quantidade_segmento[1]['quantidade']))
            $quantidade_masculino = $quantidade_segmento[1]['quantidade'];
    }
    
    $quantidade_masculino_concorrendo = 0;
    $quantidade_feminino_concorrendo = 0;
    $quantidade_segmento_concorrendo = $conexao->get_total_segmento_concorrendo();
    if(count($quantidade_segmento_concorrendo) > 0)
    {
        if(isset($quantidade_segmento_concorrendo[0]['quantidade']))
            $quantidade_feminino_concorrendo = $quantidade_segmento_concorrendo[0]['quantidade'];
        
        if(isset($quantidade_segmento_concorrendo[1]['quantidade']))
            $quantidade_masculino_concorrendo = $quantidade_segmento_concorrendo[1]['quantidade'];
    }
    
    
    // QUANTIDADE CANDIDATOS
    $quantidade_candidatos = null;
    $quantidade_cand = $conexao->get_quantidade_candidatos();
    if(count($quantidade_cand) > 0)
        $quantidade_candidatos = $quantidade_cand[0]['quantidade'];
    
    $quantidade_candidatos_concorrendo = null;
    $quantidade_cand_concorrendo = $conexao->get_quantidade_candidatos_concorrendo();
    if(count($quantidade_cand_concorrendo) > 0)
        $quantidade_candidatos_concorrendo = $quantidade_cand_concorrendo[0]['quantidade'];
    
    $quantidade_candidatos_nao_concorrendo = 0;
    $quantidade_candidatos_nao_concorrendo = $quantidade_candidatos - $quantidade_candidatos_concorrendo;
    
    // QUANTIDADE OTT/STT/MFDV

    $quantidade_ott_stt_mfdv = null;
    $quantidade_ott_stt_mfdv = $conexao->get_quantidade_ott_stt_mfdv();
    
    $quantidade_ott_stt_mfdv_concorrendo = null;
    $quantidade_ott_stt_mfdv_concorrendo = $conexao->get_quantidade_ott_stt_mfdv_concorrendo();
    
    
    // QUANTIDADE OTT

    $quantidade_ott = null;
    $quant_ott = $conexao->get_quantidade_ott();
    if(count($quant_ott) > 0)
        $quantidade_ott = $quant_ott[0]['quantidade'];
    
    $quantidade_ott_concorrendo = null;
    $quant_ott_concorrendo = $conexao->get_quantidade_ott_concorrendo();
    if(count($quant_ott_concorrendo) > 0)
        $quantidade_ott_concorrendo = $quant_ott_concorrendo[0]['quantidade'];
    
    $quantidade_ott_nao_concorrendo = 0;
    $quantidade_ott_nao_concorrendo = $quantidade_ott - $quantidade_ott_concorrendo;
    
    // QUANTIDADE STT
    
    $quantidade_stt = null;
    $quant_stt = $conexao->get_quantidade_stt();
    if(count($quant_stt) > 0)
        $quantidade_stt = $quant_stt[0]['quantidade'];
    
    $quantidade_stt_concorrendo = null;
    $quant_stt_concorrendo = $conexao->get_quantidade_stt_concorrendo();
    if(count($quant_stt_concorrendo) > 0)
        $quantidade_stt_concorrendo = $quant_stt_concorrendo[0]['quantidade'];
    
    $quantidade_stt_nao_concorrendo = 0;
    $quantidade_stt_nao_concorrendo = $quantidade_stt - $quantidade_stt_concorrendo;
    
    // QUANTIDADE INSCRIÇÃO
    
    $quantidade_total_inscrição = $quantidade_stt+$quantidade_ott;
    $quantidade_inscritos_concorrendo = $quantidade_stt_concorrendo + $quantidade_ott_concorrendo;
    $quantidade_inscritos_nao_concorrendo = $quantidade_total_inscrição - $quantidade_inscritos_concorrendo;
    
    
    ///////////// GIGAS
    
    $quantidade_docs_obrigatorios = null;
    $gigas_docs_obrigatorios = null;
    $gigas_docs = null;
    $gigas_docs = $conexao->get_gigas_docs();
    if(count($gigas_docs) > 0)
    {
        $quantidade_docs_obrigatorios = $gigas_docs[0]['quantidade'];
        $gigas_docs_obrigatorios = $gigas_docs[0]['gigas'];
    }
    
    $quantidade_curriculo = null;
    $gigas_curriculo = null;
    $gigas_curriculo = null;
    $gigas_curriculo = $conexao->get_gigas_curriculo();
    if(count($gigas_curriculo) > 0)
    {
        $quantidade_curriculo = $gigas_curriculo[0]['quantidade'];
        $gigas_curriculo = $gigas_curriculo[0]['gigas'];
    }
    
    $quantidade_foto = null;
    $gigas_foto = null;
    $gigas_foto = $conexao->get_gigas_foto();
    if(count($gigas_foto) > 0)
    {
        $quantidade_foto = $gigas_foto[0]['quantidade'];
        $gigas_foto = $gigas_foto[0]['gigas'];
    }
    
    $quantidade_pagamento = null;
    $gigas_pagamento = null;
    $gigas_pagamento = $conexao->get_gigas_pagamento();
    if(count($gigas_pagamento) > 0)
    {
        $quantidade_pagamento = $gigas_pagamento[0]['quantidade'];
        $gigas_pagamento = $gigas_pagamento[0]['gigas'];
    }
    
    $soma_total_gigas = null;
    $soma_total_gigas = $gigas_docs_obrigatorios + $gigas_curriculo + $gigas_foto + $gigas_pagamento;
    
    $soma_total_quantidade = $quantidade_pagamento+$quantidade_foto+$quantidade_curriculo+$quantidade_docs_obrigatorios;
    

?>    
<div class="card">
    <div class="row">
        <div class="col-lg-12">
            <legend>Informações da seleção</legend>
        </div>
        
        <div class="col-lg-3">
            <div class="widget-small primary"><i class="icon fa fa-users fa-3x"></i>
              <div class="info">
                
                <h4>Cand <u><?php echo $quantidade_candidatos_concorrendo ?></u></h4>
                <p><b><?php echo " Total: " . $quantidade_candidatos ."  - <font color='red'> $quantidade_candidatos_nao_concorrendo </font>"?></b></p>

              </div>
            </div>
        </div>
        
        
        <!-- OTT STT -->
        
        <div class="col-lg-3" <?php if($_SESSION['selecao_codigo'] != 'ott_stt') echo "hidden" ?>>
            <div class="widget-small info"><i class="icon fa fa-files-o fa-3x"></i>
              <div class="info">
                <h4>Esp:  <u><?php echo $quantidade_inscritos_concorrendo ?></u></h4>
                <p><b><?php echo "Total: " . $quantidade_total_inscrição ." -  <font color='red'>  $quantidade_inscritos_nao_concorrendo </font>"?></b></p> 
              </div>
            </div>
        </div>
        <div class="col-lg-3" <?php if($_SESSION['selecao_codigo'] != 'ott_stt') echo "hidden" ?>>
            
            <div class="widget-small info"><i class="icon fa fa-wrench fa-3x"></i>
              <div class="info">
                    <h4>OTT: <u><?php echo $quantidade_ott_concorrendo ?></u></h4>
                    <p><b><?php echo "Total: " . $quantidade_ott ." - <font color='red'> $quantidade_ott_nao_concorrendo </font>"?></b></p>
              </div>
            </div>
        </div>
        <div class="col-lg-3" <?php if($_SESSION['selecao_codigo'] != 'ott_stt') echo "hidden" ?>>
            <div class="widget-small info"><i class="icon fa fa-wrench fa-3x"></i>
              <div class="info">
                  <h4>STT: <u><?php echo $quantidade_stt_concorrendo ?></u></h4>
                    <p><b><?php echo "Total: " . $quantidade_stt ." - <font color='red'> $quantidade_stt_nao_concorrendo </font>"?></b></p>
              </div>
            </div>
        </div>
        
        
    </div>
    <br>
    <br>
    
    <div class="row">
        
        <div class="col-lg-12">
            <legend>Total de arquivos adicionados </legend>
        </div>
        
        <div class="col-lg-3">
            <div class="widget-small alert-laranja"><font color="#424242"><i class="icon fa fa-image fa-3x"></i>
              <div class="info">
                    <h4>TOTAL: <?php echo $soma_total_quantidade ?></h4>
                    <p><b><?php echo round($soma_total_gigas,2) . " Gbs";  ?> </b></p>
                    </font>
              </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="widget-small alert-laranja"><font color="#424242"><i class="icon fa fa-image fa-3x"></i>
              <div class="info">
                    <h4>Fotos: <?php echo $quantidade_foto;  ?></h4>
                    <p><b><?php echo $gigas_foto;  ?> </b> GBs</p>
                    </font>
              </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="widget-small alert-laranja"><font color="#424242"><i class="icon fa fa-usd fa-3x"></i>
              <div class="info">
                    <h4>Pagamentos: <?php echo $quantidade_pagamento;  ?></h4>
                    <p><b><?php echo $gigas_pagamento;  ?> GBs</b></p>
                    </font>
              </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="widget-small alert-laranja"><font color="#424242"><i class="icon fa fa-files-o fa-3x"></i>
              <div class="info">
                    <h4>Doc obg: <?php echo $quantidade_docs_obrigatorios;  ?></h4>
                    <p><b><?php echo $gigas_docs_obrigatorios;  ?> GBs</b></p>
                    </font>
              </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="widget-small alert-laranja"><font color="#424242"><i class="icon fa fa-file-text-o fa-3x"></i>
              <div class="info">
                    <h4>Currículos: <?php echo $quantidade_curriculo;  ?></h4>
                    <p><b><?php echo $gigas_curriculo;  ?> GBs</b></p>
                    </font>
              </div>
            </div>
        </div>
    </div>
</div>

<?php 

    ////////
    // TOTAL
    ////////

    $label_total_inscritos = "";
    for($i = 0; $i < count($quantidade_ott_stt_mfdv); $i++)
    {
        if($i == count($quantidade_ott_stt_mfdv) -1)
            $label_total_inscritos = $label_total_inscritos . '"'.$quantidade_ott_stt_mfdv[$i]['ott_stt'].'"';
        else
            $label_total_inscritos = $label_total_inscritos . '"'.$quantidade_ott_stt_mfdv[$i]['ott_stt'].'" , ';
    }
    $quantidade_total_inscritos = "";
    for($i = 0; $i < count($quantidade_ott_stt_mfdv); $i++)
    {
        if($i == count($quantidade_ott_stt_mfdv) -1)
            $quantidade_total_inscritos = $quantidade_total_inscritos . '"'.$quantidade_ott_stt_mfdv[$i]['quantidade'].'"';
        else
            $quantidade_total_inscritos = $quantidade_total_inscritos . '"'.$quantidade_ott_stt_mfdv[$i]['quantidade'].'" , ';
    }
    
    ////////
    // CONCORRENDO
    ////////
    
    $label_total_inscritos_concorrendo = "";
    for($i = 0; $i < count($quantidade_ott_stt_mfdv_concorrendo); $i++)
    {
        if($i == count($quantidade_ott_stt_mfdv_concorrendo) -1)
            $label_total_inscritos_concorrendo = $label_total_inscritos_concorrendo . '"'.$quantidade_ott_stt_mfdv_concorrendo[$i]['ott_stt'].'"';
        else
            $label_total_inscritos_concorrendo = $label_total_inscritos_concorrendo . '"'.$quantidade_ott_stt_mfdv_concorrendo[$i]['ott_stt'].'" , ';
    }
    
    $quantidade_total_inscritos_concorrendo = "";
    for($i = 0; $i < count($quantidade_ott_stt_mfdv_concorrendo); $i++)
    {
        if($i == count($quantidade_ott_stt_mfdv_concorrendo) -1)
            $quantidade_total_inscritos_concorrendo = $quantidade_total_inscritos_concorrendo . '"'.$quantidade_ott_stt_mfdv_concorrendo[$i]['quantidade'].'"';
        else
            $quantidade_total_inscritos_concorrendo = $quantidade_total_inscritos_concorrendo . '"'.$quantidade_ott_stt_mfdv_concorrendo[$i]['quantidade'].'" , ';
    }
    
?>

<div class="card">
    <div class="row">
        <div class="col-lg-6">
            <h3 class="card-title">TOTAL SEGMENTO</h3>
            <div id="canvas-holder" style="width:50%">
                <canvas id="total_segmento" ></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <h3 class="card-title">CONCORRENDO SEGMENTO</h3>
            <div id="canvas-holder" style="width:50%">
                <canvas id="total_segmento_concorrendo" ></canvas>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="row">
        <div class="col-lg-6">
            <h3 class="card-title">TOTAL INSCRITOS </h3>
            <div id="canvas-holder" style="width:50%">
                <canvas id="total_ott_stt_mfdv" ></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <h3 class="card-title">CONCORRENDO </h3>
            <div id="canvas-holder" style="width:50%">
                <canvas id="concorrendo_ott_stt_mfdv" ></canvas>
            </div>
        </div>
    </div>
</div>


<script>
    new Chart(document.getElementById("total_segmento"), {
    type: 'pie',
    data: {
      labels: ["Masculino", "Feminino"],
      datasets: [{
        label: "Tempo com o processo",
        backgroundColor: ["#007CB7", "#FF6F61","#3cba9f","#fff232"],
        data: [<?php
                    echo $quantidade_feminino. "," . $quantidade_masculino; 
            ?>]
      }]
    },
    options: {
        
         legend: {
            labels: {
                        //fontStyle: 'bold',
                        fontSize: 14
                    }
                },
        
      title: {
        display: true,
        showInlineValues : true,
            centeredInllineValues : true,
        text: ''
      }
    }
});
</script>

<script>
    new Chart(document.getElementById("total_segmento_concorrendo"), {
    type: 'pie',
    data: {
      labels: ["Masculino", "Feminino"],
      datasets: [{
        label: "Tempo com o processo",
        backgroundColor: ["#007CB7", "#FF6F61","#3cba9f","#fff232"],
        data: [<?php
                    echo $quantidade_feminino_concorrendo. "," . $quantidade_masculino_concorrendo; 
            ?>]
      }]
    },
    options: {
        
         legend: {
            labels: {
                        //fontStyle: 'bold',
                        fontSize: 14
                    }
                },
        
      title: {
        display: true,
        showInlineValues : true,
            centeredInllineValues : true,
        text: ''
      }
    }
});
</script>

<script>
    new Chart(document.getElementById("concorrendo_ott_stt_mfdv"), {
    type: 'pie',
    data: {
      labels: [<?php echo strtoupper($label_total_inscritos_concorrendo); ?>],
      datasets: [{
        label: "Tempo com o processo",
        backgroundColor: ["#3cba9f", "#fff232","#007CB7","#FF6F61"],
        data: [<?php echo $quantidade_total_inscritos_concorrendo; ?>]
      }]
    },
    options: {
        
         legend: {
            labels: {
                        //fontStyle: 'bold',
                        fontSize: 14
                    }
                },
        
      title: {
        display: true,
        showInlineValues : true,
            centeredInllineValues : true,
        text: ''
      }
    }
});
</script>
<script>
    new Chart(document.getElementById("total_ott_stt_mfdv"), {
    type: 'pie',
    data: {
      labels: [<?php echo strtoupper($label_total_inscritos); ?>],
      datasets: [{
        label: "Tempo com o processo",
        backgroundColor: ["#3cba9f", "#fff232","#007CB7","#FF6F61"],
        data: [<?php echo $quantidade_total_inscritos; ?>]
      }]
    },
    options: {
        
         legend: {
            labels: {
                        //fontStyle: 'bold',
                        fontSize: 14
                    }
                },
        
      title: {
        display: true,
        showInlineValues : true,
            centeredInllineValues : true,
        text: ''
      }
    }
});
</script>


<script>
Chart.plugins.register({
  afterDatasetsDraw: function(chartInstance, easing) {
    // To only draw at the end of animation, check for easing === 1
    var ctx = chartInstance.chart.ctx;
    chartInstance.data.datasets.forEach(function(dataset, i) {
      var meta = chartInstance.getDatasetMeta(i);
      if (!meta.hidden) {
        meta.data.forEach(function(element, index) {
          // Draw the text in black, with the specified font
          ctx.fillStyle = 'black';
          var fontSize = 15;
          var fontStyle = 'normal';
          //var fontFamily = 'Helvetica Neue';
          //ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
          ctx.font = Chart.helpers.fontString(fontSize, fontStyle);
          // Just naively convert to string for now
          var dataString = dataset.data[index].toString();
          // Make sure alignment settings are correct
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          var padding = 5;
          var position = element.tooltipPosition();
          ctx.fillText(dataString + '', position.x, position.y - (fontSize / 2) - padding);
        });
      }
    });
  }
});
</script>


