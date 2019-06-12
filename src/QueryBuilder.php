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

        var_dump ($this->sql, $this->bind);exit;
        
        return $this;
    }

    public function update(string $table, array $data)
    {
        $this->sql = "UPDATE `{$table}` SET name=?";
        return $this;
    }

    public function delete(string $table)
    {
        $this->sql = "DELETE FROM `{$table}`";
        return $this;
    }

    public function where(array $conditions)
    {

    }

    public function getData() :\stdClass
    {

    }
}