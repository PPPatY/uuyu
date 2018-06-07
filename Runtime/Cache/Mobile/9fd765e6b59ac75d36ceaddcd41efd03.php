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
    
<link href="/Public/Mobile/css/search.css" rel="stylesheet" >
<style>
.jshid{
  display: none;
}
</style>
<header class="header">
  <section class="wrap">
    <a href="javascript:;" class="hbtn jshid left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/nav_search_return.png"></span></span></a>
    <div class="searchbox">
      <form class="search-input table" action="" onkeydown="if(event.keyCode==13){return false;}">
        <span class="table-cell">
          <a href="javascript:;" class="iconbox jssearch"><span class="table"><span class="table-cell"><i class="icon icon-search"></i></span></span></a>
          <a href="javascript:;" class="iconbox jsdel"><span class="table"><span class="table-cell"><i class="icon icon-del"></i></span></span></a>
          <div class="input-group">
          <input type="text" class="txt jskeywords" name="keywords" placeholder="大家都在搜<?php echo ($titlegame["game_name"]); ?>" />
          </div>
        </span>
      </form>
    </div>
    <a href="javascript:;" onclick="history.go(-1);" class="hbtn right search-cancel jsscancel"><span class="table"><span class="table-cell">取消</span></span></a>
  </section>
</header>
<style>
.footer{
  display: none;
}
</style>
<div class="occupy"></div>

