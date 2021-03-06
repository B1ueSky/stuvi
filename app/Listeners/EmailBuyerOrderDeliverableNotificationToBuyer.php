<?php

namespace App\Listeners;

use App\Events\BuyerOrderWasDeliverable;
use App\Helpers\Email;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailBuyerOrderDeliverableNotificationToBuyer
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
     * @param  BuyerOrderWasDeliverable  $event
     * @return void
     */
    public function handle(BuyerOrderWasDeliverable $event)
    {
        $buyer_order = $event->buyer_order;

        $email = new Email(
            $subject = 'Schedule a delivery for your Stuvi order',
            $to = $buyer_order->buyer->primaryEmailAddress(),
            $view = 'emails.buyerOrder.deliverable',
            $data = [
                'first_name'        => $buyer_order->buyer->first_name,
                'buyer_order'    => $buyer_order
            ]
        );

        $email->send();
    }
}
