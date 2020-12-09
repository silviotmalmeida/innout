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

    if($_POST['forcedTime']) {
        $currentTime = $_POST['forcedTime'];
    }

    //realizando a marcação do ponto usando a hora atual
    $records->innout($currentTime);
    
    //populando o atributo sucess no array message
    addSuccessMsg('Ponto inserido com sucesso!');
}

//caso ocorra alguma exceção, popula o atributo error no array message
catch(AppException $e) {
    addErrorMsg($e->getMessage());
}

//redireciona para o controller day_records
header('Location: day_records.php');