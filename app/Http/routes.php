<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/test',['middleware' => 'guest', function(){
	return view('test');
}]);
Route::get('/admin','AdminController@getIndex');
Route::get('/{type?}', 'HomeController@getIndex');

Route::controllers([
	'api/usercollect' => 'API\UserCollectController',
	'api/usercomment' => 'API\UserCommentController',
	'api/comment' => 'API\ContentCommentController',
	'api/feedback' => 'API\FeedbackController',
	'api/message' => 'API\MessageController',
	'api/ads' => 'API\AdsController',
	'api' => 'ApiController',
]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	'admin' => 'AdminController',
	'home' => 'HomeController',
	'ads' => 'AdsController',
	'message' => 'MessageController',
	'app' => 'AppController',
	'comment' => 'CommentController',
	'feedback' => 'FeedbackController',
],[
	'middleware' => 'csrf'
]);