<?php
if(!defined('VERSION_INFO')){
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title><?php echo varGet($data, 'L_SUBMIT_ORDER_CONFIRM_TITLE'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="<?php echo getResource('view/css/style.css'); ?>" />
        <script type="text/javascript" src="<?php echo getResource('view/css/cardValidation.js'); ?>"></script>
        <!-- script type="text/javascript" src="http://pv.sohu.com/cityjson?ie=UTF-8"></script-->
    <style>
        #contexttable
        {
            font-family: Arial;
            color: #4d4d4d;
        }
        .labfontcolor
        {
            color: Red;
        }
        input[type=text], input[type=password]{
            padding: 2px 4px;
            width: 200px;
        }
        select{
            padding: 2px 4px;
        }
    </style>
</head>
<body style="font-family: Arial, Helvetica, sans-serif;">
    <form id="form1" method="post" action="<?php echo getResource('submitOrderQuery.php'); ?>" onSubmit="return submitCheck();">
	<!-- 收货地址 -->
    <input type="hidden" name="user_ip" id="user_ip" value="<?php echo varGet($data, 'user_ip'); ?>"/>
    <input type="hidden" name="server_ip" id="server_ip" value="<?php echo varGet($data, 'server_ip'); ?>"/>
	<input type="hidden" id="shippingFirstName" name="shippingFirstName" value="<?php echo varGet($data, 'DeliveryFirstName'); ?>" />
	<input type="hidden" id="shippingLastName" name="shippingLastName" value="<?php echo varGet($data, 'DeliveryLastName'); ?>" />
	<input type="hidden" id="shippingEmail" name="shippingEmail" value="<?php echo varGet($data, 'DeliveryEmail'); ?>" />
	<input type="hidden" id="shippingPhone" name="shippingPhone" value="<?php echo varGet($data, 'DeliveryPhone'); ?>" />
	<input type="hidden" id="shippingZipcode" name="shippingZipcode" value="<?php echo varGet($data, 'DeliveryZipCode'); ?>" />
	<input type="hidden" id="shippingAddress" name="shippingAddress" value="<?php echo varGet($data, 'DeliveryAddress'); ?>" />
	<input type="hidden" id="shippingCity" name="shippingCity" value="<?php echo varGet($data, 'DeliveryCity'); ?>" />
	<input type="hidden" id="shippingSstate" name="shippingSstate" value="<?php echo varGet($data, 'DeliveryState'); ?>" />
	<input type="hidden" id="shippingCountry" name="shippingCountry" value="<?php echo varGet($data, 'DeliveryCountry'); ?>" />
	
    <input type="hidden" name="order_token" value="<?php echo varGet($data, 'order_token'); ?>"/>
	<input type="hidden" name="account_id" value="<?php echo varGet($data, 'MerNo'); ?>"/>
    <input type="hidden" name="BillNo" value="<?php echo varGet($data, 'BillNo'); ?>"/>
	<input type="hidden" name="Currency" value="<?php echo varGet($data, 'Currency'); ?>" />
    <input type="hidden" name="Amount" value="<?php echo varGet($data, 'Amount'); ?>" />
	<input type="hidden" name="MD5info" value="<?php echo varGet($data, 'MD5info'); ?>" />
	<input type="hidden" name="Remark" value="<?php echo varGet($data, 'Remark'); ?>" />
    <input type="hidden" name="Language" value="<?php echo varGet($data, 'Language'); ?>"/>

	<input type="hidden" name="ReturnURL" value="<?php echo varGet($data, 'ReturnURL'); ?>" id="form1_ReturnURL"/>
	<input type="hidden" name="NoticeURL" value="<?php echo varGet($data, 'NoticeURL'); ?>"/>
	<input type="hidden" name="IsNotice" value="<?php echo varGet($data, 'IsNotice'); ?>"/>
	<input type="hidden" name="Products" value="<?php echo $Products; ?>" id="form1_products"/>

    <table width="920" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="362">
                <div style="float: left; border-radius: 8px; border: 1px solid #bbbbbb; width: 560px;
                    box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.5); height: auto; background: url(<?php echo getResource('view/images/bg_x.gif'); ?>);
                    background-repeat: repeat-x; margin: 10px 0 0 0;">
                    <div style="width: 520px; height: auto; border: 1px solid #dedede; border-radius: 8px;
                        background: #FFFFFF; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">
                        <div style="width: 295px; height: auto; margin: 10px; font-size: 12px;">
<div style="font-size: 12px; padding-top: 10px;">
                            <table width="464" height="175" align="center" class="tableCSS">
                                <tbody>
                                    <?php if(varGet($data, 'cardType')): ?>
                                        <tr>
                                            <td width="147" height="28">&nbsp;</td>
                                            <td width="29">&nbsp;</td>
                                            <td>
                                            	<?php foreach(varGet($data, 'cardType') as $imgName): ?>
                                            		<img src="<?php echo $imgName; ?>" border="0"/>
                                            	<?php endforeach; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_CARD_NUMBER'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="cardnum"
                                                maxlength="16" name="cardnum" onblur="checkCardNum();"/><span style="color:Red;"> * </span><br />
                                            <label id="lblCardNum" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="34"><div align="right"><b><?php echo varGet($data, 'L_EXPIRATION_DATE'); ?></b></div>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <select id="month" name="month" onblur="checkCDate()">
                                                <option value=""><?php echo varGet($data, 'L_MONTH_SELECT', 'Month'); ?></option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                            <select id="year" name="year" onblur="checkCDate()">
                                                <option value=""><?php echo varGet($data, 'L_YEAR_SELECT', 'Year'); ?></option>
                                                <option value="2013">2013</option>
                                                <option value="2014">2014</option>
                                                <option value="2015">2015</option>
                                                <option value="2016">2016</option>
                                                <option value="2017">2017</option>
                                                <option value="2018">2018</option>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                                <option value="2030">2030</option>
                                                <option value="2031">2031</option>
                                            </select><span style="color:Red;"> * </span><br /><label id="lblExpire" class="labfontcolor"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="41"><div align="right"><b><?php echo varGet($data, 'L_CVV2'); ?></b></div></td>
                                        <td><span class="tdCardLeft"></span></td>
                                        <td>
                                            <input style="width: 78px;" name="cvv2" id="cvv2"
                                                type="password" maxlength="3" onblur="checkCvv2()" /><span style="color:Red;"> * </span>
                                            <a class="aCvv" href="#" onclick="javascript:window.open('<?php echo getResource("view/cvv_demo.html"); ?>', 'cvvdemo','height=300,width=400,top=200,left=500,fullscreen=no');return false;">
                                                <img src="<?php echo getResource('view/images/pic04.gif'); ?>" style="border-style: None; vertical-align: middle;
                                                    margin-left: 8px;" /></a> 
                                            <label id="lblCvv2" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>

									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_FIRST_NAME'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="firstname"
                                                maxlength="64" name="firstname"  value="<?php echo $firstname; ?>" onblur="checkFirstName()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblFirstName" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_LAST_NAME'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style="" id="lastname" value="<?php echo $lastname; ?>" 
                                                maxlength="64" name="lastname" onblur="checkLastName()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblLastName" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_EMAIL'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="email"
                                                maxlength="64" name="email" value="<?php echo $email; ?>" onblur="checkEmail()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblEmail" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_PHONE'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="phone"
                                                maxlength="64" name="phone" value="<?php echo $phone; ?>" onblur="checkPhone()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblPhone" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_ZIP_CODE'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="zipcode"
                                                maxlength="64" name="zipcode" value="<?php echo $zipcode; ?>" onblur="checkZipCode()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblZipCode" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_ADDRESS'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="address"
                                                maxlength="64" name="address" value="<?php echo $address; ?>" onblur="checkAddress()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblAddress" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_CITY'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="city"
                                                maxlength="64" name="city" value="<?php echo $city; ?>" onblur="checkCity()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblCity" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_STATE'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="state"
                                                maxlength="64" name="state" value="<?php echo $state; ?>"/><br />
                                            <label id="lblState" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
																		<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_COUNTRY'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
<?php echo dropDownList('country', getCountryList(varGet($data, 'L_COUNTRY_SELECT', 'Please Select Country')), $country); ?>
<br/><label id="lblCountry" class="labfontcolor"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;
                                            
                                        </td>
                                        <td>&nbsp;
                                            
                                        </td>
                                        <td height="45">
                                            <input type="submit" value="<?php echo varGet($data, 'L_SUBMIT_VALUE', 'Submit'); ?>" id="btn1" 
                                                style="font-family: Arial, Helvetica, sans-serif; padding: 5px 10px;margin:10px 0;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </td>
            <td width="558" valign="top">
                <div style="float: left; width: 355px; position: inherit;margin:70px 0;">
                    <div style="color: #C88039; font-size: 16px; font-weight: bold; padding-left: 30px; margin:20px 0;"><?php echo varGet($data, 'L_ORDER_INFO'); ?></div>
                    <div style="border-top: 1px solid #bbbbbb; border-bottom: 1px solid #bbbbbb; border-right: 1px solid #bbbbbb;
                        box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.5); border-bottom-right-radius: 7px;
                        border-top-right-radius: 7px;">
                        <div style="font-size: 12px; padding-top: 10px; margin-left:32px;">
                            
                              <table width="295" border="0" cellspacing="0" cellpadding="0" id="contexttable">
                                <tr>
                                    <td height="31" colspan="2">
                                        <div>
                                         
                                            <strong>
                                                <?php echo varGet($data, 'L_ORDER_NUMBER'); ?></strong><br />
                                           <?php echo varGet($data, 'BillNo'); ?><br />
                                            <br />
                                            

                                            <strong>
                                                <?php echo varGet($data, 'L_PAYMENT_AMOUNT'); ?></strong><br />
                                                <?php if(!varGet($data, 'DisAmount')): ?>
                                                    <?php echo varGet($data, 'Amount'); ?>&nbsp;&nbsp;<?php echo varGet($data, 'CurrencyName'); ?>
                                                <?php else: ?>
                                                    <?php echo varGet($data, 'DisAmount'); ?>
                                                <?php endif; ?>
                                            <br />
                                            <br />
                                        </div>
                                    </td>
                                </tr>
                           
                                
                            </table>
							
                        </div>
						<div style=" width:300px; margin-bottom:20px; margin-left:32px; border-top:1px solid #000000">
                            <p><?php echo varGet($data, 'L_RIGHT_1'); ?></p> 
                            <p style="color:#990000"><?php echo varGet($data, 'L_RIGHT_2'); ?></p>
                            <p><?php echo varGet($data, 'L_RIGHT_3'); ?></p>
                       </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div style="width: 920px; margin: 0 auto; text-align: center; margin-top: 20px;">
        <div style="margin: 5px auto 15px; border-top: 2px solid rgb(220, 220, 220); width: 920px;
            float: left;">
        </div>
        <div style="margin-left: -56px;">
            <img src="<?php echo getResource('view/images/pci.gif'); ?>" width="166" height="50" alt="" style="border: none" />
            <img src="<?php echo getResource('view/images/nt.gif'); ?>" width="125" height="50" alt="" style="border: none; padding-left: 25px;" />
            <img src="<?php echo getResource('view/images/vs.gif'); ?>" width="103" height="50" alt="" style="border: none; padding-left: 25px;" />
            <img src="<?php echo getResource('view/images/tw.gif'); ?>" width="96" height="50" alt="" style="border: none; padding-left: 25px;" /><br />
            <div style="margin-top: 10px;"><?php echo varGet($data, 'L_COPY_RIGHT'); ?></div></div></div>
    </form>
    <div id="dvToolTip" style="width: 312px; height: 50px; font-family: Arial; font-size: 12px;
        background: url(<?php echo getResource('view/images/tooltip.gif'); ?>) no-repeat scroll 0 0 transparent;
        position: absolute; display: none; margin: 20px 0 0 -100px;">
        <div id="spTooTip" style="margin: 5px 20px 0 30px;">
            &nbsp;</div>
    </div>
    <script type="text/javascript" language="javascript">
        String.prototype.trim = function(){
            return this.replace(/(^\s*)|(\s*$)/g, "");
        }

        function G(id){
            return document.getElementById(id);
        }

        function checkRequiredInfo(id){
            var val = G(id).value;
            if(val == null || val.trim() == ""){
                return false;
            }
            return true;
        }

        function setError(id, message){
            var obj = G(id);
            if(obj == undefined){
                alert(id);
            }
            obj.innerHTML = message;
        }

        function checkCardNum(){
            var cardnum = G('cardnum').value;
            var cardPattern = /^\d{13,}$/;

            if(cardPattern.test(cardnum) &&chkCardNum(cardnum)){
                setError('lblCardNum', '');
                return true;
            }else{
                setError('lblCardNum', "<?php echo varGet($data, 'L_CARD_NUMBER_ERROR'); ?>");
                return false;
            }
        }

        function checkCDate(){
            var flag = checkRequiredInfo('month');
            if(!flag){
                setError('lblExpire', "<?php echo varGet($data, 'L_EXPIRATION_DATE_MONTH_REQUIRED'); ?>");
                return false;
            }
            flag = checkRequiredInfo('year');
            if(!flag){
                setError('lblExpire', "<?php echo varGet($data, 'L_EXPIRATION_DATE_YEAR_REQUIRED'); ?>");
                return false;
            }
            setError('lblExpire', '');
            return true;
        }

        function checkCvv2(){
            var cvv2 = G('cvv2').value;
            if(cvv2.trim() == ""){
                setError('lblCvv2', "<?php echo varGet($data, 'L_CVV2_REQUIRED'); ?>");
                return false;
            }
            if(/^\d{3}$/.test(cvv2)){
                setError('lblCvv2', '');
                return true;
            }else{
                setError('lblCvv2', "<?php echo varGet($data, 'L_CVV2_INCORRECT'); ?>");
                return false;
            }
        }

        function checkCardBank(){
            var flag = checkRequiredInfo('cardbank');
            if(flag){
                setError('lblCardBank', '');
                return true;
            }else{
                setError('lblCardBank', "<?php echo varGet($data, 'L_BANK_NAME_REQUIRED'); ?>");
                return false;
            }
        }

        function checkFirstName(){
            var flag = checkRequiredInfo('firstname');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_FIRST_NAME_REQUIRED'); ?>";
            }
            setError('lblFirstName', error);
            return flag;
        }

        function checkLastName(){
            var flag = checkRequiredInfo('lastname');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_LAST_NAME_REQUIRED'); ?>";
            }
            setError('lblLastName', error);
            return flag;
        }

        function checkEmail(){
            var email = G("email").value;
            var myReg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
            var error = '';
            var flag = myReg.test(email);
            if(!flag){
                error = "<?php echo varGet($data, 'L_EMAIL_INCORRECT'); ?>";
            }
            setError("lblEmail", error);
            return flag;
        }

        function checkPhone(){
            var flag = checkRequiredInfo('phone');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_PHONE_REQUIRED'); ?>";
            }
            setError('lblPhone', error);
            return flag;  
        }

        function checkZipCode(){
            var flag = checkRequiredInfo('zipcode');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_ZIP_CODE_REQUIRED'); ?>";
            }
            setError('lblZipCode', error);
            return flag;
        }

        function checkAddress(){
            var flag = checkRequiredInfo('address');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_ADDRESS_REQUIRED'); ?>";
            }
            setError('lblAddress', error);
            return flag;
        }

        function checkCity(){
            var flag = checkRequiredInfo('city');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_CITY_REQUIRED'); ?>";
            }
            setError('lblCity', error);
            return flag;
        }

        function checkCountry(){
            var flag = checkRequiredInfo('country');
            var error = '';
            if(!flag){
                error = "<?php echo varGet($data, 'L_COUNTRY_REQUIRED'); ?>";
            }
            setError('lblCountry', error);
            return flag;
        }

        function submitCheck(){
            var a = checkCardNum();
            var b = checkCDate();
            var c = checkCvv2();
            var d = true;
            // var d = checkCardBank();
            var e = checkFirstName();
            var f = checkLastName();
            var g = checkEmail();
            var h = checkPhone();
            var i = checkZipCode();
            var j = checkAddress();
            var k = checkCity();
            var l = checkCountry();
            if(a && b && c && d && e && f && g && h && i && j && k && l){
                return true;
            }else{
                return false;
            }
        }

        // document.getElementById("user_ip").value = returnCitySN.cip;
//         document.forms[0].user_ip.value = returnCitySN.cip;
//         alert(document.forms[0].user_ip.value);
    </script>
        <script type="text/javascript">
            maxmind_user_id = "42261";
            (function() {
                var loadDeviceJs = function() {
                    var element = document.createElement('script');
                    element.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//device.maxmind.com/js/device.js';
                    document.body.appendChild(element);
                };
                if (window.addEventListener) {
                    window.addEventListener('load', loadDeviceJs, false);
                } else if (window.attachEvent) {
                    window.attachEvent('onload', loadDeviceJs);
                }
            })();
        </script>

    



    


    
</body>
</html>
