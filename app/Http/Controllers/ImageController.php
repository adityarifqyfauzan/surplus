<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteImageRequest;
use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Usecase\Image\ImageUsecase;
use Exception;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected ImageUsecase $image_usecase;

    public function __construct(ImageUsecase $image_usecase) {
        $this->image_usecase = $image_usecase;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $resp = $this->image_usecase->getBy($request);
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
    public function store(StoreImageRequest $request)
    {
        try {

            $resp = $this->image_usecase->store($request);
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

            $resp = $this->image_usecase->getOneBy($id);
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
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteImageRequest $request)
    {
        try {

            $resp = $this->image_usecase->delete($request);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }
}
