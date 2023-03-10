<?php

namespace App\Listeners;

use App\Events\deleting;
use App\Events\ImageDeleting;
use App\Models\Image;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteImageIfNotAssignInProducts
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImageDeleting $event): void
    {
        $product_image = $event->product_image;
        $image = Image::find($product_image->image_id);
        $product_image = ProductImage::where("image_id", $image->id);
        if ($product_image->count() == 0) {
            $full_path = explode('/', $image->file);
            $filename = $full_path[5];
            $folder = $full_path[4];
            $is_deleted = Storage::disk('public')->delete($folder .'/' . $filename);
            if (!$is_deleted) {
                Log::alert(now() . ' unable to delete image [ID] : '. $image->id);
                return;
            }
            $image->delete();
        }
    }
}
