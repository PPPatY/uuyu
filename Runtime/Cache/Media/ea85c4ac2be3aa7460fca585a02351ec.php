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
    
<link href="/Public/Media/css/mall.css" rel="stylesheet" >

  </head>
  <body>
    

    <div class="main">
      <div class="pc-header">
        <div class="pc-inner clearfix">
          <div class="pc-download">
            <a href="javascript:;" class="pc-downloadbtn"><i class="pc-icon-phone"></i><span>扫码关注微信公众号</span></a>
            <div class="pc-qrcode clearfix">
             <div class="pc-ios"><img src="/Public/Media/images/iframe/Qr-code-u-uuyu.jpg"><span>扫我关注</span></div>
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
              
<header class="header mall-index integral-header">
  <section class="wrap">
    <?php if(I('get.from') == ''): ?><a href="<?php echo U('mall');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span><span class="table-cell word">商城</span></span></a>
    <?php else: ?>
    <a href="javascript:history.go(-1);" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a><?php endif; ?>
    <div class="caption"><span class="table"><span class="table-cell">积分任务</span></span></div>
    <a href="<?php echo U('mall_rule');?>" class="hbtn right mall-rule"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_rule.png"></span></span></a>
    <a href="<?php echo U('mall_record');?>" class="hbtn right mall-reco"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_record.png"></span></span></a>
  </section>
</header>

              
              
<div class="mainer">
<div class="occupy"></div>

<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<section class="trunker">
  
  <section class="banner integral-banner">
    <div class="inner">
      <div class="myscore">我的积分：<span><?php echo ($userpoint); ?></span></div>
    </div>
  </section>
  <section class="inner">
    <section class="contain">
      <div class="list integral-list">
        <ul class="clearfix">
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Media/images/mall_task_sign.png" class="icon"></div>
              <!-- 未签到 -->
              <?php if($issignin != 1): ?><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" data-url="<?php echo U('mall_sign',array('from'=>'mall_integral'));?>" data-msg="签到" class="butn signbutn jumpurl">去签到</a></span></span></div>
                <div class="textbox">
                  <div class="name">签到</div>
                  <p class="info">今日签到可获积分</p>
                  <p class="reward"><img src="/Public/Media/images/mall_task_integral.png"><span>+<?php echo ($addpoint); ?></span></p>
                </div>
               <?php else: ?> 
              <!-- 已签到 hidden-->
                <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('mall_sign',array('from'=>'mall_integral'));?>" class="butn signbutn disabled">已签到</a></span></span></div>
                <div class="textbox">
                  <div class="name">签到</div>
                  <p class="info">明日签到可获积分</p>
                  <p class="reward"><img src="/Public/Media/images/mall_task_integral.png"><span>+<?php echo ($topoint); ?></span></p>
                </div><?php endif; ?>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Media/images/mall_task_bind.png" class="icon"></div>
              <?php if($bindphone == ''): ?><!-- 未绑定 -->
                <div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" data-url="<?php echo U('Subscriber/user_bind_phone',array('from'=>'mall_integral'));?>" data-msg="绑定手机号" class="butn bindbutn jumpurl">去绑定</a></span></span></div>
              <?php else: ?>
                <!-- 已绑定 -->
                <div class="butnbox "><span class="table"><span class="table-cell"><a href="<?php echo U('Subscriber/user_bind_phone',array('from'=>'mall_integral'));?>" class="butn bindbutn disabled">已绑定</a></span></span></div><?php endif; ?>
              <div class="textbox">
                <div class="name">绑定手机号</div>
                <p class="info">绑定手机号赠送积分</p>
                <p class="reward"><img src="/Public/Media/images/mall_task_integral.png"><span>+<?php echo ($list['bind_phone']['point']); ?></span><span class="reward-phone"> (每个手机号仅限首次绑定)</span></p>
              </div>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Media/images/mall_task_first.png" class="icon"></div>
              <?php if($firstspend == ''): ?><!-- 未首充 -->
              <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('Game/index');?>" class="butn frstbutn">开始</a></span></span></div>
              <?php else: ?>
              <!-- 已首充 -->
              <div class="butnbox hidden"><span class="table"><span class="table-cell"><a href="<?php echo U('Game/index');?>" class="butn frstbutn disabled">已首充</a></span></span></div><?php endif; ?>
              <div class="textbox">
                <div class="name">每日游戏首充</div>
                <p class="info">每日首次充值任意一款游戏</p>
                <p class="reward"><img src="/Public/Media/images/mall_task_integral.png"><span>+<?php echo ($list['share_game']['point']); ?></span>
                <span style="font-size:0.32rem;color:#999;display:inline-block;margin-top:-2%;vertical-align:middle;"> (绑币除外)</span>
                </p>
              </div>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Media/images/mall_task_pay.png" class="icon"></div>
              <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('Game/index');?>" class="butn rechbutn">开始</a></span></span></div>
              <div class="textbox">
                <div class="name">游戏充值</div>
                <p class="info">每充值1元即可获得10积分</p>
                <span style="font-size:0.32rem;color:#999;display:inline-block;margin-top:-2%;vertical-align:middle;"> 长期有效(绑币除外)</span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </section>
    
  </section>
</section>
</div>


              
              
              <div class="popplog"></div>
              
            </div>
						<!-- <a href="javascript:;" class="pc-screen-btn jsscreen"><i class="pc-screen"></i></a> -->
						</div>
            <a href="javascript:history.back();" class="pc-butn"><i class="pc-icon"></i></a>
          <div class="pc-theme"><img class="pc-theme-pic" src="/Public/Media/images/iframe/theme.png"></div>
          </div>
          <div class="pc-sys"><div class="pc-qrcode-box"><img class="pc-qrcode-sys" src="<?php echo get_cover(C('PC_SET_QRCODE'),'path');?>"></div><p>扫码即玩 快乐游戏</p></div>
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
    
<div class="popmsg"></div>
<script src="/Public/Media/js/pop.lwx.min.js"></script>
<script src="/Public/Media/js/common.js"></script>
<script>
    $(function() {
			$user = "<?php echo is_login();?>";
			var popmsg = $('.popmsg').pop();
		function loginjs(type,msg){
            if(type==1){
              popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法'+msg+'哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologinsss">去登录</a></div>');
              $('.jscancel').click(function() {popmsg.close();});
              popmsg.find('.tologinsss').click(function() {popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);});
            }else{
              $('.jslogin').click();
            }
            
            return false;
          }
      $('.jumpurl').click(function() {
				var that = $(this);
				if($user<=0){
					loginjs(0,that.attr('data-msg'));
					return false;
				} else {
					window.location.href=that.attr('data-url');
				}
				
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