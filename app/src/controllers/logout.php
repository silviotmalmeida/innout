<?php

//iniciando a sessão
session_start();

//destruindo a sessão
session_destroy();

//redirecionando para o controller de login
header('Location: login.php');