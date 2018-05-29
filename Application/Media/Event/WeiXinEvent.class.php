<?php

namespace Media\Event;

use Org\WeiXinSDK\WeiXinOauth;

use Think\Controller;

class WeixinEvent extends Controller{



	public function wxpayjssdk($data){

		$jsapipay =  WeiXinOauth::getInstance("JsApiPay");

		$wxpayapi =  WeiXinOauth::getInstance("WxPayApi");

		$wxpayunifiedorder = WeiXinOauth::getInstance("WxPayUnifiedOrder");

        //①、获取用户openid

        //$tools = new JsApiPayController();

        $openId = $jsapipay->GetOpenid();

        //②、统一下单

        $input = $wxpayunifiedorder;

        $input->SetBody($data["body"]);

        $input->SetAttach("test");

        $input->SetOut_trade_no($data["pay_order_num"]);

        $input->SetTotal_fee($data['amount'] * 100);

        $input->SetTime_start(date("YmdHis"));

        $input->SetTime_expire(date("YmdHis", time() + 600));

        $input->SetGoods_tag("test");

        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");

        $input->SetTrade_type("JSAPI");

        $input->SetOpenid($openId);

        $order = $wxpayapi::unifiedOrder($input);



        $jsApiParameters = $jsapipay::GetJsApiParameters($order);

        //获取共享收货地址js函数参数

        $editAddress = $jsapipay->GetEditAddressParameters();

        

        $this->assign("jsApiParameters",$jsApiParameters);

    }

}