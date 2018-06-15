<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>-<?php echo C('WEB_SITE_TITLE');?></title>
    <link href="<?php echo get_cover(C('SITE_ICO'),'path');?>" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo">
            <img src="<?php echo get_cover(C('HT_LOGO'),'path');?>" width="100%" height="100%">
        </span>
        <!-- /Logo -->

        <!-- 主导航 -->
			<style>.main-nav li a i {float:none;}</style>
        <ul class="main-nav ieman">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (U($menu["url"])); ?>"><i class="guidicon guidicon-<?php echo ($menu["id"]); ?>"></i><h5><?php echo ($menu["title"]); ?></h5></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="topright">    
            <ul>
                <li><span><img src="/Public/Admin/images/help.png" title="帮助" class="helpimg"></span><a href="http://www.kancloud.cn/xzmch/wd/168273">帮助</a></li>
                <li class="subjectlist jssubject">
                  <a href="javascript:;" class="cbtn jscbtn">主题<i></i></a>
                  <div class="subject-sublist jssubjectlist">
                      <?php $colorstyle = get_color_style_list();?>
                      <?php if(is_array($colorstyle["list"])): $i = 0; $__LIST__ = $colorstyle["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div><a href="javascript:void(0);" target="_self" class="subject-item jssetcolor" data-value="<?php echo ($key); ?>"><img src="/Public/Admin/images/<?php echo ($key); ?>.png" class="subject-pic"><p><span><?php echo ($vo); ?></span></p><i class="subject-icon <?php if(($colorstyle["value"]) == $key): ?>yes<?php endif; ?>"></i></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
                  </div>
                </li>
                 <li class="gwlist">
                    <div class="nav" id="nav">
                    	<p class="set"><a target="_blank" href="media.php?s=index/index">官网<i></i></a></p>
                    	
                    </div>
                </li> 
                <li><a class="tuichujs" href="">退出</a></li>
            </ul>
          
        </div>
    </div>
    
    <!--下拉样式-->
    <script type="text/javascript">
		$(function(){
			$(".nav p").click(function(){
				var ul=$(".new");
				if(ul.css("display")=="none"){
					ul.slideDown();
				}else{
					ul.slideUp();
				}
			});	
		//选择主题
		$('.jscbtn').click(function() {
			$(this).siblings().slideToggle(200);
			return false;
		});

		$('.jssetcolor').click(function() {
			var that = $(this),
				value = that.attr('data-value');
			var par = that.closest('.jssubjectlist');

			if(that.hasClass('disabled')) { return false; }

			$('.jssetcolor').addClass('disabled');

			$.post('/admin.php?s=/Admin/set_color_style.html', { value: value }, function(data) {
				if(data.status == 1) {
					updateAlert(data.info, 'tip_right');
					setTimeout(function() {
						$('#tip').find('.tipclose').click();
						setTimeout(function() { location.reload(); }, 300);
					}, 1500);

				} else {
					updateAlert(data.info, 'tip_error');
					setTimeout(function() {
						$('#tip').find('.tipclose').click();
					}, 1500);
					par.slideToggle(200);
					$('.jssetcolor').removeClass('disabled');
				}
			}, 'json');

			return false;
		});
	
		})
</script>
<div id="tip" class="tip"><a class="tipclose hidden" ></a><div class="tipmain"><div class="tipicon"></div><div class="tipinfo">这是内容</div></div></div>
<script>
    /**顶部警告栏*/
    var content = $('#main');
    var top_alert = $('#tip');
    
    top_alert.find('.tipclose').on('click', function () {
        top_alert.removeClass('block').slideUp(200);
    });
    window.updateAlert = function (text,c) {
        text = text||'default';
        c = c||false;
        if ( text!='default' ) {
            top_alert.find('.tipinfo').text(text);
            if (top_alert.hasClass('block')) {
            } else {
                top_alert.addClass('block').slideDown(200);
            }
        } else {
            if (top_alert.hasClass('block')) {
                top_alert.removeClass('block').slideUp(200);
            }
        }
        if ( c!=false ) {
            top_alert.removeClass('tip_error tip_right').addClass(c);
        }
    };
</script>
<!--下拉样式结束-->
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar" <?php if(CONTROLLER_NAME == Index): ?>style="display:none<?php endif; ?>">
        <div class="user_nav">
           <span><img src="/Public/Admin/images/tx.jpg"></span>
           <p><?php echo session('user_auth.username');?></p>
           <p style="margin-top:0px;"><?php if($res['uid'] == '1'): ?>超级管理员<?php else: echo ($res1['title']); endif; ?></p>
        </div>
        <div class="fgx">功能菜单</div>
        
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3 class=""><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content" style="margin-top: 50px;position:relative;">
            <div id="tip" class="tip"><a class="tipclose hidden" ></a><div class="tipmain"><div class="tipicon"></div><div class="tipinfo">这是内容</div></div></div>
           <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            
            <?php if(CONTROLLER_NAME != 'Index' ): endif; ?>
            
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/admin_table.css" media="all">
<link rel="stylesheet" href="/Public/Admin/css/select2.min.css" type="text/css" />
<script type="text/javascript" src="/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/select2.min.js"></script>
<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
<script src="/Public/static/layer/layer.js" type="text/javascript"></script>
<script src="/Public/static/layer/extend/layer.ext.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/Public/static/webuploader/webuploader.css" media="all">
    <script src="/Public/static/md5.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/Public/static/webuploader/webuploader.js"></script>
    <style type="text/css">
    	#form .txt_title{width: 345px;}
        .tab_table .gameleft{float: left;width: 45%;}
        .tab_table .gameright{float: left;width: 55%;}
        label.checked{float: left;margin-left: 10px;}
        #play_count{float: left;}
        .data_list .check_icon {vertical-align: -4px;}
        #form .txt_area{width: 347px;height: 100px;}
    </style>
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Game/lists');?>">游戏</a></li>
            <li><a href="<?php echo U('Game/lists');?>">游戏管理</a></li>
            <li><a href="#"><?php echo ($meta_title); ?></a></li>
        </ul>
    </div>
    <!-- 标签页导航 -->
<div class="tab-wrap">
    <div class="tab_nav jstabnav">
    <ul>
        <li data-tab="tab1" class="current"><a href="javascript:void(0);">基础信息</a></li>
        <li data-tab="tab3" ><a href="javascript:void(0);">参数配置</a></li>
        <p class="description_text">说明：查看和编辑游戏的基础信息、参数信息等。</p>
    </ul>
    </div>
    <div class="tab-content tab_content">
    <!-- 表单 -->
    <form id="form" action="<?php echo U('add');?>" method="post" class="form-horizontal">
        <!-- 基础 -->
        <div id="tab1" class="tab-pane in tab1 tab_table ">
            <table border="0" cellspacing="0" cellpadding="0" class="data_list">
                <tbody>
                  <tr>
                    <td class="l"><span style="color:red;">* </span>游戏名称：</td>
                    <td class="r">
                        <input type="text" class="txt " name="game_name" value="" placeholder="请输入游戏名称">
                        <input type="hidden"  name="game_appid" value="">
                    </td>
                  </tr>
                  <tr>
                  	<td class="l"><span style="color:red;">* </span>游戏类型：</td>
                    <td class="r">
                        <select id="game_type_id" name="game_type_id" class="select_gallery">                            
                            <option value="">请选择游戏类型</option>
                            <?php $_result=get_game_type_all();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <input type="hidden" id="game_type_name" name="game_type_name" value=""></input>
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">游戏排序：</td>
                    <td class="r">
                        <input type="text" class="txt" name="sort" value="" placeholder="排序必须是数字">
                    </td>
                  </tr>
                  <tr>
                  	<td class='l'>游戏在玩人数：</td>
                    <td class='r'><input type="text" class='txt' name='play_count' value=""></td>
                  </tr>
                  <tr>
                  	<td class="l">游戏评分：</td>
                    <td class="r">
                        <input type="text" class="txt" name="game_score" value="" placeholder="请输入0-10的数字">
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">游戏开发商：</td>
                    <td class="r">
                        <input type="text" class="txt" name="developers" value="">
                    </td>
                  </tr>
                  <tr>
                    <td class="l">一句话简介：</td>
                    <td class="r" colspan="3">
                        <input type="text" class="txt txt_title" name="features" style="" value="">
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">游戏属性：</td>
                    <td class="r table_radio" >
                    	<div class="radio radio-primary">
							<input type="radio" id="radio1" class="inp_radio" value="1" name="game_attribute" checked="checked">
							<label for="radio1">网游</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="radio2" class="inp_radio" value="2" name="game_attribute">
							<label for="radio2">单机</label>
						</div>
						<span class="notice-text" style="margin-left: 250px;">设置游戏属性</span>
                    </td>
                  </tr>
                   <tr>
                    <td class="l">屏幕类型：</td>
                    <td class="r table_radio" >
                        <div class="radio radio-primary">
                            <input type="radio" id="radio10" class="inp_radio" value="1" name="screen_type">
                            <label for="radio10">横屏</label>

                        </div>
                        <div class="radio radio-primary">
                            <input type="radio" id="radio11" class="inp_radio" value="2" name="screen_type" checked="checked">
                            <label for="radio11">竖屏</label>
                        </div>
                        <span class="notice-text" style="margin-left: 250px;">设置游戏屏幕属性</span>
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">显示状态：</td>
                    <td class="r table_radio">
                        <label class="inp_radio">
                        	<div class="radio radio-primary">
								<input type="radio" id="radio3" class="inp_radio" value="0" name="game_status">
								<label for="radio3">关闭</label>
							</div>
							<div class="radio radio-primary">
								<input type="radio" id="radio4" class="inp_radio" value="1" name="game_status" checked="checked">
								<label for="radio4">开启</label>
                        </label>
                        <span class="notice-text" style="margin-left: 250px;">设置该游戏是否在各个站点显示</span>
                    </td>
                  </tr>
                    <tr>
                    <td class="l">测试状态：</td>
                    <td class="r table_radio">
                        <label class="inp_radio">
                            <div class="radio radio-primary">
                                <input type="radio" id="radio13" class="inp_radio" value="0" name="test_status">
                                <label for="radio13">测试中</label>
                            </div>
                            <div class="radio radio-primary">
                                <input type="radio" id="radio14" class="inp_radio" value="1" name="test_status" checked="checked">
                                <label for="radio14">正常</label>
                        </label>
                        <span class="notice-text" style="margin-left: 250px;">设置该游戏是否测试中,测试中不显示在列表中，但可以进入游戏</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">运营状态：</td>
                    <td class="r table_radio">
                    	<?php $_result=get_opentype_all();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="radio radio-primary">
							    <input type="radio" id="radio_<?php echo ($vo["id"]); ?>" class="inp_radio" value="<?php echo ($vo["id"]); ?>" name="category" <?php if(($key) == "0"): ?>checked<?php endif; ?> >
							    <label for="radio_<?php echo ($vo["id"]); ?>"><?php echo ($vo["open_name"]); ?></label>
					        </div><?php endforeach; endif; else: echo "" ;endif; ?>
					    <span class="notice-text" style="margin-left: 113px;">显示当前游戏的开放状态，一般为公测，删档内测，不删档内测等。主要APP上显示</span>
                    </td>
                    </td>
                  </tr>
                 <tr>
                    <td class="l">推荐状态：</td>
                    <td class="r table_radio">
								<input type="checkbox" id="radio6" class="inp_radio" value="0" name="recommend_status[]">
                <label for="radio6">不推荐</label>
                <input type="checkbox" id="radio7" class="inp_radio" value="1" name="recommend_status[]" checked>
                <label for="radio7">推荐</label>
                <input type="checkbox" id="radio8" class="inp_radio" value="2" name="recommend_status[]">
                <label for="radio8">热门</label>
                <input type="checkbox" id="radio9" class="inp_radio" value="3" name="recommend_status[]">
                <label for="radio9">最新</label>
                        <span class="notice-text" style="margin-left: 80px;">设置游戏的推荐类型，更改此游戏在网站所属的推荐板块；</span>
                    </td>
                 </tr>
                 <tr class="picbox">
            			<td class="l">游戏图标：<span class="infonotice2">(尺寸：115*115px)</span> </td>
                    <td class="r">
                        <input type="file" id="upload_picture_icon">
                        <input type="hidden" name="icon" id="cover_id_icon"/>
                        <div class="upload-img-box">
                        <?php if(!empty($data['icon'])): ?><div class="upload-pre-item"><img src="<?php echo (get_cover($data['icon'],'path')); ?>"/></div><?php endif; ?>
                        </div>   
                        <span class="notice-text" style="margin-left: 273px;">上传该游戏的图标</span>
                    </td>
            		</tr>
            		<tr class="picbox">
            			<td class="l">游戏推荐图：<span class="infonotice2">(尺寸：275*160px)</span>   </td>
                    <td class="r">
                    <?php echo hook('UploadImages', array('name'=>'cover','value'=>'','pic_num'=>1));?>
                        <span class="notice-text" style="margin-left: 273px;">当该游戏设置到推荐位时，需要此处上传相关尺寸的游戏封面图</span>
                    </td>
            		</tr>
                  <!--游戏介绍背景图-->
                  <tr class="picbox">
                      <td class="l">游戏介绍背景图：<span class="infonotice2">(尺寸：275*160px)</span>   </td>
                      <td class="r" >
                          <?php echo hook('UploadImages', array('name'=>'introducebg','value'=>'','pic_num'=>1));?>
                          <span class="notice-text" style="margin-left: 273px;">上传游戏介绍背景图，此处截图在游戏详情页头部显示</span>
                      </td>
                  </tr>
                  <!--游戏展示背景图-->
                  <tr class="picbox">
                      <td class="l">游戏展示背景图：<span class="infonotice2">(尺寸：180*210px)</span>   </td>
                      <td class="r" >
                          <?php echo hook('UploadImages', array('name'=>'showbg','value'=>'','pic_num'=>1));?>
                          <span class="notice-text" style="margin-left: 273px;">上传游戏展示背景图，此处截图在首页推荐热门显示</span>
                      </td>
                  </tr>
            		<tr class="picbox">
	            		<td class="l">游戏截图：<span class="infonotice2">(尺寸：210*350px)</span>   </td>
	                    <td class="r" >
	                      	<?php echo hook('UploadImages', array('name'=>'screenshot','value'=>''));?>
	                      	 <span class="notice-text" style="margin-left: 273px;">上传游戏的截图信息，此处截图在游戏详情页显示</span>
	                    </td>
            		</tr>
	                <tr class="picbox">
	                  	<td class="l">游戏闪屏：<span class="infonotice2">(尺寸：640*950px)</span> </td></td>
	                    <td class="r" >
	                    	<?php echo hook('UploadImages', array('name'=>'game_load_page','value'=>'','pic_num'=>1));?>
	                        <span class="notice-text" style="margin-left: 273px;">上传闪屏信息</span>
	                    </td>
	                </tr>
	                <tr>
	                    <td class="l">详细介绍：</td>
	                    <td class="r" colspan='3'>
	                        <textarea name="introduction" class="txt_area"></textarea>
	                    </td>
                	</tr>
                </tbody>
            </table>
        </div>
        <!-- 设置 -->
        <div id="tab3" class="tab-pane  tab3 tab_table">
            <table  border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                    <td class="l">推广充值CPS比例：</td>
                    <td class="r">
                        <input type="text" class="txt" name="ratio" value="" placeholder="输入0-100之间的数字"> %
                        <span class="notice-text">此处比例为推广结算时CPS模式的分成比例</span>
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">推广注册CPA单价：</td>
                    <td class="r">
                        <input type="text" class="txt" name="money" value="" placeholder="请输入不小于的数值"> 元
                        <span class="notice-text">此处数值为推广结算时CPA模式的注册单价</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">会长代充统一折扣比例：</td>
                    <td class="r">
                        <input type="text" class="txt txt_title" name="discount" value="" placeholder="输入1-10之间的数字"> 折
                        <span class="notice-text">设置该游戏的统一折扣，推广员后台会长代充时使用</span>
                    </td></tr>
                    <tr>
                    <td class="l">第三方游戏地址：</td>
                    <td class="r">
                        <input type="text" class="txt txt_title" name="third_party_url" value="" placeholder="输入游戏登录通知地址">
                        <span class="notice-text" style="margin-left: 46px;">此处功能为平台用户游戏登录时，跳转第三方游戏链接</span>
                    </td>
                  </tr>
                   <tr>
                    <td class="l">安卓微端地址：</td>
                    <td class="r">
                        <input type="text" class="txt txt_title" name="and_dow_address" value="" placeholder="输入安卓微端地址">
                        <span class="notice-text" style="margin-left: 46px;">此处功能为游戏安卓微端的下载地址</span>
                    </td>
                  </tr>
                   <tr>
                    <td class="l">苹果微端地址：</td>
                    <td class="r">
                        <input type="text" class="txt txt_title" name="ios_dow_address" value="" placeholder="输入苹果微端地址">
                        <span class="notice-text" style="margin-left: 46px;">此处功能为游戏苹果微端的下载地址</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">游戏登录通知地址：</td>
                    <td class="r">
                        <input type="text" class="txt txt_title" name="login_notify_url" value="" placeholder="输入游戏登录通知地址">
                        <span class="notice-text" style="margin-left: 46px;">此处功能为平台用户游戏登录时，通知CP方所用，该地址由CP方提供</span>
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">游戏支付通知地址：</td>
                    <td class="r">
                        <input type="text" class="txt" name="pay_notify_url" value="" placeholder="支付时通知CP方所用的地址">
                        <span class="notice-text" style="margin-left: 46px;">此处功能为平台用户游戏充值时，通知CP方所用，该地址由CP方提供</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">游戏key：</td>
                    <td class="r">
                        <input type="text" class="txt" name="game_key" value="" placeholder="输入游戏key">
                        <span class="notice-text" style="margin-left: 46px;">游戏支付通知时的加密key，可自由设置，长度不得超过32位字符串，设置完必须提供给CP方进行同步</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">同步平台账号：</td>
                    <td class="r">
                        <input type="text" class="txt" name="agent_id" value="<?php echo ($data['agent_id']); ?>" placeholder="同步平台账号">
                        <span class="notice-text">目前白鹭使用，用来填写平台账号</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">游戏支付Appid：</td>
                    <td class="r">
                        <input type="text" class="txt" name="game_pay_appid" value="" disabled="disabled" style="cursor: not-allowed;" placeholder="自动生成（游戏支付时用的的AppId）">
                        <span class="notice-text" style="margin-left: 46px;">使用微信支付时需要的appid，需要到微信开放平台申请创建，包括官方微信支付和威富通里的微信支付（需要删除，在支付配置里设置即可）</span>
                    </td>
                  </tr>
                </tbody>
            </table>
        </div>
        <div class="form-item cf">
            <button class="submit_btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class=" back_btn" onclick="javascript:location.href='<?php echo U('lists');?>';return false;">返 回</button>
        </div>
    </form>
    </div>
</div>

        </div> 
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl" style="margin-left: 15px;">感谢使用<a href="https://www.vlcms.com/" target="_blank">Vlcms溪谷软件</a>游戏运营平台 <?php echo C(VERSION_NUMBER);?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/admin.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/Public/static/think.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 160);
            }).resize();
            $('.tuichujs').click(function(){
                $.ajax({
                    type: 'POST',
                    async: false,
                    dataType: 'json',
                    url: "<?php echo U('Public/logout');?>",
                    success: function(data) {
                        updateAlert('退出成功','tip_right');
                        setTimeout(function(){
                            $('#tip').find('.tipclose').click();
                        },1500);
                        location.reload();
                    },
                    error:function(){
                        updateAlert('服务器错误','tip_error');
                        setTimeout(function(){
                            $('#tip').find('.tipclose').click();
                        },1500);
                    }
                });
            });
            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            //$subnav.find('h3').addClass('no');
            $subnav.find("a[href='" + url + "']").parent().addClass("current").closest('ul').show().prev('h3').addClass('no');

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(event){
                var e = event || window.event;
                var target = $(e.target);
                var $this = $(this);                
                if ($this.index() == target.index())
                	$this.siblings().removeClass('no'),
                	$this.toggleClass("no"),
                  $this.find(".icon").toggleClass("icon-fold");
                else
                  $this.toggleClass('no').find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });


            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo U('Game/lists');?>');
