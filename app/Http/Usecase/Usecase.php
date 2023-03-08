<?php

namespace App\Http\Usecase;

use App\Traits\Pagination;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Usecase
{

    use Pagination;

    /**
     * this function used to return the proses of Usecase
     *
     * @param int $http_status
     * @param string $message
     * @param $data
     * @return object
     */
    public function returnUsecase($http_status = Response::HTTP_OK, $message = "", $data = null, $pagination = [])
    {
        return (object) [
            "http_status" => $http_status,
            "message" => $message,
            "data" => $data,
            "pagination" => $pagination
        ];
    }

    protected function getCriteria(Request $request): array {
        return [];
    }

}
