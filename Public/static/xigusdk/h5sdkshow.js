  //已登录
  if(login){
    $("#blTagIdLoginDiv").attr('style','display:none;');//未登录
    $("#blTagIdWelcomegretDiv").attr('style','display:block;');//已登录
    if(bindphone){
      $(".phoneicon").addClass('userPhone').removeClass("userPhoneNo");
      $('.nobindphone').attr("style",'display:none;');
      $("#blTagIdIsBindSignOutBtn_Welcome").attr("style",'display:block;');
    }else{
      $(".phoneicon").addClass('userPhoneNo').removeClass("userPhone");
      $('.nobindphone').attr("style",'display:block;');
      $("#blTagIdIsBindSignOutBtn_Welcome").attr("style",'display:none;');
    }
  }else{
    loginclick();//未登录
  }

  //点击登录
  $("#blTagIdLogin").click(function(){
    $('.deleteUser').click();
    loginclick();
  });
  //点击注册
  $("#blTagIdSignUp").click(function(){
    $('.deleteUser').click();
    regclick();
  });

  //搜索框清空
  $('.deleteUser').click(function(){
    $(this).prev('input').val("");
  });

  //点击账号安全
  $("#blTagIdAccountSecurityBLBtn_Welcome").click(function(){
    $("#blTagIdLoginDiv").attr('style','display:none;');
    $("#blTagIdWelcomegretDiv").attr('style','display:none;');
    $("#blTagIdBindPhonegretDiv").attr('style','display:block;');
  });

  //取消事件
  $(".quxiao").click(function(){
    if(login){
      $("#blTagIdBindPhonegretDiv").attr('style','display:none;');//账号安全
      $("#blTagIdLoginDiv").attr('style','display:none;');//未登录
      $("#blTagIdForgetPwdDiv").attr('style','display:none;');//重置密码
      $("#blTagIdWelcomegretDiv").attr('style','display:block;');//已登录
    }else{
      $("#blTagIdBindPhonegretDiv").attr('style','display:none;');//账号安全
      $("#blTagIdLoginDiv").attr('style','display:block;');//未登录
      $("#blTagIdForgetPwdDiv").attr('style','display:none;');//重置密码
      $("#blTagIdWelcomegretDiv").attr('style','display:none;');//已登录
    }
  });

  //更换账号
  $(".changeaccount").click(function(){
    loginclick();
  });
  //检测手机号码
  $("#zblTagIdUserName").blur(function(){
    $phone = $(this).val();
    if(!(/^1[3456789]\d{9}$/.test($phone))&&$phone!=''){
      $(".zhucetype").val('0');
      $("#blTagIdSecurityCodegretDiv").attr('style','display:none;');//短信
      $("#blTagIdPicCodeDiv").attr('style','display:block;');//图片
    }else{
      $(".zhucetype").val('1');
      $("#blTagIdSecurityCodegretDiv").attr('style','display:block;');
      $("#blTagIdPicCodeDiv").attr('style','display:none;');
    }
  });
  function loginclick(){
    $("#blTagIdLoginDiv").attr('style','display:block;');
    $("#blTagIdLogin").addClass('checked').siblings().removeClass('checked');
    $("#blTagIdWelcomegretDiv").attr('style','display:none;');//已登录
    $(".loginContent").attr('style','display:block;');
    $(".regContent").attr('style','display:none;');
  }

  function regclick(){
    $("#blTagIdLoginDiv").attr('style','display:block;');
    $("#blTagIdSignUp").addClass('checked').siblings().removeClass('checked');
    $(".loginContent").attr('style','display:none;');
    $(".regContent").attr('style','display:block;');
  }

  function errorshow(error){
    $("#blTagIdRegeditSucceed").text(error);
    $("#blTagIdRegeditBg").attr('style','display:block;');
    t = setTimeout("errorclose()",2000);
    $('body').click(function(){
      window.clearInterval(t);
      errorclose();
    });
  }
  function errorclose(){
    $("#blTagIdRegeditSucceed").text('');
    $("#blTagIdRegeditBg").attr('style','display:none;');
  }
  function timedown(e) {
    var t = t?t:60,s = $(e);
    s.addClass('pdisabled');
    s.attr('style','background: #94dcb8;');
    var a = setInterval(function() {
        t--;
        s.text(t + '秒后重发'),
        0 == t && (s.attr('style',''),s.removeClass('pdisabled').text('获取验证码'), clearInterval(a))
    },1e3)
  };
