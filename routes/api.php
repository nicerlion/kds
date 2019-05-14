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

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apiuser', 'API\IuserAPIController@index');



Route::get('/apiuser/{id}', 'API\IuserAPIController@show');

Route::get('/apiuserstype/{id}', 'API\IuserAPIController@getIusersById');

Route::get('/apiitem', 'API\ItemAPIController@index');
Route::get('/apiitem/{item_number}', 'API\ItemAPIController@getitembynoitem');
Route::get('/live-search/search/{term}/{page}', 'API\SearchAPIController@search');
Route::get('/live-search/search-iuser/{doc}', 'API\SearchAPIController@searchiuser');
Route::get('/live-search/search-iuser/{doc}/{id}', 'API\SearchAPIController@searchiuserdetail');
Route::get('/live-search/search-plate/{plate}', 'API\SearchAPIController@searchplate');
Route::get('/live-search/search-company/{name}/{id}', 'API\SearchAPIController@searchcompany');
Route::get('/live-search/search-bs/{name}/{id}', 'API\SearchAPIController@searchbs');
Route::get('/live-search/search-branch/{name}/{id}', 'API\SearchAPIController@searchbranch');
Route::get('/live-search/search-responsible/{type}/{name}/{id}', 'API\SearchAPIController@searchresponsible');
Route::get('/live-search/search-iusertype/{name}/{id}', 'API\SearchAPIController@searchiusertype');
