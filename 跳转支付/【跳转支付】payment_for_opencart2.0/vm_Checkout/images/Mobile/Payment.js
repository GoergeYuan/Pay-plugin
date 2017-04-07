function show_province(province) {
    var billcountry = document.getElementById("billcountry");
    var billprovince = document.getElementById("billprovince");
    var billprovince_us = document.getElementById("billprovince_us");
    var billprovince_ca = document.getElementById("billprovince_ca");
    var country = billcountry.value.toUpperCase();
    if (country == "US" || country == "USA") {
        billprovince.style.display = "none";
        billprovince_us.style.display = "";
        billprovince_ca.style.display = "none";
        if (province != null) {
            province = province.toUpperCase().replace(/[^A-Z]/g, "");
            for (var ii = 0; ii < billprovince_us.options.length; ii++) {
                var text = billprovince_us.options[ii].text.toUpperCase().replace(/[^A-Z]/g, "");
                var value = billprovince_us.options[ii].value.toUpperCase().replace(/[^A-Z]/g, "");
                if (province == text || province == value) { billprovince_us.options[ii].selected = true; billprovince_us.selectedIndex = ii; }
            }
        }
        billprovince.value = billprovince_us.value;
    }
    else if (country == "CA" || country == "CAN") {
        billprovince.style.display = "none";
        billprovince_us.style.display = "none";
        billprovince_ca.style.display = "";
        if (province != null) {
            province = province.toUpperCase().replace(/[^A-Z]/g, "");
            for (var ii = 0; ii < billprovince_ca.options.length; ii++) {
                var text = billprovince_ca.options[ii].text.toUpperCase().replace(/[^A-Z]/g, "");
                var value = billprovince_ca.options[ii].value.toUpperCase().replace(/[^A-Z]/g, "");
                if (province == text || province == value) { billprovince_ca.options[ii].selected = true; billprovince_ca.selectedIndex = ii; }
            }
        }
        billprovince.value = billprovince_ca.value;
    }
    else {
        billprovince.style.display = "";
        billprovince_us.style.display = "none";
        billprovince_ca.style.display = "none";
    }
}
var reEmail = new RegExp(/^\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w+$/);
var reNumber = new RegExp('^[0-9 ]*$');
var reDecimal = new RegExp('^[1-9]d*.d*|0.d*[1-9]d*|0?.0+|0$');
function ShowToolTip(argID, argInfo) {
    if (argID == null) { alert(argInfo); return; }
    var xPos = argID.offsetLeft;
    var yPos = argID.offsetTop;
    var dvToolTip = document.getElementById("dvToolTip");
    dvToolTip.style.display = 'block';
    dvToolTip.style.left = GetObjPos(argID).x + dvToolTip.offsetWidth - 55 + "px";
    dvToolTip.style.top = GetObjPos(argID).y - 23 + "px";
    document.getElementById("spTooTip").innerHTML = argInfo;
    argID.focus();
}
function hideToolTip() {
    var dvToolTip = document.getElementById("dvToolTip");
    dvToolTip.style.display = 'none';
}
function CheckCardBin(cardnum) {
    var card_img1 = document.getElementById("card_img1");
    var card_img2 = document.getElementById("card_img2");
    var card_img3 = document.getElementById("card_img3");
    var ch = cardnum.substring(0, 1);
    if (ch == "4") { if (card_img1.style.display == "none") return false; }
    else if (ch == "5") { if (card_img2.style.display == "none") return false; }
    else if (ch == "3") { if (card_img3.style.display == "none") return false; }
    else return false;
    return true;
}
function GetObjPos(ATarget) {
    var target = ATarget;
    var pos = new CPos(target.offsetLeft, target.offsetTop);
    var target = target.offsetParent;
    while (target) {
        pos.x += target.offsetLeft;
        pos.y += target.offsetTop;
        target = target.offsetParent;
    }
    return pos;
}
function CPos(x, y) {
    this.x = x;
    this.y = y;
}
function AutoAddSpace(argID) {
    var len = 19;
    var reg = /\s{1,}/g;
    var card_ = "";
    var card = argID.value;
    card = card.replace(reg, "");
    for (var i = 0; i < len; i++) {
        if (i == 3 || i == 7 || i == 11 || i == 15) {
            card_ = card_ + card.charAt(i) + " ";
        }
        else {
            card_ = card_ + card.charAt(i);
        }
    }
    card_ = card_.Trim();
    argID.value = card_;
}
function card_focus() {
    if (document.getElementById("card_img1")) {
        var img1 = document.getElementById("card_img1");
        img1.src = "../resources/images/pic01.gif";
    }

    if (document.getElementById("card_img2")) {
        var img2 = document.getElementById("card_img2");
        img2.src = "../resources/images/pic02.gif";
    }

    if (document.getElementById("card_img3")) {
        var img3 = document.getElementById("card_img3");
        img3.src = "../resources/images/pic03.gif";
    }
}
function card_change(argID) {
    var img1 = "";
    var img2 = "";
    var img3 = "";

    if (document.getElementById("card_img1")) img1 = document.getElementById("card_img1");
    if (document.getElementById("card_img2")) img2 = document.getElementById("card_img2");
    if (document.getElementById("card_img3")) img3 = document.getElementById("card_img3");
    var bin = parseInt(argID.value.substring(0, 4) + argID.value.substring(5, 7));
    if (bin >= 400000 && bin <= 499999) {
        img1.src = "../resources/images/pic01.gif";
        img2.src = "../resources/images/pic020.gif";
        img3.src = "../resources/images/pic030.gif";
        document.getElementById("Card_Type").value = "1";
        document.getElementById("btnPay").disabled = false;
        if (img1.style.display == "none") {
            alert("Unsupported card types");
            document.getElementById("btnPay").disabled = true;
        }
    } else if (bin >= 510000 && bin <= 599999) {
        img1.src = "../resources/images/pic010.gif";
        img2.src = "../resources/images/pic02.gif";
        img3.src = "../resources/images/pic030.gif";
        document.getElementById("Card_Type").value = "2";
        document.getElementById("btnPay").disabled = false;
        if (img2.style.display == "none") {
            alert("Unsupported card types");
            document.getElementById("btnPay").disabled = true;
        }
    } else if (bin >= 352800 && bin <= 358999) {
        img1.src = "../resources/images/pic010.gif";
        img2.src = "../resources/images/pic020.gif";
        img3.src = "../resources/images/pic03.gif";
        document.getElementById("Card_Type").value = "3";
        document.getElementById("btnPay").disabled = false;
        if (img3.style.display == "none") {
            alert("Unsupported card types");
            document.getElementById("btnPay").disabled = true;
        }
    } else {
        img1.src = "../resources/images/pic010.gif";
        img2.src = "../resources/images/pic020.gif";
        img3.src = "../resources/images/pic030.gif";
        document.getElementById("Card_Type").value = "";
        document.getElementById("btnPay").disabled = true;
        if (argID.value != "") alert("Unsupported card types(" + bin + ").");
    }
}
String.prototype.Trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
function HideDiv(argDivID) {
    var objDiv = document.getElementById(argDivID);
    if (objDiv != null) {
        objDiv.style.display = 'none';
    }
}
function ShowDiv(argDivID, argEventObj) {
    var objDiv = document.getElementById(argDivID);
    if (objDiv != null) {
        objDiv.style.display = 'block';
        objDiv.style.left = GetObjPos(argEventObj).x + objDiv.offsetWidth + 30 + "px";
        objDiv.style.top = GetObjPos(argEventObj).y - 23 + "px";
    }
}
function sAlert(str) {
    var msgw, msgh, bordercolor;
    msgw = 400;
    msgh = 100;
    titleheight = 25;
    bordercolor = "#336699 ";
    titlecolor = "#99CCFF ";
    var sWidth, sHeight;
    sWidth = document.body.offsetWidth;
    sHeight = screen.height;
    var bgObj = document.createElement("div");
    bgObj.setAttribute('id', 'bgDiv');
    bgObj.style.position = "absolute";
    bgObj.style.top = "0";
    bgObj.style.background = "#777 ";
    bgObj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75 ";
    bgObj.style.opacity = "0.6";
    bgObj.style.left = "0";
    bgObj.style.width = sWidth + "px";
    bgObj.style.height = sHeight + "px";
    bgObj.style.zIndex = "10000";
    document.body.appendChild(bgObj);
    var msgObj = document.createElement("div");
    msgObj.setAttribute("id", "msgDiv");
    msgObj.setAttribute("align", "center");
    msgObj.style.background = "white ";
    msgObj.style.border = "1px solid ";
    msgObj.style.position = "absolute";
    msgObj.style.left = "50%";
    msgObj.style.top = "50%";
    msgObj.style.font = "14px/1.6em   Verdana,   Geneva,   Arial,   Helvetica,   sans-serif";
    msgObj.style.marginLeft = "-225px";
    msgObj.style.marginTop = -75 + document.documentElement.scrollTop + "px";
    msgObj.style.width = msgw + "px";
    msgObj.style.height = msgh + "px";
    msgObj.style.textAlign = "center";
    msgObj.style.lineHeight = "60px";
    msgObj.style.zIndex = "10001";
    function removeObj() {
        document.body.removeChild(bgObj);
        document.body.removeChild(msgObj);
    }
    document.body.appendChild(msgObj);
    var txt = document.createElement("p");
    txt.style.margin = "1em   0";
    txt.setAttribute("id", "msgTxt");
    txt.innerHTML = str;
    document.getElementById("msgDiv").appendChild(txt);
}
function init_config() {
    var hidTimeZone = document.getElementById('hidTimeZone');
    var hidScreen = document.getElementById('hidScreen');
    var hidBroswerType = document.getElementById('hidBroswerType');
    var hidOsType = document.getElementById('hidOsType');
    var d = new Date();
    var gmtHours = parseInt(d.getTimezoneOffset() / 60);
    gmtHours = -gmtHours;
    hidTimeZone.value = "GMT" + gmtHours;
    hidScreen.value = window.screen.width + "x" + window.screen.height;
    hidBroswerType.value = navigator.appName;
    hidOsType.value = navigator.cpuClass;
}
//初始化国家信息
function init_country(code) {
    var code = code.toUpperCase().replace(/[^A-Z()©-￼]/g, "");
    var us_obj, us_idx;
    var idx = -1;
    var Country = document.getElementById("billcountry");
    for (var ii = 0; ii < cou_code.length; ii++) {
        var opt = new Option();
        opt.value = cou_code[ii];
        opt.text = cou_name[ii];
        if (cou_cod2[ii] == "USA") { us_obj = opt; us_idx = ii; }
        var tmp = cou_name[ii].toUpperCase().replace(/[^A-Z()©-￼]/g, "");
        if (code != "" && tmp != "" && (cou_code[ii] == code || cou_cod2[ii] == code || tmp == code || tmp.indexOf(code + "(") == 0 || tmp.indexOf("(" + code + ")") >= 0)) { opt.selected = true; idx = ii; } //过滤除a-z A-Z 0-9外的所有字符
        Country.options[Country.options.length] = opt;
    }
    if (idx < 0 && us_obj) { us_obj.selected = true; Country.selectedIndex = us_idx; }
    else Country.selectedIndex = idx;
    var Country2 = document.getElementById("Card_BinCountry");
    for (var ii = 0; ii < cou_cod2.length; ii++) {
        var opt = new Option();
        opt.value = cou_cod2[ii];
        opt.text = cou_name[ii];
        if (cou_cod2[ii] == "USA") us_obj = opt;
        if (idx == ii) { opt.selected = true; }
        Country2.options[Country2.options.length] = opt;
    }
    if (idx < 0 && us_obj) { us_obj.selected = true; Country2.selectedIndex = us_idx; }
    else Country2.selectedIndex = idx;
}

