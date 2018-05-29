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
  <style>
    .data_list table td {
        min-width: 50px;
    }
    .submit_btn {
        margin-left: 0px;
        margin-right: 0px;
    }
    .file_view { position:absolute; left:0; width:66px; top:20px; height:28px; filter:alpha(opacity=0); opacity:0; cursor: pointer } 
    .file_upload { float:left; z-index:1; width:66px; height:28px; line-height:28px; background:#3B95C9;color: #fff; text-align:center; cursor: pointer;border-radius: 4px; } 
    .inputfiles { border:0 !important; width:280px !important; float:left !important; height:28px !important; line-height:28px!important; background:#FFF; z-index:99 } 
  </style>
    <div class="main-place">
        <span class="main-placetitle"></span>
        <ul class="main-placeul">
            <li><a href="<?php echo U('Spend/lists');?>">充值</a></li>
            <li><a href="<?php echo U('Deposit/lists');?>">平台币订单</a></li>
            <li><a href="#"><?php echo ($meta_title); ?></a></li>
        </ul>
    </div>
    <div class="cf top_nav_list navtab_list navlist_copy"> 
        <a <?php if(ACTION_NAME == 'ptbsenduser'): ?>class="tabchose"<?php endif; ?> href="<?php echo U('Provide/ptbsenduser');?>">给玩家发放</a>
        <a <?php if(ACTION_NAME == 'lists'): ?>class="tabchose"<?php endif; ?> href="<?php echo U('Provide/lists',array('group'=>2));?>">玩家发放记录</a>
        <p class="description_text" style="margin-top: 15px;">说明：此功能是为给玩家发放平台币功能</p>
    </div>
    <!-- 标签页导航 -->
    <div class="tab-wrap">
        <div class="tab_nav jstabnav">
          <ul>
            <li data-tab="tab1" class="current"><a href="javascript:void(0);">单用户</a></li>
            <li data-tab="tab3" ><a href="javascript:void(0);">多用户</a></li>
            <li data-tab="tab2" ><a href="javascript:void(0);">批量导入</a></li>
          </ul>
      </div>
       <div class="tab-content tab_content">
        <!-- 表单 -->
        
          <!-- 基础文档模型 -->
          <div id="tab1" class="tab-pane tab_pane in tab1 ">
          <form id="form1" action="<?php echo U('ptbsenduser');?>" method="post" class="form-horizontal">
          <table border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <input type="hidden" name="type" value="1">
              <tr>
                  <td class="l">玩家账号：</td>
                <td class="r" colspan='3'>
                   <input type="text" class="txt" name="account" value="" placeholder="只能输入一个账号">
                </td>
              </tr>
              <tr>
                <td class="l">发放数量：</td>
                <td class="r" colspan='3'>
                   <input type="text" class="txt" name="amount" value="" placeholder="请输入大于0的整数">
                </td>
              </tr>
              <tr>
                  <td class="l"></td>
                  <td class="r" colspan='3'>
                  	<button class="submit_btn ajax-post" id="submit1" type="submit" target-form="form-horizontal">提 交</button>
                  </td>
              </tr> 
                                        
            </tbody>
          </table>
          </form>
          </div>
          <div id="tab2" class="tab-pane  tab2">
          <form id="form2" action="<?php echo U('ptbsenduser');?>" method="post" class="form-horizontal" enctype="multipart/form-data">
          <table border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
              <input type="hidden" name="type" value="2">
                <td class="l">Excel模板：</td>
                <td class="r" colspan='3'>
                   <a href="/Uploads/excel/PtbSendUser.xls">下载模板</a>
                </td>
              </tr>
              <tr>
                <td class="l">导入Excel：</td>
                <td class="r" colspan='3'>
                   <input type="file" name="excelData" value=""  >
                </td>
              </tr>
              <tr>
                  <td class="l"></td>
                  <td class="r" colspan='3'>
                  	<button class="submit_btn execl_submit" id="submit2" type="submit" target-form="form-horizontal">提 交</button>
                  </td>
              </tr>                             
            </tbody>
          </table>
          </form>
          </div>
          <div id="tab3" class="tab-pane  tab3">
          <form id="form3" action="<?php echo U('ptbsenduser');?>" method="post" class="form-horizontal">
          <table border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <input type="hidden" name="type" value="3">
              <tr>
                <td class="l">玩家账号：</td>
                <td class="r" colspan='3'>
                  <textarea name="pay_names" id="pay_names" cols="32" rows="20" placeholder="一行一个账号"></textarea>
                </td>
              </tr>
              <tr>
                <td class="l">发放数量：</td>
                <td class="r" colspan='3'>
                   <input type="text" class="txt" name="amount" value="" placeholder="请输入大于0的整数">
                </td>
              </tr>
              <tr>
                  <td class="l"></td>
                  <td class="r" colspan='3'>
                  	<button class="submit_btn ajax-post " id="submit3" type="submit" target-form="form-horizontal">提 交</button>
                  </td>
              </tr>                               
            </tbody>
          </table>
          </form>
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
    
<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>
<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="/Public/Admin/css/select2.min.css" type="text/css" />
    <script type="text/javascript" src="/Public/Admin/js/select2.min.js"></script>
<script src="/Public/static/layer/layer.js" type="text/javascript"></script>
<script src="/Public/Admin/js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">
// $(function(){
    //导航高亮
    highlight_subnav('<?php echo U('Provide/ptbsenduser');?>');

    $('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    showTab();


    $("#moneynum").blur(function(){
        $("#coinnums").html($("#moneynum").val());
        $("#coinnum").val(  $("#coinnums").html());
    })

   $('.jstabnav li').each(function() {
        var tab = $('.jstabnav li.current').attr('data-tab'),
        taht=$('#'+tab);
        if ($(this).hasClass('current')) {
          taht.find('input,select,textarea,checkbox').prop('disabled',false);
        } else {
          taht.siblings().find('input,select,textarea,checkbox').prop('disabled',true);
        }

    });
    $('.jstabnav li').on('click',function() {
       var tab = $(this).attr('data-tab'),
       taht=$('#'+tab);
       taht.find('input,select,textarea,checkbox').prop('disabled',false);
       taht.siblings().find('input,select,textarea,checkbox').prop('disabled',true);
    });
  
    $(".select_2").select2();
		
		$('.execl_submit').on('click',function() {
			var that=$(this),form = that.closest('form');
			var excelFile = form.find('input[type=file]').val();
			form.submit(function(){return false;});
			if (!excelFile) {layer.msg('请上传文件');return false;}
			
			if ((excelFile.indexOf('.xls') == -1 && excelFile.indexOf('.xlsx') == -1)) {layer.msg('上传文件类型不对');return false;}
			
			$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
			form.ajaxSubmit({
					dataType:'json',
					type:'post',clearForm:true,
					success:function(data){
							if (parseInt(data.status) ==1) {
									layer.msg(data.info);
									setTimeout(function() {
											window.location.reload();
									},1600);
							} else {
									$(that).removeClass('disabled').prop('disabled',false);
									layer.msg(data.info);
							}
					},
					error: function(XmlHttpRequest, textStatus, errorThrown){
							$(that).removeClass('disabled').prop('disabled',false);
							layer.msg("服务器故障，稍候再试...");
					}
			});
		
			return false;
		});
		
		

</script>

</body>
</html>