<?php
// 17/06/2025 -> Iago Silva Criação da área de Heteroidentificação
$pareceres = $conexao->get_pareceres_heteroidentificacao($id_usuario);

$pareceresFase1 = array_filter($pareceres, function ($parecer) {
    return $parecer['fase'] == 1;
});

$pareceresFase2 = array_filter($pareceres, function ($parecer) {
    return $parecer['fase'] == 2;
});

$totalFase1 = count($pareceresFase1);
$totalFase2 = count($pareceresFase2);

// Se já houver 5 pareceres da fase 1, bloqueia
$bloquearInclusao = $totalFase1 >= 5;
$bloquearInclusaoRevisora = $totalFase2 >= 3;

// Verificar se o usuário logado possui alguma análise feita no candidato se sim, bloqueia a inserção
if (in_array($_SESSION['id_usuario'], array_column($pareceresFase1, 'id_avaliador'))) {
    $bloquearInclusao = true;
}

if (in_array($_SESSION['id_usuario'], array_column($pareceresFase2, 'id_avaliador'))) {
    $bloquearInclusaoRevisora = true;
}

?>
<a name='heteroidentificacao'></a>
<div <?php if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr' && $_SESSION['perfil'] != 'consulta') echo 'hidden' ?>>
    <div class="card p-4">
        <h4 class="">
            <a data-toggle="collapse" href="#hetero">Mostrar/Esconder Pareceres Heteroidentificação</a>
        </h4>
        <!-- Análises -->
        <?php $contadorHetero = 0; ?>
        <div class="row collapse" id="hetero">
            <?php foreach ($pareceresFase1 as $parecer) : ?>
                <?php $contadorHetero++; ?>
                <div class="col-md-12 mb-40">
                    <form <?php if ($parecer['id_avaliador'] != $_SESSION['id_usuario']) echo 'hidden'; ?> action="../banco_dados/candidato_edita_heteroidentificacao.php" method="post" class="row" aria-labelledby="titulo-heteroidentificacao">
                        <input hidden type="text" value="<?php echo $parecer['id']; ?>" name="id_parecer">
                        <input hidden type="text" value="<?php echo $parecer['id_avaliador']; ?>" name="id_avaliador">
                        <input hidden type="text" value="<?php echo $id_usuario; ?>" name="id_candidato">
                        <input value="<?php echo $cpf; ?>" maxlength="50" name="cpf_candidato" hidden>
                        <input value="1" name="fase" hidden>
                        <fieldset class="col-md-12">
                            <h4>Parecer número: <?php echo ($contadorHetero); ?></h4>
                            <p style="font-size: 16px;" id="titulo-heteroidentificacao" class="mb-4">
                                Análise realizada em <?php echo (trata_data_hora($parecer['data_avaliacao'])); ?> pelo <?php echo ($parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador']); ?>
                            </p>

                            <!-- Justificativa -->
                            <div class="mb-20">
                                <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                                <textarea
                                    name="justificativa"
                                    id="justificativa"
                                    class="form-control"
                                    rows="4"
                                    required><?php if ($parecer['justificativa'] != null) echo $parecer['justificativa']; ?></textarea>
                            </div>

                            <!-- Resultado da autodeclaração -->
                            <div class="mb-20">
                                <label for="parecer" class="form-label">Autodeclaração</label>
                                <select name="parecer" id="parecer" class="form-control" required>
                                    <option value="">Selecione uma opção</option>
                                    <option value="confirmada" <?php if ($parecer['parecer'] == 'confirmada') echo "selected"; ?>>Confirmada</option>
                                    <option value="nao_confirmada" <?php if ($parecer['parecer'] == 'nao_confirmada') echo "selected"; ?>>Não confirmada</option>
                                    <option value="nao_compareceu" <?php if ($parecer['parecer'] == 'nao_compareceu') echo "selected"; ?>>Não compareceu</option>
                                </select>
                            </div>

                            <!-- Botão -->
                            <div>
                                <button type="submit" class="btn btn-primary col-md-12">
                                    Salvar
                                </button>
                            </div>
                        </fieldset>
                    </form>

                    <div class="" <?php if ($parecer['id_avaliador'] == $_SESSION['id_usuario']) echo 'hidden'; ?>>
                        <h4>Parecer número: <?php echo ($contadorHetero); ?></h4>
                        <p style="font-size: 16px;" id="titulo-heteroidentificacao" class="mb-4">
                            Análise realizada em <?php echo (trata_data_hora($parecer['data_avaliacao'])); ?> pelo <?php echo ($parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador']); ?>
                        </p>

                        <!-- Justificativa -->
                        <div class="mb-20">
                            <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                            <textarea
                                name="justificativa"
                                id="justificativa"
                                class="form-control"
                                rows="4"
                                disabled><?php if ($parecer['justificativa'] != null) echo $parecer['justificativa']; ?></textarea>
                        </div>

                        <!-- Resultado da autodeclaração -->
                        <div class="mb-20">
                            <label for="resultado" class="form-label">Autodeclaração</label>
                            <input
                                name="resultado"
                                id="resultado"
                                class="form-control"
                                value="<?php if ($parecer['parecer'] == 'confirmada') {
                                            echo ('Confirmada');
                                        } elseif ($parecer['parecer'] == 'nao_confirmada') {
                                            echo ('Não Confirmada');
                                        } elseif ($parecer['parecer'] == 'nao_compareceu') {
                                            echo ('Não compareceu');
                                        } ?>"
                                disabled>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-40 mt-20" style="height: 1px; border-bottom: 1px solid #ccc;"></div>
            <?php endforeach; ?>
        </div>

        <!-- Resultado Final -->
        <div class="row" <?php if ($totalFase1 < 5) echo ('hidden'); ?>>
            <div class="col-md-12">
                <legend>
                    <h4>
                        Resultado Final: <?php echo (get_parecer_final_heteroidentificacao($id_usuario, $pareceres, 1)); ?>
                    </h4>
                </legend>
            </div>
        </div>

        <!-- Inserir avaliação -->
        <div class="card p-4" <?php if ($bloquearInclusao || $_SESSION['perfil'] != 'admin' && $_SESSION['perfil' != 'chc']) echo ('hidden'); ?>>
            <div class="row">
                <div class="col-md-12">
                    <form action="../banco_dados/candidato_heteroidentificacao.php" method="post" class="row" aria-labelledby="titulo-heteroidentificacao">
                        <input hidden type="text" value="<?php echo $id_usuario; ?>" name="id_candidato">
                        <input value="<?php echo $cpf; ?>" maxlength="50" name="cpf_candidato" hidden>
                        <input value="1" name="fase" hidden>
                        <fieldset class="col-md-12">
                            <legend id="titulo-heteroidentificacao" class="mb-4">Inserir Parecer Heteroidentificação Complementar</legend>

                            <!-- Justificativa -->
                            <div class="mb-20">
                                <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                                <textarea
                                    name="justificativa"
                                    id="justificativa"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Digite a justificativa para seu parecer aqui..."
                                    required></textarea>
                            </div>

                            <!-- Resultado da autodeclaração -->
                            <div class="mb-20">
                                <label for="parecer" class="form-label">Autodeclaração</label>
                                <select name="parecer" id="parecer" class="form-control" required>
                                    <option value="">Selecione uma opção</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="nao_confirmada">Não confirmada</option>
                                    <option value="nao_compareceu">Não compareceu</option>
                                </select>
                            </div>

                            <!-- Botão -->
                            <div>
                                <button type="submit" class="btn btn-primary col-md-12">
                                    Salvar
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h4 class="">
            <a data-toggle="collapse" href="#heteroRevisora">Mostrar/Esconder Pareceres Heteroidentificação Revisora</a>
        </h4>
        <!-- Análises Revisora -->
        <?php $contadorRevisora = 0; ?>

        <div class="row collapse" id="heteroRevisora">
            <?php foreach ($pareceresFase2 as $parecer) : ?>
                <?php $contadorRevisora++; ?>
                <div class="col-md-12 mb-40">
                    <form <?php if ($parecer['id_avaliador'] != $_SESSION['id_usuario']) echo 'hidden'; ?> action="../banco_dados/candidato_edita_heteroidentificacao.php" method="post" class="row" aria-labelledby="titulo-heteroidentificacao">
                        <input hidden type="text" value="<?php echo $parecer['id']; ?>" name="id_parecer">
                        <input hidden type="text" value="<?php echo $parecer['id_avaliador']; ?>" name="id_avaliador">
                        <input hidden type="text" value="<?php echo $id_usuario; ?>" name="id_candidato">
                        <input value="<?php echo $cpf; ?>" maxlength="50" name="cpf_candidato" hidden>
                        <input value="1" name="fase" hidden>
                        <fieldset class="col-md-12">
                            <h4>Parecer revisor número: <?php echo ($contadorRevisora); ?></h4>
                            <p style="font-size: 16px;" id="titulo-heteroidentificacao" class="mb-4">
                                Análise realizada em <?php echo (trata_data_hora($parecer['data_avaliacao'])); ?> pelo <?php echo ($parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador']); ?>
                            </p>

                            <!-- Justificativa Revisora -->
                            <div class="mb-20">
                                <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                                <textarea
                                    name="justificativa"
                                    id="justificativa"
                                    class="form-control"
                                    rows="4"
                                    required><?php if ($parecer['justificativa'] != null) echo $parecer['justificativa']; ?></textarea>
                            </div>

                            <!-- Resultado da autodeclaração Revisora -->
                            <div class="mb-20">
                                <label for="parecer" class="form-label">Autodeclaração</label>
                                <select name="parecer" id="parecer" class="form-control" required>
                                    <option value="">Selecione uma opção</option>
                                    <option value="confirmada" <?php if ($parecer['parecer'] == 'confirmada') echo "selected"; ?>>Confirmada</option>
                                    <option value="nao_confirmada" <?php if ($parecer['parecer'] == 'nao_confirmada') echo "selected"; ?>>Não confirmada</option>
                                    <option value="nao_compareceu" <?php if ($parecer['parecer'] == 'nao_compareceu') echo "selected"; ?>>Não compareceu</option>
                                </select>
                            </div>

                            <!-- Botão -->
                            <div>
                                <button type="submit" class="btn btn-primary col-md-12">
                                    Salvar
                                </button>
                            </div>
                        </fieldset>
                    </form>

                    <div class="" <?php if ($parecer['id_avaliador'] == $_SESSION['id_usuario']) echo 'hidden'; ?>>
                        <h4>Parecer revisor número: <?php echo ($contadorRevisora); ?></h4>
                        <p style="font-size: 16px;" id="titulo-heteroidentificacao" class="mb-4">
                            Análise realizada em <?php echo (trata_data_hora($parecer['data_avaliacao'])); ?> pelo <?php echo ($parecer['graduacao_avaliador'] . ' ' . $parecer['nome_avaliador']); ?>
                        </p>

                        <!-- Justificativa Revisora -->
                        <div class="mb-20">
                            <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                            <textarea
                                name="justificativa"
                                id="justificativa"
                                class="form-control"
                                rows="4"
                                disabled><?php if ($parecer['justificativa'] != null) echo $parecer['justificativa']; ?></textarea>
                        </div>

                        <!-- Resultado da autodeclaração Revisora -->
                        <div class="mb-20">
                            <label for="resultado" class="form-label">Autodeclaração</label>
                            <input
                                name="resultado"
                                id="resultado"
                                class="form-control"
                                value="<?php if ($parecer['parecer'] == 'confirmada') {
                                            echo ('Confirmada');
                                        } elseif ($parecer['parecer'] == 'nao_confirmada') {
                                            echo ('Não Confirmada');
                                        } elseif ($parecer['parecer'] == 'nao_compareceu') {
                                            echo ('Não compareceu');
                                        } ?>"
                                disabled>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-40 mt-20" style="height: 1px; border-bottom: 1px solid #ccc;"></div>
            <?php endforeach; ?>
        </div>

        <!-- Resultado Final Revisora -->
        <div class="row" <?php if ($totalFase2 < 3) echo ('hidden'); ?>>
            <div class="col-md-12">
                <legend>
                    <h4>
                        Resultado Final Comissão Revisora: <?php echo (get_parecer_final_heteroidentificacao($id_usuario, $pareceres, 2)); ?>
                    </h4>
                </legend>
            </div>
        </div>

        <!-- Inserir avaliação Revisora -->
        <div class="card p-4" <?php if ($bloquearInclusaoRevisora || $_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'cr') echo ('hidden'); ?>>
            <div class="row">
                <div class="col-md-12">
                    <form action="../banco_dados/candidato_heteroidentificacao.php" method="post" class="row" aria-labelledby="titulo-heteroidentificacao">
                        <input hidden type="text" value="<?php echo $id_usuario; ?>" name="id_candidato">
                        <input value="<?php echo $cpf; ?>" maxlength="50" name="cpf_candidato" hidden>
                        <input value="2" name="fase" hidden>
                        <fieldset class="col-md-12">
                            <legend id="titulo-heteroidentificacao" class="mb-4">Inserir Parecer Heteroidentificação Complementar - Revisora</legend>

                            <!-- Justificativa -->
                            <div class="mb-20">
                                <label for="justificativa" class="form-label">Justificativa do Parecer</label>
                                <textarea
                                    name="justificativa"
                                    id="justificativa"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Digite a justificativa para seu parecer aqui..."
                                    required></textarea>
                            </div>

                            <!-- Resultado da autodeclaração -->
                            <div class="mb-20">
                                <label for="parecer" class="form-label">Autodeclaração</label>
                                <select name="parecer" id="parecer" class="form-control" required>
                                    <option value="">Selecione uma opção</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="nao_confirmada">Não confirmada</option>
                                    <option value="nao_compareceu">Não compareceu</option>
                                </select>
                            </div>

                            <!-- Botão -->
                            <div>
                                <button type="submit" class="btn btn-primary col-md-12">
                                    Salvar
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<!-- Bootstrap 3 JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>