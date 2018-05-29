<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class UserCoinModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('num', [1,999999], '发放数量错误', self::MUST_VALIDATE, 'between', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT,'function'),
        array('op_id',UID,self::MODEL_INSERT),
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
     * 平台币记录
     * @param $promote_id 渠道ID
     * @param $sid  来源ID
     * @param $num  数量
     * @param $type 类型 1：增加 2:减少
     */
    public function record($account,$sid,$num,$type,$isbd=0,$game_id){
        $data['user_id'] = get_user_id($account);
        $data['user_account'] = $account;
        if($isbd){
            $data['coin_type'] = -1;
            $data['game_id'] = $game_id;
        }
        $data['source_id'] = $sid;
        $data['num'] = $num;
        $data['type'] = $type;
        $data = $this->create($data);
        if(!$data){
            return false;
        }
        $res = $this->add($data);
        return $res;
    }

}