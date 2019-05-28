<?php 

namespace SON\Framework;

use SON\Framework\Response;
use SON\Framework\Exceptions;


class App 
{
    private $router;
    private $container;
    private $middlewares = [
        'before' => [],
        'after' => [],
    ];

    public function __construct ($router, $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function middleware ($on, $callback)
    {
        $this->middlewares[$on][] = $callback;
    }

    public function run() 
    {   
        try {
            $result = $this->router->run();
            
            $response = new Response;
            
            $params = [
                'container' => $this->container,
                'params' => $result['params']
            ];
            
            foreach ($this->middlewares['before'] as $middleware) {
                $middleware($this->container);
            }
        
            $response($result['action'], $params);
            
           foreach ($this->middlewares['after'] as $middleware) {
                $middleware($this->container);
            }

        } catch (HttpException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}