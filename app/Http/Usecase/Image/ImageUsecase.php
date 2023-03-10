<?php

namespace App\Http\Usecase\Image;

use App\Http\Requests\DeleteImageRequest;
use App\Http\Requests\StoreImageRequest;
use App\Http\Usecase\Usecase;
use App\Models\Image;
use App\Models\ProductImage;
use App\Repository\ImageRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUsecase extends Usecase implements ImageUsecaseInterface
{
    protected ImageRepository $image_service;

    protected ProductRepository $product_service;

    protected ProductImageRepository $product_image_service;

    public function __construct(ImageRepository $image_service, ProductRepository $product_service, ProductImageRepository $product_image_service) {
        $this->image_service = $image_service;
        $this->product_service = $product_service;
        $this->product_image_service = $product_image_service;
    }

    protected function getCriteria(Request $request): array
    {
        $criteria = [];

        if ($request->query('name') != null) {
            $criteria['name'] = $request->query('name');
        }

        if ($request->query('enable') != null) {
            $criteria['enable'] = ($request->query('enable') == "true") ? 1 : 0;
        }

        return $criteria;
    }

    public function getBy(Request $request) {
        $criteria = $this->getCriteria($request);
        $pagination = $this->getPageAndSize($request);

        $images = $this->image_service->findBy($criteria, $pagination->page, $pagination->size);

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Here is your data!",
            $images,
            $this->setPagination(
                $pagination->page,
                $pagination->size,
                $this->image_service->count($criteria)
            )
        );
    }

    public function getOneBy($id) {
        $image = $this->image_service->findOneBy(["id" => $id]);
        if (!$image) {
            return $this->returnUsecase(
                Response::HTTP_NOT_FOUND,
                "Gambar Tidak Ditemukan!"
            );
        }

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Gambar Ditemukan!",
            $image
        );
    }

    public function store(StoreImageRequest $request) {

        DB::beginTransaction();

        $product = $this->product_service->findOneBy(["id" => $request->product_id]);
        if (!$product) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Menambahkan Gambar! Produk Tidak Tersedia"
            );
        }

        $file = $request->file('file');
        $filename = 'product_' . Str::slug(now()) . '_' . Str::slug($request->name) . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();

        $new_image = new Image($request->except('file', 'product_id'));
        $new_image->file = $filename;
        $image = $this->image_service->create($new_image);
        if (!$image->process) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Menambahkan Gambar! Gagal Menyimpan Data Gambar"
            );
        }

        if(Storage::disk('public')->putFileAs('product_images', $file, $filename) == false) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Menambahkan Gambar! Gambar Tidak Dapat Tersimpan Kedalam Storage"
            );
        }

        $new_product_image = new ProductImage();
        $new_product_image->product_id = $product->id;
        $new_product_image->image_id = $image->data->id;
        $product_image = $this->product_image_service->create($new_product_image);
        if (!$product_image->process) {
            DB::rollBack();
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Terjadi Kesalahan Saat Menambahkan Gambar! Gagal Assign Gambar Ke Produk"
            );
        }

        DB::commit();
        return $this->returnUsecase(
            Response::HTTP_CREATED,
            "Gambar Berhasil Ditambahkan"
        );
    }

    public function delete(DeleteImageRequest $request) {
        $product = $this->product_service->findOneBy(["id" => $request->product_id]);
        if (!$product) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Gagal Menghapus Gambar! Produk Tidak Tersedia"
            );
        }

        $image = $this->image_service->findOneBy(["id" => $request->image_id]);
        if (!$image) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Gagal Menghapus Gambar! Gambar Tidak Tersedia"
            );
        }

        $product_image = $this->product_image_service->findOneBy(["product_id" => $product->id, "image_id" => $image->id]);
        if (!$product_image) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Gagal Menghapus Gambar! Gambar Sudah Tidak Tersedia"
            );
        }

        $delete_image = $this->product_image_service->delete($product_image);
        if (!$delete_image->process) {
            return $this->returnUsecase(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                "Gagal Menghapus Gambar! Terjadi Kesalahan Saat Menghapus Gambar"
            );
        }

        return $this->returnUsecase(
            Response::HTTP_OK,
            "Gambar Berhasil Dihapus!"
        );
    }

}
