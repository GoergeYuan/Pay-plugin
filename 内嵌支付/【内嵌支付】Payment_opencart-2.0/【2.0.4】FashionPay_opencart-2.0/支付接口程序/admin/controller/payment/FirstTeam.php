<?php
class ControllerPaymentFirstTeam extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/FirstTeam');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('FirstTeam', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		    //$this->data['heading_title'] = $this->language->get('heading_title');
		    $data['heading_title'] = $this->language->get('heading_title');

			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_all_zones'] = $this->language->get('text_all_zones');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			
			$data['entry_account'] = $this->language->get('entry_account');
			$data['entry_secret'] = $this->language->get('entry_secret');
			$data['entry_merchant'] = $this->language->get('entry_merchant');

			$data['entry_test'] = $this->language->get('entry_test');
			$data['entry_order_status'] = $this->language->get('entry_order_status');	
			$data['entry_order_succeed_status'] = $this->language->get('entry_order_succeed_status');
			$data['entry_order_payWait_status_id'] = $this->language->get('entry_order_payWait_status_id');
			$data['entry_order_failed_status'] = $this->language->get('entry_order_failed_status');
			$data['entry_payment_url'] = $this->language->get('entry_payment_url');
			$data['entry_return_url'] = $this->language->get('entry_return_url');
			$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_sort_order'] = $this->language->get('entry_sort_order');

			$data['entry_Card_Type'] = $this->language->get('entry_Card_Type');
			$data['entry_Card_VISA'] = $this->language->get('entry_Card_VISA');
			$data['entry_Card_JCB'] = $this->language->get('entry_Card_JCB');
			$data['entry_Card_MASTER'] = $this->language->get('entry_Card_MASTER');
			$data['entry_Card_AE'] = $this->language->get('entry_Card_AE');

			$data['FirstTeam_payment_url'] = $this->language->get('FirstTeam_payment_url');
			$data['FirstTeam_return_url'] = $this->language->get('FirstTeam_return_url');


			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}
		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}
		if (isset($this->error['cardtype'])) {
			$data['error_cardtype'] = $this->error['cardtype'];
		} else {
			$data['error_cardtype'] = '';
		}
		if (isset($this->error['return_url'])) {
			$data['error_return_url'] = $this->error['return_url'];
		} else {
			$data['error_return_url'] = '';
		}

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
			'href' => $this->url->link('payment/FirstTeam', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = HTTPS_SERVER . 'index.php?route=payment/FirstTeam&token=' . $this->session->data['token'];

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		
//接收商户号
		if (isset($this->request->post['FirstTeam_account'])) {
			$data['FirstTeam_account'] = $this->request->post['FirstTeam_account'];
		} else {
			$data['FirstTeam_account'] = $this->config->get('FirstTeam_account');
		}
//接收商户key
		if (isset($this->request->post['FirstTeam_secret'])) {
			$data['FirstTeam_secret'] = $this->request->post['FirstTeam_secret'];
		} else {
			$data['FirstTeam_secret'] = $this->config->get('FirstTeam_secret');
		}
//接收支付卡种类型
		if (isset($this->request->post['FirstTeam_cardtype'])) {
			$data['FirstTeam_cardtype'] = $this->request->post['FirstTeam_cardtype'];
		} else {
			$data['FirstTeam_cardtype'] = $this->config->get('FirstTeam_cardtype');
		}


		$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/FirstTeam/callback';

		if (isset($this->request->post['FirstTeam_test'])) {
			$data['FirstTeam_test'] = $this->request->post['FirstTeam_test'];
		} else {
			$data['FirstTeam_test'] = $this->config->get('FirstTeam_test');
		}

		if (isset($this->request->post['FirstTeam_total'])) {
			$data['FirstTeam_total'] = $this->request->post['FirstTeam_total'];
		} else {
			$data['FirstTeam_total'] = $this->config->get('FirstTeam_total');
		}
		
//¶©µ¥×´Ì¬		

		if (isset($this->request->post['FirstTeam_order_status_id'])) {
			$data['FirstTeam_order_status_id'] = $this->request->post['FirstTeam_order_status_id'];
		} else {
			$data['FirstTeam_order_status_id'] = $this->config->get('FirstTeam_order_status_id');
		}
//³É¹¦¶©µ¥×´Ì¬		
		if (isset($this->request->post['FirstTeam_order_succeed_status_id'])) {
			$data['FirstTeam_order_succeed_status_id'] = $this->request->post['FirstTeam_order_succeed_status_id'];
		} else {
			$data['FirstTeam_order_succeed_status_id'] = $this->config->get('FirstTeam_order_succeed_status_id');
		}
		
//Ê§°Ü¶©µ¥×´Ì¬		
		if (isset($this->request->post['FirstTeam_order_failed_status_id'])) {
			$data['FirstTeam_order_failed_status_id'] = $this->request->post['FirstTeam_order_failed_status_id'];
		} else {
			$data['FirstTeam_order_failed_status_id'] = $this->config->get('FirstTeam_order_failed_status_id');
		}

			if (isset($this->request->post['FirstTeam_order_payWait_status_id'])) {
			$data['FirstTeam_order_payWait_status_id'] = $this->request->post['FirstTeam_order_payWait_status_id'];
		} else {
			$data['FirstTeam_order_payWait_status_id'] = $this->config->get('FirstTeam_order_payWait_status_id');
		}
		

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
//gateway	
		if (isset($this->request->post['FirstTeam_payment_url'])) {
			$data['FirstTeam_payment_url'] = $this->request->post['FirstTeam_payment_url'];
		} else {
			$data['FirstTeam_payment_url'] = $this->config->get('FirstTeam_payment_url');
		}
//return url
		if (isset($this->request->post['FirstTeam_return_url'])) {
			$data['FirstTeam_return_url'] = $this->request->post['FirstTeam_return_url'];
		} else {
			$data['FirstTeam_return_url'] = $this->config->get('FirstTeam_return_url');
		}
		
		

		if (isset($this->request->post['FirstTeam_geo_zone_id'])) {
			$data['FirstTeam_geo_zone_id'] = $this->request->post['FirstTeam_geo_zone_id'];
		} else {
			$data['FirstTeam_geo_zone_id'] = $this->config->get('FirstTeam_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		

		if (isset($this->request->post['FirstTeam_status'])) {
			$data['FirstTeam_status'] = $this->request->post['FirstTeam_status'];
		} else {
			$data['FirstTeam_status'] = $this->config->get('FirstTeam_status');
		}

		if (isset($this->request->post['FirstTeam_sort_order'])) {
			$data['FirstTeam_sort_order'] = $this->request->post['FirstTeam_sort_order'];
		} else {
			$data['FirstTeam_sort_order'] = $this->config->get('FirstTeam_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/FirstTeam.tpl', $data));
	}
	

	
 
	
	
	
	

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/FirstTeam')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['FirstTeam_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['FirstTeam_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}
		if (!$this->request->post['FirstTeam_payment_url']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		if (!$this->request->post['FirstTeam_cardtype']) {
			$this->error['cardtype'] = $this->language->get('error_cardtype');
		}
		if (!$this->request->post['FirstTeam_return_url']) {
			$this->error['return_url'] = $this->language->get('error_return_url');
		}

		return !$this->error;
	}
}
?>