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
class PromoteCoinModel extends Model{

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

    /* 自动验证规则 */
    protected $_validate = array(
        array('num', [1,999999], '发放数量错误', self::MUST_VALIDATE, 'between', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT,'function'),
        array('op_id',UID,self::MODEL_INSERT),
    );


    //获取列表
    public function get_lists($where){
        return $this->where($where)->select();
    }

    /**
     * 平台币记录
     * @param $promote_id 渠道ID
     * @param $sid  来源ID
     * @param $num  数量
     * @param $type 类型 1：增加 2:减少
     */
    public function record($promote_id,$sid,$num,$type){
        $data['promote_id'] = $promote_id;
        $data['source_id'] = $sid;
        $data['num'] = $num;
        $data['type'] = $type;
        $data['promote_type'] = get_promote_level($promote_id);
        $data = $this->create($data);
        if(!$data){
            return $this->getError();
        }
        $res = $this->add($data);
        return $res;
    }

}