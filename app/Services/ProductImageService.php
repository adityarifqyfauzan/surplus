<?php

namespace App\Services;

use App\Models\ProductImage;
use App\Repository\ProductImageRepository;

class ProductImageService extends Service implements ProductImageRepository
{
    public function findBy($criteria = []) {
        $product_images = ProductImage::where($criteria)->get();

        return $product_images;
    }

    public function findOneBy($criteria = []) {
        $product_image = ProductImage::where($criteria)->first();

        return $product_image;
    }

    public function create(ProductImage $product_image) {
        if ($product_image->save()) {
            return $this->return(true, $product_image);
        }
        return $this->return(false);
    }

    public function delete(ProductImage $product_image) {
        if ($product_image->delete()) {
            return $this->return(true);
        }
        return $this->return(false);
    }
}
