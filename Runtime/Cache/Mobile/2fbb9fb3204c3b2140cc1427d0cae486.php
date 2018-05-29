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
    
<link href="/Public/Mobile/css/gift.css" rel="stylesheet" >
<style>
.footer{
  display: none;
}
</style>
    <header class="header ">
      <section class="wrap">
        <a href="javascript:;" onclick="history.go(-1);" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span><span class="table-cell word">礼包</span></span></a>
        <div class="caption">
          <span class="table">
            <span class="table-cell">
              礼包详情
            </span>
          </span>
        </div>
      </section>
    </header>
    <a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    <section class="trunker">
      <section class="inner">
      
        <section class="contain">
          <div class="detail">
    <div class="occupy"></div>
            <div class="base">
              <div class="wrap">
                <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data["icon"]); ?>" class="icon"></div>
                <div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" data-gift_id="<?php echo ($data['gift_id']); ?>" data-game_id="<?php echo ($data['game_id']); ?>" <?php if($data["received"] == 1): ?>class="butn disabled getgift">已领取<?php else: ?>class="butn getgift">领取<?php endif; ?></a></span></span></div>
                <div class="textbox">
                  <div class="title"><span class="name">[<?php echo ($data["game_name"]); ?>]<?php echo ($data["giftbag_name"]); ?></span></div>
                  <div class="surplusbox aaaaaaa"><span data-all="<?php echo ($data['novice_all']); ?>" data-wei="<?php echo ($data['novice_surplus']); ?>" class="surplus"><i style="width:<?php echo (round($data['novice_surplus']/$data['novice_all'],4))*100;?>%"></i></span><span class="number">剩余<i><?php echo (round($data['novice_surplus']/$data['novice_all'],4))*100;?>%</i></span></div>
                  <p class="validitytime">有效期：<?php echo set_show_time($data['start_time'],'date','forever');?>~<?php echo set_show_time($data['end_time'],'date','forever');?></p>
                </div>
              </div>
            </div>
            <div class="description samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>礼包内容</span></div>
                <div class="content">
                  <?php echo ($data["desribe"]); ?>
                </div>
              </div>
            </div>
            <div class="limitdate samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-time"></i>使用期限</span></div>
                <div class="content">
                  <p>有效时间：<?php echo set_show_time($data['start_time'],'date','forever');?>~<?php echo set_show_time($data['end_time'],'date','forever');?></p>
                  <p>适用区服：<?php if($data['server_id'] == 0): ?>全区服<?php else: if(check_gift_server($data['server_id']) != null): echo ($data['server_name']); else: ?>适用区服已关闭<?php endif; endif; ?></p>
                </div>
              </div>
            </div>
            <div class="payment samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-way"></i>领取方法</span></div>
                <div class="content">
                  <?php echo ($data["digest"]); ?>
                </div>
              </div>
            </div>
            <div class="start-game-position"></div>
            <div class="start-game">
              <a data-href="<?php echo ($data["play_url"]); ?>" class="btn">开始游戏</a>
            </div>
          </div>  
            
        </section>
        
      </section>
    </section>
    <div class="popmsg"></div>
    <script src="/Public/Mobile/js/pop.lwx.min.js"></script>
    <script src="/Public/Mobile/js/common.js"></script>
<script src="/Public/Mobile/js/clipboard.min.js"></script>
    <script>
        function nologintc(popmsg,msg){
          popmsg.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_unlisted.png"><div class="pop-title">您还未登录</div><p class="pop-text">'+msg+'</p><a href="javascript:;" class="pop-btn tologin">去登录</a></div>'); 
          popmsg.find('.pop-close').click(function() {popmsg.close();});
          popmsg.find('.tologin').click(function() {popmsg.close();$('.jslogin').click()});
        }
        function Copy(str,that){
          text = str;
          var clipboard = new Clipboard('.copy',{
            text: function() {
                  return text;
              }
          });
          clipboard.on('success', function(e) {
            that.text('复制成功');
            e.clearSelection();
          });

          clipboard.on('error', function(e) {
            that.text('复制完成');
            alert('此浏览器不支持此操作，请长按礼包码复制');
          });
      }
        $(function() {
          var popmsg = $('.popmsg').pop();
          $user = "<?php echo is_login();?>";
          $('.getgift').click(function() {
            that = $(this);
            // 是否登录
            if ($user>0) {
              $.ajax({
                  type: 'post',
                  url: '<?php echo U("Gift/getgift");?>',
                  async:false,
                  data:{gameid:that.attr('data-game_id'),giftid:that.attr('data-gift_id')},
                  dataType: 'json',
                  success: function(data){
                      if(data.code==1){
                        // 成功
                        popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_receive_successful.png"><div class="pop-title">领取成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.nvalue+'"></span></div><p class="pop-text">可在[我的礼包]中查看</p><a href="javascript:;" class="copy pop-btn">复制</a></div>');
                        bfp =that.closest('div.butnbox').siblings('div.textbox');
                        that.addClass('disabled');
                        that.text('已领取');
                        surplusbox = bfp.find('.surplusbox');
                        all = surplusbox.find('.surplus').attr('data-all');
                        wei1 = surplusbox.find('.surplus').attr('data-wei');
                        wei2 = surplusbox.find('.surplus').attr('data-wei',wei1-1);
                        wei = surplusbox.find('.surplus').attr('data-wei');
                        baifen = (wei/all*100).toFixed(2);
                        surplusbox.find('i').css('width',baifen+'%');
                        surplusbox.find('.number i').html(baifen+'%');
                        popmsg.find('.pop-close').click(function() {popmsg.close();});
                        popmsg.find('.copy').click(function() {
                            // //移动端复制
                            $(".copy").css("color", "#14b4c3");
                            $(".copy").text('复制');
                            Copy($('.code').val(),$('.pop-hint .pop-btn'));
                          });
                      }else if(data.code==0){
                        nologintc(popmsg,'');
                      }else{
                        // 失败
												var butn = '';
												if (data.code!='-2') {butn += '<a  class="pop-btn jsfresh">重试</a>';}
                        popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_receive_fail.png"><div class="pop-title">领取失败！</div><p class="pop-text">'+data.msg+'</p>'+butn+'</div>');
                        popmsg.find('.jsfresh').click(function(){popmsg.close();});
                        popmsg.find('.pop-close').click(function() {popmsg.close();});
                      }
                  },
                  error: function(xhr, type){
                      alert('服务器错误');
                  }
              });
            }else{
              nologintc(popmsg,'暂时无法领取礼包~T_T~');
            }
          });
          $('.start-game').click(function(){
            if ($user<=0) {
              nologintc(popmsg,'');
            }else{
              location.href=$(this).find('a').data('href');
            }
          })

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