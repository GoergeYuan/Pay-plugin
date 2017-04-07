<%@ Page Language="C#" ContentType="text/html" ResponseEncoding="UTF-8" %>
<%@ Import Namespace="System.Security.Cryptography" %>

<%
	String MD5key	= "12345678";
	
	String BillNo		= System.Web.HttpContext.Current.Request.Params["BillNo"].ToString();
	String Currency		= System.Web.HttpContext.Current.Request.Params["Currency"].ToString();
	String CurrencyName	= System.Web.HttpContext.Current.Request.Params["CurrencyName"].ToString();
	String Amount		= System.Web.HttpContext.Current.Request.Params["Amount"].ToString();
	String Succeed		= System.Web.HttpContext.Current.Request.Params["Succeed"].ToString();
	String Result		= System.Web.HttpContext.Current.Request.Params["Result"].ToString();
	String MD5info		= System.Web.HttpContext.Current.Request.Params["MD5info"].ToString();
	String MD5src		= BillNo + Currency + Amount + Succeed + MD5key;
	String MD5str		= System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(MD5src, "MD5");
%>
<%
	String style;
	
	if(Succeed == "88" || Succeed == "19"){
		style 		= "color:green;";
	}else{
		style		= "color:red;";
		Result		= Result + "&nbsp;&nbsp;Response Code:" + Succeed;
	}
%>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Payment Result</title>
		<style type="text/css">
			body{margin:0 auto;text-align:center;}
			table{margin:50 auto;}
			td{padding:5px 20px;}
		</style>
	</head>
	<body>
		<% if(MD5str == MD5info){ %>
			<table border="1">
				<tr>
					<td>Your Order Number:</td>
					<td><%=BillNo%></td>
				</tr>
				<tr>
					<td>Order Amount:</td>
					<td><%=Amount%>&nbsp;&nbsp;&nbsp;<%=CurrencyName%></td>
				</tr>
				<tr>
					<td>Payment Result:</td>
					<td style="<%=style%>"><%=Result%></td>
				</tr>
			</table>
		<% }else{ %>
			<table>
				<tr>
					<td style="text-align:center;color:red;"><% Response.Write("Validation failed!"); %></td>
				</tr>
			</table>
		<% } %>
	</body>
</html>