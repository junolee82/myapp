<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', [
    'as' => 'root',
    'uses' => 'WelcomeController@index',
]);

Route::get('/home', [
    'as' => 'home',
    'uses' => 'HomeController@index',
]);

Route::resource('articles', 'ArticlesController');

Route::get('mail', function () {
    $article = App\Article::with('user')->find(1);

    return Mail::send(
        'emails.articles.created',
        compact('article'),
        function ($message) use ($article) {
            $message->to('ljhting@naver.com');
            $message->subject('새 글이 등록되었습니다. -' . $article->title);
        }
    );
});

/* Markdown Viewer */
Route::get('docs/{file?}', 'DocsController@show');
Route::get('docs/images/{image}', 'DocsController@image')
    ->where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');

//--------------------------------------------------------------------------

/* 사용자 가입 */
Route::get('auth/register', [
    'as' => 'users.create',
    'uses' => 'UsersController@create'
]);
Route::post('auth/register', [
    'as' => 'users.store',
    'uses' => 'UsersController@store',
]);
Route::get('auth/confirm/{code}', [
    'as' => 'users.confirm',
    'uses' => 'UsersController@confirm'
]);

/* 사용자 인증 */
Route::get('auth/login', [
    'as' => 'sessions.create',
    'uses' => 'SessionsController@create',
]);
Route::post('auth/login', [
    'as' => 'sessions.store',
    'uses' => 'SessionsController@store',
]);
Route::get('auth/logout', [
    'as' => 'sessions.destroy',
    'uses' => 'SessionsController@destroy',
]);

/* 비밀번호 초기화 */
Route::get('auth/remind', [
    'as' => 'remind.create',
    'uses' => 'PasswordsController@getRemind',
]);
Route::post('auth/remind', [
    'as' => 'remind.store',
    'uses' => 'PasswordsController@postRemind',
]);
Route::get('auth/reset/{token}', [
    'as' => 'reset.create',
    'uses' => 'PasswordsController@getReset',
])->where('token', '[\pL-\pN]{64}');
Route::post('auth/reset', [
    'as' => 'reset.store',
    'uses' => 'PasswordsController@postReset',
]);

/* Social Login */
Route::get('social/{provider}', [
    'as' => 'social.login',
    'uses' => 'SocialController@execute',
]);
