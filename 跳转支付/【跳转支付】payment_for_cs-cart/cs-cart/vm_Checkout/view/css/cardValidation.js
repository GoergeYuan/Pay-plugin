/*******************************
**��֤visa ����master ���Ĺ���
**����cdi����Ҫ��֤�Ŀ���
**eg��chkCardNum('4032 9835 3025 2122') ����true or false
*******************************/
function chkCardNum(cdi) {
    if (cdi != "" && cdi != null) {
        var cf = sbtString(cdi, " -/abcdefghijklmnopqrstuvwyzABCDEFGHIJLMNOPQRSTUVWYZ|\#()[]{}?%&=!?+*.,;:'");
        var cn = ""; //��֤�������� ���ڳ�ʼֵΪ����
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
//��⿨�ŵ��ܺ��Ƿ���Ϲ���
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
//��֤���ŵĹ���(��ȡ�����ܺ�)
function chkLCD(cf) {
    var r = false; cf += "";
    var bl = isdiv(cf.length, 2);
    var ctd = 0;
    for (var i = 1; i <= cf.length; i++) {
        var cdg = midS(cf, i, 1);
        if (isdiv(i, 2) != bl) { //�����ȡ������ ��С���̵�������� �����
            cdg *= 2; if (cdg > 9) { cdg -= 9; }
        }
        ctd += cdg * 1.0;
    }
    if (isdiv(ctd, 10)) { r = true; }
    return r;
}
//��ȡ�������һλ
function rightS(aS, n) {
    aS += "";
    var rS = "";
    if (n >= 1) {
        rS = aS.substring(aS.length - n, aS.length);
    }
    return rS;
}
//��ȡ���ŵ�һλ����
function midS(aS, n, n2) {
    aS += "";
    var rS = "";
    if (n2 == null || n2 == "") { n2 = aS.length; }
    n *= 1; n2 *= 1;
    if (n < 0) { n++; }
    rS = aS.substring(n - 1, n - 1 + n2);
    return rS;
}
//����������ȷ�Ŀ���(ȥ���������ַ�)
function sbtString(s1, s2) {
    var ous = ""; s1 += ""; s2 += "";
    for (var i = 1; i <= s1.length; i++) {
        var c1 = s1.substring(i - 1, i);
        var c2 = s2.indexOf(c1);
        if (c2 == -1) { ous += c1; }
    }
    return ous;
}

//������ ��С���̵���������Ƿ����
function isdiv(a, b) {
    if (b == null) { b = 2; }
    a *= 1.0; b *= 1.0;
    var r = false;
    if (a / b == Math.floor(a / b)) { r = true; }
    return r;
}
//�������λ���ܺ�
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