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
<link href="/Public/Media/css/gift.css" rel="stylesheet" >

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
              
<header class="header ">
  <section class="wrap">
    <div class="caption giftindex">
      <span class="table">
        <span class="table-cell">
          <div id="tab-menu">
            <div class="s-container">
                <div class="s-wrapper tabmenu">
                    <div class="s-slide s-visible active"><a href="#recom" class="table"><span class="table-cell">推荐</span></a></div>
                    <div class="s-slide s-visible"><a href="#hot" class="table"><span class="table-cell">热门</span></a></div>
                    <div class="s-slide s-visible"><a href="#new" class="table"><span class="table-cell">最新</span></a></div>
                    <div class="s-slide s-visible"><a href="#all" class="table"><span class="table-cell">全部</span></a></div>
                </div>
            </div>
          </div>
        </span>
      </span>
    </div>
    <a href="<?php echo U('Search/index');?>" class="hbtn search"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_btn_search.png"></span></span></a>
  </section>
</header>

              
              
<div class="mainer">
<style>
.dropload-noData{
  display: none;
}
</style>
    <div class="occupy"></div>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    <section class="trunker">
      <section class="inner">
      
        <section class="contain">
          
          
            <div class="tab-scroll">
              <div id="tab-slide">
                <div class="s-container">
                    <div class="s-wrapper tabpanel">
                        <div class="s-slide" style="display:block;">
                          <ul class="list second-list">
                          <!-- -->
                          <?php if(empty($data)): ?><div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>
                          <?php else: ?>
                            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><li class="clearfix">
                                <div class="item clearfix">
                                  <div class="game-info">
                                    <a href="<?php echo ($da["play_detail_url"]); ?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($da["icon"]); ?>" class="icon"></a>
                                    <div class="text"><div class="title"><a href="<?php echo ($da["play_detail_url"]); ?>"><?php echo ($da["game_name"]); ?></a></div><p>共<span><?php echo ($da["gbnum"]); ?></span>个礼包</p></div>
                                  </div>
                                  <div class="gift-list">
                                    <?php if(is_array($da['gblist'])): $k = 0; $__LIST__ = $da['gblist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($k % 2 );++$k;?><div <?php if($k == 1): ?>class="subitem active" <?php else: ?>class="subitem"<?php endif; ?>>
                                        <div class="butnbox getgift" data-gift_id="<?php echo ($d['gift_id']); ?>" data-game_id="<?php echo ($d['game_id']); ?>"><span class="table"><span class="table-cell"><a href="javascript:;" <?php if($d["geted"] == 0): ?>class="butn">领取<?php else: ?>class="butn disabled">已领取<?php endif; ?></a></span></span></div>
                                        <div class="text">
                                          <div class="title"><a href="<?php echo ($d["gift_detail_url"]); ?>" class="name">[<?php echo ($d["giftbag_name"]); ?>]</a></div>
                                          <p class="introduction"><?php echo ($d["desribe"]); ?>&nbsp;</p>
                                        </div>
                                      </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <?php if($da['gbnum'] > 1): ?><a class="moregift jsmoregift"><span>查看更多礼包 ( <?php echo $da['gbnum']-1;?> )<img src="/Public/Media/images/gift_hot_see.png"></span></a><?php endif; ?>
                                  </div>
                                </div>
                              </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                          </ul>
                        
                        </div>
                        <div class="s-slide loaddiv" id="hotgift">
                          <ul class="list second-list">
                          </ul>
                        </div>
                        <div class="s-slide" id="newgift">
                          <ul class="list second-list">
                          </ul>
                        </div>
                        <div class="s-slide">
                          <ul class="list second-list">
                          <?php if(empty($data)): ?><div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>
                          <?php else: ?>
                            <?php if(is_array($alldata)): $i = 0; $__LIST__ = $alldata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$allda): $mod = ($i % 2 );++$i;?><li class="clearfix">
                                <div class="item clearfix">
                                  <div class="game-info">
                                    <a href="<?php echo ($allda["play_detail_url"]); ?>" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($allda["icon"]); ?>" class="icon"></a>
                                    <div class="text"><div class="title"><a href="<?php echo ($allda["play_detail_url"]); ?>"><?php echo ($allda["game_name"]); ?></a></div><p>共<span><?php echo ($allda["gbnum"]); ?></span>个礼包</p></div>
                                  </div>
                                  <div class="gift-list">
                                    <?php if(is_array($allda['gblist'])): $k = 0; $__LIST__ = $allda['gblist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($k % 2 );++$k;?><div <?php if($k == 1): ?>class="subitem active" <?php else: ?>class="subitem"<?php endif; ?>>
                                        <div class="butnbox getgift" data-gift_id="<?php echo ($d['gift_id']); ?>" data-game_id="<?php echo ($d['game_id']); ?>"><span class="table"><span class="table-cell"><a href="javascript:;" <?php if($d["geted"] == 0): ?>class="butn">领取<?php else: ?>class="butn disabled">已领取<?php endif; ?></a></span></span></div>
                                        <div class="text">
                                          <div class="title"><a href="<?php echo ($d["gift_detail_url"]); ?>" class="name">[<?php echo ($d["giftbag_name"]); ?>]</a></div>
                                          <p class="introduction"><?php echo ($d["desribe"]); ?>&nbsp;</p>
                                        </div>
                                      </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <?php if($allda['gbnum'] > 1): ?><a class="moregift jsmoregift"><span>查看更多礼包 ( <?php echo $allda['gbnum']-1;?> )<img src="/Public/Media/images/gift_hot_see.png"></span></a><?php endif; ?>
                                  </div>
                                </div>
                              </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                          </ul>
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
    <div class="popmsg"></div>


              
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
        function nologintc(popmsg,msg){
          popmsg.addClass('pop-notice').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_unlisted.png"><div class="pop-title">您还未登录</div><p class="pop-text">'+msg+'</p><a href="javascript:;" class="pop-btn tologin">去登录</a></div>'); 
          popmsg.find('.pop-close').click(function() {popmsg.close();});
          popmsg.find('.tologin').click(function() {popmsg.close();$('.jslogin').click()});
        }
        function Copy(str,that){
            var save = function(e){
                e.clipboardData.setData('text/plain', str);
                e.preventDefault();
            }
            that.css("color", "white");
            document.addEventListener('copy', save);
            document.execCommand('copy');
            document.removeEventListener('copy',save);
            that.text('复制成功');
        }
        $(function() {
          $user = "<?php echo is_login();?>";
          var popmsg = $('.popmsg').pop();
          $("body").on("click",'.getgift',function(){
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
                        that.find('span a.butn').addClass('disabled').text('已领取');
                        popmsg.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Media/images/pop_receive_successful.png"><div class="pop-title">领取成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.nvalue+'"></span></div><p class="pop-text">可在[我的礼包]中查看</p><a href="javascript:;" class="copy pop-btn">复制激活码</a></div>');
                        popmsg.find('.pop-close').click(function() {popmsg.close();});
                        popmsg.find('.copy').click(function() {
                            // //移动端复制
                            $(".copy").css("color", "#14b4c3");
                            $(".copy").text('复制');
                            Copy($('.code').val(),$('.pop-hint .pop-btn'));
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
            }else{
              nologintc(popmsg,'暂时无法领取礼包~T_T~');
            }
          });
        });
    </script>
    <script>
        var itemIndex = 0;
        var tab1LoadEnd = false;
        var tab2LoadEnd = false;

        $(function() {
          
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
          
          $('.jsmoregift').click(function() {
            var that=$(this);
            that.fadeOut().siblings('div').addClass('active');
          });

        });
        var counter = 0;
        var counter1 = 0;
        // dropload
        var dropload = $('.loaddiv').dropload({
            scrollArea : window,
            threshold:6000,
            loadDownFn : function(me){
                var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
                // 加载菜单一的数据
                if(itemIndex == '1'){
                    var num = 10000;
                    counter++;
                    data = loadajax(me,2,num,counter);
                    var result = '';
                    if(!data){
                      result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      $("#"+loadid+' ul').append(result);
                      tab1LoadEnd = true;
                      // 锁定
                      me.lock();
                      // 无数据
                      me.noData();
                      me.resetload();
                      return;
                    }
                    if(!jQuery.isArray( data )){
                      $nnnum = 0;
                      $.each(data,function(i,e){
                          $nnnum = i;
                          var gblist = e.gblist;
                          result += '<li class="clearfix"><div class="item clearfix"><div class="game-info"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="text"><div class="title"><a href="'+e.play_detail_url+'">'+e.game_name+'</a></div><p>共<span>'+e.gbnum+'</span>个礼包</p></div></div><div class="gift-list">';

                          for(var j = 0; j < gblist.length; j++){
                            result +='<div class="subitem ';
                            if(j==0){
                              result +='active';
                            }
                            result +='"><div class="butnbox getgift" data-gift_id="'+gblist[j].gift_id+'" data-game_id="'+gblist[j].game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                            if(gblist[j].geted != 1){
                              result +='" class="butn">领取';
                            }else{
                              result +='" class="butn disabled">已领取';
                            }
                            result +='</a></span></span></div><div class="text"><div class="title"><a href="'+gblist[j].gift_detail_url+'" class="name">['+gblist[j].giftbag_name+']</a></div><p class="introduction">'+gblist[j].desribe+'&nbsp;</p></div></div>';
                          }
                          gbnum = e.gbnum;
                          cc = gbnum -1;
                          if(gbnum > 1){
                            result +='<a class="moregift jsmoregift"><span>查看更多礼包 ('+cc+')<img src="/Public/Media/images/gift_hot_see.png"></span></a></div></div></li>';
                          }
                      });
                    }else{
                      $nnnum = data.length;
                      for(var i = 0; i < data.length; i++){
                          var gblist = data[i].gblist;
                          result += '<li class="clearfix"><div class="item clearfix"><div class="game-info"><a href="'+data[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data[i].icon+'" class="icon"></a><div class="text"><div class="title"><a href="'+data[i].play_detail_url+'">'+data[i].game_name+'</a></div><p>共<span>'+data[i].gbnum+'</span>个礼包</p></div></div><div class="gift-list">';
                          if(!jQuery.isArray( gblist )){
                            for(var j = 0; j < gblist.length; j++){

                              result +='<div class="subitem ';
                              if(j==0){
                                result +='active';
                              }
                              result +='"><div class="butnbox getgift" data-gift_id="'+gblist[j].gift_id+'" data-game_id="'+gblist[j].game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                              if(gblist[j].geted == 0){
                                result +='" class="butn">领取';
                              }else{
                                result +='" class="butn disabled">已领取';
                              }
                              result +='</a></span></span></div><div class="text"><div class="title"><a href="'+gblist[j].gift_detail_url+'" class="name">['+gblist[j].giftbag_name+']</a></div><p class="introduction">'+gblist[j].desribe+'&nbsp;</p></div></div>';
                            }
                          }else{
                            var j = 0;
                            $.each(gblist,function(i,e){
                              result +='<div class="subitem ';
                              if(j==0){
                                result +='active';
                              }
                              j++;
                              result +='"><div class="butnbox getgift" data-gift_id="'+e.gift_id+'" data-game_id="'+e.game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                              if(e.geted == 1){
                                result +='" class="butn disabled">已领取';
                              }else{
                                result +='" class="butn">领取';
                              }
                              result +='</a></span></span></div><div class="text"><div class="title"><a href="'+e.gift_detail_url+'" class="name">['+e.giftbag_name+']</a></div><p class="introduction">'+e.desribe+'&nbsp;</p></div></div>';
                            });
                          }
                          gbnum = data[i].gbnum;
                          cc = gbnum -1;
                          if(gbnum > 1){
                            result +='<a class="moregift jsmoregift"><span>查看更多礼包 ('+cc+')<img src="/Public/Media/images/gift_hot_see.png"></span></a></div></div></li>';
                          }
                      }
                    }
                    // 为了测试，延迟1秒加载
                     setTimeout(function(){
                        $("#"+loadid+' ul').append(result);
                        tab1LoadEnd = true;
                        if($nnnum<num){
                          // 锁定
                          me.lock();
                          // 无数据
                          me.noData();
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                    $("body").on("click",'.jsmoregift',function(){
                        var that=$(this);
                        that.fadeOut().siblings('div').addClass('active');
                    });
                     },1);
                // 加载菜单二的数据
                }else if(itemIndex == '2'){
                    var num = 10000;
                    counter1++;
                    data = loadajax(me,3,num,counter1);
                    if(!data){
                      var result = '';
                      result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      $("#"+loadid+' ul').append(result);
                      tab2LoadEnd = true;
                      // 锁定
                      me.lock();
                      // 无数据
                      me.noData();
                      me.resetload();
                      return;
                    }
                    var result = '';
                    if(!jQuery.isArray( data )){
                      $nnnum = 0;
                      $.each(data,function(i,e){
                          $nnnum = i;
                          var gblist = e.gblist;
                          result += '<li class="clearfix"><div class="item clearfix"><div class="game-info"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="text"><div class="title"><a href="'+e.play_detail_url+'">'+e.game_name+'</a></div><p>共<span>'+e.gbnum+'</span>个礼包</p></div></div><div class="gift-list">';

                          for(var j = 0; j < gblist.length; j++){
                            result +='<div class="subitem ';
                            if(j==0){
                              result +='active';
                            }
                            result +='"><div class="butnbox getgift" data-gift_id="'+gblist[j].gift_id+'" data-game_id="'+gblist[j].game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                            if(gblist[j].geted != 1){
                              result +='" class="butn">领取';
                            }else{
                              result +='" class="butn disabled">已领取';
                            }
                            result +='</a></span></span></div><div class="text"><div class="title"><a href="'+gblist[j].gift_detail_url+'" class="name">['+gblist[j].giftbag_name+']</a></div><p class="introduction">'+gblist[j].desribe+'&nbsp;</p></div></div>';
                          }
                          gbnum = e.gbnum;
                          cc = gbnum -1;
                          if(gbnum > 1){
                            result +='<a class="moregift jsmoregift"><span>查看更多礼包 ('+cc+')<img src="/Public/Media/images/gift_hot_see.png"></span></a></div></div></li>';
                          }
                      });
                    }else{
                      $nnnum = data.length;
                      for(var i = 0; i < data.length; i++){
                          var gblist = data[i].gblist;
                          result += '<li class="clearfix"><div class="item clearfix"><div class="game-info"><a href="'+data[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data[i].icon+'" class="icon"></a><div class="text"><div class="title"><a href="'+data[i].play_detail_url+'">'+data[i].game_name+'</a></div><p>共<span>'+data[i].gbnum+'</span>个礼包</p></div></div><div class="gift-list">';

                          if(!jQuery.isArray( gblist )){
                            for(var j = 0; j < gblist.length; j++){

                              result +='<div class="subitem ';
                              if(j==0){
                                result +='active';
                              }
                              result +='"><div class="butnbox getgift" data-gift_id="'+gblist[j].gift_id+'" data-game_id="'+gblist[j].game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                              if(gblist[j].geted == 0){
                                result +='" class="butn">领取';
                              }else{
                                result +='" class="butn disabled">已领取';
                              }
                              result +='</a></span></span></div><div class="text"><div class="title"><a href="'+gblist[j].gift_detail_url+'" class="name">['+gblist[j].giftbag_name+']</a></div><p class="introduction">'+gblist[j].desribe+'&nbsp;</p></div></div>';
                            }
                          }else{
                            var j = 0;
                            $.each(gblist,function(i,e){
                              result +='<div class="subitem ';
                              if(j==0){
                                result +='active';
                              }
                              j++;
                              result +='"><div class="butnbox getgift" data-gift_id="'+e.gift_id+'" data-game_id="'+e.game_id+'"><span class="table"><span class="table-cell"><a href="'+'javascript:;';
                              if(e.geted == 1){
                                result +='" class="butn disabled">已领取';
                              }else{
                                result +='" class="butn">领取';
                              }
                              result +='</a></span></span></div><div class="text"><div class="title"><a href="'+e.gift_detail_url+'" class="name">['+e.giftbag_name+']</a></div><p class="introduction">'+e.desribe+'&nbsp;</p></div></div>';
                            });
                          }
                          gbnum = data[i].gbnum;
                          cc = gbnum -1;
                          if(gbnum > 1){
                            result +='<a class="moregift jsmoregift"><span>查看更多礼包 ('+cc+')<img src="/Public/Media/images/gift_hot_see.png"></span></a></div></div></li>';
                          }
                      }
                    }
                    // 为了测试，延迟1秒加载
                     setTimeout(function(){
                        $("#"+loadid+' ul').append(result);
                        tab2LoadEnd = true;
                        if($nnnum<num){
                          // 锁定
                          me.lock();
                          // 无数据
                          me.noData();
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                    $("body").on("click",'.jsmoregift',function(){
                        var that=$(this);
                        that.fadeOut().siblings('div').addClass('active');
                    });
                     },1);
                }

                $('#tab-slide .s-container .s-wrapper').css('height','auto');
            }
        });
				
				
				var fromnumb = location.hash;
				if(fromnumb){
					switch(fromnumb) {
						case '#recom':itemIndex=0;break;
						case '#hot':itemIndex=1;break;
						case '#new':itemIndex=2;break;
						case '#all':itemIndex=3;break;
						default:itemIndex=0;
					}
					$('#tab-menu .s-slide').removeClass('active').eq(itemIndex).addClass('active');
					$('#tab-slide .tabpanel>.s-slide').hide().eq(itemIndex).show();
					dataload(itemIndex);
				}
				

        function loadajax(me,rec_status,num,counter){
          var adddata ='';
          $.ajax({
              type: 'post',
              url: '<?php echo U("Gift/index");?>',
              async:false,
              data:{rec_status:rec_status},
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
          var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
          if(itemIndex == '1'){
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
          }else if(itemIndex == '2'){
              if(!tab2LoadEnd){
                  // 解锁
                  dropload.unlock();
                  dropload.noData(false);
              }else{
                  // 锁定
                  dropload.lock('down');
                  dropload.noData();
              }
          }
          dropload.resetload();
        }
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