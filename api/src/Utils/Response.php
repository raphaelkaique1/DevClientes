<?php

class Response {
    public function __construct(
        public string $message,
        public int $code
    ) {}

    public static function send(Response $response): void {
        http_response_code($response->code);
        echo $response->message;
    }
}

?>