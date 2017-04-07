<?php
class card
{
	var $code, $title, $description, $enabled, $sort_order, $form_action_url;
	var $order_pending_status 	= 1;
	var $order_status 			= DEFAULT_ORDERS_STATUS_ID;
	var $order_id, $Amount;

	function card() {
		global $order;
		$_SESSION['payment'] = 'card';
		$this->refrech_status 	= 0;
		$this->code 			= 'card';
		if ($_GET['main_page'] != '') {
			$this->title 		= MODULE_PAYMENT_CARD_TEXT_CATALOG_TITLE;

		} else {
			$this->title 		= MODULE_PAYMENT_CARD_TEXT_ADMIN_TITLE;
		}
		$this->description 		= MODULE_PAYMENT_CARD_TEXT_DESCRIPTION;
		$this->sort_order 		= MODULE_PAYMENT_CARD_SOTR_ORDER;
		$this->enabled 			= ((MODULE_PAYMENT_CARD_STATUS == 'True') ? true : false);
		if ((int) MODULE_PAYMENT_CARD_ORDER_STATUS_ID > 0)
			$this->order_status = MODULE_PAYMENT_CARD_ORDER_STATUS_ID;
		if (is_object($order)) {
			$this->update_status();
		}
		$this->form_action_url 	= MODULE_PAYMENT_CARD_HANDLER;
	}

