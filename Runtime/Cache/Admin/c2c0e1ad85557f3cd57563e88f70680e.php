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
            
	<link rel="stylesheet" type="text/css" href="/Public/static/bootstrap.min.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/admin_table.css" media="all">

	<script src="/Public/static/md5.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
	<style>
		.file_view { position:absolute; left:0; width:66px; top:24px; height:28px; filter:alpha(opacity=0); opacity:0; cursor: pointer } 
        .file_upload { float:left; z-index:1; width:66px; height:28px; line-height:28px; background:#3B95C9;color: #fff; text-align:center; cursor: pointer;border-radius: 4px; } 
        .inputfiles { border:0 !important; width:280px !important; float:left !important; height:28px !important; line-height:28px!important; background:#FFF; z-index:99; } 
	    #form .txt_area{width: 338px;height: 76px;}
	    /*上传封面*/
        .upload_picture_cover{float: left;margin-top: 10px;}
        .upload-img-box{float: left;}
        #upload_picture_cover,#upload_picture_detail_cover{float: left;margin-top: 5px;width: 70px !important;margin-left: 5px;}
        #upload_picture_cover-button,#upload_picture_cover-button{width: 70px !important;}
	</style>
	<div class="main-place">
		<span class="main-placetitle"></span>
		<ul class="main-placeul">
			<li>
				<a href="<?php echo U('Site/config_index');?>">站点</a>
			</li>
			<li>
				<a href="<?php echo U('lists');?>">积分商城</a>
			</li>
			<li>
				<a href="<?php echo U('lists');?>"><?php echo ($meta_title); ?></a>
			</li>
		</ul>
	</div>
	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<div class="formtitle" style="margin-bottom: 0;"><span>新增积分商品</span></div>
		<div class="tab-content tab_content">
			<!-- 表单 -->
			<form id="form" action="<?php echo U('add');?>" method="post" class="form-horizontal">
				<!-- 基础文档模型 -->
				<div id="tab1" class="tab-pane in tab1 tab_table">
					<table border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td class="l"><span style="color:red;">* </span>商品名称：</td>
								<td class="r">
									<input type="text" class="txt" name="good_name" value="<?php echo ($data['good_name']); ?>">
								</td>
							</tr>
							<tr>
								<td class="l"><span style="color:red;">* </span>商品价格：</td>
								<td class="r">
									<input type="text" class="txt" name="price" value="<?php echo ($data['price']); ?>">
								</td>
							</tr>
							<tr>
								<td class="l">商品封面：<span class="infonotice2">(尺寸：566*306px)</span></td>
								<td class="r" colspan='1'>
									<div class="upload-img-box">
                                    <?php if(!empty($data['cover'])): endif; ?>
                                </div>
                                <input type="file" id="upload_picture_cover">
                                <input type="hidden" name="cover" id="cover_id_cover" value="<?php echo ($data["cover"]); ?>" />

								</td>
							</tr>
							<tr>
								<td class="l">商品详情封面：<span class="infonotice2">(尺寸：390*390px)</span></td>
								<td class="r" colspan='1'>
									<div class="upload-img-box">
                                    <?php if(!empty($data['detail_cover'])): endif; ?>
                                </div>
                                <input type="file" id="upload_picture_detail_cover">
                                <input type="hidden" name="detail_cover" id="cover_id_detail_cover" value="<?php echo ($data["detail_cover"]); ?>" />

								</td>
							</tr>
							<tr>
								<td class="l">商品类型：</td>
								<td class="r table_radio">
									<div class="radio radio-primary">
										<input type="radio" id="radio1" class="inp_radio good_type" value="1" name="good_type" checked="checked">
										<label for="radio1">实物</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" id="radio2" class="inp_radio good_type" value="2" name="good_type">
										<label for="radio2">虚拟物品</label>
									</div>
								</td>
							</tr>
							<tr class="good_num">
								<td class="l">商品数量：</td>
								<td class="r" colspan='1'>
									<input type="text" class="txt" name="number" value="<?php echo ($data['number']); ?>">
								</td>
							</tr>
							<tr class="good_key hidden">
								<td class="l">商品兑换码：</td>
								<td class="r" colspan='1'>
									<textarea class="txt_area2" name="good_key" placeholder="一行一个"><?php echo ($data["good_key"]); ?></textarea>
								</td>
							</tr>
							<tr class="good_usage hidden">
								<td class="l">使用方法：</td>
								<td class="r">
									<textarea class="txt_area" name="good_usage"><?php echo ($data["good_usage"]); ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="l">商品详情：</td>
								<td class="r">
									<textarea class="txt_area" name="good_info"><?php echo ($data["good_info"]); ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="l">状态：</td>
								<td class="r table_radio">
									<div class="radio radio-primary">
										<input type="radio" id="radio3" class="inp_radio" value="1" name="status" checked="checked">
										<label for="radio3">开启</label>
									</div>
									<div class="radio radio-primary">
										<input type="radio" id="radio4" class="inp_radio" value="2" name="status">
										<label for="radio4">关闭</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="form-item cf">
					<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
					<button class="submit_btn ajax-post  " id="submit" type="submit" target-form="form-horizontal">确 定</button>
					<button class=" back_btn" onclick="javascript:history.back(-1);return false;">返 回</button>
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
		highlight_subnav("<?php echo U('lists');?>");
		$('#submit').click(function() {
			$('#form').submit();
		});

		$(function() {
			good_type();
			$(".good_type").click(function() {
				good_type();
			});
		});

		function good_type() {
			var type = $(":input[name='good_type']:checked").val();
			if(type == 1) {
				$(".good_usage").hide();
				$(".good_key").hide();
				$(".good_num").show();
			} else {
				$(".good_usage").show();
				$(".good_key").show();
				$(".good_num").hide();
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
            console.log(data);
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
                    $("#upload_picture_cover").removeClass('disabled').prop('disabled',false);
                },1500);
            }
        }
				
						/* 初始化上传插件 */
		$("#upload_picture_detail_cover").uploadify({
            "height"          : 30,
            "swf"             : "/Public/static/uploadify/uploadify.swf",
            "fileObjName"     : "download",
            "buttonText"      : "上传封面",
            "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
            "width"           : 120,
            'removeTimeout'   : 1,
            'fileTypeExts'    : '*.jpg; *.png; *.gif;',
            "onUploadSuccess" : upload_picture_detail_cover<?php echo ($field["name"]); ?>,
        'onFallback' : function() {
            alert('未检测到兼容版本的Flash.');
        }
        });
		function upload_picture_detail_cover<?php echo ($field["name"]); ?>(file, data){
            console.log(data);
            var data = $.parseJSON(data);
            var src = '';
            if(data.status){
                $("#cover_id_detail_cover").val(data.id);
                src = data.url || '' + data.path;
                $("#cover_id_detail_cover").parent().find('.upload-img-box').html(
                    '<div class="upload-pre-item"><img src="' + src + '"/></div>'
                );
            } else {
                updateAlert(data.info);
                setTimeout(function(){
                    $('#top-alert').find('button').click();
                    $("#upload_picture_detail_cover").removeClass('disabled').prop('disabled',false);
                },1500);
            }
        }
	</script>

</body>
</html>