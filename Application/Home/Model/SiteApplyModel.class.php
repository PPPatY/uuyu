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
class SiteApplyModel extends SiteModel{

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

    public function SaveData(){
        $data = I('post.');
        if($data['url_type'] == 1){
            $data['site_url'] = "http://".$_SERVER['HTTP_HOST'].'/Site/Index/'.PROMOTE_ACCOUNT;
        }
        $data['status'] = 2;
        return parent::SaveData($data);
    }
}
