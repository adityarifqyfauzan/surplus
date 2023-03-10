<?php

namespace App\Events;

use App\Models\ProductImage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ProductImage $product_image;

    /**
     * Create a new event instance.
     */
    public function __construct(ProductImage $product_image)
    {
        $this->product_image = $product_image;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
