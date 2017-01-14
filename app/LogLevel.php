<?php

namespace App;

/**
 * Class LogLevel
 * @package App
 * @see https://tools.ietf.org/html/rfc3164
 */
final class LogLevel
{
    const EMERGENCY = 0;        // Emergency: system is unusable
    const ALERT = 1;            // Alert: action must be taken immediately
    const CRITICAL = 2;         // Critical: critical conditions
    const ERROR = 3;            // Error: error conditions
    const WARNING = 4;          // Warning: warning conditions
    const NOTICE = 5;           // Notice: normal but significant condition
    const INFORMATIONAL = 6;    // Informational: informational messages
    const DEBUG = 7;            // Debug: debug-level messages

    /**
     * 문자열 형식의 로그 레벨 (e.g. 'DEBUG'), 즉 상수의 이름.
     *
     * @var string|null
     */
    private $name;

    /**
     * 정적 생성자.
     *
     * @param string|null $name
     * @return static
     */
    public static function getInstance(string $name = null)
    {
        return new static($name);
    }

    /**
     * 생성된 객체에 설정된 상수 이름을 조회합니다.
     *
     * @return null|string (e.g. 'DEBUG')
     */
    public function getName()
    {
        if (is_null($this->name)) {
            return null;
        }

        return $this->name;
    }

    /**
     * 생성된 객체에 설정된 상수 값을 조회합니다.
     *
     * @return integer (e.g. 7)
     */
    public function getValue()
    {
        return constant("static::{$this->name}");
    }

    /**
     * LogLevel constructor.
     * @param null $name
     */
    private function __construct($name = null)
    {
        if (is_null($name)) {
            // 생성자로 넘어온 인자가 없으면 'DEBUG'를 기본값으로 설정합니다.
            $this->name = 'DEBUG';

            return;
        }

        if (!defined('static::' . $name)) {
            // 생성자로 넘어온 인자에 해당하는 상수가 정의되어 있는 지 확인합니다.
            throw new \InvalidArgumentException(
                sprintf('정의되지 않은 상수입니다. "%s:%s"', get_called_class(), $name)
            );
        }

        $this->name = $name;
    }
}