<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Org\WeiXinSDK\Weixin;
use Think\Model;

/**
 * 文档基础模型
 */
class SpendModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array();

    /* 自动完成规则 */
    protected $_auto = array(
        array('pay_time', 'getCreateTime', self::MODEL_INSERT,'callback'),
        array('pay_status',  0, self::MODEL_INSERT),
        array('order_number','',self::MODEL_INSERT),
    );

    /**
     * 构造函数
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        /* 设置默认的表前缀 */
        $this->tablePrefix ='tab_';
        /* 执行构造方法 */
        parent::__construct($name, $tablePrefix, $connection);
    }
    

    /**
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }

    public function amdin_account()
    {
       return session('user_auth.username');
    }




    
    /**
     * 退款接口
     * @param $map
     */
    public function Refund($map,$order,$sign)
    {
        if(md5("mcaseqwezdsi".$order)!==$sign){
                return false;
        }
        $RefundRecord = M('RefundRecord', 'tab_')->where($map)->find();
        if (null == $RefundRecord) {
            $find = $this->where($map)->find();
            $order_number = $find['pay_way'] == 1 ? date("YmdHis") : "TK_" . date('Ymd') . date('His') . sp_random_string(4);
            $BatchNo=date("YmdHis");
        } else {
            $order_number = $RefundRecord['order_number'];
            $BatchNo=$RefundRecord['batch_no'];
            $find = $RefundRecord;
        }

        if ($find['pay_way'] == 1) {
            //页面上通过表单选择在线支付类型，支付宝为alipay 财付通为tenpay
            $pay = new \Think\Pay('alipay', C('alipay'));
            $vo = new \Think\Pay\PayVo();
            $detail_data = $find['order_number'] . "^" . $find['pay_amount'] . "^掉单";

            $find['batch_no']=$BatchNo;
            $vo->setOrderNo($find['order_number'])
                ->setService("refund_fastpay_by_platform_pwd")
                ->setSignType("MD5")
                ->setPayMethod("refund")
                ->setTable("RefundRecord")
                ->setBatchNo($BatchNo)
                ->setDetailData($detail_data);
            $this->add_refund_record($find, $find['order_number']);
            $this->where($map)->delete();
            return $pay->buildRequestForm($vo);
        } elseif ($find['pay_way'] == 2) {
            $weixn = new Weixin();
            $res = json_decode($weixn->weixin_Refund_pub($find['pay_order_number'], $order_number, $find['pay_amount'], $find['pay_amount'], C('wei_xin.partner')), true);
            $this->add_refund_record($find, $order_number);
            $this->where($map)->delete();
            if ($res['status'] == 1) {
                return $res['status'];
            } else {
                return $res;
            }

        } elseif ($find['pay_way'] == 4) {
            $config = array("partner" => trim(C("weixin.partner")), "email" => "", "key" => trim(C("weixin.key")));
            $pay = new \Think\Pay('swiftpass', $config);
            $vo = new \Think\Pay\PayVo();
             $vo->setService('unified.trade.refund')
                ->setSignType("MD5")
                ->setPayMethod("refund")
                ->setTable("RefundRecord")
                ->setOrderNo($find['pay_order_number'])
                ->setBatchNo($order_number)
                ->setFee($find['pay_amount']);
            $this->add_refund_record($find, $order_number);
             $this->where($map)->delete();
            $res=$pay->buildRequestForm($vo);
            if ($res['status'] == 0) {
                return $res['status'];
            } else {
                return false;
            }
        } elseif ($find['pay_way'] == 0) {
            $user_map['id'] = $find['user_id'];
            M('user', 'tab_')->where($user_map)->setInc('balance', $find['pay_amount']);
             $this->add_refund_record($find, $order_number);
             $this->where($map)->delete();
            return true;
        }

    }

    /**
     * 添加退款记录
     * @param $data
     * @return mixed
     */
    public function add_refund_record($data, $order_number)
    {
        $RefundRecord = M('RefundRecord', 'tab_');
        unset($data['id']);
        $map['pay_order_number'] = $data['pay_order_number'];
        $find = $RefundRecord->where($map)->find();
        if (null !== $find) {
            if($data['pay_way']==4||$data['pay_way']==2){
                $RefundRecord->where($map)->delete();
                $data['tui_status'] = 2;
                $data['create_time'] = time();
                $data['tui_amount'] = $data['pay_amount'];
                $data['order_number'] = $order_number;
                return $RefundRecord->add($data);
            }else{
                return true;
            }
        } else {
            if ($data['pay_way'] == 0) {
                $data['tui_status'] = 1;
                $data['tui_time'] = time();
                $savv['sub_status']=1;
                $savv['settle_check']=1;
                $this->where($map)->save($savv);
            }elseif($data['pay_way'] == 4||$data['pay_way']==3){
                $data['tui_status'] = 2;
            }

            $data['create_time'] = time();
            $data['tui_amount'] = $data['pay_amount'];
            $data['order_number'] = $order_number;
            return $RefundRecord->add($data);

        }
    }




    /**
     * 微信退款查询接口
     * @param  [type] $orderNo [description]
     * @return [type]          [description]
     */
    public function weixin_refundquery($orderNo){
          $weixn = new Weixin();
          $res = $weixn->weixin_refundquery($orderNo);

           if($res=="SUCCESS"){
            M('RefundRecord', 'tab_')->where(array('pay_order_number'=>$orderNo))->setField('tui_status', 1);
                return json_encode(array('status'=>1,'msg'=>'退款成功'));
            }elseif($res=="FAIL"){
                return json_encode(array('status'=>0,'msg'=>'退款失败'));
            }elseif($res=="PROCESSING"){
                return json_encode(array('status'=>0,'msg'=>'退款处理中'));
            }
    }



    /**
     * 威富通查询退款接口
     * @param  [type] $map [description]
     * @return [type]      [description]
     */
    public function swiftpass_refund($orderNo){
        $config = array("partner" => trim(C("weixin.partner")), "email" => "", "key" => trim(C("weixin.key")));
        $pay = new \Think\Pay('swiftpass', $config);
        $vo = new \Think\Pay\PayVo();
        $vo->setOrderNo($orderNo)
            ->setService('unified.trade.refundquery')
            ->setSignType("MD5")
            ->setPayMethod("find")
            ->setTable("RefundRecord");
        $res=$pay->buildRequestForm($vo);
        if($res['refund_status']=="SUCCESS"){
            M('RefundRecord', 'tab_')->where(array('pay_order_number'=>$orderNo))->setField('tui_status', 1);
            return json_encode(array('status'=>1,'msg'=>'退款成功'));
        }elseif($res['refund_status']=="FAIL"){
            return json_encode(array('status'=>0,'msg'=>'退款失败'));
        }elseif($res['refund_status']=="PROCESSING"){
            return json_encode(array('status'=>0,'msg'=>'退款处理中'));
        }
    }

    /**
     * 累计付费
     * @param string $map
     * @return mixed
     * author: xmy 280564871@qq.com
     */
    public function totalSpend($map=""){
        $map['s.pay_status'] = 1;
        $data = $this->alias("s")->field("IFNULL(sum(pay_amount),0) as num")
            ->join("right join tab_game g on g.id = s.game_id")
            ->where($map)
            ->find();
        return $data['num'];
    }
    public function totalSpendTimes($map=""){
        $map['s.pay_status'] = 1;
        $data = $this->alias("s")->field("IFNULL(count(s.id),0) as count")
            ->join("left join tab_game g on g.id = s.game_id")
            ->where($map)
            ->find();
        return $data['count'];
    }

}