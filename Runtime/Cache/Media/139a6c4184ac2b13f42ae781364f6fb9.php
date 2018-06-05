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
  <link href="/Public/Media/css/index.css" rel="stylesheet" >

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
              
  <header class="header indexheader">
    <section class="wrap">

			<?php if(session('user_auth.user_id') <= 0): ?><a href="javascript:;" class="hbtn left loginbox lb1 jslogin"><span class="table"><span class="table-cell"><img src="/Public/Media/images/my_head.png"></span></span></a>
			<a href="javascript:;" class="hbtn left loginbox lb2 jslogin"><span class="table"><span class="table-cell"><i class="">登录</i></span></span></a>
			<span class="hbtn left loginbox serperate "><span class="table"><span class="table-cell"><i class="">/</i></span></span></span>
			<a href="javascript:;" class="hbtn left loginbox lb3 jsregister"><span class="table"><span class="table-cell"><i class="">注册</i></span></span></a>
      <a href="javascript:;" class="hbtn right databox sign jssign" data-login="0"><span class="table"><span class="table-cell"><img src="/Public/Media/images/iframe/sign.png"><i>签到</i></span></span></a>
			<?php else: ?>
			<a href="<?php echo U('Subscriber/user');?>" class="hbtn left loginbox lb1 "><span class="table"><span class="table-cell"><?php if($userinfo['head_icon'] != ''): ?><img src="<?php echo ($userinfo['head_icon']); ?>"><?php else: ?><img src="/Public/Media/images/my_head.png"><?php endif; ?></span></span></a>
			<a href="<?php echo U('Subscriber/user');?>" class="hbtn left loginbox lb2 "><span class="table"><span class="table-cell"><i class=""><?php echo ($userinfo["nickname"]); ?></i></span></span></a>
      <a href="javascript:;" class="hbtn right databox sign jssign"  data-login="1"><span class="table"><span class="table-cell"><img src="/Public/Media/images/iframe/sign.png"><i><?php if(($issignin) == "1"): ?>已签<?php else: ?>签到<?php endif; ?></i></span></span></a><?php endif; ?>
      
      <span class="hbtn right searperate2"><span class="table"><span class="table-cell"><i class="">|</i></span></span></span>
			<a href="<?php echo U('Search/index');?>" class="hbtn right databox search2"><span class="table"><span class="table-cell"><img src="/Public/Media/images/iframe/search.png">搜索</span></span></a>
      
    </section>
  </header>

              
              
