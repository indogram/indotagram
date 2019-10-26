<?php
$data=array('by'=>'indotagram.com',
           'url'=>'http://www.indotagram.com/users/getData',
            );

function Submit($url,$fields)
    {
		$field_string = http_build_query($fields);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		curl_setopt($ch,CURLOPT_REFERER,$url);          
		curl_setopt($ch,CURLOPT_TIMEOUT,5);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT,'Opera/9.80 (Android; Opera Mini/7.6.40234/37.7148; U; id) Presto/2.12.423 Version/12.16');
		curl_setopt($ch,CURLOPT_COOKIEFILE,'login.txt');
		curl_setopt($ch,CURLOPT_COOKIEJAR,'login.txt');
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$field_string);   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$body = curl_exec($ch);
	  return $body;
		curl_close($ch);
    }
    
function proccess($ighost, $useragent, $url, $cookie = 0, $data = 0, $httpheader = array(), $proxy = 0, $userpwd = 0, $is_socks5 = 0)
    {
    $url = 'https://i.instagram.com/api/v1/'.$url;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    if($proxy):
      curl_setopt($ch, CURLOPT_PROXY, $proxy);
    endif;
    if($httpheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if ($data):
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
     $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if(!$httpcode) return false; else{
      $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
      $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
      curl_close($ch);
      return array($header, $body);
		  }
	}
	
function hook($data,$signature,$key_version) 
   {
   return 'signed_body=' . hash_hmac('sha256', $data, $signature) . '.' . $data.'&ig_sig_key_version='.$key_version;
   	}
$CY="\e[36m"; $GR="\e[2;32m"; $OG="\e[92m"; $WH="\e[37m"; $RD="\e[31m"; $YL="\e[33m"; $BF="\e[34m"; $DF="\e[39m"; $OR="\e[33m"; $PP="\e[35m"; $B="\e[1m"; $CC="\e[0m";
$BL="\e[0;30m";
$II = Submit($data['url'],array('by'=>$data['by']));
$ikeh = json_decode($II,true);
if(empty($ikeh)){
echo"\n\e[1;31mConection Error.. Try Again..\n\e[37m";
}elseif($ikeh['status']=='fail'){
echo"\n\e[1;31m".$ikeh['message']."\n\e[37m";
}else{
echo"\n\e[1m\e[37mInstagram Generate Cookie Versi ".$ikeh['data']['sign_version']."";
echo"\n\e[2;32mhttp://www.indotagram.com";
echo"\n\e[31mCode By Endo Sulistio\n";
echo"\n".$WH."? ".$OR."Insert Username : ";
$mu=trim(fgets(STDIN));
echo $WH."? ".$OR."Insert Password : \e[0;30m";$mp=trim(fgets(STDIN));
echo"\e[1;36m  |Try to login..\n";
$login = 	hook('{"_csrftoken":null,"device_id":"'.$ikeh['data']['device_id'].'"}',$ikeh['data']['signature'],$ikeh['data']['key_version']);
$login = proccess(1, $ikeh['data']['useragent'], 'accounts/read_msisdn_header/', 0,$login); 
preg_match_all('%Set-Cookie: (.*?);%',$login[0],$d);
for($o=0;$o<count($d[0]);$o++)$cookie.=$d[1][$o].";";
$II=hook('{"phone_id":"'.$ikeh['data']['phone_id'].'",
            "_csrftoken":"null",
            "username":"'.$mu.'",
            "adid":"'.$ikeh['data']['uuid'].'",
            "guid":"'.$$ikeh['data']['guid'].'",
            "device_id":"'.$ikeh['data']['device_id'].'",
            "password":"'.$mp.'",
            "login_attempt_count":"0"}',
            $ikeh['data']['signature'],
            $ikeh['data']['key_version']);
$login = proccess(1, $ikeh['data']['useragent'], 'accounts/login/', $cookie, $II);
preg_match_all('%Set-Cookie: (.*?);%',$login[0],$d);
for($o=0;$o<count($d[0]);$o++)$cookiee.=$d[1][$o].";";
if(json_decode($login['1'])->status=='ok'){
echo"\e[1;32m  |Success..\n";
echo"\n\e[0m".$cookiee."\n\n";
}elseif(json_decode($login['1'])->error_title){
echo"\e[1;31m  |".json_decode($login['1'])->error_title."\n";
}	else{
echo"\e[1;31m  |".json_decode($login['1'])->error_type."\n";
}

echo$WH;
}
	
