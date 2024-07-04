<?php
define('WEBSITE_HLS', 'https://hls-c.udemycdn.com');
define('WEBSITE_DASH', 'https://dash-enc-c.udemycdn.com');
define('WEBSITE_CLOUDSOLUTIONS', 'https://app.wordtune.com');


// if(!isset($_SERVER['HTTP_REFERER'])){
//     header('Location: http://localhost');
// }

if($_SERVER['REQUEST_URI'] === "/user/edit-account/"){
    header('Location: https://udemy.toolzbuy.com/');
}

if($_SERVER['REQUEST_URI'] === "/user/logout/"){
    header('Location: https://udemy.toolzbuy.com/');
}

function initRequest($url) {
    $response = makeRequest($url);
    //$rawResponseHeaders = $response["headers"];
    $responseBody = $response["body"];
    $responseInfo = $response["responseInfo"];
    //$infos = $response["infos"];

    $contentType = isset($responseInfo["content_type"]) ? $responseInfo["content_type"] : 'text/html';
    header("Access-Control-Allow-Origin: *");

    if (stripos($contentType, "text/html") !== false) {
        header("Content-Type: text/html");
        echo proxify($responseBody);
    } else if (stripos($contentType, "text/css") !== false) {
        header("Content-Type: " . 'text/css');
        echo proxify($responseBody);
    } else {
        header("Content-Type: " . $contentType);
        //header("Content-Length: " . strlen($responseBody));
        echo proxify($responseBody);
    }
}

function makeRequest($url) {
    $browserRequestHeaders = getallheaders();
    unset($browserRequestHeaders["Host"]);
    unset($browserRequestHeaders["Content-Length"]);
    unset($browserRequestHeaders["Accept-Encoding"]);
    unset($browserRequestHeaders["Pragma"]);
    unset($browserRequestHeaders["Connection"]);
    unset($browserRequestHeaders['Cookie']);
    $agent = AGENT;
    $referer = REFERER_URL;
    $browserRequestHeaders['User-Agent'] = $agent;
    $browserRequestHeaders['Origin'] = WEBSITE_URL;
    $browserRequestHeaders['Referer'] = $referer;
    $browserRequestHeaders['Accept'] = "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
    $browserRequestHeaders['Accept-Encoding'] = "gzip, deflate";
    $browserRequestHeaders['Accept-Language'] = "en-US,en;q=0.9";
    // $browserRequestHeaders['Connection'] = 'keep-alive';
    // $browserRequestHeaders['Accept'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8';
    // $browserRequestHeaders['Accept-Language'] = "en-US,en;q=0.5";
    // $browserRequestHeaders['Accept-Encoding'] = "gzip, deflate, br, zstd";

    // $browserRequestHeaders['Cookie'] = str_replace($_SERVER['HTTP_HOST'], $parse['host'], $browserRequestHeaders['Cookie']);
    // if(preg_match('#'.preg_quote(WEBSITE_URL_MARKETPLACE).'#', $url)){
    // $browserRequestHeaders['Sec-Fetch-Site'] = 'cross-site';
    // }


    // $ch = curl_init();
    // curl_setopt_array($ch, array(
    //     CURLOPT_URL => $url,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 60 * 60 * 24,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_SSL_VERIFYPEER => false,
    //     CURLOPT_USERAGENT => $agent,
    //     CURLOPT_COOKIEFILE => COOKIE_FILE,
    //     CURLOPT_REFERER => $referer,
    // ));

    // switch ($_SERVER["REQUEST_METHOD"]) {
    //     case "GET":
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //         break;
    //     case "POST":
    //         $browserRequestHeaders['x-kl-ajax-request'] = 'Ajax_Request';
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));
    //         break;
    //     case "PUT":
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    //         curl_setopt($ch, CURLOPT_INFILE, fopen("php://input"));
    //         break;
    //     case "OPTIONS":
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
    //         curl_setopt($ch, CURLOPT_VERBOSE, true);
    //         curl_setopt($ch, CURLOPT_NOBODY , true);
    //         break;
    // }
    // $curlRequestHeaders = array();
    // foreach ($browserRequestHeaders as $name => $value) {
    //     $curlRequestHeaders[] = $name . ": " . $value;
    // }

    // curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);
    // $err = curl_error($ch);
    // $response = curl_exec($ch);
    
    // // $response = str_replace('cdn-cgi/challenge-platform/h/g/orchestrate/chl_page/v1', '', $response);
    // $response = str_replace('src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"', '', $response);
    // $responseInfo = curl_getinfo($ch);
    // $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    // $infos = curl_getinfo($ch);
    // curl_close($ch);

    // $responseHeaders = substr($response, 0, $headerSize);
    return array("headers" => $responseHeaders, "body" => $response, "responseInfo" => $responseInfo, 'infos'=>$infos);
}
function proxify($result)
{
    $parse = parse_url(WEBSITE_URL);
    $parseHls = parse_url(WEBSITE_HLS);
    $parseDash = parse_url(WEBSITE_DASH);
    $parseCloud = parse_url(WEBSITE_CLOUDSOLUTIONS);

    $result = str_replace(
        [
            '\/'.$parseHls['host'] . '\/',
            '/'.$parseHls['host'],
            '\/'.$parseDash['host'] . '\/',
            '/'.$parseDash['host'],
            '\/'.$parseCloud['host'] . '\/',
            '/'.$parseCloud['host'],
            '\/'.$parse['host'] . '\/',
            '/'.$parse['host'],
        ],
        [
            '\/'.$_SERVER['HTTP_HOST'].'\/udemy_hls\/',
            '/'.$_SERVER['HTTP_HOST'].'/udemy_hls',
            '\/'.$_SERVER['HTTP_HOST'].'\/udemy_dash\/',
            '/'.$_SERVER['HTTP_HOST'].'/udemy_dash',
            '\/'.$_SERVER['HTTP_HOST'].'\/udemy_cloud\/',
            '/'.$_SERVER['HTTP_HOST'].'/udemy_cloud',
            '\/'.$_SERVER['HTTP_HOST'].'\/',
            '/'.$_SERVER['HTTP_HOST'],
        ],
        $result
    );
    if(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'http' && $parse['scheme'] == 'https'){
        // $result = str_replace('https://', 'http://', $result);
    }

    return $result;
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