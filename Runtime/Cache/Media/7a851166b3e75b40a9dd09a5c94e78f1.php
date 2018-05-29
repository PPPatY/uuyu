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
    <link href="/Public/Media/css/swiper.min.css" rel="stylesheet" >
    <link href="/Public/Media/css/common.css" rel="stylesheet" >
    <link href="/Public/Media/css/pc.css" rel="stylesheet">
    
<link href="/Public/Media/css/user.css" rel="stylesheet" >

  </head>
  <body>
    

    <div class="main">
      <div class="pc-header">
        <div class="pc-inner clearfix">
          <div class="pc-download">
            <a href="javascript:;" class="pc-downloadbtn"><i class="pc-icon-phone"></i><span>下载APP</span></a>
            <div class="pc-qrcode clearfix">
             <div class="pc-ios"><img src="<?php echo U('Base/appdownQrcode',array('url'=>base64_encode(base64_encode('http://'.$_SERVER['HTTP_HOST'].$ios))));?>"><span>扫码下载</span></div>
            </div>
          </div>
          <a href="<?php echo U('Index/index');?>" class="pc-logo"><?php if(!empty($union_set)): ?><img src="<?php echo get_cover($union_set['pc_logo'],'path');?>"><?php else: ?><img src="<?php echo get_cover(C('PC_SET_LOGO'),'path');?>"><?php endif; ?></a>
        </div>
      </div>
      <div class="pc-container">
        <div class="pc-wrap">
        <div class="pc-inner">
          <div class="pc-mobile">
						<div class="pc-full-box">
            <div class="pc-iframe">
              
<header class="header">
  <section class="wrap">
    <a href="<?php echo U('user');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a>
    <div class="caption"><span class="table"><span class="table-cell">实名认证</span></span></div>
  </section>
</header>

              
              
<div class="mainer user-auth">
<div class="occupy"></div>

<section class="trunker">
  <section class="inner">
    <section class="contain">
  <?php if($userinfo['real_name'] == '' || $userinfo['idcard'] == ''): ?><!-- 未认证  -->
      <form action="" class="">
      <div class="auth-info info-table jsauthinfo">
        <div class="wrap">
            <div class="item table">
              <span class="table-cell item1">真实姓名</span>
              <span class="table-cell item2"><input type="text" name="real_name" class="txt" placeholder="请输入您的真实姓名" value=""></span>
            </div>
            <div class="item table">
              <span class="table-cell item1">证件号码</span>
              <span class="table-cell item2"><input type="text" name="idcard" class="txt" placeholder="请输入您的证件号码" value=""></span>
            </div>
        </div>
      </div>
      <div class="auth-butn info-input-butn">
        <input type="submit" class="butn disabled jssubmit" value="提交">
        <p class="error-text auth-error hidden"></p>
      </div>
      <p class="auth-notice">
        根据2010年8月1日实施的《网络游戏管理暂行办法》，网络游戏用户需使用有效身份证件进行实名认证。为保证流畅游戏体验，享受健康游戏生活，请广大玩家尽快实名认证，且每个身份证件号码只能认证一次。
      </p>
      </form>


 <?php else: ?>

      <!-- 已认证 -->
      <div class="authed-info">
        <img src="/Public/Media/images/fq/my_real_already.png" class="portrait">
        <div class="title">您已进行过实名认证</div>
        <table>
          <tr><td>真实姓名：</td><td><?php echo ($userinfo['real_name']); ?></td></tr>
          <tr><td>身份证号：</td><td><?php echo substr($userinfo['idcard'],0,1);?>***********<?php echo substr($userinfo['idcard'],-1);?></td></tr>
        </table>
      </div><?php endif; ?>
    </section>
  </section>
</section>
</div>


              
<div class="pop"></div>

              
              <div class="popplog"></div>
              
            </div>
						<!-- <a href="javascript:;" class="pc-screen-btn jsscreen"><i class="pc-screen"></i></a> -->
						</div>
            <a href="javascript:history.back();" class="pc-butn"><i class="pc-icon"></i></a>
          <div class="pc-theme"><img class="pc-theme-pic" src="/Public/Media/images/iframe/theme.png"></div>
          </div>
          <div class="pc-sys"><div class="pc-qrcode-box"><img class="pc-qrcode-sys" src="<?php echo get_cover(C('PC_SET_QRCODE'),'path');?>"></div><p>扫码关注微信 在手机上玩</p></div>
          <div class="pc-container-footer">
						<div class="pc-copyright">
							<p class="pc-cr">
								<span><img src="/Public/Media/images/iframe/pc_ghs.png"><?php echo C('WEB_BEIAN');?></span>
								<span>经营性许可证：<?php echo C('WEB_SITE');?></span>
								<span><?php echo C('PC_GUAN');?></span>
							</p>
							<p>抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</p>
						</div>
					</div>
        </div>
      </div>
      </div>
    </div>
    
    <div class="loginbutdiv hidden">
        <?php if($wx_login == 1): ?><a href="javascript:;" onclick="register('weixin')" class="butn"><img src="/Public/Media/images/login_third_weixin.png"></a><?php endif; ?>
        <?php if($qq_login == 1): ?><a href="javascript:;" onclick="register('qq')" class="butn"><img src="/Public/Media/images/login_third_qq.png"></a><?php endif; ?>
        <?php if($wb_login == 1): ?><a href="javascript:;" onclick="register('weibo')" class="butn"><img src="/Public/Media/images/login_third_sina.png"></a><?php endif; ?>
        <?php if($bd_login == 1): ?><a href="javascript:;" onclick="register('baidu')" class="butn"><img src="/Public/Media/images/login_third_tencent.png"></a><?php endif; ?>
    </div>
    
    <script src="/Public/Media/js/jquery-1.11.1.min.js"></script>
    <script src="/Public/Media/js/swiper-3.4.2.jquery.min.js"></script>
    <script src="/Public/static/layer/layer.js"></script>
    <script src="/Public/Media/js/pop.lwx.min.js"></script>
    <script src="/Public/Media/js/common.js"></script>
    
