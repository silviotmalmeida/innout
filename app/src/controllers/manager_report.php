<?php

//obtendo dados da sessão
session_start();

//validando a sessão de administrador
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession(true);

//obtendo a quantidade de usuários ativos
$activeUsersCount = User::getActiveUsersCount();

//obtendo os nomes dos usuários ativos que ainda não registraram ponto no dia atual
$absentUsers = WorkingHours::getAbsentUsers();

//obtendo o mês e ano atuais
$yearAndMonth = (new DateTime())->format('Y-m');

//obtendo a quantidade total de segundos trabalhados no mês atual
$seconds = WorkingHours::getWorkedTimeInMonth($yearAndMonth);

//obtendo a quantidade de horas trabalhadas no mês atual
$hoursInMonth = explode(':', getTimeStringFromSeconds($seconds))[0];

//carregando a view, passando os atributos:
loadTemplateView('manager_report', [
    
    //quantidade de usuários ativos
    'activeUsersCount' => $activeUsersCount,
    
    //nomes dos usuários ativos que ainda não registraram ponto no dia atual
    'absentUsers' => $absentUsers,
    
    //quantidade de horas trabalhadas no mês atual
    'hoursInMonth' => $hoursInMonth,
]);