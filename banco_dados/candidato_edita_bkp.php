<?php

session_start();

include_once '../sistema/funcoes.php';

if(!$_POST)
{
    erro("Erro 23423644!");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS E VALIDAÇÕES">

$nome_completo=null;
$identidade=null;
$nome_social=null;
$dependentes=null;
$filiacao_pai=null;
$estado_civil=null;
$companheiro=null;
$sexo=null;
$nascionalidade=null;
$naturalidade=null;
$filiacao_mae=null;
$data_nascimento=null;
$uf=null;
$bairro=null;
$cidade=null;
$cep=null;
$rua=null;
$telefone=null;
$celular=null;
$mail=null;
$tempo_sv_pub=null;
$tempo_sv_pub_anos=null;
$tempo_sv_pub_meses=null;
$tempo_sv_pub_dias=null;
$tempo_sv_mil=null;
$tempo_sv_mil_anos=null;
$tempo_sv_mil_meses=null;
$tempo_sv_mil_dias=null;
$civil_militar=null;
$certificado=null;
$ativa_reserva=null;
$posto_grad=null;
$forca=null;
$arma=null;
$documento=null;
$data_expedicao=null;
$incorporacao=null;
$licenciamento=null;
$cidade_etapas_presenciais=null;
$curso_graduacao = null;
$ano_formacao_ofor = null;
$nota_ofor = null;
$arma_eipot = null;

if(!isset($_POST['declaracao']))
{
    erro("Erro 6867! Declaração de veracidade não preenchida!");
    exit();    
}

if (
        trim($_POST['nome_completo']) == null ||
        trim($_POST['identidade']) == null ||
        trim($_POST['filiacao_pai']) == null ||
        trim($_POST['sexo']) == null ||
        trim($_POST['estado_civil']) == null ||
        trim($_POST['data_nascimento']) == null ||
        trim($_POST['nascionalidade']) == null ||
        trim($_POST['naturalidade']) == null ||
        trim($_POST['filiacao_mae']) == null ||
        trim($_POST['uf']) == null ||
        trim($_POST['bairro']) == null ||
        trim($_POST['cidade']) == null ||
        trim($_POST['cep']) == null ||
        trim($_POST['rua']) == null ||
        trim($_POST['telefone']) == null ||
        trim($_POST['celular']) == null ||
        trim($_POST['mail']) == null ||
        //trim($_POST['tempo_sv_pub']) == null ||
        //trim($_POST['tempo_sv_mil']) == null ||
        trim($_POST['civil_militar']) == null

    
    )

   
    {
        erro("Erro 6864567! Todos os campos são obrigatorios!");
        exit();
    }
    
    if(trim($_POST['nome_social']) == null && isset($_POST['check_nome_social']))
    {
        erro("Erro 6456867! Você selecionou que possui nome social! Logo, deve preencher este campo");
        exit();
    }
    /*
    if($_POST['tempo_sv_pub'] == '1')
    {
        if($_POST['tempo_sv_pub_anos'] == null ||
        $_POST['tempo_sv_pub_meses'] == null ||
        $_POST['tempo_sv_pub_dias'] == null)
        {
            erro("Erro 23456867! Todos os campos do tempo de se serviço público são obrigatorios!");
            exit();
        }
    }
     */
    
    if(isset($_POST['tempo_sv_mil']) && $_POST['tempo_sv_mil'] == null && !isset($_SESSION['eipot']))
    {
        erro("Erro 2436246456! O campo de tempo de se serviço militar é obrigatorio!");
        exit();
    }
    
    if($_POST['tempo_sv_mil'] == '1')
    {
        if($_POST['tempo_sv_mil_anos'] == null ||
        $_POST['tempo_sv_mil_meses'] == null ||
        $_POST['tempo_sv_mil_dias'] == null)
        {
            erro("Erro 673423! Todos os campos do tempo de se serviço militar são obrigatorios!");
            exit();
        }
    }
    
    if($_POST['civil_militar'] == 'civil' && ($_POST['ja_foi_militar'] != 'sim' && $_POST['ja_foi_militar'] != 'nao'))
    {
        erro("Erro 2456523! Se você é civil, deve responder ser já foi militar!");
        exit();
    }
    
    $ativa_reserva = null;
    
    if($_POST['ja_foi_militar'] == 'sim')
        $ativa_reserva = "ja_foi_militar";
    
    if($_POST['ja_foi_militar'] == 'nao')
        $ativa_reserva = "nunca_foi_militar";
    
    if($_POST['civil_militar'] == 'militar')
        $ativa_reserva = 'militar_ativa';
    
    
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    //// VALIDAÇÕES CIVIL / MILITAR
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    
    
    
    if($_POST['civil_militar'] == 'civil')
    {
        
        if($_POST['ja_foi_militar'] == 'sim')
        {
            if(trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null || trim($_POST['posto_grad']) == null ||
               trim($_POST['forca']) == null || trim($_POST['arma']) == null || trim($_POST['incorporacao']) == null || 
               trim($_POST['licenciamento']) == null)
            {
                erro("Erro 84363! Os campos de EX militar são obrigatórios!");
                exit();
            }
            
            if($_POST['sexo'] == 'masculino' && $_POST['certificado'] == null)
            {
                erro("Erro 233467523! O campo certificado é obrigatório para o sexo masculino!");
                exit();
            }
        }
        
        if($_POST['ja_foi_militar'] == 'nao' && $_POST['sexo'] == 'masculino')
        {
            if($_POST['certificado'] == null || trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null)
            {
                erro("Erro 23445623! Os campos, Documento Militar,  Nº do documento e data de expedição são obrigatórios!");
                exit();
            }
        }
        
        
    }
    if($_POST['civil_militar'] == 'militar')
    {
         if(trim($_POST['documento']) == null || trim($_POST['data_expedicao']) == null || trim($_POST['posto_grad']) == null ||
            trim($_POST['forca']) == null || trim($_POST['arma']) == null || trim($_POST['incorporacao']) == null)
        {
            erro("Erro34563! Todos os campos do militar temporário são obrigatórios!");
            exit();
        }
    }
 
    if($_POST['nome_completo'] != "")
        $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
    if($_POST['identidade'] != "")
        $identidade = htmlspecialchars(trim($_POST['identidade']));
    if($_POST['data_nascimento'] != "")
        $data_nascimento = htmlspecialchars(reverte_data(trim($_POST['data_nascimento'])));
    if($_POST['nome_social'] != "")
        $nome_social = htmlspecialchars(trim($_POST['nome_social']));
    if($_POST['estado_civil'] != "")
        $estado_civil = htmlspecialchars(trim($_POST['estado_civil']));
    if($_POST['filiacao_pai'] != "")
        $filiacao_pai = htmlspecialchars(trim($_POST['filiacao_pai']));
    if($_POST['sexo'] != "")
        $sexo = htmlspecialchars(trim($_POST['sexo']));
    if($_POST['nascionalidade'] != "")
        $nascionalidade = htmlspecialchars(trim($_POST['nascionalidade']));
    if($_POST['naturalidade'] != "")
        $naturalidade = htmlspecialchars(trim($_POST['naturalidade']));
    if($_POST['filiacao_mae'] != "")
        $filiacao_mae = htmlspecialchars(trim($_POST['filiacao_mae']));

    if($_POST['uf'] != "")
        $uf = htmlspecialchars(trim($_POST['uf']));
    if($_POST['bairro'] != "")
        $bairro = htmlspecialchars(trim($_POST['bairro']));
    if($_POST['cidade'] != "")
        $cidade = htmlspecialchars(trim($_POST['cidade']));
    if($_POST['cep'] != "")
        $cep = htmlspecialchars(trim($_POST['cep']));
    if($_POST['rua'] != "")
        $rua = htmlspecialchars(trim($_POST['rua']));
    if($_POST['telefone'] != "")
        $telefone = htmlspecialchars(trim($_POST['telefone']));
    if($_POST['celular'] != "")
        $celular = htmlspecialchars(trim($_POST['celular']));
    if($_POST['mail'] != "")
        $mail = htmlspecialchars(trim($_POST['mail']));

    /*
    if($_POST['tempo_sv_pub'] != "")
        $tempo_sv_pub = htmlspecialchars($_POST['tempo_sv_pub']);
    if($_POST['tempo_sv_pub_anos'] != "")
        $tempo_sv_pub_anos = htmlspecialchars($_POST['tempo_sv_pub_anos']);
    if($_POST['tempo_sv_pub_meses'] != "")
        $tempo_sv_pub_meses = htmlspecialchars($_POST['tempo_sv_pub_meses']);
    if($_POST['tempo_sv_pub_dias'] != "")
        $tempo_sv_pub_dias = htmlspecialchars($_POST['tempo_sv_pub_dias']);
    */
    if($_POST['tempo_sv_mil'] != "")
        $tempo_sv_mil = htmlspecialchars($_POST['tempo_sv_mil']);
    if($_POST['tempo_sv_mil_anos'] != "")
        $tempo_sv_mil_anos = htmlspecialchars($_POST['tempo_sv_mil_anos']);
    if($_POST['tempo_sv_mil_meses'] != "")
        $tempo_sv_mil_meses = htmlspecialchars($_POST['tempo_sv_mil_meses']);
    if($_POST['tempo_sv_mil_dias'] != "")
        $tempo_sv_mil_dias = htmlspecialchars($_POST['tempo_sv_mil_dias']);

    if($_POST['civil_militar'] != "")    
        $civil_militar = htmlspecialchars($_POST['civil_militar']);
    if(isset($_POST['certificado']) && $_POST['certificado'] != "")
        $certificado = htmlspecialchars(trim($_POST['certificado']));
    if($_POST['documento'] != "")
        $documento = htmlspecialchars(trim($_POST['documento']));
    if($_POST['data_expedicao'] != "")
        $data_expedicao = htmlspecialchars(reverte_data(trim($_POST['data_expedicao'])));

    if($_POST['posto_grad'] != "")
        $posto_grad = htmlspecialchars($_POST['posto_grad']);
    if($_POST['forca'] != "")
        $forca = htmlspecialchars($_POST['forca']);
    if($_POST['arma'] != "")
        $arma = htmlspecialchars(trim($_POST['arma']));
    if($_POST['incorporacao'] != "")
        $incorporacao = htmlspecialchars(trim($_POST['incorporacao']));
    if($_POST['licenciamento'] != "")
        $licenciamento = htmlspecialchars(trim($_POST['licenciamento']));
    
    if($estado_civil == 'casado' || $estado_civil == 'uniao_estavel')
    {
        $companheiro = htmlspecialchars(trim($_POST['nome_companheiro']));
        if($companheiro == '' || $companheiro == null)
        {
            erro("Erro 5685854747! O nome do companheiro(a) é obrigatório!");
            exit();
        }
    }
    else $companheiro = null;
    
    
    if($_POST['curso_graduacao'] != "")
        $curso_graduacao = htmlspecialchars(trim($_POST['curso_graduacao']));
    if($_POST['ano_formacao_ofor'] != "")
        $ano_formacao_ofor = (int)htmlspecialchars(trim($_POST['ano_formacao_ofor']));
    if($_POST['nota_ofor'] != "")
        $nota_ofor = (float)htmlspecialchars(trim($_POST['nota_ofor']));
    if($_POST['arma_eipot'] != "")
        $arma_eipot = htmlspecialchars(trim($_POST['arma_eipot']));
    
    
    
    if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
    {
        erro("Erro 26783! E-Mail Inválido!");
        exit();
    }
    
    if(!valida_data($_POST['data_nascimento']))
    {
        erro("Erro 26783! Data de nascimento inválida!");
        exit();
    }
    if(trim($_POST['data_expedicao']) != null && !valida_data($_POST['data_expedicao']))
    {
        erro("Erro 22353! Data de expedição inválida!");
        exit();
    }
    
    $data_atual = new DateTime(date("Y-12-31"));
    $data_nasc = new DateTime(reverte_data($_POST['data_nascimento']));
    $intervalo = $data_atual->diff($data_nasc);

    /*
    $anos_vida = (int)$intervalo->format('%Y');

    if($anos_vida < 19)
    {
        erro("Erro 6780! A idade mínima é de 19 anos");
        exit();
    }
    */
    if($certificado == 'ci')
    {
        erro("Erro 23458! Quem possui Certificado de Isenção não pode se inscrever");
        exit();
    }
   
    ////// Tempo de serviço público
    /*
    $anos_servico_publico = 0;
    $meses_servico_publico = 0;
    $dias_servico_publico = 0;
    $anos_servico_publico = (int)$tempo_sv_mil_anos; //(int)$tempo_sv_pub_anos + (int)$tempo_sv_mil_anos;
    $meses_servico_publico = (int)$tempo_sv_mil_meses; //(int)$tempo_sv_pub_meses + (int)$tempo_sv_mil_meses;
    $dias_servico_publico = (int)$tempo_sv_mil_dias; //(int)$tempo_sv_pub_dias + (int)$tempo_sv_mil_dias;
    
    if($dias_servico_publico > 30)
        $meses_servico_publico ++;
    if($meses_servico_publico >= 12)
        $anos_servico_publico ++;
    
    if($anos_servico_publico > 5)
    {
        erro("Erro 42343! Não é permitido a inscrição de quem possuir 5 anos de serviço militar ou mais!");
        exit();
    }
    */
    if($tempo_sv_mil_anos > 6)
    {
        erro("Erro 45723! Não é permitido a inscrição de quem possuir mais de 6 anos de serviço militar!");
        exit();
    }
    
    //////////////////////////////////////////////////////////////
    ///////////
    /////////    SOMENTE PARA EIPOT
    //////////
    //////////////////////////////////////////////////////////////
    
    if(isset($_SESSION['eipot']) && $_SESSION['eipot'] === 1)
    {
        if($ativa_reserva == "nunca_foi_militar")
        {
            erro("Erro 324637475! Esta seleção é permitida apenas para quem fez EIPOT (CPOR ou NPOR)!");
            exit();
        }

        if($sexo != 'masculino')
        {
            erro("Erro 2322221! Esta seleção é permitida apenas o segmento masculino!");
            exit();
        }
        if($curso_graduacao == null)
        {
            erro("Erro 326326436! O campo Curso de graduação é obrigatório!");
            exit();
        }
        if($curso_graduacao == "outro")
        {
            erro("Erro 1346326346! O seu curso de graduação deve ser da área de interesse do Exército como consta no Edital!");
            exit();
        }
        if($arma_eipot == "outra" || $arma_eipot == "")
        {
            erro("Erro 3262347457! Nenhuma outra arma além das disponibilizadas para seleção tem vagas disponíveis!");
            exit();
        }
        if($ano_formacao_ofor == null)
        {
            erro("Erro 2363426747! O ano de formação do OFOR é obrigatório!");
            exit();
        }
        if($ano_formacao_ofor > date('Y') || $ano_formacao_ofor < 1980)
        {
            erro("Erro 367437457! O ano de formação do OFOR é inválido!");
            exit();
        }
        if($nota_ofor > 10 || $nota_ofor < 0)
        {
            erro("Erro 25325346! A nota do OFOR é inválida! Deve ser menor ou igual a 10");
            exit();
        }
    }
    
    //////////////////////////////////////////////////////////////
    ///////////
    /////////    SOMENTE PARA MFDV
    //////////
    //////////////////////////////////////////////////////////////
    $num_dependentes  = null;
    $prioridade_forca = null;
    $voluntario_12rm  = null;
    
    
    
    if($_SESSION['selecao_codigo'] == 'mfdv')
    {
        
        if($_POST['nome_ie'] != '')
            $nome_instituto_ensino = htmlspecialchars(trim($_POST['nome_ie']));
        if($nome_instituto_ensino == null || $nome_instituto_ensino == '')
        {
            erro("Erro 4373487358! O nome da Instituição de ensino é obrigatório!");
            exit();
        }
        if($_POST['ano_formacao'] != '')
            $ano_formacao = (int)htmlspecialchars(trim($_POST['ano_formacao']));
        if($ano_formacao == null || $ano_formacao == '')
        {
            erro("Erro 4373487358! O ano de formação é obrigatório!");
            exit();
        }
        if($_POST['uf_ie'] != '')
            $uf_instituto_ensino = htmlspecialchars(trim($_POST['uf_ie']));
        if($uf_instituto_ensino == null || $uf_instituto_ensino == '')
        {
            erro("Erro 4373487358! A UF da Instituição de Ensino é obrigatória!");
            exit();
        }
        if($_POST['cidade_ie'] != '')
            $cidade_instituto_ensino = (int)htmlspecialchars(trim($_POST['cidade_ie']));
        if($cidade_instituto_ensino == null || $cidade_instituto_ensino == '')
        {
            erro("Erro 4373487358! A Cidade da Instituição de Ensino é obrigatória!");
            exit();
        }
        
        if(!isset($_POST['num_dependentes']))
        {
            erro("Erro 346364! O número de dependentes é obrigatório!");
            exit();
        }
        
        if(!isset($_SESSION['6_regiao']))
        {
            if(!isset($_POST['prioridade_forca']))
            {
                erro("Erro 57868! A prioridade da força é obrigatório!");
                exit();
            }
            
            if($_POST['prioridade_forca'] != "")
            $prioridade_forca = htmlspecialchars(trim($_POST['prioridade_forca']));
            
            if(!isset($_POST['voluntario_12rm']))
            {
                erro("Erro 34653465! A opção de SIM ou NÃO para voluntário em servir na 12ª Região Militar é obrigatória!");
                exit();
            }
            if($_POST['voluntario_12rm'] != "")
            $voluntario_12rm = htmlspecialchars(trim($_POST['voluntario_12rm']));
            
            if($prioridade_forca == null || $voluntario_12rm == null)
            {
                erro("Erro 7890789! Os campos Prioridade da força e voluntário para 12ª RM são obrigatórios!");
                exit();
            }
        }
        
        if($_POST['num_dependentes'] != "")
            $num_dependentes = htmlspecialchars(trim($_POST['num_dependentes']));
        
        if($num_dependentes == null)
        {
            erro("Erro 7890789! O campo Número de dependentes é obrigatório!");
            exit();
        }
    }
    
    if(isset($_SESSION['6_regiao']))
    {
        if($_POST['cidade_etapas_presenciais_6'] == "" || $_POST['cidade_etapas_presenciais_6'] == null)
        {
            erro("Erro 235637457! O campo Cidade das Etapas Presenciais é obrigatório!");
            exit();
        }
        
        $cidade_etapas_presenciais = htmlspecialchars(trim($_POST['cidade_etapas_presenciais_6']));
    }
    
    if(isset($_SESSION['12_regiao']) && $_SESSION['selecao_regiao'] == 12)
    {
        if($_POST['cidade_etapas_presenciais12'] == "" || $_POST['cidade_etapas_presenciais12'] == null)
        {
            erro("Erro 3176742734! O campo Cidade das Etapas Presenciais é obrigatório!");
            exit();
        }
        
        $cidade_etapas_presenciais = htmlspecialchars(trim($_POST['cidade_etapas_presenciais12']));
    }

    
    // </editor-fold>
    
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
    {
        erro("Erro 674565! Você não tem permissão para realizar essa alteração!");
        exit();
    }
    
    if(!inscricao())
    {
        erro("Erro 345645! Não é possível realizar a edição, o prazo já foi encerrado!");
        exit();
    }
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 1523545! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 1465! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($sexo == "masculino" && $certificado == "nao_aplica")
    {
        erro("Erro 2743465395! Candidato do sexo masculino deve possuir um registro!");
        exit();
    }
    
    if(($certificado == "1crm" || $certificado == "2crm") && $posto_grad == '')
    {
        erro("Erro 34595! O campo Posto/Graduação não pode estar em branco quando o Certificado de reservista está selecionado!");
        exit();
    }
    
    $selecao = $conexao->get_selecao_id();
   
    if(count($selecao) == 0)
    {
        erro("Erro 56363246346!");
        exit();
    }
    
    if($selecao[0]['data_fim_inscricao'] == null)
    {
        erro("Erro 362362346342! A sua inscrição está fora do período!");
        exit();
    }
    if(strtotime(date("Y-m-d")) > strtotime($selecao[0]['data_fim_inscricao']))
    {
        erro("Erro 2362362436! As inscrições já finalizaram!");
        exit();
    }
    if(strtotime(date("Y-m-d")) < strtotime($selecao[0]['data_inicio_inscricao']))
    {
        erro("Erro 23642363246! As inscrições não iniciaram!");
        exit();
    }
    
    if($selecao[0]['data_maxima_nascimento'] != '' && $selecao[0]['data_maxima_nascimento'] != null)
    {
        if(strtotime($data_nascimento) <= strtotime($selecao[0]['data_maxima_nascimento']))
        {
            // CASO ESPECÍFICO DA SELEÇÃO MFDV 
            if(isset($_SESSION['mfdv']))
            {
                if(($posto_grad == '2_ten' || $posto_grad == '2_Ten' || $posto_grad == '1_ten' || $posto_grad == '1_Ten' || $posto_grad == 'asp') && $ativa_reserva == 'ja_foi_militar')
                {
                    $data_nasc = new DateTime($data_nascimento);
                    $intervalo = $data_atual->diff( $data_nasc );

                    $anos_vida = (int)$intervalo->format('%Y');
                    $meses_vida = (int)$intervalo->format('%m');
                    $dias_vida = (int)$intervalo->format('%d');
                    if($anos_vida >= 45)
                    {
                        erro("Erro 35783585678! A data máxima de nascimento foi atingida (Os candidatos que já fizeram o EAS não podem ter 45 anos ou mais!");
                        exit();
                    }
                }
                else
                {
                    erro("Erro 2346236246! A data máxima de nascimento foi atingida (".$selecao[0]['data_maxima_nascimento'].")!");
                    exit();
                }
            }
            else
            {
                erro("Erro 4575368548568! A data máxima de nascimento foi atingida (".$selecao[0]['data_maxima_nascimento'].")!");
                exit();
            }
        }
    }
    if($selecao[0]['data_minima_nascimento'] != '' && $selecao[0]['data_minima_nascimento'] != null)
    {
        if(strtotime($data_nascimento) >= strtotime($selecao[0]['data_minima_nascimento']))
        {
            erro("Erro 23623462346! A data mínima de nascimento não foi atingida (".$selecao[0]['data_minima_nascimento'].")!");
            exit();
        }
    }
    
    ////////////////////////////////////////////////////////
    // Edita Candidato
    //////////////////////////////////////////////////////
   
$rm_destino = NULL; 
$rm_inscricao = NULL;
    
if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    erro("Erro 4123474! Não foi possível fazer a atualização dos dados !"); 
    exit(); 
}

    if($_POST)
        $resultado = $conexao->edita_candidato( $_SESSION['id_usuario'],
                                                $nome_completo,
                                                $identidade ,
                                                $data_nascimento ,
                                                $nome_social ,
                                                $estado_civil ,
                                                $companheiro ,
                                                $dependentes ,
                                                $filiacao_pai ,
                                                $sexo ,
                                                $nascionalidade ,
                                                $naturalidade ,
                                                $filiacao_mae ,
                                                $uf ,
                                                $bairro ,
                                                $cidade ,
                                                $cep ,
                                                $rua ,
                                                $telefone ,
                                                $celular ,
                                                $mail ,
                                                $tempo_sv_pub ,
                                                $tempo_sv_pub_anos ,
                                                $tempo_sv_pub_meses ,
                                                $tempo_sv_pub_dias ,
                                                $tempo_sv_mil ,
                                                $tempo_sv_mil_anos ,
                                                $tempo_sv_mil_meses ,
                                                $tempo_sv_mil_dias ,
                                                $civil_militar ,
                                                $certificado ,
                                                $documento ,
                                                $data_expedicao ,
                                                $ativa_reserva ,
                                                $posto_grad ,
                                                $forca ,
                                                $arma ,
                                                $incorporacao ,
                                                $licenciamento,
                                                $num_dependentes,
                                                $prioridade_forca,
                                                $voluntario_12rm,
                                                $nome_instituto_ensino,
                                                $ano_formacao,
                                                $uf_instituto_ensino,
                                                $cidade_instituto_ensino,
                                                $cidade_etapas_presenciais,
                                                $curso_graduacao,
                                                $ano_formacao_ofor,
                                                $nota_ofor,
                                                $rm_destino,
                                                $rm_inscricao,
                                                $arma_eipot,
                                                $datetime);
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16101", "usuario", "Update", "Candidato atualizou os seus dados", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php");

        

?>