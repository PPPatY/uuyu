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
<script type="text/javascript" src="/Public/static/layer/layer.js"></script>
<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
<style type="text/css">
	.tab_content {padding-top: 0;}
	.select2-container{width: 367px !important;}
	.tab_content input[type=text]{width: 365px;}
	#form .txt_area2 {width: 358px;height: 70px;}
	#form .txt_area1 {width: 358px;height: 70px;}
</style>
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Game/lists');?>">游戏</a></li>
            <li><a href="<?php echo U('Giftbag/lists');?>">礼包管理</a></li>
            <li><a href="#"><?php echo ($meta_title); ?></a></li>
        </ul>
    </div>
    <!-- 标签页导航 -->
<div class="tab-wrap">
	<div class="formtitle" style="margin-bottom: 10px;"><span>编辑礼包</span></div>
    <div class="tab-content tab_content">
    <!-- 表单 -->
    <form id="form" action="<?php echo U('edit?id='.$data['id']);?>" method="post" class="form-horizontal">
        <!-- 基础文档模型 -->
        <div id="tab1" class="tab-pane in tab1 tab_table">
            <table  border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                    <td class="l"><span style="color: red;">* </span>游戏名称：</td>
                    <td class="r" >
                       <select id="game_id" name="game_id" disabled="disabled">
                            <option value="0" selected="">请选择游戏</option>
                            <?php $_result=get_game_list();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" sdk_version="<?php echo ($vo["sdk_version"]); ?>"><?php echo ($vo["game_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                       <input type="hidden" id="game_name" name="game_name" value="">
                       <input type="hidden"  name="game_id" value="<?php echo ($data["game_id"]); ?>">
                    </td>
                    </tr>
                    <tr>
                    	<td class="l"><span style="color: red;">* </span>礼包名称：</td>
                    <td class="r" >
                        <input type="text" class="txt " name="giftbag_name" value="<?php echo ($data['giftbag_name']); ?>" placeholder=" 输入礼包名称">
                    </td>
                    </tr>
                  <tr>
                    <td class="l">礼包状态：</td>
                    <td class="r table_radio">
                    	<div class="radio radio-primary">
							<input type="radio" id="radio1" value="0" name="status" <?php if(($data['status']) == "0"): ?>checked="checked"<?php endif; ?>>
							<label for="radio1">关闭</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="radio2" value="1" name="status" <?php if(($data['status']) == "1"): ?>checked="checked"<?php endif; ?>>
							<label for="radio2">开启</label>
						</div>
                    </td>
                    </tr>
                    <tr>
                    	<td class="l">礼包类型：</td>
                    <td class="r table_radio">
                    	<div class="radio radio-primary">
							<input type="radio" id="radio3" value="0" name="giftbag_type">
							<label for="radio3">不推荐</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="radio4" value="1" name="giftbag_type" checked="checked">
							<label for="radio4">推荐</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="radio5" value="2" name="giftbag_type">
							<label for="radio5">热门</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="radio6" value="3" name="giftbag_type">
							<label for="radio6">最新</label>
						</div>
                    </td>
                    </tr>
                <tr>
                    <td class="l">适用区服：</td>
                    <td class="r">
                       <select id="server_id" name="server_id">
                        <option value="">请选择区服</option>
                       </select>
                       <input type="hidden" id="server_name" name="server_name" value=""></input>
                    </td>
                  <tr>
                    <td class="l"><span style="color: red;">* </span>有效时间：</td>
                    <td class="r table_time">
                        <input type="text" class="txtt time" name="start_time" value="<?php echo set_show_time($data['start_time'],'time');?>" placeholder="输入开启时间 不能空"> 至
                        <input type="text" class="txtt time" name="end_time" <?php if($data["end_time"] != ''): ?>value="<?php echo set_show_time($data['end_time'],'time','other');?>"<?php endif; ?> placeholder="输入结束时间 不填表示 永久">
                    </td>
                  </tr>
                  <tr>
                  	 <td class="l">激活码类型：</td>
                    <td class="r table_radio">
                    	<div class="radio radio-primary">
							<input type="radio" id="no_unicode" value="0" name="is_unicode" <?php if(($data['is_unicode']) == "0"): ?>checked="checked"<?php endif; ?>>
							<label for="no_unicode">普通码</label>
						</div>
						<div class="radio radio-primary">
							<input type="radio" id="is_unicode" value="1" name="is_unicode" <?php if(($data['is_unicode']) == "1"): ?>checked="checked"<?php endif; ?>>
							<label for="is_unicode">统一码</label>
						</div>
                    </td> 
                  </tr>
                  <tr>
                    <td class="l"><span style="color: red;">*</span><span class="codet">普通码：</span></td>
                    <td class="r">
                        <textarea name="novice" class="no_unicode novice txt_area2" <?php if(($data['is_unicode']) == "1"): ?>style="display: none;"<?php endif; ?> placeholder="输入普通码，一行一个"><?php echo str_replace(",","\r\n",$data['novice']);?></textarea>
                        <input type="text" class="is_unicode novice txtt" name="novice" <?php if(($data['is_unicode']) == "0"): ?>style="display: none;" disabled="disabled"<?php endif; ?>   value="<?php echo ($data['novice']); ?>" placeholder="输入统一码号">
                    </td>
                  </tr>
                  <tr>
                  	<td class="l">剩余数量：</td>
                    <td class="r" >
                        <div <?php if(($data['is_unicode']) == "1"): ?>style="display: none;"<?php endif; ?> >
                            <input type="text" class="novcount no_unicode txt" name="" value="<?php echo arr_count($data['novice']);?>" disabled style="cursor: not-allowed;" placeholder="根据领取数自动计算礼包剩余数量，不可修改">
                        </div>
                        <div <?php if(($data['is_unicode']) == "0"): ?>style="display: none;"<?php endif; ?> >
                            <input type="text" class="novcount is_unicode txt nodisabled" name="unicode_num" value="<?php echo ($data["unicode_num"]); ?>"   placeholder="请输入统一码数量">
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">领取方法：</td>
                    <td class="r" colspan='3'>
                        <textarea name="digest" class="txt_area1" placeholder="输入领取方式描述"><?php echo ($data['digest']); ?></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td class="l">礼包内容：</td>
                    <td class="r" colspan='3'>
                        <textarea name="desribe" class="txt_area1" placeholder="输入礼包内容描述"><?php echo ($data['desribe']); ?></textarea>
                    </td>
                  </tr>
                  
                </tbody>
            </table>
        </div>
        <div class="form-item cf">
            <input type="hidden" name='id' value="<?php echo ($data['id']); ?>">
            <button class=" submit_btn ajax-post " id="submit" type="submit" target-form="form-horizontal">确 定</button>
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
    
