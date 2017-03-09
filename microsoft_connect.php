<?php

require_once ('CurlWrapper.php');
function microsoft_connect ($cookie, $login, $password) {
    $wrapper = new CurlWrapper($cookie);
    $body = $wrapper->get('https://intra.epitech.eu');
    if (!$body) {
    	return 401;
    }
    $parts = [];
    preg_match('#"https://login.microsoftonline([^"]+)"#', $body, $parts);
    $url = 'https://login.microsoftonline' . $parts[1];
    $ms1 = $wrapper->get($url);
    preg_match("/Constants.CONTEXT = '([^']+)';/", $ms1, $parts);
    if (!$ms1 || !isset($parts[1])) {
    	return 401;
    }
    $sts = $parts[1];
    $urlRealm = 'https://login.microsoftonline.com/common/userrealm/?user=' . $login . '&api-version=2.1&stsRequest=' . $sts . '&checkForMicrosoftAccount=true';
    $realmDatas = json_decode($wrapper->get($urlRealm));
    if (!$realmDatas || !isset($realmDatas->AuthURL)) {
    	return 401;
    }
    $realmUrl = $realmDatas->AuthURL;
    $epitechLoginPage = $wrapper->get($realmUrl);
    preg_match('#action="/adfs([^"]+)"#', $epitechLoginPage, $parts);
    if (!$epitechLoginPage || !isset($parts[1])) {
    	return 401;
    }
    $loginUrl = 'https://sts.epitech.eu/adfs' . $parts[1];
    $formResult = $wrapper->post($loginUrl, [
        'UserName' => $login,
        'Password' => $password,
        'Kmsi' => 'true',
        'AuthMethod' => 'FormsAuthentication'
        ]);
    preg_match('/action="([^"]+)"/', $formResult, $parts);
    if (!$formResult || !isset($parts[1])) {
    	return 401;
    }
    $microsoftLoginUrl = $parts[1];
    preg_match('/name="wa" value="([^"]+)"/', $formResult, $parts);
    if (!$formResult || !isset($parts[1])) {
    	return 401;
    }
    $wa = $parts[1];
    preg_match('/name="wresult" value="([^"]+)"/', $formResult, $parts);
    if (!$formResult || !isset($parts[1])) {
    	return 401;
    }
    $wresult = html_entity_decode($parts[1]);
    preg_match('/name="wctx" value="([^"]+)"/', $formResult, $parts);
    if (!$formResult || !isset($parts[1])) {
    	return 401;
    }
    $wctx = html_entity_decode($parts[1]);
    $microsoftLoginResult = $wrapper->post($microsoftLoginUrl, [
        'wa' => $wa,
        'wresult' => $wresult,
        'wctx' => $wctx
        ]);
    if (!$microsoftLoginResult) {
    	return 401;
    }
    if (strstr($microsoftLoginResult, "consent_accept_form") !== false)
    {
        preg_match('/name="ctx" value="([^"]+)"/', $microsoftLoginResult, $parts);
        $ctx = $parts[1];
        preg_match('/name="flowToken" value="([^"]+)"/', $microsoftLoginResult, $parts);
        $flowToken = $parts[1];
        preg_match('/name="canary" value="([^"]+)"/', $microsoftLoginResult, $parts);
        $canary = $parts[1];
        $urlParts = explode('/', str_replace('//', '/', $microsoftLoginUrl));
        $targetUrl = $urlParts[0] . '//' . $urlParts[1] . '/common/Consent/Grant';
        $wrapper->post($targetUrl, [
            'ctx' => $ctx,
            'flowToken' => $flowToken,
            'canary' => $canary
            ]);
    }
   	return $wrapper->getHttpResponseCode();
}