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

    public static function executeSQL($sql) {
        $conn = self::getConnection();
        if(!mysqli_query($conn, $sql)) {
            throw new Exception(mysqli_error($conn));
        }
        $id = $conn->insert_id;
        $conn->close();
        return $id;
    }
}