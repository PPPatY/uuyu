// //防止页面后退
// history.pushState(null, null, document.URL);
// window.addEventListener('popstate', function () {
//     history.pushState(null, null, document.URL);
// });
//监听事件
window.addEventListener('message', function(e) {
    //监听运营方返回消息
    switch(e.data.event){
      case 'pay:h5_ali_pay_wap'://支付宝wap支付
          $('.iframepay').css('display','block');
          $("#jjspay_url_mainframe").attr("src",self.location.origin+e.data.data);
          $("#jjspay_url_mainframe").css("z-index",'2000');
          $(".iframecolse").css("z-index",'2001');
          setTimeout("$('.iframecolse').css('display','block');",1500);
          break;
      case "pay:wxgzh_pay"://官方公众号支付
        if(e.data.status==1){
          callpay(e.data.data,e);
        }
      break;
      case 'pay:wxgzh_pay_ok'://官方公众号支付完成
          $("#egretBox").hide(function() {});
          self.postMessage({event:"sdk:pay_result",status:1,data:"支付成功"}, "*");
          break;
      case 'pay:payback'://支付回调页面返回游戏
            ifr = document.querySelector('#op_url_mainframe');
            closesdk(100);
            yktip(ykway,ykphone);
            if(e.data.status==1){
                ifr.contentWindow.postMessage({event:"game:h5paySdk:callback",code:1,data:"支付成功"},"*");
            }else{
                ifr.contentWindow.postMessage({event:"game:h5paySdk:callback",code:0,data:"支付失败"},"*");
            }
          break;
      case "game:loginSdk"://登录sdk
        if(e.data.code==200){
          showsdk('',e.data.data);
        }else if(e.data.code==201){
          ifr = document.querySelector('#op_url_mainframe');
          ifr.contentWindow.postMessage({event:"game:loginSdk:callback",code:e.data.code,data:e.data.data},"*");
          closesdk(10);
        }else{
          e.source.postMessage({event:"game:loginSdk:callback",code:e.data.code,data:e.data.data},"*");
        }
      break;
      case "loginSdkres"://登陆结果
          ifr = document.querySelector('#op_url_mainframe');
          ifr.contentWindow.postMessage({event:"game:loginSdk:callback",code:e.data.code,data:e.data.data},"*");
          closesdk();
      break;
      case "game:h5paySdk"://支付sdk
        if(e.data.code==200){
          showsdk('',e.data.data);
        }else{
          yktip(ykway,ykphone);
          e.source.postMessage({event:"game:h5paySdk:callback",code:e.data.code,data:e.data.data},"*");
        }
      break;
      case "sdk:pay_result"://sdk支付结果
        ifr = document.querySelector('#op_url_mainframe');
        closesdk(1);
        yktip(ykway,ykphone);
        if(e.data.status==-100){
          return;
        }
        if(e.data.status!=1){//取消支付
            ifr.contentWindow.postMessage({event:"game:h5paySdk:callback",code:0,data:"支付失败"},"*");
        }else{
            ifr.contentWindow.postMessage({event:"game:h5paySdk:callback",code:1,data:"支付成功"},"*");
        }
      break;

      case "game:shareSdk"://sdk分享
        share(e.data.data.title,link,e.data.data.imgUrl,e.data.data.desc,e);
      break;
    }
}, false);
function yktip(ykway,ykphone){
  if(ykway==0&&!ykphone){
    popplog.addClass('pop-pay-temp').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-title">友情提醒</div><div class="pop-content"><div class="pop-pay-info"></div><div class="pop-pay-notice">您现在是游客登录，为了防止您下次登录时忘记账户密码，无法通过手机号找回密码，建议您绑定手机号，还可获得 '+$bindphoneadd+' 积分哦</div><div class="pop-butn-box"><a href="/mobile.php/Subscriber/user_bind_phone" class="pop-butn">去绑定</a></div></div>');
    $('.pop-close').click(function() {popplog.close(); return false;});
  }
}
//调用微信JS api 支付
  function jsApiCall(jsApiParameters,e)
  {
      WeixinJSBridge.invoke(
          'getBrandWCPayRequest',jsApiParameters,
          function(res){
            if(res.err_msg == "get_brand_wcpay_request:ok"){
              e.source.postMessage({event:"pay:wxgzh_pay_ok",status:1}, "*");
            }
              // alert(res.err_code+res.err_desc+res.err_msg);
          }
      );
  }
  function callpay(jsApiParameters,e)
  {
      if (typeof WeixinJSBridge == "undefined"){
          if( document.addEventListener ){
              document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
          }else if (document.attachEvent){
              document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
              document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
          }
      }else{
          jsApiCall(jsApiParameters,e);
      }
  }


