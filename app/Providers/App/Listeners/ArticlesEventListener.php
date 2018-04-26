<?php

namespace App\Providers\App\Listeners;

use App\Providers\App\Events\ArticlesEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{    
    public function __construct()
    {
        //
    }
    
    public function handle(\App\Providers\App\Events\ArticlesEvent $event)
    {
        if ($event->action === 'created') {
            \Log::info(sprintf(
                '새로운 포럼 글이 등록되었습니다.: %s',
                $event->article->title
            ));
        }
    }

}
