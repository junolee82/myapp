<?php

namespace App\Listeners;

use App\Events\illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsersEventListener
{    
    public function __construct()
    {
        //
    }
    
    public function handle(Login $event)
    {
        $event->user->last_login = \Carbon\Carbon::now();

        return $event->user->save();
    }

    public function subscribe(\illuminate\Events\Dispatcher $event)
    {
        $event->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );

        $event->listen(
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }

    public function onUserCreated(\App\Events\UserCreated $event)
    {
        $user = $event->user;
        \Mail::send('emails.auth.confirm', compact('user'), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(sprintf('[%s] 회원 가입을 확인해 주세요.', config('app.name')));
        });
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event)
    {
        \Mail::send('emails.passwords.reset', ['token' => $event->token], function ($message) use ($event) {
            $message->to($event->email);
            $message->subject(sprintf('[%s] 비밀번호를 초기화하세요.', config('app.name')));
        });
    }
}
