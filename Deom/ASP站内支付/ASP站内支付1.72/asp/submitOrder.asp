<%@ CODEPAGE=65001%>

<%
Session.CodePage = "65001"
Response.CodePage = "65001"
Response.Charset = "UTF-8"
%>

<%
Dim MerNo, BillNo, Amount, CurrencyStr, ReturnURL, MD5info, CurrencyName, Products, Remark
Dim shippingFirstName, shippingLastName, shippingEmail, shippingPhone, shippingZipcode, shippingSstate, shippingCountry, shippingCity, shippingAddress
Dim firstname, lastname, email, phone, zipcode, state, country, city, address

MerNo = request("MerNo")
CurrencyStr = request("Currency")
BillNo = request("BillNo")
Amount = request("Amount")
ReturnURL = request("ReturnURL")
MD5info = request("MD5info")
Language = request("Language")
Products = request("Products")
Remark = request("Remark")

shippingFirstName = request("DeliveryFirstName")
shippingLastName = request("DeliveryLastName")
shippingEmail = request("DeliveryEmail")
shippingPhone = request("DeliveryPhone")
shippingZipcode = request("DeliveryZipCode")
shippingSstate = request("DeliveryState")
shippingCountry = request("DeliveryCountry")
shippingCity = request("DeliveryCity")
shippingAddress = request("DeliveryAddress")

If Len(request("firstname")) <> 0 Then
	firstname = request("firstname")
Else 
	firstname = shippingFirstName
End If

If Len(request("lastname")) <> 0 Then
	lastname = request("lastname")
Else
	lastname = shippingLastName
End If

If Len(request("email")) <> 0 Then
	email = request("email")
Else 
	email = shippingEmail
End If

If Len(request("phone")) <> 0 Then 
	phone = request("phone")
Else
	phone = shippingPhone
End If

If Len(request("zipcode")) <> 0 Then
	zipcode = request("zipcode")
Else
	zipcode = shippingZipcode
End If

If Len(request("state")) <> 0 Then
	state = request("state")
Else
	state = shippingSstate
End If

If Len(request("country")) <> 0 Then
	country = request("country")
Else
	country = shippingCountry
End If

If Len(request("city")) <> 0 Then
	city = request("city")
Else
	city = shippingCity
End If

If Len(request("address")) <> 0 Then
	address = request("address")
Else
	address = shippingAddress
End If


If CurrencyStr = "1" Then
    CurrencyName = "USD"
ElseIf CurrencyStr = "2" Then
    CurrencyName = "EUR"
ElseIf CurrencyStr = "6" Then 
    CurrencyName = "JPY"
ElseIf CurrencyStr = "4" Then
    CurrencyName = "GBP"
ElseIf CurrencyStr = "7" Then
    CurrencyName = "AUD"
ElseIf CurrencyStr = "11" Then
    CurrencyName = "CAD"
ElseIf CurrencyStr = "8" Then
    CurrencyName = "NOK"
ElseIf CurrencyStr = "12" Then
    CurrencyName = "DKK"
ElseIf CurrencyStr = "13" Then
    CurrencyName = "HKD"
End If

'get language by broswer
Function RegExpTest(patrn, strng)
Dim regEx, Match, Matches
Set regEx = New RegExp
regEx.Pattern = patrn
regEx.IgnoreCase = True
regEx.Global = True

Set Matches = regEx.Execute(strng)

For Each Match In Matches
RetStr = RetStr&Match.value
Next
RegExpTest = RetStr
End Function

Dim userLang, AL, LG, user_ip, server_ip
userLang = "en-us"
AL = Request.ServerVariables("HTTP_ACCEPT_LANGUAGE")
LG = Lcase(RegExpTest("^[A-Z-]+", AL))

user_ip = Request.ServerVariables("HTTP_X_FORWARDED_FOR")
If ip = "" Then
	user_ip = Request.ServerVariables("REMOTE_ADDR")
End If
server_ip = Request.ServerVariables("LOCAL_ADDR")

%>
<!--#include file="./vm_Checkout/language.asp"-->
<!--#include file="./vm_Checkout/view.asp"-->