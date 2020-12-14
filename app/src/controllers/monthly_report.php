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

//obtendo o array de WorkingHours do susuário selecionado no período selecionado
$registries = WorkingHours::getMonthlyReport($selectedUserId, $selectedPeriod);

//iniciando o array de dados a serem enviados para a view
$report = [];

//contador de dias trabalhados
$workDay = 0;

//somatório de segundos trabalhados
$sumOfWorkedTime = 0;

//obtendo o último dia do mês
$lastDay = getLastDayOfMonth($currentDate)->format('d');

//iterando sobre os dias do mês
for($day = 1; $day <= $lastDay; $day++) {
    
    //obtendo a data no formato aaaa-mm-dd
    $date = $currentDate->format('Y-m') . '-' . sprintf('%02d', $day);
    
    //obtendo o objeto WorkingHours da data referente à iteração atual
    $registry = $registries[$date];
    
    //se a data referenta à iteração atual está no passado e não é fim de semana,
    //incrementa o contador $workDay
    if(isPastWorkday($date)) $workDay++;

    //se existirem dados para a data referente à iteração atual:
    if($registry) {
        
        //incrementando o somátorio $sumOfWorkedTime
        $sumOfWorkedTime += $registry->worked_time;
        
        //adicionando os dados ao array de saída
        array_push($report, $registry);
    }
    
    //senão:
    else {
        
        //adiciona um objeto WorkingHours sem marcações no array de saída
        array_push($report, new WorkingHours([
            'work_date' => $date,
            'worked_time' => 0
        ]));
    }
}

//somatório de tempo trabalhado esperado
$expectedTime = $workDay * DAILY_TIME;

//saldo de horas absoluto em relação ao tempo esperado no formato hh:mm:ss
$balance = getTimeStringFromSeconds(abs($sumOfWorkedTime - $expectedTime));

//sinal do saldo + ou -
$sign = ($sumOfWorkedTime >= $expectedTime) ? '+' : '-';

//carregando a view, passando os atributos:
loadTemplateView('monthly_report', [
    
    //dados de marcação
    'report' => $report,
    
    //somatório de dados efetivamente trabalhados
    'sumOfWorkedTime' => getTimeStringFromSeconds($sumOfWorkedTime),
    
    //saldo de horas em relação ao tempo esperado com o sinal + ou -
    'balance' => "{$sign}{$balance}",
    
    //período selecionado
    'selectedPeriod' => $selectedPeriod,
    
    //array de meses dos últimos dois anos
    'periods' => $periods,
    
    //id do usuário selecionado
    'selectedUserId' => $selectedUserId,
    
    //array com todos os usuários cadastrados
    'users' => $users,
]);