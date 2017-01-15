<?php

class ExpectExceptionTest extends TestCase
{
    public function testDomainException()
    {
        $this->expectException(App\Exceptions\DomainException::class);
        $this->visit('exceptions/domain');
    }

    public function testCustomDomainException()
    {
        $this->expectException(App\Exceptions\CustomDomainException::class);
        $this->visit('exceptions/custom-domain');
    }

    public function testHttpDomainException()
    {
        $this->expectException(App\Exceptions\HttpDomainException::class);
        $this->visit('exceptions/http-domain');
    }

    public function testCustomHttpException()
    {
        $this->expectException(App\Exceptions\CustomHttpException::class);
        $this->visit('exceptions/custom-http');
    }

    public function testModelNotFoundException()
    {
        $this->expectException(Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->visit('exceptions/model-not-found');
    }
}
