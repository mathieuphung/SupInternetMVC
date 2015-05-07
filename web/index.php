<?php
require __DIR__ . '/../vendor/autoload.php';

/**
 * Use Yaml components for load a config routing, $routes is in yaml app/config/routing.yml :
 *
 * Url will be /index.php?p=route_name
 *
 *
 */

use Symfony\Component\Yaml\Parser;

$yaml = new Parser();

$routes = $yaml->parse(file_get_contents('../app/config/routing.yml'));
//ControllerClassName, end name is ...Controller
if(isset($_GET['p'])) {
    $currentroute = $routes[$_GET['p']]['controller'];
    $routes_array = explode(':', $currentroute);

    $controller_class = $routes_array[0];
    //ActionName, end name is ...Action
    $action_name = $routes_array[1];
    $controller = new $controller_class();
    //$Request can by an object
    $request['request'] = &$_POST;
    $request['query'] = &$_GET;
    //...
    //$response can be an object
    $response = $controller->$action_name($request);

    /**
     * Use Twig !
     */
    //require $response['view'];
}

