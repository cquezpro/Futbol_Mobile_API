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

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    // if ($request->wantsJson())
    //     return response([
    //         'status_code' => 200,
    //         'text'        => 'This is API for Fubolconnect Project.',
    //     ], 200);

    return 'entro';
});

Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/test', function () {
//    $user = \App\User::find(5);

    /*
     * reset_psw
send_new_psw
    */
//    $user->notify(new \App\Notifications\UserSendPhoneCode($user->confirmed_code, 'send_new_psw', str_random(10)));

    return 'Send message';
    /*return (new \App\Mail\UserValidationCode($user, 'send_new_psw', str_random(10)))->render();*/
});