<?php

namespace App\Http\Usecase\Product;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Usecase\Usecase;
use App\Models\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductUsecase extends Usecase implements ProductUsecaseInterface
{

    protected ProductRepository $product_service;
    protected CategoryRepository $category_service;

    public function __construct(ProductRepository $product_service, CategoryRepository $category_service)
    {
        $this->product_service = $product_service;
        $this->category_service = $category_service;
    }

    protected function getCriteria(Request $request): array
    {
        $criteria = [];

        if ($request->query('product_id') != null) {
            $criteria['id'] = $request->query('product_id');
        }

        if ($request->query('name') != null) {
            $criteria['name'] = $request->query('name');
        }

        if ($request->query('category_id') != null) {
            $criteria['category_id'] = $request->query('category_id');
        }

        if ($request->query('enable') != null) {
            $criteria['enable'] = ($request->query('enable') == "true") ? 1 : 0;
        }

        return $criteria;
    }

    public function getBy(Request $request)
    {

        $criteria = $this->getCriteria($request);
        $pagination = $this->getPageAndSize($request);

        $products = $this->product_service->findBy($criteria, $pagination->page, $pagination->size);

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Here is your data!",
            $products,
            $this->setPagination(
                $pagination->page,
                $pagination->size,
                $this->product_service->count($criteria)
            )
        );
    }

    public function getOneBy($id)
    {
        $product = $this->product_service->findOneBy(["id" => $id]);

        if (!$product) {
            return $this->returnUsecase(
                Response::HTTP_NOT_FOUND,
                "Produk Tidak Ditemukan!"
            );
        }

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Produk Ditemukan!",
            $product
        );
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        $product = $this->product_service->findOneBy(["name" => $request->name]);
        if ($product) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Produk Sudah Ada!"
            );
        }

        $product = new Product($request->except('category_ids'));
        $product = $this->product_service->create($product);
        if (!$product->process) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Menambahkan Produk! Gagal Menyimpan Data Produk"
            );
        }

        $product = Product::find($product->data->id);

        $product->categories()->sync($request->category_ids);

        DB::commit();
        return $this->returnUsecase(
            Response::HTTP_CREATED,
            "Produk Berhasil Ditambahkan!"
        );
    }

    public function update($id, UpdateProductRequest $request)
    {
        DB::beginTransaction();

        $product = $this->product_service->findOneBy(["id" => $id]);
        if (!$product) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Produk Tidak Ditemukan!"
            );
        }

        $check = $this->product_service->findOneBy(["name" => $request->name]);
        if ($check && $check->id != $id) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Produk " . $request->name . " Sudah Ada!"
            );
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->enable = $request->enable;

        $product->categories()->sync($request->category_ids);

        $product = $this->product_service->update($product);
        if (!$product->process) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Memperbarui Produk! Gagal Memperbarui Data Produk"
            );
        }

        DB::commit();
        return $this->returnUsecase(
            Response::HTTP_OK,
            "Produk Berhasil Diperbarui!"
        );
    }
}
