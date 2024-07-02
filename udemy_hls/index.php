<?php
require_once __DIR__ . '/../functions.php';
$url = WEBSITE_HLS . $_SERVER['REQUEST_URI'];
$url = preg_replace('/\/udemy_hls\//', '/', $url, 1);
initRequest($url);