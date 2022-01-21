<?php

abstract class Repository
{
    protected PDO $pdo;

    protected function __construct(string $path)
    {
        $this->pdo = new PDO($path);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    protected function doQuery(string $query)
    {
        $statement = $this->pdo->query($query);
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    }

    public abstract static function getInstance(): Repository;
}