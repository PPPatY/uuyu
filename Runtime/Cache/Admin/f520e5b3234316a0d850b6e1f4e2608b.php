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
            
<style>
    .navtab_list{ width:100%; border-bottom:1px solid #ddd; margin: 15px 0px; height: 65px;}
    .navtab_list a{ display:block; width:100px; height:35px; line-height:35px; text-align:center; float:left; margin-left:20px; background:#e0e0e0; border-radius:3px; color:#333; margin-top: 15px;}
    .navtab_list a:first-child{ margin-left:0px;}
    .navtab_list a:hover, .navtab_list a.tabchose{ text-decoration:none; border-bottom:none; background:rgb(0, 149, 248); color:#fff;}
    .navtab_list_cs{ margin-top:20px;margin-left: 20px;}
    .navtab_list_cs img{ margin:0px 8px;}
    .data_list table td {
        min-width: 50px;
    }
   
    
	.top_nav_list {
	    height: 40px;
	    line-height: 50px;
	}
	.table_radio a{color: #404040;}
	.table_radio a:hover{border-bottom: 0;}
</style>
<link rel="stylesheet" href="/Public/Admin/css/open-egret.css">
<div class="main-place main-place-overview">
    <span class="main-placetitle"></span>
    <ul class="main-placeul">
        <li><a href="<?php echo U('Statistics/overview');?>">统计</a></li>
        <li><a href="<?php echo U('Statistics/overview');?>">总览</a></li>
        <li><a href="#">总览</a></li>
    </ul>
    <p class="description_text" style="height: 40px;line-height: 40px;">说明：针对全站数据信息一个总览功能</p>
   
</div>
<div class="cf top_nav_list table_radio" style="margin-left: 40px;"> 
	参与统计设置：
    <a href="<?php echo U('overview',array('isbd'=>1,'type'=>I('type')));?>" class="radio isbdbut radio-primary">
        <input type="radio" class="isbdrt" id="radio2" value="1" name="iiss" <?php if(I('isbd') == 1): ?>checked="checked"<?php endif; ?>>
        <label for="radio2">排除绑币</label>
    </a>
	<a href="<?php echo U('overview',array('type'=>I('type')));?>" class="radio isbdbut radio-primary">
		<input type="radio" class="isbdrt" id="radio1" value="0" name="iiss" <?php if(I('isbd') != 1): ?>checked="checked"<?php endif; ?>>
		<label for="radio1">包含绑币</label>
	</a>
</div>
<div class=" main_content">
    <div class="row main_content_item">
        <div class="main_content_main m-channel-data main_content_platform">
            <div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-blue"><?php echo ($platform_data["all_user"]); ?></strong>
                累计注册玩家
            </div>
            </div>
            <div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-blue"><?php echo ($platform_data["all_pay_user"]); ?></strong>
                累计付费玩家
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-green"><?php echo ($platform_data["all_pay"]); ?></strong>
                累计流水
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-green"><?php echo ($platform_data["all_promote"]); ?></strong>
                开通推广员数
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-green"><?php echo ($platform_data["all_game"]); ?></strong>
                游戏接入数量
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-blue"><?php echo ($platform_data["all_zuser"]); ?></strong>
                自然注册玩家
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-blue"><?php echo ($platform_data["all_zpay"]); ?></strong>
                自然总流水
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-green"><?php echo ($platform_data["all_tuser"]); ?></strong>
                推广员注册玩家
            </div></div><div class="main_content_main_item">
            <div class="col-md-2">
                <strong class="s-c-green"><?php echo ($platform_data["all_tpay"]); ?></strong>
                推广员总流水
            </div></div>
        </div>
    </div>
    <div class="row main_content_item">
        <div class="main_content_main m-channel-data main_content_realtime">
        <div class="main_content_main_item mcmi1">
        <div class="col-md-2">
            <strong class="mcrt1"><?php echo ($realtime_data["today_user"]); ?></strong>
            今日注册
        </div></div><div class="main_content_main_item  mcmi2">
        <div class="col-md-2">
            <strong class="mcrt2"><?php echo ($realtime_data["thisweek_user"]); ?></strong>
            本周注册
        </div></div><div class="main_content_main_item mcmi3">
        <div class="col-md-2">
            <strong class="mcrt3"><?php echo ($realtime_data["thismounth_user"]); ?></strong>
            本月注册
        </div></div><div class="main_content_main_item mcmi4">
        <div class="col-md-2">
            <strong class="mcrt4"><?php echo ($realtime_data["today_active"]); ?></strong>
            今日活跃
        </div></div><div class="main_content_main_item mcmi5">
        <div class="col-md-2">
            <strong class="mcrt5"><?php echo ($realtime_data["thisweek_active"]); ?></strong>
            本周活跃
        </div></div><div class="main_content_main_item mcmi6">
        <div class="col-md-2">
            <strong class="mcrt6"><?php echo ($realtime_data["thismounth_active"]); ?></strong>
            本月活跃
        </div></div><div class="main_content_main_item mcmi7">
        <div class="col-md-2">
            <strong class="mcrt7"><?php echo ($realtime_data["today_pay"]); ?></strong>
            今日充值
        </div></div><div class="main_content_main_item mcmi8">
        <div class="col-md-2">
            <strong class="mcrt8"><?php echo ($realtime_data["thisweek_pay"]); ?></strong>
            本周充值
        </div></div><div class="main_content_main_item mcmi9">
        <div class="col-md-2">
            <strong class="mcrt9"><?php echo ($realtime_data["thismounth_pay"]); ?></strong>
            本月充值
        </div></div></div>
    </div>
    <div class="m-chart main_content_item2" style="height: 600px;">
    <!-- Nav tabs -->
    <ul class="nav nav-pills" role="tablist">
        <li role="presentation" <?php if($_GET['type']== 1 or $_GET['type']== ''): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/overview',array('isbd'=>I('isbd'),'type'=>1));?>" aria-controls="dayRank" role="tab" data-toggle="tab">日排行</a></li>
        <li role="presentation" <?php if($_GET['type']== 2): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/overview',array('isbd'=>I('isbd'),'type'=>2));?>" aria-controls="weekRank" role="tab" data-toggle="tab">周排行</a></li>
        <li role="presentation" <?php if($_GET['type']== 3): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/overview',array('isbd'=>I('isbd'),'type'=>3));?>" aria-controls="monthRank" role="tab" data-toggle="tab">月排行</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="col-sm-4 main_content_time" >
        <table class="table table-bordered table-striped table-hover egretRank mct1"  style="width: 30%; float: left" >
            <caption class="">注册排行</caption>
            <thead>
                <tr>
                    <th>排行</th>
                    <th>游戏名称</th>
                    <th>注册</th>
                    <th>变化</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($zhuce)): $i = 0; $__LIST__ = $zhuce;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$zhuce): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ++$a;?></td>
                        <td><span style="color:#46A3FF"><?php echo ($zhuce["fgame_name"]); ?></span></td>
                        <td><?php echo ($zhuce["cg"]); ?></td>
                        <td><?php if($zhuce["change"] < 0): ?><span style="color: green; font-weight: normal; font-size: 15px;">↑</span><?php echo substr($zhuce['change'],1); elseif($zhuce["change"] == 0): ?>--<?php else: ?><span style="color: red;font-weight: normal; font-size: 15px;">↓</span><?php echo ($zhuce["change"]); endif; ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <table class="table table-bordered table-striped table-hover egretRank mct2"  style="width: 30%; margin: 0px 3%; float: left" >
            <caption class="">活跃排行</caption>
            <thead>
                <tr>
                    <th>排行</th>
                    <th>游戏名称</th>
                    <th>活跃</th>
                    <th>变化</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($active)): $i = 0; $__LIST__ = $active;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$active): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ++$b;?></td>
                        <td><span style="color:#46A3FF"><?php echo ($active["game_name"]); ?></span></td>
                        <td><?php echo ($active["cg"]); ?></td>
                        <td><?php if($active["change"] < 0): ?><span style="color: green; font-weight: normal; font-size: 15px;">↑</span><?php echo substr($active['change'],1); elseif($active["change"] == 0): ?>--<?php else: ?><span style="color: red;font-weight: normal; font-size: 15px;">↓</span><?php echo ($active["change"]); endif; ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <table class="table table-bordered table-striped table-hover egretRank mct3"  style="width: 30%; float: left" >
            <caption class="">充值排行</caption>
            <thead>
                <tr>
                    <th>排行</th>
                    <th>游戏名称</th>
                    <th>充值</th>
                    <th>变化</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($pay)): $i = 0; $__LIST__ = $pay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pay): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ++$c;?></td>
                        <td><span style="color:#46A3FF"><?php echo ($pay["game_name"]); ?></span></td>
                        <td><?php echo ($pay["cg"]); ?></td>
                        <td><?php if($pay["change"] < 0): ?><span style="color: green; font-weight: normal; font-size: 15px;">↑</span><?php echo substr($pay['change'],1); elseif($pay["change"] == 0): ?>--<?php else: ?><span style="color: red;font-weight: normal; font-size: 15px;">↓</span><?php echo ($pay["change"]); endif; ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</div>
    <div class="page">
        <?php echo ((isset($_page) && ($_page !== ""))?($_page):''); ?>
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
    
<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>
<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo U('Statistics/overview');?>');
$("#main").removeClass('main');
$("#main").addClass('openegretmain');
</script>
<!-- ECharts单文件引入 -->
    <script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
    <script type="text/javascript">
        $('.jsnav li a').on('click',function() {
            var that=$(this),url = $.trim(that.attr('href')),li=that.closest('li'),sib = that.closest('.jsnav').siblings('.mchart');
            li.addClass('active').siblings('li').removeClass('active');
            sib.html('');
            var html = '<iframe src="'+url+'" id="iframepage" name="iframepage" frameBorder=0 scrolling=no width="100%"  height="100%" ></iframe>';
            sib.html(html);
            return false;
        });
        $('.isbdbut').click(function(){
            that = $(this);
            url = that.attr('href');
            location.href = url;
        });
    </script>

</body>
</html>