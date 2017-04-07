<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


class SC_Helper_Request {
    /**
     * Store additional order information
     * 支付请求网关
     * @param URL $url          请求网关
     * @param string $data      请求数据
     * @param number $timeout   默认请求时间
     */
    public static function vpostAPI($url, $data ,$timeout = 30) {
    
        if(function_exists('curl_init') && function_exists('curl_exec')){  //curl
    
        include 'Http_Client_Curl.php';
            $type = 'curl';
            $httpCurlQuery = new Http_Curl_Query();
            $headers[] = "Expect: ";
            $status = $httpCurlQuery
            ->setOpt(CURLOPT_URL, $url)
            ->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE)
            ->setOpt(CURLOPT_SSL_VERIFYHOST, 0)
            ->setOpt(CURLOPT_HTTPHEADER, $headers)
            ->setOpt(CURLOPT_TIMEOUT, $timeout)
            ->setOpt(CURLOPT_CONNECTTIMEOUT, $timeout)
            ->setOpt(CURLOPT_FRESH_CONNECT, 1)
            ->httpPost($data)->response['http_code'];
    
            if($status == 200){
                $result = $httpCurlQuery->response['content'];
            }else{
                $erron = $httpCurlQuery->response['errno'];
                $http_code = $httpCurlQuery->response['http_code'];
                $errorMsg = $httpCurlQuery->response['error'];
    
            }
    
        }elseif (function_exists('fsockopen')) {     //fsockopen
            $parts = parse_url($url);
            $host = $parts['host'];
            $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
            $path = isset($parts['path']) ? $parts['path'] : '/';
            if(isset($parts['port'])){
                $port = intval($parts['port']);
            }else{
                if($scheme == 'https'){
                    $port = 443;
                }else{
                    $port = 80;
                }
            }
        include 'Http_Client_Socket.php';
            $type = 'fsockopen';
            $httpClient = new Http_Client($host);
            $httpClient->setDebug(false);         //是否开启调试模式
            $httpClient->setPersistReferers(false);
            $httpClient->referer = $_SERVER['HTTP_REFERER'];
            $httpClient->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            $httpClient->timeout = $timeout;
    
            $flag = $httpClient->post($path, $data);
            $status = $httpClient->getStatus();
            if($flag === true){
                $result = $httpClient->getContent();
            }else{
                $errorMsg = $httpClient->getError();
            }
    
        }else{
            $errorMsg = 'curl or fsockopen is not enable';
        }
    
        if($status == 200 && $result){
            $payResult = $result;
        }elseif($status !== 200 && $type == 'curl'){
    
            $payResult = 'Succeed=500&Result=curl request error :'.$errorMsg;
    
        }elseif($status !== 200 && $type == 'fsockopen'){
    
            $payResult = 'Succeed=500&Result=fsockopen request error :'.$errorMsg;
        }else{
            $payResult  = 'Succeed=500&Result=request error';
        }
        parse_str($result,$myArray);
    include 'System_Response.php';
        $isErrResponse = new System_Response();
        $errResponse = $isErrResponse->checkinfo($myArray['Succeed']);
    
        return $payResult.'&description='.$errResponse;
    }

    
}