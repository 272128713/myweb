/**
 * Created by Administrator on 2016/2/16 0016.
 */
commonUrl = 'http://192.168.20.8/serverApi/shopapi/1.0/';
apiImgurl = 'http://tnfs.tngou.net/image';
//JavaScript函数：
var minute = 1000 * 60;
var hour = minute * 60;
var day = hour * 24;
var halfamonth = day * 15;
var month = day * 30;
function getDateDiff(dateTimeStamp) {
	var now = new Date().getTime();
	var diffValue = now - dateTimeStamp;
	if (diffValue < 0) {
		//若日期不符则弹出窗口告之
		//alert("结束日期不能小于开始日期！");
	}
	var monthC = diffValue / month;
	var weekC = diffValue / (7 * day);
	var dayC = diffValue / day;
	var hourC = diffValue / hour;
	var minC = diffValue / minute;
	if (monthC >= 1) {
		result = "发表于" + parseInt(monthC) + "个月前";
	} else if (weekC >= 1) {
		result = "发表于" + parseInt(weekC) + "周前";
	} else if (dayC >= 1) {
		result = "发表于" + parseInt(dayC) + "天前";
	} else if (hourC >= 1) {
		result = "发表于" + parseInt(hourC) + "个小时前";
	} else if (minC >= 1) {
		result = "发表于" + parseInt(minC) + "分钟前";
	} else
		result = "刚刚发表";
	return result;
}

function datetime_to_unix(datetime) {
	var tmp_datetime = datetime.replace(/:/g, '-');
	tmp_datetime = tmp_datetime.replace(/ /g, '-');
	var arr = tmp_datetime.split("-");
	var now = new Date(Date.UTC(arr[0], arr[1] - 1, arr[2], arr[3] - 8, arr[4], arr[5]));
	return parseInt(now.getTime() / 1000);
}

function unix_to_datetime(unix) {
	//shijianchuo是整数，否则要parseInt转换
	var time = new Date(parseInt(unix));
	var y = time.getFullYear();
	var m = time.getMonth() + 1;
	var d = time.getDate();
	var h = time.getHours();
	var mm = time.getMinutes();
	var s = time.getSeconds();
	return y + '-' + add0(m) + '-' + add0(d) + ' ' + add0(h) + ':' + add0(mm) + ':' + add0(s);
}

function add0(m) {
	return m < 10 ? '0' + m : m
}

//两次退出
function back() {
	api.addEventListener({
		name : 'keyback'
	}, function(ret, err) {
		if (!first) {
			first = new Date().getTime();
			api.toast({
				msg : '再按一次退出',
				duration : 1500,
				location : 'bottom'
			});
			setTimeout(function() {
				first = null;
			}, 1000);
		} else {
			if (new Date().getTime() - first < 1000) {
				api.closeWidget({
					silent : true
				});
			}
		}
	});
}

