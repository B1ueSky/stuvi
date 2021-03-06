<?php

namespace App\Listeners;

use Aloha\Twilio\Twilio;
use App\Events\SellerOrderWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageSellerOrderConfirmationToSeller
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
     * @param  SellerOrderWasCreated  $event
     * @return void
     */
    public function handle(SellerOrderWasCreated $event)
    {
        $seller_order = $event->seller_order;

        $twilio = new Twilio(
            config('twilio.twilio.connections.twilio.sid'),
            config('twilio.twilio.connections.twilio.token'),
            config('twilio.twilio.connections.twilio.from')
        );

        $phone_number = $seller_order->seller()->phone_number;
        $message = 'Schedule a pickup: Your textbook '.$seller_order->product->book->title.' posted on Stuvi was sold. '.
            'Please schedule a pickup at your convenience: '.
            url('/order/seller/' . $seller_order->id . '/schedulePickup');

        $twilio->message($phone_number, $message);
    }
}
