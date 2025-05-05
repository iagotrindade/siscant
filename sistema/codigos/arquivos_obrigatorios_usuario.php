<?php 
/*
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro("Erro 235345345!");
        exit();
    }
 * 
 */
?>

<div class="card">
    <legend>Arquivos Obrigatórios Adicionados</legend>
    <div class="card-body">
        <table class="table table-hover table-bordered" id="tabela_dinamica2">
            <thead>
              <tr>
                <th>Nome do arquivo</th>
              </tr>
            </thead>
            <tbody>
                <?php
                    $lista_docs_obrigatorios = $conexao->get_docs_obrigatorios_inseridos_candidato($id_usuario);  

                    foreach ($lista_docs_obrigatorios as $linha) 
                    {
                            echo '
                            <tr>
                                <td><a href="baixaPDF.php?codigo=arq_obr_usu&nome_arquivo='.$linha['nome'].'" target="_blank">'.$linha['label'].'</a></td>
                            </tr>';
                    }
                ?>   
          </tbody>
        </table>
    </div>
</div>