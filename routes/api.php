<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\ConnectionFactoryFactory;
use Interop\Amqp\AmqpTopic;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/message", function (Request $request) {
    $message = $_POST['message'];
    $mqService = new \App\Services\RabbitMQService();
    $mqService->publish($message);
    return view('welcome');
});


