<?php
declare(strict_types=1);
class Response {
    public function __construct(
        public string $message,
        public int $code
    ) {}

    public static function send(Response $response): void {
        header('Content-Type: text/plain');
        http_response_code($response->code);
        echo $response->message;
    }
}

?>