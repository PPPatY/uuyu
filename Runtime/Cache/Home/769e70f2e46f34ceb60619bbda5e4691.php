<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="keywords" content="<?php echo C('CH_SET_META_KEY');?>" />
		<meta name="description" content="<?php echo C('CH_SET_META_DESC');?>" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<?php if(ACTION_NAME == index and CONTROLLER_NAME == Index): ?><title><?php echo seo_replace(C('channel_index.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_index.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_index.seo_description');?>">
		<?php elseif(ACTION_NAME == game_list and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_game_list.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_game_list.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_game_list.seo_description');?>">
		<?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_news_detail.seo_title'),array('title'=>$info['title']),'channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_news_detail.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_news_detail.seo_description');?>">
		<?php elseif(ACTION_NAME == more_lists and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_news_list.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_news_list.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_news_list.seo_description');?>">
		<?php else: ?>
			<title><?php echo C('CH_SET_TITLE');?></title><?php endif; ?>
		<title><?php echo C('CH_SET_TITLE');?></title>
      <link href="<?php echo get_cover(C('CH_SET_ICO'),'path');?>" type="image/x-icon" rel="shortcut icon">
   
      <link href="/Public/Home/css/20170913/common.css" rel="stylesheet" >
      <script src="/Public/Home/js/jquery-1.11.1.min.js"></script>
      <script src="/Public/static/layer/layer.js" ></script>
      
       
  <link href="/Public/Home/css/20170913/index.css" rel="stylesheet" >

      
	</head>
	<body>
    <div class="header">
      <div class="inner clearfix">
          <div class="logo">
              <a href="<?php echo U('index/index');?>">
                  <img src="<?php echo get_cover(C('CH_SET_LOGO'),'path');?>" alt="logo">
              </a>
              <i></i>
              <span class="text"><span>H5推广</span><span>联盟中心</span></span>
          </div>
          <ul class="nav clearfix">
            <?php $__NAV__ = M('Channel')->field(true)->where("status=1")->order("sort asc")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): ?><li>
                      <a href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>"><span><?php echo ($nav["title"]); ?></span></a>
                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </ul>
      </div>
    </div>
     
    <div class="trunk">
		
<div class="banner">
    <div class="inner clearfix">
        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo C('CH_SET_SERVER_QQ');?>&site=qq&menu=yes" class="qqbtn" target="_blank"><img src="/Public/Home/images/20170913/qq.png">QQ咨询</a>  
        <div class="lrwrapper clearfix">
            <div class="lrbox clearfix">
                <div class="lrpane tab-pane fade active in" id="lr-login">
                    <h4 class="title"><span class="titletext">欢迎回来！</span><span class="titlebtn">还没账号？<a href="<?php echo U('register');?>" >去注册</a></span></h4>
                    <form id="loginForm" class="form-horizontal" method="post"  novalidate="novalidate">

                        <div class="form-group clearfix">
                            <div class="input-group input-format">
                                <span class="input-group-addon"><i class="input_icon input_icon_user" ></i></span>
                                <input type="text" name="account" value="<?php echo ($_COOKIE['home_account']); ?>" class="form-control" placeholder="账号" aria-describedby="basic-addon1" maxlength="30">
                            </div>
                            <div class="input-status"></div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="input-group input-format ">
                                <span class="input-group-addon"><i class="input_icon input_icon_lock"></i></span>
                                <input type="password" id="loginPassword" value="<?php echo ($_COOKIE['home_pas']); ?>" name="password" class="form-control" placeholder="密码" aria-describedby="basic-addon1" >
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-status"></div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="captchabox input-group input-format">
                                <span class="input-group-addon"><i class="input_icon input_icon_barcode"></i></span>
                                <input class="form-control" id="loginCaptcha" name="yzm" placeholder="验证码" autocomplete="off" maxlength="4">
                            </div>
                            <div class="f-wsn"><img name="changeCaptcha" src="/index.php?s=/Home/Index/verify"></div>
                            <div class="input-status"></div>
                        </div>
                        <div class="form-group ff clearfix">
                            <label class="tabbtn"><input type="checkbox" name="remm" id="remember" ><i></i><span>记住密码</span></label>
                        </div>
                        <div >
                            <input id="loginButton" type="submit" class="btn btn_primary" value="登 录">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
    <div class="code">
        <img src="images/t-code.png" clas="code-img">
        <div class="code-text">扫一扫下载</div>
        <div class="code-text code-orange">渠道管家App</div>
    </div>
<?php if(!empty($doc1)): ?><div class="news">
    <div class="inner clearfix txtScroll">
        <span><i class="icon icon-voice"></i></span>
        <a class="next" href="javascript:;"><i class="icon icon-angle_right"></i></a>
        <div class="bd">
        <ul>
          <?php if(is_array($doc1)): $i = 0; $__LIST__ = $doc1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
          	<a href="<?php echo U('Home/Article/detail?id='.$vo['id']);?>" target="_blank" title="<?php echo ($vo['title']); ?>"><i>公告</i><?php echo msubstr($vo['title'],0,70);?></a>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        </div>
    </div>
</div><?php endif; ?>

<div class="advantage page-aside">
    <div class="inner">
        <h2 class="aside-title"><span>平台优势</span></h2>
        <div class="aside-content clearfix">
            <ul>
              <li><div class="item"><i class="icon icon1"></i><span class="contitle">收入丰厚</span><p class="context"><span>CPS+CPA双模式计费方式</span><span>可持续获得收益</span></p></div></li>
              <li><div class="item"><i class="icon icon2"></i><span class="contitle">统计精准</span><p class="context"><span>每一笔收入都有迹可循</span><span>精准无误，永不扣量</span></p></div></li>
              <li><div class="item"><i class="icon icon3"></i><span class="contitle">海量资源</span><p class="context"><span>优质内容，海量资源开放合作</span><span>提供最佳合作方式</span></p></div></li>
              <li><div class="item"><i class="icon icon4"></i><span class="contitle">结算及时</span><p class="context"><span>结算快速，结算金额准确无误</span><span>绝不拖欠分成</span></p></div></li>
            </ul>
        </div>
    </div>
</div>

<div class="app page-aside">
    <div class="inner">
        <h2 class="aside-title"><span>精品应用推荐</span></h2>
        <div class="aside-content slideColumn clearfix">
            <div class="bd">
              <div class="ulWrap">
                <ul>
                    <?php if(is_array($rec_data)): $i = 0; $__LIST__ = $rec_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rec): $mod = ($i % 12 );++$i;?><li>
                      <div class="pic"><a style="cursor: default;" href="javascript:;" target="_blank"  title="<?php echo ($rec["game_name"]); ?>"><img src="<?php if(empty($rec['icon'])): ?>/Public/Home/images/game_icon.png<?php else: echo (get_cover($rec['icon'],'path')); endif; ?>"></a></div>
                      <div class="title"><a style="cursor: default;" href="javascript:;" target="_blank" title="<?php echo ($rec["game_name"]); ?>"><?php echo ($rec["game_name"]); ?></a></div>
                    </li>
                    <?php if(($mod) == "11"): ?></ul><ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
              </div>
            </div>
            <?php if(count($rec_data) > 12): ?><div class="hd"><ul>
              <?php if(is_array($rec_data)): $i = 0; $__LIST__ = $rec_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rec): $mod = ($i % 12 );++$i; if(($mod) == "11"): ?><li></li><?php endif; endforeach; endif; else: echo "" ;endif; ?></ul>
            </div>
            <a class="prev" href="javascript:void(0)"></a>
            <a class="next" href="javascript:void(0)"></a><?php endif; ?>
        </div>
    </div>
