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
class PromoteWelfareModel extends Model{

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

    //获取列表
    public function get_lists($where){
        return $this->where($where)->select();
    }

    //获取折扣
    public function get_discount($game_id){
        $map['game_id'] = $game_id;
        $map['promote_id'] = PID;
        $data = $this->where($map)->find();
        if (empty($data)){
            $game = M('game','tab_')->find($game_id);
            return $game['discount'];
        }else{
            $discount = discount_data($data);
            return $discount['promote_discount'];
        }
    }
}