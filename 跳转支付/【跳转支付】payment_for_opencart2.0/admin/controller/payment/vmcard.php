<?php
class ControllerPaymentVmcard extends Controller{

    private $error = array();

    public function index(){

        $this->load->language('payment/vmcard');
        $this->document->setTitle($this->language->get('head_title'));
        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
            $this->model_setting_setting->editSetting('vmcard', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['error_warning'] = $this->varGet('error_warning');
        $data['entry_vmcard_payable'] = $this->language->get('entry_vmcard_payable');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zone'] = $this->language->get('text_all_zone');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['entry_new_order_status'] = $this->language->get('entry_new_order_status');
        $data['entry_success_order_status'] = $this->language->get('entry_success_order_status');
        $data['entry_failed_order_status'] = $this->language->get('entry_failed_order_status');
        $data['entry_process_order_status'] = $this->language->get('entry_process_order_status');

        $data['entry_vmcard_payable'] = $this->language->get('entry_vmcard_payable');
        $data['entry_vmcard_merchant_no'] = $this->language->get('entry_vmcard_merchant_no');
        $data['entry_vmcard_md5key'] = $this->language->get('entry_vmcard_md5key');
        $data['entry_vmcard_language'] = $this->language->get('entry_vmcard_language');
        $data['entry_vmcard_returnurl'] = $this->language->get('entry_vmcard_returnurl');
        $data['entry_vmcard_sort_order'] = $this->language->get('entry_vmcard_sort_order');
        $data['entry_vmcard_gateway'] = $this->language->get('entry_vmcard_gateway');
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/vmcard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('payment/vmcard', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        // $data['vmcard_payable'] = $this->varGet('vmcard_payable');
        $merchant_no = $this->varGet('vmcard_merchant_no');
        $data['vmcard_merchant_no'] = $merchant_no ? $merchant_no : '10003';
        $merchant_md5key = $this->varGet('vmcard_md5key');
        $data['vmcard_md5key'] = $merchant_md5key ? $merchant_md5key : '12345678';
        $return_url = $this->varGet('vmcard_returnurl');
        if(!$return_url){
            $return_url = HTTP_CATALOG . 'index.php?route=payment/vmcard/callback';
            // $return_url = $this->url->link('payment/vmcard/callback');
            // if(strpos($return_url, 'http://') !== 0){
            //     $return_url = 'http://' . $this->request->server['HTTP_HOST'] . '/' . ltrim($return_url);
            // }
        }
        $data['vmcard_returnurl'] = $return_url;
        $gateway_url = $this->varGet('vmcard_gateway');
        $data['vmcard_gateway'] = $gateway_url ? $gateway_url : rtrim(HTTP_CATALOG, '/') . '/submitOrder.php';

        $data['vmcard_new_order_status'] = $this->varGet('vmcard_new_order_status');
        $data['vmcard_success_order_status'] = $this->varGet('vmcard_success_order_status');
        $data['vmcard_failed_order_status'] = $this->varGet('vmcard_failed_order_status');
        $data['vmcard_process_order_status'] = $this->varGet('vmcard_process_order_status');
        $data['vmcard_sort_order'] = $this->varGet('vmcard_sort_order');


        $data['payable_list'] = $this->createSelect('vmcard_status', $this->varGet('vmcard_status'), array(1 => 'Enable', 0 => 'Disabled'));
        $vmcardLanguageList = array(
                'en' => 'english',
                'de' => 'German',
                'es' => 'Spanish',
                'fr' => 'French',
                'it' => 'Italian',
                'ja' => 'Japanese',
                'ko' => 'Korean'
            );
        $data['language_list'] = $this->createSelect('vmcard_language', $this->varGet('vmcard_langauge'), $vmcardLanguageList);

        $this->load->model('localisation/order_status');
        $order_status_lists = $this->model_localisation_order_status->getOrderStatuses();
        $order_status_list = array();

        foreach($order_status_lists as $k=> $row){
            $order_status_list[$row['order_status_id']] = $row['name']; 
        }

        $data['new_order_list'] = $this->createSelect('vmcard_new_order_status', $this->varGet('vmcard_new_order_status'), $order_status_list);
        $data['success_order_list'] = $this->createSelect('vmcard_success_order_status', $this->varGet('vmcard_success_order_status'), $order_status_list);
        $data['process_order_list'] = $this->createSelect('vmcard_process_order_status', $this->varGet('vmcard_process_order_status'), $order_status_list);
        $data['failed_order_list'] = $this->createSelect('vmcard_failed_order_status', $this->varGet('vmcard_failed_order_status'), $order_status_list);


        $data['error_merchant_no'] = (isset($this->error['error_merchant_no'])) ? $this->error['error_merchant_no'] : '';
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/vmcard.tpl', $data));
    }

    private function varGet($key){
        if(isset($this->request->post[$key])){
            return $this->request->post[$key];
        }else{
            return $this->config->get($key);
        }
    }

    private function createSelect($name, $selectedValue, $data){
        $start =<<<STR
        <select name="{$name}" id="id-{$name}" class="form-control">
STR;

        $content = '';
        $selected = '';
        foreach($data as $value=> $label){
            if($selectedValue == $value){
                $selected = ' selected="selected"';
            }
            $content .= '<option value="' . $value . '"' . $selected . '>' . $label . "</option>";
            $selected = '';
        }
        $end =<<<STR
        </select>
STR;
        return $start . $content . $end;
    }

    private function validate(){
        if (!$this->user->hasPermission('modify', 'payment/vmcard')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if(!$this->request->post['vmcard_merchant_no']){
            $this->error['error_merchant_no'] = 'merchant no is not empty';
        }
        return !$this->error;
    }
}