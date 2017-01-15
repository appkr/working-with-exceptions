<?php

namespace App\Exceptions\Renderers;

use Exception;
use Illuminate\Http\Response;

class ModelNotFoundException implements Renderable
{
    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $exception)
    {
        $statusCode = Response::HTTP_NOT_FOUND;

        return response()->json(
            [
                'code' => $statusCode,
                'error' => ['message' => $exception->getMessage()],
            ],
            $statusCode
        );
    }
}