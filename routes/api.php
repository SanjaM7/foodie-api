<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return $data = ['version' => 1];
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['namespace' => 'Api\\V1\\'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('login', 'LoginController@login');

        Route::prefix('companies')->group(function () {
            Route::get('/', 'CompanyController@index');
            Route::get('{company}', 'CompanyController@show');
            Route::post('/', 'CompanyController@store')->middleware('role:admin');
            Route::patch('{company}', 'CompanyController@update')->middleware('role:admin');
            Route::delete('{company}', 'CompanyController@destroy')->middleware('role:admin');

            Route::prefix('/{company}/users')->group(function () {
                Route::get('/', 'UserController@index')->middleware('role:admin, producer_admin, customer_admin');
                Route::get('/{user}', 'UserController@show');
                Route::post('/', 'UserController@store')->middleware('role:admin');
                Route::patch('/{user}', 'UserController@update')->middleware('role:admin');
                Route::delete('/{user}', 'UserController@destroy')->middleware('role:admin');
            });
        });
    });

    Route::get('mealCategories', 'MealCategoryController@index');
    Route::get('mealCategories/create', 'MealCategoryController@create');
    Route::post('mealCategories', 'MealCategoryController@store')->middleware('role:producer_admin');
    Route::get('mealCategories/{id}', 'MealCategoryController@show');
    Route::get('mealCategories/{id}/edit', 'MealCategoryController@edit');
    Route::put('mealCategories/{id}', 'MealCategoryController@update')->middleware('role:producer_admin');
    Route::delete('mealCategories/{id}', 'MealCategoryController@destroy')->middleware('role:producer_admin');

});
