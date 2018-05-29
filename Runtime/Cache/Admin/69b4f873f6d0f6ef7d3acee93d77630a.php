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
    .navtab_list a.tabok{ text-decoration:none; border-bottom:none; background:#4459cb; color:#fff;}
    .navtab_list a.tabno{ text-decoration:none; border-bottom:none; background:red; color:#fff;}
    .navtab_list_cs{ margin-top:20px;margin-left: 20px;}
    .navtab_list_cs img{ margin:0px 8px;}
    .data_list table td {
        min-width: 50px;
    }
   /*多选框优化*/
	.tools label.checked{
	    line-height: 33px;
		margin-left: 10px;
	}
</style>
<link rel="stylesheet" href="/Public/Admin/css/select2.min.css" type="text/css" />
<script type="text/javascript" src="/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/select2.min.js"></script>
    <!-- 标题栏 -->
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Promote/lists');?>">推广员</a></li>
            <li><a href="<?php echo U('Promote/lists');?>">推广员管理</a></li>
            <li><a href="<?php echo U('Apply/lists');?>">推广链接</a></li>
            <li><a href="#">游戏推广</a></li>
        </ul>

    </div>
    <div class="cf top_nav_list navtab_list"> 
        <a <?php if(CONTROLLER_NAME == 'Apply'): ?>class="tabchose"<?php endif; ?> <?php if(I('group') != 2): ?>href="<?php echo U('Apply/lists');?>" <?php else: ?> href="<?php echo U('Provide/ptbsenduser');?>"<?php endif; ?> >游戏推广</a>
        <a <?php if(CONTROLLER_NAME == 'ApplyUnion'): ?>class="tabchose"<?php endif; ?> href="<?php echo U('ApplyUnion/lists');?>">全站推广</a>
        <?php if(CONTROLLER_NAME == 'Apply'): ?><p class="description_text">说明：此处功能是对推广后台申请的游戏进行审核</p>
        <?php else: ?>
        <p class="description_text">说明：此处功能是对推广后台申请的站点进行审核</p><?php endif; ?>
    </div>
  
    <div class="cf top_nav_list">
        <div class="fl button_list">
        <?php if(empty($model["extend"])): ?><div class="tools data_list">
                <a class=" ajax-post confirm" target-form="ids" url="<?php echo U("Apply/set_status",array("status"=>1,"msg_type"=>5,"field"=>"status"));?>"><span class="button_icon button_icon9"></span>审 核</a>
              
                <a class=" ajax-post confirm " target-form="ids" url="<?php echo U('del?model='.$model['id']);?>"><span class="button_icon button_icon2"></span>删 除</a>
            
                <label class="checked">
                    <?php if($PROMOTE_URL_AUTO_AUDIT == 0): ?><input class="tabok AUTO_AUDIT" data-val="<?php echo ($PROMOTE_URL_AUTO_AUDIT); ?>"  type="checkbox" value="" name="">
                    <?php else: ?>
                        <input class="tabok AUTO_AUDIT" data-val="<?php echo ($PROMOTE_URL_AUTO_AUDIT); ?>" checked type="checkbox" value="" name=""><?php endif; ?>
			        <i class="check_icon"></i>
			        <span>自动审核</span>
		        </label>
            </div><?php endif; ?>
        </div>
        <!-- 高级搜索 -->
        <div class="jssearch fr cf search_list">
         
            <div class="input-list input-list-game search_label_rehab">
            <div class="input-list input-list-promote search_label_rehab">
                <label>推广员账号：</label>
                <select id="promote_id" name="promote_id" class="select_gallery" >
                    <option value="">请选择推广员账号</option>
                    <?php $_result=get_promote_list(1);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["account"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
           
            </div>
            <div class="input-list">
                <label>申请状态：</label>
                <select name="status" class="select_gallery select2-search--hide" style="width: 100px;">
                    <option value="">全部</option>
                    <option value="0">未审核</option>
                    <option value="1">已审核</option>
                </select>
            </div>
             <div class="input-list">
                <a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('Apply/lists','model='.$model['name'],false);?>">搜索</a>
            </div>
        </div>
    </div>


    <!-- 数据列表 -->
    <div class="data_list">
        <div class="">
            <table>
                <!-- 表头 -->
                <thead>
                    <th class="row-selected row-selected">
                            <label class="checked">
								<input class="check-all" type="checkbox">
								<i class="check_icon"></i>
							</label>
                        </th>
                        <th style="text-align:center">推广员账号</th>
                        <th style="text-align:center">游戏名称</th>
                        <th style="text-align:center">推广链接</th>
                        <th style="text-align:center">申请时间</th>
                        <th style="text-align:center">申请状态</th>
               
                        <th >操作</th>
                </thead>

                <!-- 列表 -->
                <tbody>
                    <?php if(empty($list_data)): ?><td colspan="12">暂无数据</td>
                    <?php else: ?>
                    <?php if(is_array($list_data)): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                            <td style="border-right:1px solid #DDDDDD;text-align:center">
                            	<label class="checked">
							        <input class="ids" type="checkbox" value="<?php echo ($data['id']); ?>" name="ids[]">
							        <i class="check_icon"></i>
						        </label>
                            </td>
                            <td style="border-right:1px solid #DDDDDD;text-align:center"><?php echo get_promote_name($data['promote_id']);?></td>
                            <td style="border-right:1px solid #DDDDDD;text-align:center"><?php echo get_game_name($data['game_id']);?></td>
                            <td style="border-right:1px solid #DDDDDD;text-align:center"><a style="cursor: pointer;" onclick="contend('<?php echo ($data["register_url"]); ?>')">查看</a></td>
                            <td style="border-right:1px solid #DDDDDD;text-align:center"><?php echo set_show_time($data['apply_time']);?></td>
                            <td style="border-right:1px solid #DDDDDD;text-align:center"><?php echo get_info_status($data['status'],5);?></td>
                   
                            <td >
                            <?php if($data['status'] != 1): ?><a href='<?php echo U("Apply/set_status",array("ids"=>$data['id'],"status"=>1,"msg_type"=>5,"field"=>"status"));?>' class="ajax-get confirm">审核
                            <?php else: ?>
                                <span>审核</span><?php endif; ?>
                            </td><?php endforeach; endif; else: echo "" ;endif; endif; ?>
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
<script src="/Public/static/layer/layer.js" type="text/javascript"></script>
<script src="/Public/static/layer/extend/layer.ext.js" type="text/javascript"></script>
<script type="text/javascript">
<?php $_result=I('get.');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>Think.setValue('<?php echo ($key); ?>',"<?php echo ($vo); ?>");<?php endforeach; endif; else: echo "" ;endif; ?>
$(".select_gallery").select2();
$(".select2-search--hide").select2({
	minimumResultsForSearch: -1,
});
</script>
<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo U('Apply/lists');?>');
function contend(contend){
    var contend="http://"+"<?php echo ($_SERVER['HTTP_HOST']); ?>"+contend;
        layer.open({
          type: 1,
          skin: 'layui-layer-rim', //加上边框
          area: ['550px', '100px'], //宽高
          content: contend
        });
    }
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
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });
    
    $('#time-start').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });

    $('#datetimepicker').datetimepicker({
       format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true,
        pickerPosition:'bottom-left'
    })

    /* 审核状态搜索子菜单 */
    $("#apply").find(".drop-down").hover(function(){
        $("#sub-sch-menu").removeClass("hidden");
    },function(){
        $("#sub-sch-menu").addClass("hidden");
    });
    $("#sub-sch-menu li").find("a").each(function(){
        $(this).click(function(){
            var text = $(this).text();
            $("#sch-sort-txt").text(text).attr("data",$(this).attr("value"));
            $("#sub-sch-menu").addClass("hidden");
            $("#status").val($(this).attr("value"));
        })
    });
    
    
    $("#game").on('click',function(event) {
        var navlist = $(this).find('.i_list_li');
        if (navlist.hasClass('hidden')) {
            navlist.removeClass('hidden');
            $(this).find('#i_list_id').focus().val('');            
        } else {
            navlist.addClass('hidden');
        }
        $(document).one("click", function(){
            navlist.addClass('hidden');
        });
        event.stopPropagation();
    });

    $('#game #i_list_id').on('keyup',function(event) {
        var val  = $.trim($(this).val()).toLowerCase();
        $(this).closest('.drop-down').find('#i_list_idh').val(val);
    });
    
    $("#game #i_list_li").find("a").each(function(){
        $(this).click(function(){
            var text = $.trim($(this).text()).toLowerCase();
            $(this).closest('.drop-down').find("#i_list_id").val(text);
            $(this).closest('.drop-down').find('#i_list_idh').val(text);
        })
    });
    
    
    $("#promote").on('click',function(event) {
        var navlist = $(this).find('.i_list_li');
        if (navlist.hasClass('hidden')) {
            navlist.removeClass('hidden');
            $(this).find('#i_list_id').focus().val('');            
        } else {
            navlist.addClass('hidden');
        }
        $(document).one("click", function(){
            navlist.addClass('hidden');
        });
        event.stopPropagation();
    });

    $('#promote #i_list_id').on('keyup',function(event) {
        var val  = $.trim($(this).val()).toLowerCase();
        $(this).closest('.drop-down').find('#i_list_idh').val(val);
    });
    
    $("#promote #i_list_li").find("a").each(function(){
        $(this).click(function(){
            var text = $.trim($(this).text()).toLowerCase();
            $(this).closest('.drop-down').find("#i_list_id").val(text);
            $(this).closest('.drop-down').find('#i_list_idh').val(text);
        })
    });
    $(".AUTO_AUDIT").click(function(){
        that = $(this);
        $AUDIT = that.attr('data-val');
        if($AUDIT>0){
            tip = '取消自动审核：推广员申请推广链接和全站链接时将取消自动审核，确定操作吗';
        }else{
            tip = '自动审核：推广员申请推广链接和全站链接时将自动审核，确定操作吗';
        }
        layer.msg(tip, {
          time: 0 //不自动关闭
          ,btn: ['好的', '再想想']
          ,yes: function(index){
            layer.closeAll();
            $.ajax({
                url:"<?php echo U('Apply/change_auto_audit');?>",
                type:'post',
                data:{value:$AUDIT},
                async:false,
                success:function(data){
                    if($AUDIT==1){
                        that.addClass('tabok').removeClass('tabno').attr('data-val',0).text('开启自动审核');
                    }else{
                        that.addClass('tabno').removeClass('tabok').attr('data-val',1).text('关闭自动审核');
                    }
                },error:function(){
                    alert('服务器错误，请稍后再试');
                }
            })
          }
          ,btn2: function(index, layero){
            if(that.prop('checked')){
                that.removeAttr('checked')
            }else{
                that.prop('checked',true)
            }
          }
        });
    });
})
</script>

</body>
</html>