<?php

namespace App\Http\Usecase\Image;

use App\Http\Requests\DeleteImageRequest;
use App\Http\Requests\StoreImageRequest;
use Illuminate\Http\Request;

interface ImageUsecaseInterface {
    public function getBy(Request $request);
    public function getOneBy($id);
    public function store(StoreImageRequest $request);
    public function delete(DeleteImageRequest $request);
}
