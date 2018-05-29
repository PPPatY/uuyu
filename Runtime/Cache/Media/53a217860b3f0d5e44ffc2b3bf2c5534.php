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
    
<link href="/Public/static/dist/dropload.css" rel="stylesheet" >
<link href="/Public/Media/css/mall.css" rel="stylesheet" >
<style>
.dropload-down{
  display:none;
}
</style>

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
              
<header class="header mall-index">
  <section class="wrap">
    <div class="inte-score inte"><span class="table"><span class="table-cell">可用积分<span class="inte-num"><?php echo ($point); ?></span></span></span></div>
    <a href="<?php echo U('mall_rule');?>" class="hbtn right mall-rule"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_rule.png"></span></span></a>
    <a href="<?php echo U('mall_record');?>" class="hbtn right mall-reco"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_record.png"></span></span></a>
    <a href="<?php echo U('mall_integral');?>" class="hbtn right inte mall-inte"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_task.png"></span></span></a>
    <a href="<?php echo U('mall_sign');?>" class="hbtn right inte mall-sbtn jssign"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_mall_sign.png"></span></span></a>
  </section>
</header>

              
              
<div class="mainer <?php if(($data["count"]) == "0"): ?>mall-empty-mainer<?php endif; ?>">
<div class="occupy"></div>

<section class="trunker">
  
  <section class="banner">
    <div class="inner">
    <?php if(is_login() > 0): ?><p class="integral-title">可用积分</p>
      <span class="integral-score"><?php echo ($point); ?></span>
    <?php else: ?>
      <p class="integral-title"></p>
      <span class="integral-score integral-small">您还未登录</span><?php endif; ?>
      <ul class="integral-butnbox table">
        <li class="table-cell">
        <?php if($issignin != 1): ?><a href="javascript:;" class="butn jssign"><?php if(is_login() > 0): ?><span>签到 <i>+<?php echo ($loginpont); ?></i></span><?php else: ?><span>登录签到</span><?php endif; if($issignin == 0): ?><i class="circle"></i><?php endif; ?></a>
        <?php else: ?>
          <a href="javascript:;" class="butn jssign disabled"><span class=""><i style="">已签到</i></span></a><?php endif; ?>
        </li>
        <li class="table-cell"><a href="<?php echo U('mall_integral');?>" class="butn"><span>积分任务</span></a></li>
      </ul>
    </div>
  </section>
  <section class="inner">
    <section class="contain">
      <?php if(!empty($record['data'])): ?><div class="mall-message">
        <div class="iconbox"><span class="table"><span class="table-cell"><img class="icon" src="/Public/Media/images/mall_news.png"></span></span></div>
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
      <div class="list-table mall-index-list">
        <div class="mall-confition">
          <div class="table">
            <div class="table-row">
              <div class="table-cell">按首字母：</div>
              <div class="table-cell"><a href="<?php echo U('mall',array('type'=>I('get.type')));?>" class="butn <?php if(I("short") == ""): ?>active<?php endif; ?>">全部</a></div>
              <div class="table-cell">
                <div class="list-item letter">
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
            <div class="table-row">
              <div class="table-cell">商品属性：</div>
              <div class="table-cell"><a href="<?php echo U('mall',array('short'=>I('get.short')));?>" class="butn <?php if((I("type") != 1) and I("type") != 2): ?>active<?php endif; ?>">全部</a></div>
              <div class="table-cell">
                <div class="list-item">
                  <a href="<?php echo U('mall',array('type'=>1,'short'=>I('get.short')));?>" class="butn <?php if((I("type") == 1)): ?>active<?php endif; ?>">实物</a>
                  <a href="<?php echo U('mall',array('type'=>2,'short'=>I('get.short')));?>" class="butn <?php if((I("type") == 2)): ?>active<?php endif; ?>">虚拟</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="list mall-list loaddiv">
          <ul class="clearfix jsmore"><?php $dcount = $data["count"]; ?>
          <?php if($data["count"] <= 0): ?><div class="empty swiper-categroy" style="height: auto;"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>
          <?php else: ?>
              <?php if(is_array($data['data'])): $k = 0; $__LIST__ = $data['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($k % 2 );++$k;?><li class="<?php if(($k) < "3"): ?>ml<?php endif; ?>">
                  <div class="item <?php if($k%2 == 0): ?>nth2<?php endif; ?>">
                    <a href="<?php echo U('mall_detail',array('id'=>$data['id']));?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data['cover']); ?>" class="icon"></a>
                    <div class="textbox">
                      <a href="<?php echo U('mall_detail',array('id'=>$data['id']));?>" class="name"><?php echo mb_substr($data['good_name'],0,24,'utf-8');?></a>
                      <p class="info">
                        <span class="price"><img src="/Public/Media/images/mall_integral.png"><span><?php echo ($data['price']); ?></span></span>
                        <span class="score">剩余：<span><?php echo ($data['number']); ?></span></span>
                      </p>
                    </div>
                  </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
							<?php if($dcount == 1 ): ?><li class="ml">&nbsp;</li><?php endif; endif; ?>
          </ul>
        </div>
      </div>
    </section>
    
  </section>
</section>

<div class="space"></div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<div class="popmsg"></div>
</div>


              
<footer class="footer">
  <section class="wrap">
    <div class="nav table">
      <a class="item table-cell <?php if(CONTROLLER_NAME == "Index"): ?>active<?php endif; ?>" href="<?php echo U('Index/index');?>"><i class="icon icon-home"></i><span>首页</span></a>
      <a class="item table-cell <?php if(CONTROLLER_NAME == "Game"): ?>active<?php endif; ?>" href="<?php echo U('Game/index#categroy');?>"><i class="icon icon-game"></i><span>游戏</span></a>
      <a class="item table-cell <?php if(CONTROLLER_NAME == "Gift"): ?>active<?php endif; ?>" href="<?php echo U('Gift/index');?>"><i class="icon icon-gift"></i><span>礼包</span></a>
      <a class="jsshopmall item table-cell <?php if(CONTROLLER_NAME == "PointShop"): ?>active<?php endif; ?>" href="<?php echo U('PointShop/mall');?>"><i class="icon icon-mall"><?php if($mallissignin == 1): ?><i class="circle"></i><?php endif; ?></i><span>商城</span></a>
      <a class="item table-cell <?php if(CONTROLLER_NAME == "Subscriber"): ?>active<?php endif; ?>" href="<?php echo U('Subscriber/user');?>"><i class="icon icon-user"><?php if($newmsg == 1): ?><i class="circle"></i><?php endif; ?></i><span>我的</span></a>
    </div>
  </section>
</footer>


              
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
    
<script src="/Public/static/dist/dropload.js"></script>
<script>
    var itemIndex = 0;
    var tab1LoadEnd = false;
    var counter = 1;
    $(function() {
      var popmsg = $('.popmsg').pop();
      new Swiper('#message-slide', {autoplay:3000,loop:true,direction: 'vertical'});
      
      $('.integral-butnbox .jssign').click(function() {
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
      
      
      $('.mainer').scroll(function() {
        var top = $(this).scrollTop();
        var height = $('.banner').height();
        if ((top/height)*100>=80) {
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
                            result+='" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data.data[i].cover+'" class="icon"></a><div class="textbox"><a href="mall_detail.html" class="name">'+data.data.data[i].good_name+'</a><p class="info"><span class="price"><img src="/Public/Media/images/mall_integral.png"><span>'+data.data.data[i].price+'</span></span><span class="score">剩余：<span>'+data.data.data[i].number+'</span></span></p></div></div></li>';
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

            }
        });

        function loadajax(me,type,short,counter){
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