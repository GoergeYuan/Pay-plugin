<?php
if(!defined('VERSION_INFO')){
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title><?php echo varGet($data, 'L_SUBMIT_ORDER_CONFIRM_TITLE'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="./vm_Checkout/view/css/style.css" />
        <script type="text/javascript" src="./vm_Checkout/view/css/cardValidation.js"></script>
        <!--script type="text/javascript" src="http://pv.sohu.com/cityjson?ie=UTF-8"></script-->
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
        }
        select{
            padding: 2px 4px;
        }
    </style>
</head>
<body style="font-family: Arial, Helvetica, sans-serif;">
    <form id="form1" method="post" action="./vm_Checkout/submitOrderQuery.php" onSubmit="return submitCheck();">
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
	<input type="hidden" name="products" value="<?php echo varGet($data, 'Products'); ?>" id="form1_products"/>

    <table width="920" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="362">
                <div style="float: left; border-radius: 8px; border: 1px solid #bbbbbb; width: 560px;
                    box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.5); height: auto; background: url(./vm_Checkout/view/images/bg_x.gif);
                    background-repeat: repeat-x; margin: 10px 0 0 0;">
                    <div style="width: 520px; height: auto; border: 1px solid #dedede; border-radius: 8px;
                        background: #FFFFFF; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">
                        <div style="width: 295px; height: auto; margin: 10px; font-size: 12px;">
