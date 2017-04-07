//验证卡号
function checkcCardnum() {
    var cardnum = document.getElementById("cardnum").value;
    var jgpattern = /^[0-9]+$/;
    var cvvnumsize = cardnum.length;
    cardnumFlag = jgpattern.test(cardnum);
    if (cardnumFlag) {
        if (cvvnumsize == 16) {
            document.getElementById('cardnumError').innerHTML = '';
            var merNo = document.getElementById("merchantnoValue").value;
            getCardInfo('getCardTypeByJson.action?cardnum=' + cardnum + '&merNo=' + merNo);
        } else {
            document.getElementById('cardnumError').innerHTML = '16-digit card number is required!';
        }
    } else {
        document.getElementById('cardnumError').innerHTML = 'The credit card number is incorrect!';
    }
}

function getCardInfo(jsonObjGetUrl) {
    // 将favorite表单域的值转换为请求参数
    // var params = Form.serialize('form1');
    // 创建Ajax.Request对象，对应于发送请求
    var myAjax = new Ajax.Request(jsonObjGetUrl, {
        // 请求方式：POST
        method: 'post',
        // 请求参数
        // parameters:params,
        // 指定回调函数
        onComplete: processResponse,
        // 是否异步发送请求
        asynchronous: false
    });
}

function processResponse(request) {
    // 使用JSON对象将服务器响应解析成JSON对象
    var res = JSON.parse(request.responseText);
    // 遍历JSON对象的每个属性
    $("cardnumError").innerHTML = res.jsonData;
}

/***<!--验证发卡行名称-->***/
function checkcCardname() {
    var cardname = document.getElementById("cardbank").value;
    var cardnameError = document.getElementById("cardname").value;

    if (cardname == "") {
        document.getElementById("cardnameError").innerHTML = cardnameError;
    } else {
        document.getElementById("cardnameError").innerHTML = '';
    }
}

/***<!--验证cvv2-->***/
function checkCvv2() {
    var jgpattern = /^[0-9]+$/;
    var cvv2 = document.getElementById("cvv2").value;
    var cvv2_error = document.getElementById("cvv2_error").value;
    var cvvnum = cvv2.length;
    cvv2Flag = jgpattern.test(cvv2);
    if (cvv2Flag) {
        if (cvvnum == 3) {
            document.getElementById('cvv2Error').innerHTML = '';
            return true;
        } else {
            document.getElementById('cvv2Error').innerHTML = cvv2_error;
            return false;
        }
    } else {
        document.getElementById('cvv2Error').innerHTML = cvv2_error;
        return false;
    }
}

/***<!--检验邮箱-->***/
function checkMail(inputName, divValue) {
    var email = document.getElementById(inputName).value;
    var mail_info = document.getElementById("mail_info").value;

    //var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,4}){1,2})$/;
    var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    chkFlag = pattern.test(email);
    if (chkFlag) {
        document.getElementById(divValue).innerHTML = '';
        return true;
    } else {
        document.getElementById(divValue).innerHTML = mail_info;
        return false;
    }
}



// 验证有效期不能小于当前日期
function checkdate() {
    var date = new Date(); // 获得系统日期的文本值
    var y = date.getFullYear();
    var m = date.getMonth();
    var d = date.getDate();
    var dt = y + "-" + m + "-" + d;
    var year = document.getElementById("year").value; // 获得用户选择的日期文本值
    var month = document.getElementById("month").value;
    var date = "20" + year + "-" + month + "-" + d;

    var arrJHRQ = dt.split('-'); // 转成成数组，分别为年，月，日，下同
    var arrJHWCSJ = date.split('-');
    var dateJHRQ = new Date(parseInt(arrJHRQ[0]), parseInt(arrJHRQ[1]) - 1,
        parseInt(arrJHRQ[2]), 0, 0, 0); // 新建日期对象
    var dateJHWCSJ = new Date(parseInt(arrJHWCSJ[0]),
        parseInt(arrJHWCSJ[1]) - 1, parseInt(arrJHWCSJ[2]), 0, 0, 0);
    var date_info = document.getElementById("date_info").value;
    if (dateJHRQ.getTime() > dateJHWCSJ.getTime()) {

        document.getElementById('cvv2Error').innerHTML = date_info

    } else {
        document.getElementById('cvv2Error').innerHTML = "";
    }
}






