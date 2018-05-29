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
    <?php if(($app) == "1"): else: ?>
      <header class="header mall-index">
        <section class="wrap">
          <?php if(I('get.from') == 'mall_integral'): ?><a href="<?php echo U('PointShop/mall_integral');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a>
          <?php else: ?>
            <a href="<?php echo U('mall');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a><?php endif; ?>
          <div class="caption"><span class="table"><span class="table-cell">签到送积分</span></span></div>
          <a href="javascript:;" class="hbtn right mall-sign jsshare"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_btn_share.png"></span></span></a>
        </section>
      </header>
      <div class="occupy"></div><?php endif; ?>

<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    
    <section class="trunker">
      <section class="banner sign-banner">
        <div class="inner">
          <div class="sign-wrap">
          <?php if(is_login() <= 0): ?><!-- 未登录状态 -->
            <div class="loginbox "><a href="javascript:;" class="loginbutn jsloginbtn">[登录]</a>账号签到领积分</div>
          <?php else: ?>
            <!-- 已登录状态 -->
            <div class="score"><span class="my"><span class="mark">我的积分：</span><span class="number"><?php echo ($userpoint); ?></span><i class="add">+<?php echo ($addpoint); ?></i></span></div><?php endif; ?>
          </div>
        </div>
      </section>
      <section class="inner">
        <section class="contain">
          <div class="list sign-list">
            <div class="calendar-wrap">
              <div class="lwx-calendar">
                <div class="lwx-calendar-title">
                  <div class="lwx-calendar-title-wrap">
 
                    <div class="lwx-calendar-caption"><?php echo date('Y年m月');?></div>
                  </div>
                </div>
                <div class="lwx-calendar-content">
                  <div class="lwx-calendar-content-wrap">
                    <div class="lwx-calendar-list">
                      <div class="lwx-calendar-row">
                      <?php if(is_array($weeklist)): $i = 0; $__LIST__ = $weeklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wkl): $mod = ($i % 2 );++$i;?><span class="lwx-calendar-cell"><span class="lwx-calendar-cell-text"><?php echo ($wkl); ?></span></span><?php endforeach; endif; else: echo "" ;endif; ?>
                      </div>
                      <?php $d = date('d',time()) ?>
                      <?php if(is_array($week)): $i = 0; $__LIST__ = $week;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wk): $mod = ($i % 2 );++$i;?><div class="lwx-calendar-row">
                        <?php if(is_array($wk)): $i = 0; $__LIST__ = $wk;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$w): $mod = ($i % 2 );++$i; if($w[1] == "pre"): ?><span class="lwx-calendar-cell  lwx-calendar-list-prev"><span class="lwx-calendar-cell-text"><?php echo ($w[0]); ?></span></span>
                          <?php elseif($w[1] == "cur"): ?>
                            <span class="lwx-calendar-cell  lwx-calendar-list-curr <?php if($w[0] == $d && $w[3] == "is_sign"): ?>today todaysign <?php elseif($w[0] == $d): ?> today <?php elseif($w[3] == "is_sign"): ?> signed<?php endif; ?>"><span class="lwx-calendar-cell-text"><?php echo ($w[0]); ?></span></span>
                          <?php else: ?> 
                            <span class="lwx-calendar-cell  lwx-calendar-list-next"><span class="lwx-calendar-cell-text"><?php echo ($w[0]); ?></span></span><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div class="lwx-calendar-sign">
                      <a href="javascript:;" class="lwx-calendar-sign-btn jssign" data-day="<?php echo ($signday); ?>" data-score="<?php echo ($addpoint); ?>">立即签到</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="sign-rule">
              <div class="active-rule">
                <div class="cotitle">
                  <span>活动规则</span>
                </div>
                <div class="content">
                  <p>1.活动时间：2017-01-01至2019-12-31</p>
                  <p>2.参与条件：在本平台注册的账户</p>
                  <p>3.每天只能签到一次(以服务器系统时间为准)</p>
                  <p>4.签到获得的积分将自动存入【积分余额】中</p>
                  <p>5.在规定时间内连续完成签到可获得相应的奖品，签到获得积分可用来兑换相关商品</p>
                  <p>6.如果中间有一天未签到，将重新开始计算连续签到时间</p>
                  <p>7.本活动最终解释权归徐州梦创科技有限公司所有</p>
                </div>
              </div>
            
            </div>
            
          </div>
        </section>
        
      </section>
    </section>
    <div class="popmsg"></div>
    <script src="/Public/Mobile/js/pop.lwx.min.js"></script>
    <script src="/Public/Mobile/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/static/jsshare/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/static/jsshare//bshareC0.js"></script>
    <script type="text/javascript" charset="utf-8">
      bShare.addEntry({
          pic: window.location.origin+"/Public/static/guide_img_logo@2x.png"
      });
    </script> 
    <script>
    
        
        $(function() {
          $user = "<?php echo is_login();?>";
          $('.lwx-calendar-list .lwx-calendar-cell').each(function(){
            // console.log($(this).attr('class'));
            if($(this).hasClass('todaysign')){
                $('.jssign').addClass('disabled').text('今日已签');
            }
          });
          $nowsignday = "<?php echo $signday-1;?>";
          if($nowsignday>0){
            $('.inner .score').append('<span class="notice">已连续签到'+($nowsignday)+'天啦</span>');
          }
          var popmsg = $('.popmsg').pop();
          
          $('.jsshare').click(function() {
            var open = '<div class="pop-butn jscancel"><a href="javascript:;">取消</a></div><div class="pop-content"><div class="sharebutnbox"><a style="margin-left: 1.2rem;" onclick="javascript:bShare.share(event,'+"'qqim'"+');return false;" class="icon qq"><i></i><span>QQ好友</span></a> <a style="margin-left: 1.2rem;" onclick="javascript:bShare.share(event,'+"'qzone'"+');return false;" class="icon qzone"><i></i><span>QQ空间</span></a> <a style="margin-left: 1.2rem;" onclick="javascript:bShare.share(event,'+"'sinaminiblog'"+');return false;" class="icon weibo"><i></i><span>新浪微博</span></a></div></div>'; 
            $('body,html').css({'overflow':'hidden','height':'100vh'});
            if (!browser.versions.webApp&&(browser.versions.iPhone||browser.versions.iPad) && !browser.versions.qq) {
							open += '<div style="height:3rem;">&nbsp;</div>';
						}
						popmsg.addClass('pop-share').open('',open);
						
            $('.jscancel').click(function(){popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});});
            
          });
          
          $('.jsloginbtn').click(function() {
            loginjs();
            return false;
          });
          
          function loginjs(type){
            if(type==1){
              popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法签到哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologinsss">去登录</a></div>');
              $('.jscancel').click(function() {popmsg.close();});
              popmsg.find('.tologinsss').click(function() {popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);});
            }else{
              $('.jslogin').click();
            }
            
            return false;
          }
          $('.jssign').click(function() {
            var that = $(this),day=parseInt(that.attr('data-day')),score=parseInt(that.attr('data-score'));
            if($user<=0){
              loginjs(1);
              return false;
            }
            if (that.hasClass('disabled')) {return false;}
            if ($user>0) {
              $.ajax({
                type:'post',
                url:"<?php echo U('PointShop/user_sign_in');?>",
                success:function(data){
                  if(data.status==1){
                      that.addClass('disabled').text('今日已签');
                      // 签到成功
                      $addpoint = "<?php echo ($addpoint); ?>";
                      $topoint = "<?php echo ($topoint); ?>";
                      popmsg.addClass('pop-prompt2').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><div class="pop-title">签到成功</div><div class="pop-text">恭喜您获得<i class="em">'+$addpoint+'</i>积分</div><div class="pop-text2">明天可得<i>'+$topoint+'</i>积分</div></div><div class="pop-butn-box"><a data-href="'+"<?php echo U('mall');?>"+'"  class="pop-butn pop-comfirm jsgoduihuan">去兑换</a></div>');
                      
                      $('.pop-close').click(function() {popmsg.close();});
                      $('.jsgoduihuan').click(function(){
                        $mt = "<?php echo get_device_type();?>";
                        try{
                          if($mt=='ios'){
                            window.webkit.messageHandlers.goChange.postMessage(1);
                          }else if($mt!='ios'){
                            window.mengchuang.finish()
                          }
                        }catch(err){
                          location.href=$(this).data('href');
                        }
                      });
                      $('.score .add').addClass('on').animate({
                        top:'0',
                      },500,function(){
                        $(this).delay(500).animate({top:'-0.8rem'},500,function() {
                          var parent = $(this).closest('.score'),num = parent.find('.number');
                          var text = parseInt(num.text())+score;
                          num.text(text);
                          parent.find('.notice').remove();
                          parent.append('<span class="notice">已连续签到'+(day)+'天啦</span>');
                          $('.today').addClass('signed');
                        });
                      });
                  }else if(data.status==0){
                    // 签到失败
                    popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">签到失败</div><div class="pop-text error">可能是网络错误，请重新操作</div></div><div class="pop-butn-box"><a href="" class="pop-butn pop-default jscancel">取消</a> <a href="" class="pop-butn pop-comfirm jscomfirm">刷新</a></div>');
                    
                    $('.jscancel').click(function() {popmsg.close().removeClass('pop-prompt');return false;});
                    
                    $('.jscomfirm').click(function() {window.location.reload();return false;});
                  }else{
                    layer.msg(data.msg);
                  }
                },error:function(){

                }
              })
              
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