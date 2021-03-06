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


/**
 * 存取款相关接口（与产品）
 */
$router->group(['middleware'=> 'api_sign'], function () use ($router){

	$router->post('/create_customer', 'CustomerController@createCustomer');

	$router->get('/deposit_channel', 'DepositChannelController@list');

	$router->post('/paylimit', 'DepositController@paylimit');
	
	$router->post('/pay', 'DepositController@pay');

	$router->get('/withdraw_channel', 'WithdrawChannelController@list');

    $router->get('/bank_list', 'bankCodeController@list');
    
    $router->get('/province_list', 'ProvinceController@list');

    $router->get('/province_cities', 'ProvinceController@getProvinceCities');
    
    $router->get('/cities_country', 'CitiesController@getCitiesCountry');
   
	$router->post('/customer_bank', 'CustomerController@bindCustomerBank');

	$router->get('/customer_bank', 'CustomerController@getCustomerBank');

	$router->post('/do_withdraw', 'WithdrawController@doWithdraw');

});

/**
 * 业务相关接口（与I上游支付平台）
 */

$router->post('/token', 'IBusinessController@generateToken');

$router->group(['middleware'=> 'i_auth'], function () use ($router){

	$router->get('/customer', 'IBusinessController@customer');
    
    $router->post('/create_bank_account', 'IBusinessController@createBankAccount');
    
    $router->delete('/delete_bank_account', 'IBusinessController@deleteBankAccount');

    $router->get('/deposit_record', 'IBusinessController@depositRecord');
    
    $router->get('/withdraw_record', 'IBusinessController@withdrawRecord');

    $router->put('/approve', 'IBusinessController@approve');
    
    $router->put('/pay', 'IBusinessController@pay');

});


