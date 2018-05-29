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
.dropload-down{
  display:none;
}
</style>
<section class="trunker">
  
  <section class="banner">
    <div class="inner">
      <div class="user-content-p">
        <?php if(is_login() > 0): ?><p class="integral-title">可用积分 :</p>
          <span class="integral-score"><?php echo ($point); ?></span>
        <?php else: ?>
          <p class="integral-title"></p>
          <span class="integral-score integral-small">您还未登录</span><?php endif; ?>
        <div class="sign-up">
          <?php if($issignin != 1): ?><a href="javascript:;" class="butn jssign"><?php if(is_login() > 0): ?><span>签到 <i>+<?php echo ($loginpont); ?></i></span><?php else: ?><span>登录签到</span><?php endif; if($issignin == 0): ?><i class="circle"></i><?php endif; ?></a>
          <?php else: ?>
            <a href="javascript:;" class="butn jssign disabled"><span class=""><i style="font-style: normal;">已签到</i></span></a><?php endif; ?>
        </div>
      </div>
      <ul class="integral-butnbox table">
        <li class="table-cell">
          <a href="<?php echo U('mall');?>" class="butn">
            <span class="icon-mall-store common-icon"></span>
            <span>积分商城</span>
          </a>
        </li>
        <li class="table-cell">
          <a href="<?php echo U('mall_integral');?>" class="butn">
            <span class="icon-mall-task common-icon"></span>
            <span>积分任务</span>
          </a>
        </li>
        <li class="table-cell">
          <a href="<?php echo U('mall_record');?>" class="butn">
            <span class="icon-mall-record common-icon"></span>
            <span>积分记录</span>
          </a>
        </li>
        <li class="table-cell">
          <a href="<?php echo U('mall_rule');?>" class="butn">
            <span class="icon-mall-rull common-icon"></span>
            <span>积分规则</span>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <section class="inner" style="padding-top: 0">
    <section class="contain">
      <?php if(!empty($record['data'])): ?><div class="mall-message">
        <div class="iconbox"><span class="table"><span class="table-cell"><img class="icon" src="/Public/Mobile/images/mall_news.png"></span></span></div>
        <div class="message-scroll">
          <div id="message-slide" class="swiper-container">
            <div class="swiper-wrapper">
              <?php if(is_array($record['data'])): $i = 0; $__LIST__ = $record['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$record): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                  <div class="item">
                    <a href="#" class="title"><span class="name"><?php echo substr($record['account'],0,4);?>**</span><span class="thing">兑换了【<?php echo ($record['good_name']); ?>】</span></a>
                  </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
          </div>
        </div>
      </div><?php endif; ?>
      <div class="goods-type-p">
        <div class="goods-type-cont-p">
          <a href="<?php echo U('mall',array('short'=>I('get.short')));?>" class="butn <?php if((I("type") != 1) and I("type") != 2): ?>active<?php endif; ?>">全部</a>
          <a href="<?php echo U('mall',array('type'=>1,'short'=>I('get.short')));?>" class="butn <?php if((I("type") == 1)): ?>active<?php endif; ?>">实物商品</a>
          <a href="<?php echo U('mall',array('type'=>2,'short'=>I('get.short')));?>" class="butn <?php if((I("type") == 2)): ?>active<?php endif; ?>">虚拟商品</a>
        </div>
      </div>
      <div class="goods-type-p" style="padding-top: .2rem;">
          <div class="goods-type-cont-p">
              <div class="table-cell">按首字母：</div>
              <div class="table-cell-all">
                <div class="list-item letter">
                  <a href="<?php echo U('mall',array('type'=>I('get.type')));?>" class="butn <?php if(I("short") == ""): ?>active<?php endif; ?>">全部</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'ABCD'));?>" class="butn <?php if(I("short") == "ABCD"): ?>active<?php endif; ?>">ABCD</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'EFGH'));?>" class="butn <?php if(I("short") == "EFGH"): ?>active<?php endif; ?>">EFGH</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'IJKL'));?>" class="butn <?php if(I("short") == "IJKL"): ?>active<?php endif; ?>">IJKL</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'MNOP'));?>" class="butn <?php if(I("short") == "MNOP"): ?>active<?php endif; ?>">MNOP</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'QRST'));?>" class="butn <?php if(I("short") == "QRST"): ?>active<?php endif; ?>">QRST</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'UVWX'));?>" class="butn <?php if(I("short") == "UVWX"): ?>active<?php endif; ?>">UVWX</a>
                  <a href="<?php echo U('mall',array('type'=>I('get.type'),'short'=>'YZ'));?>" class="butn <?php if(I("short") == "YZ"): ?>active<?php endif; ?>">YZ</a>
                </div>
              </div>
          </div>
        </div>
      <div class="list-table">
        <div class="list mall-list loaddiv">
          <ul class="clearfix jsmore"><?php $dcount = $data["count"]; ?>
          <?php if($data["count"] <= 0): ?><div class="empty swiper-categroy" style="height: auto;"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>
          <?php else: ?>
              <?php if(is_array($data['data'])): $k = 0; $__LIST__ = $data['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($k % 2 );++$k;?><li class="<?php if(($k) < "3"): ?>ml<?php endif; ?>">
                  <div class="item <?php if($k%2 == 0): ?>nth2<?php endif; ?>">
                    <a href="<?php echo U('mall_detail',array('id'=>$data['id']));?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data['cover']); ?>" class="icon"></a>
                    <div class="textbox">
                      <a href="<?php echo U('mall_detail',array('id'=>$data['id']));?>" class="name"><?php echo mb_substr($data['good_name'],0,24,'utf-8');?></a>
                      <p class="info">
                        <span class="price"><img src="/Public/Mobile/images/mall_integral.png"><span><?php echo ($data['price']); ?></span></span>
                        <span class="score">剩余：<span><?php echo ($data['number']); ?></span></span>
                      </p>
                    </div>
                  </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
							<?php if($dcount == 1 ): ?><li class="ml"></li><?php endif; endif; ?>
          </ul>
        </div>
      </div>
    </section>
    
  </section>
</section>

<div class="space"></div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<div class="popmsg">
</div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
<link href="/Public/static/dist/dropload.css" rel="stylesheet" >
<script src="/Public/static/dist/dropload.js"></script>
<script>
    var itemIndex = 0;
    var tab1LoadEnd = false;
    var counter = 1;
    $(function() {
      var popmsg = $('.popmsg').pop();
      new Swiper('#message-slide', {autoplay:3000,loop:true,direction: 'vertical'});
      
      $('.sign-up .jssign').click(function() {
        var that = $(this),span=that.find('span'),i = that.find('i').text();
        if (that.hasClass('disabled')) {return false;}
        that.addClass('disabled');
        $user = "<?php echo is_login();?>";
        if ($user>0) {
          $.ajax({
            type:'post',
            url:"<?php echo U('PointShop/user_sign_in');?>",
            success:function(data){
              if(data.status==1){
                $('.jsshopmall i.icon').find('.circle').remove();
                span.addClass('hide');
                setTimeout(function(){
                  span.empty().removeClass('hide');
                  $('<i style="top:100%;position:absolute;left:0;right:0;">'+i+'</i>').prependTo(span).animate({
                    top:0,
                  },300,function(){
                    $nowpoint = $('.integral-score').text();
                    $add = "<?php echo ($loginpont); ?>";
                    $('.integral-score').text(parseInt($nowpoint)+parseInt($add));
                    $('.inte-score .inte-num').text(parseInt($nowpoint)+parseInt($add));
                    that.find('.circle').fadeOut(550);
                    $(this).delay(150).animate({top:'-100%'},250,function() {
                      $(this).remove();
                      that.find('.circle').remove();
                      $('<i style="display:none;">已签到</i>').appendTo(span).fadeIn("slow");
                    });
                  });
                },150);
              }else{
                layer.msg(data.msg);
              }
            },error: function(xhr, type){

            }
          })
          
        } else {
          popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);
          that.removeClass('disabled');
        }
        
      });
      
      
      $(window).scroll(function() {
        var top = $(this).scrollTop();
        var height = $('.banner').height();
        if ((top/height)*100>=20) {
          $('.inte').fadeIn();
        } else {
          $('.inte').fadeOut();
        }
        return false;
      });
      var dropload = $('.loaddiv').dropload({
            scrollArea : window,
            threshold:300,
            loadDownFn : function(me){
                // 加载菜单一的数据
                if(itemIndex == '0'){
                    num = 10;
                    counter++;
                    type = "<?php echo I('get.type');?>";
                    short = "<?php echo I('get.short');?>";
                    data = loadajax(me,type,short,counter);
                    var result = '';
                    if(data.status==0){
                      // if(counter==1){
                      //   result +='<div class="empty swiper-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      // }
                      // $("#"+loadid+' ul').append(result);
                      tab1LoadEnd = true;
                      // 锁定
                      me.lock();
                      // 无数据
                      me.noData();
                      me.resetload();
                      return;
                    }else{
                        for(var i = 0; i < data.data.data.length; i++){
                            result += '<li><div class="item';
                            if((i%2)==1){
                              result += ' nth2';
                            }
                            result+='';
                            result+='"><a href="';
                            result+="<?php echo U('mall_detail','',false);?>"+'/id/'+data.data.data[i].id;
                            result+='" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data.data[i].cover+'" class="icon"></a><div class="textbox"><a href="mall_detail.html" class="name">'+data.data.data[i].good_name+'</a><p class="info"><span class="price"><img src="/Public/Mobile/images/mall_integral.png"><span>'+data.data.data[i].price+'</span></span><span class="score">剩余：<span>'+data.data.data[i].number+'</span></span></p></div></div></li>';
                        }
                        // 为了测试，延迟1秒加载
                        // setTimeout(function(){
                            $('.jsmore').append(result);
                            tab1LoadEnd = true;
                            if(data.data.length<num){
                              // 锁定
                              me.lock();
                              // 无数据
                              me.noData();
                            }
                            // 每次数据加载完，必须重置
                            me.resetload();

                        // },1);
                    }
                }

                // $('#tab-slide .swiper-container .swiper-wrapper').css('height','auto');
            }
        });

        function loadajax(me,type,short,counter){
          //type = 0 全部 1实物  2  虚拟
          //short = 0 全部  其他 对应首字母
          var adddata ='';
          $.ajax({
              type: 'post',
              url: '<?php echo U("mall");?>',
              async:false,
              data:{type:type,short:short,p:counter},
              dataType: 'json',
              success: function(data){
                  adddata = data;
              },
              error: function(xhr, type){
                  me.resetload();
              }
          });
          return adddata;
        }
      
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