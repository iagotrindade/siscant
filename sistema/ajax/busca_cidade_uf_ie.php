
<select name="cidade_ie" id="cidade_ie" class="form-control">
     <option value="">Selecione a Cidade</option>
     <?php
        $uf = $_GET['variavel'];
        include_once '../../banco_dados/conexao.php';
        
        $conexao = new Conexao();
        $resultado = $conexao->busca_cidade_uf($uf); 

        foreach ($resultado as $value) 
        {
            echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
        }
        $conexao = null;
   
     ?>   
</select>