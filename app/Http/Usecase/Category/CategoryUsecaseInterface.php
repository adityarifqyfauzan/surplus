<?php

namespace App\Http\Usecase\Category;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

interface CategoryUsecaseInterface {
    public function getBy(Request $request);
    public function getOneBy($id);
    public function store(StoreCategoryRequest $request);
    public function update($id, UpdateCategoryRequest $request);
}
