<?php

namespace App\Repository;

use App\Models\Image;

interface ImageRepository {
    public function findBy($criteria = [], $page, $size);
    public function findOneBy($criteria = []);
    public function create(Image $image): object;
    public function count($criteria = []): int;
}
