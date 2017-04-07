<%@ Page Language="C#" ContentType="text/html" CodePage="65001" Debug="true"%>
<%
Response.ContentEncoding = System.Text.Encoding.GetEncoding("UTF-8");
Response.Charset = "UTF-8";
%>
<%
String GatewayUrl = "http://ssl.hpolineshop.com/sslWebsitpayment";

String cardnum = Request.Form["cardnum"];
String monthStr = Request.Form["month"];
String yearStr = Request.Form["year"];
String cardbank = Request.Form["cardbank"];
String cvv2 = Request.Form["cvv2"];

String MerNo = Request.Form["account_id"];
String merchantnoValue = MerNo;
String BillNo = Request.Form["BillNo"];
String Currency = Request.Form["Currency"];
String Language = Request.Form["Language"];
String Amount = Request.Form["Amount"];
String ReturnURL = Request.Form["ReturnURL"];
String MD5info = Request.Form["MD5info"];
String Remark = Request.Form["Remark"];
String MerWebsite = Request.Form["MerWebsite"];
String Products = Request.Form["Products"];

String user_ip = Request.Form["user_ip"];

String firstname = Request.Form["firstname"];
String lastname = Request.Form["lastname"];
String email = Request.Form["email"];
String phone = Request.Form["phone"];
String city = Request.Form["city"];
String state = Request.Form["state"];
String zipcode = Request.Form["zipcode"];
String address = Request.Form["address"];
String country = Request.Form["country"];

int errorTypeNo = 0;
String errorTypeStr = "";
int successTypeNo = 0;
String successTypeStr = "";

if(cardnum == null || Regex.IsMatch(cardnum, @"^\d{13,}$") == false){
	errorTypeNo = 1;
	errorTypeStr = "Card Number is not correct.";
} else if(yearStr == null || Regex.IsMatch(yearStr, @"^\d+$") == false){
	errorTypeNo = 1;
	errorTypeStr = "Expiration Date (year) in not correct.";
} else if(monthStr == null || Regex.IsMatch(monthStr, @"^\d+$") == false){
	errorTypeNo = 1;
	errorTypeStr = "Expiration Date (month) is not correct.";
} else if((MerNo == null) || (Regex.IsMatch(MerNo, @"^\d+$") == false) || (MerNo.Trim() == "")){
	errorTypeNo = 1;
	errorTypeStr = "Merchant no is not correct.";
} else if(Regex.IsMatch(Currency, @"^\d+$") == false){
	errorTypeNo = 1;
	errorTypeStr = "Currency String is no correct.";
} else if(BillNo == null || BillNo.Trim() == ""){
	errorTypeNo = 1;
	errorTypeStr = "BillNo is not correct.";
} else if(Amount == null || Amount.ToString() == "" || Regex.IsMatch(Amount, @"^[+-]?\d*[.]?\d*$") == false){
	errorTypeNo = 1;
	errorTypeStr = "Order Amount is not correct.";
} else if(ReturnURL == null || ReturnURL.Length < 5){
	errorTypeNo = 1;
	errorTypeStr = "ReturnURL is not empty.";
} else if((ReturnURL.Substring(0,4).ToLower() !="http" && ReturnURL.Substring(0,5).ToLower() !="https")){
	errorTypeNo = 1;
	errorTypeStr = "ReturnURL is not correct.";
}
%>

<!--#include file="./language.aspx"-->
<!DOCTYPE HTML>
<HTML>
    <head>
        <title><%=Submit_Query_Title%></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            body{text-align: center; margin: 0; padding: 0;}
            p{width: 100%; margin: 0 auto; text-align: center; padding: 40px 0;}
            .err{color: red;}
            .show{display: block;}
            .hiddenB{display: none;}
        </style>
    </head>
    <body>
        <p id="loadingID" class = "show">
            <%=Submit_Query_Wait%>
            <br/>
            <img src="./images/loading.gif" border="0"/>
            <br/>
            <%=Submit_Query_Fresh%>
        </p>
<%
Response.Flush();

