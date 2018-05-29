<?php
namespace Org\JtpaySDK;

use Think\Exception;

class Jtpay
{

     public function jt_pay($order_no,$amount=0.02,$account="测试",$ip,$paychannelnum="ICBC",$p9_paymethod=1,$returnurl="",$p26_iswappay=1,$p25_terminal=1){
        $p1_usercode = C('jft.partner');//"10200425";                                  //竣付通分配的八位商户号
        $p4_returnurl  = empty($returnurl)?"http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/jft_callback":$returnurl;//成功跳转
        $p5_notifyurl  = "http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/jft_callback";//通知
        $p6_ordertime =date("Ymdhms",time());
        @$p10_paychannelnum=$paychannelnum;//网银支付用
        $params=[
                'p1_usercode'=>C('jft.partner'),
                'p2_order'=>$order_no,
                'p3_money'=>$amount,
                'p4_returnurl'=>$p4_returnurl,
                'p5_notifyurl'=> $p5_notifyurl,
                'p6_ordertime'=>$p6_ordertime,
                'p7_sign'=>strtoupper(md5(C('jft.partner')."&".$order_no."&".$amount."&".$p4_returnurl."&".$p5_notifyurl."&".$p6_ordertime.C('jft.key'))),
                'p9_paymethod'=>$p9_paymethod,
                'p10_paychannelnum'=>$p10_paychannelnum,
                'p14_customname'=>$account,
                'p17_customip'=>$ip,
                'p25_terminal'=>$p25_terminal,
                'p26_iswappay'=>$p26_iswappay,
        
        ];
         $html="<form method='post' action='http://pay.jtpay.com/form/pay' id='payForm'>";
         if(($p9_paymethod==4&&!empty($paychannelnum)) ||($p9_paymethod==3&&!empty($paychannelnum)) ){
            foreach ($params as $k => $v) {
               $d[]=$k."=".$v;
            }
            header("Content-type: text/html; charset=utf-8"); 
            $pay_url="http://pay.jtpay.com/form/pay".'?'.implode("&",$d);
            return  $pay_url;  
         }else{
              foreach ($params as $k => $v) {
                $html.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
                }
                $html.="<script type='text/javascript'>document.getElementById('payForm').submit();</script>";
                return $html;
         }
          


    }


}



