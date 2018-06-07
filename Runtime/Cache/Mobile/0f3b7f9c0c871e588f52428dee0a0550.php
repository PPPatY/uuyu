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
<header class="header user-index">
  <section class="wrap">
    <div class="caption"><span class="table"><span class="table-cell">我的</span></span></div>
    <?php if(is_login() > 0): ?><a href="javascript:;" class="hbtn right user-logout jslogout"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/my_quit.png"></span></span></a>
      <a href="<?php echo U('user_message');?>" class="hbtn right user-mege "><span class="table"><span class="table-cell"><span class="picwrap"><img src="/Public/Mobile/images/my_news.png"><?php if($newmsg == 1): ?><i class="circle"></i><?php endif; ?></span></span></span></a><?php endif; ?>
  </section>
</header>
<div class="occupy"></div>

<section class="trunker">
  
  <section class="banner">
    <div class="inner">
      <div class="wrap">
      <?php if(is_login() <= 0): ?><div class="loginbox no ">
          <img src="/Public/Mobile/images/my_head.png" class="portrait">
          <div class="textbox jsloginclick">
            <a href="javascript:;" class="butn loginbtn">登录</a>
            <a href="javascript:;" class="butn registerbtn">注册</a>
          </div>
        </div>
      <?php else: ?>
        <div class="loginbox yes">
          <?php if($userinfo['head_icon'] != ''): ?><img src="<?php echo ($userinfo['head_icon']); ?>" class="portrait">
          <?php else: ?>
            <img src="/Public/Mobile/images/my_head.png" class="portrait"><?php endif; ?>
          <div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" class="butn jssign <?php if($issignin == 1 ): ?>issignin<?php endif; ?>" data-score="<?php echo ($addpoint); ?>"><span>签到</span><i class="circle"></i></a></span></span></div>
          <div class="textbox clearfix">
            <p class="name clearfix"><span><?php echo ($userinfo["nickname"]); ?></span><a href="<?php echo U('user_nickname');?>" ></a></p>
            <p class="uid">UID:1000<?php echo ($userinfo["id"]); ?></p> 
          </div>
        </div><?php endif; ?>
    </div>
    </div>
  </section>
  <section class="inner">
    <section class="contain">
      <div class="user-money clearfix">
        <a href="javascript:;" data-href="<?php echo U('user_recharge');?>" class="butn rechargebtn"><img src="/Public/Mobile/images/my_pay.png"><span>充值</span></a>
        <a href="<?php if(is_login() > 0): echo U('user_balance'); else: ?>javascript:;<?php endif; ?>" class="butn balancebtn <?php if(is_login() <= 0): ?>jsbalancebtn<?php endif; ?>"><img src="/Public/Mobile/images/my_balance.png"><span>余额</span></a>
      </div>
      <div class="user-base user-same">
        <div class="clearfix table">
        <?php if($userinfo['real_name'] == ''): ?><!-- 未认证 -->
            <a data-href="<?php echo U('user_auth');?>" class="butn table-row" id="userauth">
              <span class="table-cell butn1"><img src="/Public/Mobile/images/my_real.png" class="icon"></span>
              <span class="table-cell butn2">实名认证</span>
              <span class="table-cell butn3"><span class="non-notice">未认证</span></span>
              <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
            </a>
        <?php else: ?>
          <!-- 已认证 -->
          <a data-href="<?php echo U('user_auth');?>" class="butn table-row" id="userauth">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_real.png" class="icon"></span>
            <span class="table-cell butn2">实名认证</span>
            <span class="table-cell butn3"><span class="authed"><img src="/Public/Mobile/images/my_real_head.png">已实名</span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a><?php endif; ?>
        <?php if($userinfo['phone'] == ''): ?><!-- 未绑定 -->
          <a data-href="<?php echo U('user_bind_phone');?>" class="butn table-row" id="bindphone">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_bind.png" class="icon"></span>
            <span class="table-cell butn2">绑定手机</span>
            <span class="table-cell butn3"><span class="non-notice">首次绑定送<?php echo ($bindpoint); ?>积分</span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a>
        <?php else: ?>
          <!-- 已绑定 -->
          <a data-href="<?php echo U('user_bind_phone');?>" class="butn table-row" id="bindphone">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_bind.png" class="icon"></span>
            <span class="table-cell butn2">绑定手机</span>
            <span class="table-cell butn3"><span class="binded"><?php echo substr($userinfo['phone'],0,3);?><i>*****</i><?php echo substr($userinfo['phone'],-3);?></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a><?php endif; ?>
        <?php if($userinfo['register_way'] <= 2): ?><a data-href="<?php echo U('user_password');?>" class="butn table-row" id="userpassword">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_password.png" class="icon"></span>
            <span class="table-cell butn2">修改密码</span>
            <span class="table-cell butn3"><span class="non-notice"></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a><?php endif; ?>
          <a data-href="<?php echo U('user_address');?>" class="butn table-row" id="useraddress">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_address.png" class="icon"></span>
            <span class="table-cell butn2">添加地址</span>
            <span class="table-cell butn3"><span class="non-notice"></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a>
        </div>
      </div>
      <div class="user-other user-same">
        <div class="clearfix table">
          <!-- 无礼包 -->
          <!-- 有礼包 -->
          <a href="user_gifted.html" class="butn mygiftbut table-row">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_gift.png" class="icon"></span>
            <span class="table-cell butn2">我的礼包</span>
            <span class="table-cell butn3"><span class="non-notice"></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a>
          <!-- 有收藏 -->
          <a href="user_collection.html" class="butn mycollbut table-row">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_collection.png" class="icon"></span>
            <span class="table-cell butn2">我的收藏</span>
            <span class="table-cell butn3"><span class="non-notice"></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a>
          <a href="user_contact.html" class="butn table-row">
            <span class="table-cell butn1"><img src="/Public/Mobile/images/my_contact.png" class="icon"></span>
            <span class="table-cell butn2">联系我们</span>
            <span class="table-cell butn3"><span class="non-notice"></span></span>
            <span class="table-cell butn4"><img src="/Public/Mobile/images/ma_more.png" class="icon-right"></span>
          </a>
        </div>
      </div>
    </section>
    
  </section>
