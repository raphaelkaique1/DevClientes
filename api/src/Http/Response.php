<?php
declare(strict_types=1);

enum ContentType: string {
    case TEXT = 'text/plain';
    case HTML = 'text/html';
}

class Response {
    public function __construct(
        public ContentType $contentType,
        public string $message,
        public int $code
    ) {}

    public function send(): void {
        header("Content-Type: {$this->contentType->value}");
        http_response_code($this->code);
        echo $this->message;
    }
}

?>