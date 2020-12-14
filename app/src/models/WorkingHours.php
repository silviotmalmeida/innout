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

    //função que verifica qual a próxima marcação a ser efetuada
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

    //função que define qual dos relógios (workingInterval ou exitTime) será atualizada a cada segundo
    public function getActiveClock() {
        
        //obtendo qual a próxima marcação a ser efetuada
        $nextTime = $this->getNextTime();
        
        //se a próxima marcação for time1 ou time 3:
        if($nextTime === 'time1' || $nextTime === 'time3') {
            
            //o relógio a ser atualizado é o exitInterval
            return 'exitTime';
            
        }
        
        //se a próxima marcação for time2 ou time 4:
        elseif($nextTime === 'time2' || $nextTime === 'time4') {
            
            //o relógio a ser atualizado é o workedInterval
            return 'workedInterval';
            
        }
        
        //senão:
        else {
            
            //retorne null
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
    //retorna um DateInterval    
    function getWorkedInterval() {
        
        //populando o arrray de marcações com DateTime
        [$t1, $t2, $t3, $t4] = $this->getTimes();

        //Criando um DateInterval zerado referente ao periodo da manhã
        $part1 = new DateInterval('PT0S');
        
        //Criando um DateInterval zerado referente ao periodo da tarde
        $part2 = new DateInterval('PT0S');
        
        //calculando $part1
            //se $t1 estiver definido, $part1 recebe a diferença ao horário atual
            if($t1) $part1 = $t1->diff(new DateTime());
            //se $t2 estiver definido, $part1 recebe a diferença entre $t1 e $t2
            if($t2) $part1 = $t1->diff($t2);  
        
        //calculando $part2    
            //se $t3 estiver definido, $part2 recebe a diferença ao horário atual
            if($t3) $part2 = $t3->diff(new DateTime());
            //se $t4 estiver definido, $part2 recebe a diferença entre $t3 e $t4
            if($t4) $part2 = $t3->diff($t4);

        //retorna o DateInterval referente à soma entre $part1 e $part2
        return sumIntervals($part1, $part2);
    }

    //função que calcula o tempo usado para almoço
    //retorna um DateInterval
    function getLunchInterval() {
        
        //populando o arrray de marcações com DateTime
        //somente são populados o $t2 e $t3
        [, $t2, $t3,] = $this->getTimes();
        
        //Criando um DateInterval zerado
        $lunchInterval = new DateInterval('PT0S');
        
        //calculando $lunchInterval
            //se $t2 estiver definido, $lunchInterval recebe a diferença ao horário atual
            if($t2) $lunchInterval = $t2->diff(new DateTime());
            //se $t3 estiver definido, $lunchInterval recebe a diferença entre $t2 e $t3
            if($t3) $lunchInterval = $t2->diff($t3);

        //retorna o DateInterval referente ao tempo usado para almoço
        return $lunchInterval;
    }

    //função que calcula o horário mínimo para saída
    //retorna um DateTime
    function getExitTime() {
        
        //populando o arrray de marcações com DateTime
        //somente são populados o $t1 e $t4
        [$t1,,, $t4] = $this->getTimes();
        
        //Criando um DateInterval com o expediente padrão    
        $workday = DateInterval::createFromDateString('8 hours');

        //se o $t1 estiver nulo:
        if(!$t1) {
            
            //retorna um DateTime com a hora atual incrementada com o expediente padrão
            return (new DateTimeImmutable())->add($workday);
        }
        //se o $t4 estiver marcado:
        elseif($t4) {
            
            //retorna o horário de saída verificado
            return $t4;
        }
        //senão:
        else {
            
            //calcula o somatório entre o expediente padrão e o tempo utilizado para almoço
            $total = sumIntervals($workday, $this->getLunchInterval());

            //retorna o tempo de $t1 incrementado de $total
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

    //função que retorna um array de WorkingHours de um determinado usuário em um determinado mês
    public static function getMonthlyReport($userId, $date) {
        
        //inicializando o array de registros
        $registries = [];
        
        //definindo o primeiro dia do mês e formatando como aaaa-mm-dd
        $startDate = getFirstDayOfMonth($date)->format('Y-m-d');
        
        //definindo o último dia do mês e formatando como aaaa-mm-dd
        $endDate = getLastDayOfMonth($date)->format('Y-m-d');

        //realizando a consulta ao banco de dados
        $result = static::getResultSetFromSelect([
            'user_id' => $userId,
            'raw' => "work_date between '{$startDate}' AND '{$endDate}'"
        ]);
        
        //se existirem resultados:
        if($result) {
            
            //populando um array de objetos WorkingHours com a chave sendo a data
            while($row = $result->fetch_assoc()) {
                $registries[$row['work_date']] = new WorkingHours($row);
            }
        }
        
        //retornando o array de objetos WorkingHours
        return $registries;
    }

    //função que popula um array de DateTime com as marcações do dia
    private function getTimes() {
        
        //inicializando um array vazio
        $times = [];

        //populando o array com as marcações convertidas em DateTime ou com valor null
        $this->time1 ? array_push($times, getDateFromString($this->time1)) : array_push($times, null);
        $this->time2 ? array_push($times, getDateFromString($this->time2)) : array_push($times, null);
        $this->time3 ? array_push($times, getDateFromString($this->time3)) : array_push($times, null);
        $this->time4 ? array_push($times, getDateFromString($this->time4)) : array_push($times, null);

        //retorna o array de DateTime
        return $times;
    }
}