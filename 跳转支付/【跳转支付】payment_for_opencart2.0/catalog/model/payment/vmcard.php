<?php
class ModelPaymentVmcard extends Model{

    public function getMethod($address, $total){

        $this->load->language('payment/vmcard');
/*        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('vmcard_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('vmcard_total') > 0 && $this->config->get('vmcard_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('vmcard_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }*/

        $method_data = array(
                'code' => 'vmcard',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('vmcard_sort_order')
            );

        return $method_data;
    }

    public function addOrder($order_id, $order_status_id, $comment = '', $notify = false){
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
    }
}