function showsdk(url,content){
    $("<div id='sdkdiv2'>"+content+'</div>').prependTo('#sdkdiv');
     try{
      if(screen == 1){
        $('.pc-wrap2').removeClass('cross_screen');
      }
    }catch(err){
    }
}
function closesdk(time){
    if(time==''){
        var time = 300;
    }
    setTimeout("$('#sdkdiv2').hide(function() {});",time);
    setTimeout("$('#sdkdiv2').remove();",time+10);
     try{
      if(screen == 1){
        $('.pc-wrap2').addClass('cross_screen');
      }
    }catch(err){
    }
}
try{
//微信分享
wx.config({
    debug: false,
    appId: appId,
    timestamp: timestamp,
    nonceStr: nonceStr,
    signature: signature,
    jsApiList: [
        // 所有要调用的 API 都要加到这个列表中
        'checkJsApi',
        'openLocation',
        'getLocation',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'startRecord',
        'stopRecord',
        'playVoice',
        'downloadVoice',
        'uploadVoice',
        'onMenuShareQQ',
      ]
});
wx.ready(function () {
  // config信息验成功
  wx.checkJsApi({
      jsApiList: [
          'getLocation',
          'onMenuShareTimeline',
          'onMenuShareAppMessage',
          'startRecord',
          'stopRecord',
          'playVoice',
          'downloadVoice',
          'uploadVoice',
          'onMenuShareQQ',
      ],
      success: function (res) {
      },error:function(res){
      }
  });
  share(title,link,imgUrl,desc);
});
function share(title,link,imgUrl,desc,e){
  //分享到朋友
    wx.onMenuShareAppMessage({
        title:  title, // 分享标题
        desc:   desc, // 分享描述
        link:   link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: imgUrl, // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () { 
            if(e){
              e.source.postMessage({event:"game:shareSdk:callback",status:1,msg:'分享成功'}, "*");
            }
        },
        cancel: function () { 
            if(e){
              e.source.postMessage({event:"game:shareSdk:callback",status:0,msg:'分享失败'}, "*");
            }
        }
    });
    //分享到朋友圈
    wx.onMenuShareTimeline({
        title: title, // 分享标题
        link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: imgUrl, // 分享图标
        success: function () { 
            if(e){
              e.source.postMessage({event:"game:shareSdk:callback",status:1,msg:'分享成功'}, "*");
            }
        },
        cancel: function () { 
            if(e){
              e.source.postMessage({event:"game:shareSdk:callback",status:0,msg:'分享失败'}, "*");
            }
        }
    });
    //分享到QQ
    wx.onMenuShareQQ({
        title: title, // 分享标题
        desc: desc, // 分享描述
        link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: imgUrl, // 分享图标
        success: function () { 
          if(e){
            e.source.postMessage({event:"game:shareSdk:callback",status:1,msg:'分享成功'}, "*");
          }
        },
        cancel: function () { 
          if(e){
            e.source.postMessage({event:"game:shareSdk:callback",status:0,msg:'分享失败'}, "*");
          }
        }
    });
}
}catch(err){

}