// OWA Tracker Min file created 1257743541 

//// Start of json2 //// 

if(!this.JSON){JSON=function(){function f(n){return n<10?'0'+n:n;}
Date.prototype.toJSON=function(){return this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z';};var escapeable=/["\\\x00-\x1f\x7f-\x9f]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){return escapeable.test(string)?'"'+string.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c;}
c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+
(c%16).toString(16);})+'"':'"'+string+'"';}
function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
if(typeof rep==='function'){value=rep.call(holder,key,value);}
switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
gap+=indent;partial=[];if(typeof value.length==='number'&&!(value.propertyIsEnumerable('length'))){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}
v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v;}
if(typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}
v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}
return{stringify:function(value,replacer,space){var i;gap='';indent='';if(space){if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}}
if(!replacer){rep=function(key,value){if(!Object.hasOwnProperty.call(this,key)){return undefined;}
return value;};}else if(typeof replacer==='function'||(typeof replacer==='object'&&typeof replacer.length==='number')){rep=replacer;}else{throw new Error('JSON.stringify');}
return str('',{'':value});},parse:function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
return reviver.call(holder,key,value);}
if(/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
throw new SyntaxError('JSON.parse');},quote:quote};}();}
//// End of json2 //// 
//// Start of lazyload //// 

LazyLoad=function(){var f=document,g,b={},e={css:[],js:[]},a;function j(l,k){var m=f.createElement(l),d;for(d in k){if(k.hasOwnProperty(d)){m.setAttribute(d,k[d])}}return m}function h(d){var l=b[d];if(!l){return}var m=l.callback,k=l.urls;k.shift();if(!k.length){if(m){m.call(l.scope||window,l.obj)}b[d]=null;if(e[d].length){i(d)}}}function c(){if(a){return}var k=navigator.userAgent,l=parseFloat,d;a={gecko:0,ie:0,opera:0,webkit:0};d=k.match(/AppleWebKit\/(\S*)/);if(d&&d[1]){a.webkit=l(d[1])}else{d=k.match(/MSIE\s([^;]*)/);if(d&&d[1]){a.ie=l(d[1])}else{if((/Gecko\/(\S*)/).test(k)){a.gecko=1;d=k.match(/rv:([^\s\)]*)/);if(d&&d[1]){a.gecko=l(d[1])}}else{if(d=k.match(/Opera\/(\S*)/)){a.opera=l(d[1])}}}}}function i(r,q,s,m,t){var n,o,l,k,d;c();if(q){q=q.constructor===Array?q:[q];if(r==="css"||a.gecko||a.opera){e[r].push({urls:[].concat(q),callback:s,obj:m,scope:t})}else{for(n=0,o=q.length;n<o;++n){e[r].push({urls:[q[n]],callback:n===o-1?s:null,obj:m,scope:t})}}}if(b[r]||!(k=b[r]=e[r].shift())){return}g=g||f.getElementsByTagName("head")[0];q=k.urls;for(n=0,o=q.length;n<o;++n){d=q[n];if(r==="css"){l=j("link",{href:d,rel:"stylesheet",type:"text/css"})}else{l=j("script",{src:d})}if(a.ie){l.onreadystatechange=function(){var p=this.readyState;if(p==="loaded"||p==="complete"){this.onreadystatechange=null;h(r)}}}else{if(r==="css"&&(a.gecko||a.webkit)){setTimeout(function(){h(r)},50*o)}else{l.onload=l.onerror=function(){h(r)}}}g.appendChild(l)}}return{css:function(l,m,k,d){i("css",l,m,k,d)},js:function(l,m,k,d){i("js",l,m,k,d)}}}();
//// End of lazyload //// 
//// Start of owa //// 

