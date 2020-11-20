<?php

//função que valida se o usuário está logado
function requireValidSession($requiresAdmin = false) {

    //obtendo a variavel user da sessão
    $user = $_SESSION['user'];

    //se a variável não estiver inicializada,
    //ocorre o redirecionamento para a página de login
    if(!isset($user)) {

        //chamando o controller de login
        header('Location: login.php');
        exit();
    }
    //se a variável estiver setada,
    //porém...
    elseif($requiresAdmin && !$user->is_admin) {
        addErrorMsg('Acesso negado!');
        header('Location: day_records.php');
        exit();
    }
}