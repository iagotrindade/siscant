<tr>
    <td><b>Voluntário para o SV Militar: </b>
        <?php 
            if($voluntario_sv_militar == 1) echo "Sim"; 
            if($voluntario_sv_militar == 0) echo "Não"; 
        ?>
    </td>
    <td><b>Situação Militar: </b><?php echo $situacao_militar ?></td>
    <td><b>Arrimo: </b>
        <?php 
            if($arrimo == 1) echo "Sim"; 
            if($arrimo == 0) echo "Não"; 
        ?>
    </td>
</tr>

<tr>
    <td><b>Atestado de antecedentes: </b>
        <?php 
            if($antecedentes == 1) echo "Sim"; 
            if($antecedentes == 0) echo "Não"; 
        ?>
    </td>
    <td><b>Certidão Negativa de Fórum Civil: </b>
        <?php 
            if($forum_civil == 1) echo "Sim"; 
            if($forum_civil == 0) echo "Não"; 
        ?>
    </td>
    <td><b>Certidão Negativa de Fórum Criminal: </b>
        <?php 
            if($forum_criminal == 1) echo "Sim"; 
            if($forum_criminal == 0) echo "Não"; 
        ?>
    </td>
</tr>

<tr>
    <td><b>Solicitou adiamento: </b>
        <?php 
            if($solicitou_adiamento == 1) echo "Sim"; 
            if($solicitou_adiamento == 0) echo "Não"; 
        ?>
    </td>
    <td><b>Início do adiamento: </b><?php if($data_inicio_adiamento != null) echo trata_data ($data_inicio_adiamento) ?></td>
    <td><b>Fim do adiamento: </b><?php if($data_fim_adiamento != null) echo trata_data ($data_fim_adiamento) ?></td>
</tr>

<tr>
    <td colspan="3"><b>Especialidade do adiamento: </b><?php echo $especialidade_adiamento ?></td>
</tr>




<tr>
    <td><b>Data da realização do ex med: </b><?php if($data_exame_saude != null) echo trata_data ($data_exame_saude) ?></td>
    <td>
        <b>Apto exame médico: </b>
            <?php 
                if($apto_saude == 1) echo "Sim"; 
                if($apto_saude == 0) echo "Não"; 
            ?>
    </td>
    <td><b>Grupo do ex med: </b><?php echo $grupo_saude ?></td>
</tr>

<tr>
    <td colspan="2"><b>Observações Ex Med: </b><?php echo $observacao_exame_saude ?></td>
    <td cols><b>CID: </b><?php echo $cid_saude ?></td>
</tr>


<tr>
    <td><b>Data da realização do ex med Recurso: </b><?php echo $data_exame_saude_recurso ?></td>
    <td><b>Grupo do ex med Recurso:   </b><?php echo $grupo_saude_recurso ?></td>
    <td><b>Apto exame médico Recurso: </b>
        <?php 
            if($apto_saude_recurso == 1) echo "Sim";
            if($apto_saude_recurso == 0 && $apto_saude_recurso != null) echo "Não";
        ?>
    </td>
</tr>

<tr>
    <td colspan="2"><b>Observações Ex Med Recurso: </b><?php echo $observacao_exame_saude_recurso ?></td>
    <td cols><b>CID Recurso: </b><?php echo $cid_saude_recurso ?></td>
</tr>


<tr>
    <td><b>Transferência da FISEMI: </b>
        <?php 
            if($transferencia_fisemi == 1) echo "Sim"; 
            if($transferencia_fisemi == 0) echo "Não"; 
        ?>
    </td>
    <td><b>RM de ORIGEM: </b><?php echo $fisemi_rm_origem; if($fisemi_rm_origem != null) echo "ª RM"; ?></td>
    <td><b>RM de DESTINO: </b><?php echo $fisemi_rm_destino; if($fisemi_rm_destino != null) echo "ª RM"; ?></td>
</tr>

<tr>
    <td><b>Situação Pós CSE: </b><?php echo $refratario_impedido ?></td>
    <td><b>Histórico Judicial: </b>
        <?php 
            if($historico_judicial == 1) echo "Sim"; 
            if($historico_judicial == 0) echo "Não"; 
        ?>
    </td>
    <td><b>Transitou em Julgado: </b>
        <?php 
            if($transitou_julgado == 1) echo "Sim"; 
            if($transitou_julgado == 0) echo "Não"; 
        ?>
    </td>
</tr>

<tr>
    <td><b>Número da ação: </b><?php echo $numero_acao ?></td>
    <td><b>Data da Liminar: </b><?php if($data_liminar != null) echo trata_data ($data_liminar) ?></td>
    <td><b>Convocado: </b>
        <?php 
            if($convocado == 1) echo "Sim"; 
            if($convocado == 0) echo "Não"; 
        ?>
    </td>
</tr>

<tr>
    <td><b>Favorável/Desfavorável: </b><?php echo $favoravel_desfavoravel ?></td>
    <td colspan="2"><b>Publicação em BAR Reg, Nº e Data: </b><?php echo $publicacao_bar_reg ?></td>
</tr>