</section>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<div class="space"></div>
<div class="pop"></div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
<script>
    var pop = $('.pop').pop();
    function nologin(msg){
      pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text"'+msg+'</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologin">去登录</a></div>');
      $('.jscancel').click(function() {pop.close();});
      $('.tologin').click(function(){
        pop.close();
        $('.jslogin').click();
      });
    }
    $(function() {
      userbind = "<?php echo ($userbind); ?>";
      phone = "<?php echo ($userinfo['phone']); ?>";
      if(userbind>0&&!phone){
        pop.addClass('pop-bind').open(200,'<a href="javascript:;" class="pop-close2 jsbindbox"></a><div class="pop-content"><img src="/Public/Mobile/images/pop_binding.png"><span class="text">送'+"<?php echo ($bindpoint); ?>"+'积分</span><a href="'+"<?php echo U('user_bind_phone');?>"+'" class="bindbutn jsbindbutn">去绑定</a></div>');
      }
      
      $('.jsbindbox').click(function() {pop.close(200);});
      
      $('.jsloginclick a').click(function() {
        $('.jslogin').click();
        if($(this).hasClass('registerbtn')){
          qiehuan($('.jspoptab a').eq(1),10);
          return false;
        }
 
          
        return false;
      });
      $user = "<?php echo is_login();?>";
      $(".rechargebtn").click(function(){
          if($user>0){
            location.href = $(this).attr('data-href');
          }else{
            nologin('>您还未登录账号，暂时无法充值哦');
            return false;
          }
      });
			
			$('.jsbalancebtn').click(function() {
					
        nologin('>您还未登录账号，暂时无法进入余额哦');
        return false;
          
			});
      $('.mygiftbut').click(function() {
        if(($user>0)==false){    
          nologin('>您还未登录账号，暂时无法进入我的礼包');
          return false;
        }
          
      });
      $('.mycollbut').click(function() {
        if(($user>0)==false){  
          nologin('>您还未登录账号，暂时无法进入我的收藏');
          return false;
        }
          
      });
      $('#userauth').click(function(){
          if($user>0){
            location.href = $(this).attr('data-href');
          }else{
            nologin('>您还未登录账号，暂时无法认证哦');
            return false;
          }
      });
      $('#bindphone').click(function(){
          if($user>0){
            location.href = $(this).attr('data-href');
          }else{
            nologin('>您还未登录账号，暂时无法绑定哦');
            return false;
          }
      });
      $('#userpassword').click(function(){
          if($user>0){
            location.href = $(this).attr('data-href');
          }else{
            nologin('>您还未登录账号，暂时无法修改哦');
            return false;
          }
      });
      $('#useraddress').click(function(){
          if($user>0){
            location.href = $(this).attr('data-href');
          }else{
            nologin('>您还未登录账号，暂时无法添加哦');
            return false;
          }
      });
      $('.jslogout').click(function() {
        var that=$(this);
        
        pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">确认退出？</div><div class="pop-text" style="text-align:left;">退出后不会删除任何历史数据，下次登录依然可以使用本账号。</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm jscomfirm">确定</a></div>');
        
        $('.jscancel').click(function() {pop.close();});
        
        $('.jscomfirm').click(function() {
          $.ajax({
            type:'post',
            url:"<?php echo U('logout');?>",
            success:function(data){
              location.href = location.href;
            },error:function(){
              layer.msg('服务器错误');
              location.href = location.href;
            }
          });
        });
        
      });
      ttt= $('.jssign');
      sss=ttt.find('span')
      $is_sign = ttt.hasClass('issignin');
      if($is_sign){
        if (ttt.hasClass('disabled')) {return false;}
        ttt.addClass('disabled');
        ttt.find('.circle').remove();
        sss.text('已签到');
      }
      
      $('.jssign').click(function() {
        var that = $(this),span=that.find('span'),i = '+'+that.attr('data-score');
        if (that.hasClass('disabled')) {return false;}
        that.addClass('disabled');
        if ($user>0) {
            $.ajax({
              type:'post',
              url:"<?php echo U('PointShop/user_sign_in');?>",
              success:function(data){
                if(data.status==1){
                  span.addClass('hide');
                  setTimeout(function(){
                    span.empty().removeClass('hide');
                    $('<i style="top:100%;position:absolute;left:0;right:0;">'+i+'</i>').prependTo(span).animate({
                      top:0,
                    },500,function(){
                      that.find('.circle').fadeOut(550);
                      $(this).delay(250).animate({top:'-100%'},250,function() {
                        $(this).remove();
                        that.find('.circle').remove();
                        
                        $('<i style="display:none;">已签到</i>').appendTo(span).fadeIn("slow");
                      });
                    });
                  },250);
                }else{
                  layer.msg(data.msg);
                }
              },error:function(){

              }
            })
            
        } else {
          // 未登录 则弹出登录框
          pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法签到哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologin">去登录</a></div>');
          $('.jscancel').click(function() {pop.close();});
          $('.tologin').click(function(){
            pop.close();
            $('.jslogin').click();
          });
        }
        
      });
      
    });
    
</script>
	

    <footer class="footer">
      <section class="wrap">
        <div class="nav table">
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Index"): ?>active<?php endif; ?>" href="<?php echo U('Index/index');?>"><i class="icon icon-home"></i><span>首页</span></a>
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

            var gsurl=$(this).attr('href');

            var index = gsurl .lastIndexOf("\/");
            var gsid  = gsurl .substring(index + 1, gsurl .length);
            var result='';
            result += '<input type="hidden"  value="'+gsid+'" id="goodsid"/>';
            $("#lblTagIdUserName").append(result);

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