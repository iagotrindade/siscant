<html>
    <head>
        <title>title</title>
    </head>
    <body>
        
         
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<?php
    $vetor = [];
    $vetor[0] = "teste";
    $vetor[1] = "teste1";
    $vetor[2] = "Moreira";
    
?>

<script>
    $(function() {
        var esportes = [
        <?php
            foreach ($vetor as $linha)
            {
                echo  '"' .$linha . '",';
            }
        ?>
      "Basquete"
    ];
  
  $("#esporte" ).autocomplete({
    source: esportes
  });
});
</script>
    
    
  <input type="text" id="esporte" placeholder="Informe um esporte"/>
  
  <br>
  <br>
  <br>
  <br>

    </body>
</html>
