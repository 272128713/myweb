var winName = 
[
	{"name":"home-side", "url":"../html/home-side.html"},
	{"name":"info", "url":"../html/info.html"},
	{"name":"eat", "url":"../html/eat.html"},
	{"name":"hotel", "url":"../html/hotel.html"},
	{"name":"walk", "url":"../html/walk.html"},
	{"name":"you", "url":"../html/you.html"},
	{"name":"gou", "url":"../html/gou.html"},
	{"name":"yu", "url":"../html/yu.html"},
	{"name":"jihua", "url":"../html/jihua.html"}
];
openwin = function(index){
	// 打开页面
	var windowname = winName[index-1]["name"];
	var windowurl = winName[index-1]["url"];
	api.openWin({name:windowname,url:windowurl});
}