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
    .navtab_list{ width:100%; 
    .navtab_list a:first-child{ margin-left:0px;}
    .navtab_list_cs{ margin-top:20px;margin-left: 20px;}
    .navtab_list_cs img{ margin:0px 8px;}
    .data_list table td {
        min-width: 50px;
    }
    .main_content_dataoverview {padding-top: 0px !important;}
    .f-pl-10 { padding-left: 0px !important;}
    
    /*radio样式*/
	.navtab_list input {
		margin: 0;
		vertical-align: middle;
		padding-top: 0;
		padding-bottom: 0;
	}
	.navtab_list .radio input[type="radio"] {
		opacity: 0;
	}
	.navtab_list .radio label {
		display: inline-block;
		position: relative;
		padding-left: 5px;
	}
	.navtab_list .radio label::before {
		content: "";
		display: inline-block;
		position: absolute;
		width: 8px;
		height: 8px;
		left: 5px;
		top: 3px;
		margin-left: -20px;
		border: 3px solid #CFCFCF;
		border-radius: 50%;
		background-color: #fff;
		-webkit-transition: border 0.15s ease-in-out;
		-o-transition: border 0.15s ease-in-out;
		transition: border 0.15s ease-in-out;
	}
	.navtab_list .radio-primary input[type="radio"]:checked+label::after {
		border: 3px solid #3D94C9;
		-webkit-transform: scale(1, 1);
		-ms-transform: scale(1, 1);
		-o-transform: scale(1, 1);
		transform: scale(1, 1);
	}
	.navtab_list .radio label::after {
		display: inline-block;
		position: absolute;
		content: " ";
		width: 8px;
		height: 8px;
		left: 5px;
		top: 3px;
		margin-left: -20px;
		border-radius: 50%;
		-webkit-transform: scale(0, 0);
		-ms-transform: scale(0, 0);
		-o-transform: scale(0, 0);
		transform: scale(0, 0);
		-webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
		-moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
		-o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
		transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
	}
	.top_nav_list {
	    height: 40px;
	    line-height: 50px;
	}
	.navtab_list a{color: #404040;}
	.navtab_list a:hover{border-bottom: 0;}
</style>
<link rel="stylesheet" href="/Public/Admin/css/open-egret.css">
        <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
        <div class="main-place">
            <span class="main-placetitle"></span>
            <ul class="main-placeul">
                <li><a href="<?php echo U('Statistics/overview');?>">统计</a></li>
                <li><a href="<?php echo U('Statistics/overview');?>">总览</a></li>
                <li><a href="#">数据概况</a></li>
            </ul>
            <p class="description_text" style="height: 40px;line-height: 40px;">说明：快速查看1天，7天，30天，1年的全站注册人数和充值金额（此处充值金额只包含游戏充值，不包含账户未消费的平台币）</p>
        </div>
        <div class="cf top_nav_list navtab_list" style="height:40px;line-height:50px;margin-left:34px;"> 
        	参与统计设置：
			<a href="<?php echo U('data_profile',array('isbd'=>1,'key'=>I('key')));?>" class="isbdbut radio radio-primary">
				<input type="radio" class="isbdrt" id="radio1" value="0" name="iiss" <?php if(I('isbd') == 1): ?>checked="checked"<?php endif; ?>>
				<label for="radio1">排除绑币</label>
			</a>
			<a href="<?php echo U('data_profile',array('key'=>I('key')));?>" class="isbdbut radio radio-primary">
				<input type="radio" class="isbdrt" id="radio2" value="1" name="iiss" <?php if(I('isbd') != 1): ?>checked="checked"<?php endif; ?>>
				<label for="radio2">包含绑币</label>
			</a>
        </div>
        <div class="col-md-10 f-pl-10 main_content_dataoverview">
        <div class="m-box m-chart " style="height: 700px;">
            <ul class="nav nav-pills jsnav" role="tablist">
                <li role="presentation" <?php if($_GET['key']== 1 or $_GET['key']== ''): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/data_profile',array('key'=>1,'isbd'=>I('isbd')));?>" aria-controls="dayRank" role="tab" data-toggle="tab">1天</a></li>
                <li role="presentation" <?php if($_GET['key']== 2): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/data_profile',array('key'=>2,'isbd'=>I('isbd')));?>" aria-controls="weekRank" role="tab" data-toggle="tab">7天</a></li>
                <li role="presentation" <?php if($_GET['key']== 3): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/data_profile',array('key'=>3,'isbd'=>I('isbd')));?>" aria-controls="monthRank" role="tab" data-toggle="tab">30天</a></li>
                <li role="presentation" <?php if($_GET['key']== 4): ?>class="active"<?php endif; ?>><a href="<?php echo U('Statistics/data_profile',array('key'=>4,'isbd'=>I('isbd')));?>" aria-controls="monthRank" role="tab" data-toggle="tab">1年</a></li>
            </ul>
            <div class="mchart" style="height: 650px;">
                <div id="maindata_profile" style="height:600px"></div>
            </div>
        </div>   
        </div>
        <!-- ECharts单文件引入 -->
        <script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
         <script type="text/javascript">
            // 路径配置
            require.config({
                paths: {
                    echarts: 'http://echarts.baidu.com/build/dist'
                }
            });
     
            // 使用
            require(
                [
                    'echarts',
                    'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
                    'echarts/chart/line'
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('maindata_profile')); 
                    
                    option = {
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data:['注册人数（人）','充值金额（元）']
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    xAxis: {
        type: 'category',
        splitLine :{
            show :false,
        },
        <?php if($qingxie > 15): ?>axisLabel: {
            rotate: 30,
            },<?php endif; ?>
        boundaryGap: false,
        data: <?php echo ($xAxis); ?>
    },
   
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name:'注册人数（人）',
            type:'line',
            stack: '总量',
            data:<?php echo ($xzdate); ?>
        },
        {
            name:'充值金额（元）',
            type:'line',
            stack: '总量',
            data:<?php echo ($xsdate); ?>
        },
    ]
};
                    // 为echarts对象加载数据 
                    myChart.setOption(option); 
                }
            );
        </script>
<script>
//导航高亮
function highlight_subnav(url){
    $('.side-sub-menu').find('a[href="'+url+'"]').closest('li').addClass('current');
    /*显示选中的菜单*/
    $('.side-sub-menu').find("a[href='" + url + "']").parent().parent().prev("h3").find("i").removeClass("icon-fold");
    $('.side-sub-menu').find("a[href='" + url + "']").parent().parent().show()
}
highlight_subnav('<?php echo U('Statistics/data_profile');?>');
$('.isbdbut').click(function(){
    that = $(this);
    url = that.attr('href');
    location.href = url;
});
</script>

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
    
</body>
</html>