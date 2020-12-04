<?php

//função que realiza o require de um model específico para o controller
function loadModel($modelName) {
    require_once(MODEL_PATH . "/{$modelName}.php");
}

//função que carrega a view e cria variáveis com os parâmetros recebidos pelo controller
//parâmetros devem ser passados como array chave=>valor
function loadView($viewName, $params = array()) {

    //se existirem parâmetros, prossegue
    if(count($params) > 0) {

        //varrendo o array
        foreach($params as $key => $value) {

            //se a chave não for vazia, prossegue
            if(strlen($key) > 0) {

                //cria as variáveis dinâmicamente a partir dos valores recebidos
                //essas variáveis serão repassadas à view
                ${$key} = $value;
            }
        }
    }

    //carrega a view com as variáveis carregadas
    require_once(VIEW_PATH . "/{$viewName}.php");
}

//função que carrega a view (com header, menu e footer) e cria variáveis com os parâmetros recebidos pelo controller
//parâmetros devem ser passados como array chave=>valor
function loadTemplateView($viewName, $params = array()) {

    //se existirem parâmetros, prossegue
    if(count($params) > 0) {

        //varrendo o array
        foreach($params as $key => $value) {

            //se a chave não for vazia, prossegue
            if(strlen($key) > 0) {

                //cria as variáveis dinâmicamente a partir dos valores recebidos
                //essas variáveis serão repassadas à view
                ${$key} = $value;
            }
        }
    }

    //obtendo o usuário da sessão
    $user = $_SESSION['user'];

    //obtendo os dados de marcação do usuário no dia atual
    $workingHours = WorkingHours::loadFromUserAndDate($user->id, date('Y-m-d'));
    $workedInterval = $workingHours->getWorkedInterval()->format('%H:%I:%S');
    $exitTime = $workingHours->getExitTime()->format('H:i:s');
    $activeClock = $workingHours->getActiveClock();

    //estes dados serão repassados para as views e templates abaixo
    
    //carregando o header
    require_once(TEMPLATE_PATH . "/header.php");

    //carregando o menu lateral
    require_once(TEMPLATE_PATH . "/left.php");

    //carregando o conteudo da view
    require_once(VIEW_PATH . "/{$viewName}.php");

    //carregando o footer
    require_once(TEMPLATE_PATH . "/footer.php");
}

//funcao que carrega a div de título da página de acordo com os parâmetros informados
function renderTitle($title, $subtitle, $icon = null) {
    
    //carrega o template de título passando os parâmetros
    require_once(TEMPLATE_PATH . "/title.php");
}