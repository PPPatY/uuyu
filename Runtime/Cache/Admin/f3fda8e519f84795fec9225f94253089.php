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
        .keyword-wrap .content .li-com {
            padding: 5px;
        }
        .keyword-wrap .content .li-com .wangEditor-container{
            width: 90%;
            margin-left: 50px;
            margin-top: 10px;
        }

        .keyword-wrap .content .li-com .img-pre-show{
            margin-left: 50px;
            margin-top: 10px;
        }
        .keyword-wrap .content .li-com span.key-text {
            display: inline-block;
            width: 100px;
            text-align: right;
            vertical-align: top;
        }
        .keyword-wrap .content .li-com label {
            margin: 0 15px;
        }
        .keyword-wrap .content .li-com .tuwen-content {
            margin-left: 50px;
            margin-top: 10px;
            border: 1px solid #a9a9a9;
            padding: 10px;
        }
        .keyword-wrap .content .li-com .text-content {
            margin-left: 50px;
            margin-top: 10px;
        }
        .keyword-wrap .content .li-com textarea {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            box-sizing: border-box;
        }
        .keyword-wrap .content .li-com .show-content {
            display: none;
        }
        .keyword-wrap .content .li-com .show-content.active {
            display: block;
        }

        .keyword-wrap .content .submit{
            margin-left: 50px;
            margin-top: 15px;
            padding: 6px 20px;
        }
        .upload-img-box{
            display: inline-block;
        }
        .tab-wrap{
            position: relative;
        }
        .tab-wrap .error-tip-box{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0,0,0,.3);
            text-align: center;
            z-index: 9;
        }
        .tab-wrap .error-tip-box p{
            position: absolute;
            top: 45%;
            left: 34%;
            max-width: 108px;
            display: inline-block;
            margin: 0 auto;
            padding: 10px 130px;
            background: #00000060;
            color: #fff;
            border-radius: 10px;
            z-index: 9;
        }
    </style>
    
    <script type="text/javascript" src="/Public/static/webuploader/webuploader.js"></script>
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
                <li data-tab="tab1" class="current firsttab"><a href="javascript:void(0);" >关键字</a></li>
                <p class="description_text">/*关键字说明*/</p>
            </ul>
        </div>
        <div class="error-tip-box js-show-error-tip-p">
            <p><span></span></p>
        </div>
        <div class="keyword-wrap" style="margin-top: 15px;">
            <div class="content">
                <div class="keyword li-com">
                    <span class="key-text">关键词：</span>
                    <input type="text" class="key-word-p" value="<?php echo ($res["keyword"]); ?>" data="<?php echo ($res["id"]); ?>">
                </div>
                <div class="reply li-com">
                    <span class="key-text">回复：</span>
                    <!-- <label for="for-tuwen" class="for-tuwen js-change-keyw"><input id="for-tuwen" type="radio" value="0" name="chose" checked="checked">图文</label>
                    <label for="for-text" class="for-text js-change-keyw"><input id="for-text" type="radio" value="1" name="chose">文本</label> -->
                    <div class="tuwen-content show-content active">
                        <div class="bg-pic li-com" style="padding-left: 100px;position: relative;">
                            <span class="key-text js-showimg-url-p" style=" position: absolute; left: 0;" data="<?php echo ($res["picurl"]); ?>">背景图：</span>
                            <?php echo hook('UploadImages', array('name'=>'showbgWeChat','value'=>$res.picurl,'pic_num'=>1));?>
                        </div>
                        <div class="bg-pic li-com">
                            <span class="key-text">标题：</span>
                            <input type="text" class="key-word-title-p" value="<?php echo ($res["title"]); ?>">
                        </div>
                        <div class="bg-pic li-com">
                            <span class="key-text">描述：</span>
                            <!-- <div id="txtdiv" style="border:1px solid gray;min-height:240px"></div> -->
                        <textarea id="editor-w" style="border:1px solid gray;min-height:240px;max-width: 600px;"> <?php echo ($res["description"]); ?></textarea>
                        </div>
                        <div class="bg-pic li-com">
                            <span class="key-text">跳转地址：</span>
                            <input type="text" class="key-word-address-p" value="<?php echo ($res["url"]); ?>">
                        </div>
                    </div>
                    <div class="text-content show-content">
                    </div>
                </div>
                <input type="button" value="提交" class="submit js-submit">
            </div>
        </div>
        <!--脚本控制-->
        <script>
            var data = {};
        
            // 初始显示图片
            $(document).ready(function(){
                var picUrl = $('.js-showimg-url-p').attr("data");
                $('.upload-pre-item>img').attr("src", picUrl);
            });

            // 获取关键词
            $('.key-word-p').blur(function () {
                data.keyword = $(this).val();
                data.id = $(this).attr("data");
            })


            // 获取图文中的标题
            $('.key-word-title-p').blur(function () {
                data.title = $(this).val();
            })
        
            // 获取图文中的地址
            $('.key-word-address-p').blur(function () {
                data.url = $(this).val();
            })
        
            // 获取文本中的内容
            $('#editor-w').blur(function () {
                data.description = $(this).val();
            })
        
            // 点击提交
            $('.js-submit').click(function () {
                data.id = data.id || $('.key-word-p').attr("data");
                data.picurl = $('.upload-img-box .upload-pre-item img')[0].src.replace(/^http:\/\/[^/]+/, "");
                data.keyword = data.keyword || $('.key-word-p').val();
                data.title = data.title || $('.key-word-title-p').val();
                data.url = data.url || $('.key-word-address-p').val();
                data.description = data.description || $('#editor-w').val();
                sendDatas(data);
            })

            // 数据提交函数
            function sendDatas(e){
            var errorTip = $('.js-show-error-tip-p');
                // 提交信息
                var data_v = e;
                var sendData = $.ajax({
                    url: "<?php echo U('Wxoperate/editkeywords');?>",
                    method: 'post',
                    data: data_v,
                    dataType: 'json'
                })
                sendData.done(function (e) {
                    if (!e) {
                        return;
                    } else if(e.code == 1) {
                        errorTip.show();
                        errorTip.find('p span').html('修改成功！');
                        DialogErrorTip(e.code);
                    } else {
                        errorTip.show();
                        errorTip.find('p span').html('修改失败！');
                        DialogErrorTip(e.code);
                    }
                });
                sendData.fail(function () {
                    errorTip.show();
                    errorTip.find('p span').html('服务器出错，请稍后重试！');
                    DialogErrorTip(0)
                })
            }
        
            // 错误弹框关闭
            function DialogErrorTip(e){
                if(e == 1){
                    setTimeout(function(){
                        $('.js-show-error-tip-p').hide();
                        window.location.href = "<?php echo U('Wxoperate/keyWords');?>";
                    },1500);
                    $('.js-show-error-tip-p').click(function () {
                        $('.js-show-error-tip-p').hide();
                        window.location.href = "<?php echo U('Wxoperate/keyWords');?>";
                    })
                } else{
                    setTimeout(function(){
                        $('.js-show-error-tip-p').hide();
                    },1500);
                    $('.js-show-error-tip-p').click(function () {
                        $('.js-show-error-tip-p').hide();
                    })
                }
            }
        </script>

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
    <script>
        
        /*上传图片*/
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
                },1500);
            }
        }
    </script>

</body>
</html>