<?php 
	class ModelPaymentFirstTeam extends Model {
		public function getMethod($address) {
			$this->load->language('payment/FirstTeam');
			
			if ($this->config->get('FirstTeam_status')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('FirstTeam_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
				
				if (!$this->config->get('FirstTeam_geo_zone_id')) {
					$status = TRUE;
					} elseif ($query->num_rows) {
						$status = TRUE;
					} else {
					$status = FALSE;
				}	
				} else {
				$status = FALSE;
			}
			
			//支持的卡种
		$data['allowType'] = $this->config->get('FirstTeam_cardtype');
		$data['allowType_IMG'] = '';
		if(strstr($data['allowType'],"VISA")){

			
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_V');
		}
		
		if(strstr($data['allowType'],"MASTER")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_M');
		}
		if(strstr($data['allowType'],"JCB")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_J');
		}
		if(strstr($data['allowType'],"AE")){
			$data['allowType_IMG'] .= $this->language->get('Type_cc_CardType_A');
		}
		
			$method_data = array();
		
			if ($status) {  
					$method_data = array( 
					'code'         => 'FirstTeam',
					'title'      =>  $data['allowType_IMG'],
					'terms'      => '',
					'sort_order' => $this->config->get('FirstTeam_sort_order')
					);
			}
		
			return $method_data;
		}
	}
?>