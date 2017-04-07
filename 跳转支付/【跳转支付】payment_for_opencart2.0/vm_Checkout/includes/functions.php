<?php
function getLanguage(){
	
    if(isset($_COOKIE['_set_language_fashionpay_']) && $_COOKIE['_set_language_fashionpay_']){
        $langSet = $_COOKIE['_set_language_fashionpay_'];
    } else if($_SERVER['HTTP_ACCEPT_LANGUAGE']){
        preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $langSet = trim($matches[1]);
        setcookie('_set_language_fashionpay_', $langSet, time() + 3600);
    } else {
        $langSet = DEFAULT_PAY_LANG;
    }
    return strtolower($langSet);
}

function varGet($data, $key, $defaultValue = NULL){
	return (isset($data[$key])) ? $data[$key] : $defaultValue;
}

function getYearList($label = ''){
	$start = date('Y');
	$ylist = array();

	if($label){
		$ylist[''] = $label;
	}

	for($i=0; $i<20; $i++){
		$val = $start + $i;
		$ylist[$val] = $val;
	}

	return $ylist;
}

function getMonthList($label = ''){
	$mlist = array();

	if($label){
		$mlist[''] = $label;
	}

	for($i=1; $i<=12; $i++){
		$val = strval($i);
		$val = str_pad($val, 2, '0', STR_PAD_LEFT);
		$mlist[$val] = $val;
	}

	return $mlist;
}

