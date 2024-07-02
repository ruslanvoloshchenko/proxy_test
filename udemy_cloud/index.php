<?php
require_once __DIR__ . '/../functions.php';
$url = WEBSITE_CLOUDSOLUTIONS . $_SERVER['REQUEST_URI'];
$url = preg_replace('/\/udemy_cloud\//', '/', $url, 1);
initRequest($url);