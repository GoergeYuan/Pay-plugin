<?php

class Http_Curl_Query{

	public $response = array();
	public $ch = null;
	public $options = array();

	function __contruct(){
		$this->Http_Curl_Query();
	}

	function Http_Curl_Query(){
		if($this->ch == null){
			$this->ch = curl_init();
		}
	}
	
	function setOpt($name, $value){
		$this->options[$name] = $value;
		curl_setopt($this->ch, $name, $value);
		return $this;
	}

	private function setCurlOpt(){
		if(!in_array(CURLOPT_RETURNTRANSFER, $this->options)){
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		}
		if(!in_array(CURLOPT_REFERER, $this->options)){
			curl_setopt($this->ch, CURLOPT_REFERER, 'http://' . $_SERVER['HTTP_HOST']);
		}
		if(!in_array(CURLOPT_USERAGENT, $this->options)){
			curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		}
		if(!in_array(CURLOPT_HEADER, $this->options)){
			curl_setopt($this->ch, CURLOPT_HEADER, 0);
		}
		return $this;
	}
	
	function httpPost($data = array()){
		curl_setopt($this->ch, CURLOPT_POST, true);

        $this->setCurlOpt();
        if($data){
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->buildQueryString($data));
        }

		$this->options = array();

		$this->response['content'] = curl_exec($this->ch);
		$this->response['error'] = curl_error($this->ch);
		$this->response['errno'] = curl_errno($this->ch);

		$info = curl_getinfo($this->ch);
		$this->response = array_merge($this->response, $info);

		return $this;
	}

	function httpGet($data = array()){
		$this->setCurlOpt();
		$this->options = array();

		$this->response['content'] = curl_exec($this->ch);
		$this->response['error'] = curl_error($this->ch);
		$this->response['errno'] = curl_errno($this->ch);

		$info = curl_getinfo($this->ch);
		$this->response = array_merge($this->response, $info);

		return $this;
	}

    function buildQueryString($data) {
        $querystring = '';
        if (is_array($data)) {
            // Change data in to postable data
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $val2) {
                        $querystring .= urlencode($key).'='.urlencode($val2).'&';
                    }
                } else {
                    $querystring .= urlencode($key).'='.urlencode($val).'&';
                }
            }
            $querystring = substr($querystring, 0, -1); // Eliminate unnecessary &
        } else {
            $querystring = $data;
        }
        return $querystring;
    }

    public function buildQueryUrl($url, $params){
        $queryStr = $this->buildQueryString($params);
        if($queryStr){
            $url .= ((strstr($url, '?') === false) ? '?' : '&');
            $url .= $queryStr;
        }
        return $url;
    }

    public function close(){
        if(is_resource($this->ch)){
            curl_close($this->ch);
        }
    }

    public function __destruct(){
        $this->close();
    }
}