var i = 0;
//<!--验证数据是否为空	-->
function isSubmit() {

    var email = document.getElementById("email").value;
    var cardnum = document.getElementById("cardnum").value;
    var cvv2 = document.getElementById("cvv2").value;
    var country = document.getElementById("country").value;
    var cvv2Error = document.getElementById("cvv2Error").innerHTML.replace(/\s+/g, "");
    var month = document.getElementById("month").value;
    var year = document.getElementById("year").value;
    var cardNumError = document.getElementById("cardnumError").innerHTML.replace(/\s+/g, "");
    var countryError = document.getElementById("countryError").innerHTML.replace(/\s+/g, "");
    var mailInfo = document.getElementById("mailError").innerHTML;

    var a1 = true;
    var b1 = true;
    var c1 = true;
    var d1 = true;
    var e1 = true;
    var f1 = true;


    if (email == "" || email == null) {
        document.getElementById("mailError").innerHTML = "Incorrect billing email!";
        f1 = false;
        return false;
    } else {
        var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
        chkFlag = pattern.test(email);
        if (!chkFlag) {
            document.getElementById("mailError").innerHTML = "Email format is incorrect";
            f1 = false;
            return false;
        } else {
            document.getElementById("mailError").innerHTML = "";
            f1 = true;
        }
    }


    if (cardnum == "" || cardnum == null) {
        a1 = false;
        document.getElementById('cardnumError').innerHTML = '16-digit card number is required!';
        return false;
    } else {

        var jgpattern = /^[0-9]+$/;
        var cvvnumsize = cardnum.length;
        cardnumFlag = jgpattern.test(cardnum);
        if (cardnumFlag) {
            if (cvvnumsize == 16) {

                var card = cardnum.substr(0, 1);
                //if(card != 4){
                //	document.getElementById('cardnumError').innerHTML = 'Please input valid card number.';
                //	a1 = false;
                //	return false;
                //}else{
                //	if(cardNumError != ""){
                //		document.getElementById('cardnumError').innerHTML = 'Please input valid card number.';
                //		a1 = false;
                //		return false;
                //	}else{
                //		document.getElementById('cardnumError').innerHTML = "";
                //		a1 = true;
                //	}
                //}
                if (card == 3) {
                    document.getElementById('cardnumError').innerHTML = 'Please input valid card number.';
                    a1 = false;
                    return false;
                }
            } else {

                document.getElementById('cardnumError').innerHTML = '16-digit card number is required!';
                a1 = false;

                return false;
            }
        } else {
            document.getElementById('cardnumError').innerHTML = 'The credit card number is incorrect!';

            a1 = false;
            return false;
        }
    }

    if (month == "" || month == null) {
        alert("The month:" + month);
        document.getElementById("cvv2Error").innerHTML = "The month is required!";
        c1 = false;
        return false;
    } else {
        document.getElementById("cvv2Error").innerHTML = "";
        c1 = true;
    }

    if (year == "" || year == null) {
        alert("The year:" + year);
        document.getElementById("cvv2Error").innerHTML = "The year is required!";
        d1 = false;
        return false;
    } else {
        document.getElementById("cvv2Error").innerHTML = "";
        d1 = true;
    }



    if (cvv2 == "" || cvv2 == null) {
        document.getElementById("cvv2Error").innerHTML = "Card Verification Number for VISA/Mastercard is a 3-digit number!";
        b1 = false;
        return false;
    } else {
        document.getElementById("cvv2Error").innerHTML = "";
        b1 = true;
    }



    if (country == 0 || country == null) {
        document.getElementById("countryError").innerHTML = "The billing country is required!";
        e1 = false;
        return false;
    } else {
        document.getElementById("countryError").innerHTML = "";
        e1 = true;
    }

    //	if (email == "" || email == null) {
    //		document.getElementById("mailError").innerHTML = "Incorrect billing email!";
    //		f1 = false;
    //	return false;
    //	} else {
    //		var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    //		chkFlag = pattern.test(email);
    //	if (!chkFlag) {
    //		document.getElementById("mailError").innerHTML = "Email format is incorrect";
    //		f1 = false;
    //		return false;
    //	}else{
    //		document.getElementById("mailError").innerHTML = "";
    //		f1 = true;
    //	}
    //}

    if (a1 && b1 && c1 && d1 && e1 && f1) {
        document.getElementById("form1").submit();
    } else {
        return false;
    }
}