</div>

<div class="join page-aside">
    <div class="inner">
        <h2 class="aside-title"><span>如何加入我们</span></h2>
        <div class="aside-content clearfix">
            <ul class="clearfix">
                <li><div class="item"><img class="icon" src="/Public/Home/images/20170913/step1.png"><h5 class="contitle">注册账号</h5><p class="context"><span>注册账号，通过审核，加入联盟</span></p></div><div class="angle"><i class="iconangle"></i></div></li>
                <li><div class="item"><img class="icon" src="/Public/Home/images/20170913/step2.png"><h5 class="contitle">选择游戏资源</h5><p class="context"><span>选择推广的产品，游戏信息</span></p></div><div class="angle"><i class="iconangle"></i></div></li>
                <li><div class="item"><img class="icon" src="/Public/Home/images/20170913/step3.png"><h5 class="contitle">申请渠道分包</h5><p class="context"><span>获得自有渠道的游戏资源包</span></p></div><div class="angle"><i class="iconangle"></i></div></li>
                <li><div class="item"><img class="icon" src="/Public/Home/images/20170913/step4.png"><h5 class="contitle">推广分成</h5><p class="context"><span>每笔充值，后台申请结算的分成</span></p></div></li>
            </ul>
            <a href="<?php echo U('register');?>" class="joinbtn" >开始加入</a>
        </div>
    </div>