	/**
	 * 计算区域火柴和标志设置，以确定是否应显示模块的客户或不
	 */
	function update_status() {
		global $order, $db;

		if (($this->enabled == true) && ((int) MODULE_PAYMENT_CARD_ZONE > 0)) {
			$check_flag = false;
			$check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_CARD_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
			while (!$check_query->EOF) {
				if ($check_query->fields['zone_id'] < 1) {
					$check_flag = true;
					break;
				}
				elseif ($check_query->fields['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
				$check_query->MoveNext();
			}

			if ($check_flag == false) {
				$this->enabled = false;
			}
		}

	}
	function javascript_validation() {
		return false;
	}

	/**随着显示支付信用卡资料提交字段的方法名（如有）页上的结帐付款
	 *返回数组
	 */
	function selection() {
		return array (
			'id' 		=> $this->code,
			'module' 	=> MODULE_PAYMENT_CARD_TEXT_CATALOG_LOGO,
			'icon' 		=> MODULE_PAYMENT_CARD_TEXT_CATALOG_LOGO
		);
	}
	/**  pre_confirmation_check
	*	通常评估验收的信用卡种类和信用卡号码和到期日期的有效性
	*	(此方法在includes/modules/pages/check_confirmation/header_php.php调用)
	*/
	function pre_confirmation_check() {
		return false;
	}
	/**
	 * 选择支付方式页面的继续结账按钮所调用的方法
	 *
	 */
	function confirmation($flag = '') {
		if ($flag == 'ok') {
			//生成订单
			if (isset ($_SESSION['_card_order_id'])) {
				// $this->delete_order($_SESSION['_card_order_id']);
			}
			$this->create_order();
		}
	}
	/**
	 * 生成订单,及在相关表插入信息
	 */
	private function create_order() {

		global $total_products_price, $products_ordered, $products_tax, $languages_id, $customer_id, $language, $payment, $shipping;
		global $order, $order_total_modules,$shipping_modules,$currencies;//, $ip, $isp, $products_tax;
  		$order_totals = $order_total_modules->process();
		$sql_data_array = array (
			'customers_id' 		=> $customer_id,
			'customers_name' 	=> $order->customer['firstname'] . ' ' . $order->customer['lastname'],
			'customers_company' => $order->customer['company'],
			'customers_street_address' => $order->customer['street_address'],
			'customers_suburb' 	=> $order->customer['suburb'],
			'customers_city' 	=> $order->customer['city'],
			'customers_postcode' 	=> $order->customer['postcode'],
			'customers_state' 	=> $order->customer['state'],
			'customers_country' => $order->customer['country']['title'],
			'customers_telephone' 		=> $order->customer['telephone'],
			'customers_email_address' 	=> $order->customer['email_address'],
			'customers_address_format_id' => $order->customer['format_id'],
			'delivery_name' 	=> trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
			'delivery_company' 	=> $order->delivery['company'],
			'delivery_street_address' => $order->delivery['street_address'],
			'delivery_suburb' 	=> $order->delivery['suburb'],
			'delivery_city' 	=> $order->delivery['city'],
			'delivery_postcode' => $order->delivery['postcode'],
			'delivery_state' 	=> $order->delivery['state'],
			'delivery_country' 	=> $order->delivery['country']['title'],
			'delivery_address_format_id' => $order->delivery['format_id'],
			'billing_name' 		=> $order->billing['firstname'] . ' ' . $order->billing['lastname'],
			'billing_company' 	=> $order->billing['company'],
			'billing_street_address' => $order->billing['street_address'],
			'billing_suburb' 	=> $order->billing['suburb'],
			'billing_city' 		=> $order->billing['city'],
			'billing_postcode' 	=> $order->billing['postcode'],
			'billing_state' 	=> $order->billing['state'],
			'billing_country' 	=> $order->billing['country']['title'],
			'billing_address_format_id' => $order->billing['format_id'],
			'payment_method' 	=> $order->info['payment_method'],
			'cc_type' 			=> $order->info['cc_type'],
			'cc_owner' 			=> $order->info['cc_owner'],
			'cc_number' 		=> $order->info['cc_number'],
			'cc_expires' 		=> $order->info['cc_expires'],
			'date_purchased' 	=> 'now()',
			'orders_status' 	=> $order->info['order_status'],
			'currency' 			=> $order->info['currency'],
			'currency_value' 	=> $order->info['currency_value']);
		tep_db_perform(TABLE_ORDERS, $sql_data_array);
		$insert_id 							= tep_db_insert_id();
		$_SESSION['_card_order_id']	= $insert_id;
		for ($i = 0, $n = sizeof($order_totals); $i < $n; $i++) {
			$sql_data_array = array (
				'orders_id' 	=> $insert_id,
				'title' 		=> $order_totals[$i]['title'],
				'text' 			=> $order_totals[$i]['text'],
				'value' 		=> $order_totals[$i]['value'],
				'class' 		=> $order_totals[$i]['code'],
				'sort_order' 	=> $order_totals[$i]['sort_order']
			);
			tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
		}
		$customer_notification 	= (SEND_EMAILS == 'true') ? '1' : '0';
		$sql_data_array = array (
			'orders_id' 		=> $insert_id,
			'orders_status_id' 	=> $order->info['order_status'],
			'date_added' 		=> 'now()',
			'customer_notified' => $customer_notification,
			'comments' 			=> $order->info['comments']
		);
		tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		// initialized for the email confirmation
		$products_ordered 	= '';
		$subtotal 			= 0;
		$total_tax 			= 0;

		for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
			// Stock Update - Joao Correia
			if (STOCK_LIMITED == 'true') {
				if (DOWNLOAD_ENABLED == 'true') {
					$stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa ON p.products_id=pa.products_id LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ON pa.products_attributes_id=pad.products_attributes_id WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
					// Will work with only one option for downloadable products
					// otherwise, we have to build the query dynamically with a loop
					$products_attributes = $order->products[$i]['attributes'];
					if (is_array($products_attributes)) {
						$stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
					}
					$stock_query = tep_db_query($stock_query_raw);
				} else {
					$stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
				}
				if (tep_db_num_rows($stock_query) > 0) {
					$stock_values = tep_db_fetch_array($stock_query);
					// do not decrement quantities if products_attributes_filename exists
					if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
						$stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
					} else {
						$stock_left = $stock_values['products_quantity'];
					}
					tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					if (($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
						tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
					}
				}
			}

			// Update products_ordered (for bestsellers list)
			tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

			$sql_data_array = array (
				'orders_id' 	=> $insert_id,
				'products_id' 	=> tep_get_prid($order->products[$i]['id']
			), 'products_model' => $order->products[$i]['model'], 'products_name' => $order->products[$i]['name'], 'products_price' => $order->products[$i]['price'], 'final_price' => $order->products[$i]['final_price'], 'products_tax' => $order->products[$i]['tax'], 'products_quantity' => $order->products[$i]['qty']);
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
			$order_products_id 	= tep_db_insert_id();

			//------insert customer choosen option to order--------
			$attributes_exist 	= '0';
			$products_ordered_attributes 	= '';
			if (isset ($order->products[$i]['attributes'])) {
				$attributes_exist 			= '1';
				for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
					if (DOWNLOAD_ENABLED == 'true') {
						$attributes_query 	= "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pa.products_attributes_id=pad.products_attributes_id where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'";
						$attributes 		= tep_db_query($attributes_query);
					} else {
						$attributes 		= tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
					}
					$attributes_values 		= tep_db_fetch_array($attributes);

					$sql_data_array = array (
						'orders_id' 			=> $insert_id,
						'orders_products_id' 	=> $order_products_id,
						'products_options' 		=> $attributes_values['products_options_name'],
						'products_options_values' 	=> $attributes_values['products_options_values_name'],
						'options_values_price' 		=> $attributes_values['options_values_price'],
						'price_prefix' 				=> $attributes_values['price_prefix']
					);
					tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

					if ((DOWNLOAD_ENABLED == 'true') && isset ($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
						$sql_data_array = array (
							'orders_id' 				=> $insert_id,
							'orders_products_id' 		=> $order_products_id,
							'orders_products_filename' 	=> $attributes_values['products_attributes_filename'],
							'download_maxdays' 			=> $attributes_values['products_attributes_maxdays'],
							'download_count' 			=> $attributes_values['products_attributes_maxcount']
						);
						tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
					}
					$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
				}
			}
			//------insert customer choosen option eof ----
			$total_weight 	+= ($order->products[$i]['qty'] * $order->products[$i]['weight']);
			$total_tax 		+= tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
			$total_cost 	+= $total_products_price;

			$products_ordered 	.= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
		}
	}
	/**
	 * 根据订单id删除订单及相关表
	 */
	private function delete_order($order_id) {
		tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int) $order_id . "'");
		tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . "  where orders_id = '" . (int) $order_id . "'");
		tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int) $order_id . "'");
		tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int) $order_id . "'");
		tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int) $order_id . "'");
	}
	/**根据订单id修改订单表、订单总额表、订单历史记录表的信息
	 *
	 */
	function update_order($order_id) {
		global $order, $order_totals;
	}
	/**
	 **建立数据处理和行动时的“提交”按钮，在订单确认屏幕的压力。
	 *此发送数据进行处理支付网关。
	 *（这是隐藏在结账确认页字段）
	 */
	function process_button() {
		$this->confirmation("ok");
		global $order, $currencies, $order_totals;
		$customer 		= $order->customer;
		$billing 		= $order->billing;
		//账单人姓
		$FirstName 		= empty ($billing['firstname']) ? $customer['firstname'] : $billing['firstname'];
		//账单人名
		$LastName 		= empty ($billing['lastname']) ? $customer['lastname'] : $billing['lastname'];
		//账单人email
		$Email 			= $customer['email_address'];
		//账单人电话
		$Phone 			= $customer['telephone'];
		//账单人邮编
		$ZipCode 		= empty ($billing['postcode']) ? $customer['postcode'] : $billing['postcode'];
		//账单地址
		$Address 		= empty ($billing['street_address']) ? $customer['street_address'] : $billing['street_address'];
		//账单人城市
		$City 			= empty ($billing['city']) ? $customer['city'] : $billing['city'];
		//账单人省或州
		$State 			= empty ($billing['state']) ? $customer['state'] : $billing['state'];

		$delivery 		= $order->delivery;
		//收货人姓
		$DeliveryFirstName 	= empty ($delivery['firstname']) ? $FirstName : $delivery['firstname'];
		//收货人名
		$DeliveryLastName	= empty ($delivery['lastname']) ? $LastName : $delivery['lastname'];
		//收货人email
		$DeliveryEmail 		= empty ($delivery['email_address']) ? $Email : $delivery['email_address'];
		//收货人电话
		$DeliveryPhone 		= empty ($delivery['telephone']) ? $Phone : $delivery['telephone'];
		//收货人邮编
		$DeliveryZipCode 	= empty ($delivery['postcode']) ? $ZipCode : $delivery['postcode'];
		//收货人地址
		$DeliveryAddress 	= empty ($delivery['street_address']) ? $Address : $delivery['street_address'];
		//收货人城市
		$DeliveryCity 		= empty ($delivery['city']) ? $City : $delivery['city'];
		//收货人省或州
		$DeliveryState 		= empty ($delivery['state']) ? $State : $delivery['state'];
		//收货人国家
		$DeliveryCountry 	= empty ($delivery['country']['title']) ? empty ($billing['country']['title']) ? $customer['country']['title'] : $billing['country']['title'] : $delivery['country']['title'];

		//商户号
		$MerNo 				= trim(MODULE_PAYMENT_CARD_SELLER);
		//订单号(商户网站生成的订单号)
		$BillNo 			= $_SESSION['_card_order_id'];
		//交易金额
		/* $Amount 			= round($order->info['total'], 2); */
		$Amount 			= round($order->info['total'] * $order->info['currency_value'], 2);
		//商户密匙
		$MD5key 			= trim(MODULE_PAYMENT_CARD_MD5KEY);
		//币种(只接受1代表USD)
/* 		if (MODULE_PAYMENT_CARD_MONEYTYPE == 'USD') {
			$Currency = '1';
		} else
			if (MODULE_PAYMENT_CARD_MONEYTYPE == 'EUR') {
				$Currency = '2';
			} else
				if (MODULE_PAYMENT_CARD_MONEYTYPE == 'GBP') {
					$Currency = '4';
				} else
					if (MODULE_PAYMENT_CARD_MONEYTYPE == 'CNY') {
						$Currency = '3';
					} else {
						$Currency = '0';
					} */
		/* $Currency			= $this->getCardCurrency($_SESSION['currency']); */
		$Currency			= $this->getCardCurrency($order->info['currency']);
		//语言
		$Language 			= strtolower(MODULE_PAYMENT_CARD_LANGUAGE);
		// echo MODULE_PAYMENT_CARD_LANGUAGE;
		if($Language == 'auto'){
			$Langauge		= $this->getRequestBrowserLang();
		}
		//返回地址
		$ReturnURL 			= MODULE_PAYMENT_CARD_RETURN_URL;
		//商户网站首页地址
		$Remark 			= HTTP_SERVER;
		//组合加密项
		$MD5src 			= $MerNo . $BillNo . $Currency . $Amount . $Language . $ReturnURL . $MD5key;
		//echo $MD5src;
		//加密组合项
		$MD5info 			= strtoupper(md5($MD5src));
		//订单备注
		$OrderDesc 			= '';
		//是否开启支付日志
		$PayLog             = MODULE_PAYMENT_CARD_PAYLOG;
		
		/* Notice URL */
		$NoticeURL			= 'http://' . $_SERVER['HTTP_HOST'] . '/checkout.notice.php';
		
		$Products			= '';
		$exp 				= '###';
		$exa 				= '@@@';
		
		$proList			= $order->products;
		
	
		$tempList = "";
		foreach($proList as $key=>$value){
			
				$tempList .= "<GoodsName>".$value['name']."</GoodsName><Qty>".$value['qty']."</Qty><Price>".$value['price']."</Price><Currency>".$order->info['currency']."</Currency>";
			
			
		}
		$Products = "<Goods>".$tempList."</Goods>";


		$process_button_string = tep_draw_hidden_field('MerNo', $MerNo) .
		tep_draw_hidden_field('BillNo', $BillNo) .
		tep_draw_hidden_field('Amount', $Amount) .
		/* tep_draw_hidden_field('DispAmount', $Amount) . */
		tep_draw_hidden_field('Currency', $Currency) .
		tep_draw_hidden_field('Language', $Language) .
		tep_draw_hidden_field('MD5info', $MD5info) .
		tep_draw_hidden_field('ReturnURL', $ReturnURL) .
		tep_draw_hidden_field('OrderDesc', $OrderDesc) .
		tep_draw_hidden_field('Remark', $Remark) .
		tep_draw_hidden_field('FirstName', $FirstName) .
		tep_draw_hidden_field('LastName', $LastName) .
		tep_draw_hidden_field('Email', $Email) .
		tep_draw_hidden_field('Phone', $Phone) .
		tep_draw_hidden_field('ZipCode', $ZipCode) .
		tep_draw_hidden_field('Address', $Address) .
		tep_draw_hidden_field('City', $City) .
		tep_draw_hidden_field('State', $State) .
		tep_draw_hidden_field('DeliveryFirstName', $DeliveryFirstName) .
		tep_draw_hidden_field('DeliveryLastName', $DeliveryLastName) .
		tep_draw_hidden_field('DeliveryEmail', $DeliveryEmail) .
		tep_draw_hidden_field('DeliveryPhone', $DeliveryPhone) .
		tep_draw_hidden_field('DeliveryZipCode', $DeliveryZipCode) .
		tep_draw_hidden_field('DeliveryAddress', $DeliveryAddress) .
		tep_draw_hidden_field('DeliveryCity', $DeliveryCity) .
		tep_draw_hidden_field('DeliveryState', $DeliveryState) .
		tep_draw_hidden_field('Products', $Products).
		tep_draw_hidden_field('NoticeURL', $NoticeURL) .
		tep_draw_hidden_field('DeliveryCountry', $DeliveryCountry).
		tep_draw_hidden_field('PayLog', $PayLog);
		return $process_button_string;
	}
	/**生成订单后执行的方法(第二执行方法)
	 *
	 *(这个方法在includes/modules/checkout_process.php页面调用)
	 */
	function after_order_create($insert_id) {

	}
	function getCardCurrency($code)
	{
		$code 		= strtoupper($code);
		$CurrencyArray  = array('EUR'=>'2', 'USD'=>'1', 'JPY'=>'6', 'GBP'=>'4', 'AUD'=>'7', 'CAD'=>'11', 'NOK'=>'8','RMB'=>'3','DKK'=>'12','HKD'=>'5','SEK'=>'13','TWD'=>'14','RUB'=>'15');
		if(!$code || !isset($CurrencyArray[$code])){
			exit("Currency {$code} is not set");
		}
		return $CurrencyArray[$code];
	}
	/**提交到支付页面首先调用的方法(第一执行方法)
	 *存储交易信息的秩序和进程的任何结果，来自支付网关回
	 *(这个方法在includes/modules/checkout_process.php页面调用)
	 */
	function before_process() {
		return false;
	}
	/**(第三执行的方法)
	 **后处理活动
	 *当从处理器订单的回报，如果秒是成功的，这家以成果地位的历史，并记录为今后参考的数据
	 * @返回布尔
	 */
	function after_process() {
		return false;
	}
	/**
	*检查引荐
	*zf_domain
	* @帕拉姆字符串$
	* @返回布尔
	*/
	function check_referrer($zf_domain) {
		return true;
	}
	/**
	**建设管理页组件
	* @帕拉姆廉政$ zf_order_id
	* @返回字符串
	  */
	function admin_notification($zf_order_id) {

	}
	/**
	 *用于显示错误信息的详细 
	 * @返回布尔
	 */
	function output_error() {
		return false;
	}
	/**安装模块
	 *
	 */
	function install() {
		global $module_type;
		if (!defined('MODULE_PAYMENT_CARD_TEXT_CONFIG_1_1')) {
			include (DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/' . $module_type . '/' . $this->code . '.php');
		}

		$action_URL = HTTP_SERVER . DIR_WS_CATALOG . 'submitOrder.php';
		//模块安装状态
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_1_1 . "','MODULE_PAYMENT_CARD_STATUS','True','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_1_2 . "','6','0','tep_cfg_select_option(array(\'True\', \'False\'), ',now())");
		//商户编号
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_2_1 . "','MODULE_PAYMENT_CARD_SELLER','10003','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_2_2 . "','6','2',now())");
		//md5key
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_3_1 . "','MODULE_PAYMENT_CARD_MD5KEY','12345678','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_3_2 . "','6','4',now())");
		//币种
		/* tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_4_1 . "','MODULE_PAYMENT_CARD_MONEYTYPE','USD','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_4_2 . "','6','6','tep_cfg_select_option(array(\'USD\', \'EUR\',\'CNY\',\'GBP\'), ',now())"); */
		//支付日志
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_12_1 . "','MODULE_PAYMENT_CARD_PAYLOG','True','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_12_2 . "','6','0','tep_cfg_select_option(array(\'True\', \'False\'), ',now())");
		//语言
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_5_1 . "','MODULE_PAYMENT_CARD_LANGUAGE','2','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_5_2 . "','6','6','tep_cfg_select_option(array(\'auto\',\'en\', \'de\', \'es\',\'fr\',\'it\',\'ja\',\'ko\'), ',now())");
		//区域
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,use_function,set_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_6_1 . "','MODULE_PAYMENT_CARD_ZONE','0','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_6_2 . "','6','6','tep_get_zone_class_title','tep_cfg_pull_down_zone_classes(',now())");
		//订单状态描述
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,use_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_7_1 . "','MODULE_PAYMENT_CARD_ORDER_STATUS_ID','2','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_7_2 . "','6','8','tep_cfg_pull_down_order_statuses(','tep_get_order_status_name',now())");
		//订单完成状态描述
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,set_function,use_function,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_11_1 . "','MODULE_PAYMENT_CARD_ORDER_STATUS_FILISHED_ID','2','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_11_2 . "','6','8','tep_cfg_pull_down_order_statuses(','tep_get_order_status_name',now())");
		//排序
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_8_1 . "','MODULE_PAYMENT_CARD_SOTR_ORDER','0','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_8_2 . "','6','10',now())");
		//支付接口
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_9_1 . "','MODULE_PAYMENT_CARD_HANDLER','" . $action_URL . "','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_9_2 . "','6','12',now())");
		//返回地址
		tep_db_query("insert into " . TABLE_CONFIGURATION .
		"(configuration_title,configuration_key,configuration_value," .
		"configuration_description,configuration_group_id,sort_order,date_added" .
		") values('" . MODULE_PAYMENT_CARD_TEXT_CONFIG_10_1 . "','MODULE_PAYMENT_CARD_RETURN_URL','" . HTTP_SERVER . DIR_WS_CATALOG . "checkout.return.php','" .
		MODULE_PAYMENT_CARD_TEXT_CONFIG_10_2 . "','6','12',now())");
	}
	/**卸载模块
	 *
	 */
	function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE_PAYMENT_CARD%'");
	}

	/**检查是否已安装return 0 or 1
	 *
	 */
	function check() {
		if (!isset ($this->_check)) {
			$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CARD_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		return $this->_check;
	}
	/**设置安装模块的coufiguration_key信息
	 *(*内部配置项列表的模块的配置使用
	 *@返回数组)
	 */
	function keys() {
		return array (
			'MODULE_PAYMENT_CARD_STATUS', //模块状态
			'MODULE_PAYMENT_CARD_SELLER', //商户账号
			'MODULE_PAYMENT_CARD_MD5KEY', //md5key
			/* 'MODULE_PAYMENT_CARD_MONEYTYPE', //币种 */
			'MODULE_PAYMENT_CARD_ZONE', //区域
			'MODULE_PAYMENT_CARD_LANGUAGE', //语言
			'MODULE_PAYMENT_CARD_PAYLOG',    //支付日志
			'MODULE_PAYMENT_CARD_ORDER_STATUS_ID', //订单状态id
			'MODULE_PAYMENT_CARD_ORDER_STATUS_FILISHED_ID', //订单完成状态
			'MODULE_PAYMENT_CARD_SOTR_ORDER', //排序
			'MODULE_PAYMENT_CARD_HANDLER', //支付地址
			'MODULE_PAYMENT_CARD_RETURN_URL' //返回地址


		);
	}


	/**
	*	获取浏览器语言
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
