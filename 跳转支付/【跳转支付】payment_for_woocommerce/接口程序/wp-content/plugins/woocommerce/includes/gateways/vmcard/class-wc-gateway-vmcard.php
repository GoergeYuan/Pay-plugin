<?php

if(!defined('ABSPATH')) exit;

/**
 * vmcard Standard Payment Gateway
 *
 * Provides a vmcard Standard Payment Gateway.
 *
 * @class       WC_Jf
 * @extends     WC_Gateway_Jf
 * @version     1.01
 * @package     WooCommerce/Classes/Payment
 * @author      CreditCard
 */

class WC_Gateway_Vmcard extends WC_Payment_Gateway{


    private $vmcard_payment_url;
    // private $vmcard_payment_url = 'https://payment.hhtpay.com/sslpayment';
    private $form_submission_method                = true;

    function __construct(){

        $this->id                     = 'vmcard';
        $this->icon                   = apply_filters('woocommerce_vmcard_icon', '');
        $this->has_field              = false;
        $this->method_title           = __('CreditCard', 'woocommerce');
        $this->icon = apply_filters('woocommerce_FirstTeam_icon', home_url('wp-content/plugins/woocommerce/assets/images/vmj.png', __FILE__));

        //获取返回网址
        if ($_SERVER['HTTPS'] != "on") {
            $http_head =  "http://".$_SERVER['HTTP_HOST'];
         }else{
            $http_head =  "https://".$_SERVER['HTTP_HOST'];
        }

        $this->vmcard_payment_url = $http_head . '/submitOrder.php';
        $this->vmcard_return_url = $http_head . '/checkout/order-received/';

        $this->init_form_fields();
        $this->init_settings();
        
        $this->title                  = $this->get_option('title');
        $this->description            = $this->get_option('description');
        $this->vmcard_merchant_no       = $this->get_option('vmcard_merchant_no');
        $this->vmcard_md5_key           = $this->get_option('vmcard_md5_key');
        $pay_url = $this->get_option('vmcard_gateway_url');
        if($pay_url){
            $this->vmcard_payment_url       = $pay_url;
        }
        $this->vmcard_return_url        = $this->get_option('vmcard_return_url');
        $this->vmcard_langauge          = $this->get_option('vmcard_language');
        $this->form_submission_method = $this->get_option('vmcard_request_type');
        $this->paylog                  = $this->get_option('paylog');

        add_action('woocommerce_receipt_vmcard', array($this, 'receipt_page'));
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_thankyou_vmcard', array($this, 'thankyou_page'));
    }

    public function init_form_fields(){

        $this->form_fields  = array(
            'enabled'   => array(
                'title' => __( 'Enable/Disable:', 'woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Enable CreditCard Payment', 'woocommerce' ),
                'default' => 'yes'              
                ),
            'title'     => array(
                'title' => __( 'Title:', 'woocommerce' ),
                'type' => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                'default' => __( 'CreditCard Payment', 'woocommerce' ),
                'desc_tip'      => true,
                ),
            'description'   => array(
                'title' => __( 'Customer Message:', 'woocommerce' ),
                'type' => 'textarea',
                'description' => __( 'Give the customer instructions for paying via CreditCard, and let them know that their order won\'t be shipping until the money is received.', 'woocommerce' ),
                'default' => __( 'You will be redirect to Visa Creditcard Payment Server.', 'woocommerce' )
                ),
            'vmcard_merchant_no'  => array(
                'title'     => __('CreditCard merchant no:', 'woocommerce'),
                'type'      => 'text',
                'description'   => __('CreditCard merchant no', 'woocommerce'),
                'default'       => '10003'
                ),
            'vmcard_md5_key'      => array(
                'title'     => __('CreditCard md5key:', 'woocommerce'),
                'type'      => 'text',
                'description'   => __('CreditCard md5 key', 'woocommerce'),
                'default'       => '12345678'
                ),

            'vmcard_return_url'   => array(
                'title'     => __('CreditCard return url:', 'woocommerce'),
                'type'      => 'text',
                'description'   => __('CreditCard return url eg: ' . $this->get_return_url(), 'woocommerce'),
                'default'       => $this->vmcard_return_url
                ),
            'vmcard_gateway_url'  => array(
                    'title'     => __('CreditCard gateway url:'),
                    'type'      => 'text',
                    'description'   => __('CreditCard gateway url:'),
                    'default'   => $this->vmcard_payment_url
                ),
             'vmcard_language' => array(
                    'title' => __('CreditCard Gateway Language:'),
                    'type' => 'select',
                    'description' => __('CreditCard Gateway Language.'),
                    'default' => 'en',
                    'desc_tip' => true,
                    'options'  => array(
                        '' => __('auto','woocommerce'),
                        'en' => __('english','woocommerce'),
                        'fr' => __('french','woocommerce'),
                        'de' => __('german','woocommerce'),
                        'it' => __('italian','woocommerce'),
                        'jp' => __('japanese','woocommerce'),
                        'zh' => __('schinese','woocommerce')
                    )
                ),
            'vmcard_request_type' => array(
                    'title' => __('Request type', 'woocommerce'),
                    'type' => 'select',
                    'description' => __('Request the gateway method', 'woocommerce'),
                    'default' => 'post',
                    'desc_tip' => true,
                    'options' => array(
                            'post' => __('Post Method', 'woocommerce'),
                            'get' => __('Get Method', 'woocommerce')
                        )
                ),
            'paylog'   => array(
                'title' => __( 'Pay Log:', 'woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Save the payment information', 'woocommerce' ),
                'default' => 'no'              
                )
        );
    }
     public function admin_options(){
        ?>
        <h3><?php _e('CreditCard Payment', 'woocommerce'); ?></h3>
        <p><?php _e('Allows payments by CreditCard (the user to CreditCard to enter their payment information.)', 'woocommerce'); ?></p>
        <table class="form-table">
        <?php $this->generate_settings_html(); ?>
        </table>
        <?php 
     }

