<?php

namespace App\Listeners;

use App\Events\SellerOrderPickupWasScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snowfire;

class EmailSellerOrderPickupConfirmation
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
     * @param  SellerOrderPickupWasScheduled  $event
     * @return void
     */
    public function handle(SellerOrderPickupWasScheduled $event)
    {
        $seller_order = $event->seller_order;

        $data = array(
            'subject'           => 'Your have scheduled a pickup for ' . $seller_order->book()->title . '.',
            'to'                => $seller_order->seller()->primaryEmailAddress(),
            'first_name'        => $seller_order->seller()->first_name,
            'book_title'        => $seller_order->book()->title,
            'seller_order_id'   => $seller_order->id,
            'addressee'         => $seller_order->address->addressee,
            'address_line1'     => $seller_order->address->address_line1,
            'address_line2'     => $seller_order->address->address_line2,
            'city'              => $seller_order->address->city,
            'state_a2'          => $seller_order->address->state_a2,
            'zip'               => $seller_order->address->zip,
            'phone_number'      => $seller_order->address->phone_number,
            'time'              => date(config('app.datetime_format'), strtotime($seller_order->scheduled_pickup_time)),
            'pickup_code'       => $seller_order->pickup_code
        );

        $beautymail = app()->make(Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('emails.sellerOrder.pickupConfirmation', $data, function($message) use ($data)
        {
            $message
                ->to($data['to'])
                ->subject($data['subject']);
        });
    }
}