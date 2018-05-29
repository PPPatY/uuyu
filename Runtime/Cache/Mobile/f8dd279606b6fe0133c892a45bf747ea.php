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
<header class="header mall-index">
  <section class="wrap">
    <?php if(I('get.from') == ''): ?><a href="<?php echo U('mall');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span><span class="table-cell word">商城</span></span></a>
    <?php else: ?>
    <a href="javascript:history.go(-1);" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a><?php endif; ?>
    <div class="caption"><span class="table"><span class="table-cell">积分任务</span></span></div>
    <!-- <a href="mall_rule.html" class="hbtn right mall-rule"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_mall_rule.png"></span></span></a>
    <a href="mall_record.html" class="hbtn right mall-reco"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_mall_record.png"></span></span></a> -->
  </section>
</header>
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
              <div class="iconbox"><img src="/Public/Mobile/images/mall_task_sign.png" class="icon"></div>
              <!-- 未签到 -->
              <?php if($issignin != 1): ?><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" data-url="<?php echo U('mall_sign',array('from'=>'mall_integral'));?>" data-msg="签到" class="butn signbutn jumpurl">去签到</a></span></span></div>
                <div class="textbox">
                  <div class="name">签到</div>
                  <p class="info">今日签到可获积分</p>
                  <p class="reward"><img src="/Public/Mobile/images/mall_task_integral.png"><span>+<?php echo ($addpoint); ?></span></p>
                </div>
               <?php else: ?> 
              <!-- 已签到 hidden-->
                <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('mall_sign',array('from'=>'mall_integral'));?>" class="butn signbutn disabled">已签到</a></span></span></div>
                <div class="textbox">
                  <div class="name">签到</div>
                  <p class="info">明日签到可获积分</p>
                  <p class="reward"><img src="/Public/Mobile/images/mall_task_integral.png"><span>+<?php echo ($topoint); ?></span></p>
                </div><?php endif; ?>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Mobile/images/mall_task_bind.png" class="icon"></div>
              <?php if($bindphone == ''): ?><!-- 未绑定 -->
                <div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" data-url="<?php echo U('Subscriber/user_bind_phone',array('from'=>'mall_integral'));?>" data-msg="绑定手机号" class="butn bindbutn jumpurl">去绑定</a></span></span></div>
              <?php else: ?>
                <!-- 已绑定 -->
                <div class="butnbox "><span class="table"><span class="table-cell"><a href="<?php echo U('Subscriber/user_bind_phone',array('from'=>'mall_integral'));?>" class="butn bindbutn disabled">已绑定</a></span></span></div><?php endif; ?>
              <div class="textbox">
                <div class="name">绑定手机号</div>
                <p class="info">绑定手机号赠送积分</p>
                <p class="reward"><img src="/Public/Mobile/images/mall_task_integral.png"><span>+<?php echo ($list['bind_phone']['point']); ?></span><span style="font-size:0.32rem;color:#999;display:inline-block;margin-top:-2%;vertical-align:middle;"> (每个手机号仅限首次绑定)</span></p>
              </div>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Mobile/images/mall_task_first.png" class="icon"></div>
              <?php if($firstspend == ''): ?><!-- 未首充 -->
              <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('Game/index');?>" class="butn frstbutn">开始</a></span></span></div>
              <?php else: ?>
              <!-- 已首充 -->
              <div class="butnbox"><span class="table"><span class="table-cell"><a  class="butn frstbutn disabled">已首充</a></span></span></div><?php endif; ?>
              <div class="textbox">
                <div class="name">每日游戏首充</div>
                <p class="info">每日首次充值任意一款游戏</p>
                <p class="reward"><img src="/Public/Mobile/images/mall_task_integral.png"><span>+<?php echo ($list['share_game']['point']); ?></span>
                <span style="font-size:0.32rem;color:#999;display:inline-block;margin-top:-2%;vertical-align:middle;"> (绑币除外)</span>
                </p>
              </div>
            </div>
          </li>
          <li>
            <div class="item">
              <div class="iconbox"><img src="/Public/Mobile/images/mall_task_pay.png" class="icon"></div>
              <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('Game/index');?>" class="butn rechbutn">开始</a></span></span></div>
              <div class="textbox">
                <div class="name">游戏充值</div>
                <p class="info">每充值1元即可获得<?php echo ($list['share_article']['point']); ?>积分</p>
                <span style="font-size:0.32rem;color:#999;display:inline-block;margin-top:-2%;vertical-align:middle;"> 长期有效(绑币除外)</span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </section>
    
  </section>
</section>
<div class="popmsg"></div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
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
					loginjs(2,that.attr('data-msg'));
					return false;
				} else {
					window.location.href=that.attr('data-url');
				}
				
				return false;
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