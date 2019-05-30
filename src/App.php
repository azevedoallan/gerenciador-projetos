<?php 

namespace SON\Framework;

use Pimple\Container;
use SON\Framework\Router;
use SON\Framework\Exceptions\HttpException;
use SON\Framework\Response;




class App 
{
    private $container;
    private $middlewares = [
        'before' => [],
        'after' => [],
    ];

    public function __construct (Container $container = null)
    {
        $this->container = $container;

        if ($this->container === null)
        {
            $this->container = new Pimple;
        }
    }

    public function getRouter()
    {
        if (!$this->container->offsetExists('router')) {
            $this->container['router'] = function () {
                return new Router;  
            };    
        }
        return $this->container['router'];
    }

    public function getResponse()
    {
        if (!$this->container->offsetExists('response')) {
            $this->container['response'] = function () {
                return new Response;  
            };    
        }
        return $this->container['response'];
    }

    public function getHttpErrorHandler()
    {
        if (!$this->container->offsetExists('httpErrorHandler')) {
            $this->container['httpErrorHandler'] = function ($c) {
                header('Content-Type: application/json'); 

                $response = json_encode([
                    'code' => $c['exception']->getCode(),
                    'error' => $c['exception']->getMessage()
                ]);

                return $response;
            };    
        }
        return $this->container['httpErrorHandler'];
    }

    public function middleware ($on, $callback)
    {
        $this->middlewares[$on][] = $callback;
    }

    public function run() 
    {   
        try {
            $result = $this->getRouter()->run();
            
            $response = $this->getResponse();
            
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
            $this->container['exception'] = $e;
            echo $this->getHttpErrorHandler();
        }
    }
}