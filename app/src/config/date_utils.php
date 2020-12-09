<?php

//diversas funções utilitárias de data

//função que retorna um objeto DateTime a partir de uma string
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

//função que retorna um DateTime referente à soma de dois intervalos de tempo
function sumIntervals($interval1, $interval2) {
    
    //criando um objeto DateTime zerado
    $date = new DateTime('00:00:00');
    
    //adicionando os intervalos de tempo
    $date->add($interval1);
    $date->add($interval2);
    
    //retornando um Datetime com a diferença de tempo
    return (new DateTime('00:00:00'))->diff($date);
}

function subtractIntervals($interval1, $interval2) {
    $date = new DateTime('00:00:00');
    $date->add($interval1);
    $date->sub($interval2);
    return (new DateTime('00:00:00'))->diff($date);
}

function getDateFromInterval($interval) {
    return new DateTimeImmutable($interval->format('%H:%i:%s'));
}

function getDateFromString($str) {
    return DateTimeImmutable::createFromFormat('H:i:s', $str);
}

function getFirstDayOfMonth($date) {
    $time = getDateAsDateTime($date)->getTimestamp();
    return new DateTime(date('Y-m-1', $time));
}

function getLastDayOfMonth($date) {
    $time = getDateAsDateTime($date)->getTimestamp();
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