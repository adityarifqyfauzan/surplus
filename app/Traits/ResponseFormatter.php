<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 *  Response formatter
 */
trait ResponseFormatter
{
    /**
     * Http response
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @param array $paginate
     *
     * @return json
     */
    public function response($message, $data = null, $code = Response::HTTP_OK, $paginate = [])
    {

        if ($paginate) {
            return response()->json([
                "message" => $message,
                "data" => $data,
                "paginate" => $paginate
            ], $code);
        }

        return response()->json([
            "message" => $message,
            "data" => $data
        ], $code);

    }

    /**
     * Paginate function
     * digunakan untuk membuat sebuah pagination
     * @param integer $page
     * @param integer $size
     * @param integer $total
     *
     * @return Array
     */
    public function pagination($page, $size, $total)
    {
        return [
            "page" => $page,
            "size" => $size,
            "total" => $total
        ];
    }

    /**
     * Untuk melakukan filter error sesuai dengan environment
     * @param string $message
     * @return string
     */
    public function error($message)
    {

        if (App::environment(['staging', 'local'])) {
            return $message;
        }

        return 'Terjadi kesalahan pada server, silahkan hubungi administrator';
    }

    public function internalServerError($e)
    {
        return $this->response(
            $e,
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
