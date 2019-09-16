<?php

namespace App\Listeners;

use App\Events\DepositEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\customer;

class DepositCustomerListener
{
    /**
     * Handle the event.
     *
     * @param  DepositEvent  $event
     * @return void
     */
    public function handle(DepositEvent $event)
    {
        $customer = Customer::find($event->order->customer_id);
        $customer->deposit_count += $event->order->amount;
        $customer->save();
    }
}