var OWA={items:new Object,config:new Object,setSetting:function(name,value){this.config[name]=value;},getSetting:function(name){return this.config[name];},debug:function(){var debugging=OWA.getSetting('debug')||false;if(debugging){if(window.console&&window.console.firebug){console.log.apply(this,arguments);}}}};OWA.util={ns:function(string){return OWA.config.ns+string;},nsAll:function(obj){var nsObj=new Object();for(param in obj){nsObj[OWA.config.ns+param]=obj[param];}
return nsObj;},getScript:function(file,path){jQuery.getScript(path+file);return;},makeUrl:function(template,uri,params){var url=jQuery.sprintf(template,uri,jQuery.param(OWA.util.nsAll(params)));return url;},createCookie:function(name,value,days){if(days){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));var expires="; expires="+date.toGMTString();}
else var expires="";document.cookie=name+"="+value+expires+"; path=/";},dt_setcookie:function(name,value,expirydays){var expiry=new Date();expiry.setDate(expiry.getDate()+expirydays);document.cookie=name+"="+escape(value)+";expires="+expiry.toGMTString();console.log(document.cookie);return document.cookie;},setCookie2:function(name,value,days,path,domain,secure){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));document.cookie=name+"="+escape(value)+
((days)?"; expires="+date.toGMTString():"")+
((path)?"; path="+path:"")+
((domain)?"; domain="+domain:"")+
((secure)?"; secure":"");},readCookie:function(name){var nameEQ=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length);}
return null;},eraseCookie:function(name){var domain=OWA.getSetting('cookie_domain')||document.domain;this.setCookie2(name,"",-1,"",domain);var test=this.readCookie(name);if(test){domain="."+domain;console.log(domain);this.setCookie2(name,"",-1,"",domain);}},loadScript:function(url,callback){return LazyLoad.js(url,callback);},loadCss:function(url,callback){return LazyLoad.css(url,callback);},parseCookieString:function parseQuery(v){var queryAsAssoc=new Array();var queryString=unescape(v);var keyValues=queryString.split("|||");for(var i in keyValues){var key=keyValues[i].split("=>");queryAsAssoc[key[0]]=key[1];}
return queryAsAssoc;},parseCookieStringToJson:function parseQuery(v){var queryAsObj=new Object;var queryString=unescape(v);var keyValues=queryString.split("|||");for(var i in keyValues){var key=keyValues[i].split("=>");queryAsObj[key[0]]=key[1];}
return queryAsObj;},nsParams:function(obj){var new_obj=new Object;for(param in obj){new_obj['owa_'+param]=obj[param];}
return new_obj;}}
//// End of owa //// 
//// Start of owa.tracker //// 

