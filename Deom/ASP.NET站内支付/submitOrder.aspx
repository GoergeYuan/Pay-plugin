<%@ Page Language="C#" ContentType="text/html"  ResponseEncoding="UTF-8"%>
<%
Response.ContentEncoding = System.Text.Encoding.GetEncoding("UTF-8");
Response.Charset = "UTF-8";

%>
<%
	String MerNo, BillNo, Currency, Amount, Language, ReturnURL, MD5info, CurrencyName, CurrencyStr, Remark;
	int errorTypeNo = 0;
	
	MerNo = BillNo = Currency = Amount = ReturnURL = MD5info = Language = CurrencyStr = CurrencyName = Remark =  "";

	try{
		MerNo = Request.Params["MerNo"].ToString();
		BillNo = Request.Params["BillNo"].ToString();
		Amount = Request.Params["Amount"].ToString();
		Currency = Request.Params["Currency"].ToString();
		ReturnURL = Request.Params["ReturnURL"].ToString();
		MD5info = Request.Params["MD5info"].ToString();
	} catch (Exception e) {
		errorTypeNo = 1;
	}
	
	if(Request.Params["Language"] != null){
		Language = Request.Params["Language"].ToString();
	}
	
	String shippingFirstName = Request.Params["DeliveryFirstName"];
	String shippingLastName = Request.Params["DeliveryLastName"];
	String shippingEmail = Request.Params["DeliveryEmail"];
	String shippingPhone = Request.Params["DeliveryPhone"];
	String shippingAddress = Request.Params["DeliveryAddress"];
	String shippingZipcode = Request.Params["DeliveryZipCode"];
	String shippingCity = Request.Params["DeliveryCity"];
	String shippingSstate = Request.Params["DeliveryState"];
	String shippingCountry = Request.Params["DeliveryCountry"];
	
	String Products = Request.Params["Products"];
	
	String firstname = shippingFirstName;
	if(Request.Params["firstname"] != null){
		firstname = Request.Params["firstname"].ToString();
	}
	
	String lastname = shippingLastName;
	if(Request.Params["lastname"] != null){
		lastname = Request.Params["lastname"].ToString();
	}
	
	String email = shippingEmail;
	if(Request.Params["email"] != null){
		email = Request.Params["email"].ToString();
	}
	
	String phone = shippingPhone;
	if(Request.Params["phone"] != null){
		phone = Request.Params["phone"].ToString();
	}
	
	String address = shippingAddress;
	if(Request.Params["address"] != null){
		address = Request.Params["address"].ToString();
	}
	
	String zipcode = shippingZipcode;
	if(Request.Params["zipcode"] != null){
		zipcode = Request.Params["zipcode"].ToString();
	}
	
	String city = shippingCity;
	if(Request.Params["city"] != null){
		city = Request.Params["city"].ToString();
	}
	
	String state = shippingSstate;
	if(Request.Params["state"] != null){
		state = Request.Params["state"].ToString();
	}
	
	String country = shippingCountry;
	if(Request.Params["country"] != null){
		country = Request.Params["country"].ToString();
	}
	
    if(Currency == "1"){
		CurrencyName = "USD";
	}else if(Currency == "2"){
		CurrencyName = "EUR";
	}else if(Currency == "4"){
		CurrencyName = "GBP";
	}else if(Currency == "6"){
		CurrencyName = "JPY";
	}else if(Currency == "7"){
		CurrencyName = "AUD";
	}else if(Currency == "8"){
		CurrencyName = "NOK";
	}else if(Currency == "11"){
		CurrencyName = "CAD";
	}else if(Currency == "12"){
		CurrencyName = "DKK";
	}else if(Currency == "13"){
		CurrencyName = "SEK";
	}else if(Currency == "5"){
		CurrencyName = "HKD";
	}else{
		CurrencyName ="";
	}

	String user_ip, server_ip;
	
	if(Context.Request.ServerVariables["HTTP_VIA"] != null){
		user_ip = Context.Request.ServerVariables["HTTP_X_FORWARDED_FOR"].ToString();
	} else {
		user_ip = Context.Request.ServerVariables["REMOTE_ADDR"].ToString();
	}
	
	server_ip = Context.Request.ServerVariables["LOCAL_ADDR"].ToString();
	
	if(errorTypeNo == 1){
%>
		<P style="color: red; width: 100%; margin: 0 auto; text-align: center;">Request Error, Params must be not empty!</P>
<%
	} else {
%>
		<!--#include file="./vm_Checkout/language.aspx"-->
		<!--#include file="./vm_Checkout/view.aspx"-->
<%	
	}
%>
