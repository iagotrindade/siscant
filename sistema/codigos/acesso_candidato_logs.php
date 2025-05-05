<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta')
    {
        erro("Erro 2353567545! Página não encontrada!");
        exit();
    }
?>

<div class="card" <?php if($usuario_visualiza[0]['perfil'] == 'admin') echo ' hidden ';?>>
    <legend>Acessos a candidatos</legend>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="tabela_dinamica3">
                <thead>
                    <tr>
                      <th>CPF</th>
                      <th>Nome</th>
                      <th>Acessos</th>
                      <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        $lista_logs = $conexao->get_acessos_candidato($id_usuario); 
                        foreach ($lista_logs as $linha) 
                        {
                            echo'
                                <tr>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id_visualizado'].'">'.($linha['cpf_candidato']).'</a></td>
                                <td>'.$linha['nome_candidato'].'</td>
                                <td>'.$linha['quantidade_acessos'].'</td>
                                <td><a href="usuario_visualiza.php?id_usuario='.$linha['id_visualizado'].'"><img class="img-circle" src="fotos/'.$linha['foto'].'" width="40px"></a></td>                                
                                </tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>