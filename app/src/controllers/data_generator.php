<?php

//controller responsável por gerar uma massa de dados para o sistema,
//populando a tabela working_hours

//removendo todos os registros da tabela working_hours
Database::executeSQL('DELETE FROM working_hours');

//removendo os registros da tabela users com id > 5
Database::executeSQL('DELETE FROM users WHERE id > 5');

//função que retorna um cenário de marcações baseada em probabilidade
//baseada nos pesos informados nos argumentos
//a soma dos pesos deve ser 100
function getDayTemplateByOdds($regularRate, $extraRate, $lazyRate) {
    
    //cenário de um dia normal
    $regularDayTemplate = [
        'time1' => '08:00:00',
        'time2' => '12:00:00',
        'time3' => '13:00:00',
        'time4' => '17:00:00',
        'worked_time' => DAILY_TIME
    ];
    
    //cenário de um dia com 1 hora extra
    $extraHourDayTemplate = [
        'time1' => '08:00:00',
        'time2' => '12:00:00',
        'time3' => '13:00:00',
        'time4' => '18:00:00',
        'worked_time' => DAILY_TIME + 3600
    ];
    
    //cenário de um dia com meia hora a menos
    $lazyDayTemplate = [
        'time1' => '08:30:00',
        'time2' => '12:00:00',
        'time3' => '13:00:00',
        'time4' => '17:00:00',
        'worked_time' => DAILY_TIME - 1800
    ];
    
    //obtendo um valor aleatório entre 0 e 100
    $value = rand(0, 100);
    
    //a partir do valor aleatório, é selecionado o cenário de marcações
    if($value <= $regularRate) {
        return $regularDayTemplate;
    } elseif($value <= $regularRate + $extraRate) {
        return $extraHourDayTemplate;
    } else {
        return $lazyDayTemplate;
    }
}

//função que popula a tabela working_hours
//recebe como argumentos: id do usuário, data inicial, pesos de probabilidade
function populateWorkingHours($userId, $initialDate, $regularRate, $extraRate, $lazyRate) {
    $currentDate = $initialDate;
    $yesterday = new DateTime();
    $yesterday->modify('-1 day');
    $columns = ['user_id' => $userId, 'work_date' => $currentDate];

    while(isBefore($currentDate, $yesterday)) {
        if(!isWeekend($currentDate)) {
            $template = getDayTemplateByOdds($regularRate, $extraRate, $lazyRate);
            $columns = array_merge($columns, $template);
            $workingHours = new WorkingHours($columns);
            $workingHours->insert();
        }
        $currentDate = getNextDay($currentDate)->format('Y-m-d');
        $columns['work_date'] = $currentDate;
    }
}

$lastMonth = strtotime('first day of last month');
populateWorkingHours(1, date('Y-m-1'), 70, 20, 10);
populateWorkingHours(3, date('Y-m-d', $lastMonth), 20, 75, 5);
populateWorkingHours(4, date('Y-m-d', $lastMonth), 20, 10, 70);

echo 'Tudo certo :)';