if (!Array.prototype.push) {
    Array.prototype.push = function array_push() {
        for (var i = 0; i < arguments.length; i++) this[this.length] = arguments[i];
        return this.length;
    }
};
if (!Array.prototype.pop) {
    Array.prototype.pop = function array_pop() {
        lastElement = this[this.length - 1];
        this.length = Math.max(this.length - 1, 0);
        return lastElement;
    }
};
var cou_code = new Array();  //账单国家编码
var cou_cod2 = new Array();  //信用卡国家编码
var cou_name = new Array();  //国家名称
cou_code.push("AF"); cou_cod2.push("AFG"); cou_name.push("Afghanistan (افغانستان) ");
cou_code.push("AX"); cou_cod2.push("ALA"); cou_name.push("Aland Islands  ");
cou_code.push("AL"); cou_cod2.push("AFG"); cou_name.push("Albania (Shqipëria)  ");
cou_code.push("DZ"); cou_cod2.push("ALB"); cou_name.push("Algeria (الجزائر) ");
cou_code.push("AS"); cou_cod2.push("ASM"); cou_name.push("American Samoa ");
cou_code.push("AD"); cou_cod2.push("AND"); cou_name.push("Andorra  ");
cou_code.push("AO"); cou_cod2.push("AGO"); cou_name.push("Angola");
cou_code.push("AI"); cou_cod2.push("AIA"); cou_name.push("Anguilla ");
cou_code.push("AP"); cou_cod2.push("ATA"); cou_name.push("Antarctica  ");
cou_code.push("AG"); cou_cod2.push("ATG"); cou_name.push("Antigua and Barbuda  ");
cou_code.push("AR"); cou_cod2.push("ARG"); cou_name.push("Argentina");
cou_code.push("AM"); cou_cod2.push("ARM"); cou_name.push("Armenia (Հայաստան)");
cou_code.push("AW"); cou_cod2.push("ABW"); cou_name.push("Aruba ");
cou_code.push("AU"); cou_cod2.push("AUS"); cou_name.push("Australia");
cou_code.push("AT"); cou_cod2.push("AUT"); cou_name.push("Austria (Österreich) ");
cou_code.push("AZ"); cou_cod2.push("AZE"); cou_name.push("Azerbaijan (Azərbaycan) ");
cou_code.push("BS"); cou_cod2.push("BHS"); cou_name.push("Bahamas  ");
cou_code.push("BH"); cou_cod2.push("BHR"); cou_name.push("Bahrain (البحرين) ");
cou_code.push("BD"); cou_cod2.push("BGD"); cou_name.push("Bangladesh (বাংলাদেশ)");
cou_code.push("BB"); cou_cod2.push("BRB"); cou_name.push("Barbados ");
cou_code.push("BY"); cou_cod2.push("BLR"); cou_name.push("Belarus (Белару́сь)  ");
cou_code.push("BE"); cou_cod2.push("BEL"); cou_name.push("Belgium (België)  ");
cou_code.push("BZ"); cou_cod2.push("BLZ"); cou_name.push("Belize");
cou_code.push("BJ"); cou_cod2.push("BEN"); cou_name.push("Benin (Bénin)  ");
cou_code.push("BM"); cou_cod2.push("BMU"); cou_name.push("Bermuda  ");
cou_code.push("BT"); cou_cod2.push("BTN"); cou_name.push("Bhutan (འབྲུག་ཡུལ)");
cou_code.push("BO"); cou_cod2.push("BOL"); cou_name.push("Bolivia  ");
cou_code.push("BA"); cou_cod2.push("BIH"); cou_name.push("Bosnia and Herzegovina (Bosna i Hercegovina)  ");
cou_code.push("BW"); cou_cod2.push("BWA"); cou_name.push("Botswana ");
cou_code.push("BV"); cou_cod2.push("BVT"); cou_name.push("Bouvet Island  ");
cou_code.push("BR"); cou_cod2.push("BRA"); cou_name.push("Brazil (Brasil)");
cou_code.push("IO"); cou_cod2.push("IOT"); cou_name.push("British Indian Ocean Territory");
cou_code.push("BN"); cou_cod2.push("BRN"); cou_name.push("Brunei (Brunei Darussalam) ");
cou_code.push("BG"); cou_cod2.push("BGR"); cou_name.push("Bulgaria (България)  ");
cou_code.push("BF"); cou_cod2.push("BFA"); cou_name.push("Burkina Faso");
cou_code.push("BI"); cou_cod2.push("BDI"); cou_name.push("Burundi (Uburundi)");
cou_code.push("KH"); cou_cod2.push("KHM"); cou_name.push("Cambodia (Kampuchea) ");
cou_code.push("CM"); cou_cod2.push("CMR"); cou_name.push("Cameroon (Cameroun)  ");
cou_code.push("CA"); cou_cod2.push("CAN"); cou_name.push("Canada");
cou_code.push("CV"); cou_cod2.push("CPV"); cou_name.push("Cape Verde (Cabo Verde) ");
cou_code.push("KY"); cou_cod2.push("CYM"); cou_name.push("Cayman Islands ");
cou_code.push("CF"); cou_cod2.push("CAF"); cou_name.push("Central African Republic (République Centrafricaine)");
cou_code.push("TD"); cou_cod2.push("TCD"); cou_name.push("Chad (Tchad)");
cou_code.push("CL"); cou_cod2.push("CHL"); cou_name.push("Chile ");
cou_code.push("CN"); cou_cod2.push("CHN"); cou_name.push("China (中国)  ");
cou_code.push("CO"); cou_cod2.push("COL"); cou_name.push("Colombia ");
cou_code.push("KM"); cou_cod2.push("COM"); cou_name.push("Comoros (Comores) ");
cou_code.push("CG"); cou_cod2.push("COG"); cou_name.push("Congo ");
cou_code.push("CD"); cou_cod2.push("COD"); cou_name.push("Congo, Democratic Republic of the ");
cou_code.push("CK"); cou_cod2.push("COK"); cou_name.push("Cook Islands");
cou_code.push("CR"); cou_cod2.push("CRI"); cou_name.push("Costa Rica  ");
cou_code.push("CI"); cou_cod2.push("CIV"); cou_name.push("Côte d'Ivoire ");
cou_code.push("HR"); cou_cod2.push("HRV"); cou_name.push("Croatia (Hrvatska)");
cou_code.push("CU"); cou_cod2.push("CUB"); cou_name.push("Cuba  ");
cou_code.push("CY"); cou_cod2.push("CYP"); cou_name.push("Cyprus (Κυπρος)");
cou_code.push("CZ"); cou_cod2.push("CZE"); cou_name.push("Czech Republic (Česko)  ");
cou_code.push("DK"); cou_cod2.push("DNK"); cou_name.push("Denmark (Danmark) ");
cou_code.push("DJ"); cou_cod2.push("DJI"); cou_name.push("Djibouti ");
cou_code.push("DM"); cou_cod2.push("DMA"); cou_name.push("Dominica ");
cou_code.push("DO"); cou_cod2.push("DOM"); cou_name.push("Dominican Republic");
cou_code.push("EC"); cou_cod2.push("ECU"); cou_name.push("Ecuador  ");
cou_code.push("EG"); cou_cod2.push("EGY"); cou_name.push("Egypt (مصر) ");
cou_code.push("SV"); cou_cod2.push("SLV"); cou_name.push("El Salvador ");
cou_code.push("GQ"); cou_cod2.push("GNQ"); cou_name.push("Equatorial Guinea (Guinea Ecuatorial)");
cou_code.push("ER"); cou_cod2.push("ERI"); cou_name.push("Eritrea (Ertra)");
cou_code.push("EE"); cou_cod2.push("EST"); cou_name.push("Estonia (Eesti)");
cou_code.push("ET"); cou_cod2.push("ETH"); cou_name.push("Ethiopia (Ityop'iya) ");
cou_code.push("FK"); cou_cod2.push("FLK"); cou_name.push("Falkland Islands  ");
cou_code.push("FO"); cou_cod2.push("FRO"); cou_name.push("Faroe Islands  ");
cou_code.push("FJ"); cou_cod2.push("FJI"); cou_name.push("Fiji  ");
cou_code.push("FI"); cou_cod2.push("FIN"); cou_name.push("Finland (Suomi)");
cou_code.push("FR"); cou_cod2.push("FRA"); cou_name.push("France");
cou_code.push("GF"); cou_cod2.push("GUF"); cou_name.push("French Guiana  ");
cou_code.push("PF"); cou_cod2.push("PYF"); cou_name.push("French Polynesia  ");
cou_code.push("TF"); cou_cod2.push("ATF"); cou_name.push("French Southern Territories");
cou_code.push("GA"); cou_cod2.push("GAB"); cou_name.push("Gabon ");
cou_code.push("GM"); cou_cod2.push("GMB"); cou_name.push("Gambia");
cou_code.push("GE"); cou_cod2.push("GEO"); cou_name.push("Georgia (საქართველო) ");
cou_code.push("DE"); cou_cod2.push("DEU"); cou_name.push("Germany (Deutschland)");
cou_code.push("GH"); cou_cod2.push("GHA"); cou_name.push("Ghana ");
cou_code.push("GI"); cou_cod2.push("GIB"); cou_name.push("Gibraltar");
cou_code.push("GR"); cou_cod2.push("GRC"); cou_name.push("Greece(ελλάσ)");
cou_code.push("GL"); cou_cod2.push("GRL"); cou_name.push("Greenland");
cou_code.push("GD"); cou_cod2.push("GRD"); cou_name.push("Grenada  ");
cou_code.push("GP"); cou_cod2.push("GLP"); cou_name.push("Guadeloupe  ");
cou_code.push("GU"); cou_cod2.push("GUM"); cou_name.push("Guam  ");
cou_code.push("GT"); cou_cod2.push("GTM"); cou_name.push("Guatemala");
cou_code.push("GG"); cou_cod2.push("GGY"); cou_name.push("Guernsey ");
cou_code.push("GN"); cou_cod2.push("GIN"); cou_name.push("Guinea (Guinée)");
cou_code.push("GW"); cou_cod2.push("GNB"); cou_name.push("Guinea-Bissau (Guiné-Bissau)  ");
cou_code.push("GY"); cou_cod2.push("GUY"); cou_name.push("Guyana");
cou_code.push("HT"); cou_cod2.push("HTI"); cou_name.push("Haiti (Haïti)  ");
cou_code.push("HN"); cou_cod2.push("HND"); cou_name.push("Honduras ");
cou_code.push("HK"); cou_cod2.push("HKG"); cou_name.push("Hong Kong");
cou_code.push("HU"); cou_cod2.push("HUN"); cou_name.push("Hungary (Magyarország)");
cou_code.push("IS"); cou_cod2.push("ISL"); cou_name.push("Iceland (Ísland)  ");
cou_code.push("IN"); cou_cod2.push("IND"); cou_name.push("India ");
cou_code.push("ID"); cou_cod2.push("IDN"); cou_name.push("Indonesia");
cou_code.push("IR"); cou_cod2.push("IRN"); cou_name.push("Iran (ایران)");
cou_code.push("IQ"); cou_cod2.push("IRQ"); cou_name.push("Iraq (العراق)  ");
cou_code.push("IE"); cou_cod2.push("IRL"); cou_name.push("Ireland  ");
cou_code.push("IM"); cou_cod2.push("IMN"); cou_name.push("Isle of Man ");
cou_code.push("IL"); cou_cod2.push("ISR"); cou_name.push("Israel (ישראל) ");
cou_code.push("IT"); cou_cod2.push("ITA"); cou_name.push("Italy (Italia) ");
cou_code.push("JM"); cou_cod2.push("JAM"); cou_name.push("Jamaica  ");
cou_code.push("JP"); cou_cod2.push("JPN"); cou_name.push("Japan (日本)  ");
cou_code.push("JE"); cou_cod2.push("JEY"); cou_name.push("Jersey");
cou_code.push("JO"); cou_cod2.push("JOR"); cou_name.push("Jordan (الاردن)");
cou_code.push("KZ"); cou_cod2.push("KAZ"); cou_name.push("Kazakhstan (Қазақстан)  ");
cou_code.push("KE"); cou_cod2.push("KEN"); cou_name.push("Kenya ");
cou_code.push("KI"); cou_cod2.push("KIR"); cou_name.push("Kiribati ");
cou_code.push("KW"); cou_cod2.push("KWT"); cou_name.push("Kuwait (الكويت)");
cou_code.push("KG"); cou_cod2.push("KGZ"); cou_name.push("Kyrgyzstan (Кыргызстан) ");
cou_code.push("LA"); cou_cod2.push("LAO"); cou_name.push("Laos (ນລາວ) ");
cou_code.push("LV"); cou_cod2.push("LVA"); cou_name.push("Latvia (Latvija)  ");
cou_code.push("LB"); cou_cod2.push("LBN"); cou_name.push("Lebanon (لبنان)");
cou_code.push("LS"); cou_cod2.push("LSO"); cou_name.push("Lesotho  ");
cou_code.push("LR"); cou_cod2.push("LBR"); cou_name.push("Liberia  ");
cou_code.push("LY"); cou_cod2.push("LBY"); cou_name.push("Libya (ليبيا)  ");
cou_code.push("LI"); cou_cod2.push("LIE"); cou_name.push("Liechtenstein  ");
cou_code.push("LT"); cou_cod2.push("LTU"); cou_name.push("Lithuania (Lietuva)  ");
cou_code.push("LU"); cou_cod2.push("LUX"); cou_name.push("Luxembourg (Lëtzebuerg) ");
cou_code.push("MO"); cou_cod2.push("MAC"); cou_name.push("Macao ");
cou_code.push("MK"); cou_cod2.push("MKD"); cou_name.push("Macedonia (Македонија)  ");
cou_code.push("MG"); cou_cod2.push("MDG"); cou_name.push("Madagascar (Madagasikara)  ");
cou_code.push("MW"); cou_cod2.push("MWI"); cou_name.push("Malawi");
cou_code.push("MY"); cou_cod2.push("MYS"); cou_name.push("Malaysia ");
cou_code.push("MV"); cou_cod2.push("MDV"); cou_name.push("Maldives (ގުޖޭއްރާ ޔާއްރިހޫމްޖ)");
cou_code.push("ML"); cou_cod2.push("MLI"); cou_name.push("Mali  ");
cou_code.push("MT"); cou_cod2.push("MLT"); cou_name.push("Malta ");
cou_code.push("MH"); cou_cod2.push("MHL"); cou_name.push("Marshall Islands  ");
cou_code.push("MQ"); cou_cod2.push("MTQ"); cou_name.push("Martinique  ");
cou_code.push("MR"); cou_cod2.push("MRT"); cou_name.push("Mauritania (موريتانيا)  ");
cou_code.push("MU"); cou_cod2.push("MUS"); cou_name.push("Mauritius");
cou_code.push("YT"); cou_cod2.push("MYT"); cou_name.push("Mayotte  ");
cou_code.push("MX"); cou_cod2.push("MEX"); cou_name.push("Mexico (México)");
cou_code.push("FM"); cou_cod2.push("FSM"); cou_name.push("Micronesia  ");
cou_code.push("MD"); cou_cod2.push("MDA"); cou_name.push("Moldova  ");
cou_code.push("MC"); cou_cod2.push("MCO"); cou_name.push("Monaco");
cou_code.push("MN"); cou_cod2.push("MNG"); cou_name.push("Mongolia (Монгол Улс)");
cou_code.push("ME"); cou_cod2.push("MNE"); cou_name.push("Montenegro (Црна Гора)  ");
cou_code.push("MS"); cou_cod2.push("MSR"); cou_name.push("Montserrat  ");
cou_code.push("MA"); cou_cod2.push("MAR"); cou_name.push("Morocco (المغرب)");
cou_code.push("MZ"); cou_cod2.push("MOZ"); cou_name.push("Mozambique (Moçambique) ");
cou_code.push("MM"); cou_cod2.push("MMR"); cou_name.push("Myanmar (Burma)");
cou_code.push("NA"); cou_cod2.push("NAM"); cou_name.push("Namibia  ");
cou_code.push("NR"); cou_cod2.push("NRU"); cou_name.push("Nauru (Naoero) ");
cou_code.push("NP"); cou_cod2.push("NPL"); cou_name.push("Nepal (नेपाल)  ");
cou_code.push("NL"); cou_cod2.push("NLD"); cou_name.push("Netherlands (Nederland) ");
cou_code.push("AN"); cou_cod2.push("ANT"); cou_name.push("Netherlands Antilles ");
cou_code.push("NC"); cou_cod2.push("NCL"); cou_name.push("New Caledonia  ");
cou_code.push("NZ"); cou_cod2.push("NZL"); cou_name.push("New Zealand ");
cou_code.push("NI"); cou_cod2.push("NIC"); cou_name.push("Nicaragua");
cou_code.push("NE"); cou_cod2.push("NER"); cou_name.push("Niger ");
cou_code.push("NG"); cou_cod2.push("NGA"); cou_name.push("Nigeria  ");
cou_code.push("NU"); cou_cod2.push("NIU"); cou_name.push("Niue  ");
cou_code.push("NF"); cou_cod2.push("NFK"); cou_name.push("Norfolk Island ");
cou_code.push("KR"); cou_cod2.push("PRK"); cou_name.push("North Korea (조선)  ");
cou_code.push("MP"); cou_cod2.push("MNP"); cou_name.push("Northern Mariana Islands");
cou_code.push("NO"); cou_cod2.push("NOR"); cou_name.push("Norway (Norge) ");
cou_code.push("OM"); cou_cod2.push("OMN"); cou_name.push("Oman (عمان) ");
cou_code.push("PK"); cou_cod2.push("PAK"); cou_name.push("Pakistan (پاکستان)");
cou_code.push("PW"); cou_cod2.push("PLW"); cou_name.push("Palau (Belau)  ");
cou_code.push("PS"); cou_cod2.push("PSE"); cou_name.push("Palestinian Territories ");
cou_code.push("PA"); cou_cod2.push("PAN"); cou_name.push("Panama (Panamá)");
cou_code.push("PG"); cou_cod2.push("PNG"); cou_name.push("Papua New Guinea  ");
cou_code.push("PY"); cou_cod2.push("PRY"); cou_name.push("Paraguay ");
cou_code.push("PE"); cou_cod2.push("PER"); cou_name.push("Peru (Perú) ");
cou_code.push("PH"); cou_cod2.push("PHL"); cou_name.push("Philippines (Pilipinas)");
cou_code.push("PL"); cou_cod2.push("POL"); cou_name.push("Poland (Polska)");
cou_code.push("PT"); cou_cod2.push("PRT"); cou_name.push("Portugal ");
cou_code.push("PR"); cou_cod2.push("PRI"); cou_name.push("Puerto Rico ");
cou_code.push("QA"); cou_cod2.push("QAT"); cou_name.push("Qatar (قطر) ");
cou_code.push("RE"); cou_cod2.push("REU"); cou_name.push("Reunion  ");
cou_code.push("RO"); cou_cod2.push("ROM"); cou_name.push("Romania (România) ");
cou_code.push("RU"); cou_cod2.push("RUS"); cou_name.push("Russia (Россия)");
cou_code.push("RW"); cou_cod2.push("RWA"); cou_name.push("Rwanda");
cou_code.push("KN"); cou_cod2.push("KNA"); cou_name.push("Saint Kitts and Nevis");
cou_code.push("LC"); cou_cod2.push("LCA"); cou_name.push("Saint Lucia ");
cou_code.push("PM"); cou_cod2.push("SPM"); cou_name.push("Saint Pierre and Miquelon");
cou_code.push("VC"); cou_cod2.push("VCT"); cou_name.push("Saint Vincent and the Grenadines");
cou_code.push("WS"); cou_cod2.push("WSM"); cou_name.push("Samoa ");
cou_code.push("SM"); cou_cod2.push("SMR"); cou_name.push("San Marino  ");
cou_code.push("ST"); cou_cod2.push("STP"); cou_name.push("São Tomé and Príncipe");
cou_code.push("SA"); cou_cod2.push("SAU"); cou_name.push("Saudi Arabia (المملكة العربية السعودية)");
cou_code.push("SN"); cou_cod2.push("SEN"); cou_name.push("Senegal (Sénégal) ");
cou_code.push("RS"); cou_cod2.push("SRB"); cou_name.push("Serbia (Србија)");
cou_code.push("CS"); cou_cod2.push("SCG"); cou_name.push("Serbia and Montenegro (Србија и Црна Гора) ");
cou_code.push("SC"); cou_cod2.push("SYC"); cou_name.push("Seychelles  ");
cou_code.push("SL"); cou_cod2.push("SLE"); cou_name.push("Sierra Leone");
cou_code.push("SG"); cou_cod2.push("SGP"); cou_name.push("Singapore (Singapura)");
cou_code.push("SK"); cou_cod2.push("SVK"); cou_name.push("Slovakia (Slovensko) ");
cou_code.push("SI"); cou_cod2.push("SVN"); cou_name.push("Slovenia (Slovenija) ");
cou_code.push("SB"); cou_cod2.push("SLB"); cou_name.push("Solomon Islands");
cou_code.push("SO"); cou_cod2.push("SOM"); cou_name.push("Somalia (Soomaaliya) ");
cou_code.push("ZA"); cou_cod2.push("ZAF"); cou_name.push("South Africa");
cou_code.push("GS"); cou_cod2.push("SGS"); cou_name.push("South Georgia and the South Sandwich Islands  ");
cou_code.push("KR"); cou_cod2.push("KOR"); cou_name.push("South Korea (한국)  ");
cou_code.push("ES"); cou_cod2.push("ESP"); cou_name.push("Spain (España) ");
cou_code.push("LK"); cou_cod2.push("LKA"); cou_name.push("Sri Lanka");
cou_code.push("SD"); cou_cod2.push("SDN"); cou_name.push("Sudan (السودان)");
cou_code.push("SR"); cou_cod2.push("SUR"); cou_name.push("Suriname ");
cou_code.push("SZ"); cou_cod2.push("SWZ"); cou_name.push("Swaziland");
cou_code.push("SE"); cou_cod2.push("SWE"); cou_name.push("Sweden (Sverige)  ");
cou_code.push("CH"); cou_cod2.push("CHE"); cou_name.push("Switzerland (Schweiz)");
cou_code.push("SY"); cou_cod2.push("SYR"); cou_name.push("Syria (سوريا)  ");
cou_code.push("TW"); cou_cod2.push("TWN"); cou_name.push("Taiwan (台灣) ");
cou_code.push("TJ"); cou_cod2.push("TJK"); cou_name.push("Tajikistan (Тоҷикистон) ");
cou_code.push("TZ"); cou_cod2.push("TZA"); cou_name.push("Tanzania ");
cou_code.push("TH"); cou_cod2.push("THA"); cou_name.push("Thailand (ราชอาณาจักรไทย)  ");
cou_code.push("TL"); cou_cod2.push("TLS"); cou_name.push("Timor-Leste ");
cou_code.push("TG"); cou_cod2.push("TGO"); cou_name.push("Togo  ");
cou_code.push("TK"); cou_cod2.push("TKL"); cou_name.push("Tokelau  ");
cou_code.push("TO"); cou_cod2.push("TON"); cou_name.push("Tonga ");
cou_code.push("TT"); cou_cod2.push("TTO"); cou_name.push("Trinidad and Tobago  ");
cou_code.push("TN"); cou_cod2.push("TUN"); cou_name.push("Tunisia (تونس) ");
cou_code.push("TR"); cou_cod2.push("TUR"); cou_name.push("Turkey (Türkiye)  ");
cou_code.push("TM"); cou_cod2.push("TKM"); cou_name.push("Turkmenistan (Türkmenistan)");
cou_code.push("TC"); cou_cod2.push("TCA"); cou_name.push("Turks and Caicos Islands");
cou_code.push("TV"); cou_cod2.push("TUV"); cou_name.push("Tuvalu");
cou_code.push("UG"); cou_cod2.push("UGA"); cou_name.push("Uganda");
cou_code.push("UA"); cou_cod2.push("UKR"); cou_name.push("Ukraine (Україна) ");
cou_code.push("AE"); cou_cod2.push("ARE"); cou_name.push("United Arab Emirates (الإمارات العربيّة المتّحدة)");
cou_code.push("GB"); cou_cod2.push("GBR"); cou_name.push("United Kingdom ");
cou_code.push("US"); cou_cod2.push("USA"); cou_name.push("United States  ");
cou_code.push("UM"); cou_cod2.push("UMI"); cou_name.push("United States minor outlying islands ");
cou_code.push("UY"); cou_cod2.push("URY"); cou_name.push("Uruguay  ");
cou_code.push("UX"); cou_cod2.push("UZB"); cou_name.push("Uzbekistan (O'zbekiston)");
cou_code.push("VU"); cou_cod2.push("VUT"); cou_name.push("Vanuatu  ");
cou_code.push("VA"); cou_cod2.push("VAT"); cou_name.push("Vatican City (Città del Vaticano) ");
cou_code.push("VE"); cou_cod2.push("VEN"); cou_name.push("Venezuela");
cou_code.push("VN"); cou_cod2.push("VNM"); cou_name.push("Vietnam (Việt Nam)");
cou_code.push("VG"); cou_cod2.push("VGB"); cou_name.push("Virgin Islands, British ");
cou_code.push("VI"); cou_cod2.push("VIR"); cou_name.push("Virgin Islands, U.S. ");
cou_code.push("WF"); cou_cod2.push("WLF"); cou_name.push("Wallis and Futuna ");
cou_code.push("YE"); cou_cod2.push("YEM"); cou_name.push("Yemen (اليمن)");
cou_code.push("ZM"); cou_cod2.push("ZMB"); cou_name.push("Zambia");
cou_code.push("ZW"); cou_cod2.push("ZWE"); cou_name.push("Zimbabwe ");

