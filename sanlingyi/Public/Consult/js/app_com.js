/**
 * Created by Administrator on 2015/7/16.
 */

/**
 * 唤起app提问界面
 */
function go_to_app_consult($uid){
    if(navigator.userAgent.match('iPhone')){
       // alert();
        userConsulting($uid);
    }
    if(navigator.userAgent.match('Android')){
        //alert($uid);
        Android.userConsulting($uid);
    }
}
/**
 * 返回app
 */
function logoutConsultings(){

    if(navigator.userAgent.match('iPhone')){
        logoutConsulting();
    }
    if(navigator.userAgent.match('Android')){
        Android.logoutConsulting();
    }
}

if(navigator.userAgent.match('Android')){
    var windowHeight = document.documentElement.clientHeight;
    document.body.style.height = windowHeight + 'px';
}


