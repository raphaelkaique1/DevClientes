<?php
namespace URL;

return $PATH = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

?>