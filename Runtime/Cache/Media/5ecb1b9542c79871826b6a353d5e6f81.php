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
    
<link href="/Public/Media/css/user.css" rel="stylesheet" >

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
              
<header class="header">
  <section class="wrap">
    <a href="<?php echo U('user');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a>
    <div class="caption"><span class="table"><span class="table-cell">我的收藏</span></span></div>
    <?php if(count($coll) > 0 || count($foot) > 0): ?><a href="javascript:;" class="hbtn right user-del jsdel jsdelsh"><span class="table"><span class="table-cell"><span class="delbox"><img src="/Public/Media/images/nav_btn_delete.png" class="icon"><div class="deltxt">完成</div></span></span></span></a><?php endif; ?>
	</section>
</header>

              
              
<div class="mainer user-collection">
    <div class="occupy"></div>
    
    <section class="trunker">
    
      <section class="inner">
        <section class="contain">
          <div class="list collection-list">
            
            
            <div class="tab-scroll clearfix">
              <div id="tab-menu">
                <div class="s-container">
                    <div class="s-wrapper tabmenu">
                        <div class="s-slide s-visible active"><a href="#collection">我的收藏</a></div>
                        <div class="s-slide s-visible"><a href="#print">我的足迹</a></div>
                    </div>
                </div>
              </div>
              <div id="tab-slide">
                <div class="s-container s-no-swiping">
                    <div class="s-wrapper tabpanel">
                    <?php if(!empty($coll)): ?><div class="s-slide " style="display:block;">
                        <ul class="list text-icon-list jscollection jstype jstil">
                        <?php if(is_array($coll)): $i = 0; $__LIST__ = $coll;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><li class="itembox" data-behavior_id="<?php echo ($da["bid"]); ?>">
                            <div class="item clearfix">
                              <a href="<?php echo ($da["play_detail_url"]); ?>" class="iconbox"><label class="input-checkbox"><input type="checkbox" name="ids" class="checkbox ids" value="1"><i class="icon"></i></label><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($da["icon"]); ?>" class="icon"></a>
                              <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo ($da["play_url"]); ?>" class="butn toplay">去玩</a><a href="<?php echo U('Game/index',array('game_type_id'=>$da['game_type_id'],'from'=>'collec'));?>" class="butn similar">找相似</a></span></span></div>
                              <div class="textbox">
                                <a href="<?php echo ($da["play_detail_url"]); ?>" class="title"><?php echo ($da["game_name"]); ?></a>
                                <p class="info"><span class="type"><?php echo ($da["game_type_name"]); ?></span><span class="number"><i class=""><?php echo ($da["play_num"]); ?></i>人在玩</span></p>
                              </div>
                            </div>

                          </li><?php endforeach; endif; else: echo "" ;endif; ?>  
                        </ul>
                        
                      </div>
                    <?php else: ?>
                      <div class="s-slide" style="display:block;">
                        <div class="empty">
                          <img src="/Public/Media/images/blank_collect.png" class="empty-icon">
                          <p class="empty-text">暂无收藏</p>
                        </div>
												<?php if(!empty($recgame)): ?><div class="hot-game">
                          <div class="hot-game-title"><span>推荐游戏</span><a href="<?php echo U('Game/index');?>" class="more"><img src="/Public/Media/images/ma_more.png" class="icon-right"></a></div>
                          <ul class="list text-pic-list">
                          <?php if(is_array($recgame)): $i = 0; $__LIST__ = $recgame;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?><li class="clearfix">
                              <div class="item clearfix">
                                <a href="<?php echo ($hot["play_detail_url"]); ?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($hot["icon"]); ?>" class="icon"></a>
                                <div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" class="butn jscollection setcollection" data-collection="0" data-game_id="<?php echo ($hot["id"]); ?>">收藏</a></span></span></div>
                                <div class="textbox">
                                  <a href="<?php echo ($hot["play_detail_url"]); ?>" class="title"><?php echo ($hot["game_name"]); ?></a>
                                  <p class="info"><span class="type"><?php echo ($hot["game_type_name"]); ?></span><span class="number"><i class=""><?php echo ($hot["collect_num"]); ?></i>人收藏</span></p>
                                  <p class="slogan"><?php echo ($hot["features"]); ?></p>
                                </div>
                              </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                          </ul>
                        </div><?php endif; ?>
                      </div><?php endif; ?>
                    <?php if(!empty($foot)): ?><div class="s-slide ">
                      
                        <ul class="list text-img-list jsprint jstype">
                        <?php if(is_array($foot)): $i = 0; $__LIST__ = $foot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dat): $mod = ($i % 2 );++$i;?><li class="itembox clearfix">
                            <div class="print-title"><span class="table"><span class="table-cell"><label class="input-checkbox"><input type="checkbox" class="checkbox print-all" ><i class="icon"></i></label><span class="date"><?php echo ($key); ?></span></span></span></div>
                            <div class="print-content clearfix">
                            <?php if(is_array($dat)): $i = 0; $__LIST__ = $dat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><a href="<?php echo ($da["play_detail_url"]); ?>" class="butn" data-behavior_id="<?php echo ($da["bid"]); ?>"><div class="iconbox"><label class="input-checkbox"><input type="checkbox" class="checkbox print-ids"><i class="icon"></i></label><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($da["icon"]); ?>" class="icon"></div><p class="title"><?php echo ($da["game_name"]); ?></p></a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                          </li><?php endforeach; endif; else: echo "" ;endif; ?> 
                        </ul>
                      </div>
                    <?php else: ?>
                      <div class="s-slide">
                        <div class="empty">
                          <img src="/Public/Media/images/blank_footprint.png" class="empty-icon">
                          <p class="empty-text">暂无浏览足迹</p>
                        </div>
                        <?php if(!empty($recgame)): ?><div class="hot-game">
                          <div class="hot-game-title"><span>推荐游戏</span><a href="<?php echo U('Game/index');?>" class="more"><img src="/Public/Media/images/ma_more.png" class="icon-right"></a></div>
                          <ul class="list text-pic-list">
                            <?php if(is_array($recgame)): $i = 0; $__LIST__ = $recgame;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?><li class="clearfix">
                              <div class="item clearfix">
                                <a href="gamedetail.html" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($hot["icon"]); ?>" class="icon"></a>
                                <div class="butnbox"><span class="table"><span class="table-cell"><a href="<?php echo ($hot["play_detail_url"]); ?>" class="butn jscollection">去玩</a></span></span></div>
                                <div class="textbox">
                                  <a href="<?php echo ($hot["play_detail_url"]); ?>" class="title"><?php echo ($hot["game_name"]); ?></a>
                                  <p class="info"><span class="type">动作</span><span class="number"><i class=""><?php echo ($hot["play_num"]); ?></i>人在玩</span></p>
                                  <p class="slogan"><?php echo ($hot["features"]); ?></p>
                                </div>
                              </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                          </ul>
                        </div><?php endif; ?>
                        
                      </div><?php endif; ?>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
      </section>
    </section>
    
