//登录
$("body").on("click",'#blTagIdLoginBtn',function(){
  //登录变量
  that=$(this);
  account =$("#lblTagIdUserName").val();//账号
  password =$("#lblTagIdUserPwd").val();//密码
  var parent=that.closest('.jspopitem');
  if(!account){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入手机号或用户名');
    return false;
  }
  if(!password){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入密码');
    return false;
  }
  // 登录
  $.ajax({
      type: "POST",
      url: loginurl,
      data: {username:account, password:password,gid:gid},
      dataType: "json",
      success: function(data){
        if(data.status==0){
          parent.find('.pop-error').stop(true).fadeIn(200).text(data.msg);
          return false;
        }else if(data.status==1){
          layer.msg('登录成功',{time:1000});
          setTimeout("top.location.reload()",500);
        }else if(data.status==2){
          layer.msg('登录成功',{time:1000});
          setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
        }
      },
      error:function(){
          layer.msg("服务器错误");
          return false;
      }
  });
});

//忘记密码关闭
function wjerrorclose(){
    $("#wjblTagIdRegeditSucceed").text('');
    $("#wjblTagIdRegeditBg").attr('style','display:none;');
}

//注册
$("body").on("click",'#blTagIdSignupBtn',function(){
  that=$(this);
  smrz = that.hasClass('smrz');
  parent=that.closest('.jspopitem');
  isphone = $('.jsverify').css('display')=='none'?0:1;
  account = $("#zblTagIdUserName").val();//账号
  var first = account.substr(0,1);
  password = $("#zblTagIdUserPwd").val();//密码
  password2 = $("#zblTagIdConfirmUserPwd").val();
  verify = $("#zblTagIdPicCodeInput").val();//图片验证码
  if(!account.length){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入手机号或用户名');
    return false;
  }
  if(isphone){
      if(!(/^1[3456789][0-9]{9}$/.test(account))) {
        parent.find('.pop-error').stop(true).fadeIn(200).text('请输入正确的手机号');
        return false;
      }
  }else{
      if(account.length>15||account.length<6){
        parent.find('.pop-error').stop(true).fadeIn(200).text('用户名长度在6~15个字符');
        return false;
      }
  }
  if(verify.length<1){
    parent.find('.pop-error').stop(true).fadeIn(200).text('验证码不能为空');
    return false;
  }
	if(!password) {
		parent.find('.pop-error').stop(true).fadeIn(200).text('请输入密码');
		return false;
	}  if(password.length>15||password.length<6){
    parent.find('.pop-error').stop(true).fadeIn(200).text('密码长度不合法');
    return false;
  }
	if(!password2){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入确认密码');
    return false;
  }  if(password!=password2){
    parent.find('.pop-error').stop(true).fadeIn(200).text('密码和确认密码不一致');
    return false;
  }
  checkxy = $('.jspopitem .jscheckbox').hasClass('on');
  if(!checkxy){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请阅读并同意用户注册协议');
    return false;
  }
  if(smrz){
    popplog.find('.lrbox').fadeOut(200);
    setTimeout(function() {
      $('<div class="fgbox" style="display:none;"><a href="javascript:;" class="pop-arrow-left "></a><ul class="pop-tab pop-clear"><li class="active">注册</li></ul><div class="pop-pan"><form id="smrz" action=""><div class="pop-content"><div class="pop-table"><div class="pop-row"><div class="pop-cell"><img src="/Public/Mobile/images/login_name.png" class="icon icon-user"></div><div class="pop-cell"><div class="pop-input"><input type="text" name="real_name" class="txt account" placeholder="请输入真实姓名"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Mobile/images/login_code.png" class="icon icon-user"></div><div class="pop-cell verification"><div style="margin-right:0rem;" class="pop-input"><input type="text" id="idcard" name="idcard" class="txt" placeholder="请输入身份证号码"></div></div></div></div><div class="pop-error error">用户名不存在</div></div><div class="pop-butn-box"><input type="submit" id="blTagIdSignupBtn" class="butn fgbutn smrzbut disabled jssubbutn" value="注册"></div></form><p class="fgnotice" style="padding-top: 0.9rem;">      根据国家关于《网络游戏管理暂行办法》要求，溪谷H5游戏平台的所有玩家们必须在2017-10-01日之前完成实名认证，否则将会被禁止进入游戏！</p></div></div>').appendTo(popplog.find('.pop-box')).fadeIn(200);
        $('.pop-arrow-left').click(function() {
          popplog.find('.fgbox').remove();
          popplog.find('.lrbox').fadeIn(200);
          return false;
        });
    },210);
    return false;
  }
  var real_name ='';
  var idcard='';
  smrzbut = that.hasClass('smrzbut');
  if(smrzbut){
    parent = $('#smrz');
    real_name = $('input[name="real_name"]').val();
    idcard = $('input[name="idcard"]').val();
  }
  $.ajax({
    type: "POST",
    url: zhuceurl,
    data: {account:account, password:password,verify:verify,g_id:gid,p_id:pid,idcard:idcard,real_name:real_name},
    dataType: "json",
    success: function(data){
      console.log(data);
      if(data.status!=1){
        if(data.code==-1){
          $('.jschange').click();
        }
        parent.find('.pop-error').stop(true).fadeIn(200).text(data.msg);
        return false;
      }else if(data.status==1){
        layer.msg("注册成功");
        setTimeout("top.location.reload()",500);
        setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
      }
    },
    error:function(){
        layer.msg("服务器错误");
        return false;
    }
  });
  return false;
});

