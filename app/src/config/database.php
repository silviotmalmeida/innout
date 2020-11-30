<?php

//Classe que vai encapsular toda a lógica de conexão ao banco de dados
class Database {

    //função que cria a conexão
    public static function getConnection() {
        
        //lendo informações do env.ini
        //o dirname(__FILE__) fornece o caminho do arquivo atual
        $envPath = realpath(dirname(__FILE__) . '/../env.ini');
        $env = parse_ini_file($envPath);

        //criando a conexão
        $conn = new mysqli($env['host'], $env['username'],
            $env['password'], $env['database']);

        //Em caso de erro entra neste laço, encerra a execucao e exibe a mensagem de erro
        if($conn->connect_error) {
            die("Erro: " . $conn->connect_error);
        }

        return $conn;
    }

    //função que realiza uma consulta e retorna o resultado
    public static function getResultFromQuery($sql) {

        //criando a conexão
        $conn = self::getConnection();

        //realizando a consulta
        $result = $conn->query($sql);

        //fechando a conexão
        $conn->close();
        
        return $result;
    }

    //função que executa uma query e retorna o id de inserção,
    //caso seja uma query de inserção, senão retorna nulo
    public static function executeSQL($sql) {
        
        //criando a conexão
        $conn = self::getConnection();
        
        //executando a query
        //caso ocorra algum erro, lança uma exceção
        if(!mysqli_query($conn, $sql)) {
            throw new Exception(mysqli_error($conn));
        }
        
        //obtendo o id, caso seja uma operação de inserção
        $id = $conn->insert_id;
        
        //fechando a conexão
        $conn->close();
        
        return $id;
    }
}