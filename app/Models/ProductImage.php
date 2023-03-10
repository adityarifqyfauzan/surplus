<?php

namespace App\Models;

use App\Events\ImageDeleting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'deleted' => ImageDeleting::class
    ];
}
