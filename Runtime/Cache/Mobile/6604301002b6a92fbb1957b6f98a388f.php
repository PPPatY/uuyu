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
		<?php elseif(ACTION_NAME == 'open_game' and CONTROLLER_NAME == Game): ?>
      <title><?php echo seo_replace(C('media_game_detail.seo_title'),array('game_name'=>$game_name),'media');?></title>
      <meta name="keywords" content="<?php echo C('media_game_detail.seo_keyword');?>">
      <meta name="description" content="<?php echo C('media_game_detail.seo_description');?>">
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
		<link rel="apple-touch-icon" href="/Public/Mobile/images/touch-icon-iphone.png" />

		
		<meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
		
    <link href="/Public/Mobile/css/swiper.min.css" rel="stylesheet" >
    <link href="/Public/Mobile/css/common.css" rel="stylesheet" >
    <script src="/Public/Mobile/js/jquery-1.11.1.min.js"></script>
    <script src="/Public/Mobile/js/swiper-3.4.2.jquery.min.js"></script>
    <script src="/Public/Mobile/js/common.js"></script>
    <script src="/Public/Mobile/js/pop.lwx.min.js"></script>
  </head>
  <body <?php if(CONTROLLER_NAME == 'Search'): ?>class="searchpage" <?php elseif(CONTROLLER_NAME == 'Article'): ?>class="newspage" <?php elseif(ACTION_NAME == 'mall_record'): ?> class="integral-record"<?php elseif(ACTION_NAME == 'user_balance'): ?> class="user-balance"<?php elseif(ACTION_NAME == 'user_nickname'): ?> class="user-nickname"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_phone'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_modify'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_bind_modifyed'): ?> class="user-auth"<?php elseif(ACTION_NAME == 'user_auth' or ACTION_NAME == 'user_password'): ?> class="user-auth" <?php elseif(ACTION_NAME == 'user_address'): ?> class="user-balance" <?php elseif(ACTION_NAME == 'user_gift'): ?> class="user-gift" <?php elseif(ACTION_NAME == 'user_collectioned'): ?> class="user-collection" <?php elseif(ACTION_NAME == 'user_collection'): ?> class="user-collection" <?php elseif(ACTION_NAME == 'user_contact'): ?> class="user-contact" <?php elseif(ACTION_NAME == 'user_argeement'): ?> class="user-contact"<?php elseif(ACTION_NAME == 'user_message'): ?> class="user-message"<?php elseif(ACTION_NAME == 'mall' and $data["count"] == 0 ): ?> class="mall-empty-mainer"<?php endif; ?> >
    
<link href="/Public/Mobile/css/mall.css" rel="stylesheet" >
<style>
.footer{
  display:none;
}
</style>
<header class="header">
  <section class="wrap">
    <a href="<?php echo U('PointShop/mall');?>"  class="hbtn left arrow-left"><span class="table"><span class="table-cell"><img src="/Public/Mobile/images/back_return.png"></span></span></a>
    <div class="caption"><span class="table"><span class="table-cell">商品详情</span></span></div>
  </section>
</header>
<div class="occupy"></div>

