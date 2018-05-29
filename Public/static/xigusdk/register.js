var browser={
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {         //移动终端浏览器版本信息
             trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
        };
     }(),
     language:(navigator.browserLanguage || navigator.language).toLowerCase()
}

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}

function register(obj) {
    switch(obj){
        case 'account':{
            var account =$('.auser').val();
            var password =$('.apwd').val();
            var verify =$("#registerNameVcodePop").val();
            var data = {"account":account,'verify':verify,"password":password,"type":obj} ;
            gameboxbtn(data);
        };break;
        case 'phone':{
            var account =$('.puser').val();
            var password =$('.ppwd').val();
            var verify =$("#registerPhonePop").val();
            var data = {"account":account,"password":password,"type":obj,'verify':verify} ;
            gameboxbtn(data);
        };break;
        default:{
            if(gid){
                //有游戏信息 使用推广的方法，注册记录游戏，即非个人中心
                var url = thirdprologinurl;
            }else{
                var url = thirdloginurl;
            }
            url = url.replace(".html","")+"/type/"+obj+"/pid/"+pid+"/gid/"+gid+"/encrypt/1";
            top.location.href=url;
        }
    }
}

function gameboxbtn(data) {
        var pid="{:I('pid')}";
        var gid="{:I('gid')}"; 
        if (data.type=='phone') {
            if (!(/^[1][358][0-9]{9}/.test(data.account))) {
                f('手机号码格式不正确');return false;
            }
            if (!data.password || data.password.length<6) {
                f('请输入至少6位的密码');return false;
            }
            // if(data.verify==''){
            //     f('验证码不能为空');return false;
            // }
            if (!$(".pckb").prop("checked")){
                f('请阅读用户注册协议');return false;
            }
        }
        if (data.type=='account') {
            if(data.account.length>15||data.account.length<6){
                f('用户名为6~15位数字、字母或下划线');return false;
            }
            if (!(/^[a-zA-Z]+[0-9a-zA-Z_]{5,15}$/.test(data.account))){
                f('用户名必须由字母和数字组成,以字母开头');return false;
            }
            if (!data.password || data.password.length<6) {
                f('请输入至少6位的密码');return false;
            }
            if(data.verify==''){
                f('验证码不能为空');return false;
            }
            if (!$(".ackb").prop("checked")){
                f('请阅读用户注册协议');return false;
            }
        }
        switch(data.type){
            case 'account':{
                $.ajax({
                    type:'post',
                    dataType:'json',
                    data:{"account":data.account,"password":data.password,"type":data.type,'verify':data.verify,'p_id':pid,'g_id':gid},
                    url:'{:U("TuiRegister/register")}',
                    success:function(d) {
                        switch(d.status){
                            case 1:{
                                window.location.href=d.url;
                            };break;
                            default:{
                                f(d.msg);
                            }
                        }
                    },
                    error:function() {
                        alert('服务器故障，请稍后再试');
                    }
                });
            };break;
            case 'phone':{
                $.ajax({
                    type:'post',
                    dataType:'json',
                    data:{"account":data.account,"password":data.password,"type":data.type,'verify':data.verify,'way':0,'p_id':pid,'g_id':gid},
                    url:'{:U("TuiRegister/check_tel_code")}',
                    success:function(data) {
                        switch(parseInt(data.status)) {
                            case -3: {
                                alert(data.msg);
                            };break;
                            case 0:{
                                alert(data.msg)
                            };break;
                            default:{
                                window.location.href = data.url;
                            }
                        }
                    },
                    error:function() {
                        alert('服务器故障，请稍后再试');
                    }
                });
            };break;
            case 2:{
                //t(phone,password,2,pid,gid,"123");
                $.ajax({
                    type:'post',
                    dataType:'json',
                    data: {"phone":phone,"delay":1,"way":2,"type":data.type},
                    url: '{:U("telsvcode")}',
                    success:function(data) {
                        t(phone,password,2,pid,gid,data);
                    },
                    error:function() {
                        alert('服务器故障，请稍后再试');
                    }
                });
            };break;
        }
}