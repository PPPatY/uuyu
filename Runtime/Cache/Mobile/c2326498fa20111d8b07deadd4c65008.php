<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
  <head>
    <meta charset="UTF-8">
    <?php if(ACTION_NAME == index and CONTROLLER_NAME == Index): ?><title><?php echo seo_replace(C('media_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_index.seo_description');?>">
    <?php elseif(ACTION_NAME == index and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_list.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_list.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_list.seo_description');?>">
    <?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_detail.seo_title'),array('game_name'=>$data['game_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == index and CONTROLLER_NAME == Gift): ?>
      <title><?php echo seo_replace(C('media_gift_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_gift_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_gift_index.seo_description');?>">
    <?php elseif(ACTION_NAME == giftdetail and CONTROLLER_NAME == Gift): ?>
      <title><?php echo seo_replace(C('media_gift_detail.seo_title'),array('game_name'=>$data['game_name'],'giftbag_name'=>$data['giftbag_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_gift_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_gift_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == mall and CONTROLLER_NAME == PointShop): ?>
      <title><?php echo seo_replace(C('media_shop.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_shop.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_shop.seo_description');?>">
    <?php elseif(ACTION_NAME == mall_detail and CONTROLLER_NAME == PointShop): ?>
      <title><?php echo seo_replace(C('media_shop_detail.seo_title'),array('good_name'=>$data['good_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_shop_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_shop_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Article): ?>
      <title><?php echo seo_replace(C('media_news_detail.seo_title'),array('title'=>$data['title']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_news_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_news_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == user_recharge and CONTROLLER_NAME == Subscriber): ?>
      <title><?php echo seo_replace(C('media_recharge.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_recharge.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_recharge.seo_description');?>">
    <?php elseif(CONTROLLER_NAME == Service): ?>
      <title><?php echo seo_replace(C('media_service.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_service.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_service.seo_description');?>">
		<?php elseif(ACTION_NAME == 'open_game' and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_detail.seo_title'),array('game_name'=>$game_name),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_detail.seo_description');?>">
    <?php else: ?>
      <title><?php echo seo_replace(C('media_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_index.seo_description');?>"><?php endif; ?>
    <link href="<?php echo get_cover(C('PC_SET_ICO'),'path');?>" type="image/x-icon" rel="shortcut icon">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="applicable-device" content="mobile">
		<link rel="apple-touch-icon" href="/Public/Mobile/images/touch-icon-iphone.png" />

		
		<meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
		
    <link href="/Public/Mobile/css/swiper.min.css" rel="stylesheet" >
    <link href="/Public/Mobile/css/common.css" rel="stylesheet" >
    <script src="/Public/Mobile/js/jquery-1.11.1.min.js"></script>
    <script src="/Public/Mobile/js/swiper-3.4.2.jquery.min.js"></script>
    <script src="/Public/Mobile/js/common.js"></script>
    <script src="/Public/Mobile/js/pop.lwx.min.js"></script>
  </head>
  <body <?php if(CONTROLLER_NAME == 'Search'): ?>class="searchpage" <?php elseif(CONTROLLER_NAME == 'Article'): ?>class="newspage" <?php elseif(ACTION_NAME == 'mall_record'): ?> class="integral-record"<?php elseif(ACTION_NAME == 'user_balance'): ?> class="user-balance"<?php elseif(ACTION_NAME == 'user_nickname'): ?> class="user-nickname"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_phone'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_modify'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_modifyed'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_password'): ?> class="user-auth" <?php elseif(ACTION_NAME == 'user_address'): ?> class="user-balance" <?php elseif(ACTION_NAME == 'user_gift'): ?> class="user-gift" <?php elseif(ACTION_NAME == 'user_collectioned'): ?> class="user-collection" <?php elseif(ACTION_NAME == 'user_collection'): ?> class="user-collection" <?php elseif(ACTION_NAME == 'user_contact'): ?> class="user-contact" <?php elseif(ACTION_NAME == 'user_argeement'): ?> class="user-contact"<?php elseif(ACTION_NAME == 'user_message'): ?> class="user-message"<?php elseif(ACTION_NAME == 'mall' and $data["count"] == 0 ): ?> class="mall-empty-mainer"<?php endif; ?> >
    
<link href="/Public/Mobile/css/user.css" rel="stylesheet" >
<style>
.footer{
  display: none;
}
</style>
    <header class="header">
      <section class="wrap">
        <?php if(I('get.from') == 'mall_integral'): ?><a href="<?php echo U('PointShop/mall_integral');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a>
        <?php else: ?>
          <a href="<?php echo ($HTTP_REFERER); ?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a><?php endif; ?>
        <div class="caption"><span class="table"><span class="table-cell">验证手机号</span></span></div>
      </section>
    </header>
    <div class="occupy"></div>
    
    <section class="trunker">
      <?php if($userinfo['phone'] == ''): ?><section class="inner">
        <section class="contain">

          <form action="" class="user-bind-form">
          <div class="bind-info info-table jsauthinfo">
            <div class="wrap">
                <div class="item table">
                  <span class="table-cell item1">手机号</span>
                  <span class="table-cell item2"><input type="text" id="mobile" name="" class="txt" placeholder="请输入您的手机号" value=""></span>
                </div>
                <div class="item table codewrap">
                    <span class="table-cell item1">验证码</span>
                    <span class="table-cell item2"><input type="text" name="" id="codenum" class="txt" placeholder="请输入验证码" value=""></span>
                    <span class="table-cell item3"><a href="javascript:;" class="btn getcode">获取验证码</a></span>
                </div>
            </div>
          </div>
          <div class="bind-butn info-input-butn">
            <input type="submit" class="butn disabled jssubmit" value="提交">
            <p class="error-text auth-error hidden"></p>
          </div>
          </form>
          
        </section>
        
      </section>
      <?php else: ?>
          <section class="inner">
            <section class="contain">

              <div class="binded-info">
                <img src="/Public/Mobile/images/my_bind_already.png" class="pic">
                <div class="title">您绑定的手机号：<?php echo substr($userinfo['phone'],0,3);?>******<?php echo substr($userinfo['phone'],-3);?></div>
                <p class="binded-info-text">
                  绑定手机后，您可以通过手机号找回密码<br/>同时提高账户安全性<br/>如需更换手机号请先解绑手机
                </p>
                <div class="butnbox">
                  <a href="javascript:;" data-url="<?php echo U('user_bind_modify');?>" class="butn jsunbind">解绑手机号</a>
                </div>
              </div>
              
            </section>
            
          </section><?php endif; ?>
    </section>
    
    
    <div class="pop"></div>
    <script src="/Public/Mobile/js/pop.lwx.min.js"></script>
    <script src="/Public/Mobile/js/common.js"></script>
    <script>
        checkaccount="<?php echo U('Subscriber/checkaccount');?>";
        sendcodeurl="<?php echo U('Subscriber/telsvcode');?>";
        checkphoneexsite = "<?php echo U('Subscriber/checkphoneexsite');?>";
        zhucepurl="<?php echo U('Subscriber/check_tel_code');?>";
        $(function() {
          var pop = $('.pop').pop();
          
          $('.jsauthinfo .txt').keyup(function() {
            
            $('.jsauthinfo .txt').each(function(i,n) {
              $('.auth-error').addClass('hidden').text('');
              if ($(n).val().length<1) {$('.jssubmit').addClass('disabled');return false;}
              else {$('.jssubmit').removeClass('disabled');}
            });
            
            
            return false;
          });
					
					$('.jsunbind').click(function() {
						var that=$(this),url= that.attr('data-url');
        
						pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">解除绑定？</div><div class="pop-text" style="text-align:left;">解除绑定后将无法通过手机号找回密码</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm jscomfirm">确定</a></div>');
						
						pop.find('.jscancel').click(function() {pop.close();});
						
						pop.find('.jscomfirm').click(function() {
							window.location.href=url;
						});
						return false;
					});
          
          $('.getcode').click(function() {
            var that=$(this),parent=that.closest('form');
            if (that.hasClass('disabled')) {return false;}
            var phone = $.trim($('#mobile').val());
            parent.find('.auth-error').stop(true).fadeOut(200).addClass('hidden').text('');
            if (phone && /^1[34578][0-9]{9}$/.test(phone)) {
              // ajax
              $.ajax({
                type: "POST",
                url: checkphoneexsite,
                data: {phone:phone},
                dataType: "json",
                success: function(data){
                  console.log(data);
                  if(data.status){
                    parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text('手机号已被使用');
                    return false;
                  }else{
                      $.ajax({
                          type: "POST",
                          url: sendcodeurl,
                          data: {phone:phone,way:2},
                          dataType: "json",
                          success: function(data){
                            if(data.status){
                              clock(that);
                            }else{
                              parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text(data.msg);
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
              parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text('请输入正确的手机号');
            }
            return false;
          });
           getcode('.getcode');
          
          $('.jssubmit').click(function() {
            var that=$(this);
            parent=that.closest('form');
            if (that.hasClass('disabled')) {return false;}
            that.addClass('disabled');
            phone = $.trim($('#mobile').val());
            verify = $("#codenum").val();//验证码
            if(!(/^1[34578]\d{9}$/.test(phone))){
              parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text('请输入正确的手机号');
              return false;
            }
            if(!verify){
              parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text('请输入短信验证码');
              return false;
            }

            $.ajax({
                type: "POST",
                url: zhucepurl,
                data: {account:phone,type:'',verify:verify,way:3},
                dataType: "json",
                success: function(data){
                  if(data.status==0){
                    parent.find('.auth-error').stop(true).fadeIn(200).removeClass('hidden').text(data.msg);
                    return false;
                  }else if(data.status){
                    //成功
                    if(data.firstbid>0){
											$msg = '<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_bind_successfully.png"><div class="pop-title">';
                      $msg +='绑定成功！获得'+data.firstbid+'积分！</div><a href="'+"<?php echo U('user_balance');?>"+'" class="pop-btn">查看积分</a></div>'; 
                      pop.addClass('pop-notice').open('',$msg);
                    }else{
                      $msg = '<div class="pop-content"><div class="pop-title">绑定成功</div><div class="pop-text">绑定手机号：'+phone+'</div><div class="pop-text-voic">由于手机号重复绑定，本次绑定无法获得奖励积分</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jscancel">知道了</a></div>';
                      pop.addClass('pop-prompt pop-prompt3').open('',$msg);
                    }
                    $url = "<?php echo ($HTTP_REFERER); ?>";
                    pop.find('.pop-close').click(function() {location.href=$url;});
                    pop.find('.jscancel').click(function() {location.href=$url;});
                    
                  }
                },
                error:function(){
                    // // 失败
                    pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">绑定失败</div><div class="pop-text">可能是网络原因，请重新提交</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 refresh">重试</a></div>');
                    $('.refresh').click(function() {location.reload();});
                    return false;
                }
            });            
            
            
            return false;
          });

          
        });
    </script>
	

    <footer class="footer">
      <section class="wrap">
        <div class="nav table">
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Index"): ?>active<?php endif; ?>" href="<?php echo U('Index/index#new');?>"><i class="icon icon-home"></i><span>首页</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Game"): ?>active<?php endif; ?>" href="<?php echo U('Game/index#categroy');?>"><i class="icon icon-game"></i><span>游戏</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Gift"): ?>active<?php endif; ?>" href="<?php echo U('Gift/index#all');?>"><i class="icon icon-gift"></i><span>礼包</span></a>
          <a class="jsshopmall item table-cell <?php if(CONTROLLER_NAME == "PointShop"): ?>active<?php endif; ?>" href="<?php echo U('PointShop/mall');?>"><i class="icon icon-mall"><?php if($mallissignin == 1): ?><i class="circle"></i><?php endif; ?></i><span>商城</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Subscriber"): ?>active<?php endif; ?>" href="<?php echo U('Subscriber/user');?>"><i class="icon icon-user"><?php if($newmsg == 1): ?><i class="circle"></i><?php endif; ?></i><span>我的</span></a>
        </div>
      </section>
    </footer>
    <?php echo ($logdiv); ?>
    <div class="loginbutdiv hidden">
        <?php if($wx_login == 1 and is_weixin()): ?><a href="javascript:;" onclick="register('weixin')" class="butn"><img src="/Public/Mobile/images/login_third_weixin.png"></a><?php endif; ?>
        <?php if($qq_login == 1): ?><a href="javascript:;" onclick="register('qq')" class="butn"><img src="/Public/Mobile/images/login_third_qq.png"></a><?php endif; ?>
        <?php if($wb_login == 1): ?><a href="javascript:;" onclick="register('weibo')" class="butn"><img src="/Public/Mobile/images/login_third_sina.png"></a><?php endif; ?>
        <?php if($bd_login == 1): ?><a href="javascript:;" onclick="register('baidu')" class="butn"><img src="/Public/Mobile/images/icon_baidu@3x.png"></a><?php endif; ?>
    </div>
    <a style="display:none" href="#shell/WXThirdLogin" id="weixinThirdLogin"></a>
    <a style="display:none" href="#shell/QQThirdLogin" id="qqThirdLogin"></a>
    <form id="app_third_login" action="<?php echo U('Subscriber/app_third_login');?>" method="post" enctype="multipart/form-data" style="display:none">
      <input type="text" name="openID" id="openID"/></p>
      <input type="text" name="nickName" id="nickName"  />
      <input type="text" name="icon" id="icon"/>
      <input type="text" name="logintype" id="logintype"/>
      <input type="text" name="gid" value="<?php echo I('game_id');?>"/>
      <input type="text" name="pid" value="<?php echo (session('union_host')['union_id'])?(session('union_host')['union_id']):$promote_id;?>"/>
      <input type="submit" value="提交" />
    </form>
    <script>
      var gid = "<?php echo ($game_id); ?>";
      var pid = "<?php echo ($promote_id); ?>";
      var open_name_auth = 0;
      var name_auth_tip = '';
      var $bindphoneadd = "<?php echo ($bindphoneadd); ?>";
      <?php if($open_name_auth == 1): ?>var open_name_auth = "<?php echo ($open_name_auth); ?>";
          var name_auth_tip = "<?php echo ($name_auth_tip); ?>";<?php endif; ?>
    </script>
    <script>

        $("body").on("click",'.butnbox a.butnlogin',function(){
            res = jslogin();
            return res;
        });
        function jslogin(){
            $uid = "<?php echo is_login();?>";
            if($uid<=0){
              $('.jslogin').click();
              return false;
            }else{
              return true;
            }
        }
    </script>
    <script type="text/javascript">
      url = "<?php echo ($_SERVER['HTTP_HOST']); ?>"+"<?php echo U('Subscriber/serverNotice');?>";
      // WebSocketTest(url);
      function WebSocketTest(url){
        if ("WebSocket" in window)
        {
           // 打开一个 web socket
           var ws = new WebSocket("ws://"+url);
           ws.onopen = function()
           {
              // Web Socket 已连接上，使用 send() 方法发送数据
              ws.send("发送数据");
              alert("数据发送中...");
           };
           ws.onmessage = function (evt) 
           { 
              var received_msg = evt.data;
              alert("数据已接收...");
           };
           ws.onclose = function()
           { 
              // 关闭 websocket
              alert("连接已关闭..."); 
           };
        }
        else
        {
           // 浏览器不支持 WebSocket
           alert("您的浏览器不支持 WebSocket!");
        }
      }
    </script>
  </body>
</html>