<a href="javascript:;" style="display: none;" class="hbtn right table login jslogin"><span class="table-cell"><i class="">登录</i></span></a>
<section class="trunker">

  <section class="inner">
    <section class="contain">
      <?php if(!empty($data)): ?><div class="detail mall-detail">
          <form action="" class="">
          <input type="hidden" id="stock" name="" value="<?php echo ($data["number"]); ?>">
          <input type="hidden" id="integral" name="" value="<?php echo ($data["shop_point"]); ?>">
          <input type="hidden" id="price" name="" value="<?php echo ($data["price"]); ?>">
          <div class="base">
            <div class="wrap clearfix">
              <div class="iconbox"><span class="font table"><span class="table-cell"><?php echo C('BITMAP');?></span></span><img src="<?php echo ($data["detail_cover"]); ?>" class="icon"></div>
              <div class="textbox">
                <div class="title"><span class="name" title="<?php echo ($data["good_name"]); ?>" style="width: 7rem;"><?php echo ($data["good_name"]); ?></span></div>
                <p class="p1"><span>兑换积分：</span><span class="blue"><?php echo ($data["price"]); ?></span></p>
                <p class="p2"><span>商品库存：</span><span><?php echo ($data["number"]); ?></span><span class="red"></span></p>
                <p class="p3"><span>可用积分：</span><span><?php echo $data['shop_point']>0?$data['shop_point']:0;?></span><span class="red"></span></p>
                <p class="p4"><span>兑换数量：</span><span class="num">
                  <span class="numberbox clearfix"><a href="javascript:;" class="operation minus" data-operation="-"><i class="icon icon-minus"></i></a><input type="text" name="" class="number" id="number" readonly value="1"><a href="javascript:;" class="operation plus" data-operation="+"><i class="icon icon-plus"></i></a></span>
                </span></p>
              </div>
            </div>
          </div>
          
          <div class="description samething">
            <div class="wrap">
              <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>商品详情</span></div>
              <div class="content">
                <table>
                  <?php echo str_replace(array("\r\n", "\r", "\n"),"<br>",$data['good_info']);?>
                </table>
              </div>
            </div>
          </div>
          <?php if($data["good_type"] == 1): ?><div class="process samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>兑换流程</span></div>
                <div class="content">
                  <p>Step1：<span class="">登录账户</span> (没有注册的账户点击注册)</p>
                  <p>Step2：点击进入<span>商城页</span>浏览可兑换的商品</p>
                  <p>Step3：点击想兑换的商品进去<span class="">商品详情页</span></p>
                  <p>Step4：点击底部的悬浮按钮<span class="">【立即兑换】</span>进行兑换</p>
                </div>
              </div>
            </div>
            <div class="notes samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>兑换说明</span></div>
                <div class="content">
                  <p>商品将在兑换成功后的20个工作日内寄出，<span class="blue"><a href="javascript:;" class="jsenteraddress">点此</a></span> 填写或修改收货地址</p>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="process samething">
              <div class="wrap">
                <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>使用说明</span></div>
                <div class="content">
                  <?php echo str_replace(array("\r\n", "\r", "\n"),"<br>",$data['good_usage']);?>
                </div>
              </div>
            </div><?php endif; ?>
          
          <div class="disclaimer samething">
            <div class="wrap">
              <div class="cntitle"><span class="name"><i class="icon icon-desc"></i>免责声明</span></div>
              <div class="content">
                <p>请在兑换前仔细参照商品简介及兑换说明</p>
                <p><img src="/Public/Mobile/images/mall_commodity_detail_tagging.png">除了商品异常导致不能正常兑换外，一经兑换，一律不予退还积分</p>
                <!-- <p><img src="/Public/Mobile/images/mall_commodity_detail_tagging.png">抢购商品需先扣除积分，如抢购失败，积分将会原路退还，请注意查收</p> -->
                <p><img src="/Public/Mobile/images/mall_commodity_detail_tagging.png">活动规则最终解释权归友友互娱所有，如有疑问可联系在线客服 <a href="javascript:;" class="blue jscontactqq" data-qq="<?php echo C('PC_SET_SERVER_QQ');?>"><?php echo C('PC_SET_SERVER_QQ');?></a></p>
              </div>
            </div>
          </div>
          
          <div class="start-exchange">
            <input type="submit" class="btn submit jsexchange" value="立即兑换">
          </div>
          
          </form>
        </div>
      <?php else: ?>
        <div class="empty swiper-categroy"><img src="/Public/Mobile/images/no_date.png" class="empty-icon"><p class="empty-text">~ 空空如也 ~</p></div><?php endif; ?>
    </section>
    
  </section>
