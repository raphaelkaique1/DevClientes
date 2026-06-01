<?php
parse_str(file_get_contents('php://input'), $data);
http_response_code();
echo 'Hello world!';
?>