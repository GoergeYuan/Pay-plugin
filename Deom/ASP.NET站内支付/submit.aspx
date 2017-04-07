<%@ Page Language="C#" ContentType="text/html" ResponseEncoding="UTF-8" %>
<%@ Import Namespace="System.Security.Cryptography" %>
<%
Response.ContentEncoding = System.Text.Encoding.GetEncoding("UTF-8");
Response.Charset = "UTF-8";
%>

<%
	String MD5key;
	MD5key		= "12345678";
	
	String MerNo;
	MerNo		= "10003";	
	
	String BillNo;
	BillNo		= "111111111";
	
	String CurrencyStr;
	CurrencyStr	= "1";
	
	String Amount;	
	Amount 		= "0.01";
	
	String ReturnURL;
	ReturnURL		= "http://localhost:8080/iis/work/sht/asp.net/result.aspx";
	
	String Language;
	Language		= "en";
	
	String md5src;	
	md5src 			= MerNo + BillNo + CurrencyStr + Amount + Language + ReturnURL + MD5key;
	
	String MD5info;
	
	MD5info			= System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(md5src, "MD5");
	
	String Remark;		
	Remark			= HttpContext.Current.Request.Url.Host;			
	 
	String DeliveryFirstName;	
	DeliveryFirstName 	= "li";		
		
	String DeliveryLastName;	
	DeliveryLastName	= "feng";
	
	String DeliveryEmail;		
	DeliveryEmail		= "lishi@gmail.com";
	
	String DeliveryPhone;	
	DeliveryPhone		= "012345678";
	
	String DeliveryZipCode;		
	DeliveryZipCode		= "52000";
	
	String DeliveryAddress;	
	DeliveryAddress		= "shenzhen luohu nanhuilu";
	
	String DeliveryCity;	
	DeliveryCity		= "shenzhen";
	
	String DeliveryState;	
	DeliveryState		= "guangdong";
	
	String DeliveryCountry;	
	DeliveryCountry		= "china";
	
	String Products;
	 Products = "<Goods><GoodsName>MacBook</GoodsName><Qty>2</Qty><Price>500.00</Price><Currency>USD</Currency><GoodsName>MacBook</GoodsName><Qty>2</Qty><Price>500.00</Price><Currency>USD</Currency></Goods>";
	
	String NoticeURL;
	NoticeURL			= "";
%>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Order Payment Test</title>
	</head>
	<body>
        <DIV style="width: 100%; margin: 0 auto; text-align: center; padding: 20px 0;">
		<form action="./submitOrder.aspx" method="post" style="margin:0 auto;">
            <input type="hidden" name="MerNo" value="<%=MerNo%>"/>
            <input type="hidden" name="Currency" value="<%=CurrencyStr%>"/>
            <input type="hidden" name="BillNo" value="<%=BillNo%>"/>
            <input type="hidden" name="Amount" value="<%=Amount%>"/>
            <input type="hidden" name="ReturnURL" value="<%=ReturnURL%>"/>
            <input type="hidden" name="Language" value="<%=Language%>"/>
            <input type="hidden" name="MD5info" value="<%=MD5info%>"/>
            <input type="hidden" name="Remark" value="<%=Remark%>"/>
            <input type="hidden" name="DeliveryFirstName" value="<%=DeliveryFirstName%>"/>
            <input type="hidden" name="DeliveryLastName" value="<%=DeliveryLastName%>"/>
            <input type="hidden" name="DeliveryEmail" value="<%=DeliveryEmail%>"/>
            <input type="hidden" name="DeliveryPhone" value="<%=DeliveryPhone%>"/>
            <input type="hidden" name="DeliveryZipCode" value="<%=DeliveryZipCode%>"/>
            <input type="hidden" name="DeliveryAddress" value="<%=DeliveryAddress%>"/>
            <input type="hidden" name="DeliveryCity" value="<%=DeliveryCity%>"/>
            <input type="hidden" name="DeliveryState" value="<%=DeliveryState%>"/>
            <input type="hidden" name="DeliveryCountry" value="<%=DeliveryCountry%>"/>
            <input type="hidden" name="Products" value="<%=Products%>"/>
            <input type="hidden" name="NoticeURL" value="<%=NoticeURL%>"/>
            <input type="submit" name="payment" value="Payment Submit" style="padding: 8px 30px;"/>
		</form>
        </DIV>
	</body>
</html>