//<!--Validation card month-->
function checkMonth() {
    var month = document.getElementById("month").value;
    if (month == '') {
        document.getElementById("monthError").innerHTML = "Expiration Date month is required!";
        return false;
    } else {
        document.getElementById("monthError").innerHTML = "";
        return true;
    }
}

//<!--Validation card year-->
function checkYear() {
    var year = document.getElementById("year").value;
    if (year == '') {
        document.getElementById("yearError").innerHTML = "Expiration Date year is required!";
        return false;
    } else {
        document.getElementById("yearError").innerHTML = "";
        return true;
    }

}

//<!--检验ship邮箱是否重复-->
function checkConfirmEmail() {
    var email = document.getElementById("shippingEmail").value;
    var confirmemail = document.getElementById("shippingComfirmEmail").value;
    if (email != confirmemail) {
        document.getElementById("shipcomfirmEmailError").innerHTML = 'E-mail address is inconsistent';
    } else {
        document.getElementById("shipcomfirmEmailError").innerHTML = '';
    }
}

//<!--检验bill City-->
function checkShipZipcode() {
    var lastname = document.getElementById("shippingZipcode").value;
    if (lastname == "") {
        document.getElementById("shippingzipcodeError").innerHTML = 'Zipcode is required';
    } else {
        document.getElementById("shippingzipcodeError").innerHTML = '';
    }
}

//<!--检验bill City-->
function checkShipAddress() {
    var lastname = document.getElementById("shippingAddress").value;
    if (lastname == "") {
        document.getElementById("shippingaddreeError").innerHTML = 'Address is required';
    } else {
        document.getElementById("shippingaddreeError").innerHTML = '';
    }
}

//<!--检验bill City-->
function checkShipPhone() {
    var lastname = document.getElementById("shippingPhone").value;
    if (lastname == "") {
        document.getElementById("shippingphoneError").innerHTML = 'Phone is required';
    } else {
        document.getElementById("shippingphoneError").innerHTML = '';
    }
}



//<!--快速支付验证数据是否为空	-->
function isfastSubmit() {

    var cardnum = document.getElementById("cardnum").value;
    var cvv2 = document.getElementById("cvv2").value;
    var month = document.getElementById("month").value;
    var year = document.getElementById("year").value;
    var cardNumError = document.getElementById("cardNumError").innerHTML;
    var cvv2Error = document.getElementById("cvv2Error").innerHTML;

    if (cardnum == "") {
        alert("16-digit card number is required!");
        return false;
    }
    if (cvv2 == "") {
        alert("Card Verification Number for VISA is a 3-digit number!");
        return false;
    }
    if (month == "") {
        alert("The month is required!");
        return false;
    }
    if (year == "") {
        alert("The year is required!");
        return false;
    }
    if (cardNumError != "") {
        alert(cardNumError);
        return false;
    }
    if (cvv2Error != "") {
        alert(cvv2Error);
        return false;
    }

    document.getElementById("form1").submit();
}