</div>

<div class="gotop"><img src="/Public/Home/images/index/gotop.png"></div>

    </div>
		
    <div class="footer">
        <div class="inner" style="margin-top: 27px;">
          <p><span>客服电话：<?php echo C('CH_SET_SERVER_TEL');?></span><span>客服邮箱：<?php echo C('CH_SET_SERVER_EMAIL');?></span><span>服务时间：09:00 - 18:00</span></p>
          <p class="footer_text">
          <span class="footer_text1">网络备案：<?php echo C('CH_SET_FOR_THE_RECORD');?></span>
          <span class="footer_text2">网络文化经营许可证编号：<?php echo C('CH_SET_LICENSE');?></span>
          <span class="footer_text3"><?php echo C('CH_SET_COPYRIGHT');?></span></p>
        </div>
    </div>
    
		<script type="text/javascript">
			(function(){
				var ThinkPHP = window.Think = {
					"ROOT"   : "", //当前网站地址
					"APP"    : "/index.php?s=", //当前项目地址
					"PUBLIC" : "/Public", //项目公共目录地址
					"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
					"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
					"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
				}
			})();
		</script>
    <script src="/Public/Home/js/20170913/jquery.form.js"></script>
    <script src="/Public/Home/js/20170913/jquery.validate.min.js"></script>
    <script src="/Public/Home/js/20170913/jquery.md5.js"></script>
    <script src="/Public/Home/js/20170913/common.js"></script>
    
<script src="/Public/Home/js/20170913/jquery.SuperSlide.2.1.1.js"></script>
<script>
    highlight_subnav('<?php echo U("Index/index");?>');
    var regLogin = "";
    // 如果登录有错误

    $(document).ready(function(){
    	<?php if(count($rec_data) > 12): ?>$(".slideColumn").slide({titCell:".hd ul",mainCell:".bd .ulWrap",autoPage:true,effect:"left",autoPlay:true,vis:1});
        <?php else: ?>
        $(".slideColumn").slide({titCell:".hd ul",mainCell:".bd .ulWrap",autoPage:true,effect:"left",autoPlay:false,vis:1});<?php endif; ?>
        $(".txtScroll").slide({mainCell:".bd ul",autoPage:true,effect:"leftLoop",autoPlay:true});
    
        $('#remember').change(function() {
            var that = $(this);
            if (that.prop('checked')) {
              that.siblings('i').addClass('on');
            } else {
              that.siblings('i').removeClass('on');
            }
        });

        /**
         * 新增验证方法
         */
        $.validator.addMethod("numOrLetter", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9_\.]+$/.test(value);
        }, '只能是字母或数字');

        // 登录验证
        $("#loginForm").validate({
            //定义规则
            rules:{
                account:{
                    required:true,
                    rangelength:[6,30]
                },
                password:{
                    required:true,
                    minlength:6
                },
                yzm:{
                    required:true,
                    rangelength:[4,4]
                }
            },
            //定义错误消息
            messages:{
                account:{
                    required:"请输入登录账号",
                    rangelength:"账号必须是6~30位字符串"
                },
                password:{
                    required:"请输入登录密码",
                    minlength:'登录密码必须大于6位',
                },
                yzm:{
                    required:"请输入验证码",
                    rangelength:"验证码必须是4位字符串"
                }
            },
            submitHandler:function(form){
                data = $('#loginForm').serialize();
                $.ajax({
                    type:'post',
                    url:"<?php echo U('login');?>",
                    data:data,
                    success:function(data){
                        if(data.status==1){
                            layer.msg(data.msg, {icon: 1});
                            window.location.href=data.url;
                        }else{
                            $("img[name='changeCaptcha']").click();
                            layer.msg(data.msg, {icon: 2});
                        }
                    },error:function(){

                    }
                });
            }
        });


    });

</script>
<script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/index.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "/", //PATHINFO分割符
            "MODEL"  : ["3", "", "html"],
            "VAR"    : ["m", "c", "a"]
        }
    })();
</script>

	</body>
</html>