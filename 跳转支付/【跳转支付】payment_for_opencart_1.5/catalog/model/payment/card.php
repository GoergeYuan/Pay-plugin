<?php 
class ModelPaymentCard extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/card');
		
		if ($this->config->get('card_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('card_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('card_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}	
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'         => 'card',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('card_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
    public function getProductsByOrderId($order_id, $currency)
    {   
        $sql        = "SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id=". (int)$order_id ;
        $querys     = $this->db->query($sql);
        $data       = $querys->rows;
        $count      = count($data, 0);
        $product_splider     = '###';
        $attribute_splider   = '@@@';
        $Products            = '';
        $Product             = array();
       // $currency            = 'EUR';
        for($i=0; $i<$count; $i++){
            /* Attribute */
            $sql2       = "SELECT * FROM `" . DB_PREFIX ."order_option` WHERE order_id=" . (int)$order_id . " AND  order_product_id=". (int)($data[$i]['order_product_id']); 
            $query2     = $this->db->query($sql2);
            
            $attr       = array();
            $attr_string    = '';
            
            if($query2){
                $data2     = $query2->rows;
                foreach($data2 as $key=>$value){
                    $attr[] = $value['value'];
                }
                $attr_string    = implode('+', $attr);
            }
            $default_currency = $this->config->get('config_currency');
            $Amount = $this->currency->convert($data[$i]['price'],trim($default_currency),$currency);

            $temp       = array(
                'name'      => $data[$i]['name'],
                'quantity'  => $data[$i]['quantity'],
                'price'     => round($Amount, 2) . $currency,
                // 'price'     => round($data[$i]['price'], 2) . $currency,
                'attr'      => $attr_string
            );
            $Product[]      = implode($attribute_splider, $temp);
        }
        return implode($product_splider, $Product);
    }
}
?>