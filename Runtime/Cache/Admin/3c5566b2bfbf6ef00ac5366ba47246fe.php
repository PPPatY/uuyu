<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en" class="no-js">

    <head>

        <meta charset="utf-8">
        <title><?php echo C('WEB_SITE_TITLE');?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
     
        <link rel="stylesheet" href="/Public/Admin/css/reset.css">
        <link rel="stylesheet" href="/Public/Admin/css/supersized.css">
        <link rel="stylesheet" href="/Public/Admin/css/login_new.css">
        <script src="/Public/Admin/js/jquery-3.0.0.min.js" ></script>
        <script src="/Public/static/layer/layer.js" type="text/javascript"></script>
        <style>
        .tip {
            position: fixed;
            top: 38%;display:none;
            /* left: 38%; */width:340px;left:0;right:0;margin:0 auto;
            background-color:#4C4C4C;
            background: rgba(0,0,0,.7);color:#fff;
            box-shadow: 1px 8px 10px 1px #9b9b9b;
            border-radius: 1px;padding:20px 10px;
            z-index: 111111;}
        .tip .tipmain {text-align:center;}
        .tip .tipicon {width:20px;height:20px;border-radius:100%;background:#fff;vertical-align:middle;display:inline-block;background-image:url(/Public/Admin/images/tipicon_right.png);background-repeat:no-repeat;background-position:center center;background-size:77%;}
        .tip.tip_right .tipicon {background-image:url(/Public/Admin/images/tipicon_right.png);}
        .tip.tip_error .tipicon {background-image:url(/Public/Admin/images/tipicon_error.png);}
        .tip .tipinfo {display:inline-block;margin-left:10px;}
        label.checked{color: #404040;cursor: pointer;}
        label.checked input[type="checkbox"] {
			position: absolute;
			clip: rect(0, 0, 0, 0);
		}
		.check_icon {
			display: inline-block;
			width: 18px;
			height: 18px;
			margin: -4px auto;
		}
		label.checked input[type="checkbox"]+.check_icon {
			background: url(/Public/Admin/images/login_btn_check.png) no-repeat;
			background-position: -18px 0px;
		}
		label.checked input[type="checkbox"]+.check_icon:hover {
		}
		label.checked input[type="checkbox"]:checked+.check_icon {
			background: url(/Public/Admin/images/login_btn_check.png) no-repeat;
		}
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body class="loginbg">
        <div class="loginposition">
        <div class="loginleft">
            <img src="/Public/Admin/images/login_image.png" >
        </div>
        <div class="loginbox boxsize">
            <div class="loginform ">
                <form class="inputlogin" method="post" >
                    <div class="login_tit"><span class="logintitle">登录</span></div>
                    <div class="user boxsize">
                        <input type="text" name="username"  value="<?php echo ($_COOKIE['admin_usn']); ?>" class="username" placeholder="管理员账号" aria-required="true" aria-invalid="true" aria-describedby="">
                    </div>
                    <div class="password boxsize">
                        <input type="password" placeholder="密码" value="<?php echo ($_COOKIE['admin_pwd']); ?>" name="password" data-rule-required="true" aria-required="true">
                    </div>
                    <div class="verify boxsize">
                        <div class="verifywrap">
                            <div class="verifycode boxsize">
                                <input type="text" name="verify" class="ssssssaaaa" placeholder="请填写验证码" >
                            </div>
                            <img class="verifyimg reloadverify" title="点击切换" alt="验证码" src="<?php echo U('Public/verify');?>">
                        </div>
                    </div>
                    <div class="data_list">
                        <label class="checked">
							<input class="remember_pwd" type="checkbox" name="remember" value="1" checked="checked">
							<i class="check_icon"></i> 记住密码
						</label>
                    </div>
                    <div class="buttonbox boxsize">
                        <button type="submit" id="loginbtn" class="loginbtn">登<span></span>录</button>
                    </div>
                  
                </form>
                <form class="scanlogin hidden" >
                    <div class="login_tit"><span class="logintitle">手机扫描登录</span></div>
                    <img class="scanimg wx_qrcode" src="/Public/Admin/images/login_code.png" alt="" >
                    <p>打开微信&nbsp;&nbsp;<span>[扫一扫]</span>登录</p>
                    <div class="nouser boxsize nouser_2">
                        <a href="#"><span>没有帐号？</span>向企业管理员申请 ></a>
                    </div>
                    <div class="changebutton">
                        <img src="/Public/Admin/images/input.png" alt="inputlogin">
                    </div>
                </form>
            </div>
        </div>
        <!--新增底部链接-->
        <div class="bottom_tab">
        	<a href="/media.php" target="_blank">H5游戏平台</a>
        	<span>|</span>
        	<a href="/index.php" target="_blank">H5游戏推广联盟</a>
        </div>
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
        <!-- Javascript -->
        <script>
            $(function(){
                $(".inputlogin").unbind('submit').submit(function(){
                    $.ajax({
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        url: "<?php echo U('login');?>",
                        data: $(".inputlogin").serialize(),
                        success: function(data) {
                            if(data.status<1){
                                updateAlert(data.msg,'tip_error');
                                setTimeout(function(){
                                    $('#tip').find('.tipclose').click();
                                },1500);
                                $(".reloadverify").click();
                            }else{
                                updateAlert(data.msg,'tip_right');
                                setTimeout(function(){
                                    $('#tip').find('.tipclose').click();
                                },1500);
                                location.reload();
                            }
                        },
                        error:function(){
                            updateAlert('服务器错误','tip_error');
                            setTimeout(function(){
                                $('#tip').find('.tipclose').click();
                            },1500);
                        }
                    });
                    return false;
                });



                $('.changebutton').on('click',function(){
                    get_openid();
                    setTimeout(function(){
                        $('.jchangebutton').click();
                    },120000);
                    var that = $(this),parent = that.closest('form');
                    parent.addClass('hidden').siblings('form').removeClass('hidden');
                });
                function QrLogin(token) {
                    var ws = new WebSocket('ws://<?php echo ($_SERVER['HTTP_HOST']); ?>:1234');
                    ws.onopen = function () {
                      ws.send(token);
                    };
                    ws.onmessage = function (e) {
                        var res = e.data;
                        res = eval('(' + res + ')');
                        console.log(res,res.status);
                        if (res.status == 1) {
                            $.ajax({
                                Type: 'POST',
                                dataType: 'json',
                                data: {token: res.token},
                                url: "<?php echo U('QrLogin/QrLogin');?>",
                                success: function (data) {
                                    if (data.status == 1) {
                                        updateAlert('登陆成功','tip_right');
                                        setTimeout(function(){
                                            $('#tip').find('.tipclose').click();
                                        },1500);
                                        location.reload();
                                    }else{
                                        updateAlert(data.msg,'tip_error');
                                        setTimeout(function(){
                                            $('#tip').find('.tipclose').click();
                                        },1500);
                                    }
                                }
                            })
                        }
                    };
                }
                function get_openid(){
                    $.ajax({
                      type:"POST",
                      url:"<?php echo U('get_openid');?>",
                      dataType:"json",
                      success:function(res){
                        if(res.status){
                            $('.wx_qrcode').attr('src',res.data);
                            QrLogin(res.token);
                        }else{
                            $.ajax({
                              type:"POST",
                              url:"<?php echo U('wite_token');?>",
                              dataType:"json",
                              success:function(res){
                                if(res.status){
                                    $('.wx_qrcode').attr('src',res.data);
                                }else{
                                    alert('服务器错误');
                                }
                              },
                            })
                        }
                      },
                      error:function(){
                      }
                    })
                };
                var verifyimg = $(".verifyimg").attr("src");
                $(".reloadverify").click(function(){
                    if( verifyimg.indexOf('?')>0){
                        $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                    }else{
                        $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
                    }
                });
            });
        </script>
        </div>
    </body>

</html>