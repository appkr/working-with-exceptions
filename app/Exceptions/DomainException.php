<?php

namespace App\Exceptions;

use Exception;
use RuntimeException;

/**
 * Class DomainException
 * @package App\Exceptions
 */
class DomainException extends RuntimeException
{
    /**
     * @var array['key' => 'value']
     */
    protected $args;

    /**
     * DomainException constructor.
     * @param array['key' => 'value'] $args
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(array $args, int $code = 0, Exception $previous = null)
    {
        $this->args = $args;
        $message = $this->buildMessage($args);

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * 부모 생성자에 넘길 문자열 형식의 예외 메시지를 만듭니다.
     *
     * @param array $args
     * @return string
     */
    protected function buildMessage(array $args = [])
    {
        $flattened = array_dot($args);
        $argKeys = array_keys($flattened);
        $argValues = array_values($flattened);

        $stringified = array_map(function ($key, $value) {
            // 원하는 형식대로 자유롭게 포맷팅합니다.
            return sprintf('[%s] %s.', $key, $value);
        }, $argKeys, $argValues);

        return implode(' ', $stringified);
    }
}