     function process_payment($order_id){
        global $woocommerce;

        $order = new WC_Order($order_id);
        if(!$this->form_submission_method){
            $this->form_submission_method = $this->get_option('vmcard_request_type');
        }
        if($this->form_submission_method == 'get'){
            $data = $this->get_request_data($order_id);
            $data = http_build_query($data, '', '&');
            return array(
                    'result' => 'success',
                    'redirect' => $this->vmcard_payment_url . '?' . $data
                );
        }else{
            return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('key', $order->order_key, add_query_arg('order-pay', $order->id, get_permalink(woocommerce_get_page_id('checkout'))))
                    // 'redirect' => $order->get_checkout_payment_url()
                    // 'redirect' => add_query_arg('key', $order->order_key, add_query_arg('order-pay', $order->id, get_permalink(woocommerce_get_page_id('pay'))))
                );
        }
     }
     function receipt_page($order){
        echo '<p>' . __('Thank yout for your order, please click the button below to pay with CreditCard', 'woocommerce') .'</p>';
        echo $this->generate_vmcard_form($order);
     }
     function get_request_data($order_id)
     {
        $order      = new WC_Order($order_id);
        
        $CurrencyCode       = strtoupper(get_woocommerce_currency());
        $data['MerNo']      = $this->get_option('vmcard_merchant_no');
        $data['BillNo']     = $order_id;
        $data['Currency']   = $this->getCreditCardCodeFunc($CurrencyCode);
        if(method_exists($order, 'get_total')){
            $data['Amount'] = round($order->get_total(), 2);
        }else{
            $data['Amount']     = round($order->get_order_total(), 2);
        }
        $data['Language']   = empty($this->get_option('vmcard_language')) ? $this->getRequestBrowserLang() : strtolower($this->get_option('vmcard_language'));
        // $data['ReturnURL']  = $this->get_option('jf_return_url') ? $this->get_option('jf_return_url') : $this->get_return_url(new WC_Order($order_id));
        $data['ReturnURL']  = $this->get_option('vmcard_return_url') ? $this->get_option('vmcard_return_url') : $this->get_return_url();
        $data['MD5Key']     = trim($this->get_option('vmcard_md5_key'));
        
        $data['MD5info']    = strtoupper(md5(implode('', $data)));

        //是否开启支付日志
         $data['PayLog']   = $this->get_option('paylog');
        

        //账单地址
        $data['FirstName'] = trim($order->billing_first_name);
        $data['LastName'] = trim($order->billing_last_name);
        $data['Address'] = trim($order->billing_address_1.' '.$order->billing_address_2);
        $data['City'] = trim($order->billing_city);
        $data['State'] = $this->get_vmcard_state( $order->billing_country, $order->billing_state );
        $data['Country'] = trim($order->billing_country);
        $data['ZipCode'] = trim($order->billing_postcode);
        $data['Email'] = trim($order->billing_email);
        $data['Phone'] = trim($order->billing_phone);


        //收货地址
        $data['DeliveryFirstName']  = $order->shipping_first_name;
        $data['DeliveryLastName']   = $order->shipping_last_name;
        $city       = empty($order->shipping_city) ? $order->billing_city : $order->shipping_city;
        $data['DeliveryCity']       = $order->shipping_city;
        $state      = empty($order->shipping_state) ? $order->billing_state : $order->shipping_state;
        $data['DeliveryState']      = $state;
        $zipcode    = empty($order->shipping_postcode) ? $order->billing_postcode : $order->shipping_postcode;
        $data['DeliveryZipCode']    = $zipcode;
        $country    = empty($order->shipping_country) ? $order->billing_country : $order->shipping_country;
        $data['DeliveryCountry']    = $order->shipping_country;
        $address    = empty($order->shipping_address_1) ? $order->shipping_address_2 : $order->shipping_address_1;
        $data['DeliveryAddress']    = $address;
        $phone      = (isset($order->shipping_phone) && $order->shipping_phone) ? $order->shipping_phone : $order->billing_phone;
        $data['DeliveryPhone']      = $phone;
        $email      = (isset($order->shipping_email) && $order->shipping_email) ? $order->shipping_email : $order->billing_email;
        $data['DeliveryEmail']      = $email;

        //货物列表信息

        $goodlist = "";
            if ( sizeof( $order->get_items() ) > 0 ) {
                foreach ( $order->get_items() as $item ) {
                    if ( $item['qty'] ) {
                        $goodlist = $goodlist."<GoodsName>".$item['name'] . "</GoodsName><Qty>".$item['qty']."</Qty><Price>" .number_format($item['line_subtotal']/$item['qty'], 2, '.', '')."</Price><Currency>".$CurrencyCode."</Currency>";
                    }
                }
                $data['Products'] = "<Goods>".$goodlist."</Goods>";
            }else{
                $data['Products'] = "no product";
            }
            

       //货物列表信息  

        return $data;
     }
     function generate_vmcard_form($order_id){

        global $woocommerce;

        $order = new WC_Order($order_id);
        $data       = $this->get_request_data($order_id);
        $form       = array();
        foreach($data as $key=>$value){
            $form[]     = '<input type="hidden" name="'. esc_attr($key) . '" value="' . esc_attr($value) . '"/>'; 
        }
        if(function_exists('wc_enqueue_js')){
            wc_enqueue_js( '
                jQuery("body").block({
                        message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to Visa Creditcard Pyament Online to make payment.', 'woocommerce' ) ) . '",
                        baseZ: 99999,
                        overlayCSS:
                        {
                            background: "#fff",
                            opacity: 0.6
                        },
                        css: {
                            padding:        "20px",
                            zindex:         "9999999",
                            textAlign:      "center",
                            color:          "#555",
                            border:         "3px solid #aaa",
                            backgroundColor:"#fff",
                            cursor:         "wait",
                            lineHeight:     "24px",
                        }
                    });
                jQuery("#submit_vmcard_payment_form").click();
            ' );
        }else{
            $woocommerce->add_inline_js( '
                jQuery("body").block({
                        message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to Visa Creditcard Pyament Online to make payment.', 'woocommerce' ) ) . '",
                        baseZ: 99999,
                        overlayCSS:
                        {
                            background: "#fff",
                            opacity: 0.6
                        },
                        css: {
                            padding:        "20px",
                            zindex:         "9999999",
                            textAlign:      "center",
                            color:          "#555",
                            border:         "3px solid #aaa",
                            backgroundColor:"#fff",
                            cursor:         "wait",
                            lineHeight:     "24px",
                        }
                    });
                jQuery("#submit_vmcard_payment_form").click();
            ' );          
        }
        return '<form action="' . $this->vmcard_payment_url . '" method="post" id="vmcard_payment_form" target="_top">
                ' . implode( '', $form) . '
                <input type="submit" class="button alt" id="submit_vmcard_payment_form" value="' . __( 'Pay via Creditcard', 'woocommerce' ) . '" /> <a class="button cancel" href="'.esc_url( $order->get_cancel_order_url() ).'">'.__( 'Cancel order &amp; restore cart', 'woocommerce' ).'</a>
            </form>';
     }
    function getCreditCardCodeFunc($code)
    {
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13','TWD' => '14','RUB'=>'15');
        if(isset($CurrencyArray[$code])){
            return $CurrencyArray[$code];
        }else{
            exit($code . ' is not set');
        }
    }
    function thankyou_page($data){
        
        if(isset($_REQUEST['result']) && $_REQUEST['result']){
            parse_str(base64_decode($_REQUEST['result']), $data);
        }
        if(isset($_REQUEST['description']) && $_REQUEST['description']){
            $description = $_REQUEST['description'];
        }


        $data['MD5key']     = trim($this->get_option('vmcard_md5_key'));
        $sort       = array('BillNo', 'Currency', 'Amount', 'Succeed', 'MD5key');
        $md5src     = '';
        foreach($sort as $key=> $value){
            $md5src     .= $data[$value];
        }
        $MD5info        = strtoupper(md5($md5src));
        if($MD5info != $data['MD5info']){
            $response       = 'Validate Failed !';
            echo $response;
            return $response;
        }
        $order      = new WC_Order($data['BillNo']);
        if($data['Succeed'] == '88'){
            $data['Result']     = "<font color='green'>" . $data['Result'] . "</font>";
            $data['description']     = "<font color='green'>" . $description . "</font>";
            $order->update_status('processing', sprintf(__($data['Result'] . ', rorderno is %s', 'woocommerce'), $data['rorderno']));
            WC()->cart->empty_cart();
        }else if($data['Succeed'] == '19'){
            $data['Result']     = "<font color='green'>" . $data['Result'] . "</font>";
            $data['description']     = "<font color='green'>" . $description . "</font>";
            $order->update_status('processing',sprintf(__($data['Result'] . ', rorderno is %s', 'woocommerce'), $data['rorderno']));
            WC()->cart->empty_cart();
        }else{
            $data['Result']     = "<font color='red'>" . $data['Result'] . "</font>";
            $data['description']     = "<font color='red'>" . $description . "</font>";
            $order->update_status('failed', sprintf(__($data['Result'] . ' Description: '.$data['description'].'&nbsp;Response Code is : %s', 'woocommerce'), $data['Succeed']));
            // WC()->cart->empty_cart();
        }
        $response       = "<table class='shop_table' border='1' width='100%'><tr><td colspan='2'>{$data['Result']}</td></tr>";
        $response       .= "<tr><td>Order No:</td><td>{$data['BillNo']}</td></tr>";
        $response       .= "<tr><td>Order Amount:</td><td>{$data['Amount']}&nbsp;&nbsp;{$data['CurrencyName']}</td></tr>";
        $response       .= "<tr><td>Response Code:</td><td>{$data['Succeed']}</td></tr>";
        $response       .= "<tr><td>Payment Result:</td><td>{$data['description']}</td></tr></table>";
        echo $response;
        return $response;
    }

        /**
     * Get the state to send to FirstTeam
     * @param  string $cc
     * @param  string $state
     * @return string
     */
    
        public function get_vmcard_state( $cc, $state ) {
        if ( 'US' === $cc ) {
            return $state;
        }

        $states = WC()->countries->get_states( $cc );
        
        if ( isset( $states[ $state ] ) ) {
            return $states[ $state ];
        }

        return $state;
    }


    //获取浏览器语言
     public function getRequestBrowserLang()
    {
 
     $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
     if (preg_match("/zh-c/i", $lang))
         return "zh";
     else if (preg_match("/zh/i", $lang))
         return "zh";
     else if (preg_match("/en/i", $lang))
         return "en";
     else if (preg_match("/fr/i", $lang))
         return "fr";
     else if (preg_match("/de/i", $lang))
         return "de";
     else if (preg_match("/jp/i", $lang))
         return "jp";
     else if (preg_match("/ko/i", $lang))
         return "ko";
     else if (preg_match("/es/i", $lang))
         return "es";
     else if (preg_match("/sv/i", $lang))
         return "sv";
     else if (preg_match("/it/i", $lang))
         return "it";
     else return null;
 
    }

}