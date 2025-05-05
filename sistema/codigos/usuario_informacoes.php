<?php 
    if (!isset( $_SESSION )) 
        session_start();
    
    if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
    {
        erro ("Erro 242342!");
        exit();
    }
    $lista_especialidades = $conexao->get_especialidades_usuario_avaliador($id_usuario);
?>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                
                <legend>Informações do Usuários <?php echo $nome_selecao ?>  
                    <font color="red" ><b><?php if($apagado == 1) echo " - Usuário Apagado - "; ?></b></font>
                </legend>
                <div class="table-responsive">
                    <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nome Completo: </b><?php echo $nome_completo ?></td>
                            <td><b>CPF: </b><?php echo mascara($cpf,'###.###.###-##') ?></td>
                            <td><b>Perfil: </b><?php echo $perfil ?></td>
                        </tr>
                        <tr>
                            <td><b>Posto Graduação: </b><?php echo $posto_grad ?></td>
                            <td><b>Telefone Celular: </b><?php echo $tel_celular ?></td>
                            <td><b>E-Mail: </b><?php echo $mail ?></td>
                        </tr>
                        <tr>
                            <td><b>Nome de Guerra: </b><?php echo $nome_guerra ?></td>
                            <td colspan="2"><b>OM: </b><?php echo $om_nome ?></td>
                        </tr>
                        <tr <?php if(count($lista_especialidades) == 0 || $perfil != "avaliador") echo "hidden" ?>>
                            <td colspan="3"> <b>Especialidades com autorização para avaliar: |</b>
                                <?php  
                                foreach ($lista_especialidades as $linha_especialidade) 
                                {
                                    echo $linha_especialidade['nome'] . " | ";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><center><img src="fotos/<?php echo $foto_nome ?>" width="200px"></center></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
            </div>
            
        <?php 
            if($_SESSION['perfil'] == 'admin' || $_SESSION['perfil'] == 'consulta')
            {
                include_once 'codigos/logs_usuario.php';
            }
            if($_SESSION['perfil'] == 'admin' )
            {
                include_once 'codigos/acesso_candidato_logs.php';
            }
        ?>
            
            
        </div>
    </div>