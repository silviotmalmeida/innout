<?php

//template responsável pelo desenho da mensagem de erro na tela de login

//inicializando o array de erros
$errors = [];

//se existirem mensagens na sessão
if(isset($_SESSION['message'])) {

    //salvando o conteúdo em um novo array
    $message = $_SESSION['message'];

    //apagando as mensagens da sessão
    unset($_SESSION['message']);
}
//se existirem exceções
elseif($exception) {

    //popula o array de mensagens
    $message = [
        'type' => 'error',
        'message' => $exception->getMessage()
    ];

    //se as exceções forem do tipo 'ValidationException'
    if(get_class($exception) === 'ValidationException') {
        //popula o array de erros
        $errors = $exception->getErrors();
    }
}
//variavel que define a classe css a ser utilizada
$alertType = '';

//se existirem mensagens de erro
if($message['type'] === 'error') {

    //classe css 'danger'
    $alertType = 'danger';
}
//senão
else {

    //clase css 'sucess'
    $alertType = 'success';
}
?>


<?php
    //se existirem mensagens imprime a div com a mensagem
    if($message): ?>
    <div role="alert"
        class="my-3 alert alert-<?= $alertType ?>">
        <?= $message['message'] ?>
    </div>
<?php endif ?>