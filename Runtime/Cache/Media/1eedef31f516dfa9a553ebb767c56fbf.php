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
    <link href="/Public/Media/css/swiper.min.css" rel="stylesheet" >
    <link href="/Public/Media/css/common.css" rel="stylesheet" >
    <link href="/Public/Media/css/pc.css" rel="stylesheet">
    
<link href="/Public/static/dist/dropload.css" rel="stylesheet" >
<link href="/Public/Media/css/suspension.css" rel="stylesheet" >

  </head>
  <body>
    

     <div class="main">
      
      
  <div class="pc-container2 " >
    <div class="pc-wrap2">
  <div class="pc-game-box-wrap jsgbw">
  <div class="pc-game-box ">
    <div id="sdkdiv">
      <?php echo ($paysdk); ?>
    </div>
    <iframe id="op_url_mainframe" src="<?php echo ($login_url); ?>" style="background-image:url(<?php echo ($game_load_page); ?>);" frameborder="0" class="gamestartbox hidden"></iframe>
    <div class="pc-wrap jsloadpic">
      <div class="pc-inner">
        <div class="pc-loadbox">
          <div class="pc-mark"><img src="/Public/Media/images/slogan.png"></div>
          <div class="pc-load"><img src="/Public/Media/images/loading.gif"></div>
          <p class="pc-notice">进入本游戏意味着您已阅读并同意本公司在“游戏中心”的用户条例</p>
        </div>
      </div>
    </div>
    
    <?php if(is_login() > 0): echo W('Game/suspension');?><!-- 悬浮窗 --><?php endif; ?>
  </div>
  </div>
  </div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
</div>
<div class="pop"></div>


      
      <div class="popplog"></div>
      
      
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
      function jslogin(){
          $uid = "<?php echo is_login();?>";
          if($uid<=0){
            $('.jslogin').click();
            return false;
          }else{
            return true;
          }
      }
      $("body").on("click",'.butnbox a.butnlogin',function(){
        res = jslogin();
        return res;
      });
    </script>
    
<style>
        .cross_screen  {
            transform: rotate(-90deg) ;
            -webkit-transform: rotate(-90deg);    /* for Chrome || Safari */
            -moz-transform: rotate(-90deg);       /* for Firefox */
            -ms-transform: rotate(-90deg);        /* for IE */
            -o-transform: rotate(-90deg) ;
        }
        .cross_screen1{
          transform: rotate(90deg);
        }
				.pc-game-box {width:100%;}
    </style>
<script src="/Public/static/dist/dropload.js"></script>
<script src="/Public/static/xigusdk/xgh5sdkuse.js?time=<?php echo time();?>"></script> 
<script>
    screen = "<?php echo ($screentype); ?>";
    login = "<?php echo is_login();?>";
    game_load_page = "<?php echo ($game_load_page); ?>";
    var popplog = $('.pop').pop();
    var ykway = "<?php echo ($yk['register_way']); ?>";
    var ykphone = "<?php echo ($yk['phone']); ?>";
    ttt= $('.jssussign');
    sss=ttt.find('span');
    issignin = "<?php echo ($issignin); ?>";
    user_no_auth = "<?php echo ($user_no_auth); ?>";
    stop_play = "<?php echo ($stop_play); ?>";
    if(issignin==1){
      ttt.addClass('disabled');
      ttt.find('.circle').remove();
      sss.text('已签到');
    }
    
    var iframe = $('.gamestartbox')[0];
    if(login>0){
      console.log('noload');
      if (iframe.attachEvent){ 
        
          iframeload(); 

      } else { 

          console.log('loading');
          iframeload();
     
      };
    }else {
      setTimeout(function(){
        $(iframe).removeClass('hidden').closest('.jsgbw').addClass('pcgboxbg');
        $('body').find('.pc-footer').hide();
        res = jslogin();
        return ;
      },1500);
    }
    
    function iframeload() {
      setTimeout(function(){
        if(user_no_auth){
          if(stop_play){
            popplog.addClass('pop-login').open('','<div class="tembox"><div class="pop-title">实名认证</div><div class="pop-content"><p class="temporary-text" style="text-align: center;">'+"<?php echo ($name_auth_tip); ?>"+'</p></div><div class="pop-butn-box"><a href="<?php echo U("Subscriber/user_auth");?>" class="pop-bindbutn">去认证</a></div></div>');
          }else{
            $(iframe).removeClass('hidden').closest('.jsgbw').addClass('pcgboxbg');
            $('body').find('.pc-footer').hide();
            $('.suspensionbtn').removeClass('hidden');
            $('.jsloadpic').addClass('hidden');
            popplog.addClass('pop-login').open('','<div class="tembox"><a href="javascript:;" class="pop-close"></a><div class="pop-title">实名认证</div><div class="pop-content"><p class="temporary-text" style="text-align: center;">'+"<?php echo ($name_auth_tip); ?>"+'</p></div><div class="pop-butn-box"><a href="<?php echo U("Subscriber/user_auth");?>" class="toAuth pop-bindbutn">去认证</a></div></div>');
              $('.pop-close').click(function() {popplog.close(); return false;});
          }
        }else{console.log('authed');
            $(iframe).removeClass('hidden').closest('.jsgbw').addClass('pcgboxbg');
            $('body').find('.pc-footer').hide();
            $('.suspensionbtn').removeClass('hidden');
            $('.jsloadpic').addClass('hidden');
        }
        console.log('loaded');
      },1500);
    }
</script>

    <script>  
      $('.pc-download').hover(function(){$('.pc-qrcode').fadeIn();},function(){$('.pc-qrcode').fadeOut();});
      
      function resizephone() {
          var winHeight = $( window ).height();
					
					
							var hedh = $('.pc-header').height();
							var both = $('.pc-container-footer').height()+60;
							var pch = winHeight-hedh;
							$('.pc-container').css({'height':pch+'px'});
							var mobheight = pch-both;
							
          var mobwidth = parseInt(mobheight*552/1000);
					
					
          var scale = parseInt(mobwidth/410*100);
          //if (winHeight<830) {

              $('html').css('font-size',231*scale/100+'%');

            //} 

      }
      resizephone();
      var resizephonetime = null;
      $(window).resize(function() {
        if (resizephonetime) {clearTimeout(resizephonetime);}
        resizephonetime = setTimeout(function() {
          //resizephone();
        },1000);
      });
      
    </script>
  </body>
</html>