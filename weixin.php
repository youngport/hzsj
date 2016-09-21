<?php
$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=q7l5psDkKyuK5HiCJGW4T7kJKKyGUXboYC9ouYpQ_GkQJfN_Z0P11aok-5ZW_rFqy0_phXsjY9JUxi2ECK-gfrx4kgi5AKeFarPenRx-jpI";
$data = "{
    \"touser\": \"oc4cpuEkJoj1Qf_dPLtmaGIQLR2Q\", 
    \"msgtype\": \"text\", 
    \"text\": {
        \"content\": \"Hello World\"
    }
}"; 
//var_dump(http_post_data($url, $data));  

$ch = curl_init ();

curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_HEADER, 0 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
$return = curl_exec ( $ch );
curl_close ( $ch );
var_dump(curl_close ( $ch ));
var_dump($return); 

/* $ch = curl_init();
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$return = curl_exec ( $ch );
curl_close ( $ch );
$output_array = json_decode($return,true);
echo $output_array['access_token']; */
?>