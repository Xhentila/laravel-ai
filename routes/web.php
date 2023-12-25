<?php

use Illuminate\Support\Facades\Route;
use App\AI\Chat;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**  Route::get('/', function () {

     $poem = (new Chat)
         ->systemMessage('You are a poetic assistant, skilled in explaining complex programming concepts with creative flair.')
         ->send('Compose a poem that explains the concept of recursion in programming');

return view('welcome', ['poem' => $poem]);
});*/

Route::get('/', function () {
    session(['file' => 'asfsdfasr.mp3']);
    return view('roast');
});

Route::post('/roast', function () {
    $attributes = request()->validate([
      'topic' => [
          'required', 'string', 'min:2', 'max:50'
    ]]);

    $prompt = "Please roast {$attributes['topic']} in a sarcastic tone.";

    $response = (new Chat())->send(
        message: $prompt,
        speech: true
    );

    file_put_contents(public_path($file = '/roast/'. md5($response). '.mp3'), $response);
    return redirect('/')->back()->with([
        'file'=>$file,
        'flash' =>'Boom. Roasted'
    ]);
});
