<?php
class Process_Query{
    
    private $statusCode = 0;
    private $result;

    public $type;
    public $url = '';
    public $timeOut = 120;
    public $debug = false;
    public $referer = '';
    public $userAgent = '';
    public $obj;
    public $error = '';
    
    function __construct(){
        $this->Process_Query();
    }

    function Process_Query(){
        if(function_exists('curl_init') && function_exists('curl_exec')){
            $this->type = 'curl';
        } else if(function_exists('fsockopen')){
            $this->type = 'fsockopen';
        } else {
            exit('not support');
        }

        $this->referer = varGet($_SERVER, 'HTTP_REFERER');
        $this->userAgent = varGet($_SERVER, 'HTTP_USER_AGENT');

    }

    function query($data){
        if($this->type == 'curl'){
            return $this->curlQuery($data);
        } else if($this->type == 'fsockopen'){
            return $this->fsockopenQuery($data);
        } else {
            return false;
        }
    }

    function curlQuery($data){
        $this->obj = new Http_Curl_Query();
        $headers[] = "Expect: ";

        $status = $this->obj->setOpt(CURLOPT_URL, $this->url)
            ->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE)
            ->setOpt(CURLOPT_SSL_VERIFYHOST, 0)
            ->setOpt(CURLOPT_SSL_CIPHER_LIST, 'DEFAULT')

            ->setOpt(CURLOPT_SSLVERSION, 4)
            ->setOpt(CURLOPT_HTTPHEADER, $headers)
            ->setOpt(CURLOPT_TIMEOUT, $this->timeOut)
            ->setOpt(CURLOPT_CONNECTTIMEOUT, $this->timeOut)
            ->setOpt(CURLOPT_FRESH_CONNECT, 1)
            ->setOpt(CURLOPT_REFERER, $this->referer)
            ->httpPost($data)->response['http_code'];

        $this->statusCode = $status;

        if($status == 200){
            return true;
        } else {
            $this->error = $this->obj->response['error'];
            if($this->debug){
                httpDebug($this->error, $this->obj);
            }
            return false;
        }
    }

    function fsockopenQuery($data){
        $parts = parse_url($this->url);
        $host = $parts['host'];
        $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : 'http';
        $path = isset($parts['path']) ? $parts['path'] : '/';

        if(isset($parts['port'])){
            $port = intval($parts['port']);
        } else {
            if($scheme == 'https'){
                $port = 443;
            } else {
                $port = 80;
            }
        }
        $this->obj = new Http_Client($host, $port, $scheme);
        $this->obj->setDebug($this->debug);
        $this->obj->setPersistReferers(false);
        $this->obj->referer = $this->referer;
        $this->obj->setUserAgent($this->userAgent);
        $this->obj->timeout = $this->timeOut;

        $flag = $this->obj->post($path, $data);
        $this->statusCode = $this->obj->getStatus();
        if($flag == false){
            $this->error = $this->obj->getError();
        }
        return $flag;
    }

    function getContent(){
        if($this->type == 'curl'){
            if(isset($this->obj->response['content'])){
                return $this->obj->response['content'];
            }
        } else if($this->type == 'fsockopen'){
            return $this->obj->getContent();
        }

        return NULL;
    }

    function getStatusCode(){
        return $this->statusCode;
    }
}