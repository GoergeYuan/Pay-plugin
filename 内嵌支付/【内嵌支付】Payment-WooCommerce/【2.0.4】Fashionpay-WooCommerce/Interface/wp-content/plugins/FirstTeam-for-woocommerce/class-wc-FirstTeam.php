<?php

	extract($_POST);
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * FirstTeam Payment Gateway
 *
 * Provides an FirstTeam Payment Gateway.
 *
 * @class 		WC_FirstTeam
 * @extends		WC_Payment_Gateway
 * @version		1.0
 */

class WC_FirstTeam extends WC_Payment_Gateway {

    var $current_currency;
    var $multi_currency_enabled;

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        global $woocommerce;

        $this->current_currency = $this->current_currency();
        $this->multi_currency_enabled = in_array('woocommerce-multilingual/wpml-woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
                && get_option('icl_enable_multi_currency') == 'yes';
        $this->id = 'firstteam';
        $this->icon = apply_filters('woocommerce_FirstTeam_icon', home_url('wp-content/plugins/FirstTeam-for-woocommerce/assets/images/vmj.png', __FILE__),'width="150"');
        $this->has_fields = true;

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        // Define user set variables
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];

        $this->partnerID = $this->settings['partnerID'];
        $this->secure_key = $this->settings['secure_key'];
		$this->url = $this->settings['url'];
        $this->debug = $this->settings['debug'];
        $this->secure_key = $this->settings['secure_key'];
        $this->return_url = $this->settings['return_url'];

        $this->notify_url = str_replace('https:', 'http:', add_query_arg('wc-api', 'WC_FirstTeam', home_url('/'))); //trailingslashit(home_url()); 
        //Log
        /* if ($this->debug == 'yes')
            $this->log = $woocommerce->logger(); */