<div class="mainer">
<style>
.dropload-noData{
  display:none;
}
</style>
  <div class="occupy"></div>
    <section class="trunker">
      <section class="inner">
        <section class="banner">
          <div id="banner-carousel" class="swiper-container">
              <div class="swiper-wrapper">
                  <?php if(is_array($sliderWap)): $i = 0; $__LIST__ = $sliderWap;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$slidApp): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                      <a class="pic" target="<?php echo ($slidApp['target']); ?>" href="<?php if(!empty($slidApp["url"])): echo ($slidApp['url']); else: ?>javascript:;<?php endif; ?>"><span class="font table"><span class="table-cell"><?php echo ($slidApp['title']); ?></span></span><?php if(@fopen($slidApp['data'], 'r' )): ?><img src="<?php echo ($slidApp['data']); ?>" /><?php else: ?><img src="/Public/Media/images/activity_graph@3x.png" /><?php endif; ?></a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
              <div class="swiper-pagination"></div>
          </div>
          
        </section>
        <section class="contain">
        <?php if(session('user_auth.user_id') > 0 && !empty($userPlay)): ?><div class="recently-play">
            <div class="wrap">
              <div class="recently-play-title">
                <span class="table"><i class="table-cell">最近在玩</i></span>
              </div>
              <div class="play-scroll">
                <div id="play-slide" class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php if(is_array($userPlay)): $i = 0; $__LIST__ = $userPlay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$up): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                            <div class="item">
                              <a href="<?php echo ($up["play_detail_url"]); ?>" class="icon"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($up["icon"]); ?>"  /></a>
                              <a href="<?php echo ($up["play_detail_url"]); ?>" class="name"><?php echo ($up["game_name"]); ?></a>
                              <a href="<?php echo ($up["play_url"]); ?>" class="butn butnlogin"><span class="table"><span class="table-cell">开始</span></span></a>
                            </div>
                          </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
              </div>
            </div>
          </div><?php endif; ?>
          <div class="recommend-list">
            
            <div class="tab-scroll">
              <div id="tab-menu">
                <div class="s-container">
                    <div class="s-wrapper tabmenu">
                        <div class="s-slide s-visible active" ><a href="#hot" onclick="javascript:;">热门</a></div>
                        <div class="s-slide s-visible" ><a href="#new" onclick="javascript:;">新上架</a></div>
                        <div class="s-slide s-visible"><a href="#activity" class="hdredpoint">活动<?php if($hdmark != 1): ?><i class="circle"></i><?php endif; ?></a></div>
                        <div class="s-slide s-visible"><a href="#open">新开服</a></div>
                    </div>
                </div>
              </div>
              <div id="tab-slide">
                <div class="s-container">
                    <div class="s-wrapper tabpanel">

                        <div class="s-slide loaddiv" style="display:block;" id="hotload">
                          <ul class="hot hotload list text-pic-list" >

                          </ul>
                        </div>

                        <div class="s-slide loaddiv" id="newload">
                          <ul class="shelves newload list text-pic-list" >
                            
                          </ul>
                        </div>

                        <div class="s-slide">
                          <div class="activity list double-list">
                            <div class="jstab double-list-item">
                              <ul class="tab clearfix">
                                <li class="jshdong"><a href="#activity0" class="btn active"><span class="table"><span class="table-cell"><i class="icon icon-active"></i>活动</span></span></a></li>
                                <li class="jshdong"><a href="#activity1" class="btn"><span class="table"><span class="table-cell"><i class="icon icon-notice"></i>公告</span></span></a></li>
                              </ul>
                            </div>
                            <div class="jspanel double-list-item s-slide loaddiv">
                              <ul class="tab-panel hdload panel1 active">
                                
                              </ul>
                              <ul class="tab-panel ggload panel2">
                                
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="s-slide">
                          <div class="open list double-list">
                            <div class="jstab double-list-item">
                              <ul class="tab clearfix">
                                <li class="jsserver"><a href="#open0" class="btn active"><span class="table"><span class="table-cell"><i class="icon icon-open-yes"></i>已开新服</span></span></a></li>
                                <li class="jsserver"><a href="#open1" class="btn"><span class="table"><span class="table-cell"><i class="icon icon-open-no"></i>新服预告</span></span></a></li>
                              </ul>
                            </div>
                            <div class="jspanel double-list-item s-slide loaddiv">
                              <ul class="tab-panel openedload text-pic-list panel3 active">
                              </ul>
                              <ul class="tab-panel noopenload text-pic-list panel3 panel4 ">
                              </ul>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            
            
            
          </div>
        </section>
        
      </section>
    </section>
    <div class="space"></div>
    
