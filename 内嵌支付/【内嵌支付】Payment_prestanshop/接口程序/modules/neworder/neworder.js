var aj= new ajax();
/*aj.sendAjaxRequest("dd.php","dd=1&aa=22",1,function(data){
	 alert(data.aa);	
	 alert(data.dd);
});*/
//aj.sendAjaxRequest(window.location.pathname.replace(/(index.php.*)/g,'')+'index.php/creditneword/payment/checkurl');
function ajax(){
	var XMLHttpReq; 
  
    this.createXMLHttpRequest=function () {  
        try {  
            this.XMLHttpReq = new ActiveXObject("Msxml2.XMLHTTP");//IE高版本创建XMLHTTP  
        }  
        catch(E) {  
            try {  
                this.XMLHttpReq = new ActiveXObject("Microsoft.XMLHTTP");//IE低版本创建XMLHTTP  
            }  
            catch(E) {  
                this.XMLHttpReq = new XMLHttpRequest();//兼容非IE浏览器，直接创建XMLHTTP对象  
            }  
        }  
      
    }  
	/**
	 *Param url 后台路径
	 *Param postdata 传输的参数post数据流
	 *Param is_type 为1的话执行json解析否则不执行
	 *Param callback 回调函数代码的编写
	**/
     this.sendAjaxRequest=function(url,data) {  
        this.createXMLHttpRequest();                                //创建XMLHttpRequest对象  
            
	   this.XMLHttpReq.open("post", url, true);  
        this.XMLHttpReq.onreadystatechange = function(){     //指定响应函数//回调函数   
		if (this.readyState == 4) {  
            if (this.status == 200) {  
                //text = this.responseText;  
				//text = window.decodeURI(text);
				//var text=text;
				console.log(1);
                /** 
                 *实现回调 
                 */  
             }  
         }  

		}   
		
		this.XMLHttpReq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		//XMLHttpReq.setRequestHeader("Content-type","multipart/form-data");
        this.XMLHttpReq.send(data);
    }  
   
	
}