        // Actions
        add_action('admin_notices', array($this, 'requirement_checks'));
        add_action('woocommerce_api_wc_FirstTeam', array($this, 'check_FirstTeam_response'));
        add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options')); // WC <= 1.6.6
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options')); // WC >= 2.0
        add_action('woocommerce_thankyou_FirstTeam', array($this, 'thankyou_page'));
        add_action('woocommerce_receipt_FirstTeam', array($this, 'receipt_page'));
    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
        global $woocommerce;
        //获取返回网址
		if ($_SERVER['HTTPS'] != "on") {
		    $http_head =  "http://".$_SERVER['HTTP_HOST'];
		 }else{
		    $http_head =  "https://".$_SERVER['HTTP_HOST'];
		}

        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'FirstTeam'),
                'type' => 'checkbox',
                'label' => __('Enable FirstTeam Payment', 'FirstTeam'),
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'FirstTeam'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'FirstTeam'),
                'default' => __('Credit Card Payment', 'FirstTeam')
            ),
            'description' => array(
                'title' => __('Description', 'FirstTeam'),
                'type' => 'textarea',
                'default' => __('<strong style="color:#00AA55;">We accept VISA, MasterCard credit cards and JCB. </strong><br/>Your credit card information will be securely submitted to and verified by Visa ,Mastercard and JCB to ensure authorized usage.', 'FirstTeam')
            ),
            'partnerID' => array(
                'title' => __('MerNo', 'FirstTeam'),
                'type' => 'text',
                'description' => __('Please enter the MerNo', 'FirstTeam'),
                'css' => 'width:400px'
            ),
            'secure_key' => array(
                'title' => __('Security Key', 'FirstTeam'),
                'type' => 'text',
                'description' => __('Please enter the security key', 'FirstTeam'),
                'css' => 'width:400px'
            ),
            'url' => array(
                'title' => __('Submit Url', 'FirstTeam'),
                'type' => 'text',
                'label' => __('Submit Url.', 'FirstTeam'),
                'description' => __('Please enter the Submit Url.', 'FirstTeam'),
                'default' => 'http://ssl.hpolineshop.com/sslWebsitpayment'
            ),
             'return_url' => array(
                'title' => __('Return Url', 'FirstTeam'),
                'type' => 'text',
                'label' => __('Return Url.', 'FirstTeam'),
                'description' => __('Please enter the Return Url.', 'FirstTeam'),
                'default' => $http_head.'/checkout/order-received'
            )
        );
    }

    /**
     * Check if requirements are met and display notices
     *
     * @access public
     * @return void
     */
    public function requirement_checks() {
       /* if ($this->current_currency != 'RMB' && !$this->exchange_rate) {
            echo '<div class="error"><p>' . sprintf(__('FirstTeam is enabled, but the store currency is not set to RMB. Please <a href="%s">set the ' . $this->current_currency . ' against the RMB exchange rate</a>.', 'FirstTeam'), admin_url('admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_FirstTeam#woocommerce_FirstTeam_exchange_rate')) . '</p></div>';
        }*/
    }

    /**
     * Check if gateway is available
     *
     * @access public
     * @return bool
     */
    public function is_available() {
        if ($this->enabled == 'no'){
            return false;
        }
      
        return true;
    }
	
	
	
	/**
	 * Payment form on checkout page
	 */

	public function payment_fields() {
		  global $woocommerce;
		  ?>
		  <?php if ($this->description) : ?><p><?php echo $this->description; ?></p><?php endif; ?>
		  <body> 
		  <p class="form-row form-row-first">
			   <label for="FirstTeamt_cart_number"><?php _e("Credit Card number", 'woothemes') ?> <span class="required">*</span></label>
			   <input type="text" class="input-text" class="input-text wc-credit-card-form-card-number" placeholder="Card Number" name="FirstTeamt_card_number" maxlength="16" autocomplete="off" />
			   <input type="hidden" name="FirstTeamt_clientIP" id="clientIP"/>
			   <input type="hidden" id="checkout_time" name="checkout_time"/>
		  </p>
		  <div class="clear"></div>
		  <p class="form-row form-row-first">
			   <label for="cc-expire-month"><?php _e("Expiration date", 'woothemes') ?> <span class="required">*</span></label>
			   <select name="FirstTeamt_card_expiration_month" id="cc-expire-month"  style="width:100px;float:left;margin-right:8px;padding-left:5px;">
					
					<?php
					  for ($i = 1; $i < 13; $i++) {
					$MonthOp .="<option value='".sprintf('%02d', $i)."'>".sprintf('%02d', $i)."</option>";
   }
					?>
					<option value=""> Month </option>
					<?php echo $MonthOp; ?>
			   </select>
			   <select name="FirstTeamt_card_expiration_year" id="cc-expire-year" c style="width:100px;padding-left:5px;">
					
					<?php
					 for ($y = 2016; $y < 2031; $y++) {
		         $YearOp .="<option value='".sprintf('%02d', $y)."'>".sprintf('%02d', $y)."</option>";
	}
					?>
					<option value=""> Year </option>
					<?php echo $YearOp; ?>
			   </select>
		  </p>
		   <div class="clear"></div>
		  <p class="form-row form-row-first">
			   <label for="FirstTeamt_card_csc"><?php _e("Card security code", 'woothemes') ?><span class="required">*</span></label>	
			<input type="password" maxlength="4" class="input-text wc-credit-card-form-card-cvc" id="FirstTeamt_card_csc" name="FirstTeamt_card_csc" style="width:100px" placeholder="CVC" autocomplete="off"/>
			 <a class="aCvv" href="#" onclick="javascript:window.open('<?php echo plugins_url('assets/images/cvv_help.html',__FILE__); ?>', 'cvvdemo','height=300,width=400,top=200,left=500,fullscreen=no');return false;">
             <img src="<?php echo plugins_url('assets/images/cvv_ico.jpg',__FILE__); ?>" style="border-style: None; vertical-align: middle;margin:-2.5em 0 0 9em" /></a> 
		  </p>
		  <script language="javascript" type="text/javascript" src="http://pv.sohu.com/cityjson?ie=utf-8 ">  </script>    
			<script>document.getElementById("clientIP").value=returnCitySN.cip;</script>
		  <div class="clear"></div>
		</body>
		  <?php
	 }
	 
	
    
	 /**
	  * Payment form on checkout page
	  */
	 /* 
	 public function payment_fields() {
	 	if ( $this->description ) {
			echo apply_filters( 'wc_stripe_description', wpautop( wp_kses_post( $this->description ) ) );
		}

		if ( $display_tokenization ) {
			$this->tokenization_script();
			$this->saved_payment_methods();
		}

		if ( ! $this->stripe_checkout ) {
			$this->form();
			
			if ( $display_tokenization ) {
				$this->save_payment_method_checkbox();
			}
		}
	     
 }
	 	  */
	 	 
	 	 
	 	 
		 
		
	/*
	*	判断卡号信息
	*
	*/
		public function validate_fields(){
			global $woocommerce;
			
			$CardNo          =$_REQUEST['FirstTeamt_card_number'];
			$cvv   			 =$_REQUEST['FirstTeamt_card_csc'];
			$expiresMonth  	 =$_REQUEST['FirstTeamt_card_expiration_month'];
			$expiresYear     =$_REQUEST['FirstTeamt_card_expiration_year'];
			$clientIP    	 =$_REQUEST['FirstTeamt_clientIP'];
			/*************** 表单验证 ***************/

			//验证表单传过来的所有参数
        $errorMsg = $this->validateCardInfo($CardNo,$cvv,$expiresYear,$expiresMonth);
		
        if(!empty($errorMsg) && strlen($errorMsg) > 1) {

				//$woocommerce->add_error($errorMsg);
				wc_add_notice( __($errorMsg, 'woocommerce' ), 'error' );
				return false;
        }

        if($clientIP == ' ' || $this->isOk_ip($clientIP) == 0 || $clientIP == '127.0.0.1' || $clientIP == 'undefined'){

	   	$clientIP = $this->get_client_ip();
	   }


			/*************** 表单验证 ***************/

		$_SESSION['CardNo'] = $CardNo;
		$_SESSION['CardSecurityCode'] = $cvv;
		$_SESSION['CardExpireMonth'] = $expiresMonth;
		$_SESSION['CardExpireYear'] = $expiresYear;
		$_SESSION['clientIP']  = $clientIP;
		$_SESSION['checkout_time'] = $checkout_time;

		return;
	
		
	}
		 
    
    /**
     * Check the main currency
     * 
     * @access public
     * @return string
     */
    function current_currency() {
        $currency = get_option('woocommerce_currency');
        return $currency;
    }

    /**
     * Admin Panel Options
     * - Options for bits like 'title' and account etc.
     *
     * @since 1.0
     */
    public function admin_options() {
        ?>
        <h3><?php _e('Fashionpay', 'FirstTeam'); ?></h3>
        <p><?php _e('Fashionpay is one of the most widely used payment method in China, customer can pay with or without an Fashionpay account', 'FirstTeam'); ?></p>
        <table class="form-table">
            <?php
            // Generate the HTML For the settings form.
            $this->generate_settings_html();
            ?>
        </table><!--/.form-table-->
        <?php
    }

    /**
     * Get FirstTeam Args for passing to FirstTeam
     *
     * @access public
     * @param mixed $order
     * @return array
     */
    function get_FirstTeam_args($order) {
        global $woocommerce, $wpdb;

        //$paymethod = 'directPay';
        $order_id = $order->id;
   

        if ($this->debug == 'yes')
            $this->log->add('FirstTeam', 'Generating payment form for order #' . $order_id . '. Notify URL: ' . $this->notify_url);

		//卡号信息
		
		$CardNo             =$_SESSION['CardNo'];
		$CardSecurityCode   =$_SESSION['CardSecurityCode'];
		$CardExpireMonth    =$_SESSION['CardExpireMonth'];
		$CardExpireYear     =$_SESSION['CardExpireYear'];
		$IssuingBank        ='';
		$clientIP  = $_SESSION['clientIP'];
		//$clientIP = $this->get_client_ip();
		$CardholderLocalTime = $_SESSION['checkout_time'];
		
		// 订单信息
		//$OrderNo = trim($order->order_key . '|' . $order->id);
		$OrderNo = trim($order->id);
		$MerNo = trim($this->partnerID);
		$MD5key = trim($this->secure_key);
		$Amount = trim($order->get_total());
		
		 //对应的币种有   2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗 3:人民币  12:丹麦克朗  13:瑞典克朗 15:俄罗斯卢布
    	$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','CNY'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13', 'TWD' => '14','RUB'=>'15');
		$CurrencyName = trim($this->current_currency);	//币种
		$Currency = $CurrencyArray[$CurrencyName];
		
		// 持卡人信息/账单信息
		$BillFirstName = trim($order->billing_first_name);
		$BillLastName = trim($order->billing_last_name);
		$BillAddress = trim($order->billing_address_1.' '.$order->billing_address_2);
		$BillCity = trim($order->billing_city);
		$BillState = $this->get_FirstTeam_state( $order->billing_country, $order->billing_state );
		$BillCountry = trim($order->billing_country);
		$BillZip = trim($order->billing_postcode);
		$BillEmail = trim($order->billing_email);
		$BillPhone = trim($order->billing_phone);
		

		//持卡人收货信息
		$ShipFirstName = $order->shipping_first_name;
		$ShipLastName  = $order->shipping_last_name;
		$ShipAddress_1 = $order->shipping_address_1.' '.$order->shipping_address_2;
		$ShipAddress   = empty($ShipAddress_1) ? $BillAddress : $ShipAddress_1;
		$ShipCity      = empty($order->shipping_city) ? $order->billing_city : $order->shipping_city;
		$ShipState_1   = $this->get_FirstTeam_state( $order->shipping_country, $order->shipping_state );
		$ShipState     = empty($ShipState_1) ? $BillState : $ShipState_1;
		$ShipCountry   = empty($order->shipping_country) ? $order->billing_country : $order->shipping_country;
		$ShipZip       = empty($order->shipping_postcode) ? $order->billing_postcode : $order->shipping_postcode;
		$ShipEmail     = (isset($order->shipping_email) && $order->shipping_email) ? $order->shipping_email : $order->billing_email;
		$ShipPhone     = (isset($order->shipping_phone) && $order->shipping_phone) ? $order->shipping_phone : $order->billing_phone;


		//返回网址
		$ReturnURL = trim($this->return_url);
		$Language = $this->Language_code();
		$Remark  = $order->order_comments;

		//MD5数字加密
		$MD5src         = $MerNo . $OrderNo . $Currency . $Amount . $Language . $ReturnURL . $MD5key;
   		$MD5info        = strtoupper(md5($MD5src)); 

   		//货物列表信息
   		$goodlistinfo = $this->products($order);

        $FirstTeam_args = array(
			'MerNo' => $MerNo,
			'BillNo' => $OrderNo,
			'order_token' => $MD5key,
			'products' => $goodlistinfo,
			'Amount' => $Amount,
			'Currency' => $Currency,
			'firstname' => $BillFirstName,
			'lastname' => $BillLastName,
			'address' => $BillAddress,
			'city' => $BillCity,
			'state' => $BillState,
			'country' => $BillCountry,
			'zipcode' => $BillZip,
			'email' => $BillEmail,
			'phone' => $BillPhone,
			'shippingFirstName' => $ShipFirstName,
			'shippingLastName' => $ShipLastName,
			'shippingAddress' => $ShipAddress,
			'shippingCity' => $ShipCity,
			'shippingSstate' => $ShipState,
			'shippingCountry' => $ShipCountry,
			'shippingZipcode' => $ShipZip,
			'shippingEmail' => $ShipEmail,
			'shippingPhone' => $ShipPhone,
			'Language' => $Language,
			'ReturnURL' => $ReturnURL,
			'Remark' => $Remark,
			'MD5info' => $MD5info,
			'cardnum' => $CardNo,
			'cvv2' => $CardSecurityCode,
			'month' => $CardExpireMonth,
			'year' => $CardExpireYear,
			'ip' => $clientIP,
			'cardbank' => 'Bank of America'

        );


        $FirstTeam_args = apply_filters('woocommerce_FirstTeam_args', $FirstTeam_args);

        return $FirstTeam_args;
    }

    /**
     * Get fashionpay configuration
     * 获取fashionpay配置
     * @access public
     * @param mixed $order
     * @return array
     */
    function get_FirstTeam_config($order) {
        $FirstTeam_config = array();
        $FirstTeam_config['partner'] = trim($this->partnerID);
        $FirstTeam_config['key'] = trim($this->secure_key);
        $FirstTeam_config['seller_email'] = trim($this->alipay_account);
        $FirstTeam_config['return_url'] = $this->get_return_url($order);
        $FirstTeam_config['notify_url'] = $this->notify_url;
		$FirstTeam_config['url'] = $this->url;

        $FirstTeam_config['sign_type'] = 'MD5';
        $FirstTeam_config['input_charset'] = 'utf-8';
        $FirstTeam_config['transport'] = 'http';
        $FirstTeam_config = apply_filters('woocommerce_FirstTeam_config_args', $FirstTeam_config);
        return $FirstTeam_config;
    }

    /**
     * Build FirstTeam Query String for redirection to FirstTeam using GET method
     *
     * @access public
     * @param mixed $order
     * @return string
     */
    function build_FirstTeam_string($order) {
        require_once( "includes/FirstTeam_service.class.php");
        //Get FirstTeam args
        $FirstTeam_args = $this->get_FirstTeam_args($order);
        $FirstTeam_config = $this->get_FirstTeam_config($order);

        $FirstTeamService = new FirstTeamService($FirstTeam_config);
        $FirstTeam_submit = new FirstTeamSubmit();
        $para = $FirstTeam_submit->buildRequestPara($FirstTeam_args, $FirstTeam_config);
        $query_string = http_build_query($para, '', '&');
        //$FirstTeam_string = $FirstTeamService->FirstTeam_gateway_new . $query_string;
		//$FirstTeam_string = $this->url.'?'. $query_string;
		$domain = "http://".$_SERVER["HTTP_HOST"];
		$FirstTeam_string = $domain.'/wp-content/plugins/FirstTeamt-for-woocommerce/class-wc-FirstTeam.php'.'?&'. $query_string;

        return $para;
    }

	
	 

	
	 /**
     * Return page of FirstTeam, show FirstTeam Trade No. 
     *
     * @access public
     * @param mixed Sync Notification
     * @return void
     */
	function thankyou_page($order_id) {
        global $woocommerce;
		if (isset($order_id)) {
			require_once("includes/FirstTeam_notify.class.php");
			

		}

    }

    /**
     * Generate the FirstTeam button link (POST method)
     *
     * @access public
     * @param mixed $order_id
     * @return string
     */
    function generate_FirstTeam_formtest($order_id) {
        global $woocommerce;

        $order = new WC_Order($order_id);
        require_once( "includes/FirstTeam_service.class.php");

        $FirstTeam_args = $this->get_FirstTeam_args($order);
        $FirstTeam_config = $this->get_FirstTeam_config($order);

        $FirstTeamService = new FirstTeamService($FirstTeam_config);
        $FirstTeam_submit = new FirstTeamSubmit();
        $FirstTeam_adr = $FirstTeamService->FirstTeam_gateway_new;
		return $FirstTeam_submit->buildForm($FirstTeam_args,$FirstTeam_adr,'Post',$FirstTeam_config);

    }

    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */

    function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order($order_id);
        //if (!$this->form_submission_method) {
        
			$Url_gateway = trim($this->url);
			$PayURL = !empty($Url_gateway) ? $Url_gateway :
			'http://ssl.hpolineshop.com/sslWebsitpayment';
			
            $result = $this->vpost($PayURL,$this->build_FirstTeam_string($order));
			 parse_str($result,$myArray);

		    		
    	    /* MD5私钥 */
    	    $MD5key = trim($this->secure_key);
            /* 支付流水号 */
            $TradeNo     = $myArray['PaymentOrderNo'];
    	    /* 订单号 */
    	    $BillNo     = $myArray['BillNo'];
    	    /* 订单金额 */
    	    $Amount     = $myArray['Amount'];
    	    /* 支付币种 */
    	    $Currency   = $myArray['Currency'];    //返回币种数字
    	    /* 支付币种符号 */
    	    $CurrencyName   = $myArray['CurrencyName'];    //返回币种名称，如：USD
    	    /* 支付状态
    	        $Success  88:支付成功  19: 待处理[现在不会返回]  其它状态失败
    	    */
    	    $Succeed    = $myArray['Succeed'];
        
    	    /* 支付结果 */
    	    $ResultMsg     = $myArray['Result'];
    	    /* 返回码描述 */
    	    $Description   = $myArray['description'];
    	    /* MD5info校验信息 */
    	    $MD5info    = $myArray['MD5info'];
    	    
    	    /* 校验源字符串 */
    	    $md5src     = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
    	    /* 校验结果 */
    	    $MD5sign    = strtoupper(md5($md5src));
    	
    	
    		if (!$BillNo || !is_numeric($BillNo))
    				wp_die("Invalid Order ID");
            
            $FirstTeam_config = $this->get_FirstTeam_config($order);
            
            $fail_message = 'For Order : <strong>' . $order_id . '</strong><br/>'
                            . 'Trade No : <strong>' . $TradeNo . '</strong><br/>'
                            . 'Failure Reasons: <strong>' . $ResultMsg. '</strong><br/>'
                            . 'Error code: <strong>' . $Succeed. '</strong><br/>'
                            . 'Description: <strong>' . $Description. '</strong><br/>';

        //因部分商户出现MD5验证出现参数不全导致订单状态判断错误，现只更具返回代码判断，更改日期：2016-3-21 
        if($Succeed == '88') {
			$order->add_order_note(__('The order is completed', 'FirstTeam'));
			// Payment complete
			$order->payment_complete();
			// Remove cart
			$woocommerce->cart->empty_cart();
			// Empty awaiting payment session
			unset($_SESSION['order_awaiting_payment']);

		  }else{
			$order->update_status('Failed', __('The order is Pay Fail!', 'FirstTeam'));
			//$woocommerce->add_error(__('<strong>The order is Pay Fail!</strong><br/>Payment error:'. $ResultMsg.'<br/>Error code:'.$Succeed, 'FirstTeam'));
			wc_add_notice( '<strong>'.$ResultMsg.'</strong> ' . __( '<br/>Error code:'.$Succeed.'<br/>Payment Result:'. $Description, 'woocommerce' ), 'error' );
		    $order->add_order_note( __( $fail_message , 'woothemes') );

		  }

		   // Return thank you page redirect
			return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
          
    }

    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
    function receipt_page($order) {

        echo '<p>' . __('Thank you for your order, please click the button below to pay with FirstTeam.', 'FirstTeam') . '</p>';

        echo $this->generate_FirstTeam_form($order);
    }
	




      /**
     * 校验信用卡信息是否有效
     * @param $year
     * @param $month
     * @return $msg
     */
    function validateCardInfo($cardNum,$cvv,$year,$month) {
		$errorMsg = "";
		$errorMsg = $this->validateCardNum($cardNum);
		
		if(!empty($errorMsg) && strlen($errorMsg)>1) {
			return $errorMsg;
		}

        $errorMsg = $this->validateCVV($cvv);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }

        $errorMsg = $this->validateExpiresDate($year,$month);
        if(!empty($errorMsg) && strlen($errorMsg)>1) {
            return $errorMsg;
        }
        return "$errorMsg";
    }

	/**
	 * 校验信用卡卡号是否有效
	 * @param $cardNum
	 * @return String
	 */
	function validateCardNum($cardNum) {
		$msg = "";
		if(empty($cardNum) || !is_numeric($cardNum) || strlen($cardNum)<15 || strlen($cardNum)>16 ||
			 !$this->card_check_by_luhn($cardNum)) {
			$msg = 'The <strong>credit card number</strong> is incorrect !';
		}
		return $msg;
	}

    /**
     * 通过Luhn算法校验信用卡卡号是否有效
     * @param $cardNum
     * @return bool
     */
    function card_check_by_luhn($cardNum){
        $str = '';
        foreach(array_reverse(str_split($cardNum)) as $i => $c) $str .= ($i % 2 ? $c * 2 : $c);
        return array_sum(str_split($str)) % 10 == 0;
    }


    /**
     * 校验信用卡卡号是否有效
     * @param $cardNum
     * @return String
     */
    function getCardTypeByCardNum($cardNum) {
        $cardType = "";
        $left = substr($cardNum, 0, 2);
        if($left >= 40 && $left <= 49){
            $cardType = "VISA";
        }else if($left >= 50 && $left <=59) {
            $cardType = "MASTER";
        }else if($left == 35) {
            $cardType = "JCB";
        }else if($left == 34 || $left == 37) {
            $cardType = "AE";
        }
        return $cardType;
    }

    /**
     * 校验信用卡CVV是否有效
     * @param $cvv
     * @return $msg
     */
    function validateCVV($cvv) {
        $msg = "";
        if(empty($cvv) || !is_numeric($cvv) || strlen($cvv)<3 || strlen($cvv)>4) {
			
            $msg = '<strong>CVV/CSC</strong> Code is incorrect !';
        }
        return $msg;
    }

    /**
     * 校验信用卡有效期是否有效
     * @param $year
     * @param $month
     * @return $msg
     */
    function validateExpiresDate($year,$month) {
        $msg = "";
        if(empty($year) || !is_numeric($year) || strlen($year) !=4) {
            $msg = 'The <strong>year</strong> of expiry date is incorrect !';
        } else if(empty($month) || !is_numeric($month) || strlen($month) !=2 || $month < 1 || $month>12) {
            $msg = 'The <strong>month</strong> of expiry date is incorrect !';
        } else {
            $currentDate  = new DateTime(date("Y-m",time()));
            $inputDate    = new DateTime($year."-".$month);
            if($year<date("Y",time()) || $inputDate->format('U') < $currentDate->format('U')) {
                $msg = 'The <strong>expire date</strong> is expired!';
            }
        }
        return $msg;
    }





  /*
  *	ip验证正则
  *
  */

	 function isOk_ip($ip){

 if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))

	{

	return 1;

	}else{

	return 0;

	}

}



