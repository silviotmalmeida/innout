<?php

//iniciando a sessão de usuário
session_start();

//validando a sessão
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession();

//obtendo dados do usuário logado
$user = $_SESSION['user'];

//obtendo os registros de marcação do usuário no dia atual
$records = WorkingHours::loadFromUserAndDate($user->id, date('Y-m-d'));

try {
    
    //obtendo a hora atual
    $currentTime = strftime('%H:%M:%S', time());

    //caso o array $_POST tenha sido populado com um horário simulado, prossegue
    //o formato de preenchimento é hh:mm:ss
    if($_POST['forcedTime']) {
        
        //popula a variável com o horário simulado
        $currentTime = $_POST['forcedTime'];
    }

    //realizando a marcação do ponto usando a hora atual
    $records->innout($currentTime);
    
    //populando o atributo message na sessão
    addSuccessMsg('Ponto inserido com sucesso!');
}

//caso ocorra alguma exceção, popula o atributo message na sessão
catch(AppException $e) {
    addErrorMsg($e->getMessage());
}

//redireciona para o controller day_records
header('Location: day_records.php');

