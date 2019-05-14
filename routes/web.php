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

    // $user = Auth::user();
    // if($user->isAdmin()) {
    //     echo 'Administrator';
    // }
    // else{
    //     echo 'User';
    // }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Branch Routes
Route::get('branches', 'BranchController@index')->name('branchlist');

Route::get('items', 'ItemController@index')->name('itemlist');
Route::get('testquery','ItemController@testquery');
Route::get('item/create', 'ItemController@create');
Route::post('item','ItemController@store');
Route::get('item/createcollective', 'ItemController@createcollective');
Route::post('itemcollective','ItemController@storecollective');
Route::get('item/{id}/edit', 'ItemController@edit');
Route::get('item/{id}', 'ItemController@show')->name('itemdetail');
Route::put('item/{id}','ItemController@update');

Route::get('plate','ItemController@plate');

Route::get('admin/branches', 'AdminController@showbranches')->middleware('is_admin')->name('showbranches');
Route::get('admin/companies', 'AdminController@showcompanies')->middleware('is_admin')->name('showcompanies');
Route::get('admin/iusers', 'AdminController@showiusers')->middleware('is_admin')->name('showiusers');
Route::get('admin/bs', 'AdminController@showbs')->middleware('is_admin')->name('showbs');
Route::get('admin/responsibles', 'AdminController@showresponsibles')->middleware('is_admin')->name('showresponsibles');
Route::get('admin/iusertypes', 'AdminController@showiusertypes')->middleware('is_admin')->name('showiusertypes');
Route::get('admin/iusertype/create','AdminController@createiusertype')->middleware('is_admin');
Route::post('admin/iusertypes','AdminController@storeiusertype')->middleware('is_admin');
Route::get('admin/branch/create','AdminController@createbranch')->middleware('is_admin');
Route::get('iusers', 'IuserController@index')->name('iuserlist');
Route::get('admin/bs/create','AdminController@createbs')->middleware('is_admin');
Route::post('admin/branches','AdminController@storebranch')->middleware('is_admin');
Route::get('admin/company/create','AdminController@createcompany')->middleware('is_admin');
Route::post('admin/companies','AdminController@storecompany')->middleware('is_admin');
Route::post('admin/bs','AdminController@storebs')->middleware('is_admin');
Route::get('admin/responsible/create','AdminController@createresponsible')->middleware('is_admin');
Route::post('admin/responsible','AdminController@storeresponsible')->middleware('is_admin');
Route::get('admin/users', 'AdminController@showusers')->middleware('is_admin')->name('showusers');

Route::get('iusers/showexpiredsarlaft','IuserController@showexpiredsarlaft');
Route::get('iusers/report','IuserController@expirationreport');
Route::get('admin/records','RecordController@index')->middleware('is_admin');
Route::get('admin/records/report','RecordController@activityreport')->middleware('is_admin');


Route::get('admin/user/{id}/edit','AdminController@edituser')->middleware('is_admin')->name('editeditresponsible');
Route::put('admin/user/{id}','AdminController@updateuser')->middleware('is_admin');



Route::get('admin/responsible/{id}/edit','AdminController@editresponsible')->middleware('is_admin')->name('editeditresponsible');
Route::put('admin/responsible/{id}','AdminController@updateresponsible')->middleware('is_admin');
Route::get('admin/branch/{id}/edit','AdminController@editbranch')->middleware('is_admin')->name('editbranch');
Route::put('admin/branch/{id}','AdminController@updatebranch')->middleware('is_admin');
Route::get('admin/company/{id}/edit','AdminController@editcompany')->middleware('is_admin')->name('editcompany');
Route::put('admin/company/{id}','AdminController@updatecompany')->middleware('is_admin');
Route::get('admin/bs/{id}/edit','AdminController@editbs')->middleware('is_admin');
Route::put('admin/bs/{id}','AdminController@updatebs')->middleware('is_admin');
Route::get('admin/iusertype/{id}/edit','AdminController@editiusertype')->middleware('is_admin');
Route::put('admin/iusertype/{id}','AdminController@updateiusertype')->middleware('is_admin');

//Iusers Edition.  Half handled by User.  Full handled by Admin
Route::get('iuser/{id}/edit','IuserController@edit');
Route::put('iuser/{id}','IuserController@update');

//Route::get('admin/dashboard', 'AdminController@index');
// Route::get('admin/branch/{id}/deleteform','AdminController@deleteformbranch');
// Route::delete('admin/branch/{id}/delete','AdminController@destroybranch');
// Route::get('admin/company/{id}/deleteform','AdminController@deleteformcompany');
// Route::delete('admin/company/{id}/delete','AdminController@destroycompany');


Route::get('live-search', 'SearchController@index');

