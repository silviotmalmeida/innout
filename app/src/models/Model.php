<?php

//classe mãe de todos os models
//possui os métodos genéricos para consumo das classes filhas
class Model {

    //definição dos atributos a serem detalhados nas classes filhas
    protected static $tableName = '';
    protected static $columns = [];
    protected $values = [];

    //método construtor
    function __construct($arr, $sanitize = true) {

        //carrega os atributos a partir de um array chave=>valor
        $this->loadFromArray($arr, $sanitize);
    }

    //função que valida e carrega os atributos a partir de um array chave=>valor
    public function loadFromArray($arr, $sanitize = true) {
        if($arr) {
            // $conn = Database::getConnection();
            foreach($arr as $key => $value) {
                $cleanValue = $value;
                if($sanitize && isset($cleanValue)) {
                    //
                    $cleanValue = strip_tags(trim($cleanValue));
                    //
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                    // $cleanValue = mysqli_real_escape_string($conn, $cleanValue);
                }
                $this->$key = $cleanValue;
            }
            // $conn->close();
        }
    }

    //função get genérica para um atributo
    //está sendo utilizado o método mágico __get para simplificar a manipulação
    //(ex: ao invés de $user->get('email'), usa-se $user->email
    public function __get($key) {
        return $this->values[$key];
    }

    //função set genérica para um atributo
    //está sendo utilizado o método mágico __set para simplificar a manipulação
    //(ex: ao invés de $user->set('email', 'email@email.com'), usa-se $user->email = 'email@email.com'
    public function __set($key, $value) {
        $this->values[$key] = $value;
    }

    //função get que retorna um array chave=>valor com os atributos
    public function getValues() {
        return $this->values;
    }

    public static function getOne($filters = [], $columns = '*') {
        $class = get_called_class();
        $result = static::getResultSetFromSelect($filters, $columns);
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    public static function get($filters = [], $columns = '*') {
        $objects = [];
        $result = static::getResultSetFromSelect($filters, $columns);
        if($result) {
            $class = get_called_class();
            while($row = $result->fetch_assoc()) {
                array_push($objects, new $class($row));
            }
        }
        return $objects;
    }

    //função que implementa uma select query, retornando o resultado
    //os filtros referem-se à clausula WHERE. Deve ser passado um array chave=>valor
    //as colunas referem-se aos atributos desejados. Deve ser passado uma string separada por virgulas
    public static function getResultSetFromSelect($filters = [], $columns = '*') {
        
        //construção do comando sql
        $sql = "SELECT ${columns} FROM "

            //o nome da tabela vem do atributo $tableName
            . static::$tableName

            //a validacao da clausula WHERE vem da funcao auxiliar getFilters
            . static::getFilters($filters);

        //realizando a consulta
        $result = Database::getResultFromQuery($sql);

        //retornando os resultados
        if($result->num_rows === 0) {
            return null;
        } else {
            return $result;
        }
    }

    public function insert() {
        $sql = "INSERT INTO " . static::$tableName . " ("
            . implode(",", static::$columns) . ") VALUES (";
        foreach(static::$columns as $col) {
            $sql .= static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ')';
        $id = Database::executeSQL($sql);
        $this->id = $id;
    }

    public function update() {
        $sql = "UPDATE " . static::$tableName . " SET ";
        foreach(static::$columns as $col) {
            $sql .= " ${col} = " . static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ' ';
        $sql .= "WHERE id = {$this->id}";
        Database::executeSQL($sql);
    }

    public static function getCount($filters = []) {
        $result = static::getResultSetFromSelect(
            $filters, 'count(*) as count');
        return $result->fetch_assoc()['count'];
    }

    public function delete() {
        static::deleteById($this->id);
    }

    public static function deleteById($id) {
        $sql = "DELETE FROM " . static::$tableName . " WHERE id = {$id}";
        Database::executeSQL($sql);
    }

    //função auxiliar que avalia os filtros a serem inseridos na cláusula WHERE
    private static function getFilters($filters) {
        
        //construção da cláusula WHERE        
        $sql = '';

        //só será construída caso existam filtros
        if(count($filters) > 0) {

            
            $sql .= " WHERE 1 = 1";
            foreach($filters as $column => $value) {
                if($column == 'raw') {
                    $sql .= " AND {$value}";
                } else {
                    $sql .= " AND ${column} = " . static::getFormatedValue($value);
                }
            }
        } 
        return $sql;
    }

    private static function getFormatedValue($value) {
        if(is_null($value)) {
            return "null";
        } elseif(gettype($value) === 'string') {
            return "'${value}'";
        } else {
            return $value;
        }
    }
}