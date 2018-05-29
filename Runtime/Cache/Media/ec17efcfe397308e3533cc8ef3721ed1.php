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
    
<link href="/Public/Media/css/game.css" rel="stylesheet" >

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
              
<header class="header gamedetailheader">
  <section class="wrap">
    <a href="javascript:;" onclick="history.go(-1)" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a>
    <div class="caption">
      <span class="table">
        <span class="table-cell">
          <div class="detailgamename"><?php echo ($data["game_name"]); ?></div>
        </span>
      </span>
    </div>
  </section>
</header>

              
              
<div class="mainer">
<div class="occupy"></div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<section class="trunker">
  <section class="inner">
  
    <section class="contain">
      <div class="detail">
        <div class="base">
          <div class="wrap">
            <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data["icon"]); ?>" class="icon"></div>
            <div class="butnbox"><span class="table"><a href="javascript:;" class="butn table-cell setcollection"><i data-collection="<?php echo ($data["collect_status"]); ?>" data-game_id="<?php echo ($data["id"]); ?>" class="icon-star <?php if($data["collect_status"] == 1): ?>on collect_status1<?php else: ?>collect_status0<?php endif; ?>"></i><span><?php if($data["collect_status"] == 1): ?>已<?php endif; ?>收藏</span></a></span></div>
            <div class="textbox">
              <div class="title"><span class="name"><?php if(mb_strlen($data['game_name']) > 8 ): echo mb_substr($data['game_name'],0,8,'utf-8');?>...<?php else: echo ($data["game_name"]); endif; ?></span><span class="type"><?php echo ($data["game_type_name"]); ?></span></div>
              <p class="info"><span class="play-num"><i><?php echo ($data["play_num"]); ?></i>人在玩</span><span class="coll-num"><i><?php echo ($data["collect_num"]); ?></i>人收藏</span></p>
              <p class="slogan" title="<?php echo ($data["features"]); ?>"><?php echo ($data["features"]); ?></p>
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
                        <div class="title"><a href="<?php echo U('Article/detail',array('id'=>$active['id']));?>" class="name">《<?php echo ($active["belong_game"]); echo ($active["title"]); ?></a></div>
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
            <div class="cntitle"><a href="<?php echo U('index');?>" class="more">更多<i class="icon-arrow-right"></i></a><span class="name"><i class="icon icon-play"></i>大家都在玩</span></div>
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
      </div>  
        
    </section>
    
  </section>
</section>
</div>


              
  <div class="popmsg"></div>
  <div class="start-game">
    <a href="<?php echo U('Index/qrcode');?>" target="_blank" class="btn_weiduan">微端下载</a>
    <a data-href="<?php echo ($data['play_url']); ?>" class="btn beginlogin" onclick="jslogin1()">开始游戏</a>
  </div>

              
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
    popmsg.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_unlisted.png"><div class="pop-title">您还未登录</div><p class="pop-text">'+msg+'</p><a href="javascript:;" class="pop-btn tologin">去登录</a></div>'); 
    popmsg.find('.pop-close').click(function() {popmsg.close();});
    popmsg.find('.tologin').click(function() {popmsg.close();$('.jslogin').click()});
  }
  function Copy(str,that){
      var save = function(e){
          e.clipboardData.setData('text/plain', str);
          e.preventDefault();
      }
      that.css("color", "#FFF");
      document.addEventListener('copy', save);
      document.execCommand('copy');
      document.removeEventListener('copy',save);
      that.text('复制成功');
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
                        popmsg.addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">已收藏</p>',1000,250);
                        star.attr('data-collection',1)
                        star.addClass('on').siblings('span').text('已收藏');
                        textbox.find('p.info span.coll-num i').text(coll_num+1);
                    }else{
                        popmsg.addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">收藏已取消</p>',1000,250);
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
                    popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_receive_successful.png"><div class="pop-title">领取成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.nvalue+'"></span></div><p class="pop-text">可在[我的礼包]中查看</p><a href="javascript:;" class="copy pop-btn">复制激活码</a></div>');
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
													popmsg.open().addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">已复制</p>',1000,250);
												},440);
                      });
                  }else{
                    // 失败
										var butn = '';
										if (data.code!='-2') {butn += '<a  class="pop-btn jsfresh">重试</a>';}
                    popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_receive_fail.png"><div class="pop-title">领取失败！</div><p class="pop-text">'+data.msg+'</p>'+butn+'</div>');
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
        var height = parseInt($('html').css('font-size'))*2.5;
        var num = position.top;
        num += 32;
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