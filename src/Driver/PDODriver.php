<?php

namespace DBTiny\Driver;

use DBTiny\Driver;

abstract class PDODriver extends Driver
{
    /**
     * @var \PDO
     */
    private $pdo;
    private $transactionCounter = 0;

    protected function connect($dsn, $username, $password, array $options = [])
    {
        $this->pdo = new \PDO($dsn, $username, $password, $options);

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(
            \PDO::ATTR_STATEMENT_CLASS,
            [PDOStatement::class, []]
        );
    }

    public function getServerVersion()
    {
        return $this->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    public function execute($query)
    {
        return $this->pdo->exec($query);
    }

    public function query($query, array $params = null)
    {
        if (empty($params)) {
            return $this->pdo->query($query);
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);

        return $statement;
    }

    public function prepare($query, array $options = [])
    {
        return $this->pdo->prepare($query, $options);
    }

    public function beginTransaction()
    {
        if (!$this->transactionCounter++) {
            return $this->pdo->beginTransaction();
        }

        return $this->transactionCounter >= 0;
    }

    public function commit()
    {
        if (--$this->transactionCounter === 0) {
            return $this->pdo->commit();
        }

        return $this->transactionCounter >= 0;
    }

    public function rollBack()
    {
        if ($this->transactionCounter >= 0) {
            $this->transactionCounter = 0;

            return $this->pdo->rollBack();
        }
        $this->transactionCounter = 0;

        return false;
    }

    public function quoteLiteral($literal, $type = null)
    {
        return $this->pdo->quote($literal, $type ? : \PDO::PARAM_STR);
    }

    public function setAttribute($name, $value)
    {
        return $this->pdo->setAttribute($name, $value);
    }

    public function getAttribute($name)
    {
        return $this->pdo->getAttribute($name);
    }

    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }
}
