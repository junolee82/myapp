<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $article;

    public function __construct(\App\Article $article)
    {
        $this->article = $article;
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