$('#submit').click(function(){
    $('#form').submit();
});
$(".select_gallery").select2();
// $(".js-device").click();
$(function(){
    $("input[name='game_appid']").val("<?php echo generate_game_appid();?>");
    $("#game_type_name").val($("#game_type_id option:selected").text());
    
    $('.date').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    $('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    showTab();

});
$(document).ready(function(){
    if($(".js-device").val()==1){
        $('.android').show();
        $('.ios').hide();
    }else if($(this).val()==2){
        $('.ios').show();
        $('.android').hide();
    }
    $(".js-device").click(function(){
        if($(this).val()==1){
            $('.android').show();
            $('.ios').hide();
        }else if($(this).val()==2){
            $('.ios').show();
            $('.android').hide();
        }
    });
});
/*获取游戏类型名称*/
$("#game_type_id").change(function() {
    $("#game_type_name").val($("#game_type_id option:selected").text());
});

//上传游戏图标
/* 初始化上传插件 */
$("#upload_picture_icon").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传图标",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : upload_picture_icon<?php echo ($field["name"]); ?>,
    'onFallback' : function() {
        alert('未检测到兼容版本的Flash.');
    }
});
function upload_picture_icon<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#cover_id_icon").val(data.id);
        src = data.url || '' + data.path;
        $("#cover_id_icon").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}

