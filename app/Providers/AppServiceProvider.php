<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** 뷰 컴포저
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        if ($locale = request()->cookie('locale__myapp')) {
            app()->setLocale(\Crypt::decrypt($locale));
        }

        \Carbon\Carbon::setLocale(app()->getLocale());

        view()->composer('*', function ($view) {
            $allTags = \Cache::rememberForever('tags.list', function () {
                return \App\Tag::all();
            });

            $currentUser = auth()->user();
            $currentLocale = app()->getLocale();
            $currentUrl = current_url();

            $view->with(compact('allTags', 'currentUser', 'currentLocale', 'currentUrl'));

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