<section class="trunker">

  <section class="inner jsinner">
    <section class="contain">
      <div class="default-list">
        <div class="recommend">
          <div class="recommend-title same-title"><span>热门推荐</span><a href="javascript:;" class="searchchangebutn"><img src="/Public/Mobile/images/search_batch.png" class="icon"><span>换一批</span></a></div>
          <div class="recommend-content">
            <div id="jschange" class="swiper-container">
                <div class="swiper-wrapper">
                <?php if(is_array($allgame)): $i = 0; $__LIST__ = $allgame;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$all): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><div class="item"><a href="<?php echo ($all["play_detail_url"]); ?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($all["icon"]); ?>" class="icon"></a><a href="<?php echo ($all["play_detail_url"]); ?>" class="title"><?php echo ($all["game_name"]); ?></a></div></div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
          </div>
        </div>
        <div class="hotwords">
          <div class="hotwords-title same-title"><span>搜索热词</span></div>
          <div class="hotwords-content">
            <div class="hotwords-list clearfix">
            <?php if(is_array($hotgame)): $i = 0; $__LIST__ = $hotgame;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?><!-- <a href="<?php echo ($hot["play_detail_url"]); ?>" class="butn"><?php echo ($hot["game_name"]); ?></a> -->
              <a data-href="<?php echo ($hot["play_detail_url"]); ?>" class="butn hotbutn"><?php echo ($hot["game_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    
  </section>
</section>

<script src="/Public/Mobile/js/common.js"></script>
<script>
    cfkw = "<?php echo I('kw');?>";
    if(cfkw!=''){
      $('.jskeywords').val(cfkw);
      $('.jsscancel').attr('onclick','history.go(-2);');
    }
    $(function() {
          var timer;
          var kw = $('.jskeywords');
          $('.hotbutn').click(function(){
            $reci = $(this).text();
            kw.val($reci);
            if ($.trim(kw.val()).length>0) {$('.jsdel').addClass('on')}
            kkw = $('.jsdel').closest('form').find('input').val();
            search(kkw);
          });
          
          if ($.trim(kw.val()).length>0) {kw.closest('form').find('.jsdel').addClass('on');}
          kkw = $('.jsdel').removeClass('on').closest('form').find('input').val();
          if(kkw!=''){
            search(kkw);
          }
          $('.jsdel').click(function() {
            var that=$(this);
            that.removeClass('on').closest('form').find('input').val('').focus();
            clearInterval(timer);
            $('.trunker .jsinner').css('display','block');
            $('.nonner').remove();
            $('.isinner').remove();
            return false;
          });
          kw.addClass('disabled');
          kw.keyup(function() {
            var that = $(this),val=$.trim(that.val()),parent=that.closest('form');
            clearInterval(timer);
            kw.addClass('disabled');
            if (val) {
              parent.find('.jsdel').addClass('on');
              search(val);
            }else{
              $('.jsdel').click();
            }
            
            return false;
          });
          var val1 =$.trim(kw.val());
          kw.focus(function(){
            kw.removeClass('disabled');
            var that = $(this),parent=that.closest('form');
            timer = setInterval(function(){
              val=$.trim(that.val());
              if(val.length>0&&val1!=val){
                kw.removeClass('disabled');
              }else if(val1!=val){
                $('.jsdel').click();
              }
              if(!kw.hasClass('disabled')&&val.length>0){
                search(val);
              }
              val1 = val;
            },300);
          });
          kw.blur(function(){
            clearInterval(timer);
          });
          function search(kw){
            if(!kw){
              return false;
            }
            $.ajax({
              type:'post',
              url:"<?php echo U('search');?>",
              data:{keyword:kw},
              success:function(data){
                if(data.code==0){
                  $('.trunker .jsinner').css('display','none');
                  $('.trunker .jsinner').siblings().remove();
                  $('.trunker').append('<section class="inner nonner"><section class="contain"><div class="list search-list"><div class="empty"><img src="/Public/Mobile/images/seach_noresult.png" class="empty-icon"><p class="empty-text">没搜到相关信息？换个词试试</p></div></div></section></section>');
                }else if(data.code==1){
                  $('.arrow-left').attr('href',data.url);
                  var kwstr = $('.jskeywords').val();
                  var kwarray = kwstr.split ("");
                  var result = '<section class="inner isinner"><section class="contain"><div class="list search-list">';
                  if(data.data.game!=''){
                    result += '<div class="search-item"> <div class="search-item-title">游戏</div><div class="serrch-item-content"><ul>';
                    for(var i = 0; i < data.data.game.length; i++){
                        if(i>2){
                          result +='<li class="list-item clearfix jshid">';
                        }else{
                          result +='<li class="list-item clearfix">';
                        }
                        result += '<div class="item clearfix"><a href="'+data.data.game[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data.game[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+data.data.game[i].play_url+'" class="butn">开始</a></div><div class="textbox"><a href="'+data.data.game[i].play_detail_url+'" class="srtitle gamename">';
                        restr = data.data.game[i].game_name;
                        var rearray = restr.split ("");
                        for(var ii = 0; ii < rearray.length; ii++){
                          for(var j = 0; j < kwarray.length; j++){
                            if(rearray[ii]==kwarray[j]){
                              rearray[ii] = '<span>'+rearray[ii]+'</span>';
                            }
                          }
                        }
                        b = rearray.join("");
                        result +=b;
                        result +='</a><p class="info"><span class="type">'+data.data.game[i].game_type_name+'</span><span class="description">'+data.data.game[i].features+'</span></p></div></div></li> ';
                    }
                    result+='</ul></div>';
                    if(data.data.game.length>3){
                      result+='<a href="javascript:;" class="search-item-link jsmore"><div class="sil-inner clearfix"><span>查看更多游戏</span><span class="icon-right"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/search_more.png"></span></span></span></div></a>';
                    }
                    result+='</div>';
                  }
                  if(data.data.gift!=''){
                    console.log(data);
                    result += '<div class="search-item"><div class="search-item-title">礼包</div><div class="serrch-item-content"><ul>';
                    if(jQuery.isArray( data.data.gift )){
                      for(var i = 0; i < data.data.gift.length; i++){
                          if(i>2){
                            result +='<li class="list-item clearfix jshid">';
                          }else{
                            result +='<li class="list-item clearfix">';
                          }
                          result += '<div class="item clearfix"><a href="'+data.data.gift[i].gift_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data.gift[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+data.data.gift[i].gift_detail_url+'" class="butn">去领取</a></div><div class="textbox"><a href="'+data.data.gift[i].gift_detail_url+'" class="srtitle giftname">《';
                          restr = data.data.gift[i].game_name;
                          var rearray = restr.split ("");
                          for(var ii = 0; ii < rearray.length; ii++){
                            for(var j = 0; j < kwarray.length; j++){
                              if(rearray[ii]==kwarray[j]){
                                rearray[ii] = '<span>'+rearray[ii]+'</span>';
                              }
                            }
                          }
                          b = rearray.join("");
                          result +=b;
                          result +='》'+data.data.gift[i].giftbag_name+'</a><div class="surplusbox"><span class="surplus"><i style="width:'+(data.data.gift[i].novice_surplus/data.data.gift[i].novice_all*100).toFixed(2)+'%"></i></span><span class="number">剩余<i>'+(data.data.gift[i].novice_surplus/data.data.gift[i].novice_all*100).toFixed(2)+'%</i></span></div><p class="date">有效期：';
                          result +=data.data.gift[i].start_time+'~'+data.data.gift[i].end_time;
                          result +='</p></div></div></li>';
                      }
                    }else{
                      var i = 0;
                      $.each(data.data.gift,function(j,e){
                        i++;
                        if(i>2){
                          result +='<li class="list-item clearfix jshid">';
                        }else{
                          result +='<li class="list-item clearfix">';
                        }
                        result += '<div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.gift_detail_url+'" class="butn">去领取</a></div><div class="textbox"><a href="'+e.gift_detail_url+'" class="srtitle giftname">《';
                        restr = e.game_name;
                        var rearray = restr.split ("");
                        for(var ii = 0; ii < rearray.length; ii++){
                          for(var j = 0; j < kwarray.length; j++){
                            if(rearray[ii]==kwarray[j]){
                              rearray[ii] = '<span>'+rearray[ii]+'</span>';
                            }
                          }
                        }
                        b = rearray.join("");
                        result +=b;
                        result +='》'+e.giftbag_name+'</a><div class="surplusbox"><span class="surplus"><i style="width:'+(e.novice_surplus/e.novice_all*100).toFixed(2)+'%"></i></span><span class="number">剩余<i>'+(e.novice_surplus/e.novice_all*100).toFixed(2)+'%</i></span></div><p class="date">有效期：';
                        result +=e.start_time+'~'+e.end_time;
                        result +='</p></div></div></li>';
                      });
                    }
                    result+='</ul> </div>';
                    if(data.data.gift.length>3){
                      result+='<a href="javascript:;" class="search-item-link jsmore"><div class="sil-inner clearfix"><span>查看更多礼包</span><span class="icon-right"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/search_more.png"></span></span></span></div></a>';
                    }
                    result+='</div>';
                  }
                  if(data.data.article!=''){
                    result += '<div class="search-item"><div class="search-item-title">活动</div><div class="serrch-item-content"><ul>';
                    for(var i = 0; i < data.data.article.length; i++){
                        if(i>2){
                          result +='<li class="list-item clearfix jshid">';
                        }else{
                          result +='<li class="list-item clearfix">';
                        }
                        result += '<div class="item clearfix"><div class="butnbox"><a href="'+data.data.article[i].article_detail_url+'" class="butn">查看</a></div><div class="textbox activetext"><a href="'+data.data.article[i].article_detail_url+'" class="srtitle activename">《';
                        restr = data.data.article[i].belong_game;
                        var rearray = restr.split ("");
                        for(var ii = 0; ii < rearray.length; ii++){
                          for(var j = 0; j < kwarray.length; j++){
                            if(rearray[ii]==kwarray[j]){
                              rearray[ii] = '<span>'+rearray[ii]+'</span>';
                            }
                          }
                        }
                        b = rearray.join("");
                        result +=b;
                        result +='》'+data.data.article[i].title+'</a><p class="notice">';
                        if(data.data.article[i].hdtype!='活动'){
                          result +='<span class="cate cate-notice">';
                        }else{
                          result +='<span class="cate cate-active">';
                        }
                        result +=data.data.article[i].hdtype+'</span><span class="catchword">'+data.data.article[i].description+'</span></p></div></div></li>';
                    }
                    result+='</ul> </div>';
                    if(data.data.article.length>3){
                      result+='<a href="javascript:;" class="search-item-link jsmore"><div class="sil-inner clearfix"><span>查看更多活动</span><span class="icon-right"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/search_more.png"></span></span></span></div></a>';
                    }
                    result+='</div>';
                  }
                  result+='</div></section></section>';
                  $('.trunker .jsinner').css('display','none');
                  $('.trunker .jsinner').siblings().remove();
                  $('.trunker').append(result);
                }
              },error:function(){

              }
            });
            $('.jskeywords').addClass('disabled');
          }
          new Swiper('#jschange', {slidesPerView:4,slidesPerGroup:4,loop:true,loopAdditionalSlides:4,nextButton:'.searchchangebutn'});
          $("body").on("click",'.jsmore',function(){
              that = $(this); parent = that.closest('.search-item'); tt = $('.searchbox');
              that.css('display','none');
              parent.find('li').removeClass('jshid');
              parent.siblings('.search-item').remove();
              tt.addClass('list');
              tt.siblings('.arrow-left').removeClass('jshid');
          });
          function backsearch(kw){

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