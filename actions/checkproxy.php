<?php
    // //Check Proxy Gate
    // function check_proxy($payload)
    // {
    //     //$proxy = explode(':', $proxy);
    //     $ch = curl_init('https://checkerproxy.net/api/check');
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($payload));
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //     # Return response instead of printing.
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //     # Send request.
    //     $result = curl_exec($ch);
    //     curl_close($ch);
    //     # Print response.
    //     echo "<pre>$result</pre>";
    // }
    // //check_proxy();
    // $listproxy = '40.83.102.86:80
    // 54.95.204.242:8080
    // 208.65.214.172:3128
    // 128.134.187.111:3128
    // 202.61.51.204:3128
    // 60.234.24.162:3128
    // 190.2.130.138:808
    // 157.25.84.210:8080
    // 79.111.13.155:50625
    // 103.12.163.222:55443
    // 154.72.204.122:8080
    // 169.57.1.84:80
    // 165.227.163.91:3128
    // 202.90.133.254:8080
    // 161.202.226.194:80
    // 51.222.137.230:3128
    // 191.96.16.228:3129
    // 40.127.201.29:80
    // 191.96.16.209:3129
    // 191.96.16.161:3129
    // 191.96.16.187:3129
    // 191.96.16.48:3129
    // 191.96.16.16:3129
    // 52.199.230.88:8080
    // 191.96.16.253:3129
    // 185.128.136.252:3128
    // 191.96.16.151:3129
    // 191.96.16.198:3129
    // 43.128.23.107:3128
    // 157.230.103.189:36366
    // 198.24.169.226:8080
    // 27.147.135.165:80
    // 139.99.102.114:80
    // 103.154.160.58:8080
    // 80.191.174.220:8080
    // 41.190.95.20:56167
    // 191.96.16.92:3129
    // 191.96.16.245:3129
    // 217.113.29.211:3128
    // 191.96.16.188:3129
    // 191.96.16.58:3129
    // 157.230.255.230:8118
    // 191.96.16.61:3129
    // 191.96.16.179:3129
    // 51.75.147.40:3128
    // 191.96.16.93:3129
    // 191.96.16.120:3129
    // 191.96.16.70:3129
    // 195.138.73.54:44017
    // 24.193.59.8:8080
    // 200.55.218.202:53281
    // 181.129.43.3:8080
    // 191.96.16.23:3129
    // 51.75.147.41:3128
    // 160.119.54.12:8080
    // 193.226.199.110:32231
    // 191.242.178.209:3128
    // 165.22.81.30:42040
    // 167.172.109.12:36457
    // 175.100.5.52:32721
    // 109.238.222.5:40387
    // 202.40.188.94:40486
    // 88.82.95.146:3128
    // 118.179.173.253:40836
    // 188.166.184.113:3128
    // 31.172.105.144:8080
    // 190.12.95.170:47029
    // 131.153.150.226:8080
    // 62.106.122.90:38678
    // 77.37.131.164:55443
    // 191.96.16.178:3129
    // 191.96.16.13:3129
    // 118.27.27.165:3128
    // 185.37.230.86:8080
    // 191.96.16.124:3129
    // 121.122.107.85:8080
    // 133.167.65.45:8080
    // 51.75.147.44:3128
    // 191.96.16.136:3129
    // 173.251.68.57:4444
    // 37.77.128.162:8080
    // 217.64.109.231:45282
    // 94.253.15.25:37885
    // 5.166.57.222:48558
    // 158.140.167.148:53281
    // 109.86.182.203:3128
    // 124.41.243.72:44716
    // 172.104.124.205:80
    // 194.106.175.218:8080
    // 213.230.69.33:8080
    // 100.24.37.121:3128
    // 124.244.186.246:8080
    // 150.129.148.88:35101
    // 51.158.172.165:8761
    // 37.120.192.154:8080
    // 3.142.114.86:8080
    // 20.195.17.90:3128
    // 103.145.32.98:80
    // 194.62.157.180:3128
    // 138.197.209.229:3128';
    // $dataExplode = explode(PHP_EOL, $listproxy);
    // $arrData->type = 2;
    // $arrData->timeout = 20;
    // $arrData->publish = true;
    // $arrData->proxies = json_encode($dataExplode);

    // echo $exportArr = json_encode($arrData);

    // //echo str_replace('', $exportArr);


    // //echo check_proxy($exportArr);



    //Check Socks Gate

	//Set max execution time
	set_time_limit(100);
    
	if(!isset($_GET['timeout']))
	{
		die("Losing Params Timeout!");
	}
    
    $socksOnly = false;
    if(isset($_GET['proxy_type']))
    {
        if($_GET['proxy_type'] == "socks")
        {
            $socksOnly = true;
            $proxy_type = "socks";
        }
        else
        {
            $proxy_type = "http(s)";
        }
    }
    else
    {
        $proxy_type = "http(s)";
    }

	// Parameter
	if(isset($_GET['ip']) && isset($_GET['port']))
	{
		singleThread($_GET['ip'], $_GET['port'], $_GET['timeout'], true, $socksOnly, $proxy_type);
	}
	else
	{
		die("Payload is Null");
	}
	
	
     
    function CheckMultiProxy($proxies, $timeout, $proxy_type)
	{
		$data = array();
		foreach($proxies as $proxy)
		{
			$parts = explode(':', trim($proxy));
			$url = strtok(curPageURL(),'?');
			$data[] = $url . '?ip=' . $parts[0] . "&port=" . $parts[1] . "&timeout=" . $timeout . "&proxy_type=" . $proxy_type;
		}
		$results = multiThread($data);
		$holder = array();
		foreach($results as $result)
		{
			
			$holder[] = json_decode($result, true)["result"];
		}
		$arr = array("results" => $holder);
		echo json_encode($arr);
	}

    
    function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" .
                $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
     
	 
	 
	 function singleThread($ip, $port, $timeout, $echoResults=true, $socksOnly=false, $proxy_type="http(s)")
	 {
		$passByIPPort= $ip . ":" . $port;

		$url = "http://whatismyipaddress.com/";
		 
		// Get current time
		$loadingtime = microtime(true);
		 
		$theHeader = curl_init($url);
		curl_setopt($theHeader, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($theHeader, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($theHeader, CURLOPT_PROXY, $passByIPPort);
        
        //Socks check
        if($socksOnly)
        {
            curl_setopt($theHeader, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }

		curl_setopt($theHeader, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($theHeader, CURLOPT_SSL_VERIFYPEER, 0);
		 
		//Execute the request
		$curlResponse = curl_exec($theHeader);
		 
		 
		if ($curlResponse === false) 
		{
            //If status  response 'connection reset' is Socks 
            if(curl_errno($theHeader) == 56 && !$socksOnly)
            {
                singleThread($ip, $port, $timeout, $echoResults, true, "socks");
                return;
            }
            
            $arr = array(
                    "result" => array(
                        "success" => false,
                        "error" => curl_error($theHeader),
                        "proxy" => array(
                            "ip" => $ip,
                            "port" => $port,
                            "type" => $proxy_type
                    )
                )
            );
		} 
		else 
		{
			$arr = array(
				"result" => array(
					"success" => true,
					"proxy" => array(
						"ip" => $ip,
						"port" => $port,
						"speed" => floor((microtime(true) - $loadingtime)*1000), //Microtime to Milliseconds
                        "type" => $proxy_type
					)
				)
			);
		}
        if($echoResults)
        { 
            echo json_encode($arr);
        }
        return $arr;
	 }

	 function multiThread($data, $options = array()) 
	 {
	 
	  $curly = array();
	  $result = array();
	 
	  // multi handle
	  $mh = curl_multi_init();
	 
	  foreach ($data as $id => $d) {
	 
		$curly[$id] = curl_init();
	 
		$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
		curl_setopt($curly[$id], CURLOPT_URL,            $url);
		curl_setopt($curly[$id], CURLOPT_HEADER,         0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
	 
		// post?
		if (is_array($d)) {
		  if (!empty($d['post'])) {
			curl_setopt($curly[$id], CURLOPT_POST,       1);
			curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
		  }
		}
	 
		// extra options?
		if (!empty($options)) {
		  curl_setopt_array($curly[$id], $options);
		}
	 
		curl_multi_add_handle($mh, $curly[$id]);
	  }
	 
	  // execute the handles
	  $running = null;
	  do {
		curl_multi_exec($mh, $running);
	  } while($running > 0);
	 
	 
	  // get content and remove handles
	  foreach($curly as $id => $c) {
		$result[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	  }
	 
	  // all done
	  curl_multi_close($mh);
	 
	  return $result;
	}	

?>