<?php

namespace App\Repository;

use App\Models\Product;

interface ProductRepository {
    public function findBy($criteria = [], $page, $size);
    public function findOneBy($criteria = []);
    public function create(Product $product): object;
    public function update(Product $product): object;
    public function count($criteria = []): int;
}
