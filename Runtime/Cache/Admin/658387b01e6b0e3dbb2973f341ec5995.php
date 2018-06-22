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
    /*.navtab_list{ width:100%; border-bottom:1px solid #ddd; margin: 15px 0px; height: 65px;}*/
    .navtab_list {
			height: 36px;
			border-bottom: solid 1px #d0dee5;
			position: relative;
			border-left: solid 1px #d3dbde;
			margin: 15px 0px;
		}
		.navtab_list a {
			float: left;
			height: 37px;
			line-height: 37px;
			background: url(/Public/Admin/images/itabbg.png) repeat-x;
			border-right: solid 1px #d3dbde;
			font-size: 14px;
			color: #000;
			padding-left: 25px;
			padding-right: 25px;
			text-decoration: none;
		}
		.navtab_list a:hover {
			border-bottom: 0;
		}
		.navtab_list a.tabchose {
			text-decoration: none;
			border-bottom: none;
			color: #000;
			height: 37px;
			display: block;
			background: url(/Public/Admin/images/itabbg1.png) repeat-x;
			font-weight: bold;
		}
    .navtab_list_cs{ margin-top:20px;margin-left: 20px;}
    .navtab_list_cs img{ margin:0px 8px;}
    .data_list table td {
        min-width: 50px;
    }
  </style>
