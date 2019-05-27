<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');

    Route::get('customers', 'API\CustomersController@pageList');
    Route::get('customers/list', 'API\CustomersController@all');
    Route::get('customers/{customer_id}', 'API\CustomersController@show')
        ->where(['customer_id' => '[0-9]+']);

    Route::post('customers/new', 'API\CustomersController@new');
    Route::post('customers/edit/{customer_id}', 'API\CustomersController@edit')
        ->where(['customer_id' => '[0-9]+']);
    // TODO: delete user

    Route::get('customers/{customer_id}/transactions', 'API\TransactionsController@getListByCustomer')
        ->where(['customer_id' => '[0-9]+']);

    Route::get('customers/{customer_id}/transactions/{transaction_id}', 'API\TransactionsController@getTransactionByCustomer')
        ->where(['customer_id' => '[0-9]+'])
        ->where(['transaction_id' => '[0-9]+']);

    Route::post('customers/{customer_id}/transactions/add', 'API\TransactionsController@addCustomerTransaction')
        ->where(['customer_id' => '[0-9]+']);

    Route::post('customers/{customer_id}/transactions/update', 'API\TransactionsController@updateCustomerTransaction')
        ->where(['customer_id' => '[0-9]+']);

    Route::post('customers/{customer_id}/transactions/delete', 'API\TransactionsController@deleteTransaction')
        ->where(['customer_id' => '[0-9]+']);

//    Route::post('customers/{customer_id}/transaction/update', 'API\TransactionsController@editCustomerTransaction')
//        ->where(['customer_id' => '[0-9]+']);
    ////////////
    Route::get('transactions', 'API\TransactionsController@getList');

    Route::get('transactions/{transaction_id}', 'API\TransactionsController@getTransaction')
        ->where(['transaction_id' => '[0-9]+']);

    ////////////
    Route::get('transactions/daily-sum', 'API\TransactionsController@getDailySum');

//    Route::post('transactions/add', 'API\TransactionsController@newTransaction');
//    Route::post('transactions/update', 'API\TransactionsController@editTransaction');
//    Route::post('transactions/delete', 'API\TransactionsController@deleteTransaction');


});