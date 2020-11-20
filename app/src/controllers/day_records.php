<?php

//iniciando a sessão de usuário
session_start();

//validando a sessão
requireValidSession();

$date = (new Datetime())->getTimestamp();
$today = strftime('%d de %B de %Y', $date);
loadTemplateView('day_records', ['today' => $today]);