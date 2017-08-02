<?php

use Illuminate\Http\Request;
use App\User;
//use Response;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('cors')->get('userName', function() {
    return Response::json('Mr. Hands');
});

/*Route::get('userName', ['middleware' => 'cors', function () {
  return Response::json('Mr. Hands');
}]);*/

/*Route::post('post-route', function (Request $request) {
  return $request->all();
});*/

Route::middleware('cors')->get('publish', function() {
    Redis::publish('test-channel', json_encode(['doo' => 'goo']));
});

Route::middleware('cors')->post('SignUp', 'UserController@store');
//Route::post('SignUp', 'store@UserController');

Route::get('redis', function () {
    print_r(app()->make('redis'));    
});

//Route::get('foo', 'UserController@foo');
/*Route::get('foo', function () {
    // Retrieve a piece of data from the session...
    $value = session('id_address');
    return $value;

});*/
Route::get('showStuff', 'UserController@showStuff');
//Route::middleware('cors')->get('foo', 'UserController@foo');

Route::get('processTest', 'UserController@process_test');

Route::get('processTestTwo', 'UserController@process_test_two');

Route::get('showImage', 'UserController@showImage');


Route::post('login', 'UserController@SignIn');
//Route::post('signup', 'UserController@store');

Route::middleware('cors')->post('signup', 'UserController@foo');

// Event routes
Route::get('eventsignup', 'EventController@signUp');

Route::get('test', 'UserController@test');



Route::any('{path?}', 'MainController@index')->where("path", ".+");

