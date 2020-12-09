<?php

//model referente à tabela working_hours do banco de dados
class WorkingHours extends Model {
    
    //nome da tabela no banco de dados
    protected static $tableName = 'working_hours';
    
    //lista de atributos da tabela
    protected static $columns = [
        'id',
        'user_id',
        'work_date',
        'time1',
        'time2',
        'time3',
        'time4',
        'worked_time'
    ];

    //função que carrega os registros de ponto de um usuário em um determinado dia
    public static function loadFromUserAndDate($userId, $workDate) {
        
        //consultando o registro no banco de dados
        $registry = self::getOne(['user_id' => $userId, 'work_date' => $workDate]);

        //se não existir o registro,
        if(!$registry) {
            
            //cria um objeto de WorkingHours com os dados de marcação zerados
            $registry = new WorkingHours([
                'user_id' => $userId,
                'work_date' => $workDate,
                'worked_time' => 0
            ]);
        }
        //senão, cria um objeto de WorkingHours com os dados retornados do banco de dados

        return $registry;
    }

    //função que verifica qual o próxima marcação a ser efetuada
    public function getNextTime() {
        
        //se $time1 não estive nula, retorne a string time1
        if(!$this->time1) return 'time1';
        
        //se $time2 não estive nula, retorne a string time2
        if(!$this->time2) return 'time2';
        
        //se $time3 não estive nula, retorne a string time3
        if(!$this->time3) return 'time3';
        
        //se $time4 não estive nula, retorne a string time4
        if(!$this->time4) return 'time4';
        
        //senão retorne null
        return null;
    }

    public function getActiveClock() {
        $nextTime = $this->getNextTime();
        if($nextTime === 'time1' || $nextTime === 'time3') {
            return 'exitTime';
        } elseif($nextTime === 'time2' || $nextTime === 'time4') {
            return 'workedInterval';
        } else {
            return null;
        }
    }

    //função que executa a marcação do ponto
    //recebe como argumento um DateTime com a hora a ser registrada
    public function innout($time) {
        
        //verificando qual a próxima marcação a ser realizada
        $timeColumn = $this->getNextTime();
        
        //se a próxima marcação for null, prossegue:
        if(!$timeColumn) {
            
            //lança uma exceção
            throw new AppException("Você já fez os 4 batimentos do dia!");
        }
        
        //senão, popula a variável da próxima marcação dinamicamente
        $this->$timeColumn = $time;
       
        //popula a variavel com o total de tempo trabalhado
        $this->worked_time = getSecondsFromDateInterval($this->getWorkedInterval());
        
        //se o objeto possuir id, será realizado um update
        if($this->id) {
            $this->update();
        }
        
        //senão, será realizado um insert
        else {
            $this->insert();
        }
    }

    //função que calcula o total trabalhado no dia
    function getWorkedInterval() {
        
        //populando o arrray de marcações
        [$t1, $t2, $t3, $t4] = $this->getTimes();

        $part1 = new DateInterval('PT0S');
        $part2 = new DateInterval('PT0S');

        if($t1) $part1 = $t1->diff(new DateTime());
        if($t2) $part1 = $t1->diff($t2);
        
        
        
        if($t3) $part2 = $t3->diff(new DateTime());
        if($t4) $part2 = $t3->diff($t4);

        return sumIntervals($part1, $part2);
    }

    function getLunchInterval() {
        [, $t2, $t3,] = $this->getTimes();
        $lunchInterval = new DateInterval('PT0S');

        if($t2) $lunchInterval = $t2->diff(new DateTime());
        if($t3) $lunchInterval = $t2->diff($t3);

        return $lunchInterval;
    }

    function getExitTime() {
        [$t1,,, $t4] = $this->getTimes();
        $workday = DateInterval::createFromDateString('8 hours');

        if(!$t1) {
            return (new DateTimeImmutable())->add($workday);
        } elseif($t4) {
            return $t4;
        } else {
            $total = sumIntervals($workday, $this->getLunchInterval());
            return $t1->add($total);
        }
    }

    function getBalance() {
        if(!$this->time1 && !isPastWorkday($this->work_date)) return '';
        if($this->worked_time == DAILY_TIME) return '-';

        $balance = $this->worked_time - DAILY_TIME;
        $balanceString = getTimeStringFromSeconds(abs($balance));
        $sign = $this->worked_time >= DAILY_TIME ? '+' : '-';
        return "{$sign}{$balanceString}";
    }

    public static function getAbsentUsers() {
        $today = new DateTime();
        $result = Database::getResultFromQuery("
            SELECT name FROM users
            WHERE end_date is NULL
            AND id NOT IN (
                SELECT user_id FROM working_hours
                WHERE work_date = '{$today->format('Y-m-d')}'
                AND time1 IS NOT NULL
            )
        ");

        $absentUsers = [];
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($absentUsers, $row['name']);
            }
        }

        return $absentUsers;
    }

    public static function getWorkedTimeInMonth($yearAndMonth) {
        $startDate = (new DateTime("{$yearAndMonth}-1"))->format('Y-m-d');
        $endDate = getLastDayOfMonth($yearAndMonth)->format('Y-m-d');
        $result = static::getResultSetFromSelect([
            'raw' => "work_date BETWEEN '{$startDate}' AND '{$endDate}'"
        ], "sum(worked_time) as sum");
        return $result->fetch_assoc()['sum'];
    }

    public static function getMonthlyReport($userId, $date) {
        $registries = [];
        $startDate = getFirstDayOfMonth($date)->format('Y-m-d');
        $endDate = getLastDayOfMonth($date)->format('Y-m-d');

        $result = static::getResultSetFromSelect([
            'user_id' => $userId,
            'raw' => "work_date between '{$startDate}' AND '{$endDate}'"
        ]);

        if($result) {
            while($row = $result->fetch_assoc()) {
                $registries[$row['work_date']] = new WorkingHours($row);
            }
        }
        
        return $registries;
    }

    //fução que popula um array de strings com as marcações do dia
    private function getTimes() {
        
        //inicializando um array vazio
        $times = [];

        //populando o array com as marcações convertidas em string ou com valor null
        $this->time1 ? array_push($times, getDateFromString($this->time1)) : array_push($times, null);
        $this->time2 ? array_push($times, getDateFromString($this->time2)) : array_push($times, null);
        $this->time3 ? array_push($times, getDateFromString($this->time3)) : array_push($times, null);
        $this->time4 ? array_push($times, getDateFromString($this->time4)) : array_push($times, null);

        //retorna o array de strings
        return $times;
    }
}