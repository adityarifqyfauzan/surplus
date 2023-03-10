<?php

namespace App\Repository;

use App\Models\ProductImage;

interface ProductImageRepository {
    public function findBy($criteria = []);
    public function findOneBy($criteria = []);
    public function create(ProductImage $product_image);
    public function delete(ProductImage $product_image);
}
