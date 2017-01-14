<?php

namespace App\Exceptions;

use App\Classifiable;
use App\LogLevel;

/**
 * Class CustomHttpException
 * @package App\Exceptions
 */
class CustomHttpException extends HttpDomainException implements Classifiable
{
    protected $statusCode = 410;

    /**
     * {@inheritdoc}
     */
    public function getLogLevel()
    {
        return LogLevel::getInstance('DEBUG');
    }
}