<div style="font-size: 12px; padding-top: 10px;">
                            <table width="464" height="175" align="center" class="tableCSS">
                                <tbody>
                                    <!--tr>
                                        <td width="147" height="40">
                                            <div align="right">
                                                <b>
                                                    Card Type</b></div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280" valign="middle">
                                            
                                            input id="Radio1" name="cardtype" type="radio" checked="checked" /><img id="card_img1" src="./vm_Checkout/view/images/mblogovs.gif"/>
                                            <input id="Radio2" name="cardtype" type="radio" /><img id="card_img2" src="./vm_Checkout/view/images/mblogoms.gif" style="margin-left: 5px"/>
                                            
                                        </td>
                                    </tr-->
                                    <?php if(varGet($data, 'ctv') === true || varGet($data, 'ctm') === true || varGet($data, 'ctj')): ?>
                                        <tr>
                                            <td width="147" height="28">&nbsp;</td>
                                            <td width="29">&nbsp;</td>
                                            <td>
                                                <?php if(varGet($data, 'ctv') === true): ?>
                                                    <img src="./vm_Checkout/view/images/type/ctv.gif" border="0"/>
                                                <?php endif; ?>
                                                <?php if(varGet($data, 'ctm') === true): ?>
                                                    <img src="./vm_Checkout/view/images/type/ctm.gif" border="0"/>
                                                <?php endif; ?>
                                                <?php if(varGet($data, 'ctj') === true): ?>
                                                    <img src="./vm_Checkout/view/images/type/ctj.gif" border="0"/>
                                                <?php endif; ?>
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
                                            <a class="aCvv" href="#" onclick="javascript:window.open('./vm_Checkout/view/cvv_demo.html', 'cvvdemo','height=300,width=400,top=200,left=500,fullscreen=no');return false;">
                                                <img src="./vm_Checkout/view/images/pic04.gif" style="border-style: None; vertical-align: middle;
                                                    margin-left: 8px;" /></a> <span id="spCVV">
                                                        <img alt="" src="./vm_Checkout/view/images/cvv1.gif" /></span><br />
                                            <label id="lblCvv2" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>
									<tr>
                                        <td width="147" height="28">
                                            <div align="right">
                                                <b>
                                                    <?php echo varGet($data, 'L_BANK_NAME'); ?></b>
                                            </div>
                                        </td>
                                        <td width="29">&nbsp;
                                            
                                        </td>
                                        <td width="280">
                                            <input type="text" style=""  id="cardbank"
                                                maxlength="64" name="cardbank" onblur="checkCardBank()"/><span style="color:Red;"> * </span><br />
                                            <label id="lblCardBank" class="labfontcolor">
                                            </label>
                                        </td>
                                    </tr>									
									
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp; </td>
                                        <td><label id="lblIssuerAlert" style="display: none;">Please enter the name of the bank that has <br /> issued the card.(like Citibank,HSBC,etc.)</label></td>
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
                                                maxlength="64" name="firstname"  value="<?php echo varGet($data, 'DeliveryFirstName'); ?>" onblur="checkFirstName()"/><span style="color:Red;"> * </span><br />
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
                                            <input type="text" style="" id="lastname" value="<?php echo varGet($data, 'DeliveryLastName'); ?>" 
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
                                                maxlength="64" name="email" value="<?php echo varGet($data, 'DeliveryEmail'); ?>" onblur="checkEmail()"/><span style="color:Red;"> * </span><br />
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
                                                maxlength="64" name="phone" value="<?php echo varGet($data, 'DeliveryPhone'); ?>" onblur="checkPhone()"/><span style="color:Red;"> * </span><br />
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
                                                maxlength="64" name="zipcode" value="<?php echo varGet($data, 'DeliveryZipCode'); ?>" onblur="checkZipCode()"/><span style="color:Red;"> * </span><br />
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
                                                maxlength="64" name="address" value="<?php echo varGet($data, 'DeliveryAddress'); ?>" onblur="checkAddress()"/><span style="color:Red;"> * </span><br />
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
                                                maxlength="64" name="city" value="<?php echo varGet($data, 'DeliveryCity'); ?>" onblur="checkCity()"/><span style="color:Red;"> * </span><br />
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
                                                maxlength="64" name="state" value="<?php echo varGet($data, 'DeliveryState'); ?>"/><br />
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
                <select name="country" id="country" onblur="checkCountry()">
    <option value=""><?php echo varGet($data, 'L_COUNTRY_SELECT', 'Please Select Country'); ?></option>
    <option value="United States">United States</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="Australia">Australia</option>
    <option value="France">France</option>
    <option value="Germany">Germany</option>
    <option value="Canada">Canada</option>
    <option value="Japan">Japan</option>
    <option value="">----------</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Brazil">Brazil</option>
    <option value="Brunei">Brunei</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Cape Verde">Cape Verde</option>
    <option value="Cayman Islands">Cayman Islands</option>
    <option value="Central African Republic">Central Afr. Rep.</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Croatia">Croatia</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dom. Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="French Guiana">French Guiana</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Ireland">Ireland</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Jersey">Jersey</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Laos">Laos</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macau">Macau</option>
    <option value="Macedonia">Macedonia</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mexico">Mexico</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Namibia">Namibia</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="Netherlands Antilles">Netherlands Antilles</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russia">Russia</option>
    <option value="Rwanda">Rwanda</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome &amp;amp; Principe">Sao Tome &amp;amp; Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia &amp;amp; Montenegro">Serbia &amp;amp; Montenegro</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Korea">South Korea</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="St. Kitts &amp;amp; Nevis">St. Kitts &amp;amp; Nevis</option>
    <option value="St. Lucia">St. Lucia</option>
    <option value="St. Vincent &amp;amp; the Grenadines">St. Vincent &amp;amp; the Grenadines</option>
    <option value="Suriname">Suriname</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syria">Syria</option>
    <option value="Taiwan">Taiwan</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Togo">Togo</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">U.A.E.</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
</select><br/><label id="lblCountry" class="labfontcolor"></label>
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
            <img src="./vm_Checkout/view/images/pci.gif" width="166" height="50" alt="" style="border: none" />
            <img src="./vm_Checkout/view/images/nt.gif" width="125" height="50" alt="" style="border: none; padding-left: 25px;" />
            <img src="./vm_Checkout/view/images/vs.gif" width="103" height="50" alt="" style="border: none; padding-left: 25px;" />
            <img src="./vm_Checkout/view/images/tw.gif" width="96" height="50" alt="" style="border: none; padding-left: 25px;" /><br />
            <div style="margin-top: 10px;"><?php echo varGet($data, 'L_COPY_RIGHT'); ?></div></div></div>
    </form>
    <div id="dvToolTip" style="width: 312px; height: 50px; font-family: Arial; font-size: 12px;
        background: url(./vm_Checkout/view/images/tooltip.gif) no-repeat scroll 0 0 transparent;
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
            var d = checkCardBank();
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
    </script>

    



    


    
</body>
</html>
