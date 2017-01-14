<?php

namespace App;

/**
 * Interface Classifiable
 * @package App
 */
interface Classifiable
{
    /**
     * 로그 레벨을 조회합니다.
     *
     * @return LogLevel
     */
    public function getLogLevel();
}