<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>
<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="/Public/Admin/css/select2.min.css" type="text/css" />
    <script type="text/javascript" src="/Public/Admin/js/select2.min.js"></script>
<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo U('Giftbag/lists');?>');
if ("<?php echo ($_GET['game_id']); ?>" =="") {
    Think.setValue("game_id", <?php echo ((isset($data["game_id"]) && ($data["game_id"] !== ""))?($data["game_id"]): 0); ?>);
}else{
    Think.setValue("game_id", <?php echo ((isset($_GET['game_id']) && ($_GET['game_id'] !== ""))?($_GET['game_id']): 0); ?>);
}
Think.setValue("server_id", <?php echo ((isset($data["area_id"]) && ($data["area_id"] !== ""))?($data["area_id"]): 0); ?>);
Think.setValue("giftbag_type", <?php echo ((isset($data["giftbag_type"]) && ($data["giftbag_type"] !== ""))?($data["giftbag_type"]): 1); ?>);
Think.setValue("level", <?php echo ((isset($data["level"]) && ($data["level"] !== ""))?($data["level"]): 0); ?>);
$('#submit').click(function(){
    $('#form').submit();
});
if($("#game_id").val()){
    fun_ajax($("#game_id").val());
}
$(function(){
    $("#game_name").val($("#game_id option:selected").text());

    $('.date').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    $('.time').datetimepicker({
    language:"zh-CN",
     hour: 13,
       minute: 15
    });
    showTab();
     Initialize();
     $("#game_id").select2();
     $("#server_id").select2();
});

