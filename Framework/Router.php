<?php

namespace Blog\Framework;

use Blog\Exceptions\RouteNotFoundException;

/**
 * Represents an entity in database
 */
class Router
{
    protected $url;
    public    $request;
    protected $routes      = [];
    protected $namedRoutes = [];

    public function __construct(Request $request){
        $this->request = $request;
        $this->url     = $request->getUrl();
    }

    public function get(string $path, $callable, string $name = null){
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post(string $path, $callable, string $name = null){
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add(string $path, $callable, string $name = null, string $method){
        $route                   = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null){
            $name = $callable;
        }
        if($name){
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function run($view, $session, $config){
        if(!isset($this->routes[filter_input(INPUT_SERVER,'REQUEST_METHOD')])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[filter_input(INPUT_SERVER,'REQUEST_METHOD')] as $route){
            if($route->match($this->url)){
                return $route->call($view, $session, $config, $this);
            }
        }
        var_dump($this->url);
        throw new RouteNotFoundException("");
    }

    public function url(string $name, array $params = []){
        if(!isset($this->namedRoutes[$name])){
            throw new RouteNotFoundException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}