<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//--------------------------------------------------------------------------------
//显示文章和相应的评论
Route::get('/post/show/{post}', function (\App\Post $post) {
    $post->load('comments.owner');
    $comments = $post->getComments();
    if($comments->count()!==0) {
        $comments['root'] = $comments[''];
        unset($comments['']);
    }else{
        $comments='';
    }
    return view('posts.show', compact('post', 'comments'));
});

//用户进行评论
Route::post('post/{post}/comments', function (\App\Post $post) {
    $post->comments()->create([
        'body' => request('body'),
        'user_id' => \Auth::id(),
        'parent_id' => request('parent_id', null),
    ]);
    return back();
});