</section>
<div class="popmsg">
</div>
<script src="/Public/Mobile/js/pop.lwx.min.js"></script>
<script src="/Public/Mobile/js/common.js"></script>
<script src="/Public/Mobile/js/clipboard.min.js"></script>
<script>
		function Copy(str,that){
				text = str;
				var clipboard = new Clipboard('.copy',{
					text: function() {
								return text;
						}
				});
				clipboard.on('success', function(e) {
					that.text('复制成功');
					e.clearSelection();
				});

				clipboard.on('error', function(e) {
					that.text('复制完成');
					alert('此浏览器不支持此操作，请长按礼包码复制');
				});
		}
    $(function() {
      
      var buyok = 0;
      var popmsg = $('.popmsg').pop();
      var stock1 = $.trim($('#stock').val());
      var integral1 = $.trim($('#integral').val());
      var price1 = $.trim($('#price').val());
      var num1 = parseInt(integral1/price1);
      var input1 = $('#number'),val1=input1.val();
      var $user = "<?php echo is_login();?>";
      var $address = <?php echo ((isset($data['shop_address']) && ($data['shop_address'] !== ""))?($data['shop_address']): 0); ?>;
      var $goodstype = <?php echo ($data['good_type']); ?>;
      if($user>0){
        if(stock1<=0){
          buyok = 2;
          $('.p2 .red').text('(库存不足)');
        }
        if(num1<1){
          buyok = 2;
          $('.p3 .red').text('(积分不足)');input1.val(num1+1);
        }
      }
			$('.jscontactqq').click(function() {
				
					var qq = $.trim($(this).attr('data-qq'));
					if (browser.versions.mobile) {
						window.open("mqqwpa://im/chat?chat_type=wpa&uin="+qq+"&version=1&src_type=web");
					}else
						window.open("tencent://message/?uin="+qq+"&&menu=yes");
				return false;
				
			});
			
			$('.jsenteraddress').click(function() {
				var that = $(this);
				if ($user<=0) {
          // 未登录
          popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法兑换商品</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologinsss">去登录</a></div>');
          $('.jscancel').click(function() {popmsg.close();});
          popmsg.find('.tologinsss').click(function() {popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);});
        } else {
					window.location.href="<?php echo U('Subscriber/user_address',array('back'=>1));?>";
				}
				return false;
			});
			
      $('.operation').click(function() {
        buyok = 0;
        var that=$(this),operation = that.attr('data-operation');
        var stock = $.trim($('#stock').val());
        var integral = $.trim($('#integral').val());
        var price = $.trim($('#price').val());
        var input = $('#number'),val=input.val();
        var num = parseInt(integral/price);
        
        if (operation=='+') {
          if($goodstype>1){
            layer.msg('虚拟商品一次只能兑换一个~');
            val=1;
            input.val(val);
            return false;
          }
          val++;
          if (val>stock) {buyok = 2;$('.p2 .red').text('(库存不足)');input.val(parseInt(stock)+1);return false;}
					if (val>num) {buyok = 2;$('.p3 .red').text('(积分不足)');input.val(num+1);return false;}
        } else {
          val--;
          if (val<1) {input.val(1);return false;}
        }
        $('.p2 .red').text('');
        $('.p3 .red').text('');
        if($goodstype>1){
          layer.msg('虚拟商品一次只能兑换一个~');
          val=1;
        }
        input.val(val);
        return false;
      });
      
      $('.jsexchange').click(function() {
        var exchange = $(this);
        if (exchange.hasClass('disabled')) {return false;}
        exchange.addClass('disabled');
				
				$('body,html').css({'overflow':'hidden','height':'100vh'});
				
        if ($user<=0) {
          // 未登录
          popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法兑换商品</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologinsss">去登录</a></div>');
          $('.jscancel').click(function() {popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});exchange.removeClass('disabled');});
          popmsg.find('.tologinsss').click(function() {popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);});
        } else if (buyok==2) {
          // 积分不足
          var stock = parseInt($.trim($('#stock').val()));
          var integral = $.trim($('#integral').val());
          var price = $.trim($('#price').val());
          var val=$('#number').val();
          var num = parseInt(integral/price);
          if (val>num)           
            popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">积分不足</div><div class="pop-text">当前账户可用积分 '+integral+'，暂时无法兑换</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="<?php echo U("PointShop/mall_integral");?>" class="pop-butn pop-comfirm">获取积分</a></div>');
          if (val>stock){
            popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">库存不足</div><div class="pop-text">当前商品库存是 '+stock+'，暂时无法兑换</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jscancel">知道了</a></div>');
          }
        
          $('.jscancel').click(function() {popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});exchange.removeClass('disabled');});
          
          
        } else if ($address==''&&$goodstype!=2){ 
          // 暂无收货地址
          popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂无收货地址</div><div class="pop-text">缺少收货地址，兑换商品无法顺利送达哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="<?php echo U("Subscriber/user_address_add");?>" class="pop-butn pop-comfirm ">去添加</a></div>');
          $('.jscancel').click(function() {popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});exchange.removeClass('disabled');});
        }else {
          var price = $.trim($('#price').val());
          var val=$('#number').val();
          var openhtml = '';
          good_type = "<?php echo ($data["good_type"]); ?>";
          if(good_type == 1)
          openhtml += '<form action=""><a href="javascript:;" class="pop-close"></a><div class="pop-content"><div class="pop-title">确认兑换</div><div class="pop-table-wrap"><div class="pop-table"><div class="pop-row"><div class="pop-cell">收货地址</div><div class="pop-cell"><a ><p class="paddress"><span>'+$address.consignee+'，'+$address.consignee_phone+'</span><span>'+$address.consignee_address+'</span></p></a>';
          else
            openhtml += '<form action=""><a href="javascript:;" class="pop-close"></a><div class="pop-content"><div class="pop-title">确认兑换</div><div class="pop-table-wrap"><div class="pop-table"><div class="pop-row"><div class="pop-cell">兑换账户</div><div class="pop-cell"><a ><p class="paddress" style="text-align:right;"><span>'+"<?php echo ($data["nickname"]); ?>"+'</span></p></a>';

          openhtml +='</div></div><div class="pop-row"><div class="pop-cell">商品名称</div><div class="pop-cell"><p class="pgood">'+"<?php echo ($data["good_name"]); ?>"+'</p></div></div><div class="pop-row"><div class="pop-cell">兑换数量</div><div class="pop-cell"><p class="pnumber">×'+val+'</p></div></div><div class="pop-row"><div class="pop-cell">所需积分</div><div class="pop-cell"><p class="pintegral">'+val*price+'</p></div></div></div></div></div><div class="pop-butn-box"><input type="submit" class="butn jsbutn" value="立即兑换"></div></form>'
          
          if (!browser.versions.webApp&&(browser.versions.iPhone||browser.versions.iPad) && !browser.versions.qq) {
            openhtml += '<div style="height:3rem;">&nbsp;</div>';
          }
          
          popmsg.addClass('pop-form').open('',openhtml);
          $('.pop-close').click(function() {popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});exchange.removeClass('disabled');});
          
          $('.jsbutn').click(function() {
						
            popmsg.close(1);
            $good_id = "<?php echo ($data["id"]); ?>";
            buy($good_id,val);
            
            return false;
          });
          
        }
        var post_flag = false;
        function buy($good_id,val){
          if(post_flag) return false; 
          $.ajax({
            type:'post',
            url:"<?php echo U('shopBuy');?>",
            async:false,
            data:{good_id:$good_id,num:val},
            success:function(data){
              post_flag = true;
              if(data.status == 1){
                // 兑换成功
                if(data.msg=='xuni'){
                  
									mmm = '<a href="javascript:;" class="pop-close"></a><div class="pop-content"><div class="pop-title">兑换成功！</div><div class="pop-code pop-table"><span class="pop-cell pop-input"><input type="text" readonly class="code pop-txt" value="'+data.data.key[0]+'"></span></div><div class="pop-butn-box"><a href="javascript:;" class="copy pop-btn">复制激活码</a></div></div>';
									
									setTimeout(function(){
                    popmsg.addClass('pop-hint pop-hint2').open('',mmm);
                    popmsg.find('.pop-close').click(function() {popmsg.close();});
										popmsg.find('.copy').click(function() {
											// //移动端复制
											
											$(".copy").text('已复制');
											Copy($('.code').val(),$('.pop-hint .pop-btn'));
										});
                  },10);
                }else{
                  setTimeout(function(){
                    popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">兑换成功</div><div class="pop-text">兑换信息已成功提交，请耐心等待发货哦</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 ">继续兑换</a></div>');
                    $('.pop-comfirm2').click(function() {
                      window.location.href="<?php echo U('PointShop/mall');?>";
                    });
                  },10);
                }
              }else if(data.status == 0){
                setTimeout(function(){
                  popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">提交失败</div><div class="pop-text">可能是网络原因，请重新提交</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel" id="yscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm jsfresh" id="ysfresh">重试</a></div>');
                  document.getElementById('ysfresh').onclick = function(){
                    popmsg.close(1);
                    post_flag = false;
                    buy($good_id,val);
                  };
                  document.getElementById('yscancel').onclick = function(){
                    window.location.reload();
                  };
                },10);
              }else if(data.status == -1){
                // 未登录
                setTimeout(function(){
                  popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">暂未登录</div><div class="pop-text">您还未登录账号，暂时无法兑换商品</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm tologinsss">去登录</a></div>');
                  $('.jscancel').click(function() {popmsg.close();$('body,html').css({'overflow-y':'auto','height':'auto'});exchange.removeClass('disabled');});
                  popmsg.find('.tologinsss').click(function() {popmsg.close(1);setTimeout(function(){$('.jslogin').click();},10);});
                },10);
              }else if(data.status == -3){
                setTimeout(function(){
                  popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">积分不足</div><div class="pop-text">账户积分不足，暂时无法兑换</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn pop-default jscancel" id="yscancel">取消</a> <a href="javascript:;" class="pop-butn pop-comfirm jsfresh" id="ysfresh">获取积分</a></div>');
                  document.getElementById('ysfresh').onclick = function(){
                    popmsg.close(1);
                    post_flag = false;
                    buy($good_id,val);
                  };
                  document.getElementById('yscancel').onclick = function(){
                    popmsg.close(1);$('body,html').css({'overflow-y':'auto','height':'auto'});
                    exchange.removeClass('disabled');
                  };
                },10);
              }else if(data.status == -4){
                setTimeout(function(){
                  popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">库存不足</div><div class="pop-text">可减少兑换数量或选择其他商品</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jsfresh" id="ysfresh">好的</a></div>');
                  document.getElementById('ysfresh').onclick = function(){
                    popmsg.close(1);$('body,html').css({'overflow-y':'auto','height':'auto'});
                    exchange.removeClass('disabled');
                  };
                },10);
              }else{
                // 兑换失败
                setTimeout(function(){
                  popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">提交失败</div><div class="pop-text">服务器错误，请稍后重试</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jsfresh">好的</a></div>');
                },10);
                  $("body").on("click",'.pop-prompt .jsfresh',function(){
                    window.location.reload();
                  });
              }
            },error:function(){
              // 兑换失败
              setTimeout(function(){
                popmsg.addClass('pop-prompt').open('','<div class="pop-content"><div class="pop-title">提交失败</div><div class="pop-text">服务器错误，请稍后重试</div></div><div class="pop-butn-box"><a href="javascript:;" class="pop-butn2 pop-comfirm2 jsfresh">好的</a></div>');
              },10);
                $("body").on("click",'.pop-prompt .jsfresh',function(){
                  window.location.reload();
                });
            }
          })
        }
        
        
        
        return false;
      });
      
    });
