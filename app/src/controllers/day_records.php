<?php

//controller inicial da aplicação

//iniciando a sessão de usuário
session_start();

//validando a sessão
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession();

//obtendo a data de hoje
$date = (new Datetime())->getTimestamp();

//obtendo a data por extenso
$today = strftime('%d de %B de %Y', $date);

//carregando a view, com os dados horários de hoje
loadTemplateView('day_records', ['today' => $today]);