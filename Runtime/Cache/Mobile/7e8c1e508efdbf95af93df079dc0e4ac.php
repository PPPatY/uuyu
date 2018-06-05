<?php if (!defined('THINK_PATH')) exit();?><a href="javascript:;" class="suspensionbtn hidden" id="jssuspensionbtn"><img src="<?php echo get_cover(C('SUSPENSION_ICO'),path);?>"></a>
    
<div class="suspensionbox">
  <div class="wrap">
    <div class="sus-menu">
      <div class="sus-menu-box">
        <a href="javascript:;" class="sus-menu-butn jssusclose"><i class="icon icon-close"></i><span>关闭</span></a>
        <a href="javascript:;" class="sus-menu-butn jssusrefresh"><i class="icon icon-refresh"></i><span>刷新游戏</span></a>
        <?php if($detail["collect_status"] == 1): ?><a href="javascript:;" data-collec="<?php echo ($detail["collect_status"]); ?>" class="sus-menu-butn jssuscollection on"><i class="icon icon-collection"></i><span>已收藏</span></a>
        <?php else: ?>
          <a href="javascript:;" data-collec="<?php echo ($detail["collect_status"]); ?>" class="sus-menu-butn jssuscollection"><i class="icon icon-collection"></i><span>收藏</span></a><?php endif; ?>
      </div>
      <div class="sus-menu-quit-box">
      <?php $HTTP_REFERER = $_SERVER['HTTP_REFERER']==''?U('Index/index'):$_SERVER['HTTP_REFERER']; ?>  
      <a href="<?php echo ($HTTP_REFERER); ?>#categroy" class="sus-menu-quit jsquit">退出</a>
      </div>
    </div>
    <div class="user-info clearfix">
      <div class="iconbox"><img src="/Public/Mobile/images/fq/invitate_img_head.png" class="portrait"></div>
      <div class="butnbox">
        <div class="table">
          <span class="table-cell">
          <?php if($issignin == 1): ?><a href="javascript:;" class="sus-butn sus-sign disabled " data-score="<?php echo ($addpoint); ?>"><span class="">已签到</span></a>
          <?php else: ?>
            <a href="javascript:;" class="sus-butn sus-sign jssussign" data-score="<?php echo ($addpoint); ?>"><span class="">签到</span><i class="circle"></i></a><?php endif; ?>
          </span>
          <span class="table-cell"><a href="javascript:;" class="sus-butn sus-recharge">充值</a></span>
        </div>
      </div>
      <div class="textbox">
        <div class="name"><?php echo ($userinfo["nickname"]); ?></div>
        <div class="score">积分：<span><?php echo ($userinfo["shop_point"]); ?></span></div>
      </div>
    </div>
    <div class="user-other">
      <ul class="tab jsstab clearfix">
        <li class="active jstabitem table" data-target="0"><a href="javascript:;" class="tab-butn table-cell"><i class="icon icon-game"></i><span>游戏</span></a></li>
        <li class="table jstabitem" data-target="1"><a href="javascript:;" class="tab-butn table-cell"><i class="icon icon-gift"></i><span>礼包</span></a></li>
        <li class="table jsmall"><a href="javascript:;" class="tab-butn table-cell"><i class="icon icon-mall"></i><span>商城</span></a></li>
        <li class="table jstabitem" data-target="2"><a href="javascript:;" class="tab-butn table-cell"><i class="icon icon-cs"></i><span>客服</span></a></li>
      </ul>
      <div class="pan jsstabpan">
        <div class="panwrap">
        <div class="panitem active">
          <div class="swiper-container" id="gamescroll">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
              
          <div class="game-list-wrap">
            <div class="recently-play">
              <div class="wrap">
                <div class="recently-play-title">
                  <span class="table"><i class="table-cell">最近在玩</i></span>
                </div>
                <div class="play-scroll">
                  <div id="play-slide" class="swiper-container">
                      <div class="swiper-wrapper">
                      <?php if(is_array($userPlay)): $i = 0; $__LIST__ = $userPlay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$up): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                            <div class="item">
                              <a href="<?php echo ($up["play_url"]); ?>" class="icon"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($up["icon"]); ?>" /></a>
                              <a href="<?php echo ($up["play_url"]); ?>" class="name"><?php echo ($up["game_name"]); ?></a>
                            </div>
                          </div><?php endforeach; endif; else: echo "" ;endif; ?>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="game-hot game-list">
              <div class="game-title"><span>热门游戏</span></div>
              <div class="game-content gamegc">
                <ul class="loaddiv ">
                  
                </ul>
                
                <!-- 无数据 -->
                <div class="empty hidden">
                  <img src="/Public/Mobile/images/no_date.png" class="empty-icon">
                  <p class="empty-text">~ 空空如也 ~</p>
                </div>
              </div>
            </div>
          </div>
          
          </div>
            </div>
            <div class="swiper-scrollbar gamescrollbar"></div>
          </div>
          
        </div>
        <div class="panitem">
          <div class="swiper-container" id="giftscroll">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
              
              <div class="gift-list-wrap">                      
                <div class="game-gift gift-list">
                  <div class="gift-title"><span>游戏礼包</span></div>
                  <div class="gift-content">
                    <ul>
                      
                    </ul>
                  </div>
                </div>
                <div class="other-gift gift-list">
                  <div class="gift-title"><span>其他礼包</span></div>
                  <div class="gift-content othergc">
                    <ul>
                      
                    </ul>
                  </div>
                </div>
              </div>
                
              </div>
            </div>
            <div class="swiper-scrollbar giftscrollbar"></div>
          </div>
        </div>
        <div class="panitem">
        <div class="panitemservice">
          <div class="qq"><a href="javascript:;" data-value="<?php echo ($kefuqq); ?>" class="butn jschatqq"><img src="/Public/Mobile/images/invitate_service_qq.png" class="icon-qq"><span>客服：<?php echo ($kefuqq); ?><img src="/Public/Mobile/images/invitate_service_more.png" class="icon-arrow-right"></span></a></div>
          <div class="qrcode">
            <img class="icon-qrcode" src="<?php echo ($qrcode); ?>">
            <p class="text-qrcode">微信扫一扫 关注公众号</p>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
