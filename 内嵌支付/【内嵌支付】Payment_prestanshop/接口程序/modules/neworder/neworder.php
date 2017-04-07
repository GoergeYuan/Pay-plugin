<?php

//Prestashop为1.2版本无需定义下面常量.
if(strpos(_PS_VERSION_,"1.2") !== 0) {
    if (!defined('_CAN_LOAD_FILES_'))
    exit;
}
session_start();

/**
 * neworder 支付模块类
 * Class neworder
 */
class neworder extends PaymentModule {
    private $_postErrors = array();
    public $IVersion = "V7.0-A-200(1.5_1.6)";
    /**
     * 构造器
     */
    public function __construct() {
        
    
        
        $payment_gete = "payments_gateways";
        
        //Prestashop为1.4版本时,$payment_gete值为payments_gateways,其他版本为Payment.
        if(strpos(_PS_VERSION_,"1.4") === 0) {
            $payment_gete = "payments_gateways";
        }

        $this->name = 'neworder';
        $this->tab = $payment_gete;
        $this->author = 'Fashionpay';
        $this->version = '2.0.4';
        
        /* The parent construct is required for translations */
        parent::__construct();

        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Fashionpay Payment');
        $this->description = $this->l('Accepts payments by fashionpay Payment');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
    }

    /**
     * 安装模块
     * @return bool
     */
    public function install() {
        if (!parent::install() OR 
          !$this->registerHook('payment') OR 
          !$this->registerHook('paymentReturn') OR 
          !Configuration::updateValue('NEWORDER_MERCHANT_NO','') OR
          !Configuration::updateValue('NEWORDER_MERCHANT_KEY','') OR !Configuration::updateValue('NEWORDER_RETURN_URL','')) {
              return false;
          } else {
              return true;  
          }         
    }

    /**
     * 卸载模块
     * @return bool
     */
    public function uninstall() {
        return (
            parent::uninstall() AND
            Configuration::deleteByName('NEWORDER_MERCHANT_NO') AND
            Configuration::deleteByName('NEWORDER_MERCHANT_KEY') AND
            Configuration::deleteByName('NEWORDER_RETURN_URL') );
    }
    
    /**
     * 后台配置模块
     * @return string
     */
    public function getContent() {
         if (Tools::isSubmit('Submit_Cardpay_CheckOut')){
             
            if (empty($_POST['NEWORDER_MERCHANT_NO']))
                $this->_postErrors[] = $this->l('Merchant No is required.');
            if (!isset($_POST['NEWORDER_MERCHANT_KEY']))
                $this->_postErrors[] = $this->l('Sign Key is required.');
            if (!isset($_POST['NEWORDER_MERCHANT_KEY']))
                $this->_postErrors[] = $this->l('Return Url is required.');
            if (!sizeof($this->_postErrors))
            {
                Configuration::updateValue('NEWORDER_MERCHANT_NO', strval($_POST['NEWORDER_MERCHANT_NO']));
                Configuration::updateValue('NEWORDER_MERCHANT_KEY', strval($_POST['NEWORDER_MERCHANT_KEY']));
                Configuration::updateValue('NEWORDER_RETURN_URL', strval($_POST['NEWORDER_RETURN_URL']));
                $this->displayConf();
            }
            else
                $this->displayErrors();
         }
        $html = '<h2>'.$this->displayName.'</h2>
        <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
            <fieldset>
            <legend><img src="../modules/neworder/logo.gif" />'.$this->l('Settings').'</legend>
                <p>'.$this->l('First use the sandbox to test out the module then you can use the real mode if everything\'s fine. Don\'t forget to change your merchant key and id according to the mode!').'</p>
                <label> '.$this->l('Merchant No.').' </label>
                <div class="margin-form">
                    <input type="text" name="NEWORDER_MERCHANT_NO" value="'.Tools::getValue('NEWORDER_MERCHANT_NO', Configuration::get('NEWORDER_MERCHANT_NO')).'" size="30" />
                </div>
                <label> '.$this->l('Sign Key').' </label>
                <div class="margin-form">
                    <input type="text" name="NEWORDER_MERCHANT_KEY" value="'.Tools::getValue('NEWORDER_MERCHANT_KEY', Configuration::get('NEWORDER_MERCHANT_KEY')).'" size="30"/>
                </div>
                <label> '.$this->l('Return Url').' </label>
                <div class="margin-form">
                    <input type="text" name="NEWORDER_RETURN_URL" value="'.Tools::getValue('NEWORDER_RETURN_URL', Configuration::get('NEWORDER_RETURN_URL')).'" size="30"/><br/>
                    <span>'.(Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/payment_return/'.'</span>
                </div>
                 <div class="margin-form"><input type="submit" name="Submit_Cardpay_CheckOut" class="button" value="'.$this->l('  Save And Update ').'" /></div>
            </fieldset>
        </form>';

