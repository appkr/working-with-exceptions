<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'exceptions'], function () {
    Route::get('domain', function () {
        throw new \App\Exceptions\DomainException([
            'foo' => 'bar', 'baz' => 'qux'
        ]);
    });

    Route::get('custom-domain', function () {
        throw new \App\Exceptions\CustomDomainException([
            'foo' => 'bar', 'baz' => 'qux'
        ]);
    });

    Route::get('http-domain', function () {
        throw new \App\Exceptions\HttpDomainException([
            'foo' => 'bar', 'baz' => 'qux'
        ]);
    });

    Route::get('custom-http', function () {
        throw (new \App\Exceptions\CustomHttpException([
            'foo' => 'bar', 'baz' => 'qux'
        ]))->setStatusCode(409);
    });

    Route::get('model-not-found', function () {
        throw (new \Illuminate\Database\Eloquent\ModelNotFoundException)
            ->setModel(\App\User::class, 1);
    });
});

