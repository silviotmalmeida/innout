<?php

//carregando arquivo de configuração principal (constantes, mapeamentos e requires)
//o dirname(__FILE__, 2) fornece o caminho do diretorio pai a partir do arquivo atual
//assim conseguimos acessar o src
require_once(dirname(__FILE__, 2) . '/src/config/config.php');

//para as rotas funcionarem corretamente, o servidor deve estar apontando para a pasta public
//obtendo a uri digitada sem parametros
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
//se a uri for /, vazia ou index.php, ela torna-se /day_records.php
if($uri === '/' || $uri === '' ||  $uri === '/index.php') {
    
    //considera day_records o controller inicial da aplicação
    $uri = '/day_records.php';
}

//chamando o controller referente à uri digitada
require_once(CONTROLLER_PATH . "/{$uri}");