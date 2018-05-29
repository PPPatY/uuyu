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

class UserLoginRecordModel extends Model {

    protected $_validate = array(
        
    );

    /* 自动完成规则 */
    protected $_auto = array(
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
     * 玩家
     * @param string $map
     * @param $develop_id
     * @return mixed
     * author: cy 707670631@qq.com
     */
    public function getPlayers($map=""){
        $data = $this->alias("r")->field("r.user_id,r.promote_id,r.game_id,r.login_time")
            ->join("left join tab_game g on g.id = r.game_id")
            ->group("user_id")
            ->where($map)
            ->select();
        return $data;
    }
    /**
     * 查找玩家
     * @param string $map
     * @param $develop_id
     * @return mixed
     * author: cy 707670631@qq.com
     */
    public function findPlayer($map=""){
        $data = $this->alias("r")->field("r.user_id,r.promote_id,r.game_id,r.login_time")
            ->join("left join tab_game g on g.id = r.game_id")
            ->group("user_id")
            ->where($map)
            ->find();
        return $data;
    }
}
