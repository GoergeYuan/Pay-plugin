<?php 

	class ControllerPaymentFirstTeam extends Controller {
		private $error = array(); 

		public function index() {
			$this->load->language('payment/FirstTeam');

			$this->document->setTitle($this->language->get('heading_title'));;
			
			$this->load->model('setting/setting');
				
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
				$this->load->model('setting/setting');
				
				$this->model_setting_setting->editSetting('FirstTeam', $this->request->post);				
				
				$this->session->data['success'] = $this->language->get('text_success');

				$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
			}

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_all_zones'] = $this->language->get('text_all_zones');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			
			$this->data['entry_account'] = $this->language->get('entry_account');
			$this->data['entry_secret'] = $this->language->get('entry_secret');
			$this->data['entry_Card_Type'] = $this->language->get('entry_Card_Type');
			$this->data['entry_test'] = $this->language->get('entry_test');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
			$this->data['entry_order_succeed_status'] = $this->language->get('entry_order_succeed_status');
			$this->data['entry_order_failed_status'] = $this->language->get('entry_order_failed_status');
			$this->data['entry_order_payWait_status_id'] = $this->language->get('entry_order_payWait_status_id');
			$this->data['entry_payment_url'] = $this->language->get('entry_payment_url');
			$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_return_url'] = $this->language->get('entry_return_url');

			$this->data['entry_Card_VISA'] = $this->language->get('entry_Card_VISA');
			$this->data['entry_Card_JCB'] = $this->language->get('entry_Card_JCB');
			$this->data['entry_Card_MASTER'] = $this->language->get('entry_Card_MASTER');
			$this->data['entry_Card_AE'] = $this->language->get('entry_Card_AE');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');

			$this->data['tab_general'] = $this->language->get('tab_general');
			 
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['account'])) {
				$this->data['error_account'] = $this->error['account'];
			} else {
				$this->data['error_account'] = '';
			}	

			if (isset($this->error['cardtype'])) {
				$this->data['error_cardtype'] = $this->error['cardtype'];
			} else {
				$this->data['error_cardtype'] = '';
			}	
			
			if (isset($this->error['secret'])) {
				$this->data['error_secret'] = $this->error['secret'];
			} else {
				$this->data['error_secret'] = '';
			}	

			if (isset($this->error['merchant'])) {
				$this->data['error_merchant'] = $this->error['merchant'];
			} else {
				$this->data['error_merchant'] = '';

			}
			if (isset($this->error['return_url'])) {
			$this->data['error_return_url'] = $this->error['return_url'];
			} else {
			$this->data['error_return_url'] = '';

			}	
			
			$this->document->breadcrumbs = array();

				$this->document->breadcrumbs[] = array(
					'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
					'text'      => $this->language->get('text_home'),
					'separator' => FALSE
				);

				$this->document->breadcrumbs[] = array(
					'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
					'text'      => $this->language->get('text_payment'),
					'separator' => ' :: '
				);

				$this->document->breadcrumbs[] = array(
					'href'      => HTTPS_SERVER . 'index.php?route=payment/FirstTeam&token=' . $this->session->data['token'],
					'text'      => $this->language->get('heading_title'),
					'separator' => ' :: '
				);
					
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/FirstTeam&token=' . $this->session->data['token'];
			
			$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
			
			if (isset($this->request->post['FirstTeam_account'])) {
				$this->data['FirstTeam_account'] = $this->request->post['FirstTeam_account'];
			} else {
				$this->data['FirstTeam_account'] = $this->config->get('FirstTeam_account');
			}

			if (isset($this->request->post['FirstTeam_secret'])) {
				$this->data['FirstTeam_secret'] = $this->request->post['FirstTeam_secret'];
			} else {
				$this->data['FirstTeam_secret'] = $this->config->get('FirstTeam_secret');
			}

			if (isset($this->request->post['FirstTeam_cardtype'])) {
				$this->data['FirstTeam_cardtype'] = $this->request->post['FirstTeam_cardtype'];
			} else {
				$this->data['FirstTeam_cardtype'] = $this->config->get('FirstTeam_cardtype');
			}
			
//return url
			if (isset($this->request->post['FirstTeam_return_url'])) {
				$this->data['FirstTeam_return_url'] = $this->request->post['FirstTeam_return_url'];
			} else {
				$this->data['FirstTeam_return_url'] = $this->config->get('FirstTeam_return_url');
			}

			if (isset($this->request->post['FirstTeam_order_status_id'])) {
				$this->data['FirstTeam_order_status_id'] = $this->request->post['FirstTeam_order_status_id'];
			} else {
				$this->data['FirstTeam_order_status_id'] = $this->config->get('FirstTeam_order_status_id'); 
			}
			
			if (isset($this->request->post['FirstTeam_order_succeed_status_id'])) {
				$this->data['FirstTeam_order_succeed_status_id'] = $this->request->post['FirstTeam_order_succeed_status_id'];
			} else {
				$this->data['FirstTeam_order_succeed_status_id'] = $this->config->get('FirstTeam_order_succeed_status_id'); 
			}

			if (isset($this->request->post['FirstTeam_order_failed_status_id'])) {
				$this->data['FirstTeam_order_failed_status_id'] = $this->request->post['FirstTeam_order_failed_status_id'];
			} else {
				$this->data['FirstTeam_order_failed_status_id'] = $this->config->get('FirstTeam_order_failed_status_id'); 
			}

			if (isset($this->request->post['FirstTeam_order_payWait_status_id'])) {
				$this->data['FirstTeam_order_payWait_status_id'] = $this->request->post['FirstTeam_order_payWait_status_id'];
			} else {
				$this->data['FirstTeam_order_payWait_status_id'] = $this->config->get('FirstTeam_order_payWait_status_id'); 
			}

			$this->load->model('localisation/order_status');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			if (isset($this->request->post['FirstTeam_payment_url'])) {
				$this->data['FirstTeam_payment_url'] = $this->request->post['FirstTeam_payment_url'];
			} else {
				$this->data['FirstTeam_payment_url'] = $this->config->get('FirstTeam_payment_url');
			}
			
			if (isset($this->request->post['FirstTeam_geo_zone_id'])) {
				$this->data['FirstTeam_geo_zone_id'] = $this->request->post['FirstTeam_geo_zone_id'];
			} else {
				$this->data['FirstTeam_geo_zone_id'] = $this->config->get('FirstTeam_geo_zone_id'); 
			}
			
			$this->load->model('localisation/geo_zone');
											
			$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
			
			if (isset($this->request->post['FirstTeam_status'])) {
				$this->data['FirstTeam_status'] = $this->request->post['FirstTeam_status'];
			} else {
				$this->data['FirstTeam_status'] = $this->config->get('FirstTeam_status');
			}
			
			if (isset($this->request->post['FirstTeam_sort_order'])) {
				$this->data['FirstTeam_sort_order'] = $this->request->post['FirstTeam_sort_order'];
			} else {
				$this->data['FirstTeam_sort_order'] = $this->config->get('FirstTeam_sort_order');
			}

			$this->template = 'payment/FirstTeam.tpl';
			$this->children = array(
				'common/header',	
				'common/footer'	
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}

		private function validate() {
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
			
			if (!$this->error) {
				return TRUE;
			} else {
				return FALSE;
			}	
		}
	}
?>