</div>


                  
    <div class="collection-delbox">
      <div class="table">
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
        <label class="input-checkbox2 jsallchecked"><input type="checkbox" class="checkbox check-all"><i class="icon"></i><span>全选</span></label>
        <div class="butnbox table-cell">
          <a href="javascript:;" class="butn jsdelall">删除</a>
        </div>
      </div>
    </div>
    <div class="pop"></div>

              
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
    
<script src="/Public/Media/js/clipboard.min.js"></script>
    <script>
        $(function() {
          var pop = $('.pop').pop();
					var itemIndex=0;
          
					/* 标签切换 */
					$('#tab-menu .s-slide').click(function() {
						var that=$(this);
						var index = that.index();
						itemIndex = index;
						var hash = that.closest('.tabmenu').find('.active a').attr('href');
						if (that.find('a').attr('href') == hash) {return false;}
						that.siblings().removeClass('active');
						that.addClass('active');
						$('#tab-slide .tabpanel>.s-slide').hide().eq(index).show();
						
						
							$('.jsdel').removeClass('on').find('.deltxt').stop(true).fadeOut(200).siblings('.icon').delay(400).fadeIn(200);
              
              
              $('.jsprint .itembox').removeClass('delmark').find('.input-checkbox,.input-checkbox2').fadeOut();
              $('.jscollection .itembox .similar').fadeIn();
              $('.jscollection .itembox').removeClass('delmark').find('.input-checkbox').fadeOut();
              $('.collection-delbox').fadeOut().find('.jsallchecked').fadeOut();
							$('.jscollection .itembox .item a').each(function(){
								var that_a = $(this);
								if(that_a.attr('data-url'))
									that_a.attr('href',that_a.attr('data-url'));
							});
							$('.jsprint .itembox a').each(function(){
								var that_a = $(this);
								if(that_a.attr('data-url'))
									that_a.attr('href',that_a.attr('data-url'));
							});
							var len = $('#tab-slide .tabpanel>.swiper-slide').eq(index).find('.empty').length;
							if(len>0) {$('.jsdelsh').hide();}
							else {$('.jsdelsh').show();}
						
					});
					
					if ($('#tab-slide .jstil').length<1) {
						$('.jsdelsh').hide();
					}
        
          $('.jsitemdel').click(function() {
            var that=$(this),id=that.attr('data-id');
            $ids = that.closest('.jstype').find('li').attr('data-behavior_id');
            if(id==1){
              mmsg='收藏';
            }else{
              mmsg='足迹';
            }
            pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">确定要删除该'+mmsg+'吗？</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm ">确定</a></div>');
            $('.jscancel').click(function() {pop.close();});
            $('.pop-comfirm').click(function() {
              pop.close();
              // ajax
              setTimeout(function() {
                $.ajax({
                  type:'post',
                  url:"<?php echo U('optionBehavior');?>",
                  data:{ids:$ids,type:id},
                  success:function(data){
                    if(data.status==1){
                      // 成功
                      pop.addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">已删除</p>',1000,250);
                      that.closest('li').remove();
                      if(that.parent('ul li').length<1){
                        location.href = location.href;
                      }
                    }else{
                      // 失败
                      pop.addClass('pop-cue').msg('<div class="pop-content"><div class="pop-title">删除失败</div><div class="pop-text">可能网络错误，请重新操作</div></div>',1000,250);
                    }
                  },error:function(){
                    // 失败
                    pop.addClass('pop-cue').msg('<div class="pop-content"><div class="pop-title">删除失败</div><div class="pop-text">可能网络错误，请重新操作</div></div>',1000,250);
                    location.href= location.href;
                  }
                });
                
                
                
              },810);
              return false;
            });
            
            return false;
          });
          function nologintc(popmsg){
            popmsg.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_unlisted.png"><div class="pop-title">您还未登录</div><p class="pop-text">暂时无法收藏心爱的游戏~T_T~</p><a href="javascript:;" class="pop-btn tologin">去登录</a></div>'); 
            popmsg.find('.pop-close').click(function() {popmsg.close();});
            popmsg.find('.tologin').click(function() {popmsg.close();$('.jslogin').click()});
          }
          var popmsg = pop;
          var $user = "<?php echo is_login();?>";
          $('.setcollection').click(function() {
            var that = $(this),star = that;
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
                            setTimeout('location.href = location.href',1000);
                        }else{
                            popmsg.addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">收藏已取消</p>',1000,250);
                            star.attr('data-collection',0)
                            star.removeClass('on').siblings('span').text('收藏');
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
          $('.jsdelall').click(function() {
            $('.tabmenu .s-slide').each(function(i){
              if($(this).hasClass('active')){
                that = $('.tabpanel>.s-slide').eq(i);
                if(i==0){
                  id = 1;
                }else{
                  id = 2;
                }
              }
            });
            var $ids='';
            that.find('li .checkbox').each(function(j){
              if($(this).hasClass('on')){
                if(id==1){
                  $ii = $(this).closest('li').attr('data-behavior_id');
                  if($ii>0){
                    $ids += $ii+',';
                  }
                }else{
                  $ii = $(this).closest('li a.butn').attr('data-behavior_id');
                  if($ii>0){
                    $ids += $ii+',';
                  }
                }
              }
            });
            if($ids==''){
							layer.msg('请选择需要操作的数据');
              return false;
            }else{
              $ids = $ids.substring(0,$ids.length-1);
            }
            pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">确定要删除选中的数据吗？</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm ">确定</a></div>');
            $('.jscancel').click(function() {pop.close();});
            $('.pop-comfirm').click(function() {
              pop.close();
              // ajax
              setTimeout(function() {
                $.ajax({
                  type:'post',
                  url:"<?php echo U('optionBehavior');?>",
                  data:{ids:$ids,type:id},
                  success:function(data){
                    console.log(data);
                    if(data.status==1){
                      // 成功
                      pop.addClass('pop-message').msg('<img class="pop-image" src="/Public/Media/images/pop_completed.png"><p class="pop-text">已删除</p>',1000,250);
                      setTimeout(function(){window.location.reload();},2000);
                    }else{
                      // 失败
                      pop.addClass('pop-cue').msg('<div class="pop-content"><div class="pop-title">删除失败</div><div class="pop-text">可能网络错误，请重新操作</div></div>',1000,250);
                    }
                  },error:function(){
                    // 失败
                    pop.addClass('pop-cue').msg('<div class="pop-content"><div class="pop-title">删除失败</div><div class="pop-text">可能网络错误，请重新操作</div></div>',1000,250);
                    setTimeout(function(){window.location.reload();},2000);
                  }
                });
                
                
                
              },810);
              return false;
            });
            
            return false;
          });
          
          $('.jsdel').click(function() {
            var that=$(this);
            $('ul.jstype li').each(function(index,ele){
              if($(this).find('.jjj').attr('style')!=undefined){
                $(this).find('.jjj').removeAttr('style');
                $(this).find('.butnbox').removeClass('hide');
              }
            });
						var index = $('#tab-menu .tabmenu .s-slide.active').index();
            if (that.hasClass('on')) {
              that.removeClass('on').find('.deltxt').stop(true).fadeOut(200).siblings('.icon').delay(400).fadeIn(200);
              
              if (index==0) {
                $('.jscollection .itembox').removeClass('delmark').find('.input-checkbox').fadeOut();
                $('.jscollection .itembox .similar').fadeIn();
                $('.collection-delbox').fadeOut().find('.jsallchecked').fadeOut();
								$('.jscollection .itembox .item a').each(function(){
									var that_a = $(this);
									if (that_a.attr('data-url'))
										that_a.attr('href',that_a.attr('data-url'));
								});
              } else {
                $('.jsprint .itembox').removeClass('delmark').find('.input-checkbox,.input-checkbox2').fadeOut();
                $('.collection-delbox').fadeOut();
								$('.jsprint .itembox a').each(function(){
									var that_a = $(this);
									if (that_a.attr('data-url'))
										that_a.attr('href',that_a.attr('data-url'));
								});
              }
              
            } else {
              that.addClass('on').find('.icon').stop(true).fadeOut(200).siblings('.deltxt').delay(400).fadeIn(200);
              
              if (index==0) {
                $('.jscollection .itembox').addClass('delmark').find('.input-checkbox').fadeIn();
                $('.jscollection .itembox .similar').fadeOut();
                $('.collection-delbox').fadeIn().find('.jsallchecked').fadeIn();
								$('.jscollection .itembox .item a').each(function(){
									var that_a = $(this);
									that_a.attr('data-url',that_a.attr('href'));
									that_a.attr('href','javascript:;');
								});
              } else {
                $('.jsprint .itembox').addClass('delmark').find('.input-checkbox,.input-checkbox2').fadeIn();
                $('.collection-delbox').fadeIn();
								$('.jsprint .itembox a').each(function(){
									var that_a = $(this);
									that_a.attr('data-url',that_a.attr('href'));
									that_a.attr('href','javascript:;');
								});
              }
            }
            return false;
          });
          
          $(".print-all").click(function(){
            var that = $(this).toggleClass('on');
            var ids = that.closest('.itembox').find('.print-ids');
            this.checked?(ids.addClass('on')):(ids.removeClass('on'));
            ids.prop("checked", this.checked);
          });
          $(".print-ids").click(function(){
            var that = $(this).toggleClass('on');
            var parent = that.closest('.itembox');
            var option = parent.find(".print-ids");
            var all = parent.find('.print-all');
            option.each(function(i){
              if(!this.checked){
                all.prop("checked", false).removeClass('on');
                return false;
              }else{
                all.prop("checked", true).addClass('on');
              }
            });
          });
          
					var fromnumb = location.hash;
					if(fromnumb){
						switch(fromnumb) {
							case '#collection':itemIndex=0;break;
							case '#print':itemIndex=1;break;
							default:itemIndex=0;
						}
						$('#tab-menu .s-slide').removeClass('active').eq(itemIndex).addClass('active');
						$('#tab-slide .tabpanel>.s-slide').hide().eq(itemIndex).show();
						
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