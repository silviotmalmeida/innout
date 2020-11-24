<?php

//função que valida se o usuário está logado
//a variavel $requiresAdmin refere-se à acesso exclusivo de administrador
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
    //porém a área é restrita e
    //o usuário não seja administrador
    elseif($requiresAdmin && !$user->is_admin) {

        //mostra mensagem de erro
        addErrorMsg('Acesso negado!');

        //retorna à página de registros do dia
        header('Location: day_records.php');
        exit();
    }
    
    //caso contrário a validação é aprovada.
}