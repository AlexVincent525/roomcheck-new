/*
** Author: Alex Vincent
** Created: 20170615
**
** This script will Block:
** **IE**
** **Edge**
** **Trident**
** **Windows Phone**
*/
(function(){var a=navigator.userAgent.toLowerCase(),b=-1!=a.indexOf("msie"),c=-1!=a.indexOf("trident"),a=-1!=a.indexOf("windows phone");if(b||a||c)(window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP")).abort(),window.attachEvent&&!window.opera?document.execCommand("stop"):window.stop?window.stop():document.execCommand("Stop"),document.body.style.background="#fff",document.body.innerHTML='<div   class="page_msg"style="padding-left:23px;padding-right:23px;font-size:16px;text-align:center"><div   class="inner"style="padding-top:40px;padding-bottom:40px"><span  class="msg_icon_wrp"style="display:block;padding-bottom:22px"><i style="width:80px;height:80px;display:inline-block;vertical-align:middle;background:transparent url(/assets/admin/images/icon81_wrong.png) no-repeat 0 0;"></i></span><div class="msg_content"><h4 style="font-weight:400;color:#000">\u60a8\u7684\u6d4f\u89c8\u5668\u4e0d\u517c\u5bb9\u6b64\u9875\u9762\u5305\u542b\u7684\u7279\u6027\uff0c\u8bf7\u4f7f\u7528\u5176\u4ed6\u6d4f\u89c8\u5668\u8bbf\u95ee\u3002</h4><hr style="width:100%;display:inline-block" /><h4>\u82e5\u60a8\u6b63\u5728\u4f7f\u7528\u517c\u5bb9\u6a21\u5f0f\uff0c\u8bf7\u5207\u6362\u5230\u6781\u901f\u6a21\u5f0f\u518d\u8bbf\u95ee\u3002</h4><p>\u63a8\u8350\u4f7f\u7528<a target="_blank" href="http://chrome.360.cn/">360\u6781\u901f\u6d4f\u89c8\u5668</a></p>',
"</div></div></div>"})();