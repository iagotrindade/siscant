<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != "ouvidor" && $_SESSION['perfil'] != "consulta") {
    erro("Erro 7755! Página não encontrada!");
    exit();
}

// $lista_suporte = $conexao->get_lista_suporte_candidato();  
?>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Tutoriais <i class="fa fa-book"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Tutoriais</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">



            <div class="card">
                <legend>Vídeos</legend>
                <div class="card-body">
                    <table class="table table-hover table-bordered" id="tabela_dinamica">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Tutorial SISCANT EIPOT Etapa II</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="text-align: center; border-radius:10px; padding: 25px;">
                                        <video width="1280" height="720" controls style="border-radius:5px; box-shadow: 0px 0px 10px #197249">
                                            <source src="tutoriais/video_dois.mp4" type="video/mp4">
                                            Seu navegador não suporta a tag de vídeo.
                                        </video>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
            </div>

            <a href="javascript:history.back()"><button class="btn btn-default btn-block">VOLTAR</button></a>
        </div>
    </div>
</div>
</div>
<!--
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tabela_dinamica').DataTable({"order": [[ 0, "desc" ]]});</script> -->
</body>

</html>
<?php $conexao = null; ?>