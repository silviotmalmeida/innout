<?php

//obtendo dados da sessão
session_start();

//validando a sessão de administrador
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession(true);

//inicializando a variável de exceções
$exception = null;

//inicializando o array de informações do usuário
$userData = [];

//se o array $_POST estiver vazio e o atributo update do array $_GET estiver populado:
if(count($_POST) === 0 && isset($_GET['update'])) {
    
    //instanciando um objeto User cujo id foi recebido pelo atributo update do $_GET
    $user = User::getOne(['id' => $_GET['update']]);
    
    //populando o array de informações do usuário com os atributos do objeto
    $userData = $user->getValues();
    
    //apagando o valor da senha no array
    $userData['password'] = null;
    
}

//se existirem atributos no array $_POST
elseif(count($_POST) > 0) {
    try {
        
        //instanciando um novo User com os atributos recebidos via $_POST
        $dbUser = new User($_POST);
        
        //se foi recebido algum id via $_POST:
        if($dbUser->id) {
            
            //será realizado um update
            $dbUser->update();
            
            //insere no $_SESSION a mensagem de sucesso
            addSuccessMsg('Usuário alterado com sucesso!');
            
            //redireciona para o controller users.php
            header('Location: users.php');
            
            //encerra a execução
            exit();
            
        }
        
        //senão:
        else {
            
            //será realizado um insert
            $dbUser->insert();
            
            //insere no $_SESSION a mensagem de sucesso
            addSuccessMsg('Usuário cadastrado com sucesso!');
        }
        
        //apagando as informações do array $_POST
        $_POST = [];
        
    }
    
    //se for capturada alguma exceção:
    catch(Exception $e) {
        
        //popula a variável de exceção com a mensagem do erro
        $exception = $e;
        
    }
    
    //finalmente:
    finally {
        
       //popula o array de informações do usuário com o conteúdo de $_POST
       $userData = $_POST;
    }
}

//carregando a view, passando os atributos:
loadTemplateView('save_user', 
    
    //array de informações do usuário
    $userData
    
    + [
    
    //variável de exceções
    'exception' => $exception
    
]);
