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
    
<link href="/Public/Mobile/css/game20180607.css" rel="stylesheet" >
<style>
.footer{
  display: none;
}
</style>
<!-- <header class="header gamedetailheader">
  <section class="wrap">
    <a href="javascript:;" onclick="history.go(-1)" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a>
    <div class="caption">
      <span class="table">
        <span class="table-cell">
          <div class="detailgamename"><?php echo ($data["game_name"]); ?></div>
        </span>
      </span>
    </div>
  </section>
</header> -->
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<section class="trunker">
  <section class="inner">
  
    <section class="contain">
      <div class="detail">
        <!-- <div class="occupy"></div> -->
        <div class="game-banner-show-p">
          <!-- <img src="/Public/Mobile/images/test-img-detile.jpg" width="100%" alt=""> -->
          <img src="<?php echo ($data["introducebg"]); ?>" width="100%" alt="">
        </div>
        <div class="base">
          <div class="wrap-optice-shadow-p"></div>
          <div class="wrap">
            <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data["icon"]); ?>" class="icon"></div>
            <div class="butnbox"><span class="table"><a href="javascript:;" class="butn table-cell setcollection"><i data-collection="<?php echo ($data["collect_status"]); ?>" data-game_id="<?php echo ($data["id"]); ?>" class="icon-star <?php if($data["collect_status"] == 1): ?>on collect_status1<?php else: ?>collect_status0<?php endif; ?>"></i><span><?php if($data["collect_status"] == 1): ?>已<?php endif; ?>收藏</span></a></span></div>
            <div class="textbox">
              <div class="title"><span class="name"><?php if(mb_strlen($data['game_name']) > 8 ): echo mb_substr($data['game_name'],0,8,'utf-8');?>...<?php else: echo ($data["game_name"]); endif; ?></span><span class="type"><?php echo ($data["game_type_name"]); ?></span></div>
              <p class="info"><span class="play-num"><i><?php echo ($data["play_num"]); ?></i>人在玩</span><span class="coll-num"><i><?php echo ($data["collect_num"]); ?></i>人收藏</span></p>
              <p class="slogan" title="<?php echo ($data["features"]); ?>"><?php echo ($data["features"]); ?></p>
            </div>
            <div class="but-begin">
              <a data-href="<?php echo ($data['play_url']); ?>" class="btn beginlogin" onclick="jslogin1()">开始游戏</a>
            </div>
          </div>
        </div>
        <?php if(!empty($data['screenshots'])): ?><div class="screenshot">
            <div class="wrap">
              <div id="screenshot" class="swiper-container">
                <div class="swiper-wrapper">
                  <?php if(is_array($data['screenshots'])): $k = 0; $__LIST__ = $data['screenshots'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($k % 2 );++$k;?><div class="swiper-slide iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($sc); ?>" class="icon"></div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            </div>
          </div><?php endif; ?>
        <div class="description samething">
          <div class="wrap">
            <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>游戏介绍</span></div>
            <div class="content">
              <div class="article">
                <?php echo str_replace("~~","<br>",$data['introduction']);?>
               <?php if(strlen($data['introduction']) > 400): ?><a href="javascript:;" class="showarticle">...全文</a><i class="mark"></i><?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php if(!empty($gamegift)): ?><div class="gift samething">
            <div class="wrap">
              <div class="cntitle"><span class="name"><i class="icon icon-gift"></i>活动礼包</span></div>
              <div class="content">
                <ul class="text-list">
                  <?php if(is_array($gamegift)): $i = 0; $__LIST__ = $gamegift;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gift): $mod = ($i % 2 );++$i;?><li>
                    <div class="item">
                      <div class="butnbox"><a href="javascript:;" class="butn table"><span class="table-cell"><span data-gift_id="<?php echo ($gift['gift_id']); ?>" data-game_id="<?php echo ($gift['game_id']); ?>" class="getgift"><?php if($gift["received"] == 1): ?>已<?php endif; ?>领取</span></span></a></div>
                      <div class="text">
                        <a  class="title">[<?php echo ($gift['game_name']); ?>]<?php echo ($gift['giftbag_name']); ?></a>
                        <div class="surplusbox"><span data-all="<?php echo ($gift['novice_all']); ?>" data-wei="<?php echo ($gift['novice_surplus']); ?>" class="surplus"><i style="width:<?php echo round($gift['novice_surplus']/$gift['novice_all']*100,2);?>%;"></i></span><span class="number">剩余<i><?php echo round($gift['novice_surplus']/$gift['novice_all']*100,2);?>%</i></span></div>
                        <p class="validitytime">有效期：<?php echo set_show_time($gift['start_time'],'date','forever');?>~<?php echo set_show_time($gift['end_time'],'date','forever');?></p>
                      </div>
                    </div>
                  </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
              </div>
            </div>
          </div><?php endif; ?>
        <?php if(!empty($gameactive)): ?><div class="active samething">
            <div class="wrap">
              <div class="cntitle"><span class="name"><i class="icon icon-active"></i>活动</span></div>
              <div class="content">
                <ul class="list text-pic-list">
                  <?php if(is_array($gameactive)): $i = 0; $__LIST__ = $gameactive;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$active): $mod = ($i % 2 );++$i;?><li class="clearfix">
                    <div class="item clearfix">
                      <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo U('Article/detail',array('id'=>$active['id']));?>" class="butn">查看</a></span></span></div>
                      <div class="text">
                        <div class="title"><a href="newsdetail.html" class="name">《<?php echo ($active["belong_game"]); echo ($active["title"]); ?></a></div>
                        <p class="info"><?php if($active["type"] != 'wap_huodong'): ?><span class="cate cate-notice">公告</span><?php else: ?><span class="cate cate-active">活动</span><?php endif; ?><span class="catchword"><?php echo ($active["title"]); ?></span></p>
                      </div>
                    </div>
                  </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
              </div>
            </div>
          </div><?php endif; ?>
        <div class="play samething">
          <div class="wrap">
            <div class="cntitle"><a href="<?php echo U('index#categroy');?>" class="more">更多<i class="icon-arrow-right"></i></a><span class="name"><i class="icon icon-play"></i>大家都在玩</span></div>
            <div class="content">
              <ul class="clearfix">
              <?php if(is_array($gamelike)): $i = 0; $__LIST__ = $gamelike;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$like): $mod = ($i % 2 );++$i;?><li>
                  <div class="item">
                    <a href="<?php echo ($like['play_detail_url']); ?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($like['icon']); ?>" class="icon" /></a>
                    <a  class="namebox"><?php echo ($like['game_name']); ?></a>
                    <a href="<?php echo ($like['play_url']); ?>" class="butnbox"><span class="table"><span class="table-cell">开始</span></span></a>
                  </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?> 
              </ul>
            </div>
          </div>
        </div>
        <div class="start-game-position"></div>
        <!-- <div class="start-game">
          <?php if($data['and_dow_address'] != '' ): ?><a href="<?php echo ($data['and_dow_address']); ?>" class="btn_weiduan">微端下载</a>
          <?php elseif($data['ios_dow_address'] != '' ): ?>
          <a href="<?php echo ($data['ios_dow_address']); ?>" class="btn_weiduan">微端下载</a>
          <?php else: ?>
           <a href="#" class="btn_weiduan" style="background: #B0BBC1">微端下载</a><?php endif; ?>
          <a data-href="<?php echo ($data['play_url']); ?>" class="btn beginlogin" onclick="jslogin1()">开始游戏</a>
        </div> -->
      </div>  
        
    </section>
    
  </section>
