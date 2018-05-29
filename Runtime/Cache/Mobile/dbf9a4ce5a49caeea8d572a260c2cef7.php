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
    
<link href="/Public/Mobile/css/mall.css" rel="stylesheet" >
<style>
.footer{
  display:none;
}
</style>
<header class="header">
  <section class="wrap">
    <a href="javascript:;" onclick="history.go(-1)" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a>
    <div class="caption"><span class="table"><span class="table-cell">积分记录</span></span></div>
    <!-- <a href="<?php echo U('mall_rule');?>" class="hbtn right mall-rule"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_mall_rule.png"></span></span></a> -->
  </section>
</header>
<div class="occupy"></div>

<section class="trunker">

  <section class="inner">
    <section class="contain">
      <div class="list integral-record-list">
      <?php if(0): ?><!-- $data eq '' -->
        <div class="empty">
          <img src="/Public/Mobile/images/blank_record.png" class="empty-icon">
          <p class="empty-text">暂无记录</p>
        </div>
      <?php else: ?>
        <div class="tab-scroll clearfix">
          <div id="tab-menu">
            <div class="swiper-container">
                <div class="swiper-wrapper tabmenu">
                    <div class="swiper-slide swiper-visible active"><a href="javascript:;">全部</a></div>
                    <div class="swiper-slide swiper-visible"><a href="javascript:;">获取</a></div>
                    <div class="swiper-slide swiper-visible"><a href="javascript:;">兑换</a></div>
                </div>
            </div>
          </div>
          <div id="tab-slide">
            <div class="swiper-container mallrecordlist">
                <div class="swiper-wrapper tabpanel">
                  <div class="swiper-slide">
                  <?php if($data['all'] == ''): ?><div class="empty">
                      <img src="/Public/Mobile/images/blank_record.png" class="empty-icon">
                      <p class="empty-text">暂无记录</p>
                    </div>
                  <?php else: ?>
                    <ul class="list text-pic-list">
                    <?php if(is_array($data['all'])): $i = 0; $__LIST__ = $data['all'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><li class="clearfix">
                        <div class="item clearfix">
                          <?php if($da['key'] == 'sign_in'): ?><a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="/Public/Mobile/images/mall_record_sign.png" class="icon"></a>
                          <?php elseif($da['key'] == 'bind_phone'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="/Public/Mobile/images/mall_record_bind.png" class="icon"></a>
                          <?php elseif($da['key'] == 'share_game'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo getgameicon($da['game_id']);?>" class="icon"></a>
                          <?php elseif($da['key'] == 'share_article'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo getgameicon($da['game_id']);?>" class="icon"></a>
                          <?php elseif($da['type'] <= 0): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($da["cover"]); ?>" class="icon"></a><?php endif; ?>
                          <div class="butnbox">
                          <?php if($da["type"] > 0): ?><span class="table">
                            <span class="table-cell">+<?php echo ($da["point"]); ?></span>
                            </span></div>
                            <div class="text">
                              <?php if($da["key"] == "share_game"): ?><div class="title"><span class="name">《<?php echo get_game_name($da['game_id']);?>》充值</span></div>
                              <?php else: ?>
                                <div class="title"><span class="name"><?php echo ($da["name"]); ?></span></div><?php endif; ?>
                              <p class="info"><?php echo set_show_time($da['create_time'],'time','other');?></p>
                            </div>
                          <?php else: ?>
                            <span class="table">
                            <span class="table-cell reduce-score">-<?php echo ($da["pay_amount"]); ?></span>
                            </span></div>
                            <div class="text">
                              <div class="title"><span class="name"><?php echo ($da['good_name']); ?>兑换</span></div>
                              <p class="info"><?php echo set_show_time($da['create_time'],'time','other');?></p>
                            </div><?php endif; ?> 
                          
                        </div>
                      </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
                  </div>
                  <div class="swiper-slide">
                    <?php if($data['pointrecord'] == ''): ?><div class="empty">
                        <img src="/Public/Mobile/images/blank_record.png" class="empty-icon">
                        <p class="empty-text">暂无记录</p>
                      </div>
                    <?php else: ?>
                    <ul class="list text-pic-list">
                      <?php if(is_array($data['pointrecord'])): $i = 0; $__LIST__ = $data['pointrecord'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pr): $mod = ($i % 2 );++$i;?><li class="clearfix">
                        <div class="item clearfix">
                          <?php if($pr['key'] == 'sign_in'): ?><a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="/Public/Mobile/images/mall_record_sign.png" class="icon"></a>
                          <?php elseif($pr['key'] == 'bind_phone'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="/Public/Mobile/images/mall_record_bind.png" class="icon"></a>
                          <?php elseif($pr['key'] == 'share_game'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo getgameicon($pr['game_id']);?>" class="icon"></a>
                          <?php elseif($pr['key'] == 'share_article'): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="" class="icon"></a>
                          <?php elseif($pr['type'] <= 0): ?>
                            <a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($pr["cover"]); ?>" class="icon"></a><?php endif; ?>
                          <div class="butnbox">
                          <?php if($pr["type"] > 0): ?><span class="table">
                            <span class="table-cell">+<?php echo ($pr["point"]); ?></span>
                            </span></div>
                            <div class="text">
                              <?php if($pr["key"] == "share_game"): ?><div class="title"><span class="name">《<?php echo get_game_name($pr['game_id']);?>》充值</span></div>
                              <?php else: ?>
                                <div class="title"><span class="name"><?php echo ($pr["name"]); ?></span></div><?php endif; ?>
                              <p class="info"><?php echo set_show_time($pr['create_time'],'time','other');?></p>
                            </div><?php endif; ?> 
                          
                        </div>
                      </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
                  </div>
                  <div class="swiper-slide">
                    <?php if($data['pointshoprecord'] == ''): ?><div class="empty">
                        <img src="/Public/Mobile/images/blank_record.png" class="empty-icon">
                        <p class="empty-text">暂无记录</p>
                      </div>
                    <?php else: ?>
                    <ul class="list text-pic-list tpl3">
                      <?php if(is_array($data['pointshoprecord'])): $i = 0; $__LIST__ = $data['pointshoprecord'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$psr): $mod = ($i % 2 );++$i;?><li class="clearfix">
                        <div class="item clearfix">
                          <?php if($psr['type'] <= 0): ?><a  class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($psr["cover"]); ?>" class="icon"></a><?php endif; ?>
                          <div class="butnbox">
                          <?php if($psr["type"] <= 0): ?><span class="table">
                            <span class="table-cell reduce-score">-<?php echo ($psr["pay_amount"]); ?></span>
                            </span></div>
                            <div class="text">
                              <div class="title"><span class="name"><?php echo ($psr['good_name']); ?>兑换</span></div>
                              <p class="info"><?php echo set_show_time($psr['create_time'],'time','other');?></p>
                            </div><?php endif; ?> 
                          
                        </div>
                        <?php $good_key = json_decode($psr['good_key'],true); ?>
												<div class="addressbox">
														<div class="table"><div class="table-cell"><?php if($psr['good_type'] == 2): ?><a href="javascript:;" data-key="<?php echo ($key); ?>" class="copy copy<?php echo ($key); ?> address-butn" data-value="<?php echo ($good_key[0]); ?>">复制</a><?php echo ($good_key[0]); else: if(!empty($psr["real_name"])): echo ($psr['real_name']); ?>,<?php endif; if(!empty($psr["phone"])): echo ($psr['phone']); ?>,<?php endif; echo ($psr['address']); endif; ?></div></div>
													</div>
                      </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
                  </div>
                </div>
            </div>
          </div>
        </div><?php endif; ?>
      </div>
    </section>
    
  </section>
</section>

<script src="/Public/Mobile/js/common.js"></script>
<script src="/Public/Mobile/js/clipboard.min.js"></script>
<script>
		function Copy(str,that){
				text = str;
				var clipboard = new Clipboard('.copy'+that.attr('data-key'),{
					text: function() {
								return text;
						}
				});
				clipboard.on('success', function(e) {
					
					e.clearSelection();
				});

				clipboard.on('error', function(e) {
					
					alert('此浏览器不支持此操作，请长按礼包码复制');
				});
		}
    $(function() {
      var viewSwiper = new Swiper('#tab-slide .swiper-container', {
        autoHeight:true,
        onSlideChangeStart: function() {
          updateNavPosition()
        }
      })

      var previewSwiper = new Swiper('#tab-menu .swiper-container', {
        visibilityFullFit: true,
        slidesPerView: 'auto',
        onlyExternal: true,
        onTap: function() {
          viewSwiper.slideTo(previewSwiper.clickedIndex)
        }
      })
      
      var updateNavPosition = function() {
        $('#tab-menu .active').removeClass('active')
        var activeNav = $('#tab-menu .swiper-slide').eq(viewSwiper.activeIndex).addClass('active')
        if (!activeNav.hasClass('swiper-visible')) {
          if (activeNav.index() > previewSwiper.activeIndex) {
            var thumbsPerNav = Math.floor(previewSwiper.width / activeNav.width()) - 1
            previewSwiper.slideTo(activeNav.index() - thumbsPerNav)
          } else {
            previewSwiper.slideTo(activeNav.index())
          }
        }
      }
			
			$('.copy').on('click',function() {
				$(this).text('已复制');
				Copy($(this).attr('data-value'),$(this));
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