<link rel="stylesheet" href="/Public/Admin/css/select2.min.css" type="text/css" />
<script type="text/javascript" src="/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/select2.min.js"></script>
    <!-- 标题栏 -->
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Game/lists');?>">游戏</a></li>
            <li><a href="<?php echo U('Game/lists');?>">游戏管理</a></li>
            <li><a href="#"><?php echo ($meta_title); ?>列表</a></li>
        </ul>
        <p class="description_text" style="height: 40px;line-height: 40px;">通过对接的自运营游戏为官方游戏，可进行cps渠道推广。</p>
    </div>
	<div class="cf top_nav_list">
		<div class="fl button_list">
		<?php if(empty($model["extend"])): ?><div class="tools">
				<a class="" href="<?php echo U('add?model='.$model['id']);?>"><span class="button_icon button_icon1"></span>新 增</a>
				<a class="ajax-post confirm " target-form="ids" url="<?php echo U('del?model='.$model['id']);?>"><span class="button_icon button_icon2"></span>删 除</a>
            </div><?php endif; ?>
		</div>
		<!-- 高级搜索 -->
		<div class="jssearch fr cf search_list" style="margin-bottom:-15px;">
            <!--游戏类型搜索END-->
            <div class="input-list input-list-game search_label_rehab">
                <label>游戏名称：</label>
                <select id="game_id" name="game_name" class="select_gallery" >
                    <option value="">请选择游戏</option>
                    <?php $_result=get_game_list();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option game-id="<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["game_name"]); ?>"><?php echo ($vo["game_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>   
            </div>

            <div class="input-list input-list-game search_label_rehab">
                <label>推荐状态：</label>
                <select id="recommend_status" name="recommend_status" class="select_gallery" >
                    <option value="">请选择推荐状态</option>
                    <option value="0">不推荐</option>
                    <option value="1">推荐</option>
                    <option value="2">热门</option>
                    <option value="3">最新</option>
                    
                </select>   
            </div>

            <div class="input-list input-list-game search_label_rehab">
                <label>显示状态：</label>
                <select id="game_status" name="game_status" class="select_gallery" >
                    <option value="">请选择显示状态</option>
                    <option value="1">显示</option>
                    <option value="0">不显示</option>
                </select>   
            </div>
            <input type="hidden" name="" value="" class="sortBy">
            <div class="input-list">
                <a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('Game/lists','model='.$model['name'],false);?>">搜索</a>
            </div>
		</div>
	</div>


    <!-- 数据列表 -->
    <div class="data_list data_game_list">
        <div class="">
            <table>
                <!-- 表头 -->
                <thead>
                    <tr>
                        <th style="text-align: center;">
                            <label class="checked">
								<input class="check-all" type="checkbox">
								<i class="check_icon"></i>
							</label>
                        </th>
                        <th style="text-align: center;">游戏ID</th>
                        <th style="text-align: center;">游戏名称</th>
                        <th style="text-align: center;">游戏类型</th>
                        <th style="text-align: center;">游戏Appid</th>
                        <th style="text-align: center;">推荐状态</th>
                        <th style="text-align: center;">运营状态</th>
                        <th style="text-align: center;">显示状态</th>
                        <th ><a class="paixu" data-order='play_count'><?php if($userarpu_order == 4 and $userarpu_order_type == 'play_count'): ?>在玩人数▲<?php elseif($userarpu_order == 3 and $userarpu_order_type == 'play_count'): ?>在玩人数▼<?php else: ?>在玩人数 <img src="/Public/Admin/images/up-down.png" width="13px"><?php endif; ?></a></th>
                        <th ><a class="paixu" data-order='sort'><?php if($userarpu_order == 4 and $userarpu_order_type == 'sort'): ?>排序▲<?php elseif($userarpu_order == 3 and $userarpu_order_type == 'sort'): ?>排序▼<?php else: ?>排序 <img src="/Public/Admin/images/up-down.png" width="13px"><?php endif; ?></a></th>
                        <th >操作</th>
                    </tr>
                </thead>

                <!-- 列表 -->
                <tbody>
                    <?php if(empty($list_data)): ?><tr><td colspan="11">暂无数据</td></tr>
                    <?php else: ?>
                    <?php if(is_array($list_data)): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                            <td style="text-align: center;">
                            	<label class="checked">
								        <input class="ids" type="checkbox" value="<?php echo ($data['id']); ?>" name="ids[]">
								        <i class="check_icon"></i>
							        </label>
                            </td>
                            <td style="text-align: center;"><?php echo ($data["id"]); ?></td>
                            <td style="text-align: center;"><?php echo ($data["game_name"]); ?></td>
                            <td style="text-align: center;"><?php echo ($data["game_type_name"]); ?></td>
                            <td style="text-align: center;"><?php echo ($data["game_appid"]); ?></td>
                            <td style="text-align: center;"><?php echo get_recommend_type($data['recommend_status']);?></td>
                            <td style="text-align: center;"><?php echo get_one_opentype_name($data['category']);?></td>

                            <td style="text-align: center;">
                                <?php if($data["game_status"] == 0): ?><a class="chg_status" data-url="<?php echo U('chgculumn',array('isstatus'=>1,'column'=>'game_status','newval'=>1,'game_id'=>$data['id']));?>" href="javascript:;" oldval='<?php echo ($data["game_status"]); ?>' style="color: red ">不显示</a>
                                <?php else: ?>
                                    <a class="chg_status" data-url="<?php echo U('chgculumn',array('isstatus'=>1,'column'=>'game_status','newval'=>0,'game_id'=>$data['id']));?>" href="javascript:;" oldval='<?php echo ($data["game_status"]); ?>'>显示</a><?php endif; ?>
                            </td>

                            <td ><input class="chgculumn chgculumngame" data-url="<?php echo U('chgculumn',array('column'=>'play_count','game_id'=>$data['id']));?>"  type="text" oldval="<?php echo ($data["play_count"]); ?>" value="<?php echo play_num($data['id']);?>"> <span style="color: #5897fb;"></span></td>

                            <td><input class="chgculumn chgculumngame" data-url="<?php echo U('chgculumn',array('column'=>'sort','game_id'=>$data['id']));?>"  type="text" oldval="<?php echo ($data["sort"]); ?>" value="<?php echo ($data["sort"]); ?>"><span style="color: #5897fb;"></span></td>
                            <td>
                            <a href="<?php echo U('Game/edit?id='.$data['id']);?>">编辑</a>
                            <a href="<?php echo U('Game/del?ids='.$data['id']);?>" class="confirm ajax-get">删除</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
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
    
<script src="/Public/static/layer/layer.js"></script>
<script>
<?php $_result=I('get.');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>Think.setValue('<?php echo ($key); ?>',"<?php echo ($vo); ?>");<?php endforeach; endif; else: echo "" ;endif; ?>
$(".select_gallery").select2();
</script>
<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo U('Game/lists');?>');
$(function(){
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.jssearch').find('input').serialize();
        query  += "&"+$('.jssearch').find('select').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});

    //回车自动提交
    $('.jssearch').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });
    $(".paixu").click(function(){
        var that=$(this);
        $data_order=that.attr('data-order');
        $order_type='<?php echo ($userarpu_order); ?>';
        if($order_type==''||$order_type=='4'){
            $(".sortBy").attr('name','data_order');
            val='3,'+$data_order;
            $(".sortBy").attr('value',val);
            $("#search").click();
        }else if($order_type=='3'){
            $(".sortBy").attr('name','data_order');
            val='4,'+$data_order;
            $(".sortBy").attr('value',val);
            $("#search").click();
        }
    });
    //点击排序
    $('.list_sort').click(function(){
        var url = $(this).attr('url');
        var ids = $('.ids:checked');
        var param = '';
        if(ids.length > 0){
            var str = new Array();
            ids.each(function(){
                str.push($(this).val());
            });
            param = str.join(',');
        }

        if(url != undefined && url != ''){
            
            window.location.href = url.replace(".html","") + '/ids/' + param;
        }
    });
    //修改字段
    $('.chg_status').click(function(){
        that = $(this); 
        $.get(that.attr('data-url'),function(data,status){
            if(data.status==1){
                location.reload();
            }else{
                layer.msg(data.msg);
            }
        });
    });
    $(".chgculumn").blur(function(){
        that = $(this); 
        $.get(that.attr('data-url'),{newval:that.val()},function(data,status){
            if(data.status==1){
                layer.tips('编辑成功！', that, {
                  tips: [4, '#3595CC'],
                  time: 1000
                });
            }else{
                layer.msg(data.msg);
            }
        });
    });
})
</script>

</body>
</html>