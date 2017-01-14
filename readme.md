# PHP의 예외 클래스 이해하기

이 프로젝트에 대한 자세한 설명은 다음 포스트를 참고합니다.

-   [PHP의 예외 클래스 이해하기](http://blog.appkr.kr/work-n-play/understanding-php-exception-class/)
-   [PHP의 예외 클래스 이해하기 2부](http://blog.appkr.kr/work-n-play/understanding-php-exception-class-part-2/)

이 프로젝트는 [PHP의 예외 클래스 이해하기 2부](http://blog.appkr.kr/work-n-play/understanding-php-exception-class-part-2/)를 작성하면서 만들었습니다.

포스트에서는 라라벨을 중심으로 설명하고 있지만, 라라벨이 아니더라도 전역 예외 처리기([`set_exception_handler()`](http://php.net/manual/kr/function.set-exception-handler.php))를 사용한다면 어떤 PHP 프로젝트든 적용할 수 있는 테크닉입니다. 뿐만아니라 PHP가 아닌 다른 프레임워크를 사용할 때도 도움이 되는 내용입니다.

이 저장소에서는 사용자 정의 예외 클래스를 만들고, 예외 클래스의 타입에 따라 JSON 응답을 분리하는 예제를 구현하고 있습니다.

### 1. 프로젝트 설치

프로젝트를 복제합니다.

```bash
~ $ git clone git@github.com:appkr/working-with-exceptions.git

# 깃허브에 등록된 SSH 키가 없다면 다음 방법으로 복제하니다.
~ $ git clone https://github.com/appkr/working-with-exceptions.git
```

이 프로젝트가 의존하는 라이브러리를 설치하고, 프로젝트 설정 파일을 생성합니다.

```bash
# composer는 PHP의 표준 의존성 관리자인데, 설치하려면 getcomposer.org를 참고합니다.

$ cd working-with-exceptions
~/working-with-exceptions $ composer install
~/working-with-exceptions $ cp .env.example .env
~/working-with-exceptions $ php artisan key:generate
```

로컬 서버를 구동하고, 페이지를 열어 봅니다.

```bash
~/working-with-exceptions $ php artisan serve
# Laravel development server started on http://127.0.0.1:8000/
```

브라우저에서 `http://localhost:8000`을 열어 봅니다. 

필자는 [라라벨 Valet 서버](https://laravel.kr/docs/5.3/valet)를 이용합니다. Valet 서버를 사용하신다면 `http://working-with-exceptions.dev`처럼 로컬 프로젝트 폴더명에 `dev`를 붙여 작동을 확인할 수 있습니다.

### 2. 작동 테스트

라우팅 정의 파일([`routes/web.php`](routes/web.php))에 예외를 생성할 수 있는 테스트 라우트를 만들어 두었습니다.
 
#### 2.1. 테스트를 위한 URL 엔드포인트

미리 만들어 둔 [포스트맨](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop) 콜렉션을 이용하면 편리하게 테스트할 수 있습니다. 

```bash
~/working-with-exceptions $ wget https://raw.githubusercontent.com/appkr/working-with-exceptions/master/docs/Working-With-PHP-Exceptions.postman_collection.json
```

다운로드 받은 포스트맨 콜렉션을, 포스트맨에 임포트하고, `:host` 부분만 자신에게 맞게 변경합니다.

![포스트맨 콜렉션](https://github.com/appkr/working-with-exceptions/raw/master/docs/2017-01-13-img-01.png)

모든 `GET` 방식을 사용하므로 웹 브라우저를 사용하셔도 무방합니다. 

##### 요청

Method|Url|Exception
---|---|---
`GET`|`/exceptions/domain`|`\App\Exceptions\DomainException`
`GET`|`/exceptions/custom-domain`|`\App\Exceptions\CustomDomainException`
`GET`|`/exceptions/http-domain`|`\App\Exceptions\HttpDomainException`
`GET`|`/exceptions/custom-http`|`\App\Exceptions\CustomHttpException`
`GET`|`/exceptions/model-not-found`|`\Illuminate\Database\Eloquent\ModelNotFoundException`

##### 응답

예외 응답은 다음 형식을 따릅니다.

```http
HTTP/1.1 404 Not Found
Cache-Control: no-cache
Content-Type: application/json

{
  "code": 404,
  "error": {
    "message": "No query results for model [App\\User] 1"
  }
}
```

Key|Type|Always|Memo
---|---|---|---
`code`|`integer`|Alawys|예외 코드
`error`|`object`|Always|에러 객체
`error.*`|`string`, `array`|Sometimes|에러 객체의 속성 값들

#### 2.2. Functional Suite

`phpunit` 으로도 확인할 수 있습니다.

```bash
~/working-with-exceptions $ vendor/bin/phpunit tests/ExpectExceptionTest.php
# PHPUnit 5.7.5 by Sebastian Bergmann and contributors.
# .....                                    5 / 5 (100%)
#                         Time: 168 ms, Memory: 10.00MB
```
