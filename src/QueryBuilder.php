<?php 

namespace SON\Framework;

class QueryBuilder
{
    private $sql;
    private $bind = [];

    public function select(string $table)
    {
        $this->sql = "SELECT * FROM `{$table}`";
        return $this;
    }

    public function insert(string $table, array $data)
    {
        $sql = "INSERT INTO `{$table}` (%s) VALUES (%s)";

        $colums = array_keys($data);
        $values = array_fill(0, count($colums), '?');
        $this->bind = array_values($data);

        $this->sql = sprintf($sql, implode(', ', $colums), implode(', ', $values));
                
        return $this;
    }

    public function update(string $table, array $data)
    {
        $this->sql = "UPDATE `{$table}` SET %s";
        
        $colums = array_keys($data);
        
        foreach ($colums as &$column) {
            $column = $column . '=?';
        }
        
        $this->bind = array_values($data);
        
        $this->sql = sprintf($sql, implode(', ', $colums));

    }

    public function delete(string $table)
    {
        $this->sql = "DELETE FROM `{$table}`";
        return $this;
    }

    public function where(array $conditions)
    {
        if (!$this->sql) {
            throw new Exception("select(), update() or delete() is required before where() method");
        }

        $colums = array_keys($conditions);

        foreach ($colums as &$column) {
            $column = $column . "=?";
        }

        $this->bind = array_merge($this->bind, array_values($conditions));

        $this->sql .= ' WHERE ' . implode('and ', $colums);

        return $this;
    }

    public function getData() :\stdClass
    {
        $query = new \stdClass;
        $query->sql = $this->sql;
        $query->bind = $this->bind;

        $this->sql = null;
        $this->bind = [];

        return $query;
        
    }
}