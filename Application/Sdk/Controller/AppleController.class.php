<?php
namespace Sdk\Controller;
use Think\Controller;
use Common\Api\Game;
class AppleController extends BaseController{

    /**
    *ios移动支付
    */
    public function apple_product(){
        $apple_product=M('for_apply_pay','tab_')->where(array('status'=>1))->select();
        echo json_encode($apple_product);
    }
    public function applePay(){
        C(api('Config/lists'));
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        #获取订单信息
        $prefix = $request['code'] == 1 ? "SP_" : "PF_";
        $out_trade_no = $prefix.date('Ymd').date('His').sp_random_string(4);
        $data = array("status"=>1,"out_trade_no"=>$out_trade_no);
        $request['pay_order_number'] = $out_trade_no;
        $request['pay_status'] = 0;
        $request['pay_way']    = 3;
        $request['spend_ip']   = get_client_ip();
        if($request['code'] == 1 ){
            #TODO添加消费记录
            $this->add_spend($request);
        }else{
            #TODO添加平台币充值记录
            $this->add_deposit($request);
        }
        echo base64_encode(json_encode($data));
    }

    private function getSignVeryfy($receipt, $isSandbox = false){
        if ($isSandbox) {     
            $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';     
        }     
        else {     
            $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';     
        }     

        $postData = json_encode(
            array('receipt-data' => $receipt["paper"])
        );
      
        $ch = curl_init($endpoint);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_POST, true);     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);     
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  //这两行一定要加，不加会报SSL 错误  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        $response = curl_exec($ch);     
        $errno    = curl_errno($ch);     
        $errmsg   = curl_error($ch);     
        curl_close($ch);     
        //判断时候出错，抛出异常  
        if ($errno != 0) {     
            throw new \Think\Exception($errmsg, $errno);     
        }     
                  
        $data = json_decode($response,true);     
       
        return $data;   
    }

    /**
    *苹果支付验证
    */
    public function appleVerify(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);     
        $isSandbox = true;
        //开始执行验证  
        try  
        {  
            $info = $this->getSignVeryfy($request, $isSandbox);    
            if($info['status'] == 0){
                $out_trade_no = $request['out_trade_no'];
                $pay_where = substr($out_trade_no,0,2);
                $result = 0;
                $map['pay_order_number'] = $out_trade_no;
                $field = array("pay_status"=>1,"pay_amount"=>$request['price'],"order_number"=>$info['receipt']['transaction_id']);
                switch ($pay_where) {
                    case 'SP':
                        $result = M('spend','tab_')->where($map)->setField($field);
                        $param['out_trade_no'] = $out_trade_no;
                        $game = new GameApi();
                        $game->game_pay_notify($param);
                        break;
                    case 'PF':
                        $result = M('deposit','tab_')->where($map)->setField($field);
                        break;
                    case 'AG':
                        $result = M('agent','tab_')->where($map)->setField($field);
                        break;
                    default:
                        exit('accident order data');
                        break;
                }
                if($result){
                    echo base64_encode(json_encode(array("status"=>1,"return_code"=>"success","return_msg"=>"支付成功")));
                    exit();
                }else{
                    echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"支付状态修改失败")));
                    exit();
                }
            }else{
                echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"支付失败")));
                exit();
            }
        }  
        //捕获异常  
        catch(Exception $e)  
        {  
            echo 'Message: ' .$e->getMessage();  
        }  
    }

    
}
