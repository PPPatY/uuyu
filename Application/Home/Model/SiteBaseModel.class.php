<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * 分类模型
 */
class SiteBaseModel extends SiteModel{

    protected $_validate = array(
        array('site_name', 'require', '站点名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('site_background', 'require', '站点背景图不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('site_logo', 'require', '站点LOGO不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_BOTH),
        array('promote_id', PID, self::MODEL_BOTH),
    );

    /**
     * 获取站点信息
     * @param int $promote_id
     * @return mixed
     */
    public function get_promote_data($promote_id=PID){
        $map['promote_id'] = $promote_id;
        $map['status'] = 1;
        $data = $this->where($map)->find();
        return $data;
    }

}
