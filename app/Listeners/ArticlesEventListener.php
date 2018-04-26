<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticlesEventListener
{
    
    public function __construct()
    {
        //
    }
    
    public function handle(\App\Events\ArticleCreated $event)
    {
        var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
        var_dump($event->article->toArray());
    }
}
