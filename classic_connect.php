<?php


function classic_connect ($cookie, $login, $password) {
	$c = curl_init('https://intra.epitech.eu');
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, 'login=' . $login . '&password=' . urlencode($password) . '&remind=true&format=json');
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    $page = curl_exec($c);
    $infos = curl_getinfo($c);
    $http_code = $infos['http_code'];
    curl_close($c);
    return $http_code;
}