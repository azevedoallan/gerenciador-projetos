<?php 

namespace App\Models;
use Pimple\Container;
use SON\Framework\QueryBuilder;

class Users 
{
    private $db;
    private $events;
    private $queryBuilder;

    public function __construct(container $container)
    {
        $this->db = $container['db'];
        $this->events = $container['events'];
        $this->queryBuilder = new QueryBuilder;
    }

    public function get($id) 
    {
      $query = $this->queryBuilder->select('users')
      -> where(['id' => $id])
      ->getData();

      $stmt = $this->db->prepare($query->sql);
      $stmt->execute($query->bind);
      return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }

    public function all() 
    {
       $stmt = $this->db->prepare('SELECT * FROM `users`');
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC); 
    }
    
    public function create(array $data) {
      
      $this->events->trigger('creating.users', null, $data);

      $query = $this->queryBuilder->insert('users', $data)
        ->getData();

      $stmt = $this->db->prepare($query->sql);
      $stmt->execute($query->bind);
      $result = $this->get($this->db->lastInsertId()); 

      $this->events->trigger('created.users', null, $result);

      return $result;
    
    }

    public function update($id, array $data)
    {
      $this->events->trigger('updating.users', null, $data);

      $sql = 'UPDATE `users` SET name=? where id=?';

      $data = array_merge($data, [$id]);

      $stmt = $this->db->prepare($sql);
      $stmt->execute(array_values($data));

      $result = $this->get($id);

      $this->events->trigger('updated.users', null, $result);

      return $result;
    }

    public function delete($id)
    {
      $result = $this->get($id);
      
      $this->events->trigger('deleting.users', null, $result);

      $sql = "DELETE FROM `users` WHERE id=?";
      
      $stmt = $this->db->prepare($sql);
      
      $stmt->execute([$id]);

      $this->events->trigger('deleted.users', null, $result);
        
      return $result;
    }
}