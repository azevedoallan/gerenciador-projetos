<?php

namespace App\Events;

class UsersCreated 
{
    public function __invoke($e)
    {
        var_dump($e);
        exit;
        $event = $e->getName();
        $params = $e->getParams();
        echo sprintf('Disparado event "%s", com parametros: %s', $event, json_encode($params));
    }
}