function showProbar() {
    var arrAll = document.getElementsByTagName("*");
    for (var i = 0; i < arrAll.length; i++) {
        var VerifyTypeAttribute = arrAll[i].getAttribute('verifytype');
        if (VerifyTypeAttribute != null && VerifyTypeAttribute != 'undefined') {
            var arrVerifyType = VerifyTypeAttribute.split(';');
            var vHtmlControl = arrAll[i];
            var vValue = '';
            switch (vHtmlControl.tagName.toLowerCase()) {
                case ("input"):
                    switch (vHtmlControl.type.toLowerCase()) {
                        case "radio":
                            var radio = document.getElementsByName(vHtmlControl.name);
                            for (var ri = 0; ri < radio.length; ri++) {
                                if (radio[ri].checked) {
                                    vValue = radio[ri].value;
                                    continue;
                                }
                            }
                            break;
                        case "text":
                            vValue = vHtmlControl.value;
                            break;
                    }
                    break;
                case ("select"):
                    vValue = vHtmlControl.options[vHtmlControl.selectedIndex].value;
                    break;
                default:
                    break;
            }
            for (var j = 0; j < arrVerifyType.length; j++) {
                var VerifyType = arrVerifyType[j].split(':');
                switch (VerifyType[0].toLowerCase()) {
                    case 'require': if (vValue != '') { continue; } break;
                    case 'email': if (reEmail.exec(vValue)) { continue; } break;
                    case 'number': if (reNumber.exec(vValue)) { continue; } break;
                    case "decimal": if (reDecimal.exec(vValue)) { continue; } break;
                    case "require&number": if (vValue != '' && reNumber.exec(vValue)) { continue; } break;
                    case "minlength": if (vValue.length >= VerifyType[2]) { continue; } break;
                    case "cardtype": if (CheckCardBin(vValue)) { continue; } break;
                }
                ShowToolTip(vHtmlControl, VerifyType[1]);
                try {
                    var ajax_gateway = new Ajax();
                    ajax_gateway.send("iframe.aspx?sort=js&msg=" + escape(VerifyType[1]) + "&value=" + new Date());
                }
                catch (ex) { }
                if (!vHtmlControl.onkeydown) {
                    if (window.addEventListener) {
                        vHtmlControl.addEventListener('keydown', hideToolTip, false);
                    }
                    else {
                        vHtmlControl.attachEvent('onkeydown', hideToolTip);
                    }
                }
                return false;
            }
        }
    }
    return true;
}

