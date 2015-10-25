<?php

require 'Less.php';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = str_replace('less/compile-less.php', 'bootstrap/less', $url);

$parser = new Less_Parser();
$parser->parseFile( '../bootstrap/less/bootstrap.less', $url );
$css = $parser->getCss();

echo $css;

file_put_contents ( '../bootstrap/css/bootstrap.min.css' , $css);

?>