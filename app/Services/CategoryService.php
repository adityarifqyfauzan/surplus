<?php

namespace App\Services;

use App\Helper\Pagination;
use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Support\Arr;

class CategoryService extends Service implements CategoryRepository
{
    public function findBy($criteria = [], $page, $size) {

        $offset = Pagination::getOffset($page, $size);

        $categories = Category::where(Arr::except($criteria, "name"));

        if (Arr::exists($criteria, "name")) {
            $categories = $categories->where("name", "like", "%".$criteria["name"]."%");
        }

        $categories = $categories->take($size)->offset($offset)->get();

        return $categories;
    }

    public function findOneBy($criteria = []) {
        $category = Category::where(Arr::except($criteria, "name"));

        if (Arr::exists($criteria, "name")) {
            $category = $category->where("name", "like", "%".$criteria["name"]."%");
        }

        $category = $category->first();

        if ($category) {
            return $this->return(true, $category);
        }
        return $this->return(false);
    }

    public function create(Category $category): object {
        if ($category->save()) {
            return $this->return(true, $category);
        }
        return $this->return(false);
    }

    public function update(Category $category): object {
        if ($category->update()) {
            return $this->return(true, $category);
        }
        return $this->return(false);
    }

    public function count($criteria = []): int {
        $categories = Category::where(Arr::except($criteria, "name"));

        if (Arr::exists($criteria, "name")) {
            $categories = $categories->where("name", "like", "%".$criteria["name"]."%");
        }

        return $categories->count();
    }

}
