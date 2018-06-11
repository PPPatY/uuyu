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
    
<link href="/Public/Mobile/css/suspension.css" rel="stylesheet" >
<style>
.footer{
  display: none;

}
html,body,.container,.container .wrap,.inner,.container .loadbox {height:100%;z-index: 5;}
.gamestartbox {width:100%;height:100%;position:relative;top:0;left:0;z-index:25;background:#FFF}
#sdkdiv{height: 0px;}
@media all and (orientation:landscape) {
	html,body,.container,.container .wrap,.inner,.container .loadbox {height:100vw;}
}

</style>
  <div id="sdkdiv">
    <?php echo ($paysdk); ?>
  </div>
  <div class="container " >
    <div class="wrap jsloadpic">
      <div class="inner">
        <div class="loadbox">
          <div class="mark"><img src="/Public/Mobile/images/slogan.png"></div>
          <div class="load"><img src="/Public/Mobile/images/loading.gif"></div>
          <p class="notice">进入本游戏意味着您已阅读并同意本公司在“游戏中心”的用户条例</p>
        </div>
      </div>
    </div>
    <iframe id="op_url_mainframe" src="<?php echo ($login_url); ?>" style="background-image:url(<?php echo ($game_load_page); ?>);" frameborder="0" class="gamestartbox hidden">
      <!--<a href="javascript:;" class="suspensionbtn hidden" id="jssuspensionbtn"><img src="<?php echo get_cover(C('SUSPENSION_ICO'),path);?>"></a>-->
    </iframe>
    <?php if(is_login() > 0): echo W('Game/suspension');?><!-- 悬浮窗 --><?php endif; ?>
  </div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<div class="pop"></div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
  var appId = "<?php echo ($signPackage['appId']); ?>",
    timestamp = "<?php echo ($signPackage['timestamp']); ?>",
    nonceStr = "<?php echo ($signPackage['nonceStr']); ?>",
    signature = "<?php echo ($signPackage['signature']); ?>";
  var title = "<?php echo ($shareparams['title']); ?>",
    desc = "<?php echo ($shareparams['desc']); ?>",
    imgUrl = "<?php echo ($shareparams['imgUrl']); ?>",
    link = "<?php echo ($shareparams['link']); ?>";
</script>
<script src="/Public/static/xigusdk/xgh5sdkuse.js?time=<?php echo time();?>"></script> 
<script>
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
if(!(login>0)){
  setTimeout(function(){
    $('.gamestartbox').removeClass('hidden');
    res = jslogin();
    return;
  },1500);
}else{
  $mt = "<?php echo get_device_type();?>";
  setTimeout(function(){
    $('.gamestartbox').removeClass('hidden');
    $('.suspensionbtn').removeClass('hidden');
    $('.jsloadpic').addClass('hidden');
    if(user_no_auth){
      if(stop_play){
        $('.gamestartbox').addClass('hidden');
        $('.suspensionbtn').addClass('hidden');
        $('.jsloadpic').removeClass('hidden');
        popplog.addClass('pop-login').open('','<div class="tembox"><div class="pop-title">实名认证</div><div class="pop-content"><p class="temporary-text" style="text-align: center;">'+"<?php echo ($name_auth_tip); ?>"+'</p></div><div class="pop-butn-box"><a  data-href="<?php echo U("Subscriber/user_auth");?>" class="toAuth pop-bindbutn">去认证</a></div></div>');
      }else{
        popplog.addClass('pop-login').open('','<div class="tembox"><a href="javascript:;" class="pop-close"></a><div class="pop-title">实名认证</div><div class="pop-content"><p class="temporary-text" style="text-align: center;">'+"<?php echo ($name_auth_tip); ?>"+'</p></div><div class="pop-butn-box"><a  data-href="<?php echo U("Subscriber/user_auth");?>" class="toAuth pop-bindbutn">去认证</a></div></div>');
      }
        $('.toAuth').click(function(){
          that  = $(this);
          try{
            if($mt=='ios'){
              window.webkit.messageHandlers.RealName.postMessage(1);
            }else if($mt!='ios'){
              $thisurl =  window.location.href;
              window.mengchuang.RealName($thisurl);
            }
          }catch(err){
            window.location.href = that.data('href');
          }
        });
        $('.pop-close').click(function() {popplog.close(); return false;});
    }
  },1500);
}
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