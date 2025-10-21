<?php
include_once 'menu.php';
include_once 'codigos/funcao_apagar.php';

if ($candidato == 1 || $perfil == 'candidato' || $_SESSION['candidato'] == 1) {
    erro("Erro 23543! Página não encontrada!");
    exit();
}

if ($perfil != 'admin' && $perfil != 'consulta') {
    erro("Erro 37345757! Página não encontrada!");
    exit();
}
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Publicações <i class="fa fa-pencil"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Publicações</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <legend>Atenção <img src="imagens/urgente.gif" height="25px"></legend>

        <div class="alert alert-info p-20">
            <b>É necessário que o candidato esteja na etapa VI para ser inserido no Ranking.</b>
        </div>
    </div>

    <form name="form_relacao_classificacao_eipot_ampla" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_ampla_concorrencia">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Classificação Final após Etapas I, II, III IV, e V - Ampla Concorrência</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado_eipot">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III, IV e V - Ampla Concorrência - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III, IV e V - Ampla Concorrência." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_eipot">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_eipot" class="form-control" value="Convoco os candidatos abaixo classificados para escolha de Guarnição, via SISCANT, às 8h do dia 25 Jul 25. Em sua área logada do SISCANT o candidato deverá clicar em “SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR” e na página que abrirá ler as orientações para a escolha da guarnição. Se ainda não estiver na vez da escolha do candidato aparecerá a mensagem AGUARDE A SUA VEZ, atualize a página até que a mensagem altere para ESCOLHA AGORA A SUA GUARNIAÇÃO." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO FINAL APÓS ETAPA I, II, III, IV e V - AMPLA CONCORRÊNCIA</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_cotas_negros" action="mpdf/relacao_classificacao_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_cotas_negros">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Classificação Final após Etapas I, II, III, IV e V - Cotas Negros</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado">Título:</label>
                    <input type="text" name="titulo_resultado_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_resultado_eipot" class="form-control" value="Resultado após Etapas I, II, III, IV e V - Cotas Negros - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_resultado_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga o resultado após Etapas I, II, III, IV e V - Cotas Negros." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_eipot">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_resultado_eipot" class="form-control" value="Convoco os candidatos abaixo classificados para escolha de Guarnição, via SISCANT, às 8h do dia 25 Jul 25. Em sua área logada do SISCANT o candidato deverá clicar em “SELECIONE A GUARNIÇÃO NA QUAL DESEJA SERVIR” e na página que abrirá ler as orientações para a escolha da guarnição. Se ainda não estiver na vez da escolha do candidato aparecerá a mensagem AGUARDE A SUA VEZ, atualize a página até que a mensagem altere para ESCOLHA AGORA A SUA GUARNIAÇÃO." required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO FINAL APÓS ETAPA I, II, III, IV e V - COTAS NEGROS</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_resultado_escolha_eipot" action="mpdf/relatorio_resultado_escolha_eipot.php" method="post">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Resultado Escolha de Guarnição</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado_escolha">Título Resultado Escolhas:</label>
                    <input type="text" name="titulo_resultado_escolha" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_escolha">Subtítulo Resultado Escolhas:</label>
                    <input type="text" name="subtitulo_resultado_escolha" class="form-control" value="Resultado após Etapas I, II, III, IV e V - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_escolha">Primeiro Parágrafo Resultado Escolhas:</label>
                <input type="text" name="paragrafo_um_resultado_escolha" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, divulga a relação dos candidatos que escolheram a Xª RM para a realização do EIPOT/2025 e o resultado dos candidatos cotistas. Será publicado em documento distinto a Convocação para a Seleção Complementar onde constarão o local, horário e data de apresentação de cada candidato." required>
            </div>


            <div class="form-group">
                <label for="paragrafo_um_resultado_dois_escolha">Primeiro Parágrafo Destino Cotistas:</label>
                <input type="text" name="paragrafo_um_resultado_dois_escolha" class="form-control" value="O(s) candidato(s) cotista(s) da Etapa Presencial na Xª RM obteve(iveram) o(s) seguinte(s) resultado(s):" required>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 15 de Julho de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO RESULTADO ESCOLHA DE GUARNIÇÃO</button>
            </div>
        </div>
    </form>

    <form name="form_relacao_classificacao_cotas_negros" action="mpdf/relatorio_selecao_complementar_eipot.php" method="post">
        <input type="hidden" name="tipo_publicacao" value="eipot_cotas_negros">
        <div class="card">
            <!-- 26/06/2025 -> Iago Silva Alterado o título -->
            <h3>Convocação para Seleção Complementar</h3><br> <!-- Título do formulário -->

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="titulo_resultado">Título:</label>
                    <input type="text" name="titulo_convocacao_eipot" class="form-control" value="ESTÁGIO DE INSTRUÇÃO E DE PREPARAÇÃO PARA OFICIAIS TEMPORÁRIOS (EIPOT) / 2025" required>
                </div>
            </div>

            <div style="float: left; width: 48%;">
                <div class="form-group">
                    <label for="subtitulo_resultado_eipot">Subtítulo:</label>
                    <input type="text" name="subtitulo_convocacao_eipot" class="form-control" value="Resultado após Etapas I, II, III, IV, V e Designação - Xª RM" required>
                </div>
            </div>

            <div class="form-group">
                <label for="paragrafo_um_resultado_eipot">Primeiro Parágrafo:</label>
                <input type="text" name="paragrafo_um_convocacao_eipot" class="form-control" value="O Comandante da Xª RM, de acordo com o Aviso de Convocação Nr X-SSMR/X, de 10 de março de 2025, convoca à incorporação no Estágio de Instrução e de Preparação para Oficiais Temporários, a partir do dia 1º de setembro de 2025, após o final das etapas I a V e Designação do Processo Seletivo EIPOT 2025." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_dois_resultado_eipot">Segundo Parágrafo:</label>
                <input type="text" name="paragrafo_dois_convocacao_eipot" class="form-control" value="A apresentação para a Etapa VI - Seleção Complementar ocorrerá nos horários e endereços abaixo discriminados. Nesta etapa haverá a revisão de saúde a fim de verificar se algum candidato passou à condição de Inapto para o Serviço do Exército." required>
            </div>

            <div class="form-group">
                <label for="paragrafo_tres_resultado_eipot">Terceiro Parágrafo:</label>
                <input type="text" name="paragrafo_tres_convocacao_eipot" class="form-control" value="Quanto à entrega de documentos no dia da apresentação para a Etapa VI, os candidatos convocados deverão observar o previsto no Art 74 do Aviso de Convocação." required>
                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - INFANTARIA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[infantaria]" class="form-control" value="" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.
                    </textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - CAVALARIA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[cavalaria]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - ARTILHARIA DE CAMPANHA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[artilharia de campanha]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - ARTILHARIA ANTIAÉREA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[artilharia antiaérea]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - ENGENHARIA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[engenharia]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - COMUNICAÇÕES:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[comunicações]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - MATERIAL BÉLICO:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[material bélico]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 100%;">
                <div class="form-group">
                    <label for="titulo_um">SELEÇÃO COMPLEMENTAR - INTENDÊNCIA:</label>
                    <textarea style="height: 80px;" type="text" name="hora_arma[intendência]" class="form-control" required>Os candidatos classificados para o EIPOT/2025, abaixo discriminados, ordenados de 01 a 03 deverão apresentar-se no 29° Batalhão de Infantaria Blindado (29º BIB), localizado na Estrada Capitão Vasco Amaro da Cunha, Nr 3129 - Boi Morto Santa Maria - RS | 97030-110, às 0800h do dia 20 AGO 25. Os candidatos ordenados 04 e 05 deverão apresentar-se no Parque Regional de Manutenção da 3ª RM (Pq R Mnt/3), localizado na Rua Rad. Osvaldo Nobre, 1130 - Juscelino Kubitschek, Santa Maria - RS, 97035-000, às 0800h do dia 20 AGO 25.</textarea>
                </div>

                <div style="background-color: #CCC; height: 1px;" class="mb-20 mt-40"></div>
            </div>

            <div style="float: left; width: 48%; margin-right: 4%;">
                <div class="form-group">
                    <label for="texto_dia">Data:</label>
                    <input type="text" name="texto_dia" class="form-control" value="Porto Alegre - RS, 1 de Agosto de 2025" required>
                </div>
            </div>

            <div class="col-mg-12">
                <button type="submit" class="btn btn-primary btn-block">PUBLICAÇÃO CONVOCAÇÃO SELEÇÃO COMPLEMENTAR</button>
            </div>
        </div>
    </form>
</div>
</body>

</html>
<?php $conexao = null; ?>