<?php
class ControllerPaymentVmcard extends Controller{

    public function index(){

        $this->load->language('payment/vmcard');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');

        // $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('vmcard_new_order_status'));
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $formData['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        $formData['MerNo'] = trim($this->config->get('vmcard_merchant_no'));
        $formData['BillNo'] = $this->session->data['order_id'];
        $formData['MD5key'] = trim(html_entity_decode($this->config->get('vmcard_md5key'), ENT_QUOTES, 'UTF-8'));
        $formData['Language'] = strtolower($this->config->get('vmcard_language'));
        $formData['ReturnURL'] = trim($this->config->get('vmcard_returnurl'));
        $formData['Currency'] = $this->mapCurrency($order_info['currency_code']);

        if($this->cart->hasShipping()) {
            $firstName = $order_info['shipping_firstname'];
            $lastName = $order_info['shipping_lastname'];
            $address = ($order_info['shipping_address_1']) ? $order_info['shipping_address_1'] : $order_info['shipping_address_2'];
            $city = $order_info['shipping_city'];
            $state = $order_info['shipping_zone'];
            $country = $order_info['shipping_country'];
            $zipcode = $order_info['shipping_postcode'];
        } else {
            $firstName = $order_info['payment_firstname'];
            $lastName = $order_info['payment_lastname'];
            $address = ($order_info['payment_address_1']) ? $order_info['payment_address_1'] : $order_info['payment_address_2'];
            $city = $order_info['payment_city'];
            $state = $order_info['payment_zone'];
            $country = $order_info['payment_country'];
            $zipcode = $order_info['payment_postcode'];
        }

        $formData['DeliveryFirstName']  = html_entity_decode($firstName, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryLastName']   = html_entity_decode($lastName, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryAddress']    = html_entity_decode($address, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryCity']       = html_entity_decode($city, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryState']      = html_entity_decode($state, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryCountry']    = html_entity_decode($country, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryZipCode']    = html_entity_decode($zipcode, ENT_QUOTES, 'UTF-8');
        $formData['DeliveryEmail']      = html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');
        $formData['DeliveryPhone']      = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');

        //商品信息
        $formData['Products'] = $this->products($order_info);

        $cryptField = array('MerNo', 'BillNo', 'Currency', 'Amount', 'Language', 'ReturnURL', 'MD5key');
        $cryptStr = '';
        foreach($cryptField as $field){
            $cryptStr .= htmlspecialchars_decode($formData[$field]);
        }
        $formData['MD5info'] = strtoupper(md5($cryptStr));

        $url_create_order = $this->url->link('payment/vmcard/confirmOrder');
        if(strpos($url_create_order, 'http://') !== 0){
            $url_create_order = HTTP_SERVER . ltrim($url_create_order);
        }

        $data['url_create_order'] = $url_create_order;
        $data['action'] = trim($this->config->get('vmcard_gateway'));
        $data['formData'] = $formData;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/vmcard.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/vmcard.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/vmcard.tpl', $data);
        }
    }