//上传游戏封面
/* 初始化上传插件 */
$("#game_load_page").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传封面",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : game_load_page<?php echo ($field["name"]); ?>,
    'onFallback' : function() {
        alert('未检测到兼容版本的Flash.');
    }
});
function game_load_page<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#game_load_page1").val(data.id);
        src = data.url || '' + data.path;
        $("#game_load_page1").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}

//上传游戏封面
/* 初始化上传插件 */
$("#upload_picture_cover").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传封面",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : upload_picture_cover<?php echo ($field["name"]); ?>,
    'onFallback' : function() {
        alert('未检测到兼容版本的Flash.');
    }
});
function upload_picture_cover<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#cover_id_cover").val(data.id);
        src = data.url || '' + data.path;
        $("#cover_id_cover").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}

/*上传游戏介绍背景*/
$("#upload_picture_introducebg").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传封面",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : upload_picture_introducebg<?php echo ($field["name"]); ?>,
'onFallback' : function() {
    alert('未检测到兼容版本的Flash.');
}
});
function upload_picture_introducebg<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#introducebg_id_cover").val(data.id);
        src = data.url || '' + data.path;
        $("#introducebg_id_cover").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#introducebg_id_cover").val(data.id);
        src = data.url || '' + data.path;
        $("#introducebg_id_cover").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}
