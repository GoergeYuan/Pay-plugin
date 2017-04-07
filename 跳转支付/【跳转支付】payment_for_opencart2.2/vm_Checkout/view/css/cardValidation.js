/*******************************
**验证visa 或者master 卡的规则
**参数cdi：需要验证的卡号
**eg：chkCardNum('4032 9835 3025 2122') 返回true or false
*******************************/
function chkCardNum(cdi) {
    if (cdi != "" && cdi != null) {
        var cf = sbtString(cdi, " -/abcdefghijklmnopqrstuvwyzABCDEFGHIJLMNOPQRSTUVWYZ|\#()[]{}?%&=!?+*.,;:'");
        var cn = ""; //验证卡的类型 现在初始值为“”
        var clcd = chkLCD(cf);
        var ccck = chkCCCksum(cf, cn);
        var cjd = "INVALID CARD NUMBER"; if (clcd && ccck) { cjd = "This card number appears to be valid."; }
        if (clcd && ccck) {
            return true;
        }
        else {
            return false;
        }
    }
}
//检测卡号的总和是否符合规则
function chkCCCksum(cf, cn) {
    var r = false;
    var w = "21";
    var ml = "";
    var j = 1;
    for (var i = 1; i <= cf.length - 1; i++) {
        var m = midS(cf, i, 1) * midS(w, j, 1);
        m = sumDigits(m);
        ml += "" + m;
        j++; if (j > w.length) { j = 1; }
    }
    var ml2 = sumDigits(ml, -1);
    var ml1 = (sumDigits(ml2, -1) * 10 - ml2) % 10;
    if (ml1 == rightS(cf, 1)) { r = true; }
    return r;
}
//验证卡号的规则(获取卡号总和)
function chkLCD(cf) {
    var r = false; cf += "";
    var bl = isdiv(cf.length, 2);
    var ctd = 0;
    for (var i = 1; i <= cf.length; i++) {
        var cdg = midS(cf, i, 1);
        if (isdiv(i, 2) != bl) { //如果获取整除商 和小于商的最大整数 不相等
            cdg *= 2; if (cdg > 9) { cdg -= 9; }
        }
        ctd += cdg * 1.0;
    }
    if (isdiv(ctd, 10)) { r = true; }
    return r;
}
//获取卡号最后一位
function rightS(aS, n) {
    aS += "";
    var rS = "";
    if (n >= 1) {
        rS = aS.substring(aS.length - n, aS.length);
    }
    return rS;
}
//截取卡号的一位数字
function midS(aS, n, n2) {
    aS += "";
    var rS = "";
    if (n2 == null || n2 == "") { n2 = aS.length; }
    n *= 1; n2 *= 1;
    if (n < 0) { n++; }
    rS = aS.substring(n - 1, n - 1 + n2);
    return rS;
}
//重新生成正确的卡号(去掉里面脏字符)
function sbtString(s1, s2) {
    var ous = ""; s1 += ""; s2 += "";
    for (var i = 1; i <= s1.length; i++) {
        var c1 = s1.substring(i - 1, i);
        var c2 = s2.indexOf(c1);
        if (c2 == -1) { ous += c1; }
    }
    return ous;
}

//整除商 和小于商的最大整数是否相等
function isdiv(a, b) {
    if (b == null) { b = 2; }
    a *= 1.0; b *= 1.0;
    var r = false;
    if (a / b == Math.floor(a / b)) { r = true; }
    return r;
}
//计算对于位的总和
function sumDigits(n, m) {
    if (m == 0 || m == null) { m = 1; }
    n += "";
    if (m > 0) {
        while (n.length > m) {
            var r = 0;
            for (var i = 1; i <= n.length; i++) {
                r += 1.0 * midS(n, i, 1);
            }
            n = "" + r;
        }
    } else {
        for (var j = 1; j <= Math.abs(m); j++) {
            var r = 0;
            for (var i = 1; i <= n.length; i++) {
                r += 1.0 * midS(n, i, 1);
            }
            n = "" + r;
        }
    }
    r = n;
    return r;
}