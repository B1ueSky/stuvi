<?php

namespace App\Listeners;

use Aloha\Twilio\Twilio;
use App\Events\BuyerOrderWasPlaced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageBuyerOrderPlacedNotificationToStuvi
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
     * @param  BuyerOrderWasPlaced  $event
     * @return void
     */
    public function handle(BuyerOrderWasPlaced $event)
    {
        $buyer_order = $event->buyer_order;

        $twilio = new Twilio(
            config('twilio.twilio.connections.twilio.sid'),
            config('twilio.twilio.connections.twilio.token'),
            config('twilio.twilio.connections.twilio.from')
        );

        $phone_number = config('customer_service.phone');
        $message = 'Buyer order #' . $buyer_order->id . ' was placed!';

        $twilio->message($phone_number, $message);

        if (app()->environment() == 'production' && !env('APP_DEBUG')) {
            $twilio->message('8572064789', $message);
            $twilio->message('8572084775', $message);
        }
    }
}