function getCountryList($label = 'Select Country'){
	return array(
			'' => $label,
			'United States' => 'United States',
			'United Kingdom' => 'United Kingdom',
			'Australia' => 'Australia',
			'France' => 'France',
			'Germany' => 'Germany',
			'Canada' => 'Canada',
			'Japan' => 'Japan',
			'Afghanistan' => 'Afghanistan',
			'Albania' => 'Albania',
			'Algeria' => 'Algeria',
			'Andorra' => 'Andorra',
			'Angola' => 'Angola',
			'Antigua and Barbuda' => 'Antigua and Barbuda',
			'Argentina' => 'Argentina',
			'Armenia' => 'Armenia',
			'Aruba' => 'Aruba',
			'Austria' => 'Austria',
			'Azerbaijan' => 'Azerbaijan',
			'Bahamas' => 'Bahamas',
			'Bahrain' => 'Bahrain',
			'Bangladesh' => 'Bangladesh',
			'Barbados' => 'Barbados',
			'Belgium' => 'Belgium',
			'Belize' => 'Belize',
			'Benin' => 'Benin',
			'Bermuda' => 'Bermuda',
			'Bhutan' => 'Bhutan',
			'Bolivia' => 'Bolivia',
			'Bosnia Herzegovina' => 'Bosnia Herzegovina',
			'Botswana' => 'Botswana',
			'Brazil' => 'Brazil',
			'Brunei' => 'Brunei',
			'Bulgaria' => 'Bulgaria',
			'Burkina Faso' => 'Burkina Faso',
			'Burundi' => 'Burundi',
			'Cambodia' => 'Cambodia',
			'Cameroon' => 'Cameroon',
			'Cape Verde' => 'Cape Verde',
			'Cayman Islands' => 'Cayman Islands',
			'Central African Republic' => 'Central African Republic',
			'Chad' => 'Chad',
			'China' => 'China',
			'Chile' => 'Chile',
			'Colombia' => 'Colombia',
			'Comoros' => 'Comoros',
			'Congo' => 'Congo',
			'Costa Rica' => 'Costa Rica',
			'Croatia' => 'Croatia',
			'Cyprus' => 'Cyprus',
			'Czech Republic' => 'Czech Republic',
			'Denmark' => 'Denmark',
			'Djibouti' => 'Djibouti',
			'Dominica' => 'Dominica',
			'Dominican Republic' => 'Dominican Republic',
			'Ecuador' => 'Ecuador',
			'Egypt' => 'Egypt',
			'El Salvador' => 'El Salvador',
			'Equatorial Guinea' => 'Equatorial Guinea',
			'Eritrea' => 'Eritrea',
			'Estonia' => 'Estonia',
			'Ethiopia' => 'Ethiopia',
			'Fiji' => 'Fiji',
			'Finland' => 'Finland',
			'French Guiana' => 'French Guiana',
			'Gabon' => 'Gabon',
			'Gambia' => 'Gambia',
			'Georgia' => 'Georgia',
			'Ghana' => 'Ghana',
			'Gibraltar' => 'Gibraltar',
			'Greece' => 'Greece',
			'Grenada' => 'Grenada',
			'Guadeloupe' => 'Guadeloupe',
			'Guatemala' => 'Guatemala',
			'Guinea' => 'Guinea',
			'Guinea-Bissau' => 'Guinea-Bissau',
			'Guyana' => 'Guyana',
			'Haiti' => 'Haiti',
			'Honduras' => 'Honduras',
			'Hong Kong' => 'Hong Kong',
			'Hungary' => 'Hungary',
			'Iceland' => 'Iceland',
			'India' => 'India',
			'Indonesia' => 'Indonesia',
			'Ireland' => 'Ireland',
			'Israel' => 'Israel',
			'Italy' => 'Italy',
			'Jamaica' => 'Jamaica',
			'Jersey' => 'Jersey',
			'Jordan' => 'Jordan',
			'Kazakhstan' => 'Kazakhstan',
			'Kenya' => 'Kenya',
			'Kuwait' => 'Kuwait',
			'Kyrgyzstan' => 'Kyrgyzstan',
			'Laos' => 'Laos',
			'Latvia' => 'Latvia',
			'Lebanon' => 'Lebanon',
			'Lesotho' => 'Lesotho',
			'Libya' => 'Libya',
			'Liechtenstein' => 'Liechtenstein',
			'Lithuania' => 'Lithuania',
			'Luxembourg' => 'Luxembourg',
			'Macau' => 'Macau',
			'Macedonia' => 'Macedonia',
			'Madagascar' => 'Madagascar',
			'Malawi' => 'Malawi',
			'Malaysia' => 'Malaysia',
			'Maldives' => 'Maldives',
			'Mali' => 'Mali',
			'Malta' => 'Malta',
			'Martinique' => 'Martinique',
			'Mauritania' => 'Mauritania',
			'Mauritius' => 'Mauritius',
			'Mexico' => 'Mexico',
			'Moldova' => 'Moldova',
			'Monaco' => 'Monaco',
			'Mongolia' => 'Mongolia',
			'Morocco' => 'Morocco',
			'Mozambique' => 'Mozambique',
			'Namibia' => 'Namibia',
			'Nepal' => 'Nepal',
			'Netherlands' => 'Netherlands',
			'Netherlands Antilles' => 'Netherlands Antilles',
			'New Zealand' => 'New Zealand',
			'Nicaragua' => 'Nicaragua',
			'Niger' => 'Niger',
			'Nigeria' => 'Nigeria',
			'Norway' => 'Norway',
			'Oman' => 'Oman',
			'Pakistan' => 'Pakistan',
			'Panama' => 'Panama',
			'Papua New Guinea' => 'Papua New Guinea',
			'Paraguay' => 'Paraguay',
			'Peru' => 'Peru',
			'Philippines' => 'Philippines',
			'Poland' => 'Poland',
			'Portugal' => 'Portugal',
			'Qatar' => 'Qatar',
			'Romania' => 'Romania',
			'Russia' => 'Russia',
			'Rwanda' => 'Rwanda',
			'San Marino' => 'San Marino',
			'Sao Tome &amp;amp; Principe' => 'Sao Tome &amp;amp; Principe',
			'Saudi Arabia' => 'Saudi Arabia',
			'Senegal' => 'Senegal',
			'Serbia &amp;amp; Montenegro' => 'Serbia &amp;amp; Montenegro',
			'Seychelles' => 'Seychelles',
			'Sierra Leone' => 'Sierra Leone',
			'Singapore' => 'Singapore',
			'Slovakia' => 'Slovakia',
			'Slovenia' => 'Slovenia',
			'Somalia' => 'Somalia',
			'South Africa' => 'South Africa',
			'South Korea' => 'South Korea',
			'Spain' => 'Spain',
			'Sri Lanka' => 'Sri Lanka',
			'St. Kitts &amp;amp; Nevis' => 'St. Kitts &amp;amp; Nevis',
			'St. Lucia' => 'St. Lucia',
			'St. Vincent &amp;amp; the Grenadines' => 'St. Vincent &amp;amp; the Grenadines',
			'Suriname' => 'Suriname',
			'Swaziland' => 'Swaziland',
			'Sweden' => 'Sweden',
			'Switzerland' => 'Switzerland',
			'Syria' => 'Syria',
			'Taiwan' => 'Taiwan',
			'Tajikistan' => 'Tajikistan',
			'Tanzania' => 'Tanzania',
			'Thailand' => 'Thailand',
			'Togo' => 'Togo',
			'Trinidad and Tobago' => 'Trinidad and Tobago',
			'Tunisia' => 'Tunisia',
			'Turkey' => 'Turkey',
			'Turkmenistan' => 'Turkmenistan',
			'Turks and Caicos Islands' => 'Turks and Caicos Islands',
			'Uganda' => 'Uganda',
			'Ukraine' => 'Ukraine',
			'United Arab Emirates' => 'United Arab Emirates',
			'Uruguay' => 'Uruguay',
			'Uzbekistan' => 'Uzbekistan',
			'Venezuela' => 'Venezuela',
			'Vietnam' => 'Vietnam',
			'Western Sahara' => 'Western Sahara',
			'Yemen' => 'Yemen',
			'Zambia' => 'Zambia'
	);
}

