<?php
$typeBoost = $_GET['typeboost'];
$refHeader = $_GET['refheader'];
$userAgents = $_GET['ua'];
$socksDame = $_GET['socksdame'];
$siteBoost = $_GET['siteboost'];
$extraLinkBoost = $_GET['extralinkboost'];

if($typeBoost == 'singlethread')
{
//    echo $typeBoost.' | ';
//    echo $refHeader.' | ';
//    echo $userAgents.' | ';
//    echo $socksDame.' | ';
//    echo $siteBoost.' | ';
    $proxyauth = 'user:password'; ////PRIVATE proxy
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_PROXY, $socksDame);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgents);
    curl_setopt($ch, CURLOPT_URL, $siteBoost);
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // Req HTTP respoinse
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //redirects then go to the final redirected URL.
    curl_setopt($ch, CURLOPT_REFERER, $refHeader);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Display screen need echo to display
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely. (fix php after 30 seconds by default)
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // don't verify ssl certificates, allows https scraping
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // don't verify ssl host, allows https scraping
    $result = curl_exec($ch);
    if ($result === false) {
        $arr_data = array(
            "result" => array(
                "success" => false,
                "socks" => $socksDame,
                "referer" => $refHeader,
                "message" => curl_error($ch)
            )
        );
        echo json_encode($arr_data);
      } else {
        $arr_data = array(
            "result" => array(
                "success" => true,
                "socks" => $socksDame,
                "referer" => $refHeader,
                "message" => 'Cool! Payload success'
            )
        );
        echo json_encode($arr_data);
      
        }
    curl_close($ch);
	//var_dump($result);
}
elseif($typeBoost == 'multithread')
{
    $req = array();
    $extraLink = explode(",", $extraLinkBoost);
    $mch = curl_multi_init();
    
    foreach($extraLink as $exkey => $exlinkvalue) //ex key and ex value
    {
        $req[$exkey] = array();
        $req[$exkey]['url'] = $exlinkvalue;
        $req[$exkey]['curl_handle'] = curl_init($exlinkvalue); // create normal cURL handle
        //CONFIG OPTIONS FOR cURL
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_PROXY, $socksDame);
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_USERAGENT, $userAgents);
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_FAILONERROR, true); // Req HTTP respoinse
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_FOLLOWLOCATION, true); //redirects then go to the final redirected URL.
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_REFERER, $refHeader);
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_ENCODING, "gzip,deflate");
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_AUTOREFERER, true);
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_RETURNTRANSFER, 1); // Display screen need echo to display
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_HEADER, 0);
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_CONNECTTIMEOUT, 0); //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely. (fix php after 30 seconds by default)
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_TIMEOUT, 60); //timeout in seconds
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_SSL_VERIFYPEER, false); // don't verify ssl certificates, allows https scraping
        curl_setopt($req[$exkey]['curl_handle'], CURLOPT_SSL_VERIFYHOST, false); // don't verify ssl host, allows https scraping
    
        //Add ever single curl to curl multile handle
        curl_multi_add_handle($mch, $req[$exkey]['curl_handle']);
    }

    //Execute requests using curl_multi_exec solve block and 100% cpu
    $executeCurl = false;
    do {
        curl_multi_exec($mch, $executeCurl);
        curl_multi_select($mch); // Wait for curl multi connect
    } while ($executeCurl); // set the variable to FALSE value once it has finished

    $arr_data_res = array();
    foreach($req as $reqkey => $reqval){
        // curl_multi_remove_handle($mch, $reqval['curl_handle']);
        // $req[$reqkey]['content'] = curl_multi_getcontent($reqval['curl_handle']);
        // $req[$reqkey]['http_code'] = curl_getinfo($reqval['curl_handle'], CURLINFO_HTTP_CODE);
        
        $domain = parse_url($url, PHP_URL_HOST);
        $curlErrorCode = curl_errno($req[$reqkey]['curl_handle']);
        if ($curlErrorCode === 0) {
            $info = curl_getinfo($req[$reqkey]['curl_handle']);
            $info['url'] = trim($info['url']);
            if ($info['http_code'] == 200) {
                $content = curl_multi_getcontent($req[$reqkey]['curl_handle']);
                //$res[$domain] = sprintf("#HTTP-OK %0.2f kb returned", strlen($content) / 1024);
                    $arr_data = array(
                                "result" => array(
                                "success" => true,
                                "socks" => $socksDame,
                                "referer" => $refHeader,
                                "message" => sprintf("#HTTP-OK %0.2f kb returned", strlen($content) / 1024)
                            )
                    );
                    array_push($arr_data_res, $arr_data);
                    // echo json_encode($arr_data_success[0]);
                    //echo $arr_data_res;
                    // sleep(3);

                    
            }else {
                //$res[$domain] = "#HTTP-ERROR {$info['http_code'] }  for : {$info['url']}";
                    $arr_data = array(
                                "result" => array(
                                "success" => false,
                                "socks" => $socksDame,
                                "referer" => $refHeader,
                                "message" => "#HTTP-ERROR {$info['http_code'] }  for : {$info['url']}"
                            )
                    );
                    array_push($arr_data_res, $arr_data);
                   //echo json_encode($arr_data);
            }
        } else {
            //$res[$domain] = sprintf("#CURL-ERROR %d: %s ", $curlErrorCode, curl_error($req[$reqkey]['curl_handle']));
			    $arr_data = array(
                    "result" => array(
                    "success" => false,
                    "socks" => $socksDame,
                    "referer" => $refHeader,
                    "message" => sprintf("#CURL-ERROR %d: %s ", $curlErrorCode, curl_error($req[$reqkey]['curl_handle']))
                )
            );
            array_push($arr_data_res, $arr_data);
        }
        curl_multi_remove_handle($mch, $reqval['curl_handle']);

        //close the handle.
        curl_close($req[$reqkey]['curl_handle']);
        flush();
        ob_flush();
    }
    echo json_encode($arr_data_res);
    curl_multi_close($mch);
}
else{
    echo 'WE NEED PAYLOAD';
}


?>