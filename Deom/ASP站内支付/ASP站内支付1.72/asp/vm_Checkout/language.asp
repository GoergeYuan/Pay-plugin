<%
Dim Submit_Query_Title, Submit_Query_Wait, Submit_Query_Fresh, Paymen_Failed_Description
Dim Label_Card_Num, Label_Expire, Label_Expire_Month, Label_Expire_Year, Label_Cvv2, Label_Bank_Name, Label_First_Name, Label_Last_Name, Label_Email, Label_Phone, Label_Zip_Code, Label_Address, Label_City, Label_State, Label_Country, Label_Country_Text

Dim Required_Card_Num, Required_Expire_Month, Required_Expire_Year, Required_Cvv2, Required_Card_Bank, Required_First_Name, Required_Last_Name, Required_Email, Required_Phone, Required_Zip_Code, Required_Address, Required_City, Required_Country
Dim Order_Info_Title, Card_Info_Title, Bill_Info_Title, Order_Amount_Label, Order_Number_Label, Submit_Button_Value
Dim Label_Notice_Line_1, Label_Notice_Line_2, Label_Notice_Line_3, Response_Text

Response_Text = "Response Code:"

If Language = "zh" Then

    Order_Info_Title = "订单信息"
    Card_Info_Title = "卡号信息"
    Bill_Info_Title = "账单信息"
    Order_Number_Label = "订单号："
    Order_Amount_Label = "金额："

	Label_Notice_Line_1 = "当订单处理的过程中，请不要刷新页面"
	Label_Notice_Line_2 = "刷新页面可能会导致重复交易"
	Label_Notice_Line_3 = "你的交易将会非常安全的传送到银行，支付成功后我们将会发一封邮件到您的邮箱，祝您购物愉快"
    
	Submit_Query_Title = "订单在处理中"
	Submit_Query_Wait = "请等待"
	Submit_Query_Fresh = "不要刷新哦"
    Payment_Failed_Description = "支付失败"
    
    Submit_Title = "订单确认"
    
    Label_Card_Num = "卡号："
    Label_Expire = "有效期："
    Label_Expire_Month = "月份"
    Label_Expire_Year = "年份"
    Label_Cvv2 = "CVV："
    Label_Bank_Name = "发卡行："
    Label_First_Name = "姓："
    Label_Last_Name = "名："
    Label_Email = "邮箱："
    Label_Phone = "手机："
    Label_Zip_Code = "邮箱编码："
    Label_Address = "地址："
    Label_City = "城市："
    Label_State = "城市："
    Label_Country = "国家："
    Label_Country_Text = "--选择国家--"
    
    Required_Card_Num = "卡号必须"
    Required_Expire_Month = "月份必须"
    Required_Expire_Year = "年份必须"
    Required_Cvv2 = "校验码必须"
    Required_Card_Bank = "发卡行必须"
    Required_First_Name = "姓必须"
    Required_Last_Name = "名必须"
    Required_Email = "邮箱必须"
    Required_Phone = "手机必须"
    Required_Zip_Code = "邮箱编码必须"
    Required_Address = "地址必须"
    Required_City = "城市必须"
    Required_Country = "国家必须"
    
    Error_Card_Num = "卡号不正确"
    Error_Cvv2 = "CVV2不正确"
    Error_Email = "邮箱格式不正确"
    
    Submit_Button_Value = "提交"
