<?php

namespace App\Services;

use App\Helper\Pagination;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Support\Arr;

class ProductService extends Service implements ProductRepository
{
    public function findBy($criteria = [], $page, $size) {
        $offset = Pagination::getOffset($page, $size);
        $products = Product::with('categories:id,name', 'images:id,name,file')->where(Arr::except($criteria, ['name', 'category_id']));

        if (Arr::exists($criteria, 'name')) {
            $products = $products->where('name', 'like', '%'. $criteria['name'] . '%');
        }

        if (Arr::exists($criteria, 'category_id')) {
            $products = $products->whereHas('categories', function ($q) use ($criteria)
            {
                $q->whereIn('category_id', (array) $criteria['category_id']);
            });
        }

        $products = $products->offset($offset)->take($size)->orderBy('name')->get();

        return $products;
    }

    public function findOneBy($criteria = []) {
        $product = Product::with('categories:id,name', 'images:id,name,file')->where(Arr::except($criteria, ['category_id']));

        if (Arr::exists($criteria, 'category_id')) {
            $product = $product->whereHas('categories', function ($q) use ($criteria)
            {
                $q->whereIn('category_id', (array) $criteria['category_id']);
            });
        }

        $product = $product->first();

        return $product;
    }

    public function create(Product $product): object {
        if($product->save()){
            return $this->return(true, $product);
        }

        return $this->return(false);
    }

    public function update(Product $product): object {
        if ($product->update()) {
            return $this->return(true, $product);
        }
        return $this->return(false);
    }

    public function count($criteria = []): int {
        $products = Product::with('categories:id,name')->where(Arr::except($criteria, ['name', 'category_id']));

        if (Arr::exists($criteria, 'name')) {
            $products = $products->where('name', 'like', '%'. $criteria['name'] . '%');
        }

        if (Arr::exists($criteria, 'category_id')) {
            $products = $products->whereHas('categories', function ($q) use ($criteria)
            {
                $q->whereIn('category_id', (array) $criteria['category_id']);
            });
        }

        return $products->count();
    }

}