<script>
    $(function() {
      var pop = $('.pop').pop();
      
      $('.jsauthinfo .txt').keyup(function() {
        
        $('.jsauthinfo .txt').each(function(i,n) {
          if ($(n).val().length<1) {$('.jssubmit').addClass('disabled');return false;}
          else {$('.jssubmit').removeClass('disabled');}
        });
        
        return false;
      });
      
      
      $('.jssubmit').click(function() {
        var that=$(this);
        if (that.hasClass('disabled')) {return false;}
        that.addClass('disabled');
        $name = $('input[name="real_name"]').val();
        $idcard = $('input[name="idcard"]').val();
        if($idcard.length>18){
          $('.auth-error').removeClass('hidden').text('身份证号码错误');
          $('.jssubmit').addClass('disabled');return false;
        }
        $.ajax({
          type:'post',
          url:"<?php echo U('isChineseName');?>",
          data:{name:$name},
          success:function(data){
            if(data.status!=1){
              $('.auth-error').removeClass('hidden').text(data.msg);
            }else{
              $.ajax({
                type:'post',
                url:"<?php echo U('isidcard');?>",
                data:{idcard:$idcard,name:$name},
                success:function(data){
                  if(data.status!=1){
                    $('.auth-error').removeClass('hidden').text(data.msg);
                  }else{
                    $.ajax({
                      type:'post',
                      url:"<?php echo U('bind_idcard');?>",
                      data:{name:$name,idcard:$idcard},
                      success:function(data){
                        console.log(data);
                        if(data.status!=1&&data.status!=0){
                          $('.auth-error').removeClass('hidden').text(data.msg);
                        }else if(data.status!=1){
                          pop.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_authentication_fail.png"><div class="pop-title">提交失败</div><a href="javascript:;" class="pop-btn jscomfirm">再试一次</a></div>');
                          $('.jscomfirm').click(function() {pop.close();that.removeClass('disabled');});
                        }else{
                          // 成功
                          pop.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_authentication_success.png"><div class="pop-title">实名认证成功</div><a href="user.html" class="pop-btn2">返回个人中心</a></div>'); 
                        }
                      },error:function(XMLHttpRequest, textStatus, errorThrown){
                        pop.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_authentication_fail.png"><div class="pop-title">提交失败</div><a href="javascript:;" class="pop-btn jscomfirm">再试一次</a></div>');
                        $('.jscomfirm').click(function() {pop.close();that.removeClass('disabled');});
                      }
                    });
                  }
                },error:function(XMLHttpRequest, textStatus, errorThrown){
                  pop.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_authentication_fail.png"><div class="pop-title">提交失败</div><a href="javascript:;" class="pop-btn jscomfirm">再试一次</a></div>');
                  $('.jscomfirm').click(function() {pop.close();that.removeClass('disabled');});
                }
              });
            }
          },error:function(XMLHttpRequest, textStatus, errorThrown){
            pop.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_authentication_fail.png"><div class="pop-title">提交失败</div><a href="javascript:;" class="pop-btn jscomfirm">再试一次</a></div>');              
            $('.jscomfirm').click(function() {pop.close();that.removeClass('disabled');});
          }
        });
        
        pop.find('.pop-close').click(function() {pop.close();that.removeClass('disabled');});
        return false;
      });

      
    });
    
</script>

    <?php echo ($logdiv); ?>
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
    <script>  
      $('.pc-download').hover(function(){$('.pc-qrcode').fadeIn();},function(){$('.pc-qrcode').fadeOut();});
      
      function resizephone() {
          var winHeight = $( window ).height();
					
					
							var hedh = $('.pc-header').height();
							var both = $('.pc-container-footer').height();
							var pch = winHeight-hedh;
							if($(window).width()>720)
								$('.pc-container').css({'height':pch+'px'});
							var mobheight = pch-both;
							
          var mobwidth = parseInt(mobheight*512/1000);
					
					
          var scale = parseInt(mobwidth/410*100);

              $('html').css('font-size',231*scale/100+'%');
							
      }
      resizephone();
      var resizephonetime = null;
      $(window).resize(function() {
        if (resizephonetime) {clearTimeout(resizephonetime);}
        resizephonetime = setTimeout(function() {
				if($(window).width()<=720) {resizephone();}
        },10);
      });
			
			$('.jsscreen').click(function() {
				var that = $(this);
				if (that.hasClass('full')) {
					that.removeClass('full').css({'right':'-1.2rem'});
					$('.pc-full-box').removeClass('full-iframe').unwrap();
				} else {
					that.addClass('full');
					that.css({'right':'-0.76rem'});
					$('.pc-full-box').addClass('full-iframe').wrap('<div style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1;background:#000"></div>');				
				}
				
			});
      
    </script>
    
  </body>
</html>