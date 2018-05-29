<?php
// +----------------------------------------------------------------------
// | 徐州梦创信息科技有限公司—专业的游戏运营，推广解决方案.
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.vlcms.com  All rights reserved.
// +----------------------------------------------------------------------
// | Author: kefu@vlcms.com QQ：97471547
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class PromoteModel extends Model{

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
    *  检测
    */
    public function isLogin() {
        $user = session('promote_auth');
        if (!empty($user)) {
            return $user;
        }
        return null;
    }
        /**
    * 更新时间
    */
    protected function getTime() {
        return date("Y-m-d H:i:s",time());
    }


    /**
     * 转移平台币
     * @param $uid 渠道ID
     * @param $sid 子渠道ID
     * @param $num  数量
     */
    public function shift_coin($uid,$sid,$num){
        //扣除渠道平台币
        $e_res2 = $this->edit_promote_balance_coin($uid,$num,2,$sid);
        //增加子渠道平台币
        $e_res1 = $this->edit_promote_balance_coin($sid,$num,1,$uid);
        if($e_res2 && $e_res1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 渠道平台币修改
     * @param $promote_account 渠道帐号
     * @param $num  平台币数量
     * @param $type 1：增加 2：收回
     */
    public function edit_promote_balance_coin($promote_id,$num,$type,$sid=0){
        //开启事务
        $this->startTrans();
        $map['id'] = $promote_id;
        $data = $this->where($map)->find();
        if($type == 1){
            $data['balance_coin'] += (int)$num;
            $res = $this->where($map)->save($data);
        }
        if($type == 2){
            $data['balance_coin'] -= (int)$num;
            if($data['balance_coin'] < 0){
                $data['balance_coin'] = 0;
            }
            $res = $this->where($map)->save($data);
        }
        $rec = D('PromoteCoin')->record($promote_id,$sid,$num,$type);
        if($res && $rec){
            //事务提交
            $this->commit();
        }else{
            //事务回滚
            $this->rollback();
        }
        return $res;
    }
}