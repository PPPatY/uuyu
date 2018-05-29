<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
  <head>
    <meta charset="UTF-8">
    <?php if(ACTION_NAME == index and CONTROLLER_NAME == Index): ?><title><?php echo seo_replace(C('media_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_index.seo_description');?>">
    <?php elseif(ACTION_NAME == index and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_list.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_list.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_list.seo_description');?>">
    <?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_detail.seo_title'),array('game_name'=>$data['game_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == index and CONTROLLER_NAME == Gift): ?>
      <title><?php echo seo_replace(C('media_gift_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_gift_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_gift_index.seo_description');?>">
    <?php elseif(ACTION_NAME == giftdetail and CONTROLLER_NAME == Gift): ?>
      <title><?php echo seo_replace(C('media_gift_detail.seo_title'),array('game_name'=>$data['game_name'],'giftbag_name'=>$data['giftbag_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_gift_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_gift_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == mall and CONTROLLER_NAME == PointShop): ?>
      <title><?php echo seo_replace(C('media_shop.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_shop.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_shop.seo_description');?>">
    <?php elseif(ACTION_NAME == mall_detail and CONTROLLER_NAME == PointShop): ?>
      <title><?php echo seo_replace(C('media_shop_detail.seo_title'),array('good_name'=>$data['good_name']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_shop_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_shop_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Article): ?>
      <title><?php echo seo_replace(C('media_news_detail.seo_title'),array('title'=>$data['title']),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_news_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_news_detail.seo_description');?>">
    <?php elseif(ACTION_NAME == user_recharge and CONTROLLER_NAME == Subscriber): ?>
      <title><?php echo seo_replace(C('media_recharge.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_recharge.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_recharge.seo_description');?>">
    <?php elseif(CONTROLLER_NAME == Service): ?>
      <title><?php echo seo_replace(C('media_service.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_service.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_service.seo_description');?>">
    <?php else: ?>
      <title><?php echo seo_replace(C('media_index.seo_title'),'','media');?></title>
      <meta name="keywords" content="<?php echo C('media_index.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_index.seo_description');?>"><?php endif; ?>
    <link href="<?php echo get_cover(C('PC_SET_ICO'),'path');?>" type="image/x-icon" rel="shortcut icon">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="applicable-device" content="mobile">   
    <link href="/Public/Media/css/swiper.min.css" rel="stylesheet" >
    <link href="/Public/Media/css/common.css" rel="stylesheet" >
    <link href="/Public/Media/css/pc.css" rel="stylesheet">
    
<link href="/Public/Media/css/user.css" rel="stylesheet" >

  </head>
  <body>
    

    <div class="main">
      <div class="pc-header">
        <div class="pc-inner clearfix">
          <div class="pc-download">
            <a href="javascript:;" class="pc-downloadbtn"><i class="pc-icon-phone"></i><span>扫码关注微信公众号</span></a>
            <div class="pc-qrcode clearfix">
             <div class="pc-ios"><img src="/Public/Media/images/iframe/Qr-code-u-uuyu.jpg"><span>扫我关注</span></div>
            </div>
          </div>
          <a href="<?php echo U('Index/index');?>" class="pc-logo"><?php if(!empty($union_set)): ?><img src="<?php echo get_cover($union_set['pc_logo'],'path');?>"><?php else: ?><img src="<?php echo get_cover(C('PC_SET_LOGO'),'path');?>"><?php endif; ?></a>
        </div>
      </div>
      <div class="pc-container">
        <div class="pc-wrap">
        <div class="pc-inner">
          <div class="pc-mobile">
						<div class="pc-full-box">
            <div class="pc-iframe">
              
<header class="header">
  <section class="wrap">
  <?php if(I('get.game_id') != ''): ?><a href="javascript:history.go(-1);" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a>
  <?php else: ?>
    <a href="<?php echo U('user');?>" class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Media/images/back_return.png"></span></span></a><?php endif; ?>
    <div class="caption"><span class="table"><span class="table-cell">充值</span></span></div>
  </section>
</header>

              
              
<div class="mainer">
<div class="occupy"></div>

<section class="trunker">
  
  <section class="inner">
    <section class="contain">
      <form id="payform" action="" method="post">
      <div class="recharge-base recharge-same">
        <div class="clearfix table">
          <div class="row table-row">
            <span class="table-cell cell1">充值账户</span>
            <span class="table-cell cell2"><input type="text" class="txt" placeholder="请输入充值账户" name="" value="<?php echo ($userinfo["nickname"]); ?>"></span>
          </div>
          <div class="row table-row">
            <span class="table-cell cell1">账户余额</span>
            <span class="table-cell cell2"><span><?php echo ($userinfo["balance"]); ?> 平台币</span></span>
          </div>
          <div class="row table-row">
            <span class="table-cell cell1">充值金额</span>
            <span class="table-cell cell2"><span class="money"><input type="text" id="money" class="txt" placeholder="充值金额需为整数" name="" value=""><span class="mark"><i class="money-mark">¥</i><i class="money-del"></i></span></span></span>
          </div>
        </div>
        <div class="selectarea clearfix">
          <div class="stop cell1">快速选择</div>
          <div class="smiddle jsselected clearfix">
              <a href="javascript:;" class="butn" data-value="1000">1000</a>
              <a href="javascript:;" class="butn" data-value="500">500</a>
              <a href="javascript:;" class="butn" data-value="300">300</a>
              <a href="javascript:;" class="butn" data-value="100">100</a>
          </div>
          <div class="sbottom clearfix" id="flatcoin">
            <span class="sbottomleft">您将获得<i id="number" class="number">0.00</i>平台币</span>
            <span class="sbottomright">(兑换比例<i id="ratio" class="ratio" data-value="1">1:1</i>)</span>
          </div>
        </div>
      </div>
      <div class="recharge-way-title"><span>选择支付方式</span></div>
      <div class="recharge-way recharge-same">
        <div class="clearfix jsradio paytype table">
        <?php if(($paytype['wei_xin'] == 1) or ($paytype['weixin'] == 1)): ?><label class="item input-radio table-row " id="weixin">
            <span class="table-cell item1"><img src="/Public/Media/images/my_pay_weixin.png" class="icon"></span>
            <span class="table-cell item2">微信支付</span>
            <span class="table-cell item3 jsway"><span class="icon-hook-wrap"><input type="radio" name="way" class="radio on" checked value=""><i class="icon-hook"></i></span></span>
          </label><?php endif; ?>
        <?php if($paytype['alipay'] == 1): ?><label class="item input-radio table-row " id="alipay">
            <span class="table-cell item1"><img src="/Public/Media/images/my_pay_alipay.png" class="icon"></span>
            <span class="table-cell item2">支付宝支付</span>
            <span class="table-cell item3 jsway"><span class="icon-hook-wrap"><input type="radio" name="way" class="radio" value=""><i class="icon-hook"></i></span></span>
          </label><?php endif; ?>
        <?php if($paytype['goldpig'] == 1): ?><label class="item input-radio table-row " id="goldpig">
            <span class="table-cell item1"><img src="/Public/Media/images/goldpig.png" class="icon"></span>
            <span class="table-cell item2">金猪支付</span>
            <span class="table-cell item3 jsway"><span class="icon-hook-wrap"><input type="radio" name="way" class="radio" value=""><i class="icon-hook"></i></span></span>
          </label><?php endif; ?>
        </div>
      </div>
      <div class="recharge-butn">
        <input type="submit" class="butn jssubmit" value="立即充值">
      </div>
      </form>
    </section>
    
  </section>
</section>
</div>
<div id="payiframe">
    <iframe  src="" style="background-image:url(<?php echo ($game_load_page); ?>);" frameborder="0" class="payiframe"></iframe>
</div>


              
<div class="pop"></div>
<div class="popmmm"></div>

              
              <div class="popplog"></div>
              
            </div>
						<!-- <a href="javascript:;" class="pc-screen-btn jsscreen"><i class="pc-screen"></i></a> -->
						</div>
            <a href="javascript:history.back();" class="pc-butn"><i class="pc-icon"></i></a>
          <div class="pc-theme"><img class="pc-theme-pic" src="/Public/Media/images/iframe/theme.png"></div>
          </div>
          <div class="pc-sys"><div class="pc-qrcode-box"><img class="pc-qrcode-sys" src="<?php echo get_cover(C('PC_SET_QRCODE'),'path');?>"></div><p>扫码即玩 快乐游戏</p></div>
          <div class="pc-container-footer">
						<div class="pc-copyright">
							<p class="pc-cr">
								<span><img src="/Public/Media/images/iframe/pc_ghs.png"><?php echo C('WEB_BEIAN');?></span>
								<span>经营性许可证：<?php echo C('WEB_SITE');?></span>
								<span><?php echo C('PC_GUAN');?></span>
							</p>
							<p>抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</p>
						</div>
					</div>
        </div>
      </div>
      </div>
    </div>
    
    <div class="loginbutdiv hidden">
        <?php if($wx_login == 1): ?><a href="javascript:;" onclick="register('weixin')" class="butn"><img src="/Public/Media/images/login_third_weixin.png"></a><?php endif; ?>
        <?php if($qq_login == 1): ?><a href="javascript:;" onclick="register('qq')" class="butn"><img src="/Public/Media/images/login_third_qq.png"></a><?php endif; ?>
        <?php if($wb_login == 1): ?><a href="javascript:;" onclick="register('weibo')" class="butn"><img src="/Public/Media/images/login_third_sina.png"></a><?php endif; ?>
        <?php if($bd_login == 1): ?><a href="javascript:;" onclick="register('baidu')" class="butn"><img src="/Public/Media/images/login_third_tencent.png"></a><?php endif; ?>
    </div>
    
    <script src="/Public/Media/js/jquery-1.11.1.min.js"></script>
    <script src="/Public/Media/js/swiper-3.4.2.jquery.min.js"></script>
    <script src="/Public/static/layer/layer.js"></script>
    <script src="/Public/Media/js/pop.lwx.min.js"></script>
    <script src="/Public/Media/js/common.js"></script>
    
<script src="/Public/Media/js/pop.lwx.min.js"></script>
<script src="/Public/Media/js/common.js"></script>
<script src="/Public/static/layer/layer.js"></script>
<script>
    var pop = $('.pop').pop();
    $paynum = "<?php echo I('get.pay_order_number');?>";
    if($('#money').val()!=''){
      location.href = location.href;
    }else{
      $('.jsselected a:first-child').each(function(){
        var that = $(this),value=that.attr('data-value');
        var number = $('#number');
        var ratio = $('#ratio').attr('data-value');
        that.addClass('on').siblings().removeClass('on');
        number.html((ratio*value).toFixed(2));
        $('#money').val(value).addClass('on').siblings('.mark').find('i').fadeIn(200);
        return false;
      });
    }
    
    var popmmm = $('.popmmm').pop();
    
    if($paynum){
      pop.addClass('pop-load').open('','<div class="eloading"><span class="icon"></span><span>订单查询中...</span></div>');
      $.ajax({
        type:"POST",
        data:{pay_order_number:$paynum},
        url:"<?php echo U('Subscriber/deposit_search');?>",
        dataType:"json",
        success:function(res){
            pop.removeClass();
            if(res.status==1){
              if(res.data.pay_status==1){
                // 成功
                pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">充值成功</div><div class="pop-table"><div class="pop-row"><div class="pop-cell">充值账户：</div><div class="pop-cell">'+res.data.user_account+'</div></div><div class="pop-row"><div class="pop-cell">充值金额：</div><div class="pop-cell">￥'+res.data.payamount+'</div></div></div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">关闭</a> <a href="javascript:;" class="pop-butn pop-comfirm again">继续充值</a></div>');
                $('.jscancel').click(function() {
                  location.href="<?php echo U('Subscriber/user');?>";
                });
                $('.again').click(function() {
                  location.href="<?php echo U('Subscriber/user_recharge');?>";
                });
              }else{
                // 失败
                pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">充值失败</div><div class="pop-text">可能是网络延时，如24小时未充值完成，请联系网站客服</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">关闭</a> <a href="javascript:location.href = location.href;" class="pop-butn pop-comfirm ">重新查询</a></div>');
                $('.jscancel').click(function() {
                  location.href="<?php echo U('Subscriber/user');?>";
                });
              }
            }else{  
              // 失败
              pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">订单不存在</div><div class="pop-text">如有疑问请联系网站客服</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jscancel">关闭</a></div>');
              $('.jscancel').click(function() {
                  location.href="<?php echo U('Subscriber/user');?>";
              });
            }
        }
      });
    }
    
    $(function() {
      
      $('.jsloginclick a').click(function() {
        pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法签到哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="login.html" class="pop-butn pop-comfirm ">去登录</a></div>');
        $('.jscancel').click(function() {pop.close();});
          
        return false;
      });
      
      $('#money').keyup(function() {
        var that=$(this),val = that.val();
        that.addClass('on');
        if (val && val>0 && /^(0|[1-9][0-9]*)$/.test(val)) {
          that.siblings('.mark').find('i').fadeIn(200);
          var ratio = $('#ratio').attr('data-value');
          $('#number').html((ratio*val).toFixed(2));
        } else {$('#number').html('0.00');that.removeClass('on').val('').siblings('.mark').find('i').fadeOut(200);}
        return false;
      }).blur(function() {
        var that=$(this),val = that.val();
        val || that.removeClass('on').siblings('.mark').find('i').fadeOut(200);
      });
      
      $('.money-del').click(function() {
        var that=$(this),sib = that.siblings('.txt');
        that.fadeOut(200);
        $('#money').val('').removeClass('on');
        that.siblings('.money-mark').fadeOut(200);
        $('.jsselected a').removeClass('on');$('#number').html('0.00');
      });
      
      
      $('.jsselected a').click(function() {
        var that = $(this),value=that.attr('data-value');
        var number = $('#number');
        var ratio = $('#ratio').attr('data-value');
        that.addClass('on').siblings().removeClass('on');
        number.html((ratio*value).toFixed(2));
        $('#money').val(value).addClass('on').siblings('.mark').find('i').fadeIn(200);
        return false;
      });
      
      
      $('.jsradio .radio').change(function() {
        var that=$(this),parent=that.closest('.jsradio');
        if (that.prop('checked')) {
          parent.find('.radio').removeClass('on');
          that.addClass('on');
        }
      });
      
      
      $('.jssubmit').click(function() {
        paymoney = $("#flatcoin #number").text();
        if(paymoney<1){
          layer.msg('请输入充值金额');
          return false;
        }
        if($(this).hasClass('disabled')){
          return false;
        }
        var that = $(this);
        $('.jsway input[name="way"]').each(function(){
          if($(this).hasClass('on')){
            $paytype = $(this).closest('.paytype label').attr('id');
          }
        });
        try{
          if($paytype==''){
            layer.msg('请选择支付方式');
            return false;
          }
        }catch(err){
          layer.msg('请选择支付方式');
          return false;
        }

        if($paytype==''){
          layer.mag('请选择支付方式');
          return false;
        }else{
          switch($paytype){
            case 'weixin':
              if (isWeiXin()) {
                $type=<?php echo get_wx_type();?>;
                if($type){
                  $("#form1").attr('target',"_self");
                  $("#form1").submit();
                }else{
                  get_wx_code();
                }
              }else if(IsPC()){
                  wx_pay();
                  return false;
              }else{
                  wx_wap_pay();
                  return false;
              }
              break;
            case 'alipay':
              alipay();
              break;
            case 'jft':
              jft();
              break;
            case 'goldpig':
              goldpig();
              break;
            default:
              layer.msg('请选择支付方式');
              break;
          } 
        }
        
        $('.jscancel').click(function() {pop.close();});
        
        return false;
      });
    });
    function isWeiXin(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    }
    function get_wx_code(){
      pcloading=layer.load(0, {shade: false});
      $game_id="<?php echo base64_decode(I('game_id'));?>";
       $.ajax({
          type:"POST",
          data:{amount:$("#flatcoin #number").text(),game_id:$game_id},
          url:"<?php echo U('get_wx_code');?>",
          dataType:"json",
          success:function(res){
            if(res.status){
          window.location.href=res.url; 
            }else{
              alert(res.msg);
            }
          },
          error:function(XMLHttpRequest, textStatus, errorThrown){
            layer.closeAll();
          },
          complete:function(){
            layer.closeAll();
          }
        })
      return;
    }

    function IsPC() {
        var userAgentInfo = navigator.userAgent;
        var Agents = ["Android", "iPhone",
                    "SymbianOS", "Windows Phone",
                    "iPad", "iPod"];
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }




    function wx_pay(){
      $('.jssubmit').addClass('disabled');  
      var index = layer.load(2, {shade: false}); //0代表加载的风格，支持0-2
      $.ajax({
        type:"POST",
        url:"<?php echo U('Pay/wx_pay');?>",
        data:{amount:$("#flatcoin #number").text()},
        dataType:"json",
        success:function(data){
          layer.closeAll();
          $('.jssubmit').removeClass('disabled');  
          if(data.status==1){
            var result = '';
            result+='<div class="pop-pay-weixin" style="display:block;"><a href="javascript:;" class="pop-arrow-left2"></a><div class="pop-content"><div class="pop-title">微信支付</div><div class="pop-pay-info"><div class="pop-table"><div class="pop-row"><span class="pop-cell pm">充值金额</span> <span class="pop-cell pv"><span class="red">￥<i>';
            result+=data.amount;
            result+='</i></span></span></div><div class="pop-row"><span class="pop-cell pm">订单号</span> <span class="pop-cell pv"><span>';
            result+=data.out_trade_no;
            result+='</span></span></div></div></div><div class="pop-pay-qrcode"><img src="';
            result+=data.qrcode_url+'"><p>扫一扫 完成支付</p></div></div></div>';
            popmmm.addClass('pop-pay').open('',result);
            $('.pop-arrow-left2').click(function() {
                  popmmm.close();
            });
          }else if(data.status==0){
            layer.msg(data.info);
          }else{
            pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">创建订单失败<div class="pop-text">'+data.info+'</div></div></div><div class="pop-butn-box"><a  class="pop-butn2 pop-comfirm2 jscancel">好的</a></div>');
            $('.jscancel').click(function() {pop.close();});
            return false;
          }
          
        },
        error:function(res){
        }
      })
    }
    function checkstatus(data){
      $.ajax({
        type:"POST",
        url:"<?php echo U('checkstatus');?>",
        data:{out_trade_no:data},
        dataType:"json",
        success:function(data1){
          if(data1.status==1){
            $game_id="<?php echo base64_decode(I('game_id'));?>";
            if(!isNaN($game_id)){
              window.location.href='media.php?s=/Game/open_game/game_id/'+$game_id;
            }else{
              window.location.href="<?php echo U('Subscriber/index');?>";
            }
          }else{
            checkstatus(data);
          }
        },
        error:function(res){
        }
      })
    }
    function wx_wap_pay(){
      $.ajax({
        type:"POST",
        url:"<?php echo U('Pay/weixin_wap_pay');?>",
        data:{amount:$("#flatcoin #number").text()},
        dataType:"json",
        success:function(data){
          if(data.status==1){
            window.location=data.pay_info; 
          }else if(data.status==0){
            layer.msg(data.info);
          }else{
            layer.closeAll();
            pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">创建订单失败<div class="pop-text">'+data.info+'</div></div></div><div class="pop-butn-box"><a  class="pop-butn2 pop-comfirm2 jscancel">好的</a></div>');
            $('.jscancel').click(function() {pop.close();});
            return false;
          }
        },
        error:function(res){
            layer.closeAll();
            pop.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">服务器错误，请稍后再试</div></div><div class="pop-butn-box"><a  class="pop-butn2 pop-comfirm2 jscancel">好的</a></div>');
            $('.jscancel').click(function() {location.href = location.href});
            return false;
        }
      })
    }

    function alipay(){
      $game_id="<?php echo base64_decode(I('game_id'));?>";
      $amount = $("#flatcoin #number").text();
      if($amount<1){
        layer.msg('充值金额不正确');
        return false;
      }
      url = "<?php echo U('Pay/alipay','',false);?>"+'/amount/'+$amount+'/game_id/'+$game_id;
      window.open(url);
    }
    function jft(){
      if($("#flatcoin #number").text()<1){
        layer.msg('充值金额有误');
        return false;
      }
      $.ajax({
        type:"POST",
        url:"<?php echo U('Pay/jftpay');?>",
        data:{money:$("#flatcoin #number").text()},
        dataType:"json",
        success:function(data){
          location.href = data.url;
        },error:function(XMLHttpRequest, textStatus, errorThrown){

        }
      });
    }
    function goldpig(){
      layer.load();
      if($("#flatcoin #number").text()<1){
        layer.msg('充值金额有误');
        return false;
      }
      $.ajax({
        type:"POST",
				url:"<?php echo U('Pay/recharge_pig');?>",
        data:{money:$("#flatcoin #number").text()},
        dataType:"json",
        success:function(data){
          layer.closeAll('loading');
          if(data.status==1){
            location.href = data.url;
          }else{
            layer.msg(data.info);
          }
        },error:function(){
          layer.msg('服务器忙，请稍后再试');
          return false;
        }
      });
    }
</script>

    <?php echo ($logdiv); ?>
    <script>
      var gid = "<?php echo ($game_id); ?>";
      var pid = "<?php echo ($promote_id); ?>";
      var open_name_auth = 0;
      var name_auth_tip = '';
      var $bindphoneadd = "<?php echo ($bindphoneadd); ?>";
      <?php if($open_name_auth == 1): ?>var open_name_auth = "<?php echo ($open_name_auth); ?>";
          var name_auth_tip = "<?php echo ($name_auth_tip); ?>";<?php endif; ?>
    </script>
    <script>
      $("body").on("click",'.butnbox a.butnlogin',function(){
        res = jslogin();
        return res;
      });
      function jslogin(){
          $uid = "<?php echo is_login();?>";
          if($uid<=0){
            $('.jslogin').click();
            return false;
          }else{
            return true;
          }
      }
    </script>
    <script>  
      $('.pc-download').hover(function(){$('.pc-qrcode').fadeIn();},function(){$('.pc-qrcode').fadeOut();});
      
      function resizephone() {
          var winHeight = $( window ).height();
					
					
							var hedh = $('.pc-header').height();
							var both = $('.pc-container-footer').height();
							var pch = winHeight-hedh;
							if($(window).width()>720)
								$('.pc-container').css({'height':pch+'px'});
							var mobheight = pch-both;
							
          var mobwidth = parseInt(mobheight*512/1000);
					
					
          var scale = parseInt(mobwidth/410*100);

              $('html').css('font-size',231*scale/100+'%');
							
      }
      resizephone();
      var resizephonetime = null;
      $(window).resize(function() {
        if (resizephonetime) {clearTimeout(resizephonetime);}
        resizephonetime = setTimeout(function() {
				if($(window).width()<=720) {resizephone();}
        },10);
      });
			
			$('.jsscreen').click(function() {
				var that = $(this);
				if (that.hasClass('full')) {
					that.removeClass('full').css({'right':'-1.2rem'});
					$('.pc-full-box').removeClass('full-iframe').unwrap();
				} else {
					that.addClass('full');
					that.css({'right':'-0.76rem'});
					$('.pc-full-box').addClass('full-iframe').wrap('<div style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1;background:#000"></div>');				
				}
				
			});
      
    </script>
    
  </body>
</html>