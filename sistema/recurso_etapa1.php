<?php
include_once 'menu.php';
?>

<div class="content-wrapper">
  <div class="page-title">
    <div>
      <h1>Suporte <i class="fa fa-support"></i></h1>
    </div>
    <div>
        <ul class="breadcrumb">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><a href="index.php">Página Inicial</a></li>
            <li>Suporte </li>
        </ul>
    </div>
  </div>
            
        <div  class="row">
            <div  class="col-lg-12">
                <div class="card">
                    <legend>Preencha os campos para enviar um recurso</legend>
                    <form action="../banco_dados/#" method="post">
                        <div  class="row">
                            
                             <div  class="col-lg-12">
                                <div id="div_mail" class="form-group"> 
                                    <label>Será enviado um recurso para a administração da seleção.</label>
                                </div>
                            </div>
                            
                            <div  class="col-lg-12">
                                <div id="div_posto" class="form-group"> <label for="posto">Razão da solicitação do recurso</label>
                                    <select id="posto" name="posto_grad" class="form-control">
                                        <option value="">Selecione a opção</option>
                                        <option value="doc_obrigatorios"> ...</option>
                                        <option value="doc_obrigatorios"> ...</option>
                                        <option value="doc_obrigatorios"> ...</option>
                                        <option value="especialidade">Outro</option>
                                    </select>
                                </div>
                            </div>
                            
                           
                            
                            <div  class="col-lg-12">
                                <div id="div_mail" class="form-group"> 
                                    <label>Recurso</label>
                                    <textarea maxlength="1000" name="mensagem_suporte" class="form-control"></textarea>
                                </div>
                            </div>
                    
                            <div  class="col-lg-12">
                                <div id="mensagem_erro" hidden>
                                    <font color="red"><b><center><p id="mensagem"></p></center></b></font>
                                </div>
                            </div>
                            
                            <div  class="col-lg-12">
                                <br><button name="enviar" type="submit"  class="btn btn-primary btn-block">Enviar</button>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>
            
                    
</div>
</div>
</body>
</html>