<?php

namespace App\Config;

use App\Entity\Route\HttpMethod;

class Route{

    private static array $routes;

    public static function create() : void
    {
        self::$routes = [];
        foreach(HttpMethod::cases() as $http_method)
        {
            self::$routes[$http_method->value] = [];
        }
    }

    public static function register(HttpMethod $http_method, string $path, callable $action) : void
    {
        if(array_key_exists($path, self::$routes[$http_method->value]))
        {
            throw new \Exception("Path already exist on this http method");
        }

        self::$routes[$http_method->value][$path] = $action;
    }

    public static function run() : void
    {
        if(!self::searchPath())
        {
            \http_response_code(404);
            throw new \Exception("Page not found : 404");
        }
        self::$routes[$_SERVER["REQUEST_METHOD"]][self::searchPath()](self::getArgumentValue());
    }

    private static function searchPath() : string | bool
    {
        $current_path_names = explode("/", $_GET["url"]);
        foreach(self::$routes[$_SERVER["REQUEST_METHOD"]] as $path => $action)
        {
            $path_names = explode("/", $path);
            $i = 0; $founded = true;
            if(count($path_names) == count($current_path_names))
            {
                while($i < count($path_names) && $founded)
                {
                    if(!str_starts_with($path_names[$i], ":"))
                    {
                        if($path_names[$i] != $current_path_names[$i])
                        {
                            $founded = false;
                        }
                    }
                    $i++;
                }   
                if($founded) return $path;
            }
        }
        return false;
    }

    private static function getArgumentValue() : array
    {
        $current_path_names = explode("/", $_GET["url"]);
        $path_keys = \explode("/", self::searchPath());
        $args = [];
        for($i = 0; $i < count($path_keys); $i++)
        {
            if(\str_starts_with($path_keys[$i], ":"))
            {
                $args[\trim($path_keys[$i], ":")] = $current_path_names[$i]; 
            }
        }
        return $args;
    }
}

?>