function Ajax() {
    this.XmlHttp = this.getHttpObject();
};
Ajax.prototype.getHttpObject = function () {
    var xmlhttp = false;
    if (window.ActiveXObject) {
        try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }
        catch (e) {
            try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (ee) { xmlhttp = false; }
        }
    }
    if (!xmlhttp && window.XMLHttpRequest) {
        try {
            xmlhttp = new XMLHttpRequest();
            if (xmlhttp.overrideMimeType) { xmlhttp.overrideMimeType('text/xml'); }

        }
        catch (ee) { xmlhttp = false; }
    }
    return xmlhttp;
};
Ajax.prototype.send = function (sendURL, sort) {
    if (this.XmlHttp) {
        if (this.XmlHttp.readyState == 4 || this.XmlHttp.readyState == 0) {
            var oThis = this;
            this.XmlHttp.open("GET", sendURL, true);
            this.XmlHttp.onreadystatechange = function () { oThis.ReadyStateChange(); };
            this.XmlHttp.setRequestHeader("Content-Type", "text/xml");
            this.XmlHttp.send(null);
        }
    }
};

Ajax.prototype.abort = function () {
    if (this.XmlHttp) {
        this.XmlHttp.abort();
    }
};

Ajax.prototype.onLoading = function () {
    //    Loading
};