//忘记密码
$(".zblTagIdForgetPwd").click(function(){
  account =$("#lblTagIdUserName").val();//账号
  if(account==''){
    $("#wjblTagIdRegeditBg").attr('style','display:block;');
    $("#wjblTagIdRegeditSucceed").text('请输入您的手机号或用户名');
    setTimeout("wjerrorclose()",1000);
  }else{
    $.ajax({
      type: "POST",
      url: checkaccount,
      data: {account:account},
      dataType: "json",
      success: function(data){
        if(data.status==0){
          if(data.code==0){
            errorshow('用户不存在');
            return false;
          }else{
            errorshow('用户未绑定手机号码，请联系客服');
            return false;
          }
        }else{
          $("#blTagIdLoginDiv").attr('style','display:none;');
          $("#blTagIdForgetPwdDiv").attr('style','display:block;');
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
    });
  }
});

//重置密码 发送验证码
$("#blTagIdSendSecurityCode_ForgetPwd").click(function(){
    account =$("#lblTagIdUserName").val();//账号
    if($("#blTagIdSendSecurityCode_ForgetPwd").hasClass('pdisabled')){
        return false;
    }else{
        $.ajax({
          type: "POST",
          url: checkaccount,
          data: {account:account},
          dataType: "json",
          success: function(data){
            if(data.status){
              $.ajax({
                  type: "POST",
                  url: sendcodeurl,
                  data: {phone:account},
                  dataType: "json",
                  success: function(data){
                    if(data.status){
                      // $("#zblTagIdSendSecurityCode").attr('style','background: #94dcb8;');
                      errorshow(data.msg);
                      timedown('#blTagIdSendSecurityCode_ForgetPwd');
                    }else{
                      errorshow(data.msg);
                    }
                  },
                  error:function(){
                      errorshow("服务器错误");
                      return false;
                  }
              });
            }else{
              errorshow('用户不存在');
              return false;
            }
          },
          error:function(){
              errorshow("服务器错误");
              return false;
          }
        });
    }
});
//重置密码提交
function resetPassword(ele,account,verify,password){
  account =account;//手机号
  verify = verify;//验证码
  password = password;
  parent = ele.closest('form');
  var res = false;
  if(!verify){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入短信验证码');
    return res;
  }
  if(!password || password.length<6 || password.length > 15){
    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入6-15位的密码');
    return res;
  }
  $.ajax({
      type: "POST",
      url: zhucepurl,
      data: {account:account, password:password,type:'',verify:verify,way:-1},
      dataType: "json",
      async:false,
      success: function(data){
        if(data.status==0){
          parent.find('.pop-error').stop(true).fadeIn(200).text(data.msg);
        }else if(data.status){
          // layer.msg('修改成功');
          res = true;
        }
      },
      error:function(){
          layer.msg("服务器忙，请稍后再试");
          return false;
      }
  });
  return res;
};



//绑定手机发送 验证码
$("#blTagIdSendSecurityCode_BindBL").click(function(){
    account =$("#blTagIdUserPhone_BindBL").val();//账号
    if(!(/^1[3456789]\d{9}$/.test(account))){
      errorshow("手机号错误");
      return false;
    }
    if($("#blTagIdSendSecurityCode_BindBL").hasClass('pdisabled')){
        return false;
    }else{
        $.ajax({
          type: "POST",
          url: checkphoneexsite,
          data: {phone:account},
          dataType: "json",
          success: function(data){
            if(data.status==0){
              $.ajax({
                  type: "POST",
                  url: sendcodeurl,
                  data: {phone:account},
                  dataType: "json",
                  success: function(data){
                    if(data.status){
                      // $("#zblTagIdSendSecurityCode").attr('style','background: #94dcb8;');
                      errorshow(data.msg);
                      timedown('#blTagIdSendSecurityCode_BindBL');
                    }else{
                      errorshow(data.msg);
                    }
                  },
                  error:function(){
                      errorshow("服务器错误");
                      return false;
                  }
              });
            }else{
              errorshow('手机号已使用');
              return false;
            }
          },
          error:function(){
              errorshow("服务器错误");
              return false;
          }
        });
    }
});

function check_tel_code(ele,phone,verify,unsetcode){
  if(unsetcode!=0){
    unsetcode = 1;
  }
	if (!phone) {
		$(ele).find('.pop-error').stop(true).fadeIn(200).text('请输入手机号');
    return false;
	}
  if(!(/^1[3456789]\d{9}$/.test(phone))){
    $(ele).find('.pop-error').stop(true).fadeIn(200).text('手机号格式不正确');
    return false;
  }
  if(!verify){
    $(ele).find('.pop-error').stop(true).fadeIn(200).text('请输入验证码');
    return false;
  }
  res = false;
  $.ajax({
      type: "POST",
      url: zhucepurl,
      async:false,
      data: {account:phone, password:'',type:'',verify:verify,way:1,unsetcode:unsetcode},
      dataType: "json",
      success: function(data){
        if(data.status==0){
          $(ele).find('.pop-error').stop(true).fadeIn(200).text(data.msg);
          return false;
        }else if(data.status){
          res = true;
        }
      },
      error:function(){
          $(ele).find('.pop-error').stop(true).fadeIn(200).text('服务器忙，请稍后再试');
          return false;
      }
  });
  return res;
};
function register(obj) {
  switch(obj){
      default:{
					var hash = location.hash.replace('#','');
					
          if(gid){
              //有游戏信息 使用推广的方法，注册记录游戏，即非个人中心
              var url = thirdloginurl;
              url = url.replace(".html","")+"/type/"+obj+"/pid/"+pid+"/gid/"+gid+"/encrypt/1"+'/hash/'+hash;
          }else{
              var url = thirdloginurl;
              url = url.replace(".html","")+"/type/"+obj+'/hash/'+hash;
          }
          if (isH5App()){
              if (obj == 'qq'){
                  try{
                      h5shell.thirdlogin(0);
                  }catch(e){
                      $('#qqThirdLogin').trigger('click');
                  }
              }else{
                  try{
                      h5shell.thirdlogin(1);
                  }catch(e){
                      $('#weixinThirdLogin').trigger('click');
                  }
              }
          }else{
              top.location.href=url;
          }
      }
  }
}
function isH5App(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/app\/h5shell/i) == 'app/h5shell'){
        return true;
    }else{
        return false;
    }
}
function h5shell_login(openID,nickName,icon,logintype){
    $("#openID").val(openID);
    $("#nickName").val(nickName);
    $("#icon").val(icon);
    $("#logintype").val(logintype);
    $("#app_third_login").submit();
}