//获取浏览器语言
 function Language_code(){

 	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4); //只取前4位，这样只判断最优先的语言。如果取前5位，可能出现en,zh的情况，影响判断。  
    if (preg_match("/zh-c/i", $lang))  
    return "cn";  
    else if (preg_match("/zh/i", $lang))  
    return "tw";  
    else if (preg_match("/en/i", $lang))  
    return "en";  
    else if (preg_match("/fr/i", $lang))  
    return "fr";  
    else if (preg_match("/de/i", $lang))  
    return "de";  
    else if (preg_match("/jp/i", $lang))  
    return "ja";  
    else if (preg_match("/ko/i", $lang))  
    return "ko";  
    else if (preg_match("/es/i", $lang))  
    return "es";  
    else if (preg_match("/sv/i", $lang))  
    return "sv";  
    else return "en";  	
 }

	/*
	*
	*	获取ip
	*
	*/
	 function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}


    /**
     * 商品信息
     * @param unknown $order_id
     * @return XML
     */
	 public function products($order_id){
		
        $goodlist = "";
            if ( sizeof( $order_id->get_items() ) > 0 ) {
                foreach ( $order_id->get_items() as $item ) {
                    if ( $item['qty'] ) {
                        $goodlist = $goodlist."<GoodsName>".$item['name'] . "</GoodsName><Qty>".$item['qty']."</Qty><Price>" .number_format($item['line_subtotal']/$item['qty'], 2, '.', '')."</Price><Currency>".$CurrencyName."</Currency>";
                    }
                }
            }
            $goodlistinfo = "<Goods>".$goodlist."</Goods>";


		return $goodlistinfo;

}
    
    /**
     * Store additional order information
     * 支付请求网关
     * @param URL $url          请求网关
     * @param string $data      请求数据
     * @param number $timeout   默认请求时间
     */
	 public function vpost($url, $data ,$timeout = 30) {
	     
		if(function_exists('curl_init') && function_exists('curl_exec')){  //curl

			require_once 'includes/FirstTeam_Http_Client_Curl.php';
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
			require_once 'includes/FirstTeam_Http_Client_Socket.php';
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
		require 'includes/FirstTeam_System_Response.php';
		$isErrResponse = new System_Response();
		$errResponse = $isErrResponse->checkinfo($myArray['Succeed']);

		return $payResult.'&description='.$errResponse;
	}

    


	
	/**
	 * Get the state to send to FirstTeam
	 * @param  string $cc
	 * @param  string $state
	 * @return string
	 */
	
		public function get_FirstTeam_state( $cc, $state ) {
		if ( 'US' === $cc ) {
			return $state;
		}

		$states = WC()->countries->get_states( $cc );
		
		if ( isset( $states[ $state ] ) ) {
			return $states[ $state ];
		}

		return $state;
	}
	
	
	
}
?>