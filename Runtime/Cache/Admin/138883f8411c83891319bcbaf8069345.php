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
            
	<style type="text/css">
		.form_info li>label{width: 130px;}
	</style>
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Model/index');?>">系统</a></li>
            <li><a href="<?php echo U('Route/lists');?>">扩展工具</a></li>
            <li><a href="#"><?php echo ($meta_title); ?></a></li>
        </ul>
    </div>
    <div class="tab-wrap">
        <div class="tab_nav jstabnav">
        <ul>
            <li data-tab="tab1" class="current firsttab"><a href="javascript:void(0);" >基本设置</a></li>
            <li data-tab="tab2"><a href="javascript:void(0);" >消息设置</a></li>
            <li data-tab="tab3"><a href="javascript:void(0);" >定义菜单</a></li>
            <p class="description_text">说明：用于配置相关微信公众号参数用来后台绑定管理员账号绑定微信功能</p>
        </ul></div>
        <div class="tab-content">
        <form action="<?php echo U('saveTool');?>" method="post" class="form-horizontal qq_login form_info_ml">
        <div id="tab1" class="tab-pane in tab1">
            <ul class="form_info ">
                <li><label>接口URL：</label><input type="text" class="text input-large" value="<?php echo ($wechat_url); ?>"><i class="form_hint">请将此地址复制到微信公众平台接口URL项</i></li>

                <li><label>微信AppId：</label><input type="text" class="text input-large" name="config[appid]" value="<?php echo ($wechat['appid']); ?>"><i class="form_hint">请与微信公众平台开发者凭据AppId保持一致</i></li>

                <li><label>微信AppSecret：</label><input type="text" class="text input-large" name="config[appsecret]" value="<?php echo ($wechat['appsecret']); ?>"><i class="form_hint">请与微信公众平台开发者凭据AppSecret保持一致</i></li>

                <li><label>微信Token：</label><input type="text" class="text input-large" name="config[token]" value="<?php echo ($wechat['token']); ?>"><i class="form_hint">请与微信公众平台Token保持一致</i></li>

                <li><label>消息加密KEY：</label><input type="text" class="text input-large" name="config[key]" value="<?php echo ($wechat['key']); ?>"><i class="form_hint">请与微信公众平台EncodingAESKey保持一致(消息加解密密钥)</i></li>

                <li><label>启用状态：</label><span class="form_radio table_radio">
                	<div class="radio radio-primary">
						<input type="radio" id="radio1" value="0" name="status" <?php if(($wechat_data['status']) == "0"): ?>checked="checked"<?php endif; ?>>
						<label for="radio1">禁用</label>
					</div>
					<div class="radio radio-primary">
						<input type="radio" id="radio2" value="1" name="status" <?php if(($wechat_data['status']) == "1"): ?>checked="checked"<?php endif; ?>>
						<label for="radio2">启用</label>
					</div>
                    <!--<label><input type="radio" class="inp_radio" value="0" name="status" <?php if(($wechat_data['status']) == "0"): ?>checked="checked"<?php endif; ?>>禁用</label>
                    <label><input type="radio" class="inp_radio" value="1" name="status" <?php if(($wechat_data['status']) == "1"): ?>checked="checked"<?php endif; ?>> 启用</label>-->
                </span><i class="form_hint">微信公众号用状态</i></li>
            </ul>
        </div>
        <div id="tab2" class="tab-pane in tab2">
            <ul class="form_info ">
                <li><label>公众号名称：</label><input type="text" class="text input-large" name="config[wechatname]" value="<?php echo ($wechat['wechatname']); ?>"></li>

                <li><label>首次关注回复消息：</label><textarea style="width: 333px;height: 50px;" placeholder="目前只支持文本类型回复" class="textarea input-large" name="config[message][first_msg]"><?php echo ($wechat['message']['first_msg']); ?></textarea></li>

                <li><label>默认关注回复消息：</label><textarea style="width: 333px;height: 50px;" placeholder="目前只支持文本类型回复" class="textarea input-large" name="config[message][default_msg]"><?php echo ($wechat['message']['default_msg']); ?></textarea></li>

            </ul>
        </div>
        <div id="tab3" class="tab-pane in tab3">
            <?php if(empty($nav_data)): ?><div class="form-item channel pLi" >
                <!-- <label class="item-label">接口URL<span class="check-tips">（请将此地址复制到微信公众平台接口URL项）</span> </label> -->
                <div class="controls">
                    <span class="">一级导航：</span><input type="text" class="text" name="template[nav][1][title][]"  value="">
                    &nbsp;
                    <span class="">URL链接： </span><input type="text input-large" class="text" name="template[nav][1][url][]" value="">
                    <div class='pull-left i-list'>
                        <a href="javascript:" title="添加一级导航" class="add-one">  <i class="icon icon-plus"></i></a>
                        <a href="javascript:" title="移除一级导航" class="remove-li"><i class="icon icon-remove"></i></a>
                        <a href="javascript:" title="添加二级导航" class="add-child"><i class="icon icon-sitemap"></i></a>
                    </div>
                </div>
            </div><?php endif; ?>
            <?php if(is_array($nav_data)): foreach($nav_data as $k=>$nav1): ?><div class="form-item channel pLi" >
                <div class="controls">
                    <span class="">一级导航：</span><input type="text" class="text" name="template[nav][1][title][]"  value="<?php echo ($nav1['title']); ?>">
                    &nbsp;
                    <span class="">URL链接： </span><input type="text input-large" class="text" name="template[nav][1][url][]" value="<?php echo ($nav1['url']); ?>">
                    <div class='pull-left i-list'>
                        <a href="javascript:" title="添加一级导航" class="add-one">  <i class="icon icon-plus"></i></a>
                        <a href="javascript:" title="移除一级导航" class="remove-li"><i class="icon icon-remove"></i></a>
                        <a href="javascript:" title="添加二级导航" class="add-child"><i class="icon icon-sitemap"></i></a>
                    </div>
                </div>
                <?php if(is_array($nav1[child])): foreach($nav1[child] as $key=>$nav2): ?><div class="form-item channel cLi" >
                    <input name="template[nav][2][pid][]" class="pid" style="display: none">
                    <div class="controls">
                        <span class="">二级级导航：</span><input type="text" class="text" name="template[nav][2][title][]" value="<?php echo ($nav2['title']); ?>">
                        &nbsp;
                        <span class="">导航标示：</span><input type="text" class="text" name="template[nav][2][marak][]" value="<?php echo ($nav2['marak']); ?>">
                        &nbsp;
                        <span class="">URL链接：</span><input type="text" class="text" name="template[nav][2][url][]" value="<?php echo ($nav2['url']); ?>">
                        <div class='pull-left i-list'>
                            <a href="javascript:" title="添加二级导航" class="add-two">  <i class="icon icon-plus"></i></a>
                            <a href="javascript:" title="移除二级导航" class="remove-li"><i class="icon icon-remove"></i></a>
                        </div>
                    </div>
                </div><?php endforeach; endif; ?>
            </div><?php endforeach; endif; ?>
        </div>
        <div class="form-item">
            <label class="item-label"></label>
            <div class="controls">
                <input type="hidden" name="name" value="wechat"></input>
                <button type="submit" class=" submit_btn ajax-post" target-form="form-horizontal" style="margin-left: 159px;">确 定</button>
            </div>
        </div>
        </form>
        </div>
    </div>
    <div id="one-nav" style="display: none;">
        <div class="form-item channel pLi" >
            <div class="controls">
                <span class="">一级导航：</span><input type="text" class="text" name="template[nav][1][title][]"  value="">
                &nbsp;
                <span class="">URL链接： </span><input type="text input-large" class="text" name="template[nav][1][url][]" value="">
                <div class='pull-left i-list'>
                    <a href="javascript:" title="添加一级导航" class="add-one">  <i class="icon icon-plus"></i></a>
                    <a href="javascript:" title="移除一级导航" class="remove-li"><i class="icon icon-remove"></i></a>
                    <a href="javascript:" title="添加二级导航" class="add-child"><i class="icon icon-sitemap"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div id="two-nav" style="display: none;">
        <div class="form-item channel cLi" >
            <input name="template[nav][2][pid][]" class="pid" style="display: none">
            <div class="controls">
                <span class="">二级级导航：</span><input type="text" class="text" name="template[nav][2][title][]" value="">
                &nbsp;
                <span class="">导航标示： </span><input type="text" class="text" name="template[nav][2][marak][]" value="">
                &nbsp;
                <span class="">URL链接： </span><input type="text input-large" class="text" name="template[nav][2][url][]" value="">
                <div class='pull-left i-list'>
                    <a href="javascript:" title="添加二级导航" class="add-two">  <i class="icon icon-plus"></i></a>
                    <a href="javascript:" title="移除二级导航" class="remove-li"><i class="icon icon-remove"></i></a>
                </div>
            </div>
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
    
<script src="/Public/Admin/js/channel.js"></script>
<script type="text/javascript">
    //导航高亮
    highlight_subnav('<?php echo U('Wechat/index');?>');
    $(function(){
        //支持tab
        showTab();
        $('.firsttab').click();
    })
</script>

</body>
</html>