OWA.event=function(){this.properties=new Object();}
OWA.event.prototype={id:'',siteId:'',properties:'',get:function(name){return this.properties[name];},set:function(name,value){this.properties[name]=value;return;},setEventType:function(event_type){this.set("event_type",event_type);return;},getProperties:function(){return this.properties;},merge:function(properties){for(param in properties){this.set(param,properties[param]);}}}
OWA.tracker=function(caller_params){this.setEndpoint(OWA.config.baseUrl+'log.php');this.page=new OWA.event();this.startTime=this.getTimestamp();this.page.set('page_url',document.URL);this.setPageTitle(document.title);this.page.set("referer",document.referrer);if(typeof caller_params!='undefined'){this.page.merge(caller_params);}
var p=OWA.util.readCookie('owa_overlay');if(p){this.loadHeatmap();}}
OWA.tracker.prototype={id:'',siteId:'',init:0,startTime:null,endTime:null,active:true,endpoint:'',options:{logClicks:true,logPage:true,logMovement:false,encodeProperties:true,movementInterval:100,logDomStreamPercentage:50},streamBindings:['bindMovementEvents','bindScrollEvents','bindKeypressEvents','bindClickEvents'],page:'',click:'',movement:'',keystroke:'',hover:'',last_event:'',last_movement:'',event_queue:new Array(),player:'',overlay:'',setPageTitle:function(title){this.page.set("page_title",title);},setPageType:function(type){this.page.set("page_type",type);},setSiteId:function(site_id){this.siteId=site_id;},getSiteId:function(){return this.siteId;},setEndpoint:function(endpoint){this.endpoint=endpoint;},getEndpoint:function(){return this.endpoint;},trackPageView:function(){this.page.setEventType("base.page_request");return this.logEvent(this.page.getProperties());},logDomStream:function(){var event=new OWA.event;event.setEventType('dom.stream');event.set('site_id',this.getSiteId());event.set('page_url',this.page.get('page_url'));event.set('timestamp',this.startTime);event.set('duration',this.getElapsedTime());event.set('stream_events',JSON.stringify(this.event_queue));this.logEventAjax(event,'POST');},log:function(){this.page.setEventType("base.page_request");return this.logEvent(this.page);},logEventAjax:function(event,method){if(this.active){if(event instanceof OWA.event){var properties=event.getProperties();}else{var properties=event;}
method=method||'GET';if(method==='GET'){return this.ajaxGet(properties);}else{this.ajaxPost(properties);return;}}},isObjectType:function(obj,type){return!!(obj&&type&&type.prototype&&obj.constructor==type.prototype.constructor);},getAjaxObj:function(){if(window.XMLHttpRequest){var ajax=new XMLHttpRequest()}else{if(window.ActiveXObject){var ajax=new ActiveXObject("Microsoft.XMLHTTP");}}
return ajax;},ajaxGet:function(properties){var url=this._assembleRequestUrl(properties);var ajax=this.getAjaxObj();ajax.open("GET",url,false);ajax.send(null);},ajaxPost:function(properties){var ajax=this.getAjaxObj();var params=this.prepareRequestParams(properties);ajax.open("POST",this.getEndpoint(),false);ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");ajax.setRequestHeader("Content-length",params.length);ajax.setRequestHeader("Connection","close");ajax.onreadystatechange=function(){if(ajax.readyState==4&&ajax.status==200){}}
ajax.send(params);},prepareRequestParams:function(properties){var get='';properties.site_id=this.getSiteId();for(param in properties){value='';if(typeof properties[param]!='undefined'){if(this.getOption('encodeProperties')){value=this._base64_encode(properties[param]+'');}else{value=properties[param]+'';}}else{value='';}
get+="owa_"+param+"="+value+"&";}
return get;},logEvent:function(properties){var bug
var url
url=this._assembleRequestUrl(properties);bug="<img src=\""+url+"\" height=\"1\" width=\"1\">";document.write(bug);return;},_assembleRequestUrl:function(properties){properties.site_id=this.getSiteId();var get=this.prepareRequestParams(properties);var log_url=this.getEndpoint();if(log_url.indexOf('?')===-1){log_url+='?';}else{log_url+='&';}
return log_url+get;},_base64_encode:function(decStr){var base64s='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';var bits;var dual;var i=0;var encOut='';while(decStr.length>=i+3){bits=(decStr.charCodeAt(i++)&0xff)<<16|(decStr.charCodeAt(i++)&0xff)<<8|decStr.charCodeAt(i++)&0xff;encOut+=base64s.charAt((bits&0x00fc0000)>>18)+
base64s.charAt((bits&0x0003f000)>>12)+
base64s.charAt((bits&0x00000fc0)>>6)+
base64s.charAt((bits&0x0000003f));}
if(decStr.length-i>0&&decStr.length-i<3){dual=Boolean(decStr.length-i-1);bits=((decStr.charCodeAt(i++)&0xff)<<16)|(dual?(decStr.charCodeAt(i)&0xff)<<8:0);encOut+=base64s.charAt((bits&0x00fc0000)>>18)+
base64s.charAt((bits&0x0003f000)>>12)+
(dual?base64s.charAt((bits&0x00000fc0)>>6):'=')+'=';}
return(encOut);},getViewportDimensions:function(){var viewport=new Object();viewport.width=window.innerWidth?window.innerWidth:document.body.offsetWidth;viewport.height=window.innerHeight?window.innerHeight:document.body.offsetHeight;return viewport;},findPosX:function(obj){var curleft=0;if(obj.offsetParent)
{while(obj.offsetParent)
{curleft+=obj.offsetLeft
obj=obj.offsetParent;}}
else if(obj.x)
curleft+=obj.x;return curleft;},findPosY:function(obj){var curtop=0;if(obj.offsetParent)
{while(obj.offsetParent)
{curtop+=obj.offsetTop
obj=obj.offsetParent;}}
else if(obj.y)
curtop+=obj.y;return curtop;},_getTarget:function(e){targ=e.target||e.srcElement;if(targ.nodeType==3){targ=target.parentNode;}
return targ;},getCoords:function(e){var coords=new Object();if(typeof(e.pageX)=='number'){coords.x=e.pageX+'';coords.y=e.pageY+'';}else{coords.x=e.clientX+'';coords.y=e.clientY+'';}
return coords;},getDomElementProperties:function(targ){var properties=new Object();properties.dom_element_tag=targ.tagName;if(targ.tagName=="A"){if(targ.textContent!=undefined){properties.dom_element_text=targ.textContent;}else{properties.dom_element_text=targ.innerText;}
properties.target_url=targ.href;}
else if(targ.tagName=="INPUT"){properties.dom_element_text=targ.value;}
else if(targ.tagName=="IMG"){properties.target_url=targ.parentNode.href;properties.dom_element_text=targ.alt;}
else{if(targ.textContent!=undefined){properties.html_element_text=targ.textContent;}else{properties.html_element_text=targ.innerText;}}
return properties;},bindClickEvents:function(){var that=this;document.onclick=function(e){that.clickEventHandler(e);}},clickEventHandler:function(e){e=e||window.event;var click=new OWA.event();click.setEventType("dom.click");var targ=this._getTarget(e);click.set("dom_element_name",targ.name);click.set("dom_element_value",targ.value);click.set("dom_element_id",targ.id);click.set("dom_element_tag",targ.tagName);click.set("dom_element_class",targ.className);click.set("page_url",window.location.href);var viewport=this.getViewportDimensions();click.set("page_width",viewport.width);click.set("page_height",viewport.height);click.merge(this.getDomElementProperties(targ));click.set("dom_element_x",this.findPosX(targ)+'');click.set("dom_element_y",this.findPosY(targ)+'');var coords=this.getCoords(e);click.set('click_x',coords.x);click.set('click_y',coords.y);if(this.getOption('logClicksAsTheyHappen')){this.logEventAjax(click);}
if(this.getOption('trackDomStream')){this.addToEventQueue(click)}
this.click=click;return;},registerBeforeNavigateEvent:function(){var that=this;if(window.addEventListener){window.addEventListener('beforeunload',function(e){that.logDomStream(e);},false);}else if(window.attachEvent){window.attachEvent('beforeunload',function(e){that.logDomStream(e);});}},callMethod:function(string,data){return this[string](data);},addDomStreamEventBinding:function(method_name){this.streamBindings.push(method_name);},trackClicks:function(handler){this.setOption('logClicksAsTheyHappen',true);this.bindClickEvents();},trackDomStream:function(){var rand=Math.floor(Math.random()*100+1);if(rand<=this.getOption('logDomStreamPercentage')){this.setOption('trackDomStream',true);for(method in this.streamBindings){this.callMethod(this.streamBindings[method]);}
this.registerBeforeNavigateEvent();}else{OWA.debug("not tracking dom stream for this user %d.",50);}},bindMovementEvents:function(){var that=this;document.onmousemove=function(e){that.movementEventHandler(e);}},movementEventHandler:function(e){e=e||window.event;var now=this.getTime();if(now>this.last_movement+this.getOption('movementInterval')){this.movement=new OWA.event();this.movement.setEventType("dom.movement");var coords=this.getCoords(e);this.movement.set('cursor_x',coords.x);this.movement.set('cursor_y',coords.y);this.addToEventQueue(this.movement);this.last_movement=now;}},bindScrollEvents:function(){var that=this;window.onscroll=function(e){that.scrollEventHandler(e);}},scrollEventHandler:function(e){e=e||window.event;var now=this.getTimestamp();var event=new OWA.event();event.setEventType('dom.scroll');var coords=this.getScrollingPosition();event.set('x',coords.x);event.set('y',coords.y);var targ=this._getTarget(e);event.set("dom_element_name",targ.name);event.set("dom_element_value",targ.value);event.set("dom_element_id",targ.id);this.addToEventQueue(event);this.last_scroll=now;},getScrollingPosition:function(){var position=[0,0];if(typeof window.pageYOffset!='undefined'){position={x:window.pageXOffset,y:window.pageYOffset};}else if(typeof document.documentElement.scrollTop!='undefined'&&document.documentElement.scrollTop>0){position={x:document.documentElement.scrollLeft,y:document.documentElement.scrollTop};}else if(typeof document.body.scrollTop!='undefined'){position={x:document.body.scrollLeft,y:document.body.scrollTop};}
return position;},bindHoverEvents:function(){},bindKeypressEvents:function(){var that=this;document.onkeypress=function(e){that.keypressEventHandler(e);}},keypressEventHandler:function(e){var key_code=e.keyCode?e.keyCode:e.charCode
var key_value=String.fromCharCode(key_code);var event=new OWA.event();event.setEventType('dom.keypress');event.set('key_value',key_value);event.set('key_code',key_code);var targ=this._getTarget(e);event.set("dom_element_name",targ.name);event.set("dom_element_value",targ.value);event.set("dom_element_id",targ.id);event.set("dom_element_tag",targ.tagName);this.addToEventQueue(event);},getTimestamp:function(){return Math.round(new Date().getTime()/1000);},getTime:function(){return Math.round(new Date().getTime());},getElapsedTime:function(){return this.getTimestamp()-this.startTime;},getOption:function(name){return this.options[name];},setOption:function(name,value){this.options[name]=value;return;},setLastEvent:function(event){return;},addToEventQueue:function(event){if(this.active&&!this.isPausedBySibling()){var now=this.getTimestamp();if(event!=undefined){this.event_queue.push(event.getProperties());}else{}}},isPausedBySibling:function(){return OWA.getSetting('loggerPause');},sleep:function(delay){var start=new Date().getTime();while(new Date().getTime()<start+delay);},pause:function(){this.active=false;},restart:function(){this.active=true;},loadPlayer:function(stream){this.pause();this.player=new OWA.player();this.player.load(this.event_queue);},loadHeatmap:function(){this.pause();OWA.util.loadScript('owa/modules/base/js/includes/jquery/jquery-1.3.2.min.js',function(){});OWA.util.loadCss('owa/modules/base/css/owa.overlay.css',function(){});OWA.util.loadScript('owa/modules/base/js/owa.heatmap.js',function(){this.overlay=new OWA.heatmap();this.overlay.options.liveMode=true;this.overlay.generate();});}}
//// End of owa.tracker //// 
