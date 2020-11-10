<?php

//carregando arquivo de configuração principal (constantes, mapeamentos e requires)
//o dirname(__FILE__, 2) fornece o caminho do diretorio pai a partir do arquivo atual
//assim conseguimos acessar o src
require_once(dirname(__FILE__, 2) . '/src/config/config.php');

//para as rotas funcionarem corretamente, o servidor deve estar apontando para a pasta public

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if($uri === '/' || $uri === '' ||  $uri === '/index.php') {
    $uri = '/day_records.php';
}

require_once(CONTROLLER_PATH . "/{$uri}");