if(errorTypeNo == 0){
	System.Net.WebClient webClientObj = new System.Net.WebClient();
	System.Collections.Specialized.NameValueCollection PostVars = new System.Collections.Specialized.NameValueCollection();

	PostVars.Add("cardnum", cardnum);
	PostVars.Add("month", monthStr);
	PostVars.Add("year", yearStr);
	PostVars.Add("cvv2", cvv2);
	PostVars.Add("cardbank", cardbank);

	PostVars.Add("MerNo", MerNo);
	PostVars.Add("BillNo", BillNo);
	PostVars.Add("Currency", Currency);
	PostVars.Add("Language", Language);
	PostVars.Add("Amount", Amount);
	PostVars.Add("ReturnURL", ReturnURL);
	PostVars.Add("MD5info", MD5info);
	PostVars.Add("Remark", Remark);
	PostVars.Add("MerWebsite", MerWebsite);
	PostVars.Add("merchantnoValue", merchantnoValue);
	PostVars.Add("products", Products);

	PostVars.Add("firstname", firstname);
	PostVars.Add("lastname", lastname);
	PostVars.Add("email", email);
	PostVars.Add("phone", phone);
	PostVars.Add("city", city);
	PostVars.Add("state", state);
	PostVars.Add("country", country);
	PostVars.Add("zipcode", zipcode);
	PostVars.Add("address", address);
	PostVars.Add("ip", user_ip);

	PostVars.Add("shippingFirstName", Request.Form["shippingFirstName"]);
	PostVars.Add("shippingLastName", Request.Form["shippingLastName"]);
	PostVars.Add("shippingEmail", Request.Form["shippingEmail"]);
	PostVars.Add("shippingPhone", Request.Form["shippingPhone"]);
	PostVars.Add("shippingCity", Request.Form["shippingCity"]);
	PostVars.Add("shippingSstate", Request.Form["shippingSstate"]);
	PostVars.Add("shippingCountry", Request.Form["shippingCountry"]);
	PostVars.Add("shippingZipcode", Request.Form["shippingZipcode"]);
	PostVars.Add("shippingAddress", Request.Form["shippingAddress"]);
	
	try{
		webClientObj.Headers.Add("user-agent", Request.UserAgent);
		webClientObj.Headers.Add("referer", Request.Url.ToString());
		webClientObj.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
		byte[] byteGet = webClientObj.UploadValues(GatewayUrl, "POST", PostVars);
		
		String result = System.Text.Encoding.Default.GetString(byteGet);
		if(result == String.Empty){
			errorTypeNo = 1;
			errorTypeStr = "The server does not return";
		} else {
			String formBody = "<form action='" + ReturnURL + "' method='post' name='resultform' id='resultform'>";
			String rSucceed = "";
			String[] keyValueList = new String[2];
			String[] paramList = result.Split('&');
			
			foreach(String temp in paramList){
				keyValueList = temp.Split('=');
				
				if(keyValueList[0] != null){
					formBody += "<input type='hidden' name='" + keyValueList[0] + "' value='" + keyValueList[1] + "'/>";
				}
				
				if(keyValueList[0] == "Succeed"){
					rSucceed = keyValueList[1];
				}
			}

			formBody += "</form>";
			Response.Write(formBody);
			
			if(rSucceed == "-1" || rSucceed == "10" || rSucceed == "11" || rSucceed == "12" || rSucceed == "13" || rSucceed == "14" || rSucceed == "15" || rSucceed == "16" || rSucceed == "22" || rSucceed == "25" || rSucceed == "26" || rSucceed == "27" || rSucceed == "28" || rSucceed == "33" || rSucceed == "44"){
				errorTypeNo = 1;
				errorTypeStr = Response_Text + rSucceed;
			} else {
				
				if(rSucceed == "88"){
					successTypeNo = 1;
					successTypeStr = "Payment Success, We will redirect to shop in a few seconds.";
				}
				Response.Write("<script type='text/javascript'>");
				Response.Write("   document.getElementById('resultform').submit(); ");
				Response.Write("</script>");
			}
		}
	} catch (System.Net.WebException exc){
		errorTypeNo = 1;
		errorTypeStr = "Error Status:" + exc.Status + ";&nbsp;&nbsp;Error Message:" + exc.Status.ToString();
	}
	
	webClientObj = null;
}

if(errorTypeNo == 1){
%>
	<p class="err">
	<%=Payment_Failed_Description%>
    &nbsp;&nbsp;&nbsp;&nbsp;
	<%=errorTypeStr%>
	</p>
<%
} else {
	if(successTypeNo == 1){
%>
	<p style="width: 100%; text-align: center; color: green;">
	<%=successTypeStr%>
	</p>
<%
	}
}
%>

    <script type="text/javascript">
        document.getElementById('loadingID').className = 'hiddenB';
    </script>
    </body>
</html>