<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Set an expected exception.
     *
     * @param string $exception
     * @return $this
     */
    public function expectException($exception)
    {
        $this->disableExceptionHandling();
        parent::expectException($exception);

        return $this;
    }

    /**
     * Disable Laravel's exception handling.
     *
     * @return $this
     */
    protected function disableExceptionHandling()
    {
        app()->instance(App\Exceptions\Handler::class, new class extends App\Exceptions\Handler {
            public function __construct() {}
            public function report(Exception $exception) {}
            public function render($request, Exception $exception)
            {
                throw $exception;
            }
        });

        return $this;
    }
}
