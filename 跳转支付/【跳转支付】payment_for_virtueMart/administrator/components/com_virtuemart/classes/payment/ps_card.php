<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*ps_card.php
* @version $Id: ps_card.php 1095 2007-12-19 20:19:16Z soeren_nb $
* @package VirtueMart
* @subpackage payment
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

/**
* The ps_card class for transactions with 2Checkout 
 */
 error_reporting(0);
class ps_card {

    var $payment_code = "card";
    var $classname = "ps_card";
  
    /**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
    function show_configuration() { 
    
      global $VM_LANG;
      $database = new ps_DB();
      /** Read current Configuration ***/
      require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
      $attr1    = "size='32'";
      $attr     = " size='40'";
    ?>
      <table>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LOGIN') ?></strong></td>
            <td>
                <input type="text" name="_CARD_LOGIN" class="inputbox" value="<? echo _CARD_LOGIN ?>"  <?php echo $attr1; ?>/>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LOGIN_EXPLAIN') ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_SECRETWORD') ?></strong></td>
            <td>
                <input type="text" name="_CARD_SECRETWORD" class="inputbox" value="<? echo _CARD_SECRETWORD ?>" <?php echo $attr1; ?>/>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_SECRETWORD_EXPLAIN') ?></td>
        </tr>
		
		<?php /*
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MONEYTYPE') ?></strong></td>
            <td>
                <input type="text" name="_CARD_MONEYTYPE" class="inputbox" value="<? echo _CARD_MONEYTYPE ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MONEYTYPE_EXPLAIN') ?></td>
        </tr>
		*/ ?>
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LANGUAGE') ?></strong></td>
            <td>
                <input type="text" name="_CARD_LANGUAGE" class="inputbox" value="<? echo _CARD_LANGUAGE ?>" <?php echo $attr1; ?>/>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LANGUAGE_EXPLAIN') ?></td>
        </tr>
        <?php /*
        <?php $languages    = array('de'=>'German', 'es'=> 'Spanish', 'fr'=> 'French', 'it'=> 'Italian', 'ja'=>'Japanese','ko'=>'Korean', 'en'=>'English'); ?>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LANGUAGE'); ?></strong></td>
            <td></td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_LANGUAGE_EXPLAIN'); ?></td>
        </tr>
        */ ?>
        <?php /*
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MERWEBSITE') ?></strong></td>
            <td>
                <input type="text" name="_CARD_MERWEBSITE" class="inputbox" value="<? echo _CARD_MERWEBSITE ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MERWEBSITE_EXPLAIN') ?></td>
        </tr>
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_REMARK') ?></strong></td>
            <td>
                <input type="text" name="_CARD_REMARK" class="inputbox" value="<? echo _CARD_REMARK ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_REMARK_EXPLAIN') ?></td>
        </tr>
		*/ ?>
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_SUBMIT_URL') ?></strong></td>
            <td>
                <input type="text" name="_CARD_SUBMIT_URL" class="inputbox" value="<? echo _CARD_SUBMIT_URL ?>" <?php echo $attr; ?>/>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_SUBMIT_URL_EXPLAIN') ?></td>
        </tr>
		
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_SUCC') ?></strong></td>
            <td>
                <select name="_CARD_PAY_SUCCESS_STATUS" class="inputbox" >
                <?php
                    $q = "SELECT order_status_name,order_status_code FROM #__{vm}_order_status ORDER BY list_order";
                    $database->query($q);
                    $rows = $database->record;
                    $order_status_code = Array();
                    $order_status_name = Array();
                    
                    foreach( $rows as $row ) {
                      $order_status_code[] = $row->order_status_code;
                      $order_status_name[] =  $row->order_status_name;
                    }
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (_CARD_PAY_SUCCESS_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_SUCC_EXPLAIN') ?>
            </td>
        </tr>
	            <tr>
	            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_FAIL') ?></strong></td>
	            <td>
	                <select name="_CARD_PAY_FAIL_STATUS" class="inputbox" >
	                <?php
	                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
	                      echo "<option value=\"" . $order_status_code[$i];
	                      if (_CARD_PAY_FAIL_STATUS == $order_status_code[$i]) 
	                         echo "\" selected=\"selected\">";
	                      else
	                         echo "\">";
	                      echo $order_status_name[$i] . "</option>\n";
	                    } ?>
	                    </select>
	            </td>
	            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_FAIL_EXPLAIN') ?>
	            </td>
	        </tr>
			
			 <tr>
	            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_PROCESSING') ?></strong></td>
	            <td>
	                <select name="_CARD_PAY_PROCESSING_STATUS" class="inputbox" >
	                <?php
	                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
	                      echo "<option value=\"" . $order_status_code[$i];
	                      if (_CARD_PAY_PROCESSING_STATUS == $order_status_code[$i]) 
	                         echo "\" selected=\"selected\">";
	                      else
	                         echo "\">";
	                      echo $order_status_name[$i] . "</option>\n";
	                    } ?>
	                    </select>
	            </td>
	            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_PROCESSING_EXPLAIN') ?>
	            </td>
	        </tr>
			
			 <tr>
	            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_CARDDECLINED') ?></strong></td>
	            <td>
	                <select name="_CARD_PAY_CARDDECLINED_STATUS" class="inputbox" >
	                <?php
	                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
	                      echo "<option value=\"" . $order_status_code[$i];
	                      if (_CARD_PAY_CARDDECLINED_STATUS == $order_status_code[$i]) 
	                         echo "\" selected=\"selected\">";
	                      else
	                         echo "\">";
	                      echo $order_status_name[$i] . "</option>\n";
	                    } ?>
	                    </select>
	            </td>
	            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_CARD_CARDDECLINED_EXPLAIN') ?>
	            </td>
	        </tr>
			
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MERCHANT_NOTIF') ?></strong></td>
            <td>
                <select name="_CARD_MERCHANT_EMAIL" class="inputbox" >
                  <option <? if (_CARD_MERCHANT_EMAIL == 'True') echo "selected=\"selected\""; ?> value="True"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                  <option <? if (_CARD_MERCHANT_EMAIL == 'False') echo "selected=\"selected\""; ?> value="False"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_MERCHANT_NOTIF_EXPLAIN') ?></td>
        </tr>
		
		 <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_EMAIL') ?></strong></td>
            <td>
                <input type="text" name="_CARD_EMAIL" class="inputbox" value="<? echo _CARD_EMAIL ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_CARD_EMAIL_EXPLAIN') ?></td>
        </tr>
		
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE') ?></strong></td>
            <td>
                <select name="_CARD_TESTMODE" class="inputbox" >
                  <option <? if (_CARD_TESTMODE == 'Y') echo "selected=\"selected\""; ?> value="Y"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                  <option <? if (_CARD_TESTMODE == 'N') echo "selected=\"selected\""; ?> value="N"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE_EXPLAIN') ?></td>
        </tr>
      </table>
   <?php
      // return false if there's no configuration
      return true;
   }
   
    function has_configuration() {
      // return false if there's no configuration
      return true;
   }
   
  /**
	* Returns the "is_writeable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
   function configfile_writeable() {
      return is_writeable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }
   
  /**
	* Returns the "is_readable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
   function configfile_readable() {
      return is_readable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }   
  /**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
   function write_configuration( &$d ) {
      
      $my_config_array = array("_CARD_LOGIN" => $d['_CARD_LOGIN'],
                                "_CARD_SECRETWORD" => $d['_CARD_SECRETWORD'],
								/* "_CARD_MONEYTYPE" => $d['_CARD_MONEYTYPE'], */
								"_CARD_LANGUAGE" => $d['_CARD_LANGUAGE'],
								"_CARD_MERWEBSITE" => $d['_CARD_MERWEBSITE'],
								"_CARD_REMARK" => $d['_CARD_REMARK'],
								"_CARD_SUBMIT_URL" => $d['_CARD_SUBMIT_URL'],
                                "_CARD_PAY_SUCCESS_STATUS" => $d['_CARD_PAY_SUCCESS_STATUS'],
                                "_CARD_PAY_FAIL_STATUS" => $d['_CARD_PAY_FAIL_STATUS'],
								"_CARD_PAY_PROCESSING_STATUS"=>$d['_CARD_PAY_PROCESSING_STATUS'],
								"_CARD_PAY_CARDDECLINED_STATUS"=>$d['_CARD_PAY_CARDDECLINED_STATUS'],
                                "_CARD_TESTMODE" => $d['_CARD_TESTMODE'],
                               "_CARD_MERCHANT_EMAIL" => $d['_CARD_MERCHANT_EMAIL'],
							   "_CARD_EMAIL"=>$d['_CARD_EMAIL']
                          );
      $config = "<?php\n";
      $config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
      foreach( $my_config_array as $key => $value ) {
        //$config .= 'define ("$key", "$value");'."\n";
		$config.='define ("'.$key.'","'.$value.'" ) ;'."\n";
      }
      
      $config .= "?>";
  
      if ($fp = fopen(CLASSPATH ."payment/".$this->classname.".cfg.php", "w")) {
          fputs($fp, $config, strlen($config));
          fclose ($fp);
          return true;
     }
     else
        return false;
   }
   
  /**************************************************************************
  ** name: process_payment()
  ** created by: soeren
  ** description: 
  ** parameters: $order_number, the number of the order, we're processing here
  **            $order_total, the total $ of the order
  ** returns: 
  ***************************************************************************/
   function process_payment($order_number, $order_total, &$d) {
      return true;

   }
   
   
}
