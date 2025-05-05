
<select id="especialidade" name="especialidade" class="form-control">
     <option value="">Selecione a especialidade</option>
     <?php
        $ott_stt = $_GET['variavel'];
        include_once '../../banco_dados/conexao.php';
        session_start();
        $conexao = new Conexao();
        $resultado = $conexao->get_especialidade_ott_stt($ott_stt); 

        foreach ($resultado as $value) 
        {
            echo $value['id'];
            echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
        }
     ?>   
</select>