</section>
<div class="popmsg"></div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
<script src="/Public/Mobile/js/clipboard.min.js"></script>
<script>

  function jslogin1(){
    res=jslogin();
    if(res){
      location.href = $('.beginlogin').data('href');
    }
  }
  function nologintc(popmsg,msg){
    if(msg==''||msg==undefined){
      msg='暂时无法收藏心爱的游戏~T_T~';
    }
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
      new Swiper('#screenshot', {slidesPerView: 'auto',freeMode: true});
      var $user = "<?php echo is_login();?>";
      $('.setcollection').click(function() {
        var that = $(this),star = that.find('.icon-star'),textbox = that.closest('.butnbox').siblings('.textbox');
        var coll_num = parseInt(textbox.find('p.info span.coll-num i').text());
        // 是否登录
        if ($user) {
          $.ajax({
              type: 'post',
              url: '<?php echo U("Game/collection");?>',
              async:false,
              data:{collect_status:star.attr('data-collection'),game_id:star.data('game_id')},
              dataType: 'json',
              success: function(data){
                  if(data.code==1){
                    if(data.data==1){
                        popmsg.addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">已收藏</p>',1000,250);
                        star.attr('data-collection',1)
                        star.addClass('on').siblings('span').text('已收藏');
                        textbox.find('p.info span.coll-num i').text(coll_num+1);
                    }else{
                        popmsg.addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">收藏已取消</p>',1000,250);
                        star.attr('data-collection',0)
                        star.removeClass('on').siblings('span').text('收藏');
                        if(coll_num-1<0){
                          coll_nums = 0
                        }else{
                          coll_nums = coll_num-1;
                        }
                        textbox.find('p.info span.coll-num i').text(coll_nums);
                    }
                  }else if(data.code==-1){
                      nologintc(popmsg);
                  }
              },
              error: function(xhr, type){
                  
              }
          });
        } else {
          nologintc(popmsg);
        }
        
      });
      
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
                    that.text('已领取');
                    popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_receive_successful.png"><div class="pop-title">领取成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.nvalue+'"></span></div><p class="pop-text">可在[我的礼包]中查看</p><a href="javascript:;" class="copy pop-btn">复制</a></div>');
                    bfp =that.closest('div.butnbox').siblings('div.text');
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
                        //移动端复制
                        that = $(this);
                        $(".copy").css("color", "#14b4c3");
                        $(".copy").text('复制');
                        Copy($('.code').val(),that);
												popmsg.close(400);
												setTimeout(function(){
													popmsg.open().addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">已复制</p>',1000,250);
												},440);
                      });
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
        } else {
          nologintc(popmsg,'暂时无法领取礼包~T_T~');
        }
        
      });
			
			if (!$.trim($('.article').html())) {
				$('.article').css({'height':'.8rem'});
			}
      
      $('.showarticle').click(function() {
        var that=$(this),parent = that.closest('.article');
        var position = parent.find('.mark').position();
        var height = parseInt($('html').css('font-size'))*2.55;
        var num = position.top;
        (position.left>(parent.width()*0.6))?(num+=29):(num += 24);
        if (that.hasClass('on')) {
          that.text('...全文').removeClass('on');
          parent.css({'height':height+'px'});
        } else {
          parent.css({'height':(num)+'px'});
          that.text('收起').addClass('on');
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