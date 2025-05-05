<?php
    include_once 'menu.php';
    include_once 'codigos/funcao_apagar.php';
    
    if($_SESSION['selecao_codigo'] != 'ott_stt' && $_SESSION['selecao_codigo'] != 'cet')
    {
        erro("Erro 377456723: Página não encontrada");
        exit();
    }
    
    
    $get_selecao = $conexao->get_selecao_id();
    $duvidas_frequentes = null;
    if($get_selecao[0]['duvidas_frequentes'] != null)
        $duvidas_frequentes = $get_selecao[0]['duvidas_frequentes'];
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Dúvidas Frequentes <i class="fa fa-info-circle"></i></h1>
    </div>
    <div>
      <ul class="breadcrumb">
        <li><i class="fa fa-home fa-lg"></i></li>
        <li><a href="#">Página Inicial</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <div class="card" >
            <legend>Dúvidas Frequentes.</legend>
            <div class="row" >
                <?php
                    echo $duvidas_frequentes;
                ?>
            </div>
        </div>

        
    </div>
  </div>
</div>
</div>
</body>
</html>
<?php $conexao = null; ?>