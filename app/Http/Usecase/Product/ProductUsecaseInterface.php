<?php

namespace App\Http\Usecase\Product;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

interface ProductUsecaseInterface {
    public function getBy(Request $request);
    public function getOneBy($id);
    public function store(StoreProductRequest $request);
    public function update($id, UpdateProductRequest $request);
}
