<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class UserPlayModel extends Model {

    protected $_validate = array(
        
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('server_id', 0, self::MODEL_INSERT),
        array('server_name', 0, self::MODEL_INSERT),
        array('role_id', 0, self::MODEL_INSERT),
        array('role_name', "", self::MODEL_INSERT),
        array('role_level', 0, self::MODEL_INSERT),
        array('bind_balance', 0, self::MODEL_INSERT),
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
    public function edit_user_balance_coin($account,$game_id,$num,$type,$sid=0){
        //开启事务
        $this->startTrans();
        $map['user_account'] = $account;
        $map['game_id'] = $game_id;
        $data = $this->where($map)->find();
        //type  增加或减少
        if($type == 1){
            return false;
        }
        if($type == 2){
            $data['bind_balance'] -= (int)$num;
            if($data['bind_balance'] < 0){
                $this->error = "该用户绑定平台币小于所要扣除的绑定平台币！";
                $this->rollback();
                return false;
            }
            $res = $this->where($map)->save($data);
        }
        $rec = D('UserCoin')->record($account,$sid,$num,$type,1,$game_id);
        if($res && $rec){
            //事务提交
            $this->commit();
            return true;
        }else{
            //事务回滚
            $this->rollback();
            return false;
        }
    }
}
