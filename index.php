<?php
require 'vendor/autoload.php';

define('AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36');
define('COOKIE_FILE', __DIR__ . '/cookie.word.txt');
define('WEBSITE_URL', 'https://app.wordtune.com');
define('REFERER_URL', 'https://app.wordtune.com');

// Path to the Netscape HTTP Cookie File
$cookieFilePath = COOKIE_FILE;

// Function to parse the cookie file
function parseNetscapeCookieFile($filePath) {
    $cookies = [];
    if (file_exists($filePath) && is_readable($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                // Skip comments
                continue;
            }
            $fields = explode("\t", $line);
            if (count($fields) == 7) {
                $cookies[] = [
                    'domain'    => $fields[0],
                    'flag'      => $fields[1],
                    'path'      => $fields[2],
                    'secure'    => $fields[3],
                    'expires'   => $fields[4],
                    'name'      => $fields[5],
                    'value'     => $fields[6],
                ];
            }
        }
    }
    return $cookies;
}

// Parse the cookies
$cookies = parseNetscapeCookieFile($cookieFilePath);
// Set cookies in PHP
foreach ($cookies as $cookie) {
    // Send each cookie to the browser
    setcookie(
        $cookie['name'],
        $cookie['value'],
        $cookie['expires'],
        $cookie['path'],
        '',
        $cookie['secure'] === 'TRUE',
        true  // HttpOnly flag, you can set this based on your requirements
    );
}

if (!function_exists("getallheaders")) {
    function getallheaders() {
        $result = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 500) == "HTTP_") {
                $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 500)))));
                $result[$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}

$url = WEBSITE_URL . $_SERVER['REQUEST_URI'];

$curl = new CurlImpersonate\CurlImpersonate();
$curl->setopt(CURLCMDOPT_URL, $url);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if(str_ends_with($_SERVER["QUERY_STRING"], "js")) {
            header("Content-Type: application/javascript");
        }
        if(str_ends_with($_SERVER["QUERY_STRING"], "css")) {
            header("Content-Type: text/css");
        }
        $curl->setopt(CURLCMDOPT_METHOD, 'GET');
        break;
    case "POST":
        $browserRequestHeaders['x-kl-ajax-request'] = 'Ajax_Request';
        $curl->setopt(CURLCMDOPT_METHOD, 'POST');
        $curl->setopt(CURLCMDOPT_POSTFIELDS, file_get_contents("php://input"));
        break;
    case "PUT":
        $curl->setopt(CURLCMDOPT_METHOD, 'PUT');
        $curl->setopt(CURLCMDOPT_POSTFIELDS, fopen("php://input"));
        break;
    case "OPTIONS":
        $curl->setopt(CURLCMDOPT_METHOD, 'OPTIONS');
        break;
    default:
        $curl->setopt(CURLCMDOPT_METHOD, 'GET');
}

$browserRequestHeaders = getallheaders();
unset($browserRequestHeaders["Host"]);
unset($browserRequestHeaders["Content-Length"]);
unset($browserRequestHeaders["Accept-Encoding"]);
unset($browserRequestHeaders["Pragma"]);
unset($browserRequestHeaders["Connection"]);
unset($browserRequestHeaders['Cookie']);
$browserRequestHeaders['User-Agent'] = AGENT;
$browserRequestHeaders['Origin'] = WEBSITE_URL;
$browserRequestHeaders['Referer'] = REFERER_URL;

$curlRequestHeaders = array();
foreach ($browserRequestHeaders as $name => $value) {
    $curlRequestHeaders[] = $name . ": " . $value;
}

$curl->setopt(CURLCMDOPT_HEADER, false);
$curl->setopt(CURLCMDOPT_HTTP_HEADERS, $curlRequestHeaders);
$curl->setopt(CURLCMDOPT_COOKIEFILE, COOKIE_FILE);
$curl->setopt(CURLCMDOPT_ENGINE, __DIR__ . "/curl");
$response = $curl->execStandard();
echo $response;
$curl->closeStream();