</div>


              
<div class="pop"></div>
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
$('.hdredpoint').click(function(){
  $(this).children('.circle').remove();
});
var itemIndex = 0;
var hdIndex = 0;
var openIndex = 0;
var tab1LoadEnd = false;
var tab2LoadEnd = false;
var tab3LoadEnd = false;
var tab4LoadEnd = false;
var tab5LoadEnd = false;
var tab6LoadEnd = false;
function reservefun(ele,server_id,type){
  res = jslogin();
  if(!res){
    return false;
  }
  that = ele.parent('.jsbutnbox');
  var $ok = false;
  $.ajax({
      type:'post',
      url:"<?php echo U('Index/setServerNotice');?>",
      data:{server:server_id,type:type},
      async:false,
      success:function(data){
        if(data.code==1){
          $ok=true;
        }else if(data.code==-1){
          $ok=false;
          res = jslogin();
          if(!res){
            return false;
          }
        }else{
          $ok=false;
          layer.msg('请刷新后重试',{time:1000});
        }
      },error:function(){
        $ok=false;
      }
  });
  if(type == 1&&$ok){
    that.html('<a href="javascript:;" onclick="reservefun($(this),'+server_id+',0);"  class="butn"><i class="icon icon-notice-yes"></i><span>已设置通知</span></a>');
    layer.msg('已设置通知',{time:500});
  }else if($ok){
    that.html('<a href="javascript:;" onclick="reservefun($(this),'+server_id+',1);" class="butn"><i class="icon icon-notice-no"></i><span>通知</span></a>');
    layer.msg('已取消通知',{time:500});
  }
}
$(function(){

			$('.jsregister').click(function() {
        $('.jslogin').click();

        qiehuan($('.jspoptab a').eq(1),10);

        return false;
      });
			var pop = $('.pop').pop();
			$user = "<?php echo is_login();?>";
			$('.jssign').click(function() {
        var that = $(this);
        if (that.hasClass('disabled')) {return false;}
        that.addClass('disabled');
        if ($user>0) {
            $.ajax({
              type:'post',
              url:"<?php echo U('PointShop/user_sign_in');?>",
              success:function(data){
                if(data.status==1){
                  that.find('i').text('已签');
                }else{
                  layer.msg(data.msg);that.removeClass('disabled');
                }
              },error:function(){
							layer.msg('服务器故障，请稍候再试');
								that.removeClass('disabled');	
              }
            })
            
        } else {console.log('ddd');
          // 未登录 则弹出登录框
          pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法签到哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologin">去登录</a></div>');
          $('.jscancel').click(function() {pop.close();that.removeClass('disabled');});
          $('.tologin').click(function(){
            pop.close();
            $('.jslogin').click();
          });
        }
        
      });
			

    var pop = $('.pop').pop();
      new Swiper('#banner-carousel', {autoplay:4000,pagination : '.swiper-pagination',loop:true});
      
      new Swiper('#play-slide', {slidesPerView: 4});
      
      /* 标签切换 */
			$('#tab-menu .s-slide').click(function() {
				var that=$(this);
				var index = that.index();
				itemIndex = index;
				that.siblings().removeClass('active');
				that.addClass('active');
				$('#tab-slide .tabpanel>.s-slide').hide().eq(index).show();
				dataload(index);
			});
      
      $('.jstab .btn').click(function() {
        var that = $(this),parent = that.closest('.jstab'),sib=parent.siblings('.jspanel');
        parent.find('.btn').removeClass('active');
        var index = that.addClass('active').closest('li').index();
        sib.find('.tab-panel').eq(index).addClass('active').siblings().removeClass('active'); 
      });	
			
			
			
    $('.jshdong').on('click',function(){
        dataload(2);
    });
    $('.jsserver').on('click',function(){
        dataload(3);
    });
    var counter = 0;
    var counter1 = 0;
    var counter2 = 0;
    var counter3 = 0;
    var counter4 = 0;
    var counter5 = 0;
    // 每页展示5个
    // dropload
    var dropload = $('.s-slide.loaddiv').dropload({
        scrollArea : window,
        threshold:6000,
        loadDownFn : function(me){
            var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
            // 加载菜单一的数据
            if(itemIndex == '0'){
                var num = 5;
                counter++;
                data = loadajax1(me,'<?php echo U("Index/more_game");?>',2,num,counter);
                var result = '';
                if(data.status==0){
                  if(counter==1){
                    result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                  }
                  $("#"+loadid+' ul').append(result);
                  tab1LoadEnd = true;
                  // 锁定
                  me.lock();
                  // 无数据
                  me.noData();
                  me.resetload();
                }else{
									var nnnum=0;
										var nndata = data.data;
										if(!jQuery.isArray( nndata )){
											$.each(nndata,function(i,e){
                          nnnum = i;
													result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a>';
													if(e.gift_id>0){
														result += '<span class="mark gift-mark">礼包</span>';
													} 
													result += '</div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="description">'+e.features+'</span></p></div></div></li>';
											
											});
										}else{
											nnnum = nndata.length;
											for(var i = 0; i < nndata.length; i++){
													result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a>';
													if(nndata[i].gift_id>0){
														result += '<span class="mark gift-mark">礼包</span>';
													} 
													result += '</div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="description">'+nndata[i].features+'</span></p></div></div></li>';
											}
										}
                    // 为了测试，延迟1秒加载
                    setTimeout(function(){
                        $("#"+loadid+' ul').append(result);
                        tab1LoadEnd = true;
                        if(nnnum<num){
                          // 锁定
                          me.lock();
                          // 无数据
                          me.noData();
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                    },1);
                }
            // 加载菜单二的数据
            }else if(itemIndex == '1'){
                counter1++;
                data = loadajax1(me,'<?php echo U("Index/more_game");?>',3,num,counter1);
                var result = '';
                if(data.status==0){
                  if(counter1==1){
                    result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                  }
                  $("#"+loadid+' ul').append(result);
                  tab2LoadEnd = true;
                  // 锁定
                  me.lock();
                  // 无数据
                  me.noData();
                  me.resetload();
                }else{
									var nnnum=0;
									var nndata = data.data;
									if(!jQuery.isArray( nndata )){
										$.each(nndata,function(i,e){
												nnnum = i;
												result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title">a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a><span class="mark send-mark">首发</span>';
                        if(e.gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        }
                        result += '</div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="description">'+e.features+'</span></p></div></div></li>';
                    
										});
									}else{
										nnnum = nndata.length;
                    for(var i = 0; i < nndata.length; i++){
                        result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a><span class="mark send-mark">首发</span>';
                        if(nndata[i].gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        }
                        result += '</div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="description">'+nndata[i].features+'</span></p></div></div></li>';
                    }
									}
                    // 为了测试，延迟1秒加载
										setTimeout(function(){
                       $("#"+loadid+' ul').append(result);
                        tab2LoadEnd = true;
                        if(nnnum<hdnum){
                          // 锁定
                          me.lock();
                          // 无数据
                          me.noData();
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                     },1);
                }
            }else if(itemIndex == '2'){
                var hdIndex = $('.jshdong a.active').parent('.jshdong').index();
                if(hdIndex==0){
                  var hdnum = 10;
                  counter2++;
                  data = loadajax2(me,'<?php echo U("Index/get_article_lists");?>',counter2);
                  console.log(data);
                  try{
                    var jshdmark = data.hdmark;
                  }catch(err){
                    var jshdmark = 1
                  }
                  if(jshdmark){
                    $(".s-wrapper.tabmenu .s-slide.s-visible a").eq(itemIndex).html('活动');
                  }
                  var result = '';
                  if(data.status==0){
                    if(counter2==1){
                      result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                    }
                    $('.hdload').append(result);
                    tab3LoadEnd = true;
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                    
                  }else{
										var nnnum=0;
										var nndata = data.data;
										if(!jQuery.isArray( nndata )){
											$.each(nndata,function(i,e){
													nnnum = i;
													result += '<li><div class="item"><a href="'+e.url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.cover+'" class="icon"><span class="status disabled"><span class="table">';
                          if(e.article_status==1){
                            result += '<span class="status"><span class="table"><span class="table-cell"><img src="/Public/Media/images/activity_notice_time_ongoing.png">进行中...</span></span></span>';
                          }else if(e.article_status==-1){
                            result += '<span class="status soon"><span class="table"><span class="table-cell">即将开始</span></span></span>';
                          }else{
                            result += '<span class="status disabled"><span class="table"><span class="table-cell">已结束</span></span></span>';
                          }
                          result += '</span></span></a><h2 class="title"><a href="'+e.url+'">';
                          if(e.belong_game){
                            result +='【'+e.belong_game+'】';
                          }
                          result +=e.title+'</a></h2><p class="time">活动时间：'+e.start_time+'~'+e.end_time+'</p></div></li>';
                      
											});
										}else{
											nnnum = nndata.length;
                      for(var i = 0; i < nndata.length; i++){
                          result += '<li><div class="item"><a href="'+nndata[i].url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].cover+'" class="icon"><span class="status disabled"><span class="table">';
                          if(nndata[i].article_status==1){
                            result += '<span class="status"><span class="table"><span class="table-cell"><img src="/Public/Media/images/activity_notice_time_ongoing.png">进行中...</span></span></span>';
                          }else if(nndata[i].article_status==-1){
                            result += '<span class="status soon"><span class="table"><span class="table-cell">即将开始</span></span></span>';
                          }else{
                            result += '<span class="status disabled"><span class="table"><span class="table-cell">已结束</span></span></span>';
                          }
                          result += '</span></span></a><h2 class="title"><a href="'+nndata[i].url+'">';
                          if(nndata[i].belong_game){
                            result +='【'+nndata[i].belong_game+'】';
                          }
                          result +=nndata[i].title+'</a></h2><p class="time">活动时间：'+nndata[i].start_time+'~'+nndata[i].end_time+'</p></div></li>';
                      }
										}
                      setTimeout(function(){
                          $('.hdload').append(result);
                          tab3LoadEnd = true;
                          if(nnnum<hdnum){
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                          }
                          // 每次数据加载完，必须重置
                          me.resetload();
                      },1);
                  }
                }else{
                  hdnum = 5;
                  counter3++;
                  data = loadajax2(me,'<?php echo U("Index/get_article_lists");?>',counter3,5);
                  var result = '';
                  if(data.status==0){
                    if(counter3==1){
                      result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                    }
                    $('.ggload').append(result);
                    tab4LoadEnd = true;
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                    
                  }else{
										var nnnum=0;
										var nndata = data.data;
										if(!jQuery.isArray( nndata )){
											$.each(nndata,function(i,e){
													nnnum = i;
													 result += '<li><a href="'+e.url+'"><div class="item"><h2 class="title">';
                          if(e.belong_game){
                            result +='【'+e.belong_game+'】';
                          }
                          result +=e.title+'</h2><p class="time"><img src="/Public/Media/images/activity_notice_time.png" class="icon"><span class="date">'+e.start_time+'</span></p></div></a></li> ';
                      
											});
										}else{
											nnnum = nndata.length;
                      for(var i = 0; i < nndata.length; i++){
                          result += '<li><a href="'+nndata[i].url+'"><div class="item"><h2 class="title">';
                          if(nndata[i].belong_game){
                            result +='【'+nndata[i].belong_game+'】';
                          }
                          result +=nndata[i].title+'</h2><p class="time"><img src="/Public/Media/images/activity_notice_time.png" class="icon"><span class="date">'+nndata[i].start_time+'</span></p></div></a></li> ';
                      }
										}											
                      setTimeout(function(){
                          $('.ggload').append(result);
                          tab4LoadEnd = true;
                          if(nnnum<hdnum){
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                          }
                          // 每次数据加载完，必须重置
                          me.resetload();
                      },1);
                  }
                }
            }else if(itemIndex == '3'){
                openIndex = $('.jsserver a.active').parent('.jsserver').index();
                if(openIndex==0){
                    hdnum = 10;
                    counter4++;
                    data = loadajax3(me,'<?php echo U("Index/server");?>',0,counter4,hdnum);
                    var result = '';
                    if(data.status==0){
                      if(counter4==1){
                        result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      }
                      $('.openedload').append(result);
                      tab5LoadEnd = true;
                      // 锁定
                      me.lock();
                      // 无数据
                      me.noData();
                      me.resetload();
                      
                    }else{
											var nnnum=0;
											var nndata = data.data;
											if(!jQuery.isArray( nndata )){
												$.each(nndata,function(i,e){
														nnnum = i;
														result += '<li><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Media/images/service_trailer_area.png" class="server-icon">'+e.server_name+'</span><span class="server-time"><img src="/Public/Media/images/service_trailer_time.png"  class="server-icon">'+e.pastTime+'</span></p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result += '<li><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Media/images/service_trailer_area.png" class="server-icon">'+nndata[i].server_name+'</span><span class="server-time"><img src="/Public/Media/images/service_trailer_time.png"  class="server-icon">'+nndata[i].pastTime+'</span></p></div></div></li>';
                        }
											}
                        setTimeout(function(){
                            $('.openedload').append(result);
                            tab5LoadEnd = true;
                            if(nnnum<hdnum){
                              // 锁定
                              me.lock();
                              // 无数据
                              me.noData();
                            }
                            // 每次数据加载完，必须重置
                            me.resetload();
                        },1);
                    }
                }else{
                    hdnum = 10;
                    counter5++;
                    data = loadajax3(me,'<?php echo U("Index/server");?>',1,counter5,hdnum);
                    console.log(data);
                    var result = '';
                    if(data.status==0){
                      if(counter5==1){
                        result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      }
                      $('.noopenload').append(result);
                      tab6LoadEnd = true;
                      // 锁定
                      me.lock();
                      // 无数据
                      me.noData();
                      me.resetload();
                      
                    }else{
                        var nowtime = (Date.parse( new Date()))/1000;
											var nnnum=0;
											var nndata = data.data;
											if(!jQuery.isArray( nndata )){
												$.each(nndata,function(i,e){
														nnnum = i;
														result +='<li><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell">';
                            if((e.start_time-nowtime)<1800){
                              result +='<a href="javascript:;" data-start_time="'+e.start_time+'" class="butn opening-btn">即将开服</a>';
                            }else if(e.isnotice==0){
                              result +='<div class="jsbutnbox"><a href="javascript:;" onclick="reservefun($(this),'+e.server_id+',1);" class="butn"><i class="icon icon-notice-no"></i><span>通知</span></a></div>';
                            }else{
                              result +='<div class="jsbutnbox"><a href="javascript:;" onclick="reservefun($(this),'+e.server_id+',0);"  class="butn"><i class="icon icon-notice-yes"></i><span>已设置通知</span></a></div>';
                            }
                            result +='</span></span></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Media/images/service_trailer_area.png" class="server-icon">'+e.server_name+'</span><span class="server-time"><img src="/Public/Media/images/service_trailer_time.png"  class="server-icon">'+e.start_date+'</span></p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result +='<li><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell">';
                            if((nndata[i].start_time-nowtime)<1800){
                              result +='<a href="javascript:;" data-start_time="'+nndata[i].start_time+'" class="butn opening-btn">即将开服</a>';
                            }else if(nndata[i].isnotice==0){
                              result +='<div class="jsbutnbox"><a href="javascript:;" onclick="reservefun($(this),'+nndata[i].server_id+',1);" class="butn"><i class="icon icon-notice-no"></i><span>通知</span></a></div>';
                            }else{
                              result +='<div class="jsbutnbox"><a href="javascript:;" onclick="reservefun($(this),'+nndata[i].server_id+',0);"  class="butn"><i class="icon icon-notice-yes"></i><span>已设置通知</span></a></div>';
                            }
                            result +='</span></span></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Media/images/service_trailer_area.png" class="server-icon">'+nndata[i].server_name+'</span><span class="server-time"><img src="/Public/Media/images/service_trailer_time.png"  class="server-icon">'+nndata[i].start_date+'</span></p></div></div></li>';
                        }
											}	
                        setTimeout(function(){
                            $('.noopenload').append(result);
                            tab6LoadEnd = true;
                            if(nnnum<hdnum){
                              // 锁定
                              me.lock();
                              // 无数据
                              me.noData();
                            }
                            // 每次数据加载完，必须重置
                            me.resetload();
                        },1);
                    }
                }
            }
            $('#tab-slide .s-container .s-wrapper').css('height','auto');
        }
    });
		
		
		var fromnumb = location.hash;
			if(fromnumb){
				switch(fromnumb) {
					case '#hot':itemIndex=0;break;
					case '#new':itemIndex=1;break;
					case '#activity':itemIndex=2;break;
					case '#activity0':itemIndex=2;break;
					case '#activity1':$('.jshdong a').removeClass('active');$('.jshdong').eq(1).find('a').addClass('active');
					$('.activity .jspanel ul').removeClass('active').eq(1).addClass('active');
						itemIndex=2;break;
					case '#open':itemIndex=3;break;
					case '#open0':itemIndex=3;break;
					case '#open1':$('.jsserver a').removeClass('active');$('.jsserver').eq(1).find('a').addClass('active');
					$('.open .jspanel ul').removeClass('active').eq(1).addClass('active');
					itemIndex=3;break;
					default:itemIndex=0;
				}
				$('#tab-menu .s-slide').removeClass('active').eq(itemIndex).addClass('active');
				$('#tab-slide .tabpanel>.s-slide').hide().eq(itemIndex).show();
				dataload(itemIndex);
			}
		
		
    function loadajax1(me,url,rec_status,num,counter){
      var adddata ='';
      $.ajax({
          type: 'post',
          url: url,
          async:false,
          data:{rec_status:rec_status,p:counter,limit:num},
          dataType: 'json',
          success: function(data){
              adddata = data;
          },
          error: function(xhr, type){
              // me.resetload();
          }
      });
      return adddata;
    }
    function loadajax2(me,url,counter,category){
      var adddata ='';
      $.ajax({
          type: 'post',
          url: url,
          async:false,
          data:{p:counter,category:category},
          dataType: 'json',
          success: function(data){
              adddata = data;
          },
          error: function(xhr, type){
              // me.resetload();
          }
      });
      return adddata;
    }
    function loadajax3(me,url,type,p,row){
      var adddata ='';
      $.ajax({
          type: 'post',
          url: url,
          async:false,
          data:{type:type,p:p,row:row},
          dataType: 'json',
          success: function(data){
              adddata = data;
          },
          error: function(xhr, type){
              // me.resetload();
          }
      });
      return adddata;
    }
    function dataload(itemIndex){
      var loadid = $('#tab-slide .s-container .s-slide ul').eq(itemIndex).attr('id');
      // 如果选中菜单一
      if(itemIndex == '0'){
          // 如果数据没有加载完
          if(!tab1LoadEnd){
              // 解锁
              dropload.unlock();
              dropload.noData(false);
          }else{
              // 锁定
              dropload.lock('down');
              dropload.noData();
          }
      // 如果选中菜单二
      }else if(itemIndex == '1'){
          // 如果数据没有加载完
          if(!tab2LoadEnd){
              // 解锁
              dropload.unlock();
              dropload.noData(false);
          }else{
              // 锁定
              dropload.lock('down');
              dropload.noData();
          }
      // 如果选中菜单二
      }else if(itemIndex == '2'){
          var hdIndex = $('.jshdong a.active').parent('.jshdong').index();console.log(dropload);
          if(hdIndex == '0'){
              // 如果数据没有加载完
              if(!tab3LoadEnd){
                  // 解锁
                  dropload.unlock();
                  dropload.noData(false);
              }else{
                  // 锁定
                  dropload.lock('down');
                  dropload.noData();
              }
          // 如果选中菜单二
          }else if(hdIndex == '1'){
              if(!tab4LoadEnd){
                  // 解锁
                  dropload.unlock();
                  dropload.noData(false);
              }else{
                  // 锁定
                  dropload.lock('down');
                  dropload.noData();
              }
          }
      }else if(itemIndex == '3'){
          hdIndex = -1;
          openIndex = $('.jsserver a.active').parent('.jsserver').index();
          if(openIndex == '0'){
              // 如果数据没有加载完
              if(!tab5LoadEnd){
                  // 解锁
                  dropload.unlock();
                  dropload.noData(false);
              }else{
                  // 锁定
                  dropload.lock('down');
                  dropload.noData();
              }
          // 如果选中菜单二
          }else if(openIndex == '1'){
              if(!tab6LoadEnd){
                  // 解锁
                  dropload.unlock();
                  dropload.noData(false);
              }else{
                  // 锁定
                  dropload.lock('down');
                  dropload.noData();
              }
          }
      }
      dropload.resetload();
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