<?php
require_once 'libs/Router.php';
require_once 'app/controllers/api.controller.php';
require_once 'app/controllers/product.api.controller.php';

$router = new Router();

                //('endpoint','verbo HTTP','Controller','metodo')
$router->addRoute('products','GET','ProductApiController','get');
$router->addRoute('product/:ID','GET','ProductApiController','get');
$router->addRoute('product','POST','ProductApiController','add');
$router->addRoute('product/:ID','PUT','ProductApiController','update');
$router->addRoute('product/:ID','DELETE','ProductApiController','delete');
//Default route
$router->setDefaultRoute('ProductApiController', 'default');
$router->addRoute('default', 'GET', 'ProductApiController', 'default');


$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
