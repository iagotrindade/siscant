<?php

function mostrarRecursosMenu($primeira_regiao, 
                            $segunda_regiao, 
                            $terceira_regiao, 
                            $quarta_regiao, 
                            $quinta_regiao, 
                            $sexta_regiao, 
                            $setima_regiao, 
                            $oitava_regiao, 
                            $nona_regiao, 
                            $decima_regiao, 
                            $onze_regiao, 
                            $doze_regiao) {

        if ($primeira_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 1) { // 1 corresponde à 1ª Região Militar
               echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $primeira_regiao = null;
        }
        
        if ($segunda_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 2) { // 2 corresponde à 2ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $segunda_regiao = null;
        }
        
        if ($terceira_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 3) { // 3 corresponde à 3ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $terceira_regiao = null;
        }
        
        if ($quarta_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 4) { // 4 corresponde à 4ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $quarta_regiao = null;
        }
        
        if ($quinta_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 5) { // 5 corresponde à 5ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $quinta_regiao = null;
        }
        
        if ($sexta_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 6) { // 6 corresponde à 6ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $sexta_regiao = null;
        }
        
        if ($setima_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 7) { // 7 corresponde à 7ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $setima_regiao = null;
        }
        
        if ($oitava_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 8) { // 8 corresponde à 8ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $oitava_regiao = null;
        } 
        
        if ($nona_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 9) { // 9 corresponde à 9ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $nona_regiao = null;
        }
        
        if ($decima_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 10) { // 10 corresponde à 10ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $decima_regiao = null;
        }
        
        if ($onze_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 11) { // 11 corresponde à 11ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $onze_regiao = null;
        }
        
        if ($doze_regiao === true) {
            if ($_SESSION['selecao_regiao'] == 12) { // 12 corresponde à 12ª Região Militar
                echo '<li><a href="candidato_recurso.php"><i class="fa fa-file-o"></i><span>Recursos</span></a></li>';
            } else $doze_regiao = null;
        }
        
     
        
    }
    
