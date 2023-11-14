<?php
require_once 'libs/Router.php';
require_once 'app/controllers/api.controller.php';
require_once 'app/controllers/product.api.controller.php';

$router = new Router();

                //('endpoint','verbo HTTP','Controller','metodo')
$router->addRoute('products','GET','ProductApiController','get');
$router->addRoute('products/:ID','GET','ProductApiController','get');
$router->addRoute('products','POST','ProductApiController','add');
$router->addRoute('products/:ID','PUT','ProductApiController','update');
$router->addRoute('products/:ID','DELETE','ProductApiController','delete');
//Default route
$router->setDefaultRoute('ProductApiController', 'error');
$router->addRoute('error', 'GET', 'ProductApiController', 'error');


$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
