<?php
function fetch_proxies()
{
    $source = file_get_contents('http://www.sslproxies.org/');
    preg_match_all('/<tbody>(.*?)<\/tbody>/is', $source, $matches);
    preg_match_all('/<tr>(.*?)<\/tr>/is', $matches[1][0], $matches);
    $return = array();
    foreach ($matches[1] as $key => $val) {
        preg_match_all('/<td>(.*?)<\/td>/is', $val, $m);
        $return[] = "{$m[1][0]}:{$m[1][1]}";
    }
    return $return;
}

function freeproxylist()
{
	$url='https://spys.one/';
	$headers   = Array();
	$headers[] = ':authority:hidemy.name';
    $headers[] = ':method:GET';
    $headers[] = ':path: /en/proxy-list/?type=s';
    $headers[] = ':scheme: https';
    $headers[] = 'accept-encoding: gzip, deflate, br';
    $headers[] = 'accept-language: en-US,en;q=0.9,vi;q=0.8';
    $headers[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'cache-control: max-age=0';
    $headers[] = 'cookie: __cfduid=ddacb79b13fc92711cc20ebda3083687c1616743787; t=213086173';
    $headers[] = "sec-fetch-dest: document";
	$headers[] = "sec-fetch-mode: navigate";
	$headers[] = "sec-fetch-site: none";
	$headers[] = "sec-fetch-user: ?1";
	$headers[] = "upgrade-insecure-requests: 1";
    $ch        = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68");
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_DEFAULT);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
}

function proxy_db(){
	$source = file_get_contents('http://proxydb.net/?protocol=https&country=');
    preg_match_all('/<tbody>(.*?)<\/tbody>/is', $source, $matches);
    preg_match_all('/<tr>(.*?)<\/tr>/is', $matches[1][0], $matches);
    $return = array();
    foreach ($matches[1] as $key => $val) {
        preg_match_all('/<td>(.*?)<\/td>/is', $val, $m);
		$temp_strip = strip_tags($m[0][0]);
        $return[] = "{$temp_strip}";
    }
    return $return;
}


//echo '<html>' . str_replace(',', '<br>',implode(',', proxy_db())) . '</html>';
print str_replace(' ', '<br>', implode(' ', fetch_proxies()));