    public function confirmOrder(){
        if($this->session->data['payment_method']['code'] == 'vmcard') {
            //$this->load->model('checkout/order');
            // $this->load->model('payment/vmcard');
            if($this->checkConfigMail() === true && $this->config->get('config_email')){
                $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('vmcard_new_order_status'));
            }else{
                $this->load->model('payment/vmcard');
                $this->model_payment_vmcard->addOrder($this->session->data['order_id'], $this->config->get('vmcard_new_order_status'), 'order create with creditcard');   
            }
            // $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('vmcard_new_order_status'));
        }
    }

    private function mapCurrency($currencyName){
        $currencyList = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14');
        $currencyName = trim(strtoupper($currencyName));
        return isset($currencyList[$currencyName]) ? $currencyList[$currencyName] : '';
    }

    public function callback(){

        if(!isset($this->request->post['BillNo']) or !$this->request->post['BillNo']){
            return;
        }
        $data['redirect_time'] = 3;
        $this->language->load('payment/vmcard');
        $data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
        if(!isset($this->request->server['HTTPS']) or ($this->request->server['HTTPS'] != 'on')) {
            $data['base'] = HTTP_SERVER;
        } else {
            $data['base'] = HTTPS_SERVER;
        }

        $data['charset'] = $this->language->get('charset');
        $data['language'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');
        $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
        $data['text_response'] = $this->language->get('text_response');
        $data['text_success'] = $this->language->get('text_success');
        $data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
        $data['text_failure'] = $this->language->get('text_failure');

        $data['text_billno'] = '<font color="green">' . $this->request->post['BillNo'] . '</font>';
        $data['text_result'] = '<font color="green">' . $this->request->post['Result'] . '</font>';

        if($this->request->get['route'] != 'checkout/guest_step_3'){
            $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
        } else {
            $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
        }

        $cryptField = array('BillNo', 'Currency', 'Amount', 'Succeed', 'MD5key');
        $rData = $this->request->post;
        $rData['MD5key'] = html_entity_decode($this->config->get('vmcard_md5key'), ENT_QUOTES, 'UTF-8');
        $cryptStr = '';
        foreach($cryptField as $field){
            $cryptStr .= $rData[$field];
        }
        $MD5sign = strtoupper(md5($cryptStr));

        if($MD5sign == $rData['MD5info']) {
            $this->load->model('checkout/order');

            $message = "OrderNo: {$rData['BillNo']}\nAmount: {$rData['Amount']}&nbsp&nbsp{$rData['CurrencyName']}\nPayResult: {$rData['Result']}\nResponseCode: {$rData['Succeed']}\n";
            

            $defaultDir = 'default/template/payment/';
            $themeDir = $this->config->get('config_template') . '/template/payment/';

            if($rData['Succeed'] == '88') {
                $data['continue'] = HTTP_SERVER . 'index.php?route=checkout/success';
                if(file_exists($themeDir . 'vmcard_success.tpl')) {
                    $template = $themeDir . 'vmcard_success.tpl';
                } else {
                    $template = $defaultDir . 'vmcard_success.tpl';
                }

                $order_status_id = $this->config->get('vmcard_success_order_status');
            } else if($rData['Succeed'] == '19') {
                $data['continue'] = HTTP_SERVER . 'index.php?route=checkout/success';
                if(file_exists($themeDir . 'vmcard_success.tpl')) {
                    $template = $themeDir . 'vmcard_success.tpl';
                } else {
                    $template = $defaultDir . 'vmcard_success.tpl';
                }

                $order_status_id = $this->config->get('vmcard_process_order_status');
            } else {
                $data['continue'] = HTTP_SERVER . 'index.php?route=checkout/cart';
                if(file_exists($themeDir . 'vmcard_failure.tpl')) {
                    $template = $themeDir . 'vmcard_failure.tpl';
                } else {
                    $template = $defaultDir . 'vmcard_failure.tpl';
                }

                $order_status_id = $this->config->get('vmcard_failed_order_status');
            }

            // $this->load->model('payment/vmcard');
            // $this->model_payment_vmcard->addOrder($this->session->data['order_id'], $this->config->get('vmcard_new_order_status'), 'order create with creditcard');
            if($this->checkConfigMail() === true && $this->config->get('config_email')){
                // $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($rData['BillNo'], $order_status_id, $message, true);
            }else{
                $this->load->model('payment/vmcard');
                $this->model_payment_vmcard->addOrder($rData['BillNo'], $order_status_id, $message);   
            }
            // $this->model_checkout_order->addOrderHistory($rData['BillNo'], $order_status_id, $message, true);

            $data = array_merge($data, $rData);
            $this->response->setOutput($this->load->view($template, $data));
        } else {
            $data = array_merge($data, $this->request->post);

            $defaultTemplate = 'default/template/payment/vmcard_failure.tpl';
            $themeTemplate = $this->config->get('config_template') . 'template/payment/vmcard_failure.tpl';
            if(file_exists($themeTemplate)){
                $template = $themeTemplate;
            } else {
                $template = $defaultTemplate;
            }

            $this->response->setOutput($this->load->view($template, $data));
        }
    }

    private function checkConfigMail(){
        $mail = (array)$this->config->get('config_mail');
        if(isset($mail['smtp_hostname']) && isset($mail['smtp_username']) && isset($mail['smtp_password']) && $mail['smtp_username'] && $mail['smtp_password'] && $mail['smtp_hostname']){
            return true;
        }
        return false;
    }


    /*
    *
    *   商品信息
    *
    */
    function products($order_info){

        $products = $this->cart->getProducts();
        $GoodList = "";
        if($products){      
            foreach($products as $product){
                $GoodList=$GoodList."<GoodsName>".$product['name']."</GoodsName><Qty>".$product['quantity'].
                                "</Qty><Price>".$this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], FALSE)."</Price><Currency>".$order_info['currency_code']."</Currency>";
            }
            $GoodListInfo = "<Goods>".$GoodList."</Goods>";
            }else{

                $GoodListInfo = "no products";
            }

            return htmlspecialchars($GoodListInfo, ENT_QUOTES, 'UTF-8');
        }
    
}


