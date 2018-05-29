<?php

namespace Callback\Controller;
use Think\Controller;
use Common\Api\GameApi;
use Common\Api\PayApi;
/**
 * 支付回调控制器
 * @author 小纯洁 
 */
class BaseController extends Controller {

    protected function pay_game_notify($data){
        $game = new GameApi();
        $result = $game->game_pay_notify($data);
        return $result;
    }

    /**
    *充值到游戏成功后修改充值状态和设置游戏币
    */
    protected function set_spend($d){
            #通知游戏
            $rr = false;
            for ($i=1; $i <2 ; $i++) { 
                $return=$this->pay_game_notify($d);
                $result=json_decode($return,true);
                if($result['status']=='success'||$result['msg']=='success'||$result['code']=='1009'||$result['code']=='200'){//msg=sucess以及 code=1009 是白鹭返回，其他都是一般接口
                    $rr = M('spend','tab_')->where(array('pay_order_number'=>$d['pay_order_number']))->save(array('pay_game_status'=>1));
                    if($rr!==false){
                        $rr=true;
                        $this->record_logs($d['pay_order_number'].'共通知cp'. $i.'次，已成功','INFO');
                        break;
                    }else{
                        $this->record_logs($d['pay_order_number'].'共通知cp'. $i.'次，失败','INFO');
                    }
                }else{
                    $this->record_logs($d['pay_order_number'].'共通知cp'. $i.'次，失败','INFO');
                }
            }
            return $rr;
    }

  


    /**
    *修改订单
    */
    protected function changepaystatus($data,$d,$table){
        $table=M($table,'tab_');
        if(empty($d)){return false;}
        if($data['real_amount']<$d['pay_amount']){$this->record_logs('订单'.$data['out_trade_no'].'实际支付金额低于订单金额');return false;}
        if($d['pay_status'] == 0){
            $data_save['pay_status'] = 1;
            $data_save['order_number'] = $data['trade_no'];
            $map_s['pay_order_number'] = $data['out_trade_no'];
            $r = $table->where($map_s)->save($data_save);
            $PayApi = new PayApi();
            $PayApi->set_ratio($d['pay_order_number']);
            if($r!==false){
                $spend_data = M('spend','tab_')->where($map_s)->find();
                if(!empty($spend_data)){
                    M('user','tab_')->where(array('id'=>$d['user_id']))->setInc('cumulative',$d['pay_amount']);
                    $PayApi->buyshoppoint($spend_data);
                }
                return true;
            }else{
                $this->record_logs('订单'.$data['out_trade_no']."修改数据失败");
                return false;
            }
        }
        else{
            return true;
        }
    }
    /**
    *充值平台币成功后的设置
    */
    protected function set_deposit($d){
        $user = M("user","tab_");
        $user->where("id=".$d['user_id'])->setInc("balance",$d['pay_amount']);
        $user->where("id=".$d['user_id'])->setInc("cumulative",$d['pay_amount']);
    }

    /**
    *设置代充数据信息
    */
    protected function set_agent($d){
        $user = M("UserPlay","tab_");
        $map_play['user_id'] = $d['user_id'];
        $map_play['game_id'] = $d['game_id'];
        $user->where($map_play)->setInc("bind_balance",$d['amount']);
    }
    

    /**
    *日志记录
    */
    protected function record_logs($msg="",$type='ERR'){
        \Think\Log::record($msg,$type);
    }

}