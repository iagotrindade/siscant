<div class="card">
    <legend>Heteroidentificação Complementar</legend>
    <form action="../banco_dados/candidato_heteroidentificacao.php" method="post" class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <label>Justificativa do Parecer</label><br>
                    <textarea name="justificativa" style="width: 100%"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label>Autodeclaração</label><br>
                    <select class="form-control" name="resultado">
                        <option value="">Selecione uma opção</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="nao_confirmada">Não confirmada</option>
                        <option value="nao_compareceu">Não compareceu</option>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-top:20px;">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block">SALVAR</button>
                </div>
            </div>
        </div>
    </form>
</div>