<?php

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        if(!self::$sessionStarted){
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get ($name, $default = null)
    {
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
        return $default;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function clear()
    {
        $_SESSION = array();
    }

    public function regenerate($destroy = true)
    {
        if(!self::$sessionIdRegenerated){
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated  = true;
        }
    }

    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);
        
        $this->regenerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }

    public function run()
    {
        $params = $this->router->resolve($this->request->getPathInfo());
        if($params === false) {
            // todo-A
        }

        $controller = $params['controller'];
        $action = $params['action'];

        $this->runAction($controller, $action, $params);

        $this->response->send();
    }

    public function runAction($controller_name, $action, $params = array())
    {
        $controller_class = ucfirst($controller_name) .'Controller';

        $controller = $this->findController($controller_class);
        if($controller === false){
            // todo-b
        }

        $content = $controller->run($action, $params);
        $this->response->setContent($content);
    }

    protected function findController($controller_class)
    {
        if(!class_exists($controller_class)){
            $controller_file = $this->getControllerDir() . '/' .$controller_class .'.php';
            if(!is_readable($controller_file)){
                return false;
            }else{
                require_once Rcontroller_file;
                
                if(!class_exists($controller_class)){
                    return false;
                }
            }
        }
        return new $controller_class($this);
    }
}