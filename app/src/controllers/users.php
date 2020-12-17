<?php

//obtendo dados da sessão
session_start();

//validando a sessão de administrador
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession(true);

//inicializando a variável de exceções
$exception = null;

//se o atributo delete estiver setado em $_GET:
if(isset($_GET['delete'])) {
    try {
        
        //exclui o usuário com o id passado via $_GET no atributo delete
        User::deleteById($_GET['delete']);
        
        //insere no $_SESSION a mensagem de sucesso
        addSuccessMsg('Usuário excluído com sucesso.');
    }
    
    //se for capturada alguma exceção:
    catch(Exception $e) {
        
        //se existir a String 'FOREIGN KEY' na mensage de erro:
        if(stripos($e->getMessage(), 'FOREIGN KEY')) {
            
            //insere no $_SESSION a seguinte mensagem de erro
            addErrorMsg('Não é possível excluir o usuário com registros de ponto.');
        }
        
        //senão:
        else {
            
            //popula a variável de exceção com a mensagem do erro
            $exception = $e;
        }
    }
}

//obtendo todos os usuários cadastrados
$users = User::get();

//iterando sobre o arrau de usuários
foreach($users as $user) {
    
    //formatando a data de admissão
    $user->start_date = (new DateTime($user->start_date))->format('d/m/Y');    
    
    //se a data de desligamento estiver preenchida:
    if($user->end_date) {
        
        //formatando a data de desligamento
        $user->end_date = (new DateTime($user->end_date))->format('d/m/Y');
    }
}

//carregando a view, passando os atributos:
loadTemplateView('users', [
    
    //todos os usuários cadastrados
    'users' => $users,
    
    //variável de exceções
    'exception' => $exception
]);