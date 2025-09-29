<?php
include_once 'menu.php';

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'consulta') {
    erro("Erro 544654: Página não encontrada");
    exit();
}

$limite = 1000;

if (isset($_GET['limite']))
    $limite = $_GET['limite'];

$cpf_pesquisa = null;;
if (isset($_GET['cpf'])) {
    $cpf_pesquisa = $_GET['cpf'];

    $cpf_pesquisa = $cpf_pesquisa = str_replace('.', '', $cpf_pesquisa);
    $cpf_pesquisa = $cpf_pesquisa = str_replace('-', '', $cpf_pesquisa);
}
if ($cpf_pesquisa == "") $cpf_pesquisa = null;

$codigo_pesquisa = null;;
if (isset($_GET['codigo'])) {
    $codigo_pesquisa = (int)$_GET['codigo'];
}

?>

<style>
    .card-header {
        font-size: 20px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 12px 15px;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .badge {
        background-color: var(--primary-color);
        font-size: 0.85em;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
    }

    code {
        background-color: #f8f9fa;
        padding: 3px 6px;
        border-radius: 4px;
        font-size: 1em;
        color: #e83e8c;
    }
</style>

<div class="content-wrapper">
    <div class="page-title">
        <div>
            <h1>Auditoria <i class="fa fa-eye"></i></h1>
        </div>
        <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
                <li><a href="index.php">Página Inicial</a></li>
                <li>Auditoria</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white p-15 mb-20">
                            <span class="mb-0"><i class="fa fa-filter me-2"></i> Filtros de Pesquisa</span>
                        </div>
                        <div class="card-body">
                            <form name="formulario" action="auditoria.php" method="get" class="row g-3 align-items-end">
                                <div class="col-lg-4">
                                    <select onchange="formulario.submit()" name="limite" class="form-control">
                                        <option <?php if ($limite == 1000) echo "selected" ?> value="1000">Últimos 1000 Registros</option>
                                        <option <?php if ($limite == 5000) echo "selected" ?> value="5000">Últimos 5000 Registros</option>
                                        <option <?php if ($limite == 10000) echo "selected" ?> value="10000">Últimos 10000 Registros</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <input name="codigo" maxlength="50" placeholder="Digite o código" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <input name="cpf" maxlength="50" value="<?php echo $cpf_pesquisa ?>" placeholder="Digite o CPF" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn bg-primary">
                                        <i class="fa fa-search me-2"></i>PESQUISAR
                                    </button>
                                </div>
                            </form>

                            <?php if ($codigo_pesquisa != null && $cpf_pesquisa != null): ?>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <strong>VOCÊ DEVE ESCOLHER NA PESQUISA O CÓDIGO OU O CPF!</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white p-15 mb-20">
                            <span class="mb-0"><i class="fa fa-eye me-2"></i> Auditoria do Sistema</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="tabela_dinamica">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fa fa-hashtag me-1"></i> ID</th>
                                            <th><i class="fa fa-user me-1"></i> ID USR</th>
                                            <th><i class="fa fa-id-card me-1"></i> CPF</th>
                                            <th><i class="fa fa-edit me-1"></i> Alteração</th>
                                            <th><i class="fa fa-calendar me-1"></i> Data</th>
                                            <th><i class="fa fa-cog me-1"></i> Sistema</th>
                                            <th><i class="fa fa-sitemap me-1"></i> IP</th>
                                            <th><i class="fa fa-code me-1"></i> COD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $lista_logs = null;

                                        if ($cpf_pesquisa != null && $codigo_pesquisa == null) {
                                            $usuario_pesquisa = $conexao->get_usuario_cpf($cpf_pesquisa);
                                            $id_usuario_pesquisa = null;
                                            if (count($usuario_pesquisa) > 0)
                                                $id_usuario_pesquisa = $usuario_pesquisa[0]['id'];
                                            $lista_logs = $conexao->get_logs_avancado($cpf_pesquisa, $id_usuario_pesquisa);
                                        }
                                        if ($codigo_pesquisa != null && $cpf_pesquisa == null) {
                                            $lista_logs = $conexao->get_logs_codigo($codigo_pesquisa, $limite);
                                        }
                                        if ($codigo_pesquisa == null && $cpf_pesquisa == null) {
                                            $lista_logs = $conexao->get_logs_operadores($limite);
                                        }

                                        if ($lista_logs != null) {
                                            foreach ($lista_logs as $linha) {
                                                echo '
                                    <tr>
                                        <td><span class="badge bg-secondary">' . $linha['id'] . '</span></td>
                                        <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario'] . '" class="text-decoration-none text-primary">' . $linha['id_usuario'] . '</a></td>
                                        <td><a href="usuario_visualiza.php?id_usuario=' . $linha['id_usuario'] . '" class="text-decoration-none text-primary">' . $linha['cpf'] . '</a></td>
                                        <td><span class="text-truncate d-inline-block" style="max-width: 200px;" title="' . htmlspecialchars($linha['alteracao']) . '">' . $linha['alteracao'] . '</span></td>
                                        <td><span class="badge bg-info">' . trata_data_hora($linha['data']) . '</span></td>
                                        <td><span class="badge bg-success">' . $linha['sistema'] . '</span></td>
                                        <td><code>' . $linha['ip'] . '</code></td>
                                        <td><span class="badge bg-warning text-dark">' . $linha['codigo'] . '</span></td>
                                    </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fa fa-inbox me-2"></i>Nenhum registro encontrado</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white p-15 mb-20">
                            <span class="mb-0"><i class="fa fa-code me-2"></i> Codigos de Operações</span>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tabela_dinamica2">
                                    <thead>
                                        <tr>
                                            <th>Operação</th>
                                            <th>Detalhamento</th>
                                            <th>Código</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Candidato se cadastrou</td>
                                            <td>14101</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>E-Mail de cadastro enviado</td>
                                            <td>14102</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Fez login no sistema</td>
                                            <td>14103</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Alterou a sua senha necessariamente</td>
                                            <td>14104</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Enviou um suporte inicial</td>
                                            <td>14105</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastro de especialidade</td>
                                            <td>14106</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastro de especialidade nas cidades</td>
                                            <td>14107</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastro de usuário</td>
                                            <td>14108</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastrou uma especialidade</td>
                                            <td>14109</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Insere documento obrigatório</td>
                                            <td>14110</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastra documento obrigatório</td>
                                            <td>14111</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastra opção de currículo</td>
                                            <td>14112</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu um currículo</td>
                                            <td>14113</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu uma prioridade</td>
                                            <td>14114</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu um suporte</td>
                                            <td>14115</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Enviou E-Mail resposta suporte</td>
                                            <td>14116</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Enviou E-Mail nova senha candidato</td>
                                            <td>14117</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Enviou o arquivo de pagamento da inscrição</td>
                                            <td>14118</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu Especialidades para o avaliador</td>
                                            <td>14119</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Insere observação no candidato</td>
                                            <td>14120</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Observação adicionada, Candidato validado pelo script de inscricao</td>
                                            <td>14121</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Observação adicionada, Candidato passou para Etapa II</td>
                                            <td>14122</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastra Médico Obrigatório</td>
                                            <td>14124</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu currículo para o candidato</td>
                                            <td>14125</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu Exame Médico na Conf da Seleção</td>
                                            <td>14126</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu uma prioridade para o médico obrigatório</td>
                                            <td>14127</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu um arquivo para o candidato</td>
                                            <td>14128</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Enviou o arquivo de isenção de pagamento</td>
                                            <td>14129</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Cadastrou recurso para Candidato</td>
                                            <td>14130</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Observação adicionada, Candidato retornou para seleção pelo script CTRL Z</td>
                                            <td>14131</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Envio de E-Mail quando o candidato seleciona uma cidade para servir</td>
                                            <td>14132</td>
                                        </tr>
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Candidato adicionou um recurso</td>
                                            <td>14133</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Especialidade</td>
                                            <td>15101</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Documentação Obrigatória Cadastrada</td>
                                            <td>15102</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Documento Obrigatório Inserido</td>
                                            <td>15103</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Inscrição de Especialidade</td>
                                            <td>15104</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Opção de Currículo</td>
                                            <td>15105</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Currículo de uma especialidade</td>
                                            <td>15106</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Usuário</td>
                                            <td>15107</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga todas as prioridades de cidades de uma especialidade</td>
                                            <td>15108</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Candidato</td>
                                            <td>15109</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga arquivo pagamento de inscrição</td>
                                            <td>15110</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga observacao candidato</td>
                                            <td>15111</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga Exame Médico da Conf da Seleção</td>
                                            <td>15112</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga todas as prioridades de cidades de uma especialidade do médico obrigatório</td>
                                            <td>15113</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga arquivo que foi adicionado para o candidato</td>
                                            <td>15114</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga recurso do candidato</td>
                                            <td>15115</td>
                                        </tr>
                                        <tr>
                                            <td>DELETE</td>
                                            <td>Apaga recurso</td>
                                            <td>15116</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato altualizou os seus dados</td>
                                            <td>16101</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Atualizou a sua foto</td>
                                            <td>16102</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Resetou a senha do usuário</td>
                                            <td>16103</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Respondeu suporte para o candidato</td>
                                            <td>16104</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Respondeu suporte inicial</td>
                                            <td>16105</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato resetou a sua senha</td>
                                            <td>16106</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Resetou a senha do candidato</td>
                                            <td>16107</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou as datas da inscrição da seleção</td>
                                            <td>16108</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Esqueceu e resetou a senha</td>
                                            <td>16109</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Editou o usuário</td>
                                            <td>16110</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Editar as cidades da especialidade</td>
                                            <td>16111</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Observação adicionada, Candidato ELIMINADO pelo script de Inscricao</td>
                                            <td>16112</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato ELIMINADO pelo script de Inscricao</td>
                                            <td>16113</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Executou o script de Inscricao</td>
                                            <td>16114</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Liberou os comprovantes de inscrição</td>
                                            <td>16115</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou o status de concorrendo do candidato</td>
                                            <td>16116</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Avaliou um currículo</td>
                                            <td>16117</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Avaliou Provas de Música</td>
                                            <td>16118</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou as datas de avaliação</td>
                                            <td>16119</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou status de concorrendo em especialidade</td>
                                            <td>16120</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Atualizou a data de isenção</td>
                                            <td>16121</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Atualizou status usuário isento pagamento</td>
                                            <td>16122</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Avaliou um documento obrigatório</td>
                                            <td>16123</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Mudou a seleção de Etapa</td>
                                            <td>16124</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Executou o script de Pagamento</td>
                                            <td>16125</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Executou o script de Isentos</td>
                                            <td>16126</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a etapa do candidato</td>
                                            <td>16127</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a liberação da avaliação curricular para os candidatos</td>
                                            <td>16128</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a senha do médico obrigatório</td>
                                            <td>16129</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou os dados do médico obrigatório</td>
                                            <td>16130</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou OUTRAS INFO do médico obrigatório</td>
                                            <td>16131</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou ADIAMENTO do médico obrigatório</td>
                                            <td>16132</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou EXAME MÉDICO</td>
                                            <td>16133</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou TRANSFERÊNCIA DE FISEMI do médico obrigatório</td>
                                            <td>16134</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou REFRATÁRIO/IMPEDIMENTO JUDICIAL do médico obrigatório</td>
                                            <td>16135</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou RECURSO EXAME MÉDICO</td>
                                            <td>16136</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou DISTRIBUIÇÃO do candidato</td>
                                            <td>16137</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou IMPEDIMENTO JUDICIAL do candidato</td>
                                            <td>16138</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a liberação para candidatos selecionarem as prioridades das cidades</td>
                                            <td>16139</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou os limites de nascimento para inscrição</td>
                                            <td>16140</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou o status de seleção encerrada</td>
                                            <td>16141</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou o status de pagamento da seleção</td>
                                            <td>16142</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou o Nº de vagas de uma Especialidade X Cidade</td>
                                            <td>16143</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a eliminação do candidato caso ele não tenha colodo foto</td>
                                            <td>16144</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a eliminação do candidato caso ele não tenha colodo todos os documentos obrigatórios</td>
                                            <td>16145</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a liberação da visualização do candidato das avaliações dos documentos obrigatórios</td>
                                            <td>16146</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou dados do ofício de recurso</td>
                                            <td>16147</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Avaliador analisou o recurso</td>
                                            <td>16148</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato escolheu cidade em que quer servir</td>
                                            <td>16149</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato escolheu NENHUMA DAS OPÇÕES ao selecionar a cidade de destino</td>
                                            <td>16150</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Candidato Desclassificado do processo por não estar concorrendo em nenhuma especialidade</td>
                                            <td>161501</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou as datas de início e fim para o candidato selecionar a cidade onde quer servir</td>
                                            <td>161502</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Executou o Script CTRL + Z</td>
                                            <td>161503</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Adicionada nota de prova Teórica/Prática</td>
                                            <td>161504</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>OM Atualizou informações do candidato</td>
                                            <td>161505</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Atualiza Cidade do candidato onde ele quer servir</td>
                                            <td>161506</td>
                                        </tr>
                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Atualiza nota usuário EIPOT</td>
                                            <td>161507</td>
                                        </tr>

                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Altera E-mail candidato</td>
                                            <td>161508</td>
                                        </tr>

                                        <!-- 20/06/2025 -> Iago Silva Adicionado novos registros de auditoria para heteroidentificação -->
                                        <tr>
                                            <td>INSERT</td>
                                            <td>Inseriu um parecer de heteroidentificação do candidato</td>
                                            <td>161510</td>
                                        </tr>

                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou um parecer de heteroidentificação do candidato</td>
                                            <td>161511</td>
                                        </tr>

                                        <tr>
                                            <td>UPDATE</td>
                                            <td>Alterou a etapa da especialidade do candidato</td>
                                            <td>161509</td>
                                        </tr>

                                        <tr>
                                            <td>RESTAURA</td>
                                            <td>Restaurou candidato</td>
                                            <td>17101</td>
                                        </tr>
                                        <tr>
                                            <td>RESTAURA</td>
                                            <td>Restaurou Documento Obrigatório</td>
                                            <td>17102</td>
                                        </tr>
                                        <tr>
                                            <td>RESTAURA</td>
                                            <td>Restaurou Currículo</td>
                                            <td>17103</td>
                                        </tr>
                                        <tr>
                                            <td>RESTAURA</td>
                                            <td>Restaurou Especialidade</td>
                                            <td>17104</td>
                                        </tr>
                                        <tr>
                                            <td>RESTAURA</td>
                                            <td>Restaurou Usuário</td>
                                            <td>17105</td>
                                        </tr>
                                        <tr>
                                            <td>INSCRIÇÃO</td>
                                            <td>Gerou o relatório de inscrição</td>
                                            <td>18101</td>
                                        </tr>
                                        <tr>
                                            <td>ACESSO</td>
                                            <td>Acessou uma página</td>
                                            <td>19100</td>
                                        </tr>
                                        <tr>
                                            <td>ACESSO</td>
                                            <td>Acessou um usuario</td>
                                            <td>19101</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Criou um Relatório de Candidatos</td>
                                            <td>20101</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Criou um Relatório de Classificação de Candidatos</td>
                                            <td>20102</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Criou um Relatório de quem realizou exame de saúde em determinada data</td>
                                            <td>20102</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um relatório de quem compareceu em uma data para exame de saúde</td>
                                            <td>20103</td>
                                        </tr>
                                        <tr>
                                            <td>Abriu PDF</td>
                                            <td>Abriu um arquivo PDF</td>
                                            <td>20104</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Criou uma planilha excel da pontuação dos candidatos não avaliados</td>
                                            <td>20105</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um ofício de resposta do recurso</td>
                                            <td>20106</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou relatório de informações do médico obrigatório</td>
                                            <td>20107</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou relatório de informações do candidato</td>
                                            <td>20108</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Candidato gerou comprovante de inscrição</td>
                                            <td>20109</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um relatório de exame médico</td>
                                            <td>20110</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um relatório RECURSO de exame médico</td>
                                            <td>20111</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um excel de todos os médicos obrigatórios</td>
                                            <td>20112</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um excel da pontuação automática</td>
                                            <td>20113</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um excel distribuição</td>
                                            <td>20114</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um excel candidatos participando</td>
                                            <td>20115</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um excel candidatos</td>
                                            <td>20116</td>
                                        </tr>
                                        <tr>
                                            <td>RELATÓRIO</td>
                                            <td>Gerou um relatório de médicos obrigatórios de quem compareceu em uma data para exame de saúde</td>
                                            <td>20117</td>
                                        </tr>
                                        <tr>
                                            <td>E-Mail</td>
                                            <td>E-Mail Não enviado no cadastro de candidato</td>
                                            <td>21100</td>
                                        </tr>
                                        <tr>
                                            <td>E-Mail</td>
                                            <td>E-Mail Não enviado ao resetar a senha do candidato</td>
                                            <td>21101</td>
                                        </tr>
                                        <tr>
                                            <td>E-Mail</td>
                                            <td>E-Mail Não enviado de suporte</td>
                                            <td>21102</td>
                                        </tr>
                                        <tr>
                                            <td>E-Mail</td>
                                            <td>E-Mail Não enviado da escolha de cidade do candidato</td>
                                            <td>21103</td>
                                        </tr>
                                        <tr>
                                            <td>GRU</td>
                                            <td>Gerou uma GRU para Pagamento</td>
                                            <td>22100</td>
                                        </tr>
                                        <tr>
                                            <td>GRU</td>
                                            <td>Adicionou uma planilha CSV dos candidatos que realizaram o pagamento</td>
                                            <td>22101</td>
                                        </tr>

                                        <tr>
                                            <td>Assistente Virtual</td>
                                            <td>Adicionou uma pergunta e resposta a base de dados do Assistente Virtual</td>
                                            <td>22102</td>
                                        </tr>

                                        <tr>
                                            <td>Assistente Virtual</td>
                                            <td>Apagou uma pergunta e resposta a base de dados do Assistente Virtual</td>
                                            <td>22103</td>
                                        </tr>

                                        <tr>
                                            <td>Assistente Virtual</td>
                                            <td>Editou uma pergunta e resposta a base de dados do Assistente Virtual</td>
                                            <td>22104</td>
                                        </tr>

                                        <tr>
                                            <td>Notificações</td>
                                            <td>Enviou uma notificação para os candidatos</td>
                                            <td>22105</td>
                                        </tr>

                                        <tr>
                                            <td>Notificações</td>
                                            <td>Excluiu uma notificação para os candidatos</td>
                                            <td>22106</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    $('#tabela_dinamica').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
</script>
<script type="text/javascript">
    $('#tabela_dinamica2').DataTable();
</script>
</body>

</html>
<?php $conexao = null; ?>