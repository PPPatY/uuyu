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
    
<link href="/Public/Mobile/css/index-201806070920.css" rel="stylesheet" >
<style>
.dropload-noData{
  display:none;
}
</style>
  <header class="header ">
    <section class="wrap">
    <?php if($union_set == ''): ?><a class="hbtn table left logo"><span class="table-cell"><img src="<?php echo get_cover(C('WAP_SET_LOGO'),'path');?>"></span></a>
    <?php else: ?>
      <a class="hbtn table left logo"><span class="table-cell"><img src="<?php echo get_cover($union_set['wap_logo'],'path');?>"></span></a><?php endif; ?>
      <div class="caption"><span class="table"><span class="table-cell">首页</span></span></div>
      <?php if(session('user_auth.user_id') <= 0): ?><a href="javascript:;" class="hbtn right  login jslogin"><span class="table"><span class="table-cell"><i class="">登录</i></span></span></a>
        <a href="<?php echo U('Search/index');?>" class="hbtn login search"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_btn_search.png"></span></span></a>
      <?php else: ?>
        <a href="<?php echo U('Search/index');?>" class="hbtn search"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_btn_search.png"></span></span></a><?php endif; ?>
    </section>
  </header>
  <!-- <div class="occupy"></div> -->
    <section class="trunker">
      <section class="inner">
        <section class="banner">
          <div id="banner-carousel" class="swiper-container">
          <div class="swiper-wrapper">
          <?php if(is_array($sliderWap)): $i = 0; $__LIST__ = $sliderWap;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$slidApp): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
              <a class="pic" target="<?php echo ($slidApp['target']); ?>" href="<?php if(!empty($slidApp["url"])): echo ($slidApp['url']); else: ?>javascript:;<?php endif; ?>"><span class="font table"><span class="table-cell"><?php echo ($slidApp['title']); ?></span></span><?php if(@fopen($slidApp['data'], 'r' )): ?><img src="<?php echo ($slidApp['data']); ?>" /><?php else: ?><img src="/Public/Mobile/images/activity_graph@3x.png" /><?php endif; ?></a>
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
                <div class="now-triangle"></div>
              </div>
              <div class="play-scroll">
                <div id="play-slide" class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php if(is_array($userPlay)): $i = 0; $__LIST__ = $userPlay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$up): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                            <div class="item">
                              <!-- <a href="<?php echo ($up["play_detail_url"]); ?>" class="icon"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($up["icon"]); ?>"  /></a> -->
                              <!-- <a href="<?php echo ($up["play_detail_url"]); ?>" class="name"><?php echo ($up["game_name"]); ?></a> -->
                              <a href="<?php echo ($up["play_url"]); ?>" class="icon"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($up["icon"]); ?>"  /></a>
                              <a href="<?php echo ($up["play_url"]); ?>" class="name"><?php echo ($up["game_name"]); ?></a>
                              <a href="<?php echo ($up["play_url"]); ?>" class="butn butnlogin" style="display: none"><span class="table"><span class="table-cell">开始</span></span></a>
                            </div>
                          </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
              </div>
            </div>
          </div><?php endif; ?>
          <div class="hot-games-p">
            <div class="wrap">
              <div class="recently-play-title-p">
                <span class="table"><span class="title"> </span><i class="table-cell">热门爆款</i></span>
                <a href="/mobile.php/Game/index.html#categroy"><span class="more-games">更多 ></span></a>
              </div>
              <div class="play-scroll">
                <div id="hot-game-slide" class="swiper-container">
                  <ul class="swiper-wrapper">
                    <!--<li class="swiper-slide">-->
                      <!--<div class="item">-->
                        <!--<img class="game-bg-p" src="/Public/Mobile/images/index-game-icon-p-4.png" alt="">-->
                        <!--<a href="www.baidu.com" class="icons">-->
                          <!--<img src="/Public/Mobile/images/index-game-icon-p-4.png" class="icon" alt="">-->
                          <!--<span class="game-title">决战沙城</span>-->
                          <!--<span class="game-introduce">万人同屏战沙城</span>-->
                        <!--</a>-->
                        <!--<a class="began-game" href="www.mi.com">开始</a>-->
                      <!--</div>-->
                    <!--</li>-->
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="newes-games-p">
            <div class="wrap">
              <div class="recently-play-title-p">
                <span class="table"><span class="title"> </span><i class="table-cell">最新游戏</i></span>
              </div>
            </div>
          </div> -->
          <div class="recommend-list">
            
            <div class="tab-scroll">
              <div id="tab-menu">
                <div class="s-container">
                    <div class="s-wrapper tabmenu">
                        <div class="s-slide s-visible active" ><a href="#hot">热门</a></div>
                        <div class="s-slide s-visible" ><a href="#new">新上架</a></div>
                        <div class="s-slide s-visible"><a class="hdredpoint" href="#activity">活动<?php if($hdmark != 1): ?><i class="circle"></i><?php endif; ?></a></div>
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
    <div class="popbook"></div>
