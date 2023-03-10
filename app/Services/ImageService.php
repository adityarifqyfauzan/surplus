<?php

namespace App\Services;

use App\Helper\Pagination;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Support\Arr;

class ImageService extends Service implements ImageRepository
{
    public function findBy($criteria = [], $page, $size) {
        $offset = Pagination::getOffset($page, $size);
        $images = Image::where(Arr::except($criteria, 'name'));

        if (Arr::exists($criteria, 'name')) {
            $images = $images->where('name', 'like', '%'.$criteria['name'].'%');
        }

        $images = $images->limit($size)->offset($offset)->get();

        return $images;
    }

    public function findOneBy($criteria = []) {
        $image = Image::where($criteria)->first();
        return $image;
    }

    public function create(Image $image): object {
        if ($image->save()) {
            return $this->return(true, $image);
        }
        return $this->return(false);
    }

    public function count($criteria = []): int {
        $images = Image::where(Arr::except($criteria, 'name'));

        if (Arr::exists($criteria, 'name')) {
            $images = $images->where('name', 'like', '%'.$criteria['name'].'%');
        }

        $images = $images->count();

        return $images;
    }

}
