<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});




$router->get('info1',function (){
   phpinfo();
});
//测试
$router->get('/user/zhangsan','Index\UserController@User');
$router->get('/test/login','Index\UserController@Login');
$router->post('/test/ucenter','Index\UserController@uCenter');
$router->get('/test','Index\UserController@test');

$router->get('/test/aaa','Index\UserController@aaa');
$router->get('/test/bbb','Index\UserController@bbb');

$router->get('/test/ccc','Index\UserController@ccc');

$router->get('/test/ddd','Index\UserController@ddd');

$router->get('/login','Index\TestController@login');
