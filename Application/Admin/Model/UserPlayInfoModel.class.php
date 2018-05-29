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

class UserPlayInfoModel extends Model {

    protected $_validate = array(
        
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('server_id', 0, self::MODEL_INSERT),
        array('server_name', 0, self::MODEL_INSERT),
        array('role_id', 0, self::MODEL_INSERT),
        array('role_name', "", self::MODEL_INSERT),
        array('role_level', 0, self::MODEL_INSERT),
        array('play_time', 'time', self::MODEL_INSERT,'function'),
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
    public function role_info($where,$order='r.id desc',$p,$row){
        $data['data'] = $this->alias('r')
                ->field('r.*')
                ->join('tab_user_play p on r.user_play_id = p.id')
                ->where($where)
                ->order($order)
                ->group('p.id')
                ->page($p,$row)
                ->select();
        $data['count'] = count($this->alias('r')
            ->field('r.id')
            ->join('tab_user_play p on r.user_play_id = p.id')
            ->where($where)
            ->order($order)
            ->group('p.id')
            ->select());
        return $data;
    }
}
