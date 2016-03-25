//验证汉字、英文、数字
var reg=/^[A-Za-z0-9\u4e00-\u9fa5]+$/;
var reg2=/^[\u4e00-\u9fa5]+$/;
//校验手机号
telRuleCheck2 = function (string) {  
    var pattern = /^1[34578]\d{9}$/;  
    if (pattern.test(string)) {  
        return true;  
    }  
    console.log('check mobile phone ' + string + ' failed.');  
    return false;  
};
//返回经销商首页

function goToA(){
        if(navigator.userAgent.match('iPhone')){
            goToAgency();
        }
        if(navigator.userAgent.match('Android')){
            Android.goToAgency();
        }
}


//返回用户首页

function goToUserA(){
    if(navigator.userAgent.match('iPhone')){
    	goToUser();
    }
    if(navigator.userAgent.match('Android')){
    	//alert(111);
    	Android.goToUser();
    }	

}

//去群聊
function goToChatallA(){
    if(navigator.userAgent.match('iPhone')){
    	goToChatall();
    }
    if(navigator.userAgent.match('Android')){
    	//alert(111);
    	Android.goToChatall();
    }	
}