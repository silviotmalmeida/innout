<?php

//diversas funções utilitárias de data

//função que retorna um objeto DateTime a partir de uma string no formato aaaa-mm-dd
//caso seja informado um DateTime, ela retornará o próprio DateTime
function getDateAsDateTime($date) {
    return is_string($date) ? new DateTime($date) : $date;
}

//função que verifica se uma determonada data é um fim de semana
//retorna true ou false
function isWeekend($date) {
    $inputDate = getDateAsDateTime($date);
    return $inputDate->format('N') >= 6;
}

//função que verifica se uma data é anterior a outra
//retorna true ou false
function isBefore($date1, $date2) {
    $inputDate1 = getDateAsDateTime($date1);
    $inputDate2 = getDateAsDateTime($date2);
    return $inputDate1 <= $inputDate2;
}

//função que retorna um DateTime referente ao dia posterior ao informado no argumento
function getNextDay($date) {
    $inputDate = getDateAsDateTime($date);
    $inputDate->modify('+1 day');
    return $inputDate;
}

//função que retorna um DateInterval referente à soma de dois intervalos de tempo
//recebe dois objetos DateInterval com intervalos de tempo
function sumIntervals($interval1, $interval2) {
    
    //criando um objeto DateTime zerado
    $date = new DateTime('00:00:00');
    
    //adicionando os intervalos de tempo
    $date->add($interval1);
    $date->add($interval2);
    
    //retornando um DateInterval com a soma de intervalos    
    return (new DateTime('00:00:00'))->diff($date);
}

//função que retorna um DateInterval referente à diferença de dois intervalos de tempo
//recebe dois objetos DateInterval com intervalos de tempo
function subtractIntervals($interval1, $interval2) {
    
    //criando um objeto DateTime zerado
    $date = new DateTime('00:00:00');
    
    //adicionando o primeiro intervalo de tempo
    $date->add($interval1);
    
    //subtraindo o segundo intervalo de tempo
    $date->sub($interval2);
    
    //retornando um DateInterval com a diferença de intervalos
    return (new DateTime('00:00:00'))->diff($date);
}

//função que converte um DateInterval em um DateTime
function getDateFromInterval($interval) {
    return new DateTimeImmutable($interval->format('%H:%i:%s'));
}

//função que converte uma String no formato hh:mm:ss em um DateTime
function getDateFromString($str) {
    return DateTimeImmutable::createFromFormat('H:i:s', $str);
}

//função que retorna um DateTime com o primeiro dia do mês de uma determinada data
function getFirstDayOfMonth($date) {
    
    //convertendo a data aaaa-mm-dd em DateTime e depois em time
    $time = getDateAsDateTime($date)->getTimestamp();
    
    //retornando o primeiro dia do mês
    return new DateTime(date('Y-m-1', $time));
}

//função que retorna um DateTime com o último dia do mês de uma determinada data
function getLastDayOfMonth($date) {
    
    //convertendo a data aaaa-mm-dd em DateTime e depois em time
    $time = getDateAsDateTime($date)->getTimestamp();
    
    //retornando o último dia do mês
    return new DateTime(date('Y-m-t', $time));
}

//calcula a quantidade de segundos a partir de um 
function getSecondsFromDateInterval($interval) {
    $d1 = new DateTimeImmutable();
    $d2 = $d1->add($interval);
    return $d2->getTimestamp() - $d1->getTimestamp();
}

function isPastWorkday($date) {
    return !isWeekend($date) && isBefore($date, new DateTime());
}

function getTimeStringFromSeconds($seconds) {
    $h = intdiv($seconds, 3600);
    $m = intdiv($seconds % 3600, 60);
    $s = $seconds - ($h * 3600) - ($m * 60);
    return sprintf('%02d:%02d:%02d', $h, $m, $s);
}

function formatDateWithLocale($date, $pattern) {
    $time = getDateAsDateTime($date)->getTimestamp();
    return strftime($pattern, $time);
}