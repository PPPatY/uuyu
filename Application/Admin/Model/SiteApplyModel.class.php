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
 * 分类模型
 */
class SiteApplyModel extends Model{

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

    protected $_validate = array(
        array('site_url', 'require', '站点域名不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url_type', 'require', '站点来源不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('site_url', 'url', '站点域名不正确', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('promote_id', PID, self::MODEL_BOTH),
    );

    /**
     * 获取站点信息
     * @param int $promote_id
     * @return mixed
     */
    public function get_promote_data($promote_id=PID){
        $map['promote_id'] = $promote_id;
        $data = $this->where($map)->find();
        return $data;
    }

}
