<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 234234! Acesso restrito!");
    exit();
}

$id_usuario  = $_GET['id_usuario'];

if ($id_usuario == null || $id_usuario == '') {
    erro("Usuário não encontrado, erro: 7854654 $id_usuario");
    exit();
}

$foto_nome1 = $conexao->get_foto_usuario($id_usuario);
$foto_nome = "user.jpg";
if ($foto_nome1 != null)
    $foto_nome = $foto_nome1[0]['nome'];

$usuario_visualiza = $conexao->get_usuario_id($id_usuario);
include_once './codigos/variaveis_usuario_visualiza.php';


if (isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == 1) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>SUCESSO!</b><br> ",
                message: " A nova senha foi enviada por E-Mail!"
        },{
                type: "info"
        });
    };
    </script>';
}
if (isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == 0) {
    echo '<script type="text/javascript">
    window.onload = function() 
    {
        $.notify({
                title: "<center><b>ERRO!</b><br> ",
                message: " A nova senha NÃO foi enviada para o E-Mail do candidato!"
        },{
                type: "info"
        });
    };
    </script>';
}
?>
<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Reseta senha <i class="fa fa-lock"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Resetar senha</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Card de Reset de Senha -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-key me-2"></i>
                        Redefinir Senha do Usuário
                    </span>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-envelope fa-2x text-info mr-10"></i>
                                    <div>
                                        <h5 class="text-info mb-0">A senha será enviada por e-mail</h5>
                                        <p class="mb-0">A nova senha será enviada para o e-mail cadastrado do usuário</p>
                                    </div>
                                </div>
                            </div>

                            <form action="../banco_dados/usuario_resetar_senha.php" method="post">
                                <!-- Campos Ocultos -->
                                <input type="hidden" id="id_usuario" name="id_usuario" value="<?= $id_usuario ?>">
                                <input type="hidden" id="cpf" name="c_p_f" value="<?= $cpf ?>">
                                <input type="hidden" name="candidato" value="<?= $candidato ?>">

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fa fa-refresh me-2"></i>
                                        REDEFINIR SENHA
                                    </button>
                                </div>

                                <div class="mt-10 text-center">
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Uma senha temporária será gerada e enviada por e-mail
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    if ($candidato == 1 || $perfil == 'candidato')
        include_once './codigos/candidato_informacoes.php';
    else
        include_once './codigos/usuario_informacoes.php';

    ?>

</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
</body>

</html>