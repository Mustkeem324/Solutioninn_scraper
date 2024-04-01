<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url=$_GET['url'];
    $text.= $url;//url defined as text
    //echo $text;

    function Cookies(){
        $cookieFile = "cookies.txt";
        $email = "Enter Email";
        $password = "Enter Password";
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.solutioninn.com/sign',
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'eu='.$email.'&pw='.$password.'&rm=0',
        CURLOPT_HTTPHEADER => array(
            'authority: www.solutioninn.com',
            'accept: */*',
            'accept-language: en-US,en;q=0.9',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'dnt: 1',
            'origin: https://www.solutioninn.com',
            'referer: https://www.solutioninn.com/sign',
            'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36',
            'x-requested-with: XMLHttpRequest'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if ($response === false) {
            echo 'cURL error: ' . curl_error($ch);
        }
        $cookieContent = file_get_contents($cookieFile);
        $cookies = array();
        if (!empty($cookieContent)) {
            $cookieLines = explode("\n", $cookieContent);
            foreach ($cookieLines as $cookieLine) {
                $parts = explode("\t", $cookieLine);
                if (count($parts) >= 7) {
                    $name = $parts[5];
                    $value = $parts[6];
                    $cookies[$name] = $value;
                }
            }
        }

        $solutioninncookies =json_encode($cookies, JSON_PRETTY_PRINT); //convert the cookies array to JSON & print it 
        //echo $solutioninncookies;
        $solutioninncookies2 =json_decode($solutioninncookies,true);
        $zendid = $solutioninncookies2['zenid'];
        $u_id = $solutioninncookies2['u_id'];
        $acc_type = $solutioninncookies2['acc_type'];
        if(isset($zendid) && isset($u_id) && isset($acc_type)){
            return [$zendid,$u_id,$acc_type];
        }
        else{
            return [null,null,null];
        }
        
    }
    
    function soultioninnresponse($text){
        $solutioninncookies2 = Cookies();
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $text,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'authority: www.solutioninn.com',
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'accept-language: en-US,en;q=0.9',
            'cache-control: max-age=0',
            'cookie: zenid='.$solutioninncookies2[0].'; u_id='.$solutioninncookies2[1].'; acc_type='.$solutioninncookies2[2].';',
            'dnt: 1',
            'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: document',
            'sec-fetch-mode: navigate',
            'sec-fetch-site: none',
            'sec-fetch-user: ?1',
            'upgrade-insecure-requests: 1',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    if(preg_match("/solutioninn.com/", $text)){
        $response = soultioninnresponse($text);
        echo $response;
        // Delete the cookies file
        if (file_exists($cookieFile)) {
            unlink($cookieFile);
        }
        else{
            echo "cookies files not found";
        }
    }
}
?>