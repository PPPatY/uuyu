<?php
namespace Org\HeepaySDK;
use Think\Exception;

class Heepay  {

  public function heepay($pay){
      //获取用户IP
      $user_ip = "";
      if(isset($_SERVER['HTTP_CLIENT_IP']))
      {
          $user_ip = $_SERVER['HTTP_CLIENT_IP'];
      }
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      {
          $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }else
      {
          $user_ip = $_SERVER['REMOTE_ADDR'];
      }

      $agent_bill_time = date('YmdHis', time());
//      $is_phone = $is_phone; //=1时，支付宝wap支付，不传为扫码支付。不参与签名
      $notify_url ='http://'.$_SERVER['HTTP_HOST'] ."/notify.php/heepay_notify/method/notify";//回调
      $return_url = 'http://'.$_SERVER['HTTP_HOST']; //支付完成后跳转的地址 微信支付不涉及同步返回，此处可填写任意URL，没有实际使用
      //$wxpay_type = $_POST['wxpay_type'];


      /*************创建签名***************/
      $sign_str = '';
      $sign_str  = $sign_str . 'version=1';
      $sign_str  = $sign_str . '&agent_id=' . C('heepay.partner');
      $sign_str  = $sign_str . '&agent_bill_id=' . $pay['order'];
      $sign_str  = $sign_str . '&agent_bill_time=' . $agent_bill_time;
      $sign_str  = $sign_str . '&pay_type=' . $pay['pay_type'];
      $sign_str  = $sign_str . '&pay_amt=' . $pay['pay_amount'];
      $sign_str  = $sign_str .  '&notify_url=' . $notify_url;
      $sign_str  = $sign_str . '&return_url=' . $return_url;
      $sign_str  = $sign_str . '&user_ip=' . $user_ip;
      $sign_str = $sign_str . '&key=' . C('heepay.key');
      $sign = md5($sign_str); //签名值
      $params=[
          'version'=>1,
          'agent_id'=> C('heepay.partner'),//商户编号
          'agent_bill_id'=>$pay['order'],//订单号
          'agent_bill_time'=>$agent_bill_time,
          'pay_type'=>$pay['pay_type'],//支付宝支付22  威信33
          'pay_code'=>"",
          'pay_amt'=>$pay['pay_amount'],//价格 单位元
          'notify_url'=>$notify_url,//回调
          'return_url'=>$return_url,//支付完成后跳转的地址微信支付不涉及同步返回，此处可填写任意URL，没有实际使用
          'user_ip'=>$user_ip,
          'goods_name'=>urlencode("道具"),//商品名称,
          'goods_num'=>urlencode('1'),//商品数量
          'goods_note'=>urlencode("支付"),//支付说明,
          'remark'=>urlencode("支付"),//商户自定义
          'sign'=>$sign
      ];
      if (is_mobile_request()) {//WAP支付
          $params['is_phone'] = 1;
      }
//      if(is_mobile_request())
//      {
//          $is_phone = 1;
//          $is_frame = 0;
//      }
//      else if($wxpay_type == 2) //公众号支付
//      {
//          $is_phone = 1;
//          $is_frame = 1;
//      }
      $sHtml = "<form id='frmSubmit' name='frmSubmit' action='https://pay.Heepay.com/Payment/Index.aspx' method='post'>";
      foreach ($params as $k => $v) {
          $sHtml.= "<input type='hidden' name='{$k}' value='{$v}' />";
      }
      $sHtml = $sHtml."</form>Loading......";
      $sHtml = $sHtml."<script>document.forms['frmSubmit'].submit();</script>";
      file_put_contents(dirname(__FILE__).'/czxc.txt',json_encode($sHtml));
      return $sHtml;


	}

}