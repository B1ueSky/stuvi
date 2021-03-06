<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\SellerOrder;

class SellerOrderPickupWasScheduled extends Event
{
    use SerializesModels;

    public $seller_order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SellerOrder $seller_order)
    {
        $this->seller_order = $seller_order;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
