<%
Response.ContentEncoding = System.Text.Encoding.GetEncoding("UTF-8");
Response.Charset = "UTF-8";
%>
<%
String Submit_Title = "Order Confirmation";
String Order_Info_Title = "Order information";
String Card_Info_Title = "Card information";
String Bill_Info_Title = "Billing information";
String Order_Number_Label = "Order Number:";
String Order_Amount_Label = "Order Amount:";
String Submit_Button_Value = "Submit Order";

String Label_Card_Num = "Card Number:";
String Label_Expire = "Expiration Date:";
String Label_Expire_Month = "Month";
String Label_Expire_Year = "Year";
String Label_Bank_Name = "IssuingBank Name:";
String Label_Cvv2 = "CVV2:";
String Label_Card_Bank = "IssuingBank:";
String Label_First_Name = "First Name:";
String Label_Last_Name = "Last Name:";
String Label_Email = "Email:";
String Label_Phone = "Phone:";
String Label_City = "City:";
String Label_State = "State:";
String Label_Zip_Code = "Zip Code:";
String Label_Address = "Address:";
String Label_Country = "Country:";
String Label_Country_Text = "--Please Select Country--";

String Required_Card_Num = "Card Number is required.";
String Required_Expire_Month = "Month is required.";
String Required_Expire_Year = "Year is required.";
String Required_Cvv2 = "Cvv2 iss required.";
String Required_Card_Bank = "IssuingBank is required.";
String Required_First_Name = "First Name is required.";
String Required_Last_Name = "Last Name is required.";
String Required_Email = "Email is required.";
String Required_Zip_Code = "Zip Code is required.";
String Required_Phone = "Phone is required.";
String Required_City = "City is required.";
String Required_Address = "Address is required.";
String Required_Country = "Country is required.";

String Error_Card_Num = "Card Number is not correct.";
String Error_Cvv2 = "Cvv2 is not correct.";
String Error_Email = "Email is not correct.";

String Label_Notice_Line_1 = "Please do not refresh this page while your order is being processed.";
String Label_Notice_Line_2 = "Refreshing the page can result in your card being charged twice!";
String Label_Notice_Line_3 = "Once your order has been completed successfully you will receive a confirmation email. Your order details will be securely transmitted. Due to exchange rates, the amount billed can vary slightly. Thank you very much for shopping from our shop.";

String Submit_Query_Title = "The order is being processed";
String Submit_Query_Wait = "Please Wait ......";
String Submit_Query_Fresh = "Please do not refresh this page.Refreshing the page can result in your card being charged twice.";

String Response_Text = "Response Code:";
String Payment_Failed_Description = "Payment Failed.";
/*
if(Language == "ja"){
    
	Submit_Title = "オーダー確認";
	
    Order_Info_Title = "オーダーメーセジ";
    Card_Info_Title = "カード情報";
    Bill_Info_Title = "勘定書情報";
    Order_Number_Label = "オーダー番号:";
    Order_Amount_Label = "金額:";
    Submit_Button_Value = "オーダー提出";
    
    Label_Card_Num = "カード番号:";
    Label_Expire = "有効期限:";
    Label_Expire_Month = "月";
    Label_Expire_Year = "年";
    Label_Cvv2 = "CVV2:";
    Label_Card_Bank = "カード発行元:";
    Label_First_Name = "名前:";
    Label_Last_Name = "名字:";
    Label_Email = "メールアドレス:";
    Label_Phone = "電話番号:";
    Label_Zip_Code = "郵便番号:";
    Label_Address = "住所:";
    Label_City = "都市:";
    Label_State = "州:";
    Label_Country = "国籍:";
    Label_Country_Text = "--国籍選ぶ--";
    
    Required_Card_Num = "必須記載事項.";
    Required_Expire_Month = "必須記載事項.";
    Required_Expire_Year = "必須記載事項.";
    Required_Cvv2 = "必須記載事項.";
    Required_Card_Bank = "必須記載事項.";
    Required_First_Name = "必須記載事項.";
    Required_Last_Name = "必須記載事項.";
    Required_Email = "必須記載事項.";
    Required_Phone = "必須記載事項.";
    Required_City = "必須記載事項.";
    Required_Address = "必須記載事項.";
    Required_Country = "必須記載事項.";
    
    Error_Card_Num = "カード番号間違い.";
    Error_Cvv2 = "必須記載事項";
    Error_Email = "メールアドレス間違い.";

	Label_Notice_Line_1 = "オーダー処理中このページ更新しないってください。";
	Label_Notice_Line_2 = "このページの更新によって繰り返し費を掛け可能性ある";
	Label_Notice_Line_3 = "オーダー完成後Ｅメール届く、当日為替よって、金額変わる可能性ある";

    Submit_Query_Title = "受注処理にしている";
    Submit_Query_Wait = "待っていてください ......";
    Submit_Query_Fresh = "このページをリフレッシュしないでください,このページの更新によって繰り返し費を掛け可能性ある.";
    
    Payment_Failed_Description = "支払い失敗.";
}
*/
%>