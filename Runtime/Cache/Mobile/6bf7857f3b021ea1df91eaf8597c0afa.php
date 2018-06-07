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
    
<link href="/Public/Mobile/css/game.css" rel="stylesheet" >
<link href="/Public/Mobile/css/user.css" rel="stylesheet" >

<div class="galist">
<style>
.dropload-noData{
  display:none;
}.dropload-down {height:auto!important;}
</style>
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    <section class="trunker">
      <section class="inner">
      
        <section class="contain">
          
          
            <div class="tab-scroll">
              <div id="tab-slide">
                <div class="s-container">
                    <div class="s-wrapper tabpanel" >
                        <div class="s-slide loaddiv" id="rjload" style="height: auto;display:block;">
                          <ul class="hot list text-pic-list">
                          </ul>
                        
                        </div>
                        <div class="s-slide loaddiv" id="hotload">
                          <ul class="hot list text-pic-list">
                          </ul>
                        </div>
                        <div class="s-slide" id="fenlei">
                          <ul class=" list text-class-list">
                            <?php if(is_array($gamegroup)): $i = 0; $__LIST__ = $gamegroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gg): $mod = ($i % 2 );++$i;?><li data-type_id='<?php echo ($gg["type_id"]); ?>'>
                                <a  class="item typeitem clearfix">
                                  <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($gg["icon"]); ?>" class="icon"></div>
                                  <div class="butnbox"><span class="table"><span class="butn table-cell"><?php echo ($gg["counts"]); ?><i class="icon icon-arrow-right"></i></span></span></div>
                                  <div class="text">
                                    <div class="title"><span class="name"><?php echo ($gg["type_name"]); ?></span></div>
                                  </div>
                                </a>
                              </li><?php endforeach; endif; else: echo "" ;endif; ?>
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
    <script src="/Public/Mobile/js/common.js"></script>
    <link href="/Public/static/dist/dropload.css" rel="stylesheet" >
    <script src="/Public/static/dist/dropload.js"></script>
    <script>
      
      $(document).ready(function () {
        $('#fenlei ul li:first-child').click();
        $('#fenlei ul li:first-child').find(".item").addClass("active");
      })
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
        
        });
        var counter = 0;
        var counter1 = 0;
        // dropload
        var dropload = $('.loaddiv').dropload({
            scrollArea : window,
            threshold:100000,
            loadDownFn : function(me){
                var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
                // 加载菜单一的数据
                if(itemIndex == '0'){
                    var num = 10;
                    counter++;
                    data = loadajax(me,1,num,counter);
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
                      return;
                    }else{
											var nnnum=0;
											var nndata = data.data;
											if(!jQuery.isArray( nndata )){
												$.each(nndata,function(i,e){
													nnnum=i;
													result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'"  class="name">'+e.game_name+'</a></div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="number"><i class="">'+e.play_num+'</i>人在玩</span></p><p class="slogan">'+e.features+'</p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a  href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="number"><i class="">'+nndata[i].play_num+'</i>人在玩</span></p><p class="slogan">'+nndata[i].features+'</p></div></div></li>';
                        }
											}
                        // 为了测试，延迟1秒加载
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

                    }
                // 加载菜单二的数据
                }else if(itemIndex == '1'){
                    var num = 10;
                    counter1++;
                    data = loadajax(me,2,num,counter1);
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
                      return;
                    }else{
											var nnnum=0;
											var nndata = data.data;
											if(!jQuery.isArray( nndata )){
												$.each(nndata,function(i,e){
													nnnum=i;
													result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a  href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="number"><i class="">'+e.play_num+'</i>人在玩</span></p><p class="slogan">'+e.features+'</p></div></div></li>';
                        
												});
											}else{
												nnnum = nndata.length;
                        for(var i = 0; i < nndata.length; i++){
                            result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a  href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="number"><i class="">'+nndata[i].play_num+'</i>人在玩</span></p><p class="slogan">'+nndata[i].features+'</p></div></div></li>';
                        }
											}
                        // 为了测试，延迟1秒加载
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
                    }
                }

                $('#tab-slide .s-container .s-wrapper').css('height','auto');
            }
        });
				
				if('<?php echo I('game_type_id');?>'>0){
           itemIndex=2;
						$('#tab-menu .s-slide').removeClass('active').eq(itemIndex).addClass('active');
						$('#tab-slide .tabpanel>.s-slide').hide().eq(itemIndex).show();
						dataload(itemIndex);
            $('#fenlei li').each(function(i,n) {
              if($(this).attr('data-type_id') == '<?php echo I('game_type_id');?>'){
                $(this).click();
              }
            });
        }
				
				var fromnumb = location.hash;
			if(fromnumb){
				switch(fromnumb) {
					case '#recom':itemIndex=0;break;
					case '#hot':itemIndex=1;break;
					case '#categroy':itemIndex=2;break;
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
              url: '<?php echo U("Index/more_game");?>',
              async:false,
              data:{rec_status:rec_status,p:counter,limit:num},
              dataType: 'json',
              success: function(data){
                  adddata = data;
              },
              error: function(xhr, type){
              }
          });
          return adddata;
        }

        function dataload(itemIndex){
          var loadid = $('#tab-slide .s-container .s-slide').eq(itemIndex).attr('id');
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
    <script>
    $('.typeitem').click(function(){
      that = $(this);
      parent = that.closest('ul');
      parent.find('li').each(function(index,ele){
        $(this).find('a').removeClass('active');
      });
      if(that.closest('li').attr('data-type_id')!=-1){
        that.addClass('active');
      }
    });
    </script>

</div>
<div class="gacatelist">
    <!-- <header class="header ">
      <section class="wrap">
      <?php if(I('get.from') == 'collec'): ?><a href="javascript:history.go(-1);" class=" hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span><span class="table-cell word"></span></span></a>
      <?php else: ?>
        <a href="javascript:;" class="gacatelistht hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span><span class="table-cell word">分类</span></span></a><?php endif; ?>
        <div class="caption">
          <span class="table">
            <span class="table-cell">
            </span>
          </span>
        </div>
      </section>
    </header>
    <div class="occupy"></div> -->
    <a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    <section class="trunker">
      <section class="inner">
      
        <section class="contain">
            <div class="tab-scroll">
                <ul class="hot list text-pic-list">
                </ul>
            </div>
        </section>
      </section>
    </section>
		<div class="space"></div>
</div>

<script type="text/javascript">
$('#fenlei ul li').click(function(){
    $.ajax({
        type: 'post',
        url: '<?php echo U("Game/gamegrouplist");?>',
        data:{type_id:$(this).attr('data-type_id'),p:1,limit:10000},
        dataType: 'json',
        success: function(data){
          console.log(data);
          if(data.code==-1){
            $('.gacatelist .caption .table-cell').append('未查询到该分类');
            return false;
          }else if(data.code==0){
            alert('缺少type_id');
          }else{
            $('.gacatelist .caption .table-cell').append(data.type_name+'('+data.count+')');
          }

          result = '';
          for(var i = 0; i < data.data.length; i++){
              result += '<li class="clearfix"><div class="item clearfix"><a href="'+data.data[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data[i].icon+'" class="icon"></a><div class="butnbox"><a href="'+data.data[i].play_url+'" class="butn butnlogin">开始</a></div><div class="text"><div class="title"><a href="'+data.data[i].play_detail_url+'" class="name">'+data.data[i].game_name+'</a></div><p class="info"><span class="type">'+data.data[i].game_type_name+'</span><span class="number"><i class="">'+data.data[i].play_num+'</i>人在玩</span></p><p class="slogan">'+data.data[i].features+'</p></div></div></li>';
          }
          $('.gacatelist .contain ul').html("");
          $('.gacatelist .contain ul').append(result);
        },
        error: function(xhr, type){
          alert("出错了！！！1·")
        }
    });
    $('.gacatelist').css('display','block');
    // $('.galist').css('display','none');
});
$('.gacatelistht').click(function(){
    $('.gacatelist').css('display','none');
    $('.galist').css('display','block');
    $('.gacatelist .caption .table-cell').html('');
    $('.gacatelist .contain ul').html('');
});

// 新增签到相关代码：start- By:Patyl
      
$('.jssign').click(function() {
  var that = $(this),span=that.find('span'),i = '+'+that.attr('data-score');
  if (that.hasClass('disabled')) {return false;}
  that.addClass('disabled');
  if ($user>0) {
    $.ajax({
      type:'post',
      url:"<?php echo U('PointShop/user_sign_in');?>",
      success:function(data){
        if(data.status==1){
          span.addClass('hide');
          setTimeout(function(){
            span.empty().removeClass('hide');
            $('<i style="top:100%;position:absolute;left:0;right:0;">'+i+'</i>').prependTo(span).animate({
              top:0,
            },500,function(){
              that.find('.circle').fadeOut(550);
              $(this).delay(250).animate({top:'-100%'},250,function() {
                $(this).remove();
                that.find('.circle').remove();
                
                $('<i style="display:none;">已签到</i>').appendTo(span).fadeIn("slow");
              });
            });
          },250);
        }else{
          layer.msg(data.msg);
        }
      },error:function(){

      }
    })
  } else {
    // 未登录 则弹出登录框
  pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法签到哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologin">去登录</a></div>');
  $('.jscancel').click(function() {pop.close();});
  $('.tologin').click(function(){
    pop.close();
    $('.jslogin').click();
  });
  }
});
// :end
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