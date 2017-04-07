<?php 
if (!defined('_PS_VERSION_'))
	exit;
error_reporting(1);
class vmcard extends PaymentModule
{
    private $_html              ='';
    private $_postErrors        = array();
    private $current_cid        = 0;
    private $current_iso_code   = '';
    
    public function __construct()
    {
        $this->name         = 'vmcard';
        $this->tab          = 'payments_gateways';
        $this->author = 'fashionpay';
        $this->version      = '2.0.4';
        $this->currencies   = true;
        $this->currencies_mode  = 'radio';
        
        parent::__construct();
        $this->page         = basename(__FILE__, '.php');
        $this->displayName  = $this->l('vmcard');
        $this->description  = $this->l('Accepts payments by Fashionpay');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
        
        $version    = trim(str_replace('.', '', _PS_VERSION_));
        if(substr($version, 0, 2) == '13'){
            $this->tab      = 'Payment';
        }
    }
    
    public function getVmcardUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'submitOrder.php';
    }
    
    public function install()
    {
 		$action_URL     = $this->getVmcardUrl();
        $default_merchant_no        = '10003';
        $default_md5key             = '12345678';
        $default_language           = '';
        $default_new_order_status            = 0;
        $default_success_order_status        = 0;
        $default_processing_order_status     = 0;
        $default_failed_order_status         = 0;
		$default_return_url='http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__.'modules/vmcard/result.php';
		
		if (!parent :: install() OR !Configuration :: updateValue('VMCARD_FAILED_ORDER_STATUS', $default_failed_order_status) OR !Configuration :: updateValue('VMCARD_PROCESSING_ORDER_STATUS', $default_processing_order_status) OR !Configuration :: updateValue('VMCARD_SUCCESS_ORDER_STATUS', $default_success_order_status) OR !Configuration :: updateValue('VMCARD_NEW_ORDER_STATUS', $default_new_order_status) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_SELLER', $default_merchant_no) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_MD5KEY', $default_md5key) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_PAYLOG', $default_paylog) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_LANGUAGE', $default_language) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_HANDLER', $action_URL) OR !Configuration :: updateValue('MODULE_PAYMENT_VMCARD_RETURN_URL', $default_return_url) OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;   
    }
    public function uninstall()
    {
		if (!Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_SELLER') OR !Configuration :: deleteByName('VMCARD_FAILED_ORDER_STATUS') OR !Configuration :: deleteByName('VMCARD_PROCESSING_ORDER_STATUS') OR !Configuration :: deleteByName('VMCARD_SUCCESS_ORDER_STATUS') OR !Configuration :: deleteByName('VMCARD_NEW_ORDER_STATUS') OR !Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_MD5KEY') OR !Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_PAYLOG') OR !Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_LANGUAGE') OR !Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_HANDLER') OR !Configuration :: deleteByName('MODULE_PAYMENT_VMCARD_RETURN_URL') OR !parent :: uninstall())
			return false;
		return true;
    }
	public function getContent() {
		$this->_html = '<h2>Fashionpay</h2>';
		if (isset ($_POST['submitVmcard'])) {
			if (empty ($_POST['seller'])){
				$this->_postErrors[] = $this->l('[FASHIONPAY ID]商户号不能为空!');
            }
			if (empty ($_POST['md5key'])){
                $this->_postErrors[] = $this->l('[Fashionpay MD5key]商户密匙不能为空!');
			}
            if (empty ($_POST['handler'])){
                $this->_postErrors[] = $this->l('[Transaction URL]提交接口地址不能为空!');
			}
            if (empty ($_POST['returnurl'])){
                $this->_postErrors[] = $this->l('[Return URL]返回地址不能为空!');
            }
            if(intval($_POST['new_order_status']) <1){
                $this->_postErrors[] = $this->l('[New Order Status]请选择新订单的订单状态');
            }
            if(intval($_POST['success_order_status'])<1){
                $this->_postErrors[] = $this->l('[Success Order Status]请选择成功订单的订单状态');
            }
            if(intval($_POST['processing_order_status'])<1){
                $this->_postErrors[] = $this->l('[Processing Order Status]请选择待处理订单的状态');
            }
            if(intval($_POST['failed_order_status'])<1){
                $this->_postErrors[] = $this->l('[Failed Order Status]请选择失败订单的状态');
            }
            if(empty($_POST['paylog'])){
                $this->_postErrors[] = $this->l('[Pay Log]请选择支付日志状态');
            }
			if (!sizeof($this->_postErrors)) {

				//执行修改操作
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_SELLER', strval($_POST['seller']));
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_MD5KEY', strval($_POST['md5key']));
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_PAYLOG', strval($_POST['paylog'])); 
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_LANGUAGE', strval($_POST['language']));
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_HANDLER', strval($_POST['handler']));
				Configuration :: updateValue('MODULE_PAYMENT_VMCARD_RETURN_URL', strval($_POST['returnurl']));
                Configuration :: updateValue('VMCARD_NEW_ORDER_STATUS', strval($_POST['new_order_status']));
                Configuration :: updateValue('VMCARD_SUCCESS_ORDER_STATUS', strval($_POST['success_order_status']));
                Configuration :: updateValue('VMCARD_PROCESSING_ORDER_STATUS', strval($_POST['processing_order_status']));
                Configuration :: updateValue('VMCARD_FAILED_ORDER_STATUS', strval($_POST['failed_order_status']));
				$this->displayConf();
			} else
				$this->displayErrors();
		}

		$this->displaySHT();
		$this->displayFormSettings();
		return $this->_html;
	}
	public function displayConf() {
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="' . $this->l('Confirmation') . '" />' . $this->l('Settings updated') . '</div>';
	}
	public function displayErrors() {
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '<div class="alert error"><h3>' . ($nbErrors > 1 ? $this->l('There are') : $this->l('There is')) . ' ' . $nbErrors . ' ' . ($nbErrors > 1 ? $this->l('errors') : $this->l('error')) . '</h3><ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>' . $error . '</li>';
		$this->_html .= '</ol></div>';
	}
	//设置显示logo及提示信息
	public function displaySHT() {
		$this->_html .= '<div style="float: right; width: 440px; height: 150px; border: dashed 1px #666; padding: 8px; margin-left: 12px;"><h2>'
		. $this->l('Opening your Fashionpay account') . '</h2><div style="clear: both;"></div><p>'
		. $this->l('By opening your SHT account by clicking on the following image you are helping us significantly to improve the PrestaShop software:')
		. '</p><p style="text-align: center;"><a href="http://www.fashionpay.com"><img src="../modules/vmcard/prestashop_vmcard.png" alt="PrestaShop & Fashionpay" style="margin-top: 12px;" /></a></p><div style="clear: right;"></div></div><img src="../modules/vmcard/vmcard.png" style="float:left; margin-right:15px;" /><b>'
		. $this->l('This module allows you to accept payments by Fashionpay.')
		. '</b><br /><br />'
		. $this->l('If the client chooses this payment mode, your Fashionpay account will be automatically credited.')
		. '<br />'
		. $this->l('You need to configure your fashionpay account first before using this module.') . '<div style="clear:both;">&nbsp;</div>';
	}
    private function getLanguages()
    {
        return array('auto'=>'', 'English'=>'en', 'German'=>'de', 'Spanish'=>'es', 'French'=>'fr', 'Italian'=>'it', 'Japanese'=>'ja', 'Korean'=> 'ko');
    }
    
    private function getPaylog()
    {
        return array('Yes'=>'1','No'=>'0');
    }
    
	//设置后台表单项
	public function displayFormSettings() {
		$conf = Configuration :: getMultiple(array (
			'MODULE_PAYMENT_VMCARD_SELLER',
			'MODULE_PAYMENT_VMCARD_MD5KEY',
            'MODULE_PAYMENT_VMCARD_PAYLOG',
			'MODULE_PAYMENT_VMCARD_LANGUAGE',
			'MODULE_PAYMENT_VMCARD_HANDLER',
			'MODULE_PAYMENT_VMCARD_RETURN_URL',
            'VMCARD_NEW_ORDER_STATUS',
            'VMCARD_SUCCESS_ORDER_STATUS',
            'VMCARD_PROCESSING_ORDER_STATUS',
            'VMCARD_FAILED_ORDER_STATUS'
            
		));
		$seller = array_key_exists('seller', $_POST) ? $_POST['seller'] : (array_key_exists('MODULE_PAYMENT_VMCARD_SELLER', $conf) ? $conf['MODULE_PAYMENT_VMCARD_SELLER'] : '');
		$md5key = array_key_exists('md5key', $_POST) ? $_POST['md5key'] : (array_key_exists('MODULE_PAYMENT_VMCARD_MD5KEY', $conf) ? $conf['MODULE_PAYMENT_VMCARD_MD5KEY'] : '');
	//	$moneytype = array_key_exists('moneytype', $_POST) ? $_POST['moneytype'] : (array_key_exists('MODULE_PAYMENT_VMCARD_MONEYTYPE', $conf) ? $conf['MODULE_PAYMENT_VMCARD_MONEYTYPE'] : '');
        $paylog = array_key_exists('paylog', $_POST) ? $_POST['paylog'] : (array_key_exists('MODULE_PAYMENT_VMCARD_PAYLOG', $conf) ? $conf['MODULE_PAYMENT_VMCARD_PAYLOG'] : '');
		$language = array_key_exists('language', $_POST) ? $_POST['language'] : (array_key_exists('MODULE_PAYMENT_VMCARD_LANGUAGE', $conf) ? $conf['MODULE_PAYMENT_VMCARD_LANGUAGE'] : '');
		$handler = array_key_exists('handler', $_POST) ? $_POST['handler'] : (array_key_exists('MODULE_PAYMENT_VMCARD_HANDLER', $conf) ? $conf['MODULE_PAYMENT_VMCARD_HANDLER'] : '');
		$returnurl = array_key_exists('returnurl', $_POST) ? $_POST['returnurl'] : (array_key_exists('MODULE_PAYMENT_VMCARD_RETURN_URL', $conf) ? $conf['MODULE_PAYMENT_VMCARD_RETURN_URL'] : '');
        $new_order_status   = array_key_exists('new_order_status', $_POST) ? $_POST['new_order_status'] : (array_key_exists('VMCARD_NEW_ORDER_STATUS', $conf) ? $conf['VMCARD_NEW_ORDER_STATUS'] : 0);
        $success_order_status   = array_key_exists('success_order_status', $_POST) ? $_POST['success_order_status'] : (array_key_exists('VMCARD_SUCCESS_ORDER_STATUS', $conf) ? $conf['VMCARD_SUCCESS_ORDER_STATUS'] : 0);
        $failed_order_status    = array_key_exists('failed_order_status', $_POST) ? $_POST['failed_order_status'] : (array_key_exists('VMCARD_FAILED_ORDER_STATUS', $conf) ? $conf['VMCARD_FAILED_ORDER_STATUS'] : 0);
        $processing_order_status    = array_key_exists('processing_order_status', $_POST) ? $_POST['processing_order_status'] : (array_key_exists('VMCARD_PROCESSING_ORDER_STATUS', $conf) ? $conf['VMCARD_PROCESSING_ORDER_STATUS'] : 0);       
        $this->_html    .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear:both;"><fieldset><legend><img src="../img/admin/contact.gif"/>';
        $this->_html    .= $this->l('Setting') . '</legend>';
        $this->_html    .= '<label>' . $this->l('Fashionpay ID') . '</label><div class="margin-form"><input type="text" size="33" name="seller" value="'.htmlentities($seller, ENT_COMPAT, 'UTF-8').'" /></div>';
        $this->_html    .= '<label>' . $this->l('Fashionpay MD5key') . '</label><div class="margin-form"><input type="text" size="33" name="md5key" value="'.htmlentities($md5key, ENT_COMPAT, 'UTF-8').'" /></div>';
        
        $languages      = $this->getLanguages();
        
        $this->_html    .= '<label>'.$this->l('Language').'</label><div class="margin-form">';
        foreach($languages as $key=>$value){
            $this->_html    .= '<input type="radio" name="language" value="'.$value.'" '.($language == $value ? 'checked="checked"' : ''). '/>' . $this->l($key) .'&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $this->_html    .= '</div>';
		
        $sql             = 'SELECT * FROM `' . _DB_PREFIX_ . 'lang` ORDER BY id_lang ASC';
        $lang            = DB::getInstance()->getRow($sql);
        if($lang){
            $langId      = $lang['id_lang'];
            $sql2       = 'SELECT * FROM `' . _DB_PREFIX_ . 'order_state_lang` WHERE id_lang='.intval($langId);
            $status     = DB::getInstance()->ExecuteS($sql2);
            $this->_html    .= '<label>'. $this->l('New Order Status') .'</label><div class="margin-form"><select name="new_order_status">';
            $this->_html    .= '<option value="0"  ';
            if(intval($new_order_status) == 0){
                $this->_html    .= ' selected="selected"';
            }
            $this->_html        .= '>please select new order status</option>';
            foreach($status as $key=>$value){
                $select1         = ($value['id_order_state'] == intval($new_order_status)) ? ' selected="selected" ' : '';
                $this->_html    .= '<option value="'.$value['id_order_state'].'" '. $select1 .'>'.$value['name'].'</option>';
            }
            $this->_html    .= '</select></div>';
           
            $this->_html    .= '<label>'. $this->l('Success Order Status') .'</label><div class="margin-form"><select name="success_order_status">';
            $this->_html    .= '<option value="0"  ';
            if(intval($success_order_status) == 0){
                $this->_html    .= ' selected="selected"';
            }
            $this->_html        .= '>please select success order status</option>';           
            foreach($status as $key=>$value){
                $select2         = ($value['id_order_state'] == intval($success_order_status)) ? ' selected="selected" ' : '';
                $this->_html    .= '<option value="'.$value['id_order_state'].'" '. $select2 .'>'.$value['name'].'</option>';
            }
            $this->_html    .= '</select></div>';
           
            $this->_html    .= '<label>'. $this->l('Processing Order Status') .'</label><div class="margin-form"><select name="processing_order_status">';
            $this->_html    .= '<option value="0"  ';
            if(intval($processing_order_status) == 0){
                $this->_html    .= ' selected="selected"';
            }
            $this->_html        .= '>please select processing order status</option>';
            foreach($status as $key=>$value){
                $select3         = ($value['id_order_state'] == intval($processing_order_status)) ? ' selected="selected" ' : '';
                $this->_html    .= '<option value="'.$value['id_order_state'].'" '. $select3 .'>'.$value['name'].'</option>';
            }
            $this->_html    .= '</select></div>';
           
            $this->_html    .= '<label>'. $this->l('Failed Order Status') .'</label><div class="margin-form"><select name="failed_order_status">';
            $this->_html    .= '<option value="0"  ';
            if(intval($failed_order_status) == 0){
                $this->_html    .= ' selected="selected"';
            }
            $this->_html        .= '>please select failed order status</option>';            
            foreach($status as $key=>$value){
                $select4         = ($value['id_order_state'] == intval($failed_order_status)) ? ' selected="selected" ' : '';
                $this->_html    .= '<option value="'.$value['id_order_state'].'"'.$select4.'>'.$value['name'].'</option>';
            }
            $this->_html    .= '</select></div>';
        }
   
        $this->_html    .= '<label>' . $this->l('Transaction URL') . '</label><div class="margin-form"><input type="text" size="82" name="handler" value="'.htmlentities($handler, ENT_COMPAT, 'UTF-8').'" /></div>';
        $this->_html    .= '<label>' . $this->l('Return URL') . '</label><div class="margin-form"><input type="text" size="82" name="returnurl" value="'.htmlentities($returnurl, ENT_COMPAT, 'UTF-8').'" /></div>';
        //支付日志开关
        $getPaylog = $this->getPaylog();
        $this->_html    .= '<label>'.$this->l('Pay Log').'</label><div class="margin-form">';
        foreach($getPaylog as $key=>$val){
        $this->_html .='<input type="radio" name="paylog" value="'.$val.'" '.($paylog == $val ? 'checked="checked"' : ''). '/>' . $this->l($key) .'&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $this->_html .='</div>';
        $this->_html    .= '<br/><center><input type="submit" name="submitVmcard" value="'.$this->l('Update Setting').'"  class="button" /></center></fieldset></form><br /><br />';
/*         print_r(DB::getInstance()->getRow($sql));
        $sql2            = 'SELECT * FROM `' . _DB_PREFIX_ . 'order_state_lang` WHERE id_lang=1';
        print_r(DB::getInstance()->ExecuteS($sql2)); */
    }
    public function commitPayment($cart)
    {
        if(!$this->active){
            return;
        }
        global $smarty;
		$BillInfo 				= new Address(intval($cart->id_address_invoice));
        $ShipInfo               = new Address(intval($cart->id_address_delivery));
		$customer				= new Customer(intval($cart->id_customer));

		$MerNo 					= trim(Configuration :: get('MODULE_PAYMENT_VMCARD_SELLER'));
		$MD5key 				= trim(Configuration :: get('MODULE_PAYMENT_VMCARD_MD5KEY'));
		$paylog                 = intval(Configuration :: get('MODULE_PAYMENT_VMCARD_PAYLOG'));
		$handler 				= trim(Configuration :: get('MODULE_PAYMENT_VMCARD_HANDLER'));
		$ReturnURL 				= trim(Configuration :: get('MODULE_PAYMENT_VMCARD_RETURN_URL'));
		$Language 				= empty(Configuration :: get('MODULE_PAYMENT_VMCARD_LANGUAGE')) ? $this->getRequestBrowserLang() : trim(Configuration :: get('MODULE_PAYMENT_VMCARD_LANGUAGE'));
        $new_order_status       = intval(Configuration :: get('VMCARD_NEW_ORDER_STATUS'));

        
        $sqlc                   = 'SELECT * FROM `'._DB_PREFIX_ . 'currency`  WHERE id_currency=' .intval($cart->id_currency);
        $current_currency        = DB::getInstance()->getRow($sqlc);       
        /* $this->current_cid      = intval($current_currency['id_currency']); */
        $iso_code               = strtoupper($current_currency['iso_code']);
        //$currencyObj            = $this->getCurrency();
        $Currency               = $this->getFashionpayCurrencyCode($iso_code);
        
        $Amount                 = round($cart->getOrderTotal(true, 3), 2); 
        @$this->validateOrder($cart->id, $new_order_status, $Amount,$this->displayName);
		$BillNo 				= $this->currentOrder;        
		$Remark 				= 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__;
		$MerWebsite 			= 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__;
        $NoticeURL              = 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . '/module/vmcard/notice.php';

		//组合加密项
		$MD5src 				= $MerNo . $BillNo . $Currency  . $Amount . $Language . $ReturnURL . $MD5key;
        $MD5info                = strtoupper(md5($MD5src));
        /* -------------------------------------------------------------------------------------------- */
        //账单信息

		//收货人姓
		$DeliveryFirstName 		= empty ($ShipInfo->firstname) ? '' : $ShipInfo->firstname;
		//收货人名
		$DeliveryLastName 		= empty ($ShipInfo->lastname) ? '' : $ShipInfo->lastname;
		//收货人email
		$DeliveryEmail 			= empty ($ShipInfo->email) ? $customer->email : $ShipInfo->email;
		//收货人电话
		$DeliveryPhone 			= empty ($ShipInfo->phone) ? '' : $ShipInfo->phone;
		//收货人邮编
		$DeliveryZipCode 		= empty ($ShipInfo->postcode) ? '' : $ShipInfo->postcode;
		//收货人地址
		$DeliveryAddress 		= empty ($ShipInfo->address1) ? '' : $ShipInfo->address1;
		//收货人城市
		$DeliveryCity 			= empty ($ShipInfo->city) ? '' : $ShipInfo->city;
		//收货人省或州
		$DeliveryState 			= empty ($ShipInfo->state) ? '' : $ShipInfo->state;
		//收货人国家
		$DeliveryCountry 		= empty ($ShipInfo->country) ? '' : $ShipInfo->country;

        //账单信息

        //账单姓
        $FirstName      = empty ($BillInfo->firstname) ? '' : trim($BillInfo->firstname);
        //账单名
        $LastName       = empty ($BillInfo->lastname) ? '' : trim($BillInfo->lastname);
        //账单email
        $Email          = empty ($BillInfo->email) ? trim($customer->email) : trim($BillInfo->email);
        //账单电话
        $Phone          = empty ($BillInfo->phone) ? '' : trim($BillInfo->phone);
        //账单邮编
        $ZipCode        = empty ($BillInfo->postcode) ? '' : trim($BillInfo->postcode);
        //账单地址
        $Address        = empty ($BillInfo->address1) ? '' : trim($BillInfo->address1);
        //账单城市
        $City           = empty ($BillInfo->city) ? '' : trim($BillInfo->city);
        //账单省或州
        $State          = empty ($BillInfo->state) ? '' : trim($BillInfo->state);
        //账单国家
        $Country        = empty ($BillInfo->country) ? '' : trim($BillInfo->country);

        
        $OrderDesc              = '';
        /* --------------------------------------------------------------------------------------------- */
        //商品信息
         $ProList        = $cart->getProducts();
         foreach($ProList as $key=>$value){

            $tempList .= "<GoodsName>".$value['name']."</GoodsName><Qty>".$value['cart_quantity']."</Qty><Price>".round($value['total_wt'], 2) .' '. $iso_code."</Price><Currency>".$iso_code."</Currency>";
          }
          $Products = "<Goods>".$tempList."</Goods>";

        /* --------------------------------------------------------------------------------------------- */
		$form       = array (
			'handler' 			=> $handler,
			'MerNo' 			=> $MerNo,
			'BillNo' 			=> $BillNo,
			'Amount' 			=> $Amount,
			/* 'DispAmount' 		=> $DispAmount, */
			'Currency' 			=> $Currency,
			'Language' 			=> $Language,
			'MD5info' 			=> $MD5info,
			'ReturnURL' 		=> $ReturnURL,
			'OrderDesc' 		=> $OrderDesc,
			'Remark' 			=> $Remark,
			'FirstName' 		=> $FirstName,
			'LastName' 			=> $LastName, 
            'Email'             => $Email,
            'Phone'             => $Phone,
            'ZipCode'           => $ZipCode,
            'Address'           => $Address,
            'City'              => $City,
            'State'             => $State,
            'Country'           => $Country,

			'MerWebsite'		=> $MerWebsite,
            'NoticeURL'         => $NoticeURL,
			'DeliveryFirstName' => $DeliveryFirstName,
			'DeliveryLastName' 	=> $DeliveryLastName,
			'DeliveryEmail' 	=> $DeliveryEmail,
			'DeliveryPhone' 	=> $DeliveryPhone,
			'DeliveryZipCode' 	=> $DeliveryZipCode,
			'DeliveryAddress' 	=> $DeliveryAddress,
			'DeliveryCity' 		=> $DeliveryCity,
			'DeliveryState' 	=> $DeliveryState,
			'DeliveryCountry' 	=> $DeliveryCountry,
			'Products' 		    => $Products,
            'ms_string'         => md5(rand(0, 10000000)),
            'PayLog'            => $paylog
            ); 
      $smarty->assign($form);
        //echo __FILE__;
		return $this->display(__FILE__, __FUNCTION__ .'.tpl');
    }
    private function getFashionpayCurrencyCode($code)
    {
        $CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13','TWD'=>'14','RUB'=>'15');
        if(!$code || !isset($CurrencyArray[$code])){
            exit("Currency [$code] is not set");
        }
        return $CurrencyArray[$code];   
    }
	//前台支付方式列表界面
	public function hookPayment($params) {

		if (!$this->active){
			return;
        }
		global $smarty;
		$this_path_ssl = 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/'. __CLASS__ .'/';
		$smarty->assign(array (
			'this_path_ssl' => $this_path_ssl
		));
		return $this->display(__FILE__, __FUNCTION__ .'.tpl');
	}
	public function hookPaymentReturn($params) {
		if (!$this->active)
			return;
		return $this->display(__FILE__, __FUNCTION__ .'.tpl');
	}
	public function getL($key) {
		$translations = array (
			'mc_gross'          => $this->l('Vmcard key \'mc_gross\' not specified, can\'t control amount paid.'),
            'payment_status'    => $this->l('Vmcard key \'payment_status\' not specified, can\'t control payment validity'),
            'payment'           => $this->l('Payment: '),
            'custom'            => $this->l('Vmcard key \'custom\' not specified, can\'t rely to cart'),
            'txn_id'            => $this->l('Vmcard key \'txn_id\' not specified, transaction unknown'),
            'mc_currency'       => $this->l('Vmcard key \'mc_currency\' not specified, currency unknown'),
            'cart'              => $this->l('Cart not found'), 
            'order'             => $this->l('Order has already been placed'), 
            'transaction'       => $this->l('Vmcard Transaction ID: '), 
            'verified'          => $this->l('The Vmcard transaction could not be VERIFIED.'), 
            'connect'           => $this->l('Problem connecting to the Vmcard server.'), 
            'nomethod'          => $this->l('No communications transport available.'), 
            'socketmethod'      => $this->l('Verification failure (using fsockopen). Returned: '), 
            'curlmethod'        => $this->l('Verification failure (using cURL). Returned: '), 
            'curlmethodfailed'  => $this->l('Connection using cURL failed'),);
		return $translations[$key];
	}
	function validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array (), $currency_special = NULL, $dont_touch_amount = false) {
		if (!$this->active){	return;     }
		//$currency           = $this->getCurrency();
		$cart               = new Cart(intval($id_cart));
		//$cart->id_currency  = $currency->id;
		$secure_key			= $cart->secure_key;
		$cart->save();
		parent :: validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, true, $secure_key);
	}

    /**
    *   获取浏览器语言
    *
    */

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
?>