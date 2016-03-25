/**
 * Created by Administrator on 2015/12/17 0017.
 */
function setCookie(name, value, iDay)
{
    var oDate=new Date();

    oDate.setDate(oDate.getDate()+iDay);

    document.cookie=name+'='+encodeURIComponent(value)+';expires='+oDate;
}

function getCookie(name)
{
    var arr=document.cookie.split('; ');
    var i=0;
    for(i=0;i<arr.length;i++)
    {
        //arr2->['username', 'abc']
        var arr2=arr[i].split('=');

        if(arr2[0]==name)
        {
            var getC = decodeURIComponent(arr2[1]);
            return getC;
        }
    }

    return '';
}

function removeCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

/**
 * 时间戳转化为时间格式
 * */
function transtime(time,gs){
    var newDate = new Date();
    newDate.setTime(time * 1000);

    Date.prototype.format = function(format) {
        var date = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3),
            "S+": this.getMilliseconds()
        };
        if (/(y+)/i.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
        }
        for (var k in date) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1
                    ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
            }
        }
        return format;
    }
    return time = newDate.format(gs);
}