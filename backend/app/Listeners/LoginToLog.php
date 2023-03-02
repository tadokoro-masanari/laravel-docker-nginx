<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Carbon\Carbon;
use App\Models\AuthHistory;

class LoginToLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //過去のアクセスログと比較
        $new_access = AuthHistory::where('user_agent',request()->userAgent())->first();

        AuthHistory::create(
            [
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'login_time' => Carbon::now()
            ]
        );

        if(is_null($new_access)){
            logger()->info('これまでにはない端末'.request()->userAgent().'からのアクセスです。');
        }
    }
}
