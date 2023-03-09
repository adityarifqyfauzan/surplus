<?php

namespace App\Http\Usecase\Category;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Usecase\Usecase;
use App\Models\Category;
use App\Repository\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryUsecase extends Usecase implements CategoryUsecaseInterface
{
    protected CategoryRepository $category_service;

    public function __construct(CategoryRepository $category_service) {
        $this->category_service = $category_service;
    }

    protected function getCriteria(Request $request): array
    {
        $criteria = [];

        if ($request->query('name') != null) {
            $criteria['name'] = $request->query('name');
        }

        if ($request->query('enable') != null) {
            $criteria['enable'] = ($request->query('enable') == "true") ? true : false;
        }

        return $criteria;
    }

    public function getBy(Request $request){
        $criteria = $this->getCriteria($request);
        $pagination = $this->getPageAndSize($request);

        $result = $this->category_service->findBy($criteria, $pagination->page, $pagination->size);

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Here is your data",
            $result,
            $this->setPagination(
                $pagination->page,
                $pagination->size,
                $this->category_service->count($criteria)
            )
        );
    }

    public function getOneBy($id){
        $result = $this->category_service->findOneBy(["id" => $id]);
        if ($result) {
            return $this->returnUsecase(
                Response::HTTP_OK,
                "Kategori Ditemukan!",
                $result
            );
        }

        return $this->returnUsecase(
            Response::HTTP_NOT_FOUND,
            "Kategori Tidak Ditemukan!"
        );
    }

    public function store(StoreCategoryRequest $request){

        $category = $this->category_service->findOneBy(["name" => $request->name]);
        if ($category) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Kategori ". $request->name ." sudah ada!"
            );
        }

        $category = new Category($request->only("name"));

        $category = $this->category_service->create($category);
        if (!$category->process) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Membuat Kategori"
            );
        }

        return $this->returnUsecase(
            Response::HTTP_CREATED,
            "Berhasil Menambahkan Kategori"
        );
    }

    public function update($id, UpdateCategoryRequest $request){

        // get category
        $category = $this->category_service->findOneBy(["id" => $id]);
        if (!$category) {
            return $this->returnUsecase(
                Response::HTTP_NOT_FOUND,
                "Kategori Tidak Ditemukan!"
            );
        }

        // check if new category is not exist
        $check = $this->category_service->findOneBy(["name" => $request->name]);
        if ($check && $check->id != $id) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Kategori ". $request->name ." sudah ada!"
            );
        }

        $category->name = $request->name;
        $category->enable = $request->enable;

        $category = $this->category_service->update($category);
        if (!$category->process) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Memperbarui Kategori"
            );
        }

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Berhasil Memperbarui Kategori"
        );
    }

}
