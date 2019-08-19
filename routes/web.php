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

$router->group(['middleware'=> 'api_sign'], function () use ($router){

	$router->post('/create_customer', 'CustomerController@createCustomer');

	$router->get('/deposit_channel', 'DepositChannelController@list');

	$router->post('/paylimit', 'DepositController@paylimit');
	
	$router->post('/pay', 'DepositController@pay');

});

