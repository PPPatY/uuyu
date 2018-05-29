//登录
$("#blTagIdLoginBtn").click(function(){
  alert('???');
  // //登录变量
  // account =$("#lblTagIdUserName").val();//账号
  // password =$("#lblTagIdUserPwd").val();//密码
  // parent=that.closest('.jspopitem');
  // console.log(parent);return false;
  // if(!account){
  //   errorshow('请输入手机号或用户名');
  //   return false;
  // }
  // if(!password){
  //   errorshow('请输入密码');
  //   return false;
  // }
  // // 登录
  // $.ajax({
  //     type: "POST",
  //     url: loginurl,
  //     data: {username:account, password:password,gid:gid},
  //     dataType: "json",
  //     success: function(data){
  //       if(data.status==0){
  //         errorshow(data.msg);
  //         return false;
  //       }else if(data.status==1){
  //         errorshow("登陆成功");
  //         setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
  //       }
  //     },
  //     error:function(){
  //         errorshow("服务器错误");
  //         return false;
  //     }
  // });
});

//进入游戏功能已实现  暂不用
$('.sign_in_game').click(function(){
  if(login){
    $.ajax({
        type: "POST",
        url: signingamepurl,
        data: {user_id:login,gid:gid},
        dataType: "json",
        success: function(data){
          if(data.status!=1){
              errorshow(data.msg);
              if(data.status==-1){
                setTimeout('top.location.reload();',1000);
              }
              return false;
          }else{
            errorshow("登陆成功");
            setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
          }
        },
        error:function(){
            errorshow("服务器错误");
            return false;
        }
    });
    // setTimeout("parent.postMessage({event:'login',status:1}, '*');",10);
  }
});
//忘记密码关闭
function wjerrorclose(){
    $("#wjblTagIdRegeditSucceed").text('');
    $("#wjblTagIdRegeditBg").attr('style','display:none;');
}

//注册
$("#blTagIdSignupBtn").click(function(){
  account = $("#zblTagIdUserName").val();//账号
  password = $("#zblTagIdUserPwd").val();//密码
  password2 = $("#zblTagIdConfirmUserPwd").val();
  verify = $("#zblTagIdPicCodeInput").val();//图片验证码
  code = $("#zblTagIdSecurityCode").val();//手机验证码
  zhucetype = $(".zhucetype").val();
  if (!account || account.length<6 || account.length > 15) {
      errorshow('请输入6-15位的用户名');
      return false;
  }
  if(!password || password.length<6 || password.length > 15){
    errorshow('请输入6-15位的密码');
    return false;
  }
  if(zhucetype==1){
    type = 'phone'; 
    if(!code){
      errorshow('请输入短信验证码');
      return false;
    }
  }else{
    type = 'account'; 
    if(!verify){
      errorshow('请输入图片验证码');
      return false;
    }
  }
  // 注册 0账号 1手机
  if(zhucetype==0){
    $.ajax({
      type: "POST",
      url: zhuceurl,
      data: {account:account, password:password,type:type,verify:verify,g_id:gid,p_id:pid},
      dataType: "json",
      success: function(data){
        console.log(data);
        if(data.status!=1){
          
          if(data.code==0){
            $('#zblTagIdPicCodeImg').click();
          }
          errorshow(data.msg);
          return false;
        }else if(data.status==1){
          // 1、记录登录游戏  通知cp
          // 2
          errorshow("注册成功");
          setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
    });
  }else{
    $.ajax({
      type: "POST",
      url: checkaccount,
      data: {account:account},
      dataType: "json",
      success: function(data){
        if(data.code==1){
          // $("#zblTagIdSendSecurityCode").attr('style','background: #94dcb8;');
          errorshow('用户已存在');
          return false;
        }else{
            $.ajax({
                type: "POST",
                url: zhucepurl,
                data: {account:account, password:password,type:type,verify:code,way:0,g_id:gid,p_id:pid},
                dataType: "json",
                success: function(data){
                  if(data.status==0){
                    errorshow(data.msg);
                    return false;
                  }else if(data.status==1){
                    // 1、记录登录游戏  通知cp
                    // 2
                    errorshow("注册成功");
                    setTimeout("self.postMessage({event:'loginSdkres',status:1,data:"+data.data+"}, '*');",10);
                  }
                },
                error:function(){
                    errorshow("服务器错误");
                    return false;
                }
            });
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
    });
  }
});

//注册发送验证码
$("#zblTagIdSendSecurityCode").click(function(){
  account = $("#zblTagIdUserName").val();//手机号
  if(!(/^1[3456789]\d{9}$/.test(account))){
      errorshow("手机号错误");
      return false;
  }
  if($("#zblTagIdSendSecurityCode").hasClass('pdisabled')){
    return false;
  }else{
    $.ajax({
      type: "POST",
      url: checkaccount,
      data: {account:account},
      dataType: "json",
      success: function(data){
        if(data.code){
          // $("#zblTagIdSendSecurityCode").attr('style','background: #94dcb8;');
          errorshow('用户已存在');
          return false;
        }else{
            $.ajax({
                type: "POST",
                url: sendcodeurl,
                data: {phone:account},
                dataType: "json",
                success: function(data){
                  if(data.status){
                    // $("#zblTagIdSendSecurityCode").attr('style','background: #94dcb8;');
                    errorshow(data.msg);
                    timedown('#zblTagIdSendSecurityCode');
                  }else{
                    errorshow(data.msg);
                  }
                },
                error:function(){
                    errorshow("服务器错误");
                    return false;
                }
            });
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
    });
  }
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
$("#blTagIdUpdatePwdBtn_ForgetPwd").click(function(){
  account =$("#lblTagIdUserName").val();//手机号
  verify = $("#blTagIdSecurityCode_ForgetPwd").val();//验证码
  password = $("#blTagIdUserPwd_ForgetPwd").val();
  if(!verify){
    errorshow('请输入短信验证码');
    return false;
  }
  if(!password || password.length<6 || password.length > 15){
    errorshow('请输入6-15位的密码');
    return false;
  }
  $.ajax({
      type: "POST",
      url: zhucepurl,
      data: {account:account, password:password,type:'',verify:verify,way:-1},
      dataType: "json",
      success: function(data){
        if(data.status==0){
          errorshow(data.msg);
          return false;
        }else if(data.status){
          errorshow(data.msg);
          setTimeout('$(".quxiao").click();',1000);
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
  });
});



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

//账号安全
$("#blTagIdBindPhoneBtn_BindBL").click(function(){
  account = login;
  phone =$("#blTagIdUserPhone_BindBL").val();//账号
  verify = $("#blTagIdSecurityCode_BindBL").val();//验证码
  if(!(/^1[3456789]\d{9}$/.test(phone))){
    errorshow("手机号错误");
    return false;
  }
  if(!verify){
    errorshow("请输入短信验证码");
    return false;
  }

  $.ajax({
      type: "POST",
      url: zhucepurl,
      data: {account:phone, p_account:account,type:'',verify:verify,way:2},//手机 账号反了（特意这样写，对应方法）
      dataType: "json",
      success: function(data){
        if(data.status==0){
          errorshow(data.msg);
          return false;
        }else if(data.status){
          errorshow(data.msg);
          location.reload();
        }
      },
      error:function(){
          errorshow("服务器错误");
          return false;
      }
  });
});