function dropDownList($name, $data, $selected = ''){
	$head =<<<EOT
    <select name="{$name}" id="{$name}">
EOT;
	$bodyTemplate = '<option value="%s"%s>%s</option>';
	$body = '';
	
/* 	$countryCodeList = array();
	$cacheFile = getResource('data/country.json');
	
	if(file_exists($cacheFile)){
		$content = file_get_contents($cacheFile);
		
		if($content){
			$countryCodeList = json_decode($content, TRUE);
		}
		
	} else {
		 
		foreach($data as $val=> $label){
			$str = ucwords($val);
			$k = preg_replace('/[^A-Z]/', '', $str);
			$countryCodeList[$k] = $val;
		}
		file_put_contents($cacheFile, json_encode($countryCodeList));
	} */
	
	$selected = strtoupper($selected);
	
	foreach($data as $val=> $label){
		$selectStr = '';
	
		$temp = strtoupper($val);
		if($temp == $selected){
			$selectStr = ' selected="selected"';
		}
// 		 else {
// 			if(strlen($selected) < 3 && isset($countryCodeList[$selected])){
// 				$selectStr = ' selected="selected"';
// 			}
// 		}

		$body .= sprintf($bodyTemplate, $val, $selectStr, $label);
	}

	$foot = '</select>';

	return $head . $body . $foot;
}

function get_client_ip() {
	static $ip = NULL;
	if ($ip !== NULL) return $ip;
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos =  array_search('unknown',$arr);
		if(false !== $pos) unset($arr[$pos]);
		$ip   =  trim($arr[0]);
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	$ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
	return $ip;
}

function get_server_ip(){
	static $server_ip = NULL;

	if($server_ip !== NULL){
		return $server_ip;
	}

	if(varGet($_SERVER, 'SERVER_ADDR')){
		$server_ip = varGet($_SERVER, 'SERVER_ADDR');
	} else if(function_exists('getenv') && getenv('SERVER_NAME')){
		$server_ip = getenv('SERVER_ADDR');
	} else if(varGet($_SERVER, 'SERVER_NAME')){
		$server_ip = gethostbyname(varGet($_SERVER, 'SERVER_NAME'));
	} else if(varGet($_SERVER, 'HTTP_HOST')){
		$host = preg_replace('/(\:{1}\d+)$/', '', varGet($_SERVER, 'HTTP_HOST'));
		$server_ip = gethostbyname($host);
	} else {
		$server_ip = '';
	}

	return $server_ip;
}

function autoCharset($str, $from = 'GBK', $to = 'UTF-8'){
	if(strtoupper($from) === strtoupper($to) || empty($str) || (is_scalar($str) && !is_string($str))){
		return $str;
	}
	if (function_exists('mb_convert_encoding')) {
		$str =  mb_convert_encoding($str, $to, $from);
	} elseif (function_exists('iconv')) {
		$str = iconv($from, $to, $str);
	} else {
		$str = $str;
	}
	return $str;
}

function logResult($fileName, $content){
	if(!file_exists($fileName)){
		return false;
	}

	if(!is_writable($fileName)){
		return false;
	}

	$fp = @fopen($fileName, 'a');
	flock($fp, LOCK_EX);
	fwrite($fp, 'time:' . date('Y-m-d H:i:s') . "\t" . $content . "\r\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

function drawHTML($tag, $str, $options = array()){
	if(isset($options['closed']) && !$options['closed']){
		$closed = false;
		unset($options['closed']);
	} else {
		$closed = true;
	}

	$attribute = '';

	foreach($options as $key => $value){
		$attribute .= " {$key}=\"{$value}\"";
	}

	$html = "<{$tag}{$attribute}";

	if($closed == true){
		$html .= '>' . $str ."</{$tag}>";
	} else {
		$html .= "/>{$str}";
	}

	return $html;
}

function drawFormHTML($action, $data, $options){
	$html = '';
	$htmlFormValue = '';

	foreach($data as $field=> $value){
	$htmlFormValue .= drawHTML('input', '', array(
	'type' => 'hidden',
	'name' => $field,
			'value' => $value,
			'closed' => false
					)) . "\n";
	}

	$options['action'] = $action;

	$html .= drawHTML('form', $htmlFormValue, $options);
	return $html;
}

function javascriptFormSubmit($name, $time = 0){
$time = $time * 1000;

if($time == 0){
	$js =<<<STR
	<script type="text/javascript">
	setTimeout("document.getElementById('{$name}').submit();", {$time});
		</script>
STR;
	} else {
	$js =<<<STR
	<script type="text/javascript">
	document.getElementById("{$name}").submit();
	</script>
STR;
    }
    return $js;
}