        return $html;
    }
    public function displayConf()
    {
        $this->_html .= '
        <div class="conf confirm">
            <img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
            '.$this->l('Settings updated').'
        </div>';
    }
    public function displayErrors()
    {
        $nbErrors = sizeof($this->_postErrors);
        $this->_html .= '
        <div class="alert error">
            <h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
            <ol>';
        foreach ($this->_postErrors AS $error)
            $this->_html .= '<li>'.$error.'</li>';
        $this->_html .= '
            </ol>
        </div>';
    }
    

    public function hookPayment($params){
        global $smarty;
        

        
        $this -> getNewOrderConfig();

        $orderNo = $this->generateOrderNo($params['cart']->id);
        $_SESSION['checkorderNo']=$orderNo;
        //$this->postMonitor($orderNo);
        $messages = $_SESSION['newOrderConfig']['messages'];
        $UserAgent = $_SERVER['HTTP_USER_AGENT'];
        $cardInputType = 'text';
        if(strpos($UserAgent,'webkit') || strpos($UserAgent,'firefox') || strpos($UserAgent,'trident') || strpos($UserAgent,'safari')){
            $cardInputType='tel';
        }
        $inputClass="col-xs-7";
        $selectClass="col-xs-2";
        $logoPixel = "100%";
        $this->context;
        $formUrl= $this->context->link->getModuleLink('neworder', 'submit', array('_'=>time()));
        if(stristr($UserAgent,'mobile') && !stristr($UserAgent,'ipad')){
            $inputClass="col-xs-9";
            $selectClass="col-xs-3";
            $logoPixel="100%";
        }
        $smarty->assign(array(
            'this_path' => $this->_path,
            'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name,
            'messages'=> $messages,
            'formUrl'=>$formUrl,
            'inputClass'=> $inputClass,
            'selectClass'=> $selectClass,
            'logoPixel'=>$logoPixel,
            'cardInputType'=>$cardInputType
        ));
        if(strpos(_PS_VERSION_,"1.6") !== false){
            return $this->display(__FILE__, 'form16.tpl');
        }
        return $this->display(__FILE__, 'form.tpl');
    }
    
    private function getNewOrderConfig(){
        $newOrderConfig = null;
        $languageCode = null;
        $arr=null;
        if (!isset($_SESSION['newOrderConfig'])  || $_SESSION['newOrderConfig']['messages']=='' ) {
            $languageCode =  $this->context->language->language_code;
            $lang = substr($languageCode,0,2);
            include('modules/neworder/lang/'.$lang.'.php');     
            if(!is_array($arr)){
                include('modules/neworder/lang/default.php');
            }   
        
             $_SESSION['newOrderConfig']['messages']=$arr;
           
            /*网关*/
            $_SESSION['newOrderConfig']['gatewayUrl']='https://merchant.paytos.com/CubePaymentGateway/gateway/action.NewSubmitAction.do';
            
            
           $_SESSION['newOrderConfig']['checkUrl']='https://merchant.paytos.com/CubePaymentGateway/gateway/action.MonitorAction.do';
           // $newOrderConfig['gatewayUrl']=$_SESSION['newOrderConfig']['gatewayUrl'];
           $newOrderConfig=$_SESSION['newOrderConfig'];
            $_SESSION['lang'] = $lang;
        } else {
            $newOrderConfig = $_SESSION['newOrderConfig'];
        }

        return $newOrderConfig;
    }
    public function disneworderSubmitResult($smarty){
        include_once(dirname(__FILE__) . '/../../config/config.inc.php');
        include_once(dirname(__FILE__).'/../../header.php');
         $smarty->display(dirname(__FILE__) . "/order_result.tpl");
        include_once(dirname(__FILE__).'/../../footer.php');
    }
    private function http_response($url,$data=null,$status = null, $wait = 3){ 
       $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_REFERER, '');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch);
    }
    
    public function getGatewayUrl() {
        $_url = $_SESSION['newOrderConfig']['gatewayUrl'];
        return $_url;
    }
    public function getMonitorUrl() {
        $_url = $_SESSION['newOrderConfig']['checkUrl'];
        return $_url;
    }

    public function getMessages() {
        return  $_SESSION['newOrderConfig']['messages'];
    }
    
    public function generateOrderNo($orderNo){
        return date('Ymd').$orderNo;
    }
    
    public function postMonitor($orderNo) {
        $merNo = Configuration::get('NEWORDER_MERCHANT_NO');
        $CMSVersion = _PS_VERSION_;
        $PHPVersion = phpversion();
        $data = array(
            'IVersion' => $this->IVersion,
            'CartID' => $orderNo,
            'AcctNo' =>$merNo,
            'CMSVersion'=>$CMSVersion,
            'PHPVersion'=>$PHPVersion,
            'Framework'=>'Prestashop'
        );

        $this->curl_post($url,http_build_query($data, '', '&'));
    }
    function curl_post($payUrl, $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $payUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
          curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            return false;
        }
        curl_close($curl);
        return $tmpInfo;
    }   
    

    public function hookPaymentReturn($params) {
        if (!$this->active)
            return;
        $succeed = $_SESSION['paymentResult']["succeed"];
        $payResult = $_SESSION['paymentResult']["payResult"];
        $amountLabel = $_SESSION['paymentResult']["amountLabel"];
        $transaction = $_SESSION['paymentResult']["transaction"];
        $errorMessage = $_SESSION['paymentResult']["errorMessage"];
        $orderNo = $_SESSION['paymentResult']["orderNo"];
        $orderCurrency = $_SESSION['paymentResult']["orderCurrency"];
        if ($payResult == '1')
        {
            $this->smarty->assign(array(
                'total_to_order' => Tools::displayPrice($params['total_to_order'], $params['currencyObj'], false),
                'succeed' => $succeed,
                'payResult' => $payResult,
                'amountLabel' =>$amountLabel,
                'errorMessage' =>$errorMessage,
                'orderNo' => $orderNo,
                'transaction' =>$transaction,
                'orderCurrency' => $orderCurrency,
                'id_order' => $params['objOrder']->id
            ));
            if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
                $this->smarty->assign('reference', $params['objOrder']->reference);
        }else{
            $this->smarty->assign('payResult', $payResult);
            $this->smarty->assign('errorMessage', $errorMessage);
        }

        return $this->display(__FILE__, 'payment_return.tpl');
    }


}
?>