<?php 

namespace App\Models;
use Pimple\Container;

class Users 
{
    private $db;

    public function __construct(container $container)
    {
        $this->db = $container['db'];
        $this->events = $container['events'];
    }
    public function get($id) 
    {
       $stmt = $this->db->prepare('SELECT * FROM `users` WHERE id=?');
       $stmt->execute([$id]);
       return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }
    
    public function create(array $data) {
      
      $this->events->trigger('creating.users', null, $data);
      //banco de dados
      $this->events->trigger('created.users', null, $data);
      
        
    }
}