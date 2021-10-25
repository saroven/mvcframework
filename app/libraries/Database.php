<?php

class Database
{
    private $dbHost = DB_HOST;
    private $dbName = DB_NAME;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;

    private $statement;
    private $dbHandler;
    private $error;

    public function __construct()
    {
        $conn = 'mysql:host='.$this->dbHost . ';dbname=' .$this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
        }catch (PDOException $e){
            $this->error = $e->getMessage();

            echo $this->error;
        }
    }
    //write query
    public function query($sql)
    {
        $this->statement = $this->dbHandler->prepare($sql);
    }
    //bind params
    public function bind($parameter, $value, $type = null)
    {
        switch (is_null($type)){
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($parameter, $value, $type);
    }
    //execute the prepared statement
    public function execute()
    {
        return $this->statement->execute();
    }
    
    //return array
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }
    
    //return single row as an object
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }
    //gets the row count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
}