/*上传游戏展示背景*/
$("#upload_picture_showbg").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传封面",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : upload_picture_showbg<?php echo ($field["name"]); ?>,
'onFallback' : function() {
    alert('未检测到兼容版本的Flash.');
}
});
function upload_picture_showbg<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#showbg_id_cover").val(data.id);
        src = data.url || '' + data.path;
        $("#showbg_id_cover").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}




//上传游戏详情背景
/* 初始化上传插件 */
$("#upload_picture_detail").uploadify({
    "height"          : 30,
    "swf"             : "/Public/static/uploadify/uploadify.swf",
    "fileObjName"     : "download",
    "buttonText"      : "上传封面",
    "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
    "width"           : 120,
    'removeTimeout'   : 1,
    'fileTypeExts'    : '*.jpg; *.png; *.gif;',
    "onUploadSuccess" : upload_picture_detail<?php echo ($field["name"]); ?>,
    'onFallback' : function() {
        alert('未检测到兼容版本的Flash.');
    }
});
function upload_picture_detail<?php echo ($field["name"]); ?>(file, data){
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
        $("#detail_id_icon").val(data.id);
        src = data.url || '' + data.path;
        $("#detail_id_icon").parent().find('.upload-img-box').html(
            '<div class="upload-pre-item"><img src="' + src + '"/></div>'
        );
    } else {
        updateAlert(data.info);
        setTimeout(function(){
            $('#top-alert').find('button').click();
            $(that).removeClass('disabled').prop('disabled',false);
        },1500);
    }
}

