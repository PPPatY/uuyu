<?php
namespace Org\WeiXinpaySDK;
use Think\Exception;

class Weixin  {

  public function weixin_pay($title,$order_no,$pay_amount,$trade_type="NATIVE",$type=1){
        //官方
        header("Content-type:text/html;charset=utf-8");
        Vendor("WxPayPubHelper.WxPayPubHelper");
        // $data['pay_type']  = "weixin";
        //使用统一支付接口
        if($type==1){ //扫码
            $Notify_url="http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/wxpay_callback/method/notify";
            $unifiedOrder = new \UnifiedOrder_pub(C('wei_xin.email'),C('wei_xin.partner'),C('wei_xin.key'));
        }elseif($type==2){        //app 公众号 sdk用
            $Notify_url="http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/wxpay_callback/method/notify2";
            $unifiedOrder = new \UnifiedOrder_pub(C('wei_xin_app.email'),C('wei_xin_app.partner'),C('wei_xin_app.key'));    
        }elseif($type==3){//app用
            $Notify_url="http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/wxpay_callback/method/notify3";
            $unifiedOrder = new \UnifiedOrder_pub(C('wei_xin_apps.email'),C('wei_xin_apps.partner'),C('wei_xin_apps.key'));    
        }
        // $des='平台币充值';
        $unifiedOrder->setParameter("body",$title);//商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $unifiedOrder->setParameter("out_trade_no",$order_no);//商户订单号 
        $unifiedOrder->setParameter("total_fee",$pay_amount*100);//总金额
        $unifiedOrder->setParameter("notify_url",$Notify_url);//通知地址 
        $unifiedOrder->setParameter("trade_type",$trade_type);//交易类型
        $unifiedOrder->setParameter("product_id",$order_no);//商品ID
        //获取统一支付接口结果
        $unifiedOrderResult = $unifiedOrder->getResult();
        //商户根据实际情况设置相应的处理流程
        if ($unifiedOrderResult["return_code"] == "FAIL") 
        {
            //商户自行增加处理流程
            echo base64_encode(json_encode(array('status'=>0,'return_msg'=>$unifiedOrderResult['return_msg'])));
        }
        elseif($unifiedOrderResult["result_code"] == "FAIL")
        {
            //商户自行增加处理流程
            // echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
            echo base64_encode(json_encode(array('status'=>0,'return_msg'=>$unifiedOrderResult['err_code_des'])));

        }
        elseif($unifiedOrderResult["code_url"] != NULL)
        {

            //从统一支付接口获取到code_url
            $code_url = $unifiedOrderResult["code_url"];
            //商户自行增加处理流程
            if($unifiedOrderResult['return_code'] !=="SUCCESS"){
            \Think\Log::record($unifiedOrderResult['msg']);
            $html ='<div class="d_body" style="height:px;">
                    <div class="d_content">
                        <div class="text_center">'.$unifiedOrderResult["return_code"].'</div>
                    </div>
                    </div>';
        }else{
                return json_encode(array("status"=>1,'url'=>$unifiedOrderResult['code_url']));   
        }

      }else{
        if($trade_type=="APP"){
             $app_data['appid']=$unifiedOrderResult['appid'];
            $app_data['partnerid']=$unifiedOrderResult['mch_id'];
            $app_data['prepayid']=$unifiedOrderResult['prepay_id'];
            $app_data['noncestr']=$unifiedOrder->createNoncestr();
            $app_data['timestamp']=time();
            $app_data['package']="Sign=WXPay";
            $sign=$unifiedOrder->getSign($app_data);
            return json_encode(array("status"=>1,'appid'=>$unifiedOrderResult['appid'],'mch_id'=>$unifiedOrderResult['mch_id'],'prepay_id'=>$unifiedOrderResult['prepay_id'],'time'=>$app_data['timestamp'],'noncestr'=>$app_data['noncestr'],'sign'=>$sign));
        }
           
      }

}
  
}