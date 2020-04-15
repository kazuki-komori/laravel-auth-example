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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user', function (){
    $user = new \App\User();
    $user-> name = request()->input('name');
    $user->email = \request()->input('email');
    $user->password = sha1(\request()->input('password'));
    $user->save();
    return [
        'message'=>'ユーザーの作成が完了しました'
    ];
});

Route::post('/login', function (){
    $email = \request()->input('email');
    $password = \request()->input('password');
    $user = \App\User::where('email', $email)->first();
    if ($user){
        if ($user->password === sha1($password)){
            $token = new \App\UserToken();
            $token->user_id = $user->id;
            $token->token = Illuminate\Support\Str::random();
            $token->save();
            return [
                'message'=>'ログイン完了'
            ];
        }
    }
});

Route::get('/profile', function (){
    $token = \request()->bearerToken();
    $userToken = \App\UserToken::where('token', $token)->first();
    if ($userToken){
        $user = \App\User::where('id', $userToken->user_id)->first();
        return[
            'user'=>$user
        ];
    }
});
