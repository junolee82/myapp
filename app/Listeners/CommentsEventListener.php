<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentsEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(CommentsEvent $event)
    {
        $comment = $event->comment; $comment->load('commentable');
        $to = $this->recipients($comment);

        if (! $to) {
            return;
        }

        $view = 'emails.comments.created';

        \Mail::send(
            $view,
            compact('comment'),
            function ($message) use($to) {
                $message->to($to);
                $message->subject(trans('emails.comments.created'));
            }
        );
    }

    private function recipients(\App\Comment $comment)
    {
        static $to = [];

        if ($comment->parent) {
            $to[] = $comment->parent->user->email;
            $this->recipients($comment->parent);
        }

        if ($comment->commentable->notification) {
            $to[] = $comment->commentable->user->email;
        }

        return array_unique($to);
    }
}
