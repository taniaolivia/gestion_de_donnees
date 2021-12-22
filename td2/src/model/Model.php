<?php

namespace hellokant\model;

use hellokant\query\Query;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Model
{
    protected static $table;
    protected static $idColumn = 'id';

    protected $_v = [];
    protected static $query;

    public function __construct(array $t = null)
    {
        if (!is_null($t))
        {
            $this->_v = $t;
        }
    }

    public function __get(string $name)
    {
        if(array_key_exists($name, $this->_v))
        {
            return $this->_v[$name];
        }
        if(method_exists($this, $name))
        {
            return $this->$name();
        }

        $emess = get_class($this). ": unknown attribute $name (getAttribute)";
        throw new ModelNotFoundException($emess, 45);
    }

    public function __set($name, $val)
    {
        $this->_v[$name] = $val;
        return $this->_v[$name];
    }

    public function delete()
    {
        $oid = isset($this->_v[static::$idColumn]) ? $this->_v[static::$idColumn] : null;

        if(is_null($oid))
        {
            throw new ModelException(get_called_class().": Primary Key undefined : cannot delete");
        }
        static::$query = Query::table(static::$table);

        return static::$query
            ->where(static::$idColumn, '=', $oid)
            ->delete();
    }

    public function insert()
    {
        static::$query = Query::table(static::$table);

        $id = static::$query->insert($this->_v);
        $this->_v[static::$idColumn] = (int)$id;
        return $id;
    }

    public static function all() : array
    {
        static::$query = Query::table(static::$table);
        $all = static::$query->get();
        $return = [];
        foreach($all as $m)
        {
            $return[] = new static($m);
        }
        return $return;
    }

    public static function one(int $id)
    {
        static::$query = Query::table(static::$table);
        $row = static::$query->where(static::$idColumn, '=', $id)->one();

        return new static($row);
    }

    public static function find(...$args) : array
    {
        static::$query = Query::table(static::$table);

        $nbargs = count($args);

        if($nbargs === 0)
        {
            return static::all();
        }

        if(($nbargs === 1 && is_int($args[0])) ||
            ($nbargs === 2 && is_int($args[1])))
        {
            static::$query = static::$query->where(static::$idColumn, '=', ($nbargs === 1 ? $args[0]:$args[1]));
        }

        if(($nbargs === 1 && is_array($args[0])) ||
            ($nbargs === 2 && is_array($args[1])))
        {
            static::$query = static::$query->where(...($nbargs === 1 ? $args[0]:$args[1]));
        }

        if(($nbargs === 2 && is_array($args[0])))
        {
            static::$query = static::$query->select($args[0]);
        }

        $rows = static::$query->get();
        $return = [];
        foreach($rows as $m)
        {
            $return[] = new static($m);
        }
        return $return;
    }

    public static function first(...$args) : Model
    {
        $find = static::find(...$args);
        return $find[0];
    }

    public function belongsTo(string $m, string $fk)
    {
        $find = $m::find($this->$fk);
        return $find[0];
    }

    public function belongs_to(string $m, string $fk)
    {
        static::$query = Query::table($m::$table)
                    ->where($m::$idColumn, '=', $this->_v[$fk]);
        $row = static::$query->one();
        return new $m($row);
    }

    public function hasMany(string $m, string $fk)
    {
        return $m::find($fk, '=', $this->_v[static::$idColumn]);
    }

    public static function getLastQuery(string $m, string $fk)
    {
        return static::$query->getQuery();
    }
}