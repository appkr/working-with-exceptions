<?php

namespace App\Exceptions\Renderers;

use Exception;
use Illuminate\Http\Response;

class DomainException implements Renderable
{
    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $exception)
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        return response()->json(
            [
                'code' => $statusCode,
                'error' => (object) $exception->getArgs(),
            ],
            $statusCode
        );
    }
}