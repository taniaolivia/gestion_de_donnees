<?php

namespace hellokant\query;

use hellokant\connection\ConnectionFactory;

class Query
{
    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = '';

    private function __construct(string $table)
    {
        $this->sqltable = $table;
    }

    public static function table(string $table)
    {
        $query = new Query($table);
        return $query;
    }

    public function select(array $fields)
    {
        $this->fields = implode(',', $fields);
        return $this;
    }

    public function where(string $col, string $op, $val)
    {
        if(!is_null($this->where)) $this->where .= ' and ';
        $this->where .= ' '. $col . ' '. $op . ' ? ';
        $this->args[] = $val;
        return $this;
    }

    public function get()
    {
        $this->sql = 'select '. $this->fields . ' from '. $this->sqltable;

        if(!is_null($this->where))
        {
            $this->sql .= ' where '. $this->where;
        }

        $pdo = ConnectionFactory::getConnection();

        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function one()
    {
        $this->sql = 'select '. $this->fields . ' from '. $this->sqltable;

        if(!is_null($this->where))
        {
            $this->sql .= ' where '. $this->where;
        }

        $this->sql .= ' LIMIT 1';

        $pdo = ConnectionFactory::getConnection();

        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
        $fetchAll = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $fetchAll[0];
    }

    public function delete()
    {
        $this->sql = 'delete from '. $this->sqltable;

        if(!is_null($this->where))
        {
            $this->sql .= ' where '. $this->where;
        }

        $pdo = ConnectionFactory::getConnection();

        $stmt = $pdo->prepare($this->sql);
        return $stmt->execute($this->args);
    }

    public function insert(array $t)
    {
        $this->sql = 'insert into '. $this->sqltable;
        $into = [];
        $values = [];

        foreach($t as $attname => $attval)
        {
            $into[] = $attname;
            $values[] = ' ? ';
            $this->args[] = $attval;
        }

        $this->sql .= ' ('. implode(',', $into). ') '.
                      'values('. implode(',', $values).')';


        $pdo = ConnectionFactory::getConnection();

        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
        return (int)$pdo->lastInsertId($this->sqltable);
    }

    public function getQuery() : array
    {
        return ['query' => $this->sql,
                'args' => $this->args];
    }
}