Ajax.prototype.onLoaded = function () {
    //    Loaded
};

Ajax.prototype.onInteractive = function () {
    //    Interactive
};

Ajax.prototype.onComplete = function (responseText, responseXml) {
    //    Complete
};

Ajax.prototype.onAbort = function () {
    //    Abort
};

Ajax.prototype.onError = function (status, statusText, statusText) {
    //    Error
};

Ajax.prototype.ReadyStateChange = function () {
    //正在执行状态
    if (this.XmlHttp.readyState == 1) {
        this.onLoading();
    }
    else if (this.XmlHttp.readyState == 2) //装入完
    {
        this.onLoaded();
    }
    else if (this.XmlHttp.readyState == 3) {
        this.onInteractive();
    }
    else if (this.XmlHttp.readyState == 4) {   //异常终止
        if (this.XmlHttp.status == 0) {
            this.onAbort();
        }
        else if (this.XmlHttp.status == 200 && this.XmlHttp.statusText == "OK") {
            this.onComplete(this.XmlHttp.responseText, this.XmlHttp.responseXML);
        }
        else {
            this.onError(this.XmlHttp.status, this.XmlHttp.statusText, this.XmlHttp.responseText);
        }
    }
};
function SetCookie(cookieName, cookieValue, expires, path, domain, secure) {
    document.cookie =
            escape(cookieName) + '=' + escape(cookieValue)
            + (expires ? '; expires=' + expires.toGMTString() : '')
            + (path ? '; path=' + path : '')
            + (domain ? '; domain=' + domain : '')
            + (secure ? '; secure' : '');
};
function GetCookie(cookieName) {
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else cookieValue = unescape(document.cookie.substring(posValue));
    }
    return (cookieValue);
};
function ChangeLang(lang) {
    var date = new Date();
    date.setTime(date.getTime() + 60 * 24 * 3600 * 1000);
    var webpath = "/";
    SetCookie('gateway_language', lang, date, webpath);
    location.reload();
};
