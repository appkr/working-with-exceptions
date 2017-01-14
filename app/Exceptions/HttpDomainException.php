<?php

namespace App\Exceptions;

use App\Classifiable;
use App\LogLevel;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class HttpDomainException
 * @package App\Exceptions
 */
class HttpDomainException extends DomainException implements HttpExceptionInterface, Classifiable
{
    /**
     * @var int|null
     */
    protected $statusCode = 400;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * HttpException constructor.
     * @param array['key' => 'value'] $args
     * @param int|null $statusCode
     * @param array['key' => 'value'] $headers
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        array $args = [], int $statusCode = null,
        array $headers = [], int $code = 0, Exception $previous = null
    )
    {
        if (! is_null($statusCode)) {
            $this->statusCode = $statusCode;
        }

        if (! empty($headers)) {
            $this->headers = $headers;
        }

        parent::__construct($args, $code, $previous);
    }

    /**
     * HTTP 응답 코드를 설정합니다.
     *
     * @param int|null $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode = null)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * HTTP 응답 코드를 조회합니다.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * HTTP 응답 헤더를 설정합니다.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * HTTP 응답 헤더를 조회합니다.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 로그 레벨을 조회합니다.
     *
     * @return LogLevel
     */
    public function getLogLevel()
    {
        return LogLevel::getInstance('DEBUG');
    }
}