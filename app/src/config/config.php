<?php
//arquivo principal de configuração
//responsavel por criar constantes e realizar os requires

//seleção de timezone e locale
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.uft-8', 'portuguese');

// Constantes gerais
define('DAILY_TIME', 60 * 60 * 8);

//Mapeamento de pastas
//models
define('MODEL_PATH', realpath(dirname(__FILE__) . '/../models'));
//views
define('VIEW_PATH', realpath(dirname(__FILE__) . '/../views'));
//template
define('TEMPLATE_PATH', realpath(dirname(__FILE__) . '/../views/template'));
//controllers
define('CONTROLLER_PATH', realpath(dirname(__FILE__) . '/../controllers'));
//exceptions
define('EXCEPTION_PATH', realpath(dirname(__FILE__) . '/../exceptions'));

//Mapeamento de arquivos para require
require_once(realpath(dirname(__FILE__) . '/database.php'));
require_once(realpath(dirname(__FILE__) . '/loader.php'));
require_once(realpath(dirname(__FILE__) . '/session.php'));
require_once(realpath(dirname(__FILE__) . '/date_utils.php'));
require_once(realpath(dirname(__FILE__) . '/utils.php'));
require_once(realpath(MODEL_PATH . '/Model.php'));
require_once(realpath(MODEL_PATH . '/User.php'));
require_once(realpath(MODEL_PATH . '/WorkingHours.php'));
require_once(realpath(EXCEPTION_PATH . '/AppException.php'));
require_once(realpath(EXCEPTION_PATH . '/ValidationException.php'));