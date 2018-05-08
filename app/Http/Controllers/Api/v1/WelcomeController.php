<?php

namespace App\Http\Controllers\Api\v1;
use \App\Http\Controllers;

class WelcomeController extends Controller
{
    /**
     * Say hello to visitors.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index() {
        return response()->json([
            'name' => config('app.name').' API',
            'message' => 'This is a base endpoint of v1 API',
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route(\Route::currentRouteName())
                ],
                [
                    'rel' => 'api.v1.articles',
                    'href' => route('api.v1.articles.index')
                ],
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Set locale.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function locale()
    {
        $cookie = cookie()->forever('locale__myapp', request('locale'));

        cookie()->queue($cookie);

        return ($return = request('return'))
            ? redirect(urldecode($return))->withCookie($cookie)
            : redirect('/')->withCookie($cookie);
    }
}
