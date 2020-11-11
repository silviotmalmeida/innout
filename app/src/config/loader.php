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

                //cria e inicializa uma variável dinâmica a partir do valor recebido
                ${$key} = $value;
            }
        }
    }

    //carrega a view
    require_once(VIEW_PATH . "/{$viewName}.php");
}

function loadTemplateView($viewName, $params = array()) {

    if(count($params) > 0) {
        foreach($params as $key => $value) {
            if(strlen($key) > 0) {
                ${$key} = $value;
            }
        }
    }

    $user = $_SESSION['user'];
    $workingHours = WorkingHours::loadFromUserAndDate($user->id, date('Y-m-d'));
    $workedInterval = $workingHours->getWorkedInterval()->format('%H:%I:%S');
    $exitTime = $workingHours->getExitTime()->format('H:i:s');
    $activeClock = $workingHours->getActiveClock();

    require_once(TEMPLATE_PATH . "/header.php");
    require_once(TEMPLATE_PATH . "/left.php");
    require_once(VIEW_PATH . "/{$viewName}.php");
    require_once(TEMPLATE_PATH . "/footer.php");
}

function renderTitle($title, $subtitle, $icon = null) {
    require_once(TEMPLATE_PATH . "/title.php");
}