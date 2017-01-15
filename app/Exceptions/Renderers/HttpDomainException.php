<?php

namespace App\Exceptions\Renderers;

use Exception;

class HttpDomainException implements Renderable
{
    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $exception)
    {
        $statusCode = $exception->getStatusCode();

        return response()->json(
            [
                'code' => $statusCode,
                'error' => (object) $exception->getArgs(),
            ],
            $statusCode, $exception->getHeaders()
        );
    }
}