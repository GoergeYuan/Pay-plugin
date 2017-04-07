<!--#include file="MD5pay.asp"-->

<%
	Dim BillNo, CurrencyStr, Amount, Succeed, Result, MD5info, MD5key, MD5src, MD5str, RorderNo, CurrencyName
	
	'商户号对应的MD5key
	MD5key 			= "12345678"
	'返回的商户网站的订单号
	BillNo			= request("BillNo")
	'订单的金额 
	Amount			= request("Amount")
	'返回的支付币种符号
	CurrencyStr		= request("Currency")
	'币种符号
	CurrencyName	= request("CurrencyName")
	'支付状态 
	Succeed			= request("Succeed")
	'支付结果 
	Result			= request("Result")
	'返回的加密串 
	MD5info			= request("MD5info")
	
	MD5src			= BillNo & CurrencyStr & Amount & Succeed & MD5key
	MD5str			= Ucase(md5(MD5src))
%>
<%
	Dim style
	if Succeed = "88" or Succeed = "19" Then
	'更改订单状态成功或是待处理   88为成功  19为待处理
	'发送订单支付成功邮件
	style			= "color:green;"
	Else
	'订单支付失败
	style			= "color:red;"
	Result			= Result & "&nbsp;&nbsp;&nbsp;&nbsp;Response Code:" & Succeed
	End if
%>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Payment Result</title>
		<style type="text/css">
			body{margin:0 auto;text-align:center;}
			table{margin:50 auto;}
			td{padding:5px 20px;}
		</style>
	</head>
	<body>
		<% if MD5info = MD5str Then  %>
			<table border="1">
				<tr>
					<td>Your Order Number:</td>
					<td><%=BillNo%></td>
				</tr>
				<tr>
					<td>Order Amount:</td>
					<td><%=Amount%>&nbsp;&nbsp;&nbsp;&nbsp;<%=CurrencyName%></td>
				</tr>
				<tr>
					<td>Payment Result:</td>
					<td style="<%=style%>"><%=Result%></td>
				</tr>
			</table>
		<% Else %>
			<table>
				<tr>
					<td style="text-align:center;color:red;"><% Response.Write "Validation failed!" %></td>
				</tr>
			</table>
		<% End if %>
	</body>
</html>