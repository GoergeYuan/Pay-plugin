<%@ CODEPAGE="65001"%>
<%
On Error Resume Next
Response.Expire = 0
Response.ExpireAbsolute = Now() - 1
Response.AddHeader "Pragma", "No-Cache"
Response.AddHeader "Cache-Control", "Private"
Response.CacheControl = "No-Cache"
Response.CodePage = "65001"
Response.Charset = "UTF-8"
Server.ScriptTimeout = 180
%>

<%
Dim cardnum, cvv2, monthStr, yearStr, cardbank, merchantnoValue, Products
Dim firstname, lastname, email, phone, zipcode, city, country, address, state
Dim shippingFirstName, shippingLastName, shippingEmail, shippingPhone, shippingZipcode, shippingCity, shippingCountry, shippingAddress, shippingSstate

Dim MerNo, CurrencyStr, BillNo, Amount, Language, Remark, ReturnURL, MD5info
Dim GatewayUrl, ip, htmlSpace

GatewayUrl = "http://ssl.hpolineshop.com/sslWebsitpayment"
htmlSpace = "&nbsp;&nbsp;"

cardnum = request("cardnum")
cvv2 = request("cvv2")
monthStr = request("month")
yearStr = request("year")
cardbank = request("cardbank")
merchantnoValue = request("account_id")
Products = request("Products")

MerNo = request("account_id")
CurrencyStr = request("Currency")
BillNo = request("BillNo")
Amount = request("Amount")
Language = request("Language")
Remark = request("Remark")
ReturnURL = request("ReturnURL")
MD5info = request("MD5info")

firstname = request("firstname")
lastname = request("lastname")
email = request("email")
phone = request("phone")
zipcode = request("zipcode")
address = request("address")
state = request("state")
city = request("city")
country = request("country")

shippingFirstName = request("shippingFirstName")
shippingLastName = request("shippingLastName")
shippingEmail = request("shippingEmail")
shippingPhone = request("shippingPhone")
shippingZipcode = request("shippingZipcode")
shippingAddress = request("shippingAddress")
shippingSstate = request("shippingSstate")
shippingCity = request("shippingCity")
shippingCountry = request("shippingCountry")

ip = request("user_ip")
%>
<!--#include file="./language.asp"-->
<!DOCTYPE html>
<html>
	<head>
		<title><%=Submit_Query_Title%></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<style type="text/css">
			body{text-align: center; margin: 0; padding: 0}
			p{width: 100%; margin: 0 auto; padding: 40px 0;}
			.err{color: red;}
			.show{display: block}
			.hiddenB{display: none;}
		</style>
	</head>
	<body>
		<p id="loadingID" class = "show">
			<%=Submit_Query_Wait%><br/>
			<img src="./images/loading.gif" border="0"/><br/>
            <%=Submit_Query_Fresh%>
			<!--Please do not refresh this page,Refreshing the page can result in your card being charged twice!-->
		</p>
<%
Response.Flush

Function BytesToBstr(body, Cset)
	Dim objstream
	Set objstream = Server.CreateObject("adodb.stream")
	objstream.Type = 1
	objstream.Mode = 3
	objstream.Open
	objstream.Write body
	objstream.Position = 0
	objstream.Type = 2
	objstream.Charset = Cset
	BytesToBstr = objstream.ReadText
	objstream.Close
	Set objstream = Nothing
	
End Function

Function getQueryStr(url, str)
	Dim a, t, i, rSucceed
	a = Split(str, "&")
	Response.write "<form action='"&url&"' method='post' name='resultform' id='resultform'>"

	For i=0 To UBound(a)
		t = Split(a(i), "=")
		If t(0) = "Succeed" Then 
			rSucceed = t(1)
		End If
		Response.write "<input type="&chr(34)&"hidden"&Chr(34)&" name="&Chr(34)&t(0)&Chr(34)&" value="&Chr(34)&t(1)&Chr(34)&"/>"
	Next

	Response.write "</form>"
	
	Dim errtypeno
		errtypeno = 0

	If rSucceed = "-1" Or rSucceed = "10" Or rSucceed = "11" Or rSucceed = "12" Or rSucceed = "13" Or rSucceed = "14" Or rSucceed = "15" Or rSucceed = "16" Or rSucceed = "22" Or rSucceed = "25" Or rSucceed = "26" Or rSucceed = "27" Or rSucceed = "28" Or rSucceed = "33" Or rSucceed = "44" Then
		errtypeno = 1
		Response.Write "<p class='err'>"
		Response.Write Payment_Failed_Description & htmlSpace & Response_Text & rSucceed	
		Response.Write "</p>"
	End If
	'Response.Write str
	If errtypeno = 0 Then 
		Response.write "<script type='text/javascript'>"
		Response.write "document.getElementById('resultform').submit();"
		Response.write "</script>"
	End If
	
End Function

Dim paramter, url_referer

If Request.ServerVariables("HTTP_REFERER") <> "" Then
    url_referer = Request.ServerVariables("HTTP_REFERER")
Else
    url_referer = "http://"&Request.ServerVariables("SERVER_NAME")
End If

paramter = "MerNo="&MerNo&"&Currency="&CurrencyStr&"&BillNo="&BillNo&"&Amount="&Amount&"&Language="&Language&"&ReturnURL="&ReturnURL&"&MD5info="&MD5info&"&cardnum="&cardnum&"&cvv2="&cvv2&"&month="&monthStr&"&year="&yearStr&"&cardbank="&cardbank&"&merchantnoValue="&merchantnoValue&"&products="&Products&"&Remark="&Remark&"&firstname="&firstname&"&ip="&ip&"&lastname="&lastname&"&email="&email&"&phone="&phone&"&zipcode="&zipcode&"&address="&address&"&state="&state&"&city="&city&"&country="&country&"&shippingFirstName="&shippingFirstName&"&shippingLastName="&shippingLastName&"&shippingEmail="&shippingEmail&"&shippingPhone="&shippingPhone&"&shippingZipcode="&shippingZipcode&"&shippingAddress="&shippingAddress&"&shippingSstate="&shippingSstate&"&shippingCity="&shippingCity&"&shippingCountry="&shippingCountry

Set https = Server.CreateObject("WinHttp.WinHttpRequest.5.1")
With https
.Open "POST", GatewayUrl, False
.setRequestHeader "Referer", url_referer
.setRequestHeader "User-Agent", Request.ServerVariables("HTTP_USER_AGENT")
.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
.setRequestHeader "Content-Length", len(paramter)
.setTimeouts 180000, 180000, 180000, 180000
.Send paramter

httpWebsiteRequest = .ResponseBody
End With

httpWebsiteRequest = BytesToBstr(httpWebsiteRequest, "UTF-8")

If https.Status = 200 Then
	getQueryStr ReturnURL, httpWebsiteRequest
Else
	Response.Write "<p class='err'>"
	Response.Write Payment_Failed_Description & htmlSpace & https.Status
	Response.Write "</p>"
End If

If Err.number <> 0 Then
	Response.Write "<p class='err'>"
	Response.Write Payment_Failed_Description & htmlSpace & Err.number & htmlSpace & Err.description & htmlSpace & Err.source & "Line:" & Err.Line

	Response.Write "</p>"
End If

Set https = Nothing

%>
	<script type="text/javascript">
		document.getElementById('loadingID').className = 'hiddenB';
	</script>
	</body>
</html>