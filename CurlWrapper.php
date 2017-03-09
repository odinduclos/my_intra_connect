<?php

class CurlWrapper {
	private $curlopt = array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HEADER => false,
	);
	private $cookie = '';
	private $lastP = '';
	private $lastI = '';

	public function __construct ($_cookie) {
		$this->cookie = $_cookie;
	}

	public function get ($url, $cookie = false) {
		$c = curl_init($url);
		foreach ($this->curlopt as $key => $value) {
			curl_setopt($c, $key, $value);
		}
		if ($cookie) {
			curl_setopt($c, CURLOPT_COOKIEFILE, $this->cookie);
		}
		$p = curl_exec($c);
		$this->lastP = $p;
		$this->lastI = curl_getinfo($c);
		curl_close($c);
		return $p;
	}

	public function post ($url, $params) {
		$c = curl_init($url);
		$fields_string = '';
		foreach ($this->curlopt as $key => $value) {
			curl_setopt($c, $key, $value);
		}
		foreach($params as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
		rtrim($fields_string, '&');
    	curl_setopt($c, CURLOPT_COOKIEFILE, $this->cookie);
    	curl_setopt($c, CURLOPT_COOKIEJAR, $this->cookie);
		curl_setopt($c, CURLOPT_POST, count($params));
		curl_setopt($c, CURLOPT_POSTFIELDS, $fields_string);
		$p = curl_exec($c);
		$this->lastP = $p;
		$this->lastI = curl_getinfo($c);
		curl_close($c);
		return $p;
	}

	public function __toString() {
		return $this->lastP;
	}

	public function getHttpResponseCode () {
		return $this->lastI['http_code'];
	}

	public function getAllResponseHeaders () {
		return $this->lastI;
	}
}