/*获取区服名称*/
$("#server_id").change(function() {
    $("#server_name").val($("#server_id option:selected").text());
});
/*获取游戏名称*/
var trid = $('.js-typeradio:checked').siblings('input').css({'display':'none'}).attr('id');
$('.'+trid).hide();
var ratio_type=$("input[type=radio]:checked").val();
if(ratio_type == 1){
    var file_type="apk";
}else if(ratio_type == 2){
    var file_type="ipa";
}
$("#game_id").change(function(){
    $(".inp_radio").click();
});
$(".inp_radio").click(function(){
    if($("#game_id option:selected").val()==0){
        alert('请选择游戏');
        return false;
    }
    var ratio_type=$("#game_id").children('option:selected').attr('sdk_version');
    if (ratio_type == '') {
        ratio_type =0;
    }
    if(ratio_type == 1){
    var file_type="apk";
    }else if(ratio_type == 2){
    var file_type="ipa";
    }
    var str = location.href,game_id=$("#game_id option:selected").val();
    str = str.replace('.html','');
    str = str+'/game_id/'+game_id+'/ratio_type/'+ratio_type;
    window.location.href=str;
});
function Initialize(){
    $server_name = "<?php echo get_area_name($data['server_id']);?>";
    $("#server_id").html("<option value='<?php echo ($data["server_id"]); ?>'>"+$server_name+"</option>");
    $("#server_name").val($("#server_id option:selected").text());
}
var url = "<?php echo U('Giftbag/get_ajax_area_list');?>";
/*获取游戏名称*/
$("#game_id").change(function(){
  var ratio_type=$("#game_id").children('option:selected').attr('sdk_version');
  $("#game_name").val($("#game_id option:selected").text());
  $("input[name='server_version']").val('ratio_type');
  if(ratio_type==2){
    $(".server_version_name").html('<input type="radio" class="inp_radio" checked value="2" name="giftbag_version" > 苹果');
  }else{
    $(".server_version_name").html('<input type="radio" class="inp_radio" checked value="1" name="giftbag_version" > 安卓');
  }
});

function fun_ajax(gid){
    var url = "<?php echo U('Giftbag/get_ajax_area_list');?>";
    $.ajax({
        type:"post",
        url:url,
        dataType:"json",
        data:{game_id:gid},
        success:function(data){
            fun_html(data);
            $("#server_name").val($("#server_id option:selected").text());
        },
        error:function(){
            layer.msg('服务器异常',{icon:5});
        }
    })
}

function fun_html(data){
    var server_id = "<?php echo ($data['server_id']); ?>";
    var area='';
    if(data == null){
        $("#server_id").html('<option value="">请选择区服</option>');
    }else{
        area+="<option value=''>请选择区服</option>";
        for (var i = 0; i<data.length; i++){
            area+='<option';
            if(data[i]['id']==server_id){
                area+=' selected';
            }
            area+=' value="'+data[i]['id']+'">'+data[i]['server_name']+'</option>';
        }
        $("#server_id").html(area);
    }
}
$("input[name='is_unicode']").click(function(){
    that = $(this);
    $class = that.attr('id');
    $nov = $(".novice."+$class);
    $nov.siblings().css('display','none').prop('disabled',true);
    $nov.css('display','').prop('disabled',false);
    $novcount = $(".novcount."+$class);
    $novcount.parent().siblings().css('display','none').children('input').css('display','none').prop('disabled',true);
    $novcount.parent().css('display','');
    if($novcount.hasClass('nodisabled')){
        $novcount.css('display','').prop('disabled',false);
        $('.codet').text('统一码：');
    }else{
        $('.codet').text('普通码：');
        $novcount.css('display','').prop('disabled',true);
    }
});
$("input[name='is_unicode']").each(function(ele,index){
    if($(this).prop('checked')==true){
        $che = $(this).attr('id');
        if($che=='is_unicode'){
            $("input[name='is_unicode']:last").click();
        }
    }
});
</script>

</body>
</html>