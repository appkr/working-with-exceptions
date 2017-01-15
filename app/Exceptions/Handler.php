<?php

namespace App\Exceptions;

use App\Classifiable;
use App\LogLevel;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof Classifiable) {
            $logger = $logger = $this->container->make(LoggerInterface::class);
            $logLevel = $exception->getLogLevel();
            $method = strtolower($logLevel->getName());
            call_user_func([$logger, $method], $exception);

            if ($logLevel->getValue() <= LogLevel::ERROR) {
                var_dump('관리자에게 알림을 보내거나 SaaS 서비스에 로그를 등록하는 등의 특별한 예외 리포팅 처리를 합니다');
            }
        } else {
            parent::report($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpDomainException) {
            $statusCode = $exception->getStatusCode();

            return response()->json(
                [
                    'code' => $statusCode,
                    'error' => (object) $exception->getArgs(),
                ],
                $statusCode, $exception->getHeaders()
            );
        } elseif ($exception instanceof DomainException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(
                [
                    'code' => $statusCode,
                    'error' => (object) $exception->getArgs(),
                ],
                $statusCode
            );
        } elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;

            return response()->json(
                [
                    'code' => $statusCode,
                    'error' => $exception->getMessage(),
                ],
                $statusCode
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