elseif Language = "ja" then
    Order_Info_Title = "オーダーメーセジ"
    Card_Info_Title = "カード情報"
    Bill_Info_Title = "勘定書情報"
    Order_Number_Label = "オーダー番号:"
    Order_Amount_Label = "金額:"
    
	Submit_Query_Title = "受注処理にしている"
	Submit_Query_Wait = "待っていてください"
	Submit_Query_Fresh = "このページをリフレッシュしないでください,このページの更新によって繰り返し費を掛け可能性ある."
    Payment_Failed_Description = "支払い失敗"
    
	Label_Notice_Line_1 = "オーダー処理中このページ更新しないってください。"
	Label_Notice_Line_2 = "このページの更新によって繰り返し費を掛け可能性ある"
	Label_Notice_Line_3 = "オーダー完成後Ｅメール届く、当日為替よって、金額変わる可能性ある"

    Submit_Title = "オーダー確認"
    
    Label_Card_Num = "カード番号:"
    Label_Expire = "有効期限:"
    Label_Expire_Month = "月"
    Label_Expire_Year = "年"
    Label_Cvv2 = "CVV2:"
    Label_Bank_Name = "カード発行元:"
    Label_First_Name = "名前:"
    Label_Last_Name = "名字:"
    Label_Email = "メールアドレス:"
    Label_Phone = "電話番号:"
    Label_Zip_Code = "郵便番号:"
    Label_Address = "住所:"
    Label_City = "都市:"
    Label_State = "州:"
    Label_Country = "国籍:"
    Label_Country_Text = "--国籍選ぶ--"
    
    Required_Card_Num = "必須記載事項"
    Required_Expire_Month = "必須記載事項"
    Required_Expire_Year = "必須記載事項"
    Required_Cvv2 = "必須記載事項"
    Required_Card_Bank = "必須記載事項"
    Required_First_Name = "必須記載事項"
    Required_Last_Name = "必須記載事項"
    Required_Email = "必須記載事項"
    Required_Phone = "必須記載事項"
    Required_Zip_Code = "必須記載事項"
    Required_Address = "必須記載事項"
    Required_City = "必須記載事項"
    Required_Country = "必須記載事項"
    
    Error_Card_Num = "カード番号間違い"
    Error_Cvv2 = "必須記載事項"
    Error_Email = "メールアドレス間違い"
    
    Submit_Button_Value = "オーダー提出"
else
    Order_Info_Title = "Order Information"
    Card_Info_Title = "Card Information"
    Bill_Info_Title = "Billing Information"
    Order_Number_Label = "Order Number:"
    Order_Amount_Label = "Order Amount:"
    
	Submit_Query_Title = "The order is being processed"
	Submit_Query_Wait = "Please wait......"
	Submit_Query_Fresh = "Please do not refresh this page.Refreshing the page can result in your card being charged twice."
    Payment_Failed_Description = "Payment Failed."
    
	Label_Notice_Line_1 = "Please do not refresh this page while your order is being processed."
	Label_Notice_Line_2 = "Refreshing the page can result in your card being charged twice!"
	Label_Notice_Line_3 = "Once your order has been completed successfully you will receive a confirmation email. Your order details will be securely transmitted. Due to exchange rates, the amount billed can vary slightly. Thank you very much for shopping from our shop."

    Submit_Title = "Order Confirmation"
    Label_BillNo = "Order Number:"
    Label_Amount = "Order Amount:"
    
    Label_Card_Num = "Card Number:"
    Label_Expire = "Expiration Date:"
    Label_Expire_Month = "Month"
    Label_Expire_Year = "Year"
    Label_Cvv2 = "CVV2:"
    Label_Bank_Name = "IssuingBank:"
    Label_First_Name = "First Name:"
    Label_Last_Name = "Last Name:"
    Label_Email = "Email:"
    Label_Phone = "Phone:"
    Label_Zip_Code = "Zip Code:"
    Label_Address = "Address:"
    Label_City = "City:"
    Label_State = "State:"
    Label_Country = "Country:"
    Label_Country_Text = "--Please Select Country--"
    
    Required_Card_Num = "Card Number is required"
    Required_Expire_Month = "Month is required"
    Required_Expire_Year = "Year is required"
    Required_Cvv2 = "Cvv2 is required"
    Required_Card_Bank = "IssuingBank is required"
    Required_First_Name = "First Name is required"
    Required_Last_Name = "Last Name is required"
    Required_Email = "Email is required"
    Required_Phone = "Phone is required"
    Required_Zip_Code = "Zip Code is required"
    Required_Address = "Address is required"
    Required_City = "City is required"
    Required_Country = "Country is required"
    
    Error_Card_Num = "Card Number is not correct."
    Error_Cvv2 = "CVV2 is not correct."
    Error_Email = "Email is not correct."
    
    Submit_Button_Value = "Submit Order"
End If 
%>