</script>
	

    <footer class="footer">
      <section class="wrap">
        <div class="nav table">
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Index"): ?>active<?php endif; ?>" href="<?php echo U('Index/index');?>"><i class="icon icon-home"></i><span>首页</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Game"): ?>active<?php endif; ?>" href="<?php echo U('Game/index#categroy');?>"><i class="icon icon-game"></i><span>游戏</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Gift"): ?>active<?php endif; ?>" href="<?php echo U('Gift/index#all');?>"><i class="icon icon-gift"></i><span>礼包</span></a>
          <a class="jsshopmall item table-cell <?php if(CONTROLLER_NAME == "PointShop"): ?>active<?php endif; ?>" href="<?php echo U('PointShop/mall');?>"><i class="icon icon-mall"><?php if($mallissignin == 1): ?><i class="circle"></i><?php endif; ?></i><span>商城</span></a>
          <a class="item table-cell <?php if(CONTROLLER_NAME == "Subscriber"): ?>active<?php endif; ?>" href="<?php echo U('Subscriber/user');?>"><i class="icon icon-user"><?php if($newmsg == 1): ?><i class="circle"></i><?php endif; ?></i><span>我的</span></a>
        </div>
      </section>
    </footer>
    <?php echo ($logdiv); ?>
    <div class="loginbutdiv hidden">
        <?php if($wx_login == 1 and is_weixin()): ?><a href="javascript:;" onclick="register('weixin')" class="butn"><img src="/Public/Mobile/images/login_third_weixin.png"></a><?php endif; ?>
        <?php if($qq_login == 1): ?><a href="javascript:;" onclick="register('qq')" class="butn"><img src="/Public/Mobile/images/login_third_qq.png"></a><?php endif; ?>
        <?php if($wb_login == 1): ?><a href="javascript:;" onclick="register('weibo')" class="butn"><img src="/Public/Mobile/images/login_third_sina.png"></a><?php endif; ?>
        <?php if($bd_login == 1): ?><a href="javascript:;" onclick="register('baidu')" class="butn"><img src="/Public/Mobile/images/icon_baidu@3x.png"></a><?php endif; ?>
    </div>
    <a style="display:none" href="#shell/WXThirdLogin" id="weixinThirdLogin"></a>
    <a style="display:none" href="#shell/QQThirdLogin" id="qqThirdLogin"></a>
    <form id="app_third_login" action="<?php echo U('Subscriber/app_third_login');?>" method="post" enctype="multipart/form-data" style="display:none">
      <input type="text" name="openID" id="openID"/></p>
      <input type="text" name="nickName" id="nickName"  />
      <input type="text" name="icon" id="icon"/>
      <input type="text" name="logintype" id="logintype"/>
      <input type="text" name="gid" value="<?php echo I('game_id');?>"/>
      <input type="text" name="pid" value="<?php echo (session('union_host')['union_id'])?(session('union_host')['union_id']):$promote_id;?>"/>
      <input type="submit" value="提交" />
    </form>
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
    <script type="text/javascript">
      url = "<?php echo ($_SERVER['HTTP_HOST']); ?>"+"<?php echo U('Subscriber/serverNotice');?>";
      // WebSocketTest(url);
      function WebSocketTest(url){
        if ("WebSocket" in window)
        {
           // 打开一个 web socket
           var ws = new WebSocket("ws://"+url);
           ws.onopen = function()
           {
              // Web Socket 已连接上，使用 send() 方法发送数据
              ws.send("发送数据");
              alert("数据发送中...");
           };
           ws.onmessage = function (evt) 
           { 
              var received_msg = evt.data;
              alert("数据已接收...");
           };
           ws.onclose = function()
           { 
              // 关闭 websocket
              alert("连接已关闭..."); 
           };
        }
        else
        {
           // 浏览器不支持 WebSocket
           alert("您的浏览器不支持 WebSocket!");
        }
      }
    </script>
  </body>
</html>