<?php

//função que insere na $_SESSION a mensagem de sucesso, passada por parâmetro
function addSuccessMsg($msg) {
    $_SESSION['message'] = [
        'type' => 'success',
        'message' => $msg
    ];
}

//função que insere na $_SESSION a mensagem de erro, passada por parâmetro
function addErrorMsg($msg) {
    $_SESSION['message'] = [
        'type' => 'error',
        'message' => $msg
    ];
}