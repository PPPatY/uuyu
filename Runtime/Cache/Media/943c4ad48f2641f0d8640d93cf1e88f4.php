<?php if (!defined('THINK_PATH')) exit();?>
<script src="/Public/static/layer/layer.js"></script>
<script>
    loginurl = "<?php echo U('Subscriber/login');?>";
    zhuceurl = "<?php echo U('Subscriber/register');?>";
    ykzhuceurl = "<?php echo U('Subscriber/ykregister');?>";
    sendcodeurl="<?php echo U('Subscriber/telsvcode');?>";
    checkaccount="<?php echo U('Subscriber/checkaccount');?>";
    checkphoneexsite = "<?php echo U('Subscriber/checkphoneexsite');?>";
    zhucepurl="<?php echo U('Subscriber/check_tel_code');?>";
    signingamepurl="<?php echo U('Subscriber/siginingame');?>";
    thirdloginurl = "<?php echo U('Subscriber/thirdlogin');?>";
    thirdprologinurl = "<?php echo U('TuiRegister/thirdlogin');?>";
    
    function qiehuan(that,time){
      var parent = that.closest('li');
      parent.addClass('active').siblings().removeClass('active');
      var index = parent.index();
      $('.jspoppan .jspopitem').stop(true).fadeOut(time).delay(time+100).eq(index).fadeIn(time);
    }
      var popplog = $('.popplog').pop();
    $(function() {
      
      // 头部登录按钮事件
      $.getScript("/Public/static/xigusdk/h5sdkajax.js",function(){  //加载h5sdkajax.js,成功后，并执行回调函数
      $('.jslogin').click(function() {
          // 登录注册弹窗
          popplog.addClass('pop-login').open('','<div class="lrbox"><a href="javascript:;" class="pop-close"></a><ul class="pop-tab jspoptab pop-clear"><li class="active"><a href="javascript:;" class="tab-butn">登录</a></li><li><a href="javascript:;" class="tab-butn">注册</a></li></ul><div class="pop-pan jspoppan"><div class="pan-item jspopitem"><form action=""><div class="pop-content"><div class="pop-table"><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_name.png" class="icon icon-user"></div><div class="pop-cell"><div class="pop-input"><input type="text" name="" id="lblTagIdUserName" class="txt" placeholder="手机号/用户名"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_password.png" class="icon icon-lock"></div><div class="pop-cell"><div class="pop-input"><input type="password" name="" id="lblTagIdUserPwd" class="txt" placeholder="请输入登录密码"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i><i class="icon icon-eye jseye"></i></span></div></div></div><div class="pop-error error">用户名不存在</div></div><div class="pop-butn-box"><input type="button" class="butn lrbutn jssubbutn" id="blTagIdLoginBtn" value="登录"></div><div class="pop-link-box pop-clear"><span class="forgetbox"><a href="javascript:;" class="jspopforget">忘记密码？</a></span> <span class="temporary hidden"><a href="javascript:;" class="jspoptemporary">游客登录<img src="/Public/Media/images/game_classify_selected.png" class="icon-right-pic"></a></span></div></form><div class="thirdlogin"><div class="thirdlogin-title"><span>第三方登录</span></div><div class="thirdlogin-content pop-clear">'+$('.loginbutdiv').html()+'</div></div></div><!-- ==============分割线============ --><div class="pan-item jspopitem"><form action=""><div class="pop-content"><div class="pop-table"><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_name.png" class="icon icon-user"></div><div class="pop-cell"><div class="pop-input"><input type="text" name="" style="width:120%;" id="zblTagIdUserName" class="txt account" placeholder="手机号/用户名(字母开头6-15位)"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_code.png" class="icon icon-user"></div><div class="pop-cell verification"><div class="pop-input"><input type="text" id="zblTagIdPicCodeInput" name="" class="txt" placeholder="请输入验证码"></div><span class="pop-iconbox pop-clear" id="verify"><div class="pop-table"><div class="pop-cell"><a href="javascript:;" class="pop-code smcode jsverify">获取验证码</a><a href="javascript:;" class="pop-code pcode jschange"><img src="<?php echo U("Subscriber/verify");?>"></a></div></div></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_password.png" class="icon icon-lock"></div><div class="pop-cell"><div class="pop-input"><input type="password" id="zblTagIdUserPwd" name="" class="txt" placeholder="6-15位字母或数字"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i><i class="icon icon-eye jseye"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_password.png" class="icon icon-lock"></div><div class="pop-cell"><div class="pop-input"><input type="password" id="zblTagIdConfirmUserPwd" name="" class="txt" placeholder="确认密码"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i><i class="icon icon-eye jseye"></i></span></div></div></div><div class="pop-error error">前后密码一致</div></div><div class="pop-butn-box"><input type="button" id="blTagIdSignupBtn" class="butn lrbutn jssubbutn" value="注册"></div><div class="pop-argee-box"><label><input type="checkbox" class="checkbox jscheckbox on"><i class="icon"></i><span>我已阅读并同意<a href="<?php echo U("Subscriber/user_argeement");?>">《用户注册协议》</a></span></label></div></form></div></div></div>');
          
        // 关闭弹窗
        $('.pop-close').click(function() {popplog.close(); return false;});
        if(open_name_auth){
          $('#blTagIdSignupBtn').addClass('smrz').val('下一步');
        }
        // 登录注册切换
        $('.jspoptab li').click(function() {
          var that = $(this);
          qiehuan(that,20);
          return false;
        });
        // 检测
        checkinput('.jspopitem .txt');
        
        // 删除按钮事件
        delinput('.jsdel');
        
        // 查看密码按钮事件
        lookpwd('.jseye');
        
        // 约定按钮事件
        $('.jscheckbox').click(function() {
          
          this.checked?($(this).addClass('on')):($(this).removeClass('on'));
          
        });
        
        // 获取手机验证码
        getcode('.jsverify');
        
        // 获取图片验证码
        $('.jschange').click(function() {
          var that = $(this).find('img');
          //  ajax 更换图片
					var url = that.attr('src').replace(/((\.html)?|(\/t\/.+)?)$/g,'');
          that.attr('src',url+'/t/'+(new Date()).getTime()+'.html');
          
          return false;
        });
        
        // 确定验证码类型
        $('.account').keyup(function() {
          var that=$(this),val=$.trim(that.val()),parent=that.closest('.jspopitem');
          var verify = $('#verify');
          parent.find('.pop-error').stop(true).fadeOut(200).text('');
          if (val) {
            var first = val.substr(0,1);
            if (/^[1a-zA-z]$/.test(first)) {
              if (!isNaN(first)) {
                var ajv = verify.find('.jschange').stop(true).slideUp(200).delay(250).siblings('.jsverify').fadeIn(200);
                if(!(/^1[34578][0-9]{9}$/.test(val))) {
                  parent.find('.pop-error').stop(true).fadeIn(200).text('请输入正确的手机号');
                }
              
							} else {
                var ajc = verify.find('.jsverify').stop(true).slideUp(200).delay(250).siblings('.jschange').fadeIn(200);
								
              }
            } else {
              parent.find('.pop-error').stop(true).fadeIn(200).html('用户名格式错误');
            }
          }
          
          return false;
        });
        
        // 游客登录
        $('.jspoptemporary').click(function() {    
          $g_id = "<?php echo I('get.game_id');?>";
          $p_id = "<?php echo I('get.pid');?>";
          $.ajax({
            type:'post',
            url:ykzhuceurl,
            data:{ykregister:'yk','p_id':$p_id,'g_id':$g_id},
            success:function(data){
              if(data.status==1){
                popplog.close();             
                setTimeout(function(){
                  popplog.addClass('pop-login').open('','<div class="tembox"><a href="javascript:;" class="pop-close"></a><div class="pop-title">游客登录成功</div><div class="pop-content"><p class="temporary-account"><span>账户：</span><span>'+data.account+'</span></p><p class="temporary-account"><span>密码：</span><span>'+data.password+'</span></p><p class="temporary-text">(为了防止您下次登录时忘记账户密码，无法通过手机号找回密码，建议您绑定手机号，还可获得'+$bindphoneadd+'积分哦)</p></div><div class="pop-butn-box"><a href="<?php echo U("Subscriber/user_bind_phone");?>" class="pop-bindbutn">去绑定</a></div></div>');
                  $('.pop-close').click(function() {location.href = location.href; return false;});
                },810);
              }else{
                layer.msg(data.msg);
              }
            },error:function(){
              layer.msg('服务器忙，请稍后再试');
            }
          });          
        });
        
        // 忘记密码
        $('.jspopforget').click(function() {
          
          popplog.find('.lrbox').fadeOut(200);
          setTimeout(function() {
            $('<div class="fgbox" style="display:none;"><a href="javascript:;" class="pop-arrow-left "></a><ul class="pop-tab pop-clear"><li class="active">找回密码</li></ul><div class="pop-pan"><form id="zhmm" action=""><div class="pop-content"><div class="pop-table"><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_phone.png" class="icon icon-user"></div><div class="pop-cell"><div class="pop-input"><input type="text" name="" class="txt account" placeholder="请输入手机号"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_code.png" class="icon icon-user"></div><div class="pop-cell verification"><div class="pop-input"><input type="text" id="verify" name="" class="txt" placeholder="请输入验证码"></div><span class="pop-iconbox pop-clear" id="verify"><div class="pop-table"><div class="pop-cell"><a href="javascript:;" class="pop-code smcode jsverify">获取验证码</a></div></div></span></div></div></div><div class="pop-error error">用户名不存在</div></div><div class="pop-butn-box"><input type="submit" class="butn fgbutn disabled jssubbutn" value="下一步"></div></form><p class="fgnotice">通过手机号找回密码只适用于已绑定手机号的账户，未绑定手机号账户可<a href="<?php echo U("Subscriber/user_contact");?>" class="contact_service">联系客服<img src="/Public/Media/images/login_pop_btn_service.png"></a></p></div></div>').appendTo(popplog.find('.pop-box')).fadeIn(200);
            
            checkinput('.fgbox .txt');
            
            delinput('.fgbox .jsdel');
            
            getcode('.fgbox .jsverify',1);
            
            
            
            
            $('.fgbutn').click(function() {
              var $account = $('#zhmm .account').val();
              var $verify = $('#zhmm #verify').val();
              res = check_tel_code('#zhmm',$account,$verify,0);
              if(!res){
                return false;
              }
              popplog.find('.fgbox').fadeOut(200);
              setTimeout(function() {
                $('<div class="fgbox2" style="display:none"><a href="javascript:;" class="pop-arrow-left"></a><ul class="pop-tab pop-clear"><li class="active">重置密码</li></ul><div class="pop-pan"><form action=""><div class="pop-content"><div class="pop-table"><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_password.png" class="icon icon-lock"></div><div class="pop-cell"><div class="pop-input"><input type="password" name="pwd1" class="txt" placeholder="6-15位字母或数字"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i><i class="icon icon-eye jseye"></i></span></div></div><div class="pop-row"><div class="pop-cell"><img src="/Public/Media/images/login_password.png" class="icon icon-lock"></div><div class="pop-cell"><div class="pop-input"><input type="password" name="pwd2" class="txt" placeholder="确认密码"></div><span class="pop-iconbox pop-clear"><i class="icon icon-del jsdel"></i><i class="icon icon-eye jseye"></i></span></div><div class="pop-error error"></div></div></div></div><div class="pop-butn-box"><input type="submit" class="butn disabled fgbutn jssubbutn2" value="提交"></div></form><p class="fgnotice"></p></div></div>').appendTo(popplog.find('.pop-box')).fadeIn(200);
                checkinput('.fgbox2 .txt');
                delinput('.fgbox2 .jsdel');
                lookpwd('.fgbox2 .jseye');
                
                $('.fgbox2 .pop-arrow-left').click(function() {
                  popplog.find('.fgbox2').remove();
                  popplog.find('.fgbox').fadeIn(200);
                  return false;
                });
                
                $('.jssubbutn2').click(function() {
                  var jssubbutn2=$(this),parent = jssubbutn2.closest('form');
                  pwd1 = $('input[name="pwd1"]').val();
                  pwd2 = $('input[name="pwd2"]').val();
                  pattern = /^[a-zA-Z0-9]{6,15}$/;
									if(!pwd1){
                    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入密码');
                    return false;
                  }
									if(!pwd2){
                    parent.find('.pop-error').stop(true).fadeIn(200).text('请输入确认密码');
                    return false;
                  }
                  if(!pattern.test(pwd1)){
                    parent.find('.pop-error').stop(true).fadeIn(200).text('6-15位字母或数字');
                    return false;
                  }
                  if(pwd1!=pwd2){
                    parent.find('.pop-error').stop(true).fadeIn(200).text('密码和确认密码不一致');
                    return false;
                  }
                  if (jssubbutn2.hasClass('disabled')) {return false;}
                  jssubbutn2.addClass('disabled');
                  resetres = resetPassword(jssubbutn2,$account,$verify,pwd1);
                  if(!resetres){
                    return false;
                  }
                  popplog.close();
                  setTimeout(function(){
                    // 成功
                    popplog.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_receive_successful.png"><div class="pop-title">重置成功，新密码已经生效！</div><a href="javascript:;" class="pop-btn jsloginbutn">去登录</a></div>');              
                    $('.pop-close').click(function() {popplog.close();jssubbutn2.removeClass('disabled');});
                    $('.jsloginbutn').click(function() {
                      popplog.close();
                      setTimeout(function(){$('.jslogin').click();},810);
                    });
                    return false;
                    // 失败
                    popplog.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">提交失败</div><div class="pop-text">可能是网络原因，请重新提交</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm ">刷新</a></div>');
                    $('.jscancel').click(function() {popplog.close();jssubbutn2.removeClass('disabled');});
                    $('.pop-comfirm').click(function() {window.location.reload();});
                    
                  
                  },810);
                  
                  return false;  
                  
                });
                
                
              },210);
              
              return false;
            });
            
            $('.pop-arrow-left').click(function() {
              popplog.find('.fgbox').remove();
              popplog.find('.lrbox').fadeIn(200);
              return false;
            });
            
          },210);
        });
        
      });
      });
      
      
      
      
      
    });
    
    // 获取手机验证码
    function getcode(ele,type) {
      $(ele).click(function() {
        var that=$(this),parent=that.closest('form');
        if (that.hasClass('disabled')) {return false;}
        var phone = $.trim(parent.find('.account').val());
        parent.find('.pop-error').stop(true).fadeOut(200).text('');
				if (!phone) {
				parent.find('.pop-error').stop(true).fadeIn(200).text('请输入手机号');return false;
				}
        if (phone && /^1[34578][0-9]{9}$/.test(phone)) {
          // ajax
          $.ajax({
            type: "POST",
            url: checkaccount,
            data: {account:phone},
            dataType: "json",
            success: function(data){
              console.log(data);
              if(data.code&&type!=1){
                parent.find('.pop-error').stop(true).fadeIn(200).text('该手机已被占用');
                return false;
              }else if(data.code==0&&type==1){
                parent.find('.pop-error').stop(true).fadeIn(200).text('该手机号未绑定账号');
                return false;
              }else{
                  $.ajax({
                      type: "POST",
                      url: sendcodeurl,
                      data: {phone:phone,way:2},
                      dataType: "json",
                      success: function(data){
                        if(data.status){
                          clock(ele);
                        }else{
                          parent.find('.pop-error').stop(true).fadeIn(200).text(data.msg);
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
        } else {
          parent.find('.pop-error').stop(true).fadeIn(200).text('请输入正确的手机号');
        }
        return false;
      });
    }
    
    // 查看密码按钮事件
    function lookpwd(ele) {
      $(ele).click(function() {
        var that=$(this);
        var input = that.closest('.pop-cell').find('input');
        if (that.hasClass('on')) {
          that.removeClass('on');
          input.attr('type','password');
          $.trim(input.val())?input.addClass('pwd'):input.removeClass('pwd');
            
        } else {
          that.addClass('on');
          input.attr('type','text').removeClass('pwd');
        }
      });
    }
    
    function delinput(ele) {
      $(ele).click(function() {
        var that=$(this);
        
        that.removeClass('on').closest('.pop-cell').find('input').val('').removeClass('pwd').focus();

        return false;
      });
    }
    
    function checkinput(ele) {
      $(ele).keyup(function() {
        var that = $(this),val=$.trim(that.val()),parent=that.closest('form');
				var sib = that.closest('.pop-cell').find('.jsdel');
        parent.find('.txt').each(function(i,n) {
          if (parent.find(n).val().length<1) {parent.find('input[type=submit]').addClass('disabled');return false;}
          else {parent.find('input[type=submit]').removeClass('disabled');}
        });
        
        val.length<1 && that.removeClass('pwd');
        
        if (val) {
        
          that.attr('type')=='password'?that.addClass('pwd'):that.removeClass('pwd');
            
          sib.addClass('on');
          
          // 检测
          
          
        } else {
          that.removeClass('pwd');
          sib.removeClass('on');
        }
        
        return false;
      }).blur(function() {
        var that = $(this),val=$.trim(that.val()),parent=that.closest('form');
        parent.find('.pop-error').stop(true).fadeOut(200).text('');
        
        if (val) {
          // 检测
          
        }
      });
    }    
</script>