<link href="/Public/static/dist/dropload.css" rel="stylesheet" >
<script src="/Public/static/dist/dropload.js"></script>
<script src="/Public/Mobile/js/clipboard.min.js"></script>
<script>
    var itemIndex = 0;
    var tab1LoadEnd = false;
    var tab2LoadEnd = false;
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
      var pop = $('.pop').pop();
			
			if ((browser.versions.iPhone||browser.versions.iPad) && !browser.versions.qq) {
				if ($('.gift-list').length == 1) {
					$('.gift-content').css({'padding-bottom':'3rem'});
				} else {
					$('.othergc').css({'padding-bottom':'3rem'});
					$('.gamegc').css({'padding-bottom':'.5rem'});
				}
			}
			
      dataload(0);
      /* 悬浮按钮移动 鹿文学 2017-11-07 */
      var s = document.getElementById('jssuspensionbtn');
      var t = browser.versions.mobile?{evt1:'touchstart',evt2:'touchmove',evt3:'touchend'}:{evt1:'mousedown',evt2:'mousemove',evt3:'mouseup'};
      var h,f,l,r,c,d;
      s.addEventListener(t.evt1,function(event){
        f = !0;
        var e = event || window.event;
        var g = e.touches ? e.touches[0]:{clientX:e.clientX,clientY:e.clientY};
        l = g.clientX - s.offsetLeft;
        r = g.clientY - s.offsetTop;
        document.addEventListener(t.evt2,function(a){a.preventDefault();},!1),
        document.addEventListener(t.evt2,function(a){
          var a = a || window.event;
          if (f) {
            h = !1;
            var b = a.touches?a.touches[0]:{clientX:a.clientX,clientY:a.clientY};
            c = b.clientX - l;
            d = b.clientY - r;
            0>c?c=0:c>document.documentElement.clientWidth-s.offsetWidth && (c = document.documentElement.clientWidth-s.offsetWidth);
            0>d?d=0:d>document.documentElement.clientHeight-s.offsetHeight && (d = document.documentElement.clientHeight-s.offsetHeight);
            s.style.left = c + 'px';
            s.style.top = d + 'px';
            $(s).addClass('open');
          }
        },!1)
        
      },!1);
      s.addEventListener(t.evt3,function(event){
        f = !1;
        var e = event || window.event;
        s.style.left = 'auto';
        s.style.right = '0';
        s.style.top = d + 'px';
        $(s).removeClass('open');
        document.addEventListener(t.evt2,function(a){a.preventDefault();},!1);
        document.removeEventListener(t.evt2,function(a){a.preventDefault();},!1);
        setTimeout(function() {h = !0;},15);
        
      },!1);
      
      /* 悬浮出现 */
      $(s).on('click',function() {
        $('.suspensionbox').animate({
          left:0
        },500,function(){});
        
        return false;
      });
      
      
      /* 离开 */
      if (window.history && window.history.pushState) {
        $(window).on('popstate',function() {
          var hashLocation = location.hash;
          var hasSplit = hashLocation.split('#!/');
          var hasName = hasSplit[1];
          var result='';
          $game_id = "<?php echo I('game_id');?>"
          if (hasName != '') {
            pop.css('z-index',2000);
            var hash = window.location.hash;
            if (hash === '') {
              $.ajax({
                type:'post',
                url:"<?php echo U('Game/suspension_leave');?>",
                data:{game_id:$game_id},
                async:false,
                success:function(data){
                  result += '<a href="javascript:;" class="pop-close"></a><div class="pop-content"><div class="pop-title">今日推荐游戏</div><div class="partir-qrcode">';
                  result+='<a href="http://uuyu.com/media.php?s=Game/open_game/game_id/25" target="_self" style="display:inline-block;width:4.6rem;height:4.6rem;"><img src="';
                  // result+=data.data.qrcode;
                  result+='/Public/Mobile/images/Today-recommendation-game.png';
                  result+='"></a><p style="font-size:.5rem;color:#ff7777;">点击立即进入游戏</p></div><div class="partir-recommend"><ul class="pop-clear">';
                  for(var i=0;i<data.data.like.length;i++){
                    result+='<li><a href="'+data.data.like[i].play_detail_url+'"><img src="'+data.data.like[i].icon+'"><p>'+data.data.like[i].game_name+'</p></a></li>';
                  }
                  result+='<li><a href="<?php echo U("Game/index#categroy");?>"><img src="/Public/Mobile/images/pop_leave_logo.png"><p>更多游戏</p></a></li></ul></div></div><div class="butn100 pop-butn-box pop-clear">';
                  if(data.data.collection==1){
                    result+='<a href="javascript:;" class="pop-butn continue" style="width:100%;">离开</a> ';
                  }else if(data.data.collection==-1){
                    result+='<a href="javascript:;" class="pop-butn continue">离开</a> ';
                    result+='<a href="javascript:;" class="pop-butn collection">收藏游戏</a>';
                  }
                  result+='</div>';
									pop.addClass('pop-partir').open('',result);
									$('.pop-close').click(function(){
										pop.close();
										window.history.pushState('forward', null, '#');
									});
									// 收藏游戏
									$('.collection').click(function() {
										pop.removeClass('pop-partir');
										// ajax
										$('.jssuscollection').click();
										window.history.pushState('forward', null, '#');
										return false;
										
									});
									// 离开
									$('.continue').click(function() {
										// location.replace('<?php echo ($prev_url); ?>');
                                        // 修改游戏中点击返回 退出游戏时的重定向到首页。
                                        window.location.href = "http://uuyu.com";
                                    });
                }
              });
            }
          }
          return false;
        });
        
        window.history.pushState('forward',null,'');
      }
      $user = "<?php echo is_login();?>";
      /* 签到 */
      $('.jssussign').click(function() {
        var that = $(this),span=that.find('span'),i = '+'+that.attr('data-score');
        if (that.hasClass('disabled')) {return false;}
        that.addClass('disabled');
        
        span.addClass('hide');
        if($user>0) {
            $.ajax({
              type:'post',
              url:"<?php echo U('PointShop/user_sign_in');?>",
              success:function(data){
                if(data.status==1){
                  setTimeout(function(){
                    span.empty().removeClass('hide');
                    $('<i style="top:100%;position:absolute;left:0;right:0;">'+i+'</i>').prependTo(span).animate({
                      top:0,
                    },500,function(){
                      that.find('.circle').fadeOut(550);
                        $(this).delay(250).animate({top:'-100%'},250,function() {
                        $(this).remove();
                        that.find('.circle').remove();
                        nowpoint = parseInt($('.score span').text());
                        addpoint = parseInt(that.attr('data-score'));
                        $add = nowpoint + addpoint;
                        $('.score span').text($add);
                        $('<i style="display:none;">已签到</i>').appendTo(span).fadeIn("slow");
                      });
                    });
                  },250);
                  
                }else{
                  layer.msg(data.msg);
                  
                }
              },error:function(){
                location.href = location.href;
              }
            })
            
        }else {
          location.href = location.href;
        }

        
      });
      $("body").on("click",'.jsgetgift',function(){
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
                        that.addClass('disabled').text('已领取');
                        pop.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_receive_successful.png"><div class="pop-title">领取成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.nvalue+'"></span></div><p class="pop-text">可在[我的礼包]中查看</p><a href="javascript:;" class="copy pop-btn">复制激活码</a></div>');
                        pop.find('.pop-close').click(function() {pop.close();});
                        pop.find('.copy').click(function() {
                            // //移动端复制
                            $(".copy").css("color", "#FFF");
                            $(".copy").text('复制');
                            Copy($('.code').val(),$('.pop-hint .pop-btn'));
														pop.close(400);
														setTimeout(function(){
															pop.open().addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">已复制</p>',1000,250);
														},440);
                          });
                      }else{
                        // 失败
                        pop.addClass('pop-hint').open('','<a href="javascript:;" class="pop-close"></a><div class="pop-content"><img class="pop-image" src="/Public/Mobile/images/pop_receive_fail.png"><div class="pop-title">领取失败！</div><p class="pop-text">'+data.msg+'</p></div>');
                        pop.find('.jsfresh').click(function(){pop.close();});
                        pop.find('.pop-close').click(function() {pop.close();});
                      }
                  },
                  error: function(xhr, type){
                      alert('服务器错误');
                  }
              });
            }else{
              alert('请先登录');
            }
          });
      
      /* 悬浮关闭 */
      $('.jssusclose').click(function() {
        $('.suspensionbox').animate({
          left:'-100%'
        },500,function(){});
        
        return false;
      });
      $('.sus-recharge').click(function(){
        //询问框
        $mt = "<?php echo get_device_type();?>";
        pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">您确定要前往个人中心充值平台币吗？</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">不要</a> <a href="javascript:;" class="pop-butn pop-comfirm tosub">是的</a></div>');
        $('.jscancel').click(function() {pop.close();});
        game_id="<?php echo base64_encode(I('game_id'));?>";
        url="<?php echo U('Subscriber/user_recharge','',false);?>"+'/game_id/'+game_id;
        $('.tosub').click(function(){
          pop.close();
          try{
            if($mt=='ios'){
              window.webkit.messageHandlers.gameGoPay.postMessage(1);
            }else if($mt!='ios'){
              window.mengchuang.pay();
            }
          }catch(err){
            window.location.href = url;
          }
        });
      });
      $('.jsquit').click(function(){
        $mt = "<?php echo get_device_type();?>";
        try{
          if($mt=='ios'){
            window.webkit.messageHandlers.gameGoBack.postMessage(1);
          }else if($mt!='ios'){
            window.mengchuang.finish();
          }
        }catch(err){
          window.location.href = "<?php echo U('Index/index');?>";
        }
      });
      $('.jsmall').click(function(){
        //询问框
        pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">您确定要前往商城吗？</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">不要</a> <a href="javascript:;" class="pop-butn pop-comfirm tosub">是的</a></div>');
        $('.jscancel').click(function() {pop.close();});
        url="<?php echo U('PointShop/mall','');?>";
        $('.tosub').click(function(){
          try{
            $mt = "<?php echo get_device_type();?>";
            if($mt=='ios'){
              window.webkit.messageHandlers.goMall.postMessage(1);
            }else if($mt!='ios'){
              window.mengchuang.goMall();
            }
          }catch(err){
            pop.close();window.location.href = url
          }
        });
      });
      /* 刷新游戏 */
      $('.jssusrefresh').click(function(){window.location.reload();return false;});
      
      /* 收藏游戏 */
      $('.jssuscollection').click(function(){
        var that = $(this);
        collect_status = that.attr('data-collec');
        game_id = "<?php echo I('game_id');?>";
        // 是否登录
        if ($user) {
          $.ajax({
              type: 'post',
              url: '<?php echo U("Game/collection");?>',
              async:false,
              data:{collect_status:collect_status,game_id:game_id},
              dataType: 'json',
              success: function(data){
                  if(data.code==1){
                    if(data.data==1){
                        pop.addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">已收藏</p>',1000,250);
                        that.addClass('on').find('span').text('已收藏');
                        that.attr('data-collec',1);
                    }else{
                        pop.addClass('pop-message').msg('<img class="pop-image" src="/Public/Mobile/images/pop_completed.png"><p class="pop-text">收藏已取消</p>',1000,250);
                        that.removeClass('on').find('span').text('收藏');
                        that.attr('data-collec',0)
                    }
                  }else if(data.code==-1){
                      location.reload();
                  }
              },
              error: function(xhr, type){
                  
              }
          });
        } else {
          location.reload();
        }
        return false;
      });
      
      

			
      $('.jsstab .jstabitem').click(function() {
        var that = $(this),index=that.attr('data-target');
        
        that.addClass('active').siblings('.jstabitem').removeClass('active');
        //that.closest('.jsstab').siblings('.jsstabpan').find('.panitem').fadeOut(200).delay(200).eq(index).fadeIn(200);
        var ptp = that.closest('.jsstab').siblings('.jsstabpan').find('.panwrap');pt = ptp.find('.panitem');
        
        pt.animate({opacity:0},200,function(){
          ptp.css('marginLeft',(-index*100)+'%');
          pt.eq(index).animate({opacity:1},200);
        });
        if(index==1){
            var itemIndex = 1;
            dataload(1);
        }
        return false;
      });
      
      /* 客服 */
      $('.jschatqq').click(function() {
        var qq = $.trim($(this).attr('data-value'));
        try{
          $mt = "<?php echo get_device_type();?>";
          if($mt=='ios'){
            window.webkit.messageHandlers.chatqq.postMessage(qq);
          }else if($mt!='ios'){
            window.mengchuang.chatqq(qq);
          }
        }catch(err){
          if (browser.versions.mobile) {
            window.location.href="mqqwpa://im/chat?chat_type=wpa&uin="+qq+"&version=1&src_type=web";
          }else{
          window.open("http://wpa.qq.com/msgrd?v=3&uin="+qq+"&site=qq&menu=yes");
        }
        return false;
        }
      });
			
			
      
      
      
			setTimeout(function(){
			if(window.innerWidth>window.innerHeight) {
				setTimeout(function(){
				new Swiper('#play-slide', {slidesPerView: 3});
				},1500);
				var panwidth = $('.jsstabpan').width();
				var panheight = $('.jsstabpan').height();
				
				panwidth = parseInt($('body').css('font-size'))*12.2/($('html').width()*625/1242/1.7777)*100;
				
				$('.jsstabpan .panwrap').css({'width':(panwidth*3)+'px','height':'auto'});
				$('.jsstabpan .panwrap .panitem').css({'width':panwidth+'px','height':panheight+'px'});
			} else {
				new Swiper('#play-slide', {slidesPerView: 3});
				var panwidth = $('.jsstabpan').width();
				var panheight = $('.jsstabpan').height();
				
				$('.jsstabpan .panwrap').css({'width':(panwidth*3)+'px','height':'auto'});
				$('.jsstabpan .panwrap .panitem').css({'width':panwidth+'px','height':panheight+'px'});
			}
			
			},100);
			$(window).resize(function(){

					var panwidth = $('.jsstabpan').width();
					var panheight = $('.jsstabpan').height();
					
					$('.jsstabpan .panwrap').css({'width':(panwidth*3)+'px','height':'auto'});
					$('.jsstabpan .panwrap .panitem').css({'width':panwidth+'px','height':panheight+'px'});			
				
			});
			
      gamescrollfun();

    });
    var counter = 0;
    var counter1 = 0;
    var dropload = $('.game-content').dropload({
          scrollArea : window,
          threshold:100000,
          loadDownFn : function(me){
              if($('.jsstab .jstabitem.active').attr('data-target')==1){
                  itemIndex = 1;
              }
              // 加载菜单一的数据
              if(itemIndex == '0'){
                  var num = 10;
                  counter++;
                  data = loadajax(me,'<?php echo U("Index/more_game",array("game_id"=>I("game_id")));?>',2,num,counter);
                  var result = '';
                  if(data.status==0){
                    if(counter==1){
                      result +='<div class="empty swiper-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                    }
                    $('.game-content ul.loaddiv').append(result);
                    tab1LoadEnd = true;
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                    // gamescrollfun();
                    return false;
                  }else{
										var nnnumber = 0;
										var gamdata = data.data;
										if(!jQuery.isArray( gamdata )){
											$.each(gamdata,function(i,e){
												nnnumber = i;
												result += '<li><div class="item clearfix"><div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></div><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn">开始</a></span></span></div><div class="textbox"><div class="title"><span class="name">'+e.game_name+'</span>';
                          if(e.gift_id){
                            result += '<span class="mark gift-mark">礼包</span>';
                          }
                          result += '</div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="description">'+e.features+'</span></p></div></div></li>';
                      
											});
										}else{
											nnnumber = gamdata.length;
                      for(var i = 0; i < gamdata.length; i++){
                          result += '<li><div class="item clearfix"><div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+gamdata[i].icon+'" class="icon"></div><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+gamdata[i].play_url+'" class="butn">开始</a></span></span></div><div class="textbox"><div class="title"><span class="name">'+gamdata[i].game_name+'</span>';
                          if(gamdata[i].gift_id){
                            result += '<span class="mark gift-mark">礼包</span>';
                          }
                          result += '</div><p class="info"><span class="type">'+gamdata[i].game_type_name+'</span><span class="description">'+gamdata[i].features+'</span></p></div></div></li>';
                      }
										}
                      // 为了测试，延迟1秒加载
                          $('.game-content ul.loaddiv').append(result);
                          if(data.status==1){
                            if(nnnumber<num){
                              // 锁定
                              me.lock();
                              // 无数据
                              me.noData();
                            }
                          }
                          // 每次数据加载完，必须重置
                          me.resetload();
                  }
              // 加载菜单二的数据
              }else if(itemIndex == '1'){
                  var num = 10;
                  counter1++;
                  data = loadajax(me,'<?php echo U("suspension_gift",array("game_id"=>I("game_id")));?>',false,num,counter1);
                  detail = data.data.detail; 
                  other = data.data.other; 
                  var result1 = '';
                  var result2 = '';
                  if(detail.status==0){
                      result1 +='<div class="empty swiper-categroy" style="height:auto;"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      $('.game-gift .gift-content ul').append(result1);
                  }else if(detail.status==1){
										var detaildata = detail.data;
										if(!jQuery.isArray( detaildata )){
											$.each(detaildata,function(i,e){
												all = e.novice_all;
                          wei = e.novice_surplus;
                          baifen = (wei/all*100).toFixed(2);
                          result1 += '<li><div class="item"><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" ';
                          if(e.received!=1){
                            result1 +='class="butn jsgetgift" data-game_id="'+e.game_id+'" data-gift_id="'+e.gift_id+'">领取</a>';
                          }else{
                            result1 +='class="butn jsgetgift disabled" data-game_id="'+e.game_id+'" data-gift_id="'+e.gift_id+'">已领取</a>';
                          }
                          result1 +='</span></span></div><div class="textbox"><div class="title">['+e.game_name+']'+e.giftbag_name+'</div><div class="surplusbox"><span class="surplus"><i style="width:'+baifen+'%"></i></span><span class="number">剩余<i>'+baifen+'%</i></span></div><p class="info">'+e.desribe+'</p></div></div></li>';
                      
											});
										} else {
                      for(var i = 0; i < detaildata.length; i++){
                          all = detaildata[i].novice_all;
                          wei = detaildata[i].novice_surplus;
                          baifen = (wei/all*100).toFixed(2);
                          result1 += '<li><div class="item"><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" ';
                          if(detaildata[i].received!=1){
                            result1 +='class="butn jsgetgift" data-game_id="'+detaildata[i].game_id+'" data-gift_id="'+detaildata[i].gift_id+'">领取</a>';
                          }else{
                            result1 +='class="butn jsgetgift disabled" data-game_id="'+detaildata[i].game_id+'" data-gift_id="'+detaildata[i].gift_id+'">已领取</a>';
                          }
                          result1 +='</span></span></div><div class="textbox"><div class="title">['+detaildata[i].game_name+']'+detaildata[i].giftbag_name+'</div><div class="surplusbox"><span class="surplus"><i style="width:'+baifen+'%"></i></span><span class="number">剩余<i>'+baifen+'%</i></span></div><p class="info">'+detaildata[i].desribe+'</p></div></div></li>';
                      }
										}
                      $('.game-gift .gift-content ul').append(result1);
                  }
                  if(other.status==0){
                      result2 +='<div class="empty swiper-categroy" style="height:auto;"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
                      $('.other-gift .gift-content ul').append(result2);
                  }else if(other.status==1){
                      var otherdata = other.data;
											if(!jQuery.isArray( otherdata )){
                      $.each(otherdata,function(i,e){
                          $.each(e.gblist,function(ii,ee){
                            all = ee.novice_all;
                            wei = ee.novice_surplus;
                            baifen = (wei/all*100).toFixed(2);
                            result2 += '<li><div class="item"><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" ';
                            if(ee.geted!=1){
                              result2 += 'class="butn jsgetgift" data-game_id="'+e.game_id+'" data-gift_id="'+ee.gift_id+'">领取</a>';
                            }else{
                              result2 += 'class="butn jsgetgift disabled" data-game_id="'+e.game_id+'" data-gift_id="'+ee.gift_id+'">已领取</a>';
                            }
                            result2 += '</span></span></div><div class="textbox"><div class="title">['+e.game_name+']'+ee.giftbag_name+'</div><div class="surplusbox"><span class="surplus"><i style="width:'+baifen+'%"></i></span><span class="number">剩余<i>'+baifen+'%</i></span></div><p class="info">'+ee.desribe+'</p></div></div></li>';
                          });
                      });
											}else{
												for(var i=0;i<otherdata.length;i++) {
													var od = otherdata[i].gblist;
													for(var j=0;j<od.length;j++) {
														all = od[j].novice_all;
                            wei = od[j].novice_surplus;
                            baifen = (wei/all*100).toFixed(2);
                            result2 += '<li><div class="item"><div class="butnbox"><span class="table"><span class="table-cell"><a href="javascript:;" ';
                            if(od[j].geted!=1){
                              result2 += 'class="butn jsgetgift" data-game_id="'+otherdata[i].game_id+'" data-gift_id="'+od[j].gift_id+'">领取</a>';
                            }else{
                              result2 += 'class="butn jsgetgift disabled" data-game_id="'+otherdata[i].game_id+'" data-gift_id="'+od[j].gift_id+'">已领取</a>';
                            }
                            result2 += '</span></span></div><div class="textbox"><div class="title">['+otherdata[i].game_name+']'+od[j].giftbag_name+'</div><div class="surplusbox"><span class="surplus"><i style="width:'+baifen+'%"></i></span><span class="number">剩余<i>'+baifen+'%</i></span></div><p class="info">'+od[j].desribe+'</p></div></div></li>';
                          
													}
												}
											}
                      $('.other-gift .gift-content ul').append(result2);
                  }
                  tab2LoadEnd = true;
                  // 锁定
                  me.lock();
                  // 无数据
                  me.noData();
                  me.resetload();
                  giftscrollfun();
                  return false;
              }

              $('.game-content').css('height','auto');
          }
      });
    function loadajax(me,url,rec_status,num,counter){
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
                  me.resetload();
              }
          });
          return adddata;
    }
    function dataload(itemIndex){
      if(itemIndex == '0'){
          // 如果数据没有加载完
          if(!tab1LoadEnd){
              // 解锁
              dropload.unlock('');
              dropload.noData(false);
          }else{
              // 锁定
              dropload.lock('down');
              dropload.noData();
          }
      // 如果选中菜单二
      }else if(itemIndex == '1'){
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
    function giftscrollfun() {
      new Swiper('#giftscroll', {
          scrollbar: '.giftscrollbar',autoHeight:true,
          direction: 'vertical',
          slidesPerView: 'auto',
          mousewheelControl: true,observer:true,observeParents:false,
          freeMode: true,
          scrollbarSnapOnRelease:true,
          roundLengths : true, //防止文字模糊
      });
    }
    
    function gamescrollfun() {
      new Swiper('#gamescroll', {
          scrollbar: '.gamescrollbar',autoHeight:true,
          direction: 'vertical',
          slidesPerView: 'auto',
          mousewheelControl: true,observer:true,observeParents:false,
          freeMode: true,
          scrollbarSnapOnRelease:true,
          roundLengths : true, //防止文字模糊
      });
    }
</script>