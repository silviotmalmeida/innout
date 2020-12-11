<?php

//obtendo dados da sessão
session_start();

//validando a sessão
//caso não esteja logado, a aplicação redireciona para a tela de login
requireValidSession();

//obtendo a data atual
$currentDate = new DateTime();

//obtendo as informações do usuário logado
$user = $_SESSION['user'];
$selectedUserId = $user->id;

//iniciando o array de usuários
$users = null;

//se o usuário logado for administrador do sistema:
if($user->is_admin) {
    
    //popula o array de usuários com todos os usuários do banco
    $users = User::get();
    
    //atribui o $selectedUserId para o usuário que estiver no array $_POST
    //se não existir, utiliza o id do usuário logado
    $selectedUserId = $_POST['user'] ? $_POST['user'] : $user->id;
}

//atribui o $selectedPeriod para o período que estiver no array $_POST
//se não existir, utiliza o mês e ano atuais
$selectedPeriod = $_POST['period'] ? $_POST['period'] : $currentDate->format('Y-m');

//iniciando o array de períodos
$periods = [];

//laço para coletar os nomes dos meses dos últimos dois anos
//iterando sobre os anos
for($yearDiff = 0; $yearDiff <= 2; $yearDiff++) {
    
    //definindo o ano
    $year = date('Y') - $yearDiff;
    
    //iterando sobre os meses
    for($month = 12; $month >= 1; $month--) {
        
        //obtendo o DateTime do referido mes e ano
        $date = new DateTime("{$year}-{$month}-1");
        
        //populando o array de períodos com as Strings formatadas
        $periods[$date->format('Y-m')] = strftime('%B de %Y', $date->getTimestamp());
    }
}

$registries = WorkingHours::getMonthlyReport($selectedUserId, $selectedPeriod);

$report = [];
$workDay = 0;
$sumOfWorkedTime = 0;
$lastDay = getLastDayOfMonth($currentDate)->format('d');

for($day = 1; $day <= $lastDay; $day++) {
    $date = $currentDate->format('Y-m') . '-' . sprintf('%02d', $day);
    $registry = $registries[$date];
    
    if(isPastWorkday($date)) $workDay++;

    if($registry) {
        $sumOfWorkedTime += $registry->worked_time;
        array_push($report, $registry);
    } else {
        array_push($report, new WorkingHours([
            'work_date' => $date,
            'worked_time' => 0
        ]));
    }
}

$expectedTime = $workDay * DAILY_TIME;
$balance = getTimeStringFromSeconds(abs($sumOfWorkedTime - $expectedTime));
$sign = ($sumOfWorkedTime >= $expectedTime) ? '+' : '-';

loadTemplateView('monthly_report', [
    'report' => $report,
    'sumOfWorkedTime' => getTimeStringFromSeconds($sumOfWorkedTime),
    'balance' => "{$sign}{$balance}",
    'selectedPeriod' => $selectedPeriod,
    'periods' => $periods,
    'selectedUserId' => $selectedUserId,
    'users' => $users,
]);