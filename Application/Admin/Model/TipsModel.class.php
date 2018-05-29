<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 15:41
 */

namespace Admin\Model;

class TipsModel extends TabModel {
	/* 自动验证规则 */
    protected $_validate = array(
        array('start_time',  'require', '开始日期必须填写',         2,  'regex',  self::MODEL_BOTH),
        array('end_time',  'require', '截止日期必须填写',         1,  'regex',  self::MODEL_BOTH),
        array('tip',  'require', '提示内容不能为空',         1,  'regex',  self::MODEL_BOTH),
    );
	/* 自动完成规则 */
    protected $_auto = array(
        array('update_time',       'getCreateTime',         3,  'callback'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /**
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }
}