function ZeroPad(c,b){for(var a=c+"";a.length<b;)a="0"+a;return a}
$(document).ready(function(){ko.applyBindings(new function(){var c=this;c.datetime=jCore.synchronize("/jcore/ajax/?res=10",function(){var b={create:function(a){return ko.observable(ZeroPad(a.data,2))},update:function(a){return ZeroPad(a.data,2)}};return ko.mapping.fromJS({month:"",mday:"",hours:"0",minutes:"0",seconds:"0"},{minutes:b,seconds:b,mday:{create:function(a){return ko.observable(a.data+", ")},update:function(a){return a.data+", "}}})},function(b){ko.mapping.fromJS(b,c.datetime)});setInterval(c.datetime.synchronize,
1E3)})});
