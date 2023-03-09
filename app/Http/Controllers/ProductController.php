<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Usecase\Product\ProductUsecaseInterface;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected ProductUsecaseInterface $product_usecase;

    public function __construct(ProductUsecaseInterface $product_usecase) {
        $this->product_usecase = $product_usecase;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $resp = $this->product_usecase->getBy($request);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status,
                $resp->pagination
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {

            $resp = $this->product_usecase->store($request);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $resp = $this->product_usecase->getOneBy($id);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateProductRequest $request)
    {
        try {

            $resp = $this->product_usecase->update($id, $request);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
