<!--#include file="./MD5pay.asp"-->
<%
	Function   GetUrl()       ' 获取该网站的域名
	On   Error   Resume   Next     
	Dim   strTemp     
	If   LCase(Request.ServerVariables("HTTPS"))   =   "off"   Then     
	strTemp   =   "http://"     
	Else     
	strTemp   =   "https://"     
	End   If     
	strTemp   =   strTemp   &   Request.ServerVariables("SERVER_NAME")     
	'If   Request.ServerVariables("SERVER_PORT")   <>   80   Then   strTemp   =   strTemp   &   ":"   &   Request.ServerVariables("SERVER_PORT")        
	GetUrl   =   strTemp     
	End   Function 
%>
<%
	'[必填]--------商户号
	Dim MerNo
		MerNo		= "10003"
	'[必填]--------商户注册时MD5KEY，如果不记得请到商户后台->信息管理查看
	Dim MD5key
		MD5key		= "12345678"
	'[必填]订单号---商户自己网站产生的订单号，要求尽量不要重复
	Dim BillNo	
		BillNo		= "1"
	'[必填]交易币种信息目前支持的币种有以下：[请填写对应的数字]
	'目前已支持多币种
	'2:欧元  1:美元  6:日元  4:英镑  5:港币   7:澳元  11:加元  8:挪威克朗  3:人民币  12:丹麦克朗  13:瑞典克朗
	Dim CurrencyStr
		CurrencyStr	= "1"
	'订单金额 
	Dim Amount
		Amount		= "0.01"
	'支付页面语言，目前支持的支付页面语言有以下几种
	'此参数可为空值 当为空时 支付页面语言将会根据浏览器语言选择对应的支付页面语言,  默认为英文
    '站内系统目前只支持英语
	'de:German  es:Spanish  fr:French  it:Italian  ja:Japanese  ko:Korean  en:English
	Dim Language
		Language	= "en"
	'[必填]返回地址 即当买家支付完成后会由支付平台返回到此地址
	'此参数的值也会在商户后台绑定网站的时候用到
	Dim ReturnURL
		ReturnURL	= "http://localhost:8080/iis/work/sht/asp/result.asp"
	'备注
	Dim Remark
		Remark		= GetUrl
	'加密源字符串
	Dim MD5src
		MD5src		= MerNo & BillNo & CurrencyStr & Amount & Language & ReturnURL & MD5key
	'加密字符串
	Dim MD5info
		MD5info		= Ucase(md5(MD5src))
	'暂时未启用
	Dim NoticeURL
		NoticeURL	= ""
	
    Dim Products
        Products = "<Goods><GoodsName>MacBook</GoodsName><Qty>2</Qty><Price>500.00</Price><Currency>USD</Currency><GoodsName>MacBook</GoodsName><Qty>2</Qty><Price>500.00</Price><Currency>USD</Currency></Goods>"
	'以下为九个参数为收货人信息,能收集到的数据请尽量收集,实在收货不到的请赋空值

	Dim DeliveryFirstName	'[选填]收货人姓
		DeliveryFirstName	= "li"
	Dim DeliveryLastName	'[选填]收货人名
		DeliveryLastName	= "qing"
	Dim DeliveryEmail		'[选填]收货人邮箱
		DeliveryEmail		= "liqing@hotmail.com"
	Dim DeliveryPhone		'[选填]收货人固定电话
		DeliveryPhone		= "010968574"
	Dim DeliveryZipCode		'[选填]收货人邮编
		DeliveryZipCode		= "518000"
	Dim DeliveryAddress		'[选填]收货人具体地址
		DeliveryAddress		= "zhuzhou"
	Dim DeliveryCity		'[选填]收货人所在地区
		DeliveryCity		= "shenzhen"
	Dim DeliveryState		'[选填]收货人所在省或者州
		DeliveryState		= "guangdong"
	Dim DeliveryCountry		'[选填]收货人所在国家
		DeliveryCountry		= "china"
	Dim ActionUrl			'支付网关地址
        ActionUrl = "./submitOrder.asp"
%>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Order Payment Test</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
        <DIV style="width: 100%; margin: 0 auto; text-align: center; padding: 20px 0;">
        <form action="<%=ActionUrl%>" method="post">
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
            <input type="submit" name="Payment" value="Payment Submit" style="padding: 8px 30px;"/>
        </form>
        </DIV>
    </body>
<html>