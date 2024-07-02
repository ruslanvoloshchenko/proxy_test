<?php
require_once __DIR__ . '/../functions.php';
$url = WEBSITE_DASH . $_SERVER['REQUEST_URI'];
$url = preg_replace('/\/udemy_dash\//', '/', $url, 1);
initRequest($url);