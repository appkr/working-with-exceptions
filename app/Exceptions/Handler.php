<?php

namespace App\Exceptions;

use App\Classifiable;
use App\LogLevel;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
                // @see https://docs.bugsnag.com/platforms/php/laravel/
                Bugsnag::notifyException($exception);
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
        return $this->getRenderingStrategy($request, $exception)
            ->render($request, $exception);
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

    /**
     * 각 예외에 맞는 렌더러 인스턴스를 생성합니다.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return DomainException|HttpDomainException
     *      |ModelNotFoundException|\Illuminate\Foundation\Application|mixed
     */
    protected function getRenderingStrategy($request, Exception $exception)
    {
        if ($exception instanceof HttpDomainException) {
            return new \App\Exceptions\Renderers\HttpDomainException;
        } elseif ($exception instanceof DomainException) {
            return new \App\Exceptions\Renderers\DomainException;
        } elseif ($exception instanceof ModelNotFoundException) {
            return new \App\Exceptions\Renderers\ModelNotFoundException;
        }

        return app(get_parent_class($this));
    }

    /**
     * 라라벨 매직을 이용하여 getRenderingStrategy와 똑같은 일을 하는 메서드입니다.
     * 예외 이름과 렌더러 이름이 같다는 컨벤션을 지킬 수 있다면 이 방법도 대안입니다만..
     * 위처럼 정통적인 Strategy 패턴을 쓰는 것에 비해 가독성이 떨어집니다.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getRenderer($request, Exception $exception)
    {
        $class = "\\App\\Exceptions\\Renderers\\" . class_basename(get_class($exception));

        if (class_exists($class)) {
            return app($class);
        }

        return app(get_parent_class($this));
    }
}
