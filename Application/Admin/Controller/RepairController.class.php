<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
use Common\Api\GameApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class RepairController extends ThinkController {
    public function repairEdit($orderNo=null){
        $param['pay_order_number'] = $orderNo;
        $game = new GameApi();
        $result=$game->game_pay_notify($param,1);
        $res=json_decode($result,true);
        $spend = M('Spend',"tab_");
        if($res['status']=='success'||$res['msg']=='success'||$res['code']=='1009'){
            $rr = $spend->where(array('pay_order_number'=>$orderNo))->save(array('pay_game_status'=>1));
            $this->ajaxReturn(array('status'=>1,'msg'=>'补单成功'));
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'补单失败'));
        }
    }
    /**
    *编辑绑币补单
    */
    public function repairBindEdit($orderNo=null){
        $bindspend = M('BindSpend',"tab_");
        $param['pay_order_number'] = $orderNo;
        $param['distinction'] = 'bind';
        $game = new GameApi();
        $result=$game->game_pay_notify($param,2);
        
        $res=json_decode($result,true);
        if($res['status']=='success'||$res['msg']=='success'||$res['code']=='1009'){
            $rr = $bindspend->where(array('pay_order_number'=>$orderNo))->save(array('pay_game_status'=>1));
            $this->ajaxReturn(array('status'=>1,'msg'=>'补单成功'));
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'补单失败'));
        }
    }

    // /**
    //  * 退款接口
    //  * @param null $orderNo
    //  */
    // public function  Refund($orderNo=null){
    //     $map['pay_order_number'] = $orderNo;
    //     $order=sp_random_string(4);
    //     $res=D('Spend')->Refund($map,$order,md5("mcaseqwezdsi".$order));
    //     if(get_spend_pay_way($orderNo)==1){
    //         if($res=="10000")$this->ajaxReturn(['status'=>1,'msg'=>'退款成功']);
    //         if(res&&strlen($res)>50){
    //             $this->ajaxReturn(['status'=>1,'msg'=>$res]);
    //         }else{
    //             $this->ajaxReturn(['status'=>1,'msg'=>'错误']);
    //         }
    //     }elseif(get_spend_pay_way($orderNo)==2){
    //         if($res==1){
    //             $this->ajaxReturn(['status'=>1,'msg'=>'退款申请成功']);
    //         }else{
    //             $this->ajaxReturn(['status'=>0,'msg'=>$res]);
    //         }
    //     }elseif(get_spend_pay_way($orderNo)==4){
    //         if($res==0){
    //             $this->ajaxReturn(['status'=>1,'msg'=>'退款申请成功']);
    //         }else{
    //             $this->ajaxReturn(['status'=>0,'msg'=>$res]);
    //         }
    //     }elseif(get_spend_pay_way($orderNo)==0){
    //         $this->ajaxReturn(['status'=>1,'msg'=>'退款成功']);
    //     }

    // }


    /**
     * 退款查询借口
     * @param  [type]  $orderNo [订单号]
     * @param  integer $type    [1 威富通 2官方]
     * @return [type]           [description]
     */
    public function find_refund($orderNo=null,$type=1){
        if($type==1){
             $spen=D('Spend')->swiftpass_refund($orderNo);
        }else{
             $spen=D('Spend')->weixin_refundquery($orderNo);
        }
        $res=json_decode($spen,true);
        if($res['status']==1){
             $this->ajaxReturn(['status'=>1,'msg'=>'退款成功']);
        }else{
             $this->ajaxReturn(['status'=>1,'msg'=>$res['msg']]);
        }

    }
}
