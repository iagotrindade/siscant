<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if (hash('sha256', $_SESSION['chave'] . "pesquisa") != $_POST['criptografia']) {
    erro("Erro 4575467! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $_SESSION['perfil'] != 'admin' && $perfil != 'consulta' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 7325723895! Página não encontrada!");
    exit();
}

$pesquisa = $_POST['pesquisa'];



$lista_usuarios = $conexao->pesquisa_cpf($pesquisa);

$id_usuario_pesquisado = null;
if (count($lista_usuarios) == 1) {
    $id_usuario_pesquisado = $lista_usuarios[0]['id'];
    echo '<meta http-equiv="refresh" content="0; URL=usuario_visualiza.php?id_usuario=' . $id_usuario_pesquisado . '">';
    exit();
}

?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Pesquisa de Usuários <i class="fa fa-user"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Pesquisa de Usuários</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Card da Tabela de Usuários -->
            <div class="card dashboard-card mb-4">
                <div class="card-header dashboard-header mb-20">
                    <span class="card-title mb-0">
                        <i class="fa fa-users me-2"></i>
                        Lista de Usuários
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabela_dinamica">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Foto</th>
                                    <th>CPF</th>
                                    <th>Nome Completo</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th class="text-center">Perfil</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_usuarios as $linha):
                                    $foto = "user.jpg";
                                    $get_foto = $conexao->get_foto_usuario($linha['id']);
                                    if (count($get_foto) > 0)
                                        $foto = $get_foto[0]['nome'];
                                ?>
                                    <tr>
                                        <!-- Imagem -->
                                        <td class="text-center">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip"
                                                title="Imagem do usuário">
                                                <img class="img-circle rounded" src="fotos/<?= $foto ?>" width="32px" height="32px">
                                            </a>
                                        </td>

                                        <!-- CPF -->
                                        <td>
                                            <?= $linha['cpf'] ?>
                                        </td>

                                        <!-- Nome Completo -->
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($linha['nome_completo']) ?></span>
                                        </td>

                                        <!-- Telefone -->
                                        <td>
                                            <span><?= $linha['tel_celular'] ?></span>
                                        </td>

                                        <!-- E-mail -->
                                        <td>
                                            <span><?= htmlspecialchars($linha['mail']) ?></span>
                                        </td>

                                        <!-- Perfil -->
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($linha['perfil'] ?? 'N/A') ?>
                                            </span>
                                        </td>

                                        <td class="text-center" style="white-space: nowrap;">
                                            <a href="usuario_visualiza.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Visualizar usuário">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <a href="usuario_altera_senha.php?id_usuario=<?= $linha['id'] ?>" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Resetar senha">
                                                <i class="fa fa-key"></i>
                                            </a>

                                            <a onclick="funcao_apagar('<?= $linha['id'] ?>', 'usuario')" class="btn btn-sm action-btn" data-bs-toggle="tooltip" title="Excluir usuário">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($lista_usuarios)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fa fa-users fa-2x mb-2 opacity-50"></i><br>
                                                Nenhum usuário encontrado
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $('#tabela_dinamica').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>