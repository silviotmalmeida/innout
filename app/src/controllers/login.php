<?php
//inicio do processo de login

//realizando o require do model login
loadModel('Login');

//iniciando a sessão
session_start();

//iniciando a variável de exceção como vazia
$exception = null;

//verificando se existe alguma informação recebida do formulário
//na primeira iteração o array $_POST estará vazio
if(count($_POST) > 0) {

    //caso existam dados, instancia o model com os dados
    $login = new Login($_POST);

    try {

        //realizando a validação dos dados
        $user = $login->checkLogin();

        //carregando o usuário na sessão
        $_SESSION['user'] = $user;

        //redirecionando para a página inicial
        header("Location: day_records.php");
    }

    //caso ocorram exceções, a variável de exceção é populada
    catch(AppException $e) {
        $exception = $e;
    }
}

//renderizando a view de login e passando dados de usuário e de exceção
loadView('login', $_POST + ['exception' => $exception]);