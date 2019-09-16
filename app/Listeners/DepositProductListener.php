<?php

namespace App\Listeners;

use App\Events\DepositEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositProductListener
{
    /**
     * Handle the event.
     *
     * @param  DepositEvent  $event
     * @return void
     */
    public function handle(DepositEvent $event)
    {
        //
    }
}
