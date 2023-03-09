<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Usecase\Category\CategoryUsecaseInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    protected CategoryUsecaseInterface $category_usecase;

    public function __construct(CategoryUsecaseInterface $category_usecase) {
        $this->category_usecase = $category_usecase;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $resp = $this->category_usecase->getBy($request);
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
    public function store(StoreCategoryRequest $request)
    {
        try {

            $resp = $this->category_usecase->store($request);

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

            $resp = $this->category_usecase->getOneBy($id);
            return $this->response(
                $resp->message,
                $resp->data,
                $resp->http_status,
            );

        } catch (Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        try {

            $resp = $this->category_usecase->update($id, $request);
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
    public function destroy(Category $category)
    {
        //
    }
}
