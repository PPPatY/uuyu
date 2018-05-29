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
class PromoteWelfareModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('game_id', 'check_game', '该推广员游戏已被添加', self::MUST_VALIDATE, 'callback'),
        array('promote_discount', '/^([1-9](\.[0-9]{1,2})?|10?|10.00)$/', '推广员折扣区间为1-10', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('first_discount', [0,10], '首冲折扣区间为1-10', self::EXISTS_VALIDATE, 'between', self::MODEL_BOTH),
        array('continue_discount', [0,10], '续冲折扣区间为1-10', self::EXISTS_VALIDATE, 'between', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', 'getCreateTime', self::MODEL_INSERT,'callback'),
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
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }

    /**
     * 检查渠道游戏是否唯一
     * @return bool
     */
    public function check_game($game_id){
        $map['game_id'] = $game_id;
        $map['promote_id'] = I('promote_id');
        $data = $this->where($map)->find();
        if(!empty($data)){
            return false;
        }
        return true;
    }

}