var userInfo = {userId:"kazaff", md5:""};   //用户会话信息
var chunkSize = 5000 * 1024;        //分块大小
var uniqueFileName = null;          //文件唯一标识符
var md5Mark = null;
var backEndUrl = "<?php echo U('File/shard_upload',array('type'=>1,'session_id'=>session_id()));?>";
WebUploader.Uploader.register({
    "before-send-file" : "beforeSendFile",
    "before-send"      : "beforeSend",
    "after-send-file"  : "afterSendFile"
}, {
    beforeSendFile: function(file){
        //秒传验证
        var task = new $.Deferred();
        var start = new Date().getTime();
        (new WebUploader.Uploader()).md5File(file, 0, 10*1024*1024).progress(function(percentage){
            //console.log(percentage);
        }).then(function(val){
            //console.log("总耗时: "+((new Date().getTime()) - start)/1000);
            md5Mark = val;
            userInfo.md5 = val;
            $.ajax({
                type: "POST"
                , url: backEndUrl
                , data: {status: "md5Check", md5: val}
                , cache: false
                , timeout: 1000 //todo 超时的话，只能认为该文件不曾上传过
                , dataType: "json"
            }).then(function(data, textStatus, jqXHR){
                alert(data.chunk);
                //console.log(data);
                if(data.ifExist){   //若存在，这返回失败给WebUploader，表明该文件不需要上传
                    task.reject();
                    uploader.skipFile(file);
                    file.path = data.path;
                }else{
                    task.resolve();
                    //拿到上传文件的唯一名称，用于断点续传
                    uniqueFileName = md5(''+userInfo.userId+file.name+file.type+file.lastModifiedDate+file.size);
                }
            }, function(jqXHR, textStatus, errorThrown){    //任何形式的验证失败，都触发重新上传
                task.resolve();
                //拿到上传文件的唯一名称，用于断点续传
                uniqueFileName = md5(''+userInfo.userId+file.name+file.type+file.lastModifiedDate+file.size);
            });
        });
        return $.when(task);
    }
    , beforeSend: function(block){
        //分片验证是否已传过，用于断点续传
        var task = new $.Deferred();
        $.ajax({
            type: "POST"
            , url: backEndUrl
            , data: {
                status: "chunkCheck"
                , name: uniqueFileName
                , chunkIndex: block.chunk
                , size: block.end - block.start
            }
            , cache: false
            , timeout: 1000 //todo 超时的话，只能认为该分片未上传过
            , dataType: "json"
        }).then(function(data, textStatus, jqXHR){
            if(data.ifExist){   //若存在，返回失败给WebUploader，表明该分块不需要上传
                task.reject();
            }else{
                task.resolve();
            }
        }, function(jqXHR, textStatus, errorThrown){    //任何形式的验证失败，都触发重新上传
            task.resolve();
        });

        return $.when(task);
    }
    , afterSendFile: function(file){
        var chunksTotal = 0;
        if((chunksTotal = Math.ceil(file.size/chunkSize)) > 1){
            //合并请求
            var task = new $.Deferred();
            $.ajax({
                type: "POST"
                , url: backEndUrl
                , data: {
                    status: "chunksMerge"
                    , name: uniqueFileName
                    , chunks: chunksTotal
                    , ext: file.ext
                    , md5: md5Mark
                }
                , cache: false
                , dataType: "json"
            }).then(function(data, textStatus, jqXHR){
                //todo 检查响应是否正常
                task.resolve();
                file.path = data.path;
                $("#file_name").val(data.name);
                $("#file_url").val(data.path);
                $("#file_size").val(file.size);
            }, function(jqXHR, textStatus, errorThrown){
                task.reject();
            });
            return $.when(task);
        }else{
            //UploadComlate(file);
        }
    }
});
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf: '/Public/static/webuploader/Uploader.swf',
    // 文件接收服务端。
    server: backEndUrl,
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: {id:'#picker'},
    //dnd: "#theList",
    paste: document.body,
    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
    resize: false,
    disableGlobalDnd: true,
    fileNumLimit:1,
    threads:3,
    compress: false,
    prepareNextFile: true,
    formData: function(){return $.extend(true, {}, userInfo);},
    duplicate:true,
    chunked:true,
    chunkSize: 5*1000*1024,
    duplicate: true
});
// 当有文件被添加进队列的时候
uploader.on( 'fileQueued', function( file ) {
    $("#thelist").append( '<div id="' + file.id + '" class="item">' +
        '<h4 class="info">' + file.name + '</h4>' +
        '<p class="state">等待上传...</p>' +
        '</div>' );
});

// 文件上传过程中创建进度条实时显示。
uploader.on( 'uploadProgress', function( file, percentage ) {
    var $li = $( '#'+file.id ),
        $percent = $li.find('.progress .progress-bar');
    // 避免重复创建
    if ( !$percent.length ) {
        $percent = $('<div class="progress progress-striped active">' +
            '<div class="progress-bar" role="progressbar" style="width: 0%">' +
            '</div>' +
            '</div>').appendTo( $li ).find('.progress-bar');
    }
    $li.find('p.state').text('上传中');
    $percent.css( 'width', percentage * 100 + '%' );
    $percent.text( (percentage * 100).toFixed(0) + '%' );
});

uploader.on( 'uploadSuccess', function( file , response) {
    $( '#'+file.id ).find('p.state').text('已上传');
    if(!response.chunk){
        var url = response.path + "/" +response.name;
        $("#file_url").val(url);
    }
    //alert($("#file_name").val()+";"+$("#file_url").val()+";"+$("#file_size").val())
});

uploader.on( 'uploadError', function( file ) {
    $( '#'+file.id ).find('p.state').text('上传出错');
});

uploader.on( 'uploadComplete', function(file) {
    $( '#'+file.id ).find('.progress').fadeOut();
});
</script>

</body>
</html>