<link href="/Public/static/dist/dropload.css" rel="stylesheet" >
<script src="/Public/static/dist/dropload.js"></script>
<script>
$('.hdredpoint').click(function(){
  $(this).children('.circle').remove();
});

// 增加js操作代码：start
// $(document).ready(function(){
//   var setBg = $('#hot-game-slide .swiper-wrapper .swiper-slide') ;
//   var BgUrl = $('#hot-game-slide .swiper-wrapper .swiper-slide .item .icons .icon').attr('src');
//   setBg.find('.item').css('background', BgUrl);
// })
// $(document).ready(function(){
  // $('#tab-menu .s-slide:first-child').click();
// })

// 增加js操作代码：end


var itemIndex = 1;
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
  that = ele.parent('.butnbox');
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
    var pop = $('.pop').pop();
    var popbook = $('.popbook').pop();
      new Swiper('#banner-carousel', {autoplay:4000,pagination : '.swiper-pagination',loop:true});
      
      new Swiper('#play-slide', {slidesPerView: 4});
      
      $mobile_collect = "<?php echo ($mobile_collect); ?>";

      if (((browser.versions.webApp||(!browser.versions.iPhone&&!browser.versions.iPad)) || browser.versions.qq)&& $mobile_collect==1) {
   
        // popbook.addClass('pop-bookmark').open('','<a href="javascript:;" class="pop-close3"></a><div class="pop-content"><img class="bookmark-icon" src="/Public/Mobile/images/tip_android.png"></div>').find('.pop-box').css({'margin-bottom':'.2rem'});


        $('.pop-close3').click(function(){popbook.close();return false;});
      }
      else if(!browser.versions.webApp&&(browser.versions.iPhone||browser.versions.iPad) && !browser.versions.qq && $mobile_collect==1) {

        //  popbook.addClass('pop-bookmark').open('','<a href="javascript:;" class="pop-close3"></a><div class="pop-content"><img class="bookmark-icon" src="/Public/Mobile/images/tip_ios.png"></div>');
         $('.pop-close3').click(function(){popbook.close();return false;});
      }
      
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
      if('<?php echo I('from');?>'==3){
          viewSwiper.slideTo(2);
          updateNavPosition();
      }
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

    // 增加js操作代码：start
    // 增加js操作代码：end


    // dropload
    var dropload = $('.s-slide.loaddiv').dropload({
        scrollArea : window,
        threshold:6000,
        loadDownFn : function(me){
            var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
            // 加载菜单一的数据
        // 添加JS代码：start
            var hotGameNum = 0;
            counter++;
            var hotGameData = loadajax1(me,'<?php echo U("Index/more_game");?>',2,hotGameNum,counter);
            var hotGameContent = '';
            if (hotGameData.status == 0) {
              if (counter == 1) {
                hotGameContent += '<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>'
              }
              $('#hot-game-slide ul').append(hotGameContent);
                  tab1LoadEnd = true;
                  // 锁定
                  me.lock();
                  // 无数据
                  me.noData();
                  me.resetload();
            } else {
              var nnnum=0;
              var nndata = hotGameData.data;
              if (!jQuery.isArray(nndata)) {
                $.each(nndata, function(i, e){
                  nnnum = i;
                  hotGameContent += '<li class="swiper-slide"><div class="item butnbox"><img class="game-bg-p" src="'+e.showbg+'" alt=""><a href="'+e.play_detail_url+'" ><img src="'+e.icon+'" class="icon" alt=""><span class="game-title">'+e.game_name+'</span><span class="game-introduce">'+e.features+'</span></a><a class="began-game butn butnlogin" href="'+e.play_url+'">开始</a></div></li>';
                })
              } else {
                nnnum = nndata.length;
                for(var i = 0; i < nndata.length; i++){
                  hotGameContent += '<li class="swiper-slide"><div class="item butnbox"><img class="game-bg-p" src="'+nndata[i].showbg+'" alt=""><a href="'+nndata[i].play_detail_url+'" ><img src="'+nndata[i].icon+'" class="icon" alt=""><span class="game-title">'+nndata[i].game_name+'</span><span class="game-introduce">'+nndata[i].features+'</span></a><a class="began-game butn butnlogin" href="'+nndata[i].play_url+'">开始</a></div></li>';
                }
              }
              $('#hot-game-slide ul').append(hotGameContent);
              new Swiper('#hot-game-slide', {slidesPerView: 3});
              
                tab1LoadEnd = true;
                // if(nnnum<hotGameNum){
                  // 锁定
                  me.lock();
                  // 无数据
                  me.noData();
                // }
                // 每次数据加载完，必须重置
                me.resetload();
            }
        // 添加JS代码：end
            if(itemIndex == '0'){
                var num = 0;
                counter++;
                data = loadajax1(me,'<?php echo U("Index/more_game");?>',2,num,counter);
                var result = '';
                if(data.status==0){
                  if(counter==1){
                    result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
												result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a>';
                        if(e.gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        } 
                        result += '</div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="description">'+e.features+'</span></p></div></div></li>';
                    
										});
									}else{
										nnnum = nndata.length;
                    for(var i = 0; i < nndata.length; i++){
                        result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a>';
                        if(nndata[i].gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        } 
                        result += '</div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="description">'+nndata[i].features+'</span></p></div></div></li>';
                    }
									}
                    // 为了测试，延迟1秒加载
                    // setTimeout(function(){
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
                    // },1);
                }
            // 加载菜单二的数据
            }else
             if(itemIndex == '1'){
                counter1++;
                var num = 0;
                data = loadajax1(me,'<?php echo U("Index/more_game");?>',3,num,counter1);
                var result = '';
                if(data.status==0){
                  if(counter1==1){
                    result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
												result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a><span class="mark send-mark">首发</span>';
                        if(e.gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        }
                        result += '</div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="description">'+e.features+'</span></p></div></div></li>';
                    
										});
									}else{
										nnnum = nndata.length;
                    for(var i = 0; i < nndata.length; i++){
                        result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a><span class="mark send-mark">首发</span>';
                        if(nndata[i].gift_id>0){
                          result += '<span class="mark gift-mark">礼包</span>';
                        }
                        result += '</div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="description">'+nndata[i].features+'</span></p></div></div></li>';
                    }
									}
                    // 为了测试，延迟1秒加载
                    // setTimeout(function(){
                        $("#"+loadid+' ul').append(result);
                        tab2LoadEnd = true;
                        if(nnnum<num){
                          // 锁定
                          me.lock();
                          // 无数据
                          me.noData();
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                    // },1);

                    // 自动执行一次点击新上架标签
                    // $('#tab-menu .s-slide:first-child').click();
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
                      result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
                            result += '<span class="status"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/activity_notice_time_ongoing.png">进行中...</span></span></span>';
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
                            result += '<span class="status"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/activity_notice_time_ongoing.png">进行中...</span></span></span>';
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
                      result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
                          result +=e.title+'</h2><p class="time"><img src="/Public/Mobile/images/activity_notice_time.png" class="icon"><span class="date">'+e.start_time+'</span></p></div></a></li> ';
                      
											});
										}else{
											nnnum = nndata.length;
                      for(var i = 0; i < nndata.length; i++){
                          result += '<li><a href="'+nndata[i].url+'"><div class="item"><h2 class="title">';
                          if(nndata[i].belong_game){
                            result +='【'+nndata[i].belong_game+'】';
                          }
                          result +=nndata[i].title+'</h2><p class="time"><img src="/Public/Mobile/images/activity_notice_time.png" class="icon"><span class="date">'+nndata[i].start_time+'</span></p></div></a></li> ';
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
                        result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
														result += '<li><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Mobile/images/service_trailer_area.png" class="server-icon">'+e.server_name+'</span><span class="server-time"><img src="/Public/Mobile/images/service_trailer_time.png"  class="server-icon">'+e.pastTime+'</span></p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result += '<li><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Mobile/images/service_trailer_area.png" class="server-icon">'+nndata[i].server_name+'</span><span class="server-time"><img src="/Public/Mobile/images/service_trailer_time.png"  class="server-icon">'+nndata[i].pastTime+'</span></p></div></div></li>';
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
                        result +='<div class="empty s-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
														result +='<li><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox">';
                            if((e.start_time-nowtime)<1800){
                              result +='<a href="javascript:;" data-start_time="'+e.start_time+'" class="butn opening-btn">即将开服</a>';
                            }else if(e.isnotice==0){
                              result +='<a href="javascript:;" onclick="reservefun($(this),'+e.server_id+',1);" class="butn"><i class="icon icon-notice-no"></i><span>通知</span></a>';
                            }else{
                              result +='<a href="javascript:;" onclick="reservefun($(this),'+e.server_id+',0);"  class="butn"><i class="icon icon-notice-yes"></i><span>已设置通知</span></a>';
                            }
                            result +='</div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Mobile/images/service_trailer_area.png" class="server-icon">'+e.server_name+'</span><span class="server-time"><img src="/Public/Mobile/images/service_trailer_time.png"  class="server-icon">'+e.start_date+'</span></p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result +='<li><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox">';
                            if((nndata[i].start_time-nowtime)<1800){
                              result +='<a href="javascript:;" data-start_time="'+nndata[i].start_time+'" class="butn opening-btn">即将开服</a>';
                            }else if(nndata[i].isnotice==0){
                              result +='<a href="javascript:;" onclick="reservefun($(this),'+nndata[i].server_id+',1);" class="butn"><i class="icon icon-notice-no"></i><span>通知</span></a>';
                            }else{
                              result +='<a href="javascript:;" onclick="reservefun($(this),'+nndata[i].server_id+',0);"  class="butn"><i class="icon icon-notice-yes"></i><span>已设置通知</span></a>';
                            }
                            result +='</div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="server-name"><img src="/Public/Mobile/images/service_trailer_area.png" class="server-icon">'+nndata[i].server_name+'</span><span class="server-time"><img src="/Public/Mobile/images/service_trailer_time.png"  class="server-icon">'+nndata[i].start_date+'</span></p></div></div></li>';
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
					// case '#hot':itemIndex=0;break;
					case '#new':itemIndex=1;break;
					case '#activity':itemIndex=2;break;
					case '#activity0':itemIndex=2;break;
					case '#activity1':$('.jshdong a').removeClass('active');$('.jshdong').eq(1).find('a').addClass('active');
					$('.activity .jspanel ul').removeClass('active').eq(1).addClass('active');
						itemIndex=1;break;
					case '#open':itemIndex=3;break;
					case '#open0':itemIndex=3;break;
					case '#open1':$('.jsserver a').removeClass('active');$('.jsserver').eq(1).find('a').addClass('active');
					$('.open .jspanel ul').removeClass('active').eq(1).addClass('active');
					itemIndex=3;break;
					default:itemIndex=1;
				}
				$('#tab-menu .s-slide').removeClass('active').eq(itemIndex).addClass('active');
				$('#tab-slide .tabpanel>.s-slide').hide().eq(itemIndex).show();
				dataload(itemIndex);
			} else {
        $('#tab-menu .s-slide').removeClass('active').eq(1).addClass('active');
				$('#tab-slide .tabpanel>.s-slide').hide().eq(1).show();
				dataload(1);
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
      }else
       if(itemIndex == '1'){
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
          var hdIndex = $('.jshdong a.active').parent('.jshdong').index();
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