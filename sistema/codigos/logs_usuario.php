<a name="auditoria"></a>
<?php
if (!isset($_SESSION))
    session_start();

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 2353567545! Página não encontrada!");
    exit();
}
?>

<!-- 22/06/2025 -> Iago Silva Correção na estrutura do layout -->
<div class="">
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <legend>Auditoria do Usuário</legend>
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CPF</th>
                                <th>Operação</th>
                                <th>Tabela</th>
                                <th>Alteração</th>
                                <th>Data</th>
                                <th>Sistema</th>
                                <th>IP</th>
                                <th>COD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $lista_logs = $conexao->get_logs_usuario($id_usuario);
                            foreach ($lista_logs as $linha) {
                                echo '
                                <tr>
                                <td>' . ($linha['id']) . '</td>
                                <td>' . $linha['cpf'] . '</td>
                                <td>' . strtoupper($linha['operacao']) . '</td>
                                <td>tb_' . $linha['tabela'] . '</td>
                                <td>' . $linha['alteracao'] . '</td>
                                <td>' . trata_data_hora($linha['data']) . '</td>
                                <td>' . $linha['sistema'] . '</td>
                                <td>' . $linha['ip'] . '</td>
                                <td>' . $linha['codigo'] . '</td>
                                </tr>';
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>