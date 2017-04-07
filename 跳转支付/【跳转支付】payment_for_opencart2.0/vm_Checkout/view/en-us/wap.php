<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta content="telephone=no" name="format-detection">
        <meta name="baidu-site-verification" content="t7oDT96Amk">
        <title>
            CheckOut
        </title>
        <meta name="format-detection" content="telephone=no" />
        <link rel="stylesheet" href="<?php echo getResource('images/Mobile/MoneyBrace.min.css'); ?>">
        <link href="<?php echo getResource('images/resources/css/jquery.mobile.structure-1.4.3.min.css'); ?>" rel="stylesheet" type="text/css" />
        <script src="<?php echo getResource('images/resources/script/jquery-1.11.1.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo getResource('images/resources/script/jquery.mobile-1.4.3.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo getResource('images/Mobile/Payment.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo getResource('images/resources/script/cardValidation.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo getResource('images/js/mobilecardholders.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo getResource('images/js/json2.js'); ?>"></script>
        <script language="javascript">
            function submitQuery() {
                isSubmit();
            }
        </script>
        <script type="text/javascript">
            var year;
            var month;
            $.mobile.ajaxEnabled = false;
            $.mobile.linkBindingEnabled = false;
            $.mobile.hashListeningEnabled = false;
            $.mobile.pushStateEnabled = false;

            function showLoader(txt, istextOnly, time, html) {
                $.mobile.loading('show', {
                    text: txt,
                    textVisible: true,
                    theme: 'a',
                    textonly: istextOnly,
                    html: html
                });
                if (time != 0) {
                    setTimeout(hideLoader, time);
                }
            }

            function hideLoader() {
                $.mobile.loading('hide');
            }

            function showImage() {
                showLoader("", true, 2000, "<img style='width:200px' src='./jsp/ibank/images/resources/images/cvv1.gif' />");
            }

            $(document).ready(function() {
                $("#submit").bind("click", function() {

                    $("#form1").submit();
                    showLoader("Submiting", false, 0, "");
                    $("#submit").attr("disabled", true).css("background-color", "").button("refresh");

                });
                var date = new Date();
                year = date.getFullYear();
                month = date.getMonth() + 1;
                $("#year").attr("min", year);
                $("#year").attr("max", year + 20);
                $("#year").val(year + 5);


                $("#hidScreen").val(window.devicePixelRatio);
                $("#hidBroswerType").val(navigator.userAgent);
                $("#hidOsType").val(navigator.userAgent);
            });





            function CreditCardByCtoH(obj) {
                var result = "";
                for (var i = 0; i < obj.length; i++) {
                    if (obj.charCodeAt(i) == 12288) {
                        result += String.fromCharCode(obj.charCodeAt(i) - 12256);
                        continue;
                    }
                    if (obj.charCodeAt(i) > 65280 && obj.charCodeAt(i) < 65375)
                        result += String.fromCharCode(obj.charCodeAt(i) - 65248);
                    else
                        result += String.fromCharCode(obj.charCodeAt(i));
                }
                obj = result;
                return result;
            }



            function MonthReset(obj) {
                var month = $(obj).val(); //jQuery 
                if (parseInt(month) < 10) {
                    $(obj).val("0" + month);
                }
            }
        </script>
    </head>

    <body>
        <div data-role="page" data-theme="a">
            <form name="form1" id="form1" action="<?php echo getResource('submitOrderQuery.php'); ?>" method="post" theme="simple">

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
                    <input type="hidden" name="Products" value="<?php echo $Products; ?>" id="form1_products"/>
					<input type="hidden" name="NoticeURL" value="<?php echo varGet($data, 'NoticeURL'); ?>"/>
					<input type="hidden" name="IsNotice" value="<?php echo varGet($data, 'IsNotice'); ?>"/>
					
                    <input type="hidden" name="firstname" value="<?php echo $firstname; ?>"/>
                    <input type="hidden" name="lastname" value="<?php echo $lastname; ?>"/>
                    <input type="hidden" name="phone" value="<?php echo $phone; ?>"/>
                    <input type="hidden" name="zipcode" value="<?php echo $zipcode; ?>"/>
                    <input type="hidden" name="address" value="<?php echo $address; ?>"/>
                    <input type="hidden" name="state" value="<?php echo $state; ?>"/>
                    <input type="hidden" name="city" value="<?php echo $city; ?>"/>


                <div style="background-color:#333333; height: 50px;">
                    <div style="padding-top: 10px;">
                        <h2 style="overflow: inherit; color: White; text-align: center; margin: 0 0;">
                            CREDIT CARD PAYMENT
                        </h2>
                    </div>
                </div>
                <div data-role="content" data-theme="a">
                    <ul data-role="listview" data-inset="true" style="margin-top: 0px;">
                        <li style="padding-right: 1em">
                            <label style="margin:0.5em 0 .4em">
                                <strong>Order No.:</strong>
                                <span class="ui-li-aside" style="right: 0em"> 
                                    <?php echo varGet($data, 'BillNo'); ?>
                                </span>
                            </label>
                        </li>
                        <li>
                            <label style="margin:0.5em 0 .4em">
                                <strong>Payment Amount:</strong>
                                <span style="color: red; right: 2px;" class="ui-li-aside">
                                    <?php if(varGet($data, 'DisAmount')): ?>
                                    <?php echo varGet($data, 'DisAmount'); ?>
                                    <?php else: ?>
                                    <?php echo varGet($data, 'Amount'); ?>&nbsp;&nbsp;
                                    <?php echo varGet($data, 'CurrencyName'); ?>
                                    <?php endif; ?>
                                </span>
                            </label>
                        </li>
                    </ul>
                    <h2 style=" font-size:18px">
                        <img style="width:20px" src="<?php echo getResource('images/resources/images/ico.png'); ?>" />
                        Card Information
                    </h2>
                    <ul data-role="listview" data-inset="true">
                        <li style="background-color: #ebebeb">
                            <div class="ui-grid-a">
                                <div class="ui-block-b" style="width:60%" align="left">
                                    <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
                                        <input type="radio" name="radio" id="Radio1" value="list" checked="checked" />
                                        <label for="Radio1" style="text-align:left; float:left">VISA</label>
                                        <!--  <input type="radio" name="radio" id="Radio2" value="grid"/>
                                <label for="Radio2">
                                    Master</label>-->
                                    </fieldset>
                                </div>
                            </div>
                        </li>
                        <li style="background-color: #ebebeb">
                            <div class="ui-grid-a">
                                <div class="ui-block-a" style="width:30%">
                                    <span class="ui-grid-label"><em style="color: #fe0107;">* </em>
                                        E-Mail:
                                    </span>
                                </div>
                                <div class="ui-block-b" style="width:70%">
                                    <input class="ui-btn-inline" type="text" id="email" name="email" placeholder="E-Mail" maxlength="64" value="<?php echo $email; ?>">
                                    <div class="Info" id="mailError" style="color: #fe0107;"></div>
                                </div>
                            </div>
                        </li>
                        <li style="background-color: #ebebeb">
                            <div class="ui-grid-a">
                                <div class="ui-block-a" style="width:30%">
                                    <span class="ui-grid-label"><em style="color: #fe0107;">* </em>
                                        Card No.:
                                    </span>
                                </div>
                                <div class="ui-block-b" style="width:70%">
                                    <input class="ui-btn-inline" type="text" id="cardnum" name="cardnum" placeholder="card number" maxlength="16" onBlur="checkcCardnum()" onpaste="javascript:return false;" value="">
                                </div>
                            </div>
                            <div class="Info" id="cardnumError" style="color: #fe0107;"></div>
                        </li>
                        <li style="background-color: #ebebeb">
                            <span class="ui-grid-label">
                                Month
                            </span>
                            <select name="month" id="month" selected="selected">
                                <option value="">----</option>
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
                            <span class="ui-grid-label">
                                Year:
                            </span>
                            <select name="year" id="year" selected="selected">
                                <option value="">----</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
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
                            </select>
                            <!--  <input type="range" name="month" id="month" value="00" min="01" onchange="MonthReset(this);"
                        max="12"  data-highlight="true"/>
                 </select>
                </li>
                <li style="background-color: #ebebeb">
                    <label for="year">
                        <strong>Year:</strong></label>
                    <input type="range" name="year" id="year" value="2014" min="2010"
                        max="2030"  data-highlight="true">
                </li>-->
                            <li style="background-color: #ebebeb">
                                <!-- <a href="javascript:void(0)" style=" background-color: #ebebeb"> -->
                                <div class="ui-grid-a">
                                    <div class="ui-block-a" style="width:70%">
                                        <span class="ui-grid-label"><em style="color: #fe0107;">* </em>CVV2/CVC/CVV:</span>
                                    </div>
                                    <div class="ui-block-b" style="width:30%">
                                        <input name="cvv2" id="cvv2" placeholder="" value="" type="password" maxlength="3" />
                                    </div>
                                </div>
                                <div class="Info" id="cvv2Error" style="color: #fe0107;"></div>
                                </a>
                            </li>
                            <li style="background-color: #ebebeb">
                                <div class="ui-grid-a">
                                    <div class="ui-block-a">
                                        <span class="ui-grid-label"><em style="color: #fe0107;">* </em>
                                            Issuing Country:
                                        </span>
                                    </div>
                                </div>
                                <div class="ui-block-b" style="width:100%;">
                                    <select name="country" id="country" style="float:left;">
                                    </select>
                                </div>
                                <div class="Info" id="countryError" style="color: #fe0107;"></div>
                            </li>
                    </ul>
                    <a href="javascript:void(0)" onclick="return submitQuery()" id="submit2" class="ui-btn ui-corner-all" data-ajax="false" rel="external" style="background-color: #333333;color: #ffffff;">
                        <span style=" font-size:18px">
                            MAKE PAYMENT
                        </span>
                    </a>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            var cou_code = new Array();
            var cou_name = new Array();
            cou_code.push("US");

            cou_name.push("United States  ");
            cou_code.push("UK");

            cou_name.push("United Kingdom ");
            cou_code.push("AF");

            cou_name.push("Afghanistan");
            cou_code.push("AX");

            cou_name.push("Aland Islands");
            cou_code.push("AL");

            cou_name.push("Albania");
            cou_code.push("DZ");

            cou_name.push("Algeria");
            cou_code.push("AS");

            cou_name.push("American Samoa");
            cou_code.push("AD");

            cou_name.push("Andorra");
            cou_code.push("AO");

            cou_name.push("Angola");
            cou_code.push("AI");

            cou_name.push("Anguilla");
            cou_code.push("AP");

            cou_name.push("Antarctica");
            cou_code.push("AG");

            cou_name.push("Antigua and Barbuda");
            cou_code.push("AR");

            cou_name.push("Argentina");
            cou_code.push("AM");

            cou_name.push("Armenia");
            cou_code.push("AW");

            cou_name.push("Aruba ");
            cou_code.push("AU");

            cou_name.push("Australia");
            cou_code.push("AT");

            cou_name.push("Austria");
            cou_code.push("AZ");

            cou_name.push("Azerbaijan");
            cou_code.push("BS");

            cou_name.push("Bahamas");
            cou_code.push("BH");

            cou_name.push("Bahrain");
            cou_code.push("BD");

            cou_name.push("Bangladesh");
            cou_code.push("BB");

            cou_name.push("Barbados");
            cou_code.push("BY");

            cou_name.push("Belarus");
            cou_code.push("BE");

            cou_name.push("Belgium");
            cou_code.push("BZ");

            cou_name.push("Belize");
            cou_code.push("BJ");

            cou_name.push("Benin");
            cou_code.push("BM");

            cou_name.push("Bermuda");
            cou_code.push("BT");

            cou_name.push("Bhutan");
            cou_code.push("BO");

            cou_name.push("Bolivia  ");
            cou_code.push("BA");

            cou_name.push("Bosnia and Herzegovina");
            cou_code.push("BW");

            cou_name.push("Botswana ");
            cou_code.push("BV");

            cou_name.push("Bouvet Island");
            cou_code.push("BR");

            cou_name.push("Brazil");
            cou_code.push("IO");

            cou_name.push("British Indian Ocean Territory");
            cou_code.push("BN");

            cou_name.push("Brunei");
            cou_code.push("BG");

            cou_name.push("Bulgaria");
            cou_code.push("BF");

            cou_name.push("Burkina Faso");
            cou_code.push("BI");

            cou_name.push("Burundi");
            cou_code.push("KH");

            cou_name.push("Cambodia");
            cou_code.push("CM");

            cou_name.push("Cameroon");
            cou_code.push("CA");

            cou_name.push("Canada");
            cou_code.push("CV");

            cou_name.push("Cape Verde");
            cou_code.push("KY");

            cou_name.push("Cayman Islands ");
            cou_code.push("CF");

            cou_name.push("Central African Republic");
            cou_code.push("TD");

            cou_name.push("Chad");
            cou_code.push("CL");

            cou_name.push("Chile ");
            cou_code.push("CN");

            cou_name.push("China");
            cou_code.push("CO");

            cou_name.push("Colombia ");
            cou_code.push("KM");

            cou_name.push("Comoros");
            cou_code.push("CG");

            cou_name.push("Congo ");
            cou_code.push("CD");

            cou_name.push("Congo, Democratic Republic of the ");
            cou_code.push("CK");

            cou_name.push("Cook Islands");
            cou_code.push("CR");

            cou_name.push("Costa Rica  ");
            cou_code.push("CI");

            cou_name.push("C?te d'Ivoire ");
            cou_code.push("HR");

            cou_name.push("Croatia");
            cou_code.push("CU");

            cou_name.push("Cuba  ");
            cou_code.push("CY");

            cou_name.push("Cyprus");
            cou_code.push("CZ");

            cou_name.push("Czech Republic");
            cou_code.push("DK");

            cou_name.push("Denmark");
            cou_code.push("DJ");

            cou_name.push("Djibouti");
            cou_code.push("DM");

            cou_name.push("Dominica");
            cou_code.push("DO");

            cou_name.push("Dominican Republic");
            cou_code.push("EC");

            cou_name.push("Ecuador");
            cou_code.push("EG");

            cou_name.push("Egypt");
            cou_code.push("SV");

            cou_name.push("El Salvador ");
            cou_code.push("GQ");

            cou_name.push("Equatorial Guinea");
            cou_code.push("ER");

            cou_name.push("Eritrea");
            cou_code.push("EE");

            cou_name.push("Estonia");
            cou_code.push("ET");

            cou_name.push("Ethiopia");
            cou_code.push("FK");

            cou_name.push("Falkland Islands");
            cou_code.push("FO");

            cou_name.push("Faroe Islands");
            cou_code.push("FJ");

            cou_name.push("Fiji  ");
            cou_code.push("FI");

            cou_name.push("Finland");
            cou_code.push("FR");

            cou_name.push("France");
            cou_code.push("GF");

            cou_name.push("French Guiana");
            cou_code.push("PF");

            cou_name.push("French Polynesia");
            cou_code.push("TF");

            cou_name.push("French Southern Territories");
            cou_code.push("GA");

            cou_name.push("Gabon ");
            cou_code.push("GM");

            cou_name.push("Gambia");
            cou_code.push("GE");

            cou_name.push("Georgia");
            cou_code.push("DE");

            cou_name.push("Germany");
            cou_code.push("GH");

            cou_name.push("Ghana ");
            cou_code.push("GI");

            cou_name.push("Gibraltar");
            cou_code.push("GR");

            cou_name.push("Greece");
            cou_code.push("GL");

            cou_name.push("Greenland");
            cou_code.push("GD");

            cou_name.push("Grenada");
            cou_code.push("GP");

            cou_name.push("Guadeloupe");
            cou_code.push("GU");

            cou_name.push("Guam  ");
            cou_code.push("GT");

            cou_name.push("Guatemala");
            cou_code.push("GG");

            cou_name.push("Guernsey ");
            cou_code.push("GN");

            cou_name.push("Guinea");
            cou_code.push("GW");

            cou_name.push("Guinea-Bissau");
            cou_code.push("GY");

            cou_name.push("Guyana");
            cou_code.push("HT");

            cou_name.push("Haiti");
            cou_code.push("HN");

            cou_name.push("Honduras");
            cou_code.push("HK");

            cou_name.push("Hong Kong");
            cou_code.push("HU");

            cou_name.push("Hungary");
            cou_code.push("IS");

            cou_name.push("Iceland");
            cou_code.push("IN");

            cou_name.push("India ");
            cou_code.push("ID");

            cou_name.push("Indonesia");
            cou_code.push("IR");

            cou_name.push("Iran");
            cou_code.push("IQ");

            cou_name.push("Iraq");
            cou_code.push("IE");

            cou_name.push("Ireland");
            cou_code.push("IM");

            cou_name.push("Isle of Man");
            cou_code.push("IL");

            cou_name.push("Israel");
            cou_code.push("IT");

            cou_name.push("Italy");
            cou_code.push("JM");

            cou_name.push("Jamaica ");
            cou_code.push("JP");

            cou_name.push("Japan");
            cou_code.push("JE");

            cou_name.push("Jersey");
            cou_code.push("JO");

            cou_name.push("Jordan");
            cou_code.push("KZ");

            cou_name.push("Kazakhstan ");
            cou_code.push("KE");

            cou_name.push("Kenya ");
            cou_code.push("KI");

            cou_name.push("Kiribati ");
            cou_code.push("KW");

            cou_name.push("Kuwait");
            cou_code.push("KG");

            cou_name.push("Kyrgyzstan");
            cou_code.push("LA");

            cou_name.push("Laos");
            cou_code.push("LV");

            cou_name.push("Latvia");
            cou_code.push("LB");

            cou_name.push("Lebanon");
            cou_code.push("LS");

            cou_name.push("Lesotho  ");
            cou_code.push("LR");

            cou_name.push("Liberia  ");
            cou_code.push("LY");

            cou_name.push("Libya ");
            cou_code.push("LI");

            cou_name.push("Liechtenstein  ");
            cou_code.push("LT");

            cou_name.push("Lithuania  ");
            cou_code.push("LU");

            cou_name.push("Luxembourg ");
            cou_code.push("MO");

            cou_name.push("Macao ");
            cou_code.push("MK");

            cou_name.push("Macedonia ");
            cou_code.push("MG");

            cou_name.push("Madagascar ");
            cou_code.push("MW");

            cou_name.push("Malawi");
            cou_code.push("MY");

            cou_name.push("Malaysia ");
            cou_code.push("MV");

            cou_name.push("Maldives ");
            cou_code.push("ML");

            cou_name.push("Mali  ");
            cou_code.push("MT");

            cou_name.push("Malta ");
            cou_code.push("MH");

            cou_name.push("Marshall Islands  ");
            cou_code.push("MQ");

            cou_name.push("Martinique  ");
            cou_code.push("MR");

            cou_name.push("Mauritania ");
            cou_code.push("MU");

            cou_name.push("Mauritius");
            cou_code.push("YT");

            cou_name.push("Mayotte  ");
            cou_code.push("MX");

            cou_name.push("Mexico");
            cou_code.push("FM");

            cou_name.push("Micronesia  ");
            cou_code.push("MD");

            cou_name.push("Moldova  ");
            cou_code.push("MC");

            cou_name.push("Monaco");
            cou_code.push("MN");

            cou_name.push("Mongolia");
            cou_code.push("ME");

            cou_name.push("Montenegro ");
            cou_code.push("MS");

            cou_name.push("Montserrat  ");
            cou_code.push("MA");

            cou_name.push("Morocco");
            cou_code.push("MZ");

            cou_name.push("Mozambique");
            cou_code.push("MM");

            cou_name.push("Myanmar ");
            cou_code.push("NA");

            cou_name.push("Namibia  ");
            cou_code.push("NR");

            cou_name.push("Nauru ");
            cou_code.push("NP");

            cou_name.push("Nepal ");
            cou_code.push("NL");

            cou_name.push("Netherlands ");
            cou_code.push("AN");

            cou_name.push("Netherlands Antilles ");
            cou_code.push("NC");

            cou_name.push("New Caledonia  ");
            cou_code.push("NZ");

            cou_name.push("New Zealand ");
            cou_code.push("NI");

            cou_name.push("Nicaragua");
            cou_code.push("NE");

            cou_name.push("Niger ");
            cou_code.push("NG");

            cou_name.push("Nigeria  ");
            cou_code.push("NU");

            cou_name.push("Niue  ");
            cou_code.push("NF");

            cou_name.push("Norfolk Island ");
            cou_code.push("KR");

            cou_name.push("North Korea ");
            cou_code.push("MP");

            cou_name.push("Northern Mariana Islands");
            cou_code.push("NO");

            cou_name.push("Norway  ");
            cou_code.push("OM");

            cou_name.push("Oman  ");
            cou_code.push("PK");

            cou_name.push("Pakistan  ");
            cou_code.push("PW");

            cou_name.push("Palau ");
            cou_code.push("PS");

            cou_name.push("Palestinian Territories ");
            cou_code.push("PA");

            cou_name.push("Panama ");
            cou_code.push("PG");

            cou_name.push("Papua New Guinea  ");
            cou_code.push("PY");

            cou_name.push("Paraguay ");
            cou_code.push("PE");

            cou_name.push("Peru ");
            cou_code.push("PH");

            cou_name.push("Philippines ");
            cou_code.push("PL");

            cou_name.push("Poland ");
            cou_code.push("PT");

            cou_name.push("Portugal ");
            cou_code.push("PR");

            cou_name.push("Puerto Rico ");
            cou_code.push("QA");

            cou_name.push("Qatar ");
            cou_code.push("RE");

            cou_name.push("Reunion  ");
            cou_code.push("RO");

            cou_name.push("Romania ");
            cou_code.push("RU");

            cou_name.push("Russia ");
            cou_code.push("RW");

            cou_name.push("Rwanda");
            cou_code.push("KN");

            cou_name.push("Saint Kitts and Nevis");
            cou_code.push("LC");

            cou_name.push("Saint Lucia ");
            cou_code.push("PM");

            cou_name.push("Saint Pierre and Miquelon");
            cou_code.push("VC");

            cou_name.push("Saint Vincent and the Grenadines");
            cou_code.push("WS");

            cou_name.push("Samoa ");
            cou_code.push("SM");

            cou_name.push("San Marino  ");
            cou_code.push("ST");

            cou_name.push("S?o Tom and Prncipe");
            cou_code.push("SA");

            cou_name.push("Saudi Arabia ");
            cou_code.push("SN");

            cou_name.push("Senegal ");
            cou_code.push("RS");

            cou_name.push("Serbia ");
            cou_code.push("CS");

            cou_name.push("Serbia and Montenegro ");
            cou_code.push("SC");

            cou_name.push("Seychelles  ");
            cou_code.push("SL");

            cou_name.push("Sierra Leone");
            cou_code.push("SG");

            cou_name.push("Singapore ");
            cou_code.push("SK");

            cou_name.push("Slovakia ");
            cou_code.push("SI");

            cou_name.push("Slovenia ");
            cou_code.push("SB");

            cou_name.push("Solomon Islands");
            cou_code.push("SO");

            cou_name.push("Somalia ");
            cou_code.push("ZA");

            cou_name.push("South Africa");
            cou_code.push("GS");

            cou_name.push("South Georgia and the South Sandwich Islands  ");
            cou_code.push("KO");

            cou_name.push("South Korea ");
            cou_code.push("ES");

            cou_name.push("Spain ");
            cou_code.push("LK");

            cou_name.push("Sri Lanka");
            cou_code.push("SD");

            cou_name.push("Sudan ");
            cou_code.push("SR");

            cou_name.push("Suriname ");
            cou_code.push("SZ");

            cou_name.push("Swaziland");
            cou_code.push("SE");

            cou_name.push("Sweden ");
            cou_code.push("CH");

            cou_name.push("Switzerland ");
            cou_code.push("SY");

            cou_name.push("Syria ");
            cou_code.push("TW");

            cou_name.push("Taiwan ");
            cou_code.push("TJ");

            cou_name.push("Tajikistan  ");
            cou_code.push("TZ");

            cou_name.push("Tanzania ");
            cou_code.push("TH");

            cou_name.push("Thailand ");
            cou_code.push("TL");

            cou_name.push("Timor-Leste ");
            cou_code.push("TG");

            cou_name.push("Togo  ");
            cou_code.push("TK");

            cou_name.push("Tokelau  ");
            cou_code.push("TO");

            cou_name.push("Tonga ");
            cou_code.push("TT");

            cou_name.push("Trinidad and Tobago  ");
            cou_code.push("TN");

            cou_name.push("Tunisia ");
            cou_code.push("TR");

            cou_name.push("Turkey ");
            cou_code.push("TM");

            cou_name.push("Turkmenistan ");
            cou_code.push("TC");

            cou_name.push("Turks and Caicos Islands");
            cou_code.push("TV");

            cou_name.push("Tuvalu");
            cou_code.push("UG");

            cou_name.push("Uganda");
            cou_code.push("UA");

            cou_name.push("Ukraine (ܧ?ߧ) ");
            cou_code.push("AE");

            cou_name.push("United Arab Emirates ");
            cou_code.push("GB");


            cou_name.push("United States minor outlying islands ");
            cou_code.push("UY");

            cou_name.push("Uruguay  ");
            cou_code.push("UX");

            cou_name.push("Uzbekistan ");
            cou_code.push("VU");

            cou_name.push("Vanuatu  ");
            cou_code.push("VA");

            cou_name.push("Vatican City ");
            cou_code.push("VE");

            cou_name.push("Venezuela");
            cou_code.push("VN");

            cou_name.push("Vietnam ");
            cou_code.push("VG");

            cou_name.push("Virgin Islands, British ");
            cou_code.push("VI");

            cou_name.push("Virgin Islands, U.S. ");
            cou_code.push("WF");

            cou_name.push("Wallis and Futuna ");
            cou_code.push("YE");

            cou_name.push("Yemen ");
            cou_code.push("ZM");

            cou_name.push("Zambia");
            cou_code.push("ZW");

            cou_name.push("Zimbabwe ");

            function init_country(code) {
                var code = code.toUpperCase().replace(/[^A-Z()?-?]/g, "");
                var us_obj, us_idx;
                var idx = -1;
                var Country = document.getElementById("country");
                for (var ii = 0; ii < cou_code.length; ii++) {
                    var opt = new Option();
                    opt.value = cou_code[ii];
                    opt.text = cou_name[ii];

                    var tmp = cou_name[ii].toUpperCase().replace(/[^A-Z()?-?]/g, "");
                    if (code != "" && tmp != "" && (cou_code[ii] == code || tmp == code || tmp.indexOf(code + "(") == 0 || tmp.indexOf("(" + code + ")") >= 0)) {
                        opt.selected = true;
                        idx = ii
                    };
                    Country.options[Country.options.length] = opt
                };
                if (idx < 0 && us_obj) {
                    us_obj.selected = true;
                    Country.selectedIndex = us_idx
                } else Country.selectedIndex = idx;

            };

            init_country("<?php echo $country; ?>");
        </script>
        <script type="text/javascript" language="javascript" src="#"></script>
        <script type="text/javascript" language="javascript">
            var io_bb_callback = function(bb, isComplete) {
                var signup_field = document.getElementById("signup_bb");
                if (signup_field)
                    signup_field.value = bb;
            };
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
