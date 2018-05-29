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
              
<div class="galist">
<!-- <header class="header ">
  <section class="wrap">
    <div class="caption">
      <span class="table">
        <span class="table-cell">
          <div id="tab-menu">
            <div class="s-container">
                <div class="s-wrapper tabmenu">
                    <div class="s-slide s-visible active"><a href="#recom" class="table"><span class="table-cell">推荐</span></a></div>
                    <div class="s-slide s-visible"><a href="#hot" class="table"><span class="table-cell">热门</span></a></div>
                    <div class="s-slide s-visible"><a href="#categroy" class="table"><span class="table-cell">分类</span></a></div>
                </div>
            </div>
          </div>
        </span>
      </span>
    </div>
    <a href="<?php echo U('Search/index');?>" class="hbtn search"><span class="table"><span class="table-cell"><img src="/Public/Media/images/nav_btn_search.png"></span></span></a>
  </section>
</header> -->
</div>
<div class="gacatelist">
<!-- <header class="header ">
      <section class="wrap">
      <?php if(I('get.from') == 'collec'): ?><a href="javascript:history.go(-1);" class="gacatelistht hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span><span class="table-cell word"></span></span></a>
      <?php else: ?>
        <a href="javascript:;" class="gacatelistht hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span><span class="table-cell word">分类</span></span></a><?php endif; ?>
        <div class="caption">
          <span class="table">
            <span class="table-cell">
            </span>
          </span>
        </div>
      </section>
    </header> -->
</div>

              
              
<div class="mainer">
<div class="galist">
<style>
.dropload-down {height:auto;}
.dropload-noData{
  display:none;
}
</style>

<!-- <div class="occupy"></div> -->
<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
    <section class="trunker">
      <section class="inner">
      
        <section class="contain">
          
          
            <div class="tab-scroll">
              <div id="tab-slide">
                <div class="s-container">
                    <div class="s-wrapper tabpanel" >
                        <div class="s-slide loaddiv" style="display:block;" id="rjload">
                          <ul class="hot list text-pic-list">
                          </ul>
                        
                        </div>
                        <div class="s-slide loaddiv" id="hotload">
                          <ul class="hot list text-pic-list">
                          </ul>
                        </div>
                        <div class="s-slide" id="fenlei">
                          <ul class=" list text-class-list">
                            <!-- <li data-type_id='-1'>
                              <a href="javascript:;" class="typeitem item clearfix">
                                <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="/Public/Media/images/game_classify_all.png" class="icon"></div>
                                <div class="butnbox"><span class="table"><span class="butn table-cell"><?php echo ($allgame); ?><i class="icon icon-arrow-right"></i></span></span></div>
                                <div class="text">
                                  <div class="title"><span class="name">所有类别</span></div>
                                </div>
                              </a>
                            </li> -->
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
</div>
<div class="gacatelist">  
    <!-- <div class="occupy"></div> -->
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
</div>
</div>


              
<div class="galist">
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
                      result +='<div class="empty s-categroy"><img src="/Public/Media/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div>';
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
                          nnnum = i;
													result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="number"><i class="">'+e.play_num+'</i>人在玩</span></p><p class="slogan">'+e.features+'</p></div></div></li>';
											});
										}else {
											nnnum = nndata.length;
                      for(var i = 0; i < nndata.length; i++){
                          result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a  href="'+nndata[i].play_detail_url+'"   class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="number"><i class="">'+nndata[i].play_num+'</i>人在玩</span></p><p class="slogan">'+nndata[i].features+'</p></div></div></li>';
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
                  var num = 10;
                  counter1++;
                  data = loadajax(me,2,num,counter1);
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
                    return;
                  }else{
										var nnnum=0;
										var nndata = data.data;
										if(!jQuery.isArray( nndata )){
											$.each(nndata,function(i,e){
                          nnnum = i;
													  result += '<li class="clearfix"><div class="item clearfix"><a href="'+e.play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+e.icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+e.play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+e.play_detail_url+'" class="name">'+e.game_name+'</a></div><p class="info"><span class="type">'+e.game_type_name+'</span><span class="number"><i class="">'+e.play_num+'</i>人在玩</span></p><p class="slogan">'+e.features+'</p></div></div></li>';
                      
											});
										}else{
											nnnum = nndata.length;
                      for(var i = 0; i < nndata.length; i++){
                          result += '<li class="clearfix"><div class="item clearfix"><a href="'+nndata[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+nndata[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+nndata[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+nndata[i].play_detail_url+'" class="name">'+nndata[i].game_name+'</a></div><p class="info"><span class="type">'+nndata[i].game_type_name+'</span><span class="number"><i class="">'+nndata[i].play_num+'</i>人在玩</span></p><p class="slogan">'+nndata[i].features+'</p></div></div></li>';
                      }
										}
                      // 为了测试，延迟1秒加载
                       setTimeout(function(){
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
                      },1);
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
              result += '<li class="clearfix"><div class="item clearfix"><a href="'+data.data[i].play_detail_url+'" class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="'+data.data[i].icon+'" class="icon"></a><div class="butnbox"><span class="table"><span class="table-cell"><a href="'+data.data[i].play_url+'" class="butn butnlogin">开始</a></span></span></div><div class="text"><div class="title"><a href="'+data.data[i].play_detail_url+'" class="name">'+data.data[i].game_name+'</a></div><p class="info"><span class="type">'+data.data[i].game_type_name+'</span><span class="number"><i class="">'+data.data[i].play_num+'</i>人在玩</span></p><p class="slogan">'+data.data[i].features+'</p></div></div></li>';
          }
          $('.gacatelist .contain ul').html("");
          $('.gacatelist .contain ul').append(result);
        },
        error: function(xhr, type){
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